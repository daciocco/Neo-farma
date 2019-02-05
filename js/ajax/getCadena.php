<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$idEmpresa = $_GET['idEmpresa'];
$datosJSON;	

$cadenas	= DataManager::getCadenas($idEmpresa); 
if (count($cadenas)) {
	foreach ($cadenas as $k => $cadena) {		
		if ($idEmpresa == $cadena["IdEmpresa"]){               		
			$datosJSON[] = $cadena["IdCadena"]."-".$cadena["NombreCadena"]; 
		}   
	}                            
} 

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>