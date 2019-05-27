<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//------------------
$empresa		= 	(isset($_POST['empresa']))		? 	$_POST['empresa']	:	NULL;
$drogidCAD		= 	(isset($_POST['drogidCAD']))	? 	$_POST['drogidCAD']	:	NULL;
//-------------------
$datosJSON;	

$droguerias	= DataManager::getDrogueria(NULL, $empresa, $drogidCAD); 
if (count($droguerias)) {
	foreach ($droguerias as $k => $drog) {
		$id			= $drog['drogtid'];
		$cuenta		= $drog['drogtcliid'];		
		
		$nombre		= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $cuenta, $empresa);		
		$idLoc		= DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $cuenta, $empresa);
		$localidad	= DataManager::getLocalidad('locnombre', $idLoc);	
		
		$rentTl		= $drog['drogtrentabilidadtl'];
		$rentTD		= $drog['drogtrentabilidadtd'];
		
		$datosJSON[]= 	array(
					"id"		=> 	$id,
					"cuenta"	=> 	$cuenta,
					"nombre"	=> 	$nombre,
					"localidad"	=> 	$localidad,
					"rentTl"	=> 	$rentTl,
					"rentTd"	=> 	$rentTD
				);
	}
}

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>