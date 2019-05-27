<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

$laboratorio = (isset($_POST['laboratorio']))	? $_POST['laboratorio']	: NULL;
$empresa	 = (isset($_POST['empresa']))		? $_POST['empresa']		: NULL;
$condicion	 = (isset($_POST['condicion']))		? $_POST['condicion']	: NULL;
$listaCuenta = (isset($_POST['listaCuenta']))	? $_POST['listaCuenta']	: NULL;

if (empty($laboratorio)) {
	echo '<table><tr><td align="center">Error al seleccionar el laboratorio</td></tr></table>'; exit;
}

if(!is_null($listaCuenta) && $listaCuenta != 0){
	//$condicion = DataManager::getCondicionCampo('condid', 'condlista', $listaCuenta, $empresa, $laboratorio);
	$condiciones = DataManager::getCondiciones( 0, 0, 1, $empresa, $laboratorio, NULL, NULL, NULL, NULL, NULL, $listaCuenta);	
	if(empty($condiciones)){
		echo '<table><tr><td align="center">No hay registros actuales de la Condición Comercial con la Lista de Precios definida en la cuenta</td></tr></table>'; exit;
	} else {
		foreach ($condiciones as $k => $cond) {
			$condicion = $cond['condid'];
		}
	}
}

if($condicion){
	$articulos	= DataManager::getCondicionArticulos($condicion);
	if (count($articulos)) {	
		echo '<table id="tblTablaArt" align="center">';
		echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Precio</th></tr></thead>';
		echo '<tbody>';
		
		foreach ($articulos as $k => $artCond) {
			$condArtId		= $artCond['cartid'];
			$condArtIdArt	= $artCond['cartidart'];	                                        								
			$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
			$condArtPrecio	= ($artCond["cartpreciodigitado"] == '0.000')?	$artCond["cartprecio"]	:	$artCond["cartpreciodigitado"]; 
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
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
	} else { 
		echo '<table><tr><td align="center">No hay registros activos.</td></tr></table>'; exit;
	}
} else {
	// Busca coincidencia entre la Lista de precios entre Cuenta y condición comercial para cargar, en caso contrario cargar los artículos normales.	
	$articulos	= DataManager::getArticulos(0, 1000, 1, 1, $laboratorio, $empresa);		
	if (count($articulos)) {
		echo '<table id="tblTablaArt" align="center">';
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
		echo '<table><tr><td align="center">No hay registros activos.</td></tr></table>'; exit;
	} 	
	
} ?>