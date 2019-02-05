<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$_nropedido		=	empty($_REQUEST['nropedido']) 	?	0 : $_REQUEST['nropedido'];

$_button_print	=	sprintf( "<a id=\"imprimir\" href=\"imprimir_pedido.php?nropedido=%s\" target=\"_blank\" title=\"Imprimir\" >%s</a>", $_nropedido, "<img src=\"/pedidos/images/icons/icono-print.png\" border=\"0\" />");
$_btn_aprobar	=	sprintf( "<a id=\"aprobar\" title=\"Aprobar Negociaci&oacute;n\">%s</a>", "<img src=\"/pedidos/images/icons/icono-pedido-aprobar.png\" border=\"0\" onmouseover=\"this.src='/pedidos/images/icons/icono-pedido-aprobar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-pedido-aprobar.png';\"/>");
$_btn_rechazar	=	sprintf( "<a id=\"rechazar\" title=\"Rechazar Negociaci&oacute;n\">%s</a>", "<img src=\"/pedidos/images/icons/icono-pedido-rechazar.png\" border=\"0\" onmouseover=\"this.src='/pedidos/images/icons/icono-pedido-rechazar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-pedido-rechazar.png';\"/>");

?>

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
        $_section		=	"pedidos";
        $_subsection 	=	"mis_pedidos";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
            
    <main class="cuerpo">        
		 
		<div class="cbte"> <?php
			if ($_nropedido) {
				//Si el usuario es vendedor o es el usrid de Ernesto
				if($_SESSION["_usrrol"] !=  "V" || $_SESSION["_usrrol"] !=  "17") {
					$_usr	=	NULL;
				} else {
					$_usr	=	$_SESSION["_usrid"];
				}							
				//$_usr		=	($_SESSION["_usrrol"] !=  "V")	?	NULL	:	$_SESSION["_usrid"];
				$_detalles	= 	DataManager::getPedidos($_usr, NULL, $_nropedido);
				if ($_detalles) { 	
					$_total_final	=	0;
					foreach ($_detalles as $k => $_detalle) {	
						if ($k==0){ 
							$empresa		= 	$_detalle["pidemp"]; 
							$_empresas	= 	DataManager::getEmpresas();
							if ($_empresas) { 
								foreach ($_empresas as $i => $_emp) {
									$_idempresa	= 	$_emp['empid'];
									if ($empresa == $_idempresa){												
										$_nombreemp		= 	$_emp['empnombre'];
										$_diremp		= 	$_emp['empdomicilio'];   
										$_localidademp	= 	" - ".$_emp['emplocalidad'];
										$_cpemp			= 	" - CP".$_emp['empcp'];  
										$_telemp		= 	" - Tel: ".$_emp['empcp']; 
										$_correoemp		= 	$_emp['empcorreo']." / ";
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
									<?php echo $_diremp; ?> <?php echo $_cpemp; ?>  <?php echo $_localidademp; ?> <?php echo $_telemp; ?></br>
									<?php echo $_correoemp; ?> www.neo-farma.com.ar</br>
									IVA RESPONSABLE INSCRIPTO
								</div>  <!-- cbte_boxheader -->   
							</div>  <!-- boxtitulo -->

							<?php
							$_fecha_pedido		=	$_detalle['pfechapedido'];
							$_idusuario			=	$_detalle['pidusr'];			
							$_nombreusr			= 	DataManager::getUsuario('unombre', $_idusuario); 
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
							
							
							$_ordencompra		= 	($_detalle["pordencompra"] == 0)	?	''	:	"Orden Compra: ".$_detalle["pordencompra"];

							$_negociacion		=	$_detalle["pnegociacion"];
							$_aprobado			=	$_detalle["paprobado"];	
							$_observacion		=	$_detalle["pobservacion"];
							?>
							
							<div class="cbte_boxcontent"> 
								<div class="cbte_box">
									<?php echo $_fecha_pedido;?>
								</div>
								<div class="cbte_box">
									Nro. Pedido: 
									<input id="nropedido" name="nropedido" value="<?php echo $_nropedido; ?>" hidden/>
									<?php echo str_pad($_nropedido, 9, "0", STR_PAD_LEFT); ?>
								</div>
								<div class="cbte_box" align="right">
									<?php echo $_nombreusr;?>
								</div>
							</div>  <!-- cbte_boxcontent -->

							<div class="cbte_boxcontent"> 							
								<div class="cbte_box"> 
									Cliente: </br>
									Direcci&oacute;n: </br>
								</div>  <!-- cbte_box -->

								<div class="cbte_box2">  

									<?php echo $_idcliente." - ".$_nombrecli;?></br>
									<?php echo $_domiciliocli." - ".$_localidadcli." - ".$_codpostalcli; ?>
								</div>  <!-- cbte_box2 -->
							</div>  <!-- cbte_boxcontent -->

							<div class="cbte_boxcontent"> 
								<div class="cbte_box2">
									Condici&oacute;n de Pago: <?php echo $_condpago." | ".$_condnombre." ".$_conddias;?>
								</div>
								<div class="cbte_box" align="right">
									<?php echo $_ordencompra;?>
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

									$_total			=	0;
											
									$_idart			=	$_detalle['pidart'];
									$laboratorio	=	$_detalle['pidlab'];
									$_unidades		=	$_detalle['pcantidad'];
									$_descripcion	=	DataManager::getArticulo('artnombre', $_idart, 1, $laboratorio);									
									//$_precio		=	str_replace('EUR','', money_format('%.2n', $_detalle['pprecio']));
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

									echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
									echo sprintf("<td height=\"15\" align=\"center\">%s</td><td align=\"center\">%s</td><td>%s</td><td align=\"right\" style=\"padding-right:15px;\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"right\" style=\"padding-right:5px;\">%s</td>", $_idart, $_unidades, $_descripcion, $_precio, $_bonif, $_desc1, $_desc2, $_total); //str_replace('EUR','',money_format('%.2n', $_total))
									echo sprintf("</tr>");  
					} ?>						
								</table>                                    
							</div>  <!-- cbte_boxcontent2 -->
							
							<div class="cbte_boxcontent2"> 
								<div class="cbte_box2">
									<?php echo $_observacion;?>
								</div>
								
								<div class="cbte_box" align="right" style="font-size:18px; float: right;">
									TOTAL: <?php echo $_total_final; //str_replace('EUR','',money_format('%.2n', $_total_final));?>
								</div>
							</div>  <!-- cbte_boxcontent-->
							<?php
				}
			} ?>

			<div class="cbte_boxcontent2" align="center"> <?php 
				echo $piePedido;
				echo $_button_print; 

				if($_SESSION["_usrrol"]!="V"){														
					if($_negociacion == 1 && $_aprobado == 1){
						echo $_btn_aprobar; 
						echo $_btn_rechazar;				
					} 
				} ?>
			</div>  <!-- cbte_boxcontent2 -->  
		</div>  <!-- cbte -->   
	</main> <!-- CUERPO -->

	<footer class="pie">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
	</footer> <!-- fin pie -->
            
</body>
</html>