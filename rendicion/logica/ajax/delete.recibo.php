<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_rendid	= empty($_REQUEST['rendid']) 	? 0 : $_REQUEST['rendid'];
$_recid		= empty($_REQUEST['recid']) 	? 0 : $_REQUEST['recid'];

if($_rendid == 0 || $_recid == 0){
	echo "Debe seleccionar un recibo para eliminar"; exit;
}

$_cantRecibos	= 0;
$_cantCheques 	= 0;
$_cantFacturas 	= 0;

//Elimina registros según tenga o no tenga cheques y/o factura
$_cantRecibos	=	DataManager::getContarRecibos($_rendid); // Cuenta los recibos por rendicion
$_cantFacturas	=	DataManager::getContarFacturas($_recid);
$_cantCheques	=	DataManager::getContarCheques($_recid);

// Busco los recibos segun su talonario
$_reciboobject	=	DataManager::newObjectOfClass('TRecibos', $_recid);	
$_nroTal 		=	$_reciboobject->__get('Talonario');	
$_Recibos		=	DataManager::getRecibos($_nroTal);

if($_cantFacturas == 0){
	//Eliminar recibos ANULADOS
	$_factResult	=	DataManager::deleteReciboSinFantura($_recid);
} else {
	if($_cantCheques == 0){
		//Eliminar recibos Sin cheques
		$_chequeResult	=	DataManager::deleteReciboSinCheque($_recid);
	} else {
		//Eliminar recibos Con cheques
		//$_chequeResult	=	DataManager::deleteReciboConCheque($_recid);
		$facturasIdResult	=	DataManager::getFacturasRecibo($_recid);
		foreach ($facturasIdResult as $k => $factID) {
			$_facturaResult	=	DataManager::deleteChequesFactura($factID['factid']);
		}
		$_chequeResult	=	DataManager::deleteReciboSinCheque($_recid);
	}
}

// Si al eliminar, la cantidad de recibos es 1. Debería eliminar también la rendición.
if($_cantRecibos == 1){
	$_rendicionResult	=	DataManager::deleteRendicion($_rendid);
}

if(count($_Recibos) == 1) {
	DataManager::deletefromtabla('talonario_idusr', 'nrotalonario', $_nroTal);
}

echo "1";

?>