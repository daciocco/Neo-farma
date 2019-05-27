<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$startDate	= empty($_POST['startDate'])? 0	: $_POST['startDate'];
$endDate	= empty($_POST['endDate'])	? 0	: $_POST['endDate'];
$arrayIdCond= empty($_POST['editSelected'])	? 0	: $_POST['editSelected'];

if(!$arrayIdCond) {
	echo "Seleccione condición para modificar."; exit;
}

//si no es array, lo convierte
if(!is_array($arrayIdCond)){
	$arrayIdCond = array($arrayIdCond); 
}

if(empty($startDate) || empty($endDate)){
	echo "Debe ingresar una fecha a modificar."; exit;
}

//CONTROLAR echo "La fecha de inicio debe ser menor a la fecha de fin"; exit;
//Inicio
$startDate	=	new DateTime($startDate);
$endDate	=	new DateTime($endDate);

if($startDate >= $endDate){
	echo "La fecha de inicio debe ser inferior a la de fin."; exit;
}

foreach ($arrayIdCond as $j => $condId) {
	//Consulto los datos de dicha condición comercial para modificar
	$condicion = DataManager::getCondiciones(0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $condId);
	if(count($condicion) != 1){
		echo "Error al consultar el registro."; exit;	
	}
	
	//----------------
	// Lee Condición //
	if ($condId) {
		$condObject	= DataManager::newObjectOfClass('TCondicionComercial', $condId);
		$condObject->__set('FechaInicio' , $startDate->format("Y-m-d"));
		$condObject->__set('FechaFin'	 , $endDate->format("Y-m-d"));
		DataManagerHiper::updateSimpleObject($condObject, $condId);
		DataManager::updateSimpleObject($condObject);

		// MOVIMIENTO
		$movimiento	=	'FECHAS_INICIO_'.$startDate->format("Y-m-d")."_Y_FIN_".$endDate->format("Y-m-d");
		dac_registrarMovimiento($movimiento, "UPDATE", 'TCondicionComercial', $condId);

	} else {
		echo "No se encuentran registros."; exit;
	}
}

echo '1'; exit;

?>