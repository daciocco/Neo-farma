<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"  && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$id		= 	(isset($_POST['id']))? $_POST['id'] 		: NULL;	

if ($id) {
	$eventObject	= DataManager::newObjectOfClass('TAgenda', $id);
	$eventObject->__set('ID',	$id );
	$ID = DataManager::deleteSimpleObject($eventObject);
}
 
echo $ID; exit;
?>