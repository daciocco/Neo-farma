$(document).ready(function() {
    $("#agregar").click(function () {	// desencadenar evento cuando se hace clic en el botón
        // agregar nueva fila a la tabla utilizando la función addTableRow		
		addTableRow($("tabla_bonif"));
        // prevenir botón redireccionamiento a nueva página
        return false;
    });

    // función para agregar una fila a tabla por clonación de última fila y aumentar id en 1 para ser tr únicos
    function addTableRow(tabla) {	
		//Consigo el id del tr actual y le quito la "b_"    
      	var clickID = $("#tabla_bonif tbody tr:last").attr('id').replace('b_','');   
		
		// Genero el nuevo numero id del tr
      	var newID = parseInt(clickID)+1; 
		
		// Creo un clon del elemento tr que contiene los campos de texto
		fila = $("#tabla_bonif tbody tr:last").clone(true); 
		
		//Le asigno el nuevo numero id
      	fila.attr("id",'b_'+newID); 						
		
		//Le asigno la clase par o impar
		if ((newID % 2) == 0){ $clase="par";} else {$clase="impar";}
		fila.removeClass("impar");
		fila.addClass($clase);
				
		//Inserto nueva fila a la tabla
       	$("#tabla_bonif").append(fila.hide().fadeIn('slow'));  //.hide().fadeIn('slow') es para que aparezca lentamente 
   		
		//Luego de insertar el nuevo tr, borra todos los valores de los input dentro de los td
		fila = $("#tabla_bonif tbody tr:last td input").val('');
		
		//Borro también los datos de selects
		fila = $("#tabla_bonif tbody tr:last td select").val('');
				
        // cuenta filas
        rowCount = 0;
        $("#tabla_bonif tr td:first-child").text(function () {			
            return ++rowCount;
        });
		
        // borrar fila
		dac_remove_button();
	};
		
	// borrar fila
	function dac_remove_button() {		
        $(".remove_button").on("click", function () {
            if ( $('#tabla_bonif tbody tr').length == 1) return;			
            $(this).parents("tr").fadeOut('slow', function () {
                $(this).remove();
                rowCount = 0;
                $("#tabla_bonif tr td:first-child").text(function () {
                    return ++rowCount;
                });
            });
        });
	};	
	
	// llama función borrar fila
	dac_remove_button();
});