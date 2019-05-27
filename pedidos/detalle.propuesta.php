<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$idPropuesta=	empty($_REQUEST['propuesta']) 	?	0	:	$_REQUEST['propuesta'];

$btnPrint	=	sprintf( "<a id=\"imprimir\" href=\"print.propuesta.php?propuesta=%s\" target=\"_blank\" title=\"Imprimir\" >%s</a>", $idPropuesta, "<img class=\"icon-print\"/>");
$btnAprobar	=	sprintf( "<a id=\"aprobarPropuesta\" title=\"Aprobar\">%s</a>", "<img class=\"icon-proposal-approved\"/>");
$btnRechazar=	sprintf( "<a id=\"rechazarPropuesta\" title=\"Rechazar\">%s</a>", "<img class=\"icon-proposal-rejected\"/>");
?>

<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
    <script language="JavaScript" src="/pedidos/pedidos/logica/jquery/jquery.aprobar.js" type="text/javascript"></script>
</head>

<body>	
    <header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php");?>
    </header><!-- cabecera -->			
    
    <nav class="menuprincipal"> <?php 
        $_section	= "pedidos";
        $_subsection= "mis_propuestas";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->	
        
    <main class="cuerpo">
        <div class="cbte">
        
        <!--div class="bloque_3"-->
			<fieldset id='box_error' class="msg_error">          
				<div id="msg_error"></div>
			</fieldset>  
			<fieldset id='box_cargando' class="msg_informacion">   
				<div id="msg_cargando"></div>      
			</fieldset>
			<fieldset id='box_confirmacion' class="msg_confirmacion">
				<div id="msg_confirmacion"></div>     
			</fieldset>
		<!--/div-->
       
        <?php		
            if ($idPropuesta) {
				$usr	= ($_SESSION["_usrrol"] == "V") ? NULL : $_SESSION["_usrid"];   
                $propuesta	= 	DataManager::getPropuesta($idPropuesta);
                if ($propuesta) { 	
                    foreach ($propuesta as $k => $prop) {
						$empresa	= 	$prop["propidempresa"]; 
						$laboratorio= 	$prop["propidlaboratorio"]; 
						$nombreEmp	= 	DataManager::getEmpresa('empnombre', $empresa);	
						//header And footer
						include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
						?>                                
						<div class="cbte_header">    
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
						<div class="cbte_boxcontent"> 
							<div class="cbte_box"><?php echo $fecha;?></div>
							<div class="cbte_box">
								Nro. Propuesta:
								<input id="propuesta" name="propuesta" value="<?php echo $idPropuesta; ?>" hidden/>
								<?php echo str_pad($idPropuesta, 9, "0", STR_PAD_LEFT); ?>
							</div>
							<div class="cbte_box" align="right">
								<?php echo $usrNombre;?>
							</div>
						</div>  <!-- cbte_boxcontent -->

						<div class="cbte_boxcontent"> 
							<div class="cbte_box"> 
								Cuenta: </br>
								Direcci&oacute;n: </br>
							</div>  <!-- cbte_box -->

							<div class="cbte_box2">  
								<?php echo $cuenta." - ".$cuentaNombre;?></br>
								<?php echo $domicilio." - ".$localidad." - ".$cp; ?>
							</div>  <!-- cbte_box2 -->
						</div>  <!-- cbte_boxcontent -->
                        						
						<?php
                        $detalles	= 	DataManager::getPropuestaDetalle($idPropuesta, 1);
						if ($detalles) {
							$totalFinal = 0;
							foreach ($detalles as $j => $det) {	
								if($j == 0){
									$condIdPago	= 	$det["pdcondpago"];
									$condicionesDePago = DataManager::getCondicionesDePago(0, 0, NULL, $condIdPago); 
									if (count($condicionesDePago)) { 
										foreach ($condicionesDePago as $k => $condPago) {
											$condPagoCodigo	=	$condPago["IdCondPago"];
											$condNombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);	
											$condDias	= "(";					
											$condDias	.= empty($condPago['Dias1CP']) ? '0' : $condPago['Dias1CP'];
											$condDias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
											$condDias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
											$condDias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
											$condDias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
											$condDias	.= " D&iacute;as)";
											
											$condDias	.= ($condPago['Porcentaje1CP'] == '0.00') ? '' : ' '.$condPago['Porcentaje1CP'].' %';
										}
									}
									
									
									?>
									<div class="cbte_boxcontent"> 
										<div class="cbte_box2">
											Condici&oacute;n de Pago:
											<?php echo $condNombre." ".$condDias;?>
										</div>
									</div>  <!-- cbte_boxcontent -->
                                    
                                    <div class="cbte_boxcontent2">
                                        <table>
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
								
											$precio			=	str_replace('EUR','', $det['pdprecio']);
											$b1				=	($det['pdbonif1'] == 0)	?	''	:	$det['pdbonif1'];
											$b2				=	($det['pdbonif2'] == 0)	?	''	:	$det['pdbonif2'];
											$bonif			=	($b1 == 0)	?	''	:	$b1." X ".$b2;
											$desc1			=	($det['pddesc1'] == 0)	?	''	:	$det['pddesc1'];
											$desc2			=	($det['pddesc2'] == 0)	?	''	:	$det['pddesc2'];

											$total			=	round(dac_calcularPrecio($unidades, $precio, $medicinal, $desc1, $desc2), 3);	
											$totalFinal		+=	$total;

											echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
								
											echo sprintf("<td height=\"15\" align=\"center\">%s</td><td align=\"center\">%s</td><td>%s</td><td align=\"right\" style=\"padding-right:15px;\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"right\" style=\"padding-right:5px;\">%s</td>", $idArt, number_format($unidades,0), $descripcion, number_format(round($precio,2),2), $bonif, number_format(round($desc1,2),2), number_format(round($desc2,2),2), number_format(round($total,2),2) );								
											echo sprintf("</tr>");
							} ?>
										</table> 
									</div>  <!-- cbte_boxcontent2 -->  <?php
						} 
					} ?>
                        <div class="cbte_boxcontent2"> 
                            <div class="cbte_box2">
								<?php echo $observacion;?>
                           </div>
                            <div class="cbte_box" align="right" style="font-size:18px; float: right;">
                            	TOTAL: <?php echo number_format(round($totalFinal,2),2); ?>
                            </div>
                        </div>  <!-- cbte_boxcontent2 -->
					                             
					<div class="cbte_boxcontent2">    
						<?php echo $piePropuesta; ?>                 
					</div>  <!-- cbte_boxcontent2 -->
					
					<div class="cbte_boxcontent2" align="center"> <?php 
						echo $btnPrint; 						
						if($usr){
							if($estado == 1){
								echo $btnAprobar;
								echo $btnRechazar;
							} 
						} else {
							if($estado != 3 && $estado != '0'){
								echo $btnRechazar;	
							}
						}
						?> 
					</div>  <!-- cbte_boxcontent2 -->  <?php
				}
			}?>  
        </div>  <!-- cbte -->  
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>