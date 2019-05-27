<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!= "M"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$_drogid	=	empty($_REQUEST['drogid'])	?	0	:	$_REQUEST['drogid'];
if(empty($_REQUEST['fecha_liquidacion'])){ 
	$_mes	=	date("m"); $_anio	=	date("Y");
} else { 
	list($_mes, $_anio) = explode('-', str_replace('/', '-', $_REQUEST['fecha_liquidacion']));
}

if($_drogid){
	$_importarXLS	=	sprintf( "<img id=\"importar\" src=\"/pedidos/images/icons/icono-importxls.png\" border=\"0\" align=\"absmiddle\"  title=\"Importar Liquidacion\"/>");	
	$_exportarXLS	= sprintf( "<a href=\"logica/exportar.liquidacion.php?mes=%d&anio=%d&drogid=%d&backURL=%s\" title=\"Exportar Liquidacion\">%s</a>", $_mes, $_anio, $_drogid, $_SERVER['PHP_SELF'], "<img class=\"icon-xls-export\"/>");
	$_emitirNC	=	sprintf( "<img id=\"emitirnc\" title=\"Emitir NC\" src=\"/pedidos/images/icons/icono-emitirnc.png\" border=\"0\" align=\"absmiddle\" />");	
} ?>

<div class="box_down">	
	<table id="tabla_liquidacion" name="tabla_liquidacion" class="tabla_liquidacion" border="0">
		<thead>
			<tr>
				<th colspan="8" align="left">                 
					<form id="fm_droguerias" action="#" method="post" enctype="multipart/form-data"> 														
						<label for="drogid">Droguer&iacute;a: </label> <?php
						$_droguerias	= DataManager::getDrogueria('');						
						if (count($_droguerias)) { ?>
							<select name='drogid' id='drogid' style='width:500px; font-weight:bold;	background-color:transparent; border:none;' onChange="javascript:document.getElementById('fm_droguerias').submit();">
								<option value="0" selected>Seleccione Droguer&iacute;a...</option><?php
								foreach ($_droguerias as $k => $_drogueria) {
									$_drogueria		=	$_droguerias[$k];
									$_Did			=	$_drogueria["drogtid"];
									$_DidEmp		=	$_drogueria["drogtidemp"];
									$_Didcliente 	= 	$_drogueria["drogtcliid"];
									$_Dnombre	 	= 	$_drogueria["drogtnombre"];
									$_Dlocalidad	=	$_drogueria["drogtlocalidad"]; 

									if($_drogid == $_Didcliente){ 
										$_drogidemp	=	$_DidEmp;
										?><option value="<?php echo $_Didcliente; ?>" selected><?php echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; ?></option> <?php	
									} else { ?>
										<option value="<?php echo $_Didcliente; ?>"><?php echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; ?></option> <?php
									}
								} ?>
							</select> <?php
						} ?>
				</th>
				<th colspan="6" align="right">
					<?php echo infoFecha(); ?>
				</th>
			</tr>
			<tr>
				<th colspan="3" align="left">													
						<label for="fecha_liquidacion" >Fecha liquidacion: </label>
						<?php echo listar_mes_anio('fecha_liquidacion', $_anio, $_mes, 'javascript:dac_VerMesAnioDrog()', 'width:80px; font-weight:bold;	background-color:transparent; border:none;'); ?>
						<input hidden id="vermesanio" name="vermesanio" type="text" value="<?php echo $_mes." - ".$_anio; ?>" />
						<input hidden id="vermesaniodrog" name="vermesaniodrog" type="submit"/>
				</th>
				<th colspan="8" align="left">
						<input type="file" name="file" id="file">
						<?php echo $_importarXLS; ?><?php //echo $_guardar; ?><?php echo $_emitirNC; ?>
						<?php //echo $_boton_print; ?><?php echo $_exportarXLS; ?>
					</form> 
				</th>
				<th colspan="3"></th>
			</tr> 

			<tr height="60px;">  <!-- Títulos de las Columnas -->
				<th align="center">Transfer</th>
				<th align="center">Fecha Factura</th>
				<th align="center">Nro. Factura</th>
				<th align="center">EAN</th> 
				<th align="center">Art&iacute;culo</th> 
				<th align="center">Cantidad</th>    
				<th align="center">PSL Unit.</th>
				<th align="center">Desc. PSL</th>
				<th align="center">Importe NC</th>
				<th align="center">Cantidad</th> 
				<th align="center">PSL Unit.</th> 
				<th align="center">Desc. PSL</th>                 
				<th align="center">Importe NC</th>   
				<th align="center">Estado</th>   
			</tr>
		</thead>

		<tbody id="lista_liquidacion">
			<form id="fm_liquidacion_edit" name="fm_liquidacion_edit" method="POST"  enctype="multipart/form-data">  
				<input id="mes_liquidacion" name="mes_liquidacion" type="text" value="<?php echo $_mes; ?>" hidden/>
				<input id="anio_liquidacion" name="anio_liquidacion" type="text" value="<?php echo $_anio; ?>" hidden/>
				<input id="drogid_liquidacion" name="drogid_liquidacion" type="text" value="<?php echo $_drogid; ?>" hidden/>
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
						$_liqFechaFact	=	dac_invertirFecha( $_liq['liqfechafact'] );
						$_liqNroFact	=	$_liq['liqnrofact'];

						$_liqean		=	str_replace(" ", "", $_liq['liqean']);
						if(!empty($_liqean)){						
							$_articulo		=	DataManager::getFieldArticulo("artcodbarra", $_liqean) ;
							$_nombreart		=	$_articulo['0']['artnombre'];
							$_idart			=	$_articulo['0']['artidart'];
						}

						$_liqcant		=	$_liq['liqcant'];
						$_liqunit		=	$_liq['liqunitario'];
						$_liqdesc		=	$_liq['liqdescuento'];
						$_liqimportenc	=	$_liq['liqimportenc'];

						$_liqactiva		=	$_liq['liqactiva'];

						$_TotalNC		+=	$_liqimportenc;
						((($k % 2) != 0)? $clase="par" : $clase="impar");

						// CONTROLA las Condiciones de las Liquidaciones y Notas de Crédito//
						include($_SERVER['DOCUMENT_ROOT']."/pedidos/transfer/gestion/liquidacion/logica/controles.liquidacion.php");
						/*****************/
						?> 

						<tr id="lista_liquidacion<?php echo $k;?>" class="<?php echo $clase;?>">      
							<input id="idliquid" name="idliquid[]" type="text" value="<?php echo $_liqID;?>" hidden/> 
							<input id="activa" name="activa[]" type="text" value="<?php echo $_liqactiva;?>" hidden/> 
							<input id="fecha" name="fecha[]" type="text" value="<?php echo $_liqFecha;?>" hidden/> 
							<td><input id="transfer" name="transfer[]" type="text" size="7px" value="<?php echo $_liqTransfer;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="fechafact" name="fechafact[]" type="text" size="8px" value="<?php echo $_liqFechaFact;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="nrofact" name="nrofact[]" type="text" size="8px" value="<?php echo $_liqNroFact;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="ean" name="ean[]" type="text"  size="12px" value="<?php echo $_liqean;?>" style="border:none; text-align:center;" readonly/></td>
							<td><?php echo $_idart." - ".$_nombreart; ?>
								<input id="idart" name="idart[]" type="text" value="<?php echo $_idart;?>" readonly hidden/>
							</td>
							<td><input id="cant" name="cant[]" type="text" size="5px" value="<?php echo $_liqcant;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="unitario" name="unitario[]" type="text" size="8px" value="<?php echo $_liqunit;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="desc" name="desc[]" type="text" size="8px" value="<?php echo $_liqdesc;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="importe" name="importe[]" type="text" size="8px" value="<?php echo $_liqimportenc;?>" style="border:none; text-align:center;" readonly/></td>
							<td width="150px" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlCant;?></td>
							<td width="150px" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlPSLUnit;?></td>
							<td width="150px" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlDescPSL;?></td>
							<td width="150px" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;"><?php echo $_CtrlImpNT;?></td>
							<td align="center"><input id="estado" name="estado[]" type="text" size="8px" value="<?php echo $_Estado; ?>" style="border:none; text-align:center;" readonly/></td>
						</tr> <?php
					} //FIN del FOR 
				} else { ?>
					<tr class="impar"><td colspan="14" align="center">No hay liquidaciones cargadas</td></tr><?php           
				}?>
			</form>                       
		</tbody>

		<tfoot>
			<tr>
				<th colspan="7" height="30px" style="border:none; font-weight:bold;"></th>
				<th colspan="1" height="30px" style="border:none; font-weight:bold;">Total</th>
				<th colspan="1" height="30px" style="border:none; font-weight:bold;"><?php echo $_TotalNC; ?></th>
				<th colspan="3" height="30px" style="border:none; font-weight:bold;"></th>
				<th colspan="1" height="30px" style="border:none; font-weight:bold;"><?php echo $_CtrlTotalNC; ?></th>
				<th colspan="1" height="30px" style="border:none; font-weight:bold;"></th>
			</tr>
		</tfoot>
	</table>  
	<div hidden="hidden"><button id="btnExport" hidden="hidden"></button></div> 
