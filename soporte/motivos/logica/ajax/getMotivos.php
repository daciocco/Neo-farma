<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$idSector		=	empty($_POST['sector']) 		? 0 	: $_POST['sector'];

$motivos	= DataManager::getTicketMotivos(); 
if (count($motivos)) {
	echo '<table id="tblMotivos" class="datatab" width="100%" border="0">';
	echo '<thead><tr><th>Motivo</th><th>Responsable</th></tr></thead>';
	echo '<tbody>';
	
	foreach ($motivos as $k => $mot) {
		$id				= $mot['tkmotid'];
		$sector			= $mot['tkmotidsector'];
		$motivo			= $mot['tkmotmotivo'];
		$usrResponsable	= $mot['tkmotusrresponsable'];
		$responsable	= DataManager::getUsuario('unombre', $usrResponsable);
		
		if($idSector){
			if($idSector == $sector){
				((($k % 2) == 0)? $clase="par" : $clase="impar");
				echo "<tr id=motivo".$id." class=".$clase." style=\"cursor:pointer;\" onclick=\"javascript:dac_changeMotivo('$id', '$motivo', '$usrResponsable')\">";
				echo "<td>".$motivo."</td><td>".$responsable."</td>";
				echo "</tr>";		
			}
		} 
	}
	echo "</tbody></table>";
	
} else {
	echo	'<table border="0" width="100%"><thead><tr><th align="center">No hay registros activos.</th></tr></thead></table>'; exit;
}

?>