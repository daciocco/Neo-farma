<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_archivo	= 	empty($_REQUEST['archivo']) ? 0 : $_REQUEST['archivo'];
$_provid	= 	empty($_REQUEST['provid']) 	? 0 : $_REQUEST['provid'];
$backURL	= 	empty($_REQUEST['backURL']) ? '/pedidos/proveedores/': $_REQUEST['backURL'];

if ($_provid) {
	//Elimina documentación del proveedor
	$dir	=	$_SERVER['DOCUMENT_ROOT']."/pedidos/login/registrarme/archivos/proveedor/".$_provid;
	if (!@unlink($dir.'/'.$_archivo)) {
		$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 13);
		header('Location:' . $_goURL);
		exit;	
	} else {
		$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d", $_provid);
		header('Location:' . $_goURL);
		exit;
	}
}
 
header('Location: '.$backURL);
?>