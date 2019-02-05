<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_chequeid	= empty($_REQUEST['chequeid']) 	? 0 : $_REQUEST['chequeid'];
$_factid	= empty($_REQUEST['factid']) 	? 0 : $_REQUEST['factid'];
$_recid		= empty($_REQUEST['recid']) 	? 0 : $_REQUEST['recid'];
$_rendid	= empty($_REQUEST['rendid']) 	? 0 : $_REQUEST['rendid'];
//*******************************************//
$_factNumber 	= 	(isset($_GET['factNumber']))	? $_GET['factNumber'] 	: NULL;
$_cheqNumber 	= 	(isset($_GET['rowNumber']))		? $_GET['rowNumber']	: NULL;
$_observacion 	= 	(isset($_GET['observacion']))	? $_GET['observacion'] 	: NULL;
$_diferencia 	= 	(isset($_GET['diferencia']))	? $_GET['diferencia']	: NULL;
$_nro_rendicion	= 	(isset($_GET['nro_rendicion']))	? $_GET['nro_rendicion']: NULL;
$_nro_tal		= 	(isset($_GET['nro_tal']))		? $_GET['nro_tal']		: NULL;
$_nro_rec		= 	(isset($_GET['nro_rec']))		? $_GET['nro_rec']		: NULL;
//*******************************************//
$_nro_fact 		= 	json_decode(str_replace ('\"','"', $_GET['nrofactObject']), 	true);   
$_fecha_fact	= 	json_decode(str_replace ('\"','"', $_GET['fechaObj']), 			true);
$_nombrecli 	= 	json_decode(str_replace ('\"','"', $_GET['nombrecliObj']), 		true);
$_importe_dto	= 	json_decode(str_replace ('\"','"', $_GET['importe_dtoObj']), 	true);
$_importe_neto	= 	json_decode(str_replace ('\"','"', $_GET['importe_netoObj']), 	true);	
$_importe_bruto	= 	json_decode(str_replace ('\"','"', $_GET['importe_brutoObj']), 	true);
$_pago_efectivo	= 	json_decode(str_replace ('\"','"', $_GET['pago_efectObj']), 	true);
$_pago_transfer	= 	json_decode(str_replace ('\"','"', $_GET['pago_transfObj']), 	true);
$_pago_retencion= 	json_decode(str_replace ('\"','"', $_GET['pago_retenObj']), 	true);
$_bco_nombre	= 	json_decode(str_replace ('\"','"', $_GET['bco_nombreObj']), 	true);
$_bco_nrocheque	= 	json_decode(str_replace ('\"','"', $_GET['bco_nrochequeObj']), 	true);
$_bco_fecha		= 	json_decode(str_replace ('\"','"', $_GET['bco_fechaObj']), 		true);
$_bco_importe	= 	json_decode(str_replace ('\"','"', $_GET['bco_importeObj']), 	true); 
$nocheque		= 	0;
$_nva_rendicion =	0;

//---------------------------------------
//Controlo rendición activa (NO ENVIADA)
//---------------------------------------
$_rendicion		=	DataManager::getRendicion($_SESSION["_usrid"], $_nro_rendicion);
if (count($_rendicion)) {
	foreach ($_rendicion as $k => $_rend){
		$_rend		=	$_rendicion[$k];
		$_rendActiva=	$_rend['rendactiva'];
	}	

	if($_rendActiva != 1){
		echo "No se pueden enviar los datos con el Nro. de Rendición ".$_nro_rendicion." ya que fue enviado anteriormente."; exit;
	} 														
} else {
	//Si no existen datos, es que el nro de rendición no existe, por lo tanto es nueva
	$_nva_rendicion = 1; 
}
 
//-----------------------------
//Controlo campo de c/factura
//-----------------------------
for($i = 0; $i < $_factNumber; $i++) {
	//list($codigo, $nombre) = explode('-', $_nombrecli[$i]);
	$_nro_fact[$i]		= 	trim($_nro_fact[$i]);
	if (empty($_nro_fact[$i])){
		echo "Debe completar el Nro DE FACTURA de la Factura ".($i+1);  		
		exit;}

	$_fecha_fact[$i]	=	trim($_fecha_fact[$i]);			
	if (empty($_fecha_fact[$i])){
		echo "Debe completar correctamente la FECHA de la Factura ".($i+1); 	
		exit;}

	if ($_nombrecli[$i] == "Seleccione Cliente..." ){
		echo "Debe completar correctamente el NOMBRE DE CLIENTE de la Factura ".($i+1); 		
		exit;}

	$_importe_bruto[$i]	=	trim($_importe_bruto[$i]);
	if (empty($_importe_bruto[$i]) or $_importe_bruto[$i] <= 0){
		echo "Debe completar correctamente el TOTAL A PAGAR de la Factura ".($i+1); 			
		exit;}			
}

