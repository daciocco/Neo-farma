function dac_alertaDuplicar(){
	"use strict";
	if($('#tiposelect').val() === 'Bonificacion'){
		$('#box_cargando').css({'display':'none'});
		$('#box_confirmacion').css({'display':'none'});
		$('#box_error').css({'display':'none'});
		$('#box_observacion').css({'display':'block'});
		$("#msg_atencion").html('RECUERDA que luego de guardar los cambios hay que duplicar las Condiciones Especiales haciendo clic en el bot&oacute;n.');
	}
}

$("#btAltaCondiciones").click(function () {  
	"use strict";
	if (confirm('Desea que se creen las condiciones comerciales con los nuevos precios?')) {
		var condid=	$("#condid").val();
		$.ajax({
			type 	: 'POST',
			url 	: '/pedidos/condicion/logica/ajax/duplicar.condicion.especial.php',
			data	: {condid : condid},
			beforeSend: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
			},
			success : function (result) {
					$('#box_cargando').css({'display':'none'});
					if (result){
						if (result.replace("\n","") === "1"){ 
							$('#box_confirmacion').css({'display':'block'});
							$("#msg_confirmacion").html('Los datos han sido procesados correctamente.');
							window.location.reload(true);

						} else { 
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
						}						
					} else {
						$('#box_error').css({'display':'block'});
						$("#msg_error").html("Error en el proceso. Contacte con el adminsitrador de la web");
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

new JsDatePick({
	useMode:2,
	target:"fechaInicio",
	dateFormat:"%d-%M-%Y"			
});

new JsDatePick({
	useMode:2,
	target:"fechaFin",
	dateFormat:"%d-%M-%Y"			
});