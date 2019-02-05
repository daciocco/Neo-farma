<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

 $_condid	= empty($_REQUEST['condid']) 	? 0 					: $_REQUEST['condid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/condicion/'	: $_REQUEST['backURL'];

 if ($_condid) {
	$_condicionObject	= DataManager::newObjectOfClass('TCondicionComercial', $_condid);
	$_status			= ($_condicionObject->__get('Activa')) ? 0 : 1;
	$_condicionObject->__set('Activa', $_status);
	$ID 				= DataManager::updateSimpleObject($_condicionObject);
 }

header('Location: '.$backURL);
?>