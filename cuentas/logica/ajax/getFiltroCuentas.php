<?php
//Matar sesión para probar como hacer que se cierre bien!!!
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

$usrZonas	= isset($_SESSION["_usrzonas"]) ? $_SESSION["_usrzonas"] : '';
$field		= 	(isset($_POST['tipo']))		?	$_POST['tipo']	:	NULL;
$filtro		= 	(isset($_POST['filtro']))	?	$_POST['filtro']  :	'';

if(empty($filtro)){
	echo "Debe indicar un texto para filtrar la búsqueda"; exit;
} else {
	$filtro	= '%'.$filtro.'%';
}

if(empty($usrZonas)){
	echo "No dispone de zonas asignadas para consultar cuentas"; exit;
}
//------------------------------

$cuentas = DataManager::getCuentaAll('*', $field, $filtro, NULL, $usrZonas);
echo	"<table id=\"tblFiltroCuentas\"  style=\"table-layout:fixed;\">";
if (count($cuentas)) {	
	echo	"<thead><tr align=\"left\"><th>Emp</th><th>Tipo</th><th>Cuenta</th><th>Nombre</th><th>Cuit</th></tr></thead>";
	echo	"<tbody>";
		foreach ($cuentas as $k => $cuenta) {
			$id			= $cuenta['ctaid'];
			$idCuenta	= $cuenta['ctaidcuenta'];
			$tipo		= $cuenta['ctatipo'];
			$idEmpresa	= $cuenta['ctaidempresa'];
			
			$empActiva	= DataManager::getEmpresa('empactiva', $idEmpresa);
			if($empActiva){			
				$empresa	=  substr(DataManager::getEmpresa('empnombre', $idEmpresa), 0, 3);
				$cuit		= $cuenta['ctacuit'];
				$nombre		= $cuenta['ctanombre'];

				$_editar	= sprintf( "onclick=\"window.open('editar.php?ctaid=%d')\" style=\"cursor:pointer;\"",$id);

				$_status	= ($cuenta['ctaactiva']) ? "<img class=\"icon-status-active\" title=\"Activa\" style=\"cursor:pointer;\" onclick=\"javascript:dac_changeStatus('/pedidos/cuentas/logica/changestatus.php', $id)\"/>" : "<img class=\"icon-status-inactive\" title=\"Inactiva\" onclick=\"javascript:dac_changeStatus('/pedidos/cuentas/logica/changestatus.php', $id)\"/>";
				((($k % 2) == 0)? $clase="par" : $clase="impar");

				echo "<tr class=".$clase.">";
				echo "<td ".$_editar.">".$empresa."</td><td ".$_editar.">".$tipo."</td><td ".$_editar.">".$idCuenta."</td><td ".$_editar.">".$nombre."</td><td ".$_editar.">".$cuit."</td><td align=\"center\">".$_status."</td>";
				echo "</tr>";
			}
		}
} else { 
	echo	"<tbody>";
	echo 	"<thead><tr><th colspan=\"5\" align=\"center\">No hay registros relacionadas</th></tr></thead>"; exit;
}

echo "</tbody></table>";
	
?>