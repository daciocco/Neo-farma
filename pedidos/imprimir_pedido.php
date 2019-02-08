<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$_nropedido	= empty($_REQUEST['nropedido']) ? 0 : $_REQUEST['nropedido'];

?>
<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>    
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->		
            
    <main class="cuerpo">
                   
		<div id="factura" align="center">	
			<div class="cbte" style="overflow:auto; background-color: #FFF; width: 800px;">
				<?php
				if ($_nropedido) {
					$_usr		=	($_SESSION["_usrrol"] !=  "V")	?	NULL	:	$_SESSION["_usrid"];
					$_detalles	= DataManager::getPedidos($_usr, NULL, $_nropedido);
					if ($_detalles) { 
						foreach ($_detalles as $k => $_detalle) {	
							if ($k==0){ 
								$empresa				= 	$_detalle["pidemp"]; 
								$_empresas	= 	DataManager::getEmpresas();
								if ($_empresas) { 
									foreach ($_empresas as $i => $_emp) {
										$_idempresa		= 	$_emp['empid'];
										if ($empresa == $_idempresa){												
											$_nombreemp		= 	$_emp['empnombre'];
											$_diremp		= 	$_emp['empdomicilio'];   
											$_localidademp	= 	" - ".$_emp['emplocalidad'];
											$_cpemp			= 	" - CP".$_emp['empcp'];  
											$_telemp		= 	" - Tel: ".$_emp['empcp']; 
											//$_faxemp		= 	" - Fax: ".$_emp['empfax']; 
											$_correoemp		= 	$_emp['empcorreo']." / ";
										}
									}
									
									//header And footer
									include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
									
								}?>
								

								<div class="cbte_header" style="border-bottom: solid 1px #CCCCCC; color:#373435; padding:15px; height:110px;">
									<div class="cbte_boxheader" style="float: left; width: 350px;">
										<?php echo $cabeceraPedido; ?>  
									</div>  <!-- cbte_boxheader --> 

									<div class="cbte_boxheader_der" align="left" style="float:left; width:365px; border-left:1px solid #CCC; padding-left:30px; font-family: Verdana, Geneva, sans-serif; font-size:11px; line-height:20px; font-weight:bold;"> 
										<h1 style="color:#2D567F;">PEDIDO WEB</h1> 
										<?php echo $_diremp; ?> <?php echo $_cpemp; ?>  <?php echo $_localidademp; ?> <?php echo $_telemp; ?></br>
										<?php echo $_correoemp; ?> www.neo-farma.com.ar</br>
										IVA RESPONSABLE INSCRIPTO
									</div>  <!-- cbte_boxheader --> 
								</div>  <!-- boxtitulo -->
								

								<?php
								$_fecha_pedido		=	$_detalle['pfechapedido'];
								$_idusuario			=	$_detalle['pidusr'];			
								$_nombreusr			= 	DataManager::getUsuario('unombre', $_idusuario); 
								$empresa				= 	$_detalle["pidemp"];    										
								$_idcliente			= 	$_detalle["pidcliente"];  

								$_nombrecli			= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_idcliente, $empresa);

								$_domiciliocli		= 	DataManager::getCuenta('ctadireccion', 'ctaidcuenta', $_idcliente, $empresa)." ".DataManager::getCuenta('ctadirnro', 'ctaidcuenta', $_idcliente, $empresa)." ".DataManager::getCuenta('ctadirpiso', 'ctaidcuenta', $_idcliente, $empresa)." ".DataManager::getCuenta('ctadirdpto', 'ctaidcuenta', $_idcliente, $empresa);										

								$_localidadcli		= 	DataManager::getCuenta('ctalocalidad', 'ctaidcuenta', $_idcliente, $empresa);
								$_codpostalcli		= 	DataManager::getCuenta('ctacp', 'ctaidcuenta', $_idcliente, $empresa);
								
								$_condpago			= 	$_detalle["pidcondpago"];
								$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $_condpago); 
								if (count($condicionesDePago)) { 
									foreach ($condicionesDePago as $k => $condPago) {
										$condPagoCodigo	=	$condPago["IdCondPago"];								
										$_condnombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
										$_conddias	= "(";					
										$_conddias	.= empty($condPago['Dias1CP']) ? '' : $condPago['Dias1CP'];
										$_conddias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
										$_conddias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
										$_conddias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
										$_conddias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
										$_conddias	.= " D&iacute;as)";
									}
								}
								
								$_ordencompra		= 	($_detalle["pordencompra"] == 0)	?	''	:	"<span style=\" color: #2D567F; font-weight:bold;\">Orden Compra: </span><span style=\"font-size: 12px; font-weight:bold;\">".$_detalle["pordencompra"]."</span>";
								$_observacion		=	$_detalle["pobservacion"];

								?>
									<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color:#cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 

										<div class="cbte_box" style="height: auto; line-height: 20px; float:left; width: 33%; font-weight:bold;"><?php echo $_fecha_pedido;?></span></div>
										<div class="cbte_box" align="center" style="height: auto; line-height: 20px; float:left; width: 33%;  font-weight:bold;"><span style=" color: #2D567F;">Nro. Pedido:</span> <?php echo str_pad($_nropedido, 9, "0", STR_PAD_LEFT); ?></div>
										<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%;"><span style=" color: #2D567F; font-weight:bold;"><?php echo $_nombreusr;?></div>
									</div>  <!-- cbte_box_nro -->

									<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color:#cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;">  <!-- background-color:#A0DFFC; -->

										<div class="cbte_box" style="height: auto; line-height: 20px; float:left; width: 16%;"> 
											<span style=" color:#2D567F; font-weight:bold;">
											Cliente: </br>
											Direcci&oacute;n: </br>
											</span>
										</div>  <!-- cbte_box_col -->

										<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%;">  
											<span style="font-size: 14px; font-weight:bold;">
											<?php echo $_idcliente." - ".$_nombrecli;?></br>
											<?php echo $_domiciliocli." - ".$_localidadcli." - ".$_codpostalcli; ?></br>	
											</span>
										</div>  <!-- cbte_box_col -->
									</div>  <!-- cbte_box_nro -->

								
									<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color: #cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 
										<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%;"><span style=" color: #2D567F; font-weight:bold;">Condici&oacute;n de Pago: </span><span style="font-size: 14px; font-weight:bold;"><?php echo $_condpago." | ".$_condnombre." ".$_conddias;?></span></div>
										<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%;"><?php echo $_ordencompra;?></div>

									</div>  <!-- cbte_box_nro -->
								

								<div class="cbte_boxcontent2" style="padding: 15px; overflow:auto; height:auto;">
									<table class="datatab" width="95%" border="0" cellpadding="0" cellspacing="0">
										<thead>
											<tr align="left">
												<th scope="col" width="10%" height="18" align="center">C&oacute;digo</th>
												<th scope="col" width="10%" align="center">Cant</th>
												<th scope="col" width="30%" align="center">Descripci&oacute;n</th>
												<th scope="col" width="10%" align="center">Precio</th>
												<th scope="col" width="10%" align="center">Bonif</th>
												<th scope="col" width="10%" align="center">Dto1</th>
												<th scope="col" width="10%" align="center">Dto2</th>
												<th scope="col" width="10%" align="center">Total</th>
											</tr>
										</thead> <?php
							}

							$_total			=	0;
							$_total_final	=	0;			

							$_idart			=	$_detalle['pidart'];
							$_unidades		=	$_detalle['pcantidad'];
							$_descripcion	=	DataManager::getArticulo('artnombre', $_idart, 1, 1);		
							//$_precio		=	str_replace('EUR','',money_format('%.2n', $_detalle['pprecio']));
							$_precio		=	$_detalle['pprecio'];
							$_b1			=	($_detalle['pbonif1'] == 0)	?	''	:	$_detalle['pbonif1'];
							$_b2			=	($_detalle['pbonif2'] == 0)	?	''	:	$_detalle['pbonif2'];
							$_bonif			=	($_detalle['pbonif1'] == 0)	?	''	:	$_b1." X ".$_b2;
							$_desc1			=	($_detalle['pdesc1'] == 0)	?	''	:	$_detalle['pdesc1'];
							$_desc2			=	($_detalle['pdesc2'] == 0)	?	''	:	$_detalle['pdesc2'];

							//**************************************//
							//	Calculo precio final por artículo	//
							//**************************************//
							$precio_f 	= 	$_precio * $_unidades;									
							if ($_desc1 != ''){ $precio_f	= $precio_f - ($precio_f * ($_desc1/100)); }
							if ($_desc2 != ''){ $precio_f	= $precio_f - ($precio_f * ($_desc2/100)); }	
							$_total			=	round($precio_f, 2);
							$_total_final	+=	$_total;
							//**************************************//

							echo sprintf("<tr style=\"%s\">", ((($k % 2) == 0)? "background-color:#fff; height:40px;" : "background-color:#C3C3C3; height:40px; font-weight:bold;"));										
							echo sprintf("<td height=\"15\" align=\"center\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td style=\"border-left:1px solid #999; padding-left:15px;\">%s</td><td align=\"right\" style=\"border-left:1px solid #999; padding-right:15px;\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td align=\"right\" style=\"border-left:1px solid #999; padding-right:5px;\">%s</td>", $_idart, $_unidades, $_descripcion, round($_precio,2), $_bonif, $_desc1, $_desc2, round($_total, 2)); //str_replace('EUR','',money_format('%.2n', $_total))
							echo sprintf("</tr>");  
						} ?>						
							</table>                                    
						</div>  <!-- cbte_boxcontent2 -->

						<div class="cbte_boxcontent2" align="left" style="font-size: 14px; background-color: #cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 

							<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%; border:1px solid #cfcfcf;"><span style=" font-weight:bold;"><span style="font-size:16px; font-weight:bold;"><?php echo $_observacion;?></span>
							</div>

							<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%; font-size:26px;"><span style=" color: #2D567F; font-weight:bold;">TOTAL: </span>
							<span style="font-weight:bold;"><?php echo round($_total_final, 2); ?></span></div>

						</div>  <!-- cbte_boxcontent2 -->
						<?php // str_replace('EUR','',money_format('%.2n', $_total_final));
					}
				} ?>	
				<div class="cbte_boxcontent2" align="center"> 
					<?php echo $piePedido; ?>
				</div>
			</div>  <!-- cbte_box -->
			
		</div>  <!-- factura --> 
	</main> <!-- fin cuerpo -->
</body>
</html> 
   
<?php
	echo "<script>"; 
	echo "javascript:dac_imprimirMuestra('factura');"; 
	echo "</script>"; 
?>  

   