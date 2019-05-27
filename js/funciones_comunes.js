/** jQuery JavaScript Library ;
 * Date: 01-11-2014
 * Funciones Comunes para Extranet
*/ 

/****************************************
//FUNCIONES QUE CONTROLA QUE LOS PRECIOS SE CARGUEN CON PUNTO Y NO CON COMA
/*****************************************/
function ControlComa(id, precio) {
	"use strict";
	if(precio.indexOf(',') !== -1){
		document.getElementById(id).value = "";
		alert("El valor debe llevar punto en vez de coma");
	}
}

// Redondear Números en JAvascript
function dac_Redondeo(nro, decimales){
	"use strict";
	var flotante = parseFloat(nro);
	var resultado = Math.round(flotante * Math.pow(10,decimales)) / Math.pow(10,decimales);
	return resultado;
}

/****************************************
//FUNCIONES QUE CONTROLA QUE LOS PRECIOS SE CARGUEN CON PUNTO Y NO CON COMA
/*****************************************/
function dac_ControlNegativo(id, precio) {
	"use strict";
	if(precio < 0){
		document.getElementById(id).value = "";
		alert("El valor debe ser mayor a cero");
	}
}

//----------------------//
/*	VALIDAR CAMPO FECHA	*/
//Ver de usar en los textos de observación de cuentas etc
function dac_ValidarCaracteres(e){
	"use strict";
	var tecla = (document.all) ? e.keyCode : e.which;	
	if (tecla===13){ return false; } //Enter
    if (tecla===8){ return true; }
	var patron =/[\^$*+?=!:|\\/()\[\]{}¨º~#·&'¡¿`´><ª¬]/; //var patron =/[A-Za-z0-9]/;	
    var te = String.fromCharCode(tecla);
    if (patron.test(te)) {
     //alert('No puedes usar ese caracter');
      return false;
    }
}

/************************/
/*	VALIDAR CAMPO FECHA	*/
/************************/
//Usado para Recibos en Rendiciones
function dac_ValidarCampoFecha(id, valor, estado){
	"use strict";
	var fecha	=	valor.replace(/^\s+/,'').replace(/\s+$/,'');
	var long	=	fecha.length;
	switch(estado){
		case "KeyUp":
			var caracter 	= valor.charAt(long-1);
			if (long === 3 || long === 6){					
				if (caracter !== "-"){				
					alert ("El valor de fecha debe tener el formato dd-mm-aaaa");
					document.getElementById(id).value = valor.substring(0, valor.length-1);	
				}
			} else {
				if (isNaN(caracter)){
					alert ("El valor ingresado debe ser un n\u00famero");
					document.getElementById(id).value = valor.substring(0, valor.length-1);
				} else {
					if (long === 1 && caracter > '3'){
						alert ("Error en el ingreso del d\u00eda."); 
						document.getElementById(id).value = valor.substring(0, valor.length-1);}
					if (long === 4 && caracter > '1'){
						alert ("Error en el ingreso del mes."); 
						document.getElementById(id).value = valor.substring(0, valor.length-1);}
					if (long === 5 && caracter > '2' && (valor.charAt(long-2)) === 1){
						alert ("Error en el ingreso del mes."); 
						document.getElementById(id).value = valor.substring(0, valor.length-1);}
					if (long === 7 && caracter !== '2'){ //obliga que sea año 2000
						alert ("Error en el ingreso del a\u00f1o."+long); 
						document.getElementById(id).value = valor.substring(0, valor.length-1);}
					if (long === 8 && caracter !== '0'){ //obliga que sea siglo 1
						alert ("Error en el ingreso del a\u00f1o."+long); 
						document.getElementById(id).value = valor.substring(0, valor.length-1);}
				}
			}
			break;
		case "Blur":
			if(long !== 10 && long !== 0){
				alert('La fecha est\u00e1 incompleta. Vuelva a ingresarla');
				document.getElementById(id).value = '';
			}
			break;
	}
}

/************************************/
/*		 IMPRIMIR MUESTRA			*/
/************************************/
function dac_imprimirMuestra(muestra){
	"use strict";
	var ficha	=	document.getElementById(muestra);
	var ventimp=window.open(' ','popimpr');
	ventimp.document.write(ficha.innerHTML);
	ventimp.document.close();
	ventimp.print();
	ventimp.close();
}

/************************************/
/*	 LIMITA CANTIDAD DE CARACTERES	*/
/************************************/
function dac_LimitaCaracteres(elEvento, ID, maximoCaracteres) { 
	"use strict";
	//limita la cantidad de caracteres en cada onkeypress
	var elemento = document.getElementById(ID);
	// Obtener la tecla pulsada 
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	// Permitir utilizar las teclas con flecha horizontal
	if(codigoCaracter === 37 || codigoCaracter === 39) { return true; }
	// Permitir borrar con la tecla Backspace y con la tecla Supr.
	if(codigoCaracter === 8 || codigoCaracter === 46) { dac_ActualizaCaracteres(elemento, maximoCaracteres); /*return true;*/ }
	else /*if(elemento.value.length >= maximoCaracteres ) { return false;
	} else { return true; }*/ 
	{dac_ActualizaCaracteres(elemento, maximoCaracteres);}
}

/*************************************/
/*	ACTUALIZA CANTIDAD DE CARACTERES */
/*************************************/
function dac_ActualizaCaracteres(elemento, maximoCaracteres) { 
	"use strict";
	//actualiza cantidad de caracteres por cada onkeyup
	//var elemento = document.getElementById(ID);
	//lo siguiente es donde se notificará la cantidad de caracteres 
	var info = document.getElementById("msg_informacion");
	info.style.display = "inline";
	document.getElementById('box_informacion').style.display = "inline";
	if(elemento.value.length >= maximoCaracteres ) {
		info.innerHTML = "Sobran "+(elemento.value.length-maximoCaracteres)+" caracteres de "+maximoCaracteres+" permitidos";
	} else {
		info.innerHTML = "Quedan "+(maximoCaracteres-elemento.value.length)+" caracteres";
	}
}

/******************/
/*	CONTROL CUIT  */
/******************/
function dac_validarCuit(cuit) {
	"use strict";
	cuit = cuit.replace(/-/g, "");
	if(cuit.length !== 11) {return false;} //alert("El CUIT es incorrecto.");
	var acumulado 	= 0;
	var digitos 	= cuit.split("");
	var digito		= digitos.pop();

	for(var i = 0; i < digitos.length; i++) {
		acumulado += digitos[9 - i] * (2 + (i % 6));
	}

	var verif = 11 - (acumulado % 11);
	if(verif === 11) { verif = 0; //alert("El CUIT es incorrecto.");
	} else if(verif === 10) {verif = 9;} //alert("El CUIT es incorrecto.");

	return digito === verif; //{alert("CUIT correcto");}
}

/**********************/
/*	SCROLL SUBIR WEB  */
/**********************/
var arriba;
function subir() {
	"use strict";
	if (document.body.scrollTop !== 0 || document.documentElement.scrollTop !== 0) {
		window.scrollBy(0, -15);
		arriba = setTimeout('subir()', 10);
	} else { 
		clearTimeout(arriba);
	}
}

/**********************/
/*	ELIMINAR ARCHIVO  */
/**********************/
function dac_fileDelete(id, url, direccion) {
	"use strict";
	if(confirm('\u00BFEst\u00e1 seguro que desea ELIMINAR EL ARCHIVO?')){
		$.ajax({
			url: url,
			type:'POST',
			data:{direccion	: direccion,},
			beforeSend	: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},		
			success: function(result){	
				$('#box_cargando').css({'display':'none'});	
				if(result) {				
					/* oculto el registro del id donde se ve el archivos */
					$('#box_confirmacion').css({'display':'block'});
					$("#msg_confirmacion").html('Los datos fueron eliminados');
					document.getElementById(id).style.display = "none";
				} else {
					$('#box_error').css({'display':'block'});
					$("#msg_error").html(result);
				}				
			},
			error: function () {
				$('#box_cargando').css({'display':'none'});	
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error en el proceso");
			},	

		});
	}
}

