var nextplanifinput = 0;
function dac_Carga_Planificacion(cliente, nombre, direccion, activa) {
	"use strict";
	nextplanifinput++;
	var borrar;
	if(activa === '1'){
		borrar = '<img id="borrar_planif" src="/pedidos/images/icons/icono-delete50.png" height="30" width="30" align="absmiddle" onClick="EliminarDetallePlanif('+nextplanifinput+')"/>';			
	} else {
		borrar = '<img id="borrar_planif" src="/pedidos/images/icons/icono-check.png" height="30" width="30" align="absmiddle"/>';
	}
	
	var campo = '<div id="rutplanif'+nextplanifinput+'"><div class="bloque_9">'+borrar+'</div><div class="bloque_8"><input id="planifcliente'+nextplanifinput+'" name="planifcliente[]" type="text" value="'+cliente+'" placeholder="* CLIENTE" onblur=\"javascript:dac_Buscar_Cliente(this.value, '+nextplanifinput+', 0)\"></div><div class="bloque_6"><input id="planifnombre'+nextplanifinput+'" name="planifnombre[]" type="text" value="'+nombre+'" placeholder="* NOMBRE" readonly="readonly"></div><div class="bloque_6"><input id="planifdir'+nextplanifinput+'" name="planifdir[]" type="text" value="'+direccion+'" placeholder="* CALLE" readonly="readonly"></div></div><hr>';

	$("#detalle_planif").after(campo);
}

function EliminarDetallePlanif(id){
	"use strict";
	var elemento	=	document.getElementById('rutplanif'+id);
	elemento.parentNode.removeChild(elemento);
}

var nextparteinput = 0;
function dac_Carga_Parte(cliente, nombre, direccion, trabaja, observacion, accion, activo, planificado, acciones){
	"use strict";
	nextparteinput++;
	var campo = '<div id="rutparte'+nextparteinput+'" class="parte">';
	var readonly	=	'';
	var readonly_2	=	'';
	var deshabilitar=	'';

	if(activo === '0'){ //fué enviado el parte por lo que no podrá modificar nada y estará con el check ok
		readonly	= 'readonly="readonly"';
		readonly_2	= 'readonly="readonly"';
		deshabilitar= 'disabled="disabled"';
		
		campo = campo + '<div class="bloque_8"><img id="borrar_parte" src="/pedidos/images/icons/icono-check.png" height="30" width="30" border="0" align="absmiddle"/></div><div class="bloque_9"><label>' + nextparteinput + '</label></div><div class="bloque_8"><input  id="partecliente'+nextparteinput+'" name="partecliente[]" type="text" value="'+cliente+'" '+readonly+'/></div>';	
	}else{	
		if(planificado==='1'){//no se puodrá eliminar o modificar el cliente de los registros ya planificados				
			readonly	=	'readonly="readonly"';
			campo = campo + '<div class="bloque_8"><label>' + nextparteinput + '</label><img id="borrar_parte" src="/pedidos/images/icons/icono-delete50.png" height="30" width="30" border="0" align="absmiddle"/></div>';
			campo = campo + '<div class="bloque_8"><input  id="partecliente'+nextparteinput+'" name="partecliente[]" type="text" value="'+cliente+'" '+readonly+'/></div>';			
		} else { //readonly	=	'';
			campo = campo + '<div class="bloque_8"><label>' + nextparteinput + '</label><img id="borrar_parte" src="/pedidos/images/icons/icono-delete50.png" height="30" width="30" border="0" align="absmiddle" onClick="EliminarDetalleParte('+nextparteinput+')"/></div>';
			campo = campo + '<div class="bloque_8"><input id="partecliente'+nextparteinput+'" name="partecliente[]" type="text" value="'+cliente+'" placeholder="* CLIENTE" onblur=\"javascript:dac_Buscar_Cliente(this.value, '+nextparteinput+', 1)\"/></div>';	
		}			
	}			
	campo = campo + '<div class="bloque_3"><input id="partenombre'+nextparteinput+'" name="partenombre[]" type="text" value="'+nombre+'" placeholder="NOMBRE" '+readonly+'/></div><div class="bloque_4"><input id="partedir'+nextparteinput+'" name="partedir[]" type="text" value="'+direccion+'" placeholder="CALLE" '+readonly+'/></div><div class="bloque_6"><input id="partetrabaja'+nextparteinput+'" name="partetrabaja[]" type="text" value="'+trabaja+'" placeholder="TRABAJ&Oacute; CON..." '+readonly_2+'/></div>';	

	//------------
	//	ACCIONES 
	var acid 		= 0;
	var acnombre 	= 0;
	var id_acciones	= acciones;	
	if(id_acciones){
		id_acciones	= id_acciones.split("/");
		acid		= id_acciones[0].split(",");
		acnombre	= id_acciones[1].split(",");	
	}	
	//------------
	campo = campo + '<input id="partenro'+nextparteinput+'" name="partenro[]" type="text" value="'+nextparteinput+'" readonly hidden/>';
	campo = campo + '<div class="bloque_6"><div class="desplegable">';
		//alert(accion); ejemplo 1,3,10
		var idacciones	=	accion.split(",");
		for (var i=0; i<acid.length; i++){ //acid.length = 10	
			var checked='';
			for (var j=0; j<idacciones.length; j++){
				if(idacciones[j] === acid[i]){checked='checked'; break;}
			}
			campo = campo + '<input id="parteaccion'+nextparteinput+'" type="checkbox" name="parteaccion'+nextparteinput+'[]" value="'+acid[i]+'" '+checked+' '+deshabilitar+' style="float:left;"><label>'+acnombre[i]+'</label><hr>';
		}	
	campo = campo + '</div></div>';
	campo = campo + '<div class="bloque_4"><textarea id="parteobservacion'+nextparteinput+'" name="parteobservacion[]" type="text" value="'+observacion+'" placeholder="OBSERVACI&Oacute;N" '+readonly_2+'>'+observacion+'</textarea></div>';
	
	campo = campo + '<hr style="border-bottom: 1px solid #117db6;">';
	campo = campo + '</div>';

	$("#detalle_parte").after(campo);
}		

