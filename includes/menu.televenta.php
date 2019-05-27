<!--
Ojo con las siguientes variables!
var origen		=	document.getElementById('origen').value;
var idorigen	=	document.getElementById('idorigen').value;	
var nroRel		=	<?php //echo $_nroRel;?>;		
var telefono	=	document.getElementById('telefono').value;	
-->
<script>
	$(function() {
		$("#llamada").accordion({
			//active: 0 inicia con el número de barra desplegado que elija desde el 0 (primero por defecto) en adelante
			//event: "mouseover" se activan con mouseover en vez del clic
			collapsible: true,
			icons: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" },
			heightStyle: "content" //"auto" //"fill" / "content"			
		});
	});
	
	$(window).resize(function(e) {
		$("#dialogo").dialog("close");	
		$("#dialogo").dialog("open");
		$("#reprogramar").dialog("close");	
		$("#reprogramar").dialog("open");
	});
</script>

<div id="dialogo" style="display:none;"></div>
<div id="reprogramar" style="display:none;"></div>

<div id="llamada" align="left">
	<!-- Button -->
	<h3>Llamada</h3>        
	<div>             
		<div class="bloque_5">   
			<select id="contesta" name="contesta" style="height:45px;">
				<option value="1" selected>No Contesta</option>
				<option value="2">Ocupado</option>
				<option value="3">Si Contesta</option>
			</select>   
		</div> 
		<div class="bloque_5">
			<button id="button" style="width:140px;">
				<img class="icon-phone"/>
			</button> 
		</div>
	</div>

	<h3>Resumen marcaciones</h3>        
	<div> 	<?php   
		$arrayLlamadas = array();
		$_si_contesta	=	0;
		$_no_contesta	=	0;
		$_ocupado		=	0;
		$_incidencia	=	0;
		//--------------//
		$_si_contesta_hoy	=	0;
		$_no_contesta_hoy	=	0;
		$_ocupado_hoy		=	0;
		$_incidencia_hoy	=	0;

		$_llamadas	= DataManager::getLlamadas(NULL, NULL, $_idorigen, $_origen, 0); 
		if (count($_llamadas)) { 
			foreach ($_llamadas as $k => $_llam) { 
				$_tiporesultado	=	$_llam["llamtiporesultado"];
				$_ultima_fecha	=	$_llam["llamfecha"];
				$_fechasllamadas=	explode(" ", $_ultima_fecha);
				$_llamfecha		=	explode("-", $_fechasllamadas[0]);		
				$_resultado		=	$_llam["llamtiporesultado"]." - ".$_llam["llamresultado"];	
				$_telefono		=	$_llam["llamtelefono"];	
				$_usrUpdate		=	$_llam["llamusrupdate"];
				$_usrName		= 	DataManager::getUsuario('unombre', $_usrUpdate);	
				$_observacion	=	$_llam["llamobservacion"];	
				switch($_tiporesultado){
					case 'contesta':
						$_si_contesta++;
						break;
					case 'ocupado':
						$_ocupado++;							
						break;	
					case 'no contesta':
						$_no_contesta++;
						break;	
					case 'incidencia':
						$_incidencia++;
						break;			
				}

				if($_llamfecha[0] == date("Y") && $_llamfecha[1] == date("m") && trim($_llamfecha[2]) == date("d")){
					switch($_tiporesultado){
						case 'contesta':
							$_si_contesta_hoy++;
							break;
						case 'ocupado':
							$_ocupado_hoy++;
							break;	
						case 'no contesta':
							$_no_contesta_hoy++;
							break;	
						case 'incidencia':
							$_incidencia_hoy++;
							break;			
					}
				}
				$arrayLlamadas[$k]['usuario']	= $_usrName;
				$arrayLlamadas[$k]['fecha'] 	= trim($_llamfecha[2])."-".$_llamfecha[1]."-".$_llamfecha[0];
				$arrayLlamadas[$k]['resultado'] = $_resultado;
				$arrayLlamadas[$k]['observacion'] = $_observacion;

			}
		} ?> 
		<table width="100%">
			<thead>
				<tr>
					<th colspan="2">Contesta</th><th colspan="2"></th>
				</tr>
				<tr>
					<th>SI</th><th>NO</th><th>Ocupado</th><th>Incidencia</th>
				</tr>
			</thead>
			<tbody> 
				<tr class="par" align="center">
					<td><?php  echo $_si_contesta; ?></td><td><?php  echo $_no_contesta; ?></td><td><?php  echo $_ocupado; ?></td><td><?php  echo $_incidencia; ?></td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<th colspan="4">Hoy</th>
				</tr>
			</thead>
			<tbody> 
				<tr class="par" align="center">
					<td><?php  echo $_si_contesta_hoy; ?></td><td><?php  echo $_no_contesta_hoy; ?></td><td><?php  echo $_ocupado_hoy; ?></td><td><?php  echo $_incidencia_hoy; ?></td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<th colspan="4">&Uacute;ltimo registro</th>
				</tr>
			</thead>
			<tbody> 
				<tr class="par" align="center">
					<td><?php  echo $_si_contesta_hoy; ?></td><td><?php  echo $_no_contesta_hoy; ?></td><td><?php  echo $_ocupado_hoy; ?></td><td><?php  echo $_incidencia_hoy; ?></td>
				</tr>
			</tbody>
		</table>  
	</div>

	<h3>Historial</h3>        
	<div> <?php 
		if(count($arrayLlamadas) > 0){ ?>	
			<table width="100%">
				<thead>
					<tr>
						<th>Usuario</th><th>Fecha</th><th>Resultado</th><th>Observaci&oacute;n</th>
					</tr>
				</thead>
				<tbody> <?php
					foreach ($arrayLlamadas as $j => $arrayllam) {
						((($j % 2) == 0)? $clase="par" : $clase="impar"); ?> 
						<tr class="<?php echo $clase; ?>">
							<td><?php echo $arrayLlamadas[$j]['usuario']; ?></td><td><?php echo $arrayLlamadas[$j]['fecha']; ?></td><td><?php echo $arrayLlamadas[$j]['resultado']; ?></td><td><?php echo $arrayLlamadas[$j]['observacion']; ?></td>
						</tr> <?php
					} ?>
				</tbody>
			</table> <?php
		} ?>
	</div>
