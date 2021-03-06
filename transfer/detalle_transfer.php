<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$_ptnropedido 	= empty($_REQUEST['idpedido']) 	? 0 : $_REQUEST['idpedido'];
$backURL		= empty($_REQUEST['backURL']) 	? '/pedidos/mispedidos/': $_REQUEST['backURL'];

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>

<body>	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
	<nav class="menuprincipal"> <?php 
		$_section		=	"pedidos";
        $_subsection 	=	"mis_transfers";
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
	</nav>
        
    <main class="cuerpo">
		<div class="cbte">
			<div class="cbte_header">
				<div class="cbte_boxheader"> 
					<?php echo $cabeceraPedido; ?>
				</div>  <!-- cbte_boxheader -->
				<div class="cbte_boxheader"> 
					<h1>PEDIDO WEB TRANSFER</h1></br> 
					Guevara 1347 - CP1427 - Capital Federal - Tel: 4555-3366</br>
					transfers@neo-farma.com.ar / www.neo-farma.com.ar</br>
					IVA RESPONSABLE INSCRIPTO
				</div>  <!-- cbte_boxheader -->                    
			</div>  <!-- boxtitulo -->

			<?php
			if ($_ptnropedido) {
				//EL DETALLE DE PEDIDO NO ESTÁ CONTEMPLANDO LA POSIBILIDAD de que haya dos clientes con el mismo id pero de distinta empresa y/o nombre	 
				$_detalles	= DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_ptnropedido);
				
				//DataManager::getDetallePedidoTransfer($_ptnropedido);
				if ($_detalles) { 
					for( $k=0; $k < count($_detalles); $k++ ){		
						$_detalle 	= 	$_detalles[$k];	
						if ($k==0){
							$_fecha_pedido	= $_detalle['ptfechapedido'];	
							$_idvendedor	= $_detalle['ptidvendedor'];	
							$_nombreven		= DataManager::getUsuario('unombre', $_idvendedor);
							$paraIdUsr		= $_detalle['ptparaidusr'];
							$paraIdUsrNombre= DataManager::getUsuario('unombre', $paraIdUsr);			
							$_iddrogueria	= $_detalle['ptiddrogueria'];
							$ptIdCta		= $_detalle['ptidclineo'];
							
							$droguerias		= DataManager::getDrogueria(NULL, NULL, NULL, NULL, $_iddrogueria);
							if($droguerias){
								foreach ($droguerias as $j => $drog) {
									$empresaDrog= $drog['drogtidemp'];									
									$nombreDrog	= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_iddrogueria, $empresaDrog);
								}
							} else {
								$nombreDrog	= '';
							}
								
							$_idcliente_drog= $_detalle['ptnroclidrog'];
							if ($ptIdCta != 0){
								$_idcliente_neo	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $ptIdCta, 1);
							}							
							$_razonsocial	= $_detalle['ptclirs'];
							$_cuit			= $_detalle['ptclicuit'];
							$_domicilio		= $_detalle['ptdomicilio'];
							$_contacto		= $_detalle['ptcontacto']; ?>
							
							<div class="cbte_boxcontent"> 
								<div class="cbte_box"><?php echo $_fecha_pedido;?></div>
								<div class="cbte_box">Nro. Transfer <?php echo $_ptnropedido;?></div>
								<div class="cbte_box" align="right"><?php echo $_nombreven;?></div>
							</div>  <!-- cbte_box_nro -->

							<?php if($paraIdUsr != $_idvendedor){?>
								<div class="cbte_boxcontent" align="right">
									<div class="cbte_box" ><?php echo "<strong>Para: </strong>".$paraIdUsrNombre;?></div>
								</div>  <!-- cbte_box_nro -->
							<?php } ?>

							<div class="cbte_boxcontent">							
								<div class="cbte_box"> 
									<?php if ($ptIdCta != 0) { echo "Nro Cuenta:</br>"; }?>
									Raz&oacute;n Social: </br>
									Droguer&iacute;a: </br>
									Nro Cliente Droguer&iacute;a: </br>
									CUIT: </br>
									<?php if ($_domicilio != "") { echo "Domicilio:</br>"; }?>
									<?php if ($_contacto != "") { echo "Contacto:</br>"; }?>
								</div>  <!-- cbte_box -->

								<div class="cbte_box2">  
									<?php if ($ptIdCta != 0) { echo $_idcliente_neo."</br>";}?>
									<?php echo $_razonsocial; ?></br>
									<?php echo $_iddrogueria." - ".substr($nombreDrog,0,35);?></br>
									<?php echo $_idcliente_drog;?></br>
									<?php echo $_cuit; ?></br>
									<?php if ($_domicilio != "") { echo $_domicilio."</br>"; }?>
									<?php if ($_contacto != "") { echo $_contacto."</br>"; }?>
								</div>  <!-- cbte_box2 -->
							</div>  <!-- cbte_boxcontent -->

							<div class="cbte_boxcontent2">
								<table>
									<thead>
										<tr align="left">
											<th scope="col" width="15%" height="18">C&oacute;digo</th>
											<th scope="col" width="15%">Cantidad</th>
											<th scope="col" width="50%">Descripci&oacute;n</th>
											<th scope="col" width="10%">CondPago</th>
											<th scope="col" width="10%">Descuento</th>
										</tr>
									</thead>
									<?php
						}
						
									$_ptidart		=	$_detalle['ptidart'];
									$_unidades		=	$_detalle['ptunidades'];
									$condPagoId		=	$_detalle['ptcondpago'];
									$condPagoDias	=	DataManager::getCondicionDePagoTransfer('conddias', 'condid', $condPagoId);
									$condPagoDias 	=	($condPagoDias == 0)	?	'Habitual'	:	$condPagoDias;
									$_descuento		=	$_detalle['ptdescuento'];							
									$_descripcion	=	DataManager::getArticulo('artnombre', $_ptidart, 1, 1);

									echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
									echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td>", $_ptidart, number_format($_unidades,0), $_descripcion, $condPagoDias, number_format(round($_descuento,2),2));
									echo sprintf("</tr>");  
					} ?>												
								</table>
							</div>  <!-- cbte_boxcontent2 -->
					<?php
				}
			} ?>
			
			<div class="cbte_boxcontent2" align="center"> 
				<?php echo $piePedido; ?> 
				<?php 
				if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){ 
					//Reasigna el usuarioAsignado de la venta ?>
					<form id="frmReasignar" name="frmReasignar" method="post">
						<input id="nroPedido" name="nroPedido" value="<?php echo $_ptnropedido; ?>" hidden="hidden"/>
						<div class="bloque_1">     
							<fieldset id='box_error' class="msg_error">          
								<div id="msg_error"></div>
							</fieldset>                                                                         
							<fieldset id='box_cargando' class="msg_informacion">                        	
								<div id="msg_cargando"></div>      
							</fieldset> 
							<fieldset id='box_confirmacion' class="msg_confirmacion">
								<div id="msg_confirmacion"></div>      
							</fieldset>
						</div>
												
						<div class="bloque_6">
							<label for="ptAsignar">Reasignar a</label>
							<select id="ptAsignar" name="ptAsignar" >  
								<option id="0" value="0" selected></option> <?php
								$vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
								if (count($vendedores)) {	
									foreach ($vendedores as $k => $vend) {
										$idVend		= $vend["uid"];
										$nombreVend	= $vend['unombre'];
										if ($idVend == $paraIdUsr){ ?>                        		
											<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>" selected><?php echo $nombreVend; ?></option><?php
										} else { ?>
											<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>"><?php echo $nombreVend; ?></option><?php
										}
									}                            
								} ?>
							</select> 
                        </div>
                        
                        <div class="bloque_8">
                        	<br>
							<a id="btnReasignar" title="Reasignar" style="cursor:pointer;"> 
								<img class="icon-user-assigned" onclick="javascript:dac_sendForm(frmReasignar, '/pedidos/transfer/logica/reasignar.pedido.php', '/pedidos/transfer/' );"/>
							</a>
						</div>
					</form>
					<?php
				} ?>
			</div>  <!-- cbte_boxcontent2 --> 
		</div>  <!-- boxcenter -->  
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>