<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
 }

 $_ptid				= empty($_REQUEST['ptid']) 	? 0 			: $_REQUEST['ptid'];
 $backURL			= empty($_REQUEST['backURL']) ? '/pedidos/transfer/': $_REQUEST['backURL'];
 
 if ($_ptid) {
	//lo siguiente borra todos los registros de pedidos que tengan el mismo nro de pedido
	$_tabla	= "pedidos_transfer";
	DataManager::deletefromtabla($_tabla, 'ptidpedido', $_ptid);
 }
 
header('Location: '.$backURL);
?>