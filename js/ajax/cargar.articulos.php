<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

//*************************************************
$_laboratorio	= 	(isset($_POST['idlab']))	? $_POST['idlab']	:	NULL;
$_empresa		= 	(isset($_POST['idemp']))	? $_POST['idemp']	:	NULL;
//*************************************************

if (!empty($_laboratorio))	{
	$_articulos	= DataManager::getArticulos(0, 1000, 1, 1, $_laboratorio, $_empresa);	
	if (count($_articulos)) {								 
	  foreach ($_articulos as $k => $_articulo) {																			
		  $_articulo	= 	$_articulos[$k];
		  $_id			= 	$_articulo['artid'];
		  $_idart		= 	$_articulo['artidart'];
		  //str_replace('"', '', json_encode($_articulo["artnombre"]));	$_articulo["artnombre"];					
		  $_nombre		= 	$_articulo["artnombre"];
		  $_precio		= 	str_replace('"', '', json_encode($_articulo["artprecio"]));	$_articulo["artprecio"];
		  
		  ((($k % 2) == 0)? $clase="par" : $clase="impar");
		  
		  echo "<tr id=art".$_id." class=".$clase."  onclick=\"javascript:dac_cargarArticuloCondicion('$_id', '$_idart', '$_nombre', '$_precio', '$_b1', '$_b2', '$_desc1', '$_desc2', '$_cantmin')\"><td>".$_idart."</td><td>".$_nombre."</td><td>".$_precio."</td></tr>";
	  }
  	} else { 
  		echo "<tr><td colspan=\"3\"></br>No hay artículos activos en la empresa y laboratorio seleccionados</td></tr>";
  	} 
} else {
	echo "Error al seleccionar el laboratorio";
}			  
  
?>