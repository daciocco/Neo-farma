<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 
 if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }
 
 $_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
 $_relid	= empty($_REQUEST['relid']) 	? 0 			: $_REQUEST['relid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/relevamiento/'	: $_REQUEST['backURL'];

 if ($_relid) {
 	$_relobject	= DataManager::newObjectOfClass('TRelevamiento', $_relid);
 	$_status	= ($_relobject->__get('Activo')) ? 0 : 1;
	$_relobject->__set('Activo',	$_status);
	$ID = DataManager::updateSimpleObject($_relobject);
}

header('Location: '.$backURL.'?pag='.$_pag);
?>