<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$condId 		= (isset($_POST['condid']))			? $_POST['condid']			: NULL;
$empresa		= (isset($_POST['empselect']))		? $_POST['empselect'] 		: NULL;
$laboratorio	= (isset($_POST['labselect']))		? $_POST['labselect'] 		: NULL;
$nombre 		= (isset($_POST['nombre']))			? $_POST['nombre'] 			: NULL;
$tipo			= (isset($_POST['tiposelect']))		? $_POST['tiposelect']		: NULL;
$condPago 		= (isset($_POST['condpago']))		? $_POST['condpago']		: NULL;
$condPagoId 	= (isset($_POST['condpagoid']))		? $_POST['condpagoid'] 		: '';
$cantMinima		= (isset($_POST['cantMinima']))		? $_POST['cantMinima'] 		: NULL;
$minReferencias	= (isset($_POST['minReferencias']))	? $_POST['minReferencias'] 	: NULL;
$minMonto	 	= (isset($_POST['minMonto']))		? $_POST['minMonto'] 		: NULL;
$fechaInicio 	= (isset($_POST['fechaInicio']))	? $_POST['fechaInicio'] 	: NULL;
$fechaFin 		= (isset($_POST['fechaFin']))		? $_POST['fechaFin'] 		: NULL;
$observacion 	= (isset($_POST['observacion']))	? $_POST['observacion'] 	: NULL;
$cuentaId 		= (isset($_POST['cuentaid']))		? $_POST['cuentaid'] 		: '';
$lista			= (isset($_POST['listaPrecio']))	? $_POST['listaPrecio'] 	: 0;
//Condición Habitual
$habitualCant	= (isset($_POST['habitualCant']))	? $_POST['habitualCant']	: '';
$habitualBonif1	= (isset($_POST['habitualBonif1']))	? $_POST['habitualBonif1']	:	NULL;
$habitualBonif2 = (isset($_POST['habitualBonif2']))	? $_POST['habitualBonif2']	: NULL;
$habitualDesc1	= (isset($_POST['habitualDesc1']))	? $_POST['habitualDesc1'] 	: NULL;
$habitualDesc2 	= (isset($_POST['habitualDesc2']))	? $_POST['habitualDesc2'] 	: NULL;
//String para pasar a array
//$arrayCondIdCond= (isset($_POST['condidcond']))	? $_POST['condidcond'] 	: 	NULL;
$arrayCondIdArt	= (isset($_POST['condidart']))		? $_POST['condidart'] 		: NULL;
$arrayPrecioArt	= (isset($_POST['condprecioart']))	? $_POST['condprecioart'] 	: NULL;
$arrayPrecioDigit= (isset($_POST['condpreciodigit'])) ? $_POST['condpreciodigit']: NULL;
$arrayCantMin 	= (isset($_POST['condcantmin']))	? $_POST['condcantmin'] 	: NULL;
$arrayEstadoOAM	= (isset($_POST['condoferta']))		? $_POST['condoferta'] 		: NULL;
$arayOfertaCheck = (isset($_POST['condofertacheck']))	? $_POST['condofertacheck']	: NULL;

//Arrays de string para pasar a Arrays
$arrayCant		= (isset($_POST['condcant']))		? explode('|', $_POST['condcant']) 	: NULL;
$arrayBonif1	= (isset($_POST['condbonif1']))		? explode('|', $_POST['condbonif1']) : NULL;
$arrayBonif2	= (isset($_POST['condbonif2']))		? explode('|', $_POST['condbonif2']) : NULL;
$arrayDesc1		= (isset($_POST['conddesc1']))		? explode('|', $_POST['conddesc1']) : NULL;
$arrayDesc2		= (isset($_POST['conddesc2']))		? explode('|', $_POST['conddesc2']) : NULL;

