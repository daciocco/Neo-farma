<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
  	header("Location: $_nextURL");
  	exit;
}

//---------------------	
//	ACCIONES ACTIVAS
$_accobject	=	DataManager::getAcciones('', '', 1);
if (count($_accobject)) {	
	foreach ($_accobject as $k => $_accion) {
		$_array_acid 		= (empty($_array_acid)) 	? $_array_acid=$_accion["acid"] 			: $_array_acid.",".$_accion["acid"];	
		$_array_acnombre	= (empty($_array_acnombre)) ? $_array_acnombre=$_accion["acnombre"] 	: $_array_acnombre.",".$_accion["acnombre"]; //utf8_decode(
	}
	$_acciones	=	$_array_acid."/".$_array_acnombre;
} else {
	$_acciones	= "0/0";
}

//---------------------
$_fecha_planif			=	empty($_REQUEST['fecha_planif'])	?	date("d-m-Y")	:	$_REQUEST['fecha_planif'];
//---------------------
$_button_enviar 		= 	sprintf("<a title=\"Enviar Planificaci&oacute;n\" onclick=\"javascript:dac_Guardar_Planificacion(1)\">%s</a>", "<img class=\"icon-send\"/>");
$_button_print			= 	sprintf( "<a href=\"detalle_planificacion.php?fecha_planif=%s\" title=\"Imprimir Planificaci&oacute;n\" target=\"_blank\">%s</a>", $_fecha_planif, "<img class=\"icon-print\"/>");
$_button_guardar_planif= 	sprintf( "<a title=\"Guardar Planificaci&oacute;n\" onclick=\"javascript:dac_Guardar_Planificacion(0)\">%s</a>", "<img class=\"icon-save\"/>");
$_button_nuevo			= 	sprintf( "<a title=\"Nueva Planificaci&oacute;n\" onclick=\"javascript:dac_Carga_Planificacion('','','','1')\">%s</a>", "<img class=\"icon-new\"/>");
$_button_enviar_parte 	= 	sprintf("<a title=\"Enviar Parte\" onclick=\"javascript:dac_Guardar_Parte(1)\">%s</a>", "<img class=\"icon-send\"/>"); 
$_button_print_parte	= 	sprintf( "<a href=\"detalle_parte.php?fecha_planif=%s\" title=\"Imprimir Parte\" target=\"_blank\">%s</a>", $_fecha_planif, "<img class=\"icon-print\"/>"); 
$_button_guardar_parte	= 	sprintf( "<a title=\"Guardar Parte\" onclick=\"javascript:dac_Guardar_Parte(0)\">%s</a>", "<img class=\"icon-save\"/>");  
$_button_nuevo_parte	= 	sprintf( "<a title=\"Nuevo Parte\" onclick=\"javascript:dac_Carga_Parte('','','','','','','1','0', '%s')\">%s</a>", $_acciones, "<img class=\"icon-new\"/>");
$_btn_anularplanif		= 	sprintf("<input type=\"submit\" id=\"btsend\" value=\"Anular\" style=\"float:right;\" title=\"Anular Planificaci&oacute;n\" onclick=\"javascript:dac_Anular_Planificacion()\"/>");
$_btn_anularparte		= 	sprintf("<input type=\"submit\" id=\"btsend\" value=\"Anular\" style=\"float:right;\" title=\"Anular Parte\" onclick=\"javascript:dac_Anular_Parte()\"/>");

