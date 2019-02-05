<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO. A</td></tr></table>'; exit;
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

echo	"<table id=\"tblFiltroArticulos\" class=\"datatab\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >";

if (count($articulos)) {	
	echo	"<thead><tr align=\"left\" width=\"20%\"><th>Emp</th><th width=\"15%\">Art</th><th width=\"40%\">Nombre</th><th width=\"15%\">Laboratorio</th><th width=\"15%\"></th></tr></thead>";
	echo	"<tbody>";
	foreach ($articulos as $k => $art) {
		$id			= $art['artid'];
		$idArticulo	= $art['artidart'];
		$idEmpresa	= $art['artidempresa'];
		
		$empresa	=  substr(DataManager::getEmpresa('empnombre', $idEmpresa), 0, 3);
		
		$idLaboratorio	= $art['artidlab'];
		$laboratorio	= DataManager::getLaboratorio('Descripcion', $idLaboratorio);
		
		
		//$ean		= $art['artcodbarra'];
		$nombre		= $art['artnombre'];

		$_editar	= sprintf( "onclick=\"window.open('editar.php?artid=%d')\" style=\"cursor:pointer;\"",$id);

		$_status	= 	($art['artactivo']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";	

		$_borrar	= sprintf( "<a href=\"logica/changestatus.php?artid=%d\" title=\"borrar articulo\">%s</a>", $art['artid'], $_status);


		((($k % 2) == 0)? $clase="par" : $clase="impar");

		echo "<tr class=".$clase.">";
		echo "<td ".$_editar.">".$empresa."</td><td ".$_editar.">".$idArticulo."</td><td ".$_editar.">".$nombre."</td><td ".$_editar.">".$laboratorio."</td><td align=\"center\">".$_borrar."</td>";
		echo "</tr>";
	}
} else { 
	echo	"<tbody>";
	echo 	"<thead><tr><th colspan=\"5\" align=\"center\">No hay registros relacionadas</th></tr></thead>"; exit;
}

echo "</tbody></table>";
	
?>