if (empty($empresa)) {
	echo "Seleccione una empresa."; exit;
}
if (empty($laboratorio)) {
	echo "Indique un laboratorio."; exit;
}
if(empty($tipo)){
	echo "Indique el tipo de condici&oacute;n"; exit;
}
if(empty($nombre)){
	echo "Indique el nombre de condici&oacute;n"; exit;
}
if(!empty($cantMinima) && (!is_numeric($cantMinima) || $cantMinima < 0)){
	echo "La cantidad m&iacute;nima debe ser num&eacute;rica"; exit;
}
if(!empty($minReferencias) && (!is_numeric($minReferencias) || $minReferencias < 0)){
	echo "El m&iacute;nimo de referencias debe ser num&eacute;rico"; exit;
}

if($lista != 0 && (!empty($cuentaId) ||  !empty($condPagoId)) ){
	echo "Una condición tipo lista no puede tener cuentas relacionadas ni condiciones de pago"; exit;
} else {
	//Controla que la lista no exista activa en otra condicion comercial activa en fecha inicio y fin actual.
	//$fechaListasHoy 	= new DateTime('now'); //$fechaListasHoy->format("Y-m-d")
	//$fechaInicio->format("Y-m-d")
	//$fechaFin->format("Y-m-d")
	$fechaDesde = new DateTime($fechaInicio);
	$fechaHasta	= new DateTime($fechaFin);
	
	$condicionesListas	= DataManager::getCondiciones(0, 0, 1, $empresa, $laboratorio, $fechaDesde->format("Y-m-d"), NULL, NULL, NULL, NULL, NULL);
	if($condicionesListas){
		foreach($condicionesListas as $k => $cond){
			$condIdCond = $cond['condid'];
			$condNombre = $cond['condnombre'];
			
			$condLista 	= $cond['condlista'];
			if($condId != $condIdCond && $condLista == $lista && $condLista != 0){
				echo "Ya existe la condción comercial '".$condNombre."' con la misma lista de precios definida."; exit;
			}
		}
	}
}

if(!empty($minMonto) && (!is_numeric($minMonto) || $minMonto < 0)){
	echo "El Monto M&iacute;nimo debe ser num&eacute;rico"; exit;
} 
if (empty($fechaInicio) || empty($fechaFin)) {
	echo "Debe indicar fecha de Inicio y Fin"; exit;
}
$fechaI = new DateTime($fechaInicio);
$fechaF = new DateTime($fechaFin);

if($fechaI->format("Y-m-d") >= $fechaF->format("Y-m-d")){
	echo "La fecha de Inicio debe ser menor que la de Fin"; exit;
}
if (count($arrayCondIdArt) < 1){
	echo "Cargue art&iacute;culos a la condici&oacute;n."; exit;
} else {
	//Controlar que no se repitan los artículos
	$resultado = dac_duplicadoEnArray($arrayCondIdArt);	
	if($resultado){ echo $resultado; exit; }
}

