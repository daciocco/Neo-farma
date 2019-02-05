$(document).ready(function() {
	$("#btnSendTransfer").click(function () {
		dac_Controlar_Envio();		
		return false;
    });	
	
	function dac_Controlar_Envio() {
		var	ctaId			= 	$('input[name="ctaid"]:text').val();	
		var	tipo			= 	$('select[name="tiposelect"]').val();
		var nroTransfer 	= 	[]; //new Array();
		var nroDrogueria 	= 	[]; //new Array();
		
		//recorre arrays
		$('input[name="cuentaIdTransfer[]"]:text').each(function(i) 	{ nroTransfer[i] 	=	$(this).val();});
		$('input[name="cuentaId[]"]:text').each(function(i) 			{ nroDrogueria[i] 	= 	$(this).val();});
		
		dac_ControlDelPedido();	
		function dac_ControlDelPedido(){	
			$.ajax({
				type: "POST",
				url	: '/pedidos/cuentas/logica/update.transfer.relacionado.php',
				data: {	ctaId			:	ctaId,
					    tipo			:	tipo,
						cuentaIdTransfer:	nroTransfer,
						cuentaIdDrog	:	nroDrogueria,
					  },
				beforeSend: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});					
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
					$("#btnSendTransfer").hide(100);
				},
				success: function(result) {					
					if (result){
						if(result.replace("\n","") === '1'){		
								$('#box_cargando').css({'display':'none'});
								$('#box_confirmacion').css({'display':'block'});
								$("#msg_confirmacion").html('El Pedido fue realizado.');
						} else {
							$('#box_cargando').css({'display':'none'});
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
						}
						$("#btnSendTransfer").show(100);
					} else {
						$('#box_cargando').css({'display':'none'});
						$('#box_error').css({'display':'block'});
						$("#msg_error").html("Ocurrió un error al registrar los datos. Póngase en contacto con el adminsitrador de la web");
					}
				}				
			});
		}
	}
	
});