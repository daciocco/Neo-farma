<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$usrAsignado	=	(isset($_POST['pwusrasignado']))? $_POST['pwusrasignado'] 	:	NULL;
$empresa		=	(isset($_POST['empselect']))	? $_POST['empselect'] 		:	NULL;
$laboratorio	=	(isset($_POST['labselect']))	? $_POST['labselect'] 		:	NULL;
$condPago		=	(isset($_POST['condselect']))	? $_POST['condselect'] 		: 	NULL;
$idCondComercial=	(isset($_POST['pwidcondcomercial']))? $_POST['pwidcondcomercial']	:	NULL;
//Arrays
$idCuentas		=	(isset($_POST['pwidcta']))		? $_POST['pwidcta'] 		: 	NULL; 
$nroOrdenes		=	(isset($_POST['pworden']))		? $_POST['pworden'] 		: 	NULL;
$observaciones	=	(isset($_POST['pwobservacion']))? $_POST['pwobservacion']	: 	NULL;
$articulosIdArtS= 	(isset($_POST['pwidart']))		? unserialize(stripslashes($_POST['pwidart'])) 		:	NULL;
$articulosCantS	= 	(isset($_POST['pwcant']))		? unserialize(stripslashes($_POST['pwcant'])) 		:	NULL;
$articulosB1S 	= 	(isset($_POST['pwbonif1']))		? unserialize(stripslashes($_POST['pwbonif1'])) 	:	NULL;
$articulosB2S 	= 	(isset($_POST['pwbonif2']))		? unserialize(stripslashes($_POST['pwbonif2'])) 	:	NULL;
$articulosD1S 	= 	(isset($_POST['pwdesc1']))		? unserialize(stripslashes($_POST['pwdesc1'])) 		:	NULL;
$articulosD2S 	= 	(isset($_POST['pwdesc2']))		? unserialize(stripslashes($_POST['pwdesc2'])) 		:	NULL;

//Controlo cuentas repetidas
if(count($idCuentas) != count(array_unique($idCuentas))){
	echo "Existen cuentas duplicadas."; exit;
}

if(count($idCuentas) <= 1) {
	echo "El pedido debe contener al menos 2 cuentas de tipo cadena."; exit;
}

$cantidades = [];
$bonificados = [];
foreach ($idCuentas as $k => $idCuenta) {
	//Arrays por número de cuenta
	$articulosIdArt	= 	(isset($_POST['pwidart'.$idCuenta])) 		? $_POST['pwidart'.$idCuenta] 		: NULL;
	$articulosCant	= 	(isset($_POST['pwcant'.$idCuenta])) 		? $_POST['pwcant'.$idCuenta] 		: NULL;
	$articulosB1	= 	(isset($_POST['pwbonif1'.$idCuenta])) 		? $_POST['pwbonif1'.$idCuenta] 		: NULL;
	
	if(empty($articulosCant)) {
		echo "Indique una cantidad en el artículo ".$articulosIdArt[$k]." de la cuenta ".$idCuenta;	exit;
	}
	
	//Controlo artículos duplicados
	if(count($articulosIdArt) != count(array_unique($articulosIdArt))){
		echo "Existen artículos duplicados en la cuenta ".$idCuenta; exit;
	}
	
	//Recorro los artículos
	foreach ($articulosIdArt as $j => $artIdArt) {
		//sumos Cantidades por Artículo
		if(empty($articulosCant[$j])){
			echo "Indique una cantidad en el artículo ".$artIdArt." de la cuenta ".$idCuenta;	exit;
		}
		
		$cantidades[$artIdArt] = isset($cantidades[$artIdArt]) ? ($cantidades[$artIdArt] + $articulosCant[$j]) : $articulosCant[$j];
		
		//Sumo Bonificados por Artículo
		$articuloBonif = ($articulosB1[$j]) ? $articulosB1[$j] : 0;
		$bonificados[$artIdArt] = isset($bonificados[$artIdArt]) ? ($bonificados[$artIdArt] + $articuloBonif ) : $articuloBonif;
	}	
}


foreach ($articulosIdArtS as $k => $artIdArt) {	
	if($articulosCantS[$k] != $cantidades[$artIdArt]){
		echo "Las $articulosCantS[$k] unidades pedidas del artículo ".$artIdArt." no coindiden con las $cantidades[$artIdArt] cargadas.";	exit;
	}	
	
	//Si hay bonificación, calculos las unidades totales a bonificar.
	if(!empty($articulosB2S[$k]) && !empty($articulosB1S[$k])){
		$totalBonificables 	=  ($articulosCantS[$k] / $articulosB2S[$k]) * ($articulosB1S[$k] - $articulosB2S[$k]);
		if($totalBonificables != $bonificados[$artIdArt]) {
			echo "Las $totalBonificables unidades bonificadas del artículo ".$artIdArt." no coindiden con las $bonificados[$artIdArt] cargadas."; exit;
		}	
	} 
}

foreach ($idCuentas as $k => $idCuenta) {
	unset($articulosIdArt);
	unset($articulosCant);
	unset($articulosB1);
	unset($articulosB2);
	
	$nroOrden 		= 	$nroOrdenes[$k];
	$observacion	=	$observaciones[$k];
	$articulosIdArt	= 	(isset($_POST['pwidart'.$idCuenta])) 	? $_POST['pwidart'.$idCuenta] 		: NULL;
	$articulosCant	= 	(isset($_POST['pwcant'.$idCuenta])) 	? $_POST['pwcant'.$idCuenta] 		: NULL;
	$articulosPrecio= 	(isset($_POST['pwprecioart'.$idCuenta]))? $_POST['pwprecioart'.$idCuenta] 	: NULL;			
	$pwbonif1		=	(isset($_POST['pwbonif1'.$idCuenta])) 	? $_POST['pwbonif1'.$idCuenta] 		: NULL;	
	
	foreach($articulosCant as $j => $artCant){
		if(!empty($pwbonif1[$j])){
			$articulosB1[]	= $articulosCant[$j] + $pwbonif1[$j];
			$articulosB2[]	= $articulosCant[$j];	
		} else {
			$articulosB1[]	= '';
			$articulosB2[]	= '';
		}
	}
	
	$articulosD1	= 	(isset($_POST['pwdesc1'.$idCuenta]))	? $_POST['pwdesc1'.$idCuenta] 		: NULL;
	$articulosD2	= 	(isset($_POST['pwdesc2'.$idCuenta]))	? $_POST['pwdesc2'.$idCuenta] 		: NULL;
	
	$idPropuesta	=	0;
	$estado			=	0;
	
	$observacion	=	"Pedido CADENA. ".$observacion;
	
	//********************//
	// 	Generar Pedido	 //
	require($_SERVER['DOCUMENT_ROOT']."/pedidos/pedidos/logica/ajax/generarPedido.php" );
}
echo "1"; exit;

//**********************************//	
//	Controla si Existe Nro Pedido	//	en otro usuario
/*function dac_controlNroPedido() {
} */
?>