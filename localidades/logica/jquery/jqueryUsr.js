//----------------------
//	Carga Datos Cuenta
var nextCuenta = 0;
function dac_cargarDatosCuenta(ctaId, empresa, idCuenta, zonaV, stringZonas) {
	"use strict";
	nextCuenta ++;
	var campo =	'<div id="rutcuenta'+nextCuenta+'">';
		campo +='<div class="bloque_4"><input id="ctaId'+nextCuenta+'" name="ctaId[]" type="text" value='+ctaId+' hidden="hidden"><input type="text" value='+empresa+' disabled></div><div class="bloque_2"><input type="text" value='+idCuenta+' disabled ></div>';
		//------------
		//	ZONAS	
		if(stringZonas){ var zonas	= stringZonas.split(","); }
		campo += '<div class="bloque_4"><select name="zonaVExc[]">';
		for (var i=0; i < zonas.length; i++){
			var selected='';
			if(zonas[i] === zonaV){selected='selected';}
			campo += '<option id="zonaVExc'+nextCuenta+'" value='+zonas[i]+' '+selected+' >'+zonas[i]+'</option>';
		}
		campo += '</select></div>';
		//------------			
		campo +='<div class="bloque_4"><input id="btmenos" class="btmenos" type="button" value=" - " onClick="dac_deleteCuenta('+ctaId+', '+nextCuenta+')"></div>';	
	campo +='</div><hr>';
	$("#excepciones").append(campo);
	/*Oculto la fila */
	if(document.getElementById('cuenta'+ctaId)){
		document.getElementById('cuenta'+ctaId).style.display = "none";
	}
}
// Botón eliminar div
function dac_deleteCuenta(id, nextCuenta) {
	"use strict";
	document.getElementById('cuenta'+id).style.display = "table-row";
	var elemento	=	document.getElementById('rutcuenta'+nextCuenta);
	elemento.parentNode.removeChild(elemento);
}