//******************************
//Controlo campo de c/cheques.
//******************************
//Si es un solo cheque (lleno o vacío)
if ($_cheqNumber == 1) {
	$_bco_nrocheque[0]	=	trim($_bco_nrocheque[0]);
	$_bco_fecha[0]		=	trim($_bco_fecha[0]);
	$_bco_importe[0]	=	trim($_bco_importe[0]);
	if ($_bco_nombre[0] == "Seleccione Banco..." && empty($_bco_nrocheque[0]) && empty($_bco_fecha[0]) && empty($_bco_importe[0])){
		$nocheque	=	1;
		//Si no hay cheques, controlo ingresos de las facturas
		for ($i = 0; $i < $_factNumber; $i++){	
			//Controla que haya alguna forma de pago en cada factura (ya que no se pagó con ningún cheque)
			$pagoTransfer	= (empty($_pago_transfer[$i])) ? 0 : $_pago_transfer[$i];
			$pagoRetencion	= (empty($_pago_retencion[$i])) ? 0 : $_pago_retencion[$i];
			$pagoEfectivo	= (empty($_pago_efectivo[$i])) ? 0 : $_pago_efectivo[$i];
			$_pagofact 		= $pagoEfectivo + $pagoTransfer + $pagoRetencion[$i];	

			if ($_pagofact == 0 || empty($_pagofact)){
				echo "Debe completar correctamente alguna FORMA DE PAGO para la factura ".($i+1); exit;}
		}			
	} else {	
		if ($_bco_nombre[0] == "Seleccione Banco..." || empty($_bco_nrocheque[0]) || empty($_bco_fecha[0]) || $_bco_importe[0] == ""){
			echo "Debe completar correctamente todos los CAMPOS DEL CHEQUE 1"; exit;}
	} 			
} else {	
	//Si son varios cheques 
	for ($i = 0; $i < $_cheqNumber; $i++){	
		$_bco_nrocheque[$i]	=	trim($_bco_nrocheque[$i]);
		$_bco_fecha[$i]		=	trim($_bco_fecha[$i]);
		$_bco_importe[$i]	=	trim($_bco_importe[$i]);
		//controla campos vacíos de cheques
		if ($_bco_nombre[$i] == "Seleccione Banco..." || empty($_bco_nrocheque[$i]) || empty($_bco_fecha[$i]) || empty($_bco_importe[$i])){
			echo "Debe completar correctamente todos los CAMPOS DEL CHEQUE ".($i+1); exit;}			
	}
}

//-------------------------------
//Controlo diferencia del recibo
//-------------------------------
if ($_diferencia != "" && $_diferencia != 0){
	$observ_longitud = strlen(trim($_observacion, ' '));
	if($observ_longitud == 0){
		echo "Debe completar correctamente el motivo de la DIFERENCIA."; exit;
	}		
}

//----------------	
//	PARA GUARDAR
//----------------
//-----------
//	cheques
//-----------
if ($nocheque == 0){ // si hay cheque	
	unset($_IDcheque);
	for($i = 0; $i < $_cheqNumber; $i++){			
		$date = $_bco_fecha[$i];
		list($dia, $mes, $ano) = explode('-', str_replace('/', '-', $date));	
		$fechacheq = $ano."-".$mes."-".$dia;

		$_chequeobject	=	($_chequeid) ? DataManager::newObjectOfClass('TCheques', $_chequeid) : DataManager::newObjectOfClass('TCheques');
		$_chequeobject->__set('Banco',	$_bco_nombre[$i]);
		$_chequeobject->__set('Numero',	$_bco_nrocheque[$i]);
		$_chequeobject->__set('Fecha', 	$fechacheq);
		$_chequeobject->__set('Importe',$_bco_importe[$i]);
		if ($_chequeid) {
			$ID = DataManager::updateSimpleObject($_chequeobject);
			$_IDcheque[] = $_chequeid;
		} else {
			$_chequeobject->__set('ID', $_chequeobject->__newID());
			$_IDcheque[] = DataManager::insertSimpleObject($_chequeobject);
		}
	}
}

