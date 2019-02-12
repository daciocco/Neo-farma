<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){ echo "Invalido"; exit; }

$_mes 		= empty($_POST['mes'])	?	date("m")	:	$_POST['mes'];
$_anio 		= empty($_POST['anio'])	?	date("Y")	:	$_POST['anio'];
$_mes_sig 	= $_POST['mes_sig'];
$_anio_sig	= $_POST['anio_sig'];

if(empty($_mes_sig) || empty($_anio_sig)){echo "Error al cargar el mes o año de destino."; exit;}

//Borro los registros que están en la ddbb del mes siguiente a donde voy a grabar el duplicado, o sea, el mes siguiente.
$_bonificaciones_sig	=	DataManager::getDetalleBonificacion($_mes_sig, $_anio_sig);
if ($_bonificaciones_sig) {
	foreach ($_bonificaciones_sig as $k => $_bonisig){
		$_bonisig		=	$_bonificaciones_sig[$k];
		$_bonisigID		=	$_bonisig['bonifid'];
		
		//borro cualquier id que encuentre
		$_bobject	= DataManager::newObjectOfClass('TBonificacion', $_bonisigID);
		$_bobject->__set('ID',	$_bonisigID );
		$ID = DataManager::deleteSimpleObject($_bobject);
	}
}


//Reccoro la bonif actual para grabarla en la bonif siguiente como nueva
$_bonificaciones	=	DataManager::getDetalleBonificacion($_mes, $_anio);
if ($_bonificaciones) {
	foreach ($_bonificaciones as $k => $_bonif){
		$_bonif			=	$_bonificaciones[$k];
		//$_bonifID		=	$_bonif['bonifid'];
		
		//Creo el objeto nuevo para cargar la bonificacion nueva
		$_bonifobject	= 	DataManager::newObjectOfClass('TBonificacion');		
		$_bonifobject->__set('Empresa', 	'1');
 		$_bonifobject->__set('Articulo', 	$_bonif['bonifartid']);
 		$_bonifobject->__set('Mes', 		$_mes_sig);
 		$_bonifobject->__set('Anio', 		$_anio_sig);
 		$_bonifobject->__set('Precio', 		$_bonif['bonifpreciodrog']);
 		$_bonifobject->__set('Publico', 	$_bonif['bonifpreciopublico']);
 		$_bonifobject->__set('Iva', 		$_bonif['bonifiva']);
 		$_bonifobject->__set('Digitado',	$_bonif['bonifpreciodigitado']);
 		$_bonifobject->__set('Oferta', 		$_bonif['bonifoferta']);
 		$_bonifobject->__set('1A', 	$_bonif['bonif1a']);
 		$_bonifobject->__set('1B', 	$_bonif['bonif1b']);
 		$_bonifobject->__set('1C', 	$_bonif['bonif1c']);
 		$_bonifobject->__set('3A', 	$_bonif['bonif3a']);
 		$_bonifobject->__set('3B', 	$_bonif['bonif3b']);
 		$_bonifobject->__set('3C',	$_bonif['bonif3c']);
		$_bonifobject->__set('6A',	$_bonif['bonif6a']);
		$_bonifobject->__set('6B',	$_bonif['bonif6b']);
		$_bonifobject->__set('6C',	$_bonif['bonif6c']);
		$_bonifobject->__set('12A',	$_bonif['bonif12a']);
		$_bonifobject->__set('12B',	$_bonif['bonif12b']);
		$_bonifobject->__set('12C',	$_bonif['bonif12c']);
		$_bonifobject->__set('24A',	$_bonif['bonif24a']);
		$_bonifobject->__set('24B',	$_bonif['bonif24b']);
		$_bonifobject->__set('24C',	$_bonif['bonif24c']);
		$_bonifobject->__set('36A',	$_bonif['bonif36a']);
		$_bonifobject->__set('36B',	$_bonif['bonif36b']);
		$_bonifobject->__set('36C',	$_bonif['bonif36c']);
		$_bonifobject->__set('48A',	$_bonif['bonif48a']);
		$_bonifobject->__set('48B',	$_bonif['bonif48b']);
		$_bonifobject->__set('48C',	$_bonif['bonif48c']);
		$_bonifobject->__set('72A',	$_bonif['bonif72a']);
		$_bonifobject->__set('72B',	$_bonif['bonif72b']);
		$_bonifobject->__set('72C',	$_bonif['bonif72c']);	
		
		//Inserto la bonif nueva
 		$_bonifobject->__set('ID', $_bonifobject->__newID());
 		$ID = DataManager::insertSimpleObject($_bonifobject);	
	}
	
	$movimiento 	= 'BONIF_DUPLICATE_'.$_mes."-".$_anio."_TO_".$_mes_sig."-".$_anio_sig;
	$movTipo	= 'INSERT';
	
	//**********************//	
	//Registro de movimiento//
	//**********************//
	dac_registrarMovimiento($movimiento, $movTipo, "TBonificacion");
	
		
	echo "1"; exit;
}

echo "Error. No hay datos de bonificación en la página actual para duplicar.";
?>