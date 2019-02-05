<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//*******************************************/
$_ctoid			=	(isset($_POST['ctoid']))	? $_POST['ctoid'] 		:	NULL;
$_origenid		=	(isset($_POST['origenid']))	? $_POST['origenid'] 	:	NULL; //id de la tabla de origen
$_origen		=	(isset($_POST['origen']))	? $_POST['origen']		: 	NULL; //es el nombre de la tabla de donde se origina el contacto (ejemplo Proveedor) el Destino, sería la tabla CONTACTOS
$_nombre		=	(isset($_POST['nombre']))	? $_POST['nombre'] 		: 	NULL;
$_apellido		=	(isset($_POST['apellido']))	? $_POST['apellido'] 	: 	NULL;
$_telefono		=	(isset($_POST['telefono']))	? $_POST['telefono']	: 	NULL;
$_interno		=	(isset($_POST['interno']))	? $_POST['interno'] 	: 	NULL;
$_correo		=	(isset($_POST['correo']))	? $_POST['correo'] 		: 	NULL;
$_activo		=	(isset($_POST['activo']))	? $_POST['activo']		: 	NULL;
$_sector		=	(isset($_POST['sector']))	? $_POST['sector']		: 	NULL;
$_puesto		=	(isset($_POST['puesto']))	? $_POST['puesto']		: 	NULL;
$_genero		=	(isset($_POST['genero']))	? $_POST['genero']		: 	NULL;
//*******************************************/
			
//******************//
//	Controlo Datos	// 
//******************//

if(empty($_origen)){
	echo "Indique un origen"; exit;	
}

if(empty($_sector)){
	echo "Indique un sector"; exit;	
}

if(empty($_puesto)){
	echo "Indique un puesto"; exit;	
}

if(empty($_nombre)){
	echo "Indique el nombre"; exit;	
}	

if(empty($_apellido)){
	echo "Indique el apellido"; exit;	
}

if(empty($_genero)){
	echo "Indique un genero"; exit;	
}

if(empty($_telefono) || !is_numeric($_telefono)){
	echo "Indique un telefono"; exit;	
}

if (!preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $_correo)) {
	echo "El correo es incorrecto"; exit;
}

$_ctoobject	= ($_ctoid) ? DataManager::newObjectOfClass('TContacto', $_ctoid) : DataManager::newObjectOfClass('TContacto');
$_ctoobject->__set('Origen', 		$_origen);
$_ctoobject->__set('IDOrigen', 		$_origenid);
$_ctoobject->__set('Domicilio', 	0);
$_ctoobject->__set('Sector', 		$_sector);
$_ctoobject->__set('Puesto', 		$_puesto);
$_ctoobject->__set('Nombre', 		$_nombre);
$_ctoobject->__set('Apellido', 		$_apellido);
$_ctoobject->__set('Genero', 		$_genero);
$_ctoobject->__set('Telefono', 		$_telefono);
$_ctoobject->__set('Interno', 		$_interno);
$_ctoobject->__set('Email', 		$_correo);
$_ctoobject->__set('Activo', 		1);


if ($_ctoid) {
	$ID = DataManager::updateSimpleObject($_ctoobject);
} else {
	$_ctoobject->__set('ID',		$_ctoobject->__newID());
	$ID	= DataManager::insertSimpleObject($_ctoobject);
}
 
echo '1'; exit;
 
?>