<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}
$_max_rendicion = 0;
$_rendID 		= 0;

//Busco último nro rendición del usuario
$_nro_rendicion = (!isset($_POST['nro_rendicion'])) ? 0  : $_POST['nro_rendicion'];
if (empty($_nro_rendicion)) {
	$_nro_rendicion	=	DataManager::getMaxRendicion($_SESSION["_usrid"]);
	if(empty($_nro_rendicion)){$_nro_rendicion = 0;}
}

//Consulto si la rendición fue enviada (activa)
$_rendActiva	=	0;
$_rendiciones	=	DataManager::getRendicion($_SESSION["_usrid"], $_nro_rendicion, '1');
if (count($_rendiciones) > 0) {
	$_rendActiva	=	1; //Rendición Activa
}

/*************************************************************************************************/ 
$_button_print	=	sprintf( "<a id=\"imprimir\" href=\"detalle_rendicion.php?nro_rendicion=%s\" target=\"_blank\" title=\"Imprimir\" >%s</a>", $_nro_rendicion, "<img src=\"/pedidos/images/icons/icono-print.png\" border=\"0\" />");
$_button_print2	=	sprintf( "<input type=\"submit\" title=\"Imprimir\" value=\"Ver\" target=\"_blank\" />");
$_button_nuevo	= 	sprintf( "<a id=\"open_talonario\" class=\"botones_rend\" title=\"Agregar\">%s</a>", "<img src=\"/pedidos/images/icons/icono-nuevo50.png\" border=\"0\" />");
$_btn_close_popup 	= 	sprintf( "<a id=\"close-talonario\" href=\"#\">%s</a>", "<img id=\"close_rend\" src=\"/pedidos/images/icons/icono-close20.png\" border=\"0\" align=\"absmiddle\" />");
$_button_eliminar	=	sprintf( "<a id=\"eliminar_talonario\" title=\"Eliminar\" href=\"#\" onclick=\"dac_deleteRecibo()\">%s</a>", "<img id=\"close_rend\" src=\"/pedidos/images/icons/icono-eliminar.png\" border=\"0\" />");
$_button_enviar		=	sprintf( "<a id=\"enviar\" title=\"Enviar Rendicion\" href=\"#\" onclick=\"dac_EnviarRendicion()\">%s</a>", "<img id=\"enviar_rend\" src=\"/pedidos/images/icons/icono-send50.png\" border=\"0\" />");
//---------------------//
 $_btn_anularrendi		= 	sprintf("<input type=\"button\" id=\"btsend\" value=\"Anular\" title=\"Anular Rendici&oacute;n\" onclick=\"javascript:dac_Anular_Rendicion()\"/>");
?>                
<script type="text/javascript" src="logica/jquery/jquery.rendicion.js"></script>
<script type="text/javascript" src="logica/jquery/jquery.add.factura.js"></script>
<script type="text/javascript" src="logica/jquery/jquery.add.cheque.js"></script>
<script type="text/javascript" src="logica/jquery/jqueryHeader.js"></script>

<script>
function dac_GrabarEfectivo(ret, dep){		
	$.ajax({
		type: 'GET',
		url	: 'logica/ajax/update.rendicion.php',
		data:{	ret			: ret,
				dep			: dep,
				rendicion	: <?php echo $_nro_rendicion; ?>
				},				
		beforeSend: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});					
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
		},
		success : function (result) {
			$('#box_cargando').css({'display':'none'});
			if (result.replace("\n","") === '1'){
				$('#box_confirmacion').css({'display':'block'});
				$("#msg_confirmacion").html('Los cambios se han guardado');	
				document.getElementById("total_efectivo").value = document.getElementById("total").value -  ret - dep;								
			} else {
				$('#box_error').css({'display':'block'});
				$("#msg_error").html(result);	
			}																								
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("ERROR! al guardar los Pagos");
		}								
	});
}

function dac_EnviarRendicion(){
	$.ajax({
		url : 'logica/ajax/enviar.rendicion.php',
		data : {nro_rendicion	: <?php echo $_nro_rendicion;?>},
		type : 'GET',
		success : function (result) { 
					if (result){
						if (result.replace("\n","") === '1'){
							alert("Rendición enviada.");
							javascript:location.reload();
						} else {
							alert(result);
						}						
					}																	
				},
		error: function () {
				alert("Error");
			}								
	});
}
</script>

