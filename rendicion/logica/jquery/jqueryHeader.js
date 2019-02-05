function dac_BuscarRecibo(rec, tal){
	"use strict";
	$.ajax({
		url 	: 'logica/ajax/buscar.recibo.php',
		data 	: {	nrorecibo		: rec,
					nrotalonario	: tal
				  },
		type 	: 'GET',
		beforeSend: function () {
			$('#box_confirmacion2').css({'display':'none'});
			$('#box_error2').css({'display':'none'});
			$('#box_cargando2').css({'display':'block'});					
			$("#msg_cargando2").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : function (result) {
			$('#box_cargando2').css({'display':'none'});
			if (result){									
				if (result.replace("\n","") === '1'){
					$('#box_confirmacion2').css({'display':'block'});
					$("#msg_confirmacion2").html("El número de talonario no existe. Presione el botón 'Nuevo Talonario' si desea crearlo.");
				} else {							
					document.getElementById("close-recibo").click();
					$('#box_error2').css({'display':'block'});
					$("#msg_error2").html(result);
				}							
			} else {					
				//SALTA A LA SIGUIENTE VENTANA
				document.getElementById("nro_tal").value = tal;
				document.getElementById("nro_rec").value = rec;	
				document.getElementById("open-recibo").click();	
			}																						
		},
		error: function () {
			$('#box_cargando2').css({'display':'none'});
			$('#box_error2').css({'display':'block'});
			$("#msg_error2").html("Error!");
		}						
	});
}

function dac_NuevoTalonario(tal){
	"use strict";
	$.ajax({
		url 	: 'logica/ajax/nuevo.talonario.php',
		data 	: {nrotalonario	: 	tal},
		type 	: 'GET',
		beforeSend: function () {
			$('#box_confirmacion2').css({'display':'none'});
			$('#box_error2').css({'display':'none'});
			$('#box_cargando2').css({'display':'block'});					
			$("#msg_cargando2").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : function (result) {
			if (result) {
				$('#box_cargando2').css({'display':'none'});
				if (result.replace("\n","") === '1'){
					$('#box_confirmacion2').css({'display':'block'});
					$("#msg_confirmacion2").html("El nuevo talonario fue creado.");
					document.getElementById("open-recibo").click();
				} else { alert(result); }						
			}																				
		},
		error: function () {
			$('#box_cargando2').css({'display':'none'});
			$('#box_error2').css({'display':'block'});
			$("#msg_error2").html("Error!");
		}							
	});
}

function dac_AnularRecibo(rendid, nro_rend, nro_tal, nro_rec){	
	"use strict";
	$.ajax({
		url : 'logica/ajax/anular.recibo.php',
		data : {rendid		:	rendid,
				nro_rend	: 	nro_rend,
				nro_tal		: 	nro_tal,
				nro_rec		: 	nro_rec},
		type : 'GET',
		beforeSend: function () {
			$('#box_confirmacion2').css({'display':'none'});
			$('#box_error2').css({'display':'none'});
			$('#box_cargando2').css({'display':'block'});					
			$("#msg_cargando2").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : function (result) { 
					if (result){
						$('#box_cargando2').css({'display':'none'});
						if (result.replace("\n","") === '1'){
							$('#box_confirmacion2').css({'display':'block'});
							$("#msg_confirmacion2").html("El recibo fue ANULADO.");
							document.getElementById("close-recibo").click();
							document.getElementById("close-talonario").click();
							location.reload();
						} else {
							$('#box_cargando2').css({'display':'none'});
							$('#box_error2').css({'display':'block'});
							$("#msg_error2").html(result);
						}						
					}																				
				},
		error: function () {
			$('#box_cargando2').css({'display':'none'});
			$('#box_error2').css({'display':'block'});
			$("#msg_error2").html("Error!");
		}								
	});
}

/* ALGUNOS controles para el formulario de nueva factura */
function dac_ValidarNumero(num, id){
	"use strict";
	var bruto, dto, total_neto, id_nro = 0;
	if(isNaN(num)){
		document.getElementById(id).value = "";
		alert("Debe ingresar un valor numérico.");
	}

	if(id.substring(0, 13) === "importe_bruto"){
		id_nro 	= id.substring(13, id.length);
		if (document.getElementById('importe_dto'+id_nro).value === '0'){
			document.getElementById('importe_neto'+id_nro).value = num;					
		} else {	
			bruto = num;
			dto = document.getElementById('importe_dto'+id_nro).value;	
			total_neto = bruto - ((bruto * dto) / 100);
			document.getElementById('importe_neto'+id_nro).value = total_neto;
		}		
		dac_Calcular_Diferencia();			
	}

	if(id.substring(0, 11) === "importe_dto"){
		id_nro 	= id.substring(11, id.length);
		dto = num;
		bruto = document.getElementById('importe_bruto'+id_nro).value;	
		if (dto !== "") {
			total_neto = bruto - ((bruto * dto) / 100);
			document.getElementById('importe_neto'+id_nro).value = total_neto;
		} else {
			document.getElementById('importe_neto'+id_nro).value = bruto;
		}
		dac_Calcular_Diferencia();
	}

	if((id.substring(0, 13) === "pago_efectivo") || (id.substring(0, 13) === "pago_transfer") || (id.substring(0, 14) === "pago_retencion") || (id.substring(0, 15) === "pagobco_importe")){	
		dac_Calcular_Diferencia();
	}								
}

function dac_Calcular_Diferencia() {
	"use strict";
	//---------------------------------
	//SUMA DE LOS TOTALES DE facturas
	var factNumber	= document.getElementsByName("nro_factura[]").length; 	//cantidad de facturass		
	var rowNumber 	= document.getElementsByName('pagobco_importe[]').length; //cantidad de cheques			
	var neto 	= 0; 
	var efect 	= 0;
	var transf	= 0;
	var retenc	= 0;
	var importe = 0;
	var suma 		= 0;
	var diferencia 	= 0;	

	for (var j = 0; j < factNumber; j++){
		var importe_neto	=	parseFloat(document.getElementsByName("importe_neto[]").item(j).value);			
		var pago_efectivo	=	parseFloat(document.getElementsByName("pago_efectivo[]").item(j).value);
		var pago_transfer	=	parseFloat(document.getElementsByName("pago_transfer[]").item(j).value);
		var pago_retencion	=	parseFloat(document.getElementsByName("pago_retencion[]").item(j).value);								
		if (!isNaN(importe_neto)){		neto 	= neto + importe_neto;} 
		if (!isNaN(pago_efectivo)){		efect 	= efect + pago_efectivo;} 
		if (!isNaN(pago_transfer)){		transf 	= transf + pago_transfer;} 
		if (!isNaN(pago_retencion)){	retenc 	= retenc + pago_retencion;}						
	}		

	for (var i = 0; i < rowNumber; i++){	
		var	pagobco_importe	=	parseFloat(document.getElementsByName("pagobco_importe[]").item(i).value);				
		if (!isNaN(pagobco_importe)){	importe = importe + pagobco_importe;
		} 
	}

	suma = efect + transf + retenc + importe;							
	if (suma !== 0){			
		diferencia = suma - neto;
		document.getElementById("diferencia").value = parseFloat(diferencia).toFixed(2);
	}else{
		document.getElementById("diferencia").value = "";
	}				
}

function dac_EnviarRecibo(){
	"use strict";
	var factNumber		=	document.getElementsByName("nro_factura[]").length;	//cantidad de facturass
	var rowNumber 		= 	document.getElementsByName("pagobco_importe[]").length;	//cantidad de cheques
	var rendid			= 	document.getElementById("rendid").value;
	var observacion		= 	document.getElementById("observacion").value;
	var diferencia		= 	document.getElementById("diferencia").value;

	//Otros datos para grabar				
	var nro_rendicion	= 	document.getElementById("nro_rend").value;				
	var nro_tal			= 	document.getElementById("nro_tal").value;
	var nro_rec			= 	document.getElementById("nro_rec").value;

	//Declaro Objetos facturas
	var nrofactObject 	={};	
	var fechaObj 		={};
	var nombrecliObj	={};
	var importe_dtoObj	={};
	var importe_netoObj	={};
	var importe_brutoObj={};
	var pago_efectObj 	={};
	var pago_transfObj 	={};
	var pago_retenObj 	={};

	//Declaro Objetos Cheques
	var bco_nombreObj 	={};
	var bco_nrochequeObj={};
	var bco_fechaObj 	={};
	var bco_importeObj 	={};	

	//por cant de facturas
	for (var i = 0; i < factNumber; i++){						
		nrofactObject[i] 	= document.getElementsByName("nro_factura[]").item(i).value; // nro_factura[i].value;
		fechaObj[i] 		= document.getElementsByName("fecha_factura[]").item(i).value; // fecha_factura[i].value;						
		nombrecliObj[i] 	= document.getElementsByName("nombrecli[]").item(i).value; // nombrecli[i].value;
		importe_dtoObj[i]	= document.getElementsByName("importe_dto[]").item(i).value; // importe_dto[i].value;
		importe_netoObj[i]	= document.getElementsByName("importe_neto[]").item(i).value; // importe_neto[i].value;
		importe_brutoObj[i] = document.getElementsByName("importe_bruto[]").item(i).value; // importe_bruto[i].value;
		pago_efectObj[i]  	= document.getElementsByName("pago_efectivo[]").item(i).value; // pago_efectivo[i].value;
		pago_transfObj[i]  	= document.getElementsByName("pago_transfer[]").item(i).value; // pago_transfer[i].value;
		pago_retenObj[i]  	= document.getElementsByName("pago_retencion[]").item(i).value; // pago_retencion[i].value;						
	}		

	//según cant de cheques
	for (i = 0; i < rowNumber; i++){
		bco_nombreObj[i] 	= document.getElementsByName("pagobco_nombre[]").item(i).value; //pagobco_nombre[i].value;
		bco_nrochequeObj[i] = document.getElementsByName("pagobco_nrocheque[]").item(i).value; //pagobco_nrocheque[i].value;
		bco_fechaObj[i] 	= document.getElementsByName("bco_fecha[]").item(i).value; //bco_fecha[i].value;						
		bco_importeObj[i]  	= document.getElementsByName("pagobco_importe[]").item(i).value; //pagobco_importe[i].value;
	}

	nrofactObject 		= JSON.stringify(nrofactObject);
	fechaObj	 		= JSON.stringify(fechaObj);				
	nombrecliObj		= JSON.stringify(nombrecliObj);
	importe_dtoObj		= JSON.stringify(importe_dtoObj);
	importe_netoObj		= JSON.stringify(importe_netoObj);
	importe_brutoObj	= JSON.stringify(importe_brutoObj);
	pago_efectObj 		= JSON.stringify(pago_efectObj);
	pago_transfObj 		= JSON.stringify(pago_transfObj);
	pago_retenObj 		= JSON.stringify(pago_retenObj);
	bco_nombreObj	 	= JSON.stringify(bco_nombreObj);
	bco_nrochequeObj	= JSON.stringify(bco_nrochequeObj);
	bco_fechaObj	 	= JSON.stringify(bco_fechaObj);				
	bco_importeObj  	= JSON.stringify(bco_importeObj);

	$.ajax({
		type : 'GET',
		cache: false,
		url : 'logica/ajax/update.recibo.php',					
		data:{	
			rendid			:	rendid,
			factNumber		:	factNumber,
			rowNumber		:	rowNumber,
			observacion		:	observacion,
			diferencia		:	diferencia,
			nro_rendicion	:	nro_rendicion,
			nro_tal			:	nro_tal,
			nro_rec			:	nro_rec,
			nrofactObject	:  	nrofactObject,
			fechaObj		: 	fechaObj,
			nombrecliObj	: 	nombrecliObj,
			importe_dtoObj	: 	importe_dtoObj,
			importe_netoObj	: 	importe_netoObj,
			importe_brutoObj: 	importe_brutoObj,
			pago_efectObj 	: 	pago_efectObj,
			pago_transfObj 	: 	pago_transfObj,
			pago_retenObj 	: 	pago_retenObj,
			bco_nombreObj	: 	bco_nombreObj,
			bco_nrochequeObj: 	bco_nrochequeObj,
			bco_fechaObj	: 	bco_fechaObj,				
			bco_importeObj  : 	bco_importeObj	
		},
		beforeSend: function () {
			$('#box_confirmacion2').css({'display':'none'});
			$('#box_error2').css({'display':'none'});
			$('#box_cargando2').css({'display':'block'});					
			$("#msg_cargando2").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : function (result) { 								
					if (result){
						$('#box_cargando2').css({'display':'none'});
						if (result.replace("\n","") === '1'){
							$('#box_confirmacion2').css({'display':'block'});
							$("#msg_confirmacion2").html("El Nuevo recibo fue creado.");
							document.getElementById("close-recibo").click();
							document.getElementById("close-talonario").click();
							location.reload();
						} else {
							$('#box_error2').css({'display':'block'});
							$("#msg_error2").html(result);
						}						
					}															
				},
		error: function () {
			$('#box_cargando2').css({'display':'none'});
			$('#box_error2').css({'display':'block'});
			$("#msg_error2").html("Error en el proceso de Envío de Formulario.");
		}								
	});
}

function dac_SelectFilaToDelete(recid, recnro){
	"use strict";
	//al seleccionar una fila cambia de color y la ultima anterior seleccionada vuelve a su color original segun sea par o impar	
	// ultimo id y nrorecibo de fila seleccionado
	var ultimoid 		= document.getElementById("ultimocssth").value;		
	var ultimorecibo	= document.getElementById("ultimorecibo").value;
	// grabo el id de fila y nro recibo seleecionado para registrar el próximo ultimocss seleccionado
	document.getElementById("ultimocssth").value	=	recid;
	document.getElementById("ultimorecibo").value	=	recnro;	
	// en caso de que se haga clic en delete se borrará el recibo de la fila seleccionada
	document.getElementById("deleterendicion").value	=	recid;	
	if(recid !== ultimoid){
		if((ultimorecibo % 2) === 0){
			document.getElementById(ultimoid).style.background 	= "#fff";  
		} else {
			document.getElementById(ultimoid).style.background 	= "#f6f6f6"; 
		}		
		document.getElementById(recid).style.background			= "#5697C7";
	}
}

function dac_deleteRecibo(){
	"use strict";
	var recid	=	document.getElementById("deleterendicion").value;	
	var rendid	=	document.getElementById("rendicionid").value;
	$.ajax({
		url : 'logica/ajax/delete.recibo.php',
		data : {recid	: 	recid,
				rendid	:	rendid},
		type : 'GET',
		beforeSend: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});					
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : function (result) {
			if (result){
				$('#box_cargando').css({'display':'none'});
				if (result.replace("\n","") === '1'){
					$('#box_confirmacion').css({'display':'block'});
					$("#msg_confirmacion").html("El recibo fue ELIMINADO.");	
					location.reload();
				} else {
					alert(result);
				}						
			}																				
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("ERROR!");
		}								
	});
}

function dac_NuevaRendicion(nro){	
	"use strict";
	if (isNaN(nro) || nro===0){alert("Debe utilizar la cobranza Nro. 1");
	} else {
		//document.getElementById("nro_rend_actual").value = nro;
		document.getElementById("nro_rend").value = parseInt(nro) + 1;
	}
}

function dac_Anular_Rendicion(){
	"use strict";
	var nro_anular 	= document.getElementById("nrorendi_anular").value;
	var idusr 		= document.getElementById("vendedor").value;

	if(confirm("Desea anular el env\u00edo de la rendici\u00f3n?")){
		$.ajax({
			type : 'POST',
			cache:	false,
			url : '/pedidos/rendicion/logica/ajax/anular.rendicion.php',					
			data:{	idusr			:	idusr,
					nro_anular	:	nro_anular
			},	
			beforeSend: function () {
				$('#box_confirmacion').css({'display':'none'});
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});					
				$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
			},
			success : function (result) {
				if (result){ 
					$('#box_cargando').css({'display':'none'});
					$('#box_confirmacion').css({'display':'block'});
					$("#msg_confirmacion").html(result);	
				}
			},

			error: function () {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al intentar anular la Rendici\u00f3n.");
			}								
		});
	}
}