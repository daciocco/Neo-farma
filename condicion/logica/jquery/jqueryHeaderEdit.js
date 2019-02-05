//previene que se pueda hacer Enter en observaciones
$(document).ready(function() {
	"use strict";
	$('textarea').keypress(function(event) {	
		if (event.keyCode === 13) {
			event.preventDefault();
		}
	});
	
	$( "#btsend" ).click(function () {
		var condid		=	$('input[name="condid"]:text').val();
		var empselect	=	$('select[name="empselect"]').val();
		var labselect	=	$('select[name="labselect"]').val();
		var tiposelect	=	$('select[name="tiposelect"]').val();
		
		var nombre		= 	$('input[name="nombre"]:text').val();
		var condpago	= 	$('input[name="condpago"]:text').val();
		var minMonto	= 	$('input[name="minMonto"]:text').val();
		var cantMinima	= 	$('input[name="cantMinima"]:text').val();
		var minReferencias	=	$('input[name="minReferencias"]:text').val();
		var fechaInicio	= 	$('input[name="fechaInicio"]:text').val();
		var fechaFin	= 	$('input[name="fechaFin"]:text').val();
		
		var observacion	= 	$('textarea[name="observacion"]').val();
		
		var habitualCant	= 	$('input[name="habitualCant"]:text').val();
		var habitualBonif1	= 	$('input[name="habitualBonif1"]:text').val();
		var habitualBonif2	= 	$('input[name="habitualBonif2"]:text').val();
		var habitualDesc1	= 	$('input[name="habitualDesc1"]:text').val();
		var habitualDesc2	= 	$('input[name="habitualDesc2"]:text').val();
						
		var cuentaid;		//= new Array();
		var condpagoid;  	
		var condidart		= []; //= new Array();
		var condprecioart	= []; //= new Array();
		var condpreciodigit	= []; //= new Array();
		var condcantmin		= []; //= new Array();
		var condoferta		= []; //= new Array();
		
		$('input[name="cuentaid[]"]:text').each(function() 	{		
			cuentaid =  (cuentaid) ? cuentaid+","+$(this).val() : $(this).val();
		});
		
		$('input[name="condpagoid[]"]:text').each(function() 	{		
			condpagoid =  (condpagoid) ? condpagoid+","+$(this).val() : $(this).val();
		});
		
		$('input[name="condprecioart[]"]:text').each(function(i) 	{ condprecioart[i] 	=  $(this).val();});
		$('input[name="condpreciodigit[]"]:text').each(function(i)	{ condpreciodigit[i]=  $(this).val();});
		$('input[name="condcantmin[]"]:text').each(function(i) 		{ condcantmin[i] 	=  $(this).val();});
		$('select[name="condoferta[]"]').each(function(i) 			{ condoferta[i] 	=  $(this).val();});
		
		var condcant 	= '';
		var condbonif1	= '';
		var condbonif2	= '';
		var conddesc1	= '';
		var conddesc2	= '';
				
		//al recorrer el foreach de artículos, cargo sus bonificaciones?
		$('input[name="condidart[]"]:text').each(function(i) {
			condidart[i] =  $(this).val();
			
			$('input[name="condcant'+condidart[i]+'[]"]:text').each(function(j) {
				condcant	=	(j === 0) ? condcant+$(this).val() : condcant+"-"+$(this).val();
			});
			$('input[name="condbonif1'+condidart[i]+'[]"]:text').each(function(j) { 
				condbonif1	=	(j === 0) ? condbonif1+$(this).val() : condbonif1+"-"+$(this).val();
			});
			$('input[name="condbonif2'+condidart[i]+'[]"]:text').each(function(j) { 
				condbonif2	=	(j === 0) ? condbonif2+$(this).val() : condbonif2+"-"+$(this).val();
			});
			$('input[name="conddesc1'+condidart[i]+'[]"]:text').each(function(j) { 
				conddesc1	=	(j === 0) ? conddesc1+$(this).val() : conddesc1+"-"+$(this).val();
			});
			$('input[name="conddesc2'+condidart[i]+'[]"]:text').each(function(j) { 
				conddesc2	=	(j === 0) ? conddesc2+$(this).val() : conddesc2+"-"+$(this).val();
			});
			
			condcant 	= condcant + '|';
			condbonif1	= condbonif1 + '|';	
			condbonif2 	= condbonif2 + '|';
			conddesc1 	= conddesc1 + '|';
			conddesc2	= conddesc2 + '|';
			
		});
		
		dac_enviar();
		function dac_enviar(){
			$.ajax({
				type: 'POST',
				url: '/pedidos/condicion/logica/update.condicion.php',
				data: {	
					condid		:	condid,
					empselect	:	empselect,
					labselect	:	labselect,
					tiposelect	:	tiposelect,
					nombre		:	nombre,
					condpago	:	condpago,
					minMonto	:	minMonto,
					cantMinima	:	cantMinima,
					minReferencias:minReferencias,
					fechaInicio	:	fechaInicio,
					fechaFin	:	fechaFin,
					observacion	:	observacion,
					habitualCant	: 	habitualCant,
					habitualBonif1	: 	habitualBonif1,
					habitualBonif2	: 	habitualBonif2,
					habitualDesc1	: 	habitualDesc1,
					habitualDesc2	: 	habitualDesc2,
					cuentaid	:	cuentaid,
					condpagoid	:	condpagoid,
					condidart	:	condidart,
					condprecioart:	condprecioart,
					condpreciodigit:condpreciodigit,
					condcantmin	:	condcantmin,
					condoferta	:	condoferta,
					condcant	:	condcant,
					condbonif1	:	condbonif1,
					condbonif2	:	condbonif2,
					conddesc1	:	conddesc1,
					conddesc2	:	conddesc2
				},
				beforeSend	: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
					$("#btsend").hide(100);
				},
				success: function(result) {
					if (result){		
						var scrolltohere = '';
						$('#box_cargando').css({'display':'none'});						
						if (result.replace("\n","") === '1'){
							//Confirmación	
							scrolltohere = "#box_confirmacion";
							$('#box_confirmacion').css({'display':'block'});
							$("#msg_confirmacion").html('Los datos fueron registrados');
							window.location.reload(true);
						} else {
							//El pedido No cumple Condiciones
							scrolltohere = "#box_error";
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
							if(result === "Invalido"){
								window.location = "/pedidos/login/index.php";
							}
						}					
						$('html,body').animate({
							scrollTop: $(scrolltohere).offset().top
						}, 2000);
						$("#btsend").show(100);									
					}
				},
				error: function () {
					$('#box_cargando').css({'display':'none'});	
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error en el proceso");	
					$("#btsend").show(100);	
				},
			});
		}
	});
});

