<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$empresa		= (isset($_POST['empresa']))	? 	$_POST['empresa'] 	: 	NULL;
//Id tabla drogueriaCAD para buscar los datos en tabla droguerías, columna drgdcadId
$drogCADId		= (isset($_POST['drogid']))		? 	$_POST['drogid'] 	: 	NULL; 
$nombre			= (isset($_POST['nombre']))		? 	$_POST['nombre'] 	: 	NULL; // nombre drog CAD
// id tabla drogueria de la columna drogid
$drogCuentaId	= (isset($_POST['drogtid']))	? 	$_POST['drogtid'] 	: 	NULL; 
$cuenta			= (isset($_POST['drogtcliid']))	? 	$_POST['drogtcliid'] : 	NULL; //cuenta relacionada
$correotrans	= (isset($_POST['drogtcorreotrans']))	? 	$_POST['drogtcorreotrans'] 	: 	NULL;
$tipotrans		= (isset($_POST['drogttipotrans']))		? 	$_POST['drogttipotrans'] 	: 	NULL;
$rentTl			= (isset($_POST['rentTl']))		? 	$_POST['rentTl'] 	: 	NULL;
$rentTd			= (isset($_POST['rentTd']))		? 	$_POST['rentTd'] 	: 	NULL;

if (empty($empresa)) {
	echo "Seleccione una empresa.";  exit;
} 
if(empty($nombre)){
	echo "Indique el nombre de la droguería."; exit;
}
if(empty($cuenta) || !is_numeric($cuenta)){
	echo "Indique un número de cuenta de droguería.";  exit;
} else {
	//controlar que la cuenta exista
	$ctaId = DataManager::getCuenta('ctaid', 'ctaidcuenta', $cuenta, $empresa);
	if(empty($ctaId)){
		echo "La cuenta indicada no existe registrada en ésta empresa.";  exit;
	}
	
	//controlar que la cuenta no esté ya cargada
	if(empty($drogCuentaId)){
		$drogId = DataManager::getDrogueria(NULL, $empresa, NULL, NULL, $cuenta);
		if($drogId){
			echo "La cuenta indicada ya existe registrada.";  exit;
		}
	}
}

if (empty($correotrans) || !preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $correotrans )) {
	echo "El correo transfer es incorrecto."; exit;
}
if (empty($tipotrans) || is_numeric($tipotrans)) {
	echo "Indique el tipo de transfer."; exit;
}
if (empty($rentTl) || !is_numeric($rentTl)) {
	echo "Indique la rentabilidad TL correcta."; exit;
}
if (empty($rentTd) || !is_numeric($rentTd)) {
	echo "Indique la rentabilidad TD correcta."; exit;
}

//drogueriasCAD
$objectDrogCAD	= ($drogCADId) ? DataManager::newObjectOfClass('TDrogueriaCAD', $drogCADId) : DataManager::newObjectOfClass('TDrogueriaCAD');
$objectDrogCAD->__set('Empresa'		, $empresa);
$objectDrogCAD->__set('Nombre'		, strtoupper($nombre));
$objectDrogCAD->__set('LastUpdate'	, date("Y-m-d H:m:s"));
$objectDrogCAD->__set('UserUpdate'	, $_SESSION["_usrid"]);

if ($drogCADId) { //UPDATE
	DataManagerHiper::updateSimpleObject($objectDrogCAD, $drogCADId);
	DataManager::updateSimpleObject($objectDrogCAD);
	$IDCad = $drogCADId;
	//	MOVIMIENTO	//	
	$movTipo	= 'UPDATE';
	
} else {  //INSERT
	$objectDrogCAD->__set('ID'		, $objectDrogCAD->__newID());
	$objectDrogCAD->__set('Activa'	, 1);
	
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin	
	$IDCad = DataManager::insertSimpleObject($objectDrogCAD);
	DataManagerHiper::insertSimpleObject($objectDrogCAD, $IDCad);
	//	MOVIMIENTO	//
	$movTipo	= 'INSERT';	
	
}

$movimiento = 'drogCADId';	
dac_registrarMovimiento($movimiento, $movTipo, 'TDrogueriaCAD', $IDCad);

//-----------------//
//droguerias
$objectDrog	= ($drogCuentaId) ? DataManager::newObjectOfClass('TDrogueria', $drogCuentaId) : DataManager::newObjectOfClass('TDrogueria');
$objectDrog->__set('IDEmpresa'		, $empresa);
$objectDrog->__set('Cliente'		, $cuenta);
$objectDrog->__set('CorreoTransfer'	, $correotrans);
$objectDrog->__set('TipoTransfer'	, $tipotrans);
$objectDrog->__set('RentabilidadTL'	, $rentTl);
$objectDrog->__set('RentabilidadTD'	, $rentTd);
$objectDrog->__set('CadId'			, $IDCad); // = $drogCADId

if ($drogCuentaId) { //UPDATE
	DataManagerHiper::updateSimpleObject($objectDrog, $drogCuentaId);
	DataManager::updateSimpleObject($objectDrog);	
	//	MOVIMIENTO	//
	$movTipo	= 'UPDATE';
	
} else {  //INSERT
	$objectDrog->__set('CorreoAbm'	, $correotrans);
	$objectDrog->__set('TipoAbm'	, $tipotrans);	
	$objectDrog->__set('ID', $objectDrog->__newID());
	$objectDrog->__set('Activa', 1);
	
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin	
	$drogCuentaId = DataManager::insertSimpleObject($objectDrog);
	DataManagerHiper::insertSimpleObject($objectDrog, $drogCuentaId);	
	//	MOVIMIENTO	//
	$movTipo	= 'INSERT';	
}

$movimiento = 'drogId';
dac_registrarMovimiento($movimiento, $movTipo, 'TDrogueria', $drogCuentaId);
 
echo 1; exit;
?>