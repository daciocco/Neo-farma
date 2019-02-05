<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
 }

  
//*******************************************
 $_vendedor		=	(isset($_POST['vendedor']))		? $_POST['vendedor'] 		: NULL;
 $_fecha_anular	=	(isset($_POST['fecha_anular']))	? $_POST['fecha_anular'] 	: NULL;	
//*******************************************
//Controles
//*******************************************
 if ($_vendedor == 0) { echo "Debe seleccionar un vendedor."; exit; }

 if (empty($_fecha_anular)) { echo "Debe indicar la fecha a anular."; exit; } 
//*************************************************
 //	Anulo la planificación
 //************************************************* 
 //Modifico partes con esa fecha y usuario de enviado a no enviado
 $_parte	=	DataManager::getDetalleParteDiario($_fecha_anular, $_vendedor); 
 if (count($_parte)){
 	foreach ($_parte as $k => $_part){															
		$_part 		= $_parte[$k];
		$_partid	= $_part["parteid"];
		
		/*se setea el parte*/
		$_partobject	= DataManager::newObjectOfClass('TPartediario', $_partid);
		if($_partobject->__get('Activa') == 0){	
			$_partobject->__set('Envio',	'2001-01-01');		
			$_partobject->__set('Activa',	'1');
			$ID = DataManager::updateSimpleObject($_partobject);
			if (empty($ID)) { echo "Error al anular el parte. $ID."; exit; }
		}
	}
 } else {
 	echo "No se verifica que haya datos de parte diario para anular."; exit;
 }

 
//*******************************************************************
 echo "Se ha anulado el envío del parte. Si no ve lo cambios actualice con la tecla F5";
?>
