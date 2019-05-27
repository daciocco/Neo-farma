<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$condId	= empty($_REQUEST['condid']) 	? 0 					: $_REQUEST['condid'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/condicion/'	: $_REQUEST['backURL'];

if ($condId) {
	$condicionObject= DataManager::newObjectOfClass('TCondicionComercial', $condId);
	$status			= ($condicionObject->__get('Activa')) ? 0 : 1;
	$condicionObject->__set('Activa', $status);
	DataManagerHiper::updateSimpleObject($condicionObject, $condId);
	DataManager::updateSimpleObject($condicionObject);
	 
	 //	Registro movimiento
	$movimiento	= 'ChangeStatus_a_'.$status;
	$movTipo	= 'UPDATE';
	dac_registrarMovimiento($movimiento, $movTipo, 'TCondicionComercial', $condId);
}

header('Location: '.$backURL);
?>