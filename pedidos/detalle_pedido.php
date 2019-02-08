<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$nroPedido	= empty($_REQUEST['nropedido']) 	?	0 : $_REQUEST['nropedido'];
$btnPrint	= sprintf( "<a id=\"imprimir\" href=\"imprimir_pedido.php?nropedido=%s\" target=\"_blank\" title=\"Imprimir\" >%s</a>", $nroPedido, "<img src=\"/pedidos/images/icons/icono-print.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-print-hover';\"  onmouseout=\"this.src='/pedidos/images/icons/icono-print.png';\" border=\"0\" />");
$btnAprobar	= sprintf( "<a id=\"aprobar\" title=\"Aprobar Negociaci&oacute;n\">%s</a>", "<img src=\"/pedidos/images/icons/icono-pedido-aprobar.png\" border=\"0\" onmouseover=\"this.src='/pedidos/images/icons/icono-pedido-aprobar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-pedido-aprobar.png';\"/>");
$btnRechazar= sprintf( "<a id=\"rechazar\" title=\"Rechazar Negociaci&oacute;n\">%s</a>", "<img src=\"/pedidos/images/icons/icono-pedido-rechazar.png\" border=\"0\" onmouseover=\"this.src='/pedidos/images/icons/icono-pedido-rechazar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-pedido-rechazar.png';\"/>"); ?>

