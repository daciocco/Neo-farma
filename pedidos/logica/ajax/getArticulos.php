<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

//*************************************************
$laboratorio	= 	(isset($_POST['laboratorio']))	? $_POST['laboratorio']	:	NULL;
$empresa		= 	(isset($_POST['empresa']))		? $_POST['empresa']		:	NULL;
$condicion		= 	(isset($_POST['condicion']))	? 	$_POST['condicion']	:	NULL;
//*************************************************

if (empty($laboratorio)) {
	echo '<table border="0" width="100%"><tr><td align="center">Error al seleccionar el laboratorio</td></tr></table>'; exit;
}

if($condicion){
	$articulos	= DataManager::getCondicionArticulos($condicion);
	if (count($articulos)) {	
		echo '<table border="0" id="tblTablaArt" width="100%" align="center">';
		echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Precio</th></tr></thead>';
		echo '<tbody>';
		
		foreach ($articulos as $k => $artCond) {
			$artCond 		= $articulos[$k];
			$condArtId		= $artCond['cartid'];
			$condArtIdArt	= $artCond['cartidart'];	                                        								
			$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
			$condArtPrecio	= ($artCond["cartpreciodigitado"] == '0.000')?	$artCond["cartprecio"]	:	$artCond["cartpreciodigitado"]; 
			((($k % 2) == 0)? $clase="par" : $clase="impar");
				
			//echo "<tr class=".$clase." onclick=\"javascript:dac_CargarArticulo('$condArtIdArt', '$condArtNombre', '$condArtPrecio', '', '', '', '', '')\" style=\"cursor:pointer\" ><td>".$condArtIdArt."</td><td>".$condArtNombre."</td><td>".$condArtPrecio."</td></tr>";
			
			$articulosBonif	= DataManager::getCondicionBonificaciones($condicion, $condArtIdArt);
			if (count($articulosBonif) == 1) {								 
				foreach ($articulosBonif as $j => $artBonif) {	
					$artBonifCant	= empty($artBonif['cbcant'])	?	''	:	$artBonif['cbcant'];
					$artBonifB1		= empty($artBonif['cbbonif1'])	?	''	:	$artBonif['cbbonif1'];
					$artBonifB2		= empty($artBonif['cbbonif2'])	?	''	:	$artBonif['cbbonif2'];
					$artBonifD1		= ($artBonif['cbdesc1'] == '0.00')	?	''	:	$artBonif['cbdesc1'];	
					$artBonifD2		= ($artBonif['cbdesc2'] == '0.00')	?	''	:	$artBonif['cbdesc2'];
				}
				
				echo "<tr class=".$clase." style=\"cursor:pointer\"  onclick=\"javascript:dac_CargarArticulo('$condArtIdArt', '$condArtNombre', '$condArtPrecio', '$artBonifB1', '$artBonifB2', '$artBonifD1', '$artBonifD2', '$artBonifCant')\"><td>".$condArtIdArt."</td><td>".$condArtNombre."</td><td>".$condArtPrecio."</td></tr>";
			} else {
				echo "<tr class=".$clase." style=\"cursor:pointer\"  onclick=\"javascript:dac_CargarArticulo('$condArtIdArt', '$condArtNombre', '$condArtPrecio', '', '', '', '', '')\"><td>".$condArtIdArt."</td><td>".$condArtNombre."</td><td>".$condArtPrecio."</td></tr>";	
			}
			
		}
		echo '</tbody></table>';
		exit;
		/*$objJason = json_encode($_datos);
		echo $objJason;*/
	} else { 
		echo '<table border="0" width="100%"><tr><td align="center">No hay registros activos.</td></tr></table>'; exit;
	}
} else {
	$articulos	= DataManager::getArticulos(0, 1000, 1, 1, $laboratorio, $empresa);		
	if (count($articulos)) {
		echo '<table border="0" id="tblTablaArt" width="100%" align="center">';
		echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Precio</th></tr></thead>';
		echo '<tbody>';
		foreach ($articulos as $k => $art) {																			
			$art 	= $articulos[$k];
			$idArt	= $art['artidart'];				
			$nombre	= $art["artnombre"];
			$precio	= str_replace('"', '', json_encode($art["artpreciolista"]));	$art["artpreciolista"];
							
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
			echo "<tr class=".$clase."  onclick=\"javascript:dac_CargarArticulo('$idArt', '$nombre', '$precio', '', '', '', '', '', '')\"><td>".$idArt."</td><td>".$nombre."</td><td>".$precio."</td></tr>";		
		}
		echo '</tbody></table>';
		exit;
	} else { 
		echo '<table border="0" width="100%"><tr><td align="center">No hay registros activos.</td></tr></table>'; exit;
	} 
}

	  
  
?>