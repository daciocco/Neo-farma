<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//---------------------------
$empresa	= 	(isset($_POST['empselect']))	? 	$_POST['empselect']	:	NULL;
$activos	= 	(isset($_POST['actselect']))	? 	$_POST['actselect']	:	NULL;
$tipo		= 	(isset($_POST['tiposelect']))	?	$_POST['tiposelect']:	NULL;
$pag		= 	(isset($_POST['pag']))			?	$_POST['pag']		:	NULL;
$_LPP		= 	(isset($_POST['rows']))			?	$_POST['rows']		:	NULL;
//----------------------------
$tipo = ($tipo == '0') ? NULL : "'".$tipo."'";
$conds		= DataManager::getCondiciones($pag, $_LPP, $activos, $empresa, NULL, NULL, $tipo);
$rows		= count($conds);

echo "<table id='tblCondiciones' class='datatab' width='100%' border='0' cellpadding='0' cellspacing='0' >";
if ($rows) {	
	echo "<thead><tr><th scope='colgroup' height='18'>Tipo</th><th scope='colgroup' >Nombre</th><th scope='colgroup' >Inicio</th><th scope='colgroup' >Fin</th>";	
	if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){
		echo "<th scope='colgroup' colspan='3' align='center'>Acciones</th>";
	} else {
		echo "<th scope='colgroup' colspan='2' align='center'>Acciones</th>";
	}	
	echo "</tr></thead>";
	echo "<tbody>";
	
	for( $k=0; $k < $_LPP; $k++ ) {
		if ($k < $rows) {
			$cond 		= $conds[$k];
			$condId		= $cond['condid'];
			$tipo		= $cond['condtipo'];
			$nombre		= $cond['condnombre'];
			$activa		= $cond['condactiva'];
			$inicio		= dac_invertirFecha( $cond['condfechainicio'] );
			$fin		= dac_invertirFecha( $cond['condfechafin'] );

			$checkBox	= "<input type='checkbox' name='editSelected' value='".$condId."'/>";		
			
			$btnExport	=	"<a id='exporta' href='logica/export.condicion.php?condid=".$condId."' title='Exportar'><img class=\"icon-xls-export\"/></a> ";
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			echo "<tr class='".$clase."'>";
			
			if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){ 
				$editar	= sprintf( "onclick=\"window.open('editar.php?condid=%d')\" style=\"cursor:pointer;\"",$condId);
				
				$_status	= 	($cond['condactiva']) ? "<a title='Desactivar' href='logica/changestatus.php?condid=".$condId."'><img class=\"icon-status-active\"/></a>" : "<a title='Activar' href='logica/changestatus.php?condid=".$condId."'><img class=\"icon-status-inactive\"/></a>";				
				
				echo "<td height='15'".$editar.">".$tipo."</td><td ".$editar.">".$nombre."</td><td ".$editar.">".$inicio."</td><td ".$editar.">".$fin."</td><td align='center'>".$checkBox."</td><td align='center'>".$btnExport."</td><td align='center'>".$_status."</td>";
			} else {			
				$_status	= 	($cond['condactiva']) ? "<a title='Desactivar'><img class=\"icon-status-active\"/></a>" : "<a title='Activar'><img class=\"icon-status-inactive\"/></a>";
				
				if(!$activa){ $btnExport = ''; } 				
				echo "<td height='15'>".$tipo."</td><td>".$nombre."</td><td >".$inicio."</td><td>".$fin."</td><td align='center'>".$btnExport."</td><td align='center'>".$_status."</td>";
			}
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