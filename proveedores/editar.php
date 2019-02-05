<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$_pag		= empty($_REQUEST['pag']) 		? 0 						:	$_REQUEST['pag'];
$_provid	= empty($_REQUEST['provid']) 	? 0 						:	$_REQUEST['provid'];
$_sms 		= empty($_GET['sms']) 			? 0 						:	$_GET['sms'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/proveedores/'	:	$_REQUEST['backURL'];

if ($_sms) {	
	$_idempresa		= $_SESSION['s_empresa'];
	$_idproveedor	= $_SESSION['s_idproveedor']; 
	$_nombre		= $_SESSION['s_nombre'];
	$_direccion 	= $_SESSION['s_direccion'];
	$_idprovincia	= $_SESSION['s_provincia'];
	$_idloc			= $_SESSION['s_localidad'];
	$_cp			= $_SESSION['s_cp'];
	$_cuit 			= $_SESSION['s_cuit'];
	$_nroIBB		= $_SESSION['s_nroIBB'];
	$_telefono		= $_SESSION['s_telefono'];
	$_correo		= $_SESSION['s_correo'];	 
	$_observacion	= $_SESSION['s_observacion'];
	$_activo		= $_SESSION['s_activo'];
	
	switch ($_sms) { 
		case 1: $_info = "El n&uacute;mero de empresa es obligatorio."; break;
		case 2: $_info = "El n&uacute;mero de proveedor es obligatorio o ya existe."; break;
		case 3: $_info = "El nombre es obligatorio."; break;
		case 4: $_info = "La Direcci&oacute;n es obligatoria."; break;
		case 5: $_info = "La Provincia es obligatoria."; break;
		case 6: $_info = "La Localidad es obligatoria."; break;
		case 7: $_info = "El CP es incorrecto."; break;
		case 8: $_info = "El CUIT es obligatorio o incorrecto."; break;
		case 9: $_info = "Por favor, introduce un e-mail correcto."; break;
		case 10: $_info = "El Tel&eacute;fono o Corre es obligatorio."; break;
		case 11: $_info = "El Tama&ntilde;o del archivo no debe superar 4MB."; break;
		case 12: $_info = "Error al intentar subir el archivo."; break;
		case 13: $_info = "Error al intentar eliminar el archivo."; break;
		case 14: $_info = "El proveedor ya existe registrado. Controle los datos existentes y actualice."; break;
		case 15: $_info = "El c&oacute;digo de proveedor ya existe en la empresa indicada."; break;
		case 16: $_info = "Complete Ingresos Brutos"; break;
		//case 17: $_info = "El archivo fue eliminado."; break;
	} // mensaje de error
}

if ($_provid) {
	if (!$_sms) {
		$_proveedor		= DataManager::newObjectOfClass('TProveedor', $_provid);
		$_idempresa 	= $_proveedor->__get('Empresa');
		$_idproveedor	= $_proveedor->__get('Proveedor');
		$_loguin		= $_proveedor->__get('Login');
		$_nombre 		= $_proveedor->__get('Nombre');
		$_direccion 	= $_proveedor->__get('Direccion');
		$_idprovincia 	= $_proveedor->__get('Provincia');
		$_idloc 		= $_proveedor->__get('Localidad');
		$_cp 			= $_proveedor->__get('CP');
		$_cuit 			= $_proveedor->__get('Cuit');
		$_nroIBB 		= $_proveedor->__get('NroIBB');		
		$_telefono 		= $_proveedor->__get('Telefono');
		$_correo		= $_proveedor->__get('Email');
		$_observacion 	= $_proveedor->__get('Observacion');
		$_activo	 	= $_proveedor->__get('Activo');
	}
	$_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\"/>");
	$_action = "logica/update.proveedor.php?backURL=".$backURL;	
} else {	
	if (!$_sms) {
		$_idempresa 	= "";
		$_idproveedor 	= "";
		$_loguin		= "";
		$_nombre 		= "";
		$_direccion 	= "";
		$_idprovincia 	= "";
		$_idloc 		= "";
		$_cp 			= "";
		$_cuit 			= "";
		$_nroIBB		= "";
		$_telefono 		= "";
		$_correo		= "";
		$_observacion 	= "";
	}	
	$_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\"/>");
	$_action = sprintf("logica/update.proveedor.php?provid=%d&backURL=", $_provid, $backURL);	
} ?>

