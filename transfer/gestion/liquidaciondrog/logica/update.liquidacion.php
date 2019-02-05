<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php" );

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_drogueria	=	empty($_POST['drogid']) 			?	0 :	$_POST['drogid'];
$_fecha_liq	= 	empty($_POST['fecha_liquidacion']) 	?	0 :	$_POST['fecha_liquidacion'];

if (empty($_drogueria)){	echo "Seleccione una droguería"; exit; }
if ($_fecha_liq == 0){	echo "Seleccione un fecha a conciliar."; exit; }

list($_mes, $_anio) = explode("-", $_fecha_liq);

//**************************//
//	Concilio Liquidación	//
//**************************//
$_liquidaciones	=	DataManager::getDetalleLiquidacion($_mes, $_anio, $_drogueria, 'TD');
if ($_liquidaciones) {
	foreach ($_liquidaciones as $k => $_liq) {
		$_liq			=	$_liquidaciones[$k];
		$_liqID			=	$_liq['liqid'];
				
		//EDITA ESTADO DE LIQUIDADO
		$liqObject	=	DataManager::newObjectOfClass('TLiquidacion', $_liqID);		
		if($liqObject->__get('Activa')){
			echo "La liquidación ya fue conciliada. No puede volver a liquidarla."; exit;
		}
		
		$liqObject->__set('Activa',	0);														
		$ID = DataManager::updateSimpleObject($liqObject);
		
	}
	
	//**********************//	
	//	Registro MOVIMIENTO	//
	//**********************//
	$movimiento = 'LIQUIDACION_NC';	
	$movTipo	= 'UPDATE';	
	dac_registrarMovimiento($movimiento, $movTipo, "TAbm");	
	
	echo "1";
	
} else {
	echo "Ocurrió un error al querer actualizar el estado de liquidaciones. Vuelva a intentarlo."; exit;
}
		


?>