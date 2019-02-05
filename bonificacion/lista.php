<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!= "M"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

if(empty($_REQUEST['fecha_bonif'])){
	$_mes	=	date("m");
	$_anio	=	date("Y");
} else {
	list($_mes, $_anio) = explode('-', $_REQUEST['fecha_bonif']);
}
$_disabled =	($_SESSION["_usrrol"]=="V") ? "disabled" : "";

//Consultar el estado del primer registro de la fecha para saber si está activo o inactivo
$_bonificacionestado	=	DataManager::getDetalleBonificacion($_mes, $_anio);
if ($_bonificacionestado) {
	foreach ($_bonificacionestado as $k => $_bonifest){
		$_bonifest	=	$_bonificacionestado[$k];
		$_activa 	=	$_bonifest['bonifactiva'];
		break;
	}
} else {$_activa = 0;}

$_boton_copy	= 	sprintf( "<img id=\"copia\" src=\"/pedidos/images/icons/ico-copy.png\" border=\"0\" align=\"absmiddle\" title=\"duplicar bonificaci&oacute;n\" onclick=\"javascript:dac_DuplicarBonificacion(%d, %d)\"/>", $_mes, $_anio);

$_boton_exportar=	sprintf( "<a id=\"exporta\" href=\"logica/exportar.bonificacion.php?mes=%d&anio=%d\" title=\"exportar bonificaci&oacute;n\"><img src=\"/pedidos/images/icons/export_excel.png\" border=\"0\" align=\"absmiddle\"/></a> ", $_mes, $_anio);

$_status		= ($_activa == 1) ? "<img id=\"activar\" src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Desactivar Bonificación\"/>" : "<img id=\"activar\" src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Activar Bonificación\"/>";

if($_SESSION["_usrrol"]!="V") {
	$_cambiar		= 	sprintf( "<a href=\"logica/changestatus.php?mes=%d&anio=%d&activa=%d&backURL=%s\" title=\"Cambiar Estado\">%s</a>", $_mes, $_anio, $_activa, $_SERVER['PHP_SELF'], $_status);
} else {
	$_cambiar		=	$_status;
}

