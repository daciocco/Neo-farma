<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
}

 $_mes		= empty($_REQUEST['mes']) 	? 0 : $_REQUEST['mes'];
 $_anio		= empty($_REQUEST['anio']) 	? 0 : $_REQUEST['anio'];
 $_drogid	= empty($_REQUEST['drogid'])? 0 : $_REQUEST['drogid'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/transfer/gestion/liquidacion/': $_REQUEST['backURL'];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Liquidacion-".$_mes."-".$_anio.".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos (Liquidacion) .::</TITLE>
<head></head>
<body>

<div id="cuadro-liquidacion">
	<div id="muestra_liquidacion">     
   	<table id="tabla_liquidacion" name="tabla_liquidacion" class="tabla_liquidacion" cellpadding="0" cellspacing="0" border="0">
       	<thead>
        	<tr>
               	<th colspan="15" align="left"> 
                	Droguer&iacute;a: <?php
					$_droguerias	= DataManager::getDrogueria('');						
					if (count($_droguerias)) { 
						foreach ($_droguerias as $k => $_drogueria) {
							$_drogueria		=	$_droguerias[$k];
							$_Did			=	$_drogueria["drogtid"];
							$_Didcliente 	= 	$_drogueria["drogtcliid"];
							$_DidEmp		=	$_drogueria["drogtidemp"];
							$_Dnombre	 	= 	$_drogueria["drogtnombre"];
							$_Dlocalidad	=	$_drogueria["drogtlocalidad"]; 
																			
                           	if($_drogid == $_Didcliente){	
								$_drogidemp	=	$_DidEmp; 		
								echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; 
                            }
                        }
					} ?>
                </th>
            </tr>
            <tr>
               	<th colspan="15" align="left">													
                	Fecha liquidacion: <?php echo $_mes." - ".$_anio; ?>                       
                </th>
            </tr> 
                                                      
            <tr height="60px;">  <!-- Títulos de las Columnas -->
            	<th align="center">Transfer</th>
                <th align="center">Demora</th>
                <th align="center">Fecha Factura</th>
                <!--th align="center">Nro. Factura</th-->
                <th align="center">EAN</th> 
                <th align="center">Art&iacute;culo</th> 
                <th align="center">Detalle</th> 
                <th align="center">Cantidad</th>    
                <th align="center">P.S.L. Unit.</th>
                <th align="center">Desc. P.S.L</th>
                <th align="center">Importe NC</th>
                <th align="center">Cantidad</th> 
                <th align="center">PSL Unit.</th> 
                <th align="center">Desc. PSL</th>                 
                <th align="center">Importe NC</th>   
                <th align="center">Estado</th>   
			</tr>
		</thead>
                 
        <tbody id="lista_liquidacion">
        	<?php 
			//******************************************//  
			//Consulta liquidacion del mes actual y su drogueria//
			//******************************************//				
				$_liquidaciones	=	DataManager::getDetalleLiquidacion($_mes, $_anio, $_drogid, 'TL');
				if ($_liquidaciones) {
					foreach ($_liquidaciones as $k => $_liq){
						$_liq			=	$_liquidaciones[$k];
						$_liqID			=	$_liq['liqid'];
						$_liqFecha		=	$_liq['liqfecha'];
						$_liqTransfer	=	$_liq['liqnrotransfer'];
						
						$_transfer		=	DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_liqTransfer); //DataManager::getDetallePedidoTransfer($_liqTransfer);
						if($_transfer){
							for( $j=0; $j < count($_transfer); $j++ ){		
								$_detalle 		= 	$_transfer[$j];	
								$fecha_transfer = 	$_detalle["ptfechapedido"];
							}
						}
						
						//Si devuelve cero, es que transcurrieron menos de 24 horas
						$_liqDemora		=	dac_dias_transcurridos($fecha_transfer, $_liq['liqfechafact']);
						
						$_liqFechaFact	=	dac_invertirFecha( $_liq['liqfechafact'] );
						//$_liqNroFact	=	$_liq['liqnrofact'];
						$_liqean		=	str_replace(" ", "", $_liq['liqean']);
						$_articulo		=	DataManager::getFieldArticulo("artcodbarra", $_liqean);
						$_nombreart		=	$_articulo['0']['artnombre'];
						$_idart			=	$_articulo['0']['artidart'];	
						$_liqcant		=	$_liq['liqcant'];
						$_liqunit		=	$_liq['liqunitario'];
						$_liqdesc		=	$_liq['liqdescuento'];
						$_liqimportenc	=	$_liq['liqimportenc'];
						$_TotalNC		+=	$_liqimportenc;
						
						$_liqactiva		=	$_liq['liqactiva'];
						((($k % 2) != 0)? $clase="par" : $clase="impar");
						
						// CONTROLA las Condiciones de las Liquidaciones y Notas de Crédito//
						include($_SERVER['DOCUMENT_ROOT']."/pedidos/transfer/gestion/liquidacion/logica/controles.liquidacion.php");
						/*****************/
																		
						?>                        
                    	<tr id="lista_liquidacion<?php echo $k;?>" class="<?php echo $clase;?>">      
                        	<td><?php echo $_liqTransfer;?></td>
                            <td><?php echo $_liqDemora;?></td>
                    		<td><?php echo $_liqFechaFact;?></td>
                            <!--td><?php echo $_liqNroFact;?></td-->
                            <td style="mso-style-parent:style0; mso-number-format:\@"><?php echo $_liqean;?></td>
                            <td><?php echo $_idart;?></td>
                            <td><?php echo $_nombreart;?></td>
                            <td><?php echo $_liqcant;?></td>
                            <td><?php echo $_liqunit;?></td>
                            <td><?php echo $_liqdesc;?></td>
                            <td><?php echo $_liqimportenc;?></td>
                            <td width="120" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlCant;?></td>
                            <td width="120" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlPSLUnit;?></td>
                            <td width="120" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlDescPSL;?></td>
                            <td width="120" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlImpNT;?></td>
                            <td align="center"><?php echo $_Estado; ?></td>
						</tr> <?php
					} //FIN del FOR 
				} else { ?>
					<tr class="impar"><td colspan="9" align="center">No hay liquidaciones cargadas</td></tr><?php           
				}?>
            </form>                       
		</tbody>
                
        <tfoot>
        	<tr>
            	<th colspan="7" height="30px" style="border:none; font-weight:bold;"></th>
                <th colspan="2" height="30px" style="border:none; font-weight:bold;">Total</th>
                <th height="30px" style="border:none; font-weight:bold;"><?php echo $_TotalNC; ?></th>
                <th colspan="3" height="30px" style="border:none; font-weight:bold;"></th>
                <th height="30px" style="border:none; font-weight:bold;"><?php echo $_CtrlTotalNC; ?></th>                
                <th height="30px" style="border:none; font-weight:bold;"></th>
            </tr>
        </tfoot>
	</table>  
    <div hidden="hidden"><button id="btnExport" hidden="hidden"></button></div>
                  
	</div> <!-- FIN muestra_bonif --> 
</div> <!-- FIN liquidacion -->

</body>
</html> 
          
               
               