function EliminarDetalleParte(id){
	"use strict";
	var elemento_parte	=	document.getElementById('rutparte'+id);
	elemento_parte.parentNode.removeChild(elemento_parte);
}	

/*****************************/
function dac_Buscar_Cliente(id, posicion, tipo){
	"use strict";
	$.ajax({
		type : 'POST',
		cache:	false,
		url : '/pedidos/planificacion/logica/ajax/buscar.cliente.php',					
		data:{	idcliente	:	id,
				posicion	:	posicion,
				tipo		:	tipo
			},				
		success : function (result) { 								
					if (result){
						var elem	= 	result.split('/', 4);
						var nro 	= 	elem[0];
						var pos		=	elem[1];
						var cli		= 	elem[2];
						var dir		= 	elem[3];
						
						if(nro==='1'){ // Si el cliente se encontró
							if(tipo===0){ //si es planificación
								if(id!=='0'){
									document.getElementById('planifnombre'+pos).value	=	cli;
									document.getElementById('planifdir'+pos).value		=	dir;
								} else {
									document.getElementById('planifnombre'+pos).value	=	"";
									document.getElementById('planifdir'+pos).value		=	"";
								}
							} else { //si es para parte diario
								document.getElementById('partenombre'+pos).value	=	cli;
								document.getElementById('partedir'+pos).value		=	dir;
							}
						} else{
							if(tipo===0){ //si es planificación
								document.getElementById('planifcliente'+posicion).value	=	"";	
								document.getElementById('planifnombre'+posicion).value	=	"";
								document.getElementById('planifdir'+posicion).value		=	"";
							} else {
								if(result.replace("\n","") === '0'){
									alert("Est\u00e1 por cargar un nuevo cliente");
								} else {									
									document.getElementById('partecliente'+posicion).value	=	"";	
									document.getElementById('partenombre'+posicion).value	=	"";
									document.getElementById('partedir'+posicion).value		=	"";
								}
							}							
						}				
					}															
				},
		error: function () {
			alert("Error al buscar el cliente.");
		}								
	});
}


