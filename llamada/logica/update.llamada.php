<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_origen 		= 	(isset($_POST['origen']))		? $_POST['origen'] 		: NULL;	
$_idorigen 		= 	(isset($_POST['idorigen']))		? $_POST['idorigen'] 	: NULL;
$_nroRel	 	= 	(isset($_POST['nroRel']))		? $_POST['nroRel'] 		: NULL;
$_telefono		= 	(isset($_POST['telefono']))		? $_POST['telefono'] 	: NULL;
$_tiporesultado	= 	(isset($_POST['tiporesultado']))? $_POST['tiporesultado'] : NULL;
$_resultado		= 	(isset($_POST['resultado']))	? $_POST['resultado'] 	: NULL;
$_observacion	= 	(isset($_POST['observacion']))	? $_POST['observacion'] : NULL;

if (empty($_origen ) || empty($_idorigen) || empty($_nroRel) || empty($_tiporesultado)) {
	echo "Error al leer los datos de registro."; exit;
}

if($_tiporesultado == 'incidencia'){
	switch($_resultado){
		case '0':
			echo "Debe indicar el tipo de incidencia"; exit;
			break;
		case 'otras':
			if (empty($_observacion)){
				echo "Debe indicar el motivo de la incidencia"; exit;
			}
			break;
		default: break;
	}
}

if(empty($_resultado)){
	echo "Debe indicar un resultado de llamada"; exit;
}

$_llamobject	=	DataManager::newObjectOfClass('TLlamada');
$_llamobject->__set('ID'			, $_llamobject->__newID());
$_llamobject->__set('Origen'		, $_origen);
$_llamobject->__set('IDOrigen'		, $_idorigen);
$_llamobject->__set('Telefono'		, $_telefono);
$_llamobject->__set('Fecha'			, date("Y-m-d H:i:s"));
$_llamobject->__set('TipoResultado'	, $_tiporesultado);		
$_llamobject->__set('Resultado'		, $_resultado);
$_llamobject->__set('Observacion'	, $_observacion);
$_llamobject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
$_llamobject->__set('LastUpdate'	, date("Y-m-d H:i:s"));
$_llamobject->__set('Activa'		, 0);		
$ID = DataManager::insertSimpleObject($_llamobject);

echo "1"; exit;

?>