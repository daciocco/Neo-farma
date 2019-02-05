<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//******************************// 
//		Controles Generales		//
if(empty($idCondComercial) && empty($propuesta) && empty($tipoPedido)){ echo "Seleccione Condici&oacute;n Comercial o indique como Propuesta."; exit;}
if(!empty($propuesta)){ $idCondComercial = "";}
if(empty($usrAsignado)){echo "Seleccione usuario asignado."; exit;}
if(empty($empresa)) { echo "Seleccione una empresa."; exit; }
if(empty($laboratorio)) { echo "Indique un laboratorio."; exit; }
if(empty($idCuenta))	{ 	echo "Seleccione una cuenta"; exit; }
if(!empty($nroOrden) && !is_numeric($nroOrden)){ echo "El N&uacute;mero de Orden debe ser num&eacute;rico"; exit; }

//***********************//
// PROPUESTAS PENDIENTES //
//La cuenta no puede tener pedidos PENDIENTES o APROBADOS para hacer una nuevo pedido/propuesta.	
//QUITO ÉSTE CONTROL PORQUE HABRÍA QUE SACAR LOS DUPLICADOS DE PROPUESTAS APROBADAS QUE NUNCA SE PASARON
//Y AGREGAR EL MISMO CONTROL AL ENVIARSE EL PEDIDO EN COMO PROPUESTA PORQUE DEJA ENVÍAR MAS DE UNA PROPUESTA AL MISMO CLIENTE
/*
$propuestas	=	DataManager::getPropuestas($idCuenta);
if (count($propuestas) > 0) {
	$contProp = 0;
	foreach ($propuestas as $k => $prop) {		
		$propEstado	= $prop['propestado'];
		switch($propEstado){
			case '1': //Pendientes
			case '2': //Aprobados
				$contProp++;
				break;
		}				
	}
	if($contProp > 2){
		echo "La cuenta tiene propuestas pendientes, no puede cargar un nuevo pedido.", exit;
	}
}*/

//------------------------//
// Control COND DE PAGO	 //
if(empty($condPago)){ 	
	echo "Seleccione condici&oacute;n de pago"; exit; 
} else {	
	//controla si la condición de pago de cond fechas para modificar los días según fecha actual
	$condicionPago	=	DataManager::getCondicionesDePago(0,0,1,$condPago);
	if (count($condicionPago)) {
		foreach ($condicionPago as $k => $condP) {				
			$condPagoId		= $condP['condid'];	
			$condDecrece	= $condP['conddecrece'];
			if($condDecrece == 'S'){
				//Al ser con fechas controla la vigencia de las mismas y resta los días en caso 
				//calcular días que restan
				$dateFrom	= new DateTime();
				
				for($k=1; $k <= 5; $k++){
					$condFechasDec[$k]	= ($condP["condfechadec$k"] == '2001-01-01') ? '' : $condP["condfechadec$k"];
					$condDias[$k]		= ($condP["Dias".$k."CP"]) ? $condP["Dias".$k."CP"] : '' ;
					
					if(!empty($condFechasDec[$k])){
						$dateTo		= new DateTime(dac_invertirFecha($condFechasDec[$k]));	
						$dateFrom->setTime($dateTo->format('H'), $dateTo->format('m'), $dateTo->format('s'));
						if($dateTo <  $dateFrom){
							echo "La fecha de condición de pago a finalizado."; exit;
						}
						
						//Controla los días para editar a la fecha actual
						$dateFrom->modify('-1 day');
						$interval = $dateFrom->diff($dateTo);						
						if($interval->format('%R%a') != $condDias[$k]){
							//si es distinto se hará el update
							$condObject	= DataManager::newObjectOfClass('TCondicionPago', $condPagoId);
							if($k == 1){
								$condObject->__set('Dias'	, $interval->format('%R%a'));
							} else {
								$condObject->__set("Dias$k"	, $interval->format('%R%a'));
							}
							DataManager::updateSimpleObject($condObject);	
							DataManagerHiper::updateSimpleObject($condObject, $condPagoId);
						}
						
					}
				}
			}
		}
	} else {
		echo "La condción de pago seleccionada a sido desactivada."; exit;
	}
}

