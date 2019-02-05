<?php 
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }

 $_zid		= empty($_REQUEST['zid']) ? 0 : $_REQUEST['zid'];
 $_nrozona	= empty($_REQUEST['nrozona']) ? 0 : $_REQUEST['nrozona'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/zonas/': $_REQUEST['backURL'];
 
 if ($_zid) {
	$_zobject	= DataManager::newObjectOfClass('TZonas', $_zid);
	$_zobject->__set('ID',	$_zid );
	$ID = DataManager::deleteSimpleObject($_zobject);
	
	//lo siguiente borra la relación que tenga esa zona con los vendedores
	$_tabla	= "zonas_vend";
	DataManager::deletefromtabla($_tabla, 'zona', $_nrozona);
 }
 
header('Location: '.$backURL);
?>