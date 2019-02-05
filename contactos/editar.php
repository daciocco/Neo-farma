<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="V"  && $_SESSION["_usrrol"]!="G"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_origen		= empty($_REQUEST['origen']) 		? 	0			:	$_REQUEST['origen'];
$_origenid		= empty($_REQUEST['origenid']) 		? 	0			:	$_REQUEST['origenid'];
$_ctoid			= empty($_REQUEST['ctoid']) 		? 	0 			:	$_REQUEST['ctoid'];

if ($_ctoid) {
	$_action 		= "Guardar";
	$_proveedor		= DataManager::newObjectOfClass('TContacto', $_ctoid);
	$_origen	 	= $_proveedor->__get('Origen');
	$_sector		= $_proveedor->__get('Sector');
	$_puesto 		= $_proveedor->__get('Puesto');
	$_nombre	 	= $_proveedor->__get('Nombre');
	$_apellido	 	= $_proveedor->__get('Apellido');
	$_genero 		= $_proveedor->__get('Genero');
	$_telefono		= $_proveedor->__get('Telefono');
	$_interno		= $_proveedor->__get('Interno');
	$_correo 		= $_proveedor->__get('Email');
} else {
	$_action		= "Crear";
	//$_origen	 	= "";
	$_sector		= "";
	$_puesto 		= "";
	$_nombre	 	= "";
	$_apellido	 	= "";
	$_genero 		= "";
	$_telefono		= "";
	$_interno		= "";
	$_correo 		= "";
}

$_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"%s\"/>", $_action);

?>
<!DOCTYPE html>
<html lang="es">
<body style="background-image: none; background-color: transparent;">
    <head>
        <?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
        <script language="JavaScript" type="text/javascript" src="/pedidos/contactos/logica/jquery/jquery.enviar.js"></script>
        
    </head>
                
	
	<form name="fm_prov_contact_edit" class="fm_edit_iframe" method="POST">
		<input name="ctoid" id="ctoid" type="text" value="<?php echo $_ctoid; ?>" hidden/>
		<input name="origenid" id="origenid" type="text" value="<?php echo $_origenid; ?>" hidden/>                                                 		
		<div class="bloque_1">
			<label for="ctoorigen">Origen</label>
			<select id="ctoorigen" name="ctoorigen"/> 
				<option></option> 		
				<option value="<?php echo $_origen; ?>" selected><?php echo substr($_origen,1); ?></option>                                
			</select>
		</div>

		<div class="bloque_3">    
			<label for="ctosector">Sector / Departamento</label>
			<select id="ctosector" name="ctosector"/> 
				<option></option> <?php 
				$_sectores	= DataManager::getSectores(1); 
				if($_sectores){ 
					foreach ($_sectores as $k => $_sect) {
						$_sectid		= $_sect['sectid'];
						$_sectnombre	= $_sect['sectnombre']; 
						if($_sectid == $_sector){ ?>
							<option value="<?php echo $_sectid;?>" selected><?php echo $_sectnombre;?></option><?php
						} else {?>  
							<option value="<?php echo $_sectid;?>"><?php echo $_sectnombre;?></option><?php
						}
					}
				} else { ?>
					<option selected>Error Sectores</option> <?php
				} ?>      
			</select>
		</div>

		<div class="bloque_3">    
			<label for="ctopuesto">Puesto</label>
			<select id="ctopuesto" name="ctopuesto"/> 
				<option></option> <?php 
				$_puestos	= DataManager::getPuestos(1); 
				if($_puestos){ 
					foreach ($_puestos as $k => $_pto) {
						$_ptoid		= $_pto['ptoid'];
						$_ptonombre	= $_pto['ptonombre']; 
						if($_ptoid == $_puesto){ ?>
							<option value="<?php echo $_ptoid;?>" selected><?php echo $_ptonombre;?></option><?php
						} else {?>  
							<option value="<?php echo $_ptoid;?>"><?php echo $_ptonombre;?></option><?php
						}
					}
				} else { ?>
					<option selected>Error Puestos</option> <?php
				} ?>                                
			</select>
		</div>
		<div class="bloque_4">
			<select id="ctogenero" name="ctogenero"/>
				<?php
				if ($_genero == "F"){ ?> 
					<option></option>                       		
					<option value="<?php echo $_genero; ?>" selected><?php echo $_genero; ?></option>
					<option value="M">M</option>   <?php   
				} else {																
					if ($_genero == "M"){ ?> 
						<option></option>
						<option value="F">F</option>                       		
						<option value="<?php echo $_genero; ?>" selected><?php echo $_genero; ?></option> <?php   
					} else { ?>
						<option selected></option> 
						<option value="F">F</option>
						<option value="M">M</option>  <?php
					}
				} ?>
			</select>
		</div>
		<div class="bloque_2">    
			<input name="ctonombre" id="ctonombre" type="text" placeholder="Nombre"  maxlength="50" value="<?php echo $_nombre;?>"/>
		</div>
		<div class="bloque_1">    
			<input name="ctoapellido" id="ctoapellido" type="text" placeholder="Apellido" maxlength="50" value="<?php echo $_apellido;?>"/>
		</div>
		
		<div class="bloque_3">
			<input name="ctotelefono" id="ctotelefono" type="text" placeholder="Tel&eacute;fono" maxlength="20" value="<?php echo $_telefono;?>"/>
		</div>
		<div class="bloque_3">
			<input name="ctointerno" id="ctointerno" type="text" placeholder="Interno" maxlength="10" value="<?php echo $_interno; ?>"/>
		</div>
		<div class="bloque_1">
			<input name="ctocorreo" id="ctocorreo" type="text" placeholder="Correo" maxlength="50" value="<?php echo $_correo;?>"/>
		</div>
		<div class="bloque_1">
			<fieldset id='box_cargando' class="msg_informacion">                  
				<div id="msg_cargando" align="center"></div>      
			</fieldset>            

			<fieldset id='box_error' class="msg_error">           
				<div id="msg_error" align="center"></div>
			</fieldset>

			<fieldset id='box_confirmacion' class="msg_confirmacion">
				<div id="msg_confirmacion" align="center"></div>      
			</fieldset>
		</div>
		<div class="bloque_3">
			<?php echo $_button; ?>
		</div>

	</form>		
		
</body>
</html>