<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/detect.Browser.php");
?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";	?>
</head>
<body>
	<main class="cuerpo">
  		<div id="box_down">
			<div id="logoneo" align="center">
				<?php
				//header And footer
				include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
				echo $cabeceraPedido;
				 ?>
			</div><!-- logo Neofarma -->    

			<?php include "terminos.php"; ?> 
		</div> <!-- cuerpo -->
    </main> <!-- fin cuerpo -->
</body>
</html>