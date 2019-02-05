<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 } 

$_nropedido	= empty($_REQUEST['nropedido']) ? 	0 	: $_REQUEST['nropedido'];
$_status	= empty($_REQUEST['status']) 	?	0	: $_REQUEST['status'];
$backURL	= empty($_REQUEST['backURL']) 	? 	'/pedidos/transfer/gestion/liquidacion/'	: $_REQUEST['backURL'];

$_detalles	= DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_nropedido); //DataManager::getDetallePedidoTransfer($_nropedido);
if ($_detalles) { 
	for( $k=0; $k < count($_detalles); $k++ ){		
		$_detalle 		= 	$_detalles[$k];	
		$_ptid			=	$_detalle['ptid'];
		$_ptliquidado	=	$_detalle['ptliquidado'];
		
		if ($_ptliquidado == $_status){
			$_cliobject	= DataManager::newObjectOfClass('TPedidostransfer', $_ptid);			
			$_cliobject->__set('Liquidado',	'LT');			
			$ID = DataManager::updateSimpleObject($_cliobject);
		}
	}
}

header('Location: '.$backURL);
?>