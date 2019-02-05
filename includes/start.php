<?php
session_start();
if (empty($_SESSION["_usrid"])) {
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrid"];
 	header("Location: $_nextURL");
 	exit;
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/detect.Browser.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php");
?>