// Botón eliminar para quitar un div de artículos
function dac_deleteCuentaTransferRelacionada(id, nextCuentaTransfer){
	"use strict";
	var elemento	=	document.getElementById('rutcuenta'+nextCuentaTransfer);
	elemento.parentNode.removeChild(elemento);
}

/*******************/
/* Select  Cuentas */ 
/*******************/
function dac_changeStatus(url, id, pag) {	
	"use strict";
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	url,					
		data:	{	id	:	id,
					pag	:	pag
				},
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
		},
		success : 	function (result) {			
			if (result){
				$('#box_cargando').css({'display':'none'});
				if (result.replace("\n","") === '1'){
					$('#box_confirmacion').css({'display':'block'});
					$("#msg_confirmacion").html('Cambio de estado realizado.');
					window.location.reload(true);
				} else { 	
					$('#box_error').css({'display':'block'});
					$("#msg_error").html(result);
				}
			}
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al intentar consultar el estado.");	
		},								
	});		
}

//**************//
//	Llamadas	//
//**************//
function dac_registrarLlamada(origen, idorigen, nroRel, telefono, contesta, resultado, observacion){
	"use strict";
	$.ajax({
		type: "POST",						
		url: "/pedidos/llamada/logica/update.llamada.php",	
		data: {	origen		:	origen, 
				idorigen	:	idorigen,
				nroRel		:	nroRel,
				telefono	:	telefono,
				tiporesultado:	contesta,
				resultado	:	resultado,
				observacion	:	observacion
				},
		success: function(result){	
			if(result){	
				if(result.replace("\n","") !== '1'){ 
					alert(result); //
				} else {
					//Cierra el formulario y registra la llamada como No contesta							
					$("#dialogo").dialog("close");
				}
			}
		}			
	});
}

