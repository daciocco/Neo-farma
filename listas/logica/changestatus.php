<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A"  && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

 $_listaid	= empty($_REQUEST['listaid']) 	? 0 				: $_REQUEST['listaid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/listas/': $_REQUEST['backURL'];

 if ($_listaid) {
	$_lista		= DataManager::newObjectOfClass('TLista', $_listaid);
	$_status	= ($_lista->__get('Activa')) ? 0 : 1;
	$_lista->__set('Activa', $_status);
	$ID 		= DataManager::updateSimpleObject($_lista);
 }

header('Location: '.$backURL);
?>