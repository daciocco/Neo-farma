<script>
	$(function() {
		$("#accordion").accordion({
			//active: 0 inicia con el número de barra desplegado que elija desde el 0 (primero por defecto) en adelante
			//animate: 200 tiempo de animación para pasar de una barra a la otra
			//disabled: true //desabilita el acorde de menues
			//event: "mouseover" se activan con mouseover en vez del click
			icons: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" },
			heightStyle: "content" //"auto" //"fill" / "content"			
		});			
	});
	
	function dacCloseDialog(){		
		$("#dialog").dialog("close");
		location.reload();
	}
</script>

<script>
	function dac_showDialog(dialo_type){
		$( "#dialog" ).empty();
		var origen		=	document.getElementById('origen').value;
		var idorigen	=	document.getElementById('idorigen').value;		
		var title		=	'';
		
		switch (dialo_type){
			case 'new':
				title	=	'Nuevo Contacto';			
				ctoid	=	0;
				dac_crearDialog(ctoid, idorigen, origen, title); 
				break;
			case 'edit':
				title	=	'Editar Contacto';
				if ( document.getElementById( 'contactos' )) {
					var posicion	=	document.getElementById('contactos').options.selectedIndex;
					var ctoid		=	document.getElementById('contactos').options[posicion].value; 
					dac_crearDialog(ctoid, idorigen, origen, title);
				} else { return false; }
				break;
			case 'delete':
				title	=	'Eliminar Contacto';
				if ( document.getElementById( 'contactos' )) {
					var posicion	=	document.getElementById('contactos').options.selectedIndex; //posicion
					var ctoid		=	document.getElementById('contactos').options[posicion].value; 
					if(confirm("\u00BFSeguro que desea eliminar el contacto?")){
						dac_deleteContacto(ctoid); //al borrar contacto, hay que tener en cuenta todos sus domicilios, cuando los tenga.
					}
				} else { return false; }
				break;
			default: break;
		}
	}
	
	function dac_crearDialog(ctoid, idorigen, origen, title){
		$("#dialog").dialog({
			modal: true, title: 'Mensaje', zIndex: 10000, autoOpen: true,
			resizable: false,
			width: 380, 	//40 mas que el iframe
			height: 440,	 //90 más que el iframe		
		});
		
		$( "#dialog" ).dialog( "option", "title", title );	
		
		$('<input id=\"closeDialog\" onClick=\"javascript:dacCloseDialog()\" value=\"cerrar\" type=\"button\" hidden >').appendTo('#dialog'); 
		
		$('<iframe name=\"dialog_iframe\" src=\"/pedidos/contactos/editar.php?origenid='+idorigen+'&origen='+origen+'&ctoid='+ctoid+'\" height=\"350\" width=\"100%\" frameborder=\"0\" scrolling=\"auto\"></iframe>').appendTo('#dialog'); 		
	}
</script>

<script language="JavaScript" type="text/javascript">
	function dac_deleteContacto(ctoid) {
		$.ajax({
			type : 	'POST',
			cache:	false,
			url : 	'/pedidos/contactos/logica/ajax/delete.contacto.php',					
			data:	{	ctoid	:	ctoid	},	
			beforeSend: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success : 	function (resultado) {
				$('#box_cargando').css({'display':'none'});
				if (resultado){								
					location.reload();			
				} else {
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error al intentar eliminar el contacto.");
				}
			},
			error: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al intentar eliminar el contacto.");
			}						
		});
	}
</script>

<script language="JavaScript" type="text/javascript">
	function dac_cambiarContacto() {
		var posicion	=	document.getElementById('contactos').options.selectedIndex; //posicion
		var ctoid		=	document.getElementById('contactos').options[posicion].value;
		
		$.ajax({
			type : 	'POST',
			cache:	false,
			url : 	'/pedidos/contactos/logica/ajax/cargar.contacto.php',					
			data:	{	ctoid	:	ctoid	},
			beforeSend: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success : 	function (resultado) {
				$('#box_cargando').css({'display':'none'});
				if (resultado){
					var json = eval(resultado);	
					document.getElementById('ctoapellido').value	= 	json[0].apellido; 
					document.getElementById('ctonombre').value 		= 	json[0].nombre; 
					document.getElementById('ctotelefono').value 	= 	json[0].telefono;
					document.getElementById('ctointerno').value 	= 	json[0].interno;
					document.getElementById('ctosector').value 		= 	json[0].sector;
					document.getElementById('ctocorreo').value 		=	json[0].correo;
				} else {
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error al consultar el contacto.");
				}
			},
			error: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al intentar consultar el contacto.");
			}								
		});
	}	
	
	
