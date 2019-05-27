<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$drogtId		= empty($_REQUEST['drogtid']) ? 0 : $_REQUEST['drogtid']; //ID de la droguería
$drogueriaCAD	= empty($_REQUEST['drogid']) ? 0 : $_REQUEST['drogid']; //ID de la droguería CAD
$empresa		= empty($_REQUEST['empresa']) ? 0 : $_REQUEST['empresa'];

if ($drogtId) {
	$objectDrog 	= DataManager::newObjectOfClass('TDrogueria', $drogtId);
	$id			 	= $objectDrog->__get('ID');
	$empresa		= $objectDrog->__get('IDEmpresa');
	$cuenta			= $objectDrog->__get('Cliente');
	
	$correotransfer	= $objectDrog->__get('CorreoTransfer');
	$tipotransfer	= $objectDrog->__get('TipoTransfer');
	$rentTl 		= $objectDrog->__get('RentabilidadTL');
	$rentTd 		= $objectDrog->__get('RentabilidadTD');
	$cadId 			= $objectDrog->__get('CadId');
	
	$drogueriasCAD	= DataManager::getDrogueriaCAD('', $empresa, $cadId);
	if (count($drogueriasCAD)) {
		foreach ($drogueriasCAD as $k => $drogCAD) {
			$nombreCAD	= $drogCAD['dcadNombre'];
		}
	}
} else {
	$id				= "";
	$cuenta			= "";
	$correotransfer	= "";
	$tipotransfer	= "";
	$rentTl 		= "";
	$rentTd 		= "";
	
	
	if($drogueriaCAD){
		$drogueriasCAD	= DataManager::getDrogueriaCAD('', $empresa, $drogueriaCAD);
		if (count($drogueriasCAD)) {
			foreach ($drogueriasCAD as $k => $drogCAD) {
				$cadId		= $drogCAD['dcadId'];
				$nombreCAD	= $drogCAD['dcadNombre'];
			}
		}	
	} else {
		$cadId 		= "";
		$empresa	= "";	
		$nombreCAD	= "";
	}
	
} ?>

<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>
<body>
	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
        $_section 		= 'droguerias';
        $_subsection	= 'nueva_drogueria';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>
     
    <main class="cuerpo">
    	<div class="box_body">
			<form id="fmDrogueria" name="fmDrogueria" method="post">
				<fieldset>
					<legend>Droguer&iacute;a</legend>
					<div class="bloque_1">
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
					<div class="bloque_1">
						<?php $urlSend	=	'/pedidos/droguerias/logica/update.drogueria.php';?>
						<a id="btnSend" title="Enviar"> 
							<img class="icon-send" onclick="javascript:dac_sendForm(fmDrogueria, '<?php echo $urlSend;?>');"/>
						</a>
					</div>
					
					<div class="bloque_1">
						<label for="empresa">Empresa</label>                        
						<select id="empresa" name="empresa"> <?php
							$empresas	= DataManager::getEmpresas(1); 
							if (count($empresas)) {	
								foreach ($empresas as $k => $emp) {
									$idEmp		=	$emp["empid"];
									$nombreEmp	=	$emp["empnombre"];
									if ($idEmp == $empresa){ ?>                        		
										<option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>" selected><?php echo $nombreEmp; ?></option><?php
									} else { ?>
										<option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>"><?php echo $nombreEmp; ?></option><?php
									}   
								}                            
							} ?>
						</select>
					</div>

					<div class="bloque_7">
						<label for="drogid">Drogueria</label>
						<input type="text" id="drogid" name="drogid" readonly value="<?php echo $cadId; ?>">	
					</div>

					<div class="bloque_5">
						<label for="nombre">Nombre</label>	
						<input type="text" id="nombre" name="nombre" class="text-uppercase" value="<?php echo $nombreCAD; ?>" >	
					</div>
				
					<div class="bloque_7">
						<label for="drogtcliid">Cuenta Asociada</label>
						<input name="drogtcliid" id="drogtcliid" type="text" maxlength="10" value="<?php echo @$cuenta;?>">
					</div>
										
					<div class="bloque_1">
						Los pedidos transfers se enviar&aacute;n filtrando por iguales destinos de correo. Controle el correcto ingreso de &eacute;ste campo.
					</div>
					<div class="bloque_5">
						<label for="drogtcorreotrans">Correo Transfer</label>
						<input name="drogtcorreotrans" id="drogtcorreotrans" type="text" maxlength="50" value="<?php echo @$correotransfer;?>"> 
					</div>                        
					<div class="bloque_7">
						<label for="drogttipotrans">Tipo Transfer</label>
						<select id="drogttipotrans" name="drogttipotrans"> 
							<option id="0" value="0" <?php if(!$tipotransfer) { echo "selected";} ?>></option> 		
							<option id="A" value="A" <?php if($tipotransfer == "A"){ echo "selected";} ?>>A</option>
							<option id="B" value="B" <?php if($tipotransfer == "B"){ echo "selected";} ?>>B</option>
							<option id="C" value="C" <?php if($tipotransfer == "C"){ echo "selected";} ?>>C</option>
							<option id="D" value="D" <?php if($tipotransfer == "D"){ echo "selected";} ?>>D</option>
						</select>
					</div>
					
					<div class="bloque_8">
						<label for="rentTl">Rent. TL</label>
						<input id="rentTl" name="rentTl" type="text" value="<?php echo @$rentTl;?>" maxlength="10" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);"/>
					</div>
					
					<div class="bloque_8">
						<label for="rentTd">Rent. TD</label>
						<input id="rentTd" name="rentTd" type="text" value="<?php echo @$rentTd;?>" maxlength="10" onkeydown="ControlComa(this.id, this.value);" onkeyup="ControlComa(this.id, this.value);"
					</div>
					
					<input type="hidden" name="drogtid" value="<?php echo @$id;?>" >
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