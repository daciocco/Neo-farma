<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$pId 			= 	0;
$usrAsignado	= 	(isset($_POST['ptParaIdUsr']))	? $_POST['ptParaIdUsr'] : NULL;
$idCuenta		= 	(isset($_POST['tblTransfer']))	? $_POST['tblTransfer'] : NULL; //ctaid de tabla cuenta
$nombreCuenta 	= 	DataManager::getCuenta('ctanombre', 'ctaid', $idCuenta);
$cuit		 	= 	DataManager::getCuenta('ctacuit', 'ctaid', $idCuenta);

$direccion	 	= 	DataManager::getCuenta('ctadireccion', 'ctaid', $idCuenta);
$nro	 		= 	DataManager::getCuenta('ctadirnro', 'ctaid', $idCuenta);
$piso	 		= 	DataManager::getCuenta('ctadirpiso', 'ctaid', $idCuenta);
$dpto	 		= 	DataManager::getCuenta('ctadirdpto', 'ctaid', $idCuenta);
$domicilio 		= 	$direccion." ".$nro." ".$piso." ".$dpto;

$contacto 		= 	(isset($_POST['contacto']))	? $_POST['contacto'] 	: NULL;

$idDrogueria	= 	(isset($_POST['cuentaId']))	? $_POST['cuentaId'] : NULL; //ctarelidcuenta de tabla cuenta_relacionada

$nroClienteDrog	= 	(isset($_POST['cuentaIdTransfer']))	? $_POST['cuentaIdTransfer'] : NULL; //número de lciente transfer para la droguería

$condPago= 	(isset($_POST['ptcondpago']))	? $_POST['ptcondpago'] 	: NULL;
$idArt	= 	(isset($_POST['ptidart']))	? $_POST['ptidart'] : NULL;
$precio	= 	(isset($_POST['ptprecioart']))	? $_POST['ptprecioart'] : NULL;
$cant	= 	(isset($_POST['ptcant']))	? $_POST['ptcant'] : NULL;
$desc	= 	(isset($_POST['ptdesc']))	? $_POST['ptdesc'] : NULL;

//----------------------//
//	Controlo campos		//
if(empty($usrAsignado)){
	echo "Debe indicar usuario asignado.";  exit;
}
 
if(count($idDrogueria) != 1){
	echo "El pedido debe tener una cuenta transfer."; exit;
} else {
	$idDrogueria 	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $idDrogueria[0]);
	$nroClienteDrog = $nroClienteDrog[0];
}

if (count($idArt) < 1){
	echo "Debe cargar al menos un artículo.";  exit;
}

