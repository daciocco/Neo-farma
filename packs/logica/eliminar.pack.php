<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }

 $_packid			= 	empty($_REQUEST['packid']) 			? 0 : $_REQUEST['packid'];
 $backURL			= 	empty($_REQUEST['backURL']) ? '/pedidos/packs/': $_REQUEST['backURL'];

 if ($_packid) {
	$_packobject	=	DataManager::newObjectOfClass('TPack', $_packid);
	$_packobject->__set('ID', 				$_packid );
	$ID = DataManager::deleteSimpleObject($_packobject);
	
	//lo siguiente borra los detalles que pueda tener el pack
	$_tabla	=	"pack_detalle";	
	DataManager::deletefromtabla($_tabla, 'pdpackid', $_packid);
 }
 
header('Location: '.$backURL);
?>