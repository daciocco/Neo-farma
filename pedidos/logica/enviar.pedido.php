<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
} ?>

<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?> 
    <script src="/pedidos/includes/ExcelExport/jquery.btechco.excelexport.js"></script>
	<script src="/pedidos/includes/ExcelExport/jquery.base64.js"></script> 
	<script language="javascript">
		function dac_Exportar_Pedido(id){
			$('tblExport_'+id).btechco_excelexport({		
				containerid: 'tblExport_'+id,
				datatype: $datatype.Table
			});
		}
	</script>
    
	<script language="javascript">
		function saveTextAsFile(empresa) {			
			var textToWrite 		=	document.getElementById("inputTextToSave"+empresa).value;
			textToWrite 			= 	textToWrite.replace(/#/g, "\r\n");
			var textFileAsBlob 		= 	new Blob([textToWrite], {type:'text/plain'});
			var fileNameToSaveAs 	= 	document.getElementById("inputFileNameToSaveAs"+empresa).value;
			var downloadLink 		= 	document.createElement("a");
			downloadLink.setAttribute("id", empresa);			
			downloadLink.download 	= 	fileNameToSaveAs;
			downloadLink.innerHTML 	= 	"Descargando Archivo";
				// Firefox requires the link to be added to the DOM
				// before it can be clicked.
				downloadLink.href 			= 	window.URL.createObjectURL(textFileAsBlob);
				downloadLink.onclick 		= 	destroyClickedElement;
				downloadLink.style.display 	= 	"none";
				document.body.appendChild(downloadLink);
			downloadLink.click();
			//Se agrega para quitar el bloqueo por cargar de página 
			$('#imgBloqueo').fadeOut('slow');	
			$('#imgBloqueo').remove;
			$('html, body, .cuerpo').css('overflow', 'auto'); 
		}
		
		function destroyClickedElement(event) {
			document.body.removeChild(event.target);
		}
	</script>
	
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->
          
    <?php if ($_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){?>
		 <nav class="menuprincipal"> <?php 
			$_section	=	"pedidos";
			$_subsection 	=	"mis_pedidos";
			include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
		</nav> <!-- fin menu -->										
	<?php }?>  

    <main class="cuerpo"> 
		<div class="pedidos_enviados">  <?php
			//*********************//
			// Recorro por empresa //
			//*********************//
			$_empresas	= 	DataManager::getEmpresas(1);
			if ($_empresas) { 
				foreach ($_empresas as $k => $_emp) {
					$_idempresa		= 	$_emp['empid'];											
					$_nombreemp		= 	$_emp['empnombre'];
					
					//**************************************************************//
					// Selecciono los Pedidos Activos por Empresa PARA Pre-facturar //
					//**************************************************************//
					$_pedidos 	= 	DataManager::getPedidos(NULL, 1, NULL, $_idempresa, NULL, 0); 
					if ($_pedidos) { 
						$_txtFile = "Fecha|idcliente|IdPedido|IdLab|IdArt|NombreArt|Cantidad|Precio|Bonif1|Bonif2|Dto1|Dto2|Dto3|idcondpago|oc|observacion|Tipo|Nombre|Provincia|Localidad|Direccion|CP|Telefono|ctrl"; ?>
						<table id="tblExport_<?php echo $_idempresa;?>" border="1">
							<TR>
								<TD colspan="2">Fecha:</TD>
								<TD colspan="3" align="left"><?php echo date("d/m/y H:i:s"); ?></TD>
								<TD colspan="12"  align="center"><?php echo $_nombreemp;?></TD>
							</TR>
							<TR>
								<TD colspan="2">Exportado por:</TD>
								<TD colspan="3"><?php echo $_SESSION["_usrname"]; ?></TD>
								<TD colspan="12" align="center">
									<button id="crearText<?php echo $_idempresa;?>" onclick="saveTextAsFile('<?php echo $_idempresa; ?>')" style="font-size: 14px; font-weight:bold; height: 30px; padding: 5px; background-color: #0f6b9c; color: white; border: none;">EXPORTAR TXT</button>
									
								</TD>
							</TR>			
							<TR>
								<TD width="1px">Fecha</TD>
								<TD width="1px">Vendedor</TD>
								<TD width="1px">Cliente</TD>
								<TD width="1px">Pedido</TD>
								<TD width="1px">Lab</TD>
								<TD width="1px">IdArt</TD>
								<TD width="1px">Cant</TD>
								<TD width="1px">Precio</TD>
								<TD width="1px">B1</TD> <TD width="1px">B2</TD>
								<TD width="1px">D1</TD> <TD width="1px">D2</TD> <TD width="1px">D3</TD>
								<TD width="1px">CondPago</TD>
								<TD width="1px">OC</TD>
								<TD width="1px">Ctrl</TD>
								<TD width="1px">Observaci&oacute;n</TD>
							</TR> <?php	

							foreach ($_pedidos as $j => $_pedido) { 
								$_idpedido		= 	$_pedido["pid"];
								$_idusuario		= 	$_pedido["pidusr"];

								//datos para control
								$_idemp			=	$_pedido["pidemp"];
								$idCondComercial=	$_pedido["pidcondcomercial"];
								//*****************//

								$_fecha_pedido	=	substr($_pedido['pfechapedido'], 0, 10);				
								$_nombreusr		= 	DataManager::getUsuario('unombre', $_idusuario); 	
								$_idcli			= 	$_pedido["pidcliente"];  
								$_nropedido		=	$_pedido["pidpedido"]; 
								$_idlab			=	$_pedido["pidlab"];  
								$_idart			=	$_pedido['pidart'];
								$_nombreart		=	DataManager::getArticulo('artnombre', $_idart, $_idemp, $_idlab);
								$_cantidad		=	$_pedido['pcantidad'];							
								$_precio		=	str_replace('EUR','', money_format('%.3n', $_pedido['pprecio']));
								//$_precio		=	$_pedido['pprecio'];
								
								
								$_b1			=	$_pedido['pbonif1'];
								$_b2			=	$_pedido['pbonif2'];
								$_desc1			=	$_pedido['pdesc1'];
								$_desc2			=	$_pedido['pdesc2'];
								$_desc3			=	'';
								$_condpago		=	$_pedido["pidcondpago"];
								$_ordencompra	= 	($_pedido["pordencompra"] == 0)	?	''	:	$_pedido["pordencompra"];
								$_observacion	= 	$_pedido["pobservacion"];
								
								$_control 		= 	'';

								//Si hay condicion se controla, sino significa que es propuesta.
								if($idCondComercial){
									$ctaRuteo			= 	DataManager::getCuenta('ctaruteo', 'ctaidcuenta', $_idcli, $_idemp);
									$ctaCatComercial	= 	DataManager::getCuenta('ctacategoriacomercial', 'ctaidcuenta', $_idcli, $_idemp);
									$_categoria			=	($ctaRuteo == 0) ? $ctaCatComercial : $ctaRuteo.$ctaCatComercial;


									//Exceptúo del control a cuentas Droguería
									if ($_categoria != 1){
										//************************//
										// Condiciones COMERCIALES //
										//************************//
										$articulosCond	= DataManager::getCondicionArticulos($idCondComercial);
										if (count($articulosCond)) {	
											foreach ($articulosCond as $j => $artCond) {	
												$condArtIdArt	= $artCond['cartidart'];
												if($_idart == $condArtIdArt){

													$condArtPrecio	= $artCond["cartprecio"];					
													$condArtPrecioDigit	= ($artCond["cartpreciodigitado"] == '0.000')?	''	:	$artCond["cartpreciodigitado"]; 
													$precioArt 		= ($artCond["cartpreciodigitado"] == '0.000')?	$condArtPrecio	:	$condArtPrecioDigit;
													$condArtIva		= $artCond["cartiva"];                         
													$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];

													//Control de datos por artículo
													//Controlo Precio
													if($precioArt > $_precio){
														$_control .= "X";
														//echo "Precio de art&iacute;culo ".$articulosIdArt[$key]." no v&aacute;lido."; exit;
													}

													//Controlo Cantidad mínima
													if($condArtCantMin){
														if($_cantidad < $condArtCantMin){
															$_control .= "X";
															//echo "La cantidad m&iacute;nima del art&iacute;culo ".$articulosIdArt[$key]." debe ser ".$condArtCantMin; exit;
														}
													}			

													//**********************************//
													//	Calculo Condiciones del Pedido	//
													//**********************************//	
													$articulosBonifB1		= 	($_b1) ? $_b1 : 1;
													$articulosBonifB2		= 	($_b2) ? $_b2 : 1;	
													$articulosBonifD1		= 	($_desc1) ? $_desc1 : 0;
													$articulosBonifD2		=	($_desc2) ? $_desc2 : 0;

													$cantBonificada			=	($articulosBonifB1 * $_cantidad) / $articulosBonifB2;	
													$cantEnteraBonificada	=	dac_extraer_entero($cantBonificada);				
													$precioUno				= 	$_cantidad * $_precio;				
													$precioDos				=	$precioUno / $cantEnteraBonificada; 
													$precioDesc1			=	$precioDos - ($precioDos * $articulosBonifD1/100);			
													$precioDesc2 			=	$precioDesc1 - ($precioDesc1 * $articulosBonifD2/100);						
													$precioFinalVendido 	= 	$precioDesc2;

													//NO CONTROLO EXACTAMENTE LAS BONIFICACIONES y DESCUENTOS
													//YA QUE solo basta que EL RESULTADO NO SEa MENOR A X								
													//**************************************//
													//	Calculo Condiciones de la Empresa	// 
													//según la cantidad pedida por el vendedor!
													//**************************************//	
													$artBonifCant	= 	1;
													$artBonifB1		= 	1;
													$artBonifB2		= 	1;	
													$artBonifD1		= 	0;
													$artBonifD2		=	0;
													$articulosBonif	= DataManager::getCondicionBonificaciones($idCondComercial, $condArtIdArt);
													//importante que la función devuelva los valores ordenados
													if (count($articulosBonif)){
														foreach ($articulosBonif as $i => $artBonif){	
															if($_cantidad >= $artBonif['cbcant']){
																$artBonifCant	= 	($artBonif['cbcant']) ? $artBonif['cbcant'] 	: '1';
																$artBonifB1		= 	($artBonif['cbbonif1']) ? $artBonif['cbbonif1'] : '1';
																$artBonifB2		= 	($artBonif['cbbonif2']) ? $artBonif['cbbonif2'] : '1';	
																$artBonifD1		= 	($artBonif['cbdesc1']) ? $artBonif['cbdesc1'] 	: '0';	
																$artBonifD2		=	($artBonif['cbdesc2']) ? $artBonif['cbdesc2'] 	: '0';
															}
														}
													} 

													$cantBonificada			=	($artBonifB1 * $_cantidad) / $artBonifB2;	  	
													$cantEnteraBonificada	=	dac_extraer_entero($cantBonificada);
													$precioUno				= 	$_cantidad * $precioArt;	
													$precioDos				=	$precioUno / $cantEnteraBonificada; 
													$precioDesc1			=	$precioDos - ($precioDos * $artBonifD1/100);			
													$precioDesc2 			=	$precioDesc1 - ($precioDesc1 * $artBonifD2/100);
													$precioFinalEmpresa 	= 	$precioDesc2;									
													//*************************************//
													if($precioFinalVendido < $precioFinalEmpresa){ 
														//Si precio final vendido < precio final de empresa, estará mal
														//echo "Las condiciones del art&iacute;culo ".$articulosIdArt[$key]." dan precio menor al acordado. "; exit;
														$_control .= "X";
													}	
												}
											}
										}
									} else { 
										//"Cuenta DROGUERÍA...";
										$_control = 'D'; //"X1"
									}
								} else { $_control = ''; }

								$_b1	=	($_b1 == 0)		?	''	:	$_b1;
								$_b2	=	($_b2 == 0)		?	''	:	$_b2;
								$_desc1	=	($_desc1 == 0)	?	''	:	$_desc1;
								$_desc2	=	($_desc2 == 0)	?	''	:	$_desc2;
								?>

								<TR>
									<TD><?php echo $_fecha_pedido; ?></TD><TD><?php echo $_nombreusr; ?></TD><TD><?php echo $_idcli; ?></TD><TD><?php echo $_nropedido; ?><TD><?php echo $_idlab; ?></TD><TD><?php echo $_idart; ?></TD><TD><?php echo $_cantidad; ?></TD><TD><?php echo $_precio; ?></TD><TD><?php echo $_b1; ?></TD><TD><?php echo $_b2; ?></TD><TD><?php echo $_desc1; ?></TD><TD><?php echo $_desc2; ?></TD><TD><?php echo $_desc3; ?></TD><TD><?php echo $_condpago; ?></TD><TD><?php echo $_ordencompra; ?></TD><TD><?php echo $_control; ?></TD><TD><?php echo $_observacion; ?></TD>
								</TR>  <?php

								$_observ_txt = str_replace('"', '', $_observacion);
								//Realizo sanear observación ya que hiperwin al importar excel dan errores los acentos y demás.
								$_observ_txt = dac_sanearString($_observ_txt);
								//completo con 0 los vacíos para que no de error al importar en Hiperwin  
								$_cantidad		= (empty($_cantidad))? 0 : $_cantidad;  
								$_precio		= (empty($_precio))? 0.00 : $_precio;
								$_b1			= (empty($_b1))? 0 : $_b1;
								$_b2			= (empty($_b2))? 0 : $_b2;
								$_desc1			= (empty($_desc1))? 0 : $_desc1;  
								$_desc2			= (empty($_desc2))? 0 : $_desc2;  
								$_desc3			= (empty($_desc3))? 0 : $_desc3;  
								$_ordencompra	= (empty($_ordencompra))? 0 : $_ordencompra; 
								
								
								$tipoPedido		= $_pedido["ptipo"];
								$nombre 		= $_pedido["pnombre"];
								$provincia 		= $_pedido["pprovincia"];
								$localidad 		= $_pedido["plocalidad"];
								$direccion 		= $_pedido["pdireccion"];
								$cp 			= $_pedido["pcp"];
								$telefono 		= $_pedido["ptelefono"];
								
								$_txtFile = $_txtFile."#".$_fecha_pedido.'|'.$_idcli.'|'.$_nropedido.'|'.$_idlab.'|'.$_idart.'|'.$_nombreart.'|'.$_cantidad.'|'.$_precio.'|'.$_b1.'|'.$_b2.'|'.$_desc1.'|'.$_desc2.'|'.$_desc3.'|'.$_condpago.'|'.$_ordencompra.'|'.$_observ_txt.'|'.$tipoPedido.'|'.$nombre.'|'.$provincia.'|'.$localidad.'|'.$direccion.'|'.$cp.'|'.$telefono.'|'.$_control;
								
								// ******************************* //
								// Al Enviar el pedido, lo desactivo (por enviado)
								// ******************************* //
								if ($_idpedido) {
									$_pobject	= DataManager::newObjectOfClass('TPedido', $_idpedido);	
									$_status	= ($_pobject->__get('Activo')) ? 0 : 1;		
									$_pobject->__set('IDAdmin'			, $_SESSION["_usrid"]);
									$_pobject->__set('Administrador'	, $_SESSION["_usrname"]);
									$_pobject->__set('FechaExportado'	, date('Y-m-d H:i:s'));	
									$_pobject->__set('Activo'			, '0');                             
									$ID = DataManager::updateSimpleObject($_pobject);	
									//Actualizo fecha última compra de cliente neo-farma		
									/*if($_idcli != 0){	
										$ctaid		= 	DataManager::getCuenta('ctaid', 'ctaidcuenta', $_idcli, $_idempresa);	

										$ctaObject	= 	DataManager::newObjectOfClass('TCuenta', $ctaid);
										$ctaObject->__set('FechaCompra', date('Y-m-d H:m:s'));

									}	*/								
								}																			 

							} ?>
						</table>

						<?php
						//EXPORTA PEDIDOS A EXCEL
						echo "<script>";
						echo "dac_Exportar_Pedido('".$_idempresa."');";
						echo "</script>";
						?>

						<textarea id="inputTextToSave<?php echo $_idempresa; ?>" title="texto a grabar" hidden="hidden"><?php echo $_txtFile; ?> </textarea>
						<input id="inputFileNameToSaveAs<?php echo $_idempresa; ?>" value="PedidosWeb_<?php echo date("d-m-Y")."_".substr($_nombreemp, 0, 3); ?>" title="Archivo para guardar como..." hidden></input>
						
						</br>
						
						<!--button id="crearText<?php echo $_idempresa;?>" onclick="saveTextAsFile('<?php echo $_idempresa; ?>')" style="font-size: 14px; font-weight:bold; height: 40px; padding: 5px;">EXPORTAR TXT</button-->
						
						
						<?php
						//EXPORTA PEDIDOS A .TXT	AUTOMÁTICOS (No exporta bien si hay 2 empresas)	
						/*echo "<script>";
						echo "document.getElementById('crearText".$_idempresa."').click();";
						echo "</script>";*/
						?>  
						                    
						</br></br>
						<?php                                                        
					} //fin if pedido	
				} //fin for	             
			} else { echo "No se encuentran EMPRESAS ACTIVAS. Gracias."; } ?>
		</div> <!-- pedidos enviados -->	
		
	</main> <!-- fin cuerpo -->
            
	<footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->

</body>
</html>