// CARGAR FACTURA
var nextpagoinput 	= 0;
var saldo_total 	= 0;

function dac_CargarDatosPagos(k, idfact, idempresa, idprov, nombre, plazo, fechavto, tipo, factnro, fechacbte, saldo, observacion, activa){
	"use strict";
	nextpagoinput++;
	var borrar 		= '<div class="bloque_10"><img id="borrar_fact" class=\"icon-delete\" onClick="dac_EliminarDetallePagos('+nextpagoinput+', '+saldo+')"/></div>';		
	var notificar 	= '<div class="bloque_10"><img class=\"icon-notify\" onClick="dac_NotificarFechaPago('+idempresa+', '+idprov+', '+factnro+')"/></div>';			
	var campo = '<div id="rutfact'+nextpagoinput+'">';
		campo += '<input id="idfact'+nextpagoinput+'" name="idfact[]" type="text" value="'+idfact+'" readonly="readonly" hidden="hidden">';
		campo += '<div class="bloque_10"><input id="empresa'+nextpagoinput+'" name="empresa[]" type="text" value="'+idempresa+'" readonly="readonly"></div>';
		campo += '<div class="bloque_9"><input id="idprov'+nextpagoinput+'" name="idprov[]" type="text" value="'+idprov+'" readonly="readonly"></div>';
		campo += '<div class="bloque_7"><input id="nombre'+nextpagoinput+'" name="nombre[]" type="text" value="'+nombre+'" readonly="readonly"></div>';
		campo += '<div class="bloque_10"><input id="plazo'+nextpagoinput+'" name="plazo[]" type="text" value="'+plazo+'" readonly="readonly"></div>';
		campo += '<div class="bloque_8"><input id="fechavto'+nextpagoinput+'" name="fechavto[]" type="text" value="'+fechavto+'" readonly="readonly"></div>';
		campo += '<div class="bloque_10"><input id="tipo'+nextpagoinput+'" name="tipo[]" type="text" value="'+tipo+'" readonly="readonly"></div>';
		campo += '<div class="bloque_9"><input id="factnro'+nextpagoinput+'" name="factnro[]" type="text" value="'+factnro+'" readonly="readonly"></div>';
		campo += '<div class="bloque_8"><input id="fechacbte'+nextpagoinput+'" name="fechacbte[]" type="text" value="'+fechacbte+'" readonly="readonly"></div>';
		campo += '<div class="bloque_9"><input id="saldo'+nextpagoinput+'" name="saldo[]" type="text" value="'+saldo+'" style="text-align:right;" readonly="readonly"></div>';
		campo += '<div class="bloque_8"><input id="observacion'+nextpagoinput+'" name="observacion[]" type="text" value="'+observacion+'"></div>';
		campo += borrar;
		campo += notificar+'<hr class="hr-line">';
	$("#lista_fechaspago").before(campo);

	saldo_total += parseFloat(saldo);
	document.getElementById('saldo_total').innerHTML = dac_Redondeo(saldo_total, 2);
}		

function dac_EliminarDetallePagos(id, saldo){
	"use strict";
	var elemento	=	document.getElementById('rutfact'+id);
	elemento.parentNode.removeChild(elemento);
	saldo_total -= parseFloat(saldo);
	document.getElementById('saldo_total').innerHTML = dac_Redondeo(saldo_total, 2);
}

function dac_NotificarFechaPago(idemp, idprov, factnro){
	"use strict";
	var fecha	=	document.getElementById('f_fecha').value;
	$.ajax({
		type	: 	"POST",
		cache	:	false,
		url		: 	"/pedidos/proveedores/fechaspago/logica/ajax/notificar.fechapago.php",
		data	: 	{	idempresa	: 	idemp,
						idprov		:	idprov,
						fechapago	:	fecha,
						factnro		:	factnro },
		beforeSend: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});					
			$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
		},
		success	: 	function(result) {
			if(result){
				$('#box_cargando').css({'display':'none'});
				if(result.replace("\n","") !== '1'){ 
					$('#box_error').css({'display':'block'});
					$("#msg_error").html(result);
					subir();
				} else{ 
					$('#box_confirmacion').css({'display':'block'});
					$("#msg_confirmacion").html('Se envió la notificación de fecha de pago');
					setTimeout($('#box_confirmacion').css({'display':'block'}), 5000);
				}
			}
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("ERROR! al cargar los datos del proveedor");
		}
	});
}

/* Controla si el proveedor está activo en el listado de proveedores */
function dac_ControlProveedor(idempresa, idprov){
	"use strict";
	$.ajax({
		type	: 	"POST",
		cache	:	false,
		url		: 	"/pedidos/proveedores/fechaspago/logica/ajax/control.proveedor.php",
		data	: 	{	idempresa	: 	idempresa,
						idprov		:	idprov },
		beforeSend: function () {
						$('#box_confirmacion').css({'display':'none'});
						$('#box_error').css({'display':'none'});
						$('#box_cargando').css({'display':'block'});					
						$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
		},
		success	: 	function(result) {
						if(result){	
							$('#box_cargando').css({'display':'none'});
							if(result.replace("\n","") !== '1'){ 
								$('#box_error').css({'display':'block'});
								$("#msg_error").html(result);
								subir();
							}
						}
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("ERROR! al cargar los datos del proveedor");
		}		
	});
}