<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
}
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=TablaArticulos-".date('d-m-y').".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>
	<table border="0" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td scope="col" >artid</td>
				<td scope="col" >artidempresa</td>            
				<td scope="col" >artidlab</td>
				<td scope="col" >artidart</td> 
				<td scope="col" >artnombre</td> 
				<td scope="col" >artdescripcion</td>   
				<td scope="col" >artprecio</td>   
				<td scope="col" >artcodbarra</td>   
				<td scope="col" >artstock</td>   
				<td scope="col" >artmedicinal</td>   
				<td scope="col" >artimagen</td> 
				<td scope="col" >artusrupdate</td> 
				<td scope="col" >artlastupdate</td>  
			    <td scope="col" >artactivo</td>
			</tr>
		</thead>			
		<?php
		$articulos	= DataManager::getArticulos(0, 0, ''); 
		if (count($articulos)) {
			foreach ($articulos as $k => $articulo) {
				$artid			= $articulo['artid'];
				$artidempresa	= $articulo['artidempresa'];
				$artidlab		= $articulo['artidlab'];
				$artidart		= $articulo['artidart'];
				$artnombre		= $articulo['artnombre'];
				$artdescripcion	= $articulo['artdescripcion'];
				$artprecio		= $articulo['artprecio'];
				$artcodbarra	= $articulo['artcodbarra'];
				$artstock		= $articulo['artstock'];
				$artmedicinal	= $articulo['artmedicinal'];
				$artimagen		= $articulo['artimagen'];
				$artusrupdate	= $articulo['artusrupdate'];
				$artlastupdate	= $articulo['artlastupdate'];
				$artactivo		= $articulo['artactivo'];
				
				echo sprintf("<tr align=\"left\">");
				echo sprintf("<td >%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $artid, $artidempresa, $artidlab, $artidart, $artnombre, $artdescripcion, $artprecio, $artcodbarra, $artstock, $artmedicinal, $artimagen, $artusrupdate, $artlastupdate, $artactivo);
				echo sprintf("</tr>");
			}
		} ?>
	</table>	

</body>
</html>                
               
               