<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//---------------------------
$empresa	= 	(isset($_POST['empselect']))	? 	$_POST['empselect']	:	NULL;
$activos	= 	(isset($_POST['actselect']))	? 	$_POST['actselect']	:	NULL;
$tipo		= 	(isset($_POST['tiposelect']))		?	$_POST['tiposelect']		:	NULL;
//----------------------------
$tipo = ($tipo == '0') ? NULL : "'".$tipo."'";

$conds		= DataManager::getCondiciones(0, 0, $activos, $empresa, NULL, NULL, $tipo);

echo count($conds);
	
?>