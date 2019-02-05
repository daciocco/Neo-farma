<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }

 $_nropedido	=	empty($_REQUEST['nropedido'])	? 0	: $_REQUEST['nropedido'];
 $backURL		=	empty($_REQUEST['backURL']) ? '/pedidos/pedidos/	': $_REQUEST['backURL'];
 
 if ($_nropedido) {
	//lo siguiente borra todos los registros de pedidos que tengan el mismo nro de pedido
	$_tabla	= "pedido";
	DataManager::deletefromtabla($_tabla, 'pidpedido', $_nropedido);
 }
 
header('Location: '.$backURL);
?>