<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

//Id tabla drogueriaCAD para buscar los datos en tabla droguerías, columna drgdcadId
$drogCADId		= (isset($_POST['drogid']))		? 	$_POST['drogid'] 	: 	NULL; 
$nombre			= (isset($_POST['nombre']))		? 	$_POST['nombre'] 	: 	NULL; // nombre drog CAD
// id tabla drogueria de la columna drogid
$drogCuentaId	= (isset($_POST['drogtid']))	? 	$_POST['drogtid'] 	: 	NULL;
$rentTl			= (isset($_POST['rentTl']))		? 	$_POST['rentTl'] 	: 	NULL;
$rentTd			= (isset($_POST['rentTd']))		? 	$_POST['rentTd'] 	: 	NULL;

if(empty($drogCADId) || empty($nombre)){
	echo "Indique datos de la droguería."; exit;
}

if($drogCADId){
	//drogueriasCAD
	$objectDrogCAD	= DataManager::newObjectOfClass('TDrogueriaCAD', $drogCADId);	
	$objectDrogCAD->__set('Nombre'		, strtoupper($nombre));
	$objectDrogCAD->__set('LastUpdate'	, date("Y-m-d H:m:s"));
	$objectDrogCAD->__set('UserUpdate'	, $_SESSION["_usrid"]);
	DataManagerHiper::updateSimpleObject($objectDrogCAD, $drogCADId);
	DataManager::updateSimpleObject($objectDrogCAD);	
	
	//--------------//
	//	MOVIMIENTO	//
	$movimiento = 'drogCADId';
	$tipoMov	= 'UPDATE';
	dac_registrarMovimiento($movimiento, $tipoMov, 'TDrogueriaCAD', $drogCADId);
	
	if(count($drogCuentaId) > 0){
		for($k=0; $k < count($drogCuentaId); $k++){
			//-----------------//
			// UPDATE droguerias
			$objectDrog	= DataManager::newObjectOfClass('TDrogueria', $drogCuentaId[$k]);
			$objectDrog->__set('RentabilidadTL'	, $rentTl[$k]);
			$objectDrog->__set('RentabilidadTD'	, $rentTd[$k]);
			DataManagerHiper::updateSimpleObject($objectDrog, $drogCuentaId[$k]);
			DataManager::updateSimpleObject($objectDrog);
		}
		
		//--------------//	
		//	MOVIMIENTO	//
		$movimiento = 'drogId';		
		$tipoMov	= 'UPDATE';	
		dac_registrarMovimiento($movimiento, $tipoMov, 'TDrogueria', $drogCADId);
	}
} else {
	echo "Error al intentar actualizar los datos."; exit;
}

echo 1; exit;
?>