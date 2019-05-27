<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M") {
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//----------------------------------
//SE AGREGA ARTICULO PROMOCIONAL OBLIGATORIO A LAS SIGUIENTES CUENTAS CON
//se aplica aquí y en editar.cadena.php
//categoría sea <> 1 (distintas de droguerías) AND 
$categoria = DataManager::getCuenta('ctacategoriacomercial', 'ctaidcuenta', $idCuenta, $empresa);
//empresa == 1 AND 
//$idCondComercial <> de ListaEspecial DR AHORRO, FARMACITY, FARMACITY 2
// originalmente $idCondComercial != 1764  && $idCondComercial != 1765 && $idCondComercial != 1761
$condBonificar	= 0;
$condiciones 	= DataManager::getCondiciones(0, 0, 1, $empresa, $laboratorio, NULL, NULL, NULL, NULL, $idCondComercial);
if (count($condiciones) > 0) {
	foreach ($condiciones as $i => $cond) {
		$condTipo 	= $cond['condtipo'];
		$condNombre = $cond['condnombre'];
		if($condTipo == 'ListaEspecial' && ($condNombre == 'DR AHORRO' || $condNombre == 'FARMACITY' || $condNombre == 'FARMACITY 2')){
			$condBonificar = 1;
		}
	}
}
if($empresa == 1 && $categoria <> 1 && $laboratorio == 1 && $condBonificar == 0){
	array_unshift ( $articulosIdArt	, 3801 );
	array_unshift ( $articulosCant 	, 1 );
	array_unshift ( $articulosPrecio, 0 );
	array_unshift ( $articulosB1 	, 0 );
	array_unshift ( $articulosB2 	, 0 );
	array_unshift ( $articulosD1 	, 0 );
	array_unshift ( $articulosD2 	, 0 );
}
//-------------------------------------

//TIPO DE PEDIDOS ADMINISTRACIÓN PARA VALES, SALIDAS PROMOCION, ETC
$nombre 	= '';
$provincia 	= '';
$localidad 	= '';
$direccion 	= '';
$cp 		= '';
$telefono 	= '';
if($tipoPedido == 'PARTICULAR') {
	$nombre 	= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, $empresa);
	$idProv 	= DataManager::getCuenta('ctaidprov', 'ctaidcuenta', $idCuenta, $empresa);
	$provincia	= DataManager::getProvincia('provnombre', $idProv);
	$idLoc		= DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $idCuenta, $empresa);	
	$localidad 	= DataManager::getLocalidad('locnombre', $idLoc);		
	$domicilio 	= DataManager::getCuenta('ctadireccion', 'ctaidcuenta', $idCuenta, $empresa);
	$numero 	= DataManager::getCuenta('ctadirnro', 'ctaidcuenta', $idCuenta, $empresa);
	$piso	 	= DataManager::getCuenta('ctadirpiso', 'ctaidcuenta', $idCuenta, $empresa);
	$dpto	 	= DataManager::getCuenta('ctadirdpto', 'ctaidcuenta', $idCuenta, $empresa);		
	$direccion 	= $domicilio.' '.$numero.' '.$piso.' '.$dpto;
	$direccion	= str_replace("  ", " ", $direccion);
	$cp 		= DataManager::getCuenta('ctacp', 'ctaidcuenta', $idCuenta, $empresa);
	$telefono 	= DataManager::getCuenta('ctatelefono', 'ctaidcuenta', $idCuenta, $empresa);
}

$items 				= 	0;
$maxItems			=	($empresa == 3)	?	8	:	14; //	Máxima can de Items por Pedido	// 
$limiteContado 		=	MONTO_MINIMO - (MONTO_MINIMO * PORC_RETENCION); //   CONTADO Limita por % Retención	// 
$contado			=	0; //($condPago == 3 || $condPago == 5)	?	1	:	0	;
$limiteFactura		=	($contado)	?	$limiteContado 	:	MONTO_MAXIMO;
$nroPedido			=	0;
$precioArt			=	0;
$totalFactura		=	0;
$cantFacturas		=	0;
$ultimaFactura		=	0;
$contadorFacturas	=	0; // control
unset($IdPedido);

