<?php
$tkid		= empty($_REQUEST['tkid']) ? 0 : $_REQUEST['tkid'];
if($tkid){
	$tickets	=	DataManager::getTicket(0, 0, $tkid);
	foreach( $tickets as $k => $tk ) {
		$idSector	= $tk['tkidsector'];
		$idMotivo	= $tk['tkidmotivo'];
		$estado		= $tk['tkestado'];
		$usrCreated	= $tk['tkusrcreated'];
	}
} 

if ($usrCreated != $_SESSION["_usrid"] && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="A"){
	echo "USUARIO DESLOGUEADO $usrCreated != ".$_SESSION["_usrid"];
  	exit;
}
?>

<div class="box_body">	
	<form id="fmTicket" class="fm_edit2" method="post" enctype="multipart/form-data">
		<div class="bloque_1">
			<label>
				<span style="font-size:24px; color:#666;" ><?php echo "Consulta Nro. ".$tkid; ?></span>
			</label>
		</div>
		
		<div class="bloque_1">
			<label><?php 
				switch($estado){
					case 0: //RESPONDIDO
						echo "<input type=\"button\" value=\"Respondido\" style=\"background-color: gray;\">";
						break;
					case 1: //ACTIVO
						echo "<input type=\"button\" value=\"Activo\" style=\"background-color:green;\">";
						break;
				}
				?>
			</label>
		</div>
		
		<div class="bloque_3">
			<label>
				<span style="font-size:16px; font-weight: bold; color: #666;">
					<?php 
					$sectores	=	DataManager::getTicketSector();
					foreach( $sectores as $k => $sec ) {	
						$id		= $sec['tksid'];
						$sector = $sec['tksnombre']; 
						if($id == $idSector){	
							echo $sector; 
						}
					} ?>
				</span>
			</label>
		</div>
		
		<?php
		$mensajes	=	DataManager::getTicketMensajes($tkid);
		foreach( $mensajes as $k => $msg ) {	
			$mensaje		= $msg['tkmsgmensaje'];
			$msgUsrCreated	= $msg['tkmsgusrcreated'];
			$msgNameCreated	= DataManager::getUsuario('unombre', $msgUsrCreated);	
			$msgCreated		= $msg['tkmsgcreated'];
			
			if($msgUsrCreated != $_SESSION["_usrid"]){
				$style = "background-color: #FFF";
			} else {
				$style = "background-color: transparent";
			} ?>
			
			<fieldset style="<?php echo $style; ?>">
				<div class="bloque_3">
					<?php echo $msgCreated; ?>
				</div>
				
				<div class="bloque_3">
					<?php echo $mensaje; ?>
				</div>	
				
				<div class="bloque_3">
					------------------------------------
					<br><?php echo $msgNameCreated; ?><br>
					------------------------------------
				</div>
				
				<?php 
				/*if($adjunto){
					echo "Archivo adjunto.";
				}; */
				?>
			</fieldset>
			<?php
		} ?>
	
		<fieldset>
			<legend>A&ntilde;adir una respuesta</legend>
			<div class="bloque_3">
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

			<!--input type="hidden" name="tkcopia" value="<?php //echo $copia;?>"/-->
			<input type="hidden" name="usrCreated" value="<?php echo $usrCreated;?>"/>
			<input type="hidden" name="tkestado" value="<?php echo $estado;?>"/>
			<input type="hidden" name="tkid" value="<?php echo $tkid;?>"/>
			<input type="hidden" name="tkidsector" value="<?php echo $idSector;?>"/>
			<input type="hidden" name="tkidmotivo" value="<?php echo $idMotivo;?>"/>

			<div class="bloque_3">
				<textarea name="tkmensaje" type="text" placeholder="Escribe tu respuesta..."/></textarea> 
			</div>

			<div class="bloque_1">
				<div class="inputfile"><input id="imagen" name="imagen" class="file" type="file"/></div>
			</div> 

			<div class="bloque_1"> 
				<?php $urlSend	=	'/pedidos/soporte/mensajes/logica/update.mensaje.php';?>
				<a id="btnSend" title="Enviar" style="cursor:pointer;"> 
					<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(fmTicket, '<?php echo $urlSend;?>');"/>
				</a>
			</div> 
		</fieldset>	
		
		
	</form>		
</div> <!-- FIN box_body -->
<hr>	
         