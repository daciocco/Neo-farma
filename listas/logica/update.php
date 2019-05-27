<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$id 			= (isset($_POST['id'])) ? $_POST['id'] : NULL;
$nombre			= (isset($_POST['nombre'])) ? $_POST['nombre']  : NULL;
$catComercial	= (isset($_POST['categoriaComer'])) ? $_POST['categoriaComer']  : NULL;

if(empty($nombre)){
	echo "Indique un nombre"; exit;
} else {
	//Comprobar que nombre de cadena no exista
	$cont	= 0;
	$nombre = mb_strtoupper(trim(str_replace("  ", " ", $nombre)),"UTF-8" );
	$listas	= DataManager::getListas();
	foreach ($listas as $k => $list) {
		$listNombre	= 	$list['NombreLT'];
		if(!strcasecmp($nombre, $listNombre)) {
			$cont++;
		}
	}
	if(empty($id)) {$cont++;}
	if($cont > 1){ echo "El nombre ya existe"; exit; }	
}

if($catComercial){
	$catComercial = implode(",", $catComercial);
}

//-----------
$listObject	= ($id) ? DataManager::newObjectOfClass('TListas', $id) : DataManager::newObjectOfClass('TListas');
$listObject->__set('Nombre'	, $nombre);
$listObject->__set('CategoriaComercial', $catComercial);

if($id) {
	$tipoMov	= 'UPDATE';	
	DataManagerHiper::updateSimpleObject($listObject, $id);
	DataManager::updateSimpleObject($listObject);
} else {
	$tipoMov = 'INSERT';
	$listObject->__set('ID'		, $listObject->__newID());
	$listObject->__set('Activa'	, 0);
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin	
	$id = DataManager::insertSimpleObject($listObject);
	DataManagerHiper::insertSimpleObject($listObject, $id);
}

$movimiento = 'ListadePrecios_'.$nombre;

// MOVIMIENTO CADENA
dac_registrarMovimiento($movimiento, $tipoMov, "TListas", $id);

echo 1; exit;

?>