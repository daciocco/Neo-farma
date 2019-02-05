<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
 }

 $_idnt				= empty($_REQUEST['idnt']) 	? 0 			: $_REQUEST['idnt'];
 $backURL			= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL'];
 
 if ($_idnt) {
	$_uobject	= DataManager::newObjectOfClass('TNoticia', $_idnt);
	$_uobject->__set('ID', 	$_idnt );
	$ID = DataManager::deleteSimpleObject($_uobject);
 }
 
header('Location: '.$backURL);
?>