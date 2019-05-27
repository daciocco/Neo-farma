<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$backURL			= empty($_REQUEST['backURL']) 	? '/pedidos/rendicion/': $_REQUEST['backURL'];
$error				= 0;
$_msg				= "";
$_usrname			= "";
$_rendiciones 		= 0;
$_rendActiva		= 0;
$_rendDepositoVend 	= 0;
$_rendRetencionVend	= 0;

if ($_SESSION["_usrrol"]=="V"){
	$_nro_rendicion		=	empty($_REQUEST['nro_rendicion'])	? 1 : $_REQUEST['nro_rendicion'];
	$_usrid				=	$_SESSION["_usrid"];
	$_usrname			=	$_SESSION["_usrname"];
} else {
	$_nro_rendicion		=	empty($_POST['nro_anular'])	? 0 : $_POST['nro_anular'];
	$_usrid				=	empty($_POST['vendedor'])	? 0 : $_POST['vendedor'];	
	/****************/
	/*	CONTROLES	*/
	/****************/
	if($_usrid	== 'Vendedor...'){
		$_msg 	= 	"No seleccionó ningún vendedor </br>";
		$error	=	1;
		//header('Location:' . $backURL);
	} else {
		//Nombre Vendedor
		$_nombreVen		= 	DataManager::getUsuario('unombre', $_usrid);
		/*$_Vendedor		=	DataManager::getVendedor( 1, $_usrid );
		foreach ($_Vendedor as $k => $_Ven){
			//$_Ven		=	$_Vendedor[$k];
			$_nombreVen	=	$_Ven['unombre'];
		}*/
		$_usrname		=	"Consulta de Administraci&oacute;n </br> Vendedor: ".$_nombreVen;
	}

	if($_nro_rendicion	== 0){
		$_msg = "No indic&oacute; ninguna rendici&oacute;n </br>";
	}
	/***************/
}

if($error == 0) {
	//Consulto si la rendición fue enviada (activa)	
	$_rendiciones	=	DataManager::getRendicion($_usrid, $_nro_rendicion, '1');
	if (count($_rendiciones)){
		$_rendActiva	=	1; //rendición activa
	}
}

 $_button_print		= 	sprintf( "<a id=\"imprime\" title=\"Imprimir Rendici&oacute;n\"  onclick=\"javascript:dac_imprimirMuestra('rendicion')\">%s</a>", "<img class=\"icon-print\"/>");
?>

<!DOCTYPE html>
<html >
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>

