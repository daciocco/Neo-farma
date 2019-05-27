<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

$idLoc	=	(isset($_POST['idLoc']))	?	$_POST['idLoc']		:	NULL;
$idProv	=	(isset($_POST['idProv']))	?	$_POST['idProv']	:	NULL;

$cuentas	= DataManager::getCuentas(0, 0, NULL, NULL, '"C","CT","T","TT"', $_SESSION["_usrzonas"]);
if (count($cuentas)) {
	
	$zonas	= DataManager::getZonas(0, 0, 1);
	foreach($zonas as $k => $zona){
		$arrayZonas[]	= $zona['zzona'];
	}	
	$stringZonas = implode(",", $arrayZonas);	
	
	echo '<table id="tblTablaCta" style="table-layout:fixed;">';
	echo '<thead><tr align="left"><th>Emp</th><th>Id</th><th>Nombre</th><th>Localidad</th></tr></thead>';
	echo '<tbody>';

	foreach ($cuentas as $k => $cta) {
		$ctaID		=	$cta["ctaid"];
		$empresa	=	$cta["ctaidempresa"];
		$idCuenta 	= 	$cta["ctaidcuenta"];
		$nombre	 	= 	$cta["ctanombre"];
		$zonaV		= 	$cta["ctazona"];
		$idLoc		= 	$cta["ctaidloc"];
		$localidad	=	'';
		$localidad	=	(empty($idLoc))	? DataManager::getCuenta('ctalocalidad', 'ctaid', $ctaID, $empresa) :	DataManager::getLocalidad('locnombre', $idLoc);

		((($k % 2) == 0)? $clase="par" : $clase="impar");

		if($idCuenta != 0){
			echo "<tr id=cuenta".$ctaID." class=".$clase." onclick=\"javascript:dac_cargarDatosCuenta('$ctaID', '$empresa', '$idCuenta', '$zonaV', '$stringZonas' )\" style=\"cursor:pointer\"><td>".$empresa."</td><td>".$idCuenta."</td><td>".$nombre."</td><td>".$localidad."</td></tr>";
		}
	}	

	echo '</tbody></table>';
} else { 
	echo '<table><tr><td align="center">No hay registros activos.</td></tr></table>';
}
  
?>