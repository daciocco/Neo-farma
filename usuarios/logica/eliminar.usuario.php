<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }

 $_uid				= empty($_REQUEST['uid']) 	? 0 			: $_REQUEST['uid'];
 $backURL			= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL'];
 
 if ($_uid) {
	$_uobject	= DataManager::newObjectOfClass('TUsuario', $_uid);
	$_uobject->__set('ID', 				$_uid );
	$ID = DataManager::deleteSimpleObject($_uobject);
	
	//lo siguiente borra las zonas que pueda tener el vendedor
	$_tabla	= "zonas_vend";
	DataManager::deletefromtabla($_tabla, 'uid', $_uid);
 }
 
header('Location: '.$backURL);
?>