<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$nroPedido			= empty($_POST['nroPedido']) 	? 0	: $_POST['nroPedido'];
$usrAsignado		= empty($_POST['ptAsignar'])	? NULL : $_POST['ptAsignar'];

if(empty($usrAsignado)){
	echo "Debe indicar usuario asignado.";  exit;
}

if ($nroPedido) {
	$detalles	= DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $nroPedido);  //DataManager::getDetallePedidoTransfer($nroPedido);
	if ($detalles) { 
		foreach ($detalles as $k => $det) {	
			$ptId = $det['ptid'];
			if($ptId) {
				$ptObject	= DataManager::newObjectOfClass('TPedidosTransfer', $ptId);
				$ptObject->__set('ParaIdUsr', $usrAsignado);
				DataManagerHiper::updateSimpleObject($ptObject, $ptId);
				DataManager::updateSimpleObject($ptObject);
				
				//MOVIMIENTO de CUENTA
				$movimiento = 'REASIGNA_USR_'.$usrAsignado;	
				$movTipo	= 'UPDATE';
				dac_registrarMovimiento($movimiento, $movTipo, 'TPedidosTransfer', $ptId);
				
			}
		}
	}
}

echo "1"; exit;
?>