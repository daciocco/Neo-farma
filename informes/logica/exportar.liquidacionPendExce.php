<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
}
//******************************************* 
//	1) UNIDADES PENDIENTES / EXCEDENTES
//		Mostrará el ESTADO con un número positivo o negativo
//		Si las unidades liquidadas son distintas a las unidades transfers pedidas
//		y el número de diferencia por cada artículo
//	2) ARTÍCULOS FALTANTES
//		Mostrará el ESTADO FALTANTES
//		Si se pidió un artículo y la droguería núnca lo liquidó?
//	3) PEDIDOS SIN LIQUIDAR
//		Mostrará el ESTADO SIN LIQUIDAR, si el número transfer 
//		no se encuentra liquidado en dicha droguería.
//
//******************************************* 
$fechaDesde	=	(isset($_POST['fechaDesde']))	? $_POST['fechaDesde']	: NULL;
$fechaHasta	= 	(isset($_POST['fechaHasta']))	? $_POST['fechaHasta'] 	: NULL;
 
if(empty($fechaDesde) || empty($fechaHasta)){ echo "Debe indicar las fechas a exportar"; exit; }
//******************************************* 
$fechaInicio		=	new DateTime(dac_invertirFecha($fechaDesde));
$fechaFin			=	new DateTime(dac_invertirFecha($fechaHasta));
//$fechaFin->modify("+1 day");
//*************************************************
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=LiquidacionPendExce-".date('d-m-y').".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>
	<?php
	$droguerias	= DataManager::getDrogueria();						
	if (count($droguerias)) { ?>
    	<table border="0" cellpadding="0" cellspacing="0">
        	<thead> 
				<tr>
                    <th scope="col" align="left" colspan="3">Exportado: <?php echo date("d-m-Y"); ?></th>	
                    <th scope="col" align="left" colspan="2">Desde: <?php echo $fechaInicio->format("d-m-Y"); ?></th>
                    <th scope="col" align="left" colspan="2">Hasta: <?php echo $fechaFin->format("d-m-Y"); ?></th>	
                </tr>
                <tr>
                    <th colspan="7">DIFERENCIA DE UNIDADES, ART&Iacute;CULOS FALTANTES y PEDIDOS SIN LIQUIDAR</th>
                </tr>
                <tr> <th colspan="7"></th> </tr>
                <tr>
                    <th align="left">Droguer&iacute;a</th>
                    <th align="left">Nombre</th>
                    <th align="left">Localidad</th>
                    <th align="left">Nro Transfer</th>
                    <th align="left">Art&iacute;culo</th>
                    <th align="left">Fecha</th>
                    <th align="left">Estado</th>
                </tr>
            </thead>
			<?php							
            foreach ($droguerias as $k => $drog) {
                $drog			=	$droguerias[$k];
                $drogId			=	$drog["drogtid"];
                $drogIdCliente 	= 	$drog["drogtcliid"];
				$drogIdEmpresa	= 	$drog["drogtidemp"];
				
				$drogNombre		= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $drogIdCliente, $drogIdEmpresa);		
				$idLoc			= DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $drogIdCliente, $drogIdEmpresa);
				$drogLocalidad	= DataManager::getLocalidad('locnombre', $idLoc);	
				
				//*************************************//  
				//Consulta liquidaciones desde - hasta //
				//*************************************//					
				$liquidaciones	=	DataManager::getDetalleLiquidaciones(0, $fechaInicio->format("Y-m-d"), $fechaFin->format("Y-m-d"), $drogIdCliente, 'TL');	
				if (count($liquidaciones)) { 					
					unset($arrayArtLiq);
					$arrayArtLiq = array(); 
					unset($arrayTransfer);
					$arrayTransfer = array(); 
					
					foreach ($liquidaciones as $k => $liq){
						$liq			=	$liquidaciones[$k];
						$liqId			=	$liq['liqid'];
						$liqTransfer	=	$liq['liqnrotransfer'];
						$liqFecha		=	$liq['liqfecha'];
						
						$arrayTransfer[]=	$liqTransfer;
						
						$liqcant		=	$liq['liqcant'];
						$liqEAN			=	str_replace("", "", $liq['liqean']);
						
						$ctrlCant		=	'';
						$estado			=	'';
						$cantPedidas	=	0;
						$cantUnidades	=	0;
						
						$bonifArtId		=	DataManager::getFieldArticulo('artcodbarra', $liqEAN);
						$artId			=	$bonifArtId[0]['artidart'];						
						if(!empty($artId)){	
							//DataManager::getDetallePedidoTransfer($liqTransfer);
							$_detallestransfer	= 
							DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $liqTransfer);
							if ($_detallestransfer) {			
								
								unset($arrayArtPedido);
								$arrayArtPedido = array(); 
								unset($arrayArtFaltantes);
								$arrayArtFaltantes = array(); 	
											
								foreach ($_detallestransfer as $j => $_dettrans){
									$_dettrans	=	$_detallestransfer[$j];
									$_ptiddrog	=	$_dettrans['ptiddrogueria'];
									$_ptidart	=	$_dettrans['ptidart'];
									
									$arrayArtPedido[] = $_ptidart;
									
									$_ptunidades=	$_dettrans['ptunidades'];									
									//Cantidad Pedida del TRANSFER Original
									if($_ptidart == $artId){ $cantPedidas 	+= 	$_ptunidades; }
								}
																
								//Si droguería del pedido == a la liquidación
								if($_ptiddrog == $drogIdCliente) {
									//**************************************//
									//	#1 UNIDADES PENDIENTE / EXCEDENTES	//
									//**************************************//
									//Si EAN no salió aún en la liquidación									
									$cantUnidades 	= 	$liqcant;							
									$liqTransfers	=	DataManager::getDetalleLiquidacionTransfer($liqId, $drogIdCliente, $liqTransfer, $liqEAN); 
									if($liqTransfers){
										foreach ($liqTransfers as $j => $liqTrans){
											$liqTrans	=	$liqTransfers[$j];
											$liqtCant	=	$liqTrans['liqcant'];												
											//Suma las unidades totales de dicho transfer en liquidaciones
											$cantUnidades	+=	$liqtCant;
										}								
									}
								
									// DIFERENCIA entre Unidades LIQUIDADAS y Unidades PEDIDAS
									if($cantUnidades != $cantPedidas){
										if(!in_array($artId, $arrayArtLiq)){
											$arrayArtLiq[]  = $artId;
											$estado			=	$cantUnidades - $cantPedidas;	
										}									
									}	
								} /*else {	 $estado	= "#ErrorDrog $_ptiddrog"; }*/
								if(!in_array($artId, $arrayArtPedido)){									
									$arrayArtFaltantes[]	= 	$artId;
								}
							} /*else { //Si transfer no existe! $estado	= "#ErrorNroTrans"; }*/
						} else { 
							$estado	= "#ErrorEAN";
						}
						
						if($estado){ ?>
                            <tr> 
                            	<th scope="row" align="left"><?php echo $drogIdCliente; ?></th>
                                <th scope="row" align="left"><?php echo $drogNombre; ?></th>
                                <th scope="row" align="left"><?php echo $drogLocalidad; ?></th>
                                <td scope="row" align="left"><?php echo $liqTransfer; ?></td>
                                <td scope="row" align="left"><?php echo $artId; ?></td>
                                <td scope="row" align="left"><?php echo dac_invertirFecha( $liqFecha ); ?></td>
                                <td scope="row" align="left"><?php echo $estado; ?></td>
                            </tr>
                            <?php 	
						}
						
						if(count($arrayArtFaltantes)){ 
							if(in_array($artId, $arrayArtFaltantes)){ ?>
                                <tr> 
                                	<th scope="row" align="left"><?php echo $drogIdCliente; ?></th>
                                    <th scope="row" align="left"><?php echo $drogNombre; ?></th>
                                    <th scope="row" align="left"><?php echo $drogLocalidad; ?></th>
                                    <td scope="row" align="left"><?php echo $liqTransfer; ?></td>
                                    <td scope="row" align="left"><?php echo $artId; ?></td>
                                    <td scope="row" align="left"><?php echo dac_invertirFecha( $liqFecha ); ?></td>
                                    <td scope="row" align="left"><?php echo "Faltante"; ?></td>
                                </tr>
                                <?php 
							}
						}
					}
				}
				
				unset($arrayTransfersSinLiquidar);
				$arrayTransfersSinLiquidar = array(); 
				
				$transfers	= DataManager::getTransfersPedido(0, $fechaInicio->format("Y-m-d H:m:s"), $fechaFin->format("Y-m-d H:m:s"), $drogIdCliente);
					//getTransfers2(0, $fechaInicio->format("Y-m-d H:m:s"), $fechaFin->format("Y-m-d H:m:s"), $drogIdCliente, NULL); 
				if($transfers){
					foreach ($transfers as $k => $transf){
						$transf		=	$transfers[$k];
						$nroPedido	= 	$transf['ptidpedido'];
						$fechaPedido= 	$transf['ptfechapedido'];
						if(!in_array($nroPedido, $arrayTransfer)){
							if(!in_array($nroPedido, $arrayTransfersSinLiquidar)){
								$arrayTransfersSinLiquidar[] = $nroPedido; ?>
								<tr> 
                                	<th scope="row" align="left"><?php echo $drogIdCliente; ?></th>
                                    <th scope="row" align="left"><?php echo $drogNombre; ?></th>
                                    <th scope="row" align="left"><?php echo $drogLocalidad; ?></th>
									<td scope="row" align="left"><?php echo $nroPedido; ?></td>
                                    <td scope="row" align="left"></td>
                                    <td scope="row" align="left"><?php echo $fechaPedido; ?></td>
                                    <td scope="row" align="left"><?php echo "Sin Liquidar"; ?></td>
								</tr> <?php 
							}
						}
					}
					
				}
            } ?>
		</table> <?php
	} ?>
</body>
</html>                
               
               