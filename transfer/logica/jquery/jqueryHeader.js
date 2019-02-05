
//-----------------------------------/
//	Limpia Artículos del Pedido		
//-----------------------------------/
function dac_limpiarArticulos(){
	"use strict";
	$('#pwsubtotal').html('');
	$("#lista_articulos2").empty();
}

function dac_mostrarCuentasRelacionada(id){
	"use strict";
	var nro = document.getElementById('tblTransfer').value; //la primera será cero	
	document.getElementById('tblTransfer').value	=	id;
	if(nro !== id){	
		dac_limpiarArticulos();	
		document.getElementById(id).style.display	=	'block';					
		document.getElementById(nro).style.display	=	'none';		
		$("#detalle_cuenta").empty();//limpia contenido de cuentas

	}
}

//**********************//
//	Nuevo div Artículo	//
//**********************//
/*
var nextinput = 0;
function dac_CargarArticulo(idart, ean, nombre, precio){
	document.getElementById("field_listart").style.display	=	'block';
	nextinput++;		

	var campo	= 	'<div id="rut'+nextinput+'">';

		campo += 	'<input id="ptidart'+nextinput+'" name="ptidart[]" type="text" value="'+idart+'" hidden/>';
		campo += 	'<input id="ptnombreart'+nextinput+'" name="ptnombreart[]" type="text" value="'+nombre+'" hidden/>';
		campo += 	'<input id="ptean'+nextinput+'" name="ptean[]" type="text" value="'+ean+'" hidden/>';
		campo += 	'<input id="ptprecioart'+nextinput+'" name="ptprecioart[]" type="text" value="'+precio+'" hidden/>';				
		campo	+= '<div class="bloque_1"><strong> Art&iacute;culo '+idart+'</strong></br>'+nombre+'</div>';

		campo += 	'<div class="bloque_4"><strong> Cantidad </strong><input id="ptcant'+nextinput+'" name="ptcant[]" onblur="javascript:dac_CalcularSubtotalTransfer();" maxlength="5"/></div>';

		campo += 	'<div class="bloque_4"><strong>% Desc </strong> <input id="ptdesc'+nextinput+'" name="ptdesc[]" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onkeyup="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onblur="javascript:dac_CalcularSubtotalTransfer();" maxlength="5"/></div>';

		var plazos = '';
		<?php
		$plazos	= DataManager::getCondicionesDePagoTransfer(0, 0, 1);
		if (count($plazos)) { 

			foreach ($plazos as $j => $plazo) {
				$plazo			=	$plazos[$j];
				$plazoId		=	$plazo["condid"];
				$plazoNombre	= 	$plazo["condnombre"];

				//if ($plazoId != 3) {
					if ($plazoId == 1) { ?>	
						plazos += '<option value="<?php echo $plazoId; ?>" selected><?php echo $plazoNombre; ?></option>';
						<?php
					} else { ?>
						plazos += '<option value="<?php echo $plazoId; ?>"><?php echo $plazoNombre; ?></option>';
							<?php	
					}
				//}                                        
			} 
		} ?>

		campo += 	'<div class="bloque_4"><strong>Condici&oacute;n</strong><select id="ptcondpago" name="ptcondpago[]">'+plazos+'</select></div>';	

		campo += 	'<div class="bloque_4"></br><input id="btmenos" type="button" value="-" onClick="EliminarArt('+nextinput+')"  style="width:25px; background-color:#C22632;"></div>';

		campo += 	'<hr>'
	campo	+= '</div>';

	$("#lista_articulos2").append(campo);		
	dac_CargarDescAbm(nextinput);		

}		

// Quitar div de artículo
function EliminarArt(id){
	elemento=document.getElementById('rut'+id);
	elemento.parentNode.removeChild(elemento);
	dac_CalcularSubtotalTransfer();
}

function dac_limpiarArticulos(){
	$("#lista_articulos2").empty();
}
*/
//**************//
//	Carga %ABM	//
//**************//
function dac_CargarDescAbm(id) {
	"use strict";
	var elem = document.getElementsByName("cuentaId[]");
	var drogid = '';
	for (var i = 0; i < elem.length; ++ i) {
		drogid = elem[i].value;
	}
	var artid	=	document.getElementById("ptidart"+id).value;                       
	$.ajax({
		type : 'POST',
		cache:	false,
		url : '/pedidos/transfer/logica/ajax/cargar.abm.php',					
		data:{	drogid		:	drogid,
				artid		:	artid
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
						if (result.replace("\n","") === '1'){
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Hubo un error en la carga autom\u00e1tica de descuento del AMB del art\u00edculo agregado, o no existe en el ABM del mes actual.");
						} else {
							document.getElementById("ptdesc"+id).value = result;
						}											
					}															
				},
		error: function () {
			$('#box_cargando2').css({'display':'none'});
			$('#box_error2').css({'display':'block'});
			$("#msg_error2").html("Error en el proceso de control de ABM");
		}								
	});		
}

