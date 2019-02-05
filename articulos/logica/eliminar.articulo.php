<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;	
}

$artId		= empty($_REQUEST['artid']) 	? 0 					: $_REQUEST['artid'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/articulos/'	: $_REQUEST['backURL'];

if ($artId) {
	// Elimia artículo, dispone y fórmula
	$artObject		= DataManager::newObjectOfClass('TArticulo', $artId);
	$artIdDispone	= $artObject->__get('Dispone');
	$artImagen	 	= $artObject->__get('Imagen');	
	$artObject->__set('ID', $artId );
	DataManager::deleteSimpleObject($artObject);
	
	//FALTA desarrollar ELIMINAR IMAGEN DEL ARTÍCULO
	if($artImagen){
		$rutaFile		=	"/pedidos/images/imagenes/";
		$imagenObject	= DataManager::newObjectOfClass('TImagen', $idImagen);
		$imagenObject->__set('Imagen' , $imagenNombre);
		
		$dir = $_SERVER['DOCUMENT_ROOT'].$rutaFile.$artImagen;
		if (file_exists($dir)) { unlink($dir); }			
	}
	//---------------
	
	if($artIdDispone){
		$dispObject	= DataManager::newObjectOfClass('TArticuloDispone', $artIdDispone);
		$dispObject->__set('ID', $artIdDispone);
		DataManager::deleteSimpleObject($dispObject);
		
		$formulas = DataManager::getArticuloFormula( $artId );
		if (count($formulas)) {
			foreach ($formulas as $k => $form) {
				$fmId	= $form["afid"];		
				
				$formObject	= DataManager::newObjectOfClass('TArticuloFormula', $fmId);
				$formObject->__set('ID',	$fmId );
				DataManager::deleteSimpleObject($formObject);
			}
		}
	}
}
 
header('Location: '.$backURL);
?>