/****************************/
/*	Carga Datos de Cuentas	*/
/****************************/
function dac_CargarCuentas(idEmpresa, condidcuentas) {	
	"use strict";
	if(!condidcuentas){ condidcuentas = '';}

	$.ajax({
		type	: 	'POST',
		cache	:	false,
		url 	: 	'logica/ajax/getCuentas.php',					
		data	:	{	empresa			:	idEmpresa,
						condidcuentas 	: 	condidcuentas},		
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');

			$('#box_cargando2').css({'display':'block'});
			$("#msg_cargando2").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},		
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});		
						if (resultado){								
							document.getElementById('tablacuenta').innerHTML = resultado;
							$('#box_cargando2').css({'display':'none'});	
						} else {
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_cargando2').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al intentar consultar los registros.");	
		},								
	});
	document.getElementById('pwidcta').value		= '';
	document.getElementById('pwnombrecta').value 	= '';
}

/****************************************/
/*  Carga Datos de Cuentas al Pedido	*/
/****************************************/
function dac_cargarDatosCuenta(idcli, nombre, direccion, observacion, condpago) {
	"use strict";
	document.getElementById('pwidcta').value 		=	(idcli)		?	idcli	:	'';
	document.getElementById('pwnombrecta').value 	= 	(nombre) 	?	nombre 	: 	'';	

	if(observacion){
		document.getElementById('msg_atencion').innerHTML 			= observacion;
		document.getElementById('box_observacion').style.display 	= "inline";			
	} else {
		document.getElementById('box_observacion').style.display 	= "none";
	}	
	
	if(condpago){
		//Haces referencia al elemento para no recorrer el DOM varias veces
		var sel = document.getElementById("condselect");
		for (var i = 1; i < sel.length; i++) {
			//  Aca haces referencia al "option" actual
			var opt = sel[i];
			if(opt.value === condpago) {
				document.getElementById('condselect').selectedIndex =  i;
			}
		}
	}
}

/********************************/
/*	Carga Condiciones de Pago	*/
/********************************/
function dac_CargarCondicionesPago(condpago){	
	"use strict";
	if(!condpago){ condpago = ''; }
	var condselect = document.getElementById("condselect");	
	do  { 
		condselect.remove(condselect.length-1);
	} while (condselect.length > 1);			
	var variable		=	new	Option("","0");
	var cosa			=	document.forms['fmPedidoWeb'].elements['condselect'];
	cosa.options[0]	=	variable;
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getCondicionesPago.php',				
		data:	{	condicion	:	condpago },		
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},		
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});	
						if (resultado){		
							var json = eval(resultado);	
							for(var i = 0; i < json.length; i++){
								variable	=	new	Option(json[i].nombre+" - "+json[i].dias+" - ["+json[i].porc+"%]", json[i].condcodigo);									
								cosa		=	document.forms['fmPedidoWeb'].elements['condselect'];
								cosa.options[i+1]	=	variable;
							}	
						} else {
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
function dac_selectChangeEmpresa(idEmpresa) {
	"use strict";
	var idEmp	=	(idEmpresa) ? idEmpresa : 1;	
	var idlab	=	document.getElementById('labselect').options[document.getElementById('labselect').selectedIndex].value;
	var idPropuesta		=	document.getElementById('pwidpropuesta').value;

	dac_LimpiarArticulos();
	dac_CargarArticulos(idEmp, idlab);

	if (idPropuesta === '0'){
		dac_CargarCuentas(idEmp);
		document.getElementById('pwidcta').value		= '';
		document.getElementById('pwnombrecta').value 	= '';
		dac_CargarCondicionesComerciales(idEmp, idlab);	
	}
}


/****************************************/
/*	Carga Datos de Artículos según Lab	*/
/****************************************/
function dac_selectChangeLaboratorio(idlab) {
	"use strict";
	var idLaboratorio	=	(idlab) ? idlab : 1;	
	var idEmpresa		=	document.getElementById('empselect').options[document.getElementById('empselect').selectedIndex].value;
	dac_LimpiarArticulos();
	dac_CargarArticulos(idEmpresa, idLaboratorio);
	dac_CargarCondicionesComerciales(idEmpresa, idLaboratorio);				
}


/**********************************/
/* Cargar Condiciones Comerciales */
/**********************************/
function dac_CargarCondicionesComerciales(idEmpresa, idlab){
	"use strict";
	var idLaboratorio = (idlab) ? idlab : 1;
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getCondiciones.php',					
		data:	{	empresa		:	idEmpresa,
					laboratorio	:	idLaboratorio,
					},	
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},			
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});	
						if (resultado){		
							document.getElementById('tablacondiciones').innerHTML = resultado;
						} else {
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al consultar registros.");	
		},								
	});
}


