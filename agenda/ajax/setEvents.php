<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"  && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
	exit;
}


$eventData 		= 	(isset($_POST['eventData']))? $_POST['eventData'] 		: NULL;	
$restringido 	=	(empty($eventData['constraint']) ? NULL : $eventData['constraint']);

if(empty($eventData)){
	echo "No se pudo cargar el evento"; exit;
}
if(empty($eventData['title'])){
	echo "Indique motivo del evento."; exit;
}

if(empty($eventData['color'])){
	$eventData['color'] = '3A87AD';
} else {
	$eventData['color'] = str_replace("#", "", $eventData['color']);
}
$fechaI	= explode( ' ', $eventData['start'] );
list($mesIni, $diaIni, $anoIni) = explode( '/', $fechaI[0]);
$fechaF	= explode( ' ', $eventData['end'] );
list($mesFin, $diaFin, $anoFin) = explode( '/', $fechaF[0]);

$fechaInicio	=	new DateTime($anoIni."-".$mesIni."-".$diaIni." ".$fechaI[1]);
$fechaFin		=	new DateTime($anoFin."-".$mesFin."-".$diaFin." ".$fechaF[1]);

if($mesIni=="00" || $diaIni=="00" || $anoIni=="0000"){
	echo "Indique fecha de Inicio correcta"; exit;
}
if($mesFin=="00" || $diaFin=="00" || $anoFin=="0000"){
	echo "Indique fecha de Fin correcta"; exit;
}
if($fechaInicio >= $fechaFin){
	echo "La fecha y hora de Fin debe ser menor a la Inicial."; exit;
}

$eventObject	=	($eventData['id']) ? DataManager::newObjectOfClass('TAgenda', $eventData['id']) : DataManager::newObjectOfClass('TAgenda');
$eventObject->__set('IdUsr'			, $_SESSION["_usrid"]);
$eventObject->__set('StartDate'		, $fechaInicio->format("Y-m-d H:i:s"));
$eventObject->__set('EndDate'		, $fechaFin->format("Y-m-d H:i:s"));
$eventObject->__set('Color'			, $eventData['color']);
$eventObject->__set('Title'			, $eventData['title']);
$eventObject->__set('Texto'			, $eventData['texto']);
$eventObject->__set('Url'			, (empty($eventData['url'])) ? '' : $eventData['url']	);
$eventObject->__set('Restringido'	, $restringido);
$eventObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
$eventObject->__set('LastUpdate'	, date("Y-m-d H:i:s"));
$eventObject->__set('Activa'		, 1);
if($eventData['id']){
	$ID = DataManager::updateSimpleObject($eventObject);
} else {
	$eventObject->__set('ID'		, $eventObject->__newID());	
	$ID = DataManager::insertSimpleObject($eventObject);
}
	
echo $ID; exit;

?>