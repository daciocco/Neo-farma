<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$condId	=	empty($_GET['condid']) ? 0 : $_GET['condid'];
if ($condId) {
	$condicion		= DataManager::newObjectOfClass('TCondicionComercial', $condId);
	$empresa 		= $condicion->__get('Empresa');
	$laboratorio	= $condicion->__get('Laboratorio');	
	$cuentas		= $condicion->__get('Cuentas');
	$nombre 		= $condicion->__get('Nombre');
	$tipo	 		= $condicion->__get('Tipo');
	$condPago 		= $condicion->__get('CondicionPago');
	$cantMinima		= ($condicion->__get('CantidadMinima')) ? $condicion->__get('CantidadMinima') : '';
	$minReferencias	= ($condicion->__get('MinimoReferencias')) ? $condicion->__get('MinimoReferencias') : '';
	$minMonto		= ($condicion->__get('MinimoMonto') == '0.000') ? '' : $condicion->__get('MinimoMonto');
	
	$fechaInicio	= dac_invertirFecha( $condicion->__get('FechaInicio'));
	$fechaFin 		= dac_invertirFecha($condicion->__get('FechaFin'));	
	$observacion	= $condicion->__get('Observacion');
	
	$habitualCant	= ($condicion->__get('Cantidad') == '0') ? '' : $condicion->__get('Cantidad');
	$habitualBonif1 = ($condicion->__get('Bonif1') == '0') ? '' : $condicion->__get('Bonif1');
	$habitualBonif2 = ($condicion->__get('Bonif2') == '0') ? '' : $condicion->__get('Bonif2');
	$habitualDesc1	= ($condicion->__get('Desc1') == '0.00') ? '' : $condicion->__get('Desc1');
	$habitualDesc2	= ($condicion->__get('Desc2') == '0.00') ? '' : $condicion->__get('Desc2');
} else {
	$empresa 		= 1;
	$laboratorio	= 1;	
	$cuentas		= "";
	$nombre 		= "";
	$tipo	 		= "";
	$cantMinima		= "";
	$minReferencias	= "";
	$minMonto		= "";
	$condPago 		= "";
	$fechaInicio	= "";
	$fechaFin 		= "";	
	$observacion	= "";	
	$habitualCant	= "";
	$habitualBonif1 = "";
	$habitualBonif2 = "";
	$habitualDesc1	= "";
	$habitualDesc2	= "";
} 

$btnAltasCondiciones =	sprintf("<input type=\"button\" id=\"btAltaCondiciones\" value=\"Actualizar Alta Condiciones\" title=\"Actualizar Alta en Condiciones Comerciales\"/>");
?>

<!doctype html>
<html xml:lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?> 
   
    <script src="logica/jquery/jqueryHeaderEdit.js"></script>
</head>