for($i = 0; $i < count($idArt); $i++){	
	//Controla artículos repetidos
	for($j = 0; $j < count($idArt); $j++){
		if ($i != $j){
			if ($idArt[$i] == $idArt[$j]){
				echo "El artículo ".$idArt[$i]." está repetido.";  exit;
			}
		}
	}
	
	if (empty($cant[$i]) || !is_numeric($cant[$i]) || $cant[$i] < 1){
		echo "Debe cargar una cantidad para el artículo ".$idArt[$i];  exit;
	}	  
	
	//CONTROL de DESCUENTO
	if ($desc[$i] < 0 || $desc[$i] == "" || !is_numeric($desc[$i])){
		echo "Debe cargar el porcentaje de descuento para el artículo ".$idArt[$i];  exit;
	}
	
	//CONTROL ABM
	$abms	=	DataManager::getDetalleAbm(date("m"), date("Y"), $idDrogueria, 'TL');
	if ($abms) {
		foreach ($abms as $k => $abm){
			$abmArtId	= 	$abm['abmartid'];
			$abmDesc	= 	$abm['abmdesc'];			
			$abmCondPago= 	$abm['abmcondpago'];			
			
			//Si descuento <= que el desc del ABM ACTUAL
			if ($abmArtId == $idArt[$i]) { //&& $abmDrog == $idDrogueria
				//Descuento ABM debe ser >= que del artículo.
				if ($abmDesc < $desc[$i]) {
					echo "El descuento del artículo ".$abmArtId." supera el porcentaje pactado actualmente.";  exit;
				}
					
				//Controla Condición de pago					
				$abmDias	=	0;
				$abmCondPagoDias	=	DataManager::getCondicionDePagoTransfer('conddias', 'condid', $abmCondPago);	
				if ($abmCondPagoDias) {
					//Saca los días máximos de cond de pago del Artículo.
					$abmDias	=	max(explode(',', str_replace(' ', '', trim($abmCondPagoDias))));
				}
				
				
				$condPagoDiasPedido	= 0;
				$condicionesPagoDiasPedido	= DataManager::getCondicionDePagoTransfer('conddias', 'condid', $condPago[$i]);
				if ($condicionesPagoDiasPedido) {
					//saca días el maximo de días del array (30, 60, 90...)
					$condPagoDiasPedido	=	max(explode(',', str_replace(' ', '', trim($condicionesPagoDiasPedido)))); 
				}
				
				if($condPagoDiasPedido > $abmDias) {
					echo "$condPagoDiasPedido > $abmDias - $abmArtId - "; exit;
					  echo "La condición de pago del pedido transfer para el artículo ".$abmArtId.", no cumple las condiciones de plazo de la droguería"; exit;
				  }
				//Si el pedido es a X cantidad de días, el ABM debe tener opciones iguales o mayores a dicha cantdidad
			}	
		}
	} else {
		echo "No hay condiciones ABM cargadas para la droguería ".$idDrogueria.".";  exit;
	} 
}

if (($idDrogueria != 220061 && $idDrogueria != 220181) && !is_numeric($nroClienteDrog)){
	echo "El código de cliente de la droguería es incorrecto.";  exit;
}

//******************************//
//	UPDATE DEL PEDIDO TRANSFER	//
//Consulta Nro Pedido para Crear el nuevo.
$nroPedido	= DataManager::dacLastId('pedidos_transfer', 'ptidpedido');
for($i = 0; $i < count($idArt); $i++){
	$ptObject	= ($pId) ? DataManager::newObjectOfClass('TPedidostransfer', $pId) : DataManager::newObjectOfClass('TPedidostransfer');
	$ptObject->__set('IDPedido'			, $nroPedido);
	$ptObject->__set('IDVendedor'		, $_SESSION["_usrid"]);
	$ptObject->__set('ParaIdUsr'		, $usrAsignado); //NO LLEGA
	$ptObject->__set('IDDrogueria'		, $idDrogueria);
	$ptObject->__set('ClienteDrogueria'	, $nroClienteDrog);
	$ptObject->__set('ClienteNeo'		, $idCuenta);
	$ptObject->__set('RS'				, $nombreCuenta); //sanear_string
	$ptObject->__set('Cuit'				, $cuit);
	$ptObject->__set('Domicilio'		, $domicilio); //sanear_string
	$ptObject->__set('Contacto'			, $contacto); //sanear_string
	$ptObject->__set('Articulo'			, $idArt[$i]);
	$ptObject->__set('Precio'			, $precio[$i]);
	$ptObject->__set('Unidades'			, $cant[$i]);
	$ptObject->__set('Descuento'		, $desc[$i]);
	$ptObject->__set('CondicionPago'	, $condPago[$i]);
	$ptObject->__set('FechaPedido'		, date('Y-m-d H:i:s'));
	$ptObject->__set('Activo'			, '1');	
	
	$ptObject->__set('IDAdmin'			, '0');	
	$ptObject->__set('IDNombreAdmin'	, '');	
	$ptObject->__set('FechaExportado'	, date('2001-01-01'));
	$ptObject->__set('Liquidado'		, '0');
	
	
  if ($pId) {
	  echo "Error. PID.";
  } else {
	  $ptObject->__set('ID'				, $ptObject->__newID());
	  $ID = DataManager::insertSimpleObject($ptObject);
  }	
}

echo "1";
?>