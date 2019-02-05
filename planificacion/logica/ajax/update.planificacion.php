<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){
	echo 'SU SESION HA EXPIRADO.'; exit;
 }

 //******************************************* 
 $_enviar	 		= 	(isset($_POST['enviar']))			? $_POST['enviar'] 		: NULL;
 $_cantplanif 		= 	(isset($_POST['cantplanif']))		? $_POST['cantplanif'] 	: NULL;
 $_fecha_plan 		= 	(isset($_POST['fecha_plan']))		? $_POST['fecha_plan'] 	: NULL;
 $_planifcliente	= 	json_decode(str_replace ('\"','"', $_POST['planifcliente_Obj']), true);
 $_planifnombre		= 	json_decode(str_replace ('\"','"', $_POST['planifnombre_Obj']), true);
 $_planifdir		= 	json_decode(str_replace ('\"','"', $_POST['planifdir_Obj']), 	true);
 //*************************************************
 //Controlo campos
 //*************************************************  
 //controla si el envío de la planif ya fue realizado
 $_planificado	= DataManager::getDetallePlanificacion($_fecha_plan, $_SESSION["_usrid"]);
 if (count($_planificado)){
 	foreach ($_planificado as $k => $_planifcontrol){															
		$_planifcontrol 		= $_planificado[$k];
		$_planifcontrolactiva	= $_planifcontrol["planifactiva"];	
		if($_planifcontrolactiva == 0){
			echo "La planificación YA fue enviada. No puede ser modificada."; exit;
		}
	}
 } 
  
 //*************************************************
 //Controlo campos
 //*************************************************
 //controla que no se hayan repetido idclientes.
 for($i = 0; $i < $_cantplanif; $i++){
 	if($_planifcliente[$i] != 0){ //discrimina el cero que serían clientes nuevos
 		for($j = 0; $j < $_cantplanif; $j++){
 			if (($_planifcliente[$i] == $_planifcliente[$j]) && ($i!=$j)){
				echo "No puede cargarse la misma cuenta ".$_planifcliente[$i]." más de una vez."; exit;
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
 $ID	=	DataManager::deleteFromPlanificado($_SESSION["_usrid"], $_fecha_plan); 
 //*******************************************	
 //			GRABO LA PLANIFICACIÓN
 //*******************************************
 for($i = 0; $i < $_cantplanif; $i++){
	 if (!empty($_planifcliente[$i])){
		if(empty($_planifnombre[$i]) || empty($_planifdir[$i])){
			echo "Hubo un error al cargar los datos. Revise y vuelva a intentarlo"; exit;
 		}
		 
		$_planifobject	= DataManager::newObjectOfClass('TPlanificacion');
		$_planifobject->__set('ID',			$_planifobject->__newID());
		$_planifobject->__set('IDVendedor',	$_SESSION["_usrid"]);
		$_planifobject->__set('Fecha',		$_fecha_plan);
 		$_planifobject->__set('Cliente',	$_planifcliente[$i]);
 		$_planifobject->__set('Nombre',		$_planifnombre[$i]);
 		$_planifobject->__set('Direccion',	$_planifdir[$i]);
		 
		if ($_enviar == 1){
			//Al realizar el envío, se crea el objeto para cargarlo también como parte diario
			$_parteobject	= DataManager::newObjectOfClass('TPartediario');
			$_parteobject->__set('ID'		, $_parteobject->__newID());
			$_parteobject->__set('IDVendedor', $_SESSION["_usrid"]);
			$_parteobject->__set('Fecha'	, $_fecha_plan);
 			$_parteobject->__set('Cliente'	, $_planifcliente[$i]);
 			$_parteobject->__set('Nombre'	, $_planifnombre[$i]);
 			$_parteobject->__set('Direccion', $_planifdir[$i]);
			$_parteobject->__set('Envio'	, date("2001-01-01 00:00:00"));
			$_parteobject->__set('Activa'	, '1');
			
			$ID_parte = DataManager::insertSimpleObject($_parteobject);
			if (empty($ID_parte)) { echo "Error Parte. $ID."; exit; }			
			//una vez cargado como parte diario, se desactiva como planificado ya que fué enviado y se graba la fecha 
			
			$_planifobject->__set('Envio'	, date("Y-m-d H:i:s"));
			$_planifobject->__set('Activa'	, '0');
		} else {
			$_planifobject->__set('Envio'	, date("2001-01-01 00:00:00"));
			$_planifobject->__set('Activa'	, '1');
		}
		$ID = DataManager::insertSimpleObject($_planifobject);
		 
		if (empty($ID)) { echo "Error. $ID."; exit; }
	 }
 }

 echo $_enviar;
?>