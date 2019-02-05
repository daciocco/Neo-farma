<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	 echo 'SU SESION HA EXPIRADO.'; exit;
 }

//*******************************************
 $_vendedor		=	(isset($_POST['vendedor']))		? $_POST['vendedor'] 		: NULL;
 $_fecha_anular	=	(isset($_POST['fecha_anular']))	? $_POST['fecha_anular'] 	: NULL;
//*************************************************
//Controles
//*************************************************
 if ($_vendedor == 0) { echo "Debe seleccionar un vendedor."; exit; }

 if (empty($_fecha_anular)) { echo "Debe indicar la fecha a anular."; exit; }
//*************************************************
 //	Anulo la planificación
 //************************************************* 
 //Elimino partes con esa fecha y usuario
 $_parte	=	DataManager::getDetalleParteDiario($_fecha_anular, $_vendedor); 
 if (count($_parte)){
 	foreach ($_parte as $k => $_part){															
		$_part 		= $_parte[$k];
		$_partid	= $_part["parteid"];				
		/*se elimina el parte*/
		$_partobject	= DataManager::newObjectOfClass('TPartediario', $_partid);
		$_partobject->__set('ID',	$_partid);
		$ID = DataManager::deleteSimpleObject($_partobject);
	}
 } /*else {
 	echo "No se verifica que haya datos de parte diario para anular."; exit;
 }*/
 		
 //Desactivo planificaciones con esa fecha y usuario
 $_planificado	=	DataManager::getDetallePlanificacion($_fecha_anular, $_vendedor); 
 if (count($_planificado)){
 	foreach ($_planificado as $k => $_planif){															
		$_planif 	= $_planificado[$k];
		$_planifid	= $_planif["planifid"];	
				
		/*se modifica la planificación*/
		$_planifobject	= DataManager::newObjectOfClass('TPlanificacion', $_planifid);
		if($_planifobject->__get('Activa') == 0){
			$_planifobject->__set('Envio',		'2001-01-01');
			$_planifobject->__set('Activa',		'1');
			$ID = DataManager::updateSimpleObject($_planifobject);
			if (empty($ID)) { echo "Error al anular lo planificado. $ID."; exit; }
		}
	}
 } else {
 	echo "No se verifica que haya datos planificados para anular."; exit;
 } 
 
 //*******************************************************************
 echo "Se ha anulado la planificación. Si no ve lo cambios actualice con la tecla F5";
 //*******************************************************************
?>
