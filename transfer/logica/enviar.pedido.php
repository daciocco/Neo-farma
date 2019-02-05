<?php 
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.ToolBar.php");
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
 }
 
?>	    

<!DOCTYPE html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?> 
    <script src="/pedidos/includes/ExcelExport/jquery.btechco.excelexport.js"></script>
	<script src="/pedidos/includes/ExcelExport/jquery.base64.js"></script>    
	<script language="javascript">
	function dac_Exportar_Pedidos_Transfer(id){
		$("#tblExport"+id).btechco_excelexport({			
        	containerid: "tblExport"+id,
			datatype: $datatype.Table
        });
	}
	</script>
</head>

<body>
    <header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
        $_section	=	"pedidos";
        $_subsection 	=	"mis_transfers";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
    
    <main class="cuerpo"><?php
		/*******************************/
		//Recorro por destino de correo//
		$_drogueria_destinos = DataManager::getDrogueriaTransferTipo(1); 
		if ($_drogueria_destinos) {	
			foreach ($_drogueria_destinos as $k => $_drog_destino) {
				$_Dest_correo	=	$_drog_destino["drogtcorreotransfer"];
				$_Dest_tipo		=	$_drog_destino["drogttipotransfer"];
				$_Dest_cliente	=	$_drog_destino["drogtcliid"];
				$destIdEmpresa	=	$_drog_destino["drogtidemp"];
				
				$_Dest_nombre	= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_Dest_cliente, $destIdEmpresa);		
				$idLoc			= DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $_Dest_cliente, $destIdEmpresa);
				$_Dest_localidad= DataManager::getLocalidad('locnombre', $idLoc);	
				
				$transfer = 0; 
				$_posicion = 0;
				switch($_Dest_tipo){ //Aca puede que valga solo con tipo A B C y D								
					case 'A': /*delsud*/						
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'Cuit', 'Domicilio', 'Contacto', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Descuento', 'Plazo', 'Fecha'); $_posicion = 7; break;	
					case 'B': /*monroe*/ 								
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'Cuit', 'Domicilio', 'Contacto', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Descuento', 'Fecha'); $_posicion = 7; break;						
					case 'C': /*suizo*/ 								
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Bonificadas', 'Descuento', 'Cuit', 'Fecha'); $_posicion = 4; break;							
					case 'D': /*OTROS - Kellerof - cofarmen - delLitoral - SUR - PICO*/ 								
						$titulosColumnas = array('IDTransfer', 'NroClienteDrog', 'Raz&oacute;nSocial', 'Cuit', 'EAN', 'Descripci&oacute;n', 'Unidades', 'Descuento', 'CondPago', 'Fecha'); $_posicion = 5; break;								
					default: $transfer = 1; break;					
				}
				
				if ($transfer == 0){ 
					//*--------------------------------------//
					//Selecciono Droguerías por $_Dest_correo//
					$_droguerias = DataManager::getDrogueria( NULL, NULL, NULL, $_Dest_correo ); 
					if ($_droguerias) {
						$_pedido	=	0;
						foreach ($_droguerias as $x => $_drogueria) {
							$_Dcorreo		=	$_drogueria["drogtcorreotransfer"];
							$_Did			=	$_drogueria["drogtid"];
							$_Didcliente 	= 	$_drogueria["drogtcliid"];
							
							//Busco pedidos de cada droguería con el mismo mail
							$_transfers_recientes	= DataManager::getTransfersPedido(1, NULL, NULL, $_Didcliente, NULL, NULL); 
							if (count($_transfers_recientes)){ 
								if ($_pedido == 0) { //carga en el primer pedido que ecuentra 
									$_pedido = 1;  ?>
                                        <table id="tblExport<?php echo $_Dest_cliente;?>" border="1">
                                            <TR>
                                                <TD colspan="2" align="center">PEDIDO TRANSFER (<?php echo date('d-m-y'); ?>)</TD>
                                                <TD colspan="<?php echo (count($titulosColumnas)-6);?>" align="center"><?php echo $_Dest_nombre; if($_Dest_tipo == 'A'){ echo " - ".$_Dest_localidad;} ?></TD>
                                                <TD colspan="4" align="right"><?php echo $_Dest_correo;?></TD>
                                            </TR>                            
                                            <TR> <?php
                                                for ($x=0; $x<count($titulosColumnas); $x++){ 
                                                    echo "<TD>".$titulosColumnas[$x]."</TD>";
                                                }?>    						
                                            </TR> <?php	
								}
								
								foreach($_transfers_recientes as $y => $_transfer_r){	
									$_idpedido		= 	$_transfer_r["ptid"];
									$_nropedido		= 	$_transfer_r["ptidpedido"];
									$_nroclidrog	= 	$_transfer_r["ptnroclidrog"];
									$_ptidclineo	= 	$_transfer_r["ptidclineo"];
									$_ptclirs 		=	$_transfer_r["ptclirs"];
									$_ptclicuit		=	$_transfer_r["ptclicuit"];
									$_ptdomicilio	=	$_transfer_r["ptdomicilio"];
									$_ptcontacto	=	$_transfer_r["ptcontacto"];
									$_ptidart 		=	$_transfer_r["ptidart"];
									
									$idCondPago 	=	$_transfer_r["ptcondpago"];
									$condPlazo		=	DataManager::getCondicionDePagoTransfer('conddias', 'condid', $idCondPago);
									$condPlazo		=	($condPlazo == 0) ? 'HABITUAL' : $condPlazo; 
									
									$_articulo		=	DataManager::getFieldArticulo("artidart", $_ptidart);
									$_ean			= 	$_articulo['0']["artcodbarra"];
									$_descripcion	=	$_articulo['0']['artnombre'];
									
									$_ptunidades	= 	$_transfer_r["ptunidades"];	
									$_ptdescuento	= 	$_transfer_r["ptdescuento"];	
									$_ptfechapedido	= 	substr($_transfer_r["ptfechapedido"], 0, 10);	
									$_ptunidades	= 	$_transfer_r["ptunidades"];									
									
									switch($_Dest_tipo){
										case 'A': /*delsud*/ 
											$_datosColumnas = array($_nropedido, $_nroclidrog, $_ptclirs, $_ptclicuit, $_ptdomicilio, $_ptcontacto, $_ean, $_descripcion, $_ptunidades, $_ptdescuento, $condPlazo, $_ptfechapedido); break;
										case 'B': /*monroe*/ 
											$_datosColumnas = array($_nropedido, $_nroclidrog, $_ptclirs, $_ptclicuit, $_ptdomicilio, $_ptcontacto, $_ean, $_descripcion, $_ptunidades, $_ptdescuento, $_ptfechapedido); break;											
										case 'C': /*suizo*/	
											$_datosColumnas = array($_nropedido, $_nroclidrog, $_ptclirs, $_ean, $_descripcion, $_ptunidades, '0', $_ptdescuento, $_ptclicuit, $_ptfechapedido); break;											 	
										case 'D': /*OTRAS*/
											$_datosColumnas = array($_nropedido, $_nroclidrog, $_ptclirs, $_ptclicuit, $_ean, $_descripcion, $_ptunidades, $_ptdescuento, $condPlazo, $_ptfechapedido); break;
										default: break;				
									} ?>
					
									<TR> <?php										
										for ($x=0;$x<count($_datosColumnas); $x++){
											if($x == ($_posicion-1)){
												echo '<TD align=left style="mso-style-parent:style0; mso-number-format:\@">'.$_datosColumnas[$x].'</TD>'; 
											} else{ echo '<TD align=left>'.$_datosColumnas[$x].'</TD>'; }							
										} ?>     						
									</TR> <?php 
				
									// ******************************* //
									// Al Registrar el pedido, lo desactivo (por enviado)
									// ******************************* //
									if ($_idpedido) {
										$_ptobject	= DataManager::newObjectOfClass('TPedidostransfer', $_idpedido);											
										$_status	= ($_ptobject->__get('Activo')) ? 0 : 1;	
																			
										$_ptobject->__set('IDAdmin'			, $_SESSION["_usrid"]);
										$_ptobject->__set('IDNombreAdmin'	, $_SESSION["_usrname"]);
										$_ptobject->__set('FechaExportado'	, date('Y-m-d H:i:s'));
									
										$_ptobject->__set('Activo'			, '0');
										$ID = DataManager::updateSimpleObject($_ptobject);	
										
										//*************************//
										//REGISTRO DE ULTIMA COMPRA//
										//*************************//										
										//actualizo fecha última compra de LA CUENTA ( cliente neo-farma )
										/*if($_ptidclineo != 0){		
											$cuenta		= 	DataManager::getCuenta('ctaid', 'ctaidcuenta', $_ptidclineo, 1);
											//$_cliente	= 	DataManager::getCliente('cliid', 'cliidcliente', $_ptidclineo, 1);
											/*$_cliptobject	=	DataManager::newObjectOfClass('TCliente', $_cliente);
											$_cliptobject->__set('Fechacompra', date('Y-m-d'));
											$ID 		= 	DataManager::updateSimpleObject($_cliptobject);*/
											/*$ctaObject	=	DataManager::newObjectOfClass('TCuenta', $cuenta);
											$ctaObject->__set('FechaCompra', date('Y-m-d HH:mm:ss'));
											$ID 		= 	DataManager::updateSimpleObject($ctaObject);
											
										}*/
										
									} 
									
									
									
								} //fin foreach 
							} //fin if
						} //fin foreach	
						if ($_pedido == 1){ ?>
									</table> 
								<div hidden="hidden"><button id="btnExport" hidden="hidden"></button></div>
							</br></br>
							
							<?php
							echo "<script>";
							echo "dac_Exportar_Pedidos_Transfer(".$_Dest_cliente.");";
							echo "</script>";
							//sleep(2);	
					   }									
								
					} //fin if 	
				} else { echo "Una de las droguerias no tiene bien definido su tipo.";  }//fin if transfer						
			} //fin for
		} else {
			echo "No se encuentran DROGUERIAS ACTIVAS. Gracias."; 
		} ?>
    </main> <!-- fin cuerpo -->		
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>