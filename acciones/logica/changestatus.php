<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

 $_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
 $_acid		= empty($_REQUEST['acid']) 		? 0 			: $_REQUEST['acid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/acciones/'	: $_REQUEST['backURL'];

 if ($_acid) {
 	$_acobject	= DataManager::newObjectOfClass('TAccion', $_acid);
 	$_status	= ($_acobject->__get('Activa')) ? 0 : 1;
	$_acobject->__set('Activa',	$_status);
	$ID = DataManager::updateSimpleObject($_acobject);
}

header('Location: '.$backURL.'?pag='.$_pag);
?>