</div>

<script>	
	$( "#button" ).button();
	$( "#button" ).click(function(){
		$("#dialogo").empty();
		$("#dialogo").dialog({
			modal: true, title: 'Mensaje', zIndex: 100, autoOpen: true,
			resizable: false,
			width: 380,
		});			
		
		$( "#dialogo" ).dialog( "option", "title", 'Registro de llamadas');		
		var origen		=	document.getElementById('origen').value;
		var idorigen	=	document.getElementById('idorigen').value;	
		var nroRel		=	<?php echo $_nroRel;?>;		
		var telefono	=	document.getElementById('telefono').value;	
		var empresa		=	document.getElementById('empselect').value;	
											
		var contesta	=	$( "#contesta" ).val();		
		switch(contesta){
			case '1': 
				//NO CONTESTA//	
				$("#dialogo").dialog("close"); 
				dac_reprogramar(origen, idorigen, nroRel, 'no contesta', telefono);
				break;
			case '2':	
				//OCUPADO//	
				$("#dialogo").dialog("close"); 
				dac_reprogramar(origen, idorigen, nroRel, 'ocupado', telefono);
				break;
			case '3':
				//SI CONTESTAS // 
				$( "#dialogo" ).dialog({	
					height: 300,
					buttons: {
						Aceptar : function () {
							var radio = $("input[name='radio']:checked").val();
							switch(radio){									
								case '1': 
									/* ARGUMENTAR --> LLAMADA OK */
									$("#btsend").click();																		
									dac_registrarLlamada(origen, idorigen, nroRel, telefono, 'contesta', 'argumentado', '');
									href ='/pedidos/relevamiento/relevar/index.php?origenid='+idorigen+'&origen='+origen+'&nroRel='+nroRel+'&empresa='+empresa;
									//redirige al relevamiento
									window.open(href,'_blank');								
									//Cierra el formulario					
									$(this).dialog("close");							
									break;
								case '2': 
									/* REPROGRAMAR LLAMADA */
									dac_reprogramar(origen, idorigen, nroRel, 'contesta', telefono);
									break;
								/*case '3':
									RELLAMAR INMEDIATAMENTE
									//Cierra el formulario						
									$(this).dialog("close");	
									break;*/
								case '4':
									/* INDIDENCIA */
									var tipo_incidencia	=	$( "#tipo_incidencia" ).val();		
									var observacion		=	$( "#descripcion" ).val();
									dac_registrarLlamada(origen, idorigen, nroRel, telefono, 'incidencia', tipo_incidencia, observacion);									
									
									break;
								default: 
									$(this).dialog("close");
									break;
							}
						},
						Cerrar : function () {	
							$(this).dialog("close"); 
						}
					},
				});
					
				var contenido	= 
					'<form>'+
						'<div class="bloque_1"><h1>Resultado del contacto</h1></div>'+
					
						'<div class="bloque_1"><input type="radio" id="radio1" name="radio" value="1" checked="checked" onclick="dac_incidencia(0)"><label for="radio1" value="1">Argumentado (iniciar preguntas)</label></div><hr>'+
						'<div class="bloque_1"><input type="radio" id="radio2" name="radio" value="2" onclick="dac_incidencia(0)"><label for="radio2">Volver a llamar</label></div><hr>'+
						'<div class="bloque_1"><input type="radio" id="radio4" name="radio" value="4" onclick="dac_incidencia(1)"><label for="radio4">Incidencia</label></div><hr>'+					
						
						'<div id="incidencia" style="display:none">'+
							'<div class="bloque_1">'+
								'<label>Tipo de incidencia</label>'+
								'<select id="tipo_incidencia" name="tipo_incidencia">'+
									'<option value="0" selected></option>'+
									'<option value="ilocalizable">Ilocalizable</option>'+
									'<option value="no_colabora">No Colabora</option>'+
									'<option value="duplicado">Duplicado</option>'+
									'<option value="tel_equivocado">Tel. equivocado</option>'+
									'<option value="otras">Otras</option>'+
								'</select> '+
							'</div>'+
							'<div class="bloque_1"><label>Descripci&oacute;n</label>		<input id="descripcion" name="descripcion" type="text"></div>'+ 
						'</div>'+
					'</form>'
				;		
						
				$(contenido).appendTo('#dialogo');
				break;
			default: break;
		}		
	});
</script>