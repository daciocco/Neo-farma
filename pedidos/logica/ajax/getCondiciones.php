<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}
$empresa		= 	(isset($_POST['empresa']))		? 	$_POST['empresa']		:	NULL;
$laboratorio	= 	(isset($_POST['laboratorio']))	? 	$_POST['laboratorio']	:	NULL;

$condiciones	= DataManager::getCondiciones(0, 0, 1, $empresa, $laboratorio, date("Y-m-d"));
if (count($condiciones)) {	
	echo '<table id="tblCondiciones" style="table-layout:fixed;">';
	echo '<thead><tr align="left"><th scope="colgroup" height="18" align="left">Tipo</th><th scope="colgroup" align="left">Nombre</th></tr></thead>';
	echo '<tbody>';
	foreach ($condiciones as $k => $cond) {	
		$condId				= $cond['condid'];
		$condTipo			= $cond['condtipo'];
		$condCuentas		= $cond['condidcuentas'];
		$condNombre			= $cond['condnombre'];
		$condPago			= $cond['condcondpago'];
		$condObservacion	= $cond['condobservacion'];	
		$condListaCondicion = $cond['condlista'];
		
		if($condTipo == "Pack") {
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			echo "<tr class=".$clase." style=\"cursor:pointer;\" onclick=\"javascript:dac_CargarCondicionComercial($empresa, $laboratorio, $condId, '$condTipo', '$condCuentas', '$condPago', '$condNombre', '$condObservacion', '$condListaCondicion')\">";
			echo "<td>$condTipo</td>";
			echo "<td>$condNombre</td>";
			echo "</tr>";
		}
	}
	echo "</tbody></table>";	
	exit;
} else { 
	echo '<table><tr><td align="center">No hay registros activos.</td></tr></table>'; exit;
}


	
?>