/********************/
/*	Carga Artículos */
/********************/
function dac_CargarArticulos(idEmpresa, idlab, condicion){
	"use strict";
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getArticulos.php',					
		data:	{	laboratorio	:	idlab,
					empresa		:	idEmpresa,
					condicion	:	condicion,
				},				
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');

			$('#box_cargando3').css({'display':'block'});
			$("#msg_cargando3").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});	
						if (resultado){														
							document.getElementById('tablaarticulos').innerHTML = resultado;
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
			$("#msg_error").html("Error al consultar los artículos.");	
		},								
	});	
}

/************************************/
/*	Limpia Artículos del Pedido		*/
/************************************/
function dac_LimpiarArticulos(){ 
	"use strict";
	$('#pwsubtotal').html('');
	$("#lista_articulos2").empty();
}

//-----------------------------
//	Carga Artículo al Pedido	
var nextinput = 0;
function dac_CargarArticulo(idart, nombre, precio, b1, b2, desc1, desc2, cant){
	"use strict";
	nextinput++;
	var campo =		'<div id="rut'+nextinput+'">';		
			campo += 	'<input id="pwidart'+nextinput+'" name="pwidart[]" type="text" value="'+idart+'" hidden/>';
			campo += 	'<div class="bloque_1"><strong> Art&iacute;culo '+idart+ '</strong></br>'+nombre+' <strong><div id="artalert'+idart+'" align="center"></div></strong></div>';
			campo += 	'<div class="bloque_9"><br><input id="btmenos" type="button" value="-" onClick="dac_eliminarArt('+nextinput+')"  style="background-color:#C22632;"></div>';
			campo += 	'<div class="bloque_8"><strong> Cantidad </strong> <input id="pwcant'+nextinput+'" name="pwcant[]" type="text" value="'+cant+'" onblur="javascript:dac_CalcularSubtotal()"  maxlength="5"/></div>';
			campo += 	'<div class="bloque_7"><strong> Precio </strong> <input id="pwprecioart'+nextinput+'" name="pwprecioart[]" type="text" value="'+precio+'" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value); javascript:dac_CalcularSubtotal()"  maxlength="6"/> </div>';
			campo += 	'<div class="bloque_8"><strong>Bonif 1</strong> <input id="pwbonif1'+nextinput+'" name="pwbonif1[]" type="text" value="'+b1+'" maxlength="2"/></div>';
			campo += 	'<div class="bloque_8"><strong>Bonif 2 </strong><input id="pwbonif2'+nextinput+'" name="pwbonif2[]" type="text" value="'+b2+'" maxlength="2"/> </div>';		
			campo += 	'<div class="bloque_8"><strong>Desc 1 </strong> <input id="pwdesc1'+nextinput+'" name="pwdesc1[]" type="text" value="'+desc1+'" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onkeyup="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onblur="javascript:dac_CalcularSubtotal()" maxlength="5"/></div>';
			campo += 	'<div class="bloque_8"><strong>Desc 2 </strong> <input id="pwdesc2'+nextinput+'" name="pwdesc2[]" type="text" maxlength="5" value="'+desc2+'" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onkeyup="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onblur="javascript:dac_CalcularSubtotal()"/></div>';
			campo += 	'<hr style="border-bottom: 1px solid #117db6;">';
		campo += 	'</div>';	

	$("#lista_articulos2").append(campo);	
}		

/************************************************/
/* 		Elimina un Artículo del Pedido			*/
/************************************************/
function dac_eliminarArt(id){
	"use strict";
	var elemento = document.getElementById('rut'+id);
	elemento.parentNode.removeChild(elemento);
	dac_CalcularSubtotal();
}

/********************************************/
/* Cargar Cuentas según Condición Comercial */
/********************************************/
function dac_CargarCondicionComercial(idEmpresa, idlab, condicion, condtipo, condidcuentas, condpago, nombre, observacion) {
	"use strict";
	dac_LimpiarArticulos();
	//borra los datos de cada tabla
	document.getElementById('tablacuenta').innerHTML 	= "";
	document.getElementById('tablaarticulos').innerHTML = "";

	//carga los registros de la condición correspondiente
	dac_CargarCondicionesPago(condpago);
	dac_CargarArticulos(idEmpresa, idlab, condicion);
	dac_CargarCuentas(idEmpresa, condidcuentas);	
	document.getElementById('msg_atencion').innerHTML 			= condtipo.toUpperCase()+' '+observacion;
	document.getElementById('box_observacion').style.display 	= "inline";				
	document.getElementById('pwidcondcomercial').value 			= condicion;			
}

