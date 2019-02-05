<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$idPropuesta	= empty($_REQUEST['propuesta']) ? 0 : $_REQUEST['propuesta'];

?>
<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>    
</head>

<body>	
    <header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php");?>
    </header><!-- cabecera -->
    
    <main class="cuerpo">
    
        <div id="factura" align="center">	
            <div class="cbte" style="overflow:auto; background-color: #FFF; width: 800px;">	
                <?php
				$propuesta	= 	DataManager::getPropuesta($idPropuesta);
				if ($propuesta) { 
					foreach ($propuesta as $k => $prop) {
						$empresa	= 	$prop["propidempresa"]; 
						$laboratorio= 	$prop["propidlaboratorio"]; 
						$nombreEmp	= 	DataManager::getEmpresa('empnombre', $empresa);	
						
						//header And footer
						include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
						
						?>                                
						<div class="cbte_header" style="border-bottom: solid 1px #CCCCCC; color:#373435; padding:15px; height:110px;">    
							<?php echo $cabeceraPropuesta; ?>                 
						</div>  <!-- boxtitulo -->
												
						<?php						
						$fecha			=	$prop['propfecha'];
						$usrProp		=	$prop['propusr'];			
						$usrNombre		= 	DataManager::getUsuario('unombre', $usrProp);  																		
						$cuenta			= 	$prop["propidcuenta"];  									   
						$cuentaNombre	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $cuenta, $empresa);
						$domicilio		= 	DataManager::getCuenta('ctadireccion', 'ctaidcuenta', $cuenta, $empresa);
						$idLocalidad	= 	DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $cuenta, $empresa);
						$localidad		=	DataManager::getLocalidad('locnombre', $idLocalidad);	
						$cp				= 	DataManager::getCuenta('ctacp', 'ctaidcuenta', $cuenta, $empresa);
						$estado			=	$prop["propestado"];						
						$observacion	=	$prop["propobservacion"];
						
						?>
							<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color:#cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 
							<div class="cbte_box" style="height: auto; line-height: 20px; float:left; width: 33%; font-weight:bold;">
								<?php echo $fecha;?>
							</div>
							<div class="cbte_box" style="height: auto; line-height: 20px; float:left; width: 33%; font-weight:bold;">
								Nro. Propuesta:
								<?php echo str_pad($idPropuesta, 9, "0", STR_PAD_LEFT); ?>
							</div>
							<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%; font-weight:bold;">
								<?php echo $usrNombre;?>
							</div>
							</div>  <!-- cbte_boxcontent -->
                        
                        
						
						
							<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color:#cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 
								<div class="cbte_box" style="height: auto; line-height: 20px; float:left; width: 16%;"> 
									Cuenta: </br>
									Direcci&oacute;n: </br>
								</div>  <!-- cbte_box -->
							
								<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%;">  
									
									<?php echo $cuenta." - ".$cuentaNombre;?></br>
									<?php echo $domicilio." - ".$localidad." - ".$cp; ?>
								</div>  <!-- cbte_box2 -->
							</div>  <!-- cbte_boxcontent -->
						
						
						<?php
						$detalles	= 	DataManager::getPropuestaDetalle($idPropuesta, 1);
						if ($detalles) { 	
							foreach ($detalles as $j => $det) {	
								if($j == 0){
									$condIdPago	= 	$det["pdcondpago"];
									
									$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $condIdPago); 
									if (count($condicionesDePago)) { 
										foreach ($condicionesDePago as $k => $condPago) {
											$condPagoCodigo	=	$condPago["IdCondPago"];								
											$condNombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
											$condDias	= "(";					
											$condDias	.= empty($condPago['Dias1CP']) ? '' : $condPago['Dias1CP'];
											$condDias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
											$condDias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
											$condDias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
											$condDias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
											$condDias	.= " D&iacute;as)";
											
											$condDias	.= ($condPago['Porcentaje1CP'] == '0.00') ? '' : ' '.$condPago['Porcentaje1CP'].' %';
										}
									}
									
									?>
									
										<div class="cbte_boxcontent" align="left" style="font-size: 14px; background-color: #cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 
											<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%;"><span style=" color: #2D567F; font-weight:bold;">Condici&oacute;n de Pago:<?php echo $condNombre." ".$condDias;?></div>
										</div>  <!-- cbte_boxcontent -->
									
									<div class="cbte_boxcontent2" style="padding: 15px; overflow:auto; height:auto;">
										<table class="datatab" width="95%" border="0" cellpadding="0" cellspacing="0">
											<thead>
												<tr align="left">
													<th scope="col" width="10%" align="center">Producto</th>
													<th scope="col" width="10%" height="18" align="center">Art</th>
													<th scope="col" width="10%" align="center">Cant</th>
													<th scope="col" width="30%" align="center">Descripci&oacute;n</th>
													<th scope="col" width="10%" align="center">Precio</th>
													<th scope="col" width="10%" align="center">Bonif</th>
													<th scope="col" width="10%" align="center">Dto1</th>
													<th scope="col" width="10%" align="center">Dto2</th>
													<th scope="col" width="10%" align="center">Total</th>
												</tr>
											</thead>
									<?php
								}
								
								$total			=	0;
								$totalFinal		+=	0;		
								$idArt			=	$det['pdidart'];
								$unidades		=	$det['pdcantidad'];
								$descripcion	=	DataManager::getArticulo('artnombre', $idArt, $empresa, $laboratorio);	
								$medicinal		=	DataManager::getArticulo('artmedicinal', $idArt, $empresa, $laboratorio);
								$medicinal		=	($medicinal == 'S') ? 0 : 1;
								
								$precio			=	str_replace('EUR','',money_format('%.2n', $det['pdprecio']));
								$b1				=	($det['pdbonif1'] == 0)	?	''	:	$det['pdbonif1'];
								$b2				=	($det['pdbonif2'] == 0)	?	''	:	$det['pdbonif2'];
								$bonif			=	($det['pdbonif1'] == 0)	?	''	:	$b1." X ".$b2;
								
								$desc1			=	($det['pddesc1'] == 0)	?	''	:	$det['pddesc1'];
								$desc2			=	($det['pddesc2'] == 0)	?	''	:	$det['pddesc2'];
																	
								$total			=	dac_calcularPrecio($unidades, $precio, 0, $desc1, $desc2);	
								$totalFinal		+=	$total;
								
								$artImagen		=	DataManager::getArticulo('artimagen', $idArt, $empresa, $laboratorio);
								//$artImagen	 	= $_articulo->__get('Imagen');		
								$imagenObject	= DataManager::newObjectOfClass('TImagen', $artImagen);
								$imagen			= $imagenObject->__get('Imagen');
								$img			= ($imagen) ?	"/pedidos/images/imagenes/".$imagen : "/pedidos/images/sin_imagen.png";
								
								/*<img src="<?php echo $img; ?>" alt="Imagen" width="100"/>*/
							
								echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
								echo sprintf("<td height=\"15\" align=\"center\"><img src=\"%s\" alt=\"Imagen\" width=\"100\"/></td><td align=\"center\">%s</td><td>%s</td><td>%s</td><td align=\"right\" style=\"padding-right:15px;\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"right\" style=\"padding-right:5px;\">%s</td>", $img, $idArt, $unidades, $descripcion, $precio, $bonif, $desc1, $desc2, str_replace('EUR','',money_format('%.2n', $total)));
								echo sprintf("</tr>");
								
								
							} ?>
							</table> 
							</div> <!-- cbte_boxcontent2 --> <?php
						} 
					} ?>
						
						<div class="cbte_boxcontent2" align="left" style="font-size: 14px; background-color: #cfcfcf; padding: 5px; padding-left: 15px; padding-right: 15px; min-height: 20px; overflow: hidden;"> 
							<div class="cbte_box2" style="height: auto; line-height: 20px; float:left; width: 66%; border:1px solid #cfcfcf;"><span style=" font-weight:bold;"><?php echo $observacion;?></div>
							<div class="cbte_box" align="right" style="height: auto; line-height: 20px; float:left; width: 33%; font-size:26px;"><span style=" color: #2D567F; font-weight:bold;">TOTAL: <?php echo str_replace('EUR','',money_format('%.2n', $totalFinal));?></div>
						</div>  <!-- cbte_boxcontent2 -->
					
					<div class="cbte_boxcontent2">    
						<?php echo $piePropuesta; ?>                 
					</div>  <!-- cbte_boxcontent2 -->
					
					<?php
				} ?>	
        	</div>  <!-- factura contenido --> 	
        </div>  <!-- factura --> 
                  
                   
    </main> <!-- fin cuerpo -->	  
</body>
</html> 

<?php	
	echo "<script>"; 
	echo "javascript:setTimeout(function(){ dac_imprimirMuestra('factura'); }, 3000);";
	echo "</script>"; 
?>     