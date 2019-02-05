<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"  && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_pag		= empty($_REQUEST['pag']) 		? 0	: $_REQUEST['pag'];
$id			= empty($_REQUEST['id']) 	? 0 : $_REQUEST['id'];

if ($id) {
	$artObject	= DataManager::newObjectOfClass('TArticulo', $id);
	$stock	= ($artObject->__get('Stock')) ? 0 : 1;
	$artObject->__set('Stock',	$stock);
	
	
	$artObjectHiper	= DataManagerHiper::newObjectOfClass('THiperArticulo', $id);
	$artObjectHiper->__set('Stock',	$stock);
	
	DataManagerHiper::updateSimpleObject($artObjectHiper, $id);
	DataManager::updateSimpleObject($artObject);
}

echo "1"; exit;
?>