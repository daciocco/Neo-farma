<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
}

 
//*************************************************
$_nrotalonario	= 	(isset($_REQUEST['nrotalonario']))	?	$_REQUEST['nrotalonario']	: NULL;
$_nrorecibo		= 	(isset($_REQUEST['nrorecibo']))		?	$_REQUEST['nrorecibo']		: NULL;
$salir 			= 	0;
//*************************************************

if((empty($_nrotalonario) or !is_numeric($_nrotalonario)) and $salir == 0){
	echo "Debe completar el número de talonario correctamente.\n"; $salir = 1; exit;
}

if((empty($_nrorecibo) or !is_numeric($_nrorecibo)) and $salir == 0){
	echo "Debe completar el número de recibo correctamente.\n"; $salir = 1; exit;
}

//*************************************************
if($salir == 0){
	//BUSCA EL talonario DE talonario_idusr
	$_talorarios	=	DataManager::getBuscarTalonario($_nrotalonario);
	if(count($_talorarios)){				
		foreach ($_talorarios as $k => $_talonario){	
			$_talonario		=	$_talorarios[$k];				
			$_usuario	=	$_talonario["idusr"];
			if ($_usuario != $_SESSION["_usrid"]){		
				echo "El número de talonario corresponde a otro Vendedor"; exit;
			}
		}	
		
		//******************//
		//		CONTROL		//
		//******************//
		//Controlo que el Talonario y Recibo no esté ya cargado en la tabla
		$_recibos	=	DataManager::getRecibos($_nrotalonario, $_nrorecibo);
		if(count($_recibos)){ echo "Este recibo ya existe. Intente nuevamente"; exit;
		} else {
			//Si no existe, veo si puede cargarlo en la rendicion actual	
			
					
			//BUSCA el nro MAX de Recibo del Talonario indicado
			/*$_max_recibo	=	DataManager::getMaxRecibo($_nrotalonario);
       		$_nro_siguiente = 	$_max_recibo + 1;	
			if (($_nrorecibo <=  $_max_recibo || $_nrorecibo > $_nro_siguiente)){ //¿¿¿¿ && $max_rec != 0 ???		
				echo "El número de recibo [ $_nrorecibo ] no es válido. Debe ser $_nro_siguiente."; exit;
			}	*/
			
			//CONTROLA que el Recibo > Menor Nro Recibo && Recibo < (Menor Nro Recibo + 24) 
			$_min_recibo	=	DataManager::getMinRecibo($_nrotalonario);
			if($_min_recibo){
				//si la consulta es != nula	 (existe algún recibo)		
				$_max_recibo 	= 	$_min_recibo + 25;	
				if (($_nrorecibo <  $_min_recibo || $_nrorecibo > $_max_recibo)){
					//Si está dentro del rango
					echo "El número de recibo [ $_nrorecibo ] no es válido. Debe estar entre ".($_min_recibo + 1)." y $_max_recibo."; exit;
				}
			}
		}
	} else { echo "1"; } //NO EXISTE EL TALONARIO POR LO CUAL ANTES DEBE CREARLO
} 
?>