function dac_incidencia(onoff) {
	"use strict";
	if(onoff === 1){
		$('#incidencia').css("display", "inline");
		$( "#dialogo" ).css("height", 320);
	} else {
		$('#incidencia').css("display", "none");
		$( "#dialogo" ).css("height", 180);
	}
}

function dac_reprogramar(origen, idorigen, nroRel, contesta, telefono){
	"use strict";
	$("#reprogramar").empty();
	$("#reprogramar").dialog({
		modal: true, 
		title: 'Reprogramar llamada', 
		zIndex: 100, 
		autoOpen: true,
		resizable: false,
		width: 380,
		height: 420,
		buttons: {
			Aceptar : function () {
				var fecha_reprog	=	$( "#fecha_reprog" ).val();
				var hora_reprog		=	$( "#hora_reprog" ).val();
				var motivo			=	$( "#motivo" ).val();
				var telefono2		=	$( "#telefono2" ).val();
				var descripcion2	=	$( "#descripcion2" ).val();

				if(fecha_reprog.trim() === "" || fecha_reprog.length < 1){
					alert("Indique una fecha para rellamar");
				} else if (hora_reprog.trim() === "" || hora_reprog.length < 1) {
					alert("Indique un horario");
				} else if (motivo.trim() === "" || motivo.length < 1) {
					alert("Indique el motivo de rellamada");
				} else if(telefono2.trim() === "" || telefono2.length < 1){
					alert("Indique el tel\u00e9fono a rellamar");
				} else {
					dac_registrarLlamada(origen, idorigen, nroRel, telefono2, contesta, 'rellamar', descripcion2);		
					//CARGA A LA AGENDA COOMO RELLAMAR//
					//dac_updateEvent();
					var fechaInicio = fecha_reprog.split('/');					
					var fechaFin = new Date(fechaInicio[2]+"/"+fechaInicio[1]+"/"+fechaInicio[0]+" "+hora_reprog);
					//var dias = fechaFin.getDate();
    				//fechaFin.setDate(dias + 1);	
					var horas = fechaFin.getHours();
					fechaFin.setHours(horas + 1);
					var hora = fechaFin.getHours();
					var minuto = fechaFin.getMinutes();
					var dia = fechaFin.getDate();					
					var mes = fechaFin.getMonth() + 1;
					mes 	= (mes < 10) ? ("0" + mes) : mes;
 					dia 	= (dia < 10) ? ("0" + dia) : dia;
					hora 	= (hora < 10) ? ("0" + hora) : hora;
 					minuto 	= (minuto < 10) ? ("0" + minuto) : minuto;
					//alert(mes+'/'+dia+'/'+fechaFin.getFullYear()+" "+hora+":"+minuto);
					var url = window.location.origin+'/pedidos/cuentas/editar.php?ctaid='+idorigen;			var eventData;

					eventData = {
						id		:	0,
						color	:	"ffee00", //Amarillo
						title	:	"Rellamar Prospecto",
						url		: 	url,
						texto	:	"Rellamar Prospecto ID "+idorigen+". "+descripcion2,
						start	:	fechaInicio[1]+'/'+fechaInicio[0]+'/'+fechaInicio[2]+' '+hora_reprog,
						end		:	mes+'/'+dia+'/'+fechaFin.getFullYear()+" "+hora+":"+minuto,
						constraint : "Rellamar",
					};

					$.ajax({
						type: "POST",
						cache:	false,						
						url: "/pedidos/agenda/ajax/setEvents.php",
						data: {	eventData : eventData,}, 
						success: function(result){	
							if(result){
								if(isNaN(result)){ 
									alert("No se pudo registrar el rellamado en la agenda");
								}
							}
						},
						error: function () {
							alert("Error. No se pudo registrar el rellamado en la agenda");
						},							
					});						

					$("#this").dialog("close");				
					$("#reprogramar").dialog("close");
				}
			},
			Cerrar : function () {	
				$("#reprogramar").dialog("close"); 
			}
		},
	});

	var fecha	=	new Date();
	var h		=	dac_addZero(fecha.getHours());
	var m		=	dac_addZero(fecha.getMinutes());
	var hm		=	h+":"+m;	

	function dac_addZero(i) {
		if (i < 10) { i = "0" + i; }
		return i;
	}
	var contenido	= 
		'<form>'+
			'<div class="bloque_6"><label>Fecha</label><input id="fecha_reprog" type="text" name="fecha_reprog" readonly></div>'+
			'<div class="bloque_7"><label>Hora</label><input id="hora_reprog" type="text" class="time" value="'+hm+'" maxlength="5"/></div>'+
			'<div class="bloque_6"><label>Tel&eacute;fono: </label><input id="telefono2" name="telefono2" type="text" value="'+telefono+'" maxlength="25"></div>'+
			'<div class="bloque_1"><label>Motivo:</label><input id="motivo" name="motivo" type="text" value="'+contesta+'"></div>'+ 				
			'<div class="bloque_1"><label>Comentario:</label><textarea id="descripcion2" name="descripcion2" type="text" maxlength="200"></textarea></div>'+
		'</form>';			

	$(contenido).appendTo('#reprogramar');		
	$( "#fecha_reprog" ).datepicker({ inline: true });
    $( "#fecha_reprog" ).datepicker( "option", "dateFormat", "dd/mm/yy" );

	$('#hora_reprog').timepicker({ 
		'timeFormat'		: 'H:i',
		'disableTimeRanges'	: [
			['0', '7:30'],
			['18:30', '24:00']
		]		
	});
}	

