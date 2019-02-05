// CARGAR FACTURA
var nextpagoinput 	= 0;
var saldo_total 	= 0;
function dac_CargarDatosPagos(k, idfact, idempresa, idprov, nombre, plazo, fechavto, tipo, factnro, fechacbte, saldo, observacion, activa){
	"use strict";
	nextpagoinput++;

	var borrar 		= '<img id="borrar_fact" src="/pedidos/images/icons/icono-eliminar.png" border="0" align="absmiddle" onClick="dac_EliminarDetallePagos('+nextpagoinput+', '+saldo+')"/>';		

	var notificar 	= '<img id="notificar_fecha" src="/pedidos/images/icons/icono-correo-fecha.png" border="0" align="absmiddle" onClick="dac_NotificarFechaPago('+idempresa+', '+idprov+', '+factnro+')"/>';			

	var clase =	((nextpagoinput % 2) === 0)	? "par" : "impar";

	var campo = '<tr id="rutfact'+nextpagoinput+'" class='+clase+'><td><input id="idfact'+nextpagoinput+'" name="idfact[]" type="text" value="'+idfact+'" readonly="readonly" hidden="hidden"/><input id="empresa'+nextpagoinput+'" name="empresa[]" type="text" value="'+idempresa+'" style="width:30px;" readonly="readonly"/></td> <td><input id="idprov'+nextpagoinput+'" name="idprov[]" type="text" value="'+idprov+'" style="width:50px;" readonly="readonly"/></td><td><input id="nombre'+nextpagoinput+'" name="nombre[]" type="text" value="'+nombre+'" style="width:200px; text-align:left;" readonly="readonly"/></td> <td><input id="plazo'+nextpagoinput+'" name="plazo[]" type="text" value="'+plazo+'" style="width:40px;" readonly="readonly"/></td> <td><input id="fechavto'+nextpagoinput+'" name="fechavto[]" type="text" value="'+fechavto+'" style="width:80px;" readonly="readonly"/></td> <td><input id="tipo'+nextpagoinput+'" name="tipo[]" type="text" value="'+tipo+'" style="width:50px;" readonly="readonly"/></td> <td><input id="factnro'+nextpagoinput+'" name="factnro[]" type="text" value="'+factnro+'" style="width:60px;" readonly="readonly"/></td> <td><input id="fechacbte'+nextpagoinput+'" name="fechacbte[]" type="text" value="'+fechacbte+'" style="width:80px;" readonly="readonly"/></td> <td><input id="saldo'+nextpagoinput+'" name="saldo[]" type="text" value="'+saldo+'" style="width:80px; text-align:right;" readonly="readonly" /></td><td><input id="observacion'+nextpagoinput+'" name="observacion[]" type="text" value="'+observacion+'" style="width:140px;"/></td><td>'+borrar+'</td><td>'+notificar+'</td> </tr>';

	$("#lista_fechaspago").before(campo);

	saldo_total += parseFloat(saldo);
	document.getElementById('saldo_total').innerHTML = dac_Redondeo(saldo_total, 2); //Math.round(saldo_total * 100) / 100;
}		

function dac_EliminarDetallePagos(id, saldo){
	"use strict";
	var elemento	=	document.getElementById('rutfact'+id);
	elemento.parentNode.removeChild(elemento);

	saldo_total -= parseFloat(saldo);
	document.getElementById('saldo_total').innerHTML = dac_Redondeo(saldo_total, 2); //Math.round(saldo_total * 100) / 100;
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
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
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
						$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
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