//------------------//
//  Nro de Orden	//
$categoria	=	DataManager::getCuenta('ctacategoriacomercial', 'ctaidcuenta', $idCuenta, $empresa);
if ($categoria) {
	//SI Cuenta ES DROGUERÍA o COOPERATIVA (Categoría 1), nro de Orden de compra es obligatorio
	if ($categoria == 1 && empty($nroOrden) && $empresa == 3){
		echo "Indique n&uacute;mero de orden de compra."; exit;
	}
} else {
	echo "La categor&iacute; comercial no es correcta."; exit;
	//echo "ERROR! al verificar la cuenta, p&oacute;ngase en contacto con el administrador de la web $idCuenta, $empresa"; exit;
}

//**********************//
// Artículos cargados	//
if(!count($articulosIdArt)){ echo "Debe cargar art&iacute;culos"; exit; }
$precioIva	=	0;
for($i = 0; $i < count($articulosIdArt); $i++){		
	//**********************//
	// Artículos repetidos	//
	for($j = 0; $j < count($articulosIdArt); $j++){
		if ($i != $j){
			if ($articulosIdArt[$i] == $articulosIdArt[$j]){
				echo "Art&iacute;culo ".$articulosIdArt[$i]." repetido.";  exit;
			}
		}
	}
	//**************************//
	//	Cantidad del Artículo	//
	if(empty($articulosCant[$i]) || $articulosCant[$i] <= 0){ 
		echo "Indique una cantidad correcta al art&iacute;culo ".$articulosIdArt[$i]; exit; 
	}	
	//**************************//
	//	Controla Bonificacion	//
	if($articulosB1[$i] < $articulosB2[$i]){
		echo "Bonificaci&oacute;n del art&iacute;culo ".$articulosIdArt[$i]." incorrecta.";  exit;
	}
	//**************************//
	// Control de MONTO_MAXIMO //
	$medicinal		=	DataManager::getArticulo('artmedicinal', $articulosIdArt[$i], $empresa, $laboratorio);
	$medicinal		=	($medicinal == 'S') ? 0 : 1;
	$precioIva		+=	dac_calcularPrecio($articulosCant[$i], $articulosPrecio[$i], $medicinal, $articulosD1[$i], $articulosD2[$i]);
}

