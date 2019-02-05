<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

 $_idnt		= empty($_REQUEST['idnt']) ? 0 : $_REQUEST['idnt'];
 $_sms 		= empty($_GET['sms']) ? 0 : $_GET['sms'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/noticias/': $_REQUEST['backURL'];

 if ($_idnt) {
	$_noticia			= DataManager::newObjectOfClass('TNoticia', $_idnt);
	$_ntitulo			= $_noticia->__get('Titulo');
	$_nfecha	 		= $_noticia->__get('Fecha');
		$_f = explode(" ", $_nfecha);
		list($ano, $mes,  $dia) = explode("-", $_f[0]);
	$_nfecha = $dia."-".$mes."-".$ano;	
	$_nnoticia			= $_noticia->__get('Descripcion');
	$_nlink				= $_noticia->__get('Link');
 }
  
 $_Navegacion 	= array();
 $_Navegacion[] = "Noticias";
?>

<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php
        $_section 	= '';
        $_subsection	= '';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
        
    <main class="cuerpo">
    	<?php if($_idnt) { ?>
			<div class="boxbody_noti_select">
				<article> 
					<header>Noticia Seleccionada</header>
					<section><titulo><?php echo $_ntitulo; ?></titulo>  </section> 
					<section><subtitulo><?php echo $_nfecha; ?></subtitulo></section> 
					<section><subtitulo><?php if($_nlink != ""){ echo "Link --> "; ?><a href="<?php echo $_nlink; ?>"/> <?php echo $_ntitulo; ?> </a> <?php }?></subtitulo></section> 
					<section><body><?php echo @$_nnoticia; ?> </body></section> 
				</article> 	
			</div>  <!-- boxbody -->
		<?php } ?>

		<?php
		$_total 	= DataManager::getNumeroFilasTotales('TNoticia', 0); 
		$_noticiastodas	= DataManager::getNoticias(); 

		for( $k=0; $k < $_total; $k++ ){
			$_noticiat 		= $_noticiastodas[$k];						
				$fecha 		= 	explode(" ", $_noticiat["ntfecha"]);
				list($ano, $mes, $dia) 	= 	explode ("-", $fecha[0]);
			$_fecha 		= 	$dia."-".$mes."-".$ano;
			$_titulo 		= 	$_noticiat["nttitulo"];	
			$_nlink			= 	$_noticiat["ntlink"];	
			$_descripcion 	= 	$_noticiat["ntdescripcion"];
			?>

			<div class="boxbody_noti">
				<article>							
					<section><titulo><?php echo $_titulo; ?></titulo></section>
					 <section><subtitulo><?php echo $_fecha; ?></subtitulo></section>
					<section><subtitulo><?php if($_nlink != ""){ echo "Link --> "; ?><a href="<?php echo $_nlink; ?>"/> <?php echo $_titulo; ?> </a> <?php }?></subtitulo></section> 
					<section><body><?php echo $_descripcion; ?></body></section>							
				</article>	
			</div>
			<?php
		} ?>
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->

</body>
</html>