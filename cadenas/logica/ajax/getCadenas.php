<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
 	exit;
}

//*************************************************
$empresa	= 	(isset($_POST['idEmpresa']))	? 	$_POST['idEmpresa']	:	NULL;
//*************************************************

$cadenas	= DataManager::getCadenas($empresa); 
if (count($cadenas)) {
	echo '<table id="tblCadenas" class="datatab" width="100%" border="0">';
	echo '<thead><tr><th>Cadena</th><th>Nombre</th></tr></thead>';
	echo '<tbody>';
	
	foreach ($cadenas as $k => $cadena) {
		$id			= $cadena['cadid'];
		$idCadena	= $cadena['IdCadena'];
		$nombre		= $cadena['NombreCadena'];
		
		((($k % 2) == 0)? $clase="par" : $clase="impar");
		if($idCadena != 0){
			echo "<tr id=cadena".$id." class=".$clase." style=\"cursor:pointer;\" onclick=\"javascript:dac_changeCadena('$id', '$idCadena', '$nombre')\">";
			echo "<td>".$idCadena."</td><td>".$nombre."</td>";
			echo "</tr>";	
		}
	}
	echo "</tbody></table>";
	
} else {
	echo	'<table border="0" width="100%"><thead><tr><th align="center">No hay registros activos.</th></tr></thead></table>'; exit;
}

?>