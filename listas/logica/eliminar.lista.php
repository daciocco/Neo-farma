<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }

 $_listaid	= 	empty($_REQUEST['listaid']) ? 0 				: $_REQUEST['listaid'];
 $backURL	= 	empty($_REQUEST['backURL']) ? '/pedidos/listas/': $_REQUEST['backURL'];

 if ($_listaid) {
	$_listaobject	=	DataManager::newObjectOfClass('TLista', $_listaid);
	$_listaobject->__set('ID', 				$_listaid);
	$ID = DataManager::deleteSimpleObject($_listaobject);
	
	//lo siguiente borra los detalles de clientes y artículos de la lista
	$_tabla_cli	= 	'lista_esp_cliente';
 	$_tabla_art	= 	'lista_esp_art';
	DataManager::deletefromtabla($_tabla_cli, 'leclistaid', $_listaid);
	DataManager::deletefromtabla($_tabla_art, 'lealistaid', $_listaid);	
 }
 
header('Location: '.$backURL);
?>