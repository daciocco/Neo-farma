<?php
$_nextURL	= (isset($_REQUEST["_go"])) ? sprintf("%s?_go=%s", $_SERVER["PHP_SELF"], $_REQUEST["_go"]) : "/pedidos/index.php"; 
$_goURL		= (isset($_REQUEST["_go"])) ? $_REQUEST["_go"] : "/pedidos/index.php"; // url destino
$btAccion	= sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\" style=\"background-color:#DF6900;\"/>");
$_action 	= sprintf("logica/update.registrarme.php");

$_sms = (empty($_GET['sms'])) ? 0 : $_GET['sms'];
if ($_sms) {
	$_razonsocial		=	isset($_SESSION['razonsocial']) ? $_SESSION['razonsocial'] : '';	
	$_usuario			=	isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';	
	$_telefono			=	isset($_SESSION['telefono']) ? $_SESSION['telefono'] : '';	
	$_provincia			=	isset($_SESSION['provincia']) ? $_SESSION['provincia'] : '';	
	$_localidad			=	isset($_SESSION['localidad']) ? $_SESSION['localidad'] : '';	
	$_direccion			=	isset($_SESSION['direccion']) ? $_SESSION['direccion'] : '';	
	$_codpostal			=	isset($_SESSION['codpostal']) ? $_SESSION['codpostal'] : '';	
	$_email				=	isset($_SESSION['email']) ? $_SESSION['email'] : '';	
	$_emailconfirm		=	isset($_SESSION['emailconfirm']) ? $_SESSION['emailconfirm'] : '';	
	$_clave				=	isset($_SESSION['clave']) ? $_SESSION['clave'] : '';	
	$_web				=	isset($_SESSION['web']) ? $_SESSION['web'] : '';	
	$_cuit				=	isset($_SESSION['cuit']) ? $_SESSION['cuit'] : '';	
	$_nroIBB			=	isset($_SESSION['nroIBB']) ? $_SESSION['nroIBB'] : '';	
	$_comentario		=	isset($_SESSION['comentario']) ? $_SESSION['comentario'] : '';	

	switch ($_sms) {
		case 1: $_info = "Indique Raz&oacuten Social.";
				break;
		case 2: $_info = "CUIT incorrecto.";
				break;
		case 3: $_info = "Indique n&uacute;mero de tel&eacute;fono.";
				break;
		case 4: $_info = "El usuario ya existe registrado.";
				break;
		case 5: $_info = "El correo ya existe registrado.";
				break;
		case 6: $_info = "La direcci&oacute;n de correo es incorrecta.";
				break;
		case 7: $_info = "Los correos no coinciden.";
				break;
		case 8:$_info 	= "Indique una clave de usuario.";
				break;
		case 9:$_info = "La direcci&oacute;n Web es incorrecta.";
				break;
		case 10:$_info = "Debe adjuntar Constancia de inscripci&oacute;n y Formulario CM01.";
				break;
		case 11:$_info = "Controle que los archivos adjuntos no superen los 4 MB.";
				break;
		case 12:$_info = "El usuario es incorrecto.";
				break;
		case 13:$_info = "Indique la provincia.";
				break;
		case 14:$_info = "Indique la localidad.";
				break;
		case 15:$_info = "El c&oacute;digo postal debe ser num&eacute;rico.";
				break;	
		case 16:$_info = "Indique una direcci&oacute;n.";
				break;	
		case 18:$_info = "El usuario debe contener solo caracteres alfanum&eacute;ricos.";
				break;
		case 19:$_info = "Debe activar el control de reCAPTCHA para indicar que no es un robot.";
				break;
		case 20:$_info = "No se pudo enviar la solicitud.";
				break;
		case 21:$_info = "Fall&oacute; el env&iacute;o por correo. Si no recibe respuesta, p&oacute;ngase en contacto con la empresa.";
				break;
		case 22:$_info = "Uno de los archivos no es imagen o pdf.";
				break;	
		case 23:$_info = "Error al archivar la documentaci&oacute;n adjunta";
				break;
		case 24:$_info = "Debe completar Ingresos Brutos";
				break;
		case 30:$_info = "Su solicitud fue enviada correctamente";
				break;
		
	} // mensaje de error	
} else {
	$_razonsocial		=	$_usuario			=	$_telefono			=
	$_provincia			=	$_localidad			=	$_direccion			=	
	$_codpostal			=	$_email				=	$_emailconfirm		=	
	$_clave				=	$_web				=	$_cuit				=	
	$_nroIBB			=	$_comentario		=	"";
} // $_sms > 0 ==> ERROR DETECTADO EN EL SCRIPT DE PROCESO DEL FORMULARIO (NO JAVASCRIPT)
/**************************/
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#email").bind('paste', function(e) {
			e.preventDefault();
		});
		$("#emailconfirm").bind('paste', function(e) {
			e.preventDefault();
		});
	});