//----------------------------------
//	CONTROL DE FECHA PARA ALERTAS	
if ($_SESSION["_usrrol"]=="V"){ //ALERTAS// 
	 $dias 			= 	array(0,1,2,3,4,5,6); //array("dom","lun","mar","mie","jue","vie","sab");
 	$_dia			= 	$dias[date("w")]; 
 	/*Da la fecha viernes de la prox semana*/
 	$_prox_viernes	=	date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+(12-$_dia), date("Y")));//se define con --> date("d")+(12-$_dia):   El 12 es por (6 + 6) 
	$_prox_viernes2 = dac_invertirFecha( $_prox_viernes ); 
 	/*Da la fecha lunes de la prox semana*/
 	$_prox_lunes	=	date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+(8-$_dia), date("Y")));//se define con --> date("d")+(8-$_dia) El 8 es por (6 + 2)
	$_prox_lunes2 = dac_invertirFecha( $_prox_lunes );
 	/*Da la fecha lunes de la semana actual*/
 	$_lunes_actual	=	date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+(1-$_dia), date("Y")));
	$_lunes_actual2 = dac_invertirFecha( $_lunes_actual ); 
 	/*Da la fecha viernes de la semana actual*/
 	$_viernes_actual	=	date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+(5-$_dia), date("Y")));
	$_viernes_actual2 = dac_invertirFecha( $_viernes_actual );
  
 	if ($_dia == 5 || $_dia == 6 || $_dia == 0){ // "ES fine";	
		/*Controlo si faltan cargar o enviar planificaciones de la proxima semana*/
		$_planif	= 	DataManager::getControlEnvioPlanif($_prox_lunes, $_prox_viernes, $_SESSION["_usrid"]);
		if (count($_planif)){
			if (count($_planif) < 5){
				$_msg_error_planif	=	"Faltan PLANIFICACIONES en la semana </br> del ".$_prox_lunes2." al ".$_prox_viernes2;
			} else {
				foreach ($_planif as $k => $_plan){															
					$_plan 			= $_planif[$k];
					$_planenviado	= $_plan["planifactiva"];
					if ($_planenviado == 1){
						$_msg_error_planif	=	"Hay PLANIFICACIONES sin enviar </br> del ".$_prox_lunes2." al ".$_prox_viernes2;
					}	
				}
			}
		} else { 	$_msg_error_planif	=	"No hay PLANIFICACIONES cargadas </br> del ".$_prox_lunes2." al ".$_prox_viernes2; }
	
		/*Controlo si hay partes que falten enviar de la semana pasada desde el Lunes que pasó al finde actual*/
		$_parts		=	DataManager::getControlEnvioPartes($_lunes_actual, $_viernes_actual, $_SESSION["_usrid"]);
		if (count($_parts)){	
			if (count($_parts) < 5){
				$_msg_error_parte	=	"Faltan PARTES en la semana </br> del ".$_lunes_actual2." al ".$_viernes_actual2;
			} else {
				foreach ($_parts as $k => $_part){															
					$_part 			= $_parts[$k];
					$_partenviado	= $_part["parteactiva"];
					if ($_partenviado == 1){
						$_msg_error_parte	=	"Hay PARTES sin enviar </br> del ".$_lunes_actual2." al ".$_viernes_actual2;
					}	
				}
			}
		} else { 	$_msg_error_parte	=	"NO hay PARTES cargados </br> del ".$_lunes_actual2." al ".$_viernes_actual2;}	
 	}
 } 

?>

<script language="JavaScript"  src="/pedidos/planificacion/logica/jquery/jqueryUsr.js" type="text/javascript"></script>
<?php if ($_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "A"){ ?> 
	<script language="JavaScript"  src="/pedidos/planificacion/logica/jquery/jqueryAdmin.js" type="text/javascript"></script>
<?php } ?> 

