<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"  && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$id		= empty($_REQUEST['id']) 	? 0 : $_REQUEST['id'];

if ($id) {
	$artObject	= DataManager::newObjectOfClass('TArticulo', $id);
	$status	= ($artObject->__get('Activo')) ? 0 : 1;
	$artObject->__set('Activo',	$status);
	
	$artObjectHiper	= DataManagerHiper::newObjectOfClass('THiperArticulo', $id);
	$artObjectHiper->__set('Activo',	$status);
	
	DataManagerHiper::updateSimpleObject($artObjectHiper, $id);
	DataManager::updateSimpleObject($artObject);
	
	//	Registro MOVIMIENTO	 //
	$movimiento = 'ChangeStatus';
	$movTipo	= 'UPDATE';
	dac_registrarMovimiento($movimiento, $movTipo, "TArticulo", $id);
}

echo "1"; exit;
?>