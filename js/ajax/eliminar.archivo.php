<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){ 	
	//$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	//echo $_SESSION["_usrol"];
	//header("Location: $_nextURL");
	echo "Usuario no permitido para realizar ésta acción."; exit;
}

$direccion	= 	empty($_REQUEST['direccion']) ? 0 	: $_REQUEST['direccion'];

$dir	=	$_SERVER['DOCUMENT_ROOT'].$direccion;
if (!@unlink($dir)) { echo "Error al querer eliminar el archivo"; exit; }
	
?>