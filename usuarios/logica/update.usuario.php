<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A"){
		$_nextURL = sprintf("%s", "/pedidos/login/index.php");
		echo $_SESSION["_usrol"];
	 	header("Location: $_nextURL");
 		exit;
}

$_uid		= empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL'];

$_nombre		= $_POST['unombre'];
$_usuario		= $_POST['uusuario'];
$_password		= $_POST['upassword'];
$_passwordbis	= $_POST['upasswordbis'];
$_dni			= $_POST['udni'];
$_email			= $_POST['uemail'];
$_rol			= $_POST['urol'];
$_obs			= $_POST['uobs'];

$_SESSION['s_nombre'] 		= $_nombre;
$_SESSION['s_email'] 		= $_email;
$_SESSION['s_usuario']		= $_usuario;
$_SESSION['s_password']		= $_password;
$_SESSION['s_passwordbis']	= $_passwordbis;
$_SESSION['s_dni']			= $_dni;
$_SESSION['s_rol']			= $_rol;
$_SESSION['s_obs']			= $_obs;

if (empty($_nombre)) {
	$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 1);
	header('Location:' . $_goURL);
	exit;
}

if (!empty($_usuario)) {
$_loginid = DataManager::getIDByField('TUsuario','ulogin', $_usuario);
if ($_loginid && ($_loginid != $_uid)) {
	$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 2);
	header('Location:' . $_goURL);
	exit;
}
} else {
	$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 2);
	header('Location:' . $_goURL);
	exit;
}

if ((0 != strcmp($_password,$_passwordbis)) || empty($_password)) {
	$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 3);
	header('Location:' . $_goURL);
	exit;
}

if (empty($_dni) || !is_numeric($_dni)) {
	$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 4);
	header('Location:' . $_goURL);
	exit;
	
} else {
	$_loginid = DataManager::getIDByField('TUsuario','udni', $_dni);
	if ($_loginid && ($_loginid != $_uid)) {
		$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 4);
		header('Location:' . $_goURL);
		exit;
	}
	
}
/*
if (empty($_dni) || !is_numeric($_dni)) {
$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 4);
header('Location:' . $_goURL);
exit;
}
*/
if (!preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $_email )) {
	$_goURL = sprintf("/pedidos/usuarios/editar.php?uid=%d&sms=%d", $_uid, 5);
	header('Location:' . $_goURL);
	exit;
}

//Si ya existe una clave, la lee de la ddbb cifrada y al querer grabar la vuelve a cifrar dando errores.
//Por eso mismo hago el siguiente control previo.
if (strlen($_password) <= 15){$_password = md5($_password);}

$_usrobject	= ($_uid) ? DataManager::newObjectOfClass('TUsuario', $_uid) : DataManager::newObjectOfClass('TUsuario');
$_usrobject->__set('Nombre'	, $_nombre);
$_usrobject->__set('Email'	, $_email);
$_usrobject->__set('Login'	, $_usuario);
$_usrobject->__set('Clave'	, $_password);
$_usrobject->__set('Dni'	, $_dni);
$_usrobject->__set('Rol'	, $_rol);
$_usrobject->__set('Obs'	, $_obs);
if ($_uid) {
 	$ID = DataManager::updateSimpleObject($_usrobject);
} else {
	$_usrobject->__set('ID'		, $_usrobject->__newID());
	$_usrobject->__set('Activo'	, 1);
	$ID = DataManager::insertSimpleObject($_usrobject);
}

unset($_SESSION['s_nombre']);
unset($_SESSION['s_email'] );
unset($_SESSION['s_usuario']);
unset($_SESSION['s_password']);
unset($_SESSION['s_passwordbis']);
unset($_SESSION['s_dni']);
unset($_SESSION['s_rol']);
unset($_SESSION['s_obs']);

header('Location:' . $backURL);
?>