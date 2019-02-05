$("#btnEdit").click(function () {
	"use strict";
	event.preventDefault();		
	var startDate 	= $('#startDate').val(),
		endDate		= $('#endDate').val();
	var editSelected = $("#tablaCondiciones input[name='editSelected']:checkbox:checked").map(function(){
	  return $(this).val();
	}).get();

	$.ajax({
		type	: "POST",
		cache	: false,						
		url		: '/pedidos/condicion/logica/ajax/setFechaCondiciones.php',
		data:	{	startDate	:	startDate,
					endDate		: 	endDate,
					editSelected:	editSelected
				},
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success	: function(result){
			$('#box_cargando').css({'display':'none'});
			if(result === '1'){
				$('#box_confirmacion').css({'display':'block'});
				$("#msg_confirmacion").html('Los datos fueron registrados');
				location.reload();
			} else {
				$('#box_error').css({'display':'block'});
				$("#msg_error").html(result);		
			}
		},	
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error en el proceso");	
			$("#btsend").show(100);	
		},
	});
});

new JsDatePick({
	useMode:2,
	target:"startDate",
	dateFormat:"%d-%M-%Y"			
});
new JsDatePick({
	useMode:2,
	target:"endDate",
	dateFormat:"%d-%M-%Y"			
});	

function dac_eliminarCondicion(){
	"use strict";
	if(confirm('Esta seguro que desea ELIMINAR?')) { dac_ModificarSelect('eliminar'); }
}

function dac_ModificarSelect(typeChange){
	"use strict";
	if(typeChange){
		var url = '';
		switch(typeChange){
			case 'status':
				url = 'logica/ajax/change.status.php';
				break;
			case 'price':
				url = 'logica/ajax/actualizar.precios.php';
				break;
			case 'duplicate':
				url = 'logica/ajax/duplicar.condicion.php';
				break;
			case 'eliminar':
				url = 'logica/ajax/delete.condicion.php';
				break;
			default: return;
		}

		var checkboxes = document.getElementById("frmCondicion").editSelected;
		var arrayIdCond = [];
		for (var x=0; x < checkboxes.length; x++) {
			if (checkboxes[x].checked) {
				arrayIdCond.push(checkboxes[x].value);
			}
		}

		$.ajax({
			type 	: 'POST',
			url 	: url,					
			data	: {	condid	: arrayIdCond,},
			beforeSend: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
			},
			success : function (result) { 								
					if (result){
						$('#box_cargando').css({'display':'none'});
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
						$("#msg_error").html("Ocurrió un error al registrar los datos. Póngase en contacto con el adminsitrador de la web");
					}														
				},
			error	: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error en el proceso.");
			}								
		});
	} else {
		$('#box_error').css({'display':'block'});
		$("#msg_error").html("Ocurrió un error al registrar los datos. Póngase en contacto con el adminsitrador de la web");
	}
}	