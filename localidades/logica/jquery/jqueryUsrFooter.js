$(document).ready(function() {
	"use strict";
	var idLoc	= 	$('#idLoc').val();
	var idProv	= 	$('#provincia').val();	
	$.ajax({
		type	: 	'POST',
		cache	:	false,
		url 	: 	'logica/ajax/getCuentas.php',					
		data	:	{	idLoc	:	idLoc,
						idProv	:	idProv},		
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},		
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});		
						if (resultado){	
							document.getElementById('tablacuenta').innerHTML = resultado;
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
});