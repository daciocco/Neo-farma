<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//*************************************************
$empresa	= 	(isset($_POST['empresa']))	? 	$_POST['empresa']	:	NULL;
$cadena		= 	(isset($_POST['cadena']))	? 	$_POST['cadena']	:	NULL;
//*************************************************
$datosJSON;	

$cuentas	= DataManager::getCuentasCadena($empresa, $cadena);
if (count($cuentas)) {
	foreach ($cuentas as $k => $cta) {	
		$idCuenta	= 	$cta['IdCliente'];
		$tipoCadena= 	$cta['TipoCadena'];
		$ctaid		= 	DataManager::getCuenta('ctaid', 'ctaidcuenta', $idCuenta, $empresa);	
		$nombre		= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, $empresa);
		$datosJSON[]	= 	array(
					"id"		=> 	$ctaid,
					"cuenta"	=> 	$idCuenta,
					"nombre"	=> 	$nombre,
					"tipocad"	=> 	$tipoCadena
				);		
	}
}

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>