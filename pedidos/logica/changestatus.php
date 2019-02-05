<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}
 
$_nropedido		=	empty($_REQUEST['nropedido']) 	?	0 					: 	$_REQUEST['nropedido'];
$backURL		=	empty($_REQUEST['backURL']) 	? 	'/pedidos/pedidos/'	:	$_REQUEST['backURL'];
 
if ($_nropedido) {
	$_detalles	= 	DataManager::getPedidos(NULL, NULL, $_nropedido);
	if ($_detalles) { 	
		foreach ($_detalles as $k => $_detalle) {	
			$_idPedido		=	$_detalle["pid"];
			$_pedidoObject	= 	DataManager::newObjectOfClass('TPedido', $_idPedido);
			$_status		= 	($_pedidoObject->__get('Activo')) ? 0 : 1;
			$_pedidoObject->__set('Activo',	$_status);
			$ID = DataManager::updateSimpleObject($_pedidoObject);
		}
		
		//--------------//	
		//	MOVIMIENTO	//
		$movimiento = 'PEDIDO_CHANGESTATUS_'.$_nropedido;	
		$movTipo	= 'UPDATE';				
		dac_registrarMovimiento($movimiento, $movTipo, "TPedido", $_nropedido);
	}
}

header('Location: '.$backURL);
?>