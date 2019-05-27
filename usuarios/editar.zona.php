<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A"){ 	
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }
 
 $_uid		= empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL'];

 if ($_uid) {
	$_usuario = DataManager::newObjectOfClass('TUsuario', $_uid);
	$_unombre 	= $_usuario->__get('Nombre');	
 } else {
  	$_unombre 	= "";
 } 

 $_button = sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Enviar\"/>");
 $_action = sprintf("logica/update.zonaven.php?uid=%d&backURL=", $_uid, $backURL);
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>

<body>
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
        <script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php
        $_section 	= '';
        $_subsection	= '';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
            
	<main class="cuerpo">
      	<div class="box_body">
			<form name="fm_zona_vend_edit" method="post" action="<?php echo $_action;?>">
				<fieldset>
					<legend>ZONAS DE USUARIO: <strong><?php echo $_unombre;?></strong></legend>
					<input type="hidden" name="uid" value="<?php echo $_uid; ?>" />
								
					<div class="bloque_8" style="float: right;">  <?php echo $_button; ?> </div>					
					<hr>					
					<?php 	
					$_zonas		= DataManager::getZonas( 0, 0, 1);								
					$_max	 	= count($_zonas);
					for( $k = 0; $k < $_max; $k++ ) {
						$_zona 	= $_zonas[$k];
						$_numero= $_zona['zzona'];
						$nombre	= $_zona['znombre'];
						$_checked = 0;
						?>
						
						<div class="bloque_5">
							<?php
							$zonas_actuales = DataManager::getZonasVendedor($_uid);
							$_max2	 		= count($zonas_actuales);
							for( $i = 0; $i < $_max2; $i++ ) {		
								$zona_actual	 = $zonas_actuales[$i];
								if($_numero == $zona_actual['zona']){$_checked = 1;}
							}
							$_checked = ($_checked == 1) ? 'checked="checked"' : '' ; 
							?>
							
							<input name="zzonas[]"  id="zzonas" type="checkbox" value="<?php echo $_numero ?>" <?php echo $_checked ?>/>	
							<label><?php echo $_numero." - ".$nombre; ?></label>			
						</div>
						<?php 										
					} ?>
				</fieldset>		
        	</form>	
    	</div> <!-- boxbody -->
        	
	</main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>