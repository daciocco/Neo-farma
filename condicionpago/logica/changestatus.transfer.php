<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

 $_pag		= empty($_REQUEST['pag2']) 		? 0 			: $_REQUEST['pag2'];
 $_condid	= empty($_REQUEST['condid'])	? 0 			: $_REQUEST['condid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/condicionpago/'	: $_REQUEST['backURL'];

 if ($_condid) {
 	$_condobject	= DataManager::newObjectOfClass('TCondiciontransfer', $_condid);
 	$_status	= ($_condobject->__get('Activa')) ? 0 : 1;
	$_condobject->__set('Activa',	$_status);
	$ID = DataManager::updateSimpleObject($_condobject);
}

header('Location: '.$backURL.'?pag2='.$_pag);
?>