//------------------------------//
// Crea Nuevo div de artículo	//
var nextcondicion = 0;
function dac_cargarArticuloCondicion(id, idart, nombre, precioLista, cantmin, precioDrog, oam, medicinal, iva, empresa, ganancia){
	"use strict";
	nextcondicion++;			
	//-----------------------
	var p1 =	parseFloat(precioLista);
	var p2 =	parseFloat(1.450);
	var pvp =	parseFloat(p1*p2);
	if(iva === 'N') { pvp = parseFloat(pvp*1.21); }			
	if(medicinal === 'N'){ pvp = parseFloat(pvp*1.21); }			
	if(empresa === '3') {
		if(iva === 'N') { pvp = parseFloat(pvp*1.21); }
		if(medicinal === 'S') { pvp = parseFloat(pvp*1.21); }
	}		
	if(ganancia !== undefined && ganancia !== '0.00'){
		var porcGanancia 	= (parseFloat(ganancia) / 100) + 1;		
		pvp = parseFloat(pvp / porcGanancia);
	}			
	pvp = pvp.toFixed(3);	
	//----------------------			
	var campo =		'<div id="rutcondicion'+nextcondicion+'">';	
		campo += 	'<input id="condidart'+nextcondicion+'" name="condidart[]" type="text" value="'+idart+'" hidden="hidden"/>';
		campo += 	'<div class="bloque_10"><br><input type="button" value="-" onClick="dac_deleteArticuloCondicion('+nextcondicion+')" class="btmenos" style="background-color:#C22632;"></div>';
		campo += 	'<div class="bloque_6"><br>&nbsp;<strong>'+idart+ ' | </strong>'+nombre+'</div>';
		campo += 	'<div class="bloque_8"><label>PSL</label><input id="condprecioart'+nextcondicion+'" name="condprecioart[]" type="text" value="'+precioLista+'" maxlength="8" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onKeyUp="javascript:ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);"/></div>';
		campo += 	'<div class="bloque_8"><label>PVP</label><input type="text" value="$ '+pvp+'" readonly style="border:none;"/></div>';
		campo += 	'<div class="bloque_8"><label>Digitado</label><input id="condpreciodigit'+nextcondicion+'" name="condpreciodigit[]" type="text" value="'+precioDrog+'" maxlength="8" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onKeyUp="javascript:ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);"/></div>';
		campo += 	'<div class="bloque_9"><label>CantMin</label><input type="text" id="condcantmin'+nextcondicion+'" name="condcantmin[]" value="'+cantmin+'" maxlength="2" align="center"/></div>';
		campo += 	'<div class="bloque_8"><label>OAM</label>';
			campo += 	'<select id="condoferta" name="condoferta[]">';
			campo += 	'	<option value=""></option>';
			campo += 	'	<option value="alta" '; if(oam === "alta"){ campo += 'selected';} campo += '>Alta</option>';
			campo += 	'	<option value="modificado" '; if(oam === "modificado"){ campo += 'selected';} campo += '>Modifica</option>';
			campo += 	'	<option value="oferta" '; if(oam === "oferta"){ campo += 'selected';} campo += '>Oferta</option>';
			campo += 	'	<option value="altaoff" '; if(oam === "altaoff"){ campo += 'selected';} campo += '>AltaOff</option>';
			campo += 	'	<option value="modifoff" '; if(oam === "modifoff"){ campo += 'selected';} campo += '>ModifOff</option>';
			campo += 	'</select>';
		campo += 	'</div><div class="bloque_10" style="float:right;"><br><input id="btmas" type="button" value="+" onClick="dac_addBonificacion('+nextcondicion+', '+id+', '+idart+', 0, 0, 0, 0, 0, 0)" style="background-color:#3dc349;"></div><hr>';
		campo += 	'<div id="bonificacionArticulo'+nextcondicion+'"></div>';
		campo += 	'<hr style="border-bottom: 1px solid #117db6;"></div>';
	campo += 	'</div>';	
	
	$("#detalle_articulo").append(campo);
}		