</script>

<script type="text/javascript">
	function dac_MostrarSms(sms){
		document.getElementById('box_error').style.display 			= 'none';
		if(sms){	
			if(sms > 0 && sms < 30){
				document.getElementById('box_error').style.display 			= 'block';
				document.getElementById('box_confirmacion').style.display 	= 'none';
			} else {
				document.getElementById('box_confirmacion').style.display 	= 'block';
				document.getElementById('box_error').style.display 			= 'none';
			}
		}
	}
</script>

<div id="box_down" align="center">
	<?php
	//header And footer
	include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
	echo $cabeceraPedido;
	?>
		
	<div class="registro" align="center">   
		<div class="barra" align="left">
			<tit_1>Registrarme como Proveedor</tit_1> 
			<hr>
		</div>     
		<form method="post" enctype="multipart/form-data" action="<?php echo $_action; ?>">
			<input type="hidden" name="sms" id="sms" value="<?php echo $_sms;?>"/>
			
			<div class="bloque_1"> 
				<fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;">                  
					<div id="msg_cargando" align="center"></div>      
				</fieldset>   
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

			<div class="bloque_1">
				<select id="empselect" name="empselect"  style="color:#5c788e;"><?php
					$_empresas	= DataManager::getEmpresas(1); 
					if (count($_empresas)) {	
						foreach ($_empresas as $k => $_emp) {
							$_idemp		=	$_emp["empid"];
							$_nombreemp	=	$_emp["empnombre"];
							if ($_idemp == 3){ ?>                        		
								<option id="<?php echo $_idemp; ?>" value="<?php echo $_idemp; ?>" selected><?php echo $_nombreemp; ?></option><?php
							} else { ?>
								<option id="<?php echo $_idemp; ?>" value="<?php echo $_idemp; ?>"><?php echo $_nombreemp; ?></option><?php
							}   
						}                            
					} ?>
				</select>
			</div>

			<div class="bloque_1">
				<input id="razonsocial" name="razonsocial" type="text" placeholder="Raz&oacute;n Social *" value="<?php echo @$_razonsocial;?>" maxlength="50"/>
			</div>

			<div class="bloque_5">
				<input id="cuit" name="cuit" type="text" placeholder="Cuit *" value="<?php echo @$_cuit;?>" maxlength="13"  /> 
			</div>

			<div class="bloque_5">
				<input id="nroIBB" name="nroIBB" type="text" placeholder="Nro. Ingresos Brutos *" value="<?php echo @$_nroIBB;?>" maxlength="15"  /> 
			</div>

			<div class="bloque_5">
				<!--El id lo usaremos para seleccionar este elemento con el jQuery-->
				<select id="provincia" name="provincia" /> 
					<option> Seleccione Provincia... </option> <?php
					$_provincias	= DataManager::getProvincias(); 
					if (count($_provincias)) {	
						foreach ($_provincias as $k => $_prov) {		
							if ($_provincia == $_prov["provid"]){ ?>  
								<option id="<?php echo $_prov["provnombre"]; ?>" value="<?php echo $_prov["provid"]; ?>" selected><?php echo $_prov["provnombre"]; ?></option>   <?php
							} else { ?>
								<option id="<?php echo $_prov["provnombre"]; ?>" value="<?php echo $_prov["provid"]; ?>"><?php echo $_prov["provnombre"]; ?></option><?php
							}   
						}                            
					} ?> 
				</select>
			</div>  

			<div class="bloque_5">                                                          
				<select id="f_localidad" name="localidad">  <?php 
					if (!empty($_localidad)){ ?>
						<option value="<?php echo $_localidad; ?>" selected><?php echo $_localidad; ?></option> <?php
					} ?>
				</select>
			</div>

			<div class="bloque_1">
				<input id="direccion" name="direccion" type="text" placeholder="Direcci&oacute;n *" value="<?php echo $_direccion;?>" maxlength="50"/>
			</div>

			<div class="bloque_7"> 
				<input id="codpostal" name="codpostal" type="text" placeholder="C&oacute;digo Postal" value="<?php echo $_codpostal;?>" maxlength="6"  /> 
			</div>


			<div class="bloque_7">
				<input id="telefono" name="telefono" type="text" placeholder="Tel&eacute;fono *" value="<?php echo @$_telefono;?>"  maxlength="15"/>
			</div>

			<div class="bloque_7">
				<input id="usuario" name="usuario" type="text" placeholder="Usuario *" value="<?php echo @$_usuario;?>" maxlength="15"/>
			</div>

			<div class="bloque_7">
				<input id="clave" name="clave" type="password" placeholder="Contrase&ntilde;a *" value="<?php echo $_clave;?>" maxlength="15" /> 
			</div>

			<div class="bloque_1">
				<input id="email" name="email" type="text" placeholder="Correo electr&oacute;nico *" value="<?php echo @$_email;?>" maxlength="50"/>
			</div>

			<div class="bloque_1">
				<input id="emailconfirm" name="emailconfirm" type="text" placeholder="Confirmar correo electr&oacute;nico *" value="<?php echo $_emailconfirm;?>" maxlength="50" />
			</div>

			<div class="bloque_1">
				<input id="web" name="web" type="text" placeholder="P&aacute;gina web" value="<?php echo @$_web;?>" maxlength="50" />
			</div>
			
			<div class="bloque_1">
				<label><strong>Documentaci&oacute;n (m&aacute;ximo por archivo de 4 MB, pdf o jpg) </strong></label>
			</div>                                                                                                     
			<div class="bloque_5" > 
				<label style="color:#666;"> Constancia de Inscripci&oacute;n *</label>     </br>                           	
				<div class="inputfile"><input type="file" name="archivo1"/></div>
			</div>                
			<div class="bloque_5" >  
				<label style="color:#666;"> Formulario CM01</label> </br>
				<div class="inputfile"><input type="file" name="archivo2" /></div>  
			</div>    

			<div class="bloque_5">
				<label style="color:#666;"> Formulario CM05 </label></br>
				<div class="inputfile"><input type="file" name="archivo3" /></div>  
			</div>                                    

			<div class="bloque_5"> 
				<label style="color:#666;"> Excenci&oacute;n de ganancias </label> </br> 
				<div class="inputfile"><input type="file" name="archivo4" /></div>     
			</div>  

			<div class="bloque_1">
				<textarea id="comentario" name="comentario" placeholder="Comentarios" rez value="<?php echo $_comentario;?>" style="resize:none;" onKeyUp="javascript:dac_LimitaCaracteres(event, 'comentario', 200);" onKeyDown="javascript:dac_LimitaCaracteres(event, 'comentario', 200);"/><?php echo $_comentario;?></textarea>
				<fieldset id='box_informacion' class="msg_informacion">
					<div id="msg_informacion" align="center"></div> 
				</fieldset>
			</div>

			<div class="bloque_1" style="color:#666;">
				Al hacer clic en "Enviar" aceptas los <a href="/pedidos/legal/terminos/" target="_blank">T&eacute;rminos y Condiciones</a>, confirmando as&iacute; fueron le&iacute;das.
			</div>

			<div class="bloque_1" align="center">
				<div class="g-recaptcha" data-theme="dark light" data-size="compact normal" data-sitekey="6LfNHR0TAAAAACIVpgrpukULyFpP3IPnZSSaAJ-g"></div>
			</div>

			<div class="bloque_7" style="float: right;"> <?php echo $btAccion;?> </div>

		</form>
	</div> <!-- fin registro -->  
</div> <!-- fin cuerpo -->  