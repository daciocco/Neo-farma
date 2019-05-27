$(document).ready(function() {
	"use strict";
	$("#guardar_pagos").click(function (event) {
		var idfact = [];
		var empresa		= [];
		var idprov		= [];
		var nombre		= [];
		var plazo		= [];
		var fechavto	= [];
		var tipo		= [];
		var factnro		= [];
		var fechacbte	= [];
		var saldo		= [];
		var fechapago	= [];
		var observacion	= [];
		
		$('input[name="idfact[]"]:text').each(function() 		{ idfact 		=  idfact+"-"+$(this).val();});
		$('input[name="empresa[]"]:text').each(function() 		{ empresa 		=  empresa+"-"+$(this).val();});
		$('input[name="idprov[]"]:text').each(function() 		{ idprov 		=  idprov+"-"+$(this).val();});
		$('input[name="nombre[]"]:text').each(function() 		{ nombre 		=  nombre+"-"+$(this).val();});
		$('input[name="plazo[]"]:text').each(function() 		{ plazo 		=  plazo+"-"+$(this).val();});
		$('input[name="fechavto[]"]:text').each(function() 		{ fechavto 		=  fechavto+"-"+$(this).val();});
		$('input[name="tipo[]"]:text').each(function() 			{ tipo 			=  tipo+"-"+$(this).val();});
		$('input[name="factnro[]"]:text').each(function() 		{ factnro 		=  factnro+"-"+$(this).val();});
		$('input[name="fechacbte[]"]:text').each(function() 	{ fechacbte 	=  fechacbte+"-"+$(this).val();});
		$('input[name="saldo[]"]:text').each(function() 		{ saldo 		=  saldo+"-"+$(this).val();});
		$('input[name="fechapago[]"]:text').each(function() 	{ fechapago 	=  fechapago+"-"+$(this).val();});
		$('input[name="observacion[]"]:text').each(function() 	{ observacion	=  observacion+"-"+$(this).val();});
		var fecha	=	$('input[name="fecha"]:text').val();
		
		$.ajax({
			type	: 	"POST",
			cache	:	false,
			url		: 	"/pedidos/proveedores/fechaspago/logica/ajax/update.pagos.php",
			data	: 	{	fecha		: 	fecha,
							idfact		:	idfact,
							empresa		:	empresa,
							idprov		:	idprov,
							nombre		:	nombre,
							plazo		:	plazo,
							fechavto	:	fechavto,
							tipo		:	tipo,
							factnro		:	factnro,
							fechacbte	:	fechacbte,
							saldo		:	saldo,
							fechapago	:	fechapago,
							observacion	:	observacion },
			beforeSend: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success	: 	function(result) {
				if(result){	
					$('#box_cargando').css({'display':'none'});
					if(result.replace("\n","") === '1'){ 
						$('#box_confirmacion').css({'display':'block'});
						$("#msg_confirmacion").html('Los cambios se han guardado');		
						location.reload();									
					}else{ 
						$('#box_error').css({'display':'block'});
						$("#msg_error").html(result);	
					}
				}
			},
			error: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("ERROR! al guardar los Pagos");
			}			
		});
	});
});

$(function(){ "use strict"; $('#importar').click(ImportarFacturasProveedores); });

