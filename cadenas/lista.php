<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){	
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
	exit;
} ?>

<script type="text/javascript" language="JavaScript"  src="/pedidos/cadenas/logica/jquery/scriptCadenas.js"></script>

<div class="box_body">
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
	<form id="fmCadena" name="fmCadena" method="post">
		<input type="text" id="cadid" name="cadid" hidden="hidden">
		<fieldset>
			<legend>Datos de cadena</legend>			
			
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
				<a title="Nueva Cadena" onClick="dac_changeEmpresa(empresa.value)">
					<img class="icon-new"/>
				</a>
			</div>	
			<div class="bloque_9">
				<br>				
				<?php $urlSend	=	'/pedidos/cadenas/logica/update.cadena.php';?>
				<a id="btnSend" title="Enviar"> 
					<img class="icon-send" onclick="javascript:dac_sendForm(fmCadena, '<?php echo $urlSend;?>');"/>
				</a>
			</div>
			<hr>
			
			<div class="bloque_8">
				<label for="cadena">Cadena</label>	
				<input type="text" id="cadena" name="cadena" readonly>
			</div>
			
			<div class="bloque_2">
				<label for="id">Nombre</label>	
				<input type="text" id="nombre" name="nombre" class="text-uppercase">	
			</div> 
			
		</fieldset>
		
		<fieldset>
			<legend>Cuentas Relacionadas</legend> 
			<div id="cuenta_relacionada"></div>
		</fieldset> 
	</form>
</div> <!-- Fin box body -->

<div class="box_seccion"> 
	<div class="barra">
		<div class="bloque_5">
			<h1>Cadenas</h1>                	
		</div>
		<div class="bloque_5" style="float: right">
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
			<input id="txtBuscarEn" type="text" value="tblCadenas" hidden/>
		</div> 
		<hr>     
	</div> <!-- Fin barra -->            
	<div class="lista"> 
		<div id='tablacadenas'></div>
	</div> <!-- Fin lista -->
	
	<div class="barra">
	   <div class="bloque_5">
			<h2>Cuentas</h2>  
	   </div>

		<div class="bloque_5" style="float: right">               
			<input id="txtBuscar2" type="search" autofocus placeholder="Buscar..."/>
			<input id="txtBuscarEn2" type="text" value="tblCuentas" hidden/>
		</div>
		<hr>
	</div> <!-- Fin barra -->  

	<div class="lista">
		<div id='tablacuentas'></div>
	</div> <!-- Fin listar -->	     
</div> <!-- Fin box_seccion -->

<hr>