<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

$condiciones		= (!empty($_POST['condicion']))	? $_POST['condicion']	:	'';
$listaCondiciones	= empty($condiciones) ? 0 : explode(",", $condiciones);

//------------------------------//
//	Cargo condiciones de pago	//
$condicionesPago	=	DataManager::getCondicionesDePago(0, 100, 1);
if (count($condicionesPago)) {	
	foreach ($condicionesPago as $k => $cond) {				
		$idCond		= $cond['condid'];	
		$condCodigo	= $cond['IdCondPago'];	
		$condNombre	= DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $cond['condtipo']);
		
		$condDias	= "(";					
		$condDias	.= empty($cond['Dias1CP']) ? '' : $cond['Dias1CP'];
		$condDias	.= empty($cond['Dias2CP']) ? '' : ', '.$cond['Dias2CP'];
		$condDias	.= empty($cond['Dias3CP']) ? '' : ', '.$cond['Dias3CP'];
		$condDias	.= empty($cond['Dias4CP']) ? '' : ', '.$cond['Dias4CP'];
		$condDias	.= empty($cond['Dias5CP']) ? '' : ', '.$cond['Dias5CP'];
		$condDias	.= " Días)";					
		$condPorc	= ($cond['Porcentaje1CP']== '0.00') ? '' : $cond['Porcentaje1CP'];
		
		//Descarto la opción FLETERO porque se supone que ya no debería usarse
		if(trim($condNombre) != "FLETERO"){	
			if($listaCondiciones) {
				for ($i = 0; $i < count($listaCondiciones); $i++) {
					if($condCodigo == $listaCondiciones[$i]){					
						$_datos[]	= 	array(
							"condcodigo"=> $condCodigo,
							"nombre" 	=> $condNombre,
							"dias" 		=> $condDias,
							"porc" 		=> $condPorc							
						);
					}
				} 
			} else {
				$_datos[]	=	array(
					"condcodigo"=> $condCodigo,
					"nombre" 	=> $condNombre,
					"dias" 	=> $condDias,
					"porc" 	=> $condPorc							
				);
			}	
		}
	}
	$objJason = json_encode($_datos);
	echo $objJason;					  
} else {
	  echo "Error al cargar condiciones de pago"; exit;
}
?>