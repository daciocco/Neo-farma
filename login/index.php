<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/detect.Browser.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
$HOME		 = $_SERVER['DOCUMENT_ROOT']."/pedidos/index.php";
$_step		 = empty($_REQUEST["_step"]) ? 1 : $_REQUEST["_step"];
$_nextURL	 = (isset($_REQUEST["_go"])) ? sprintf("%s?_go=%s", $_SERVER["PHP_SELF"], $_REQUEST["_go"]) : $HOME; 
$_goURL		 = (isset($_REQUEST["_go"])) ? $_REQUEST["_go"] : "/pedidos/index.php"; // url destino
$_salimos	 = false;
$backURL	 = empty($_REQUEST['backURL']) ? '/pedidos/login/': $_REQUEST['backURL'];

switch ($_step) {
	// Solo la primera vez ( el resto entra por el caso por defecto)
	case 1:
		$usrusuario	 = "";
		$usrpassword = "";
		$usrtipo	 = "";
		//$usrrol		= $USR_OPERADOR;
		$_message	 = "";
		break;
	default:
		$_message		= "Introduzca credenciales de usuario";
		$usrusuario 	= $_REQUEST["usrusuario"];
		$usrpassword	= $_REQUEST["usrpassword"];
		$usrtipo		= (isset($_REQUEST["usrtipo"])) ? $_REQUEST["usrtipo"] : '';
		unset($_SESSION["_usrid"], $_SESSION["_usrrol"], $_SESSION["_usrname"], $_SESSION["_usrlogin"]);
		
		switch ($usrtipo){
			case 'P':
				$_ID = DataManager::getIDByField('TProveedor', 'provlogin', $usrusuario);
				break;
			default:
				$_ID = DataManager::getIDByField('TUsuario', 'ulogin', $usrusuario);
				break;
		}
		
		if ($_ID > 0) {
			switch ($usrtipo){
				case 'P':
					$_usrobject = DataManager::newObjectOfClass('TProveedor', $_ID); 
					$usrtipo	= 'P';
					break;
				default:
					$_usrobject = DataManager::newObjectOfClass('TUsuario', $_ID);
					break;
			}
			
			$_SESSION["_usractivo"]	= $_usrobject->__get("Activo");
			
			//Si el usuario fue desactivado, no podrá loguearse
			if ($_SESSION["_usractivo"] != 1) {
				$_message = "Su usuario est&aacute; inactivo.";
			} else {
				if ($_usrobject->login(md5($usrpassword))) {
					$_SESSION["_usrid"]		= $_ID;		
					$_SESSION["_usrname"]	= $_usrobject->__get("Nombre");
					$_SESSION["_usrlogin"]	= $_usrobject->__get("Login");
					$_SESSION["_usrclave"]	= $_usrobject->__get("Clave");	
					$_SESSION["_usremail"]	= $_usrobject->__get("Email");
					
					if (empty($usrtipo)){
						$_SESSION["_usrrol"]	= $_usrobject->__get("Rol");
						$_SESSION["_usrdni"]	= $_usrobject->__get("Dni");						
					} else {
						$_SESSION["_usrrol"]	= $usrtipo;
						$_SESSION["_usrcuit"]	= $_usrobject->__get("Cuit");
						//Si el usuario es proveedor, debo tener sus datos extras
						if ($usrtipo == 'P') {
							$_SESSION["_usridemp"]	= $_usrobject->__get("Empresa");		
						}
					}
					
					$_goURL		= empty($_goURL) ? "/pedidos/index.php" : $_goURL; 
					$_salimos	= true;
					
					// SI EL ROL ES DISTINTO DE P O C					
					// Teniendo el Vendedor, consulto sus Zonas
					if ($_SESSION["_usrrol"] != "P"){
						if (empty($usrtipo)){
							$_zonas = DataManager::getZonasVendedor($_ID, 0);	
							unset($_SESSION["_usrzonas"]);
							if (count($_zonas) ) {					
								foreach ($_zonas as $k => $_zona) {
									if ($_SESSION["_usrzonas"] == ""){ $_SESSION["_usrzonas"] = $_zona["zona"];
									} else { $_SESSION["_usrzonas"] = $_SESSION["_usrzonas"].", ".$_zona["zona"]; }						
								}
							}
						}
					}
					
				} else { $_message = "Password incorrecto"; }
			}
			
		} else { $_message = "Usuario inexistente"; }
		
		break;
}

if ($_salimos) { 
	header("Location: $_goURL");
	exit;
}


// valores para configuracion del formulario
$_step++;
$btAccion	= sprintf("<input id=\"bt_login\" name=\"_accion\" type=\"submit\" value=\"Iniciar\"/>");

/**************************/
$_info = "";
$_sms	=	empty($_GET['sms']) ? 0 : $_GET['sms'];
if ($_sms) {
	$_rec_usuario	= $_SESSION['s_usuario'];
	$_rec_mail 		= $_SESSION['s_email'];
	switch ($_sms) {
		case 1: $_info = "El usuario es incorrecto."; break;
		case 2: $_info = "El formato de correo es incorrecto."; break;
		case 3: $_info = "El correo es incorrecto."; break;
		case 4: $_info = "Se han enviado los datos a la cuenta de correo del usuario."; break;		
	} // mensaje de error
 } 
 
 $_button = sprintf("<input type=\"submit\" id=\"f_enviar\" name=\"_accion\" value=\"Recuperar Clave\"/>");
 $_action = "/pedidos/usuarios/logica/recupera.usuario.php?backURL=".$backURL;

