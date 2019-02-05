/*La funcion $( document ).ready hace que no se carge el script hasta que este cargado el html*/
$( document ).ready(function() {
	/*Esta funcion se activa cada vez que se cambia el elemento seleccionado del <select id=provincia>*/
	$('#provincia').change(function(){
		/*Variable para almacenar la informacion que nos devuelve el servicio php*/
		var localidades;
		/*Coge el value de elemento seleccionado del <select id=provincia>*/
		var idProvincia = $('#provincia option:selected').val();
		/*Esta es la funcion de la peticion ajax. El primer parametro es la direccion del servicio php
		en el que se hace la peticion de informacion, el segundo parametro es la funcion que se ejecuta
		cuando se devuelve los datos por JSON.

		El ajax accede desde el html que lo carga no desde el script de js.*/
		$.getJSON('/pedidos/js/provincias/getLocalidades.php?codigoProvincia='+idProvincia, function(datos) {
			localidades = datos;		
			/*Borro la lista cada vez que se pide una nueva provincia para que no se acumulen las anteriores*/
			$('#f_localidad').find('option').remove();
						
			/*Hago un foreach en jQuery para cada elemento del array ciudades y lo inserto en el <select id="ciudad">*/			
			$.each( localidades, function( key, value ) {
				$('#f_localidad').append("<option value='" + value + "'>" + value + "</option>");
			});
		});		
		
	});
});	
