function dac_getDroguerias(empresa) {
	"use strict";
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getDroguerias.php',					
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
							document.getElementById('tabladroguerias').innerHTML = resultado;
						} else {
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

/********************************/
/*	Cuando cambia de Empresa	*/
/********************************/
function dac_changeEmpresa(idEmpresa) {
	"use strict";
	dac_getDroguerias(idEmpresa);
	$('#drogid').val('');
	$('#nombre').val('');
	$("#drogueria_relacionada").empty();
	$("#acciones").empty();
}

function dac_changeDrogueria(id, nombre) {
	"use strict";
	$("#drogueria_relacionada").empty();
	$("#acciones").empty();
	$('#drogid').val(id);
	$('#nombre').val(nombre);

	var empresa = document.getElementById('empresa').value;
	dac_getCuentasDrogueria(empresa, id);
	dac_newDrogRelacionada(id, empresa);
	
	
	var a = document.getElementById('deleteDrog');
	a.href = "logica/eliminar.drogueria.php?drogid="+$('#drogid').val();
}

var nextDrogueria = 0;
function dac_drogueriaRelacionada(id, idCuenta, nombre, localidad, rentTl, rentTd){	
	"use strict";
	nextDrogueria++;
	var campo;
	campo =	'<div id="rutdrog'+nextDrogueria+'">';		
		campo += '<input id="drogtid'+nextDrogueria+'" name="drogtid[]" type="text" value='+id+' hidden>';

		campo += '<div class="bloque_2">'+localidad+'</div>';
		campo +='<input id="drogtcliid'+nextDrogueria+'" name="drogtcliid[]" type="text" value='+idCuenta+' hidden><div class="bloque_4">'+idCuenta+'</div>';
		campo +='<div class="bloque_4"><input id="rentTl'+nextDrogueria+'" name="rentTl[]" type="text" value='+rentTl+' maxlength="10" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);"></div>';
		campo +='<div class="bloque_4"><input id="rentTd'+nextDrogueria+'" name="rentTd[]" type="text" value='+rentTd+'  maxlength="10" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);"></div>';
		campo +='<div class="bloque_4"><a href="editar.php?drogtid='+id+'" target="_blank" title="editar"><img src="../images/icons/icono-editar.png" border="0" align="absmiddle" style="height:30px;" onmouseover="this.src=\'/pedidos/images/icons/icono-editar-hover.png\';" onmouseout="this.src=\'/pedidos/images/icons/icono-editar.png\';"/></a>';	
		campo +='<a title="eliminar" onclick="javascript:dac_deleteDrogRelacionada('+id+', '+nextDrogueria+')"><img src="../images/icons/icono-eliminar.png" border="0" align="absmiddle" style="height:30px;" onmouseover="this.src=\'/pedidos/images/icons/icono-eliminar-hover.png\';" onmouseout="this.src=\'/pedidos/images/icons/icono-eliminar.png\';"/></a></div>';
	
	campo +='</div>';
	$("#drogueria_relacionada").append(campo);		
}

// Eliminar div de cuenta
function dac_deleteDrogRelacionada(id, nextDrogueria){
	"use strict";
	var elemento	=	document.getElementById('rutdrog'+nextDrogueria);
	elemento.parentNode.removeChild(elemento);
	
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/eliminar.drogueriaRelacionada.php',					
		data:	{	drogtid	:	id, },	
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});
						if (resultado){
							if(resultado === '1'){
								$('#box_confirmacion').css({'display':'block'});
								$("#msg_confirmacion").html("La cuenta ha sido eliminada.");	
							} else {
								$('#box_error').css({'display':'block'});
								$("#msg_error").html(resultado);	
							}							
						} else {
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

// Agregar div de droguería
function dac_newDrogRelacionada(id, empresa){
	"use strict";
	var campo;
		campo ='<a href="editar.php?drogid='+id+'&empresa='+empresa+'" target="_blank" title="Nueva Drogueria Relacionada"><img src="../images/icons/icono-nuevo.png" border="0" align="absmiddle" style="height:30px;" onmouseover="this.src=\'/pedidos/images/icons/icono-nuevo-hover.png\';" onmouseout="this.src=\'/pedidos/images/icons/icono-nuevo.png\';"/></a>';

	$("#acciones").append(campo);
}

function dac_getCuentasDrogueria(empresa, drogidCAD){
	"use strict";
	$.ajax({
		type	: 	'POST',
		cache	:	false,
		url 	: 	'logica/ajax/getCuentasDrogueria.php',					
		data	:	{	empresa		:	empresa,
						drogidCAD	:	drogidCAD},		
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
								dac_drogueriaRelacionada(json[i].id, json[i].cuenta, json[i].nombre, json[i].localidad, json[i].rentTl, json[i].rentTd);
							}
						} else {
							$('#box_cargando').css({'display':'none'});	
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("No hay registros relacionados.");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al intentar consultar los registros.");	
		},

	});
}