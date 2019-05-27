<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$usrAsignado	= (isset($_POST['pwusrasignado']))	? $_POST['pwusrasignado'] 	: NULL;
$empresa		= (isset($_POST['empselect']))		? $_POST['empselect'] 		: NULL;
$laboratorio	= (isset($_POST['labselect']))		? $_POST['labselect'] 		: NULL;
$idCuenta		= (isset($_POST['pwidcta']))		? $_POST['pwidcta'] 		: NULL;
$nroOrden		= (isset($_POST['pworden']))		? $_POST['pworden'] 		: NULL;
$condPago		= (isset($_POST['condselect']))		? $_POST['condselect'] 		: NULL;
$observacion	= (isset($_POST['pwobservacion']))	? $_POST['pwobservacion']	: NULL;
$idCondComercial= (isset($_POST['pwidcondcomercial']))? $_POST['pwidcondcomercial'] : NULL;
$propuesta		= (isset($_POST['pwpropuesta']))	? $_POST['pwpropuesta'] 	: NULL;
$idPropuesta	= (isset($_POST['pwidpropuesta']))	? $_POST['pwidpropuesta'] 	: NULL;
$estado			= (isset($_POST['pwestado']))		? $_POST['pwestado'] 		: NULL; //estado de la propuesta
$articulosIdArt	= (isset($_POST['pwidart'])) 		? $_POST['pwidart'] 		: NULL;
$articulosCant	= (isset($_POST['pwcant'])) 		? $_POST['pwcant'] 			: NULL;
$articulosPrecio= (isset($_POST['pwprecioart']))	? $_POST['pwprecioart'] 	: NULL;
$articulosB1	= (isset($_POST['pwbonif1'])) 		? $_POST['pwbonif1'] 		: NULL;
$articulosB2	= (isset($_POST['pwbonif2'])) 		? $_POST['pwbonif2'] 		: NULL;
$articulosD1	= (isset($_POST['pwdesc1'])) 		? $_POST['pwdesc1'] 		: NULL;
$articulosD2	= (isset($_POST['pwdesc2'])) 		? $_POST['pwdesc2'] 		: NULL;
$tipoPedido		= (isset($_POST['pwtipo'])) 		? $_POST['pwtipo'] 			: NULL;
$lista			= (isset($_POST['pwlista']))		? $_POST['pwlista'] 		: 0;

//-----------------------//
// 	Controles Generales	 //
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/pedidos/logica/ajax/controlPedido.php" );

