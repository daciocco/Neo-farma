<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_retencion 	=	(isset($_REQUEST['ret']))			? $_REQUEST['ret'] 			: '0.00';
$_deposito		=	(isset($_REQUEST['dep']))			? $_REQUEST['dep'] 			: '0.00';
$_nrorendicion	=	(isset($_REQUEST['rendicion']))		? $_REQUEST['rendicion'] 	: NULL;

$_retencion 	= empty($_retencion) 	? '0.00' : $_retencion;
$_nrorendicion 	= empty($_nrorendicion) ? '0.00' : $_nrorendicion;

if(empty($_nrorendicion)){
	echo "No se verifica el número de rendición."; exit;
}

$_rendiciones	=	DataManager::getRendicion($_SESSION["_usrid"], $_nrorendicion, '1');
if (count($_rendiciones)){
	foreach ($_rendiciones as $k => $_rendicion) {
		$_rendid		=	$_rendicion['rendid'];
		$_rendicionbject= 	DataManager::newObjectOfClass('TRendicion', $_rendid);
		$_rendicionbject->__set('Retencion',	$_retencion);
 		$_rendicionbject->__set('Deposito',		$_deposito);		
		DataManager::updateSimpleObject($_rendicionbject);
		echo 1; exit;		
	}
} else {
	echo "El valor no puede modificarse. Esta rendición YA fue enviada."	;
}

?>