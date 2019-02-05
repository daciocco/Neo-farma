<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
}
 
//*************************************************
$_nrotalonario	= 	(isset($_REQUEST['nrotalonario']))	?	$_REQUEST['nrotalonario']	: NULL;
$salir = 0;
//*************************************************
	
if((empty($_nrotalonario) or !is_numeric($_nrotalonario))){
	echo "Debe completar el número de talonario correctamente.\n"; $salir = 1; exit;
}

if ($salir == 0){
	$_talorarios	=	DataManager::getBuscarTalonario($_nrotalonario);
	if ($_talorarios){ echo "El talonario ya existe."; exit;
	} else {
		//Consulto último talonario cargado en las rendiciones y que tenga 25 recibos para crear uno nuevo.
		$_ultima_rendicion	=	DataManager::getMaxRendicion($_SESSION["_usrid"]);
		$_rendiciones		=	DataManager::getDetalleRendicion($_SESSION["_usrid"], $_ultima_rendicion);
		if ($_rendiciones){
			$_talAnt = 0;
			foreach ($_rendiciones as $k => $_rend){
				$_rend			=	$_rendiciones[$k];
				//Guardo el mayor nro de Talonario
				$_rendTal		= 	$_rend['Tal'];
				if($_rendTal > $_talAnt){
					$_talAnt		=	$_rendTal;
					$_rendIDRecibo	= 	$_rend['IDRecibo'];
				}
			}
			
			// Cuento cuantos recibos tiene ese talonario
			$_reciboobject	=	DataManager::newObjectOfClass('TRecibos', $_rendIDRecibo);			
			$_max_Talonario =	$_reciboobject->__get('Talonario');
			$_tot_recibos 	=	DataManager::ExistFromDatabase($_reciboobject, 'rectalonario', $_max_Talonario);
			if($_tot_recibos < 25){
				echo "Faltan cargar recibos del talonario $_max_Talonario"; exit;
			} else {
				$_insertar	=	DataManager::insertToTable('talonario_idusr', 'nrotalonario, idusr', "'".$_nrotalonario."','".$_SESSION["_usrid"]."'");
				echo "1"; exit;
			}			
		}else{
			//Por acá significa que no existen talonarios grabados en la tabla
			$_insertar	=	DataManager::insertToTable('talonario_idusr', 'nrotalonario, idusr', "'".$_nrotalonario."','".$_SESSION["_usrid"]."'");
			echo "1"; exit;
			//echo "Error en la consulta de sus rendiciones. Consulte con el administrador de la web.";
		}		
	}
} 

?>