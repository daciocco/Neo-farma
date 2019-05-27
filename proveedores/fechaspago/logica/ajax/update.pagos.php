<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$fecha 		= (isset($_POST['fecha'])) ? dac_invertirFecha($_POST['fecha']) : NULL ;
//Arrays 
$idFact	 	= explode("-", substr($_POST['idfact'], 1));
$empresa	= explode("-", substr($_POST['empresa'], 1));
$idProv	 	= explode("-", substr($_POST['idprov'], 1));
$nombre	 	= explode("-", substr($_POST['nombre'], 1));
$plazo	 	= explode("-", substr($_POST['plazo'], 1));
$fvto		= explode("-", substr($_POST['fechavto'], 1));
$tipo	 	= explode("-", substr($_POST['tipo'], 1));
$factNro	= explode("-", substr($_POST['factnro'], 1));
$fcbte		= explode("-", substr($_POST['fechacbte'], 1));
$saldo	 	= explode("-", substr($_POST['saldo'], 1));
$observacion= explode("-", substr($_POST['observacion'], 1));

/*
for($i=0; $i < count($fcbte); $i=$i+3){ 
	$fechaCbte[] = $fcbte[$i]."-".$fcbte[$i+1]."-".$fcbte[$i+2];
	$fechaVto[]  = $fvto[$i]."-".$fvto[$i+1]."-".$fvto[$i+2];	
}*/

if(empty($fecha)){
	echo "Error en la fecha seleccionada."; exit;	
}

//Recorre las facturas de la fecha actual
for($i=0; $i < count($idFact); $i++){ 
	
	//Controlar que no haya duplicados
	$cont = 0;
	for($j=0; $j < count($idFact); $j++){  
		if($empresa[$i] == $empresa[$j] && $idProv[$i] == $idProv[$j] && $tipo[$i] == $tipo[$j] && $factNro[$i] == $factNro[$j]){ 
			$cont++;
			if($cont > 1) {
				echo "El siguiente registro está repetido: </br> Emp: ".$empresa[$i]." Código: ".$idProv[$i]." Tipo: ".$tipo[$i]." Nro: ".$factNro[$i]; exit;
			}
		}
	}
	
	//Controlar que la factura no exista cargada en otras fechas diferente a la actual
	$facturas = DataManager::getFacturasProveedor($empresa[$i], NULL, NULL, $tipo[$i], $factNro[$i], $idProv[$i]);
	if($facturas) {
		foreach ($facturas as $k => $fact) {
			$factFechaCbte = $fact['factfechapago'];
			
			if($factFechaCbte != $fecha && $factFechaCbte != '2001-01-01'){
				echo "El comprobante $tipo[$i] - $factNro[$i] del proveedor $idProv[$i] ya existe cargado en la fecha ".dac_invertirFecha($factFechaCbte); exit;
			}			
		}
	}
}

//Busco registros ya guardados en ésta fecha y pongo en cero si no están en el array (si fueron eliminados)
$facturasPago	=	DataManager::getFacturasProveedor(NULL, 1, $fecha);
if($facturasPago) {
	foreach ($facturasPago as $k => $factPago) {
		$idFactura	= $factPago['factid'];
		$activa		= $factPago['factactiva'];
		//si el idfact NO aparece en el array, se hace un UPDATE para ponerlo a cero
		if (!in_array($idFactura, $idFact)) {
			$factObject	= DataManager::newObjectOfClass('TFacturaProv', $idFactura);
			$factObject->__set('Pago'		, '2001-01-01');		
			$factObject->__set('Observacion', ' ');
			$factObject->__set('Activa'		, 0);	
			$ID = DataManager::updateSimpleObject($factObject);
		}
	}
}


//----------------------------------
//	Edicion de las fact como pagos 	
for($i=0; $i < count($idFact); $i++){ 
	if ($idFact[$i]){
		$factObject	= DataManager::newObjectOfClass('TFacturaProv', $idFact[$i]);
		$factObject->__set('Pago'		, $fecha);		
		$factObject->__set('Observacion', $observacion[$i]);
		$factObject->__set('Activa'		, 1);	
		$ID = DataManager::updateSimpleObject($factObject);		
	}/* else {
		echo "Error al ingresar los datos de las facturas. Verifique el resultado."; exit;
	}*/
}
	
echo "1"; exit;

?>