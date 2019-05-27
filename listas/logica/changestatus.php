<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A"  && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$id		= empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];
$backURL= empty($_REQUEST['backURL']) ? '/pedidos/listas/': $_REQUEST['backURL'];

if ($id) {
	$listObject= DataManager::newObjectOfClass('TListas', $id);
	$_status	= ($listObject->__get('Activa')) ? 0 : 1;
	$listObject->__set('Activa', $_status);
	DataManagerHiper::updateSimpleObject($listObject, $id);
	DataManager::updateSimpleObject($listObject);
}

//	Registro MOVIMIENTO	 //
$movTipo	= 'UPDATE';
$movimiento = 'ChangeStatusTo_'.$_status;
dac_registrarMovimiento($movimiento, $movTipo, "TListas", $id);

header('Location: '.$backURL);
?>