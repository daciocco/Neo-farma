<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
 }

 $condId	= empty($_REQUEST['condid']) ? 0 : $_REQUEST['condid'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/condicionpago/': $_REQUEST['backURL'];
 
 $_codigo	= $_POST['condcodigo'];
 $_nombre	= $_POST['condnombre'];
 $_dias		= $_POST['conddias'];
 $_porc		= $_POST['condporcentaje'];
  
 $_SESSION['s_codigo']	=	$_codigo;
 $_SESSION['s_nombre']	=	$_nombre;
 $_SESSION['s_dias']	=	$_dias;
 $_SESSION['s_porc']	=	$_porc;
 
  
if (empty($_codigo)) {
	$_goURL = sprintf("/pedidos/condicionpago/editar_transfer.php?condid=%d&sms=%d", $condId, 1);
 	header('Location:' . $_goURL);
	exit;
}
 
if (empty($_nombre)) {
	$_goURL = sprintf("/pedidos/condicionpago/editar_transfer.php?condid=%d&sms=%d", $condId, 2);
 	header('Location:' . $_goURL);
	exit;
}

$_condicion	= DataManager::getCondicionDePagoTransfer('condid', 'condcodigo', $_codigo);
if($_condicion){
	if($_condicion != $condId){
		$_goURL = sprintf("/pedidos/condicionpago/editar_transfer.php?condid=%d&sms=%d", $condId, 3);
		header('Location:' . $_goURL);
		exit;
	}
}
 
$_condobject	= ($condId) ? DataManager::newObjectOfClass('TCondiciontransfer', $condId) : DataManager::newObjectOfClass('TCondiciontransfer');
$_condobject->__set('Codigo', 		$_codigo);
$_condobject->__set('Empresa', 	1);
$_condobject->__set('Nombre', 		$_nombre);
$_condobject->__set('Dias', 		$_dias);
$_condobject->__set('Porcentaje',	$_porc);

//--------------------
//	MOVIMIENTO
//--------------------
$movimiento = 	'CONDICION_PAGO_TRANSFER';
 if ($condId) {
	 DataManager::updateSimpleObject($_condobject);	 
	 //REGISTRA MOVIMIENTO	
	$movTipo	= 'UPDATE';		
 } else {
 	$_condobject->__set('ID',		$_condobject->__newID());
 	$_condobject->__set('Activa',	1);
 	$ID = DataManager::insertSimpleObject($_condobject);	 
	//REGISTRA MOVIMIENTO
	$movTipo	= 'INSERT';
	 $condId	=	$ID;
 }
dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $condId);

 unset($_SESSION['s_codigo']);
 unset($_SESSION['s_nombre']);
 unset($_SESSION['s_dias']);
 unset($_SESSION['s_porc']);
 
 header('Location:' . $backURL);
?>