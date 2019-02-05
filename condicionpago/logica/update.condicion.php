<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	echo "SESIÓN CADUCADA."; exit;
}
$condId		= 	(isset($_POST['condid']))		? 	$_POST['condid']		: 	NULL;
$codigo		= 	(isset($_POST['condcodigo']))	? 	$_POST['condcodigo']	: 	NULL;
$condTipo	= 	(isset($_POST['condtipo']))		? 	$_POST['condtipo']		: 	NULL;
$condCuotas	= 	(isset($_POST['condcuotas']))	? 	$_POST['condcuotas']	: 	NULL;
$condDecrece= 	(isset($_POST['conddecrece']))	? 	$_POST['conddecrece']	: 	NULL;

if (empty($condTipo)) {
	echo "Debe indicar un tipo de condición."; exit;
}

for($k=1; $k <= 5; $k++){
	$condTipos[$k] 		= (isset($_POST["condtipo$k"]))			? 	$_POST["condtipo$k"]		: 	NULL;
	$condDias[$k]		= (isset($_POST["conddias$k"]))			? 	$_POST["conddias$k"]		: 	NULL;
	$condPorcentajes[$k]= (isset($_POST["condporcentaje$k"]))	? 	$_POST["condporcentaje$k"]	: 	NULL;
	$condSignos[$k]		= (isset($_POST["condsigno$k"]))		? 	$_POST["condsigno$k"]		: 	NULL;
	$condFechasDec[$k]	= (isset($_POST["condfechadec$k"]))		? 	$_POST["condfechadec$k"]	: 	NULL;
	
	if(!empty($condTipos[$k])){
		if(empty($condDias[$k]) && $condDias[$k] != 0){
			echo "Debe indicar los días en cada condición."; exit;
		}		
		
		if($condDecrece == 'S'){
			if(!empty($condFechasDec[$k])){
				//calcular días que restan
				$dateFrom	= new DateTime();
				$dateTo		= new DateTime(dac_invertirFecha($condFechasDec[$k]));	
				$dateFrom->setTime($dateTo->format('H'), $dateTo->format('m'), $dateTo->format('s'));

				if($dateTo <  $dateFrom){
					echo "La fecha ".$condFechasDec[$k]." debe ser mayor al día de hoy."; exit;
				}

				$dateFrom ->modify('-1 day');			
				$interval = $dateFrom->diff($dateTo);
				if($interval->format('%R%a') != $condDias[$k]){
					echo "Los días para la fecha ".$condFechasDec[$k]." son incorrectos. <br> Deberían ser ".$interval->format('%R%a')." días.";  exit;
				}
			} else {			
				echo "Debe indicar días y fecha para cada condición que cree."; exit;
			}
		} else {
			if(!empty($condFechasDec[$k])){
				echo "Debe indicar la opción Decrece en S si desea usar fechas."; exit;
			}
		}
	} else {
		if(!empty($condDias[$k]) || !empty($condPorcentajes[$k]) || !empty($condSignos[$k]) || !empty($condFechasDec[$k])){
			echo "Debe indicar un tipo de condición para el día ".$condDias[$k]; exit;
		}
	}
	$condTipos[$k] 		= ($condTipos[$k]) 		? $condTipos[$k] 		: 0;
	$condDias[$k] 		= ($condDias[$k]) 		? $condDias[$k] 		: 0;	
	$condSignos[$k] 	= ($condSignos[$k]) 	? $condSignos[$k] 		: '';	
	$condPorcentajes[$k]= ($condPorcentajes[$k])? $condPorcentajes[$k] 	: '0.00';
	$condFechasDec[$k] 	= ($condFechasDec[$k]) 	? $condFechasDec[$k] 	: '01-01-2001';
}

//controlar que se cargue al menos una condición
if(count(array_unique(array_filter($condTipos))) == 0){
	echo "Debe completar al menos una condición."; exit;
}

//buscar días repetidos
if(count(array_filter($condDias)) != count(array_unique(array_filter($condDias)))){
	echo "Existen días repetidos."; exit;
}

//Controlar si ya existe dicha condición de pago
$cont = 0;
$condiciones	= DataManager::getCondicionesDePago(); 
if(count($condiciones)){
	foreach($condiciones as $k => $condicion) {
		if(	$condTipo == $condicion['condtipo']
		   && $condTipos[1] == $condicion['condtipo1']
		   && $condTipos[2] == $condicion['condtipo2']
		   && $condTipos[3] == $condicion['condtipo3']
		   && $condTipos[4] == $condicion['condtipo4']
		   && $condTipos[5] == $condicion['condtipo5']
		   && $condDias[1] == $condicion['Dias1CP']
		   && $condDias[2] == $condicion['Dias2CP']
		   && $condDias[3] == $condicion['Dias3CP']
		   && $condDias[4] == $condicion['Dias4CP']
		   && $condDias[5] == $condicion['Dias5CP']
		   && $condPorcentajes[1] == $condicion['Porcentaje1CP']
		   && $condPorcentajes[2] == $condicion['Porcentaje2CP']
		   && $condPorcentajes[3] == $condicion['Porcentaje3CP']
		   && $condPorcentajes[4] == $condicion['Porcentaje4CP']
		   && $condPorcentajes[5] == $condicion['Porcentaje5CP']
		  ){ 
			$cont++; 
		}
	}
	if($condId){
		if($cont > 1){echo "La condición de pago ya existe"; exit;}		
	} else {
		if($cont > 0){echo "La condición de pago ya existe"; exit;}		
	}
}

