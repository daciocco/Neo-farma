<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
//require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_pag		=	empty($_REQUEST['pag']) 	? 0	: $_REQUEST['pag'];
$provId	= 	empty($_REQUEST['provid']) 	? 0 : $_REQUEST['provid'];
$backURL	=	empty($_REQUEST['backURL']) ? '/pedidos/proveedores/': $_REQUEST['backURL'];

if ($provId) {
	//Elimina documentación del proveedor
	$dir	=	$_SERVER['DOCUMENT_ROOT']."/pedidos/login/registrarme/archivos/proveedor/".$provId;
	dac_deleteDirectorio($dir);
	//Elimina Contactos del Proveedor?? // Los contactos podrían seguir estando por el tema de CRM
	
	//Elimina proveedor
	$_cliobject	= DataManager::newObjectOfClass('TProveedor', $provId);
	$provIdProv = $_cliobject->__get('Proveedor', $provId);
	$_cliobject->__set('ID', $provId );
	DataManager::deleteSimpleObject($_cliobject);
	
	//**********************//	
	//	Registro MOVIMIENTO	//
	//**********************//
	$movimiento 	= 'PROVEEDOR_'.$provId."-".$provIdProv;	
	$movTipo		= 'DELETE';
	dac_registrarMovimiento($movimiento, $movTipo, "TProveedor", $provId);
	
	
}
 
header('Location: '.$backURL.'?pag='.$_pag);
?>