for($i = 0; $i < count($articulosIdArt); $i++){
	$cant		=	$articulosCant[$i];
	$idArt		=	$articulosIdArt[$i];
	$b1			=	empty($articulosB1[$i])		? 0	:	$articulosB1[$i];
	$b2			=	empty($articulosB2[$i])		? 0	:	$articulosB2[$i];
	$d1			= 	empty($articulosD1[$i])		? 0	:	$articulosD1[$i]; 
	$d2			= 	empty($articulosD2[$i])		? 0	:	$articulosD2[$i];
	$precio		=	empty($articulosPrecio[$i])	? 0	:	$articulosPrecio[$i];
	$medicinal	=	DataManager::getArticulo('artmedicinal', $idArt, $empresa, $laboratorio);
	$medicinal	=	($medicinal == 'S') ? 0 : 1;		
		
	$precioArt	=	dac_calcularPrecio($cant, $precio, $medicinal, $d1, $d2);
	if ($precioArt > $limiteFactura && $contadorFacturas == 0) {		
		$cantBonificar		=	empty($b2) ? 0 : dac_extraer_entero((($cant / $b2) * ($b1 - $b2)));	
		$cantFacturas		= 	ceil($precioArt / $limiteFactura);	
		$unidXFactura		=	floor($cant / $cantFacturas); 
		$unidBonifXFactura	=	floor($cantBonificar / $cantFacturas); 
		$precioArt			=	dac_calcularPrecio($unidXFactura, $precio, $medicinal, $d1, $d2);
		$cantPendiente		=	$cant;
		$cantBonifPend		=	$cantBonificar	- $unidBonifXFactura;
		$observacion		=	" [Art:$idArt - Cant:$cant ($b1 X $b2)] ".$observacion;
		$cant				=	$unidXFactura;
		$b1					=	0;
		$b2					=	0;	
	}
	$totalFactura	=	$totalFactura	+ $precioArt;
	
	//	CONTROL ITEMS POR ARTICULOS	   //  (BONIFICADOS vale x 2) 
	$items	= ($b1 == 0 && $b2 == 0) ? $items + 1 : $items + 2;
		
	//	DEFINE NUEVA FACTURAs
	if($items > $maxItems || ($totalFactura > $limiteFactura && $contado == 1) || ($totalFactura > $limiteFactura && $contadorFacturas == 0 && $contado == 0)) {
		//$observacion	=	"DESG $nroPedido.".$observacion; 
		$items			= 	($items > $maxItems) ? $items - $maxItems : $maxItems - $items;
		//$items			=	$items - $maxItems;		
		$nroPedido		=	0;
		$ultimaFactura 	= 	0;
		$totalFactura	=	$precioArt;		
		$contadorFacturas = ($contado == 0) ? 1 : 0;		
	}
	
	// NUEVO NUMERO PEDIDO // 
	if ($nroPedido == 0) { 
		$fechaPedido	=	date("Y-m-d H:i:s");		
		$nroPedido 		= 	dac_controlNroPedido();	
		$IdPedido[] 	= 	$nroPedido;	
	}

	//	GRABAR	//
	do {			
		$nombreArt	=	DataManager::getArticulo('artnombre', $idArt, $empresa, $laboratorio);
		if (empty($nombreArt)){
			echo "Error con el artículo $idArt. P&oacute;ngase en contacto con el administrador de la web"; exit;
		}
		
		$nroOrden = (empty($nroOrden)) ? 0 : $nroOrden;
		
		$pedidoObject	=	DataManager::newObjectOfClass('TPedido');	
		$pedidoObject->__set('Usuario'			, $_SESSION["_usrid"]);
		$pedidoObject->__set('Pedido'			, $nroPedido);
		$pedidoObject->__set('Cliente'			, $idCuenta);
		$pedidoObject->__set('Empresa'			, $empresa);
		$pedidoObject->__set('Laboratorio'		, $laboratorio);
		$pedidoObject->__set('IDArt'			, $idArt);
		$pedidoObject->__set('Articulo'			, $nombreArt);
		$pedidoObject->__set('Cantidad'			, $cant);
		$pedidoObject->__set('Precio'			, $precio);
		$pedidoObject->__set('Bonificacion1'	, $b1);
		$pedidoObject->__set('Bonificacion2'	, $b2);
		$pedidoObject->__set('Descuento1'		, $d1);
		$pedidoObject->__set('Descuento2'		, $d2);
		$pedidoObject->__set('Descuento3'		, 0);
		$pedidoObject->__set('CondicionPago'	, $condPago);	
		$pedidoObject->__set('OrdenCompra'		, $nroOrden);		
		$pedidoObject->__set('Lista'			, 0);
		$pedidoObject->__set('Pack'				, 0);
		$pedidoObject->__set('IDAdmin'			, 0);
		$pedidoObject->__set('Administrador'	, 0);
		$pedidoObject->__set('FechaExportado'	, date("2001-01-01"));
		$pedidoObject->__set('IDResp'			, 0);
		$pedidoObject->__set('Responsable'		, '');
		$pedidoObject->__set('FechaAprobado'	, date("2001-01-01"));
		$pedidoObject->__set('Aprobado'			, 0);		
		$pedidoObject->__set('Propuesta'		, $idPropuesta);	
		$pedidoObject->__set('CondicionComercial',$idCondComercial);
		$pedidoObject->__set('Negociacion'		, $idPropuesta); //Negociaciones sería la nueva opción de PROPUESTA
		$pedidoObject->__set('Aprobado'			, ($estado == 2) ? 0 : $estado); //Aprobado sería el nuevo Estado del pedido
		$observacion	=	substr($observacion, 0, 250);
		$pedidoObject->__set('Observacion'		, $observacion);
		$pedidoObject->__set('FechaPedido'		, $fechaPedido);
		$pedidoObject->__set('Activo'			, 1); //Queda activo porque aún no está exportado		
		$pedidoObject->__set('Tipo'				, $tipoPedido);
		$pedidoObject->__set('Nombre'			, $nombre);
		$pedidoObject->__set('Provincia'		, $provincia);
		$pedidoObject->__set('Localidad'		, $localidad);
		$pedidoObject->__set('Direccion'		, $direccion);
		$pedidoObject->__set('CP'				, $cp);
		$pedidoObject->__set('Telefono'			, $telefono);		
				
		$pedidoObject->__set('ID'				, $pedidoObject->__newID());
		if ($cant != 0){
			$ID	= DataManager::insertSimpleObject($pedidoObject);
			if(empty($ID)){ 
				echo "Error. No se grabó el pedido $nroPedido. P&oacute;ngase en contacto con el administrador de la web"; exit;
			}
		}
				
		// División de Facturas //
		if($cantFacturas > 0){			
			$observacion	=	substr($observacion, 0, 250);
			$pedidoObject	=	DataManager::newObjectOfClass('TPedido');	
			$pedidoObject->__set('Usuario'			, $_SESSION["_usrid"]);
			$pedidoObject->__set('Pedido'			, $nroPedido);
			$pedidoObject->__set('Cliente'			, $idCuenta);
			$pedidoObject->__set('Empresa'			, $empresa);
			$pedidoObject->__set('Laboratorio'		, $laboratorio);
			$pedidoObject->__set('IDArt'			, $idArt);
			$pedidoObject->__set('Articulo'			, $nombreArt);
			$pedidoObject->__set('Cantidad'			, $unidBonifXFactura);
			$pedidoObject->__set('Precio'			, 0);
			$pedidoObject->__set('Bonificacion1'	, $b1);
			$pedidoObject->__set('Bonificacion2'	, $b2);
			$pedidoObject->__set('Descuento1'		, $d1);
			$pedidoObject->__set('Descuento2'		, $d2);
			$pedidoObject->__set('Descuento3'		, 0);
			$pedidoObject->__set('CondicionPago'	, $condPago);
			$pedidoObject->__set('OrdenCompra'		, $nroOrden);			
			$pedidoObject->__set('Lista'			, 0);
			$pedidoObject->__set('Pack'				, 0);
			$pedidoObject->__set('IDAdmin'			, 0);
			$pedidoObject->__set('Administrador'	, '');
			$pedidoObject->__set('FechaExportado'	, date("2001-01-01"));
			$pedidoObject->__set('IDResp'			, 0);
			$pedidoObject->__set('Responsable'		, '');
			$pedidoObject->__set('FechaAprobado'	, date("2001-01-01"));
			$pedidoObject->__set('Aprobado'			, 0);
			$pedidoObject->__set('Propuesta'		, $idPropuesta);
			$pedidoObject->__set('CondicionComercial',$idCondComercial);	
			$pedidoObject->__set('Negociacion'		, $idPropuesta);
			$pedidoObject->__set('Aprobado'			, ($estado == 2) ? 0 : $estado);
			$pedidoObject->__set('Observacion'		, $observacion);
			$pedidoObject->__set('FechaPedido'		, $fechaPedido);
			$pedidoObject->__set('Activo'			, 1); //Queda activo porque aún no está exportado			
			$pedidoObject->__set('Tipo'				, $tipoPedido);
			$pedidoObject->__set('Nombre'			, $nombre);
			$pedidoObject->__set('Provincia'		, $provincia);
			$pedidoObject->__set('Localidad'		, $localidad);
			$pedidoObject->__set('Direccion'		, $direccion);
			$pedidoObject->__set('CP'				, $cp);
			$pedidoObject->__set('Telefono'			, $telefono);			
			$pedidoObject->__set('ID'				, $pedidoObject->__newID());
			
			if ($unidBonifXFactura != 0){
				$ID	= DataManager::insertSimpleObject($pedidoObject);				
				if(empty($ID)){ 
					echo "Error. No se grabó el pedido $nroPedido. P&oacute;ngase en contacto con el administrador de la web."; exit; 
				}		
			}			
			
			if($contado == 0 && $contadorFacturas == 0){
				$cantFacturas	=	1;
				$ultimaFactura 	= 	1;
				$contadorFacturas =	1;				
			} else {
				$cantFacturas	=	$cantFacturas	- 	1; 
			}
			
			$cantPendiente	=	$cantPendiente 	- 	$unidXFactura;
			$cantBonifPend	=	$cantBonifPend	-	$unidBonifXFactura;
			
			//SI es la ULTIMA FACTURA
			if($cantFacturas == 1) {
				$cant				=	$cantPendiente;
				$unidBonifXFactura	=	$unidBonifXFactura + $cantBonifPend;
				$cantPendiente		=	0;		
				$cantBonifPend		=	0;			
				$unidXFactura		=	0;
				$precioArt			=	dac_calcularPrecio($cant, $precio, $medicinal, $d1, $d2);
					
				if($precioArt > $limiteFactura && $contadorFacturas == 0){
					$cantBonificar		=	$unidBonifXFactura;
					$cantFacturas		= 	2; //redondea hacia arriba
					$unidXFactura		=	floor($cant / $cantFacturas); //redondea hacia abajo
					$unidBonifXFactura	=	floor($cantBonificar / $cantFacturas);
					$cantPendiente		=	$cant;
					$cantBonifPend		=	$cantBonificar	- $unidBonifXFactura;
					$observacion 		= 	$observacion." (desglose distinto)";
					$cant				=	$unidXFactura;
					$items				=	0;	
				} else {
					$unidXFactura		=	$cant;
					$items				=	2;
				}
				$nroPedido 		= 	dac_controlNroPedido();
				$IdPedido[] 	= 	$nroPedido;	
				$precioArt		=	dac_calcularPrecio($cant, $precio, $medicinal, $d1, $d2);
				$totalFactura	=	$precioArt;
				//CAMBIO FACTURA A 2 PARA QUE SALGA
				$ultimaFactura 	= 	1;
			}

			if($ultimaFactura == 0){
				$nroPedido 		= 	dac_controlNroPedido();
				$IdPedido[] 	= 	$nroPedido;
			}
		} 		
	} while ($cantFacturas > 0);	
	
} //fin for articulos


//Al crear los pedidos, carga  el archivo de la orden de compra.
if ($filePeso != 0){	
	$ctaId 		= DataManager::getCuenta('ctaid', 'ctaidcuenta', $idCuenta, $empresa);
	$rutaFile	= "/pedidos/cuentas/archivos/".$ctaId."/pedidos/".$nroPedido."/";
	$ext		= explode(".", $fileNombre);
	$name		= 'ordenCompra'.$nroOrden; //$ext[0];
	
	$rutaFile = $_SERVER['DOCUMENT_ROOT'].$rutaFile;
	if(file_exists($rutaFile) || @mkdir($rutaFile, 0777, true))  {		
		$origen		=	$fileTempName;		
		$ext		=	explode(".", $fileNombre);
		$name		= 	$name.".".$ext[1];
		$destino	=	$rutaFile.$name; //nombre del archivo	
		# movemos el archivo
		if(@move_uploaded_file($origen, $destino)) {
		} else { echo "Error al intentar subir el archivo."; exit;}
	} else { echo "Error al intentar subir el archivo."; exit; }
}
//------------------------
		
?>