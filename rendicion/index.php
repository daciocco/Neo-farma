<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="V"  && $_SESSION["_usrrol"]!="G"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }
?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?> 
</head>
<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
		
	<?php if ($_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "V" || $_SESSION["_usrrol"]== "M"){?>
		<nav class="menuprincipal"> <?php 
			$_section = "rendicion";
			$_subsection = "";
			include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
		</nav> <!-- fin menu -->									
	<?php }?> 
	
	<main class="cuerpo">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/rendicion/lista.php"); ?>  
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>