<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo "SU SESIÓN HA EXPIRADO."; exit;
}

$backURL	= 	empty($_REQUEST['backURL']) ? '/pedidos/soporte/tickets/nuevo/editar.php': $_REQUEST['backURL'];
$idSector	=	empty($_POST['tkidsector']) 	? 0 	: $_POST['tkidsector'];
$motivo		=	empty($_POST['tkmotivo']) 	? "" 	: $_POST['tkmotivo'];
$mensaje	=	empty($_POST['tkmensaje']) 	? "" 	: $_POST['tkmensaje'];
$correo		=	empty($_POST['tkcopia']) 	? "" 	: $_POST['tkcopia'];
  
if (empty($idSector)) {
	echo "Error al seleccionar un motivo de consulta."; exit;
}

if (empty($motivo)) {
	echo "Seleccione un motivo de servicio."; exit;
} 

if (empty($mensaje)) {
	echo "Indique un mensaje."; exit;
}

if (!empty($correo)) {
	$correo = trim($correo, ' ');
	if (!dac_validateMail($correo)) {
		echo "El corr&eacute;o es incorrecto."; exit;
	}
}

$imagenNombre	=	$_FILES["imagen"]["name"]; 
$imagenPeso		= 	$_FILES["imagen"]["size"]; 
if ($imagenPeso != 0){
	if($imagenPeso > MAX_FILE){ 
		echo "El archivo no debe superar los 4 MB"; exit;
	}
}

if ($imagenPeso	 != 0){
	if(dac_fileFormatControl($_FILES["imagen"]["type"], 'imagen')){
		$ext	=	explode(".", $imagenNombre);
		$name	= 	dac_sanearString($ext[0]);	
	} else {
		echo "La imagen debe ser .JPG o .PDF"; exit;		
	}		
}

$objectTicket	= DataManager::newObjectOfClass('TTicket');
$objectTicket->__set('Sector'		, $idSector);
$objectTicket->__set('IDMotivo'		, $motivo);
$objectTicket->__set('Prioridad'	, 1);
$objectTicket->__set('Estado'		, 1); //1 ACTIVO //2PENDIENTE //0 CERRADO
$objectTicket->__set('UsrCreated'	, $_SESSION["_usrid"]);
$objectTicket->__set('UsrUpdate'	, $_SESSION["_usrid"]);
$objectTicket->__set('LastUpdate'	, date("Y-m-d H:m:s"));
$objectTicket->__set('DateCreated'	, date("Y-m-d H:m:s"));
$objectTicket->__set('Activo'		, 1);
$objectTicket->__set('ID'			, $objectTicket->__newID());
$IDTicket = DataManager::insertSimpleObject($objectTicket);

$objectMsg	= DataManager::newObjectOfClass('TTicketMensaje');
$objectMsg->__set('IDTicket'	, $IDTicket);
$objectMsg->__set('Descripcion'	, $mensaje);
$objectMsg->__set('UsrCreated'	, $_SESSION["_usrid"]);
$objectMsg->__set('DateCreated'	, date("Y-m-d H:m:s"));
$objectMsg->__set('Activo'		, 1);
$objectMsg->__set('ID'			, $objectMsg->__newID());
$IDMsg = DataManager::insertSimpleObject($objectMsg);

echo "1"; exit;
 
?>