function dac_Guardar_Planificacion(enviar){	
	"use strict";
	//cantidad de planificaciones
	var cantplanif			=	document.getElementsByName('planifcliente[]').length;	
	var	fecha_planif		= 	document.getElementById("fecha_planif").value;					
	//Declaro Objetos de planificados
	var planifcliente_Obj 	=	{};
	var planifnombre_Obj 	=	{};
	var planifdir_Obj 		=	{};
	//según cant de registros
	if (cantplanif !== 0){
		if (cantplanif === 1){		
			planifcliente_Obj[0]	=	document.getElementById("planifcliente1").value;
			planifnombre_Obj[0]		=	document.getElementById("planifnombre1").value;
			planifdir_Obj[0]		=	document.getElementById("planifdir1").value;
		} else {			
			var planifcliente 		= document.fm_planificacion.elements["planifcliente[]"];
			var planifnombre		= document.fm_planificacion.elements["planifnombre[]"];
			var planifdir	 		= document.fm_planificacion.elements["planifdir[]"];		
			for(var i = 0; i < cantplanif; i++){ //i in cantplanif
				planifcliente_Obj[i]	=	planifcliente[i].value;
				planifnombre_Obj[i] 	=	planifnombre[i].value;
				planifdir_Obj[i] 		=	planifdir[i].value;	
			}			
		}
		
		planifcliente_Obj	=	JSON.stringify(planifcliente_Obj);
		planifnombre_Obj	=	JSON.stringify(planifnombre_Obj);
		planifdir_Obj	 	=	JSON.stringify(planifdir_Obj);	
	
		$.ajax({
			type : 'POST',
			cache:	false,
			url : '/pedidos/planificacion/logica/ajax/update.planificacion.php',				
			data:{	cantplanif			:	cantplanif,
					fecha_plan			:	fecha_planif,
					planifcliente_Obj	:	planifcliente_Obj,
					planifnombre_Obj	:	planifnombre_Obj,
					planifdir_Obj		:	planifdir_Obj,
					enviar				:	enviar
				},
			beforeSend	: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
				},
			success : function (result) { 
						$('#box_cargando').css({'display':'none'});
						if (result){
							var url;
							if (result.replace("\n","") === '0'){
								$('#box_cargando').css({'display':'none'});
								$('#box_confirmacion').css({'display':'block'});
								$("#msg_confirmacion").html('La planificaci\u00f3n fue grabada.');
							} else {
								if(result.replace("\n","") === '1'){ 
									$('#box_cargando').css({'display':'none'});
									$('#box_confirmacion').css({'display':'block'});
									$("#msg_confirmacion").html("La planificaci\u00f3n fue enviada.");
									url = window.location.origin+'/pedidos/planificacion/index.php?fecha_planif=' + document.getElementById("fecha_planif").value;						
									document.location.href=url;
								} else {
									$('#box_error').css({'display':'block'});
									$("#msg_error").html(result);
								}
							}				
						}															
					},
			error: function () {
					$('#box_cargando').css({'display':'none'});	
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error en el proceso de Envío de Planificaci\u00f3n.");	
				},							
		});
	} else {
		$('#box_cargando').css({'display':'none'});	
		$('#box_error').css({'display':'block'});
		$("#msg_error").html("Deber cargar al menos un cliente.");	
	}
}	

