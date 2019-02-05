<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$idPropuesta	= empty($_REQUEST['propuesta'])	?	0	:	$_REQUEST['propuesta'];

if($idPropuesta) {
	$propObject 	=	DataManager::newObjectOfClass('TPropuesta', $idPropuesta);
	$idCuenta		= 	$propObject->__get('Cuenta');
	$usrAsignado	= 	$propObject->__get('UsrAsignado');
	$idEmpresa		= 	$propObject->__get('Empresa');
	$estado			= 	$propObject->__get('Estado');
	$idLaboratorio	= 	$propObject->__get('Laboratorio');
	$nombreCuenta 	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, $idEmpresa);
	$observacion	= 	$propObject->__get('Observacion');
	
	$detalles	= 	DataManager::getPropuestaDetalle($idPropuesta);
	if ($detalles) { 
		foreach ($detalles as $j => $det) {	
			$condPago		= 	$det["pdcondpago"];
			$idArt			=	$det['pdidart'];
			$unidades		=	$det['pdcantidad'];
			$descripcion	=	DataManager::getArticulo('artnombre', $idArt, 1, 1);									
			$precio			=	$det['pdprecio'];//str_replace('EUR','',money_format('%.2n', $det['pdprecio']));
			$b1				=	($det['pdbonif1'] == 0)	?	''	:	$det['pdbonif1'];
			$b2				=	($det['pdbonif2'] == 0)	?	''	:	$det['pdbonif2'];
			$desc1			=	($det['pddesc1'] == 0)	?	''	:	$det['pddesc1'];
			$desc2			=	($det['pddesc2'] == 0)	?	''	:	$det['pddesc2'];
		}
	}		
} else {
	$idCuenta 			= 	"";
	$nombreCuenta 		= 	"";
	$usrAsignado		= 	$_SESSION["_usrid"];
	$idEmpresa			=	1;
	$estado				=	"";
	$idLaboratorio		=	1;
	$observacion		= 	"";
} ?>

