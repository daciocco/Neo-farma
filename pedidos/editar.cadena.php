<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$usrAsignado	=	(isset($_POST['pwusrasignado']))? $_POST['pwusrasignado'] 	:	NULL;
$usrAsigName	=  	DataManager::getUsuario('unombre', $usrAsignado);
$empresa		=	(isset($_POST['empselect']))	? $_POST['empselect'] 		:	NULL;
$laboratorio	=	(isset($_POST['labselect']))	? $_POST['labselect'] 		:	NULL;
$idCuenta		=	(isset($_POST['pwidcta']))		? $_POST['pwidcta'] 		: 	NULL;
$nombreCuenta 	=  	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, $empresa);
$nroOrden		=	(isset($_POST['pworden']))		? $_POST['pworden'] 		: 	NULL;
$condPago		=	(isset($_POST['condselect']))	? $_POST['condselect'] 		: 	NULL;
$observacion	=	(isset($_POST['pwobservacion']))? $_POST['pwobservacion']	: 	NULL;
//***********************//
$idCondComercial=	(isset($_POST['pwidcondcomercial']))? $_POST['pwidcondcomercial'] :	NULL;
//***********************//
$articulosIdArt	= 	(isset($_POST['pwidart'])) 		? $_POST['pwidart'] 		: NULL;

for($i=0;$i<count($articulosIdArt);$i++){
	$articulosNombre[] = DataManager::getArticulo('artnombre', $articulosIdArt[$i], $empresa, $laboratorio);
}

$articulosCant	= 	(isset($_POST['pwcant'])) 		? $_POST['pwcant'] 			: NULL;
$articulosPrecio= 	(isset($_POST['pwprecioart'])) 	? $_POST['pwprecioart'] 	: NULL;
$articulosB1	= 	(isset($_POST['pwbonif1'])) 	? $_POST['pwbonif1'] 		: NULL;
$articulosB2	= 	(isset($_POST['pwbonif2'])) 	? $_POST['pwbonif2'] 		: NULL;
$articulosD1	= 	(isset($_POST['pwdesc1'])) 		? $_POST['pwdesc1'] 		: NULL;
$articulosD2	= 	(isset($_POST['pwdesc2'])) 		? $_POST['pwdesc2'] 		: NULL;
$cadena			=	(isset($_POST['cadena']))		? $_POST['cadena'] 			: NULL;

//----------------------------------
//SE AGREGA ARTICULO PROMOCIONAL OBLIGATORIO A LAS SIGUIENTES CUENTAS CON
//se aplica aquí y en generar.pedido.php.php
//categoría sea <> 1 (distintas de droguerías) AND 
$categoria = DataManager::getCuenta('ctacategoriacomercial', 'ctaidcuenta', $idCuenta, $empresa);
//empresa == 1 AND 
//$idCondComercial <> de ListaEspecial DR AHORRO, FARMACITY, FARMACITY 2
// originalmente $idCondComercial != 1764  && $idCondComercial != 1765 && $idCondComercial != 1761
$condBonificar	= 0;
$condiciones = DataManager::getCondiciones(0, 0, 1, $empresa, $laboratorio, NULL, NULL, NULL, NULL, $idCondComercial);
if (count($condiciones) > 0) {
	foreach ($condiciones as $i => $cond) {
		$condTipo 	= $cond['condtipo'];
		$condNombre = $cond['condnombre'];
		if($condTipo == 'ListaEspecial' && ($condNombre == 'DR AHORRO' || $condNombre == 'FARMACITY' || $condNombre == 'FARMACITY 2')){
			$condBonificar = 1;
		}
	}
} 
if($empresa == 1 && $categoria <> 1 && $laboratorio == 1 && $condBonificar == 0){
	array_unshift ( $articulosIdArt	, 369 );
	array_unshift ( $articulosCant 	, 1 );
	array_unshift ( $articulosPrecio, 0 );
	array_unshift ( $articulosB1 	, 0 );
	array_unshift ( $articulosB2 	, 0 );
	array_unshift ( $articulosD1 	, 0 );
	array_unshift ( $articulosD2 	, 0 );
}
//-------------------------------------


?>

