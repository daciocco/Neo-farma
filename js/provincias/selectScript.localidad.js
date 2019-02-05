/*La funcion $( document ).ready hace que no se carge el script hasta que este cargado el html*/
$( document ).ready(function() {
	/*Esta funcion se activa cada vez que se cambia el elemento seleccionado del <select id=f_localidad>*/
	$('#f_localidad').change(function(){
		/*Variable para almacenar la informacion que nos devuelve el servicio php*/
		var codigosPostales;
		/*Coge el value de elemento seleccionado del <select id=f_localidad>*/
		var nombreLocalidad = $('#f_localidad option:selected').val();
		/*Esta es la funcion de la peticion ajax. El primer parametro es la direccion del servicio php
		en el que se hace la peticion de informacion, el segundo parametro es la funcion que se ejecuta
		cuando se devuelve los datos por JSON.

		El ajax accede desde el html que lo carga no desde el script de js.*/
		
		$.getJSON('/pedidos/js/provincias/getCodigosPostales.php?nombreLocalidad='+nombreLocalidad, function(datos) {
			codigosPostales = datos;	
			/*Borro la lista cada vez que se pide una nueva provincia para que no se acumulen las anteriores*/
			//$('#f_codigopostal').find('option').remove();
			$('#codigopostal').val("");
						
			/*Hago un foreach en jQuery para cada elemento del array ciudades y lo inserto en el <select id="ciudad">*/			
			$.each( codigosPostales, function( key, value ) {
				$('#codigopostal').val(value);
			});
		});		
		
	});
});	
