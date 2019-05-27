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
	$_importarXLS	=	sprintf( "<img id=\"importar\" src=\"/pedidos/images/icons/icono-importxls.png\" border=\"0\" align=\"absmiddle\"  title=\"Importar Liquidacion\" style=\"float:right;\" onclick=\"javascript:dac_sendForm(fm_liquidacion_edit, '/pedidos/transfer/gestion/liquidaciondrog/logica/importar_liquidacion.php')\">");	
	$_exportarXLS	= sprintf( "<a href=\"logica/exportar.liquidacion.php?mes=%d&anio=%d&drogid=%d&backURL=%s\" title=\"Exportar Liquidacion\">%s</a>", $_mes, $_anio, $_drogid, $_SERVER['PHP_SELF'], "<img class=\"icon-xls-export\"/>");	
	$_emitirNC	=	sprintf( "<img id=\"emitirnc\" src=\"/pedidos/images/icons/icono-emitirnc.png\" border=\"0\" align=\"absmiddle\"  title=\"Emitir NC\" onclick=\"javascript:dac_sendForm(fm_liquidacion_edit, '/pedidos/transfer/gestion/liquidaciondrog/logica/update.liquidacion.php')\">");	
} ?>

<form id="fm_liquidacion_edit" method="POST">
	<div class="bloque_3">     
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
    
    <fieldset>    	        
    	<div class="bloque_3">  												
            <label for="drogid">Droguer&iacute;a: </label> <?php
            $_droguerias	= DataManager::getDrogueria('');
            if (count($_droguerias)) { ?>
                <select name='drogid' id='drogid' onchange="javascript:dac_chageDrogueria();">
                    <option value="0" selected>Seleccione Droguer&iacute;a...</option><?php
                    foreach ($_droguerias as $k => $_drogueria) {
                        $_drogueria		=	$_droguerias[$k];
                        $_Did			=	$_drogueria["drogtid"];
                        $_Didcliente 	= 	$_drogueria["drogtcliid"];
						$_DidEmp		=	$_drogueria["drogtidemp"];
                        $_Dnombre	 	= 	$_drogueria["drogtnombre"];
                        $_Dlocalidad	=	$_drogueria["drogtlocalidad"]; 
                            
                        if($_drogid == $_Didcliente){ 
							$_drogidemp	=	$_DidEmp; ?>			
                            <option value="<?php echo $_Didcliente; ?>" selected><?php echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; ?></option> <?php	} else { ?>
                            <option value="<?php echo $_Didcliente; ?>"><?php echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; ?></option> <?php			}
                    } ?>
                </select> <?php
            } ?>
        </div> 
        
        <div class="bloque_1">  												
            <label for="fecha_liquidacion" >Fecha liquidaci&oacute;n: </label>                        
            <?php echo listar_mes_anio('fecha_liquidacion', $_anio, $_mes, 'dac_chageDrogueria()', 'width:190px; font-weight:bold;	background-color:transparent;'); ?>
        </div>
        <div class="bloque_1"> </div>
        <div class="bloque_3">  
        	<?php echo $_importarXLS; ?>          	
        	<input type="file" name="file" id="file">
        </div>
        <div class="bloque_3"> 
        	<?php echo $_emitirNC; ?><?php echo $_exportarXLS; ?> 
        </div>
        
        <table id="tabla_liquidacion" name="tabla_liquidacion" class="tabla_liquidacion" width="100%" border="0">
            <thead>                                                  
                <tr height="60px;">
                    <th align="center">Transfer</th>
                    <th align="center">Fecha Factura</th>
                    <th align="center">Nro. Factura</th>
                    <th align="center">EAN</th> 
                    <th align="center">Art&iacute;culo</th> 
                    <th align="center">Cantidad</th>    
                    <th align="center">PSL Unit.</th>
                    <th align="center">Desc. PSL</th>
                    <th align="center">Importe NC</th>
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
                $_liquidaciones	=	DataManager::getDetalleLiquidacion($_mes, $_anio, $_drogid, 'TD');
                if ($_liquidaciones) {
                    foreach ($_liquidaciones as $k => $_liq) {
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
                        include($_SERVER['DOCUMENT_ROOT']."/pedidos/transfer/gestion/liquidaciondrog/logica/controles.liquidacion.php");
                        /*****************/
                        ?> 
                                               
                        <tr id="lista_liquidacion<?php echo $k;?>" class="<?php echo $clase;?>" align="center">      
                            <input id="idliquid" name="idliquid[]" type="text" value="<?php echo $_liqID;?>" hidden/> 
                            <input id="activa" name="activa[]" type="text" value="<?php echo $_liqactiva;?>" hidden/> 
                            <input id="fecha" name="fecha[]" type="text" value="<?php echo $_liqFecha;?>" hidden/> 
                            <td><?php echo $_liqTransfer;?>
                            	<input id="transfer" name="transfer[]" type="text" size="7px" value="<?php echo $_liqTransfer;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td><?php echo $_liqFechaFact;?>
                            	<input id="fechafact" name="fechafact[]" type="text" size="8px" value="<?php echo $_liqFechaFact;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td><?php echo $_liqNroFact;?>
                            	<input id="nrofact" name="nrofact[]" type="text" size="8px" value="<?php echo $_liqNroFact;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td><?php echo $_liqean;?>
                            	<input id="ean" name="ean[]" type="text"  size="12px" value="<?php echo $_liqean;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td><?php echo $_idart." - ".$_nombreart; ?>
                                <input id="idart" name="idart[]" type="text" value="<?php echo $_idart;?>" readonly hidden/>
                            </td>
                            <td><?php echo $_liqcant;?>
                            	<input id="cant" name="cant[]" type="text" size="5px" value="<?php echo $_liqcant;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td><?php echo $_liqunit;?>
                            	<input id="unitario" name="unitario[]" type="text" size="8px" value="<?php echo $_liqunit;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td><?php echo $_liqdesc;?>
                            	<input id="desc" name="desc[]" type="text" size="8px" value="<?php echo $_liqdesc;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td><?php echo $_liqimportenc;?>
                            	<input id="importe" name="importe[]" type="text" size="8px" value="<?php echo $_liqimportenc;?>" style="border:none; text-align:center;" readonly hidden/></td>
                            <td width="150px" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;">
								<?php echo $_CtrlPSLUnit;?></td>
                            <td width="150px" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;">
								<?php echo $_CtrlDescPSL;?></td>
                            <td width="150px" align="center" style="border-bottom:1px #CCCCCC solid; color:#ba140c; font-weight:bold;">
								<?php echo $_CtrlImpNT;?></td>
                            <td align="center">
                            	<?php echo $_Estado; ?>
                            	<input id="estado" name="estado[]" type="text" size="8px" value="<?php echo $_Estado; ?>" style="border:none; text-align:center;" readonly hidden/></td>
                        </tr> <?php
                    } //FIN del FOR 
                } else { ?>
                    <tr class="impar"><td colspan="13" align="center">No hay liquidaciones cargadas</td></tr><?php           
                }?>                  
            </tbody>
                    
            <tfoot>
                <tr>
                    <th colspan="7" height="30px" style="border:none; font-weight:bold;"></th>
                    <th colspan="1" height="30px" style="border:none; font-weight:bold;">Total</th>
                    <th colspan="1" height="30px" style="border:none; font-weight:bold;"><?php echo $_TotalNC; ?></th>
                    <th colspan="2" height="30px" style="border:none; font-weight:bold;"></th>
                    <th colspan="1" height="30px" style="border:none; font-weight:bold;"><?php echo $_CtrlTotalNC; ?></th>
                    <th colspan="1" height="30px" style="border:none; font-weight:bold;"></th>
                </tr>
            </tfoot>
        </table>  
        
        
    	<div hidden="hidden"><button id="btnExport" hidden="hidden"></button></div>  
    </fieldset>
</form> 	
    
<script type="text/javascript" src="logica/jquery/jquery.process.emitirnc.js"></script>
<script type="text/javascript">
	function dac_chageDrogueria(){
		var fecha = $('#fecha_liquidacion').val();
		var drogueria = $('#drogid').val(); 
		window.location = '/pedidos/transfer/gestion/liquidaciondrog/index.php?fecha_liquidacion='+fecha+'&drogid='+drogueria;		
	}	
</script>