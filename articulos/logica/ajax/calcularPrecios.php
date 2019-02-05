<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$artId		= (isset($_POST['artId']))			?	$_POST['artId'] 		: 	NULL;
$empresa	= (isset($_POST['empresa']))		?	$_POST['empresa'] 		: 	NULL;
$laboratorio= (isset($_POST['laboratorio']))	?	$_POST['laboratorio'] 	: 	NULL;
$iva		= (isset($_POST['iva']))			?	$_POST['iva'] 			: 	NULL;
$medicinal	= (isset($_POST['medicinal']))		?	$_POST['medicinal'] 	: 	NULL;
$precioVenta= (isset($_POST['precioVenta']))	?	$_POST['precioVenta'] 	: 	NULL;
$porcGanancia= (isset($_POST['porcentaje']))		?	$_POST['porcentaje'] 	: 	NULL;

if(!is_numeric($precioVenta) || $precioVenta <= 0){
	echo "Debe ingresar un precio de Venta"; exit;
}

if(empty($empresa)){
	echo "Seleccione empresa"; exit;
}

if(empty($laboratorio)){
	echo "Seleccione laboratorio"; exit;
}

$precioLista= floatval($precioVenta)/floatval(1.450);

//----------------
if($empresa == 3){
	if($medicinal == 'true'){
		$precioLista= $precioLista/floatval(1.210);
	}
}

$precioArt	= $precioLista / floatval(1.210);

//----------------------
$dto 		= 0;
$porBonif 	= 0;
$divBonif 	= 0;
if($artId){
	$condicionesCompra = DataManagerHiper::getCondCompra($empresa, $laboratorio, $artId);
	if($condicionesCompra){
		foreach ($condicionesCompra as $k => $condCompra) {
			$dto 		= $condCompra["Descuento"];
			$porBonif 	= $condCompra["PorBonif"];
			$divBonif 	= $condCompra["DivBonif"];
		}
	}
}
$presCompra	= 0;
$presVenta 	= 0;
if($artId){
	$equivalenciaUnidades = DataManagerHiper::getEquivUnid($empresa, $laboratorio, $artId);
	if($equivalenciaUnidades){
		foreach ($equivalenciaUnidades as $k => $equivUnid) {
			$presCompra = $equivUnid["PresCompra"];
			$presVenta 	= $equivUnid["PresVenta"];
		}
	}
}
$iva1 	= 0;
$empresas = DataManagerHiper::getEmpresas($empresa);
if($empresas) {
	foreach ($empresas as $k => $emp) {
		$iva1 = $emp["Iva1Emp"];
	}
}
$ivaResult 	= ($iva1 / 100 ) + 1;
$precioCom 	= 0;
$precioRep 	= 0;

//Calculo con descuentos
$precioCom 	= $precioArt;
if($dto <> 0){
	$desc 		= ($precioArt * $dto) / 100;	
	$precioCom 	= $precioCom - $desc;
}

if($porBonif <> 0)	{ $precioCom = $precioCom * $porBonif; 	}

if($divBonif <> 0)	{ $precioCom = $precioCom / $divBonif; 	}
if($presVenta <> 0)	{ $precioCom = $precioCom / $presCompra;}
if($presCompra <> 0){ $precioCom = $precioCom * $presVenta; }
//---------------------------

if($iva == 'true') {
	$precioCom = $precioCom * $ivaResult;
} else {
	$precioLista= $precioLista / floatval(1.210);
}

if($medicinal == 'false'){
	$precioCom	= $precioCom / floatval(1.210);
	$precioLista= $precioLista / floatval(1.210);
}

//porcentaje de ganancia
if(!empty($porcGanancia) && $porcGanancia <> "0.00"){
	$porcGanancia= ($porcGanancia / 100) + 1;
	$precioLista = $precioCom * $porcGanancia;
	/*$porcGanancia 	= ($artGanancia / 100) + 1;			
	$artPrecioVenta = $artPrecioVenta / $porcGanancia;*/
}
$precioRep	= $precioCom;


/*
if($empresa == 3){
	$precioLista	= $precioLista / floatval(1.210);
	$precioCom		= $precioCom / floatval(1.210);
	$precioRep		= $precioRep / floatval(1.210);
	$precioArt		= $precioArt / floatval(1.210);
}*/

$precioLista = number_format($precioLista,3,'.','');
$precioCom	 = number_format($precioCom,3,'.','');
$precioRep	 = number_format($precioRep,3,'.','');
$precioArt	 = number_format($precioArt,3,'.','');
	
echo "$precioLista/$precioCom/$precioRep/$precioArt"; exit;
?>