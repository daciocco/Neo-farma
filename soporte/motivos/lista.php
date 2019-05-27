<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){	
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
} ?>

<script type="text/javascript" language="JavaScript"  src="/pedidos/soporte/motivos/logica/jquery/script.js"></script>

<div class="box_body">
	<form id="fmMotivo" name="fmMotivo" method="post">
		<fieldset>
			<legend>Datos de Motivo</legend>			
			<div class="bloque_1" align="center">
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
			
			
			<input type="text" id="motid" name="motid" hidden="hidden">
			
			<div class="bloque_5"> 
				<label for="sector">Sector</label>
				<select id="sector" name="sector">   
					<option id="0" value="0" selected></option> 
					<?php
					$sectores	=	DataManager::getTicketSector();
					foreach( $sectores as $k => $sec ) {	
						$id		= $sec['tksid'];
						$titulo	= $sec['tksnombre']; ?>
						<option id="<?php echo $id; ?>" value="<?php echo $id; ?>"><?php echo $titulo; ?></option><?php
					} ?>
				</select>
			</div>
			
			<div class="bloque_5">
				<?php $urlSend	=	'/pedidos/soporte/motivos/logica/update.motivo.php';?>
				<a id="btnSend" title="Enviar"> 
					<img class="icon-send" onclick="javascript:dac_sendForm(fmMotivo, '<?php echo $urlSend;?>');"/>
				</a>
			</div>
			
			<div class="bloque_5">
				<label for="responsable">Responsable</label>
				<select id="responsable" name="responsable">   
					<option id="0" value="0" selected></option> <?php
					$responsables	= DataManager::getUsuarios( 0, 0, 1, NULL, '"A", "M", "G"');
					if (count($responsables)) {	
						foreach ($responsables as $k => $resp) {
							$idUsr		=	$resp["uid"];
							$nombreUsr	=	$resp['unombre']; ?>
							<option id="<?php echo $idUsr; ?>" value="<?php echo $idUsr; ?>"><?php echo $nombreUsr; ?></option><?php
						}                            
					} ?>
				</select>
			</div>
			
			<div class="bloque_5">
				<label for="motivo">Motivo</label>	
				<input type="text" id="motivo" name="motivo">
			</div>
			
		</fieldset>
	</form>
</div> <!-- Fin box body -->

<div class="box_seccion">
	<div class="barra">
		<div class="bloque_5">
			<h1>Motivos</h1>                	
		</div>
		<div class="bloque_5">
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
			<input id="txtBuscarEn" type="text" value="tblmotivos" hidden/>
		</div> 
		<hr>     
	</div> <!-- Fin barra -->            
	<div class="lista">
		<div id='tablamotivos'></div>
	</div> <!-- Fin lista -->
</div> <!-- Fin box_seccion -->

<hr>