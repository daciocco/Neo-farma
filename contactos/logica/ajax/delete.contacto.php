<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

 $_ctoid	= 	empty($_REQUEST['ctoid']) ? 0 	: $_REQUEST['ctoid'];

 if ($_ctoid) {
	$_contactoobject	=	DataManager::newObjectOfClass('TContacto', $_ctoid);
	$_contactoobject->__set('ID', 				$_ctoid);
	$ID = DataManager::deleteSimpleObject($_contactoobject);
 }
 
 echo 1; exit;
?>