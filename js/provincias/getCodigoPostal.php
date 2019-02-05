<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");

/*Me traigo el codigo Localidad seleccionado, el codigo localidad es la referencia a cada Localidad de la BBDD*/
$idLocalidad = $_GET['idLocalidad'];
$datosJSON;	

$_localidades	= DataManager::getLocalidades(); 
if (count($_localidades)) {	
	foreach ($_localidades as $k => $_loc) {		
		if ($idLocalidad == $_loc["locidloc"]){          		
			$datosJSON[] = $_loc["loccodpostal"]; 
		}   
	}                            
} 

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>