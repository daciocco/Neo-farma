<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	echo "Usuario no permitido."; 
	exit;
}

$arrayIdCond	=	empty($_POST['condid'])	?	0	:	$_POST['condid'];
if(!$arrayIdCond){
	echo "Seleccione condición para modificar."; exit;
}

if(count($arrayIdCond)){
	foreach ($arrayIdCond as $j => $condId) {
		if ($condId) {
			$condObject		= DataManager::newObjectOfClass('TCondicionComercial', $condId);
			$empresa 		= $condObject->__get('Empresa');
			$laboratorio	= $condObject->__get('Laboratorio');	
			$tipo			= $condObject->__get('Tipo');	
			$nombre			= $condObject->__get('Nombre');	
			
			$articulosCond	= DataManager::getCondicionArticulos($condId);
			if (count($articulosCond)) {								 
				foreach ($articulosCond as $k => $artCond) {	
					$artCond 		= $articulosCond[$k];
					$condArtId		= $artCond['cartid'];
					$condArtIdArt	= $artCond['cartidart'];					
					$condArtPrecio	= $artCond["cartprecio"];		
					
					$artPrecio		= DataManager::getArticulo('artprecio', $condArtIdArt, $empresa, $laboratorio);
					if($artPrecio){
						$condArtObject	= DataManager::newObjectOfClass('TCondicionComercialArt', $condArtId);
						$condArtObject->__set('Precio'	, $artPrecio);	
						DataManager::updateSimpleObject($condArtObject);
						
					} else {
						echo "El artículo $condArtIdArt de la condición $tipo $nombre no se encuentra para actualizar. Verifique y vuelva a intentarlo"; exit;
					}
				}
			}
			//**********************//	
			//* Registro MOVIMIENTO *//
			//**********************//
			$movimiento	=	'COND_COMERCIAL_ACTUALIZA_PRECIOS_CONDID_'.$condId;
			dac_registrarMovimiento($movimiento, "UPDATE", 'TCondicionComercial', $condId);
			
		} else {
			echo "Error al consultar condición."; exit;
		}
	}
	echo "1"; exit;
} else {
	echo "Seleccione una condición."; exit;
}

echo "Error de proceso en condiciones."; exit;
?>