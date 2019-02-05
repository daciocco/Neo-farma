<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){ echo "Invalido"; exit; }

$_mes 	= 	$_POST['mes'];
$_anio 	= 	$_POST['anio'];

if(empty($_mes) || empty($_anio)){echo "Error al cargar el mes o año para duplicar los precios."; exit;}

//Leo cada artículo y precio de la bonificación para actualizar los precios en tabla artículos
$_bonificaciones	=	DataManager::getDetalleBonificacion($_mes, $_anio);
if ($_bonificaciones) {
	foreach ($_bonificaciones as $k => $_bonif){
		$_bonif			=	$_bonificaciones[$k];
		$_bonifart		=	$_bonif['bonifartid'];
		$_bonifprecio	=	$_bonif['bonifpreciodrog'];		
		$_bonifdigitado	=	$_bonif['bonifpreciodigitado'];
		$_bonifiva		=	$_bonif['bonifiva'];
				
		//Busco ID Art de tabla Artículo para UPDATE precio NEO-FARMA
		$_artid		= DataManager::getArticulo('artid', $_bonifart, 1, 1); //busca el artículo de la empresa y laboratorio 1
		//$_artobject	= ($_artid) ? DataManager::newObjectOfClass('TArticulo', $_artid) : DataManager::newObjectOfClass('TArticulo');
 		//$_artobject->__set('Precio',	$_bonifprecio);
 		if ($_artid) {
			$_artobject	=	DataManager::newObjectOfClass('TArticulo', $_artid);
			$_artobject->__set('Precio',	$_bonifprecio);
			$ID = DataManager::updateSimpleObject($_artobject);
 		} else {
			echo "El artículo ".$_bonifart." no fue encontrado en Neofarma."; exit;
 		}
		
		//Busco ID Art de tabla Artículo para UPDATE precio LABORATORIO
		$_artid		= DataManager::getArticulo('artid', $_bonifart, 3, 1); //busca el artículo de la empresa y laboratorio 1
		//$_artobject	= ($_artid) ? DataManager::newObjectOfClass('TArticulo', $_artid) : DataManager::newObjectOfClass('TArticulo');
 		
		//$_bonifprecio	= (empty($_bonifiva)) ? ($_bonifprecio / 1.21) : $_bonifprecio;				
		//$_artobject->__set('Precio',	$_bonifprecio);
 		if ($_artid) {
			$_artobject	= DataManager::newObjectOfClass('TArticulo', $_artid); 		
			$_bonifprecio	= (empty($_bonifiva)) ? ($_bonifprecio / 1.21) : $_bonifprecio;				
			$_artobject->__set('Precio',	$_bonifprecio);
			$ID = DataManager::updateSimpleObject($_artobject);
 		} else {
			echo "El artículo ".$_bonifart." no fue encontrado en Laboratorio."; exit;
 		}
		
	}
	
	//**********************//	
	//Registro de movimiento//
	//**********************//
	$movimiento = 'BONIF_ACTUALIZAR_PRECIOS';
	$movTipo	= 'UPDATE';
	dac_registrarMovimiento($movimiento, $movTipo, "TArticulo");
		
	echo "1"; exit;
	
}

echo "Error. No hay datos de bonificación en la página actual para realizar actualizaciones."; exit;
?>