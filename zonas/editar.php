<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}
 
$zId	= empty($_REQUEST['zid']) ? 0 : $_REQUEST['zid'];
if ($zId) {
	$zObject 	= DataManager::newObjectOfClass('TZonas', $zId);
	$zZona 		= $zObject->__get('Zona');
	$zNombre 	= $zObject->__get('Nombre');		 
	$usrAssigned= $zObject->__get('UsrAssigned');
} else {
	$zZona	 	= "";
	$zNombre	= "";
	$usrAssigned= '';
} ?>

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
        $_section 		= 'zonas';
        $_subsection	= 'nueva_zona';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
        
    <main class="cuerpo">
    	<div class="box_body">					
			<form name="fmZona" class="fm_edit2" method="post">
				<input type="hidden" name="zid" value="<?php echo @$zId; ?>" />
				<fieldset>
					<legend>Zona</legend>
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
						<label for="zzona">Zona</label>
						<input name="zzona" type="text" maxlength="3" value="<?php echo @$zZona;?>"/>
					</div> 
					<div class="bloque_1">
						<label for="znombre">Nombre</label>
						<input type="text" name="znombre" maxlength="30" value="<?php echo @$zNombre;?>"/>
					</div>

					<div class="bloque_2">
						<label for="asignado">Asignado a</label>
						<select name="asignado">   
							<option id="0" value="0" selected></option> <?php
							$vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
							if (count($vendedores)) {	
								foreach ($vendedores as $k => $vend) {
									$idVend		=	$vend["uid"];
									$nombreVend	=	$vend['unombre'];
									if ($idVend ==  $usrAssigned){ ?>                        		
										<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>" selected><?php echo $nombreVend; ?></option><?php
									} else { ?>
										<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>"><?php echo $nombreVend; ?></option><?php
									}
								}                            
							} ?>
						</select>
					</div>
					
					<div class="bloque_4">
						<?php $urlSend	=	'/pedidos/zonas/logica/update.zona.php';?>
						<a id="btnSend" title="Enviar" style="cursor:pointer;"> 
							<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(fmZona, '<?php echo $urlSend;?>');"/>
						</a>
					</div>
				</fieldset>		
			</form>			
		</div> <!-- boxbody -->	
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
			
</body>
</html>