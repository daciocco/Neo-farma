<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
}

$condId		=	empty($_GET['condid']) ? 0 : $_GET['condid'];
$backURL	= 	empty($_GET['backURL']) ? '/pedidos/condicion/': $_GET['backURL'];

if ($condId) {
	$condicion		= DataManager::newObjectOfClass('TCondicionComercial', $condId);
	$empresa 		= $condicion->__get('Empresa');
	$laboratorio	= $condicion->__get('Laboratorio');	
	$cuentas		= $condicion->__get('Cuentas');
	$nombre 		= $condicion->__get('Nombre');
	$tipo	 		= $condicion->__get('Tipo');
	$condPago 		= $condicion->__get('CondicionPago');
	$cantMinima		= ($condicion->__get('CantidadMinima')) ? $condicion->__get('CantidadMinima') : '';
	$minReferencias	= ($condicion->__get('MinimoReferencias')) ? $condicion->__get('MinimoReferencias') : '';
	$minMonto		= ($condicion->__get('MinimoMonto') == '0.000') ? '' : $condicion->__get('MinimoMonto');
	
	$fechaInicio	= dac_invertirFecha( $condicion->__get('FechaInicio'));
	$fechaFin 		= dac_invertirFecha($condicion->__get('FechaFin'));	
	$observacion	= utf8_decode($condicion->__get('Observacion'));
} else {
	$empresa 		= 1;
	$laboratorio	= 1;	
	$cuentas		= "";
	$nombre 		= "";
	$tipo	 		= "";
	$cantMinima		= "";
	$minReferencias	= "";
	$minMonto		= "";
	$condPago 		= "";
	$fechaInicio	= "";
	$fechaFin 		= "";	
	$observacion	= "";
} 

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=".$tipo."-".date("d-m-Y").".xls"); ?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head>
	<style>
		.datatab{
			font-size:14px;
		}
		tr th {
			font-weight:bold;
			height: 20px;	
		}
		td.par {
			background-color: #fff;
			height: 20px;
		}
		td.impar {
			background-color: #cfcfcf;
			height: 20px;
			font-weight:bold;
		}
	</style>
</head>

