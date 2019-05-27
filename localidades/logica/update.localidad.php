<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo "LA SESION HA CADUCADO."; exit;
}

$idLoc			=	(isset($_POST['idLoc']))		?	$_POST['idLoc']			: NULL;
$provincia		=	(isset($_POST['provincia']))	?	$_POST['provincia']		: NULL;
$localidad		=	(isset($_POST['localidad']))	?	$_POST['localidad']		: NULL;
$codigoPostal	=	(isset($_POST['codigopostal']))	?	$_POST['codigopostal']	: NULL;
$zonaVSelect	=	(isset($_POST['zonaVSelect']))	?	$_POST['zonaVSelect']	: NULL;
$zonaDSelect	=	(isset($_POST['zonaDSelect']))	?	$_POST['zonaDSelect']	: NULL;

$ctaIdWeb		=	(isset($_POST['ctaId']))		?	$_POST['ctaId']			: NULL;
$zonaVExcWeb	=	(isset($_POST['zonaVExc']))		?	$_POST['zonaVExc']		: NULL;


if(empty($provincia)){ echo "Debe indicar una provincia."; exit; }
if(empty($localidad)){ echo "Debe indicar una localidad."; exit; }
if(empty($codigoPostal) && $codigoPostal != 0){ echo "Debe indicar un código postal."; exit; }
if(empty($zonaVSelect)){ echo "Debe indicar una zona de venta."; exit; }
if(empty($zonaDSelect)){ echo "Debe indicar una zona de distribución."; exit; }

//Comprobar que dicha localidad no existe
$cont 			= 0;
$localidad 		= mb_strtoupper(trim(str_replace("  ", " ", $localidad)),"UTF-8" );
$localidades	= DataManager::getLocalidades(0, $provincia);
foreach ($localidades as $k => $loc) {
	$locNombre	= 	$loc['locnombre'];
	$locIdLoc	= 	$loc['locidloc'];
	if($idLoc == 0) {
		//Si localidad es nueva, solo debe uscar en ddbb.
		if(!strcasecmp($localidad, $locNombre)) {
			echo "La localidad ya existe."; exit;
		}
	} else {
		//Si localidad es editada, debe controlar bbdd <> a editar + la posibilidad a editar
		if(!strcasecmp($localidad, $locNombre) && $idLoc <> $locIdLoc) {
			echo "La localidad ya existe."; exit;
		}
	}
}

if($ctaIdWeb){
	if(count($ctaIdWeb) != count(array_unique($ctaIdWeb))){
		echo "Existen excepciones duplicadas"; exit;
	}	
	
	//La zona de excepción debe ser diferente a la zona de la localidad	
	if(in_array($zonaVSelect, $zonaVExcWeb)){
		echo "Las zonas de excepción deben ser diferentes a la localidad."; exit;
	}
}

//-----------//
//	GUARDAR  //
$localObject	= ($idLoc) ? DataManager::newObjectOfClass('TLocalidad', $idLoc) : DataManager::newObjectOfClass('TLocalidad');	
$localObject->__set('IDProvincia'	, $provincia);
$localObject->__set('Localidad'		, $localidad);
$localObject->__set('CP'			, $codigoPostal);
$localObject->__set('ZonaVenta'		, $zonaVSelect);
$localObject->__set('ZonaEntrega'	, $zonaDSelect);
if ($idLoc) {
	DataManagerHiper::updateSimpleObject($localObject, $idLoc);
	DataManager::updateSimpleObject($localObject);						
	//--------------//	
	//	MOVIMIENTO	//
	$movimiento = 'LOCALIDAD';	
	$movTipo	= 'UPDATE';	
} else {
	$localObject->__set('ID'		, $localObject->__newID());
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin
	$idLoc	= DataManager::insertSimpleObject($localObject);
	DataManagerHiper::insertSimpleObject($localObject, $idLoc);	
	//--------------//	
	//	MOVIMIENTO	//
	$movimiento = 'LOCALIDAD';	
	$movTipo	= 'INSERT';	
}
dac_registrarMovimiento($movimiento, $movTipo, "TLocalidad", $idLoc);

//CONTROLAR DIFERENCIA ENTRE EXCEPCIONES
$zeCtaIdDDBB= [];
$zeZonaDDBB	= [];
$zonasExpecion	= DataManager::getZonasExcepcion($idLoc);
if(count($zonasExpecion)){
	foreach ($zonasExpecion as $k => $ze) {
		$zeCtaIdDDBB[]	= $ze['zeCtaId'];
		$zeZonaDDBB[]	= $ze['zeZona'];
	}
}