</script>

<div id="dialog" style="display:none;"> </div>
   
<div id="accordion" align="left">         
	<?php $_contactos	=	DataManager::getContactosPorCuenta( $_idorigen, $_origen, 1); ?>              
	<h3>Contactos <?php echo "(".count($_contactos).")"; ?></h3>        
	<div>
		<div class="bloque_1">  
			<fieldset id='box_observacion' class="msg_alerta">
				<div id="msg_atencion" align="center"></div>       
			</fieldset>
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
		<input id="idorigen" type="text" value="<?php echo $_idorigen;?>" hidden/> 
		<input id="origen" type="text" value="<?php echo $_origen;?>" hidden/> 		
		<?php 
		if (count($_contactos)) { ?>     
			<div class="bloque_1">                	
				<select id="contactos" name="contactos"  onchange="javascript:dac_cambiarContacto()">  <?php
					foreach ($_contactos as $k => $_cont){
						$_ctoid		=	$_cont["ctoid"];							
						if ($k == 0){                                
							$_ctoapellido	=	$_cont["ctoapellido"];
							$_ctonombre		=	$_cont["ctonombre"];
							$_ctotelefono	=	$_cont["ctotelefono"];
							$_ctointerno	=	$_cont["ctointerno"];
							$_sector		=	$_cont["ctosector"];
							$_sectores		= 	DataManager::getSectores(1);
							if($_sectores){ 
								foreach ($_sectores as $k => $_sect) {
									$_sectid		= $_sect['sectid'];
									if($_sectid == $_sector){ 
										$_sectnombre	= $_sect['sectnombre']; 
									} 
								}
							}            
							$_ctocorreo		=	$_cont["ctocorreo"];	?>
							<option value="<?php echo $_ctoid; ?>" selected><?php echo strtoupper($_cont["ctoapellido"]).", ".$_cont["ctonombre"]; ?></option><?php 
						} else { ?>
							<option value="<?php echo $_ctoid; ?>"><?php echo strtoupper($_cont["ctoapellido"]).", ".$_cont["ctonombre"]; ?></option><?php
						}                     
					} ?> 
				</select> 
			</div> 
			<div class="bloque_5">
				<label><strong>Apellido </strong></label>              
				<input id="ctoapellido" type="text" value="<?php echo $_ctoapellido;?>" readonly/>
			</div> 
			<div class="bloque_5">
				<label><strong>Nombre </strong></label>
				<input id="ctonombre" type="text" value="<?php echo $_ctonombre;?>" readonly/>
			</div>
			<div class="bloque_5" >
				<label><strong>Tel&eacute;fono </strong></label> 
				<input id="ctotelefono" type="text" value="<?php echo $_ctotelefono;?>" readonly/>
			</div>
			<div class="bloque_5" >
				<label><strong>Interno </strong></label> 
				<input id="ctointerno" type="text" value="<?php echo $_ctointerno;?>" readonly/>
			</div>
			<div class="bloque_5" >
				<label><strong>Sector/Dpto </strong></label> 
				<input id="ctosector" type="text" value="<?php echo $_sectnombre;?>" readonly />
			</div>
			<div class="bloque_5" >
				<label><strong>Correo </strong></label> 
				<input id="ctocorreo" type="text" value="<?php echo $_ctocorreo;?>" readonly/>
			</div>
			<?php  
		} ?>
		<hr> 

		<div class="bloque_1">
			<img class="icon-new2" onclick="javascript:dac_showDialog('new')"/>
			<img class="icon-edit" onclick="javascript:dac_showDialog('edit')"/>  
			<img class="icon-delete" onclick="javascript:dac_showDialog('delete')"/>                 
		</div>
	</div>
</div> <!-- fin accordion -->