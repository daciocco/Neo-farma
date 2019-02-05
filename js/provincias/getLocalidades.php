<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");

/*Me traigo el codigo Provincia seleccionado, el codigo provincia es la referencia a cada Provincia de la BBDD*/
$codigoProvincia = $_GET['codigoProvincia'];
$datosJSON;	

$_localidades	= DataManager::getLocalidades(); 
if (count($_localidades)) {	
	foreach ($_localidades as $k => $_loc) {		
		if ($codigoProvincia == $_loc["locidprov"]){               		
			$datosJSON[] = $_loc["locnombre"]; 
		}   
	}                            
} 

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>