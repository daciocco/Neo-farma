<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A"){
	echo "SU SESIÓN HA EXPIRADO"; exit;
}

$zId		= 	(isset($_POST['zid']))		? $_POST['zid'] : NULL;
$zona		= 	(isset($_POST['zzona']))	? $_POST['zzona'] : NULL;
$nombre		= 	(isset($_POST['znombre']))	? $_POST['znombre'] : NULL;
$assigned	= 	(isset($_POST['asignado']))	? $_POST['asignado'] : NULL;

if (empty($zona)) {
	echo "Indique la zona."; exit;
}
if (empty($nombre)) {
	echo "Indique el nombre."; exit;
}
if(empty($assigned)){
	echo "Indique usuario asignado."; exit;
}

$zObject	= ($zId) ? DataManager::newObjectOfClass('TZonas', $zId) : DataManager::newObjectOfClass('TZonas');
$zObject->__set('Zona'			, $zona);
$zObject->__set('Nombre'		, $nombre);
$zObject->__set('UsrAssigned'	, $assigned);
if ($zId) {
 	$ID = DataManager::updateSimpleObject($zObject);
} else {
	$zObject->__set('ID'	, $zObject->__newID());
	$zObject->__set('Activo', 1);
	$ID = DataManager::insertSimpleObject($zObject);
}

 echo "1"; exit;
?>