$(function(){"use strict"; $('#btsend').click(sendForm); });

/********************/
//	SUBIR INFORMES UNICOS //
/********************/
function sendForm(){
	"use strict";
	
	var archivos= document.getElementById("imagen");
	var archivo = archivos.files;
	archivos 	= new FormData();
	
	for(var i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); 
	}
	
	var idtipo 	= document.getElementById("tkidtipo").value;
	var tipo 	= document.getElementById("tktipo").value;
	var mensaje = document.getElementById("tkmensaje").value;
	var copia	= document.getElementById("tkcopia").value;
	archivos.append('idtipo'	, idtipo);
	archivos.append('tipo'		, tipo);
	archivos.append('mensaje'	, mensaje);
	archivos.append('copia'		, copia);
	
	$.ajax({
		url			:'/pedidos/soporte/tickets/nuevo/logica/update.ticket.php',
		type		:'POST',
		contentType	:false,
		data		:archivos,
		processData	:false,
		cache		:false,
		beforeSend	: 	function () {							
			$('#box_error').css({'display':'none'});	
			$('#box_cargando').css({'display':'block'});					
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		afterSend	:	function() {									
			$('#box_cargando').css({'display':'none'});											
		},
		success		: 	function(result) {			
			if(result){	
				$('#box_cargando').css({'display':'none'});		
				$('#box_error').css({'display':'block'});
				$("#msg_error").html(result);	
			} else {
				//reenvía al inicia
			}											
		},
	});
}