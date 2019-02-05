<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$empresa	= 	(isset($_POST['empresa']))		? $_POST['empresa'] : NULL;
$cadId		= 	(isset($_POST['cadid']))		? $_POST['cadid']	: NULL;
$cadena		= 	(isset($_POST['cadena']))		? $_POST['cadena']	: NULL;
$nombre		= 	(isset($_POST['nombre']))		? strtoupper($_POST['nombre'])	: NULL;
$idCuentas	= 	(isset($_POST['cuentaId']))		? $_POST['cuentaId'] 	: NULL;
$tiposCadena= 	(isset($_POST['tipoCadena']))	? $_POST['tipoCadena'] 	: NULL;

if(empty($empresa)){
	echo "No se registra empresa seleccionada."; exit;
}

if(empty($cadId)){
	//Si cadena es vacía, significa que se va a crear una cadena nueva.
	$cadena	= DataManager::dacLastId('cadenas', 'IdCadena');
	if(!$cadena){
		echo "No se pudo crear un n&uacute;mero de cadena."; exit;
	}
}

if(empty($nombre)){
	echo "Indique nombre de la cadena."; exit;
} else {
	//Comprobar que nombre de cadena no exista
	$cont 			= 0;
	$nombre 		= mb_strtoupper(trim(str_replace("  ", " ", $nombre)),"UTF-8" );
	$cadenas		= DataManager::getCadenas($empresa);
	foreach ($cadenas as $k => $cad) {
		$cadNombre	= 	$cad['NombreCadena'];
		if(!strcasecmp($nombre, $cadNombre)) {
			$cont++;
		}
	}
	if($cadId == 0) {$cont++;}
	if($cont > 1){ echo "La cadena ya existe.".$cont; exit; }	
}

if(count($idCuentas) < 1) {	
	echo "Debe relacionar una cuenta."; exit;
} else {
	//Controla duplicados
	if(count($idCuentas) != count(array_unique($idCuentas))){
		echo "Existen registros duplicados."; exit;
	}	
}

//controlar que CADA cuenta no exista duplicada en cnCadenas
foreach ($idCuentas as $k => $cadIdCuenta) {
	$cuentas	= DataManager::getCuentasCadena($empresa, NULL, $cadIdCuenta);
	if (count($cuentas)) {
		foreach ($cuentas as $k => $cta) {
			$idCuenta	= $cta['IdCliente'];
			$idCadena	= $cta['IdCadena'];			
			if($cadena != $idCadena){
				echo "La cuenta ".$idCuenta." ya existe registrada en la cadena ".$idCadena; exit; 	
			}
		}
	}	
}

//-------------------//
//	GUARDAR CAMBIOS  //
$cadObject	= ($cadId) ? DataManager::newObjectOfClass('TCadena', $cadId) : DataManager::newObjectOfClass('TCadena');
$cadObject->__set('Empresa'	, $empresa);
$cadObject->__set('Cadena'	, $cadena);
$cadObject->__set('Nombre'	, $nombre);

if($cadId) {
	DataManagerHiper::updateSimpleObject($cadObject, $cadId);
	DataManager::updateSimpleObject($cadObject);
	// MOVIMIENTO de Cuenta
	$tipoMov	= 'UPDATE';	
} else {
	$cadObject->__set('ID'	, $cadObject->__newID());
	
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin	
	$ID = DataManager::insertSimpleObject($cadObject);
	DataManagerHiper::insertSimpleObject($cadObject, $ID);
	
	//MOVIMIENTO de CUENTA
	$cadId		=  $ID;
	$tipoMov	= 'INSERT';
}

// MOVIMIENTO de CUENTA
$movimiento = 'ID_'.$cadId."_CADENA_".$cadena;

//------------------------------//	
// UPDATE CUENTAS RELACIONADAS  //
if(count($idCuentas)){
	$cuentas	=	DataManager::getCuentasCadena($empresa, $cadena);
	if (count($cuentas)) {
		foreach ($cuentas as $k => $cta) {	
			$idCtaRel	= 	$cta['cadid'];
			$idCuenta	= 	$cta['IdCliente'];			
			$id			= 	DataManager::getCuenta('ctaid', 'ctaidcuenta', $idCuenta, $empresa);	
			$nombre		= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, $empresa);			
			//Creo Array de Cuentas Relacionadas
			$arrayCadDDBB[] = $idCuenta;
			
			if (!in_array($idCuenta, $idCuentas)) { //DELETE de cadenas relacionadas
				$cadRelObject	=	DataManager::newObjectOfClass('TCadenaCuentas', $idCtaRel);
				$cadRelObject->__set('ID',	$idCtaRel);
				
				$ID = $cadRelObject->__get('ID');	
				DataManagerHiper::deleteSimpleObject($cadRelObject, $ID);				
				DataManager::deleteSimpleObject($cadRelObject);
				
			} else {
				//UPDATE
				$cadRelObject	=	DataManager::newObjectOfClass('TCadenaCuentas', $idCtaRel);	
				$cadRelObject->__set('TipoCadena', $tiposCadena[$k]);
				
				$ID = $cadRelObject->__get('ID');
				DataManagerHiper::updateSimpleObject($cadRelObject, $ID);					
				DataManager::updateSimpleObject($cadRelObject);	
			}
		}
		
		foreach ($idCuentas as $k => $cadIdCuenta) {
			if (!in_array($cadIdCuenta, $arrayCadDDBB)) {
				//INSERT
				$cadRelObject	=	DataManager::newObjectOfClass('TCadenaCuentas');	
				$cadRelObject->__set('Empresa'	, $empresa);	
				$cadRelObject->__set('Cadena'	, $cadena);
				$cadRelObject->__set('Cuenta'	, $cadIdCuenta);
				$cadRelObject->__set('TipoCadena', $tiposCadena[$k]);
				$cadRelObject->__set('ID'		, $cadRelObject->__newID());
				
				DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin
				$IDCuentaRel	= DataManager::insertSimpleObject($cadRelObject);	
				DataManagerHiper::insertSimpleObject($cadRelObject, $IDCuentaRel);
				
			}	
		}
	} else { //INSERT - Si no hay cuentas relacionadas, las crea		
		foreach ($idCuentas as $k => $cadIdCuenta) {			
			//INSERT
			$cadRelObject	=	DataManager::newObjectOfClass('TCadenaCuentas');	
			$cadRelObject->__set('Empresa'	, $empresa);
			$cadRelObject->__set('Cadena'	, $cadena);
			$cadRelObject->__set('Cuenta'	, $cadIdCuenta);
			$cadRelObject->__set('TipoCadena', $tiposCadena[$k]);
			$cadRelObject->__set('ID'		, $cadRelObject->__newID());
			
			DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin			
			$IDCuentaRel	= DataManager::insertSimpleObject($cadRelObject);			
			DataManagerHiper::insertSimpleObject($cadRelObject, $IDCuentaRel);
		}	
	}
} else { echo "No se registran cuentas relacionadas para editar"; exit; }


//-------------------//	
// MOVIMIENTO CADENA //
dac_registrarMovimiento($movimiento, $tipoMov, "TCadena", $cadId);

echo 1; exit;

?>