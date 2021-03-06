<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$arrayIdCond	=	empty($_POST['condid'])	?	0	:	$_POST['condid'];

if(!$arrayIdCond){
	echo "Seleccione condición para modificar."; exit;
}

if(count($arrayIdCond)){
	foreach ($arrayIdCond as $j => $condId) {
		if ($condId) {
			$condObject	= DataManager::newObjectOfClass('TCondicionComercial', $condId);
			$status		= ($condObject->__get('Activa')) ? 0 : 1;
			$condObject->__set('Activa', $status);
			DataManagerHiper::updateSimpleObject($condObject, $condId);
			DataManager::updateSimpleObject($condObject);
			
			// Registro MOVIMIENTO
			$movimiento	=	'ChangeStatus_a_'.$status;
			dac_registrarMovimiento($movimiento, 'UPDATE', 'TCondicionComercial', $condId);
			
		} else {
			echo "Error al consultar los registros."; exit;
		}		
	}
	echo "1"; exit;
} else {
	echo "Seleccione una condición."; exit;
}

echo "Error de proceso."; exit;
?>