<!DOCTYPE html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?>  
	<script language="JavaScript"  src="/pedidos/pedidos/logica/jquery/jquery.js" type="text/javascript"></script>
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
       	$_section		=	"pedidos";
		$_subsection 	=	"nuevo_pedido";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>
                	
	<main class="cuerpo">
    	<div class="box_body">               
        	<form id="fmPedidoWeb" name="fmPedidoWeb" class="fm_edit2" method="post">
            	<input type="text" id="pwestado" name="pwestado" value="<?php echo $estado; ?>" hidden="hidden"/>
            	<input type="text" id="pwidcondcomercial" name="pwidcondcomercial" hidden="hidden"/>
            	<fieldset>
                	<legend>Pedido Web</legend>
                    <div class="bloque_3" align="center">     
                        <fieldset id='box_error' class="msg_error">          
                            <div id="msg_error" align="center"></div>
                        </fieldset>                                                                         
                        <fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;">                        	
                            <div id="msg_cargando" align="center"></div>      
                        </fieldset> 
                        <fieldset id='box_confirmacion' class="msg_confirmacion">
                            <div id="msg_confirmacion" align="center"></div>      
                        </fieldset>
                    </div>
                    
                    <div class="bloque_1">
                    	<label for="pwusrasignado">Asignado a</label>
                        <select id="pwusrasignado" name="pwusrasignado">
                        	<option id="0" value="0" selected></option> <?php
                            $vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
                            if (count($vendedores)) {	
                                foreach ($vendedores as $k => $vend) {
                                    $idVend		=	$vend["uid"];
                                    $nombreVend	=	$vend['unombre'];
                                    $rolVend	= 	$vend['urol'];
									
									if ($rolVend == 'V'){									
										if ($usrAsignado == $idVend	){ 
											?><option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>" selected><?php echo $nombreVend; ?></option><?php
										} else { ?>
											<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>"><?php echo $nombreVend; ?></option><?php
										}
									}
                                }                            
                            } ?>
                        </select>	
                    </div> 
                    
                    <?php 
					$hiddenTipo = ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"  || $_SESSION["_usrrol"]=="G") ? "" : "hidden";?>
                    <div class="bloque_2" <?php echo $hiddenTipo; ?> >
                    	<label for="pwtipo" <?php echo $hiddenTipo; ?> >Tipo</label>
                        <select name="pwtipo" <?php echo $hiddenTipo; ?> >
                        	<option value=""></option> 
                        	<option value="PARTICULAR">Cliente Particular</option>
                        	<option value="SP">Salida Promoción</option>
                        	<option value="SF">Solo Facturar</option>
                        	<option value="VALE">Vale</option>
                        </select>	
                    </div> 
                    
                    
                    <div class="bloque_2">
                        <a id="btsend" title="Enviar" style="float:left;">
                            <img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle"/>
                        </a>
                    </div>                   
                    
                    <div class="bloque_1">
                        <label for="empselect">Empresa</label>                        
                        <select id="empselect" name="empselect" onchange="javascript:dac_selectChangeEmpresa(this.value);"> <?php
                            $empresas	= DataManager::getEmpresas(1); 
                            if (count($empresas)) {	
                                foreach ($empresas as $k => $emp) {
                                    $idEmp		=	$emp["empid"];
                                    $nombreEmp	=	$emp["empnombre"];
                                    if ($idEmp == $idEmpresa){ ?>                        		
                                        <option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>" selected><?php echo $nombreEmp; ?></option><?php
                                    } else { ?>
                                        <option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>"><?php echo $nombreEmp; ?></option><?php
                                    }   
                                }                            
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_1">
                        <label for="labselect">Laboratorio</label> 
                        <select name="labselect" id="labselect" onchange="javascript:dac_selectChangeLaboratorio(this.value);"><?php 			
                            $laboratorios	= DataManager::getLaboratorios(); 
                            if (count($laboratorios)) {	
                                foreach ($laboratorios as $k => $lab) {
                                    $idLab			=	$lab["idLab"];
                                    $descripcion	=	$lab["Descripcion"];							
                                    if ($idLab == $idLaboratorio){ ?>                        		
                                        <option id="<?php echo $idLab; ?>" value="<?php echo $idLab; ?>" selected><?php echo $descripcion; ?></option><?php											
                                    } else { ?>
                                        <option id="<?php echo $idLab; ?>" value="<?php echo $idLab; ?>"><?php echo $descripcion; ?></option><?php
                                    }   
                                }                            
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_3"  align="center">     
                        <fieldset id='box_observacion' class="msg_alerta">
                            <div id="msg_atencion" align="center"></div>       
                        </fieldset>
                    </div>
                    
                    <div class="bloque_2"> 
                        <label for="pwidcta">Cuenta</label>
                        <input type="text" name="pwidcta" id="pwidcta" value="<?php echo $idCuenta; ?>" readonly style="border:none;"/>
                    </div>
                    
                    <div class="bloque_1"> 
                    	<label>Raz&oacute;n social</label>
                    	<input type="text" name="pwnombrecta" id="pwnombrecta" value="<?php echo $nombreCuenta; ?>" readonly style="border:none;"/>
                    </div>
                    
                    <div class="bloque_2">
                        <label for="pworden">Nro Orden</label>
                        <input type="text" name="pworden" id="pworden" maxlength="10"/>
                    </div>
                    
                   	<div class="bloque_1">  
                        <label>Condici&oacute;n de pago </label>
                        <select name="condselect" id="condselect"> <?php
                            $condicionesPago	=	DataManager::getCondicionesDePago(0, 0, 1); 
                            if (count($condicionesPago)) { ?>                        		
								<option id="0" value="0" selected></option><?php			
                                foreach ($condicionesPago as $k => $cond) {		
									$idCond		= $cond['condid'];	
									$condCodigo	= $cond['IdCondPago'];										
									$condNombre	= DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $cond['condtipo']);
									
									$condDias	= "(";					
									$condDias	.= empty($cond['Dias1CP']) ? '' : $cond['Dias1CP'];
									$condDias	.= empty($cond['Dias2CP']) ? '' : ', '.$cond['Dias2CP'];
									$condDias	.= empty($cond['Dias3CP']) ? '' : ', '.$cond['Dias3CP'];
									$condDias	.= empty($cond['Dias4CP']) ? '' : ', '.$cond['Dias4CP'];
									$condDias	.= empty($cond['Dias5CP']) ? '' : ', '.$cond['Dias5CP'];
									$condDias	.= " D&iacute;as)";					
									$condPorc	= ($cond['Porcentaje1CP']== '0.00') ? '' : $cond['Porcentaje1CP'];
									
                                    //Descarto la opción FLETERO porque se supone que ya no debería usarse
                                    if(trim($condNombre) != "FLETERO"){
                                        if($condPago == $condCodigo){ ?>                        		
                                            <option id="<?php echo $idCond; ?>" value="<?php echo $condCodigo; ?>" selected><?php echo $condNombre." - ".$condDias." - [".$condPorc."%]"; ?></option><?php							
                                        } else { ?> 
                                            <option id="<?php echo $idCond; ?>" value="<?php echo $condCodigo; ?>" ><?php echo $condNombre." - ".$condDias." - [".$condPorc."%]"; ?></option><?php	
                                        }
                                    }
                                }
                                $objJason = json_encode($_datos);
                                echo $objJason;					  
                            } ?>
                        </select>
                    </div>
                    <div class="bloque_2">
                    	<input type="text" name="pwidpropuesta" id="pwidpropuesta" value="<?php echo $idPropuesta; ?>" hidden="hidden"/>
                       
                        <input type="checkbox" name="pwpropuesta" id="pwpropuesta" value="1" style="margin-top:15px; float: left;" <?php if($idPropuesta){ echo "checked='checked'";}; ?> />
                        <label for="pwpropuesta" style="padding:20px 0 0 10px"><strong>PROPUESTA</strong></label>
                    </div>
                    
					<div class="bloque_2">
						<input type="checkbox" name="cadena" id="cadena" style="margin-top:15px; float: left;"/>
						<label for="cadena" style="padding:20px 0 0 10px"><strong>CADENA</strong></label>
					</div>              
                    
                   	<div class="bloque_3">
                   		<label>Observaci&oacute;n</label>  
                        <textarea type="text" name="pwobservacion" id="pwobservacion" cols="30" rows="5" maxlength="200" ><?php echo $observacion; ?></textarea>
                   	</div>
                   	
               	</fieldset>
               
               	<fieldset>
                	<legend>Art&iacute;culos</legend>
               		<div id="lista_articulos2"></div>                    
                    <div class="bloque_3">
                    	<div id="pwsubtotal" style="display:none;"></div>
                    </div>
               	</fieldset>
            </form>                         
        </div> <!-- FIN box_body-->	
        
        <div class="box_seccion">
        	<div class="barra">
                <div class="buscadorizq">
                    <h1>Condiciones Comerciales</h1>                	
                </div>
                <hr>
            </div> <!-- Fin barra -->            
            <div class="lista"> 
            	<div id='tablacondiciones'></div>
            </div> <!-- Fin lista -->	
            
            <div class="barra">
                <div class="buscadorizq">
                    <h1>Cuentas</h1>                	
                </div>
                <div class="buscadorder">
                	<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
                    <input id="txtBuscarEn" type="text" value="tblTablaCta" hidden/>
                </div> 
                <hr>     
            </div> <!-- Fin barra -->            
            <div class="lista"> 
                <div id='tablacuenta'></div>
            </div> <!-- Fin lista -->		
            
            <div class="barra">
                <div class="buscadorizq">
                    <h1>Art&iacute;culos</h1>                	
                </div>
                <div class="buscadorder">
                	<input id="txtBuscar2" type="search" autofocus placeholder="Buscar..."/> 
                    <input id="txtBuscarEn2" type="text" value="tblTablaArt" hidden/>
                </div>
                <hr>
            </div> <!-- Fin barra -->            
            <div class="lista">
               <fieldset id='box_cargando3' class="msg_informacion" style="alignment-adjust:central;"> 
					<div id="msg_cargando3" align="center"></div>      
				</fieldset> 
                <div id='tablaarticulos'></div>
            </div> <!-- Fin lista -->	
        </div> <!-- FIN box_seccion-->	
        <hr>
	</main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->            
        
</body>
</html>

<script language="JavaScript" type="text/javascript">
	dac_selectChangeEmpresa(<?php echo $idEmpresa; ?>);
</script>

<?php 
if($idPropuesta){
	$detalles	= 	DataManager::getPropuestaDetalle($idPropuesta);
	if ($detalles) { 
		foreach ($detalles as $j => $det) {	
			//$condPago	= 	$det["pdcondpago"];
			$idArt		=	$det['pdidart'];
			$unidades	=	$det['pdcantidad'];
			$descripcion=	DataManager::getArticulo('artnombre', $idArt, 1, 1);									
			$precio		=	$det['pdprecio']; //str_replace('EUR','',money_format('%.2n', $det['pdprecio']));
			$b1			=	($det['pdbonif1'] == 0)	?	''	:	$det['pdbonif1'];
			$b2			=	($det['pdbonif2'] == 0)	?	''	:	$det['pdbonif2'];
			$desc1		=	($det['pddesc1'] == 0)	?	''	:	$det['pddesc1'];
			$desc2		=	($det['pddesc2'] == 0)	?	''	:	$det['pddesc2'];

			echo "<script>";
			echo "javascript:dac_CargarArticulo('$idArt', '$descripcion', '$precio', '$b1', '$b2', '$desc1', '$desc2', '$unidades');";
			echo "</script>";
		}
	}
} ?>