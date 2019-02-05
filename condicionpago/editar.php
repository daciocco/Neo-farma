<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_condid	= empty($_REQUEST['condid']) ? 0 : $_REQUEST['condid'];
$readOnly = '';
 
if ($_condid) {
	$_condcion 		= DataManager::newObjectOfClass('TCondicionPago', $_condid);
	$_condcodigo 	= $_condcion->__get('Codigo');
	$_condtipo	 	= $_condcion->__get('Tipo');
	$_condtipo1	 	= $_condcion->__get('Tipo1');
	$_condtipo2	 	= $_condcion->__get('Tipo2');
	$_condtipo3	 	= $_condcion->__get('Tipo3');
	$_condtipo4	 	= $_condcion->__get('Tipo4');
	$_condtipo5	 	= $_condcion->__get('Tipo5');
	$_conddias	 	= ($_condcion->__get('Dias') == '0') ? '' : $_condcion->__get('Dias');
	$_conddias2	 	= ($_condcion->__get('Dias2') == '0') ? '' : $_condcion->__get('Dias2');
	$_conddias3	 	= ($_condcion->__get('Dias3') == '0') ? '' : $_condcion->__get('Dias3');
	$_conddias4	 	= ($_condcion->__get('Dias4') == '0') ? '' : $_condcion->__get('Dias4');
	$_conddias5	 	= ($_condcion->__get('Dias5') == '0') ? '' : $_condcion->__get('Dias5');
	$_condporcentaje= ($_condcion->__get('Porcentaje') == '0.00') ? '' : $_condcion->__get('Porcentaje');
	$_condporcentaje2= ($_condcion->__get('Porcentaje2') == '0.00') ? '' : $_condcion->__get('Porcentaje2');
	$_condporcentaje3= ($_condcion->__get('Porcentaje3') == '0.00') ? '' : $_condcion->__get('Porcentaje3');
	$_condporcentaje4= ($_condcion->__get('Porcentaje4') == '0.00') ? '' : $_condcion->__get('Porcentaje4');
	$_condporcentaje5= ($_condcion->__get('Porcentaje5') == '0.00') ? '' : $_condcion->__get('Porcentaje5');
	$_condsigno		= $_condcion->__get('Signo');
	$_condsigno2	= $_condcion->__get('Signo2');
	$_condsigno3	= $_condcion->__get('Signo3');
	$_condsigno4	= $_condcion->__get('Signo4');
	$_condsigno5	= $_condcion->__get('Signo5');		
	$_condfechadec	= ($_condcion->__get('FechaFinDec') == '2001-01-01') ? '' : $_condcion->__get('FechaFinDec');
	$_condfechadec2	= ($_condcion->__get('FechaFinDec2') == '2001-01-01') ? '' : $_condcion->__get('FechaFinDec2');
	$_condfechadec3	= ($_condcion->__get('FechaFinDec3') == '2001-01-01') ? '' : $_condcion->__get('FechaFinDec3');
	$_condfechadec4	= ($_condcion->__get('FechaFinDec4') == '2001-01-01') ? '' : $_condcion->__get('FechaFinDec4');
	$_condfechadec5	= ($_condcion->__get('FechaFinDec5') == '2001-01-01') ? '' : $_condcion->__get('FechaFinDec5');

	$_conddecrece	= $_condcion->__get('Decrece');

	$_condcuotas	= ($_condcion->__get('Cantidad') == 0) ? '' : $_condcion->__get('Cantidad');
	$_condactiva	= $_condcion->__get('Activa');
 } else {
	$_condcodigo	= "";
	$_condtipo		= "";
	$_condtipo1		= "";
	$_condtipo2		= "";
	$_condtipo3		= "";
	$_condtipo4		= "";
	$_condtipo5		= "";
	$_conddias	 	= "";
	$_conddias2	 	= "";
	$_conddias3	 	= "";
	$_conddias4	 	= "";
	$_conddias5	 	= "";
	$_condporcentaje= "";
	$_condporcentaje2= "";
	$_condporcentaje3= "";
	$_condporcentaje4= "";
	$_condporcentaje5= "";
	$_condsigno		=	"";
	$_condsigno2	=	"";
	$_condsigno3	=	"";
	$_condsigno4	=	"";
	$_condsigno5	=	"";
	$_condfechadec	=	"";
	$_condfechadec2	=	"";
	$_condfechadec3	=	"";
	$_condfechadec4	=	"";
	$_condfechadec5	=	"";
	$_conddecrece	= "";
	$_condcuotas	= "";
	$_condactiva 	= "";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>	
</head>
<body>
	<header class="cabecera">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
	</header><!-- cabecera -->	

	<nav class="menuprincipal"> <?php 
		$_section 	= "condiciones_pago";
        $_subsection 	= "nueva_condicion_neo";
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
	</nav>

	<main class="cuerpo">
		<div class="box_body">
			<form id="fmCondicionDePago" name="fmCondicionDePago" class="fm_edit2" method="post" action="<?php echo $_action;?>">
				<fieldset>
					<input type="hidden" name="condid" value="<?php echo @$_condid;?>"/> 
					
					<legend>Condici&oacute;n</legend>   
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
				   
					<div class="bloque_4"> 
						<label for="condcodigo">C&oacute;digo</label>
						<input name="condcodigo" type="text" value="<?php echo @$_condcodigo;?>" readonly/>
					</div> 
					<div class="bloque_1">
						<label for="condtipo">Condici&oacute;n</label>
						<?php
						$condicionesTipo	= DataManager::getCondicionesDePagoTipo(); 
						if (count($condicionesTipo)) { ?>
                        	<select name="condtipo"> 
                            	<option id="0" value="" selected></option> <?php
                                foreach ($condicionesTipo as $j => $condTipo) {	
									$tipoID		= $condTipo['ID'];	
									$tipoNombre	= $condTipo['Descripcion'];
									$selected	= ($_condtipo == $tipoID) ? "selected" : ""; ?>
									<option id="<?php echo $tipoID; ?>" value="<?php echo $tipoID; ?>" <?php echo $selected; ?>><?php echo $tipoNombre; ?> </option> <?php	
								} ?>
                        	</select>
						<?php } ?>
					</div>
					
					<div class="bloque_4"> 
						<label for="condcuotas">Cuotas</label>
						<input name="condcuotas" type="text" maxlength="2" value="<?php echo @$_condcuotas;?>"/>
					</div>
					
					<div class="bloque_4"> 
						<label for="conddecrece">Decrece</label>
						<select name="conddecrece">
							<option id="0" value="N" selected>N</option> <?php
							$selected = ($_conddecrece == 'S') ? "selected" : ""; 
							?> <option id="1" value="S" <?php echo $selected;?>>S</option>		
						</select>
					</div>
					
				  	<hr>
					
					<div class="bloque_2"> 
						<label for="condtipo1">Condici&oacute;n</label>
						<?php
						if (count($condicionesTipo)) { ?>
                        	<select name="condtipo1"> 
                            	<option id="0" value="" selected></option> <?php
                                foreach ($condicionesTipo as $j => $condTipo) {	
									$tipoID		= $condTipo['ID'];	
									$tipoNombre	= $condTipo['Descripcion'];
									$selected	= ($_condtipo1 == $tipoID) ? "selected" : ""; ?>
									<option id="<?php echo $tipoID; ?>" value="<?php echo $tipoID; ?>" <?php echo $selected; ?>><?php echo $tipoNombre; ?> </option> <?php	
								} ?>
                        	</select>
						<?php } ?>
					</div>         
					 
					<div class="bloque_4"> 
						<label for="conddias1">D&iacute;as</label>
						<?php $readOnly = (!empty($_condfechadec)) ? 'readonly="readonly"' : ''; ?>
						<input name="conddias1" type="text" maxlength="3" value="<?php echo @$_conddias;?>" <?php echo $readOnly;?>>
					</div>
					<div class="bloque_4"> 
						<label for="condporcentaje1">%</label>
						<input name="condporcentaje1" type="text" maxlength="1" value="<?php echo @$_condporcentaje;?>" />
					</div>
					<div class="bloque_4"> 
						<label for="condsigno1">Signo</label>
						<select name="condsigno1">
							<option value="" selected></option> <?php
							$selected = ($_condsigno) ? "selected" : ""; 
							?> <option value="%" <?php echo $selected;?>>%</option> 
						</select>						
					</div> 
					<div class="bloque_2"> 
						<label for="condfechadec1">Hasta el d&iacute;a</label>
						<input name="condfechadec1" id="condfechadec1" type="text" value="<?php echo @$_condfechadec;?>" readonly/>
					</div>
					
					<hr>
					<div class="bloque_2"> 
						<label for="condtipo2">Condici&oacute;n</label>
						<?php
						if (count($condicionesTipo)) { ?>
                        	<select name="condtipo2"> 
                            	<option id="0" value="" selected></option> <?php
                                foreach ($condicionesTipo as $j => $condTipo) {	
									$tipoID		= $condTipo['ID'];	
									$tipoNombre	= $condTipo['Descripcion'];
									$selected	= ($_condtipo2 == $tipoID) ? "selected" : ""; ?>
									<option id="<?php echo $tipoID; ?>" value="<?php echo $tipoID; ?>" <?php echo $selected; ?>><?php echo $tipoNombre; ?> </option> <?php	
								} ?>
                        	</select>
						<?php } ?>
					</div>          
					<div class="bloque_4"> 
						<label for="conddias2">D&iacute;as</label>
						<?php $readOnly = (!empty($_condfechadec2)) ? 'readonly="readonly"' : ''; ?>
						<input name="conddias2" type="text" maxlength="3" value="<?php echo @$_conddias2;?>" <?php echo $readOnly;?>>
					</div>
					<div class="bloque_4"> 
						<label for="condporcentaje2">%</label>
						<input name="condporcentaje2" type="text" maxlength="1" value="<?php echo @$_condporcentaje2;?>"/>
					</div>
					<div class="bloque_4"> 
						<label for="condsigno2">Signo</label>
						<select name="condsigno2">
							<option value="" selected></option> <?php
							$selected = ($_condsigno2) ? "selected" : ""; 
							?> <option value="%" <?php echo $selected;?>>%</option> 
						</select>						
					</div>
					<div class="bloque_2"> 
						<label for="condfechadec2">Hasta el d&iacute;a</label>
						<input name="condfechadec2" id="condfechadec2" type="text" value="<?php echo @$_condfechadec2;?>" readonly/>
					</div> 					
					<hr>
					
					<div class="bloque_2"> 
						<label for="condtipo3">Condici&oacute;n</label>
						<?php
						if (count($condicionesTipo)) { ?>
                        	<select name="condtipo3"> 
                            	<option id="0" value="" selected></option> <?php
                                foreach ($condicionesTipo as $j => $condTipo) {	
									$tipoID		= $condTipo['ID'];	
									$tipoNombre	= $condTipo['Descripcion'];
									$selected	= ($_condtipo3 == $tipoID) ? "selected" : ""; ?>
									<option id="<?php echo $tipoID; ?>" value="<?php echo $tipoID; ?>" <?php echo $selected; ?>><?php echo $tipoNombre; ?> </option> <?php	
								} ?>
                        	</select>
						<?php } ?>
					</div>          
					<div class="bloque_4"> 
						<label for="conddias3">D&iacute;as</label>
						<?php $readOnly = (!empty($_condfechadec3)) ? 'readonly="readonly"' : ''; ?>
						<input name="conddias3" type="text" maxlength="3" value="<?php echo @$_conddias3;?>" <?php echo $readOnly;?>>
					</div>
					<div class="bloque_4"> 
						<label for="condporcentaje3">%</label>
						<input name="condporcentaje3" type="text" maxlength="1" value="<?php echo @$_condporcentaje3;?>"/>
					</div>
					<div class="bloque_4"> 
						<label for="condsigno3">Signo</label>
						<select name="condsigno3">
							<option value="" selected></option> <?php
							$selected = ($_condsigno3) ? "selected" : ""; 
							?> <option value="%" <?php echo $selected;?>>%</option> 
						</select>						
					</div> 
					<div class="bloque_2"> 
						<label for="condfechadec3">Hasta el d&iacute;a</label>
						<input name="condfechadec3" id="condfechadec3" type="text" value="<?php echo @$_condfechadec3;?>" readonly/>
					</div>
					<hr>
					
					<div class="bloque_2"> 
						<label for="condtipo4">Condici&oacute;n</label>
						<?php
						if (count($condicionesTipo)) { ?>
                        	<select name="condtipo4"> 
                            	<option id="0" value="" selected></option> <?php
                                foreach ($condicionesTipo as $j => $condTipo) {	
									$tipoID		= $condTipo['ID'];	
									$tipoNombre	= $condTipo['Descripcion'];
									$selected	= ($_condtipo4 == $tipoID) ? "selected" : ""; ?>
									<option id="<?php echo $tipoID; ?>" value="<?php echo $tipoID; ?>" <?php echo $selected; ?>><?php echo $tipoNombre; ?> </option> <?php	
								} ?>
                        	</select>
						<?php } ?>
					</div>          
					<div class="bloque_4"> 
						<label for="conddias4">D&iacute;as</label>
						<?php $readOnly = (!empty($_condfechadec4)) ? 'readonly="readonly"' : ''; ?>
						<input name="conddias4" type="text" maxlength="3" value="<?php echo @$_conddias4;?>" <?php echo $readOnly;?>>
					</div>
					<div class="bloque_4"> 
						<label for="condporcentaje4">%</label>
						<input name="condporcentaje4" type="text" maxlength="1" value="<?php echo @$_condporcentaje4;?>"/>
					</div>
					<div class="bloque_4"> 
						<label for="condsigno4">Signo</label>
						<select name="condsigno4">
							<option value="" selected></option> <?php
							$selected = ($_condsigno4) ? "selected" : ""; 
							?> <option value="%" <?php echo $selected;?>>%</option> 
						</select>						
					</div> 
					<div class="bloque_2"> 
						<label for="condfechadec4">Hasta el d&iacute;a</label>
						<input name="condfechadec4" id="condfechadec4" type="text" value="<?php echo @$_condfechadec4;?>" readonly/>
					</div>
					<hr>	
									
					<div class="bloque_2"> 
						<label for="condtipo5">Condici&oacute;n</label>
						<?php
						if (count($condicionesTipo)) { ?>
                        	<select name="condtipo5"> 
                            	<option id="0" value="" selected></option> <?php
                                foreach ($condicionesTipo as $j => $condTipo) {	
									$tipoID		= $condTipo['ID'];	
									$tipoNombre	= $condTipo['Descripcion'];
									$selected	= ($_condtipo5 == $tipoID) ? "selected" : ""; ?>
									<option id="<?php echo $tipoID; ?>" value="<?php echo $tipoID; ?>" <?php echo $selected; ?>><?php echo $tipoNombre; ?> </option> <?php	
								} ?>
                        	</select>
						<?php } ?>
					</div>          
					<div class="bloque_4"> 
						<label for="conddias5">D&iacute;as</label>
						<?php $readOnly = (!empty($_condfechadec5)) ? 'readonly="readonly"' : ''; ?>
						<input name="conddias5" type="text" maxlength="3" value="<?php echo @$_conddias5;?>" <?php echo $readOnly;?>>
					</div>
					<div class="bloque_4"> 
						<label for="condporcentaje5">%</label>
						<input name="condporcentaje5" type="text" maxlength="1" value="<?php echo @$_condporcentaje5;?>"/>
					</div>
					<div class="bloque_4"> 
						<label for="condsigno5">Signo</label>
						<select name="condsigno5">
							<option value="" selected></option> <?php
							$selected = ($_condsigno5) ? "selected" : ""; 
							?> <option value="%" <?php echo $selected;?>>%</option> 
						</select>						
					</div> 	
					<div class="bloque_2"> 
						<label for="condfechadec5">Hasta el d&iacute;a</label>
						<input name="condfechadec5" id="condfechadec5" type="text" value="<?php echo @$_condfechadec5;?>" readonly/>
					</div>
					<hr>
												
					<?php $urlSend	=	'/pedidos/condicionpago/logica/update.condicion.php';?>
					<a id="btnSend" title="Enviar" style="cursor:pointer;"> 
						<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(fmCondicionDePago, '<?php echo $urlSend;?>');"/>
					</a>	
					
					
				</fieldset>		
			</form>	

		</div> <!-- FIN box_body -->
		<hr>
	</main> <!-- fin cuerpo -->

	<footer class="pie">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
	</footer> <!-- fin pie -->
</body>
</html>


<script language="javascript" type="text/javascript">
	new JsDatePick({
		useMode:2,
		target:"condfechadec1",
		dateFormat:"%d-%M-%Y"			
	});
	new JsDatePick({
		useMode:2,
		target:"condfechadec2",
		dateFormat:"%d-%M-%Y"			
	});
	new JsDatePick({
		useMode:2,
		target:"condfechadec3",
		dateFormat:"%d-%M-%Y"			
	});
	new JsDatePick({
		useMode:2,
		target:"condfechadec4",
		dateFormat:"%d-%M-%Y"			
	});
	new JsDatePick({
		useMode:2,
		target:"condfechadec5",
		dateFormat:"%d-%M-%Y"			
	});
</script>