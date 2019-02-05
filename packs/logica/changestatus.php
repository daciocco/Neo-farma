<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

 $_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
 $_packid	= empty($_REQUEST['packid']) 	? 0 			: $_REQUEST['packid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/packs/': $_REQUEST['backURL'];

 if ($_packid) {
	$_pack		= DataManager::newObjectOfClass('TPack', $_packid);
	$_status	= ($_pack->__get('Activa')) ? 0 : 1;
	$_pack->__set('Activa', $_status);
	$ID 		= DataManager::updateSimpleObject($_pack);
	
	//Si un pack se REACTIVA. Se crea una noticia web de activación de pack
	if($_status == 1){
	  $_noticiaobject	=	DataManager::newObjectOfClass('TNoticia');
	  $_noticiaobject->__set('Titulo', 			"Nuevo Pack Activado");
	  $_noticiaobject->__set('Fecha', 			date("Y-m-d"));
	  $_noticiaobject->__set('Descripcion',		"Un nuevo pack fue activado para realizar pedidos web");
	  $_noticiaobject->__set('ID',				$_noticiaobject->__newID());
	  $_noticiaobject->__set('Activa', 			1);
	  $ID = DataManager::insertSimpleObject($_noticiaobject);
	}
 }

header('Location: '.$backURL.'?pag='.$_pag);
?>