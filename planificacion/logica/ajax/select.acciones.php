<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//Éste header se utiliza porqe sino los caracteres con acento los pasa como signos de pregunta
 header("Content-Type: text/plain; charset=iso-8859-1"); 
 //*******************************************	
 //	CONSULTO ACCIONES ACTIVAS
 //*******************************************
 $_accobject	=	DataManager::getAcciones('', '', 1);
 if (count($_accobject)) {	
	foreach ($_accobject as $k => $_accion) {
		$_array_acid 		= (empty($_array_acid)) 	? $_array_acid=$_accion["acid"] 			: $_array_acid.",".$_accion["acid"];	
		$_array_acnombre	= (empty($_array_acnombre)) ? $_array_acnombre=$_accion["acnombre"] 	: $_array_acnombre.",".$_accion["acnombre"];
	}
	$_acciones	=	$_array_acid."/".$_array_acnombre;
	echo $_acciones;
 } else {
	 echo "0/0";
	//echo "No se han encontrado acciones registradas para cargar."; 
 }
?>