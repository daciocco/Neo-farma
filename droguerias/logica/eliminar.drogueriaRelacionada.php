<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$drogTId	= empty($_REQUEST['drogtid'])	?	0	: $_REQUEST['drogtid'];
if ($drogTId) {
	//eliminar cuenta relacionada
	$drogObject	= DataManager::newObjectOfClass('TDrogueria', $drogTId);
	$drogObject->__set('ID', $drogTId );
	DataManagerHiper::deleteSimpleObject($drogObject, $drogTId);
	DataManager::deleteSimpleObject($drogObject);
}

echo "1"; exit;
?>

