<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
$_uid		= empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL'];


if ($_uid) {
	$_usrobject	= DataManager::newObjectOfClass('TUsuario', $_uid);
	$_status	= ($_usrobject->__get('Activo')) ? 0 : 1;
	$_usrobject->__set('Activo', $_status);
	$ID = DataManager::updateSimpleObject($_usrobject);
	
}

header('Location: '.$backURL.'?pag='.$_pag);
?>