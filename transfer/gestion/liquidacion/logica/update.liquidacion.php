<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_mes 		= $_POST['mes'];
$_anio 		= $_POST['anio'];
$_drogid	= $_POST['drogid'];
$_conciliar	= $_POST['conciliar'];

//Arrays 
$_idliquid	= explode("|", substr($_POST['idliquid'], 	1));
$_fecha		= explode("|", substr($_POST['fecha'],	 	1));
$_transfer	= explode("|", substr($_POST['transfer'], 	1));
$_fechafact	= explode("|", substr($_POST['fechafact'], 	1));
$_desc		= explode("|", substr($_POST['desc'], 		1));
$_nrofact	= explode("|", substr($_POST['nrofact'], 	1));
$_ean		= explode("|", substr($_POST['ean'], 		1));
$_idart		= explode("|", substr($_POST['idart'], 		1));
$_cant		= explode("|", substr($_POST['cant'],	 	1));
$_unitario	= explode("|", substr($_POST['unitario'], 	1));
$_importe	= explode("|", substr($_POST['importe'], 	1));
$_estado	= explode("|", substr($_POST['estado'], 	1));

/*****************/
//Control de Datos
/*****************/
if(empty($_drogid) || $_drogid==0){
	echo "Debe seleccionar una droguería"; exit;
}

for($i=0; $i<count($_idliquid); $i++){	

	if(empty($_transfer[$i])){
		echo "Error en número de transfer en la fila ".($i+1); exit;
	}

	if(empty($_fechafact[$i])){
		echo "Error en fecha de factura en la fila ".($i+1); exit;
	}
	
	if(empty($_nrofact[$i])){
		echo "Error en número de factura en la fila ".($i+1); exit;
	}
	
	if(empty($_ean[$i]) || !is_numeric($_ean[$i])){
		echo "Error de número EAN en la fila ".($i+1); exit;
	}
	
	if(empty($_cant[$i]) || !is_numeric($_cant[$i])){
		echo "Error de cantidad en la fila ".($i+1); exit;
	}
		
	if(empty($_unitario[$i]) || !is_numeric($_unitario[$i])){
		echo "Error de PSL Unitario en la fila ".($i+1); exit;
	}
	
	if(empty($_desc[$i]) || !is_numeric($_desc[$i])){
		echo "Error de descuento en la fila ".($i+1)." (quite el simbolo '%')"; exit;
	}
	
	if(empty($_importe[$i]) || !is_numeric($_importe[$i])){
		echo "Error de Importe NC en la fila ".($i+1); exit;
	}
}


//**********************//
//	Edito Liquidación	//
//**********************//
for($i=0; $i<count($_idliquid); $i++){	
	if (!empty($_transfer[$i])){
		//**********************************//
		//	Registro Estado del Transfer	// LT (Liquidado Total) | LP (Liquidado PArcial) | LE (Liquidado Excedente)
		//**********************************//
		//Consulto transfer para que devuelva idtransfers y editar estado de artículo.
		$_ptransfers	=	DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_transfer[$i]); //DataManager::getDetallePedidoTransfer($_transfer[$i]);
		if ($_ptransfers) {
			foreach ($_ptransfers as $j => $_pt){
				$_pt		=	$_ptransfers[$j];
				$_ptID		=	$_pt['ptid'];
				$_ptidart	=	$_pt['ptidart'];
				
				if ($_idart[$i] == $_ptidart){		
					//EDITA ESTADO DEL ARTÍCULO TRANSFER		
					$_ptobject	=	DataManager::newObjectOfClass('TPedidostransfer', $_ptID);				
					$_ptobject->__set('Liquidado', 	$_estado[$i]);													
					$ID = DataManager::updateSimpleObject($_ptobject);
				}
			}
		} else {
			//echo "El pedido transfer ".$_transfer[$i]." no se encuentra o es inexistente"; exit;
		}
		
		//EDITA ESTADO DEL ARTÍCULO LIQUIDADO
		$_liqobject	=	DataManager::newObjectOfClass('TLiquidacion', $_idliquid[$i]);				
		$_liqobject->__set('Activa',	1);													
		$ID = DataManager::updateSimpleObject($_liqobject);	
	} else {
		echo "Ocurrió un error al querer actualizar el estado de transfers. Verifique el transfer ".$_transfer[$i]." y vuelva a intentarlo."; exit;
	}
		
}

