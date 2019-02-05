$("#btPreciosCondiciones").click(function () {   
	"use strict";
	if (confirm('Desea que se creen las condiciones comerciales con los nuevos precios?')) {
		$.ajax({
			type 	: 'POST',
			url 	: '/pedidos/articulos/logica/ajax/duplicar.condicion.php',
			beforeSend: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
			},
			success : function (result) { 								
					if (result){
						if (result.replace("\n","") === '1'){ 
							$('#box_cargando').css({'display':'none'});
							$('#box_confirmacion').css({'display':'block'});
							$("#msg_confirmacion").html('Los datos han sido procesados correctamente.');
							window.location.reload(true);

						} else { 
							$('#box_cargando').css({'display':'none'});
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
						}						
					} else {
						$('#box_cargando').css({'display':'none'});
						$('#box_error').css({'display':'block'});
						$("#msg_error").html("Ocurrió un error al registrar los datos. Póngase en contacto con el adminsitrador de la web");
					}														
				},
			error	: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html('Error en el proceso.');
			}								
		});
	}  

});

function dac_mostrarFiltro(tipo, filtro){
	"use strict";
	$.ajax({
		type 	: 	'POST',
		cache	:	false,
		url 	: 	'/pedidos/articulos/logica/ajax/getFiltroArticulos.php',				
		data	:	{	
					tipo 	: tipo,
					filtro 	: filtro
				},
		beforeSend	: function () {
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (result) {
			if (result){
				var tabla = result;											
				document.getElementById('tablaFiltroArticulos').innerHTML = tabla;
				$('#box_cargando').css({'display':'none'});
			} else { 
				$('#box_cargando').css({'display':'none'});	
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al consultar los registros.");
			}
		},		
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al consultar los registros.");
		},
	});
}