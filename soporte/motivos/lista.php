<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){	
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
} ?>

<script type="text/javascript" language="JavaScript"  src="/pedidos/soporte/motivos/logica/jquery/script.js"></script>

<div class="box_body">
	<form id="fmMotivo" name="fmMotivo" class="fm_edit2" method="post">
		<fieldset>
			<legend>Datos de Motivo</legend>
			<input type="text" id="motid" name="motid" hidden="hidden">
			
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
			
			<div class="bloque_2">
				<?php $urlSend	=	'/pedidos/soporte/motivos/logica/update.motivo.php';?>
				<a id="btnSend" title="Enviar" style="cursor:pointer;"> 
					<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(fmMotivo, '<?php echo $urlSend;?>');"/>
				</a>
			</div>
			
			<div class="bloque_1">
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
			
			<div class="bloque_1">
				<label for="motivo">Motivo</label>	
				<input type="text" id="motivo" name="motivo">
			</div>
			
			
		</fieldset>
	</form>
</div> <!-- Fin box body -->

<div class="box_seccion">
	<div class="barra">
		<div class="buscadorizq">
			<h1>Motivos</h1>                	
		</div>
		<div class="buscadorder">
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