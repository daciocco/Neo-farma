<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!= "P"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>
<body>
    <header class="cabecera">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
        $_section	=	'provpagos';
        $_subsection	= 	'provfechapago';			
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
        
    <main class="cuerpo">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/banner.header.inc.php"); ?> 
        
        <?php include "lista.php"; ?> 
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>