?>
<script language="JavaScript"  src="/pedidos/js/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
	// FUNCIONES PARA ABRIR Y CERRAR LOS POPUPS
	$(document).ready(function(){
		$('#recuperar-clave').click(function(){
			$('#popup-recupera').fadeIn('slow');
			$('#popup-recupera').css({ //coloca el popup-recupera centrado en la web, solo al momento de crearse 
				'left': ($(window).width() / 2 - $('popup-recupera').width() / 2) + 'px', 
				'top': ($(window).height() / 2 - $('popup-recupera').height() / 2) + 'px'
			});
			$('.popup-overlay').fadeIn('slow');
			$('.popup-overlay').height($(window).height());
			return false;
		});
	
		$('#close-recupera').click(function(){
			$('#popup-recupera').fadeOut('slow');
			$('.popup-overlay').fadeOut('slow');				
			return false;
		});	
	});		
</script>
<script type="text/javascript">
	function dac_recuperar(sms){
		if (sms){
			document.getElementById("popup-recupera").style.display = 'inline';
		}
	}
</script>
        
<!DOCTYPE html>
<html lang='es'>
	<head>
    	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";	?>
	</head>
	<body>
		<main class="cuerpo" align="center">		
			<img id="imglogo" src="../images/logo/logoLogin.png" />
			<form id="fmlogin" method="post" action="<?php sprintf("%s", $_nextURL); ?>">
				<fieldset>
					<div class="bloque_3"> 
						<input name="usrusuario" id="usrusuario" size="15" maxlength="15" type="text" placeholder="Usuario" title="Usuario" value="<?php echo @$usrusuario;?>"/>
					</div>					
					<div class="bloque_7"> 
						<img title="Usuario" src="/pedidos/images/icons/icono-usuario.png"/>
					</div>
					
					<div class="bloque_3">
						<input name="usrpassword" id="usrpassword" size="15" maxlength="10" type="password" title="Clave no debe superar los 10 d&iacute;gitos" placeholder="Clave"  value="<?php echo @$usrpassword;?>"/> 
					</div>					
					<div class="bloque_7"> 
						<img title="Clave" src="/pedidos/images/icons/icono-login.png"/>
					</div>
					
					<div class="bloque_1" align="right">
						<input name="usrtipo" id="usrtipo" type="radio" value="P" style="float:left; margin-left: 30px;"/>
						<label>Soy Proveedor</label>  
						<img title="Proveedor" src="/pedidos/images/icons/icono-proveedor.png"/>
					</div>
					
					<div class="bloque_1" align="right">
						<div id="recuperar-clave" class="link-loguin" align="right">&iquest;Olvid&eacute; mi clave?</div> 
					</div> 
					
					<div class="bloque_1" align="right">
						<a href="registrarme/" style="text-decoration:none;"><div id="registrarme" class="link-loguin" align="right">Registrarme como Proveedor</div></a>
					</div> 

					<div class="bloque_1"> <?php echo $btAccion;?> </div>
				</fieldset>
				
				
				<input type="hidden" name="_step" id="_step" value="<?php echo @$_step;?>"/>

				<p style="color:orange;" align="center"><?php echo $_message; ?></p>
			</form>
				

			<div id="popup-recupera" style="display:none;">  	
				<form id="fm_recuperacion" method="post" action="<?php echo $_action;?>">
					<fieldset>
						<legend>Recupera tu cuenta</legend>
						
						<div class="bloque_1">
							<a href="#" id="close-recupera" style="float:right;">
							<img id="img-close" src="/pedidos/images/popup/close.png"/></a>
						</div>
						<div class="bloque_3">
							<input name="rec_usuario" id="rec_usuario" maxlength="15" type="text" placeholder="Usuario" value="<?php echo @$_rec_usuario;?>" />
						</div>	
						<div class="bloque_7"> 
							<img title="Usuario" src="/pedidos/images/icons/icono-usuario.png"/>
						</div>
					
						<div class="bloque_3">
							<input name="rec_mail" id="rec_mail" maxlength="50" type="text" placeholder="Correo electr&oacute;nico" value="<?php echo @$_rec_mail;?>" /> 
						</div>						
						<div class="bloque_7"> 
							<img id="img-correo" src="/pedidos/images/icons/icono-correo.png"/>
						</div>						
						<div class="bloque_1"><?php echo $_button; ?></div>
					</fieldset>
					
					<input type="hidden" name="_sms" id="_sms" value="<?php echo @$_sms;?>" />
					<p style="color:orange;" align="center"><?php echo $_info;?></p>
				</form>
					
			</div><!-- fin popup-recupera -->  

			<?php
				echo "<script>";
				echo "javascript:dac_recuperar(".$_sms.")";
				echo "</script>";
			?>    
		</main>
	</body>
</html>