//ACTUALIZAR EXCEPCIONES
if(count($zeCtaIdDDBB)){
	//Recooro DDBB
	for ($k=0; $k < count($zeCtaIdDDBB); $k++){		
		$zonaDefinida = 0;		
		if(count($ctaIdWeb)){
			//SI ZONA está en DDBB y WEB, es UPDATE
			if(in_array($zeCtaIdDDBB[$k], $ctaIdWeb)){
				$key = array_search($zeCtaIdDDBB[$k], $ctaIdWeb);
				//SOLO Si ZONA DDBB != A WEB es UPDATE
				if($zeZonaDDBB[$k] != $zonaVExcWeb[$key]){	
					DataManager::deletefromtabla('zona_excepcion', 'zeCtaId', $zeCtaIdDDBB[$k]);
					$campos	=	'zeIdLoc, zeCtaId, zeZona';
					$values	=	$idLoc.",".$zeCtaIdDDBB[$k].",".$zonaVExcWeb[$key];
					DataManager::insertToTable('zona_excepcion', $campos, $values);			
					//DEFINE  ÉSTA ZONA PARA LA CUENTA
					$zonaDefinida = $zonaVExcWeb[$key];
				}
			//SI ZONA está en DDBB y NO WEB, es DELETE
			} else {
				//DELETE
				DataManager::deletefromtabla('zona_excepcion', 'zeCtaId', $zeCtaIdDDBB[$k]);		
				//DEFINE ZONA ORIGINAL DE LA LOCALIDAD PARA LA CUENTA
				$zonaDefinida = $zonaVSelect;
			}
		} else {
			//DELETE
			DataManager::deletefromtabla('zona_excepcion', 'zeCtaId', $zeCtaIdDDBB[$k]);		
			//DEFINE ZONA ORIGINAL DE LA LOCALIDAD PARA LA CUENTA
			$zonaDefinida = $zonaVSelect;
		}

		//SI SE DEFINIO UNA ZONA PARA CUENTA, se modifica en la cuenta.
		if($zonaDefinida){
			$ctaObject	= DataManager::newObjectOfClass('TCuenta', $zeCtaIdDDBB[$k]);
			$ctaObject->__set('Zona', $zonaDefinida);
						
			$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $zeCtaIdDDBB[$k]);
			$ctaObjectHiper->__set('Zona', $zonaDefinida);
			DataManagerHiper::updateSimpleObject($ctaObjectHiper, $zeCtaIdDDBB[$k]);
			DataManager::updateSimpleObject($ctaObject);
			//----------------------------------	
		}
	}
}

//se procede a los INSERTAR si hay excepciones en WEB que no estén en DDBB
if(count($ctaIdWeb)){		
	for($k=0; $k<count($ctaIdWeb); $k++) {
		//si hay excepciones en DDBB
		if(empty($zeCtaIdDDBB)){ $insertar = TRUE;
		} else {
			if(!in_array($ctaIdWeb[$k], $zeCtaIdDDBB)){
				$insertar = TRUE;
			} else {
				$insertar = FALSE;
			}
		}		
		if($insertar){
			$ctaExiste	= DataManager::getZonasExcepcion(NULL, NULL, $ctaIdWeb[$k]);
			if(count($ctaExiste) > 0){
				$idCuenta	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaIdWeb[$k]);
				echo "La cuenta ".$idCuenta." ya existe cargada como excepción en otra localidad."; exit;
			}
			//INSERT
			$campos	=	'zeIdLoc, zeCtaId, zeZona';
			$values	=	$idLoc.",".$ctaIdWeb[$k].",".$zonaVExcWeb[$k];
			DataManager::insertToTable('zona_excepcion', $campos, $values);
			
			//--------------------------------
			//UPDATE EN ZONA DE LA CUENTA
			$ctaObject	= DataManager::newObjectOfClass('TCuenta', $ctaIdWeb[$k]);
			$ctaObject->__set('Zona' , $zonaVExcWeb[$k]);
			$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaIdWeb[$k]);
			$ctaObjectHiper->__set('Zona' , $zonaVExcWeb[$k]);
			
			DataManagerHiper::updateSimpleObject($ctaObjectHiper, $ctaIdWeb[$k]);
			DataManager::updateSimpleObject($ctaObject);	
			//----------------------------------	
		}		
	}
}

echo '1'; exit; ?>