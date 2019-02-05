<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
$condId		= empty($_REQUEST['condid'])	? 0 			: $_REQUEST['condid'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/condicionpago/'	: $_REQUEST['backURL'];

if ($condId) {
	$condObject	= DataManager::newObjectOfClass('TCondicionPago', $condId);
	$_status	= ($condObject->__get('Activa')) ? 0 : 1;
	$condObject->__set('Activa', $_status);	
	$condObject->__set('UsrUpdate'	, $_SESSION["_usrid"]);
	$condObject->__set('Update'		, date("Y-m-d"));
		
	DataManagerHiper::updateSimpleObject($condObject, $condId);
	DataManager::updateSimpleObject($condObject);
	
	//REGISTRA MOVIMIENTO
	$movimiento = 'CONDICION_PAGO_CHANGESTATUS';
	$movTipo	= 'UPDATE';
	dac_registrarMovimiento($movimiento, $movTipo, "TCondicionPago", $condId);
}

header('Location: '.$backURL.'?pag='.$_pag);
?>