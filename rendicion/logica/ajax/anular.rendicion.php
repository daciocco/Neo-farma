<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
 }

//*******************************************
 $_idusr		=	(isset($_POST['idusr']))		? $_POST['idusr'] 		: NULL;
 $_nro_anular	=	(isset($_POST['nro_anular']))	? $_POST['nro_anular'] 	: NULL;
 
//*************************************************
//Controles
//*************************************************
 if ($_idusr == 0) { echo "Debe seleccionar un vendedor."; exit; }
 if (empty($_nro_anular) || !is_numeric($_nro_anular)) { echo "Número de rendición incorrecto."; exit; }
 
//*************************************************
 //	Desactivo la rendición (con esa  fecha y usuario)
 //************************************************* 
 $_rendicion	=	DataManager::getRendicion($_idusr, $_nro_anular); 
 if (count($_rendicion)){
 	foreach ($_rendicion as $k => $_rend){															
		$_rend 		= $_rendicion[$k];
		$_rendid	= $_rend["rendid"];				
		/*se anula la rendicion*/
		$_rendobject	= DataManager::newObjectOfClass('TRendicion', $_rendid);
		$_rendobject->__set('ID',		$_rendid);
		if(($_rendobject->__get('Activa')) == 0){
			$_rendobject->__set('ID',	$_rendid);
			$_rendobject->__set('Activa',	1);
			$ID = DataManager::updateSimpleObject($_rendobject);
			if (empty($ID)) { echo "Error al anular la rendición. $ID."; exit; }
		} else {
			echo "El recibo indicado no se puede anular porque no fue enviado."; exit;
		}		
	}
 } else {
 	echo "No se verifica que haya datos de rendición para anular en esa fecha."; exit;
 }
 
 //*******************************************************************
 echo "Se ha anulado el envío de la rendición. Si no ve los cambios presione la tecla F5";
 //*******************************************************************
?>
