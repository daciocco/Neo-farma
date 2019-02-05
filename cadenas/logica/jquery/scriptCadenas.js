function dac_getCadenas(empresa) {
	"use strict";
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getCadenas.php',					
		data:	{	idEmpresa	:	empresa, },	
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});
						if (resultado){	
							document.getElementById('tablacadenas').innerHTML = resultado;
						} else {
							$('#box_cargando').css({'display':'none'});	
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al consultar los registros.");	
		},							
	});
}	
/****************************/
/*	Carga Datos de Cuentas	*/
/****************************/
function dac_getCuentas(idEmpresa) {
	"use strict";
	$.ajax({
		type	: 	'POST',
		cache	:	false,
		url 	: 	'logica/ajax/getCuentas.php',					
		data	:	{	empresa			:	idEmpresa},		
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},		
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});		
						if (resultado){								
							document.getElementById('tablacuentas').innerHTML = resultado;
						} else {
							$('#box_cargando').css({'display':'none'});	
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al intentar consultar los registros.");	
		},

	});
}


/********************************/
/*	Cuando cambia de Empresa	*/
/********************************/
function dac_changeEmpresa(idEmpresa) {
	"use strict";
	dac_getCadenas(idEmpresa);
	dac_getCuentas(idEmpresa);
	$("#cuenta_relacionada").empty();
	
	$('#cadid').val('');
	$('#cadena').val('');
	$('#nombre').val('');

}

function dac_changeCadena(id, idCadena, nombre) {
	"use strict";
	$("#cuenta_relacionada").empty();
	
	$('#cadid').val(id);
	$('#cadena').val(idCadena);
	$('#nombre').val(nombre);

	var empresa = document.getElementById('empresa').value;
	dac_getCuentasCadena(empresa, idCadena);
}

var nextCadena = 0;
function dac_cuentaRelacionada(id, idCuenta, nombre, tipocad){	
	"use strict";
	nextCadena++;
	var campo;
	campo =	'<div id="rutcuenta'+nextCadena+'">';
		//campo +='<input id="ctaId'+nextCadena+'" name="ctaId[]" type="text" value='+id+' hidden>';
		campo += '<div class="bloque_1">'+nombre+'</div>';
		campo +='<input id="cuentaId'+nextCadena+'" name="cuentaId[]" type="text" value='+idCuenta+' hidden><div class="bloque_4">'+idCuenta+'</div>';
		
		campo += '<div class="bloque_4"><select id="tipoCadena" name="tipoCadena[]">';
			if(tipocad === '1'){
				campo += '<option  id="0" value="0" ></option>';
				campo += '<option  id="1" value="1"  selected >Sucursal</option>'; 
			} else {
				campo += '<option  id="0" value="0" selected></option>';
				campo += '<option  id="1" value="1"   >Sucursal</option>'; 	
			}
				
		campo += '</select></div>';
	
		campo +='<div class="bloque_4"><input id="btmenos" class="btmenos" type="button" value=" - " onClick="dac_deleteCuentaRelacionada('+id+', '+nextCadena+')"></div>';
	campo +='</div>';
	$("#cuenta_relacionada").append(campo);		
}	

// Eliminar div de cuenta
function dac_deleteCuentaRelacionada(id, nextCadena){
	"use strict";
	var elemento	=	document.getElementById('rutcuenta'+nextCadena);
	elemento.parentNode.removeChild(elemento);
}


function dac_getCuentasCadena(empresa, idCadena){
	"use strict";
	$.ajax({
		type	: 	'POST',
		cache	:	false,
		url 	: 	'logica/ajax/getCuentasCadena.php',					
		data	:	{	empresa	:	empresa,
						cadena	:	idCadena},		
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});	
						if (resultado){		
							var json = resultado;	
							for(var i = 0; i < json.length; i++){
								dac_cuentaRelacionada(json[i].id, json[i].cuenta, json[i].nombre, json[i].tipocad);
							}	

						} else {
							$('#box_cargando').css({'display':'none'});	
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al intentar consultar los registros.");	
		},

	});
}