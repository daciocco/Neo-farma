<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_idempresa		= empty($_REQUEST['idempresa']) ? 0 : $_REQUEST['idempresa'];
$_idprov 		= empty($_REQUEST['idprov']) 	? 0 : $_REQUEST['idprov'];

if(empty($_idempresa) || empty($_idprov)){
	echo "Ocurrió un ERROR al verificar el proveedor."; exit;
}

//Busco registros ya guardados en ésta fecha y pongo en cero si no están en el array (si fueron eliminados)
$_proveedor		=	DataManager::getProveedor('providprov', $_idprov, $_idempresa);
if($_proveedor){
	$_activo	=	$_proveedor['0']['provactivo'];
	$_nombre	= 	$_proveedor['0']['provnombre'];
	if(!$_activo){
		echo "El proveedor '".$_idprov." - ".$_nombre."' no se encuentra activo."; exit;
	}	
} else {
	echo "El proveedor no existe cargado"; exit;
}

echo "1"; exit;

?>