<div class="box_body">
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
	<hr>
	
	<!-- FORM CALENDARIOS -->
	<form id="export_partes_to_excel" action="logica/exportar.parte.php" method="POST">
		<div class="bloque_7">
			<label><?php if ($_SESSION["_usrrol"]== "V"){ echo "Origen";} else { echo "Desde";}?></label>
			<input id="fecha_planif" name="fecha_planificado" type="text" value="<?php echo @$_fecha_planif;?>" style="background-color:#f2f7d8;" readonly>
		</div>

		<div class="bloque_7">
			<label><?php if ($_SESSION["_usrrol"]== "V"){ echo "Destino";} else { echo "Hasta";}?></label> 
			<input id="fecha_destino" name="fecha_destino" type="text" value="<?php echo @$_fecha_destino;?>" style="background-color:#fdd494;" readonly>
		<!--/div>  <!-- end fechas_p-->
		</div>

		<?php if ($_SESSION["_usrrol"]== "V" || $_SESSION["_usrrol"]== "A" ){ ?>
			<div class="bloque_8">
				<br>
				<a title="Duplicar" onclick="javascript:dac_Duplicar_Planificacion('<?php echo $_fecha_planif;?>')"><img class="icon-copy" /></a>
			</div>
		<?php }?>

		<?php if ($_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){?> 
			<div class="bloque_8">
				<br>
				<a title="Exportar Planificaciones" onclick="javascript:dac_ExportarPlanifOPartesToExcel(fecha_planif.value, fecha_destino.value, 'planificado')">
					<img class="icon-xls-export-planif" />
				</a>				
			</div>

			<div class="bloque_8">
				<br>
				<a title="Exportar Partes" onclick="javascript:dac_ExportarPlanifOPartesToExcel(fecha_planif.value, fecha_destino.value, 'parte')">
					<img class="icon-xls-export-parte"/>
				</a>
			</div>	
		<?php }?>    
		<input id="tipo_exportado" name="tipo_exportado" type="text" hidden>       
		<?php if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){?>
			<div class="bloque_8">
				<br>
				<a title="Exportar Reporte" onclick="javascript:dac_ExportarPlanifOPartesToExcel(fecha_planif.value, fecha_destino.value, 'reporte')">
					<img class="icon-xls-export-report"/>
				</a>
			</div>					
		<?php }?>
	</form>	
	<hr>

	<div class="bg-orange barra">
		<h1>IMPORTANTE! Recuerda pedir a todas tus cuentas, <br>
		<strong>DISPONE de habilitación y DT.</strong></h1>
	</div>
	<hr>

	<div class="barra">		
		<div class="bloque_5">
			<h1>Planificaci&oacute;n</h1> 
		</div>
		<hr>
		<div class="bloque_9"> <?php echo $_button_nuevo; ?> </div>
		<div class="bloque_9"> <?php echo $_button_guardar_planif; ?>  </div>
		<div class="bloque_9"> <?php echo $_button_print; ?> </div>
		<div class="bloque_9"> <?php echo $_button_enviar; ?> </div>
		<hr>
	</div> <!-- Fin barra -->

	<form id="fm_planificacion" name="fm_planificacion" method="post" enctype="multipart/form-data">
		<div id="detalle_planif"></div> 			  
		<?php	
		$_idclientes = array();	
		$_planificacion	= DataManager::getDetallePlanificacion($_fecha_planif, $_SESSION["_usrid"]);
		if (count($_planificacion)){	
			foreach ($_planificacion as $k => $_planif){	
				$_planif 		= $_planificacion[$k];
				$_planifcliente	= $_planif["planifidcliente"];						
				$_planifnombre	= $_planif["planifclinombre"];
				$_planifdir		= $_planif["planifclidireccion"];
				$_planifactiva	= $_planif["planifactiva"];
				$_idclientes[$k]= $_planifcliente;	
				echo "<script>";
				echo "javascript:dac_Carga_Planificacion('".$_planifcliente."','".$_planifnombre."','".$_planifdir."','".$_planifactiva."')";
				echo "</script>";
			}
		} ?>   
	</form>
	<hr>

	<!-- PARTE -->
	<?php				
	$_parte_diario	= DataManager::getDetalleParteDiario($_fecha_planif, $_SESSION["_usrid"]);
	if (count($_parte_diario)){	 ?>
		<div class="barra">
			<div class="bloque_5"> 
				<h1>Parte Diario</h1>  
			</div>
			<hr>
			<div class="bloque_9"> <?php echo $_button_nuevo_parte; ?> </div>
			<div class="bloque_9"> <?php echo $_button_guardar_parte; ?></div>
			<div class="bloque_9"> <?php echo $_button_print_parte; ?> </div>
			<div class="bloque_9"> <?php echo $_button_enviar_parte; ?> </div>
			<hr>
		</div> <!-- Fin barra -->

		<form id="fm_parte" name="fm_parte" method="post" enctype="multipart/form-data">
			<div id="detalle_parte"></div>  <?php				
			foreach ($_parte_diario as $k => $_parte){		
				$_partecliente		= $_parte["parteidcliente"];
				$_partenombre		= $_parte["parteclinombre"];
				$_partedir			= $_parte["parteclidireccion"];	
				$_partetrabajo		= $_parte["partetrabajocon"];
				$_parteobservacion	= $_parte["parteobservacion"];
				$_parteaccion		= $_parte["parteaccion"];			
				$_parteactiva		= $_parte["parteactiva"];
				$_parteplanificada	= 0;
				for($i=0; $i < count( $_idclientes); $i++){
					if($_partecliente == $_idclientes[$i]){$_parteplanificada	=	1;}
				}
				echo '<script >';
				echo "javascript:dac_Carga_Parte('".$_partecliente."', '".$_partenombre."', '".$_partedir."', '".$_partetrabajo."', '".$_parteobservacion."', '".$_parteaccion."', '".$_parteactiva."', '".$_parteplanificada."', '".$_acciones."')";
				echo '</script>';					

			} ?>     
		</form>
		<hr>
	<?php }?>
