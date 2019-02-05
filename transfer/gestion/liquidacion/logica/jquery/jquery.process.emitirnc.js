$(document).ready(function() {
	$("#emitirnc").click(function () {	
		var idliquid	= "";
		var fecha		= "";
		var transfer	= "";
		var fechafact	= "";
		var nrofact		= "";
		var ean			= "";
		var idart		= "";
		var cant		= "";
		var unitario	= "";
		var desc		= "";
		var importe		= "";
		var estado		= "";
		var activa 		= 	0;	
		
		//CONTROL//
		//Si liquidación está ACTIVA (liquidada) NO permitirá EMITIR NUEVAMENTE
		$('input[name="activa[]"]:text').each(function(){
			if($(this).val() == "1"){ activa	=	1; }
		});
		
		if(activa	== 0){			
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
			$('input[name="idart[]"]:text').each(function()		{ idart		=  idart+"|"+$(this).val();});
			$('input[name="ean[]"]:text').each(function()		{ ean		=  ean+"|"+$(this).val();});
			$('input[name="unitario[]"]:text').each(function()	{ unitario	=  unitario+"|"+$(this).val();});
			$('input[name="importe[]"]:text').each(function()	{ importe	=  importe+"|"+$(this).val();});		
			$('input[name="estado[]"]:text').each(function()	{ estado	=  estado+"|"+$(this).val();});
			
			dac_conciliarLiquidacion();	
			
			function dac_conciliarLiquidacion(){	
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
							idart		:	idart,
							cant		:	cant,
							unitario	:	unitario,
							importe		:	importe,
							conciliar	:	conciliar,	
							estado		:	estado
						  },
					success: function(result) {
						  if(result){	
							  if(result == 1){ 							
								  alert("Los cambios se han actualizado."); 	
								  location.reload();						
							  }else{ alert(result);}
						  }
					}				
				});
			}
		} else {
			alert("ATENCI\u00D3N! No puede volver a EMITIR NC de \u00E9sta liquidaci\u00F3n.");
		}
	});
});