<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
	exit;
}

//*************************************************
$empresa	= 	(isset($_POST['empresa']))	? 	$_POST['empresa']	:	NULL;
//*************************************************

$cuentas	= DataManager::getCuentas(0, 0, $empresa, NULL, "'C', 'CT', 'T', 'TT'", $_SESSION["_usrzonas"]);
if (count($cuentas)) {	
	echo '<table id="tblCuentas" class="datatab" width="100%" border="0" align="center" style="table-layout:fixed;">';
	echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Localidad</th></tr></thead>';
	echo '<tbody>';
	foreach ($cuentas as $k => $cta) {	
		$id			= $cta['ctaid'];
		$idCuenta	= $cta['ctaidcuenta'];
		$nombre		= $cta['ctanombre'];
		$localidad	= $cta['ctaidloc'];	
		$localidadNombre	=	DataManager::getLocalidad('locnombre', $localidad);			
		$localidadNombre	=	(empty($localidadNombre)	?	$cta['ctalocalidad']	:	$localidadNombre);	
		((($k % 2) == 0)? $clase="par" : $clase="impar");
		
		if($idCuenta != 0){
			echo "<tr id=cuenta".$id." class=".$clase." style=\"cursor:pointer;\" onclick=\"javascript:dac_cuentaRelacionada('$id', '$idCuenta', '$nombre')\">";
			echo "<td>".$idCuenta."</td><td>".$nombre."</td><td>".$localidadNombre."</td>";
			echo "</tr>";	
		}
			
	}
	echo "</tbody></table>";
} else { 
	echo 	'<table border="0" width="100%"><thead><tr><th align="center">No hay registros activos.</th></tr></thead></table>'; exit;
}
	
?>