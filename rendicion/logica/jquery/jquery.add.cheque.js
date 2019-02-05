$(document).ready(function() {
	"use strict";
	//ACA le asigno el evento click a cada boton de la clase btn_checque_plus y llamo a la funcion addField
	$(".btn_checque_plus").each(function (){
		$(this).bind("click",addField);
	});
});

function addField(){
	"use strict";
// ID del elemento div quitandole la palabra "bank_" de delante. Pasi asi poder aumentar el número. Esta parte no es necesaria pero yo la utilizaba ya que cada campo de mi formulario tenia un autosuggest , así que dejo como seria por si a alguien le hace falta.
	var clickID = parseInt($(this).parent('div').parent('div').attr('id').replace('bank_',''));
// Genero el nuevo numero id
	var newID = (clickID+1);
// Creo un clon del elemento div que contiene los campos de texto
	var newClone = $('#bank_'+clickID).clone(true);	
//Le asigno el nuevo numero id
	newClone.attr("id",'bank_'+newID);
		
// Asigno nuevo id al primer campo input dentro del div y le borro cualquier valor que tenga asi no copia lo ultimo que hayas escrito.(igual que antes no es necesario tener un id)
	newClone.children().eq(0).children("select").eq(0).attr("id", 'pagobco_nombre'+newID).val(''); //nombre
	newClone.children().eq(1).children("input").eq(0).attr("id",'pagobco_nrocheque'+newID).val(''); //input nrocheque
	newClone.children().eq(2).children("input").eq(0).attr("id",'bco_fecha'+newID).val(''); //input fecha
	newClone.children().eq(3).children("input").eq(0).attr("id", 'pagobco_importe'+newID).val(''); // importe	
//Asigno nuevo id al boton
	newClone.children().eq(4).children("input").eq(0).attr("id", 'boton_'+newID);
	
//Inserto el div clonado y modificado despues del div original
	newClone.insertAfter($('#bank_'+clickID));

//Cambio el signo "+" por el signo "-" y le quito el evento addfield	
	$("#boton_"+clickID).val('-').unbind("click", addField);
//Ahora le asigno el evento delRow para que borre la fial en caso de hacer click
	$("#boton_"+clickID).bind("click", delRow);
}

function delRow() {// Funcion que destruye el elemento actual una vez echo el click
	//Antes borra los datos
	$(this).parent('div').parent('div').find('input:text').val('');
	//Recalcula la diferencia porque si se borra el div ya no se podrá calcular
	dac_Calcular_Diferencia();
	$(this).parent('div').parent('div').remove();
}