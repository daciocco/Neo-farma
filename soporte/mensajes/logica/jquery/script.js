$(function(){"use strict"; $('#cerrarTicket').click(sendForm); });

/********************/
//	SUBIR INFORMES UNICOS //
/********************/
function sendForm(){
	"use strict";
	var tkid = $('#tkid').val();
	$.ajax({
		url			:'/pedidos/soporte/mensajes/logica/close.ticket.php',
		type		:'POST',
		data		: { tkid : tkid, },
		contentType	:false,
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