//-----------------------------------------------------------------------//
// Estados de un PEDIDO	
//-Un pedido con $idCondComercial, pasará directo como CIERRE
//-Si es PROPUESTA: pasa por PROPUESTA - PENDIENTE - APROBADO/RECHAZADO - CIERRE
//-Si es APROBADO: podrá: CIERRE (pedido enviado) - ELIMINAR (eliminará el pedido pero no los registros de estados de las propuestas) - EDITAR (volviendo a PENDIENTE)
//-Si es RECHAZADO podrá: ELIMINAR o EDITAR (volviendo a pasar a PENDIENTE)
if($propuesta) {
	//Si es una propuesta, NO SE GRABA UN NÚMERO DE PEDIDO, se crea una PROPUESTA
	switch($estado){
		case '0': //CERRADA
			//Ya fue emitida como pedido (pre-facturada).
			echo "La propuesta fue CERRADA, no puede modificarla. Realice un nuevo pedido."; exit;
			break;
		case '1':
			//PENDIENTE
			//Si se envía estando PENDIENTE, significaría que se realizaron cambios, 
			//por lo que habría que pasar la propuesta a Rechazada y se crearía una nueva,
			//pendiente a aprobar.
			//Pasa la propuesta actual a RECHAZADA y de INSERTA UNA NUEVA PROPUESTA
			$propuestaObject	=	DataManager::newObjectOfClass('TPropuesta', $idPropuesta);
			$propuestaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
			$propuestaObject->__set('LastUpdate'	, date("Y-m-d H:i:s"));
			$propuestaObject->__set('FechaCierre'	, date("2001-01-01"));
			$propuestaObject->__set('Estado'		, 3); //Pasa a RECHAZADA
			$propuestaObject->__set('Activa'		, 0);
			DataManager::updateSimpleObject($propuestaObject);
			
			//Cargo el ESTADO de la propuesta RECHAZADA
			$estadoObject	=	DataManager::newObjectOfClass('TEstado');
			$estadoObject->__set('Origen'	, 'TPropuesta');
			$estadoObject->__set('IDOrigen'	, $idPropuesta);
			$estadoObject->__set('Fecha'	, date("Y-m-d H:i:s"));
			$estadoObject->__set('UsrCreate', $_SESSION["_usrid"]);
			$estadoObject->__set('Estado'	, 3);
			$estadoObject->__set('Nombre'	, 'Pendiente');
			$estadoObject->__set('ID'		, $estadoObject->__newID());
			$IDEst	= DataManager::insertSimpleObject($estadoObject);
			if(empty($IDEst)){ 
				echo "No se grab&oacute; correctamente el estado de la propuesta."; exit;
			}
			//-------------------------//
			//CREA UNA PROPUESTA NUEVA	
			$propuestaObject	=	DataManager::newObjectOfClass('TPropuesta');
			$propuestaObject->__set('Nombre'		, 'Pedido');
			$propuestaObject->__set('Tipo'			, 'Venta');
			$propuestaObject->__set('Estado'		, 1);
			$propuestaObject->__set('Cuenta'		, $idCuenta);
			$propuestaObject->__set('Empresa'		, $empresa);
			$propuestaObject->__set('Laboratorio'	, $laboratorio);
			$propuestaObject->__set('Fecha'			, date("Y-m-d H:i:s"));
			$propuestaObject->__set('FechaCierre'	, date("2001-01-01"));
			$observacion	=	substr($observacion, 0, 250);
			$propuestaObject->__set('Observacion'	, $observacion);			
			$propuestaObject->__set('UsrCreate'		, $_SESSION["_usrid"]);
			$propuestaObject->__set('UsrAsignado'	, $usrAsignado);
			$propuestaObject->__set('LastUpdate'	, date("Y-m-d H:i:s"));
			$propuestaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
			
			$propuestaObject->__set('Activa'		, 1); //Queda activo porque aún no está APROBADA
			$propuestaObject->__set('ID'			, $propuestaObject->__newID());						
			$IDProp	= DataManager::insertSimpleObject($propuestaObject);
			if(empty($IDProp)){ 
				echo "No se grab&oacute; correctamente la propuesta."; exit;
			}
			
			for($i = 0; $i < count($articulosIdArt); $i++){
				$cant	=	$articulosCant[$i];
				$idArt	=	$articulosIdArt[$i];
				$b1		=	empty($articulosB1[$i])		? 0	:	$articulosB1[$i];
				$b2		=	empty($articulosB2[$i])		? 0	:	$articulosB2[$i];
				$d1		= 	empty($articulosD1[$i])		? 0	:	$articulosD1[$i]; 
				$d2		= 	empty($articulosD2[$i])		? 0	:	$articulosD2[$i];
				$precio	=	empty($articulosPrecio[$i])	? 0	:	$articulosPrecio[$i];
				
				$propDetalleObject	=	DataManager::newObjectOfClass('TPropuestaDetalle');
				$propDetalleObject->__set('IDPropuesta'		,	$IDProp);
				$propDetalleObject->__set('CondicionPago'	,	$condPago);
				$propDetalleObject->__set('IDArt'			,	$idArt);
				$propDetalleObject->__set('Cantidad'		,	$cant);
				$propDetalleObject->__set('Precio'			,	$precio);
				$propDetalleObject->__set('Bonificacion1'	,	$b1);
				$propDetalleObject->__set('Bonificacion2'	,	$b2);
				$propDetalleObject->__set('Descuento1'		,	$d1);
				$propDetalleObject->__set('Descuento2'		, 	$d2);
				$propDetalleObject->__set('Estado'			,	1);
				$propDetalleObject->__set('Fecha'			,	date("Y-m-d H:i:s"));
				$propDetalleObject->__set('Activo'			,	1);
				$propDetalleObject->__set('ID'				,	$propDetalleObject->__newID());
						
				$IDProrDet	= DataManager::insertSimpleObject($propDetalleObject);
				if(empty($IDProrDet)){ 
					echo "No se grabaron correctamente los art&iacute;culos."; exit;
				}
			}
						
			//Cargo el ESTADO de la propuesta PENDIENTE
			$estadoObject	=	DataManager::newObjectOfClass('TEstado');
			$estadoObject->__set('Origen'	, 'TPropuesta');
			$estadoObject->__set('IDOrigen'	, $IDProp);
			$estadoObject->__set('Fecha'	, date("Y-m-d H:i:s"));
			$estadoObject->__set('UsrCreate', $_SESSION["_usrid"]);
			$estadoObject->__set('Estado'	, 1); //pendiente
			$estadoObject->__set('Nombre'	, 'Pendiente');
			$estadoObject->__set('ID'		, $estadoObject->__newID());
			$IDEst	= DataManager::insertSimpleObject($estadoObject);
			if(empty($IDEst)){
				echo "No se grab&oacute; correctamente el estado de la propuesta."; exit;
			}
			echo "1"; exit;	//echo "queda PENDIENTE de Aprobar/Rechazar.";
			break;
		case '2':	//APROBADO
			//Si la propuesta está aprobada, consulta y carga los datos del pedido y cambia el estado a CERRADA
			$propuestaObject=	DataManager::newObjectOfClass('TPropuesta', $idPropuesta);
			$idCuenta		=	$propuestaObject->__get('Cuenta');
			$empresa		=	$propuestaObject->__get('Empresa');
			$laboratorio	=	$propuestaObject->__get('Laboratorio');
			$usrAsignado	=	$propuestaObject->__get('UsrAsignado');
			$observacion	= 	"PROPUESTA ".$idPropuesta.": ".$observacion;
			$observacion	=	substr($observacion, 0, 250);
			$propuestaObject->__set('Observacion'	, $observacion);
			$propuestaObject->__set('FechaCierre'	, date("Y-m-d H:i:s"));
			$propuestaObject->__set('Estado'		, 0);
			$propuestaObject->__set('Activa'		, 0); 
			//Queda activo porque aún no está APROBADA
			DataManager::updateSimpleObject($propuestaObject);
			//Cargo el ESTADO de la propuesta a CERRADA
			$estadoObject	=	DataManager::newObjectOfClass('TEstado');
			$estadoObject->__set('Origen'	, 'TPropuesta');
			$estadoObject->__set('IDOrigen'	, $idPropuesta);
			$estadoObject->__set('Fecha'	, date("Y-m-d H:i:s"));
			$estadoObject->__set('UsrCreate', $_SESSION["_usrid"]);
			$estadoObject->__set('Estado'	, 0); //cerrado
			$estadoObject->__set('Nombre'	, 'Cerrado');
			$estadoObject->__set('ID'		, $estadoObject->__newID());
			$IDEst	= DataManager::insertSimpleObject($estadoObject);
			if(empty($IDEst)){ 
				echo "No se grab&oacute; correctamente el CIERRE de la propuesta."; exit;
			}
			
			$detalles	= 	DataManager::getPropuestaDetalle($idPropuesta);
			if ($detalles) { 
				unset($articulosIdArt, $articulosCant, $articulosPrecio, $articulosB1, $articulosB2, $articulosD1, $articulosD2);
				foreach ($detalles as $j => $det) {	
					$condPago			= 	$det["pdcondpago"];
					$articulosIdArt[]	=	$det['pdidart'];
					$articulosCant[]	=	$det['pdcantidad'];
					$articulosPrecio[]	=	round($det['pdprecio'], 3);
					$articulosB1[]		=	($det['pdbonif1'] == 0)	?	''	:	$det['pdbonif1'];
					$articulosB2[]		=	($det['pdbonif2'] == 0)	?	''	:	$det['pdbonif2'];
					$articulosD1[]		=	($det['pddesc1'] == 0)	?	''	:	$det['pddesc1'];
					$articulosD2[]		=	($det['pddesc2'] == 0)	?	''	:	$det['pddesc2'];					
				}
			}
			break;
		case '3':  //RECHAZADA
			//Si el pedido fue RECHAZADO deberá crear una nueva propuesta.
			echo "La propuesta ya fue RECHAZADA. Cree una nueva propuesta si lo desea."; exit;
			break;
		default: 
			//CREA PROPUESTA
			//Al inicio $estado no está definico, por lo que se crea la propuesta			
			$propuestaObject	=	DataManager::newObjectOfClass('TPropuesta');
			$propuestaObject->__set('Nombre'		, 'Pedido');
			$propuestaObject->__set('Tipo'			, 'Venta');
			$propuestaObject->__set('Estado'		, 1);
			$propuestaObject->__set('Cuenta'		, $idCuenta);
			$propuestaObject->__set('Empresa'		, $empresa);
			$propuestaObject->__set('Laboratorio'	, $laboratorio);
			$propuestaObject->__set('Fecha'			, date("Y-m-d H:i:s"));
			$propuestaObject->__set('FechaCierre'	, date("2001-01-01"));
			$observacion	=	substr($observacion, 0, 250);
			$propuestaObject->__set('Observacion'	, $observacion);			
			$propuestaObject->__set('UsrCreate'		, $_SESSION["_usrid"]);
			$propuestaObject->__set('UsrAsignado'	, $usrAsignado);
			$propuestaObject->__set('LastUpdate'	, date("Y-m-d H:i:s"));
			$propuestaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
			$propuestaObject->__set('Activa'		, 1); //Queda activo porque aún no está APROBADA
			$propuestaObject->__set('ID'			, $propuestaObject->__newID());						
			$IDProp	= DataManager::insertSimpleObject($propuestaObject);
			if(empty($IDProp)){ 
				echo "No se grab&oacute; correctamente la propuesta."; exit;
			}
			for($i = 0; $i < count($articulosIdArt); $i++){
				$cant	=	$articulosCant[$i];
				$idArt	=	$articulosIdArt[$i];
				$b1		=	empty($articulosB1[$i])		? 0	:	$articulosB1[$i];
				$b2		=	empty($articulosB2[$i])		? 0	:	$articulosB2[$i];
				$d1		= 	empty($articulosD1[$i])		? 0	:	$articulosD1[$i]; 
				$d2		= 	empty($articulosD2[$i])		? 0	:	$articulosD2[$i];
				$precio	=	empty($articulosPrecio[$i])	? 0	:	$articulosPrecio[$i];
				
				$propDetalleObject	=	DataManager::newObjectOfClass('TPropuestaDetalle');
				$propDetalleObject->__set('IDPropuesta'		, $IDProp);
				$propDetalleObject->__set('CondicionPago'	, $condPago);
				$propDetalleObject->__set('IDArt'			, $idArt);
				$propDetalleObject->__set('Cantidad'		, $cant);
				$propDetalleObject->__set('Precio'			, $precio);
				$propDetalleObject->__set('Bonificacion1'	, $b1);
				$propDetalleObject->__set('Bonificacion2'	, $b2);
				$propDetalleObject->__set('Descuento1'		, $d1);
				$propDetalleObject->__set('Descuento2'		, $d2);
				$propDetalleObject->__set('Estado'			, 1);
				$propDetalleObject->__set('Fecha'			, date("Y-m-d H:i:s"));
				$propDetalleObject->__set('Activo'			, 1);
				$propDetalleObject->__set('ID'				, $propDetalleObject->__newID());
						
				$IDProrDet	= DataManager::insertSimpleObject($propDetalleObject);
				if(empty($IDProrDet)){ 
					echo "No se grabaron correctamente los art&iacute;culos."; exit;
				}
			}
						
			//Cargo el ESTADO de la propuesta PENDIENTE
			$estadoObject	=	DataManager::newObjectOfClass('TEstado');
			$estadoObject->__set('Origen'	, 'TPropuesta');
			$estadoObject->__set('IDOrigen'	, $IDProp);
			$estadoObject->__set('Fecha'	, date("Y-m-d H:i:s"));
			$estadoObject->__set('UsrCreate', $_SESSION["_usrid"]);
			$estadoObject->__set('Estado'	, 1); //pendiente
			$estadoObject->__set('Nombre'	, 'Pendiente');
			$estadoObject->__set('ID'		, $estadoObject->__newID());
			$IDEst	= DataManager::insertSimpleObject($estadoObject);
			if(empty($IDEst)){ 
				echo "No se grab&oacute; correctamente el estado de la propuesta."; exit;
			}
			echo "1"; exit;//echo "Propuesta creada"; exit;
			break;
	}
} else {
	$estado	=	0; //CERRADO
}

//-------------------//
// 	Generar Pedido	 //
require($_SERVER['DOCUMENT_ROOT']."/pedidos/pedidos/logica/ajax/generarPedido.php" );

echo "1"; exit; ?>