<body>	
    <header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
       	$_section	=	'condiciones';
        $_subsection 	=	'';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
	</nav>
    
    <main class="cuerpo">
    	<form id="fm_condicion_edit" name="fm_condicion_edit" method="POST">
			<div class="box_body"> 
				<input type="text" id="condid" name="condid" value="<?php echo $condId;?>" hidden="hidden"/>
				<fieldset>
					<legend>Condici&oacute;n Comercial</legend>
					<div class="bloque_1">  
						<fieldset id='box_observacion' class="msg_alerta">
							<div id="msg_atencion" align="center"></div>       
						</fieldset>
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

					<div class="bloque_5">
						<label for="empselect">Empresa</label> 
						<select id="empselect" name="empselect" onchange="javascript:dac_changeEmpresa(this.value, labselect.value);"><?php
							$empresas	= DataManager::getEmpresas(1); 
							if (count($empresas)) {	
								foreach ($empresas as $k => $_emp) {
									$_idemp		=	$_emp["empid"];
									$_nombreemp	=	$_emp["empnombre"];	
									?><option id="<?php echo $_idemp; ?>" value="<?php echo $_idemp; ?>" <?php if ($empresa == $_idemp){ echo "selected"; } ?>><?php echo $_nombreemp; ?></option><?php
								} 
								echo "<script>";
								echo "javascript:dac_changeEmpresa($empresa, $laboratorio)";
								echo "</script>";                           
							} ?>
						</select>
					</div>

					<div class="bloque_5"> 
						<label for="labselect">Laboratorio</label> 
						<select id="labselect" name="labselect" onchange="javascript:dac_changeLaboratorio(empselect.value, this.value);"><?php				
							$laboratorios	= DataManager::getLaboratorios(); 
							if (count($laboratorios)) {	
								foreach ($laboratorios as $k => $lab) {
									$idLab		=	$lab["idLab"];
									$nombreLab	=	$lab["Descripcion"];
									?><option id="<?php echo $idLab; ?>" value="<?php echo $idLab; ?>" <?php if ($laboratorio == $idLab){ echo "selected"; }?> ><?php echo $nombreLab; ?></option><?php
								}    
								echo "<script>";
								echo "javascript:dac_changeLaboratorio($empresa, $laboratorio)";
								echo "</script>";                        
							} ?>
						</select>
					</div>

					<div class="bloque_5"> 
						<label for="tiposelect">Tipo</label>
						<select id="tiposelect" name="tiposelect"> 
							<option id=0 value=0 		<?php if ($tipo == 0){ echo "selected"; } ?>></option> 
							<option id=1 value='Pack' 	<?php if ($tipo == 'Pack'){ echo "selected"; } ?>>Pack</option> 
							<option id=2 value='ListaEspecial' 		<?php if ($tipo == 'ListaEspecial'){ echo "selected"; } ?>>Lista Especial</option> 
							<option id=3 value='CondicionEspecial' 	<?php if ($tipo == 'CondicionEspecial'){ echo "selected"; } ?>>Condici&oacute;n Especial</option> 
							<option id=4 value='Propuesta' 			<?php if ($tipo == 'Propuesta'){ echo "selected"; } ?>>Propuesta</option>
							<option id=5 value='Bonificacion'		<?php if ($tipo == 'Bonificacion'){ echo "selected"; } ?>>Bonificaci&oacute;n</option>
						</select>                        
					</div>

					<div class="bloque_5"> 
						<label for="nombre">Nombre</label>
						<input name="nombre" id="nombre" type="text" maxlength="50" value="<?php echo $nombre; ?>"/>
					</div>

					<div class="bloque_7"> 
						<label for="minMonto">Monto m&iacute;nimo</label>
						<input name="minMonto" id="minMonto" type="text" maxlength="10" value="<?php echo $minMonto;?>"/>
					</div>

					<div class="bloque_7"> 
						<label for="cantMinima">Cantidad M&iacute;nima</label>
						<input name="cantMinima" id="cantMinima" type="text" maxlength="10" value="<?php echo $cantMinima;?>"/>
					</div>

					<div class="bloque_7"> 
						<label for="minReferencias">Min de Referencias</label>
						<input name="minReferencias" id="minReferencias" type="text" maxlength="5" value="<?php echo $minReferencias;?>"/>
					</div>

					<div class="bloque_7"> 
						<label for="fechaInicio">Fecha Inicio</label>
						<input name="fechaInicio" id="fechaInicio" type="text" value="<?php echo $fechaInicio;?>" readonly/>
					</div>

					<div class="bloque_7">
						<label for="fechaFin">Fecha Fin</label>
						<input name="fechaFin" id="fechaFin" type="text" value="<?php echo $fechaFin;?>" readonly/>
					</div>

					<?php if($tipo == 'Bonificacion') { ?>                   
						<div class="bloque_6"> <br><?php echo $btnAltasCondiciones; ?> </div>
					<?php } ?> 

					<div class="bloque_1">
						<label for="observacion">Observaci&oacute;n</label>
						<textarea name="observacion" id="observacion" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'observacion', 200);" onkeydown="javascript:dac_LimitaCaracteres(event, 'observacion', 200);" value="<?php echo $observacion;?>"/><?php echo $observacion;?></textarea> 
						
						<fieldset id='box_informacion' class="msg_informacion">
							<div id="msg_informacion" align="center"></div> 
						</fieldset> 
					</div>

					<div class="bloque_7">
						<?php $_url = '/pedidos/condicion/logica/update.condicion.php';?>
						<?php $_urlBack	= '/pedidos/condicion/';?>
						<input id="btsend" type="button" value="Enviar" title="Enviar"/> 
					</div>  
				</fieldset>

				<fieldset>
					<legend>Condici&oacute;n de Pago</legend>
					<div id="detalle_condpago"></div>
					<?php
					if ($condId) {
						if(!empty($condPago)){
							$condicionesPago = explode(",", $condPago);						
							foreach ($condicionesPago as $condicionPago) {	

								$condicionesPago	=	DataManager::getCondicionesDePago(0, 0, NULL, $condicionPago); 
								if (count($condicionesPago)) { 
									foreach ($condicionesPago as $k => $condPago) {	
										$condPagoNombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);				
										$condPagoDias	= "(";					
										$condPagoDias	.= empty($condPago['Dias1CP']) ? '' : $condPago['Dias1CP'];
										$condPagoDias	.= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
										$condPagoDias	.= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
										$condPagoDias	.= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
										$condPagoDias	.= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
										$condPagoDias	.= " D&iacute;as)";
									}
								}
								echo "<script>";
								echo "javascript:dac_cargarCondicionPago('".$condicionPago."', '".$condPagoNombre."', '".$condPagoDias."')";
								echo "</script>";
							}
						}
					} ?>
				</fieldset>


				<fieldset>
					<legend>Condici&oacute;n Habitual</legend>
					<div class="bloque_8">
						<label for="habitualCant">Cantidad</label>
						<input name="habitualCant" id="habitualCant" type="text" maxlength="2" value="<?php echo $habitualCant; ?>"/>
					</div>
					<div class="bloque_8">
						<label for="habitualBonif1">B1</label>
						<input name="habitualBonif1" id="habitualBonif1" type="text" maxlength="2" value="<?php echo $habitualBonif1; ?>"/>
					</div>
					<div class="bloque_8">
						<label for="habitualBonif2">B2</label>
						<input name="habitualBonif2" id="habitualBonif2" type="text" maxlength="2" value="<?php echo $habitualBonif2; ?>"/>
					</div>
					<div class="bloque_8">
						<label for="habitualDesc1">D1</label>
						<input name="habitualDesc1" id="habitualDesc1" type="text" maxlength="5" value="<?php echo $habitualDesc1; ?>" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onKeyUp="javascript:ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);"/>
					</div>
					<div class="bloque_8">
						<label for="habitualDesc2">D2</label>
						<input name="habitualDesc2" id="habitualDesc2" type="text" maxlength="5" value="<?php echo $habitualDesc2; ?>" onkeydown="ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);" onKeyUp="javascript:ControlComa(this.id, this.value); dac_ControlNegativo(this.id, this.value);"/>
					</div>
				</fieldset>

				<fieldset>
					<legend>Cuentas</legend> 
					<div id="detalle_cuenta"></div>
				</fieldset> 
				<?php
				if ($condId) {
					if(!empty($cuentas)){
						$cuentasCondiciones = explode(",", $cuentas);
						foreach ($cuentasCondiciones as $ctaCond) {							
							$ctaCondIdCta	= 	DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaCond, $empresa);					
							$ctaCondNombre	= 	DataManager::getCuenta('ctanombre', 'ctaid', $ctaCond, $empresa);									
							echo "<script>";
							echo "javascript:dac_cargarCuentaCondicion('".$ctaCond."', '".$ctaCondIdCta."', '".$ctaCondNombre."')";
							echo "</script>";
						}
					}
				} ?> 
			</div> <!-- END box_body -->   

			<div class="box_seccion"> 
				<div class="barra">
				   <div class="buscadorizq">
						<h2>Cuentas</h2>  
				   </div>
					<div class="buscadorder">               
						<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
						<input id="txtBuscarEn" type="text" value="tblCuentas" hidden/>
					</div>
					<hr>
				</div> <!-- Fin barra -->  

				<div class="lista">
					<div id='tablacuentas'></div>
				</div> <!-- Fin listar -->	             

				<div class="barra">
					<div class="buscadorizq">
						<h2>Art&iacute;culos</h2> 
					</div>
					<div class="buscadorder">
						<input id="txtBuscar2" type="search" autofocus placeholder="Buscar..."/>
						<input id="txtBuscarEn2" type="text" value="tblArticulos" hidden/>
					</div>
					<hr>
				</div> <!-- Fin barra -->    
				<div class="lista">
					<div id='tablaarticulos'></div>
				</div> <!-- fin lista --> 

				<div class="barra">
					<div class="buscadorizq">
						<h2>Condiciones de Pago</h2> 
					</div>
					<div class="buscadorder">
						<input id="txtBuscar3" type="search" autofocus placeholder="Buscar..."/>
						<input id="txtBuscarEn3" type="text" value="tblCondicionesPago" hidden/>
					</div>
					<hr>
				</div> <!-- Fin barra --> 
				<div class="lista"><?php
					$condicionesDePago	= DataManager::getCondicionesDePago(0,0,1);
					if (count($condicionesDePago)) {	
						echo '<table id="tblCondicionesPago" class="datatab" width="100%" border="0" align="center" style=\"table-layout:fixed\">';
						echo '<thead><tr align="left"><th>Cod</th><th>Nombre</th><th>D&iacute;as</th></tr></thead>';
						echo '<tbody>';
						foreach ($condicionesDePago as $k => $condDePago) {	
							$condCod	= $condDePago['IdCondPago'];
							$nombre		= DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condDePago['condtipo']);
							$dias		= "(";					
							$dias		.= empty($condDePago['Dias1CP']) ? '' : $condDePago['Dias1CP'];
							$dias		.= empty($condDePago['Dias2CP']) ? '' : ', '.$condDePago['Dias2CP'];
							$dias		.= empty($condDePago['Dias3CP']) ? '' : ', '.$condDePago['Dias3CP'];
							$dias		.= empty($condDePago['Dias4CP']) ? '' : ', '.$condDePago['Dias4CP'];
							$dias		.= empty($condDePago['Dias5CP']) ? '' : ', '.$condDePago['Dias5CP'];
							$dias		.= " D&iacute;as)";

							((($k % 2) == 0)? $clase="par" : $clase="impar");

							if($condCod != 0){
								echo "<tr id=condPago".$condCod." class=".$clase." style=\"cursor:pointer;\" onclick=\"javascript:dac_cargarCondicionPago('$condCod', '$nombre', '$dias')\">";
								echo "<td>".$condCod."</td><td>".$nombre."</td><td>".$dias."</td>";
								echo "</tr>";	
							}
						}
						echo "</tbody></table>";
					} else { 
						echo 	'<table border="0" width="100%"><thead><tr><th align="center">No hay registros activos.</th></tr></thead></table>'; exit;
					} ?>
				</div> <!-- fin lista --> 
			</div> <!-- Fin box_seccion --> 
			<hr>

			<div class="box_down">
				<fieldset>
					<legend>Art&iacute;culos</legend> 
					<div id="detalle_articulo"></div>
				</fieldset>
				<?php
				if ($condId) {	
					$articulosCond		= DataManager::getCondicionArticulos($condId);
					if (count($articulosCond)) {								 
						foreach ($articulosCond as $k => $artCond) {	
							$artCond 		= $articulosCond[$k];
							$condArtId		= $artCond['cartid'];
							$condArtIdArt	= $artCond['cartidart'];	         
							$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, $empresa, $laboratorio);
							// --> precio original de la tabla artículos
							$condArtPrecio	= $artCond["cartprecio"];
							// --> precio de Lista
							$condArtMedicinal= DataManager::getArticulo('artmedicinal', $condArtIdArt, $empresa, $laboratorio);
							$condArtIva		 = DataManager::getArticulo('artiva', $condArtIdArt, $empresa, $laboratorio);
							$condArtGanancia = DataManager::getArticulo('artganancia', $condArtIdArt, $empresa, $laboratorio);
							//--> precio digitado o precio de venta.
							$condArtPrecioDigit	= ($artCond["cartpreciodigitado"] == '0.000')?	''	:	$artCond["cartpreciodigitado"];                     
							$condArtCantMin	= empty($artCond['cartcantmin'])?	''	:	$artCond['cartcantmin'];
							$condArtOAM		= $artCond["cartoam"];
							echo "<script>";
							echo "javascript:dac_cargarArticuloCondicion('".$condArtId."', '".$condArtIdArt."', '".$condArtNombre."', '".$condArtPrecio."', '".$condArtCantMin."', '".$condArtPrecioDigit."', '".$condArtOAM."', '".$condArtMedicinal."', '".$condArtIva."', '".$empresa."', '".$condArtGanancia."')";
							echo "</script>";

							//Controlo si tiene Bonificaciones para cargar
							$articulosBonif	= DataManager::getCondicionBonificaciones($condId, $condArtIdArt);
							if (count($articulosBonif)) {								 
								foreach ($articulosBonif as $j => $artBonif) {	
									$artBonifId		= empty($artBonif['cbid'])		?	''	:	$artBonif['cbid'];
									$artBonifCant	= empty($artBonif['cbcant'])	?	''	:	$artBonif['cbcant'];
									$artBonifB1		= empty($artBonif['cbbonif1'])	?	''	:	$artBonif['cbbonif1'];
									$artBonifB2		= empty($artBonif['cbbonif2'])	?	''	:	$artBonif['cbbonif2'];
									$artBonifD1		= empty($artBonif['cbdesc1'])	?	''	:	$artBonif['cbdesc1'];
									$artBonifD2		= empty($artBonif['cbdesc2'])	?	''	:	$artBonif['cbdesc2'];

									echo "<script>";
									echo "javascript:dac_addBonificacion('".($k+1)."', '".$condArtId."', '".$condArtIdArt."', '".$artBonifCant."', '".$artBonifB1."', '".$artBonifB2."', '".$artBonifD1."', '".$artBonifD2."', '".$artBonifId."')";
									echo "</script>";				
								}
							}						
						}
					}
				} ?>                                       
			</div> <!-- Fin box_down --> 
        </form>
        <hr>
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->    
</body>
</html>

<script src="logica/jquery/jqueryFooterEdit.js"></script>