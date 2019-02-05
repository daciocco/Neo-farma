<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

$artId			= (isset($_POST['artId']))			?	$_POST['artId'] 	: 	NULL;
if(empty($artId)){ exit;}

$formulas = DataManager::getArticuloFormula( $artId );
if (count($formulas)) {
	foreach ($formulas as $k => $form) {
		$fmId			=	$form["afid"];
		$fmIfa			=	$form["afifa"];
		$fmCant			=	$form["afcantidad"];
		$fmMedida		=	$form["afumedida"];
		$fmIfaComo		=	$form["afifacomo"];
		$fmCantComo		=	$form["afcantidadcomo"];
		$fmMedidaComo	=	$form["afumedidacomo"];						
		echo "<script>";
		echo "javascript:dac_cargarFormula('".$fmId."', '".$fmIfa."', '".$fmCant."', '".$fmMedida."', '".$fmIfaComo."', '".$fmCantComo."', '".$fmMedidaComo."');";
		echo "</script>";		
		
		((($k % 2) == 0)? $clase="par" : $clase="impar");	
		
		echo "<tr id=formula".$fmId." class=".$clase."  onclick=\"javascript:dac_cargarFormula('$fmId', '$fmIfa', '$fmCant', '$fmMedida', '$fmIfaComo', '$fmCantComo', '$fmMedidaComo' )\" style=\"cursor:pointer;\"><td>".$idCuenta."</td><td>".$nombre."</td><td>".$localidad."</td></tr>";
		
	}                       
} 
exit;
?>