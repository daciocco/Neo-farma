<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$arrayIdCond=	empty($_POST['condid'])	?	0	:	$_POST['condid'];
$backURL	= 	'/pedidos/condicion/';

if(!$arrayIdCond) {
	echo "Seleccione condición para modificar."; exit;
}

//si no es array, lo convierte
if(!is_array($arrayIdCond)){
	$arrayIdCond = array($arrayIdCond); 
}

//Inicio
$dtInicio	=	new DateTime("now");
if($dtInicio->format("m") == 12){
	$ano	=	$dtInicio->format("Y") + 1;
}
$ano	=	$dtInicio->format("Y");
$mes	=	$dtInicio->format("m") + 1;	
$dia 	= 	"01";
$dtInicio->setDate($ano , $mes , $dia);
//echo $dtInicio->format("Y-m-d");

//FIN
$dtFin  = 	clone $dtInicio;
$dia	=	 date( 't', strtotime( $dtFin->format("Y-m-d")));
$dtFin->setDate($ano , $mes , $dia);
//echo $dtFin->format("Y-m-d");

foreach ($arrayIdCond as $j => $condId) {
	//Consulto los datos de dicha condición comercial para duplcar
	$condicion	=	DataManager::getCondicion($condId);
	if(count($condicion) != 1){
		echo "Error al consultar la condición."; exit;	
	}
	
	//*************************//
	// Lee y Duplica Condición //
	//*************************//
	if ($condId) {
		$condObject 	= DataManager::newObjectOfClass('TCondicionComercial', $condId);
		$condIdEmp 		= $condObject->__get('Empresa');
		$condIdLab 		= $condObject->__get('Laboratorio');
		$condCuentas	= $condObject->__get('Cuentas');
		$condNombre		= $condObject->__get('Nombre');	
		$condTipo		= $condObject->__get('Tipo');
		$condCondPago	= $condObject->__get('CondicionPago');
		$condcCantMin	= $condObject->__get('CantidadMinima');
		$condMinRef		= $condObject->__get('MinimoReferencias');
		$condMinMon		= $condObject->__get('MinimoMonto');
		$condObservacion= $condObject->__get('Observacion');
		
		$habitualCant	= $condObject->__get('Cantidad');
		$habitualBonif1	= $condObject->__get('Bonif1');
		$habitualBonif2	= $condObject->__get('Bonif2');
		$habitualDesc1	= $condObject->__get('Desc1');
		$habitualDesc2	= $condObject->__get('Desc2');
		//**************************//	
		//	CREO condición nueva	//
		//**************************//  
		$condObjectDup	=	DataManager::newObjectOfClass('TCondicionComercial');
		$condObjectDup->__set('Empresa'				, $condIdEmp);
		$condObjectDup->__set('Laboratorio'			, $condIdLab);
		$condObjectDup->__set('Cuentas'				, $condCuentas);
		$condObjectDup->__set('Nombre'				, $condNombre);
		$condObjectDup->__set('Tipo'				, $condTipo);
		$condObjectDup->__set('CondicionPago'		, $condCondPago);
		$condObjectDup->__set('CantidadMinima'		, $condcCantMin);
		$condObjectDup->__set('MinimoReferencias'	, $condMinRef);
		$condObjectDup->__set('MinimoMonto'			, $condMinMon);
		$condObjectDup->__set('FechaInicio'			, $dtInicio->format("Y-m-d"));
		$condObjectDup->__set('FechaFin'			, $dtFin->format("Y-m-d")); 
		$condObjectDup->__set('Observacion'			, $condObservacion);		
		
		$condObjectDup->__set('Cantidad'			, $habitualCant);
		$condObjectDup->__set('Bonif1'				, $habitualBonif1);
		$condObjectDup->__set('Bonif2'				, $habitualBonif2);
		$condObjectDup->__set('Desc1'				, $habitualDesc1);
		$condObjectDup->__set('Desc2'				, $habitualDesc2);		
		
		$condObjectDup->__set('UsrUpdate'			, $_SESSION["_usrid"]);
		$condObjectDup->__set('LastUpdate'			, date("Y-m-d")); 
		$condObjectDup->__set('ID'					, $condObjectDup->__newID());
		$condObjectDup->__set('Activa'				, 0); //Inactivo para modificar antes de activar
		$ID = DataManager::insertSimpleObject($condObjectDup);

		//******************//	
		// cargo artículos  //
		//******************//
		$articulosCond = DataManager::getCondicionArticulos($condId);
		if (count($articulosCond)) {		 
			foreach ($articulosCond as $k => $detArt) {																		
				$detArt 	= $articulosCond[$k];
				$detIdart	= $detArt['cartidart'];
				$detPrecio	= $detArt["cartprecio"];
				$detDigitado= $detArt["cartpreciodigitado"];
				$detCantMin	= empty($detArt['cartcantmin'])	?	0	:	$detArt['cartcantmin'];
				$detOAM		= $detArt["cartoam"];

				//******************************//	
				//	Duplico Detalle de Artículo	//
				//******************************//  
				$condArtObject	=	DataManager::newObjectOfClass('TCondicionComercialArt');						
				$condArtObject->__set('Condicion'		, $ID);
				$condArtObject->__set('Articulo'		, $detIdart);
				$condArtObject->__set('Precio'			, $detPrecio);
				$condArtObject->__set('Digitado'		, $detDigitado);
				$condArtObject->__set('CantidadMinima'	, $detCantMin);
				$condArtObject->__set('OAM'				, $detOAM);
				$condArtObject->__set('Activo'			, 0);
				$condArtObject->__set('ID'				, $condArtObject->__newID());
				$IDArt = DataManager::insertSimpleObject($condArtObject);

				//**********************************//	
				//	Creo Detalle de Bonificaciones	//
				//**********************************//  
				$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $detIdart);
				if (count($articulosBonif)) {								
					foreach ($articulosBonif as $j => $artBonif) {			
						$artBonifCant	= empty($artBonif['cbcant'])	?	0	:	$artBonif['cbcant'];
						$artBonifB1		= empty($artBonif['cbbonif1'])	?	0	:	$artBonif['cbbonif1'];
						$artBonifB2		= empty($artBonif['cbbonif2'])	?	0	:	$artBonif['cbbonif2'];	
						$artBonifD1		= empty($artBonif['cbdesc1'])	?	0	:	$artBonif['cbdesc1'];	
						$artBonifD2		= empty($artBonif['cbdesc2'])	?	0	:	$artBonif['cbdesc2'];

						$condArtBonifObject	=	DataManager::newObjectOfClass('TCondicionComercialBonif');						
						$condArtBonifObject->__set('Condicion'		, $ID);
						$condArtBonifObject->__set('Articulo'		, $detIdart);		
						$condArtBonifObject->__set('Cantidad'		, $artBonifCant);
						$condArtBonifObject->__set('Bonif1'			, $artBonifB1);
						$condArtBonifObject->__set('Bonif2'			, $artBonifB2);
						$condArtBonifObject->__set('Desc1'			, $artBonifD1);
						$condArtBonifObject->__set('Desc2'			, $artBonifD2);
						$condArtBonifObject->__set('Activo'			, 0);
						$condArtBonifObject->__set('ID'				, $condArtBonifObject->__newID());
						$IDArt = DataManager::insertSimpleObject($condArtBonifObject);	
					}
				}
			}
		}

		//**********************//	
		//* Registro MOVIMIENTO *//
		//**********************//
		$movimiento	=	'CONDICION_COMERCIAL_DUPLICADO_ID_'.$condId;
		dac_registrarMovimiento($movimiento, "INSERT", 'TCondicionComercial', $condId);

	} else {
		echo "No se encuentran datos de Condición."; exit;
	}
}

echo "1"; exit;

header('Location: '.$backURL);

?>