//------------------//
//	facturas
//------------------//
unset($_IDfactura);	
for($i = 0; $i < $_factNumber; $i++){
	list($codigo, $nombre) = explode('-', $_nombrecli[$i]);

	$date = $_fecha_fact[$i];
	list($dia, $mes, $ano) = explode('-', str_replace('/', '-', $date));
	$fechafact = $ano."-".$mes."-".$dia;

	$pagoTransfer	=	(empty($_pago_transfer[$i])) ? 0 : $_pago_transfer[$i];
	$importeDesc	=	(empty($_importe_dto[$i])) ? 0 : $_importe_dto[$i];
	$pagoRetencion	=	(empty($_pago_retencion[$i])) ? 0 : $_pago_retencion[$i];
	$pagoEfectivo	=	(empty($_pago_efectivo[$i])) ? 0 : $_pago_efectivo[$i];

	$_facturaobject	=	($_factid) ? DataManager::newObjectOfClass('TFacturas', $_factid) : DataManager::newObjectOfClass('TFacturas');
	$_facturaobject->__set('Numero',	$_nro_fact[$i]);
	$_facturaobject->__set('Cliente', 	$codigo);
	$_facturaobject->__set('Fecha',		$fechafact);
	$_facturaobject->__set('Bruto',		$_importe_bruto[$i]);
	$_facturaobject->__set('Descuento',	$importeDesc);
	$_facturaobject->__set('Neto',		$_importe_neto[$i]);
	$_facturaobject->__set('Efectivo',	$pagoEfectivo);
	$_facturaobject->__set('Transfer',	$pagoTransfer);
	$_facturaobject->__set('Retencion',	$pagoRetencion);

	if ($_factid) {
		$ID = DataManager::updateSimpleObject($_facturaobject);
		$_IDfactura[] = $_factid;
	} else {
		$_facturaobject->__set('ID', $_facturaobject->__newID());
		$_IDfactura[] = DataManager::insertSimpleObject($_facturaobject);		
	}	
}

//--------------
//	fact_cheq
//--------------	 
if ($nocheque == 0){ // si hay cheque
	for($i = 0; $i < $_factNumber; $i++){	
		for($j = 0; $j < $_cheqNumber; $j++){	
			$_campos	=	'factid, cheqid';
			$_values	=	$_IDfactura[$i].",".$_IDcheque[$j];
			DataManager::insertToTable('fact_cheq', $_campos, $_values);
		}
	}
}

//----------
//	Recibo
//---------- 
$_reciboobject	=	($_recid) ? DataManager::newObjectOfClass('TRecibos', $_recid) : DataManager::newObjectOfClass('TRecibos');
$_reciboobject->__set('Numero'		, $_nro_rec);
$_reciboobject->__set('Talonario'	, $_nro_tal);
$_reciboobject->__set('Observacion'	, $_observacion);
$_reciboobject->__set('Diferencia'	, $_diferencia);
if ($_recid) {
	DataManager::updateSimpleObject($_reciboobject);
	$_IDrecibo = $_recid;
} else {
	$_reciboobject->__set('ID', $_reciboobject->__newID());
	$_IDrecibo = DataManager::insertSimpleObject($_reciboobject);
}

//-------------
//	rec_fact
//-------------
for($i = 0; $i < $_factNumber; $i++){	
	$_campos	=	'recid, factid';
	$_values	=	$_IDrecibo.",".$_IDfactura[$i];
	DataManager::insertToTable('rec_fact', $_campos, $_values);
}

//-------------
//	rendicion
//-------------
$rendObject	= ($_nva_rendicion == 0) ? DataManager::newObjectOfClass('TRendicion', $_rendid) : DataManager::newObjectOfClass('TRendicion');
$rendObject->__set('Numero'		, $_nro_rendicion); 
$rendObject->__set('IdUsr'		, $_SESSION["_usrid"]); 
$rendObject->__set('NombreUsr'	, $_SESSION["_usrname"]); 
$rendObject->__set('Activa'		, 1);
//Si el $_nro_rendicion es nuevo, deberá crearse una nueva rendición
if ($_nva_rendicion == 0) {
	DataManager::updateSimpleObject($rendObject);
	$_IDrendicion	=	$_rendid;
} else {
	$rendObject->__set('Retencion'	, '0.00'); 
	$rendObject->__set('Deposito'	, '0.00'); 
	$rendObject->__set('Envio'		, date("2001-01-01")); 
	$rendObject->__set('Fecha'		, date("Y-m-d"));
	$rendObject->__set('ID'			, $rendObject->__newID());
	$_IDrendicion = DataManager::insertSimpleObject($rendObject);
}

//-------------
//	rend_rec		
//-------------
$_campos	=	'rendid, recid';
$_values	=	$_IDrendicion.",".$_IDrecibo;
DataManager::insertToTable('rend_rec', $_campos, $_values);


echo "1";
	 
?>