//**********************//
// Envío de Formulario	//
//**********************//
function dac_sendForm(form, url){
	"use strict";
	var scrolltohere = "";
	var formData = new FormData($(form)[0]);	
	$.ajax({
		url			: url,
		type		: 'POST',
		data		: formData,	
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			$("#btsend").hide(100);
		},			
		success		: function(result) {					
			if (result){							
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
				}					
				$('html,body').animate({
					scrollTop: $(scrolltohere).offset().top
				}, 2000);
				$("#btsend").show(100);									
			}
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			scrolltohere = "#box_error";
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error en el proceso");	
			$("#btsend").show(100);	
		},	
		cache		: false,
		contentType	: false,
		processData	: false
	});	
	return false;
}	

//******************//
// GEOLOCALIZACIÓN	//

/*	GOOGLE MAPS  */
function dac_showMap(lat, long) {
	"use strict";
	var myCenter	=	new google.maps.LatLng(lat, long);

	function dac_initiarMap() {
		var mapProp = {
			center:myCenter,
		  	zoom:15,
		  	mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);

		var marker=new google.maps.Marker({
		  position:myCenter,
		});
		marker.setMap(map);
	}
	google.maps.event.addDomListener(window, 'load', dac_initiarMap);

}

function dac_refreshMap(lat, long) {
	"use strict";
	var myCenter	=	new google.maps.LatLng(lat, long);
	google.maps.event.addDomListener(window, 'load');							
	var mapProp = {
	  center:myCenter,
	  zoom:15,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	var marker=new google.maps.Marker({
	  position:myCenter,
	});
	marker.setMap(map);
}

function dac_getLatitudLongitud(provincia, localidad, direccion, nro){
	"use strict";
	//controles de referencias	
	$('#box_confirmacion').css({'display':'none'});
	$('#box_error').css({'display':'none'});
	$('#box_cargando').css({'display':'block'});
	$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');

	if(provincia === "Provincia..."){
		$('#box_cargando').css({'display':'none'});	
		$('#box_error').css({'display':'block'});		
		$("#msg_error").html("Debe indicar una provincia");
		//alert("Debe indicar una provincia"); 
		return false;
	}

	if(direccion === ""){
		$('#box_cargando').css({'display':'none'});	
		$('#box_error').css({'display':'block'});
		$("#msg_error").html("Debe indicar una direcci&oacute;n");
		//alert("Debe indicar una dirección")
		return false;
	}

	if(nro === ""){
		$('#box_cargando').css({'display':'none'});	
		$('#box_error').css({'display':'block'});
		$("#msg_error").html("Debe indicar un n&uacute;mero de direcci&oacute;n");
		//alert("Debe indicar un número de dirección"); 
		return false;
	}

	// If adress is not supplied, use default value 'Buenos Aires, Argentina'	
	var address  = provincia+", "+localidad+", "+direccion+" "+nro;
	// Initialize the Geocoder
	var geocoder = new google.maps.Geocoder();		
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status === google.maps.GeocoderStatus.OK) {				
			var latitud		= results[0].geometry.location.lat();			
			var longitud 	= results[0].geometry.location.lng();				
			//alert('La longitud es: ' + longitud + ', la latitud es: ' + latitud);	
			document.getElementById("longitud").value = longitud;
			document.getElementById("latitud").value = latitud;			
			dac_refreshMap(latitud, longitud);
			$('#box_cargando').css({'display':'none'});	
		} else {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			switch(status){
				case 'ZERO_RESULTS': 					
					$("#msg_error").html("No se encuentra la direcci&oacute;n indicada.");
					break;
				case 'ERROR': 
					$("#msg_error").html("Hubo un problema con los servidores de Google.");
					break;
				case 'INVALID_REQUEST': 
					$("#msg_error").html("La direcci&oacute;n no es válida.");
					break;
				case 'OVER_QUERY_LIMIT': 
					$("#msg_error").html("La p&aacute;gina web ha superado el l&iacute;mite de solicitudes en un per&iacute;íodo muy corto de tiempo.");
					break;
				case 'REQUEST_DENIED': 
					$("#msg_error").html("La p&aacute;gina web no puede utilizar el geocodificador.");
					break;
				default: 
					$("#msg_error").html("Geocoding fallo debido a : " + status);
					break;
			}
		}
	});
}