// función del botón eliminar para quitar un div de artículos
function dac_deleteArticuloCondicion(nextcondicion){ //elimina
	"use strict";
	var elemento	=	document.getElementById('rutcondicion'+nextcondicion);
	elemento.parentNode.removeChild(elemento);
}

var nextbonificacion = 0;
function dac_addBonificacion(nextcondicion, id, idart, cant, b1, b2, d1, d2, idbonif){
	"use strict";
	idbonif = (idbonif===0) ? idbonif = '' : idbonif;
	cant = (cant===0) ? cant = '' : cant;
	b1 = (b1===0) ? b1 = '' : b1;
	b2 = (b2===0) ? b2 = '' : b2;
	d1 = (d1===0) ? d1 = '' : d1;
	d2 = (d2===0) ? d2 = '' : d2;

	nextbonificacion++;			
	var campo =		'<div id="rutbonificacion'+nextbonificacion+'" class="bloque_5" style="background-color:#DDD;">';
		campo += 	'<div class="bloque_8"><label>Cant.</label><input type="text" name="condcant'+idart+'[]" size="2" value="'+cant+'" maxlength="2"/></div>';		
		campo += 	'<div class="bloque_8"><label>Bonif 1</label><input type="text" name="condbonif1'+idart+'[]" size="2" value="'+b1+'" maxlength="2"/></div>';
		//campo += 	'<div class="bloque_10"><br><label>X</label></div>';	
		campo += 	'<div class="bloque_8"><label>Bonif 2</label><input type="text" name="condbonif2'+idart+'[]" size="2" value="'+b2+'" maxlength="2"/></div>';
		campo += 	'<div class="bloque_8"><label>Dto1</label> <input type="text" name="conddesc1'+idart+'[]" value="'+d1+'" maxlength="5" size="2" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onKeyUp="javascript:ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" style="text-align:center;"/></div>';
		campo += 	'<div class="bloque_8"><label>Dto2</label><input type="text" name="conddesc2'+idart+'[]" value="'+d2+'" maxlength="5" size="2" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onKeyUp="javascript:ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" style="text-align:right;"/></div>';
		campo += 	'<div class="bloque_9"><br><input type="button" value=" - " onClick="dac_deleteBonificacion('+nextbonificacion+')" style="background-color:#117db6;"></div>';
	campo += 	'<hr></div>';

	$("#bonificacionArticulo"+nextcondicion).append(campo);
}