</div>    
   
<hr>
    
    
<div class="box_body2"> <!-- datos --> 
    <div class="barra">
        <div class="bloque_5">
            <h1>Tienen ARTÍCULOS PENDIENTES</h1>                	
        </div>
        <hr>
    </div> <!-- Fin barra -->
    
    <div class="lista_super">
        <table id="tblliquidaciones">
            <thead>
                <tr>
                    <td scope="col" width="20%" height="18">Fecha</td>
                    <td scope="col" width="20%">Transfer</td>
                    <td scope="col" width="50%">Cliente</td>
                    <td scope="colgroup" colspan="2" width="10%" align="center">Acciones</td>
                </tr>
            </thead> <?php
			$_fila				=	0;
            $_liqTransfer_ant 	=	0;
            $_no_existe			=	0;	
			//Recorro cada número de transfer de la liquidación
            $_liquidaciones	=	DataManager::getDetalleLiquidacion($_mes, $_anio, $_drogid, 'TL');
            if ($_liquidaciones) {
                foreach ($_liquidaciones as $k => $_liq){
					$_liq			=	$_liquidaciones[$k];
					$_liqTransfer	=	$_liq['liqnrotransfer'];	
					
					//Si el transfer se repite más de una vez, no hago el control
					if ($_liqTransfer != $_liqTransfer_ant){					
						unset($_idartArray);
						//Recorro cualquier liquidación de la droguería que pueda tener con el número transter
						$_detalles_trans_liq	= DataManager::getDetalleLiquidacionTransfer(NULL, $_drogid, $_liqTransfer, NULL);
						if ($_detalles_trans_liq) { 
							foreach ($_detalles_trans_liq as $i => $_det_tl){	
								$_det_tl 	= 	$_detalles_trans_liq[$i];	
									$_liqean		=	str_replace("", "", $_det_tl['liqean']);
									$_articulo		=	DataManager::getFieldArticulo("artcodbarra", $_liqean) ;		
								$_idart			=	$_articulo['0']['artidart'];
							  
								//Cargo el array de artículos liquidados
								$_idartArray[]	=	$_idart;
							}
						}
						
						//Recorro los artículos del transfer
						$_detalles	= DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_liqTransfer); //DataManager::getDetallePedidoTransfer($_liqTransfer);
						if ($_detalles) { 
							foreach ($_detalles as $j => $_detalle){	
								$_detalle 	= 	$_detalles[$j];	
								$_drogtransf=	$_detalle["ptiddrogueria"];	
								$_fecha 	=	$_detalle["ptfechapedido"];				
								$_nombre	= 	$_detalle["ptclirs"];
								$_det_idart	=	$_detalle['ptidart'];
								
								//si la droguería del transfer no coincide con la de la liquidación, no la agrega.
								if($_drogid == $_drogtransf) {
									//Si algún artículo del transfer no está liquidado, lo agrega a la tabla
									if (!in_array($_det_idart, $_idartArray) && $_no_existe	== 0) {							
										$_no_existe		=	1;
										
										$_fila = $_fila + 1;
										//el artículono NO existe en el array SEGURO SALGA TANTAS VECES COMO ARTÍCULOS DE TRANSFER NO SALGAN EN EL ARRAY						
										$_conciliar_liq	=	sprintf( "<a id=\"conciliar\" href=\"/pedidos/transfer/gestion/liquidacion/detalle_liq.php?idpedido=%s\" target=\"_blank\" title=\"Conciliar Liquidaci&oacute;n\" >%s</a>", $_liqTransfer, "<img src=\"/pedidos/images/icons/icono-conciliar.png\" border=\"0\" />");
										$_detalle	= 	sprintf( "<a href=\"../../detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_liqTransfer, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" border=\"0\" align=\"absmiddle\" />");	
										echo sprintf("<tr class=\"%s\">", ((($_fila % 2) == 0)? "par" : "impar"));
										echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_fecha, $_liqTransfer, $_nombre, $_conciliar_liq, $_detalle);
										echo sprintf("</tr>");
									}  
								}
							}
						}
					}
					$_no_existe			=	0;														
                    $_liqTransfer_ant	=	$_liqTransfer;
                }					
            } else { ?>
                <tr>
                    <td scope="colgroup" colspan="5" height="25" align="center">No hay pedidos Transfer SIN entregar</td>
                </tr> <?php
            } ?>
        </table>
    </div> <!-- Fin listar -->	  
	
   
    <div class="barra">
        <div class="bloque_5">
            <h1>Tienen ARTÍCULOS NO PEDIDOS</h1>                	
        </div>
        <hr>
    </div> <!-- Fin barra -->
    
    <div class="lista_super">
        <table id="tblliquidaciones" width="90%" border="0">
            <thead>
                <tr>
                    <td scope="col" width="20%" height="18">Fecha</td>
                    <td scope="col" width="20%">Transfer</td>
                    <td scope="col" width="50%">Cliente</td>
                    <td scope="colgroup" width="10%" colspan="2" align="center">Acciones</td>
                </tr>
            </thead> <?php			
            
            //Busco transfers que tengan artículos liquidaros y NO PEDIDOS ¿solo en éste mes?
            $_liquidaciones	=	DataManager::getDetalleLiquidacion($_mes, $_anio, $_drogid, 'TL');
            if ($_liquidaciones) {		
                //recorro todos los artículos que haya en la liquidación
                $_nropedido_ant = 0;
                foreach ($_liquidaciones as $k => $_liq){
                    $_liq			=	$_liquidaciones[$k];
                    $_liqTransfer	=	$_liq['liqnrotransfer'];
                        $_liqean		=	str_replace("", "", $_liq['liqean']);
                        $_articulo		=	DataManager::getFieldArticulo("artcodbarra", $_liqean) ;		
                    $_idart			=	$_articulo['0']['artidart'];
                                                
                    //pregunto si el artículo de ésta liquidación, existe en el transfer original						
                    //Busco el artículo del nrotransfer en las liquidaciones
                    $_detalles	= DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_liqTransfer); //DataManager::getDetallePedidoTransfer($_liqTransfer);
                    if ($_detalles) { 
                        unset($_idartArray);
                        foreach ($_detalles as $j => $_detalle){	
                            $_detalle 		= 	$_detalles[$j];	
							$_drogtransf	=	$_detalle["ptiddrogueria"];		
                            $_fecha 		=	$_detalle["ptfechapedido"];
                            $_nropedido		= 	$_detalle["ptidpedido"];			
                            $_nombre		= 	$_detalle["ptclirs"];
                            $_det_idart		=	$_detalle['ptidart'];
                            
                            $_idartArray[]	=	$_det_idart;
                        }
						
						//si el transfer que liquidan no corresponde a la droguería del transfer original, no lo cuenta
						if($_drogtransf == $_drogid){
							if (!in_array($_idart, $_idartArray) && ($_nropedido_ant != $_nropedido)) {
								
								$_nropedido_ant = $_nropedido;
								
								$_fila = $_fila + 1;
								
								//El Art sale en una liquidacio, pero NO EXISTE PEDIDO en el transfer original
								$_conciliar_liq	=	sprintf( "<a id=\"conciliar\" href=\"/pedidos/transfer/gestion/liquidacion/detalle_liq.php?idpedido=%s\" target=\"_blank\" title=\"Conciliar Liquidaci&oacute;n\" >%s</a>", $_nropedido, "<img src=\"/pedidos/images/icons/icono-conciliar.png\" border=\"0\" />");
								
								$_detalle	= 	sprintf( "<a href=\"../../detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" border=\"0\" align=\"absmiddle\" />");	
								echo sprintf("<tr class=\"%s\">", ((($_fila % 2) == 0)? "par" : "impar"));
								echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_fecha, $_nropedido, $_nombre, $_conciliar_liq, $_detalle);
								echo sprintf("</tr>");
							}
						}
                    } 
                }					
            } else { ?>
                <tr>
                    <td scope="colgroup" colspan="4" height="25" align="center">No hay pedidos Transfer con artículos NO PEDIDOS</td>
                </tr> <?php
            } ?>
        </table>
    </div> <!-- Fin listar -->	       