//**********************************//
//	Manejo de visión de imágenes	//
//**********************************//
function dac_ShowImgZoom(src){	
	"use strict";
	$('#img_ampliada').fadeIn('slow');			
	$('#img_ampliada').css({
		'width': '100%',
		'height': '100%',
		'left': ($(window).width() / 2 - $(img_ampliada).width() / 2) + 'px', 
		'top': ($(window).height() / 2 - $(img_ampliada).height() / 2) + 'px'
	});
	
	document.getElementById("imagen_ampliada").src = src;	
	$(window).resize();		
	return false;
}

//**********************************//
function dac_CloseImgZoom(){
	"use strict";
	$('#img_ampliada').fadeOut('slow');		
	return false;
}		
//**********************************//

$(window).resize(function(){
	if($('#img_ampliada').length){
		$('#img_ampliada').css({
			'width': '100%',
			'height': '100%',
			'left': ($(window).width() / 2 - $(img_ampliada).width() / 2) + 'px', 
			'top': ($(window).height() / 2 - $(img_ampliada).height() / 2) + 'px'
		});
		
		$('#pdf_ampliado').css({
			'width': '100%',
			'height': '100%',
			'left': ($(window).width() / 2 - $(img_ampliada).width() / 2) + 'px', 
			'top': ($(window).height() / 2 - $(img_ampliada).height() / 2) + 'px'
		});
	}
});

//-------------------------------
//	 Exportar archivo Excel
function exportTableToExcel(table, filename){
	var downloadLink;			
	// Specify file name
	filename = filename ? filename+'.xls' : 'excel_data.xls';

	var uri 	= 'data:application/vnd.ms-excel;base64,'
	, template 	= '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'			
	, base64 	= function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
	, format 	= function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }

	if (!table.nodeType) { table = document.getElementById(table); }
	var ctx = { worksheet: filename || 'Worksheet', table: table.innerHTML }

	// Create download link element
	downloadLink 			= document.createElement("a");
	document.body.appendChild(downloadLink);
	// Create a link to the file
	downloadLink.href 		= uri + base64(format(template, ctx));
	// Setting the file name
	downloadLink.download 	= filename;
	//triggering the function
	downloadLink.click();			
	//window.location.href = uri + base64(format(template, ctx))
}