<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$laboratorio	= 	(isset($_POST['idlab']))	? $_POST['idlab']	:	NULL;
$empresa		= 	(isset($_POST['idemp']))	? $_POST['idemp']	:	NULL;

if (!empty($laboratorio))	{
	$articulos	= DataManager::getArticulos(0, 1000, 1, 1, $laboratorio, $empresa);	
	if (count($articulos)) {	
		echo '<table id="tblArticulos" border="0" align="center" style=\"table-layout:fixed\">';
		echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Precio</th></tr></thead>';
		echo '<tbody>';
										 
		foreach ($articulos as $k => $art) {																			
			$art	= 	$articulos[$k];
			$id		= 	$art['artid'];
			$idArt	= 	$art['artidart'];				
			$nombre	= 	$art["artnombre"];
			$precio	= 	str_replace('"', '', json_encode($art["artprecio"]));	$art["artprecio"];
			
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
			echo "<tr id=art".$id." class=".$clase."  onclick=\"javascript:dac_cargarArticuloCondicion('$id', '$idArt', '$nombre', '$precio', '$_b1', '$_b2', '$_desc1', '$_desc2', '$_cantmin')\"><td>".$idArt."</td><td>".$nombre."</td><td>".$precio."</td></tr>";
		}
		
		echo '</tbody></table>';
  	} else { 
  		echo '<table border="0" align="center"><tr><td >No hay artículos activos. </td></tr></table>';
  	} 
} else {
	echo '<table border="0" align="center"><tr><td >Error al seleccionar el laboratorio </td></tr></table>';
}			  
  
?>