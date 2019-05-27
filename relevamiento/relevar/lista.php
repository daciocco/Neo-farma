<?php
$btAccion	= sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Guardar\"/>");
?>

<link rel="stylesheet" type="text/css" href="../../js/jquery-ui-1.11.4/jquery-ui.structure.css"/>
<link rel="stylesheet" type="text/css" href="../../js/jquery-ui-1.11.4/jquery-ui.theme.css"/>

<div class="box_body"> 	
	<?php
	$_origenid		=	empty($_REQUEST['origenid'])	?	0 	:	$_REQUEST['origenid'];
	$_origen		=	empty($_REQUEST['origen']) 		? 	'' 	: 	$_REQUEST['origen'];
	$_nroRel		=	empty($_REQUEST['nroRel']) 		? 	0 	:	$_REQUEST['nroRel'];
	$empresa		=	empty($_REQUEST['empresa']) 		? 	0 	:	$_REQUEST['empresa'];
	$_procorreo		= 	DataManager::getCuenta('ctacorreo', 'ctaid', $_origenid, $empresa);	
	$_protelefono	= 	DataManager::getCuenta('ctatelefono', 'ctaid', $_origenid, $empresa);
	
	//	ENCUETAS	//
	$_relevamiento	= DataManager::getRelevamiento($_nroRel, 1); 
	if (count($_relevamiento)) { ?>
		<form id="fm_relevar" method="post">	
			<input type="text" id="origenid" name="origenid" hidden="hidden" value="<?php echo $_origenid; ?>">	
			<input type="text" id="origen" name="origen" hidden="hidden" value="<?php echo $_origen; ?>">
			<input type="text" id="nrorel" name="nrorel" hidden="hidden" value="<?php echo $_nroRel; ?>"> 
			<input type="text" id="telefono" name="telefono" hidden="hidden" value="<?php echo $_protelefono; ?>">
			<input type="text" id="email" name="email" hidden="hidden" value="<?php echo $_procorreo; ?>">    

			<fieldset>
				<legend>Relevamiento</legend>
				<?php
				foreach ($_relevamiento as $k => $_rel) { 
					$_relid 		= 	$_rel["relid"];
					$_relpregorden 	=	$_rel["relpregorden"];
					$_relpregunta	= 	$_rel["relpregunta"];
					$_reltiporesp	= 	$_rel["reltiporesp"];
					//Consulto si ya existe el relevamiento hecho para poner las respuestas.
					$_respuesta		=	'';
					$_resid			=	'';
					$_respuestas	= 	DataManager::getRespuesta( $_origenid, $_origen, $_relid, 1);
					if($_respuestas){
						foreach ($_respuestas as $j => $_res) {
							$_resid			=	$_res["resid"];
							$_respuesta		=	$_res["respuesta1"];
						}
					} ?>  

					<div class="bloque_1">
						<h4> <?php echo ($k+1).") ".$_relpregunta; ?> </h4>
					</div>

					<input type="text" name="resid<?php echo $k; ?>" id="resid<?php echo $k; ?>" value="<?php echo $_resid; ?>" hidden="hidden">
										
					<?php
					switch ($_reltiporesp){
						case 'sino': ?>
							<div class="bloque_8">
								<input type="radio" name="sino<?php echo $k; ?>" value="1" <?php if($_respuesta == 1) { echo "checked='checked'";} ?>>Si
							</div>
							<div class="bloque_8">
								<input type="radio" name="sino<?php echo $k; ?>" value="2" <?php if($_respuesta == 2) { echo "checked='checked'";} ?>>No
							</div> <hr><?php								
							break;
						case 'cant': ?>
							<div class="bloque_8">	
								<input type="text" name="cant<?php echo $k; ?>" id="cant<?php echo $k; ?>" maxlength="4" max="1000" min="0" value="<?php echo $_respuesta; ?>">
							</div> <?php
							break;
						case 'abierto': ?>
							<div class="bloque_1">
								<textarea id="respuesta<?php echo $k; ?>" name="respuesta<?php echo $k; ?>" onKeyUp="javascript:dac_LimitaCaracteres(event, 'respuesta', 200);" onKeyDown="javascript:dac_LimitaCaracteres(event, 'respuesta', 200);" /><?php echo $_respuesta; ?></textarea>
							</div> <?php
							break;
						default:	?>
							<div class="bloque_1">
								Error en el tipo de respuesta.
							</div>
							<?php
							break;
					} ?>  
						<?php 
				} ?>
				
				<div id="relevar" class="bloque_4">
					<label >Resultado</label>
					<select id="resultado_arg" name="resultado_arg">
						<option value="0" selected></option>
						<option value="1" >Enviar informaci&oacute;n y volver a llamar</option>
						<!--option value="2" >Enviar informaci&oacute;n inicial</option-->
						<option value="3" >No le interesa</option>
						<option value="4" >Venta Transfer</option>
						<option value="5" >Volver a llamar</option>
						<!--option value="6" >Volver a llamar a largo plazo</option-->
					</select> 
				</div> 
				
				<div class="bloque_7">
					<br>
					<?php echo $btAccion; ?>
				</div>
					
				<div id="mensajesAlertas" class="bloque_1">
					<div id="alertas">		
						<fieldset id='box_error' class="msg_error">          
							<div id="msg_error"></div>
						</fieldset>
						<fieldset id='box_cargando' class="msg_informacion">                    	
							<div id="msg_cargando"></div>      
						</fieldset> 
						<fieldset id='box_confirmacion' class="msg_confirmacion">
							<div id="msg_confirmacion"></div>      
						</fieldset>
					</div>
				</div>    <!-- Fin mensajesAlertas -->
				
			</fieldset>  
		</form>	<?php	
	} ?>      
	  
	<div id="dialogo" style="display:none;"></div>
	<div id="reprogramar" style="display:none;"></div>
	<div id="enviarmail" style="display:none;"></div>        
