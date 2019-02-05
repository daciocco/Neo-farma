<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }

 $_relid	= empty($_REQUEST['relid']) ? 0 : $_REQUEST['relid'];
 
 if ($_relid) {
	$_rel 		= DataManager::newObjectOfClass('TRelevamiento', $_relid);
	$_nro		= $_rel->__get('Relevamiento');
	$_orden	 	= $_rel->__get('Orden');
	$_nulo	 	= $_rel->__get('Nulo');
	$_pregunta	= $_rel->__get('Pregunta');
	$_tipo		= $_rel->__get('Tipo');
 } else {
	$_nro 		= "";
	$_orden 	= "";
	$_nulo	 	= 0;
	$_pregunta	= "";
	$_tipo 		= "";
 }
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>  
    <script type="text/javascript" src="jquery/jquery.enviar.js"></script>
    
</head>
<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php
        $_section 	= 'relevamientos';
        $_subsection	= 'nuevo_relevamiento';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
        
    <main class="cuerpo">
    	<div class="box_body">       				
			<form id="fm_rel_edit" name="fm_rel_edit" class="fm_edit2" method="post">
				<fieldset>
					<legend>Relevamiento</legend>
					<div class="bloque_4">
						<label for="nro">N&uacute;mero *</label>
						<input type="text" name="nro" id="nro" maxlength="2" value="<?php echo @$_nro;?>"/>
					</div>

					<div class="bloque_4">
						<label for="orden">Orden</label>
						<input type="text" name="orden" id="orden"  maxlength="2" value="<?php echo @$_orden;?>"/>
					</div>

					<div class="bloque_1">
						<label for="tipo">Tipo Respuesta</label>
						<select id="tipo" name="tipo"/> 
							<option value="" <?php if(empty($_tipo)){echo 'selected="selected"';}?>></option>
							<option value="abierto" <?php if($_tipo=="abierto"){echo 'selected="selected"';}?>>Abierto</option>
							<option value="sino" <?php if($_tipo=="sino"){echo 'selected="selected"';}?>>Si/No</option>
							<option value="cant" <?php if($_tipo=="cant"){echo 'selected="selected"';}?>>Cantidad</option>
							<!--option value="multiopcion">M&uacute;ltiples Opciones</option>
							<option value="unicaopcion">&Uacute;nica Opci&oacute;n</option-->
						</select>                                    
					</div>

					<div class="bloque_2">
						<label for="nulo" >Admite Nulos </label></br>   
						<input id="nulo" name="nulo" type="checkbox" <?php if($_nulo){echo "checked=checked";};?> />                                                     
					</div>

					<div class="bloque_3" align="center">
						<label for="pregunta">Pregunta</label>
						<textarea id="pregunta" name="pregunta" value="<?php echo $_pregunta;?>" style="resize:none;" onKeyUp="javascript:dac_LimitaCaracteres(event, 'pregunta', 200);" onKeyDown="javascript:dac_LimitaCaracteres(event, 'pregunta', 200);"/><?php echo $_pregunta;?></textarea>
						</br>
						<fieldset id='box_informacion' class="msg_informacion">
							<div id="msg_informacion" align="center"></div> 
						</fieldset>
					</div>

					<div class="bloque_3">     
						<fieldset id='box_error' class="msg_error">          
							<div id="msg_error" align="center"></div>
						</fieldset>

						<fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;">                                                	<div id="msg_cargando" align="center"></div>      
						</fieldset> 

						<fieldset id='box_confirmacion' class="msg_confirmacion">
							<div id="msg_confirmacion" align="center"></div>      
						</fieldset>
					</div>

					<input type="hidden" id="relid" name="relid" value="<?php echo @$_relid;?>" />

					<div class="bloque_2">
						<input id="btsend" type="button" value="Enviar" title="Enviar Relevamiento"/>   
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