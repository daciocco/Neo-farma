$(document).ready(function() {
//ACA le asigno el evento click a cada boton de la clase btn_plus y llamo a la funcion addfactura
	"use strict";
	$(".btn_plus").each(function (){
		$(this).bind("click", addfactura);
	 });
});

function addfactura(){
	"use strict";
// ID del elemento div quitandole la palabra "fact_" de delante. Pasi asi poder aumentar el número. Esta parte no es necesaria pero yo la utilizaba ya que cada campo de mi formulario tenia un autosuggest,
	var clickID = parseInt($(this).parent('div').parent('div').attr('id').replace('fact_','')); //1
// Genero el nuevo numero id
	var newID = (clickID+1);
// Creo un clon del elemento div que contiene los campos de texto
	var newClone = $('#fact_'+clickID).clone(true);	
//Le asigno el nuevo numero id
	newClone.attr("id",'fact_'+newID);	
// Asigno los nuevos id y borro su valor anterior
	//.children().eq(0) --> busca el primer hijo del clon
	//.children("input").eq(0) --> busca el primer input del primer elemento del clon
	//.attr("id", 'nro_factura'+newID) --> modifica el atributo ID del elemento
	//.val('') -->le da un valor vacio
	newClone.children().eq(0).children("input").eq(0).attr("id", 'nro_factura'+newID).val(''); //nro
	newClone.children().eq(1).children("input").eq(0).attr("id", 'fecha_factura'+newID).val(''); //fecha_factura	
	//el 2 es el select que no se reinicia
	newClone.children().eq(2).children("select").eq(0).attr("id", 'nombrecli'+newID).val(''); //importe 
	newClone.children().eq(3).children("input").eq(0).attr("id", 'importe_bruto'+newID).val(''); //importe 	
	newClone.children().eq(4).children("input").eq(0).attr("id", 'pago_efectivo'+newID).val(''); 	//dto
	newClone.children().eq(5).children("input").eq(0).attr("id", 'importe_dto'+newID).val(''); //neto	
	newClone.children().eq(6).children("input").eq(0).attr("id", 'pago_transfer'+newID).val(''); 	//efect
	newClone.children().eq(7).children("input").eq(0).attr("id", 'importe_neto'+newID).val(''); 	//transfer
	newClone.children().eq(8).children("input").eq(0).attr("id", 'pago_retencion'+newID).val(''); //retenc
//Asigno nuevo id al boton
	newClone.children().eq(9).children("input").eq(0).attr("id", 'btnuevo_'+newID);	
	
//Inserto el div clonado y modificado despues del div original
	newClone.insertAfter($('#fact_'+clickID));
	
//Cambio el signo "+" por el signo "-" y le quito el evento addfactura
	$("#btnuevo_"+clickID).val('-').unbind("click", addfactura);
//Ahora le asigno el evento borrarFact para que borre la fial en caso de hacer click
	$("#btnuevo_"+clickID).bind("click", borrarFact);
}

function borrarFact() { // Funcion que destruye el elemento actual una vez echo el click  
	"use strict";
	//Antes LIMPIA todos los datos de la factura 
	$(this).parent('div').parent('div').find('input:text').val('');
	//Recalcula la diferencia porque si se borra el div ya no se podrá calcular
	dac_Calcular_Diferencia();
	$(this).parent('div').parent('div').remove();
	
}