//busca un código de condiciones de pago disponible
if(!$condId) {
	$condicionesPago	= DataManager::getCondicionesDePago();
	foreach($condicionesPago as $k => $condPago){
		$codigos[] 	= $condPago['IdCondPago'];
	}	
	//busca valores intermedios perdidos
	$arrayRange 	= range(1,max($codigos));
	$missingValues 	= array_diff($arrayRange, $codigos);
	
	if(count($missingValues)){
		reset($missingValues);		
		$codigo = reset($missingValues); //$missingValues[0];
	} else {
		$codigo = max($codigos) + 1;
	}
}

$condObject	= ($condId) ? DataManager::newObjectOfClass('TCondicionPago', $condId) : DataManager::newObjectOfClass('TCondicionPago');
$condObject->__set('Codigo'		, $codigo);
$condObject->__set('Tipo'		, $condTipo);
$condObject->__set('Tipo1'		, $condTipos[1]);
$condObject->__set('Tipo2'		, $condTipos[2]);
$condObject->__set('Tipo3'		, $condTipos[3]);
$condObject->__set('Tipo4'		, $condTipos[4]);
$condObject->__set('Tipo5'		, $condTipos[5]);
$condObject->__set('Nombre'		, DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condTipos[1]));
$condObject->__set('Nombre2'	, DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condTipos[2]));
$condObject->__set('Nombre3'	, DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condTipos[3]));
$condObject->__set('Nombre4'	, DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condTipos[4]));
$condObject->__set('Nombre5'	, DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condTipos[5]));
$condObject->__set('Dias'		, $condDias[1]);
$condObject->__set('Dias2'		, $condDias[2]);
$condObject->__set('Dias3'		, $condDias[3]);
$condObject->__set('Dias4'		, $condDias[4]);
$condObject->__set('Dias5'		, $condDias[5]);
$condObject->__set('Porcentaje'	, $condPorcentajes[1]);
$condObject->__set('Porcentaje2',$condPorcentajes[2]);
$condObject->__set('Porcentaje3',$condPorcentajes[3]);
$condObject->__set('Porcentaje4',$condPorcentajes[4]);
$condObject->__set('Porcentaje5',$condPorcentajes[5]);
$condObject->__set('Signo'		, $condSignos[1]);
$condObject->__set('Signo2'		, $condSignos[2]);
$condObject->__set('Signo3'		, $condSignos[3]);
$condObject->__set('Signo4'		, $condSignos[4]);
$condObject->__set('Signo5'		, $condSignos[5]);
$condObject->__set('Cuotas'		, ($condCuotas)	? 'S' : 'N');
$condObject->__set('Cantidad'	, ($condCuotas)	? $condCuotas : 0);  
$condObject->__set('Decrece'	, $condDecrece);
$condObject->__set('FechaFinDec', dac_invertirFecha($condFechasDec[1]));
$condObject->__set('FechaFinDec2', dac_invertirFecha($condFechasDec[2]));
$condObject->__set('FechaFinDec3', dac_invertirFecha($condFechasDec[3]));
$condObject->__set('FechaFinDec4', dac_invertirFecha($condFechasDec[4]));
$condObject->__set('FechaFinDec5', dac_invertirFecha($condFechasDec[5]));
$condObject->__set('UsrUpdate'	, $_SESSION["_usrid"]);
$condObject->__set('Update'		, date("Y-m-d"));

if ($condId) {
	DataManagerHiper::updateSimpleObject($condObject, $condId);
	DataManager::updateSimpleObject($condObject);	
	
	//REGISTRA MOVIMIENTO
	$movTipo	= 'UPDATE';	
} else {
	$condObject->__set('UsrCreated'	, $_SESSION["_usrid"]);
	$condObject->__set('Created'	, date("Y-m-d"));
	$condObject->__set('ID'			, $condObject->__newID());
	$condObject->__set('Activa'		, 1);
	
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin
	$ID = DataManager::insertSimpleObject($condObject);
	DataManagerHiper::insertSimpleObject($condObject);
	
	//REGISTRA MOVIMIENTO
	$movTipo	= 'INSERT';	
	$condId		= $ID;
}

$movimiento = 'CONDICION_PAGO';
dac_registrarMovimiento($movimiento, $movTipo, "TCondicionPago", $condId);

echo "1"; exit;
?>