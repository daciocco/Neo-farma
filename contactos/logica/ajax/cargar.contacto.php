<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//*************************************************
$_ctoid	= 	(isset($_POST['ctoid']))	? $_POST['ctoid']		: 	NULL;
//*************************************************

$_contacto 		= DataManager::newObjectOfClass('TContacto', $_ctoid);
$_apellido 		= $_contacto->__get('Apellido');
$_nombre 		= $_contacto->__get('Nombre');
$_telefono 		= $_contacto->__get('Telefono');
$_interno 		= $_contacto->__get('Interno');
$_sector 		= $_contacto->__get('Sector');
$_correo 		= $_contacto->__get('Email');

$_sectores		= 	DataManager::getSectores(1);
if($_sectores){ 
	foreach ($_sectores as $k => $_sect) {
		$_sectid	= $_sect['sectid'];
		if($_sectid == $_sector){ 
			$_sectnombre	= $_sect['sectnombre']; 
		} 
	}
}
//*************************************************

$_datos[]	=	array(
	"apellido"	=> 	$_apellido,
	"nombre"	=> 	$_nombre,
	"telefono" 	=> 	$_telefono,
	"interno" 	=> 	$_interno,
	"sector"	=>  $_sectnombre,
	"correo" 	=>	$_correo									
);		

$objJason = json_encode($_datos);
echo $objJason;
		
?>