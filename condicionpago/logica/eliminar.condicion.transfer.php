<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;	
 }
 $_pag		= empty($_REQUEST['pag2']) 		? 0 : $_REQUEST['pag2'];
 $condId	= empty($_REQUEST['condid'])? 0 : $_REQUEST['condid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/condicionpago/'	: $_REQUEST['backURL'];

 if ($condId) {
	$_condobject	= DataManager::newObjectOfClass('TCondiciontransfer', $condId);
	$_condobject->__set('ID', $condId );
	$ID = DataManager::deleteSimpleObject($_condobject);
	 
	//REGISTRA MOVIMIENTO
	$movimiento = 'CONDICION_PAGO_TRANSFER';
	$movTipo	= 'DELETE';	 
	 dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $condId);
 }
 
header('Location: '.$backURL.'?pag2='.$_pag);
?>