echo "1";



//borro los registros que están en la ddbb y ya no están en la tabla
/*$_liquidaciones		=	DataManager::getDetalleLiquidacion($_mes, $_anio, $_drogid, 'TL');
if ($_liquidaciones) {
	foreach ($_liquidaciones as $k => $_liquid){
		$_liquid		=	$_liquidaciones[$k];
		$_liquidID		=	$_liquid['liqid'];
		
		//busco si hay algún artículo que no esté en la tabla
		$encontrado = 0; $i = 0;
		while(($i<count($_idliquid)) && ($encontrado == 0)){
			if(($_liquidID == $_idliquid[$i])){ $encontrado = 1; }
			$i++;
		}
		
		if ($encontrado == 0){ //borrar de la ddbb $_liquidID
			$_liquidobject	= DataManager::newObjectOfClass('TLiquidacion', $_liquidID);
			$_liquidobject->__set('ID',	$_liquidID );
			$ID = DataManager::deleteSimpleObject($_liquidobject);
		}
	}
}*/


//el siguiente control no se hace, porque puede haber de un mismo transfer liquidado el mismo mes, pero separados por una fecha de factura diferente.
//**********************************//
//		Controlo para conciliar		// //Controlo Que no se repita en un mismo Nro transfer un número EAN	
//**********************************//  
/*for($i = 0; $i < count($_idliquid); $i++){
	$cont = 0;
	for($j = 0; $j < count($_idliquid); $j++){			
		if($_transfer[$i] == $_transfer[$j]){
			if($_ean[$i] == $_ean[$j]){
				$cont = $cont + 1;	
			}				
		}
	}		
	if ($cont > 1){
		echo "Hay códigos EAN repetidos en la liquidación, Verifique."; exit;
	}
}*/

//REGISTRO SU UN ARTÍCULO SE ACEPTO COMO LT LIQUIDADO TOTAL, para que quede como liqactiva = 1 y así no volver a modificar o consultar por este???
//Recorro nuevamente cada registro para grabar y/o insertar los nuevos registros de liquidacion
//update para los que tengan idliquid e insert para los que no
/*for($i=0; $i<count($_idliquid); $i++){
	$_liquidid		=	empty($_idliquid[$i])	?	0	:	$_idliquid[$i];
	$_liquidobject	= 	($_liquidid) ? DataManager::newObjectOfClass('TLiquidacion', $_liquidid) : DataManager::newObjectOfClass('TLiquidacion');
 	$_liquidobject->__set('Drogueria', 	$_drogid);
	$_liquidobject->__set('Fecha',	 	$_fecha[$i]);
	$_liquidobject->__set('Transfer', 	$_transfer[$i]);
	$_liquidobject->__set('FechaFact', 	dac_invertirFecha( $_fechafact[$i] ) );
	$_liquidobject->__set('NroFact', 	$_nrofact[$i]);
	$_liquidobject->__set('EAN', 		$_ean[$i]);
	$_liquidobject->__set('Cantidad', 	$_cant[$i]);
	$_liquidobject->__set('Unitario', 	$_unitario[$i]);
 	$_liquidobject->__set('Descuento', 	$_desc[$i]);
 	$_liquidobject->__set('ImporteNC', 	$_importe[$i]);
	$_liquidobject->__set('ImporteNC', 	$_importe[$i]);
	
	if($_conciliar == 1){ $_liquidobject->__set('Activa', 1); }	
	
	if ($_liquidid) { //Modifica liquidacion
		$ID = DataManager::updateSimpleObject($_liquidobject);
 	} else { //La liquidacion es nueva
 		$_liquidobject->__set('ID', $_liquidobject->__newID());
 		$ID = DataManager::insertSimpleObject($_liquidobject);
	}	
}*/
?>