<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
     <script language="JavaScript"  src="/pedidos/pedidos/logica/jquery/jquery.aprobar.js" type="text/javascript"></script>
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
   
    <nav class="menuprincipal"> <?php
        $_section	= "pedidos";
        $_subsection= "mis_pedidos";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
            
    <main class="cuerpo">
		<div class="cbte"> <?php
			if ($nroPedido) {
				//Si el usuario es vendedor o es el usrid de Ernesto
				if($_SESSION["_usrrol"] !=  "V" || $_SESSION["_usrrol"] !=  "17") {
					$usr	=	NULL;
				} else {
					$usr	=	$_SESSION["_usrid"];
				}								
				
				$detalles	= 	DataManager::getPedidos($usr, NULL, $nroPedido);
				if ($detalles) { 	
					$totalFinal	=	0;
					foreach ($detalles as $k => $detalle) {	
						if ($k==0){ 
							$empresa	= $detalle["pidemp"]; 
							$empresas	= DataManager::getEmpresas();
							if ($empresas) { 
								foreach ($empresas as $i => $emp) {
									$empresaId	= 	$emp['empid'];
									if ($empresa == $empresaId){
										$empNombre		= $emp['empnombre'];
										$empDir			= $emp['empdomicilio'];   
										$empLocalidad	= " - ".$emp['emplocalidad'];
										$empCP			= " - CP".$emp['empcp'];  
										$empTel			= " - Tel: ".$emp['empcp']; 
										$empCorreo		= $emp['empcorreo']." / ";
									}
								}
								//header And footer
								include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
							}
							?>                                
							<div class="cbte_header">                    
								<div class="cbte_boxheader">  
									<?php echo $cabeceraPedido; ?>   
								</div>  <!-- cbte_boxheader -->

								<div class="cbte_boxheader">                        
									<h1>PEDIDO WEB</h1> </br>
									<?php echo $empDir; ?> <?php echo $empCP; ?>  <?php echo $empLocalidad; ?> <?php echo $empTel; ?></br>
									<?php echo $empCorreo; ?> www.neo-farma.com.ar</br>
									IVA RESPONSABLE INSCRIPTO
								</div>  <!-- cbte_boxheader -->   
							</div>  <!-- boxtitulo -->

							<?php
							$fechaPedido		= $detalle['pfechapedido'];
							$usrId				= $detalle['pidusr'];			
							$usrNombre			= DataManager::getUsuario('unombre', $usrId); 
							$clienteId			= $detalle["pidcliente"];
							$ctaId				= DataManager::getCuenta('ctaid', 'ctaidcuenta', $clienteId, $empresa);
							$cliNombre			= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $clienteId, $empresa);
							$_domiciliocli		= DataManager::getCuenta('ctadireccion', 'ctaidcuenta', $clienteId, $empresa)." ".DataManager::getCuenta('ctadirnro', 'ctaidcuenta', $clienteId, $empresa)." ".DataManager::getCuenta('ctadirpiso', 'ctaidcuenta', $clienteId, $empresa)." ".DataManager::getCuenta('ctadirdpto', 'ctaidcuenta', $clienteId, $empresa);	
							$_localidadcli		= DataManager::getCuenta('ctalocalidad', 'ctaidcuenta', $clienteId, $empresa);
							$_codpostalcli		= DataManager::getCuenta('ctacp', 'ctaidcuenta', $clienteId, $empresa);
							$_condpago			= $detalle["pidcondpago"];
							
							$condicionesDePago	= DataManager::getCondicionesDePago(0, 0, NULL, $_condpago); 
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
							
							$ordenCompra	= ($detalle["pordencompra"] == 0)	?	''	:	"Orden Compra: ".$detalle["pordencompra"];
							$negociacion	= $detalle["pnegociacion"];
							$aprobado		= $detalle["paprobado"];	
							$observacion	= $detalle["pobservacion"];
							?>
							
							<div class="cbte_boxcontent"> 
								<div class="cbte_box">
									<?php echo $fechaPedido;?>
								</div>
								<div class="cbte_box">
									Nro. Pedido: 
									<input id="nropedido" name="nropedido" value="<?php echo $nroPedido; ?>" hidden/>
									<?php echo str_pad($nroPedido, 9, "0", STR_PAD_LEFT); ?>
								</div>
								<div class="cbte_box" align="right">
									<?php echo $usrNombre;?>
								</div>
							</div>  <!-- cbte_boxcontent -->

							<div class="cbte_boxcontent"> 							
								<div class="cbte_box"> 
									Cliente: </br>
									Direcci&oacute;n: </br>
								</div>  <!-- cbte_box -->

								<div class="cbte_box2">
									<?php echo $clienteId." - ".$cliNombre;?></br>
									<?php echo $_domiciliocli." - ".$_localidadcli." - ".$_codpostalcli; ?>
								</div>  <!-- cbte_box2 -->
							</div>  <!-- cbte_boxcontent -->

							<div class="cbte_boxcontent"> 
								<div class="cbte_box2">
									Condici&oacute;n de Pago: <?php echo $_condpago." | ".$_condnombre." ".$_conddias;?>
								</div>
								<div class="cbte_box" align="right">
									<?php echo $ordenCompra;?>
								</div>
							</div>  <!-- cbte_boxcontent -->

							<div class="cbte_boxcontent2">
								<table class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
									<thead>
										<tr align="left">
											<th scope="col" width="10%" height="18" align="center">C&oacute;digo</th>
											<th scope="col" width="10%" align="center">Cant</th>
											<th scope="col" width="30%" align="center">Descripci&oacute;n</th>
											<th scope="col" width="10%" align="center">Precio</th>
											<th scope="col" width="10%" align="center">Bonif</th>
											<th scope="col" width="10%" align="center">Dto1</th>
											<th scope="col" width="10%" align="center">Dto2</th>
											<th scope="col" width="10%" >Total</th>
										</tr>
									</thead> <?php
						}

									$total			=	0;
									$artId			= $detalle['pidart'];
									$laboratorio	= $detalle['pidlab'];
									$unidades		= $detalle['pcantidad'];
									$descripcion	= DataManager::getArticulo('artnombre', $artId, 1, $laboratorio);									
									//$precio		=	str_replace('EUR','', money_format('%.2n', $detalle['pprecio']));
									$precio	= $detalle['pprecio'];
									$b1		= ($detalle['pbonif1'] == 0)	?	''	:	$detalle['pbonif1'];
									$b2		= ($detalle['pbonif2'] == 0)	?	''	:	$detalle['pbonif2'];
									$bonif	= ($detalle['pbonif1'] == 0)	?	''	:	$b1." X ".$b2;
									$desc1	= ($detalle['pdesc1'] == 0)	?	''	:	$detalle['pdesc1'];
									$desc2	= ($detalle['pdesc2'] == 0)	?	''	:	$detalle['pdesc2'];

									//**************************************//
									//	Calculo precio final por artículo	//
									//**************************************//
									$precio_f	= $precio * $unidades;									
									if ($desc1 != ''){ $precio_f = $precio_f - ($precio_f * ($desc1/100)); }
									if ($desc2 != ''){ $precio_f = $precio_f - ($precio_f * ($desc2/100)); }	
									$total		= round($precio_f, 2);
									$totalFinal	+= $total;
									//**************************************//

									echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
									echo sprintf("<td height=\"15\" align=\"center\">%s</td><td align=\"center\">%s</td><td>%s</td><td align=\"right\" style=\"padding-right:15px;\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"right\" style=\"padding-right:5px;\">%s</td>", $artId, $unidades, $descripcion, round($precio,2), $bonif, $desc1, $desc2, round($total,2));
									echo sprintf("</tr>");  
					} ?>						
								</table>                                    
							</div>  <!-- cbte_boxcontent2 -->
							
							<div class="cbte_boxcontent2"> 
								<div class="cbte_box2">
									<?php echo $observacion;?>
								</div>
								
								<div class="cbte_box" align="right" style="font-size:18px; float: right;">
									TOTAL: $ <?php echo round($totalFinal, 2); //str_replace('EUR','',money_format('%.2n', $totalFinal));?>
								</div>
							</div>  <!-- cbte_boxcontent-->
							<?php
				}
			} ?>
			
			<div class="bloque_1" align="center"> <?php 
				echo $piePedido;
				echo $btnPrint;
				if($_SESSION["_usrrol"]!="V"){
					if($negociacion == 1 && $aprobado == 1){
						echo $btnAprobar; 
						echo $btnRechazar;				
					} 
				} 
				
				$ruta	= $_SERVER['DOCUMENT_ROOT']."/pedidos/cuentas/archivos/".$ctaId."/pedidos/".$nroPedido."/";	
				$data	=	dac_listar_directorios($ruta);
				if($data){ 	
					foreach ($data as $file => $timestamp) {
						//saco la extensión del archivo
						$extencion	= explode(".", $timestamp);
						$ext		= $extencion[1];
						$name 		= explode("-", $timestamp, 4);
						$archivo 	= trim($name[3]);
						?>
						<a href="<?php echo "/pedidos/cuentas/archivos/".$ctaId."/pedidos/".$nroPedido."/".$archivo; ?>" title="Orden de Compra" target="_blank"> <?php
							if($ext == "pdf"){ ?> 
								<img id="imagen" 
									src="/pedidos/images/icons/icono-ordencompra.png"	onmouseover="this.src='/pedidos/images/icons/icono-pdf-hover.png';" 	onmouseout="this.src='/pedidos/images/icons/icono-ordencompra.png';"/>
								<?php 	  
							} else { ?>
								<img id="imagen" 
									src="/pedidos/images/icons/icono-ordencompra.png"	onmouseover="this.src='/pedidos/images/icons/icono-jpg-hover';" 	onmouseout="this.src='/pedidos/images/icons/icono-ordencompra.png';"/>
								<?php  
							} ?>
						</a> <?php 
					} 
				} ?>
			</div> 
		</div>  <!-- cbte -->   
	</main> <!-- CUERPO -->

	<footer class="pie">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
	</footer> <!-- fin pie -->
            
</body>
</html>