<!DOCTYPE html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?>  
	<script language="JavaScript"  src="/pedidos/pedidos/logica/jquery/jquery.js" type="text/javascript"></script>
	
	
	<script>
		/****************************************/
		/*  Carga Datos de Cuentas al Pedido	*/
		function dac_cargarCuentaCadena(idcli, nombre, observacion, fijo) {
			"use strict";
			var campo 	=  '<fieldset id="cta'+idcli+'">';
			if(fijo === 0){
				campo 	+= '<div class="bloque_9"><br><input id="btmenos" type="button" value="-" onClick="dac_eliminarCuentaCadena('+idcli+')"></div>';
			}
			campo 		+= '<div class="bloque_8"> ';
			campo 		+= '	<label for="pwidcta">Cuenta</label>';
			campo 		+= '	<input type="text" name="pwidcta[]" value="'+idcli+'" readonly style="border:none;"/>';
			campo 		+= '</div>';
			campo 		+= '<div class="bloque_3">';
			campo 		+= '	<label>Raz&oacute;n social</label>';
			campo 		+= '	<input type="text" value="'+nombre+'" readonly style="border:none;"/>';
			campo 		+= '</div>';
			
			campo 		+= '<div class="bloque_4">';
			campo 		+= '	<label>Observaci&oacute;n</label> '; 
			campo 		+= '	<textarea type="text" name="pwobservacion[]" maxlength="200" >'+observacion+'</textarea>';
			campo 		+= '</div> ';
			
			campo 		+= '<div class="bloque_7">';
			campo 		+= '	<label for="pworden">Nro Orden</label>';
			campo 		+= '	<input type="text" name="pworden[]" maxlength="10"/>';
			campo 		+= '</div>';
			
			campo 		+= '<div class="bloque_7">';
			campo 		+= '	<div class="inputfile">';
			campo 		+= '		<input name="file[]" class="file" type="file">';
			campo 		+= '	</div>';
			campo 		+= '</div>';
			
			campo 		+= '</fieldset>';	

			campo 		+= '<fieldset id="art'+idcli+'">';
			campo 		+= '	<legend>Art&iacute;culos</legend>';
			var idArticulos=<?php echo json_encode($articulosIdArt);?>;
			var nombresArt=<?php echo json_encode($articulosNombre);?>;
			var preciosArt=<?php echo json_encode($articulosPrecio);?>;
			var desc1=<?php echo json_encode($articulosD1);?>;
			var desc2=<?php echo json_encode($articulosD2);?>;
			for(var i=0;i<idArticulos.length;i++){
				campo +=	'<div id="rut'+idcli+'-'+idArticulos[i]+'">';				
					campo += 	'<input name="pwidart'+idcli+'[]" type="text" value="'+idArticulos[i]+'" hidden/>';
					
					campo += 	'<div class="bloque_9"><input id="btmenos" type="button" value="-" onClick="dac_eliminarArt('+idcli+', '+idArticulos[i]+')" style="background-color:#C22632;"></div>';
					campo += 	'<div class="bloque_2"><strong> Art&iacute;culo '+idArticulos[i]+ '</strong></br>'+nombresArt[i]+'</div>';
					
					campo += 	'<div class="bloque_8"><strong> Cantidad </strong> <input name="pwcant'+idcli+'[]" type="text" maxlength="5"/></div>';
					campo += 	'<div class="bloque_8"><strong> Precio </strong> <input type="text" name="pwprecioart'+idcli+'[]" value="'+preciosArt[i]+'"  readonly style="border:none"/> </div>';
					campo += 	'<div class="bloque_8"><strong>Bonifica</strong> <input name="pwbonif1'+idcli+'[]" type="text" maxlength="5"/></div>';
					campo += 	'<div class="bloque_8"><strong>Desc 1 </strong> <input type="text" name="pwdesc1'+idcli+'[]" value="'+desc1[i]+'" readonly style="border:none"/></div>';
					campo += 	'<div class="bloque_8"><strong>Desc 2 </strong> <input type="text" name="pwdesc2'+idcli+'[]" value="'+desc2[i]+'" readonly style="border:none"/></div>';	
				campo += 	'</div><hr>';
			};
			campo 		+= '</fieldset>';
			$("#fmPedidoWebCadena").append(campo);	
		}

		/****************************/
		/* 		Elimina Cuenta		*/
		function dac_eliminarCuentaCadena(idcli){
			"use strict";
			var elemento = document.getElementById('cta'+idcli);
			elemento.parentNode.removeChild(elemento);

			var elemento = document.getElementById('art'+idcli);
			elemento.parentNode.removeChild(elemento);

		}

		/********************************/
		/* 		Elimina un Artículo 	*/
		function dac_eliminarArt(idcli, idArt){
			"use strict";
			var elemento = document.getElementById('rut'+idcli+'-'+idArt);
			elemento.parentNode.removeChild(elemento);
		}
	</script>
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
       	$_section	 = "pedidos";
		$_subsection = "nuevo_pedido";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>
                	
	<main class="cuerpo">
    	<div class="box_body">               
        	<form id="fmPedidoWebCadena" name="fmPedidoWebCadena" method="post">
            	<input type="text" name="pwestado" value="<?php echo $estado; ?>" hidden="hidden"/>
            	<input type="text" name="pwidcondcomercial" value="<?php echo $idCondComercial; ?>" hidden="hidden"/>
            	<input type="hidden" name="pwidart" value='<?php echo serialize($articulosIdArt) ?>'/>
           		<input type="hidden" name="pwprecioart" value='<?php echo serialize($articulosPrecio) ?>'/>
            	<input type="hidden" name="pwcant" value='<?php echo serialize($articulosCant) ?>'/>
            	<input type="hidden" name="pwbonif1" value='<?php echo serialize($articulosB1) ?>'/>
            	<input type="hidden" name="pwbonif2" value='<?php echo serialize($articulosB2) ?>'/>
            	<input type="hidden" name="pwdesc1" value='<?php echo serialize($articulosD1) ?>'/>
            	<input type="hidden" name="pwdesc2" value='<?php echo serialize($articulosD2) ?>'/>
            	
               	<fieldset>
                	<legend>Pedido CADENA</legend>
                   	<div class="bloque_1" align="right">
                        <a id="btsendPedidoCadena" title="Enviar">
                            <img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle"/>
                        </a>
                    </div> 
                    
                    <div class="bloque_1" align="center">     
                        <fieldset id='box_error' class="msg_error">          
                            <div id="msg_error" align="center"></div>
                        </fieldset>    
                        <fieldset id='box_cargando' class="msg_informacion">
                            <div id="msg_cargando" align="center"></div>      
                        </fieldset> 
                        <fieldset id='box_confirmacion' class="msg_confirmacion">
                            <div id="msg_confirmacion" align="center"></div>      
                        </fieldset>
                        <fieldset id='box_observacion' class="msg_alerta">
                            <div id="msg_atencion" align="center"></div>       
                        </fieldset>
                    </div>
                    
                    <div class="bloque_5">
                    	<label for="pwusrasignado">Asignado a</label>
                      	<input type="text" name="pwusrasignado" id="pwusrasignado" value="<?php echo $usrAsignado; ?>" hidden/>
                       	<input type="text" value="<?php echo $usrAsigName; ?>" readonly style="border:none;"/>
                    </div>        
                    
                    <div class="bloque_5">
                        <label for="empselect">Empresa</label>                        
                        <select id="empselect" name="empselect"> <?php
                            $empresas	= DataManager::getEmpresas(1); 
                            if (count($empresas)) {	
                                foreach ($empresas as $k => $emp) {
                                    $idEmp		=	$emp["empid"];
                                    $nombreEmp	=	$emp["empnombre"];
                                    if ($idEmp == $empresa){ ?>     
                                        <option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>" selected><?php echo $nombreEmp; ?></option><?php
                                    } 
                                }                            
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="labselect">Laboratorio</label> 
                        <select name="labselect" id="labselect"><?php 				
                            $laboratorios	= DataManager::getLaboratorios(); 
                            if (count($laboratorios)) {	
                                foreach ($laboratorios as $k => $lab) {
                                    $idLab			=	$lab["idLab"];
                                    $descripcion	=	$lab["Descripcion"];
                                    if ($idLab == $laboratorio){ ?>         
                                        <option id="<?php echo $idLab; ?>" value="<?php echo $idLab; ?>" selected><?php echo $descripcion; ?></option><?php						
                                    }
                                }                            
                            } ?>
                        </select>
                    </div>
                    
                   	<div class="bloque_5">  
                        <label>Condici&oacute;n de pago</label>
                        <select name="condselect" id="condselect"> <?php
                            $condicionesPago	=	DataManager::getCondicionesDePago(0, 0, 1); 
                            if (count($condicionesPago)) {	
                                foreach ($condicionesPago as $k => $cond) {		
									$idCond		= $cond['condid'];	
									$condCodigo	= $cond['IdCondPago'];
									$condNombre	=  	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $cond['condtipo']);			
									$condDias	= "(";					
									$condDias	.= empty($cond['Dias1CP']) ? '' : $cond['Dias1CP'];
									$condDias	.= empty($cond['Dias2CP']) ? '' : ', '.$cond['Dias2CP'];
									$condDias	.= empty($cond['Dias3CP']) ? '' : ', '.$cond['Dias3CP'];
									$condDias	.= empty($cond['Dias4CP']) ? '' : ', '.$cond['Dias4CP'];
									$condDias	.= empty($cond['Dias5CP']) ? '' : ', '.$cond['Dias5CP'];
									$condDias	.= " D&iacute;as)";					
									$condPorc	= ($cond['Porcentaje1CP']== '0.00') ? '' : $cond['Porcentaje1CP'];
									
                                    //Descarto la opción FLETERO
                                    if(trim($condNombre) != "FLETERO"){
                                        if($condPago == $condCodigo){ ?>                        		
                                            <option id="<?php echo $idCond; ?>" value="<?php echo $condCodigo; ?>" selected><?php echo $condNombre." - ".$condDias." - [".$condPorc."%]"; ?></option><?php							
                                        } 
                                    }
                                }				  
                            } ?>
                        </select>
                    </div>
                </fieldset>        
            </form>                         
        </div> <!-- FIN box_body-->	
        
        <div class="box_seccion">
            <div class="barra">
                <div class="buscadorizq">
                    <h1>Cuentas Cadenas</h1>                	
                </div>
                <div class="buscadorder">
                	<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
                    <input id="txtBuscarEn" type="text" value="tblTablaCta" hidden/>
                </div> 
                <hr>     
            </div> <!-- Fin barra -->            
            <div class="lista">  <?php
				$cuentasCadena	= DataManager::getCuentasCadena($empresa, NULL, $idCuenta);
				if(count($cuentasCadena)){
					foreach ($cuentasCadena as $k => $ctaCad) {
						$ctaIdCadena	=	$ctaCad["IdCadena"];
					}	
					
					$cuentas	= DataManager::getCuentasCadena($empresa, $ctaIdCadena, NULL);
					if (count($cuentas)) {
						echo '<table id="tblTablaCta" border="0" width="100%" align="center" style="table-layout:fixed;">';
						echo '<thead><tr align="left"><th>Cuenta</th><th>Nombre</th><th>Localidad</th></tr></thead>';
						echo '<tbody>';
						
						foreach ($cuentas as $k => $cta) {
							$idCuentaCad 	= 	$cta["IdCliente"];
							$nombre			= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuentaCad, $empresa);
							$idLoc			= 	DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $idCuentaCad, $empresa);
							$localidad		=	(empty($idLoc) || $idLoc == 0)	? DataManager::getCuenta('ctalocalidad', 'ctaidcuenta', $idCuentaCad, $empresa) :	DataManager::getLocalidad('locnombre', $idLoc);
							
							((($k % 2) == 0)? $clase="par" : $clase="impar");

							echo "<tr class=".$clase." onclick=\"javascript:dac_cargarCuentaCadena('$idCuentaCad', '$nombre', '$observacion', 0)\" style=\"cursor:pointer\"><td>".$idCuentaCad."</td><td>".$nombre."</td><td>".$localidad."</td></tr>";
							
							if($idCuentaCad == $idCuenta) {
								echo "<script>";
								echo "javascript:dac_cargarCuentaCadena('$idCuentaCad', '$nombre', '$observacion', 1)";
								echo "</script>";
							}
						}	
						echo '</tbody></table>';
					}
				} else { 
					echo '<table border="0" width="100%"><tr><td align="center">No hay registros activos.</td></tr></table>';
				} ?>
            </div> <!-- Fin lista -->			
        </div> <!-- FIN box_seccion-->	
        <hr>
	</main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->            
        
</body>
</html>



