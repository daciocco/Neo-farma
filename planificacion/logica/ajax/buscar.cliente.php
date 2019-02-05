<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

 //*************************************************
 $_idcliente 	= 	(isset($_POST['idcliente']))? $_POST['idcliente'] 	: NULL;
 $_posicion 	= 	(isset($_POST['posicion']))	? $_POST['posicion'] 	: NULL;
 $_tipo 		= 	(isset($_POST['tipo']))		? $_POST['tipo'] 		: NULL;
 
 //*************************************************
 //Controlo campo
 //************************************************* 
 if ($_tipo == 0){ //si es planif //se pidió en la semana anterior al 06-03-2015 y se pide que se quite la semana del  08-05-15
 	if (!is_numeric($_idcliente)){
		echo "El Código de cliente debe ser numérico."; exit;
 	}
 } else {//si es parte diario
 	if ($_idcliente == "0" || $_idcliente == ""){ //en caso de cliente nuevo con entrada CERO
		echo 0; exit;
		//echo "Está cargando un cliente nuevo"; exit;
	} else { 
		if (!is_numeric($_idcliente)){
			//echo 1; exit;
			echo "El Código de cliente debe ser numérico. En caso de ser un cliente nuevo. Debe colocarse el nro de cliente 0 (cero)"; 		
			exit;
 		}
	}
 }

//*********************************	
//	Control de Existencia de Cuenta
//*********************************  
$ctaIdCta	= 	DataManager::getCuenta('ctaid', 'ctaidcuenta', $_idcliente, 1);		
if($ctaIdCta) {
	$_nombre	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_idcliente, 1);
	$_direccion	= 	DataManager::getCuenta('ctadireccion', 'ctaidcuenta', $_idcliente, 1)." ".DataManager::getCuenta('ctadirnro', 'ctaidcuenta', $_idcliente, 1);
	echo "1/$_posicion/$_nombre/$_direccion"; exit;
} else {
	echo "La cuenta no se encuentra."; exit;
}
?>