</div> <!-- Fin datos -->
<hr>

<script type="text/javascript" src="jquery/jquery.enviar.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.11.4/jquery-ui.js"></script>

<script>
	$(window).resize(function(e){
		$("#enviarmail").dialog("close");	
		$("#enviarmail").dialog("open");
		$("#reprogramar").dialog("close");	
		$("#reprogramar").dialog("open");
		$("#dialogo").dialog("close");	
		$("#dialogo").dialog("open");
	});
</script>

<script>
	$("#resultado_arg").change(function(){
		$("#dialogo").empty();
		$("#dialogo").dialog({
			modal: true, title: 'Mensaje', zIndex: 100, autoOpen: true,
			resizable: false,
			width: 380,
			height: 200,
			
		});	
		$("#dialogo").dialog( "option", "title", 'Registro de Acciones');		
		
		var origen		=	$('#origen').val();
		var idorigen	=	$('#origenid').val();
		var nroRel		=	$('#nrorel').val(); 
		var telefono	=	$('#telefono').val();
		var email		=	$('#email').val(); 
		
		var resultado = $( "#resultado_arg" ).val();
		switch(resultado){
			case '0': $("#dialogo").dialog("close"); break;
			case '1':
				//Enviar información y reprogramar llamada	
				$( "#dialogo" ).dialog({
					buttons: {
						Aceptar : function () {
							//Enviar Información
							$( "#alertas" ).remove();
							dac_sendMail(1, email, "Informaci\u00f3n GEZZI");							
							
							// REPROGRAMAR LLAMADA							
							//dac_reprogramar(origen, idorigen, nroRel, 'enviar información', telefono);
							$(this).dialog("close");
						},
						Cerrar : function () {	
							$(this).dialog("close"); 
						}
					},
				});
				var contenido	= '<h3>\u00BFRegistrar la llamada como "Enviar informaci\u00f3n"?</h3>';
				$(contenido).appendTo('#dialogo'); 	
				break;
			case '3':
				//No le interesa y ¿desactivar prospecto?
				$("#dialogo").dialog("close");
				dac_reprogramar(origen, idorigen, nroRel, 'no le interesa', telefono);
				/*$( "#dialogo" ).dialog({
					buttons: {
						Aceptar : function () {
							//dac_registrarLlamada(origen, idorigen, nroRel, telefono, 'contesta', 'no le interesa', '');
							// REPROGRAMAR LLAMADA							
							//dac_reprogramar(origen, idorigen, nroRel, 'contesta', telefono);
							//dac_registrarLlamada(origen, idorigen, nroRel, telefono2, contesta, 'rellamar', descripcion2);		
							//parent.window.close();
							//$(this).dialog("close");
							
						},
						Cerrar : function () {	
							$(this).dialog("close"); 
						}
					},
				});
				var contenido	= '<h3>\u00BFRegistrar la llamada como "No le interesa"?</h3>';
				$(contenido).appendTo('#dialogo'); 		*/	
				
				//aca abría que desactivar el prospecto???? y registrar volver a llamar a largo plazó??
				
				break;
			case '4':
				//Venta Transfer
				$("#btsend").click();
				$("#dialogo").dialog("close"); 
				href =	window.location.origin+'/pedidos/transfer/editar.php';
				window.open(href,'_blank');
				break;
			case '5':
				//Volver a llamar - Reprograma llamada
				$("#dialogo").dialog("close");
				dac_reprogramar(origen, idorigen, nroRel, 'volver a llamar', telefono);
				/*
				$( "#dialogo" ).dialog({
					buttons: {
						Aceptar : function () {
							// REPROGRAMAR LLAMADA							
							dac_reprogramar(origen, idorigen, nroRel, 'volver a llamar', telefono);
						},
						Cerrar : function () {	
							$(this).dialog("close"); 
						}
					},
				});
				var contenido	= '<h3>\u00BFRegistrar la llamada como "Volver a llamar"?</h3>';
				$(contenido).appendTo('#dialogo'); 	*/
				break;
			default: $("#dialogo").dialog("close"); break;
		}
	});
	
		
	function dac_sendMail(empresa, email, asunto){	
		//Php co ajax para envío de email
		$("#enviarmail").empty();
		$("#enviarmail").dialog({
			modal: true, 
			title: 'Mensaje', 
			zIndex: 100, 
			autoOpen: true,
			resizable: false,
			width: 380,
			height: 560,			
		});	
		$("#enviarmail").dialog( "option", "title", 'Redactar Correo');	
		$("#enviarmail").dialog({
			  buttons: {
				  Enviar : function () {
					  //Bloquear los campòs del correo
					  
					  //Enviar Correo vía AJAX
					  dac_sendForm('#fm_correo', '/pedidos/js/ajax/send.email.php');
					  
					  var valorDiv = $("#box_confirmacion").text();
					 //if(valorDiv == "Los datos fueron enviados") {  alert("A");
					 // } else { alert("B");  }
					 // $(this).dialog("close");
				  },
				  Cerrar : function () {
					  //Desbloquear los campòs del correo
					  
					  $( "#alertas" ).remove();
					  $(alertas).appendTo('#mensajesAlertas'); 
					  $(this).dialog("close"); 
				  }
			  },
		});
		  
		var contenido	=	'<form id="fm_correo" name="fm_correo" class="fm_popup" method="post" enctype="multipart/form-data">';
			contenido		+=	'<input type="text" name="idemp" value="'+empresa+'" hidden="hidden">';
			contenido		+=	'<div class="bloque_1">Para:</div><div class="bloque_1"><input type="text" name="email" value="'+email+'"> </div>';
			contenido		+=	'<div class="bloque_1">Asunto:</div><div class="bloque_1"><input type="text" name="asunto" value="'+asunto+'"></div>';		  	
			contenido		+=	'<div class="bloque_1">Adjunto/s:</div><div class="bloque_1"><input name="multifile[]" type="file" multiple="multiple" class="file"/></div>';	  
			contenido		+=	'<div class="bloque_1">Mensaje:</div><div class="bloque_1"><textarea id="mensaje" name="mensaje" type="text"></textarea>';
			contenido		+=	'</form>';		
		
		var alertas		=	'<div id="alertas">';          
			alertas		+=	'<fieldset id="box_error" class="msg_error">';          
			  alertas		+=	'<div id="msg_error"></div>';
			alertas		+=	'</fieldset>';											
			alertas		+=	'<fieldset id="box_cargando" class="msg_informacion">';                    	
			  alertas		+=	'<div id="msg_cargando"></div>';      
			alertas		+=	'</fieldset>';						  
			alertas		+=	'<fieldset id="box_confirmacion" class="msg_confirmacion">';
			  alertas		+=	'<div id="msg_confirmacion"></div>';      
			alertas		+=	'</fieldset>';
			alertas		+=	'</div>';
				
		contenido		+=	alertas;
		
		$(contenido).appendTo('#enviarmail');
	}	
	
</script>


    
    