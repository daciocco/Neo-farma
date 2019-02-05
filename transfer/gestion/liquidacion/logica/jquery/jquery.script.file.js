$(function(){ $('#importar').click(ImportarLiq); });

/****************************/
//	archivo de liquidacion	//
/****************************/
function ImportarLiq(){	
	var drog 		= 	document.getElementById("drogid").value;
	var fecha_liq	= 	document.getElementById("fecha_liquidacion").value;
	var activa 		= 	0;
	alert(fecha_liq);
	//CONTROL//
	//Si liquidación está ACTIVA (liquidada) NO permitirá importar
	$('input[name="activa[]"]:text').each(function(){
		if($(this).val() == "1"){ activa	=	1; }
	});
	
	if( (activa	== 1) && confirm("ATENCI\u00D3N! Importar\u00E1 una liquidadaci\u00F3n en un mes ya liquidado. Si lo hace, ELIMINAR\u00C1 la informaci\u00F3n existente. Desea Continuar?")){
		 activa	=	0;
	}
	
	if (activa == 0) {
		var archivos 	= 	document.getElementById("file");
		var archivo 	= 	archivos.files;

		var archivos 	= 	new FormData();
		archivos.append('archivo',archivo);
		for(i=0; i<archivo.length; i++){
			archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo al arreglo con un indice direfente	
		}
		
		archivos.append('drog', drog);
		archivos.append('fecha_liq', '01-'+fecha_liq);	
		
		$.ajax({
			url:'/pedidos/transfer/gestion/liquidacion/logica/importar_liq.php',
			type:'POST',
			contentType:false,
			data:archivos,			
			processData:false,
			cache:false
		}).done(function(msg){
			alert(msg);
			 location.reload();
		});		
	} else {
		alert("La importaci\u00F3n no fue realizada");
	}
}