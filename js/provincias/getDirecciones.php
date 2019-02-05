<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");

/*Me traigo el codigo Provincia seleccionado, el codigo provincia es la referencia a cada Provincia de la BBDD*/
$codigoProvincia = $_GET['codigoProvincia'];
$datosJSON;	

$_direcciones	= DataManager::getDirecciones(); 
if (count($_direcciones)) {	
	foreach ($_direcciones as $k => $_dir) {		
		if ($codigoProvincia == $_dir["diridprov"]){               		
			$datosJSON[] = $_dir["dirnombre"]; 
		}   
	}                            
} 

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>