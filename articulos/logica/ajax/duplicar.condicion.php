<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$dtHoy		=	new DateTime("now");
$dtManana	=	clone $dtHoy;
$dtManana->modify("+1 day");
$dtAyer		=	clone $dtHoy;
$dtAyer->modify("-1 day");

//calculo ULTIMO DÍA del mes de mañana
$dtFin		=	clone $dtManana;
$dtFin->setDate($dtFin->format('Y'), $dtFin->format('m'), $dtFin->format("t"));

//Se consultarán TODAS las condiciones comerciales donde su FECHA de FIN sea mayor o igual al día de MAÑANA
$condiciones	=	DataManager::getCondiciones(0, 0, '', '', '', '', '', '', $dtManana->format("Y-m-d"));
if (count($condiciones)) {
	//------------------------------//
	// Lee cada condición comercial //
	foreach ($condiciones as $k => $cond) {
		$condId	= $cond['condid'];
		if ($condId) {
			$condObject 	= DataManager::newObjectOfClass('TCondicionComercial', $condId);
			$condEmpresa	= $condObject->__get('Empresa');
			$condLaboratorio= $condObject->__get('Laboratorio');
			$condFechaInicio= $condObject->__get('FechaInicio');
			$fechaInicio	= new DateTime($condFechaInicio);
			$condFechaFin	= $condObject->__get('FechaFin');
			
			//Si la fecha de Inicio de la condicion es MENOR a MAÑANA
			if($fechaInicio->format("Y-m-d") < $dtManana->format("Y-m-d")){
				//------------------
				//MODIFICO fecha FIN de condición vigente para que cierre HOY
				$condObject->__set('FechaFin', $dtHoy->format("Y-m-d"));
				DataManagerHiper::updateSimpleObject($condObject, $condId);
				DataManager::updateSimpleObject($condObject);
				
				//----------------		
				//CLONO EL OBJETO PARA DUPLICAR
				$condObjectDup	=	DataManager::newObjectOfClass('TCondicionComercial');
				$condObjectDup->__set('Empresa'				, $condEmpresa);
				$condObjectDup->__set('Laboratorio'			, $condLaboratorio);
				$condObjectDup->__set('Cuentas'				, $condObject->__get('Cuentas'));
				$condObjectDup->__set('Nombre'				, $condObject->__get('Nombre'));
				$condObjectDup->__set('Tipo'				, $condObject->__get('Tipo'));
				$condObjectDup->__set('CondicionPago'		, $condObject->__get('CondicionPago'));
				$condObjectDup->__set('CantidadMinima'		, $condObject->__get('CantidadMinima'));
				$condObjectDup->__set('MinimoReferencias' 	, $condObject->__get('MinimoReferencias'));
				$condObjectDup->__set('MinimoMonto' 		, $condObject->__get('MinimoMonto'));
				$condObjectDup->__set('Observacion' 		, $condObject->__get('Observacion'));
				$condObjectDup->__set('Cantidad' 			, $condObject->__get('Cantidad'));
				$condObjectDup->__set('Bonif1' 				, $condObject->__get('Bonif1'));
				$condObjectDup->__set('Bonif2' 				, $condObject->__get('Bonif2'));
				$condObjectDup->__set('Desc1' 				, $condObject->__get('Desc1'));
				$condObjectDup->__set('Desc2' 				, $condObject->__get('Desc2'));				
				$condObjectDup->__set('FechaInicio'			, $dtManana->format("Y-m-d"));
				$condObjectDup->__set('FechaFin'			, $dtFin->format("Y-m-d")); 
				$condObjectDup->__set('UsrUpdate'			, $_SESSION["_usrid"]);
				$condObjectDup->__set('LastUpdate'			, date("Y-m-d"));
				$condObjectDup->__set('Activa'				, 1);
				$condObjectDup->__set('Lista'				, $condObject->__get('Lista'));
				$condObjectDup->__set('ID'					, $condObjectDup->__newID());
				DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin
				$IDCondDup = DataManager::insertSimpleObject($condObjectDup);
				DataManagerHiper::insertSimpleObject($condObjectDup, $IDCondDup);
				
				//------------------//	
				// Cargo Artículos  //
				$articulosCond = DataManager::getCondicionArticulos($condId);
				if (count($articulosCond)) {
					foreach ($articulosCond as $k => $detArt) {
						$detId		= $detArt['cartid'];
						$detIdart	= $detArt['cartidart'];
						$artPrecio	= DataManager::getArticulo('artpreciolista', $detIdart, $condEmpresa, $condLaboratorio);
						$artPrecio 	= (empty($artPrecio)) ? '0.000' : $artPrecio;						
						//------------------------------//	
						//	Clono Detalle de Artículo	//
						$condArtObject		= DataManager::newObjectOfClass('TCondicionComercialArt', $detId);
						//$condArtObjectDup 	= clone $condArtObject;
						//modifico los datos del duplicado
						$condArtObjectDup	=	DataManager::newObjectOfClass('TCondicionComercialArt');
						$condArtObjectDup->__set('Articulo'			, $condArtObject->__get('Articulo'));
						$condArtObjectDup->__set('Digitado'			, $condArtObject->__get('Digitado'));
						$condArtObjectDup->__set('CantidadMinima'	, $condArtObject->__get('CantidadMinima'));
						$condArtObjectDup->__set('OAM'				, $condArtObject->__get('OAM'));
						$condArtObjectDup->__set('Oferta'			, $condArtObject->__get('Oferta'));
						$condArtObjectDup->__set('Activo'			, $condArtObject->__get('Activo'));
						$condArtObjectDup->__set('Condicion'		, $IDCondDup);
						$condArtObjectDup->__set('Precio'			, $artPrecio);
						$condArtObjectDup->__set('ID'				, $condArtObjectDup->__newID());
						
						DataManagerHiper::_getConnection('Hiper');
						$IDArt = DataManager::insertSimpleObject($condArtObjectDup);
						DataManagerHiper::insertSimpleObject($condArtObjectDup, $IDCondDup);						
						
						//----------------------------------//	
						//	Creo Detalle de Bonificaciones	//
						$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $detIdart);
						if (count($articulosBonif)) {								 
							foreach ($articulosBonif as $j => $artBonif) {
								$artBonifId			= $artBonif['cbid'];
								$condArtBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif', $artBonifId);
								//------------------------------//
								//	Duplico Detalle de Artículo	//
								$condArtBonifObjectDup 	= DataManager::newObjectOfClass('TCondicionComercialBonif');
								//$condArtBonifObjectDup 	= clone $condArtBonifObject;
								$condArtBonifObjectDup->__set('Articulo'	, $condArtBonifObject->__get('Articulo'));
								$condArtBonifObjectDup->__set('Cantidad'	, $condArtBonifObject->__get('Cantidad'));
								$condArtBonifObjectDup->__set('Bonif1'		, $condArtBonifObject->__get('Bonif1'));
								$condArtBonifObjectDup->__set('Bonif2'		, $condArtBonifObject->__get('Bonif2'));
								$condArtBonifObjectDup->__set('Desc1'		, $condArtBonifObject->__get('Desc1'));
								$condArtBonifObjectDup->__set('Desc2'		, $condArtBonifObject->__get('Desc2'));
								$condArtBonifObjectDup->__set('Activo'		, $condArtBonifObject->__get('Activo'));
								$condArtBonifObjectDup->__set('Condicion'	, $IDCondDup);
								$condArtBonifObjectDup->__set('ID'			, $condArtBonifObjectDup->__newID());
								
								DataManagerHiper::_getConnection('Hiper');
								$IDArt = DataManager::insertSimpleObject($condArtBonifObjectDup);
								DataManagerHiper::insertSimpleObject($condArtBonifObjectDup, $IDCondDup);
							}
						}
					}
				}
				$movimiento	=	'DUPLICA_ID_'.$condId;
				$movTipo	=	'INSERT';
			} else {
				//Si la fecha de INICIO es MAYOR O IGUAL a MAÑANA, SOLO se actualizan los precios???
				$articulosCond = DataManager::getCondicionArticulos($condId);
				if (count($articulosCond)) {
					foreach ($articulosCond as $k => $detArt) {
						$detId		= $detArt['cartid'];
						$detIdart	= $detArt['cartidart'];						
						$artPrecio	= DataManager::getArticulo('artpreciolista', $detIdart, $condEmpresa, $condLaboratorio);
						$artPrecio 	= (empty($artPrecio)) ? '0.000' : $artPrecio;						
						//+-----------------------------//
						//	Update precios de Artículo	//
						$condArtObject = DataManager::newObjectOfClass('TCondicionComercialArt', $detId);
						$condArtObject->__set('Precio', $artPrecio);
						
						DataManagerHiper::updateSimpleObject($condArtObject, $detId);
						DataManager::updateSimpleObject($condArtObject);
					}
				}			
				$movimiento	= 'PRECIO_ID_'.$condId;
				$movTipo	= 'UPDATE';
			}
			
			//-----------------------//	
			//  Registro MOVIMIENTO  //
			dac_registrarMovimiento($movimiento, $movTipo, 'TCondicionComercial', $condId);			
		} else {
			echo "Error al consultar los registros."; exit;
		}
	}
} else {
	echo "No se encontraron registros para duplicar."; exit;
}

echo "1"; exit; ?>