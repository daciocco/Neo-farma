$(document).ready(function() {
	"use strict";
	$("#aprobar").click(function () {	// desencadenar evento cuando se hace clic en el botón
		dac_aprobarPedido(0);
        // prevenir botón redireccionamiento a nueva página
        return false;
    });
	
	$("#rechazar").click(function () {	// desencadenar evento cuando se hace clic en el botón
		dac_aprobarPedido(2);
        // prevenir botón redireccionamiento a nueva página
        return false;
    });
	
	//--------------------------------
	$("#aprobarPropuesta").click(function () {
		dac_aprobarPropuesta(2);
        return false;
    });
	
	$("#rechazarPropuesta").click(function () {
		dac_aprobarPropuesta(3);
        return false;
    });
	//--------------------------------
	
	function dac_aprobarPedido(estado) {	
		$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');	
		var	nropedido	= 	$('input[name="nropedido"]:text').val();		
		$.ajax({
			type: "POST",
			url: "/pedidos/pedidos/logica/ajax/aprobar.pedido.php",
			data: {	nropedido	:	nropedido,
					estado		:	estado },
			beforeSend: function(){					
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');					
			},			
			success: function(result) {				
				if (result){
					$('#box_cargando').css({'display':'none'});
					if(result.replace("\n","") === '1'){
						$('#box_error').css({'display':'block'});
						$("#msg_error").html("El pedido ha sido autorizado");
						window.close();
					} else {
						if(result.replace("\n","") === '2'){
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("El pedido fue rechazado");
							window.close();
						} else {
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
						}
					}					
								
				}
			},
			error	: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error en el proceso.");
			}	
		});
	}
	
	//-------------------------------
	function dac_aprobarPropuesta(estado) {	
		var	propuesta	= 	$('input[name="propuesta"]:text').val();	
		$.ajax({
			type: "POST",
			url: "/pedidos/pedidos/logica/ajax/aprobar.propuesta.php",
			data: {	propuesta	:	propuesta,
					estado		:	estado 
			},
			beforeSend: function(){					
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');					
			},
			success: function(result) {					
				if (result){
					$('#box_cargando').css({'display':'none'});
					if(result.replace("\n","") === '1'){
						$('#box_confirmacion').css({'display':'block'});
						$("#msg_confirmacion").html("Proceso finalizado.");
						parent.history.back();
					} else {
						$('#box_error').css({'display':'block'});
						$("#msg_error").html(result);	
					}
				} 
			},
			error	: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error en el proceso.");
			}
		});
	}
	
});