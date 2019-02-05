<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	echo "SU SESIÓN HA EXPIRADO."; exit;
}

$backURL	= 	empty($_REQUEST['backURL']) ? '/pedidos/soporte/motivos/lista.php': $_REQUEST['backURL'];
$sector		=	empty($_POST['sector']) 		? 0 	: $_POST['sector'];
$responsable=	empty($_POST['responsable']) 	? 0 	: $_POST['responsable'];
$motid		=	empty($_POST['motid']) 			? 0 	: $_POST['motid'];
$motivo		=	empty($_POST['motivo']) 		? 0 	: $_POST['motivo'];

if (empty($sector)) { echo "Seleccione un sector."; exit; }
if (empty($responsable)) { echo "Seleccione un responsable."; exit; }
if (empty($motivo)) { echo "Indique un motivo."; exit; }

$object	= ($motid) ? DataManager::newObjectOfClass('TTicketMotivo', $motid) : DataManager::newObjectOfClass('TTicketMotivo');
$object->__set('Sector'			, $sector);
$object->__set('Motivo'			, $motivo);
$object->__set('UsrResponsable'	, $responsable);
if($motid) {
	$object->__set('UsrUpdate'	, $_SESSION["_usrid"]);
	$object->__set('LastUpdate'	, date("Y-m-d H:m:s")); 
	DataManager::updateSimpleObject($object);
} else {
	$object->__set('UsrCreated'	, $_SESSION["_usrid"]);
	$object->__set('DateCreated', date("Y-m-d H:m:s"));
	$object->__set('Activo'		, 1);
	$object->__set('ID'			, $object->__newID());
	$ID = DataManager::insertSimpleObject($object);
}

echo "1"; exit;
 
?>