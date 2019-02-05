<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$condIdBonif	= 	(empty($_POST['condid']))	?	0	:	$_POST['condid'];
if($condIdBonif== 0) {
	echo "No se pudo cargar la condición comercial."; exit;
}

$dtHoy		=	new DateTime("now");
$dtManana	=	clone $dtHoy;
$dtManana->modify("+1 day");

//ULTIMO DÍA del mes de mañana
$dtFin		=	clone $dtManana;
$dtFin->setDate($dtFin->format('Y'), $dtFin->format('m'), $dtFin->format("t"));

//Se consultarán las condiciones comerciales tipo CONDICIONES ESPECIALES 
//donde su FECHA de FIN sea mayor o igual al día de HOY
$condiciones	=	DataManager::getCondiciones(0, 0, '', '', '', '', '"CondicionEspecial"', '', $dtHoy->format("Y-m-d"));
if (count($condiciones)) {
	//************************************//
	// Consulta los artículos de la Bonif //
	//************************************//
	$condBonifObject 		= DataManager::newObjectOfClass('TCondicionComercial', $condIdBonif);
	$condBonifFechaInicio	= $condBonifObject->__get('FechaInicio');
	$fechaBonifInicio		= new DateTime($condBonifFechaInicio);
	$condBonifFechaFin		= $condBonifObject->__get('FechaFin');
	$fechaBonifFin			= new DateTime($condBonifFechaFin);
	
	if($fechaBonifInicio->format("Y-m-d") <= $dtHoy->format("Y-m-d") || $fechaBonifFin->format("Y-m-d") <= $dtHoy->format("Y-m-d")){
		echo "La fecha de Bonificación de referencia NO puede ser menor o igual a la actual."; exit;
	}
	
	$articulosBonifCond = DataManager::getCondicionArticulos($condIdBonif);
	if (count($articulosBonifCond)) {
		foreach ($articulosBonifCond as $k => $detArtBonif) {	
			$detBonifIdArt		= $detArtBonif['cartidart'];
			$arrayBonifIdArt[] 	= $detBonifIdArt;
		}
	}
	
	//******************************//
	// Lee cada condición comercial //
	//******************************//
	foreach ($condiciones as $k => $cond) {
		$condId	=	$cond['condid'];		
		if ($condId) {
			$condObject 	= DataManager::newObjectOfClass('TCondicionComercial', $condId);
			$condEmpresa	= $condObject->__get('Empresa');
			$condLaboratorio= $condObject->__get('Laboratorio');
			$condFechaInicio= $condObject->__get('FechaInicio');
			$fechaInicio	= new DateTime($condFechaInicio);
			//$condFechaFin	= $condObject->__get('FechaFin');
			//Condición Habitual
			$condCant		= $condObject->__get('Cantidad');
			$condB1			= $condObject->__get('Bonif1');
			$condB2			= $condObject->__get('Bonif2');
			$condD1			= $condObject->__get('Desc1');
			$condD2			= $condObject->__get('Desc2');
			
			if($fechaInicio->format("Y-m-d") < $dtHoy->format("Y-m-d")){
				$dtFinAntes	=	clone $fechaBonifInicio;
				$dtFinAntes->modify("-1 day");
				
				//----------------
				//MODIFICO fecha FIN de condición vigente para que cierre UN DÍA ANTES DEL INICIO DE LA BONIFICACIÓN
				$condObject->__set('FechaFin', $dtFinAntes->format("Y-m-d"));
				DataManager::updateSimpleObject($condObject);		
				
				//----------------
				//CLONO EL OBJETO PARA DUPLICAR
				$condObjectDup 	= DataManager::newObjectOfClass('TCondicionComercial');				
				$condObjectDup->__set('Empresa'		, $condEmpresa);
				$condObjectDup->__set('Laboratorio'	, $condLaboratorio);
				$condObjectDup->__set('Cuentas'		, $condObject->__get('Cuentas'));
				$condObjectDup->__set('Nombre'		, $condObject->__get('Nombre'));
				$condObjectDup->__set('Tipo'		, $condObject->__get('Tipo'));
				$condObjectDup->__set('CondicionPago' , $condObject->__get('CondicionPago'));
				$condObjectDup->__set('CantidadMinima' , $condObject->__get('CantidadMinima'));
				$condObjectDup->__set('MinimoReferencias' , $condObject->__get('MinimoReferencias'));
				$condObjectDup->__set('MinimoMonto' , $condObject->__get('MinimoMonto'));				
				$condObjectDup->__set('Observacion'	, $condObject->__get('Observacion'));
				$condObjectDup->__set('Cantidad'	, $condCant);
				$condObjectDup->__set('Bonif1'		, $condB1);
				$condObjectDup->__set('Bonif2'		, $condB2);
				$condObjectDup->__set('Desc1'		, $condD1);
				$condObjectDup->__set('Desc2'		, $condD2);
				//------------------
				$condObjectDup->__set('FechaInicio'	, $condBonifObject->__get('FechaInicio'));
				$condObjectDup->__set('FechaFin'	, $condBonifObject->__get('FechaFin')); 
				$condObjectDup->__set('UsrUpdate'	, $_SESSION["_usrid"]);
				$condObjectDup->__set('LastUpdate'	, date("Y-m-d"));		
				$condObjectDup->__set('Activa'		, 1);
				$condObjectDup->__set('ID'			, $condObjectDup->__newID());
				
				$IDCondDup = DataManager::insertSimpleObject($condObjectDup);
				if(!$IDCondDup){
					echo "Error en el proceso de grabado de datos."; exit;
				}
				//------------------//	
				// Cargo Artículos  //
				//------------------//
				$articulosCond = DataManager::getCondicionArticulos($condId);
				if (count($articulosCond)) {
					unset($arrayIdArtDup);
					foreach ($articulosCond as $k => $detArt) {	
						$detId				= $detArt['cartid'];
						$detIdart			= $detArt['cartidart'];
						$arrayIdArtDup[]	= $detIdart;
						
						//Si el artículo EXISTE en la bonificación, lo duplica.
						if(in_array($detIdart, $arrayBonifIdArt)){
							//******************************//	
							//	Clono Detalle de Artículo	//
							//******************************// 
							$condArtObjectDup	= DataManager::newObjectOfClass('TCondicionComercialArt');
							//modifico los datos del duplicado
							$condArtObjectDup->__set('Condicion'		, $IDCondDup);
							$condArtObjectDup->__set('Articulo'			, $detIdart);
							$condArtObjectDup->__set('Precio'			, $detArt['cartprecio']);
							$condArtObjectDup->__set('Digitado'			, $detArt['cartpreciodigitado']);
							$condArtObjectDup->__set('CantidadMinima'	, $detArt['cartcantmin']);
							$condArtObjectDup->__set('OAM'				, $detArt['cartoam']);
							$condArtObjectDup->__set('Activo'			, $detArt['cartactivo']);
							$condArtObjectDup->__set('ID'				, $condArtObjectDup->__newID());
							$IDArt = DataManager::insertSimpleObject($condArtObjectDup);
							if(!$IDArt){
								echo "Error en el proceso de grabado de datos."; exit;
							}

							//**********************************//	
							//	Creo Detalle de Bonificaciones	//
							//**********************************//
							$articuloBonif	= DataManager::getCondicionBonificaciones($condId, $detIdart);
							if (count($articuloBonif)) {								 
								foreach ($articuloBonif as $j => $artBonif) {
									$artBonifId			= $artBonif['cbid'];
									
									//******************************//
									//	Duplico Detalle de Artículo	//
									//******************************//
									$condArtBonifObjectDup	= DataManager::newObjectOfClass('TCondicionComercialBonif');
									$condArtBonifObjectDup->__set('Condicion'	, $IDCondDup);
									$condArtBonifObjectDup->__set('Articulo'	, $artBonif['cbidart']);
									$condArtBonifObjectDup->__set('Cantidad'	, $artBonif['cbcant']);
									$condArtBonifObjectDup->__set('Bonif1'		, $artBonif['cbbonif1']);
									$condArtBonifObjectDup->__set('Bonif2'		, $artBonif['cbbonif2']);
									$condArtBonifObjectDup->__set('Desc1'		, $artBonif['cbdesc1']);
									$condArtBonifObjectDup->__set('Desc2'		, $artBonif['cbdesc2']);
									$condArtBonifObjectDup->__set('Activo'		, $artBonif['cbactivo']);
									$condArtBonifObjectDup->__set('ID'			, $condArtBonifObjectDup->__newID());
									$IDBonif = DataManager::insertSimpleObject($condArtBonifObjectDup);
									
									if(!$IDBonif){
										echo "Error en el proceso de grabado de datos."; exit;
									}
								}
							}	
						}
					}
					
					//Recorro el array de la bonificación
					for ($i = 0; $i < count($arrayBonifIdArt); $i++) {
						//si el artículo se encuentra en la bonififcación y no en el duplicado, se insertará en la condicion duplicada
						if(!in_array($arrayBonifIdArt[$i], $arrayIdArtDup)){							
							//******************************//	
							//	Creo Detalle de Artículo	//
							//******************************// 
							$artPrecio	= DataManager::getArticulo('artprecio', $arrayBonifIdArt[$i], $condEmpresa, $condLaboratorio);
							
							$condArtObject		= DataManager::newObjectOfClass('TCondicionComercialArt');
							$condArtObject->__set('Condicion'		, $IDCondDup);
							$condArtObject->__set('Articulo'		, $arrayBonifIdArt[$i]);
							$condArtObject->__set('Precio'			, $artPrecio);
							$condArtObject->__set('Activo'			, 1);							
							$condArtObject->__set('Digitado'		, '0.000');
							$condArtObject->__set('CantidadMinima'	, '0');
							$condArtObject->__set('OAM'				, '');							
							$condArtObject->__set('ID'				, $condArtObject->__newID());
							$IDArt = DataManager::insertSimpleObject($condArtObject);
							
							//**********************************//	
							//	Creo Detalle de Bonificaciones	// Con la condicion habitual
							//**********************************//
							if(!empty($condCant) && (!empty($condB1) || !empty($condB2) || !empty($condD1) || !empty($condD2))) {
								$condArtBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif');
								$condArtBonifObject->__set('Condicion'	, $IDCondDup);
								$condArtBonifObject->__set('Articulo'	, $arrayBonifIdArt[$i]);
								$condArtBonifObject->__set('Cantidad'	, $condCant);
								$condArtBonifObject->__set('Bonif1'		, $condB1);
								$condArtBonifObject->__set('Bonif2'		, $condB2);
								$condArtBonifObject->__set('Desc1'		, $condD1);
								$condArtBonifObject->__set('Desc2'		, $condD2);
								$condArtBonifObject->__set('Activo'		, 1);
								$condArtBonifObject->__set('ID'			, $condArtBonifObject->__newID());
								$IDBonif = DataManager::insertSimpleObject($condArtBonifObject);
								if(!$IDBonif){
									echo "Error en el proceso de grabado de datos."; exit;
								}
							}
						}
					}
				}
			} else {
				//CONDICIONES FUTURAS planificadas, 
				//solo controla si hay artículos nuevos para agregar.
				//******************//	
				// Cargo Artículos  //
				//******************//
				$articulosCond = DataManager::getCondicionArticulos($condId);
				if (count($articulosCond)) {
					unset($arrayIdArtDup);
					foreach ($articulosCond as $k => $detArt) {	
						$detId				= $detArt['cartid'];
						$detIdart			= $detArt['cartidart'];
						$arrayIdArtDup[]	= $detIdart;
						
					}

					//Recorro el array de la bonificación
					for ($i = 0; $i < count($arrayBonifIdArt); $i++) {
						//si el artículo se encuentra en la bonififcaicón y no se encuentra en el duplicado, se insertará
						if(!in_array($arrayBonifIdArt[$i], $arrayIdArtDup)){
							//******************************//	
							//	Creo Detalle de Artículo	//
							//******************************// 
							$artPrecio	= DataManager::getArticulo('artprecio', $arrayBonifIdArt[$i], $condEmpresa, $condLaboratorio);
							
							$condArtObject		= DataManager::newObjectOfClass('TCondicionComercialArt');
							//modifico los datos del duplicado
							$condArtObject->__set('Condicion'		, $condId);
							$condArtObject->__set('Articulo'		, $arrayBonifIdArt[$i]);
							$condArtObject->__set('Precio'			, $artPrecio);
							$condArtObject->__set('Activo'			, 1);
							
							$condArtObject->__set('Digitado'		, '0.000');
							$condArtObject->__set('CantidadMinima'	, '0');
							$condArtObject->__set('OAM'				, '');
							
							$condArtObject->__set('ID'			, $condArtObject->__newID());
							$IDArt = DataManager::insertSimpleObject($condArtObject);
							if(!$IDArt){
								echo "Error en el proceso de grabado de datos."; exit;
							}
							
							//**********************************//	
							//	Creo Detalle de Bonificaciones	// Con la condición habitual si existiera
							//**********************************//
							if(!empty($condCant) && (!empty($condB1) || !empty($condB2) || !empty($condD1) || !empty($condD2))) {
								$condArtBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif');
								$condArtBonifObject->__set('Condicion'	, $condId);
								$condArtBonifObject->__set('Articulo'	, $arrayBonifIdArt[$i]);
								$condArtBonifObject->__set('Cantidad'	, $condCant);
								$condArtBonifObject->__set('Bonif1'		, $condB1);
								$condArtBonifObject->__set('Bonif2'		, $condB2);
								$condArtBonifObject->__set('Desc1'		, $condD1);
								$condArtBonifObject->__set('Desc2'		, $condD2);
								$condArtBonifObject->__set('Activo'		, 1);
								$condArtBonifObject->__set('ID'			, $condArtBonifObject->__newID());
								$IDBonif = DataManager::insertSimpleObject($condArtBonifObject);
								if(!$IDBonif){
									echo "Error en el proceso de grabado de datos."; exit;
								}
							}
						}
					}
				}
			}

			//***********************//	
			//* Registro MOVIMIENTO *//
			//***********************//
			$movimiento	=	'CONDICION_COMERCIAL_DUPLICADA_ID_'.$condId;
			dac_registrarMovimiento($movimiento, "INSERT", 'TCondicionComercial', $condId);
			
			
		} else {
			echo "Error al consultar los registros."; exit;
		}
	}
	
	echo "1"; exit;
} else {
	echo "No se encontraron registros para duplicar."; exit;
}

?>