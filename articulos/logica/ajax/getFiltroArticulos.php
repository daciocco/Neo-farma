<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO. A</td></tr></table>'; exit;
}

$field		= 	(isset($_POST['tipo']))		?	$_POST['tipo']	:	NULL;
$filtro		= 	(isset($_POST['filtro']))	?	$_POST['filtro']  :	'';

if(empty($filtro)){
	echo "Debe indicar un texto para filtrar la búsqueda"; exit;
} else {
	$filtro	= '%'.$filtro.'%';
}
//------------------------------------

$articulos	= DataManager::getArticuloAll('*', $field, $filtro);

echo	"<table id=\"tblFiltroArticulos\"  >";

if (count($articulos)) {	
	echo	"<thead><tr align=\"left\" width=\"20%\"><th>Emp</th><th width=\"15%\">Art</th><th width=\"40%\">Nombre</th><th width=\"15%\">Laboratorio</th><th width=\"15%\"></th></tr></thead>";
	echo	"<tbody>";
	foreach ($articulos as $k => $art) {
		$id				= $art['artid'];
		$idArticulo		= $art['artidart'];
		$idEmpresa		= $art['artidempresa'];		
		$empresa		=  substr(DataManager::getEmpresa('empnombre', $idEmpresa), 0, 3);		
		$idLaboratorio	= $art['artidlab'];
		$laboratorio	= DataManager::getLaboratorio('Descripcion', $idLaboratorio);
		
		$nombre		= $art['artnombre'];
		$_editar	= sprintf( "onclick=\"window.open('editar.php?artid=%d')\" style=\"cursor:pointer;\"",$id);

		/*$_status	= ($art['artactivo']) ? "<img class=\"icon-status-active\" title=\"Desactivar\"/>" : "<img class=\"icon-status-inactive\" title=\"Activar\"/>";
		$_borrar	= sprintf( "<a href=\"logica/changestatus.php?artid=%d\" title=\"borrar articulo\">%s</a>", $art['artid'], $_status);*/
		
		$_status	= ($art['artactivo']) ? "<a title=\"Activo\" onclick=\"javascript:dac_changeStatus('/pedidos/articulos/logica/changestatus.php', $id)\"> <img class=\"icon-status-active\"/></a>" : "<a title=\"Inactivo\" onclick=\"javascript:dac_changeStatus('/pedidos/articulos/logica/changestatus.php', $id)\">  <img class=\"icon-status-inactive\" /></a>";


		((($k % 2) == 0)? $clase="par" : $clase="impar");

		echo "<tr class=".$clase.">";
		echo "<td ".$_editar.">".$empresa."</td><td ".$_editar.">".$idArticulo."</td><td ".$_editar.">".$nombre."</td><td ".$_editar.">".$laboratorio."</td><td align=\"center\">".$_status."</td>";
		echo "</tr>";
	}
} else { 
	echo	"<tbody>";
	echo 	"<thead><tr><th colspan=\"5\" align=\"center\">No hay registros relacionadas</th></tr></thead>"; exit;
}

echo "</tbody></table>";
	
?>