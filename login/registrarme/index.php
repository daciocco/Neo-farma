<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/detect.Browser.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php");
?>
<!DOCTYPE html >
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";	?>
	<script type="text/javascript">
        $(document).ready(function() {		
            $("#emailconfirm").bind('paste', function(e) {
                e.preventDefault();
            });		
        });
    </script>
</head>
<body>
   	<main class="cuerpo">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/login/registrarme/registrarme.php"); ?> 
	</main> <!-- fin cuerpo -->	       
</body>
</html>