</div> <!-- end box_body-->   

<div class="box_seccion">
	<!-- ADMINISTRAR PARTES Y PLANIF -->
	<?php if ($_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "A"){ ?> 
			<div class="barra"> 
				<h1>Administrar</h1>  <hr>
			</div> <!-- Fin barra --> 

			<form id="fm_anularplanif" name="fm_anularplanif" method="POST">  
				<fieldset>
					<legend>Anular Planificaci&oacute;n</legend>   
					<!--El id lo usaremos para seleccionar este elemento con el jQuery-->
					<div class="bloque_5">
						<select id="vendedor" name="vendedor"/> 
							<option>Vendedor...</option> <?php
							$vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
							if (count($vendedores)) {	
								foreach ($vendedores as $k => $vend) { ?>
									<option id="<?php echo $vend["unombre"]; ?>" value="<?php echo $vend["uid"]; ?>"><?php echo $vend["unombre"]; ?></option><?php
								}                            
							} ?> 
						</select>
					</div>
					<div class="bloque_7">
						<input id="f_fecha_anular" name="fecha_anular" type="text" placeholder="Fecha" value="<?php echo @$_fechaanular;?>" size="16" readonly/>
					</div>
					<div class="bloque_7"><?php echo $_btn_anularplanif; ?> </div>
				</fieldset> 
			</form>

			<form id="fm_anularparte" name="fm_anularparte" method="POST">  
				<fieldset>
					<legend>Anular Parte</legend>   
					<!--El id lo usaremos para seleccionar este elemento con el jQuery-->
					<div class="bloque_5">
						<select id="vendedor2" name="vendedor2"/> 
							<option>Vendedor...</option> <?php
							$vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
							if (count($vendedores)) {	
								foreach ($vendedores as $k => $vend) { ?>
									<option id="<?php echo $vend["unombre"]; ?>" value="<?php echo $vend["uid"]; ?>"><?php echo $vend["unombre"]; ?></option><?php
								}                            
							} ?> 
						</select>
					</div>
					<div class="bloque_7">
						<input id="f_fecha_anular_parte" name="fecha_anular_parte" type="text" placeholder="Fecha" value="<?php echo @$_fechaanularparte;?>" size="16" readonly/>
					</div>
					<div class="bloque_7"><?php echo $_btn_anularparte; ?> </div>
				</fieldset> 
			</form>
	<?php }?>
	
	
 	<!-- ALERTAS -->	 
	<?php if ($_SESSION["_usrrol"]== "V" || $_SESSION["_usrrol"]== "A"){ ?>
		<?php if (!empty($_msg_error_planif)){?>
		<div class="bg-orange barra">
			<h1><?php echo $_msg_error_planif; ?></h1> 
			<hr>      
		</div> <!-- Fin barra -->
		<?php }?>

		<?php if (!empty($_msg_error_parte)){?>
		<div class="bg-orange barra">
			<h1><?php echo $_msg_error_parte; ?></h1>   
			<hr>    
		</div> <!-- Fin barra -->
		<?php }?>

		<!-- LISTADO DE CLIENTES -->
		<div class="barra">
			<div class="bloque_5"> <h1>Cuentas</h1> </div>
			<div class="bloque_5">
				<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
				<input id="txtBuscarEn" type="text" value="tblTablaCli" hidden/>
			</div>  
			<hr>    
		</div> <!-- Fin barra -->

		<div class="lista"> 
			<table border="0" id="tblTablaCli" align="center">
				<thead>
					<tr align="left">
						<th>Id</th>
						<th>Nombre</th>
						<th>Localidad</th>
					</tr>
				</thead>
				
				<tbody> <?php
					if (!empty($_SESSION["_usrzonas"]))	{
						$_clientes	= DataManager::getCuentas( 0, 0, 1, NULL, '"C","CT","T","TT"', $_SESSION["_usrzonas"], 2);
						if (count($_clientes)) {
							//Consulto si la planificacion fue enviada para que los clientes se carguen en planif o partes
							$_estado		=	0;
							$_planificacion	= 	DataManager::getDetallePlanificacion($_fecha_planif, $_SESSION["_usrid"]);
							if (count($_planificacion) > 0){					 
								foreach ($_planificacion as $k => $_planif) {	
									$_planif 		= $_planificacion[$k];
									$_planactiva	= $_planif["planifactiva"];
									if($_planactiva == 0){ $_estado	=	1; }												
								}
							} 
					
							if($_estado == 0){ ?>
								<tr class="par" onclick="javascript:dac_Carga_Planificacion('999999', 'Otra Actividad', 'Otra Actividad', '1')">
									<td>999999</td>
									<td>Otra Actividad</td>										
									<td>Otra Actividad</td>
								</tr>
								<tr class="impar" onclick="javascript:dac_Carga_Planificacion('888888', 'Nuevos Clientes', 'Nuevos Clientes', '1')">
									<td>888888</td>
									<td>Nuevos Clientes</td>
									<td>Nuevos Clientes</td>
								</tr>
								<?php
							} else { ?>
								<tr class="par" onclick="javascript:dac_Carga_Parte('999999', 'Otra Actividad', 'Otra Actividad', '', '', '', '1', '0', '<?php echo $_acciones; ?>')">
									<td>999999</td>
									<td>Otra Actividad</td>										
									<td>Otra Actividad</td>
								</tr>
								<tr class="impar" onclick="javascript:dac_Carga_Parte('888888', 'Nuevos Clientes', 'Nuevos Clientes', '', '', '', '1', '0', '<?php echo $_acciones; ?>')">
									<td>888888</td>
									<td>Nuevos Clientes</td>
									<td>Nuevos Clientes</td>
								</tr> <?php
							}
		
							foreach ($_clientes as $k => $_cliente) {
								$_Cid			=	$_cliente["ctaid"];
								$_Cidcliente 	= 	$_cliente["ctaidcuenta"];
								$ctaActiva	 	= 	$_cliente["ctaactiva"];
								$ctaTipo		=	$_cliente["ctatipo"];

								if($_Cidcliente != 0){
									$_Cnombre	 	=	$_cliente["ctanombre"];
									$_Ccuit	 		=	$_cliente["ctacuit"];
									$_Cdireccion	= 	($_cliente["ctaidloc"] == 0) ? $_cliente["ctalocalidad"] : DataManager::getLocalidad('locnombre', $_cliente["ctaidloc"]) ;
									$_Ccorreo 		=	$_cliente["ctacorreo"];
																		
									((($k % 2) == 0)? $clase="par" : $clase="impar");
									if($_estado == 0){ 
										$_onclick	= "javascript:dac_Carga_Planificacion('".$_Cidcliente."', '".$_Cnombre."', '".$_Cdireccion."', '1')";
									} else {
										$_onclick	= "javascript:dac_Carga_Parte('".$_Cidcliente."', '".$_Cnombre."', '".$_Cdireccion."', '', '', '', '1', '0', '".$_acciones."')";
									} ?>
									
									<tr class="<?php echo $clase;?>" onclick="<?php echo $_onclick;?>">
										<td><?php echo $_Cidcliente;?></td>
										<td><?php echo $_Cnombre;?></td>
										<td><?php echo $_Cdireccion;?></td>
									</tr> <?php 
								}
							}
						} else {?>
							<tr>
								<td colspan="3"><?php echo "No se encontraron registros."; ?></td>	
							</tr> <?php 
						}
					} ?> 
				</tbody>
			</table>
		</div> <!-- Fin lista -->	
	<?php }?>   
</div> <!-- Fin box_seccion -->
<hr>

<!-- Scripts para calendario  -->
<script language="JavaScript"  src="/pedidos/planificacion/logica/jquery/jqueryUsrFooter.js" type="text/javascript"></script>
<?php if ($_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "A"){ ?> 
	<script language="JavaScript"  src="/pedidos/planificacion/logica/jquery/jqueryAdminFooter.js" type="text/javascript"></script>
<?php } ?>