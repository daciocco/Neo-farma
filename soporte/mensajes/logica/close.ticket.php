<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo "SU SESIÓN HA EXPIRADO."; exit;
}

$tkid	=	empty($_POST['tkid']) ? 0 	: $_POST['tkid'];
  
if (empty($tkid)) {
	echo "Error al seleccionar el ticket."; exit;
}

$objectTicket	= DataManager::newObjectOfClass('TTicket', $tkid);
$objectTicket->__set('UsrUpdate'	, $_SESSION["_usrid"]);
$objectTicket->__set('DateUpdate'	, date("Y-m-d H:m:s"));
$objectTicket->__set('Estado'		, 0);
$objectTicket->__set('Activo'		, 1);
$IDTicket = DataManager::updateSimpleObject($objectTicket);

echo "1"; exit;
 
?>