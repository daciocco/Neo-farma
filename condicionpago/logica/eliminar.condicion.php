<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;	
}

$_pag		= empty($_REQUEST['pag']) 		? 0 : $_REQUEST['pag'];
$condId		= empty($_REQUEST['condid'])	? 0 : $_REQUEST['condid'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/condicionpago/'	: $_REQUEST['backURL'];

if ($condId) {
	$_condobject	= DataManager::newObjectOfClass('TCondicionPago', $condId);
	$_condobject->__set('ID', $condId );
	
	DataManagerHiper::deleteSimpleObject($_condobject, $condId);
	DataManager::deleteSimpleObject($_condobject);
	
	//REGISTRA MOVIMIENTO
	$movimiento = 'CONDICION_PAGO';
	$movTipo	= 'DELETE';	
	dac_registrarMovimiento($movimiento, $movTipo, "TCondicionPago", $condId);
}
 
header('Location: '.$backURL.'?pag='.$_pag);
?>