<!DOCTYPE html>
<html ang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
    <style>				
		#pdf_ampliado{		
			overflow: hidden;
			position:fixed;
			display: none;
			z-index: 1; 
			background-color: rgba(50,50,50,0.8);
			display:none;
			width:80%; 
			margin:-10px 0px 0px 0px;
			padding:3%;
		}		
	</style>
    
    <script language="JavaScript" type="text/javascript">
		function dac_ShowPdf(archivo){	
			$("#pdf_ampliado").empty();
			campo	= 	'<iframe src=\"https://docs.google.com/gview?url='+archivo+'&embedded=true\" style=\"width:650px; min-height:260px; height:90%;\" frameborder=\"0\"></iframe>';			
			$("#pdf_ampliado").append(campo);
			
			$('#pdf_ampliado').fadeIn('slow');			
			$('#pdf_ampliado').css({
				'width': '100%',
				'height': '100%',
				'left': ($(window).width() / 2 - $(img_ampliada).width() / 2) + 'px', 
				'top': ($(window).height() / 2 - $(img_ampliada).height() / 2) + 'px'
			});
			
			//document.getElementById("pdf_ampliado").src = src;	
			$(window).resize();
			return false;
		}
		
		function dac_ClosePdfZoom(){
			$('#pdf_ampliado').fadeOut('slow');		
			return false;
		}
	</script>
    
    <script type="text/javascript">
		function dac_ShowImgZoom(src){						
			$('#img_ampliada').fadeIn('slow');			
			$('#img_ampliada').css({
				'width': '100%',
				'height': '100%',
				'left': ($(window).width() / 2 - $(img_ampliada).width() / 2) + 'px', 
				'top': ($(window).height() / 2 - $(img_ampliada).height() / 2) + 'px'
			});
			
			document.getElementById("imagen_ampliada").src = src;	
			$(window).resize();		
			
			return false;
		}	
		//**********************************//	
		function dac_CloseImgZoom(){
			$('#img_ampliada').fadeOut('slow');		
			return false;
		}		
		//**********************************//
		$(window).resize(function(){
			$('#img_ampliada').css({
				'width': '100%',
				'height': '100%',
				'left': ($(window).width() / 2 - $(img_ampliada).width() / 2) + 'px', 
				'top': ($(window).height() / 2 - $(img_ampliada).height() / 2) + 'px'
			});
			$('#pdf_ampliado').css({
				'width': '100%',
				'height': '100%',
				'left': ($(window).width() / 2 - $(img_ampliada).width() / 2) + 'px', 
				'top': ($(window).height() / 2 - $(img_ampliada).height() / 2) + 'px'
			});
		});
	</script>
    <!-- Scripts para SUBIR ARCHIVOS -->
    <script type="text/javascript" src="jquery/jquery.script.file.js"></script>
</head>