<div class="box_down" style="overflow: auto;"> 
	<!--div class="acco_bloq_2"-->  
		<fieldset id='box_observacion' class="msg_alerta">
			<div id="msg_atencion" align="center"></div>       
		</fieldset>
		<fieldset id='box_error' class="msg_error">          
			<div id="msg_error" align="center"></div>
		</fieldset>
		<fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;">  
			<div id="msg_cargando" align="center"></div>      
		</fieldset>
		<fieldset id='box_confirmacion' class="msg_confirmacion">
			<div id="msg_confirmacion" align="center"></div>      
		</fieldset>
	<!--/div-->  
	<?php if ($_SESSION["_usrrol"]!= "M"){ 
		//Consulta última Rendición del usuario
		$_rendicion	=	DataManager::getDetalleRendicion($_SESSION["_usrid"], $_nro_rendicion);		
		if (count($_rendicion) > 0) { 
			$readonly = '';?>
			<!--div id="muestra-rendicion" style="overflow-x: auto;" align="center"-->            
				<table id="tabla_rendicion" cellpadding="0" cellspacing="0" border="1" class="display">
					<thead>                    
						<tr align="left">
							<th colspan="7" align="center"><?php echo $_SESSION["_usrname"]; ?></th>
							<th colspan="6" align="center"> <?php 
								echo $_button_nuevo;	
								if ($_SESSION["_usrrol"]!="M"){echo $_button_print;}?> 
								<input id="deleterendicion" type="text" hidden /> <?php	
								if($_rendActiva == 1) {								
									echo $_button_eliminar;	
									echo $_button_enviar; 
								} else { ?>
									<b>RENDICI&Oacute;N YA ENVIADA</b>
									<?php 
									$readonly = 'readonly';
								} ?>
							</th>
							<th colspan="6">
								<form id="f_consultar" action="#" method="post" enctype="multipart/form-data">
									<div class="bloque_7">
										<br>
										<label for="nro_rendicion" >Rendici&oacute;n: </label>
									</div>
									<div class="bloque_8">
										<select id="nro_rendicion" name="nro_rendicion" style="border:none;" onChange="document.getElementById('ver').click()"/> <?php 
											$_max_rendicion	=	DataManager::getMaxRendicion($_SESSION["_usrid"]);	
											for($i = $_max_rendicion; $i > 0; $i--){
												if ($i == $_nro_rendicion){ ?>
													<option id="<?php echo $i; ?>" name="<?php echo $i; ?>" value="<?php echo $i; ?>" selected><?php echo $i; ?></option><?php
												} else { ?>
													<option id="<?php echo $i; ?>" name="<?php echo $i; ?>" value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php
												}	
											} ?>
										</select>
									</div>										
									<input hidden id="ver" name="ver" Value="Ver" type="submit"/>
								</form>         	
							</th>
						</tr>

						<tr align="center" style="font-weight:bold; background-color:#EAEAEA" height="40px">
							<td colspan="3" align="center" style="background-color:#d9ebf4; color:#2D567F" >DATOS CLIENTE</td>
							<td colspan="2" align="center" style="background-color:#d9ebf4; color:#2D567F" >RECIBO</td>
							<td colspan="2" align="center" style="background-color:#d9ebf4; color:#2D567F" >FACTURA</td>
							<td colspan="3" align="center" style="background-color:#d9ebf4; color:#2D567F" >IMPORTE</td>
							<td colspan="3" align="center" style="background-color:#d9ebf4; color:#2D567F" >FORMA DE PAGO</td>
							<td colspan="4" align="center" style="background-color:#d9ebf4; color:#2D567F" >PAGO POR BANCO</td>
							<td colspan="2" align="center" style="background-color:#d9ebf4; color:#2D567F" >OTROS</td>
						</tr>

						<tr style="font-weight:bold;" height="30px">  <!-- Títulos de las Columnas -->
							<td align="center" hidden>Idrend</td>
							<td align="center" >C&oacute;digo</td>
							<td align="center" >Nombre</td>
							<td align="center" >Zona</td>
							<td align="center" >Tal</td>
							<td align="center" >Nro</td>
							<td align="center" >Nro</td>
							<td align="center" >Fecha</td>
							<td align="center" >Bruto</td>
							<td align="center" >Dto</td>
							<td align="center" >Neto</td>
							<td align="center" >Efectivo</td>
							<td align="center" >Transf</td>
							<td align="center" >Retenci&oacute;n</td>
							<td align="center" >Banco</td>
							<td align="center" >N&uacute;mero</td>
							<td align="center" >Fecha</td>
							<td align="center" >Importe</td>
							<td align="center" >Observaci&oacute;n</td>
							<td align="center" >Diferencia</td>
						</tr>
					</thead>

					<tbody> 
						<input id="ultimocssth" value="1" hidden/>
						<input id="ultimorecibo" value="1" hidden/>

						<?php 
						//SACAMOS LOS REGISTROS DE LA TABLA
						$total_efectivo 	= 0;
						$id_anterior 		= 0;
						$id_cheque_anterior = 0;
						$id_cheque 			= array();
						$idfact_anterior 	= 0;
						$ocultar 			= 0;	
						$total_transfer		= 0;
						$total_retencion	= 0;
						$total_diferencia	= 0;
						$total_importe		= 0;
						foreach ($_rendicion as $k => $_rend){
							$_rend				=	$_rendicion[$k];
							$_rendID			=	$_rend['IDR'];
							$_rendCodigo		= 	$_rend['Codigo'];
							$_rendNombre		= 	($_rendCodigo == 0) ? "" :  $_rend['Nombre'];
							$_rendZona			= 	$_rend['Zona'];
							$_rendTal			= 	$_rend['Tal'];
							$_rendIDRecibo		= 	$_rend['IDRecibo'];
							$_rendRnro			= 	$_rend['RNro'];							
							$_rendFnro			=	$_rend['FNro'];
							$_rendFactFecha 	= 	$_rend['FFecha'];
							$_rendBruto			=	$_rend['Bruto'];
							$_rendDto			=	($_rend['Dto']		 	== '0')		?	''	:	$_rend['Dto'];
							$_rendNeto			=	$_rend['Neto'];
							$_rendEfectivo		=	($_rend['Efectivo'] 	== '0.00')	?	''	:	$_rend['Efectivo'];
							$_rendTransf		=	($_rend['Transf']		== '0.00')	?	''	:	$_rend['Transf'];
							$_rendRetencion		= 	($_rend['Retencion'] 	== '0.00')	?	''	:	$_rend['Retencion'];
							$_rendIDCheque		=	$_rend['IDCheque'];
							$_rendChequeBanco	=	$_rend['Banco'];
							$_rendChequeNro		= 	$_rend['Numero'];
							$_rendChequefecha	= 	$_rend['Fecha'];
							$_rendChequeImporte = 	($_rend['Importe'] 		== '0.00')	?	''	:	$_rend['Importe'];	
							$_rendObservacion	=	$_rend['Observacion'];
							$_rendDiferencia	= 	($_rend['Diferencia'] 	== '0.00')	?	''	:	$_rend['Diferencia'];

							$_rendDepositoVend	=	($_rend['Deposito'] 	== '0.00')	?	0	:	$_rend['Deposito'];
							$_rendRetencionVend	=	($_rend['RetencionVend']== '0.00')	?	0	:	$_rend['RetencionVend'];	

							$_estilo	=	((($_rendRnro % 2) == 0)? "par" : "impar"); 

							//**************************************************//
							//Controlo si repite registros de cheques y facturas//
							//**************************************************//
							if ($id_anterior == $_rendIDRecibo){

								//********************//
								// Al hacer el cambio a CUENTAS (que usa clientes en cero)
								// debo discriminar los registros con nro cuenta cero y sin observación de ANULADO
								//********************//
								if($_rendCodigo == 0 && $_rendObservacion == "ANULADO") { } else {


									//Busco cheque repetidos en la misma rendición para NO mostrarlos												
									for($j = 0; $j < (count($id_cheque)); $j++){
										if($id_cheque[$j] == $_rendIDCheque){ $ocultar = 1;}
									}

									if ($ocultar == 1 && $_rendIDCheque != ""){														
										if ($idfact_anterior != $_rendFnro){
											//CASO = 3; //CASO "C" VARIAS facturas - UN CHEQUE.
											?><tr id="<?php echo $_rendIDRecibo; ?>" class="<?php echo $_estilo; ?>" onclick="dac_SelectFilaToDelete(<?php echo $_rendIDRecibo; ?>, <?php echo $_rendRnro; ?>)">  <?php
											?> <td hidden> <?php echo $_rendID; ?> </td>
											<td> <?php echo $_rendCodigo; ?> </td>
											<td> <?php echo $_rendNombre; ?> </td>
											<td> <?php echo $_rendZona; ?> </td>
											<td> <?php echo $_rendTal; ?> </td>
											<td> <?php echo $_rendRnro; ?> </td>
											<td> <?php echo $_rendFnro; ?> </td>
											<td> <?php echo $_rendFactFecha; ?> </td>
											<td align="right"> <?php echo $_rendBruto; ?> </td>
											<td align="center"> <?php echo $_rendDto; ?> </td>
											<td> <?php echo $_rendNeto; ?> </td>
											<td align="right"> <?php echo $_rendEfectivo; ?> </td>
											<td align="right"> <?php echo $_rendTransf; ?> </td>
											<td align="right"> <?php echo $_rendRetencion; ?> </td>
											<td hidden> <?php echo $_rendIDCheque; ?> </td>
											<td></td> <td></td> <td></td> <td></td> <td></td> <td></td>							
											<?php	

											//******************************//
											//	CALCULOS PARA LOS TOTALES	//	
											$total_efectivo 	=	$total_efectivo + floatval($_rendEfectivo);		
											$total_transfer 	=	$total_transfer + floatval($_rendTransf);
											$total_retencion	=	$total_retencion + floatval($_rendRetencion);
										} 
									} else {
										if ($idfact_anterior == $_rendFnro){
											//CASO = 2; //CASO "B". VARIOS CHEQUES - UNA factura
											?><tr id="<?php echo $_rendIDRecibo; ?>" class="<?php echo $_estilo; ?>" onclick="dac_SelectFilaToDelete(<?php echo $_rendIDRecibo; ?> , <?php echo $_rendRnro; ?>)">  <?php
											?> <td hidden> <?php echo $_rendID; ?> </td>
											<td></td> <td><?php //echo $_rendNombre; ?></td> 
											<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
											<td hidden> <?php echo $_rendIDCheque; ?> </td>
											<td> <?php echo $_rendChequeBanco; ?> </td>
											<td> <?php echo $_rendChequeNro; ?> </td>
											<td> <?php echo $_rendChequefecha; ?> </td>
											<td align="right"> <?php echo $_rendChequeImporte; ?> </td>                            
											<td></td> <td></td> <?php
										} else {
											//CASO = 1; //CASO "A" SIN CHEQUES - VARIAS facturas.
											?><tr id="<?php echo $_rendIDRecibo; ?>" class="<?php echo $_estilo; ?>" onclick="dac_SelectFilaToDelete(<?php echo $_rendIDRecibo; ?>, <?php echo $_rendRnro; ?>)">  <?php
											?> <td hidden> <?php echo $_rendID; ?> </td>
											<td> <?php //echo $_rendCodigo ; ?> </td>
											<td> <?php //echo $_rendNombre; ?> </td>
											<td> <?php //echo $_rendZona; ?> </td>
											<td> <?php echo $_rendTal; ?> </td>
											<td> <?php echo $_rendRnro; ?> </td>
											<td> <?php echo $_rendFnro; ?> </td>
											<td> <?php echo $_rendFactFecha; ?> </td>
											<td align="right"> <?php echo $_rendBruto; ?> </td>
											<td align="center"> <?php echo $_rendDto; ?> </td>
											<td align="right"> <?php echo $_rendNeto; ?> </td>
											<td align="right"> <?php echo $_rendEfectivo; ?> </td>
											<td align="right"> <?php echo $_rendTransf; ?> </td>
											<td align="right"> <?php echo $_rendRetencion; ?> </td>
											<td hidden> <?php echo $_rendIDCheque; ?> </td>
											<td> <?php echo $_rendChequeBanco; ?> </td>
											<td> <?php echo $_rendChequeNro; ?> </td>
											<td> <?php echo $_rendChequefecha; ?> </td>
											<td align="right"> <?php echo $_rendChequeImporte; ?> </td> 
											<td></td>  <td></td> <?php	 

											//**********************************
											//	CALCULOS PARA LOS TOTALES		
											$total_efectivo 	=	$total_efectivo + floatval($_rendEfectivo);	
											$total_transfer 	=	$total_transfer + floatval($_rendTransf);	
											$total_retencion 	=	$total_retencion + floatval($_rendRetencion);			
										}
									}
								} // fin if anulado
							} else {
								//***********************************
								//si cambia el nro de cheque resetea id_cheque y completa toda la fila de datos
								//**********************************	
								unset($id_cheque);	
								$id_cheque = array();
								?>
								<tr id="<?php echo $_rendIDRecibo; ?>" class="<?php echo $_estilo; ?>" onclick="dac_SelectFilaToDelete(<?php echo $_rendIDRecibo; ?>, <?php echo $_rendRnro; ?>)"> 
								<td hidden> <?php echo $_rendID; ?> </td>
								<td> <?php echo $_rendCodigo ; ?> </td>
								<td> <?php echo $_rendNombre; ?> </td>
								<td> <?php echo $_rendZona; ?> </td>
								<td> <?php echo $_rendTal; ?> </td>
								<td> <?php echo $_rendRnro; ?> </td>
								<td> <?php echo $_rendFnro; ?> </td>
								<td> <?php echo $_rendFactFecha; ?> </td>
								<td align="right"> <?php echo $_rendBruto; ?> </td>
								<td align="center"> <?php echo $_rendDto; ?> </td>
								<td align="right"> <?php echo $_rendNeto; ?> </td>
								<td align="right"> <?php echo $_rendEfectivo; ?> </td>
								<td align="right"> <?php echo $_rendTransf; ?> </td>
								<td align="right"> <?php echo $_rendRetencion; ?> </td>
								<td hidden> <?php echo $_rendIDCheque; ?> </td>
								<td> <?php echo $_rendChequeBanco; ?> </td>
								<td> <?php echo $_rendChequeNro; ?> </td>
								<td> <?php echo $_rendChequefecha; ?> </td>
								<td align="right"> <?php echo $_rendChequeImporte; ?> </td>                            
								<td> <?php echo $_rendObservacion; ?> </td> 
								<td align="right"> <?php echo $_rendDiferencia; ?></td> <?php

								//**********************************
								//	CALCULOS PARA LOS TOTALES			
								$total_efectivo 		=	$total_efectivo + floatval($_rendEfectivo);	
								$total_transfer 		=	$total_transfer + floatval($_rendTransf);	
								$total_retencion 		=	$total_retencion + floatval($_rendRetencion);
								$total_diferencia 		= 	$total_diferencia + floatval($_rendDiferencia);										
							} 
							 ?>
						</tr><?php	

							//**********************************						
							//CALCULOS PARA TOTALES IMPORTE. Sin discriminar si hay varios cheques
							//**********************************
							if ($id_cheque_anterior != $_rendIDCheque){ 
								//controla que el cheque no pertenezca a varias facturas									 
								for($j = 0; $j < (count($id_cheque)); $j++){
									if($id_cheque[$j] == $_rendIDCheque){ $ocultar = 1; }
								}										
								if ($ocultar != 1){
									$total_importe = $total_importe + floatval($_rendChequeImporte);
								}
							}
							//**********************************
							if ($_rendIDCheque != ""){
								if ($ocultar != 1){$id_cheque[] = $_rendIDCheque;} 
							}							

							$ocultar 			= 0;
							$idfact_anterior 	= $_rendFnro;
							$id_anterior 		= $_rendIDRecibo; //Cierrre de calculo de TOTALES		
							$id_cheque_anterior = $_rendIDCheque;				
						} //FIN del FOR Rendicion ?>       
						<input id="rendicionid" value="<?php echo $_rendID; ?>" hidden/>     
					</tbody>

					<tfoot>
						<tr>
							<th colspan="10" align="right" style="background-color:#d9ebf4; color:#2D567F;">TOTALES</th>
							<th align="right" ><?php
								if ($total_efectivo != 0) {	
									$total = $total_efectivo;	
									$total_efectivo = $total_efectivo - (floatval($_rendRetencionVend) + floatval($_rendDepositoVend)); 
									?>	
									<input hidden id="total" name="total" type="text" value="<?php echo $total ?>" readonly/>
									<input id="total_efectivo" name="total_efectivo" type="text" style="width:50px; text-align:right; font-weight:bold; border:none" value="<?php echo round($total_efectivo, 2 ); ?>" readonly/> <?php 
								}?>
							</th>
							<th align="right" style="font-weight:bold;"><?php  if ($total_transfer != 0) {echo "$".$total_transfer;} ?></th>
							<th align="right" style="font-weight:bold;"><?php  if ($total_retencion != 0) {echo "$".$total_retencion;} ?></th>
							<th colspan="3" style="background-color:#d9ebf4;"></th>
							<th align="right"><?php  if ($total_importe != 0) {echo "$".$total_importe;} ?></th>
							<th style="background-color:#d9ebf4;"></th>
							<th align="right"><?php  if ($total_diferencia != 0) {echo "$".$total_diferencia;} ?></th>                        
						</tr>
						<tr>
							<th colspan="19" style="height:20px; "></th>
						</tr>
						<tr >
							<th colspan="13" align="right" style="background-color:#d9ebf4; color:#2D567F">
								<button id="open-recibo" hidden></button>
								<label>Boleta de Dep&oacute;sito: </label>
							</th>
							<th>
								<input id="deposito" name="deposito" type="text" value="<?php  if ($_rendDepositoVend != 0) {echo $_rendDepositoVend;} ?>" onChange="dac_GrabarEfectivo(ret.value, deposito.value);" onkeydown="javascript:ControlComa(this.id, this.value);" onkeyup="javascript:ControlComa(this.id, this.value);" <?php echo $readonly; ?> style="border:none; font-weight:bold; text-align:center;" />                    	
							</th> 
							<th align="right" style="background-color:#d9ebf4; color:#2D567F">
								<label>Retenci&oacute;n: </label>                        	
							</th> 
							<th >
							<input id="ret" name="ret" type="text" value="<?php  if ($_rendRetencionVend != 0) {echo $_rendRetencionVend;} ?>" onChange="dac_GrabarEfectivo(ret.value, deposito.value);" onkeydown="javascript:ControlComa(this.id, this.value);" onkeyup="javascript:ControlComa(this.id, this.value);" <?php echo $readonly; ?> style="border:none; font-weight:bold; text-align:center;"/>       
							</th>  
							<th colspan="3" style="background-color:#d9ebf4;">
							</th>           
						</tr>
					</tfoot>
				</table>

			<!--/div--> <!-- FIN muestra-rendicion -->  <?php
		} else { 
			if ($_SESSION["_usrrol"]== "V" || $_SESSION["_usrrol"]== "A") {?>
				<table border="0" width="100%">
					<tr align="center">
						<th align="center">
							<button id="open-recibo" hidden></button>
							<?php echo "Nueva Rendici&oacute;n </br>".$_button_nuevo; ?>
						</th>
					</tr>
				</table><?php 
			}
		} 
	} ?>    
 </div> <!-- FIN box_bod -->
   
