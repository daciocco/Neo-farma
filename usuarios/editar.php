<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A"){ 	
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }
 
 $_uid		= empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
 $_sms 		= empty($_GET['sms']) ? 0 : $_GET['sms'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL'];
 
 if ($_sms) {
 	 $_unombre 			= $_SESSION['s_nombre'];
	 $_uusuario 		= $_SESSION['s_usuario'];
	 $_upassword 		= $_SESSION['s_password'];
	 $_upasswordbis 	= $_SESSION['s_passwordbis'];
	 $_udni				= $_SESSION['s_dni'];
	 $_uemail 			= $_SESSION['s_email'];
	 $_urol				= $_SESSION['s_rol'];
	 $_uobs				= $_SESSION['s_obs'];
	 switch ($_sms) {
		case 1: $_info = "El nombre es obligatorios."; break;
		case 2: $_info = "Debe completar un nombre de usuario o el mismo está siendo utilizado."; break;
		case 3: $_info = "Las claves no coinciden."; break;
		case 4: $_info = "Debe completar un dni de usuario o el mismo ya existe."; break;
	 	case 5: $_info = "Por favor, introduzca un e-mail correcto."; break;
	 } // mensaje de error
 } else {
	 if ($_uid) {
		$_usuario = DataManager::newObjectOfClass('TUsuario', $_uid);
		$_unombre 			= $_usuario->__get('Nombre');
		$_uusuario 			= $_usuario->__get('Login');
		$_upassword 		= $_usuario->__get('Clave');
		$_upasswordbis 		= $_usuario->__get('Clave');
		$_udni		 		= $_usuario->__get('Dni');
		$_uemail 			= $_usuario->__get('Email');
		$_urol				= $_usuario->__get('Rol');
		$_uobs				= $_usuario->__get('Obs');
	 } else {
	 	$_unombre 			= "";
		$_uusuario 			= "";
		$_upassword 		= "";
		$_upasswordbis 		= "";
		$_udni		 		= "";		
		$_uemail 			= "";
		$_urol				= "";
		$_uobs				= "";
	 }
 }

 $_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\"/>");
 $_action = sprintf("logica/update.usuario.php?uid=%d&backURL=", $_uid, $backURL);
 
 //$_clientes 	= DataManager::getClientesForSelect();
 $_Navegacion 	= array();
 $_Navegacion[] = sprintf("<a href=\"%s\" title=\"usuarios del sistema\">%s</a>", "/pedidos/usuarios/", "<img src=\"../images/icons/icono-lista.png\" border=\"0\" align=\"absmiddle\" />");
 $_Navegacion[] = ($_uid) ? "Editar usuario" : "Nuevo usuario";
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
    <script type="text/javascript" src="../js/funciones_comunes.js"></script>
</head>

