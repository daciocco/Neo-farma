<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//*************************************************
$empresa	= 	(isset($_POST['idEmpresa']))	? 	$_POST['idEmpresa']	:	NULL;
//*************************************************

$droguerias	= DataManager::getDrogueriaCAD(NULL, $empresa); 
if (count($droguerias)) {
	echo '<table id="tblDroguerias" class="datatab" width="100%" border="0">';
	echo '<thead><tr><th>Droguer&iacute;a</th><th>Nombre</th></tr></thead>';
	echo '<tbody>';
	
	foreach ($droguerias as $k => $drog) {
		$id			= $drog['dcadId'];
		$nombre		= $drog['dcadNombre'];
		
		((($k % 2) == 0)? $clase="par" : $clase="impar");
		echo "<tr id=cadena".$id." class=".$clase." style=\"cursor:pointer;\" onclick=\"javascript:dac_changeDrogueria('$id', '$nombre')\">";
		echo "<td>".$id."</td><td>".$nombre."</td>";
		echo "</tr>";	
	}
	echo "</tbody></table>";
	
} else {
	echo	'<table border="0" width="100%"><thead><tr><th align="center">No hay registros activos.</th></tr></thead></table>'; exit;
}

?>