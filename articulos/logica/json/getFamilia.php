<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );

$idEmpresa = $_GET['idEmpresa'];
$datosJSON;	

$familias	= DataManager::getCodFamilias(0,0,$idEmpresa); 
if (count($familias)) {
	foreach ($familias as $k => $flia) {
		$idFlia		= $flia["IdFamilia"];
		$nombreFlia	= $flia["Nombre"];
		$datosJSON[] = $idFlia."-".$nombreFlia; 
	}    
} 

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>