/****************************************/
/*	Calcula el Subtotal del Pedido		*/
/****************************************/
function dac_CalcularSubtotal(){
	"use strict";
	var cantArts	=	document.getElementsByName('pwidart[]').length;	//cantidad de artículos	
	var subtotal	=	0;

	for(var i = 0; i < cantArts; i++){
		var pwprecio 	=	document.getElementsByName("pwprecioart[]").item(i).value;
		var pwcant 		=	document.getElementsByName("pwcant[]").item(i).value;

		var pwdesc1		=	document.getElementsByName("pwdesc1[]").item(i).value;
		var pwdesc2		=	document.getElementsByName("pwdesc2[]").item(i).value;

		var total		=	pwcant * pwprecio;	
		var totalD1		=	total - (total * pwdesc1/100);
		var totalD2		=	totalD1 - (totalD1 * pwdesc2/100);
		//totalIva	=	totalD2 + (totalD2 * (iva/100));	

		subtotal	+=	totalD2;
	}			
	document.getElementById("pwsubtotal").style.display	=	'block';
	$('#pwsubtotal').html('<div class="bloque_3"><strong>Subtotal: $ '+subtotal.toFixed(3)+ '</strong> (No refleja IVA)</div>');
}

$(document).ready(function() {
	"use strict";
	//previene el Enter
	$('textarea').keypress(function(event) {		
		if (event.keyCode === 13) {
			event.preventDefault();
		}
	});
	
	$("#btsendPedidoCadena").click(function () {
		var url		= '/pedidos/pedidos/logica/ajax/update.pedidoCadena.php';
		var form	= 'form#fmPedidoWebCadena';
		//dac_sendForm(form, url);
		var formData = new FormData($(form)[0]);
		$.ajax({
				url			: url,
				type		: 'POST',
				data		: formData,	
				beforeSend	: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
					$("#btsend").hide(100);
				},			
				success		: function(result) {					
					if (result){							
						$('#box_cargando').css({'display':'none'});						
						if (result.replace("\n","") === '1'){
							//Confirmación	
							$('#box_confirmacion').css({'display':'none'});
							window.history.back();
						} else {
							//El pedido No cumple Condiciones
							var scrolltohere = "#box_error";
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
						}					
						$('html,body').animate({
							scrollTop: $(scrolltohere).offset().top
						}, 2000);
						$("#btsend").show(100);									
					}
				},
				error: function () {
					$('#box_cargando').css({'display':'none'});	
					var scrolltohere = "#box_error";
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error en el proceso");	
					$("#btsend").show(100);	
				},	
				cache		: false,
				contentType	: false,
				processData	: false
			});	
		
		
		
	});	
	
	$("#btsend").click(function () {	// desencadenar evento cuando se hace clic en el botón
		var cadena  = $("#cadena").prop('checked');
		var form	= 	"";
		var url		=	"";
		if(cadena){			
			url 	= '/pedidos/pedidos/logica/ajax/controlCadena.php';
			form	= "form#fmPedidoWeb";			
			var formData = new FormData($(form)[0]);	
			$.ajax({
				url			: url,
				type		: 'POST',
				data		: formData,	
				beforeSend	: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
					$("#btsend").hide(100);
				},			
				success		: function(result) {					
					if (result){							
						$('#box_cargando').css({'display':'none'});						
						if (result.replace("\n","") === '1'){
							//Confirmación	
							$('#box_confirmacion').css({'display':'none'});
							$('#fmPedidoWeb').attr('action', '/pedidos/pedidos/editar.cadena.php');
							$('#fmPedidoWeb').submit();
						} else {
							//El pedido No cumple Condiciones
							var scrolltohere = "#box_error";
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
						}					
						$('html,body').animate({
							scrollTop: $(scrolltohere).offset().top
						}, 2000);
						$("#btsend").show(100);									
					}
				},
				error: function () {
					$('#box_cargando').css({'display':'none'});	
					var scrolltohere = "#box_error";
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error en el proceso");	
					$("#btsend").show(100);	
				},	
				cache		: false,
				contentType	: false,
				processData	: false
			});	
			return false;			
		} else {
			url		= '/pedidos/pedidos/logica/ajax/update.pedido.php';
			form	= "form#fmPedidoWeb";
			dac_sendForm(form, url);
		}
	});
});
