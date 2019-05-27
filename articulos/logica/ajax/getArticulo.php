<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G") {
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

$empresa	= (isset($_POST['empresa']))	? 	$_POST['empresa']	:	NULL;
$activos	= (isset($_POST['activos']))	? 	$_POST['activos']	:	NULL;
$laboratorio= (isset($_POST['laboratorio']))?	$_POST['laboratorio']:	NULL;
$pag		= (isset($_POST['pag']))		?	$_POST['pag']		:	NULL;
$LPP		= (isset($_POST['rows']))		?	$_POST['rows']		:	NULL;
//------------------------------------

$articulos	= DataManager::getArticulos($pag, $LPP, '', $activos, $laboratorio, $empresa); 
$rows		= count($articulos);
echo "<table id=\"tblArticulos\"  style=\"table-layout:fixed;\">";
if (count($articulos)) {
	echo	"<thead><tr align=\"left\"><th width=\"10%\">Art</th><th width=\"45%\">Nombre</th><th width=\"15%\">Precio</th><th align=\"center\" colspan=\"3\" width=\"30%\" >Acciones</th></tr></thead>";
	echo	"<tbody>";
	for( $k=0; $k < $LPP; $k++ ) {
		if ($k < $rows) {
			$art 		= $articulos[$k];
			$id			= $art['artid'];
			$idArt		= $art['artidart'];	
			$nombre		= $art['artnombre'];
			$precio		= $art['artpreciolista'];			
			
			$editar	= sprintf( "onclick=\"window.open('editar.php?artid=%d')\" style=\"cursor:pointer;\"",$id);
			
			$status		= ($art['artactivo']) ? "<a title=\"Activo\" onclick=\"javascript:dac_changeStatus('/pedidos/articulos/logica/changestatus.php', $id, $pag)\"> <img class=\"icon-status-active\"/></a>" : "<a title=\"Inactivo\" onclick=\"javascript:dac_changeStatus('/pedidos/articulos/logica/changestatus.php', $id, $pag)\">  <img class=\"icon-status-inactive\" /></a>";
			
			$statusStock = ($art['artstock']) ? "<a title=\"Stock Activo\" onclick=\"javascript:dac_changeStatus('/pedidos/articulos/logica/changestock.php', $id, $pag)\"> <img class=\"icon-status-active\" /></a>" : "<a  title=\"Stock Inactivo\" onclick=\"javascript:dac_changeStatus('/pedidos/articulos/logica/changestock.php', $id, $pag)\"> <img class=\"icon-status-inactive\" /></a>";
						
			$eliminar 	= 	sprintf ("<a href=\"logica/eliminar.articulo.php?artid=%d\" title=\"eliminar articulo\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR LA ARTÍCULO?')\"> <img class=\"icon-delete\"/></a>", $id, "Eliminar");
			
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
			echo "<tr class=".$clase.">";
			echo "<td ".$editar.">".$idArt."</td><td ".$editar.">".$nombre."</td><td ".$editar.">".$precio."</td><td align=\"center\">".$statusStock."</td><td align=\"center\">".$status."</td><td align=\"center\">".$eliminar."</td>";
			echo "</tr>";			
		}
	}
} else { 
	echo	"<tbody>";
	echo 	"<thead><tr><th colspan=\"6\" align=\"center\">No hay registros relacionadas</th></tr></thead>"; exit;
}

echo "</tbody></table>";
	
?>