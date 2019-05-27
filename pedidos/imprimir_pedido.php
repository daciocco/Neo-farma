<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$nroPedido	= empty($_REQUEST['nropedido']) ? 0 : $_REQUEST['nropedido'];

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
				if ($nroPedido) {
					$usr			=	($_SESSION["_usrrol"] !=  "V")	?	NULL	:	$_SESSION["_usrid"];
					$totalFinal	=	0;
					$detalles	= DataManager::getPedidos($usr, NULL, $nroPedido);
					if ($detalles) { 
						foreach ($detalles as $k => $detalle) {	
							if ($k==0){ 
								$empresa				= 	$detalle["pidemp"]; 
								$empresas	= 	DataManager::getEmpresas();
								if ($empresas) { 
									foreach ($empresas as $i => $emp) {
										$idEmpresa		= 	$emp['empid'];
										if ($empresa == $idEmpresa){												
											$nombreEmp		= 	$emp['empnombre'];
											$dirEmp		= 	$emp['empdomicilio'];   
											$localidadEmp	= 	" - ".$emp['emplocalidad'];
											$cpEmp			= 	" - CP".$emp['empcp'];  
											$telEmp		= 	" - Tel: ".$emp['empcp']; 
											$correoEmp		= 	$emp['empcorreo']." / ";
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
										<?php echo $dirEmp; ?> <?php echo $cpEmp; ?>  <?php echo $localidadEmp; ?> <?php echo $telEmp; ?></br>
										<?php echo $correoEmp; ?> www.neo-farma.com.ar</br>
										IVA RESPONSABLE INSCRIPTO
									</div>  <!-- cbte_boxheader --> 
								</div>  <!-- boxtitulo -->
								

								<?php
								$fechaPedido		=	$detalle['pfechapedido'];
								$idUsuario			=	$detalle['pidusr'];			
								$nombreUsr			= 	DataManager::getUsuario('unombre', $idUsuario); 
								$empresa				= 	$detalle["pidemp"];    										
								$idCliente			= 	$detalle["pidcliente"];  

								$nombreCli			= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCliente, $empresa);

								$domicilioCli		= 	DataManager::getCuenta('ctadireccion', 'ctaidcuenta', $idCliente, $empresa)." ".DataManager::getCuenta('ctadirnro', 'ctaidcuenta', $idCliente, $empresa)." ".DataManager::getCuenta('ctadirpiso', 'ctaidcuenta', $idCliente, $empresa)." ".DataManager::getCuenta('ctadirdpto', 'ctaidcuenta', $idCliente, $empresa);										

								$localidadCli		= 	DataManager::getCuenta('ctalocalidad', 'ctaidcuenta', $idCliente, $empresa);
								$codpostalCli		= 	DataManager::getCuenta('ctacp', 'ctaidcuenta', $idCliente, $empresa);
								
								$condpago			= 	$detalle["pidcondpago"];
								$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $condpago); 
								if (count($condicionesDePago)) { 
									foreach ($condicionesDePago as $k => $condPago) {
										$condPagoCodigo	=	$condPago["IdCondPago"];								
										$condnombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
										$conddias	= "(";					
										$conddias	.= empty($condPago['Dias1CP']) ? '0' : $condPago['Dias1CP'];
										$conddias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
										$conddias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
										$conddias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
										$conddias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
										$conddias	.= " D&iacute;as)";
										$conddias .= ($condPago['Porcentaje1CP'] == '0.00') ? '' : ' '.$condPago['Porcentaje1CP'].' %';
									}
								}
								
								$ordenCompra		= 	($detalle["pordencompra"] == 0)	?	''	:	"<span style=\" color: #2D567F; font-weight:bold;\">Orden Compra: </span><span style=\"font-size: 12px; font-weight:bold;\">".$detalle["pordencompra"]."</span>";
								$observacion		=	$detalle["pobservacion"];

								?>
									<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color:#cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 

										<div class="cbte_box" style="height: auto; line-height: 20px; float:left; width: 33%; font-weight:bold;"><?php echo $fechaPedido;?></span></div>
										<div class="cbte_box" align="center" style="height: auto; line-height: 20px; float:left; width: 33%;  font-weight:bold;"><span style=" color: #2D567F;">Nro. Pedido:</span> <?php echo str_pad($nroPedido, 9, "0", STR_PAD_LEFT); ?></div>
										<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%;"><span style=" color: #2D567F; font-weight:bold;"><?php echo $nombreUsr;?></div>
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
											<?php echo $idCliente." - ".$nombreCli;?></br>
											<?php echo $domicilioCli." - ".$localidadCli." - ".$codpostalCli; ?></br>	
											</span>
										</div>  <!-- cbte_box_col -->
									</div>  <!-- cbte_box_nro -->

								
									<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color: #cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 
										<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%;"><span style=" color: #2D567F; font-weight:bold;">Condici&oacute;n de Pago: </span><span style="font-size: 14px; font-weight:bold;"><?php echo $condpago." | ".$condnombre." ".$conddias;?></span></div>
										<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%;"><?php echo $ordenCompra;?></div>
									</div>  <!-- cbte_box_nro -->
								

								<div class="cbte_boxcontent2" style="padding: 15px; overflow:auto; height:auto;">
									<table width="95%" border="0">
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

							$total		= 0;										

							$idArt		= $detalle['pidart'];
							$unidades	= $detalle['pcantidad'];
							$descripcion= DataManager::getArticulo('artnombre', $idArt, 1, 1);		
							$precio	= $detalle['pprecio'];
							$b1		= ($detalle['pbonif1'] == 0)? '' : $detalle['pbonif1'];
							$b2		= ($detalle['pbonif2'] == 0)? '' : $detalle['pbonif2'];
							$bonif	= ($detalle['pbonif1'] == 0)? '' : $b1." X ".$b2;
							$desc1	= ($detalle['pdesc1'] == 0)	? '' : $detalle['pdesc1'];
							$desc2	= ($detalle['pdesc2'] == 0)	? '' : $detalle['pdesc2'];

							//**************************************//
							//	Calculo precio final por artículo	//
							$precioF 	= 	$precio * $unidades;									
							if ($desc1 != ''){ $precioF	= $precioF - ($precioF * ($desc1/100)); }
							if ($desc2 != ''){ $precioF	= $precioF - ($precioF * ($desc2/100)); }	
							$total			=	round($precioF, 3);
							$totalFinal	+=	$total;
							//**************************************//

							echo sprintf("<tr style=\"%s\">", ((($k % 2) == 0)? "background-color:#fff; height:40px;" : "background-color:#C3C3C3; height:40px; font-weight:bold;"));										
							echo sprintf("<td height=\"15\" align=\"center\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td style=\"border-left:1px solid #999; padding-left:15px;\">%s</td><td align=\"right\" style=\"border-left:1px solid #999; padding-right:15px;\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td align=\"center\" style=\"border-left:1px solid #999;\">%s</td><td align=\"right\" style=\"border-left:1px solid #999; padding-right:5px;\">%s</td>", $idArt, number_format($unidades,0), $descripcion, number_format(round($precio,2),2), $bonif, number_format(round($desc1,2),2), number_format(round($desc2,2),2), number_format(round($total,2),2));
							echo sprintf("</tr>");  
						} ?>						
							</table>                                    
						</div>  <!-- cbte_boxcontent2 -->

						<div class="cbte_boxcontent2" align="left" style="font-size: 14px; background-color: #cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 

							<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%; border:1px solid #cfcfcf;"><span style=" font-weight:bold;"><span style="font-size:16px; font-weight:bold;"><?php echo $observacion;?></span>
							</div>

							<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%; font-size:26px;"><span style=" color: #2D567F; font-weight:bold;">TOTAL: </span>
							<span style="font-weight:bold;"><?php echo number_format(round($totalFinal,2),2); ?></span></div>

						</div>  <!-- cbte_boxcontent2 -->
						<?php
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

   