</div> <!-- Fin datos -->

<div class="box_body2">
    <div class="barra">
        <div class="bloque_5">
            <h1>Tienen UNIDADES PENDIENTES</h1>                	
        </div>
        <div class="bloque_5">
        	<?php $exportXLSUnidsPend	= sprintf( "<a href=\"logica/exportar.unidades_pendientes.php?drogid=%d&backURL=%s\" title=\"Exportar PENDIENTES\">%s</a>", $_drogid, $_SERVER['PHP_SELF'], "<img class=\"icon-xls-export\"/>"); 
			echo $exportXLSUnidsPend;?>	
        </div>
        <hr>
    </div> <!-- Fin barra -->
    
    <div class="lista_super">
        <table id="tblliquidaciones">
            <thead>
                <tr>
                    <td scope="col" width="20%" height="18">Fecha</td>
                    <td scope="col" width="20%">Transfer</td>
                    <td scope="col" width="50%">Cliente</td>
                    <td scope="colgroup" width="10%" colspan="2" align="center">Acciones</td>
                </tr>
            </thead> <?php		
            $_transfers_liquidados	= DataManager::getTransfersLiquidados(NULL, 'LP', $_drogid); 
            if ($_transfers_liquidados) { 
                for( $k=0; $k < count($_transfers_liquidados); $k++ ){
					$_detalle	= 	$_transfers_liquidados[$k];					
					$_fecha 	=	$_detalle["ptfechapedido"];
					$_nropedido	= 	$_detalle["ptidpedido"];	
					$_nombre	= 	$_detalle["ptclirs"];
					
					$_conciliar_liq	=	sprintf( "<a id=\"conciliar\" href=\"/pedidos/transfer/gestion/liquidacion/detalle_liq.php?idpedido=%s\" target=\"_blank\" title=\"Conciliar Liquidaci&oacute;n\" >%s</a>", $_nropedido, "<img src=\"/pedidos/images/icons/icono-conciliar.png\" border=\"0\" />");
					$_detalle	= 	sprintf( "<a href=\"../../detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" border=\"0\" align=\"absmiddle\" />");					
					$_status	= "<img src=\"/pedidos/images/icons/icono-LP-LT.png\" border=\"0\" align=\"absmiddle\" title=\"Pasar LP a LT\"/>";
					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?nropedido=%d&status='LP'&backURL=%s\" title=\"Pasar a LT\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], $_status);
					
                    echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
                    echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_fecha, $_nropedido, $_nombre, $_borrar, $_conciliar_liq, $_detalle);
                    echo sprintf("</tr>");
                }
            } else { ?>
                <tr>
                    <td scope="colgroup" colspan="4" height="25" align="center">No hay Transfer con UNIDADES PENDIENTES</td>
                </tr> <?php
            } ?>
        </table>
    </div> <!-- Fin listar -->	       
	
   
    <div class="barra">
        <div class="bloque_5">
            <h1>Tienen UNIDADES EXCEDENTES</h1>                	
        </div>
        <div class="bloque_5">
        	<?php $exportXLSUnidsExced	= sprintf( "<a href=\"logica/exportar.unidades_excedentes.php?drogid=%d&backURL=%s\" title=\"Exportar EXCEDENTES\">%s</a>", $_drogid, $_SERVER['PHP_SELF'], "<img class=\"icon-xls-export\"/>"); 
			echo $exportXLSUnidsExced;?>	
        </div>
        <hr>
    </div> <!-- Fin barra -->
    
    <div class="lista_super">
        <table id="tblliquidaciones" width="90%" border="0">
            <thead>
                <tr>
                    <td scope="col" width="20%" height="18">Fecha</td>
                    <td scope="col" width="20%">Transfer</td>
                    <td scope="col" width="50%">Cliente</td>
                    <td scope="colgroup" width="10%" colspan="3" align="center">Acciones</td>
                </tr>
            </thead>	 <?php	
			
				
            $_transfers_liquidados	= DataManager::getTransfersLiquidados(NULL, 'LE', $_drogid); 
            if ($_transfers_liquidados) {
                for( $k=0; $k < count($_transfers_liquidados); $k++ ){
					$_detalle 	= 	$_transfers_liquidados[$k];						
					$_fecha 	=	$_detalle["ptfechapedido"];
					$_nropedido	= 	$_detalle["ptidpedido"];	
					$_nombre	= 	$_detalle["ptclirs"];
					
					$_conciliar_liq	=	sprintf( "<a id=\"conciliar\" href=\"/pedidos/transfer/gestion/liquidacion/detalle_liq.php?idpedido=%s\" target=\"_blank\" title=\"Conciliar Liquidaci&oacute;n\" >%s</a>", $_nropedido, "<img src=\"/pedidos/images/icons/icono-conciliar.png\" border=\"0\" />");
					$_detalle	= 	sprintf( "<a href=\"../../detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" border=\"0\" align=\"absmiddle\" />");	
					
					$_status	= "<img src=\"/pedidos/images/icons/icono-LE-LT.png\" border=\"0\" align=\"absmiddle\" title=\"Pasar LE a LT\"/>";
					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?nropedido=%d&status='LE'&backURL=%s\" title=\"Pasar a LT\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], $_status);					
                    echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
                    echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_fecha, $_nropedido, $_nombre, $_borrar, $_conciliar_liq, $_detalle);
                    echo sprintf("</tr>");
                }
            } else { ?>
                <tr>
                    <td scope="colgroup" colspan="5" height="25" align="center">No hay Transfer CON UNIDADES EXCEDENTES</td>
                </tr> <?php
            } ?>
        </table>
    </div> <!-- Fin listar -->	       
</div> <!-- Fin boxdatosuper -->


<!-- Scripts para IMPORTAR ARCHIVO -->
<script type="text/javascript" src="/pedidos/transfer/gestion/liquidacion/logica/jquery/jquery.script.file.js"></script>
<!--script type="text/javascript" src="logica/jquery/jquery.process.liquidacion.js"></script-->
<script type="text/javascript" src="logica/jquery/jquery.process.emitirnc.js"></script>
<script type="text/javascript">
	function dac_VerMesAnioDrog(){ document.getElementById("vermesaniodrog").click();}
</script>