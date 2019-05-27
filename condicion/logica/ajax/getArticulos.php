<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$laboratorio	= 	(isset($_POST['idlab']))	? $_POST['idlab']	:	NULL;
$empresa		= 	(isset($_POST['idemp']))	? $_POST['idemp']	:	NULL;

if (!empty($laboratorio))	{
	$articulos	= DataManager::getArticulos(0, 1000, NULL, 1, $laboratorio, $empresa);	
	if (count($articulos)) {	
		echo '<table id="tblArticulos" border="0" align="center" style=\"table-layout:fixed\">';
		echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Precio</th></tr></thead>';
		echo '<tbody>';
										 
		foreach ($articulos as $k => $art) {																		
			$art	= 	$articulos[$k];
			$id		= 	$art['artid'];
			$idArt	= 	$art['artidart'];				
			$nombre	= 	$art['artnombre'];
			$precio	= 	str_replace('"', '', json_encode($art["artprecio"]));
						
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			echo "<tr id=art".$id." class=".$clase." style=\"cursor:pointer;\"  onclick=\"javascript:dac_cargarArticuloCondicion('$id', '$idArt', '$nombre', '$precio', '', '', '', ''); dac_alertaDuplicar()\"><td>".$idArt."</td><td>".$nombre."</td><td>".$precio."</td></tr>";
		}
		
		echo '</tbody></table>';
  	} else { 
		echo 	'<table><thead><tr><th align="center">No hay registros activos.</th></tr></thead></table>'; exit;
  	} 
} else {
	echo '<table><tr><th align="center">Error al seleccionar el laboratorio. </th></tr></table>'; exit;
}			  
  
?>