<!------------------------>
<!--	ADMINISTRAR		-->
<?php if ($_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){?> 
	<div class="box_seccion"> 
		<div class="barra"> 
			<h1>Administrar</h1> <hr>
		</div> <!-- Fin barra --> 		
		<fieldset>
			<legend>Rendiciones</legend> 
			<form name="fm_anularrendi" method="POST" action="detalle_rendicion.php"> 
				<div class="bloque_7">
					<input id="nrorendi_anular" name="nro_anular" type="text" placeholder="* NRO" size="5"/>          
				</div>				
				<div class="bloque_3">
					<select id="vendedor" name="vendedor"/> 
						<option>Vendedor...</option> <?php
						$vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
						if (count($vendedores)) {	
							foreach ($vendedores as $k => $vend) { ?>
								<option id="<?php echo $vend["unombre"]; ?>" value="<?php echo $vend["uid"]; ?>"><?php echo $vend["unombre"]; ?></option><?php
							}                            
						} ?>
					</select>
				</div>
				<div class="bloque_5"> <?php echo $_button_print2; ?> </div> 
				<?php if($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?>
					<div class="bloque_5">  
						<?php echo $_btn_anularrendi;  ?>
					</div>  						
				<?php } ?>
				<hr>
			</form>
		</fieldset>

		<?php
		$recAnuladosPendientes	= DataManager::getTalonariosIncompletos();
		if (count($recAnuladosPendientes)) { ?>
			<fieldset>
				<legend> <?php echo count($recAnuladosPendientes); ?> Talonarios Incompletos</legend>			
				<div class="desplegable"> 
					<table>
						<thead>   
							<tr>
								<td>Talonario</td>
								<td>Recibo</td>
								<td>Usuario</td>
							</tr> 
						</thead> 
						<body><?php								
						foreach ($recAnuladosPendientes as $k => $anulado) { ?>
							<tr>
								<td ><?php echo $anulado["rectalonario"]; ?></td>
								<td ><?php echo $anulado["recnro"]; ?></td>
								<td ><?php echo $anulado["unombre"]; ?></td>
							</tr>
						<?php
						}  ?>
						</body>
					</table>
				</div>
			</fieldset>
		<?php } ?>	

		<?php
		$recAnuladosPendientes	= DataManager::getRecibosAnuladosPendientes();
		if (count($recAnuladosPendientes)) { ?>
			<fieldset>
				<legend> <?php echo count($recAnuladosPendientes); ?> Recibos ANULADOS de talonarios incompletos </legend>
				<div class="desplegable"> 
					<table>
						<thead>   
							<tr>
								<td>Talonario</td>
								<td>Recibo</td>
								<td>Usuario</td>
							</tr>
						</thead> 
						<body><?php								
						foreach ($recAnuladosPendientes as $k => $anulado) { ?>
							<tr>
								<td ><?php echo $anulado["rectalonario"]; ?></td>
								<td ><?php echo $anulado["recnro"]; ?></td>
								<td ><?php echo $anulado["unombre"]; ?></td>
							</tr>
						<?php
						}  ?>
						</body>
					</table> 
				</div>
			</fieldset>
		<?php } ?>
	</div> <!-- Fin boxseccion-->
<?php }?>

