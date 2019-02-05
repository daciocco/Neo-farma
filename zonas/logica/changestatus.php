<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
$_zid		= empty($_REQUEST['zid']) ? 0 : $_REQUEST['zid'];
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/zonas/': $_REQUEST['backURL'];

if ($_zid) {
	$_zobject	= DataManager::newObjectOfClass('TZonas', $_zid);
	$zona 		= $_zobject->__get('Zona');
	//---------------------------
	//verificar que no haya cuentas con esa zona definida
	$cuentas	= DataManager::getCuentaAll('*', 'ctazona', $zona);
	if (count($cuentas)) {	
		echo "Existen cuentas que aún tienen definida ésta ZONA."; exit;	
	}	
	//---------------------------
	
	$_status	= ($_zobject->__get('Activo')) ? 0 : 1;
	$_zobject->__set('Activo', $_status);
	$ID = DataManager::updateSimpleObject($_zobject);
	
}

header('Location: '.$backURL.'?pag='.$_pag);
?>