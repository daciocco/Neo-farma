$( document ).ready(function() {
	"use strict";
	$("#rtecategoria").change(function(){
		
		//var reportes;
		var categoria = $('#rtecategoria option:selected').val();
		
		
		/*$.getJSON('/pedidos/reportes/getReporte.php?categoria='+categoria, function(datos) {
			reportes = datos;		*/
			$('#rtereporte').find('option').remove();
			switch(categoria){
				case "0": break;
				case "abm": 
					$('#rtereporte').append("<option value='0'>Datos de ABM</option>");
					$('#rtereporte').append("<option value='1'>Opcion 2 </option>");
					$('#rtereporte').append("<option value='2'>Opcion 3</option>");
					break;
				case "agenda": 
					$('#rtereporte').append("<option value='0'>Datos de Agenda</option>");
					break;
				case "articulo": 
					$('#rtereporte').append("<option value='0'>Datos de Artículo</option>");
					break;
				case "condicion": 
					$('#rtereporte').append("<option value='0'>Datos de Condición Comercial</option>");
					break;
				case "cuenta": 
					$('#rtereporte').append("<option value='0'>Datos de Cuenta</option>");
					break;
				case "llamada": 
					$('#rtereporte').append("<option value='0'>Datos de Llamada</option>");
					break;
				case "parte_diario": 
					$('#rtereporte').append("<option value='0'>Datos de Parte Diario</option>");
					break;
				case "pedido": 
					$('#rtereporte').append("<option value='0'>Datos de Pedido</option>");
					break;
				case "pedidos_transfer": 
					$('#rtereporte').append("<option value='0'>Datos de Pedido Transfer</option>");
					break;
				case "planificado": 
					$('#rtereporte').append("<option value='0'>Datos de Planificación</option>");
					break;
				case "propuesta": 
					$('#rtereporte').append("<option value='0'>Datos de Propuesta</option>");
					break;
			}
			
			/*$.each( reportes, function( key, value ) {
				var arr = value.split('-');
				$('#rtereporte').append("<option value='" + arr[0] + "'>" + arr[1] + "</option>");
			});
		});	*/
		
		
		
	});
});	
