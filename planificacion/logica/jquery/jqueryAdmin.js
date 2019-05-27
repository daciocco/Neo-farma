function dac_Anular_Planificacion(){
	"use strict";
	var fecha_anular = document.getElementById("f_fecha_anular").value;
	var vendedor	 = document.getElementById("vendedor").value;
	if(confirm("Desea anular el env\u00edo de la planificaci\u00f3n?")){
		$.ajax({
			type : 'POST',
			cache:	false,
			url : '/pedidos/planificacion/logica/ajax/anular.planificado.php',					
			data:{	vendedor		:	vendedor,
					fecha_anular	:	fecha_anular
			},				
			beforeSend	: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success : function (resultado) {
				$('#box_cargando').css({'display':'none'});
				if (resultado){ 
					$('#box_error').css({'display':'block'});
					$("#msg_error").html(resultado);
				}
			},
			error: function () {
				$('#box_cargando').css({'display':'none'});	
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al intentar anular la Planificaci\u00f3n.");	
			}							
		});
	}
}

function dac_Anular_Parte(){
	"use strict";
	var fecha_anular = document.getElementById("f_fecha_anular_parte").value;
	var vendedor	 = document.getElementById("vendedor2").value;
	if(confirm("Desea anular el env\u00edo del parte?")){
		$.ajax({
			type : 'POST',
			cache:	false,
			url : '/pedidos/planificacion/logica/ajax/anular.parte.php',					
			data:{	vendedor		:	vendedor,
					fecha_anular	:	fecha_anular
			},		
			beforeSend	: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success : function (resultado) {
				$('#box_cargando').css({'display':'none'});
				if (resultado){
					$('#box_error').css({'display':'block'});
					$("#msg_error").html(resultado);
				}
			},
			error: function () {
				$('#box_cargando').css({'display':'none'});	
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al intentar anular el Parte.");	
			}					
		});
	}
}
