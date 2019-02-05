$(document).ready(function() {
	"use strict";
	$("#btsend").click(function () {	// desencadenar evento cuando se hace clic en el botón
		dac_formControl();
        // prevenir botón redireccionamiento a nueva página
        return false;
    });
	
	function dac_formControl() {		
		var	ctoid		= 	$('input[name="ctoid"]:text').val();
		var	nombre		= 	$('input[name="ctonombre"]:text').val();
		var	apellido	= 	$('input[name="ctoapellido"]:text').val();	
		var	telefono	= 	$('input[name="ctotelefono"]:text').val();
		var	interno		= 	$('input[name="ctointerno"]:text').val();	
		var	correo		= 	$('input[name="ctocorreo"]:text').val(); //nombre de la tabla a quien pertenece el contacto???
		var	activo		= 	$('input[name="ctoactivo"]:text').val();				
		var	origenid	= 	$('input[name="origenid"]:text').val();
		var	origen		= 	$('select[name="ctoorigen"]').val();			
		var	sector		= 	$('select[name="ctosector"]').val();
		var	puesto		= 	$('select[name="ctopuesto"]').val();		
		var	genero		= 	$('select[name="ctogenero"]').val();	

		dac_enviarForm();		
		function dac_enviarForm(){	
			$.ajax({
				type: "POST",
				url: "/pedidos/contactos/logica/ajax/update.contacto.php",
				data: {	ctoid		:	ctoid,
						nombre		:	nombre,
						apellido	:	apellido,
						telefono	:	telefono,
						interno		:	interno,
						correo		:	correo,
						activo		:	activo,
						origenid	:	origenid,
						origen		:	origen,
						sector		:	sector,
						puesto		:	puesto,
						genero		:	genero
				},
						
				beforeSend: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});					
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
					$("#btsend").hide(100);
				},
				
				success: function(result) {					
					if (result){							
						$('#box_cargando').css({'display':'none'});						
						if (result.replace("\n","") === '1'){
							//Confirmación
							$('#box_cargando').css({'display':'none'});
							$('#box_confirmacion').css({'display':'block'});
							$("#msg_confirmacion").html('Los datos fueron registrados');
							
							//cierra la ventana de dialog de contactos
							
							window.parent.document.getElementById('closeDialog').click();
						} else {
							//El pedido No cumple Condiciones Excluyentes
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
							$("#btsend").show(100);	
						}						
					}
				}				
			});
		}
	}
});