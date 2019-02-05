<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}
?>
<!DOCTYPE>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
    <link rel="stylesheet" type="text/css" href="fullcalendar.css">
    <!--link rel="stylesheet" type="text/css" href="fullcalendar.print.css"-->
    <!--script type="text/javascript" src="lib/jquery.min.js"></script-->
    <script type="text/javascript" src="lib/moment.min.js"></script>
    <script type="text/javascript" src="fullcalendar.js"></script>
    
    <!-- Están definidos todos los idiomas que puede tener la agenda-->
    <script type="text/javascript" src="locale-all.js"></script>
</head>

<body>

    <header class="cabecera" >
		<?php 		
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
            <tr align=">	
    <nav class="menuprincipal"> <?php 
        $_section 	= 'agenda';
        $_subsection	= '';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
         
    <main class="cuerpo">
        <?php include "lista.php"; ?> 
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>