function dac_Guardar_Parte(enviar){	
	"use strict";
	var cantparte			=	document.getElementsByName('partecliente[]').length; //cantidad de partes	
	var	fecha_parte			= 	document.getElementById("fecha_planif").value;	
	var cant_acciones		=	document.getElementsByName('parteaccion1[]').length; //cantidad de acciones
	//--------------------------
	//Declaro Objetos del Parte
	var partecliente_Obj 	=	{};
	var partenombre_Obj 	=	{};
	var partedir_Obj 		=	{};
	var partetrabaja_Obj 	=	{};	
	var parteobservacion_Obj=	{};
	var parteacciones_Obj	=	{};	
	var nroparte_Obj		=	{};
	//--------------------------
	//según cant de partes
	var parteacciones;
	var j;
	if (cantparte === 1){
		nroparte_Obj[0]			=	document.getElementById("partenro1").value;
		partecliente_Obj[0]		=	document.getElementById("partecliente1").value;
		partenombre_Obj[0]		=	document.getElementById("partenombre1").value;
		partedir_Obj[0]			=	document.getElementById("partedir1").value;
		partetrabaja_Obj[0]		=	document.getElementById("partetrabaja1").value;
		parteobservacion_Obj[0]	=	document.getElementById("parteobservacion1").value;		
		//-------------------------------
		//recorro acciones del parte 1
		for(j=0;j<cant_acciones;j++){			
			parteacciones		= 	document.fm_parte.elements['parteaccion1[]'];
			//se consulta si los checks estan en true o false
			if(parteacciones[j].checked){	
				if(typeof(parteacciones_Obj[0]) === "undefined"){
					parteacciones_Obj[0]	=	parteacciones[j].value;
				} else {
					parteacciones_Obj[0]	=	parteacciones_Obj[0]+","+parteacciones[j].value;
				}				
			}
		}
	} else {
		var partecliente 		= document.fm_parte.elements["partecliente[]"];
		var partenombre			= document.fm_parte.elements["partenombre[]"];
		var partedir	 		= document.fm_parte.elements["partedir[]"];	
		var partetrabaja		= document.fm_parte.elements["partetrabaja[]"];
		var parteobservacion	= document.fm_parte.elements["parteobservacion[]"];
		//nros de id de cada parte para sacar el nombre de acciones
		var nroparte			= document.fm_parte.elements["partenro[]"];	

		for(var i=0; i<cantparte; i++){ //cantidad de partes cargados
			partecliente_Obj[i]		=	partecliente[i].value;			
			partenombre_Obj[i] 		=	partenombre[i].value;				
			partedir_Obj[i] 		=	partedir[i].value;				
			partetrabaja_Obj[i] 	=	partetrabaja[i].value;			
			parteobservacion_Obj[i] =	parteobservacion[i].value;
			nroparte_Obj[i] 		=	nroparte[i].value; //nro identificador del parte		
			var title_ac			=	'parteaccion'+(nroparte_Obj[i])+'[]';
			parteacciones		= 	document.fm_parte.elements[title_ac]; //Carga los valores de las aaciones de cada parte

			//----------------------------	
			//recorre cada accion de cada parte para cargar cada parteacciones_Obj[i] con los id de las acciones checadas
			for(j=0;j<cant_acciones;j++){ 			
				//se consulta si los checks estan en true o false y se le pasa el id para grabar los seleccionados: Ejemplo: 1,5,14...etc 
				if(parteacciones[j].checked){	
					if(typeof(parteacciones_Obj[i]) === "undefined"){
						parteacciones_Obj[i]	=	parteacciones[j].value;
					} else {
						parteacciones_Obj[i]	=	parteacciones_Obj[i]+","+parteacciones[j].value;
					}				
				}
			}			
		}		
	}

	nroparte_Obj			=	JSON.stringify(nroparte_Obj);
	partecliente_Obj		=	JSON.stringify(partecliente_Obj);
	partenombre_Obj			=	JSON.stringify(partenombre_Obj);
	partedir_Obj	 		=	JSON.stringify(partedir_Obj);	
	partetrabaja_Obj		=	JSON.stringify(partetrabaja_Obj);
	parteobservacion_Obj	=	JSON.stringify(parteobservacion_Obj);
	parteacciones_Obj		=	JSON.stringify(parteacciones_Obj);

	$.ajax({
		type : 'POST',
		cache:	false,
		url : '/pedidos/planificacion/logica/ajax/update.parte.php',					
		data:{	cantparte			:	cantparte,
				fecha_plan			:	fecha_parte,
				partecliente_Obj	:	partecliente_Obj,
				partenombre_Obj		:	partenombre_Obj,
				partedir_Obj		:	partedir_Obj,
				partetrabaja_Obj	:	partetrabaja_Obj,
				parteobservacion_Obj:	parteobservacion_Obj,
				parteacciones_Obj	:	parteacciones_Obj,
				nroparte_Obj		:	nroparte_Obj,
				enviar				:	enviar
		},
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : function (result) { 
			$('#box_cargando').css({'display':'none'});
			if (result){
				if (result.replace("\n","") === '0'){
					$('#box_confirmacion').css({'display':'block'});
					$("#msg_confirmacion").html('El parte fue grabado.');
				} else {
					if(result.replace("\n","") === '1'){ 
						$('#box_confirmacion').css({'display':'block'});
						$("#msg_confirmacion").html('El parte fue enviado.');
						var url = window.location.origin+'/pedidos/planificacion/index.php?fecha_planif=' + document.getElementById("fecha_planif").value;
						document.location.href=url;
					} else {
						$('#box_error').css({'display':'block'});
						$("#msg_error").html(result);
					}
				}				
			}															
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error en el proceso de Envío del Parte Diario.");	
		}								
	});
}	


function dac_Duplicar_Planificacion(fecha_origen){
	"use strict";
	var fecha_destino = document.getElementById("fecha_destino").value;
	if(confirm("Desea duplicar la planificaci\u00f3n del d\u00eda "+fecha_origen+" a la fecha "+fecha_destino+" ?")){
		$.ajax({
			type : 'POST',
			cache:	false,
			url : '/pedidos/planificacion/logica/ajax/duplicar.planificacion.php',					
			data:{	fecha_origen	:	fecha_origen,
					fecha_destino	:	fecha_destino
			},	
			beforeSend	: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
			},
			success : function (result) {
						$('#box_cargando').css({'display':'none'});
						if (result){
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
						}
			},
			error: function () {
				$('#box_cargando').css({'display':'none'});	
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error en el duplicado de Planificaci\u00f3n.");	
			}								
		});
	}
}

function dac_ExportarPlanifOPartesToExcel(fecha_inicio, fecha_final, tipo){
	"use strict";
	document.getElementById("tipo_exportado").value = tipo;
	if(fecha_final === ""){
		alert("Debe indicar la fecha final para descargar el parte diario.");
	} else {
		if(confirm("Desea exportar lo/el "+tipo+" del d\u00eda "+fecha_inicio+" a la fecha "+fecha_final+" ?")){	
			document.getElementById("export_partes_to_excel").submit();
		}			
	}
}


