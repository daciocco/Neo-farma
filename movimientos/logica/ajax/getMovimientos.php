<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//---------------------------
/*$empresa	= 	(isset($_POST['empselect']))	? 	$_POST['empselect']	:	NULL;
$activos	= 	(isset($_POST['actselect']))	? 	$_POST['actselect']	:	NULL;
$tipo		= 	(isset($_POST['tiposelect']))	?	$_POST['tiposelect']:	NULL;*/
$pag		= 	(isset($_POST['pag']))			?	$_POST['pag']		:	NULL;
$_LPP		= 	(isset($_POST['rows']))			?	$_POST['rows']		:	NULL;
//----------------------------
//$tipo = ($tipo == '0') ? NULL : "'".$tipo."'";
$movimientos		= DataManager::getMovimientos($pag, $_LPP); //, $activos, $empresa, NULL, NULL, $tipo
$rows		= count($movimientos);

echo "<table id='tblMovimientos'>";
if ($rows) {	
	echo "<thead><tr><th scope='colgroup'>Id</th><th scope='colgroup'>Origen</th><th scope='colgroup'>Id Origen</th><th scope='colgroup'>Transacción</th><th scope='colgroup'>Operación</th><th scope='colgroup'>Fecha</th><th scope='colgroup'>Usuario</th>";
	echo "</tr></thead>";
	echo "<tbody>";
	
	for( $k=0; $k < $_LPP; $k++ ) {
		if ($k < $rows) {
			$mov 		= $movimientos[$k];
			$id			= $mov['movid'];
			$operacion	= $mov['movoperacion'];
			$transaccion= $mov['movtransaccion'];
			$origen		= $mov['movorigen'];
			$origenId	= $mov['movorigenid'];
			$fecha		= dac_invertirFecha( $mov['movfecha'] );
			$usrId		= $mov['movusrid'];
			$usuario	= DataManager::getUsuario('unombre', $usrId);

			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
			echo "<tr class='".$clase."'>";
			
			echo "<td>".$id."</td><td >".$origen."</td><td >".$origenId."</td><td >".$transaccion."</td><td>".$operacion."</td><td>".$fecha."</td><td>".$usuario."</td>";
			
			echo "</tr>";	
		}
	}
	echo "</table> </div>";	
} else { 
	echo "<tbody>";
	echo "<thead><tr><th colspan='7' align='center'>No hay registros relacionados</th></tr></thead>"; exit;
}

echo "</tbody></table>";
	
?>