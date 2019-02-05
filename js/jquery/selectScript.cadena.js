/*La funcion $( document ).ready hace que no se carge el script hasta que este cargado el html*/
$( document ).ready(function() {
	/*Esta funcion se activa cada vez que se cambia el elemento seleccionado del <select id=cadena>*/
	$('#empresaselect').change(function(){
		/*Variable para almacenar la informacion que nos devuelve el servicio php*/
		var idCadenas;
		/*Coge el value de elemento seleccionado del <select id=f_localidad>*/
		var idEmpresa = $('#empresaselect option:selected').val();
		/*Esta es la funcion de la peticion ajax. El primer parametro es la direccion del servicio php
		en el que se hace la peticion de informacion, el segundo parametro es la funcion que se ejecuta
		cuando se devuelve los datos por JSON.

		El ajax accede desde el html que lo carga no desde el script de js.*/
		
		$.getJSON('/pedidos/js/ajax/getCadena.php?idEmpresa='+idEmpresa, function(datos) {
			idCadenas = datos;	
			/*Borro la lista cada vez que se pide una nueva provincia para que no se acumulen las anteriores*/
			$('#cadena').find('option').remove();
						
			/*Hago un foreach en jQuery para cada elemento del array ciudades y lo inserto en el <select id="ciudad">*/			
			$.each( idCadenas, function( key, value ) {
				var arr = value.split('-');
				$('#cadena').append("<option value='" + arr[0] + "'>" + arr[1] + "</option>");
			});
		});		
		
	});
});	
