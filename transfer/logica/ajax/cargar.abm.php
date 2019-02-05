<?php

session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/transfer/': $_REQUEST['backURL'];

$_drogid	= 	(isset($_POST['drogid']))	? $_POST['drogid'] 	: NULL;
$idCuenta	=	DataManager::getCuenta('ctaidcuenta', 'ctaid', $_drogid);

$_artid 	= 	(isset($_POST['artid']))	? $_POST['artid'] 	: NULL;

//----------------
//Controlo campos
if (empty($_drogid) || empty($_artid)){ echo "1"; exit; }
//---------------------------------------
//Consulto el ABM actual de la droguería
$_abms	=	DataManager::getDetalleAbm(date("m"), date("Y"), $idCuenta, 'TL');

if (count($_abms)>0) {
	foreach ($_abms as $k => $_abm){
		$_abmDrog	=	$_abm['abmdrogid'];
		$_abmArtid	= 	$_abm['abmartid'];
		$_abmDesc	= 	$_abm['abmdesc'];
		
		if ($_abmArtid == $_artid && $_abmDrog == $idCuenta) {
			echo $_abmDesc; exit;
		}		
	}
} 


echo "1"; exit;
?>