function dac_deleteBonificacion(nextbonificacion){ //elimina
	"use strict";
	var elemento	=	document.getElementById('rutbonificacion'+nextbonificacion);
	elemento.parentNode.removeChild(elemento);
}

/**********************/
/* OnChangeLaboratorio*/ 
/**********************/
function dac_changeEmpresa(emp, lab) {
	"use strict";
	dac_changeLaboratorio(emp, lab);	
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getCuentas.php',					
		data:	{	empresa	:	emp, },	
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (result) {
						$('#box_cargando').css({'display':'none'});
						if (result){							
							document.getElementById('tablacuentas').innerHTML = result;
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

function dac_changeLaboratorio(emp, lab) {
	"use strict";
	dac_limpiarListaArticulos();				
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getArticulos.php',					
		data:	{	idlab	:	lab,
					idemp	:	emp},	
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (result) {
			$('#box_cargando').css({'display':'none'});
			if (result){		
				document.getElementById('tablaarticulos').innerHTML = result;
			} else {
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al consultar los registros.");
			}
		},
		error: function () { 
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al consultar los registros.");
		}								
	});
}

function dac_limpiarListaArticulos(){ 
	"use strict";
	$("#detalle_articulo").empty();
}

/******************************/
/*	Carga Condicion de Pago   */
var nextCondPago = 0;
function dac_cargarCondicionPago(condid, nombre, dias){
	"use strict";
	nextCondPago ++;
	var campo =	'<div id="rutconpago'+nextCondPago+'">';	
		campo +='<div class="bloque_2"><input id="condpagoid'+nextCondPago+'" name="condpagoid[]" type="text" value='+condid+' hidden="hidden"/>'+condid+' - '+nombre+' '+dias+'</div>';
		campo +='<div class="bloque_9"><input id="btmenos" class="btmenos" type="button" value=" - " onClick="dac_deleteCondPago('+condid+', '+nextCondPago+')"></div>';	
	campo +='<hr></div>';
	$("#detalle_condpago").append(campo);
	/*Oculto la fila */
	if(document.getElementById('condPago'+condid)){
		document.getElementById('condPago'+condid).style.display = "none";
	}
}	
// Botón eliminar div
function dac_deleteCondPago(id, nextCondPago) {
	"use strict";
	document.getElementById('condPago'+id).style.display = "table-row";
	var elemento	=	document.getElementById('rutconpago'+nextCondPago);
	elemento.parentNode.removeChild(elemento);
}

/*************************************/
/*	Cargas Cuentas de la Condicion   */
var nextcuenta = 0;
function dac_cargarCuentaCondicion(id, idcuenta, nombre){
	"use strict";
	nextcuenta++;
	var campo =	'<div id="rutcuenta'+nextcuenta+'">';
		campo +='<div class="bloque_2"><input id="cuentaid'+nextcuenta+'" name="cuentaid[]" type="text" value='+id+' hidden="hidden" />'+idcuenta+' - '+nombre+'</div>';
		campo +='<div class="bloque_9"><input id="btmenos" class="btmenos" type="button" value=" - " onClick="dac_deleteCuenta('+id+', '+nextcuenta+')"></div>';
	campo +='</div>';
	
	$("#detalle_cuenta").append(campo);

	/*Oculto la fila de la tabla para que no la repitan*/
	if(document.getElementById('cuenta'+id)){
		document.getElementById('cuenta'+id).style.display = "none";
	}
}		

// Botón eliminar para quitar un div de artículos
function dac_deleteCuenta(id, nextcuenta){
	"use strict";
	//Muestra la fila en la tabla para que la pueda volver a seleccionar//
	document.getElementById('cuenta'+id).style.display = "table-row";
	//elimina
	var elemento	=	document.getElementById('rutcuenta'+nextcuenta);
	elemento.parentNode.removeChild(elemento);
}