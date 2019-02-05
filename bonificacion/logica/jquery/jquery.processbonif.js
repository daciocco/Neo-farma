$(document).ready(function() {
	$("#guardar").click(function () {
		selectArt	= new Array();
		bonifID		= new Array();
		preciosDrog = new Array();
		preciosPublico 	= new Array();
		Ivas		= new Array();
		Digitados	= new Array();
		Ofertas		= new Array();
		a1	= new Array(); b1	= new Array(); c1	= new Array();
		a3	= new Array(); b3	= new Array(); c3	= new Array();
		a6	= new Array(); b6	= new Array(); c6	= new Array();
		a12	= new Array(); b12	= new Array(); c12	= new Array();
		a24	= new Array(); b24	= new Array(); c24	= new Array();
		a36	= new Array(); b36	= new Array(); c36	= new Array();
		a48	= new Array(); b48	= new Array(); c48	= new Array();
		a72	= new Array(); b72	= new Array(); c72	= new Array();
		
		$('select[name="art[]"]').each(function() 			{ selectArt =  selectArt+"-"+$(this).val();});
		//recorremos todos los inputs con el nombre indicado para cargarlos como arrays		
		//$('input[name="idbonif[]"]:text').each(function() 	{ bonifID.push($(this).val());}); //Esto carga como array
		$('input[name="idbonif[]"]:text').each(function() 	{ bonifID =  bonifID+"-"+$(this).val();});
		$('input[name="drog[]"]:text').each(function() 		{ preciosDrog =  preciosDrog+"-"+$(this).val();});
		$('input[name="publico[]"]:text').each(function() 	{ preciosPublico =  preciosPublico+"-"+$(this).val();});
		$('input[name="iva[]"]:text').each(function() 		{ Ivas =  Ivas+"-"+$(this).val();});
		$('input[name="digitado[]"]:text').each(function() 	{ Digitados =  Digitados+"-"+$(this).val();});
		$('select[name="oferta[]"]').each(function() 		{ Ofertas =  Ofertas+"-"+$(this).val();});
		$('input[name="1a[]"]:text').each(function() 		{ a1 =  a1+"-"+$(this).val();});
		$('input[name="1b[]"]:text').each(function() 		{ b1 =  b1+"-"+$(this).val();});
		$('input[name="1c[]"]:text').each(function() 		{ c1 =  c1+"-"+$(this).val();});
		$('input[name="3a[]"]:text').each(function() 		{ a3 =  a3+"-"+$(this).val();});
		$('input[name="3b[]"]:text').each(function() 		{ b3 =  b3+"-"+$(this).val();});
		$('input[name="3c[]"]:text').each(function() 		{ c3 =  c3+"-"+$(this).val();});
		$('input[name="6a[]"]:text').each(function() 		{ a6 =  a6+"-"+$(this).val();});
		$('input[name="6b[]"]:text').each(function() 		{ b6 =  b6+"-"+$(this).val();});
		$('input[name="6c[]"]:text').each(function() 		{ c6 =  c6+"-"+$(this).val();});
		$('input[name="12a[]"]:text').each(function() 		{ a12 =  a12+"-"+$(this).val();});
		$('input[name="12b[]"]:text').each(function() 		{ b12 =  b12+"-"+$(this).val();});
		$('input[name="12c[]"]:text').each(function() 		{ c12 =  c12+"-"+$(this).val();});
		$('input[name="24a[]"]:text').each(function() 		{ a24 =  a24+"-"+$(this).val();});
		$('input[name="24b[]"]:text').each(function() 		{ b24 =  b24+"-"+$(this).val();});
		$('input[name="24c[]"]:text').each(function() 		{ c24 =  c24+"-"+$(this).val();});
		$('input[name="36a[]"]:text').each(function() 		{ a36 =  a36+"-"+$(this).val();});
		$('input[name="36b[]"]:text').each(function() 		{ b36 =  b36+"-"+$(this).val();});
		$('input[name="36c[]"]:text').each(function() 		{ c36 =  c36+"-"+$(this).val();});
		$('input[name="48a[]"]:text').each(function() 		{ a48 =  a48+"-"+$(this).val();});
		$('input[name="48b[]"]:text').each(function() 		{ b48 =  b48+"-"+$(this).val();});
		$('input[name="48c[]"]:text').each(function() 		{ c48 =  c48+"-"+$(this).val();});
		$('input[name="72a[]"]:text').each(function() 		{ a72 =  a72+"-"+$(this).val();});
		$('input[name="72b[]"]:text').each(function() 		{ b72 =  b72+"-"+$(this).val();});
		$('input[name="72c[]"]:text').each(function() 		{ c72 =  c72+"-"+$(this).val();});
		
		mes		=	$('input[name="mes_bonif"]:text').val();
		anio	=	$('input[name="anio_bonif"]:text').val();
				
		//var dataString = $('#fm_bonificacion_edit').serialize(); alert('Datos serializados: '+dataString);		
		dac_enviarDatosBonificacion();	
		function dac_enviarDatosBonificacion(){	
			$.ajax({
            	type: "POST",
            	url: "logica/update.bonificacion.php",
            	data: {	mes		:	mes,
						anio		:	anio,
						idbonif		:	bonifID,
						articulos	:	selectArt,
						drog		:	preciosDrog,	
						publico		:	preciosPublico,
						iva			:	Ivas,
						digitado	:	Digitados,
						oferta		:	Ofertas,
						a1	:	a1, b1	:	b1, c1	:	c1,
						a3	:	a3, b3	:	b3, c3	:	c3,
						a6	:	a6, b6	:	b6, c6	:	c6,
						a12	:	a12, b12	:	b12, c12	:	c12,
						a24	:	a24, b24	:	b24, c24	:	c24,
						a36	:	a36, b36	:	b36, c36	:	c36,
						a48	:	a48, b48	:	b48, c48	:	c48,
						a72	:	a72, b72	:	b72, c72	:	c72
						},
            	success: function(result) {
					if(result == "Invalido"){	
						alert("El usuario es inválido. Se cerrará sesión."); window.location = "/pedidos/login/index.php";
					} else {
						if(result == 1){
							alert("Los cambios se han guardado"); window.location = "/pedidos/bonificacion/index.php?fecha_bonif="+mes+"-"+anio;
						}else{
							alert(result);
						}						
					}
				}				
        	});
		}		
	});
});