//***********************//
// 	Condicion Comercial	 //
if($idCondComercial){	
	$condicion		= DataManager::newObjectOfClass('TCondicionComercial', $idCondComercial);
	$cuentas		= $condicion->__get('Cuentas');
	$tipo	 		= $condicion->__get('Tipo');
	$condCondPago	= $condicion->__get('CondicionPago');
	$cantMinima		= ($condicion->__get('CantidadMinima')) 		? $condicion->__get('CantidadMinima') : '';
	$minReferencias	= ($condicion->__get('MinimoReferencias')) 		? $condicion->__get('CantidadMinima') : '';
	$minMonto		= ($condicion->__get('MinimoMonto') == '0.000') ? '' : $condicion->__get('MinimoMonto');
	
	//*******************************//
	// Controles Condición Comercial //
	if(!empty($cuentas)){
		$cuentasId = explode(',', $cuentas);
		$ctaID = DataManager::getCuenta('ctaid', 'ctaidcuenta', $idCuenta, $empresa);
		if(!in_array($ctaID, $cuentasId)){
			echo "Cuenta seleccionada no v&aacute;lida."; exit;
		}
	} else {
		$condiciones = DataManager::getCondiciones(0, 0, 1, $empresa, $laboratorio, date("Y-m-d"));
		if (count($condiciones)) {
			foreach ($condiciones as $k => $cond) {
				$cond 				=	$condiciones[$k];
				$condId				=	$cond['condid'];
				$condCuentas		= 	$cond['condidcuentas'];
				$condNombre			= 	$cond['condnombre'];

				if($condCuentas){
					if($condId != $idCondComercial){
						$arrayCondIdCtas = explode(",", $condCuentas);
						$ctaID = DataManager::getCuenta('ctaid', 'ctaidcuenta', $idCuenta, $empresa);
						if(in_array($ctaID, $arrayCondIdCtas)){
							echo "La cuenta utilizada corresponde a la condición comercial --> $condNombre."; exit;	
						}
					}
				}
			}
		} 
	}	
	
	if($condCondPago){
		$condicionesPago = explode(',', $condCondPago);
		if(!in_array($condPago, $condicionesPago)){
			echo "Condici&oacute;n de pago no v&aacute;lida."; exit;
		}
	} else {
		//Si no se definió condición de pago, deberá respetar las siguientes condiciones respecto a la condición de pago de la cuenta:
		// 1) tenga <= cantidad de días. SIN cambiar tipo de condición
		// 2) SI es FIRMA, acepta cambio de tipo a CONTRARREMBOLSO además de cumplir el punto 1
		// Si se desea cambiar la condición de la cuenta, deberá pasar el pedido como propuesta y pedir en observación aprobar el cambio de condición de la cuenta.
		$condPagoCuenta = DataManager::getCuenta('ctacondpago', 'ctaidcuenta', $idCuenta, $empresa);
		if($condPago != $condPagoCuenta) {
			//Busca condicion de pago de la cuenta
			$condicionesPagoCuenta	=	DataManager::getCondicionesDePago(0, 0, 1, $condPagoCuenta); 
			if (count($condicionesPagoCuenta)) { 
				$condDiasCuenta = [];
				foreach ($condicionesPagoCuenta as $k => $cond) {
					$condTipoCuenta	= $cond['condtipo'];					
					$condDiasCuenta = array($cond['Dias1CP'], $cond['Dias2CP'], $cond['Dias3CP'], $cond['Dias4CP'], $cond['Dias5CP']);					
					$condDiasCuenta = array_filter($condDiasCuenta);
					$maxDiasCuenta 	= (count($condDiasCuenta) > 0) ? max($condDiasCuenta) : 0;		
				}
			} else {
				echo "La condicion de pago de la cuenta debe actualizarse antes de realizar un pedido."; exit;
			}			
			//Busca condicion de pago seleccionada
			$condicionesPago	=	DataManager::getCondicionesDePago(0, 0, 1, $condPago); 
			if (count($condicionesPago)) { 
				$condDias = [];
				foreach ($condicionesPago as $k => $cond) {	
					$condTipo	= $cond['condtipo'];					
					$condDias = array($cond['Dias1CP'], $cond['Dias2CP'], $cond['Dias3CP'], $cond['Dias4CP'], $cond['Dias5CP']);		
					$condDias = array_filter($condDias);
					$maxDias = (count($condDias) > 0) ? max($condDias) : 0;					
				}				  
			} else {
				echo "Error al intentar consultar la condición de pago seleccionada."; exit;
			}
			
			//Controla diferencia de tipo
			if($condTipoCuenta == 3 && $condTipo != 3){
				echo "La condición de pago debe ser CONTRAREEMBOLSO."; exit;
			}
			//Controla diferencia de días
			if($maxDias > $maxDiasCuenta){
				echo "La condición de pago seleccionada es mayor a la permitida."; exit;	
			}
		}
	}
		
	if($cantMinima){
		if(array_sum($articulosCant) < $cantMinima){
			echo "La cantidad de unidades no v&aacute;lida."; exit;
		}
	}
	
	if($minReferencias){
		if($minReferencias < count($articulosIdArt)){
			echo "Cantidad de referencias no v&aacute;lida."; exit;
		}
	}
		
	$montoFinal = 0;			
	$articulosCond	= DataManager::getCondicionArticulos($idCondComercial);
	if (count($articulosCond)) {	
		foreach ($articulosCond as $j => $artCond) {	
			$condArtIdArt	= $artCond['cartidart'];
			$condArtPrecio	= $artCond["cartprecio"];					
			$condArtPrecioDigit	= ($artCond["cartpreciodigitado"] == '0.000')?	''	:	$artCond["cartpreciodigitado"]; 
			$precioArt 		= ($artCond["cartpreciodigitado"] == '0.000')?	$condArtPrecio	:	$condArtPrecioDigit;
			$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];
			
			//Control de datos por artículo
			if(in_array($condArtIdArt, $articulosIdArt)){
				$key = array_search($condArtIdArt, $articulosIdArt);			
				if($key >= 0){
					//Controlo Precio
					if($precioArt > $articulosPrecio[$key]){
						echo "Precio de art&iacute;culo ".$articulosIdArt[$key]." no v&aacute;lido."; exit;
					}
					
					//Controlo Cantidad mínima
					if($condArtCantMin){
						if($articulosCant[$key] < $condArtCantMin){
							echo "La cantidad m&iacute;nima del art&iacute;culo ".$articulosIdArt[$key]." debe ser ".$condArtCantMin; exit;
						}
					}						
					
					//**************************************//
					//	Calculo Condiciones del Vendedor	//
					//**************************************//	
					$articulosBonifB1		= 	($articulosB1[$key]) ? $articulosB1[$key] : 1;
					$articulosBonifB2		= 	($articulosB2[$key]) ? $articulosB2[$key] : 1;	
					$articulosBonifD1		= 	($articulosD1[$key]) ? $articulosD1[$key] : 0;
					$articulosBonifD2		=	($articulosD2[$key]) ? $articulosD2[$key] : 0;
					
					$cantBonificada			=	($articulosBonifB1 * $articulosCant[$key]) / $articulosBonifB2;	
					$cantEnteraBonificada	=	dac_extraer_entero($cantBonificada);				
					$precioUno				= 	$articulosCant[$key] * $articulosPrecio[$key];				
					$precioDos				=	$precioUno / $cantEnteraBonificada; 
					$precioDesc1			=	$precioDos - ($precioDos * $articulosBonifD1/100);			
					$precioDesc2 			=	$precioDesc1 - ($precioDesc1 * $articulosBonifD2/100);						
					$precioFinalVendido 	= 	$precioDesc2;
					
					//NO CONTROLO EXACTAMENTE LAS BONIFICACIONES y DESCUENTOS
					//YA QUE solo basta que EL RESULTADO NO SEa MENOR A X								
					//**************************************//
					//	Calculo Condiciones de la Empresa	// según la cantidad pedida por el vendedor!
					//**************************************//	
					$artBonifCant	= 	1;
					$artBonifB1		= 	1;
					$artBonifB2		= 	1;	
					$artBonifD1		= 	0;
					$artBonifD2		=	0;
					$articulosBonif	= DataManager::getCondicionBonificaciones($idCondComercial, $condArtIdArt);
					//importante que la función devuelva los valores ordenados
					if (count($articulosBonif)){
						foreach ($articulosBonif as $i => $artBonif){					
							//$artBonifCant	= 	$artBonif['cbcant'];
							if($articulosCant[$key] >= $artBonif['cbcant']){
								$artBonifCant	= 	($artBonif['cbcant']) ? $artBonif['cbcant'] 	: '1';
								$artBonifB1		= 	($artBonif['cbbonif1']) ? $artBonif['cbbonif1'] : '1';
								$artBonifB2		= 	($artBonif['cbbonif2']) ? $artBonif['cbbonif2'] : '1';	
								$artBonifD1		= 	($artBonif['cbdesc1']) ? $artBonif['cbdesc1'] 	: '0';	
								$artBonifD2		=	($artBonif['cbdesc2']) ? $artBonif['cbdesc2'] 	: '0';
							}
						}
					} 
					
					$cantBonificada			=	($artBonifB1 * $articulosCant[$key]) / $artBonifB2;	  	
					$cantEnteraBonificada	=	dac_extraer_entero($cantBonificada);
					$precioUno				= 	$articulosCant[$key] * $precioArt;	
					$precioDos				=	$precioUno / $cantEnteraBonificada; 
					$precioDesc1			=	$precioDos - ($precioDos * $artBonifD1/100);			
					$precioDesc2 			=	$precioDesc1 - ($precioDesc1 * $artBonifD2/100);
					$precioFinalEmpresa 	= 	$precioDesc2;									
														
					//y el IVA ???? $articulosIva[$key];				
					//CONTROLAR QUE  POR EJEMPLO $articulosBonifB1 NO PUEDA SER CERO y que sea 1 en su defecto
					//*************************************//
					if($precioFinalVendido < $precioFinalEmpresa){ 
					//Si precio final vendido < precio final de empresa, estará mal
						if($tipo == 'CondicionEspecial' && $condArtIdArt >= 600){
							//Para ésta condición comercial se hace una excepción para:
							//Artículos código >= 600. El precio final podrá ser menor si,
							//si se coloca al menos descuento que exista comobonificación de ese artícuulo
							$condicionesBonif = DataManager::getCondiciones(0, 0, 1, $empresa, $laboratorio, date("Y-m-d"), 'Bonificacion');
							if (count($condicionesBonif)) {
								foreach ($condicionesBonif as $k => $condBonif) {
									$condIdBonif		= $condBonif['condid'];
									$articulosCondBonif	= DataManager::getCondicionArticulos($condIdBonif);
									if (count($articulosCondBonif)) {
										foreach ($articulosCondBonif as $q => $artCondBonif) {
											$condArtIdArtBonif			= $artCondBonif['cartidart'];
											$condArtPrecioBonif			= $artCondBonif["cartprecio"];					
											$condArtPrecioDigitBonif	= ($artCondBonif["cartpreciodigitado"] == '0.000')?	''	:	$artCondBonif["cartpreciodigitado"]; 
											$precioArtBonif 			= ($artCondBonif["cartpreciodigitado"] == '0.000')?	$condArtPrecioBonif		:	$condArtPrecioDigitBonif;
											
											if($condArtIdArt == $condArtIdArtBonif){
												$articulosBonif	= DataManager::getCondicionBonificaciones($condIdBonif, $condArtIdArtBonif);
												if (count($articulosBonif)) {	
													foreach ($articulosBonif as $l => $artBonif) {
														
														$artB1	= 	($artBonif['cbbonif1']) ? $artBonif['cbbonif1'] : 1;
														$artB2	= 	($artBonif['cbbonif2']) ? $artBonif['cbbonif2'] : 1;
														$artD1	= 	($artBonif['cbdesc1']) ? $artBonif['cbdesc1'] : 0;
														$artD2	=	($artBonif['cbdesc1']) ? $artBonif['cbdesc1'] : 0;
														$cantBonif	=	($artB1 * $articulosCant[$key]) / $artB2;
														
														$cantEnteraBonificada	=	dac_extraer_entero($cantBonif);
														$precioUno		= 	$articulosCant[$key] * $precioArtBonif;	
														$precioDos		=	$precioUno / $cantEnteraBonificada; 
														$precioDesc1	=	$precioDos - ($precioDos * $artD1/100);
														$precioDesc2 	=	$precioDesc1 - ($precioDesc1 * $artD2/100);
														$precioFinalEmpresa2	= 	$precioDesc2; 

														if($l == 0 && $precioFinalVendido < $precioFinalEmpresa2){ 
															echo "Las condiciones del art&iacute;culo ".$articulosIdArt[$key]." dan precio menor al acordado. "; exit;	
														} 
													}
												} else {
													echo "Las condiciones del art&iacute;culo ".$articulosIdArt[$key]." dan precio menor al acordado. "; exit;	
												}
											}
										}
									}
								}
							} else {
								echo "Las condiciones del art&iacute;culo ".$articulosIdArt[$key]." dan precio menor al acordado. "; exit;
							}
							
						} else {
							echo "Las condiciones del art&iacute;culo ".$articulosIdArt[$key]." dan precio menor al acordado. "; exit; //$precioFinalVendido < $precioFinalEmpresa
						}
					}				
					//************************************//
					
					//Sumo monto final
					$montoFinal	+= $precioFinalVendido * $articulosCant[$key];	
				}
			}
		}
	}
}

?>