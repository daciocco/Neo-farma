$(function(){"use strict"; $('#enviar_informes').click(subirInformes); });
$(function(){"use strict"; $('#enviar_informesUnicos').click(subirInformesUnicos); });
$(function(){"use strict"; $('#sendImportFile').click(importTableFile); });

/********************/
//	SUBIR INFORMES //
/********************/
function subirInformes(){
	"use strict"; 
	var archivos 	= document.getElementById("informes");
	var archivo 	= archivos.files;
	archivos 		= new FormData();
	
	for(var i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); 
	}	
	
	var tipo 		= document.getElementById("tipo_informe").value;
	archivos.append('tipo', tipo);
	
	$.ajax({
		url:'/pedidos/informes/logica/subir.informes.php',
		type:'POST',
		contentType:false,
		data:archivos,
		processData:false,
		cache:false,
		beforeSend	: 	function () {							
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
				$('#box_error').css({'display':'block'});
				$("#msg_error").html(result);	
			}											
		},
	}).done(function(){
		if(tipo === "archivos/facturas/contrareembolso"){
			vaciarFactuasContra();
		}
	});
}

//Vaciar las facturas contrareeembolso
function vaciarFactuasContra(){		
	"use strict";
	$.ajax({
		url:'/pedidos/informes/logica/vaciar_factcontra.php',
		type:'POST',
		contentType:false,
		//data:archivos,
		processData:false,
		cache:false
	});
}


/********************/
//	SUBIR INFORMES UNICOS //
/********************/
function subirInformesUnicos(){
	"use strict"; 
	var archivos 	= document.getElementById("informesUnicos");
	var archivo 	= archivos.files;
	archivos 		= new FormData();
	
	for(var i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); 
	}	
	
	var tipo 		= document.getElementById("tipo_informeUnico").value;
	archivos.append('tipo', tipo);
	
	$.ajax({
		url:'/pedidos/informes/logica/subir.informesUnicos.php',
		type:'POST',
		contentType:false,
		data:archivos,
		processData:false,
		cache:false,
		beforeSend	: 	function () {							
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
				$('#box_error').css({'display':'block'});
				$("#msg_error").html(result);	
			}											
		},
	});
}

/********************/
function importTableFile(){
	"use strict"; 
	var archivos 	= document.getElementById("importTableFile");
	var archivo 	= archivos.files;
	archivos 		= new FormData();
	
	for(var i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); 
	}	
	
	var tipo 		= document.getElementById("importTable").value;
	archivos.append('tipo', tipo);
	
	$.ajax({
		url:'/pedidos/informes/logica/import.tableFile.php',
		type:'POST',
		contentType:false,
		data:archivos,
		processData:false,
		cache:false,
		beforeSend	: 	function () {							
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
				$('#box_error').css({'display':'block'});
				$("#msg_error").html(result);	
			}											
		},
	});
}