<div id="popup-flotante"> <!-- INICIO popup-flotante POPUP TALONARIO DE RECIBOS--> 
	<div id="popup-talonario"> <!-- INICIO popup-talonario--> 
		<fieldset id='box_observacion2' class="msg_alerta">
			<div id="msg_atencion2" align="center"></div>       
		</fieldset>
		<fieldset id='box_error2' class="msg_error">          
			<div id="msg_error2" align="center"></div>
		</fieldset>
		<fieldset id='box_cargando2' class="msg_informacion" style="alignment-adjust:central;">  
			<div id="msg_cargando2" align="center"></div>      
		</fieldset>
		<fieldset id='box_confirmacion2' class="msg_confirmacion">
			<div id="msg_confirmacion2" align="center"></div>      
		</fieldset>
		
		<div class="content-popup-talonario"> <!-- INICIO content-popup-talonario--> 
			<div class="close-talonario"> <?php echo $_btn_close_popup; ?> </div>
			
			<form id="fm_nvo_recibo" name="fm_nvo_recibo" method="POST" enctype="multipart/form-data"> 
				<input id="rendid" type="text" name="rendid" value="<?php echo $_rendID;?>" hidden/>	 
				<!-- Recibo --> 
				<div id="rec_recuadro">
					<div class="bloque_7">
						<input id="nro_tal" name="nro_tal" type="text" placeholder="Talonario" style="text-align:center;"/>
					</div>
					<div class="bloque_7">
						<input id="nro_rec" name="nro_rec" type="text" placeholder="Recibo" style="text-align:center"/> 
					</div>  
					<div class="bloque_8">                         
						<input id="ir" type="button" name="ir" value="+" title="Abrir Recibo" onClick="dac_BuscarRecibo(nro_rec.value, nro_tal.value);" />
					</div>
					<div class="bloque_7">  
						<input id="nvo_tal" type="button" name="nvo_tal" value="Nuevo Talonario" onClick="dac_NuevoTalonario(nro_tal.value);"/>
					</div>
					<div class="bloque_8">  	 
						<input id="anular" type="button" name="anular" value="A" onClick="dac_AnularRecibo(rendid.value, nro_rend.value, nro_tal.value, nro_rec.value);"/>
					</div>
					<hr>
				</div><!-- FIN rec_recuadro -->

				<div id="popup-recibo">  <!-- INICIO popup-recibo-->
					<div class="content-popup-recibo" align="center"><!-- Número de Rendición-->  
						<div class="bloque_3">
							<b>Rendici&oacute;n de Cobranza N&uacute;mero:</b>
						</div>						
						<div class="bloque_8">
							<input id="nro_rend" name="nro_rend" type="text" size="2" style="font-weight:bold; text-align:center; border:none;" value="<?php if ($_nro_rendicion == "") {echo 1;} else { echo $_nro_rendicion; } ?>" readonly/>
						</div>						
						<div class="bloque_8">
							<input id="nvacbza" type="button" name="nvacbza" value="+" onClick="javascript:dac_NuevaRendicion('<?php echo $_max_rendicion; ?>')" />
						</div>
						<hr>
					</div><!--FIN número de Rendición --> 

					<div class="content-popup-recibo"> <!-- CONTENIDO facturas-->  
						<button id="close-recibo" hidden></button>
						<div class="bloque_1"> 
							<label>Factura</label>
						</div>
						
						<div id="fact_1">
							<div class="bloque_7">
								<label for="nro_factura">Nro.</label>
								<input id="nro_factura1" name="nro_factura[]" type="text" maxlength="10" onBlur="dac_ValidarNumero(this.value, this.id)"/> 
							</div>

							<div class="bloque_7">
								<label for="fecha_factura">Fecha</label>
								<input id="fecha_factura1" name="fecha_factura[]" type="text" size="10" maxlength="10" onKeyUp="javascript:dac_ValidarCampoFecha(this.id, this.value, 'KeyUp');" onBlur="javascript:dac_ValidarCampoFecha(this.id, this.value, 'Blur');" placeholder="dd-mm-aaaa"/>	
							</div>    

							<div class="bloque_5">
								<label>Cuenta</label>
								<select id="nombrecli1" name="nombrecli[]"/>
									<option > Seleccione Cuenta... </option> <?php
									$_clientes	= DataManager::getCuentas(0, 0, 1, 1, "'C'", $_SESSION["_usrzonas"], 2);
									if (count($_clientes)) {
										foreach ($_clientes as $k => $_cliente) {
											$_cliid		 	= 	$_cliente["ctaidcuenta"];
											$_clinombre	 	= 	$_cliente["ctanombre"];
											?>  
											<option id="<?php echo $_clinombre; ?>" name="<?php echo $_clinombre; ?>" value=<?php echo "'".$_cliid."-".$_clinombre."'"; ?> ><?php echo $_clinombre." (".$_cliid.")"; ?></option>
											<?php 
										}
									}

									?> 
									<option id="otro" name="otro" value="999999-otro">Otro Cliente...</option>		
								</select>	
							</div>    
							<div class="bloque_7">
								<label for="importe_bruto"> A pagar</label>	
								<input id="importe_bruto1" name="importe_bruto[]" type="text" onBlur="dac_ValidarNumero(this.value, this.id)"/>
							</div> 
							<div class="bloque_7">
								<label for="pago_efectivo">Efectivo</label>
								<input id="pago_efectivo1" name="pago_efectivo[]" type="text" onBlur="dac_ValidarNumero(this.value, this.id)"/>	
							</div> 
							<div class="bloque_7">	
								<label for="importe_dto"> % DTO.</label>
								<input id="importe_dto1" name="importe_dto[]" type="text" onBlur="dac_ValidarNumero(this.value, this.id)" maxlength="2"/>
							</div> 
							<div class="bloque_7">	
								<label for="pago_transfer">Transfer</label>
								<input id="pago_transfer1" name="pago_transfer[]" type="text" onBlur="dac_ValidarNumero(this.value, this.id)"/>
							</div>
							<div class="bloque_7">	
								<label for="importe_neto">Neto</label>
								<input id="importe_neto1" name="importe_neto[]" type="text" style="background-color:#CCC;" readonly/> 
							</div> 
							<div class="bloque_7">	
								<label for="pago_retencion">Retenci&oacute;n</label>
								<input id="pago_retencion1" name="pago_retencion[]" type="text" onBlur="dac_ValidarNumero(this.value, this.id)"/>
							</div>
						
							<div class="bloque_7">
								<input id="btnuevo_1" class="btn_plus" type="button" value="+">
							</div>
							
							<hr style="border-bottom: 2px solid #CCC;"> 
						</div> <!--FIN DATOS fact-->
					</div>  <!--FIN CONTENIDO facturas-->

					<div class="content-popup-recibo"><!--CONTENIDO cheques--><!--DATOS DE BANCO que se clonan -->
						<div class="bloque_1">		
							<label>Cheque</label>	
						</div>						
						<hr>						
						<div id="bank_1"> 
							<div class="bloque_1">
								<label>Banco</label>
								<select id="pagobco_nombre1" name="pagobco_nombre[]" />
									<option>Seleccione Banco...</option> <?php
										$_bancos	=	DataManager::getBancos();
										if ($_bancos) {	
											foreach ($_bancos as $k => $_bank){
												$_bank 			= $_bancos[$k];
												$_bconombre		= $_bank["nombre"];
												?> <option id="<?php echo $_bconombre; ?>" value=<?php echo "'".$_bconombre."'"; ?> > <?php echo $_bconombre; ?> </option> <?php
											}
										} ?> 
								</select> 
							</div>							
							<div class="bloque_7">
								<input id="pagobco_nrocheque1" name="pagobco_nrocheque[]" type="text" placeholder="Nro. Cheque" onBlur="dac_ValidarNumero(this.value, this.id)"/>
							</div>
							
							<div class="bloque_7">
								<input id="bco_fecha1" name="bco_fecha[]" type="text" placeholder="Fecha" maxlength="10" onKeyUp="javascript:dac_ValidarCampoFecha(this.id, this.value, 'KeyUp');" onBlur="javascript:dac_ValidarCampoFecha(this.id, this.value, 'Blur');"/>
							</div>
							
							<div class="bloque_7">                     	  
								<input id="pagobco_importe1" name="pagobco_importe[]" type="text" placeholder="Importe" onBlur="dac_ValidarNumero(this.value, this.id)"/>
							</div>
							
							<div class="bloque_8"> 
								<input id="boton_1" class="btn_checque_plus" type="button" value="+"/>
							</div>	
							<hr style="border-bottom: 2px solid #CCC;"> 
						</div>
					</div> <!--FIN CONTENIDO cheques--> 

					<div class="content-popup-recibo" align="center"> <!--otros DATOS-->
						<div class="bloque_5">
							<div class="rec-contenido">  
								<textarea id="observacion" name="observacion" type="text" style="resize:none;" cols="16" rows="10" placeholder="Observaci&oacute;n"></textarea>
							</div>
						</div>
							
						<div class="bloque_5"> 
							<div class="rec-contenido">  
								<input id="diferencia" name="diferencia" type="text" placeholder="Diferencia" style="background-color:#CCC;" onBlur="dac_ValidarNumero(this.value, this.id)" readonly/>	
							</div>
						</div>
						<hr> 
					</div> <!-- FIN otros DATOS-->

					<div class="bloque_5"> </div>
					<div class="bloque_7">
						<input id="enviar_form" name="enviar_form" type="button" value="Enviar" title="Agregar Registro" onClick="dac_EnviarRecibo();"/>
					</div>

					<div class="bloque_7">
						<input id="close-talonario" type="button" name="cerrar" value="Cancelar" onClick="document.getElementById('close-talonario').click();"/>
					</div>
						                     
					<hr> 
				</div><!-- fin popup-recibo-->                                
			</form>      
		</div> <!-- FIN content-popup-talonario -->                
	</div><!-- FIN popup-talonario -->  
</div><!-- FIN POPUP popup-flotante -->  