<body>

	<header class="cabecera">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
	</header><!-- cabecera -->		

	<nav class="menuprincipal"> <?php 
		$_section 	= 'usuarios';
        $_subsection= 'nuevo_usuario';
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
	</nav>		

	<main class="cuerpo">
		<div class="box_body">
			<div class="bloque_1">     
				<fieldset id='box_error' class="msg_error">          
					<div id="msg_error" align="center"></div>
				</fieldset>                                                                         
				<fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;"> 
					<div id="msg_cargando" align="center"></div>      
				</fieldset> 
				<fieldset id='box_confirmacion' class="msg_confirmacion">
					<div id="msg_confirmacion" align="center"></div>      
				</fieldset>
				<fieldset id='box_informacion' class="msg_informacion">
					<div id="msg_informacion" align="center"></div> 
				</fieldset>   
			</div>
			<?php
			if ($_sms) { ?>
				<div class="bloque_1">
					<fieldset id='box_error' class="msg_error" style="display: block;">          
						<div id="msg_error" align="center"><?php echo $_info;?></div>
					</fieldset> 
				</div>

			<?php
			} ?>
			
			<form method="post" action="<?php echo $_action;?>">
				<input type="hidden" name="uid" value="<?php echo @$_uid; ?>" />
				<fieldset>
					<legend>Usuario</legend>  
					<div class="bloque_7">
						<label for="unombre">Nombre</label>
						<input type="text" name="unombre" id="unombre" maxlength="30" value="<?php echo @$_unombre;?>"/>
					</div>
					
					<div class="bloque_7">
						<label for="uapellido">Apellido</label>
						<input type="text" name="uapellido" id="uapellido" maxlength="30" value="<?php echo @$_uapellido;?>"/>
					</div>
					
					<div class="bloque_7">
						<label for="udni">Documento</label>
						<input name="udni"  id="udni" type="text" maxlength="50" value="<?php echo @$_udni;?>"/>
					</div>
					
					<div class="bloque_7">
						<label for="urol">Tipo de usuario</label>
						<select name="urol" id="urol">
							<option value="M" <?php if ($_urol=="M") echo "selected=\"selected\"";?>>Administraci&oacute;n</option>
							<option value="A" <?php if ($_urol=="A") echo "selected=\"selected\"";?>>Administrador</option>
							<option value="U" <?php if ($_urol=="U") echo "selected=\"selected\"";?>>Auditor&iacute;a</option>
							<option value="C" <?php if ($_urol=="C") echo "selected=\"selected\"";?>>Cliente</option>
							<option value="G" <?php if ($_urol=="G") echo "selected=\"selected\"";?>>Gerente</option> 
							<option value="V" <?php if ($_urol=="V") echo "selected=\"selected\"";?>>Vendedor</option>
						</select>
					</div>
					
					<div class="bloque_7">
						<label for="uarea">Area</label>
						<select name="uarea" id="urol">
							<option value="contaduria" <?php if ($uarea=="Contaduría") echo "selected=\"selected\"";?>>Contadur&iacute;a</option>
							<option value="A" <?php if ($uarea=="A") echo "selected=\"selected\"";?>>Administrador</option>
							<option value="U" <?php if ($uarea=="U") echo "selected=\"selected\"";?>>Auditor&iacute;a</option>
							<option value="C" <?php if ($uarea=="C") echo "selected=\"selected\"";?>>Cliente</option>
							<option value="G" <?php if ($uarea=="G") echo "selected=\"selected\"";?>>Gerente</option> 
							<option value="V" <?php if ($uarea=="V") echo "selected=\"selected\"";?>>Vendedor</option>
						</select>
					</div>
					
					<div class="bloque_7">
						<label for="uusuario">Usuario</label>
						<input name="uusuario"  id="uusuario" type="text" maxlength="20" value="<?php echo @$_uusuario;?>"/>
					</div>
					<div class="bloque_7">	
						<label for="upassword">Clave</label>
						<input name="upassword"  id="upassword" type="password" maxlength="10" value="<?php echo @$_upassword;?>"/>
					</div>
					<div class="bloque_7">
						<label for="upasswordbis">Repita clave</label>
						<input name="upasswordbis"  id="upasswordbis" type="password" maxlength="10" value="<?php echo @$_upasswordbis;?>"/>
					</div>
					
					<div class="bloque_5">
						<label for="uemail">correo</label>
						<input name="uemail"  id="uemail" type="text" maxlength="50" value="<?php echo @$_uemail;?>"/>
					</div>					

					<div class="bloque_5">
						<label for="uobs">Observaci&oacute;n</label>
						<textarea name="uobs" id="uobs" type="text" rows="4" cols="30" onkeyup="javascript:dac_LimitaCaracteres(event, 'uobs', 200);" onkeydown="javascript:dac_LimitaCaracteres(event, 'uobs', 200);" value="<?php echo @$_uobs;?>"/><?php echo @$_uobs;?></textarea> 
					</div>
					
					<div class="bloque_8">
						<label for="_accion">&nbsp;</label>
						<?php echo $_button; ?>
					</div>

					
				</fieldset>		
			</form>
					
		</div> <!-- END box_body --> 
		<hr>
	</main> <!-- fin cuerpo -->
	
			
	<footer class="pie">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
	</footer> <!-- fin pie -->

</body>
</html>