$_boton_precio	= 	sprintf( "<img id=\"precio\" src=\"/pedidos/images/icons/icono-precio.png\" border=\"0\" align=\"absmiddle\" title=\"Modificar Precios\" onclick=\"javascript:dac_ModificarPrecios(%d, %d)\"/>", $_mes, $_anio);
?>
<div id="cuadro-bonificacion">          
    <table id="tabla_bonif" name"tabla_bonif" class="tabla_bonif" cellpadding="0" cellspacing="0" border="1">
        <thead>                    
            <tr align="left">
                <th colspan="3" style="border:none"> 
                    <form id="fm_mesanio" action="#" method="post" enctype="multipart/form-data" style="float:left" > 														
                        <label for="fecha_bonif" >Fecha Bonificaci&oacute;n: </label>
                        <?php echo listar_mes_anio('fecha_bonif', $_anio, $_mes, 'javascript:dac_VerMesAnio()', 'width:80px; font-weight:bold; background-color:#FFF;'); ?>
                        <input hidden id="vermesanio" name="vermesanio" type="submit"/>
                    </form>   
                </th>
                <th colspan="1" style="border:none" align="center"> 
                    <?php /*if($_SESSION["_usrrol"]!="V") { ?>
                    	<img id="guardar" title="Guardar Cambios" src="../images/icons/icono-save50.png" border="0" align="absmiddle" /> 
					<?php } */?>
                </th>
                <th colspan="1" align="center" style="border:none">  <?php echo $_cambiar; ?>  </th>
                <!--th colspan="2" style="border:none" align="center">  <?php //echo $_boton_print; ?> </th-->
                <th colspan="1" style="border:none" align="center">  <?php echo $_boton_exportar; ?> </th>
                <th colspan="1" style="border:none" align="center">  <?php /*if($_SESSION["_usrrol"]!="V") {echo $_boton_copy;} */ ?> </th>
                <th colspan="1" style="border:none" align="center">  <?php /*if($_SESSION["_usrrol"]!="V") {echo $_boton_precio;} */ ?> </th>
                <th colspan="29" style="border:none"></th>
            </tr>
                                                      
            <tr>  <!-- Títulos de las Columnas -->
                <th colspan="3"></th>
                <th align="center" >Droguer&iacute;a</th>
                <th align="center" >P&uacute;blico</th>
                <th align="center" >Iva</th>
                <th align="center" >Digitado</th>
                <th align="center" >OAM</th>
                <th align="center" colspan="3">1</th> <th align="center" colspan="3">3</th>
                <th align="center" colspan="3">6</th> <th align="center" colspan="3">12</th>
                <th align="center" colspan="3">24</th> <th align="center" colspan="3">36</th>
                <th align="center" colspan="3">48</th> <th align="center" colspan="3">72</th>
                <th></th>                
            </tr>
        </thead>
                 
        <tbody> 
            <form id="fm_bonificacion_edit" name="fm_bonificacion_edit" method="POST"  enctype="multipart/form-data">        	
                <input id="mes_bonif" name="mes_bonif" type="text" value="<?php echo $_mes; ?>" hidden/>
                <input id="anio_bonif" name="anio_bonif" type="text" value="<?php echo $_anio; ?>" hidden/>
                <?php 
                /*************************************/  
                //Consulta Bonificación del mes actual
                /*************************************/
                $_bonificacion	=	DataManager::getDetalleBonificacion($_mes, $_anio);
                if ($_bonificacion) {
                    foreach ($_bonificacion as $k => $_bonif){
                        $_bonif			=	$_bonificacion[$k];
                        $_bonifID		=	$_bonif['bonifid'];
                        $_bonifEmp		=	$_bonif['bonifempid'];
                        $_bonifArtid	= 	$_bonif['bonifartid'];
                        $_bonifPrecio	= 	$_bonif['bonifpreciodrog'];
                        $_bonifPublico	= 	$_bonif['bonifpreciopublico'];
                        $_bonifIva		= 	$_bonif['bonifiva'];
                        $_bonifDigitado	= 	$_bonif['bonifpreciodigitado'];
                        $_bonifOferta	= 	$_bonif['bonifoferta'];
                        $_bonif1a		= 	($_bonif['bonif1a']	== 0)	?	''	:	$_bonif['bonif1a'];							
                        $_bonif1b		=	($_bonif['bonif1b']	== 0)	?	''	:	$_bonif['bonif1b'];
                        $_bonif1c		= 	($_bonif['bonif1c'] == 0)	?	''	:	$_bonif['bonif1c'];
                        $_bonif3a		=	($_bonif['bonif3a']	== 0)	?	''	:	$_bonif['bonif3a'];
                        $_bonif3b		=	($_bonif['bonif3b']	== 0)	?	''	:	$_bonif['bonif3b'];
                        $_bonif3c		=	($_bonif['bonif3c'] == 0)	?	''	:	$_bonif['bonif3c'];
                        $_bonif6a		=	($_bonif['bonif6a']	== 0)	?	''	:	$_bonif['bonif6a'];
                        $_bonif6b		=	($_bonif['bonif6b']	== 0)	?	''	:	$_bonif['bonif6b'];
                        $_bonif6c		= 	($_bonif['bonif6c'] == 0)	?	''	:	$_bonif['bonif6c'];
                        $_bonif12a		=	($_bonif['bonif12a']== 0)	?	''	:	$_bonif['bonif12a'];
                        $_bonif12b		=	($_bonif['bonif12b']== 0)	?	''	:	$_bonif['bonif12b'];
                        $_bonif12c		= 	($_bonif['bonif12c']== 0)	?	''	:	$_bonif['bonif12c'];
                        $_bonif24a		= 	($_bonif['bonif24a']== 0)	?	''	:	$_bonif['bonif24a'];
                        $_bonif24b		= 	($_bonif['bonif24b']== 0)	?	''	:	$_bonif['bonif24b'];	
                        $_bonif24c		=	($_bonif['bonif24c']== 0)	?	''	:	$_bonif['bonif24c'];
                        $_bonif36a		= 	($_bonif['bonif36a']== 0)	?	''	:	$_bonif['bonif36a'];
                        $_bonif36b		= 	($_bonif['bonif36b']== 0)	?	''	:	$_bonif['bonif36b'];
                        $_bonif36c		= 	($_bonif['bonif36c']== 0)	?	''	:	$_bonif['bonif36c'];
                        $_bonif48a		= 	($_bonif['bonif48a']== 0)	?	''	:	$_bonif['bonif48a'];
                        $_bonif48b		= 	($_bonif['bonif48b']== 0)	?	''	:	$_bonif['bonif48b'];
                        $_bonif48c		= 	($_bonif['bonif48c']== 0)	?	''	:	$_bonif['bonif48c'];
                        $_bonif72a		= 	($_bonif['bonif72a']== 0)	?	''	:	$_bonif['bonif72a'];
                        $_bonif72b		= 	($_bonif['bonif72b']== 0)	?	''	:	$_bonif['bonif72b'];
                        $_bonif72c		= 	($_bonif['bonif72c']== 0)	?	''	:	$_bonif['bonif72c'];
                        ((($k % 2) != 0)? $clase="par" : $clase="impar"); ?>
                        
                        <tr id="b_<?php echo ($k+1);?>" class="<?php echo $clase;?>">                    	
                            <td></td>
                            <td>1<input id="idbonif" name="idbonif[]" type="text" value="<?php echo $_bonifID;?>" hidden/></td>
                            <td><select id="art" name="art[]" style="width: 300px;" <?php echo $_disabled; ?>>
                                    <option value=''>Indica art&iacute;culo...</option>  <?php
                                    $_articulos	= DataManager::getArticulos($_pag, $_LPP, FALSE, 1, 1, 1);
                                    if (count($_articulos)) {	
                                        //Los múltiples select generan desbordes de datos en la web							 
                                        foreach ($_articulos as $k => $_articulo) {		
                                            unset($_articulo, $_idart, $_nombre);																		
                                            $_articulo 		= $_articulos[$k];
                                            $_idart			= $_articulo['artidart'];	
                                            $_nombre		= $_articulo["artnombre"];	
                                                                            
                                            if($_bonifArtid == $_idart){ ?>                                        
                                                <option value="<?php echo $_idart;?>" selected><?php echo $_idart." - ".$_nombre; ?></option><?php	
                                            } else { ?>                                        
                                               	<option value="<?php echo $_idart;?>"><?php echo $_idart." - ".$_nombre; ?></option><?php	
                                            }
                                        }
                                    } else { ?>
                                        <option value="">Actualmente no hay art&iacute;culos activos.</option><?php 
                                    } ?>
                                </select>                            
                            </td>
                            <td><input id="drog" name="drog[]" type="text" size="4px" maxlength="7" value="<?php echo $_bonifPrecio; ?>" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);" align="left" <?php echo $_disabled; ?> /></td>
                            <td><input id="publico" name="publico[]" type="text" size="4px" maxlength="7" value="<?php echo $_bonifPublico; ?>" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);" align="right" <?php echo $_disabled; ?>/></td>
                            <td><input id="iva" name="iva[]" type="text" size="1px" value="<?php if($_bonifIva != 0) {echo $_bonifIva;} ?>" maxlength="3" <?php echo $_disabled; ?>/></td>
                            <td><input id="digitado" name="digitado[]" type="text" size="4px" maxlength="7" value="<?php if($_bonifDigitado != "0.000"){echo $_bonifDigitado;}; ?>" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);" align="right" <?php echo $_disabled; ?>/></td>
                            <td class="<?php echo $_bonifOferta; ?>">
                                <select id="oferta" name="oferta[]" style="width: 70px;" <?php echo $_disabled; ?>>
                                    <option value=""></option>
                                    <option value="alta" <?php if ($_bonifOferta=="alta"){echo "selected";}; ?> >Alta</option>
                                    <option value="modificado" <?php if ($_bonifOferta=="modificado"){echo "selected";}; ?>>Modificaci&oacute;n</option>                                   
                                    <option value="oferta" <?php if ($_bonifOferta=="oferta"){echo "selected";}; ?>>Oferta</option>
                                    <option value="altaoff" <?php if ($_bonifOferta=="altaoff"){echo "selected";}; ?>>AltaOff</option>
                                    <option value="modifoff" <?php if ($_bonifOferta=="modifoff"){echo "selected";}; ?>>ModifOff</option>
                                </select>                      
                            </td>
                            <td class="borde_left"><input id="1a" name="1a[]" type="text" size="1px" value="<?php echo $_bonif1a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="1b" name="1b[]" type="text" size="1px" value="<?php echo $_bonif1b; ?>" maxlength="2" placeholder="X" <?php echo $_disabled; ?>/></td>
                            <td><input id="1c" name="1c[]" type="text" size="1px" value="<?php echo $_bonif1c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="borde_left"><input id="3a" name="3a[]" type="text" size="1px" value="<?php echo $_bonif3a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="3b" name="3b[]" type="text" size="1px" value="<?php echo $_bonif3b; ?>" maxlength="2" placeholder="X" <?php echo $_disabled; ?>/></td>
                            <td><input id="3c" name="3c[]" type="text" size="1px" value="<?php echo $_bonif3c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="borde_left"><input id="6a" name="6a[]" type="text" size="1px" value="<?php echo $_bonif6a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="6b" name="6b[]" type="text" size="1px" value="<?php echo $_bonif6b; ?>" maxlength="2" placeholder="X" <?php echo $_disabled; ?>/></td>
                            <td><input id="6c" name="6c[]" type="text" size="1px" value="<?php echo $_bonif6c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="borde_left"><input id="12a" name="12a[]" type="text" size="1px" value="<?php echo $_bonif12a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="12b" name="12b[]" type="text" size="1px" value="<?php echo $_bonif12b; ?>" maxlength="2" placeholder="X"<?php echo $_disabled; ?>/></td>
                            <td><input id="12c" name="12c[]" type="text" size="1px" value="<?php echo $_bonif12c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="borde_left"><input id="24a" name="24a[]" type="text" size="1px" value="<?php echo $_bonif24a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="24b" name="24b[]" type="text" size="1px" value="<?php echo $_bonif24b; ?>" maxlength="2" placeholder="X" <?php echo $_disabled; ?>/></td>
                            <td><input id="24c" name="24c[]" type="text" size="1px" value="<?php echo $_bonif24c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="borde_left"><input id="36a" name="36a[]" type="text" size="1px" value="<?php echo $_bonif36a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="36b" name="36b[]" type="text" size="1px" value="<?php echo $_bonif36b;?>" maxlength="2" placeholder="X" <?php echo $_disabled; ?>/></td>
                            <td><input id="36c" name="36c[]" type="text" size="1px" value="<?php echo $_bonif36c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="borde_left"><input id="48a" name="48a[]" type="text" size="1px" value="<?php echo $_bonif48a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="48b" name="48b[]" type="text" size="1px" value="<?php echo $_bonif48b; ?>" maxlength="2" placeholder="X" <?php echo $_disabled; ?>/></td>
                            <td><input id="48c" name="48c[]" type="text" size="1px" value="<?php echo $_bonif48c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="borde_left"><input id="72a" name="72a[]" type="text" size="1px" value="<?php echo $_bonif72a; ?>"  maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td><input id="72b" name="72b[]" type="text" size="1px" value="<?php echo $_bonif72b; ?>" maxlength="2" placeholder="X" <?php echo $_disabled; ?>/></td>
                            <td><input id="72c" name="72c[]" type="text" size="1px" value="<?php echo $_bonif72c; ?>" maxlength="2" <?php echo $_disabled; ?>/></td>
                            <td class="eliminar" style="border-left: 3px solid;">
                            	<?php if($_SESSION["_usrrol"]!="V") {  ?>
                               		<img src="../images/icons/icono-eliminar-claro.png" class="btn remove_button" border="0" align="absmiddle" />
                            	<?php } ?>
                            </td>
                        </tr> <?php 
                    } //FIN del FOR 
                }else { ?> <!-- fila base para clonar y agregar al final -->
                    <tr id="b_1" class="impar">
                        <td></td>
                        <td>1<input id="idbonif" name="idbonif[]" type="text"  hidden/></td>
                        <td><select id="art" name="art[]" style="width:300px;" >
                            <option value="">Indica art&iacute;culo...</option>  <?php
                                $_articulos	= DataManager::getArticulos($_pag, $_LPP, FALSE, NULL, 1, 1);
                                if (count($_articulos)) {								 
                                    foreach ($_articulos as $k => $_articulo) {																			
                                        $_articulo 		= $_articulos[$k];
                                        $_idart			= $_articulo['artidart'];	
                                        $_nombre		= $_articulo["artnombre"]; ?>                                        
                                        <option value="<?php echo $_idart;?>"><?php echo $_idart." - ".$_nombre; ?></option><?php	
                                    }
                                } else { ?>
                                    <option value="">No hay art&iacute;culos activos.</option><?php 
                                } ?>
                        </select>
                        </td>
                        <td><input id="drog" name="drog[]" type="text" size="4px" maxlength="7" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);" align="left" /></td>
                        <td><input id="publico" name="publico[]" type="text" size="4px" maxlength="7" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);" align="right" /></td>
                        <td><input id="iva" name="iva[]" type="text" size="1px" maxlength="3" /></td>
                        <td><input id="digitado" name="digitado[]" type="text" size="4px" maxlength="7" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);" align="right" /></td>
                        <td><select id="oferta" name="oferta[]" style="width: 70px;" >
                                <option value=""></option>
                                <option value="alta">Alta</option>
                                <option value="modificado">Modificaci&oacute;n</option>
                                <option value="oferta">Oferta</option>
                                <option value="altaoff">AltaOff</option>
                            </select>
                        </td>                    
                        <td><input id="1a" name="1a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="1b" name="1b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="1c" name="1c[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="3a" name="3a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="3b" name="3b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="3c" name="3c[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="6a" name="6a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="6b" name="6b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="6c" name="6c[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="12a" name="12a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="12b" name="12b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="12c" name="12c[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="24a" name="24a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="24b" name="24b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="24c" name="24c[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="36a" name="36a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="36b" name="36b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="36c" name="36c[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="48a" name="48a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="48b" name="48b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="48c" name="48c[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="72a" name="72a[]" type="text" size="1px" maxlength="2" /></td>
                        <td><input id="72b" name="72b[]" type="text" size="1px" maxlength="2" placeholder="X" /></td>
                        <td><input id="72c" name="72c[]" type="text" size="1px" maxlength="2" /></td>
                        <td class="eliminar">
							<?php if($_SESSION["_usrrol"]!="V") {  ?>
                            	<img src="../images/icons/icono-eliminar-claro.png" class="btn remove_button" border="0" align="absmiddle" />
                            <?php } ?>
                        </td>
                    </tr> <!-- fin de código: fila base --> <?php
                } ?>
            </form>                       
        </tbody>
                
        <tfoot>
            <tr>
                <th colspan="3" height="30px" style="border:none" align="center">
                	<?php /*if($_SESSION["_usrrol"]!="V") {  ?>
                    	<img id="agregar" title="Agregar Bonificación" src="../images/icons/icono-nuevo50.png" border="0" align="absmiddle"/><!-- Agregar filas -->
                    <?php } */ ?>
                </th>
                <th colspan="1" style="border:none" align="center">
                	<?php /*if($_SESSION["_usrrol"]!="V") {  ?>
                    	<img id="guardar2" title="Guardar Cambios" src="../images/icons/icono-save50.png" border="0" align="absmiddle" onclick="document.getElementById('guardar').click();"/>
                    <?php } */ ?>
                </th>
                <th colspan="29" style="border:none"></th>
            </tr>
            <tr>
                <th colspan="11">
                    OAM: Ofertas, Altas y Modificaciones  </br>
                    Plazo de Pago: </br>
                    Pedidos generales 30 dias</br>
                    Pedidos  mas de 10 referencias y mas de $ 3.000 (que cumplan las dos condiciones)  30  y 60 dias. O 45 dias
                </th>                                   
            
                <th colspan="22">
                    ATENCION: </br>
                    Los articulos con condicion de IVA Gravado se encuentran identificados con su correspondiente alicuota en la columna IVA, y su precio Publico tienen incluido este valor. A los precios Drogueria y Digitados se les deberá adicionar el IVA.
                </th>                                   
            </tr>
        </tfoot>
    </table>        
</div> <!-- FIN bonificacion --> 
    
<script type="text/javascript" src="logica/jquery/jquery.agregarbonif.js"></script>
<script type="text/javascript" src="logica/jquery/jquery.processbonif.js"></script>
<script type="text/javascript">
	function dac_VerMesAnio(){ document.getElementById("vermesanio").click();}
</script>

<script type="text/javascript">
function dac_DuplicarBonificacion(mes, anio){
	if(confirm("Recuerde que se borrará cualquier dato en la fecha donde duplique la bonificación. ¿Desea continuar?")){
		mes_sig	= prompt("Ingrese el mes a donde desea duplicar la bonificación. (de 1 a 12)");
		if(mes_sig < 1 || mes_sig > 12){ 
			alert("El mes indicado es incorrecto. El duplicado no se realizará. Vuelva a Intentarlo.");
		} else {
			anio_sig= prompt("Ingrese el año a donde desea duplicar la bonificación. (ejemplo 2017)");
			if(anio_sig < 2015 || anio_sig > 2025){ 
				alert("El año indicado es incorrecto. El duplicado no se realizará. Vuelva a Intentarlo.");
			}else{
				if(mes == mes_sig && anio == anio_sig){
					alert("Ha intentado duplicar una bonificación en la misma fecha. Vuelva a Intentarlo.");
				} else {
					if(!(mes_sig < 1 || mes_sig > 12) && !(anio_sig < 2015 || anio_sig > 2025)){
						$.ajax({
							type : 'POST',
							url : 'logica/ajax/duplicar.bonificacion.php',					
							data:{	mes		:	mes,
								mes_sig	:	mes_sig,
								anio	:	anio,
								anio_sig:	anio_sig
							},				
							success : function (resultado) { 								
								if (resultado){
									if (resultado == "1"){
										alert("La Bonificación fue duplicada.");
									} else {
										alert(resultado);
									}						
								}															
							},
							error: function () {alert("Error en el proceso de duplicado.");}								
						});
					}
				}
			}
		}
	}
}	

</script>

<script type="text/javascript">
function dac_ModificarPrecios(mes, anio){
	if(confirm("Se van a actualizar los precios de artículos incluidos en la bonificación. ¿Está seguro de que desea continuar?")){
		document.getElementById("guardar").click();
		$.ajax({
			type : 'POST',
			url : 'logica/ajax/actualizar.precios.php',					
			data:{	mes		:	mes,
					anio	:	anio,
			 	},				
			success : function (resultado) { 								
					if (resultado){
						if (resultado == "1"){ alert("Los precios incluidos en la bonificación han sido actualizados en la tabla de artículos de la web.");
						} else { alert(resultado); }						
					}															
				},
			error: function () {alert("Error en el proceso de actualización de precios.");}								
		});
	}					
}	
</script>

<script type="text/javascript">
/************************************/
/*		 IMPRIMIR MUESTRA			*/
/************************************/
function dac_PrintMuestra(){
	document.getElementById('imprime').style.display = 'none';
	document.getElementById('menuprincipal').style.display = 'none';
	document.getElementById('cabecera').style.display = 'none';
	document.getElementById('pie').style.display = 'none';
	document.getElementById('copia').style.display = 'none';
	document.getElementById('exporta').style.display = 'none';
	document.getElementById('precio').style.display = 'none';
	document.getElementById('activar').style.display = 'none';
	document.getElementById('guardar').style.display = 'none';
	document.getElementById('guardar2').style.display = 'none';
	document.getElementById('agregar').style.display = 'none';
	
	var ventimp=window.print(' ','popimpr');
	
	document.getElementById('cabecera').style.display = 'inline';
	document.getElementById('menuprincipal').style.display = 'inline';
	document.getElementById('imprime').style.display = 'inline';
	document.getElementById('pie').style.display = 'inline';
	document.getElementById('copia').style.display = 'inline';
	document.getElementById('exporta').style.display = 'inline';
	document.getElementById('precio').style.display = 'inline';
	document.getElementById('activar').style.display = 'inline';
	document.getElementById('guardar').style.display = 'inline';
	document.getElementById('guardar2').style.display = 'inline';
	document.getElementById('agregar').style.display = 'inline';
	
	ventimp.document.close();
	ventimp.print();
	ventimp.close();
	
}
</script>