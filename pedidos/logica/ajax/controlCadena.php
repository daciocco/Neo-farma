<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$usrAsignado	=	(isset($_POST['pwusrasignado']))? $_POST['pwusrasignado'] 	:	NULL;
$empresa		=	(isset($_POST['empselect']))	? $_POST['empselect'] 		:	NULL;
$laboratorio	=	(isset($_POST['labselect']))	? $_POST['labselect'] 		:	NULL;
$idCuenta		=	(isset($_POST['pwidcta']))		? $_POST['pwidcta'] 		: 	NULL;
$nroOrden		=	(isset($_POST['pworden']))		? $_POST['pworden'] 		: 	NULL;
$condPago		=	(isset($_POST['condselect']))	? $_POST['condselect'] 		: 	NULL;
$observacion	=	(isset($_POST['pwobservacion']))? $_POST['pwobservacion']	: 	NULL;
//-----------------------//
$idCondComercial=	(isset($_POST['pwidcondcomercial']))? $_POST['pwidcondcomercial'] :	NULL;
$propuesta		=	(isset($_POST['pwpropuesta']))	? $_POST['pwpropuesta'] 	: 	NULL;
$idPropuesta	=	(isset($_POST['pwidpropuesta']))? $_POST['pwidpropuesta'] 	: 	NULL;
$estado			=	(isset($_POST['pwestado']))		? $_POST['pwestado'] 		: 	NULL; //estado de la propuesta
//-----------------------//
$articulosIdArt	= 	(isset($_POST['pwidart'])) 		? $_POST['pwidart'] 		: NULL;
$articulosCant	= 	(isset($_POST['pwcant'])) 		? $_POST['pwcant'] 			: NULL;
$articulosPrecio= 	(isset($_POST['pwprecioart'])) 	? $_POST['pwprecioart'] 	: NULL;
$articulosB1	= 	(isset($_POST['pwbonif1'])) 	? $_POST['pwbonif1'] 		: NULL;
$articulosB2	= 	(isset($_POST['pwbonif2'])) 	? $_POST['pwbonif2'] 		: NULL;
$articulosD1	= 	(isset($_POST['pwdesc1'])) 		? $_POST['pwdesc1'] 		: NULL;
$articulosD2	= 	(isset($_POST['pwdesc2'])) 		? $_POST['pwdesc2'] 		: NULL;
//-----------------------//
$tipoPedido		= 	(isset($_POST['pwtipo'])) 		? $_POST['pwtipo'] 		: NULL;

//-----------------------//
// 	Controles Generales	 //
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/pedidos/logica/ajax/controlPedido.php" );

//Que cuenta pertenezca a una cadena
$cuentasCad	= DataManager::getCuentasCadena($empresa, NULL, $idCuenta);
if (count($cuentasCad)) {
	foreach ($cuentasCad as $j => $ctaCad) {
		$idCadenaCad	= 	$ctaCad['IdCadena'];
		$tipoCadena		= 	$ctaCad['TipoCadena'];
	}
} else {
	echo "La cuenta no corresponde a una cadena registrada."; exit;
}

echo "1"; exit;