<body>	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
     	         		
		<div id="rendicion" style="margin:20px;" align="center">  <?php
			$_rendicion 	= array();
			if($error	==	0){
				$_rendicion	=	DataManager::getDetalleRendicion($_usrid, $_nro_rendicion);	
			}
			if (count($_rendicion) > 0) { ?>
				<div id="muestra-rendicion" align="center">            
					<table id="tabla_rendicion" border="1" class="display">
						<thead>                    
							<tr align="left">
								<th colspan="7" align="center"><?php echo $_usrname;?></th>
								<th colspan="7" align="center"> <?php 
									echo "Rendici&oacute;n N: ".$_nro_rendicion; ?>       	
								</th>
								<th colspan="5" align="center"> <?php 
									echo  $_button_print;														
									if($_rendActiva == 0) {	?>	
										<div style="float:right; padding:20px;">RENDICI&Oacute;N YA ENVIADA</div> <?php 
									} ?>
								</th>                                    
							</tr>
							<tr align="center" style="font-weight:bold; background-color:#d9ebf4; color:#2D567F" height="40px">
								<td colspan="3" align="center" style="background-color:#d9ebf4; color:#2D567F" >DATOS CLIENTE</td>
								<td colspan="2" align="center" style="background-color:#d9ebf4; color:#2D567F" >RECIBO</td>
								<td colspan="2" align="center" style="background-color:#d9ebf4; color:#2D567F" >FACTURA</td>
								<td colspan="3" align="center" style="background-color:#d9ebf4; color:#2D567F" >IMPORTE</td>
								<td colspan="3" align="center" style="background-color:#d9ebf4; color:#2D567F" >FORMA DE PAGO</td>
								<td colspan="4" align="center" style="background-color:#d9ebf4; color:#2D567F" >PAGO POR BANCO</td>
								<td colspan="2" align="center" style="background-color:#d9ebf4; color:#2D567F" >OTROS</td>
							</tr>

							<tr style="font-weight:bold; background-color:#EAEAEA" height="30px">  <!-- Títulos de las Columnas -->
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

						<tbody> <?php 
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
								//$_rend				=	$_rendicion[$k];
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

								$_rendDepositoVend	=	($_rend['Deposito'] 	== '0.00')	?	''	:	$_rend['Deposito'];
								$_rendRetencionVend	=	($_rend['RetencionVend']== '0.00')	?	''	:	$_rend['RetencionVend'];	

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
												<td> <?php echo $_rendCodigo ; ?> </td>
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
												//******************************//
												$total_efectivo 	=	$total_efectivo + floatval($_rendEfectivo);		
												$total_transfer 	=	$total_transfer + floatval($_rendTransf);
												$total_retencion	=	$total_retencion + floatval($_rendRetencion);
												//$total_diferencia 	=	$total_diferencia + floatval($_rendDiferencia);
											}/* else {
												//caso en que no debe mostrarse la fila por ser repetida
											}*/
										} else {
											if ($idfact_anterior == $_rendFnro){
												//CASO = 2; //CASO "B". VARIOS CHEQUES - UNA facturas.
												?><tr id="<?php echo $_rendIDRecibo; ?>" class="<?php echo $_estilo; ?>" onclick="dac_SelectFilaToDelete(<?php echo $_rendIDRecibo; ?> , <?php echo $_rendRnro; ?>)">  <?php
												?> <td hidden> <?php echo $_rendID; ?> </td>
												<td></td> <td><?php //echo $_rendNombre; ?></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
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
												//**********************************
												$total_efectivo 		=	$total_efectivo + floatval($_rendEfectivo);							
												$total_transfer 		=	$total_transfer + floatval($_rendTransf);								
												$total_retencion 		=	$total_retencion + floatval($_rendRetencion);		
											}
										}
									} //fin if anulado
								} else {
									//***********************************
									//si cambia el nro de cheque resetea id_cheque y completa toda la fila de datos
									//**********************************	
									unset($id_cheque);	
									$id_cheque	= array();
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
									//**********************************
									$total_efectivo 		=	$total_efectivo + floatval($_rendEfectivo);
									$total_transfer 		=	$total_transfer + floatval($_rendTransf);								
									$total_retencion 		=	$total_retencion + floatval($_rendRetencion);							
									$total_diferencia 		= 	$total_diferencia + floatval($_rendDiferencia);										
								} ?>
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
						</tbody>

					   <tfoot>
							<tr>
								<th colspan="10" align="right" style="height:40px; background-color:#d9ebf4; color:#2D567F;">TOTALES</th>
								<th align="right" style="width:60px; text-align:right; height:40px; font-weight:bold;" ><?php
									if ($total_efectivo != 0) {									
										$total 			= $total_efectivo;
										$total_efectivo = $total_efectivo - (floatval($_rendRetencionVend) + floatval($_rendDepositoVend)); ?>	
										<?php echo number_format(round($total_efectivo,2),2); 
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
								<th colspan="13" align="right" style="background-color:#d9ebf4; color:#2D567F;">
									<label>Boleta de Dep&oacute;sito: </label>
								</th>
								<th style="height:40px; font-weight:bold; text-align:center;">
								   <?php echo $_rendDepositoVend; ?>              	
								</th> 
								<th align="right" style="background-color:#d9ebf4; color:#2D567F;">
									<label>Retenci&oacute;n: </label>                        	
								</th> 
								<th style="height:40px; font-weight:bold; text-align:center;">
									<?php echo $_rendRetencionVend; ?>
								</th>  
								<th colspan="3" style="background-color:#d9ebf4;">
								</th>           
							</tr>
						</tfoot>
					</table>
				</div> <!-- FIN muestra-rendicion -->  <?php 
			} else { ?>
				<div align="center">            
					<table id="tabla_rendicion" border="1" class="display">
						<thead>                    
							<tr align="left">
								<th colspan="7" align="center"><?php echo $_usrname;?></th>
								<th colspan="6" align="center"> <?php 
									echo "Rendici&oacute;n N: ".$_nro_rendicion; ?>       	
								</th>
								<th colspan="6" align="center"> <?php 		
									echo "No existen datos en la rendici&oacute;n indicada. </br>";  
									echo $_msg;?>
								</th>                                    
							</tr>
						<thead>
					</table> 
				</div> <?php					
			}?>    
		</div><!-- FIN RENDICIÓN -->
        
</body>
</html>