//**************//
//	SUBTOTAL	//
//**************//
function dac_CalcularSubtotalTransfer(){
	"use strict";
	var cantArts	=	document.getElementsByName('ptidart[]').length;
	var subtotal	=	0;

	for(var i = 0; i < cantArts; i++){			
		var ptprecio= document.getElementsByName("ptprecioart[]").item(i).value;
		var ptcant 	= document.getElementsByName("ptcant[]").item(i).value;								
		var ptdesc	= document.getElementsByName("ptdesc[]").item(i).value;

		var total	= ptcant * ptprecio;	
		var desc	= total * ptdesc/100;				
		subtotal	+= total - desc;
	}	

	document.getElementById("ptsubtotal").style.display	=	'block';
	$('#ptsubtotal').html('<strong>Subtotal: $ '+subtotal.toFixed(2)+ '</strong> (No refleja IVA)');

}  

//*****************************************//
// Crea Div de Cuenta Transfer relacionada //
//*****************************************//
var nextCuentaTransfer = 0;
function dac_cargarCuentaTransferRelacionada(id, idCta, idCuenta, nombre, nroClienteTransfer, ctaRelEmpresa){
	"use strict";
	//limpia el contenido de detalle_cuenta
	dac_limpiarArticulos();	
	$("#detalle_cuenta").empty();

	nextCuentaTransfer++;
	var campo =	'<div id="rutcuenta'+nextCuentaTransfer+'">';
		campo +='<div class="bloque_4"><input id="cuentaIdTransfer'+nextCuentaTransfer+'" name="cuentaIdTransfer[]" type="text" size="10" maxlength="10" placeholder="Cliente Transfer" value="'+nroClienteTransfer+'"/></div>';
		campo +='<div class="bloque_1"><input id="cuentaId'+nextCuentaTransfer+'" name="cuentaId[]" type="text" size="2" value='+idCta+' hidden/>&nbsp;'+idCuenta+" - "+nombre.substring(0,25)+'</div>';
		campo +='<div class="bloque_4"><input id="btmenos" class="btmenos" type="button" value=" - " onClick="dac_deleteCuentaTransferRelacionada('+id+', '+nextCuentaTransfer+')"></div>';
	campo +='</div>';

	$("#detalle_cuenta").append(campo);	

	dac_CargarArticulos(ctaRelEmpresa, 1);
}

/********************/
/*	Carga Artículos */
/********************/
function dac_CargarArticulos(idEmpresa, idlab, condicion){
	"use strict";
	$.ajax({
		type : 	'POST',
		cache:	false,
		url : 	'logica/ajax/getArticulos.php',					
		data:	{	laboratorio	:	idlab,
					empresa		:	idEmpresa,
					condicion	:	condicion,
				},				
		beforeSend	: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');

			$('#box_cargando3').css({'display':'block'});
			$("#msg_cargando3").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : 	function (resultado) {
						$('#box_cargando').css({'display':'none'});	
						if (resultado){														
							document.getElementById('tablaarticulos').innerHTML = resultado;
							$('#box_cargando3').css({'display':'none'});	
						} else {
							$('#box_error').css({'display':'block'});
							$("#msg_error").html("Error al consultar los registros");
						}
					},
		error: function () {
			$('#box_cargando').css({'display':'none'});	
			$('#box_cargando3').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("Error al consultar los artículos.");	
		},								
	});	
}