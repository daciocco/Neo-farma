//-----------------------------------------//
// Crea Div de Cuenta Transfer relacionada //
var nextCuentaTransfer = 0;
function dac_cargarCuentaTransferRelacionada2(id, idCta, idCuenta, nombre, nroClienteTransfer){
	"use strict";
	nextCuentaTransfer++;
	var campo =	'<div id="rutcuenta'+nextCuentaTransfer+'">';
		campo +='<div class="bloque_8"><input id="btmenos" class="btmenos" type="button" value=" - " onClick="dac_deleteCuentaTransferRelacionada2('+id+', '+nextCuentaTransfer+')"></div >';
		campo +='<div class="bloque_8"><input id="cuentaIdTransfer'+nextCuentaTransfer+'" name="cuentaIdTransfer[]" type="text" maxlength="10" placeholder="Transfer" value="'+nroClienteTransfer+'"/></div >';			
		campo +='<input id="cuentaId'+nextCuentaTransfer+'" name="cuentaId[]" type="text" value='+idCta+' hidden/>';
		campo += '<div class="bloque_8">&nbsp;'+idCuenta+'</div><div class="bloque_4">'+nombre.substring(0,25)+'</div>';
		campo += '<hr>';
	campo +='</div>';
	$("#detalle_cuenta2").append(campo);
}

// Botón eliminar para quitar un div de artículos
function dac_deleteCuentaTransferRelacionada2(id, nextCuentaTransfer){
	"use strict";
	var elemento	=	document.getElementById('rutcuenta'+nextCuentaTransfer);
	elemento.parentNode.removeChild(elemento);
}

function dac_cuentaTransferRelacionada() {
	"use strict";
	$("#detalle_cuenta2").empty();
	$('#box_cargando3').css({'display':'block'});
	$.ajax({
		type 	: 	'POST',
		cache	:	false,
		url 	: 	'/pedidos/cuentas/logica/jquery/cargar.cuentasTransferRelacionada.php',
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			$('#box_cargando3').css({'display':'block'});
			$("#msg_cargando3").html('<img class="icon-loading"/>Cargando... espere por favor!');
		},
		success : function (resultado) {
						$('#box_cargando').css({'display':'none'});
						if (resultado){
							$('#tablaCuentasTransfer2').html(resultado);
							$('#box_cargando3').css({'display':'none'});	
						} else {
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},	
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_cargando3').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al intentar consultar los registros.");	
		},
	});	
}

function dac_changeEmpresa(idEmpresa){	
	"use strict";
	$.getJSON('/pedidos/js/ajax/getCadena.php?idEmpresa='+idEmpresa, function(datos) {
		var idCadenas = datos;			
		$('#cadena').find('option').remove();
		$('#cadena').append("<option value='0' selected></option>");	
		$.each( idCadenas, function( key, value ) {
			var arr = value.split('-');
			var cadena = document.getElementById('cadena').value;
			if(arr[0] === cadena){
				$('#cadena').append("<option value='" + arr[0] + "' selected>" + arr[1] + "</option>");	
			} else {
				$('#cadena').append("<option value='" + arr[0] + "'>" + arr[1] + "</option>");
			}
		});
	});
}

function dac_changeLocalidad(localidad){
	"use strict";
	var idProvincia = 	$('#provincia').val();			
	$.getJSON('/pedidos/js/provincias/getLocalidad.php?idProvincia='+idProvincia, function(datos) {
		var localidades = datos;		
		$('#localidad').find('option').remove();							
		$.each( localidades, function( key, value ) {
			var arr = value.split('-');
			if(arr[0] === localidad) {
				$('#localidad').append("<option value='" + arr[0] + "' selected>" + arr[1] + "</option>");	
			} else {
				$('#localidad').append("<option value='" + arr[0] + "'>" + arr[1] + "</option>");
			}					
		});
	});

	if(idProvincia !== '1'){
		var codigosPostales;	
		$.getJSON('/pedidos/js/provincias/getCodigoPostal.php?idLocalidad='+localidad, function(datos) {
			codigosPostales = datos;	

			$('#codigopostal').val("");
			$.each( codigosPostales, function( key, value ) {
				$('#codigopostal').val(value);
			});
		});
	}
}

function dac_ShowPdf(archivo){	
	"use strict";
	$("#pdf_ampliado").empty();
	var campo	= 	'<iframe src=\"https://docs.google.com/gview?url='+archivo+'&embedded=true\" style=\"width:650px; min-height:260px; height:90%;\" frameborder=\"0\"></iframe>';			
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
	"use strict";
	$('#pdf_ampliado').fadeOut('slow');		
	return false;
}

//previene que se pueda hacer Enter en observaciones
$(document).ready(function() {
	"use strict";
	$('textarea').keypress(function(event) {		
		if (event.keyCode === 13) {
			event.preventDefault();
		}
	});	
	
	//-------------
	//Definir Lista de Precios disponible segun Categoría comercial seleccionada.
	$("#categoriaComer").change(function () {		
		var idCatComerc = $('#categoriaComer option:selected').val();	
		var empresa = $("#empselect").val();		
		var listaPrecios;
		$.getJSON('/pedidos/cuentas/logica/jquery/getListaPrecios.php?idCatComerc='+idCatComerc+'&empresa='+empresa, function(datos) {
			listaPrecios = datos;			
			$('#listaPrecio').find('option').remove();
			$.each( listaPrecios, function( key, value ) {
				var arr = value.split('|');
				$('#listaPrecio').append("<option value='" + arr[0] + "'>" + arr[1] + "</option>");
			});
		});
	});
	//---------------------
	
	
	
	
	
});