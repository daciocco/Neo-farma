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
	<form id="fmDrogueria" name="fmDrogueria" method="post">
    	<fieldset>
			<legend>Datos de droguer&iacute;a</legend>
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
			
			<div class="bloque_5">
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
			
			<div class="bloque_9">
				<br>
				<a href="editar.php" title="Nueva">
					<img class="icon-new"/>
				</a>
			</div>
			<div class="bloque_9">
				<br>	
				<a id="deleteDrog" title="Eliminar">
					<img class="icon-delete"/>
				</a>
			</div>
			<div class="bloque_9">
				<br>	
				<?php $urlSend	=	'/pedidos/droguerias/logica/update.lista.php';?>
				<a id="btnSend" title="Enviar"> 
					<img class="icon-send" onclick="javascript:dac_sendForm(fmDrogueria, '<?php echo $urlSend;?>');"/>
				</a>
			</div>
			<hr>
			<div class="bloque_7">
				<label for="drogid">Drogueria</label>
				<input type="text" id="drogid" name="drogid" readonly>
			</div>
			
			<div class="bloque_3">
				<label for="id">Nombre</label>	
				<input type="text" id="nombre" name="nombre" class="text-uppercase">	
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Droguer&iacute;as Relacionadas</legend> 
				<div class="bloque_7">Localidad</div>
				<div class="bloque_7">Cuenta</div>
				<div class="bloque_8" align="center">TL</div>
				<div class="bloque_8" align="center">TD</div>
				<div class="bloque_7" id="acciones"></div>
				<hr>
				<div id="drogueria_relacionada"></div>
		</fieldset>
	</form>	
</div> <!-- Fin box body -->

<div class="box_seccion"> 
	<div class="barra">
		<div class="bloque_5">
			<h1>Droguer&iacute;as</h1>                	
		</div>
		<div class="bloque_5">
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





