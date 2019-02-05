$(document).ready(function() {
	$("#guardar_liquidacion").click(function (event) {
		var idliquid	= "";
		var fecha		= "";
		var transfer	= "";
		var fechafact	= "";
		var nrofact		= "";
		var ean			= "";
		var cant		= "";
		var unitario	= "";
		var desc		= "";
		var importe		= "";
		
		drogid		=	$('input[name="drogid_liquidacion"]:text').val();
		mes			=	$('input[name="mes_liquidacion"]:text').val();
		anio		=	$('input[name="anio_liquidacion"]:text').val();
		conciliar	=	$('input[name="conciliar"]:text').val();
					
		$('input[name="idliquid[]"]:text').each(function()	{ idliquid	=  idliquid+"|"+$(this).val();});
		$('input[name="fecha[]"]:text').each(function()		{ fecha		=  fecha+"|"+$(this).val();});
		$('input[name="transfer[]"]:text').each(function()	{ transfer	=  transfer+"|"+$(this).val();});
		$('input[name="fechafact[]"]:text').each(function()	{ fechafact	=  fechafact+"|"+$(this).val();});
		$('input[name="desc[]"]:text').each(function()		{ desc 		=  desc+"|"+$(this).val();});
		$('input[name="nrofact[]"]:text').each(function()	{ nrofact	=  nrofact+"|"+$(this).val();});
		$('input[name="cant[]"]:text').each(function()		{ cant		=  cant+"|"+$(this).val();});
		$('input[name="ean[]"]:text').each(function()		{ ean		=  ean+"|"+$(this).val();});
		$('input[name="unitario[]"]:text').each(function()	{ unitario	=  unitario+"|"+$(this).val();});
		$('input[name="importe[]"]:text').each(function()	{ importe	=  importe+"|"+$(this).val();});
		
		dac_enviarDatosAbm();	
		function dac_enviarDatosAbm(){	
			$.ajax({
            	type: "POST",
            	url: "logica/update.liquidacion.php",
            	data: {	idliquid	:	idliquid, 
						mes			:	mes,
						anio		:	anio,
						drogid		:	drogid,
						fecha		:	fecha,
						fechafact	:	fechafact,
						transfer	:	transfer,	
						desc		:	desc,
						nrofact		:	nrofact,
						ean			:	ean,
						cant		:	cant,
						unitario	:	unitario,
						importe		:	importe,
						conciliar	:	conciliar
						},
            	success: function(result) {
					if(result){	
						document.getElementById("conciliar").value = 0;
						if(result == 1){ 							
							alert("Los cambios se han guardado"); 
							window.location = "/pedidos/transfer/gestion/liquidacion/index.php?fecha_liquidacion="+mes+"-"+anio+"&drogid="+drogid;							
						}else{ alert(result);}
					}
				}				
        	});
		}		
	});
});