<body>	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->
    
    <nav class="menuprincipal"> <?php 
       	$_section	=	'proveedores';
        $_subsection	= 	'';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>
                                
    <main class="cuerpo"> 
    	<div id="img_ampliada" align="center" onclick="javascript:dac_CloseImgZoom()">
			<img id="imagen_ampliada" style="width:80%; margin:10px 0px 10px 0px;"/>    
		</div>
		<div id="pdf_ampliado" align="center" onclick="javascript:dac_ClosePdfZoom()">
			 <div id="pdf_amp"></div>
		</div> 
			
		<div class="box_body"> 
		                           				
			<form id="fm_proveedor_edit" name="fm_proveedor_edit" class="fm_edit2" method="post" action="<?php echo $_action;?>">
				<fieldset >
					<legend>Proveedor</legend>
					<div class="bloque_1">
						<label for="idempresa">Empresa *</label>
                        <select id="idempresa" name="idempresa"  style="color:#5c788e;"><?php
                            $empresas	= DataManager::getEmpresas(1); 
                            if (count($empresas)) {	
                                foreach ($empresas as $k => $emp) {
                                    $idEmp		=	$emp["empid"];
                                    $nombreEmp	=	$emp["empnombre"];
                                    if ($idEmp == $_idempresa){ $selected="selected";
                                    } else { $selected=""; } ?>
                                    <option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>" <?php echo $selected; ?>><?php echo $nombreEmp; ?></option><?php  
                                }                            
                            } 
							
							?>
                        </select>
					
					</div>
					<div class="bloque_2">
						<label for="loguin">Usuario</label>
						<input name="loguin" type="text" value="<?php echo $_loguin; ?>" disabled>
					</div>
					
					<div class="bloque_2">
						<label for="idproveedor">Id Proveedor *</label>
						<input name="idproveedor" id="idproveedor" type="text" maxlength="10" value="<?php echo $_idproveedor; ?>">
					</div>
					<div class="bloque_1">
						<label for="nombre">Proveedor *</label>
						<input name="nombre" id="nombre" type="text" maxlength="50" value="<?php echo $_nombre;?>">
					</div> 
					
					<div class="bloque_2">	
						<label for="cuit">CUIT *</label>
						<input name="cuit"  id="cuit" type="text" maxlength="13" value="<?php echo $_cuit;?>">
					</div> 
					 
					<div class="bloque_2">	
						<label for="nroIBB">Nro. IBB *</label>
						<input name="nroIBB"  id="nroIBB" type="text" maxlength="13" value="<?php echo $_nroIBB;?>"/>
					</div>  
					
					<div class="bloque_1">
						<label for="idprovincia">Provincia *</label>
                        <select id="idprovincia" name="idprovincia"/> 
                            <option value="0" selected> Provincia... </option> <?php
                            $provincias	= DataManager::getProvincias(); 
                            if (count($provincias)) {	
                                $idprov = 0;
                                foreach ($provincias as $k => $prov) {		
                                    if ($_idprovincia == $prov["provid"]){ 
                                        $selected = "selected";										
                                    } else { 
                                        $selected = ""; 
                                    } ?>                        		
                                    <option id="<?php echo $prov["provid"]; ?>" value="<?php echo $prov["provid"]; ?>" <?php echo $selected; ?>><?php echo $prov["provnombre"]; ?></option>   <?php
                                }                            
                            } ?> 
                        </select>
					</div>
					<div class="bloque_1">	
						<label for="idloc">Localidad *</label>
						<input name="idloc"  id="idloc" type="text" maxlength="30" value="<?php echo $_idloc;?>"/>
					</div>
																
					<div class="bloque_3">
						<label for="direccion">Direcci&oacute;n *</label>
						<input name="direccion" id="direccion" type="text" maxlength="50" value="<?php echo $_direccion;?>"/>
					</div>
					<div class="bloque_2">
						<label for="cp">C&oacute;digo Postal</label>
						<input name="cp"  id="cp" type="text" maxlength="10" value="<?php echo $_cp;?>"/>
					</div>	
					
					<div class="bloque_2">
						<label for="telefono">Tel&eacute;fono *</label>
						<input name="telefono" id="telefono" type="text" maxlength="20" value="<?php echo $_telefono;?>"/>
					</div>
					
					<div class="bloque_1">
						<label for="correo">Correo *</label>
						<input name="correo" id="correo" type="text" maxlength="50" value="<?php echo $_correo;?>"/>
					</div>
					<div class="bloque_3">
						<label for="observacion">Observaci&oacute;n</label>
						<textarea name="observacion" id="observacion" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'observacion', 200);" onkeydown="javascript:dac_LimitaCaracteres(event, 'observacion', 200);" value="<?php echo $_observacion;?>"/><?php echo $_observacion;?></textarea>                           
					</div>

					<?php 
					if ($_sms) {
						if ($_sms < 17) { ?> 
							<div class="bloque_3">
								<fieldset id='box_error' class="msg_error" style="display:block;">
									<legend>&iexcl;ERROR!</legend>                     
									<div id="msg_error" align="center"><?php echo $_info; ?></div>
								</fieldset>
							</div> <?php 
						} else { ?> 
							<div class="bloque_3">
								<fieldset id='box_confirmacion' class="msg_confirmacion" style="display:block;">
									<div id="msg_confirmacion" align="center"><?php echo $_info; ?></div>      
								</fieldset>
							</div> <?php 
						} 
					}?>

					<div class="bloque_3">     
						<fieldset id='box_error' class="msg_error">
							<legend>&iexcl;ERROR!</legend>                     
							<div id="msg_error" align="center"></div>
						</fieldset>

						<fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;">                 		<div id="msg_cargando" align="center"></div>      
						</fieldset> 
					</div>

					<input type="hidden" id="provid" name="provid" value="<?php echo $_provid;?>" />
					<input type="hidden" id="activo" name="activo" value="<?php echo $_activo;?>" />
					<input type="hidden" name="pag" value="<?php echo $_pag;?>" />

					<div class="bloque_3">
						<label for="_accion">&nbsp;</label>  
						<?php echo $_button; ?>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Documentaci&oacute;n</legend>  
					<div class="bloque_3">
						<div class="lista"> <?php
							$ruta 	=	$_SERVER['DOCUMENT_ROOT'].'/pedidos/login/registrarme/archivos/proveedor/'.$_provid."/";													
							$data	=	dac_listar_directorios($ruta);	
							if($data){ ?>
								<table name="tblTablaFact" border="0" width="100%" align="center">
									<thead>
										<tr align="left">
											<th>Subido</th>
											<th>Archivo</th>
											<th>Imagen</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>	
										<?php	
										$fila 	= 0;
										//  $zonas	= explode(", ", $_SESSION["_usrzonas"]);
										foreach ($data as $file => $timestamp) {
											$fila = $fila + 1;
											(($fila % 2) == 0)? $clase="par" : $clase="impar";																
											$extencion	=	explode(".", $timestamp);
											$ext		=	$extencion[1];	
											$name 		=	explode("-", $timestamp, 4);
											$archivo 	= 	trim($name[3]);
											

											$_eliminar	=	sprintf ("<a href=\"logica/eliminar.archivo.php?provid=%d&backURL=%s&archivo=%s\" title=\"Eliminar\" onclick=\"return confirm('&iquest;Est&aacute; seguro que desea ELIMINAR EL ARCHIVO?')\"> <img src=\"../images/icons/icono-eliminar.png\" border=\"0\" align=\"absmiddle\"/></a>", $_provid, $_SERVER['PHP_SELF'], $archivo,  "Eliminar");
											if($ext == "pdf"){ ?>
												<tr class="<?php echo $clase;?>">
													<td><?php echo $name[0]."/".$name[1]."/".$name[2]; ?></td>
													<td><?php echo $name[3]; ?></td>
													<td align="center">
														<a href='<?php echo "../login/registrarme/archivos/proveedor/".$_provid."/".$archivo; ?>' target="_blank">
															<img id="imagen" src="../images/icons/icono-pdf.png" height="60px"/>
														</a>
													</td>
													<td><?php echo $_eliminar;?></td>
												</tr> <?php
											} else{ ?>                    
												<tr class="<?php echo $clase;?>"> 
													<td><?php echo $name[0]."/".$name[1]."/".$name[2]; ?></td>
													<td><?php echo $name[3]; ?></td>
													<td align="center">
														<img id="imagen" src="<?php echo "../login/registrarme/archivos/proveedor/".$_provid."/".$archivo; ?>" onclick="javascript:dac_ShowImgZoom(this.src)" height="100px"/>
													</td>
													<td><?php echo $_eliminar;?></td>       
												</tr><?php
											} 
										} ?>
									</tbody>
								</table> <?php                                                        
							} else {?>
								<table name="tblDocum" border="0" width="100%" align="center">
									<tr>
										<td colspan="4"><?php echo " No hay documentaci&oacute;n disponible"; ?></td>	
									</tr>  
								</table><?php 
							}?>
						</div>
					</div>
					
					<div class="bloque_1">	
						<div class="inputfile"><input id="archivo" name="archivo" class="file" type="file"/></div>
					</div>
					
					<div class="bloque_2">
					 	<input type="button" id="btfile_send" value="Subir">
					</div> 
				</fieldset>
			</form>	                 
    	</div>
    	
    	
    	<div class="box_seccion">  <?php 
			$_origen	=	'TProveedor';
			$_idorigen	=	$_provid;
			include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.accord.php"); ?> 
		</div> <!-- fin boxbody menu -->
    	
		<hr>
    </main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>


