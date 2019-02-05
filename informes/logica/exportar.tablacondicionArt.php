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
header("content-disposition: attachment;filename=TablaCondicionArt-".date('d-m-y').".xls");

?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>
	<table border="0" cellpadding="0" cellspacing="0"> <?php
		$registros	= DataManager::getCondicionArticulos(); 
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
					if($j != 3){
						echo sprintf("<td >%s</td>", $registro[$name]);	
					} else {
						echo sprintf("<td style=\"mso-number-format:'\@';\">%s</td>", $registro[$name]);	
					}
					
				}
				echo sprintf("</tr>");
			}
		} ?>
	</table>	

</body>
</html>                
               
               