<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//*************************************************
$laboratorio	= 	(isset($_POST['laboratorio']))	? $_POST['laboratorio']	:	NULL;
$empresa		= 	(isset($_POST['empresa']))		? $_POST['empresa']		:	NULL;
$condicion		= 	(isset($_POST['condicion']))	? $_POST['condicion']	:	NULL;
//*************************************************

if (empty($laboratorio)) {
	echo '<table><tr><td align="center">Error al seleccionar el laboratorio</td></tr></table>'; exit;
}

if(!$condicion){
	$articulos	= DataManager::getArticulos(0, 0, FALSE, 1, $laboratorio, $empresa);		
	if (count($articulos)) {
		echo '<table id="tblTablaArt" align="center">';
		echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Precio</th></tr></thead>';
		echo '<tbody>';
		foreach ($articulos as $k => $art) {																			
			$art 	= $articulos[$k];
			$ean	= $art["artcodbarra"];
			$idArt	= $art['artidart'];				
			$nombre	= $art["artnombre"];
			$precio	= str_replace('"', '', json_encode($art["artpreciolista"]));	$art["artpreciolista"];
							
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
			echo "<tr class=".$clase." onclick=\"javascript:dac_CargarArticulo('$idArt', '$ean', '$nombre', '$precio')\"><td>".$idArt."</td><td>".$nombre."</td><td>".$precio."</td></tr>";		
		}
		echo '</tbody></table>';
		exit;
	} else { 
		echo '<table><tr><td align="center">No hay registros activos.</td></tr></table>'; exit;
	} 
}

	  
  
?>