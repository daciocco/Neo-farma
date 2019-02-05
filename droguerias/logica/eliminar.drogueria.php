<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");

if ($_SESSION["_usrrol"]!="A"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/droguerias/': $_REQUEST['backURL'];
$drogIdCAD	= empty($_REQUEST['drogid']) ? 0 : $_REQUEST['drogid']; //ID de la droguería

if ($drogIdCAD) {
	//eliminar cuentas relacionadas
	$cuentasDroguerias	= DataManager::getDrogueria(NULL, NULL, $drogIdCAD); 
	if (count($cuentasDroguerias)) {
		foreach($cuentasDroguerias as $k => $drog) {			
			$id			= $drog['drogtid'];
			$drogObject	= DataManager::newObjectOfClass('TDrogueria', $id);
			$drogObject->__set('ID', $id);
			DataManagerHiper::deleteSimpleObject($drogObject, $id);
			DataManager::deleteSimpleObject($drogObject);	
		}			
	}		
	//eliminar drogueria
	$drogCadObject	= DataManager::newObjectOfClass('TDrogueriaCAD', $drogIdCAD);
	$drogCadObject->__set('ID',	$drogIdCAD );
	DataManagerHiper::deleteSimpleObject($drogCadObject, $drogIdCAD);
	DataManager::deleteSimpleObject($drogCadObject);
} else {
	echo "Error al intentar eliminar registros."; exit;
}
 
header('Location: '.$backURL);
?>