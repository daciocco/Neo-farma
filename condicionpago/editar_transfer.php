<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
 }

 $_condid	= 	empty($_REQUEST['condid']) ? 0 : $_REQUEST['condid'];
 $_sms 		= 	empty($_GET['sms']) ? 0 : $_GET['sms'];
 $backURL	= 	empty($_REQUEST['backURL']) ? '/pedidos/condicionpago/': $_REQUEST['backURL'];

 if ($_sms) {	
 	$_condcodigo 	=	isset($_SESSION['s_codigo']) ? $_SESSION['s_codigo'] : '';
	$_condnombre 	=	isset($_SESSION['s_nombre']) ? $_SESSION['s_nombre'] : '';
	$_conddias	 	=	isset($_SESSION['s_dias']) ? $_SESSION['s_dias'] : '';
	$_condporcentaje=	isset($_SESSION['s_porcentaje']) ? $_SESSION['s_porcentaje'] : '';
	 
 	 switch ($_sms) { 
	 	case 1: $_info = "El c&oacute;digo es obligatorio"; break;
		case 2: $_info = "El nombre es obligatorio."; break;
		case 3: $_info = "La condici&oacute;n de pago ya existe."; break;	
	 } // mensaje de error
 }
 
 if ($_condid) {
	if (!$_sms) {
		$_condcion 		= DataManager::newObjectOfClass('TCondiciontransfer', $_condid);
		$_condcodigo 	= $_condcion->__get('Codigo');
		$_condnombre 	= $_condcion->__get('Nombre');
		$_conddias	 	= $_condcion->__get('Dias');
		$_condporcentaje= $_condcion->__get('Porcentaje');
	}
	$_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_condicion\" value=\"Enviar\"/>");
	$_action = "logica/update.condicion.transfer.php?backURL=".$backURL;
 } else {
	if (!$_sms) {
		$_condcodigo	= "";
		$_condnombre	= "";
		$_conddias	 	= "";
		$_condporcentaje= "";
	}
	$_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_condicion\" value=\"Enviar\"/>");
	$_action = sprintf("logica/update.condicion.transfer.php?uid=%d&backURL=", $_condid, $backURL);
 }
?>
<!DOCTYPE>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>	
</head>
<body>	
	<header class="cabecera">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
	</header><!-- cabecera -->	

	<nav class="menuprincipal"> <?php 
		$_section 		= "condiciones_pago";
		$_subsection 	= "nueva_condicion_transfer";
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
	</nav>	

	<main class="cuerpo">
		<div class="box_body">	
		    <div class="bloque_1"> <?php
				if ($_sms) { ?>
					<div class="bloque_1" align="center">
						<fieldset id='box_error' class="msg_error" style="display:block">          
							<div id="msg_error"><?php echo $_info;?></div>
						</fieldset> 
					</div> <?php
				} ?>
			</div>     				
			<form id="fm_condicion_edit" name="fm_condicion_edit" method="post" action="<?php echo $_action;?>">
				<fieldset>
					<legend>Condici&oacute;n</legend>					  
					<div class="bloque_1">
		  				<fieldset id='box_observacion' class="msg_alerta" style="display:block">          
							<div id="msg_atencion">Importante: los d&iacute;as deber&aacute;n cargarse en enteros separados por comas (como en el ejemplo) sin caracteres adicionales.</div>
						</fieldset>
				  	</div> 
				  	 
					<div class="bloque_8">
						<label for="condcodigo">C&oacute;digo *</label>
						<input name="condcodigo" id="condcodigo" type="text" maxlength="5" value="<?php echo @$_condcodigo;?>"/>
					</div>                    

					<div class="bloque_4">
						<label for="condnombre">Condici&oacute;n*</label>
						<input name="condnombre" id="condnombre" type="text" maxlength="50" value="<?php echo @$_condnombre;?>"/>
					</div>      
					                 
					<div class="bloque_8">                        	
						<label for="conddias">D&iacute;as</label>                           
						<input name="conddias" id="conddias" type="text" maxlength="50" value="<?php echo @$_conddias;?>" placeholder="30, 60, 90"/>
					</div>
					  
					<div class="bloque_8">
						<label for="condporcentaje">Porcentaje</label>
						<input name="condporcentaje" id="condporcentaje" type="text" maxlength="1" value="<?php echo @$_condporcentaje;?>"/>
					</div> 
					<input type="hidden" name="condid" value="<?php echo @$_condid;?>"/>

					<div class="bloque_7"> <?php echo $_button; ?> </div>
				</fieldset>		
			</form>	
		</div> <!-- FIN box_body -->
		<hr>
	</main> <!-- fin cuerpo -->

	<footer class="pie">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
	</footer> <!-- fin pie -->

</body>
</html>