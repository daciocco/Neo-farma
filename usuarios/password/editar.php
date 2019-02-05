<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="P" && $_SESSION["_usrrol"]!="G"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_sms 		= empty($_GET['sms']) ? 0 : $_GET['sms'];
$_uid		= $_SESSION["_usrid"]; //empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/logout.php': $_REQUEST['backURL'];

if ($_sms) {
	$_uusuario 			= $_SESSION['s_usuario'];
	$_upassword 		= $_SESSION['s_password'];
	$_unewpassword 		= $_SESSION['s_newpassword'];
	$_unewpasswordbis 	= $_SESSION['s_newpasswordbis'];
	switch ($_sms) {
		case 1: $_info = "Usuario err&oacute;neo o inexistente"; break;
		case 2: $_info = "Ingrese la clave actual"; break;
		case 3: $_info = "La clave del usuario es incorrecta"; break; //Habría que controlar la cantidad de intentos para desloguearlo
		case 4: $_info = "Complete la nueva clave"; break;
		case 5: $_info = "Repita la nueva clave"; break;
		case 6: $_info = "La nueva clave no coincide"; break;
		case 7: $_info = "Error en el e-mail. Confirme con la empresa su cuenta de correo"; break;
		case 8: $_info = "Su Contrase&ntilde;a ha sido modificada"; break;
		
	} // mensaje de error
} else {	
	if ($_uid) {
		$_usuario 			= DataManager::newObjectOfClass('TUsuario', $_uid);
		$_uusuario			= "";
		$_unewpassword 		= "";
		$_unewpasswordbis 	= "";
	 } 
}
$_button = sprintf("<input type=\"submit\" id=\"f_enviar\" name=\"_accion\" value=\"Cambiar\"/>");
$_action = "/pedidos/usuarios/password/logica/update.password.php?backURL=".$backURL;
?>

<script type="text/javascript">
	function dac_MostrarSms(sms){
		document.getElementById('box_error').style.display 			= 'none';
		if(sms){	
			if(sms > 0 && sms < 8){
				document.getElementById('box_error').style.display 			= 'block';
				document.getElementById('box_confirmacion').style.display 	= 'none';
			} else {
				document.getElementById('box_confirmacion').style.display 	= 'block';
				document.getElementById('box_error').style.display 			= 'none';
				
				window.setTimeout(window.location="/pedidos/login/index.php", 8000);
			}
		}
	}
</script>


<div class="box_body">							
	<form id="fm_cambiar_clave" name="fm_cambiar_clave" method="post" class="fm_edit2" action="<?php echo $_action;?>">
		<fieldset>
			<legend>&nbsp;Introduzca la nueva clave</legend> 
			<div class="bloque_3" align="center"> 
				<fieldset id='box_error' class="msg_error"> 	
					<div id="msg_error" align="center"> <?php  echo $_info; ?> </div>
				</fieldset>

				<fieldset id='box_confirmacion' class="msg_confirmacion">
					<div id="msg_confirmacion" align="center"><?php echo $_info;?></div>      
				</fieldset>   

				<?php
					echo "<script>";
					echo "javascript:dac_MostrarSms(".$_sms.")";
					echo "</script>";
				?>       
			</div> 

			<div class="bloque_2"> 
				<label for="uusuario">Usuario *</label>
				<input name="uusuario"  id="uusuario" type="text" maxlength="10" value="<?php echo @$_uusuario;?>"/>
			</div>
			<div class="bloque_2"> 	
				<label for="upassword">Clave actual *</label>
				<input name="upassword"  id="upassword" type="password" maxlength="10" value="<?php echo @$_upassword;?>"/>
			</div>
			<div class="bloque_2"> 
				<label for="unewpassword">Nueva Clave *</label>
				<input name="unewpassword"  id="unewpassword" type="password" maxlength="10" value="<?php echo @$_unewpassword;?>"/>
			</div>
			<div class="bloque_2"> 
				<label for="unewpasswordbis">Repita Nueva Clave *</label>
				<input name="unewpasswordbis"  id="unewpasswordbis" type="password" maxlength="10" value="<?php echo @$_unewpasswordbis;?>"/>
			</div>                     

			<div class="bloque_2"> <?php echo $_button;?></div>
		</fieldset>	
	</form>	
</div> <!-- boxbody -->