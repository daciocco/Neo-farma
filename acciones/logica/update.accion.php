<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A"){
		$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	 	header("Location: $_nextURL");
 		exit;
}

 $_acid		= empty($_REQUEST['acid']) ? 0 : $_REQUEST['acid'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/acciones/': $_REQUEST['backURL'];
 
 $_nombre	= $_POST['acnombre'];
 $_sigla	= $_POST['acsigla'];
  
 $_SESSION['s_nombre']	=	$_nombre;
 $_SESSION['s_sigla']	=	$_sigla;
 
 if (empty($_nombre)) {
	$_goURL = sprintf("/pedidos/acciones/editar.php?acid=%d&sms=%d", $_acid, 1);
 	header('Location:' . $_goURL);
	exit;
 }
 
 if (empty($_sigla)) {
	$_goURL = sprintf("/pedidos/acciones/editar.php?acid=%d&sms=%d", $_acid, 2);
 	header('Location:' . $_goURL);
	exit;
 }
 
 $_acobject	= ($_acid) ? DataManager::newObjectOfClass('TAccion', $_acid) : DataManager::newObjectOfClass('TAccion');
 $_acobject->__set('Nombre', 		$_nombre);
 $_acobject->__set('Sigla', 		$_sigla);
 if ($_acid) {
	 $ID = DataManager::updateSimpleObject($_acobject);
 } else {
 	$_acobject->__set('ID',		$_acobject->__newID());
 	$_acobject->__set('Activa',	1);
 	$ID = DataManager::insertSimpleObject($_acobject);
 }

 unset($_SESSION['s_nombre']);
 unset($_SESSION['s_sigla']);
 
 header('Location:' . $backURL);
?>