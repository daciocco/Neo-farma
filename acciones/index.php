<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.ToolBar.php");
if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

//BARRA DE HERRAMIENTAS (ACCIONES)
$_links 		= array();
$_links[1][]	= array('url'=>'editar.php', 'texto'=>'<img src=../images/icons/icono-nuevo50.png border=0 align=absmiddle title=nuevo_accion />', 'class'=>'newitem');

$_params 		= array(
	'modo'		=> 1,
	'separador' => '',
	'estilo'	=> 'toolbar',
	'aspecto'	=> 'links',
	'links'		=> $_links[1]);
$bar = ToolBar::factory($_params);

$_Navegacion 	= array();
$_Navegacion[] 	= 'Acciones';
?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
	<script language="JavaScript"> 
		function confirmar ( mensaje ) { return confirm( mensaje );} 
	</script>
</head>	
<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
        <script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php
        $_section 	= 'acciones';
        $_subsection	= 'lista_acciones';
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