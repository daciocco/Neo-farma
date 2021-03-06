<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//*************************************************
$empresa	= 	(isset($_POST['empresa']))	? 	$_POST['empresa']	:	NULL;
//*************************************************

$cuentas	= DataManager::getCuentas(0, 0, $empresa, NULL, "'C', 'CT'", $_SESSION["_usrzonas"]);
if (count($cuentas)) {	
	echo '<table id="tblCuentas" style="table-layout:fixed;">';
	echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Localidad</th></tr></thead>';
	echo '<tbody>';
	foreach ($cuentas as $k => $cta) {	
		$cta 		= $cuentas[$k];
		$id			= $cta['ctaid'];
		$idCuenta	= $cta['ctaidcuenta'];
		$zona		= $cta['ctazona'];
		$nombre		= $cta['ctanombre'];
		$localidad	= $cta['ctaidloc'];	
		$localidadNombre	=	DataManager::getLocalidad('locnombre', $localidad);			
		$localidadNombre	=	(empty($localidadNombre)	?	$cta['ctalocalidad']	:	$localidadNombre);	
		((($k % 2) == 0)? $clase="par" : $clase="impar");
		
		if($idCuenta != 0 && $zona != 95){
			echo "<tr id=cuenta".$id." class=".$clase." style=\"cursor:pointer;\" onclick=\"javascript:dac_cargarCuentaCondicion('$id', '$idCuenta', '$nombre')\">";
			echo "<td>".$idCuenta."</td><td>".$nombre."</td><td>".$localidadNombre."</td>";
			echo "</tr>";	
		}
			
	}
	echo "</tbody></table>";
} else { 
	echo 	'<table><thead><tr><th align="center">No hay registros activos.</th></tr></thead></table>'; exit;
}
	
?>