//***********************************//
//Control de los artículos cargados  //
//empieza el for desde el 1 porque el cero envía en todos un valor desconocido.
for($k = 0; $k < count($arrayCondIdArt); $k++){
	if (empty($arrayPrecioArt[$k]) || !is_numeric($arrayPrecioArt[$k])){
		echo "El precio del art&iacute;culo ".$arrayCondIdArt[$k]." es incorrecto"; exit;
	}	
	if (!empty($arrayPrecioDigit[$k]) && !is_numeric($arrayPrecioDigit[$k]) || $arrayPrecioDigit[$k] < 0){
		echo "El precio digitado del art&iacute;culo ".$arrayCondIdArt[$k]." es incorrecto"; exit;
	}	
	if ($arrayCantMin[$k] < 0){
		echo "La cantidad m&iacute;nima del art&iacute;culo ".$arrayCondIdArt[$k]." debe ser mayor o igual a cero."; exit;
	}
	
	$arrayPrecioDigit[$k] 	= empty($arrayPrecioDigit[$k]) ? 0 : $arrayPrecioDigit[$k];
	$arrayCantMin[$k] 		= empty($arrayCantMin[$k]) ? 0 : $arrayCantMin[$k];
	
	//si hay cantidades en el array cantidades
	if($arrayCant[$k]){
		//Arrays Bonificaciones del artículo K
		$cant	= explode("-", $arrayCant[$k]);
		$bonif1	= explode("-", $arrayBonif1[$k]);
		$bonif2	= explode("-", $arrayBonif2[$k]);
		$desc1	= explode("-", $arrayDesc1[$k]);
		$desc2	= explode("-", $arrayDesc2[$k]);
		
		//Controlar que no se repitan las cantidades
		$resultado = dac_duplicadoEnArray($cant);	
		if($resultado){ echo $resultado; exit; }

		for($j = 0; $j < count($cant); $j++){
			if(empty($cant[$j]) || $cant[$j] < 0){
				echo "Indique las cantidadades para la bonificaci&oacute;n del art&iacute;culo ".$arrayCondIdArt[$k]; exit;
			}		
			if (!empty($bonif1[$j]) || !empty($bonif2[$j])){
				if (empty($bonif1[$j]) || empty($bonif2[$j]) || !is_numeric($bonif1[$j]) || !is_numeric($bonif2[$j]) || $bonif1[$j] < 1 || $bonif2[$j] < 1 || $bonif1[$j] <= $bonif2[$j]){	
					echo "La bonificaci&oacute;n del art&iacute;culo ".$arrayCondIdArt[$k]." con cantidad ".$cant[$j]." es incorrecta."; exit;
				}
			}		
			if ((!empty($desc1[$j]) && (!is_numeric($desc1[$j])) || $desc1[$j] < 0)){
				echo "Un descuento 1 del art&iacute;culo ".$arrayCondIdArt[$k]." con cantidad ".$cant[$j]." es incorrecto."; exit;
			}		
			if ((!empty($desc2[$j]) && (!is_numeric($desc2[$j])) || $desc2[$j] < 0)){
				echo "Un descuento 2 del art&iacute;culo ".$arrayCondIdArt[$k]." con cantidad ".$cant[$j]." es incorrecto."; exit;
			}		
			if(empty($bonif1[$j]) && empty($bonif2[$j]) && empty($desc1[$j]) && empty($desc2[$j])){
				echo "Debe indicar bonificaci&oacute;n o descuento para el art&iacute;culo ".$arrayCondIdArt[$k]." con cantidad ".$cant[$j]."."; exit;
			}
		}
	}
}

//**********//
//	Grabar	//
$cantMinima 	= empty($cantMinima) ? 0 : $cantMinima;
$minReferencias = empty($minReferencias) ? 0 : $minReferencias;
$minMonto 		= empty($minMonto) ? 0 : $minMonto;
$habitualCant 	= empty($habitualCant) ? 0 : $habitualCant;
$habitualBonif1	= empty($habitualBonif1) ? 0 : $habitualBonif1;
$habitualBonif2	= empty($habitualBonif2) ? 0 : $habitualBonif2;
$habitualDesc1	= empty($habitualDesc1) ? 0 : $habitualDesc1;
$habitualDesc2	= empty($habitualDesc2) ? 0 : $habitualDesc2;

