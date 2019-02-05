$(document).ready(function() {	
	$( "#btsend" ).click(function () {
		var condid		=	$('input[name="condid"]:text').val();
		var empselect	=	$('select[name="empselect"]').val();
		var labselect	=	$('select[name="labselect"]').val();
		var tiposelect	=	$('select[name="tiposelect"]').val();
		
		var nombre		= 	$('input[name="nombre"]:text').val();
		var condpago	= 	$('input[name="condpago"]:text').val();
		var minMonto	= 	$('input[name="minMonto"]:text').val();
		var cantMinima	= 	$('input[name="cantMinima"]:text').val();
		var minReferencias	=	$('input[name="minReferencias"]:text').val();
		var fechaInicio	= 	$('input[name="fechaInicio"]:text').val();
		var fechaFin	= 	$('input[name="fechaFin"]:text').val();
		
		var observacion	= 	$('textarea[name="observacion"]').val();
		
		var habitualCant	= 	$('input[name="habitualCant"]:text').val();
		var habitualBonif1	= 	$('input[name="habitualBonif1"]:text').val();
		var habitualBonif2	= 	$('input[name="habitualBonif2"]:text').val();
		var habitualDesc1	= 	$('input[name="habitualDesc1"]:text').val();
		var habitualDesc2	= 	$('input[name="habitualDesc2"]:text').val();
						
		var cuentaid;		//= new Array();
		var condpagoid;  	
		var condidart		= []; //= new Array();
		var condprecioart	= []; //= new Array();
		var condpreciodigit	= []; //= new Array();
		var condcantmin		= []; //= new Array();
		var condoferta		= []; //= new Array();
		
		$('input[name="cuentaid[]"]:text').each(function() 	{		
			cuentaid =  (cuentaid) ? cuentaid+","+$(this).val() : $(this).val();
		});
		
		$('input[name="condpagoid[]"]:text').each(function() 	{		
			condpagoid =  (condpagoid) ? condpagoid+","+$(this).val() : $(this).val();
		});
		
		$('input[name="condprecioart[]"]:text').each(function(i) 	{ condprecioart[i] 	=  $(this).val();});
		$('input[name="condpreciodigit[]"]:text').each(function(i)	{ condpreciodigit[i]=  $(this).val();});
		$('input[name="condcantmin[]"]:text').each(function(i) 		{ condcantmin[i] 	=  $(this).val();});
		$('select[name="condoferta[]"]').each(function(i) 			{ condoferta[i] 	=  $(this).val();});
		
		var condcant 	= '';
		var condbonif1	= '';
		var condbonif2	= '';
		var conddesc1	= '';
		var conddesc2	= '';
				
		//al recorrer el foreach de artículos, cargo sus bonificaciones?
		$('input[name="condidart[]"]:text').each(function(i) {
			condidart[i] =  $(this).val();
			
			$('input[name="condcant'+condidart[i]+'[]"]:text').each(function(j) {
				condcant	=	(j === 0) ? condcant+$(this).val() : condcant+"-"+$(this).val();
			});
			$('input[name="condbonif1'+condidart[i]+'[]"]:text').each(function(j) { 
				condbonif1	=	(j === 0) ? condbonif1+$(this).val() : condbonif1+"-"+$(this).val();
			});
			$('input[name="condbonif2'+condidart[i]+'[]"]:text').each(function(j) { 
				condbonif2	=	(j === 0) ? condbonif2+$(this).val() : condbonif2+"-"+$(this).val();
			});
			$('input[name="conddesc1'+condidart[i]+'[]"]:text').each(function(j) { 
				conddesc1	=	(j === 0) ? conddesc1+$(this).val() : conddesc1+"-"+$(this).val();
			});
			$('input[name="conddesc2'+condidart[i]+'[]"]:text').each(function(j) { 
				conddesc2	=	(j === 0) ? conddesc2+$(this).val() : conddesc2+"-"+$(this).val();
			});
			
			condcant 	= condcant + '|';
			condbonif1	= condbonif1 + '|';	
			condbonif2 	= condbonif2 + '|';
			conddesc1 	= conddesc1 + '|';
			conddesc2	= conddesc2 + '|';
			
		});
		
		dac_enviar();
		function dac_enviar(){
			$.ajax({
				type: 'POST',
				url: '/pedidos/condicion/logica/update.condicion.php',
				data: {	
					condid		:	condid,
					empselect	:	empselect,
					labselect	:	labselect,
					tiposelect	:	tiposelect,
					nombre		:	nombre,
					condpago	:	condpago,
					minMonto	:	minMonto,
					cantMinima	:	cantMinima,
					minReferencias:minReferencias,
					fechaInicio	:	fechaInicio,
					fechaFin	:	fechaFin,
					observacion	:	observacion,
					habitualCant	: 	habitualCant,
					habitualBonif1	: 	habitualBonif1,
					habitualBonif2	: 	habitualBonif2,
					habitualDesc1	: 	habitualDesc1,
					habitualDesc2	: 	habitualDesc2,
					cuentaid	:	cuentaid,
					condpagoid	:	condpagoid,
					condidart	:	condidart,
					condprecioart:	condprecioart,
					condpreciodigit:condpreciodigit,
					condcantmin	:	condcantmin,
					condoferta	:	condoferta,
					condcant	:	condcant,
					condbonif1	:	condbonif1,
					condbonif2	:	condbonif2,
					conddesc1	:	conddesc1,
					conddesc2	:	conddesc2
				},
				beforeSend	: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
					$("#btsend").hide(100);
				},
				success: function(result) {
					if (result){							
						$('#box_cargando').css({'display':'none'});						
						if (result.replace("\n","") === '1'){
							//Confirmación	
							var scrolltohere = "#box_confirmacion";
							$('#box_confirmacion').css({'display':'block'});
							$("#msg_confirmacion").html('Los datos fueron registrados');
							window.location.reload(true);
						} else {
							//El pedido No cumple Condiciones
							var scrolltohere = "#box_error";
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(result);
							if(result === "Invalido"){
								window.location = "/pedidos/login/index.php";
							}
						}					
						$('html,body').animate({
							scrollTop: $(scrolltohere).offset().top
						}, 2000);
						$("#btsend").show(100);									
					}
				},
				error: function () {
					$('#box_cargando').css({'display':'none'});	
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error en el proceso");	
					$("#btsend").show(100);	
				},
			});
		}
	});
});