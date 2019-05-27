<?php
session_start();
//Matar sesión para probar como hacer que se cierre bien!!!
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	echo "SE PERDIÓ LA SESIÓN. VUELVA A INICIARLA."; exit;
}

//*************************************************
$empresa	= 	(isset($_POST['empresa']))	? 	$_POST['empresa']	:	NULL;
$activos	= 	(isset($_POST['activos']))	? 	$_POST['activos']	:	NULL;
$tipo		= 	(isset($_POST['tipo']))		?	$_POST['tipo']		:	NULL;
$pag		= 	(isset($_POST['pag']))		?	$_POST['pag']		:	NULL;
$_LPP		= 	(isset($_POST['rows']))		?	$_POST['rows']		:	NULL;
//*************************************************
$cuentas	= DataManager::getCuentas($pag, $_LPP, $empresa, $activos, "'".$tipo."'", $_SESSION["_usrzonas"]);
$_rows		= count($cuentas); //DataManager::getCuentas($pag, $_LPP, $empresa, $activos, $tipo, 

echo	"<table id=\"tblCuentas\"  style=\"table-layout:fixed;\">";

if (count($cuentas)) {	
	echo	"<thead><tr align=\"left\"><th>Cuenta</th><th>Nombre</th><th>Provincia</th><th>Localidad</th><th>Modificada</th><th align=\"center\">Acciones</th></tr></thead>";
	echo	"<tbody>";
	for( $k=0; $k < $_LPP; $k++ ) {
		if ($k < $_rows) {
			$cuenta 	= $cuentas[$k];
			$id			= $cuenta['ctaid'];
			$idCuenta	= $cuenta['ctaidcuenta'];
			$empresa	= $cuenta['ctaidempresa'];
			$nombre		= $cuenta['ctanombre'];
			$provincia	= $cuenta['ctaidprov'];
			$localidad	= $cuenta['ctaidloc'];	
			$dateUpdate	= ($cuenta['ctaupdate']=='0000-00-00 00:00:00' || $cuenta['ctaupdate']=='2001-01-01 00:00:00' || $cuenta['ctaupdate']=='1900-01-01 00:00:00') ? '' : $cuenta['ctaupdate'];	
			
			$provinciaNombre	=	DataManager::getProvincia('provnombre', $provincia);
			$localidadNombre	=	DataManager::getLocalidad('locnombre', $localidad);			
			$localidadNombre	=	(empty($localidadNombre)	?	$cuenta['ctalocalidad']	:	$localidadNombre);
			
			$_editar	= sprintf( "onclick=\"window.open('editar.php?ctaid=%d')\" style=\"cursor:pointer;\"",$id);
			
			$_status	= ($cuenta['ctaactiva']) ? "<img class=\"icon-status-active\" title=\"Activa\" style=\"cursor:pointer;\" onclick=\"javascript:dac_changeStatus('/pedidos/cuentas/logica/changestatus.php', $id, $pag)\"/>" : "<img class=\"icon-status-inactive\" title=\"Inactiva\" onclick=\"javascript:dac_changeStatus('/pedidos/cuentas/logica/changestatus.php', $id, $pag)\"/>";
			
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
			echo "<tr class=".$clase.">";
			echo "<td ".$_editar.">".$idCuenta."</td><td ".$_editar.">".$nombre."</td><td ".$_editar.">".$provinciaNombre."</td><td ".$_editar.">".$localidadNombre."</td><td ".$_editar.">".$dateUpdate."</td><td align=\"center\">".$_status."</td>";
			echo "</tr>";			
		}
	}
} else { 
	echo	"<tbody>";
	echo 	"<thead><tr><th colspan=\"6\" align=\"center\">No hay cuentas relacionadas</th></tr></thead>"; exit;
}

echo "</tbody></table>";
	
?>