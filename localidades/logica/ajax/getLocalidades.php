<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//------------------------------------------------
$idProv		= 	(isset($_POST['idProv']))	? 	$_POST['idProv']	:	NULL;
$idZonaV	= 	(isset($_POST['idZonaV']))	? 	$_POST['idZonaV']	:	NULL;
$idZonaD	= 	(isset($_POST['idZonaD']))	?	$_POST['idZonaD']	:	NULL;
//------------------------------------------------

$localidades= DataManager::getLocalidades(NULL, $idProv, $idZonaV, $idZonaD);
$rows		= count($localidades);

echo	"<table id=\"tblLocalidades\" class=\"datatab\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"table-layout:fixed;\">";
if ($rows) {	
	echo	"<thead><tr align=\"left\"><th width=\"25%\">Provincia</th><th width=\"25%\">Localidad</th><th width=\"10%\">CP</th><th width=\"15%\">Zona Vendedor</th><th width=\"15%\">Zona Entrega</th><th align=\"center\" width=\"10%\">Acciones</th></tr></thead>";
	echo	"<tbody>";
	for( $k=0; $k < $rows; $k++ ) {
		$loc 		= 	$localidades[$k];
		$idLoc		=	$loc['locidloc'];	
		$locNombre	= 	$loc['locnombre'];
		$idProv		= 	$loc['locidprov'];
		$codPostal	= 	$loc['loccodpostal'];
		$nombreProv	=	DataManager::getProvincia('provnombre', $idProv);						
		$zonaVendedor = $loc['loczonavendedor'];
		$zonaEntrega  = $loc['loczonaentrega'];
		
		$_editar	= sprintf( "onclick=\"window.open('editar.php?idLoc=%d')\" style=\"cursor:pointer;\"",$idLoc);

		$checkBox	=	"<input type=\"checkbox\" name=\"editSelected\" value=\"$idLoc\" onClick=\"dac_addLocalidad()\"  style=\"width:20px; height:20px;\" >";

		((($k % 2) == 0)? $clase="par" : $clase="impar");

		echo "<tr class=".$clase.">";
		echo "<td height=\"15\" ".$_editar.">".$nombreProv."</td><td ".$_editar.">".$locNombre."</td><td ".$_editar.">".$codPostal."</td><td ".$_editar.">".$zonaVendedor."</td><td ".$_editar.">".$zonaEntrega."</td><td >".$checkBox."</td>";
		echo "</tr>";	
	}
} else { 
	echo	"<tbody>";
	echo 	"<thead><tr><th colspan=\"6\" align=\"center\">No hay cuentas relacionadas</th></tr></thead>"; exit;
}

echo "</tbody></table>";
	
?>