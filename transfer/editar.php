<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
} ?>

<!doctype html>
<html xml:lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?>
	<script type="text/javascript" src="logica/jquery/jqueryHeader.js"></script>
	<script language="JavaScript" type="text/javascript">
		//**********************//
		//	Nuevo div Artículo	//
		var nextinput = 0;
		function dac_CargarArticulo(idart, ean, nombre, precio){
			document.getElementById("field_listart").style.display	=	'block';
			nextinput++;	

			var campo	= 	'<div id="rut'+nextinput+'">';
				campo += 	'<input id="ptidart'+nextinput+'" name="ptidart[]" type="text" value="'+idart+'" hidden/>';
				campo += 	'<input id="ptnombreart'+nextinput+'" name="ptnombreart[]" type="text" value="'+nombre+'" hidden/>';
				campo += 	'<input id="ptean'+nextinput+'" name="ptean[]" type="text" value="'+ean+'" hidden/>';
				campo += 	'<input id="ptprecioart'+nextinput+'" name="ptprecioart[]" type="text" value="'+precio+'" hidden/>';				
				campo	+= '<div class="bloque_6"><strong> Art&iacute;culo '+idart+'</strong></br>'+nombre+'</div>';
				campo += 	'<div class="bloque_8"><strong> Cantidad </strong><input id="ptcant'+nextinput+'" name="ptcant[]" onblur="javascript:dac_CalcularSubtotalTransfer();" maxlength="5"/></div>';
				campo += 	'<div class="bloque_8"><strong>% Desc </strong> <input id="ptdesc'+nextinput+'" name="ptdesc[]" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onkeyup="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onblur="javascript:dac_CalcularSubtotalTransfer();" maxlength="5"/></div>';

				var plazos = '';
				<?php
				$plazos	= DataManager::getCondicionesDePagoTransfer(0, 0, 1);
				if (count($plazos)) {
					foreach ($plazos as $j => $plazo) {
						$plazo			=	$plazos[$j];
						$plazoId		=	$plazo["condid"];
						$plazoNombre	= 	$plazo["condnombre"];
						
						if ($plazoId == 1) { ?>	
							plazos += '<option value="<?php echo $plazoId; ?>" selected><?php echo $plazoNombre; ?></option>';
							<?php
						} else { ?>
							plazos += '<option value="<?php echo $plazoId; ?>"><?php echo $plazoNombre; ?></option>';
								<?php
						}                         
					} 
				} ?>
				campo += 	'<div class="bloque_7"><strong>Condici&oacute;n</strong><select id="ptcondpago" name="ptcondpago[]">'+plazos+'</select></div>';	
				campo += 	'<div class="bloque_9"></br><input id="btmenos" class="btmenos" type="button" value="-" onClick="EliminarArt('+nextinput+')"></div>';
				campo += 	'<hr>'
			campo	+= '</div>';

			$("#lista_articulos2").append(campo);		
			dac_CargarDescAbm(nextinput);	
		}		

		// Quitar div de artículo
		function EliminarArt(id){
			elemento=document.getElementById('rut'+id);
			elemento.parentNode.removeChild(elemento);
			dac_CalcularSubtotalTransfer();
		}
    </script>
	
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
       	$_section	= "pedidos";
		$_subsection= "nuevo_transfer";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>
    
    <main class="cuerpo">
    	<div class="box_body">
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
        	<form id="fmPedidoTransfer" name="fmPedidoTransfer" method="post">
           		<input id="tblTransfer" name="tblTransfer" type="text" value="0" hidden/>
            	<fieldset>
                    <legend>Pedido Transfer</legend>
                    <div class="bloque_5">
                    	<label for="ptParaIdUsr">Asignado a</label>
                        <select id="ptParaIdUsr" name="ptParaIdUsr" >  
                        	<option id="0" value="0" selected></option> <?php
                            $vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
                            if (count($vendedores)) {	
                                foreach ($vendedores as $k => $vend) {
                                    $idVend		= $vend["uid"];
                                    $nombreVend	= $vend['unombre'];
									if ($idVend == $_SESSION["_usrid"]){ ?>                        		
										<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>" selected><?php echo $nombreVend; ?></option><?php
									} else { ?>
										<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>"><?php echo $nombreVend; ?></option><?php
									}
                                }                            
                            } ?>
                        </select>	
                    </div>
                    
                    <div class="bloque_7">
                    	<label for="contacto">Contacto</label>
                    	<input name="contacto" type="text" maxlength="50"/>		
                    </div> 
                    
                    <div class="bloque_7" align="right">
                        <?php $urlSend	=	'/pedidos/transfer/logica/update.pedido.php';?>
                        <?php $urlBack	=	'/pedidos/transfer/';?>                        
                        <a id="btnSend" title="Enviar"> 
                           	<br>
                            <img class="icon-send" onclick="javascript:dac_sendForm(fmPedidoTransfer, '<?php echo $urlSend;?>', '<?php echo $urlBack;?>');"/>
                        </a>                        
                    </div>       
                </fieldset>
                
                <fieldset>
                    <legend>Cuenta</legend>
                    <div id="detalle_cuenta"></div>
                </fieldset>  
                
                <fieldset id="field_listart" style="display:none;">
                    <legend>Art&iacute;culos</legend> 
                    <div id="lista_articulos2"></div>
                    <div class="bloque_1">
                    	<div id="ptsubtotal" style="display:none;"></div>
                    </div>
                </fieldset>
            </form>	
        </div> <!-- END box_body -->
                                                                
		<div class="box_seccion">
        	<div class="barra">
                <div class="bloque_5">
                    <h1>Cuentas</h1>                	
                </div>
                <div class="bloque_5">
                	<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
                    <input id="txtBuscarEn" type="text" value="tblCuentasTransfer" hidden/>
                </div>  
                <hr>    
            </div> <!-- Fin barra -->            
            <div class="lista">
                <table id="tblCuentasTransfer">
                    <thead>
                        <tr align="left">
                            <th>Emp</th>
                            <th>Id</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>	 <?php	
                        if (!empty($_SESSION["_usrzonas"]))	{
							$cuentas	= DataManager::getCuentas(0, 0, 1, NULL, '"C","CT","T","TT"', $_SESSION["_usrzonas"]);
                            if (count($cuentas)) {
								$claseFila = 0;
                                foreach ($cuentas as $k => $cta) {
                                    $ctaId			=	$cta["ctaid"];
                                    $ctaIdCuenta 	= 	$cta["ctaidcuenta"];
									$ctaIdEmpresa	= 	$cta["ctaidempresa"];
                                    $ctaNombre	 	= 	$cta["ctanombre"];
									$ctaTipo	 	= 	$cta["ctatipo"];
									$ctaActiva	 	= 	$cta["ctaactiva"];
									
									if($ctaIdCuenta != 0){
										//deberá mostrar tódos los transfers menos transfers inactivos.
										if(($ctaTipo == 'T' || $ctaTipo == 'TT') && $ctaActiva == 0){/*no mostrar*/} else {										
											$cuentasRelacionadas	=	DataManager::getCuentasRelacionadas($ctaId);
											if (count($cuentasRelacionadas)) { 
												((($claseFila % 2) == 0)? $clase="par" : $clase="impar"); 
												$claseFila ++; ?>
												<tr class="<?php echo $clase;?>" onclick="javascript:dac_mostrarCuentasRelacionada('<?php echo $ctaId;?>')">
													<td><?php echo $ctaIdEmpresa;?></td>
													<td><?php echo $ctaIdCuenta;?></td>
													<td><?php echo $ctaNombre;?></td>  
												</tr> 

												<tr>
													<td colspan="3">
														<table id="<?php echo $ctaId;?>" class="table-transfer">
															<tbody>
																<tr align="center">
																	<th hidden="hidden">ID</th>
																	<th hidden="hidden">Nombre</th>
																	<th width="30%">Cliente</th>
																	<th width="30%">Droguer&iacute;a</th>
																	<th width="40%">Nombre</th>
																</tr> <?php 

																foreach ($cuentasRelacionadas as $j => $ctaRel) { 
																	((($j % 2) == 0)? $clase2="par" : $clase2="impar");
																	$ctaRelDrogId	= $ctaRel["ctarelidcuentadrog"];
																	
																	$ctRelIdCuenta = DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaRelDrogId);
																	$ctRelNombre = DataManager::getCuenta('ctanombre', 'ctaid', $ctaRelDrogId);				
																	$ctaRelNroCliente = 	$ctaRel["ctarelnroclientetransfer"]; 
																
																	$ctRelEmpresa = DataManager::getCuenta('ctaidempresa', 'ctaid', $ctaRelDrogId);	
																	?>

																	<tr class="<?php echo $clase2;?>" onclick="javascript:dac_cargarCuentaTransferRelacionada('<?php echo $ctaId;?>', '<?php echo $ctaRelDrogId;?>', '<?php echo $ctRelIdCuenta;?>', '<?php echo $ctRelNombre;?>', '<?php echo $ctaRelNroCliente;?>', '<?php echo $ctRelEmpresa;?>')">
																		<td hidden="hidden"><?php echo $ctaIdCuenta;?></td>
																		<td hidden="hidden"><?php echo $ctaNombre;?></td>
																		<td style="border:2px solid #117db6;"><?php echo $ctaRelNroCliente;?></td>
																		<td style="border:2px solid #117db6;"><?php echo $ctRelIdCuenta;?></td>
																		<td style="border:2px solid #117db6;"><?php echo $ctRelNombre;?></td>  
																	</tr>  <?php
																}?>
															</tbody>
														</table>
													</td>  
												</tr><?php 
											}
										}
									}
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="3"><?php echo "No hay cuentas activas."; ?></td>	
                                </tr> <?php 
                            }
                        } else { ?>
                            <tr>
                                <td colspan="3"><?php echo "Usuario sin zonas habilitadas."; ?></td>	
                            </tr> <?php 
                        } ?> 
                    </tbody>
                </table>
            </div> <!-- Fin listar -->
            
            <div class="barra">
                <div class="bloque_5">
                    <h1>Art&iacute;culo</h1>                	
                </div>
                <div class="bloque_5">
                	<input id="txtBuscar2" type="search" autofocus placeholder="Buscar..."/>
                    <input id="txtBuscarEn2" type="text" value="tblTablaArt" hidden/>
                </div>
                <hr>
            </div> <!-- Fin barra -->	
            
            <div class="lista">
            	<div id='tablaarticulos'></div>
            </div> <!-- Fin listar -->	
        </div> <!-- FIN box_seccion -->
        <hr>
	</main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>
