<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");

/*Me traigo el codigo Provincia seleccionado, el codigo provincia es la referencia a cada Provincia de la BBDD*/
$idProvincia = $_GET['idProvincia'];
$datosJSON;	

$_localidades	= DataManager::getLocalidades(); 
if (count($_localidades)) {
	foreach ($_localidades as $k => $_loc) {		
		if ($idProvincia == $_loc["locidprov"]){               		
			$datosJSON[] = $_loc["locidloc"]."-".$_loc["locnombre"]; 
		}   
	}                            
} 

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>