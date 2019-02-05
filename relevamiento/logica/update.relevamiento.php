<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }

$_relid 		= 	(isset($_POST['relid']))	?	$_POST['relid'] : NULL;	
$_nro 			= 	(isset($_POST['nro']))		?	$_POST['nro'] : NULL;
$_orden	 		= 	(isset($_POST['orden']))	?	$_POST['orden'] : NULL;
$_tipo	 		= 	(isset($_POST['tipo']))		?	$_POST['tipo'] : NULL;
$_pregunta		= 	(isset($_POST['pregunta']))	?	$_POST['pregunta'] : NULL;
$_nulo			= 	(isset($_POST['nulo']))		?	$_POST['nulo'] : NULL;
$_nulo = ($_nulo == 'on') ? 1 : 0;

if (empty($_nro) || !is_numeric($_nro)) {
	echo "Ingrese un número de Relevamiento"; exit;
}

if (empty($_orden) || !is_numeric($_orden)) {
	echo "Ingrese orden de aparición de la pregunta"; exit;
}

if (empty($_tipo)) {
	echo "Indique un tipo de respuesta"; exit;
}

if (empty($_pregunta)) {
	echo "Ingrese una pregunta"; exit;
}
//**************************//
//		Acciones Guardar	//
//**************************//
$_relobject	= ($_relid) ? DataManager::newObjectOfClass('TRelevamiento', $_relid) : DataManager::newObjectOfClass('TRelevamiento');
$_relobject->__set('Relevamiento'	, $_nro);
$_relobject->__set('Tipo'			, $_tipo);
$_relobject->__set('Orden'			, $_orden);
$_relobject->__set('Nulo'			, $_nulo);
$_relobject->__set('Pregunta'		, $_pregunta);
$_relobject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
$_relobject->__set('LastUpdate'		, date("Y-m-d"));


if ($_relid) {
	//Modifica Cliente
	DataManager::updateSimpleObject($_relobject);
} else {	
	$_relobject->__set('ID'			, $_relobject->__newID());
	$_relobject->__set('Activo'		, 1);
	$ID = DataManager::insertSimpleObject($_relobject);
}
 
echo "1"; exit;
?>