$condObject	= ($condId) ? DataManager::newObjectOfClass('TCondicionComercial', $condId) : DataManager::newObjectOfClass('TCondicionComercial');
$condObject->__set('Empresa'			, $empresa);
$condObject->__set('Laboratorio'		, $laboratorio);
$condObject->__set('Cuentas'			, $cuentaId);
$condObject->__set('Nombre'				, $nombre);
$condObject->__set('Tipo'				, $tipo);
$condObject->__set('CondicionPago'		, $condPagoId); //$condPago
$condObject->__set('CantidadMinima'		, $cantMinima); 
$condObject->__set('MinimoReferencias' 	, $minReferencias);
$condObject->__set('MinimoMonto'		, $minMonto);
$condObject->__set('FechaInicio'		, $fechaI->format("Y-m-d"));		
$condObject->__set('FechaFin'			, $fechaF->format("Y-m-d"));	
$condObject->__set('Observacion'		, $observacion);
$condObject->__set('Cantidad'			, $habitualCant);
$condObject->__set('Bonif1'				, $habitualBonif1);
$condObject->__set('Bonif2'				, $habitualBonif2);
$condObject->__set('Desc1'				, $habitualDesc1);
$condObject->__set('Desc2'				, $habitualDesc2);
$condObject->__set('UsrUpdate'			, $_SESSION["_usrid"]);
$condObject->__set('LastUpdate'			, date("Y-m-d H:i:s"));
$condObject->__set('Lista'				, $lista);
if ($condId) {
	//UPDATE
	$IDCondCom = $condId;
	DataManagerHiper::updateSimpleObject($condObject, $condId);
	DataManager::updateSimpleObject($condObject);
	//***************************************//	
	// Insert, Update o Delete de artículos  //
	$articulosCondicion = DataManager::getCondicionArticulos($condId);
	if (count($articulosCondicion)) {
		unset($arrayArticulosDDBB);
		foreach ($articulosCondicion as $k => $artCond) {			
            $condArtId		= $artCond['cartid'];
            $condArtIdArt	= $artCond['cartidart'];
			//Creo el array de artículos de BBDD
			$arrayArticulosDDBB[]	=	$condArtIdArt;
			if (count($arrayCondIdArt) && in_array($condArtIdArt, $arrayCondIdArt)) {
				//B //UPDATE
				//Indice de donde se encuentra el artículo en el array web
				$key	=	array_search($condArtIdArt, $arrayCondIdArt);				
				unset($cant);	
				$cant	=	explode("-", $arrayCant[$key]);	
				unset($arrayCantidadesDDBB);
				
				$condArtObject	= DataManager::newObjectOfClass('TCondicionComercialArt', $condArtId);
				$condArtObject->__set('Precio'			, $arrayPrecioArt[$key]);	
				$condArtObject->__set('Digitado'		, $arrayPrecioDigit[$key]);	
				$condArtObject->__set('CantidadMinima'	, $arrayCantMin[$key]);
				$condArtObject->__set('OAM'				, $arrayEstadoOAM[$key]);				
				$condArtObject->__set('Oferta'			, $arayOfertaCheck[$key]);
				
				DataManagerHiper::updateSimpleObject($condArtObject, $condArtId);
				DataManager::updateSimpleObject($condArtObject);
				
				$bonificacionesArt	= DataManager::getCondicionBonificaciones($condId, $condArtIdArt);
				if (count($bonificacionesArt)) {
					foreach ($bonificacionesArt as $j => $bonifArt) { 
						$bonifArtID		= $bonifArt['cbid'];	
						$bonifArtCant	= ($bonifArt['cbcant']) ? $bonifArt['cbcant'] : 0;	

						//Creo el array de cantidades de BBDD de ésta condicion y artículo
						$arrayCantidadesDDBB[]	=	$bonifArtCant;		
						if(count($cant) && in_array($bonifArtCant, $cant)) {
							//B //UPDATE bonificacion	
							$bonif1	= 	explode("-", $arrayBonif1[$key]);
							$bonif2	= 	explode("-", $arrayBonif2[$key]);
							$desc1	= 	explode("-", $arrayDesc1[$key]);
							$desc2	= 	explode("-", $arrayDesc2[$key]);
							
							//indice de bonificaciones donde se encuentra la cantidad
							$key2	=	array_search($bonifArtCant, $cant);	
							
							$bonif1[$key2] 	= empty($bonif1[$key2]) ? 0 : $bonif1[$key2];
							$bonif2[$key2] 	= empty($bonif2[$key2]) ? 0 : $bonif2[$key2];
							$desc1[$key2] 	= empty($desc1[$key2]) ? 0 : $desc1[$key2];
							$desc2[$key2] 	= empty($desc2[$key2]) ? 0 : $desc2[$key2];
							//-----------------
							$condBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif', $bonifArtID);
							$condBonifObject->__set('Bonif1'	, $bonif1[$key2]);
							$condBonifObject->__set('Bonif2'	, $bonif2[$key2]);
							$condBonifObject->__set('Desc1'		, $desc1[$key2]);
							$condBonifObject->__set('Desc2'		, $desc2[$key2]);
							
							DataManagerHiper::updateSimpleObject($condBonifObject, $bonifArtID);
							DataManager::updateSimpleObject($condBonifObject);					
						} else {							
							//C //DELETE bonificacion
							$condBonifObject = DataManager::newObjectOfClass('TCondicionComercialBonif', $bonifArtID);
							$condBonifObject->__set('ID',	$bonifArtID);
							DataManagerHiper::deleteSimpleObject($condBonifObject, $bonifArtID);
							DataManager::deleteSimpleObject($condBonifObject);
						}				
					}

					//INSERT Bonificaciones
					if(count($cant)){
						$bonif1	= 	explode("-", $arrayBonif1[$key]);
						$bonif2	= 	explode("-", $arrayBonif2[$key]);
						$desc1	= 	explode("-", $arrayDesc1[$key]);
						$desc2	= 	explode("-", $arrayDesc2[$key]);
						
						for($j = 0; $j < count($cant); $j++){
							if(!in_array($cant[$j], $arrayCantidadesDDBB)) {
								$bonif1[$j] = empty($bonif1[$j]) ? 0 : $bonif1[$j];
								$bonif2[$j] = empty($bonif2[$j]) ? 0 : $bonif2[$j];
								$desc1[$j] 	= empty($desc1[$j])  ? 0 : $desc1[$j];
								$desc2[$j] 	= empty($desc2[$j])  ? 0 : $desc2[$j];
								//-----------------
								$condBonifObject = DataManager::newObjectOfClass('TCondicionComercialBonif');
								$condBonifObject->__set('Condicion'		, $condId);
								$condBonifObject->__set('Articulo'		, $condArtIdArt);
								$condBonifObject->__set('Cantidad'		, $cant[$j]);
								$condBonifObject->__set('Bonif1'		, $bonif1[$j]);
								$condBonifObject->__set('Bonif2'		, $bonif2[$j]);
								$condBonifObject->__set('Desc1'			, $desc1[$j]);
								$condBonifObject->__set('Desc2'			, $desc2[$j]);			
								$condBonifObject->__set('Activo'		, 0);
								$condBonifObject->__set('ID'			, $condBonifObject->__newID());
								
								DataManagerHiper::_getConnection('Hiper');
								$IDCondBonif = DataManager::insertSimpleObject($condBonifObject);
								DataManagerHiper::insertSimpleObject($condBonifObject, $IDCondBonif);	
							}
						}
					}
				} else {					
					//A //INSERT Bonificaciones	
					if($arrayCant[$key]){
						$bonif1	= 	explode("-", $arrayBonif1[$key]);
						$bonif2	= 	explode("-", $arrayBonif2[$key]);
						$desc1	= 	explode("-", $arrayDesc1[$key]);
						$desc2	= 	explode("-", $arrayDesc2[$key]);
						for($j = 0; $j < count($cant); $j++){	
							$bonif1[$j] = empty($bonif1[$j]) ? 0 : $bonif1[$j];
							$bonif2[$j] = empty($bonif2[$j]) ? 0 : $bonif2[$j];
							$desc1[$j] 	= empty($desc1[$j]) ? 0 : $desc1[$j];
							$desc2[$j] 	= empty($desc2[$j]) ? 0 : $desc2[$j];
							//-----------------
							$condBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif');
							$condBonifObject->__set('Condicion'		, $condId);
							$condBonifObject->__set('Articulo'		, $condArtIdArt);
							$condBonifObject->__set('Cantidad'		, $cant[$j]);
							$condBonifObject->__set('Bonif1'		, $bonif1[$j]);
							$condBonifObject->__set('Bonif2'		, $bonif2[$j]);
							$condBonifObject->__set('Desc1'			, $desc1[$j]);
							$condBonifObject->__set('Desc2'			, $desc2[$j]);			
							$condBonifObject->__set('Activo'		, 0);
							$condBonifObject->__set('ID'			, $condBonifObject->__newID());
							DataManagerHiper::_getConnection('Hiper');
							$IDCondBonif = DataManager::insertSimpleObject($condBonifObject);
							DataManagerHiper::insertSimpleObject($condBonifObject, $IDCondBonif);	
						}
					}
				}
			} else {
				//C //DELETE de artículos
				$condArtObject	=	DataManager::newObjectOfClass('TCondicionComercialArt', $condArtId);
				$condArtObject->__set('ID',	$condArtId);
				DataManagerHiper::deleteSimpleObject($condArtObject, $condArtId);
				DataManager::deleteSimpleObject($condArtObject);
				
				$bonificacionesArt	= DataManager::getCondicionBonificaciones($condId, $condArtIdArt);
				if (count($bonificacionesArt)) {								 
					foreach ($bonificacionesArt as $j => $bonifArt) {	
						$IDCondBonif		=	$bonifArt['cbid'];
						$condBonifObject	=	DataManager::newObjectOfClass('TCondicionComercialBonif', $IDCondBonif);
						$condBonifObject->__set('ID',	$IDCondBonif);
						DataManagerHiper::deleteSimpleObject($condBonifObject, $IDCondBonif);
						DataManager::deleteSimpleObject($condBonifObject);		
					}
				}
			}
		}
		
		//Recorro e INSERT DE los artículos en el array que no estan en la addbb para insertar
		for($k = 0; $k < count($arrayCondIdArt); $k++){	
			if(!in_array($arrayCondIdArt[$k], $arrayArticulosDDBB)) {				
				$condArtObject	= DataManager::newObjectOfClass('TCondicionComercialArt');	
				$condArtObject->__set('Condicion'		, $condId);
				$condArtObject->__set('Articulo'		, $arrayCondIdArt[$k]);
				$condArtObject->__set('Precio'			, $arrayPrecioArt[$k]);
				$condArtObject->__set('Digitado'		, $arrayPrecioDigit[$k]);	
				$condArtObject->__set('CantidadMinima'	, $arrayCantMin[$k]);
				$condArtObject->__set('OAM'				, $arrayEstadoOAM[$k]);
				$condArtObject->__set('Oferta'			, $arayOfertaCheck[$k]);
				$condArtObject->__set('Activo'			, 0);
				$condArtObject->__set('ID'				, $condArtObject->__newID());
				DataManagerHiper::_getConnection('Hiper');
				$IDCondArt = DataManager::insertSimpleObject($condArtObject);
				DataManagerHiper::insertSimpleObject($condArtObject, $IDCondArt);
				
				if($arrayCant[$k]){
					//INSERT Bonificaciones	
					$cant	= 	explode("-", $arrayCant[$k]);
					$bonif1	= 	explode("-", $arrayBonif1[$k]);
					$bonif2	= 	explode("-", $arrayBonif2[$k]);
					$desc1	= 	explode("-", $arrayDesc1[$k]);
					$desc2	= 	explode("-", $arrayDesc2[$k]);

					for($j = 0; $j < count($cant); $j++){
						$bonif1[$j] = empty($bonif1[$j]) ? 0 : $bonif1[$j];
						$bonif2[$j] = empty($bonif2[$j]) ? 0 : $bonif2[$j];
						$desc1[$j] 	= empty($desc1[$j]) ? 0 : $desc1[$j];
						$desc2[$j] 	= empty($desc2[$j]) ? 0 : $desc2[$j];
						//-----------------
						$condBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif');
						$condBonifObject->__set('Condicion'	, $condId);
						$condBonifObject->__set('Articulo'	, $arrayCondIdArt[$k]);
						$condBonifObject->__set('Cantidad'	, $cant[$j]);
						$condBonifObject->__set('Bonif1'	, $bonif1[$j]);
						$condBonifObject->__set('Bonif2'	, $bonif2[$j]);
						$condBonifObject->__set('Desc1'		, $desc1[$j]);
						$condBonifObject->__set('Desc2'		, $desc2[$j]);			
						$condBonifObject->__set('Activo'	, 0);
						$condBonifObject->__set('ID'		, $condBonifObject->__newID());
						
						DataManagerHiper::_getConnection('Hiper');
						$IDCondBonif = DataManager::insertSimpleObject($condBonifObject);
						DataManagerHiper::insertSimpleObject($condBonifObject, $IDCondBonif);
					}
				}
			}
		}		
	} else {		
		//INSERT Articulos	 //Si no hay artículos en la BBDD, solo inserta
		for($k = 0; $k < count($arrayCondIdArt); $k++){				
			$condArtObject	= DataManager::newObjectOfClass('TCondicionComercialArt');	
			$condArtObject->__set('Condicion'		, $condId);
			$condArtObject->__set('Articulo'		, $arrayCondIdArt[$k]);
			$condArtObject->__set('Precio'			, $arrayPrecioArt[$k]);
			$condArtObject->__set('Digitado'		, $arrayPrecioDigit[$k]);
			$condArtObject->__set('CantidadMinima'	, $arrayCantMin[$k]);
			$condArtObject->__set('OAM'				, $arrayEstadoOAM[$k]);
			$condArtObject->__set('Oferta'			, $arayOfertaCheck[$k]);
			$condArtObject->__set('Activo'			, 0);
			$condArtObject->__set('ID'				, $condArtObject->__newID());
			DataManagerHiper::_getConnection('Hiper');
			$IDCondArt = DataManager::insertSimpleObject($condArtObject);
			DataManagerHiper::insertSimpleObject($condArtObject, $IDCondArt);
			
			//Si hay bonificaciones cargadas
			if($arrayCant[$k]){
				//INSERT Bonificaciones	
				$cant	= 	explode("-", $arrayCant[$k]);
				$bonif1	= 	explode("-", $arrayBonif1[$k]);
				$bonif2	= 	explode("-", $arrayBonif2[$k]);
				$desc1	= 	explode("-", $arrayDesc1[$k]);
				$desc2	= 	explode("-", $arrayDesc2[$k]);

				for($j = 0; $j < count($cant); $j++){
					$bonif1[$j] = empty($bonif1[$j]) ? 0 : $bonif1[$j];
					$bonif2[$j] = empty($bonif2[$j]) ? 0 : $bonif2[$j];
					$desc1[$j] 	= empty($desc1[$j]) ? 0 : $desc1[$j];
					$desc2[$j] 	= empty($desc2[$j]) ? 0 : $desc2[$j];
					//-----------------
					$condBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif');
					$condBonifObject->__set('Condicion'	, $condId);
					$condBonifObject->__set('Articulo'	, $arrayCondIdArt[$k]);
					$condBonifObject->__set('Cantidad'	, $cant[$j]);
					$condBonifObject->__set('Bonif1'	, $bonif1[$j]);
					$condBonifObject->__set('Bonif2'	, $bonif2[$j]);
					$condBonifObject->__set('Desc1'		, $desc1[$j]);
					$condBonifObject->__set('Desc2'		, $desc2[$j]);			
					$condBonifObject->__set('Activo'	, 0);
					$condBonifObject->__set('ID'		, $condBonifObject->__newID());
					DataManagerHiper::_getConnection('Hiper');
					$IDCondBonif = DataManager::insertSimpleObject($condBonifObject);
					DataManagerHiper::insertSimpleObject($condBonifObject, $IDCondBonif);
				}
			}
		}
	}
	//MOVIMIENTO Condiciones Comerciales
	$condId			= $IDCondCom;
	$movimiento 	= 'CONDICION_COMERCIAL';	
	$movTipo		= 'UPDATE';
	
} else {	
	//INSERT Condicion
	$condObject->__set('ID'		, $condObject->__newID());
	$condObject->__set('Activa'	, 0);
	DataManagerHiper::_getConnection('Hiper');
	$IDCondCom = DataManager::insertSimpleObject($condObject);
	DataManagerHiper::insertSimpleObject($condObject, $IDCondCom);
	
	//INSERT Articulos	
	for($k = 0; $k < count($arrayCondIdArt); $k++){
		$condArtObject	= DataManager::newObjectOfClass('TCondicionComercialArt');	
		$condArtObject->__set('Condicion'		, $IDCondCom);
		$condArtObject->__set('Articulo'		, $arrayCondIdArt[$k]);
		$condArtObject->__set('Precio'			, $arrayPrecioArt[$k]);
		$condArtObject->__set('Digitado'		, $arrayPrecioDigit[$k]);
		$condArtObject->__set('CantidadMinima'	, $arrayCantMin[$k]);
		$condArtObject->__set('OAM'				, $arrayEstadoOAM[$k]);
		$condArtObject->__set('Oferta'			, $arayOfertaCheck[$k]);
		$condArtObject->__set('Activo'			, 0);
		$condArtObject->__set('ID'				, $condArtObject->__newID());
		DataManagerHiper::_getConnection('Hiper');
		$IDCondArt = DataManager::insertSimpleObject($condArtObject);	
		DataManagerHiper::insertSimpleObject($condArtObject, $IDCondArt);
		
		if($arrayCant[$k]){
			//INSERT Bonificaciones	
			$cant	= 	explode("-", $arrayCant[$k]);
			$bonif1	= 	explode("-", $arrayBonif1[$k]);
			$bonif2	= 	explode("-", $arrayBonif2[$k]);
			$desc1	= 	explode("-", $arrayDesc1[$k]);
			$desc2	= 	explode("-", $arrayDesc2[$k]);
			
			for($j = 0; $j < count($cant); $j++){
				$bonif1[$j] = empty($bonif1[$j]) ? 0 : $bonif1[$j];
				$bonif2[$j] = empty($bonif2[$j]) ? 0 : $bonif2[$j];
				$desc1[$j] 	= empty($desc1[$j]) ? 0 : $desc1[$j];
				$desc2[$j] 	= empty($desc2[$j]) ? 0 : $desc2[$j];
				//-----------------
				$condBonifObject	= DataManager::newObjectOfClass('TCondicionComercialBonif');
				$condBonifObject->__set('Condicion'		, $IDCondCom);
				$condBonifObject->__set('Articulo'		, $arrayCondIdArt[$k]);
				$condBonifObject->__set('Cantidad'		, $cant[$j]);
				$condBonifObject->__set('Bonif1'		, $bonif1[$j]);
				$condBonifObject->__set('Bonif2'		, $bonif2[$j]);
				$condBonifObject->__set('Desc1'			, $desc1[$j]);
				$condBonifObject->__set('Desc2'			, $desc2[$j]);			
				$condBonifObject->__set('Activo'		, 0);
				$condBonifObject->__set('ID'			, $condBonifObject->__newID());
				DataManagerHiper::_getConnection('Hiper');
				$IDCondBonif = DataManager::insertSimpleObject($condBonifObject);
				DataManagerHiper::insertSimpleObject($condBonifObject, $IDCondBonif);
			}
		}
	}
	//MOVIMIENTO Condicion Comerciales
	$condId			=  $IDCondCom;
	$movimiento 	= 'CONDICION_COMERCIAL';
	$movTipo		= 'INSERT';
}

//	Registro movimiento
dac_registrarMovimiento($movimiento, $movTipo, 'TCondicionComercial', $condId);

//Controlar las fechas con condiciones comerciales finalizadas que estén activas, para desactivar
$condiciones	= DataManager::getCondiciones(0, 0, 1);
if($condiciones){
	$fechaHoy = new DateTime('now');
	foreach($condiciones as $k => $condicion) {
		$fechaFinal = new DateTime($condicion['condfechafin']);
		if($fechaFinal->format("Y-m-d") < $fechaHoy->format("Y-m-d")){
			$condId 	= $condicion['condid'];			
			$condObject	= DataManager::newObjectOfClass('TCondicionComercial', $condId);
			$condObject->__set('Activa'	, 0);
			DataManagerHiper::updateSimpleObject($condObject, $condId);
			DataManager::updateSimpleObject($condObject);
			
			//MOVIMIENTO Condicion Comerciales
			$movimiento = 'DesactivaPorFechaFin_'.$fechaFinal->format("Y-m-d")."_menorA_".$fechaHoy->format("Y-m-d");
			$movTipo	= 'UPDATE';
			dac_registrarMovimiento($movimiento, $movTipo, 'TCondicionComercial', $condId);
		}
	}
}

echo 1; exit;
?>