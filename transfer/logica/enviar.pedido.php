<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
} ?>

<!DOCTYPE html>
<html lang='es'>

<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?>
</head>

<body>
    <header class="cabecera">       
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
        $_section	= "pedidos";
        $_subsection= "mis_transfers";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
    
    <main class="cuerpo"><?php
		//Recorro por destino de correo//
		$drogueriaDestinos = DataManager::getDrogueriaTransferTipo(1); 
		if ($drogueriaDestinos) {	
			foreach ($drogueriaDestinos as $k => $drogDestino) {
				$destCorreo		= $drogDestino["drogtcorreotransfer"];
				$destTipo		= $drogDestino["drogttipotransfer"];
				$destCuenta		= $drogDestino["drogtcliid"];
				$destIdEmpresa	= $drogDestino["drogtidemp"];
				$destNombre		= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $destCuenta, $destIdEmpresa);		
				$idLoc			= DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $destCuenta, $destIdEmpresa);
				$destLocalidad	= DataManager::getLocalidad('locnombre', $idLoc);	
				
				$transfer 		= 0; 
				$posicion 		= 0;
				switch($destTipo){ //Aca puede que valga solo con tipo A B C y D								
					case 'A': /*delsud*/						
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'Cuit', 'Domicilio', 'Contacto', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Descuento', 'Plazo', 'Fecha'); $posicion = 7; break;	
					case 'B': /*monroe*/ 								
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'Cuit', 'Domicilio', 'Contacto', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Descuento', 'Fecha'); $posicion = 7; break;						
					case 'C': /*suizo*/ 								
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Bonificadas', 'Descuento', 'Cuit', 'Fecha'); $posicion = 4; break;							
					case 'D': /*OTROS - Kellerof - cofarmen - delLitoral - SUR - PICO*/
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'Cuit', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Descuento', 'CondPago', 'Fecha'); $posicion = 5; break;								
					default: $transfer = 1; break;					
				}
				
				if ($transfer == 0){ 					
					//Selecciono Droguerías por $destCorreo
					$droguerias = DataManager::getDrogueria( NULL, NULL, NULL, $destCorreo ); 
					if ($droguerias) {
						$pedido	= 0;
						foreach ($droguerias as $x => $drog) {
							$drogCorreo		=	$drog["drogtcorreotransfer"];
							$drogCuenta 	= 	$drog["drogtcliid"];
							
							//Busco pedidos de cada droguería con el mismo mail
							$transfersRecientes	= DataManager::getTransfersPedido(1, NULL, NULL, $drogCuenta, NULL, NULL); 
							if (count($transfersRecientes)){ 
								if ($pedido == 0) { //carga en el primer pedido que ecuentra 
									$pedido = 1;  ?>
									<table id="tblExport<?php echo $destCuenta;?>" border="1">
										<tr>
											<td colspan="2" align="center">PEDIDO TRANSFER (<?php echo date('d-m-y'); ?>)</td>
											<td colspan="<?php echo (count($titulosColumnas)-6);?>" align="center"><?php echo $destNombre; if($destTipo == 'A'){ echo " - ".$destLocalidad;} ?></td>
											<td colspan="4" align="right"><?php echo $destCorreo;?></td>
										</tr>                            
										<tr> <?php
											for ($x=0; $x<count($titulosColumnas); $x++){ 
												echo "<td>".$titulosColumnas[$x]."</td>";
											}?>    						
                                        </tr> <?php	
								}
								
								foreach($transfersRecientes as $y => $transRec){	
									$idPedido		= $transRec["ptid"];
									$nroPedido		= $transRec["ptidpedido"];
									$nroCliDrog		= $transRec["ptnroclidrog"];
									$ptIdCuenta		= $transRec["ptidclineo"];
									$ptCuentaRS 	= $transRec["ptclirs"];
									$ptCuentaCuit	= $transRec["ptclicuit"];
									$ptDomicilio	= $transRec["ptdomicilio"];
									$ptContacto		= $transRec["ptcontacto"];
									$ptIdArt 		= $transRec["ptidart"];
									$idCondPago 	= $transRec["ptcondpago"];
									$condPlazo		= DataManager::getCondicionDePagoTransfer('conddias', 'condid', $idCondPago);
									$condPlazo		= ($condPlazo == 0) ? 'HABITUAL' : $condPlazo;
									$articulo		= DataManager::getFieldArticulo("artidart", $ptIdArt);
									$ean			= $articulo['0']["artcodbarra"];
									$descripcion	= $articulo['0']['artnombre'];
									$ptUnidades	= 	$transRec["ptunidades"];	
									$ptDescuento	= $transRec["ptdescuento"];	
									$ptFechaPedido	= substr($transRec["ptfechapedido"], 0, 10);	
									$ptUnidades		= $transRec["ptunidades"];																	
									switch($destTipo){
										case 'A': /*delsud*/ 
											$datosColumnas = array($nroPedido, $nroCliDrog, $ptCuentaRS, $ptCuentaCuit, $ptDomicilio, $ptContacto, $ean, $descripcion, $ptUnidades, $ptDescuento, $condPlazo, $ptFechaPedido); break;
										case 'B': /*monroe*/ 
											$datosColumnas = array($nroPedido, $nroCliDrog, $ptCuentaRS, $ptCuentaCuit, $ptDomicilio, $ptContacto, $ean, $descripcion, $ptUnidades, $ptDescuento, $ptFechaPedido); break;											
										case 'C': /*suizo*/	
											$datosColumnas = array($nroPedido, $nroCliDrog, $ptCuentaRS, $ean, $descripcion, $ptUnidades, '0', $ptDescuento, $ptCuentaCuit, $ptFechaPedido); break;											 	
										case 'D': /*OTRAS*/
											$datosColumnas = array($nroPedido, $nroCliDrog, $ptCuentaRS, $ptCuentaCuit, $ean, $descripcion, $ptUnidades, $ptDescuento, $condPlazo, $ptFechaPedido); break;
										default: break;				
									} ?>
					
									<tr> <?php										
										for ($x=0;$x<count($datosColumnas); $x++){
											if($x == ($posicion-1)){
												echo '<td align=left style="mso-style-parent:style0; mso-number-format:\@">'.$datosColumnas[$x].'</td>'; 
											} else{ echo '<td align=left>'.$datosColumnas[$x].'</td>'; }
										} ?>     						
									</tr> <?php
									// Al Registrar el pedido, lo desactivo (por enviado)
									if ($idPedido) {
										$ptObject	= DataManager::newObjectOfClass('TPedidostransfer', $idPedido);											
										$_status	= ($ptObject->__get('Activo')) ? 0 : 1;
										$ptObject->__set('IDAdmin'			, $_SESSION["_usrid"]);
										$ptObject->__set('IDNombreAdmin'	, $_SESSION["_usrname"]);
										$ptObject->__set('FechaExportado'	, date('Y-m-d H:i:s'));
										$ptObject->__set('Activo'			, '0');
										$ID = DataManager::updateSimpleObject($ptObject);	
									}
								} //fin foreach 
							} //fin if
						} //fin foreach	
						if ($pedido == 1){ ?>
							</table> 
							</br></br>
							<?php
							echo "<script>";
							echo "exportTableToExcel('tblExport".$destCuenta."', 'Transfers-".date("d-m-Y")."-".$destNombre."')";
							echo "</script>";
					   }	
					} //fin if
				} else { echo "Una de las droguerias no tiene bien definido su tipo.";  }//fin if transfer						
			} //fin for
		} else {
			echo "No se encuentran DROGUERIAS ACTIVAS."; 
		} ?>
    </main> <!-- fin cuerpo -->		
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>