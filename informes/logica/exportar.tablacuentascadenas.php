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
header("content-disposition: attachment;filename=TablaCuentasCadenas-".date('d-m-y').".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>
	<table border="0">
		<thead>
			<tr>
				<td scope="col" >cadid</td>
				<td scope="col" >IdEmpresa</td>            
				<td scope="col" >IdCadena</td>
				<td scope="col" >IdCliente</td>
				<td scope="col" >TipoCadena</td>   
			</tr>
		</thead>			
		<?php
		$cadenas	= DataManager::getCuentasCadena(); 
		if (count($cadenas)) {
			foreach ($cadenas as $k => $cadena) {
				$id			= $cadena['cadid'];
				$idEmpresa	= $cadena['IdEmpresa'];
				$idCadena	= $cadena['IdCadena'];
				$idCuenta	= $cadena['IdCliente'];
				$tipoCadena	= $cadena['TipoCadena'];
				
				echo sprintf("<tr align=\"left\">");
				echo sprintf("<td >%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $id, $idEmpresa, $idCadena, $idCuenta, $tipoCadena);
				echo sprintf("</tr>");
			}
		} ?>
	</table>	

</body>
</html>                
               
               