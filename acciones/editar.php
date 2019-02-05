<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
 }

 $_acid		= empty($_REQUEST['acid']) ? 0 : $_REQUEST['acid'];
 $_sms 		= empty($_GET['sms']) ? 0 : $_GET['sms'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/acciones/': $_REQUEST['backURL'];

 if ($_sms) {	
	 $_acnombre		= $_SESSION['s_nombre'];
	 $_acsigla		= $_SESSION['s_sigla'];
	 
 	 switch ($_sms) { 
	 	case 1: $_info = "El nombre de la acci&oacute;n es obligatorio."; break;
		case 2: $_info = "La sigla de la acci&oacute;n es obligatoria."; break;
	 } // mensaje de error
 }
 
 if ($_acid) {
	if (!$_sms) {
		$_accion 				= DataManager::newObjectOfClass('TAccion', $_acid);
		$_acnombre 				= $_accion->__get('Nombre');
		$_acsigla	 			= $_accion->__get('Sigla');
		$_acactiva				= $_accion->__get('Activa');
	}
	$_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\"/>");
	$_action = "logica/update.accion.php?backURL=".$backURL;
 } else {
	if (!$_sms) {
		$_acnombre 			= "";
		$_acsigla	 		= "";
	}
	$_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\"/>");
	$_action = sprintf("logica/update.accion.php?uid=%d&backURL=", $_acid, $backURL);
 }
 
 $_Navegacion 		= array();
 $_Navegacion[] 	= sprintf("<a href=\"%s\" title=\"lista de acciones\">%s</a>", "/pedidos/acciones/", "<img src=\"../images/icons/icono-lista.png\" border=\"0\" align=\"absmiddle\" />");
 $_Navegacion[] 	= ($_acid) ? "Editar acci&oacute;n" : "Nuevo acci&oacute;n";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>	
</head>
<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
        <script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php
        $_section 	= 'acciones';
        $_subsection	= 'nueva_accion';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
        
             
    <main class="cuerpo">  
		<div class="box_body"> 				
			<form name="fm_accion_edit" class="fm_edit2" method="post" action="<?php echo $_action;?>">
				<fieldset>
					<legend>Acci&oacute;n</legend>  
					<div class="bloque_3"> 
						<label> <?php
							if ($_sms) {
								echo sprintf("<p style=\"background-color:#fcf5f4;color:#ba140c;border:2px solid #ba140c;font-weight:bold;padding:4px;\">%s</p>", $_info);
							}
						?></label>
					</div>                       

					<div class="bloque_1">
						<label for="acnombre">Nombre Acci&oacute;n*</label>
						<input name="acnombre" id="acnombre" type="text" size="20" maxlength="50" value="<?php echo @$_acnombre;?>"/>
					</div>                       
					<div class="bloque_2">   
						<label for="acsigla">Sigla *</label>
						<input name="acsigla" id="acsigla" type="text" size="10" maxlength="20" value="<?php echo @$_acsigla;?>"/>
					</div>                       
					<input type="hidden" name="acid" value="<?php echo @$_acid;?>" />
					<div class="bloque_2">   
						<label for="_accion">&nbsp;</label>
						<?php echo $_button; ?>
					</div>
				</fieldset>		
			</form>						
		</div> <!-- boxbody -->
	</main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
	
	
</body>
</html>