//	archivo de liquidacion	//
function ImportarFacturasProveedores(){	
	"use strict";
	var archivos 	= 	document.getElementById("file");
	var archivo 	= 	archivos.files;
	
	if(archivo.length !== 0){
		var fecha	= 	document.getElementById("f_fecha").value;	
		archivos 	= 	new FormData();
		archivos.append('archivo',archivo);		
		
		for(var i=0; i<archivo.length; i++){
			archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo al arreglo con un indice direfente	
		}
		
		archivos.append('fecha', fecha);
		
		$.ajax({
			url			:	'/pedidos/proveedores/fechaspago/logica/importar_facturas.php',
			type		:	'POST',
			contentType	:	false,
			data		:	archivos,			
			processData	:	false,
			cache		:	false,		
			beforeSend	: 	function () {
								alert("Se proceder\u00e1 a importar el archivo de facturas de pago");	
								$('#box_confirmacion').css({'display':'none'});								
								$('#box_error').css({'display':'none'});	
								$('#box_cargando').css({'display':'block'});					
								$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
							},
			afterSend	:	function() {									
								$('#box_cargando').css({'display':'none'});								
							},
			success		: 	function(result) {			
								if(result){	
									$('#box_cargando').css({'display':'none'});
									if(!isNaN(parseInt(result))){ 
										$('#box_confirmacion').css({'display':'block'});
										$("#msg_confirmacion").html('Archivo Subido correctamente con '+result+' registros');	
										location.reload();										
									}else{ 
										$('#box_error').css({'display':'block'});
										$("#msg_error").html(result);				
										
									}
								}											
							},
		}).fail( function(jqXHR, textStatus, errorThrown) {	
			if (jqXHR.status === 0) {		
				alert('Not connect: Verify Network.');	
			} else if (jqXHR.status === 404) {	
				alert('Requested page not found [404]');	
			} else if (jqXHR.status === 500) {	
				alert('Internal Server Error [500].');	
			} else if (textStatus === 'parsererror') {	
				alert('Requested JSON parse failed.');	
			} else if (textStatus === 'timeout') {	
				alert('Time out error.');	
			} else if (textStatus === 'abort') {	
				alert('Ajax request aborted.');	
			} else {	
				alert('Uncaught Error: ' + jqXHR.responseText);	
			}		
		});
	} else {
		alert("Debe adjuntar un archivo para importar.");
	}
}

g_globalObject = new JsDatePick({
	useMode:	2,
	isStripped:	false, //borde gris
	yearsRange: new Array (1971,2100),
	target:	"fechaDesde",
	dateFormat:"%d-%M-%Y"
});	

g_globalObject = new JsDatePick({
	useMode:	2,
	isStripped:	false, //borde gris
	yearsRange: new Array (1971,2100),
	target:	"fechaHasta",
	dateFormat:"%d-%M-%Y"
});	

g_globalObject = new JsDatePick({
	useMode:	2,
	isStripped:	false, //borde gris
	yearsRange: new Array (1971,2100),
	//limitToToday: true,
	target:	"f_fecha",
	dateFormat:"%d-%M-%Y"
});		

g_globalObject.setOnSelectedDelegate(function(){
	"use strict";
	var obj 	= g_globalObject.getSelectedDay();
	console.log(obj);
	var fecha 	= ("0" + obj.day).slice (-2) + "-" + ("0" + obj.month).slice (-2) + "-" + obj.year;	
	
	document.getElementById("f_fecha").value	= 	fecha;
	var url 	= window.location.origin+'/pedidos/proveedores/fechaspago/index.php?fecha=' + fecha;
	document.location.href=url;			
});

$("#btnExporHistorial").click(function () {
	"use strict";
	var fechaDesde = $('#fechaDesde').val();
	var fechaHasta = $('#fechaHasta').val();
	
	$('#box_confirmacion').css({'display':'none'});								
	$('#box_error').css({'display':'none'});	
	if(fechaDesde === '' || fechaHasta === ''){
		$('#box_error').css({'display':'block'});
		$("#msg_error").html('Indique las fechas de descarga.');
	} else {		
		if( (new Date(fechaDesde).getTime() > new Date(fechaHasta).getTime())){
			$('#box_error').css({'display':'block'});
			$("#msg_error").html('La fecha "Desde" debe ser menor a la fecha "Hasta"');
		} else {			
			var url = "logica/exportar.historial.php?desde="+fechaDesde+"&hasta="+fechaHasta;
    		$("body").append("<iframe src='" + url + "' style='display: none;' ></iframe>");
			$('#box_confirmacion').css({'display':'block'});
			$("#msg_confirmacion").html('Archivo exportado correctamente.');
		}	
	}
});