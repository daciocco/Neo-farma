<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A"){ 	
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }

 $_idnt		= empty($_REQUEST['idnt']) 		? 0 : $_REQUEST['idnt'];
 $_sms 		= empty($_GET['sms']) 			? 0 : $_GET['sms'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/noticias/': $_REQUEST['backURL'];

 if ($_sms){
	$_ntitulo		= $_SESSION['s_titulo'];
	$_nfecha		= $_SESSION['s_fecha'];
	$_ndescripcion 	= $_SESSION['s_descripcion'];
	$_nnoticia 		= $_SESSION['s_noticia'];
	switch ($_sms) {
		case 1: $_info = "Debe completar el campo del titulo."; break;
		case 2: $_info = "La fecha es incorrecta."; break;
		case 3: $_info = "El campo noticia debe completarse."; break;
	 } 
 } else {
	if ($_idnt) {
		$_noticia			= DataManager::newObjectOfClass('TNoticia', $_idnt);
		$_ntitulo			= $_noticia->__get('Titulo');
		$_nfecha	 		= $_noticia->__get('Fecha');
			$_f = explode(" ", $_nfecha);
			list($ano, $mes,  $dia) = explode("-", $_f[0]);
		$_nfecha = $dia."-".$mes."-".$ano;	
		$_nnoticia			= $_noticia->__get('Descripcion');
		$_nlink				= $_noticia->__get('Link');
	} else {
 		$_ntitulo		= "";
		$_nfecha		= "";
		$_nnoticia		= "";
		$_nlink			= "";
	}
 }

 $_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\"/>");
 $_action = sprintf("logica/update.noticia.php?idnt=%d", $_idnt);
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
        $_section 	= 'noticias';
        $_subsection	= 'nueva_noticia';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
        
    <main class="cuerpo">
		
		<div class="box_body"> 
			<form name="fm_noticia" method="post" action="<?php echo $_action;?>">
				<fieldset>
					<legend>Noticia</legend> 
					<div class="bloque_1">
						<?php
						if ($_sms) {
							echo sprintf("<p style=\"background-color:#fcf5f4;color:#ba140c;border:2px solid #ba140c;font-weight:bold;padding:4px;\">%s</p>", $_info);
						} ?>			
					</div>
					<div class="bloque_5">
						<label for="ntitulo">Titulo *</label>
						<input type="text" id="ntitulo" name="ntitulo" maxlength="80" value="<?php echo @$_ntitulo; ?>"/>
					</div>												
					<div class="bloque_5">
						<label for="nfecha">Fecha *</label>
						<input type="text" name="nfecha" id="nfecha" maxlength="10" value="<?php echo @$_nfecha; ?>" readonly/>&nbsp;
					</div>

					<div class="bloque_1">
						<label for="nnoticia">Noticia *</label>
						<textarea id="nnoticia" name="nnoticia" value="<?php echo @$_nnoticia; ?>"/></textarea>
					</div>
					<div class="bloque_1">
						<label for="nlink">Link</label>
						<input type="text" id="nlink" name="nlink" maxlength="80" value="<?php echo @$_nlink; ?>"/>
					</div>
					<input type="hidden" name="idnt" value="<?php echo @$_idnt;?>"/>
					
					<div class="bloque_8">
						<label for="_accion">&nbsp;</label> <?php echo $_button; ?>
					</div>
				</fieldset>	
			</form>	
			
		</div>  <!-- boxbody -->
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->


</body>
</html>


 <!-- Scripts para calendario -->
	<script type="text/javascript">
			new JsDatePick({
				useMode:2,
				target:"nfecha",
				dateFormat:"%d-%M-%Y"			
			});
	</script>