<body>
	<?php 
	
	switch($tipo){
		case 'Pack':  ?>
			<table class="datatab" border="0" cellpadding="0" cellspacing="0" width="600">
				<thead>
					<tr>
						<th scope="colgroup" colspan="7" align="center" style="height:100px;"><?php echo $cabecera; ?></th>
					</tr>
					<tr>
						<th scope="colgroup" rowspan="2" colspan="2" align="center" style="font-size:24px; color:#117db6; border:1px solid #666" ><?php echo $tipo; ?></th>
						<th scope="colgroup" colspan="5" align="center" style="font-size:24px; color:#117db6; border:1px solid #666"><?php echo $nombre; ?></th>						
					</tr>
					<tr>
						<th scope="colgroup" colspan="5" align="center" style="border:1px solid #666">
							Vigencia: <?php echo " ".$fechaInicio." "; ?> / <?php echo " ".$fechaFin; ?> 
						</th>
					</tr>
					<tr>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Art</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="250">Descripci&oacute;n</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">CantM&iacute;n</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="100">PSL</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Unid</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Desc</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Bonif</th>
					</tr>
				</thead>
				<tbody> <?php 
					if ($condId) {	
						$articulosCond	=	DataManager::getCondicionArticulos($condId);
						if (count($articulosCond)) {								 
							foreach ($articulosCond as $k => $artCond) {
								$artCond 		= $articulosCond[$k];
								//$condArtId		= $artCond['cartid'];
								$condArtIdArt	= $artCond['cartidart'];
								$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
								$condArtPrecio		= $artCond["cartprecio"]; //(PSL)
								//--> precio digitado o precio de venta. (PV)
								$condArtPrecioDigit	= ($artCond["cartpreciodigitado"] == '0.000')?	''	: $artCond["cartpreciodigitado"]; 
                     
								$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];
								
								$_estilo =	(($k % 2) == 0)? "par" : "impar";	
								?>
								<tr>
									<td class="<?php echo $_estilo; ?>" align="center"><?php echo $condArtIdArt; ?></td>
									<td class="<?php echo $_estilo; ?>" ><?php echo utf8_decode($condArtNombre); ?></td>
									<td class="<?php echo $_estilo; ?>" ><?php echo $condArtCantMin; ?></td>
									<td class="<?php echo $_estilo; ?>" align="right"><?php echo "$ ".$condArtPrecio; ?></td>
									
									<?php
									//Controlo si tiene Bonificaciones y dewscuentos para cargar
									$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $condArtIdArt);
									if (count($articulosBonif)) {								 
										foreach ($articulosBonif as $j => $artBonif) {	
											//$artBonifId		= empty($artBonif['cbid'])		?	''	:	$artBonif['cbid'];
											$artBonifCant	= empty($artBonif['cbcant'])	?	''	:	$artBonif['cbcant'];
											$artBonifB1		= empty($artBonif['cbbonif1'])	?	''	:	$artBonif['cbbonif1'];
											$artBonifB2		= empty($artBonif['cbbonif2'])	?	''	:	$artBonif['cbbonif2'];
											$bonif 			= (empty($artBonifB1)) 			?	''	:	$artBonifB1.' X '.$artBonifB2;
											$artBonifD1		= ($artBonif['cbdesc1'] == '0.00')	?	''	:	$artBonif['cbdesc1'];	
											//$artBonifD2		= empty($artBonif['cbdesc2'])	?	''	:	$artBonif['cbdesc2'];
											if($j == 0){ ?>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifCant; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD1; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $bonif; ?></td>	
												</tr> <?php	
											} else {  ?>
												<tr>
													<td class="<?php echo $_estilo; ?>" align="center"></td>
													<td class="<?php echo $_estilo; ?>" ></td>
													<td class="<?php echo $_estilo; ?>" ></td>
													<td class="<?php echo $_estilo; ?>" align="center"></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifCant; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD1; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $bonif; ?></td>
												</tr>	 <?php 
											}
										}
									} else { ?>
											<td class="<?php echo $_estilo; ?>" align="center"></td>
											<td class="<?php echo $_estilo; ?>" align="center"></td>
											<td class="<?php echo $_estilo; ?>" align="center"></td>	
										</tr> <?php	
									}					
							} ?>
							<tr>
								<td scope="colgroup" colspan="7"></td>
							</tr>
							
							<?php if($minMonto){?>
								<tr>
									<td scope="colgroup" colspan="2"></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">Monto M&iacute;nimo</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo "$ ".$minMonto;?></td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?> 
							<?php if($cantMinima){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">Cantidad Total M&iacute;nima</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  $cantMinima;?> </td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?> 
							<?php if($minReferencias){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">M&iacute;nimo de Referencias</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  $minReferencias;?>  </td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?>
							
							<?php if($condPago){?>
								<tr>
									<td scope="colgroup" colspan="7"  style="font-weight:bold;">CONDICIONES DE PAGO: </td>
								</tr>  

								<?php 
								$condicionesPago	=	explode(",", $condPago);
								if($condicionesPago){										
									for( $j=0; $j < count($condicionesPago); $j++ ) {
										$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $condicionesPago[$j]); 
										if (count($condicionesDePago)) { 
											foreach ($condicionesDePago as $k => $condPago) {
												$condPagoCodigo	=	$condPago["IdCondPago"];									
												$nombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
												$condPagoDias	= "(";					
												$condPagoDias	.= empty($condPago['Dias1CP']) ? '' : $condPago['Dias1CP'];
												$condPagoDias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
												$condPagoDias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
												$condPagoDias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
												$condPagoDias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
												$condPagoDias	.= " D&iacute;as)";
												
												$condNombre	=	$nombre." ".$condPagoDias;
											}
										}
										
										?>
										<tr>
											<td scope="colgroup" colspan="1" ></td>
											<td scope="colgroup" colspan="6" ><?php echo $condicionesPago[$j].' - '.utf8_decode($condNombre); ?></td>
										</tr>
										<?php 
									}
								} ?>
							<?php }?>
														
							<?php if($observacion){?> 
								<tr>
									<td scope="colgroup" colspan="7"  style="font-weight:bold;">OBSERVACI&Oacute;N </td>
								</tr> 
								<tr>
									<td scope="colgroup" colspan="1"  ></td>
									<td scope="colgroup" colspan="6"  style="height:50px;" ><?php echo $observacion; ?></td>
								</tr> 
							<?php }?>
							<tr>
								<td scope="colgroup" colspan="7" ></td>
							</tr>
						<?php			 
						}
					} else { ?>
						<tr>
							<td scope="colgroup" colspan="7"  style="border:1px solid #666">No se encontraron condiciones.</td>
						</tr> <?php
					} ?>
				</tbody>
				<tfoot>
					<tr>
						<th scope="colgroup" colspan="7" align="center" style="height:100px;"><?php echo $pie; ?></th>
					</tr>	
				</tfoot> 
			</table> <?php
			break;
		case 'ListaEspecial': ?> 
			<table class="datatab" border="0" cellpadding="0" cellspacing="0" width="600">
				<thead>
					<tr>
						<th scope="colgroup" colspan="8" align="center" style="height:100px;"><?php echo $cabecera; ?></th>
					</tr>
					<tr>
						<th scope="colgroup" rowspan="2" colspan="2" align="center" style="font-size:24px; color:#117db6; border:1px solid #666" ><?php echo $tipo; ?></th>
						<th scope="colgroup" colspan="6" align="center" style="font-size:24px; color:#117db6; border:1px solid #666"><?php echo $nombre; ?></th>
					</tr>
					<tr>
						<th scope="colgroup" colspan="6" align="center" style="border:1px solid #666">
							Vigencia: <?php echo " ".$fechaInicio." "; ?> / <?php echo " ".$fechaFin; ?> 
						</th>
					</tr>
					<tr>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Art</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="250">Descripci&oacute;n</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">PVP</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">PSL</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">PV</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Unid</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Desc</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Bonif</th>
					</tr>
				</thead>
				<tbody> <?php 
					if ($condId) {	
						$articulosCond	=	DataManager::getCondicionArticulos($condId);
						if (count($articulosCond)) {								 
							foreach ($articulosCond as $k => $artCond) {
								$artCond 		= $articulosCond[$k];
								//$condArtId		= $artCond['cartid'];
								$condArtIdArt	= $artCond['cartidart'];
								$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
								// --> precio original de la tabla artículos (droguería)
								$condArtMedicinal		= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
								$condArtIva		 = DataManager::getArticulo('artiva', $condArtIdArt, $empresa, $laboratorio);
								$condArtGanancia = DataManager::getArticulo('artganancia', $condArtIdArt, $empresa, $laboratorio);
								
								$condArtPrecio	= $artCond["cartprecio"]; //(PSL)
								
								$condArtDigitado= ($artCond["cartpreciodigitado"] == '0.000')	? '' : $artCond["cartpreciodigitado"]; // (PVP)
								
								$precio = (empty($condArtPrecio)) ? $condArtDigitado : $condArtPrecio;
																
								/*$p1 =	floatval($precio);//1.45 es el 45% que sale dividiendo PVP / PSL
								$p2 =	floatval(1.450);
								$pvp = $p1*$p2;								
								$pvp	= ($medicinal) ? $pvp*1.21  : $pvp;
								$pvp = number_format($pvp,3,'.',''); // (PVP) */
								
								//Calcular PVP
								$pvp = dac_calcularPVP($precio, $condArtIva, $condArtMedicinal, $empresa, $condArtGanancia);
								
                     
								$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];
								
								$_estilo =	(($k % 2) == 0)? "par" : "impar"; ?>
								
								<tr>
									<td class="<?php echo $_estilo; ?>" align="center"><?php echo $condArtIdArt; ?></td>
									<td class="<?php echo $_estilo; ?>" ><?php echo utf8_decode($condArtNombre); ?></td>
									<td class="<?php echo $_estilo; ?>" align="right"><?php echo "$ ".$pvp; ?></td>
									<td class="<?php echo $_estilo; ?>" align="right"><?php if($condArtPrecio){ echo "$ ".$condArtPrecio; } ?></td>
									<td class="<?php echo $_estilo; ?>" align="right"><?php if($condArtDigitado){ echo "$ ".$condArtDigitado; } ?></td>
									
									<?php
									//Controlo si tiene Bonificaciones y dewscuentos para cargar
									$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $condArtIdArt);
									if (count($articulosBonif)) {								 
										foreach ($articulosBonif as $j => $artBonif) {	
											//$artBonifId		= empty($artBonif['cbid'])		?	''	:	$artBonif['cbid'];
											$artBonifCant	= empty($artBonif['cbcant'])	?	''	:	$artBonif['cbcant'];
											$artBonifB1		= empty($artBonif['cbbonif1'])	?	''	:	$artBonif['cbbonif1'];
											$artBonifB2		= empty($artBonif['cbbonif2'])	?	''	:	$artBonif['cbbonif2'];
											$bonif 			= (empty($artBonifB1)) 			?	''	:	$artBonifB1.' X '.$artBonifB2;
											$artBonifD1		= ($artBonif['cbdesc1'] == '0.00')	?	''	:	$artBonif['cbdesc1'];	
											//$artBonifD2	= empty($artBonif['cbdesc2'])	?	''	:	$artBonif['cbdesc2'];
											
											if($j == 0){ ?>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifCant; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD1; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $bonif; ?></td>	
												</tr> <?php	
											} else {  ?>
												<tr>
													<td class="<?php echo $_estilo; ?>" align="center"></td>
													<td class="<?php echo $_estilo; ?>" ></td>
													<td class="<?php echo $_estilo; ?>" ></td>
													<td class="<?php echo $_estilo; ?>" align="center"></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifCant; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD1; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $bonif; ?></td>
												</tr>	 <?php 
											}
										}
									} else { ?>
											<td class="<?php echo $_estilo; ?>" align="center"></td>
											<td class="<?php echo $_estilo; ?>" align="center"></td>
											<td class="<?php echo $_estilo; ?>" align="center"></td>	
										</tr> <?php	
									}					
							} ?>
							<tr>
								<td scope="colgroup" colspan="8"></td>
							</tr>
							
							<?php if($minMonto){?>
								<tr>
									<td scope="colgroup" colspan="2"></td>
									<td scope="colgroup" colspan="4"  style="font-weight:bold; text-align: right;">Monto M&iacute;nimo</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  "$ ".$minMonto;?></td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?> 
							<?php if($cantMinima){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="4"  style="font-weight:bold; text-align: right;">Cantidad Total M&iacute;nima</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  $cantMinima;?> </td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?> 
							<?php if($minReferencias){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="4"  style="font-weight:bold; text-align: right;">M&iacute;nimo de Referencias</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  $minReferencias;?>  </td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?>
							
							<?php if($condPago){?>
								<tr>
									<td scope="colgroup" colspan="8"  style="font-weight:bold;">CONDICIONES DE PAGO: </td>
								</tr>  

								<?php 
								$condicionesPago	=	explode(",", $condPago);
								if($condicionesPago){										
									for( $j=0; $j < count($condicionesPago); $j++ ) {	
										$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $condicionesPago[$j]); 
										if (count($condicionesDePago)) { 
											foreach ($condicionesDePago as $k => $condPago) {
												$condPagoCodigo	=	$condPago["IdCondPago"];									
												$nombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
												$condPagoDias	= "(";					
												$condPagoDias	.= empty($condPago['Dias1CP']) ? '' : $condPago['Dias1CP'];
												$condPagoDias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
												$condPagoDias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
												$condPagoDias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
												$condPagoDias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
												$condPagoDias	.= " D&iacute;as)";
												
												$condNombre	=	$nombre." ".$condPagoDias;
											}
										}
										?>
										<tr>
											<td scope="colgroup" colspan="1" ></td>
											<td scope="colgroup" colspan="6" ><?php echo $condicionesPago[$j].' - '.utf8_decode($condNombre); ?></td>
										</tr>
										<?php 
									}
								} ?>
							
							<?php }?>
							
							<?php if($observacion){?> 
								<tr>
									<td scope="colgroup" colspan="8"  style="font-weight:bold;">OBSERVACI&Oacute;N </td>
								</tr> 
								<tr>
									<td scope="colgroup" colspan="1"  ></td>
									<td scope="colgroup" colspan="7"  style="height:50px;" ><?php echo $observacion; ?></td>
								</tr> 
							<?php }?>
							<tr>
								<td scope="colgroup" colspan="8" ></td>
							</tr>
						<?php			 
						}
						
						if(!empty($cuentas)){ ?>
							<tr>
								<td scope="colgroup" colspan="8"  style="font-weight:bold;">Cuentas Relacionadas</td>
							</tr>
							<?php
							$cuentasCondiciones = explode(",", $cuentas);
							foreach ($cuentasCondiciones as $ctaCond) {							
								$ctaCondIdCta	= 	DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaCond, $empresa);
								$ctaCondNombre	= 	DataManager::getCuenta('ctanombre', 'ctaid', $ctaCond, $empresa);
								?>
								<tr>
									<th scope="colgroup" align="left" style="border:1px solid #666; background-color: #cfcfcf;" width="50"><?php echo $ctaCondIdCta; ?></th>
									<th colspan="7" scope="colgroup" align="left"  style="border:1px solid #666; background-color: #cfcfcf" width="250"><?php echo $ctaCondNombre; ?></th>
								</tr>
								<?php
								
							}
						}
					} else { ?>
						<tr>
							<td scope="colgroup" colspan="8"  style="border:1px solid #666">No se encontraron condiciones.</td>
						</tr> <?php
					} ?>
					
				</tbody>

				<tfoot>
					<tr>
						<th scope="colgroup" colspan="8" align="center" style="height:100px;"><?php echo $pie; ?></th>
					</tr>	
				</tfoot> 
			</table> <?php
			break;
		case 'CondicionEspecial': ?>
			<table class="datatab" border="0" cellpadding="0" cellspacing="0" width="600">
				<thead>
					<tr>
						<th scope="colgroup" colspan="8" align="center" style="height:100px;"><?php echo $cabecera; ?></th>
					</tr>
					<tr>
						<th scope="colgroup" rowspan="2" colspan="2" align="center" style="font-size:24px; color:#117db6; border:1px solid #666" ><?php echo $tipo; ?></th>
						<th scope="colgroup" colspan="6" align="center" style="font-size:24px; color:#117db6; border:1px solid #666"><?php echo $nombre; ?></th>
					</tr>
					<tr>
						<th scope="colgroup" colspan="6" align="center" style="border:1px solid #666">
							Vigencia: <?php echo " ".$fechaInicio." "; ?> / <?php echo " ".$fechaFin; ?> 
						</th>
					</tr>
					<tr>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Art</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="250">Descripci&oacute;n</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">PVP</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">PSL</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">PV</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Desc</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Desc</th>
						<th scope="colgroup"  style="border:1px solid #666; background-color: #cfcfcf" width="50">Bonif</th>
					</tr>
				</thead>
				<tbody> <?php 
					if ($condId) {	
						$articulosCond	=	DataManager::getCondicionArticulos($condId);
						if (count($articulosCond)) {								 
							foreach ($articulosCond as $k => $artCond) {
								$artCond 		= $articulosCond[$k];
								//$condArtId		= $artCond['cartid'];
								$condArtIdArt	= $artCond['cartidart'];
								$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
								$medicinal		= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
								$condArtPrecio	= $artCond["cartprecio"]; //(PSL)								
								$condArtDigitado= ($artCond["cartpreciodigitado"] == '0.000')	? '' : $artCond["cartpreciodigitado"]; // (PV) (Precio Venta)
								
								//Calcular PVP
								$condArtMedicinal= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
								$condArtIva		 = DataManager::getArticulo('artiva', $condArtIdArt, $empresa, $laboratorio);
								$condArtGanancia = DataManager::getArticulo('artganancia', $condArtIdArt, $empresa, $laboratorio);
								
								$pvp = dac_calcularPVP($condArtPrecio, $condArtIva, $condArtMedicinal, $empresa, $condArtGanancia);
								/*
								$p1 	= floatval($condArtPrecio); //1.45 es el 45% que sale dividiendo PVP / PSL
								$p2 	= floatval(1.450);
								$pvp 	= $p1*$p2;
								$pvp	= ($medicinal) ? $pvp*1.21  : $pvp;
								$pvp 	= number_format($pvp,3,'.','');   // (PVP) */
								
								$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];
								$_estilo =	(($k % 2) == 0)? "par" : "impar";
								?>
								
								<tr>
									<td class="<?php echo $_estilo; ?>" align="center"><?php echo $condArtIdArt; ?></td>
									<td class="<?php echo $_estilo; ?>" ><?php echo utf8_decode($condArtNombre); ?></td>
									<td class="<?php echo $_estilo; ?>" align="right"><?php echo "$ ".$pvp; ?></td>
									<td class="<?php echo $_estilo; ?>" align="right"><?php echo "$ ".$condArtPrecio; ?></td>
									<td class="<?php echo $_estilo; ?>" align="right"><?php if($condArtDigitado){ echo "$ ".$condArtDigitado; } ?></td>
									<?php
									//Controlo si tiene Bonificaciones y dewscuentos para cargar
									$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $condArtIdArt);
									if (count($articulosBonif)) {								 
										foreach ($articulosBonif as $j => $artBonif) {	
											//$artBonifId		= empty($artBonif['cbid'])		?	''	:	$artBonif['cbid'];
											$artBonifCant	= empty($artBonif['cbcant'])	?	''	:	$artBonif['cbcant'];
											$artBonifB1		= empty($artBonif['cbbonif1'])	?	''	:	$artBonif['cbbonif1'];
											$artBonifB2		= empty($artBonif['cbbonif2'])	?	''	:	$artBonif['cbbonif2'];
											$bonif 			= (empty($artBonifB1)) 			?	''	:	$artBonifB1.' X '.$artBonifB2;
											$artBonifD1		= ($artBonif['cbdesc1'] == '0.00')	?	''	:	$artBonif['cbdesc1'];	
											$artBonifD2		= ($artBonif['cbdesc2'] == '0.00')	?	''	:	$artBonif['cbdesc2'];
											
											if($j == 0){ ?>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD1; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD2; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $bonif; ?></td>	
												</tr> <?php	
											} else {  ?>
												<tr>
													<td class="<?php echo $_estilo; ?>" align="center"></td>
													<td class="<?php echo $_estilo; ?>" ></td>
													<td class="<?php echo $_estilo; ?>" ></td>
													<td class="<?php echo $_estilo; ?>" align="center"></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD1; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $artBonifD2; ?></td>
													<td class="<?php echo $_estilo; ?>" align="center"><?php echo $bonif; ?></td>
												</tr>	 <?php 
											}
										}
									} else { ?>
											<td class="<?php echo $_estilo; ?>" align="center"></td>
											<td class="<?php echo $_estilo; ?>" align="center"></td>
											<td class="<?php echo $_estilo; ?>" align="center"></td>	
										</tr> <?php	
									}					
							} ?>
							<tr>
								<td scope="colgroup" colspan="8"></td>
							</tr>
							
							<?php if($minMonto){?>
								<tr>
									<td scope="colgroup" colspan="2"></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">Monto M&iacute;nimo</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  "$ ".$minMonto;?></td>
									<td scope="colgroup" colspan="2"></td>
								</tr>
							<?php }?> 
							<?php if($cantMinima){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">Cantidad Total M&iacute;nima</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  $cantMinima;?> </td>
									<td scope="colgroup" colspan="2"></td>
								</tr>
							<?php }?> 
							<?php if($minReferencias){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">M&iacute;nimo de Referencias</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  $minReferencias;?>  </td>
									<td scope="colgroup" colspan="2"></td>
								</tr>
							<?php }?>
							
							<?php if($condPago){?>
								<tr>
									<td scope="colgroup" colspan="8"  style="font-weight:bold;">CONDICIONES DE PAGO: </td>
								</tr>  

								<?php 
								$condicionesPago	=	explode(",", $condPago);
								if($condicionesPago){										
									for( $j=0; $j < count($condicionesPago); $j++ ) {	
										$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $condicionesPago[$j]); 
										if (count($condicionesDePago)) { 
											foreach ($condicionesDePago as $k => $condPago) {
												$condPagoCodigo	=	$condPago["IdCondPago"];									
												$nombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
												$condPagoDias	= "(";					
												$condPagoDias	.= empty($condPago['Dias1CP']) ? '' : $condPago['Dias1CP'];
												$condPagoDias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
												$condPagoDias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
												$condPagoDias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
												$condPagoDias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
												$condPagoDias	.= " D&iacute;as)";
												
												$condNombre	=	$nombre." ".$condPagoDias;
											}
										}
										?>
										<tr>
											<td scope="colgroup" colspan="1" ></td>
											<td scope="colgroup" colspan="7" ><?php echo $condicionesPago[$j].' - '.utf8_decode($condNombre); ?></td>
										</tr>
										<?php 
									}
								} ?>
							
							<?php }?>
							
							<?php if($observacion){?> 
								<tr>
									<td scope="colgroup" colspan="8"  style="font-weight:bold;">OBSERVACI&Oacute;N </td>
								</tr> 
								<tr>
									<td scope="colgroup" colspan="1"  ></td>
									<td scope="colgroup" colspan="7"  style="height:50px;" ><?php echo $observacion; ?></td>
								</tr> 
							<?php }?>
							<tr>
								<td scope="colgroup" colspan="8" ></td>
							</tr>
						<?php	
						}
						
						if(!empty($cuentas)){ ?>
							<tr>
								<td scope="colgroup" colspan="8"  style="font-weight:bold;">Cuentas Relacionadas</td>
							</tr>
							<?php
							$cuentasCondiciones = explode(",", $cuentas);
							foreach ($cuentasCondiciones as $ctaCond) {							
								$ctaCondIdCta	= 	DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaCond, $empresa);
								$ctaCondNombre	= 	DataManager::getCuenta('ctanombre', 'ctaid', $ctaCond, $empresa);
								?>
								<tr>
									<th scope="colgroup" align="left" style="border:1px solid #666; background-color: #cfcfcf;" width="50"><?php echo $ctaCondIdCta; ?></th>
									<th colspan="7" scope="colgroup" align="left"  style="border:1px solid #666; background-color: #cfcfcf" width="250"><?php echo $ctaCondNombre; ?></th>
								</tr>
								<?php
								
							}
						}
						
					} else { ?>
						<tr>
							<td scope="colgroup" colspan="8"  style="border:1px solid #666">No se encontraron condiciones.</td>
						</tr> <?php
					} ?>
					
				</tbody>

				<tfoot>
					<tr>
						<th scope="colgroup" colspan="8" align="center" style="height:100px;"><?php echo $pie; ?></th>
					</tr>	
				</tfoot> 
			</table> <?php
			break;
		case 'Bonificacion': ?>
			<table class="datatab" border="0" cellpadding="0" cellspacing="0" width="860" style="font-size:10px;">
				<thead>
					<tr>
						<th scope="colgroup" style="border:1px solid #666; background-color: #cfcfcf" width="35"></th>
						<th scope="colgroup" align="center" style="color:#117db6; border:1px solid #666" width="215">
							<?php echo $tipo; ?> de <?php echo " ".$fechaInicio." "; ?> a <?php echo " ".$fechaFin; ?> </th>
						<th scope="colgroup" style="border:1px solid #666; background-color: #cfcfcf" width="65">PSL
						</th>
						<th scope="colgroup" style="border:1px solid #666; background-color: #cfcfcf" width="65">PVP</th>
						<th scope="colgroup" style="border:1px solid #666; background-color: #cfcfcf" width="30">IVA</th>
						<th scope="colgroup" style="border:1px solid #666; background-color: #cfcfcf" width="60">Digitado</th>
						<th scope="colgroup" style="border:1px solid #666; background-color: #cfcfcf" width="60">OAM</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">1</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">3</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">6</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">12</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">24</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">36</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">48</th>
						<th scope="colgroup" colspan="3" style="border:1px solid #666; background-color: #cfcfcf" width="45">72</th>
					</tr>
					
				</thead>
				<tbody> <?php 
					if ($condId) {	
						$articulosCond	=	DataManager::getCondicionArticulos($condId);
						if (count($articulosCond)) {	
							$arrayCantidades = array(1, 3, 6, 12, 24, 36, 48, 72);
							foreach ($articulosCond as $k => $artCond) {
								$artCond 		= $articulosCond[$k];
								//$condArtId		= $artCond['cartid'];
								$condArtIdArt	= $artCond['cartidart'];
								$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
								$medicinal		= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
								
								$condArtPrecio	= $artCond["cartprecio"]; //(PSL)
								$condArtPrecioDigit	= ($artCond["cartpreciodigitado"] == '0.000') ?	''	: "$ ".$artCond["cartpreciodigitado"]; // (PV)
								
								//Calcular PVP
								$condArtMedicinal= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
								$condArtIva		 = DataManager::getArticulo('artiva', $condArtIdArt, $empresa, $laboratorio);
								$condArtGanancia = DataManager::getArticulo('artganancia', $condArtIdArt, $empresa, $laboratorio);
								$pvp = dac_calcularPVP($condArtPrecio, $condArtIva, $condArtMedicinal, $empresa, $condArtGanancia);
								/*
								$p1		=	floatval($condArtPrecio);//1.45 es el 45% que sale dividiendo PVP / PSL
								$p2 	=	floatval(1.450);
								$pvp	= 	$p1*$p2;
								$pvp	=   ($medicinal) ? $pvp*1.21  : $pvp;
								$pvp	= 	number_format($pvp,3,'.',''); // (PVP)
								*/
                     
								$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];
								$condArtOAM		= $artCond['cartoam'];
								
								$_estilo =	(($k % 2) == 0)? "par" : "impar"; ?>
								
								<tr>
									<td class="<?php echo $_estilo; ?>" align="center" style="font-size:10px;"><?php echo $condArtIdArt; ?></td>
									<td class="<?php echo $_estilo; ?>" style="font-size:10px;"><?php echo utf8_decode($condArtNombre); ?></td>
									<td class="<?php echo $_estilo; ?>" align="right" style="font-size:10px;"><?php echo "$ ".$condArtPrecio; ?></td>
									<td class="<?php echo $_estilo; ?>" align="right" style="font-size:10px;"><?php echo "$ ".$pvp; ?></td>
									<td class="<?php echo $_estilo; ?>" align="center" style="font-size:10px;"><?php echo $medicinal; ?></td>
									<td class="<?php echo $_estilo; ?>" align="right" style="font-size:10px;"><?php echo $condArtPrecioDigit; ?></td>
									<td class="<?php echo $_estilo; ?>" style="font-size:10px; border-right: 1px solid #666;"><?php echo $condArtOAM; ?></td>
									
									<?php
									//Controlo si tiene Bonificaciones y dewscuentos para cargar
									$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $condArtIdArt);
									if (count($articulosBonif)) {										
										unset($arrayCant);
										//unset($arrayBonif);
										unset($array1);
										unset($array2);
										unset($array3);
										
										foreach ($articulosBonif as $j => $artBonif) {	
											//$artBonifId		= empty($artBonif['cbid'])		?	''	:	$artBonif['cbid'];
											$artBonifCant	= empty($artBonif['cbcant'])		?	''	:	$artBonif['cbcant'];
											$artBonifB1		= empty($artBonif['cbbonif1'])		?	''	:	$artBonif['cbbonif1'];
											$artBonifB2		= empty($artBonif['cbbonif2'])		?	''	:	$artBonif['cbbonif2'];
											//$artBonifD1		= ($artBonif['cbdesc1'] == '0.00')	?	''	:	ceil($artBonif['cbdesc1']).' %';
											$artBonifD1		= ($artBonif['cbdesc1'] == '0.00')	?	''	:	ceil($artBonif['cbdesc1']);
											
											//$bonif 			= (empty($artBonifB1)) ? $artBonifD1 : $artBonifB1.' X '.$artBonifB2;
											if(empty($artBonifB1)){
												$array1[] = '';
												$array2[] = $artBonifD1;
												$array3[] = ' %';
											} else {
												$array1[] = $artBonifB1;
												$array2[] = 'X';
												$array3[] = $artBonifB2;
											}
											
											$arrayCant[] 	= $artBonifCant;
											//$arrayBonif[] 	= $bonif;
											
											
										}
										
										//recorro el array para cargar
										for($i=0; $i < count($arrayCantidades); $i++){
											if(in_array($arrayCantidades[$i], $arrayCant)){
												$key = array_search($arrayCantidades[$i], $arrayCant);
												?> 
												<td class="<?php echo $_estilo; ?>" align="center" style="font-size:10px;"><?php echo $array1[$key]; ?></td>
												<td class="<?php echo $_estilo; ?>" align="center" style="font-size:10px;"><?php echo $array2[$key]; ?></td>
												
												<td class="<?php echo $_estilo; ?>" align="center" style="font-size:10px; border-right: 1px solid #666;"><?php echo $array3[$key]  //$arrayBonif[$key]; ?></td> <?php	
											} else { ?> 
												<td class="<?php echo $_estilo; ?>"></td>
												<td class="<?php echo $_estilo; ?>"></td>
												<td class="<?php echo $_estilo; ?>" style="border-right: 1px solid #666;"></td> <?php	
											}
										}
									} else { ?> 
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td> 
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td>
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td>
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td>
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td>
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td>
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td>
										<td class="<?php echo $_estilo; ?>" colspan="3" style="background-color:#333; border-right: 1px solid #666;"></td>
										<?php
									} ?>	
							  	</tr> <?php
							} ?>
							<tr>
								<td scope="colgroup" colspan="15"></td>
							</tr>
							
							<?php if($minMonto){?>
								<tr>
									<td scope="colgroup" colspan="2"></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">Monto M&iacute;nimo</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  "$ ".$minMonto;?></td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?> 
							<?php if($cantMinima){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">Cantidad Total M&iacute;nima</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo  $cantMinima;?> </td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?> 
							<?php if($minReferencias){?>
								<tr>
									<td scope="colgroup" colspan="2" ></td>
									<td scope="colgroup" colspan="3"  style="font-weight:bold; text-align: right;">M&iacute;nimo de Referencias</td>
									<td scope="colgroup" colspan="2"  style="font-weight:bold;"><?php echo $minReferencias;?>  </td>
									<td scope="colgroup" colspan="1"></td>
								</tr>
							<?php }?>
							
							<?php if($condPago){?>
								<tr>
									<td scope="colgroup" colspan="7"  style="font-weight:bold;">CONDICIONES DE PAGO: </td>
								</tr>  

								<?php 
								$condicionesPago	=	explode(",", $condPago);
								if($condicionesPago){										
									for( $j=0; $j < count($condicionesPago); $j++ ) {
										$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $condicionesPago[$j]); 
										if (count($condicionesDePago)) { 
											foreach ($condicionesDePago as $k => $condPago) {
												$condPagoCodigo	=	$condPago["IdCondPago"];									
												$nombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
												$condPagoDias	= "(";					
												$condPagoDias	.= empty($condPago['Dias1CP']) ? '' : $condPago['Dias1CP'];
												$condPagoDias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
												$condPagoDias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
												$condPagoDias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
												$condPagoDias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
												$condPagoDias	.= " D&iacute;as)";
												
												$condNombre	=	$nombre." ".$condPagoDias;
											}
										}
										?>
										<tr>
											<td scope="colgroup" colspan="1" ></td>
											<td scope="colgroup" colspan="6" ><?php echo utf8_decode($condNombre); ?></td>
										</tr>
										<?php 
									}
								} ?>
							
							<?php }?>
							
							<?php if($observacion){?> 
								<tr>
									<td scope="colgroup" colspan="7"  style="font-weight:bold;">OBSERVACI&Oacute;N</td>
								</tr> 
								<tr>
									<td scope="colgroup" colspan="1" ></td>
									<td scope="colgroup" colspan="6"  style="height:50px;" ><?php echo $observacion; ?></td>
								</tr> 
							<?php }?>
							<tr>
								<td scope="colgroup" colspan="7" ></td>
							</tr>
						<?php			 
						}
					} else { ?>
						<tr>
							<td scope="colgroup" colspan="7"  style="border:1px solid #666">No se encontraron condiciones.</td>
						</tr> <?php
					} ?>
					
				</tbody>
			</table> <?php
			break;
		case 'Propuesta': ?>
			<table class="datatab" border="0" cellpadding="0" cellspacing="0" width="600">
				<thead>
					<tr>
						<th scope="colgroup" colspan="9" align="center" style="height:100px;"><?php echo $cabecera; ?></th>
					</tr>
					<tr>
						<th scope="colgroup" rowspan="2" colspan="3" align="center" style="font-size:24px; color:#117db6; border:1px solid #666" ><?php echo $tipo; ?></th>
						<th scope="colgroup" colspan="6" align="center" style="font-size:24px; color:#117db6; border:1px solid #666"></th>
					</tr>
					<tr>
						<th scope="colgroup" colspan="6" align="right" style="border:1px solid #666">
							<?php echo "Buenos Aires, ".date("d")." de ".Mes(date("m"))." de ".date("Y"); ?> 
						</th>
					</tr>
					
					<tr><th scope="colgroup" colspan="9"></th></tr>
					<tr><th scope="colgroup" colspan="9" align="left">Estimada Farmacia,</th></tr>
					<tr><th scope="colgroup" colspan="9"></th></tr>
					<tr><th scope="colgroup" colspan="9" align="left">Agradeciendo el tiempo dedicado y de cara a consolidar nuestra relaci&oacute;n directa,</th></tr>
					<tr><th scope="colgroup" colspan="9" align="left"> detallo a continuaci&oacute;n la propuesta comercial:</th></tr>
					<tr><th scope="colgroup" colspan="9"></th></tr>
					<tr><th scope="colgroup" colspan="9" align="left">Modalidad: Transfer entregado a trav&eacute;s de su droguer&iacute;a habitual.</th></tr>
					<tr><th scope="colgroup" colspan="9"></th></tr>
					
					
					<tr>									
						<td scope="colgroup" align="center" class="impar">Producto</td>

						<td scope="colgroup" align="center" class="impar"></td>
						<td scope="colgroup" align="center" class="impar"></td>
						<td scope="colgroup" align="center" class="impar"></td>
						
						<td scope="colgroup"></td>

						<td scope="colgroup" align="center" class="impar">Producto</td>

						<td scope="colgroup" align="center" class="impar"></td>
						<td scope="colgroup" align="center" class="impar"></td>
						<td scope="colgroup" align="center" class="impar"></td>
					</tr>
				</thead>
				<tbody>
					<?php 
					if ($condId) {	
						$articulosCond	=	DataManager::getCondicionArticulos($condId);
						if (count($articulosCond)) {
							$style = 0;
							for($k = 0; $k < count($articulosCond); $k++){
							//foreach ($articulosCond as $k => $artCond) {
								$rentabilidad = $rentabilidad2 = $pvp = $pvp2 = 0;
								$artCond 		= $articulosCond[$k];
								$condArtIdArt	= $artCond['cartidart'];
								$condArtPrecio	= $artCond["cartprecio"]; //(PSL)
								$medicinal		= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
								
								$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
								$condArtDescripcion	= DataManager::getArticulo('artdescripcion', $condArtIdArt, $empresa, $laboratorio);
								$artImagen		=	DataManager::getArticulo('artimagen', $condArtIdArt, $empresa, $laboratorio);
								//$artImagen	 	= $_articulo->__get('Imagen');		
								$imagenObject	= DataManager::newObjectOfClass('TImagen', $artImagen);
								$imagen			= $imagenObject->__get('Imagen');
								$producto		= ($imagen) ?	"https://www.neo-farma.com.ar/pedidos/images/imagenes/".$imagen : "/pedidos/images/sin_imagen.png";
										
								//Calcular PVP
								$condArtMedicinal= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
								$condArtIva		 = DataManager::getArticulo('artiva', $condArtIdArt, $empresa, $laboratorio);
								$condArtGanancia = DataManager::getArticulo('artganancia', $condArtIdArt, $empresa, $laboratorio);
								
								$pvp = dac_calcularPVP($condArtPrecio, $condArtIva, $condArtMedicinal, $empresa, $condArtGanancia);
								/*
								$p1 	= floatval($condArtPrecio);//1.45 es el 45% que sale dividiendo PVP / PSL
								$p2 	= floatval(1.450);
								$pvp 	= $p1*$p2;
								$pvp	= ($medicinal) ? $pvp*1.21  : $pvp;
								$pvp 	= number_format($pvp,3,'.','');	// (PVP) 							
								*/
								//$fechaInicio
								$abmArt		=	DataManager::getDetalleArticuloAbm(8, date("Y"), 220181, $condArtIdArt, 'TL');								
								$abmDesc	=	($abmArt[0]['abmdesc'] * $p1) / 100;								
								//$rentabilidad	=	(1 - ( ( $p1 - $abmDesc ) / $pvp)); EN EXCEL
								$abmDesc		= 	number_format($abmDesc,3,'.','');
								$rentabilidad	= 	$p1 - $abmDesc;
								$rentabilidad	=	number_format($rentabilidad,3,'.','');
								$rentabilidad	=	$rentabilidad	/ $pvp;
								$rentabilidad	=	number_format($rentabilidad,3,'.','');
								$rentabilidad	=	ceil((1 - $rentabilidad) * 100);
								$rentabilidad	=	number_format($rentabilidad,0,'.','');
								$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];
								
								//*******************//
								if($k+1 < count($articulosCond)){
									$artCond2 		= $articulosCond[$k+1];
									$condArtIdArt2	= $artCond2['cartidart'];
									$condArtPrecio2	= $artCond["cartprecio"]; //(PSL)

									$condArtNombre2	= DataManager::getArticulo('artnombre', $condArtIdArt2, $empresa, $laboratorio);		
									$condArtDescripcion2	= DataManager::getArticulo('artdescripcion', $condArtIdArt, $empresa, $laboratorio);
									$artImagen2		= DataManager::getArticulo('artimagen', $condArtIdArt2, $empresa, $laboratorio);
									//$artImagen	= $_articulo->__get('Imagen');		
									$imagenObject2	= DataManager::newObjectOfClass('TImagen', $artImagen2);
									$imagen2		= $imagenObject2->__get('Imagen');
									$producto2		= ($imagen2) ?	"https://www.neo-farma.com.ar/pedidos/images/imagenes/".$imagen2 : "/pedidos/images/sin_imagen.png";
										
									//Calcular PVP
									$condArtMedicinal= DataManager::getArticulo('artmedicinal', $condArtIdArt2, $empresa, $laboratorio);
									$condArtIva		 = DataManager::getArticulo('artiva', $condArtIdArt2, $empresa, $laboratorio);
									$condArtGanancia = DataManager::getArticulo('artganancia', $condArtIdArt2, $empresa, $laboratorio);

									$pvp2 = dac_calcularPVP($condArtPrecio2, $condArtIva, $condArtMedicinal, $empresa, $condArtGanancia);
									/*
									$p12 	= floatval($condArtPrecio2);//1.45 es el 45% que sale dividiendo PVP / PSL
									$p22	= floatval(1.450);
									$pvp2 	= $p12*$p22;
									$pvp2 	= number_format($pvp2,3,'.','');
									*/
									
									//$fechaInicio
									$abmArt2		=	DataManager::getDetalleArticuloAbm(8, date("Y"), 220181, $condArtIdArt2, 'TL');								
									$abmDesc2		=	($abmArt2[0]['abmdesc'] * $condArtPrecio2) / 100;								
									//$rentabilidad	=	(1 - ( ( $p1 - $abmDesc ) / $pvp)); EN EXCEL
									$abmDesc2		= 	number_format($abmDesc2,3,'.','');
									$rentabilidad2	= 	$p12 - $abmDesc2;
									$rentabilidad2	=	number_format($rentabilidad2,3,'.','');
									$rentabilidad2	=	$rentabilidad2	/ $pvp2;
									$rentabilidad2	=	number_format($rentabilidad2,3,'.','');
									$rentabilidad2	=	ceil((1 - $rentabilidad2) * 100);
									$rentabilidad2	=	number_format($rentabilidad2,0,'.','');
									$condArtCantMin2	= empty($artCond2['cartcantmin'])?	''	:	$artCond2['cartcantmin'];
								}
								//********************//
								
								$_estilo =	(($style % 2) == 0)? "par" : "impar"; 
								$style	++;
								
								?>
								<tr height="20"><td colspan="9"></td></tr>
								
								<tr height="20">
									<td width="180" class="<?php echo $_estilo; ?>" colspan="4" align="left" style="font-weight: bold;">	
										<?php echo $condArtIdArt." - ".utf8_decode($condArtNombre); ?>
									</td>
									
									<td width="20"></td>
									
									<td width="180" class="<?php echo $_estilo; ?>" colspan="4" align="left" style="font-weight: bold;">	
										<?php echo $condArtIdArt2." - ".utf8_decode($condArtNombre2); ?>
									</td>
								</tr>

								<tr height="25">
									<td width="100" class="<?php echo $_estilo; ?>" rowspan="4" align="center">
										<img src="<?php echo $producto; ?>" alt="Imagen" width="95" height="95"/>
									</td>
									
									<td width="120" class="<?php echo $_estilo; ?>" rowspan="4" colspan="2"  valign="middle" style="font-size: 10px; font-weight:normal;">	
										<?php echo $condArtDescripcion; ?>
									</td>
									
									<td width="60" class="<?php echo $_estilo; ?>" align="left" style="font-size: 10; font-weight: bold;">	
										Rentab
									</td>
									
									
									<td width="20"></td>
									
									
									<td width="100" class="<?php echo $_estilo; ?>" rowspan="4" align="center">
										<img src="<?php echo $producto2; ?>" alt="Imagen" width="95" height="95"/>
									</td>
									
									<td width="120" class="<?php echo $_estilo; ?>" rowspan="4" colspan="2" valign="middle" style="font-size: 10px; font-weight:normal;">	
										<?php echo $condArtDescripcion2; ?>
									</td>
									
									<td width="60" class="<?php echo $_estilo; ?>" align="left" style="font-size: 10; font-weight: bold;">	
										Rentab
									</td>
									
									
								</tr>
								
								<tr height="25">
									<td class="<?php echo $_estilo; ?>" width="60" align="center" style="font-weight: bold;">
										<?php echo $rentabilidad." %"; ?></td>
									
									<td width="20"></td>
									
									<td class="<?php echo $_estilo; ?>" width="60" align="center" style="font-weight: bold;">
										<?php echo $rentabilidad2." %"; ?></td>
								</tr>
								
								<tr height="25">
									<td class="<?php echo $_estilo; ?>" width="60" align="left" style="font-size: 10; font-weight: bold;">PVP</td>
									
									<td width="20"></td>
									
									<td class="<?php echo $_estilo; ?>" width="60" align="left" style="font-size: 10; font-weight: bold;">PVP</td>
								</tr>
								
								<tr height="25">
									<td class="<?php echo $_estilo; ?>" width="60" align="center" style="font-weight: bold;">
										<?php echo "$ ".number_format($pvp,2,'.',''); ?></td>
									
									<td width="20"></td>
									
									<td class="<?php echo $_estilo; ?>" width="60" align="center" style="font-weight: bold;">
										<?php echo "$ ".number_format($pvp2,2,'.',''); ?></td>
								</tr>
								
								<tr height="20"><td colspan="9"></td></tr>
								
								<?php	
								$k++;
							} ?>
							
							
							<tr><td scope="colgroup" colspan="9"></td></tr>
							<tr><td scope="colgroup" colspan="9" align="left">Notas: PVP Precio de venta al P&uacute;blico. </td></tr>
							<tr><td scope="colgroup" colspan="9"></td></tr>
							<tr><td scope="colgroup" colspan="9" align="left" style="font-weight: bold;">* Los precios son los detallados a la fecha y pueden sufrir modificaciones.  </td></tr>
							<tr><td scope="colgroup" colspan="9"></td></tr>
							<tr><td scope="colgroup" colspan="9" align="left">Cualquier inquietud no dude en consultarme y me estar&eacute; comunicando con ustede </td></tr>
							<tr><td scope="colgroup" colspan="9" align="left">a la brevedad.  </td></tr>
							<tr><td scope="colgroup" colspan="9"></td></tr>
							<tr><td scope="colgroup" colspan="9" align="left">Saludos cordiales.</td></tr>
							<tr><td scope="colgroup" colspan="9"></td></tr>
							
						<?php
						}
					} else { ?>
						<tr>
							<td scope="colgroup" colspan="9"  style="border:1px solid #666">No se encontraron condiciones.</td>
						</tr> <?php
					} ?>
					
				</tbody>

				<tfoot>
					<tr>
						<th scope="colgroup" colspan="9" align="center" style="height:100px;"><?php echo $pie; ?></th>
					</tr>	
				</tfoot> 
			</table> <?php
			break;
		default: exit;
			break;
	} ?>
</body>
</html>                
               
               