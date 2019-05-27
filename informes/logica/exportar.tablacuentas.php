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
header("content-disposition: attachment;filename=TablaCuentas-".date('d-m-y').".xls");

?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>
	<table border="0"> <?php
		//consultar todas las zonas
		$zonas	= DataManager::getZonas(); 
		if (count($zonas)) {
			$stringZona	= "'0'";
			foreach ($zonas as $k => $zon) {
				$nroZona	=	$zon["zzona"];
				$stringZona	=	$stringZona.", '".$nroZona."'";
			}                              
		}
		
		$registros	= DataManager::getCuentas(0, 0, NULL, NULL, NULL, $stringZona, NULL); 
		if (count($registros)) {
			
			$names = array_keys($registros[0]); ?>
			<thead>
				<tr> <?php
			foreach ($names as $j => $name) {
				?><td scope="col" ><?php echo $name; ?></td><?php
			} ?>
				</tr>
			</thead> <?php
			
			foreach ($registros as $k => $registro) {
				echo sprintf("<tr align=\"left\">");
				foreach ($names as $j => $name) {
					switch($j){
						case 13: 
							echo sprintf("<td style=\"mso-number-format:'@';\">%s</td>", $registro[$name]);
							break;
						case 24:
						case 25:
						case 48:
						case 51:
							echo sprintf("<td style=\"mso-number-format:'yyyy-mm-dd hh:mm:ss';\">%s</td>", $registro[$name]);
							break;
						default:
							echo sprintf("<td >%s</td>", $registro[$name]);	
							break;
					}
				}
				echo sprintf("</tr>");
			}
		} ?>
	</table>	

</body>
</html>                
               
               