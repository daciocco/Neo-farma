<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G") {	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}
?>

<script type="text/javascript" language="JavaScript"  src="/pedidos/droguerias/logica/jquery/scriptDroguerias.js"></script>
<div class="box_body">
	<form id="fmDrogueria" name="fmDrogueria" class="fm_edit2" method="post">
    	<fieldset>
			<legend>Datos de droguer&iacute;a</legend>
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
				<label for="empresa">Empresa</label>                        
				<select id="empresa" name="empresa" onchange="javascript:dac_changeEmpresa(this.value);"> <?php
					$empresas	= DataManager::getEmpresas(1); 
					if (count($empresas)) {	
						foreach ($empresas as $k => $emp) {
							$idEmp		=	$emp["empid"];
							$nombreEmp	=	$emp["empnombre"];
							if ($idEmp == 1){ ?>                        		
								<option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>" selected><?php echo $nombreEmp; ?></option><?php
							} else { ?>
								<option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>"><?php echo $nombreEmp; ?></option><?php
							}   
						}                            
					} 
					
					echo "<script>";
					echo "dac_changeEmpresa(1);";
					echo "</script>";
					?>
				</select>
			</div>
			
			<div class="bloque_1">
				<a href="editar.php" title="Nueva" style="cursor:pointer; text-decoration: none;">
					<img src="../images/icons/icono-nuevo.png" border="0" align="absmiddle" onmouseover="this.src='/pedidos/images/icons/icono-nuevo-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-nuevo.png';"/>
				</a>
				
				<a id="deleteDrog" title="Eliminar" style="cursor:pointer; text-decoration: none;">
					<img src="../images/icons/icono-eliminar.png" border="0" align="absmiddle" onmouseover="this.src='/pedidos/images/icons/icono-eliminar-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-eliminar.png';"/>
				</a>
				
				<?php $urlSend	=	'/pedidos/droguerias/logica/update.lista.php';?>
				<a id="btnSend" title="Enviar" style="cursor:pointer;"> 
					<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(fmDrogueria, '<?php echo $urlSend;?>');"/>
				</a>
			</div>
			
			<div class="bloque_2">
				<label for="drogid">Drogueria</label>
				<input type="text" id="drogid" name="drogid" readonly>
			</div>
			
			<div class="bloque_5">
				<label for="id">Nombre</label>	
				<input type="text" id="nombre" name="nombre" style="text-transform:uppercase;">	
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Droguer&iacute;as Relacionadas</legend> 
				<div class="bloque_2">Localidad</div>
				<div class="bloque_4">Cuenta</div>
				<div class="bloque_4" align="center">TL</div>
				<div class="bloque_4" align="center">TD</div>
				<div class="bloque_4" id="acciones"></div>
			<div id="drogueria_relacionada"></div>
		</fieldset>
	</form>	
</div> <!-- Fin box body -->

<div class="box_seccion"> 
	<div class="barra">
		<div class="buscadorizq">
			<h1>Droguer&iacute;as</h1>                	
		</div>
		<div class="buscadorder">
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
			<input id="txtBuscarEn" type="text" value="tblDroguerias" hidden/>
		</div> 
		<hr>     
	</div> <!-- Fin barra -->            
	<div class="lista"> 
		<div id='tabladroguerias'></div>
	</div> <!-- Fin lista -->
</div> <!-- Fin box_seccion -->

<hr>





