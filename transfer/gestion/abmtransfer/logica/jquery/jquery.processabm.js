$(document).ready(function() {
	$("#guardar_abm").click(function () {
		var idabm		= "";
		var selectArt	= "";
		var descuento	= "";
		var difcompens	= "";
		var plazo		= "";
		
		drogid		=	$('input[name="drogid_abm"]:text').val();
		mes			=	$('input[name="mes_abm"]:text').val();
		anio		=	$('input[name="anio_abm"]:text').val();
		
		$('input[name="idabm[]"]:text').each(function() 	{ idabm		 	=  idabm+"-"+$(this).val();});
		$('input[name="art[]"]:text').each(function() 		{ selectArt	 	=  selectArt+"-"+$(this).val();});
		$('input[name="desc[]"]:text').each(function() 		{ descuento 	=  descuento+"-"+$(this).val();});
		$('select[name="plazoid[]"]').each(function() 		{ plazo 		=  plazo+"-"+$(this).val();});				
		$('input[name="difcompens[]"]:text').each(function(){ difcompens 	=  difcompens+"-"+$(this).val();});
		
		if(selectArt == ""){	alert("Debe completar algun articulo para guardar los cambios.");
		} else { 				dac_enviarDatosAbm(); }		
		
		function dac_enviarDatosAbm(){	
			$.ajax({
            	type: "POST",
            	url: "logica/update.abm.php",
            	data: {	idabm		:	idabm, 
						mes			:	mes,
						anio		:	anio,
						drogid		:	drogid,
						selectArt	:	selectArt,
						descuento	:	descuento,
						plazo		:	plazo,	
						difcompens	:	difcompens
						},
            	success: function(result) {
					if(result){	
						if(result == 1){ alert("Los cambios se han guardado"); window.location = "/pedidos/transfer/gestion/abmtransfer/index.php?fecha_abm="+mes+"-"+anio+"&drogid="+drogid;
						}else{ alert(result);}
					}
				}				
        	});
		}		
	});
});