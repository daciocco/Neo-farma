<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$arrayIdCond	=	empty($_POST['condid'])	?	0	:	$_POST['condid'];
if(!$arrayIdCond){
	echo "Seleccione condición para eliminar."; exit;
}

if(count($arrayIdCond)){
	foreach ($arrayIdCond as $j => $condId) {
		if ($condId) {
			$condObject	=	DataManager::newObjectOfClass('TCondicionComercial', $condId);
			$condObject->__set('ID', $condId);
			DataManagerHiper::deleteSimpleObject($condObject, $condId);
			DataManager::deleteSimpleObject($condObject);

			//Borra los detalles de artículos
			$artCondicion = DataManager::getCondicionArticulos($condId);
			if (count($artCondicion)) {								 
				foreach ($artCondicion as $k => $detArt) {
					$detArt 	= $artCondicion[$k];
					$detId		= $detArt['cartid'];
					$detIdArt	= $detArt['cartidart'];

					$artCondObject	=	DataManager::newObjectOfClass('TCondicionComercialArt', $detId);
					$artCondObject->__set('ID',	$detId);
					DataManagerHiper::deleteSimpleObject($artCondObject, $detId);
					DataManager::deleteSimpleObject($artCondObject);

					//Borra las bonificaciones del artículo			
					$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $detIdArt);
					if (count($articulosBonif)) {								 
						foreach ($articulosBonif as $j => $artBonif) {	
							$IDCondBonif		=	$artBonif['cbid'];
							$condBonifObject	=	DataManager::newObjectOfClass('TCondicionComercialBonif', $IDCondBonif);
							$condBonifObject->__set('ID',	$IDCondBonif);
							DataManagerHiper::deleteSimpleObject($condBonifObject, $IDCondBonif);
							DataManager::deleteSimpleObject($condBonifObject);		
						}
					}
				}
			}

			//------------//	
			// Movimiento //
			$movimiento	=	'DeleteCondicion';
			dac_registrarMovimiento($movimiento, "DELETE", 'TCondicionComercial', $condId);
		} else {
			echo "Error al consultar los registros."; exit;
		}		
	}
	echo "1"; exit;
} else {
	echo "Seleccione una condición."; exit;
}

echo "Error de proceso."; exit;
?>