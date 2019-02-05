<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){
	 echo 'SU SESION HA EXPIRADO.'; exit;
 }

 //******************************************* 
$_enviar	 		= 	(isset($_POST['enviar']))			? $_POST['enviar'] 		: NULL;
$_cantparte 		= 	(isset($_POST['cantparte']))		? $_POST['cantparte'] 	: NULL;
$_fecha_plan 		= 	(isset($_POST['fecha_plan']))		? $_POST['fecha_plan'] 	: NULL;
 
$_partecliente		= 	json_decode(str_replace ('\"','"', 	$_POST['partecliente_Obj']), 	true);
$_partenombre		= 	json_decode(str_replace ('\"','"', 	$_POST['partenombre_Obj']), 	true);
$_partedir			= 	json_decode(str_replace ('\"','"', 	$_POST['partedir_Obj']), 		true);

$_partetrabaja		= 	json_decode(str_replace ('\"','"', 	$_POST['partetrabaja_Obj']), 	true);
$_parteobservacion	= 	json_decode(str_replace ('\"','"', 	$_POST['parteobservacion_Obj']),true);
$_parteacciones		=	json_decode(str_replace ('\"','"', 	$_POST['parteacciones_Obj']), 	true);

$_partenro			= 	json_decode(str_replace ('\"','"', 	$_POST['nroparte_Obj']), 	true); 

 //*************************************************
 //Controlo campos
 //*************************************************  
 //controla si el envío del parte ya fue realizado
 $_parte_diario	= DataManager::getDetalleParteDiario($_fecha_plan, $_SESSION["_usrid"]);
 if (count($_parte_diario)){
 	foreach ($_parte_diario as $k => $_partecontrol){															
		$_partecontrol 			= $_parte_diario[$k];
		$_partecontrolactiva	= $_partecontrol["parteactiva"];	
		if($_partecontrolactiva == 0){
			echo "El Parte Diario Ya fue enviado. No puede ser modificado."; exit;
		}
	}
 }
 
 //*************************************************
 //Controlo campos
 //*************************************************
 //controla que no se hayan repetido idclientes.
 for($i = 0; $i < $_cantparte; $i++){
	if($_partecliente[$i] != 0){ //discrimina el cero que serían clientes nuevos
 		for($j = 0; $j < $_cantparte; $j++){		
 			if (($_partecliente[$i] == $_partecliente[$j]) && ($i!=$j)){
				echo "No puede cargarse el mismo cliente más de una vez. Verifique el cliente ".$_partecliente[$i]; exit;
			}			
 		}
 	}
 }
 //*************************************************
 
 //*************************************************
 //Control de campos si quiere enviar el parte
 //*************************************************  
 if ($_enviar == 1){	 
	 if(empty($_cantparte)){echo "No hay partes diarios cargados o no se envió aún la planificación."; exit;}
	 
	 for($i = 0; $i < $_cantparte; $i++){
		 if(empty($_partenombre[$i])){	echo "Debe completar el nómbre de cliente del parte ".$_partenro[$i]; exit;} //($i + 1)
		 if(empty($_partedir[$i])){		echo "Debe completar dirección de cliente del parte ".$_partenro[$i]; exit;}
		 
		 if(empty($_parteacciones) || empty($_parteacciones[$i])){		echo "Debe completar alguna acción del parte ".$_partenro[$i]; exit;
		 } else{
			$acciones = explode(',', $_parteacciones[$i]);	
		 	for($j = 0; $j < count($acciones); $j++){
		 		if($acciones[$j] == 10){ //Si accion No Realizada, aclarar por qué y que solo esté esa acción
					if(count($acciones)>1){
						echo "Verifique el parte ".$_partenro[$i].". Si No realizó acciones, no puede elegir otra acción."; exit;
					}
					if (empty($_parteobservacion[$i])){echo "Indique el motivo de la acción No realizada del parte ".$_partenro[$i]; exit;}
			 	}
		 	}	
		 }
	 }
 }
 //*************************************************  
 
 $date = $_fecha_plan;
 list($dia, $mes, $ano) = explode('-', str_replace('/', '-', $date));	
 $_fecha_plan = $ano."-".$mes."-".$dia;
 
 //*******************************************	
 //BORRO EL REGISTRO EN ESA FECHA Y ESE VENDEDOR
 //*******************************************
 $ID	=	DataManager::deleteFromParte($_SESSION["_usrid"], $_fecha_plan);

 //*******************************************	
 //			GRABO EL PARTE
 //*******************************************
 for($i = 0; $i < $_cantparte; $i++){
	if(empty($_partenombre[$i]) || empty($_partedir[$i])){
		echo "Hubo un error al cargar los datos. Revise y vuelva a intentarlo"; exit;
	}
	$_parteobject	= DataManager::newObjectOfClass('TPartediario');
	$_parteobject->__set('ID',			$_parteobject->__newID());
	$_parteobject->__set('IDVendedor',	$_SESSION["_usrid"]);
	$_parteobject->__set('Fecha',		$_fecha_plan);
	if (empty($_partecliente[$i])){ 	$_parteobject->__set('Cliente',		0);
	} else {							$_parteobject->__set('Cliente',		$_partecliente[$i]);}
	$_parteobject->__set('Nombre',		$_partenombre[$i]); //htmlentities($_partenombre[$i], ENT_QUOTES,'UTF-8'));
	$_parteobject->__set('Direccion',	$_partedir[$i]); //htmlentities($_partedir[$i], ENT_QUOTES,'UTF-8'));
	$_parteobject->__set('Trabajocon',	$_partetrabaja[$i]);
	$_parteobject->__set('Observacion',	$_parteobservacion[$i]); //htmlentities($_parteobservacion[$i], ENT_QUOTES,'UTF-8'));	 
	$acciones = (empty($_parteacciones) || empty($_parteacciones[$i])) ? '0' : $_parteacciones[$i];
	$_parteobject->__set('Acciones',	$acciones);	
	//$_parteobject->__set('EnvioPlanif',	$_parteacciones[$i]);	
	//Al realizar el envío, se crea el objeto para cargarlo como parte ENVIADO (y se graba la fecha de envío) o sin enviar si se hace clic en botón guardar	
	 
	if ($_enviar == 1){		
		$time = time(); 
 		$_parte_fecha_envio	=	date("Y-m-d H:i:s");  
				
 		$_parteobject->__set('Envio'	, $_parte_fecha_envio);
		$_parteobject->__set('Activa'	, '0');
	} else { 				
		$_parteobject->__set('Envio'	, date("2001-01-01 00:00:00"));
		$_parteobject->__set('Activa'	, '1'); }
	
	 
	$ID_parte = DataManager::insertSimpleObject($_parteobject);
	if (empty($ID_parte)) { echo "Error Parte. $ID."; exit; }
 }

 echo $_enviar;
?>