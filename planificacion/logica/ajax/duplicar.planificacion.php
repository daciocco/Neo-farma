<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){
	echo 'SU SESION HA EXPIRADO.'; exit;
 }

 //******************************************* 
 $_fecha_origen		=	(isset($_POST['fecha_origen']))		? $_POST['fecha_origen'] : NULL;
 $_fecha_destino 	= 	(isset($_POST['fecha_destino']))	? $_POST['fecha_destino'] : NULL;
  
 //*************************************************
 //Controles
 //*************************************************  
 if (empty($_fecha_destino)){
	 echo "Debe seleccionar una fecha de destino"; exit;
 }
 
 $_planificado_destino	= DataManager::getDetallePlanificacion($_fecha_destino, $_SESSION["_usrid"]);
 if (count($_planificado_destino) > 0){
	echo "Imposible duplicar. Ya existen datos en la fecha ".$_fecha_destino." de destino indicada"; exit;
 }
  
 //*************************************************
 //Duplicado de registros
 //************************************************* 
 	$date = $_fecha_destino;
	list($día, $mes, $año) = explode('-', str_replace('/', '-', $date));	
		
 	$_fecha_destino = $año."-".$mes."-".$día;
		
 $_planificado_origen	= DataManager::getDetallePlanificacion($_fecha_origen, $_SESSION["_usrid"]);
 if (count($_planificado_origen)){
 	foreach ($_planificado_origen as $k => $_planiforig){															
		$_planiforig 		= $_planificado_origen[$k];
		$_planiforigcliente	= $_planiforig["planifidcliente"];								
		$_planiforignombre	= $_planiforig["planifclinombre"];
		$_planiforigdir		= $_planiforig["planifclidireccion"];	
				
		/*se graba el duplicado*/
		$_planifobjectdest	= DataManager::newObjectOfClass('TPlanificacion');
		$_planifobjectdest->__set('ID'			, $_planifobjectdest->__newID());
		$_planifobjectdest->__set('IDVendedor'	, $_SESSION["_usrid"]);
		$_planifobjectdest->__set('Fecha'		, $_fecha_destino);
 		$_planifobjectdest->__set('Cliente'		, $_planiforigcliente);
 		$_planifobjectdest->__set('Nombre'		, $_planiforignombre);
 		$_planifobjectdest->__set('Direccion'	, $_planiforigdir);
		$_planifobjectdest->__set('Envio'		, date("2001-01-01 00:00:00"));
		$_planifobjectdest->__set('Activa'		, '1');
		$ID = DataManager::insertSimpleObject($_planifobjectdest);
		if (empty($ID)) {
			echo "Error de duplicado. $ID."; exit;
 		}
	}
 } else {
 	echo "No se verifica que haya datos para duplicar en la fecha de origen"; exit;
 }
 
 echo "Se ha duplicado la planificación";
?>