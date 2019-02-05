<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

 $_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
 $_idnt		= empty($_REQUEST['idnt']) 		? 0 			: $_REQUEST['idnt'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/noticias/': $_REQUEST['backURL'];

 if ($_idnt) {
	$_noticia	= DataManager::newObjectOfClass('TNoticia', $_idnt);
	$_status	= ($_noticia->__get('Activa')) ? 0 : 1;
	$_noticia->__set('Activa', $_status);
	$ID = DataManager::updateSimpleObject($_noticia);
 }

header('Location: '.$backURL.'?pag='.$_pag);
?>