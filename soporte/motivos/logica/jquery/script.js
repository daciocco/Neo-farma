$(function(){"use strict"; $('#sector').change(changeSector); });

function changeSector() {
	"use strict";
	$('#motid').val("");
	$('#responsable').val("");
	$('#motivo').val("");
	var idSector = $("#sector").val();
	getMotivos(idSector);
}

function getMotivos(idSector) {
	"use strict";
	$.ajax({
		type 	: 'POST',
		cache	: false,
		data	: {sector : idSector},
		url 	: 'logica/ajax/getMotivos.php',	
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});
						if (resultado){	
							document.getElementById('tablamotivos').innerHTML = resultado;
						} else {
							$('#box_cargando').css({'display':'none'});	
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al consultar los registros.");	
		},							
	});
}

function dac_changeMotivo(id, motivo, usrresponsable) {
	"use strict";
	$('#motid').val(id);
	$('#motivo').val(motivo);
	$('#responsable').val(usrresponsable).change();
}


