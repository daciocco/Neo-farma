<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!= "M" && $_SESSION["_usrrol"]!= "V"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$_sms	= empty($_GET['sms']) ? 0 : $_GET['sms'];  
if ($_sms) {	
	$_tipo_informe		=	$_SESSION['inf_nombre'];	
	switch ($_sms) {
		case 6: $_info	= "Notificaci&oacute;n enviada a Vendedores."; break;
	} // mensaje de error
}

//generales
$_button_notificar = sprintf("<input type=\"submit\" id=\"btsExportar Tablasend\" name=\"_accion\" value=\"Notificar\"/>");
$_action_notificar = sprintf("/pedidos/informes/logica/new.noticia.php");
$btnExporTbl =	sprintf("<input type=\"button\" id=\"btnExporTbl\" value=\"Exportar\" title=\"Exportar Tabla Seleccionada\"/>");
$btnExporReport =	sprintf("<input type=\"button\" id=\"btnExporReport\" value=\"Exportar\" title=\"Exportar Informe Seleccionado\"/>");
?>

<div class="box_body">	<!-- datos -->
	<div class="bloque_1">
		<fieldset id='box_cargando' class="msg_informacion">                  
			<div id="msg_cargando"></div>      
		</fieldset>
		<fieldset id='box_error' class="msg_error">           
			<div id="msg_error"></div>
		</fieldset>
		<fieldset id='box_confirmacion' class="msg_confirmacion">
			<div id="msg_confirmacion"></div>      
		</fieldset>		
		<?php if ($_sms) { 
			if($_sms == 6){ ?>
				<fieldset id='box_observacion' class="msg_alerta" style="display: block">
					<div id="msg_atencion" align="center"><?php echo $_info; ?></div>       
				</fieldset> <?php
			}
		} ?>
	</div>
	
	<div class="temas2">
		<a href="javascript:dac_exportar(11);">
			<div class="box_mini2">
				Comprobantes <br> <p>Neo-farma</p>
			</div>
		</a>	
		<?php if($_SESSION["_usrdni"] == "3035" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "G") { ?>
		<a href="javascript:dac_exportar(22);" >
			<div class="box_mini2">
				Comprobantes <br> <p>Gezzi</p>
			</div>
		</a>
		<?php } ?>			
	</div> 
	
	<div class="temas2">
		<a href="https://neo-farma.com.ar/pedidos/informes/archivos/DevolucionesNeo.xls">
			<div class="box_mini2">
				Devoluciones <br> <p>Neo-farma</p>
			</div>
		</a>		
		<a href="https://neo-farma.com.ar/pedidos/informes/archivosgezzi/DevolucionesGezzi.xls" >
			<div class="box_mini2">
				Devoluciones <br> <p>Gezzi</p>
			</div>
		</a>
	</div>
	
	<div class="temas2">
	 	<a href="javascript:dac_exportar(12);"  >
			<div class="box_mini2">
				Deudas <br> <p>Neo-farma</p>
			</div>
		</a> 
		<?php if($_SESSION["_usrdni"] == "3035" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "G") { ?>
		<a href="https://neo-farma.com.ar/pedidos/informes/archivosgezzi/deudores/30_Informe_de_Deudas.XLS" >
			<div class="box_mini2">
				Deudas <br> <p>Gezzi</p>
			</div>
		</a>	   
		<?php } ?>
	</div>
	
	<div class="temas2">
		<a href="https://neo-farma.com.ar/pedidos/informes/archivos/NotasValor.xls" >
			<div class="box_mini2">
				Notas de Valor <br> <p>Neo-farma</p>
			</div>
		</a>
	</div>
	
	<hr>
		
	<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]== "M"){ ?>
		<fieldset>
			<legend>Archivos &Uacute;nicos</legend>
			<div class="bloque_6">                      
				<select id="tipo_informeUnico" name="tipo_informeUnico">
					<option value="0">Seleccione archivo...</option>
					<option value="Ofertas">Ofertas</option>
					<!--option value="Cantidades">Neo Cantidades</option>
					<option value="Minoristas">Neo Minoristas</option>
					<option value="NotasValor">Neo Notas de Valor</option>
					<option value="PedidosPendientes">Neo Pedidos pendientes</option>
					<option value="DevolucionesNeo">Neo Devoluciones</option-->
					<!--option value="Stock">Neo Stock</option-->
					<!--option value="PedidosPendientesGezzi">Gezzi Pedidos pendientes</option>
					<option value="DevolucionesGezzi">Gezzi Devoluciones</option-->					
				</select>  
			</div>
			<div class="bloque_5">
				<input id="informesUnicos" class="file" type="file"/>
			</div>
			<div class="bloque_8">
				<input type="button" id="enviar_informesUnicos" value="Enviar">    			
			</div>
			
			
		</fieldset>
		
		<fieldset>
			<legend>Archivos M&uacute;ltiples (M&aacute;ximo 20 archivos por vez)</legend>
			<div class="bloque_6">                      
				<select id="tipo_informe" name="tipo_informe">
					<option value="0">Seleccione archivos...</option>
					<!--option value="archivos/comprobantes">Neo Comprobantes</option>
					<option value="archivos/deudores">Neo Deudas</option>
					<option value="archivos/cartasdeporte">Neo Cartas de Porte</option-->
					<option value="archivos/facturas/contrareembolso">Neo Facturas Contrareembolso</option>
					<!--option value="archivosgezzi/comprobantes">Gezzi Comprobantes</option>
					<option value="archivosgezzi/deudores">Gezzi Deudas</option>
					<option value="archivosgezzi/cartasdeporte">Gezzi Cartas de Porte</option-->
					
				</select>  
			</div>
			<div class="bloque_5">
				<input id="informes" class="file" type="file" multiple/>
			</div>
			<div class="bloque_8">
				<input type="button" id="enviar_informes" value="Enviar">    			
			</div>
		</fieldset>
	<?php } ?>
	
</div>


<div class="box_seccion">
	<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]== "M"){ ?>
		<form name="fm_noticia" method="post" action="<?php echo $_action_notificar;?>" enctype="multipart/form-data">
			<fieldset>
				<legend>Notificar Cambios</legend> 
				<div class="bloque_1">
					<?php echo $_button_notificar; ?>
				</div>					
			</fieldset>
		</form>
	<?php } ?>     
		
	<form id="exportInforme" action="#" method="POST"> 
		<fieldset>
			<legend>Exportar Informes</legend>
				<div class="bloque_5">
					<input id="fechaDesde" name="fechaDesde" type="text" placeholder="* DESDE" size="14" readonly/>
				</div>
				<div class="bloque_5">
					<input id="fechaHasta" name="fechaHasta" type="text" placeholder="* HASTA" size="14" readonly/>
				</div>
				
				<div class="bloque_5"> 
					<select id="exportReport">
						<option value="0">Seleccione...</option>
						<option value="llamadas">Llamadas</option>
						<option value="transfers">Transfers</option>						
						<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]== "M"){ ?>
							<option value="pedidos">Pedidos</option>
							<!--option value="liqPendExce">Liquidaciones Pendientes Excedentes</option-->
						<?php } ?>
						
					</select>
				</div>
				<div class="bloque_5"><?php echo $btnExporReport; ?> </div>
		</fieldset>
		
		<fieldset>
			<legend>Exportar Tablas</legend>
			<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]== "M"){ ?>
				<div class="bloque_5"> 
					<select id="exportTable">
						<option value="0">Seleccione...</option>
						<!--option value="abm">Abm</option-->
						<option value="articulo">Articulo</option>
						<option value="cadena">Cadena</option>
						<option value="cuentasCadena">Cuentas Cadena</option>
						<option value="condicion">Condicion</option>
						<option value="condicionArt">CondicionArt</option>
						<option value="condicionBonif">CondicionBonif</option>
						<option value="cuentas">Cuentas</option>
						<option value="droguerias">Droguerias</option>
						<option value="drogueriasCad">DrogueriasCAD</option>
						<option value="transfers">Transfers</option>
						<option value="proveedor">Proveedores</option>
					</select>
				</div>
				<div class="bloque_5"><?php echo $btnExporTbl; ?> </div>
				
			<?php } ?> 
		</fieldset> 
		
		<fieldset>
			<legend>Importar Tablas</legend>
			<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]== "M"){ ?>
				<div class="bloque_5"> 
					<select id="importTable" name="importTable">
						<option value="0">Seleccione...</option>
						<!--option value="abm">Abm</option-->
					</select>
				</div>
				<div class="bloque_5">
					<input type="button" id="sendImportFile" value="Importar">    			
				</div>
			
				<div class="bloque_1">
					<input id="importTableFile" class="file" type="file"/>  
				</div>
			<?php } ?> 
		</fieldset>
	</form>
	
	<fieldset> 
		<legend>Facturas Contrareembolso Actuales:</legend>
		<div class="bloque_1">
			<div class="lista"> <?php
				$ruta 	= $_SERVER['DOCUMENT_ROOT'].'/pedidos/informes/archivos/facturas/contrareembolso/';
				$data	= dac_listar_directorios($ruta);	
				if($data){
					foreach ($data as $file => $timestamp) {  
						echo $timestamp."</br>";
					}
				} else {
					echo "No hay facturas subidas";
				} ?>
			</div>
		</div>
	</fieldset> 
</div>

<!-- Scripts para IMPORTAR MULTIPLES ARCHIVOS -->
<script type="text/javascript" src="/pedidos/informes/logica/js/jquery.script.multifile.js"></script>

<script language="javascript" type="text/javascript">
	$("#btnExporReport").click(function () {
		//el control de fechas debería ser acá y no en el excel
		switch($('select[id=exportReport]').val()){
			case "0": 
				$('#box_error').css({'display':'block'});
				$("#msg_error").html('Seleccione un informe.');	
				break;
			case "llamadas": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.registro_llamadas.php');
				document.forms["exportInforme"].submit();
				break;
			case "pedidos": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.pedidos.php');
				document.forms["exportInforme"].submit();
				break;
			case "transfers": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.transfer.php');
				document.forms["exportInforme"].submit();
				break;
			case "liqPendExce": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.liquidacionPendExce.php');
				document.forms["exportInforme"].submit();	
				break;
		}
	});
	
	$("#btnExporTbl").click(function () {
		//el control de fechas debería ser acá y no en el excel
		switch($('select[id=exportTable]').val()){
			case "0": 	
				$('#box_error').css({'display':'block'});
				$("#msg_error").html('Seleccione un tabla.');	
				break;
			case "transfers": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablatransfer.php');
				document.forms["exportInforme"].submit();
				break;
			case "abm": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablaabm.php');
				document.forms["exportInforme"].submit();
				break;
			case "cadena": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablacadenas.php');
				document.forms["exportInforme"].submit();	
				break;
			case "cuentasCadena":
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablacuentascadenas.php');
				document.forms["exportInforme"].submit();	
				break;
			case "articulo": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablaarticulos.php');
				document.forms["exportInforme"].submit();	
				break;
			case "condicion": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablacondicion.php');
				document.forms["exportInforme"].submit();
				break;
			case "condicionArt": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablacondicionArt.php');
				document.forms["exportInforme"].submit();	
				break;
			case "condicionBonif":
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablacondicionBonif.php');
				document.forms["exportInforme"].submit();	
				break;
			case "cuentas": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablacuentas.php');
				document.forms["exportInforme"].submit();	
				break;
			case "droguerias": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tabladroguerias.php');
				document.forms["exportInforme"].submit();	
				break;
			case "drogueriasCad": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tabladrogueriasCAD.php');
				document.forms["exportInforme"].submit();	
				break;
			case "proveedor": 
				$('#exportInforme').attr('action', '/pedidos/informes/logica/exportar.tablaproveedor.php');
				document.forms["exportInforme"].submit();	
				break;
				
				
		}
	});
</script>


<script type="text/javascript">
	function dac_exportar(nro){
		switch (nro){
			case 11:	
				if (confirm("ATENCI\u00d3N: Se proceder\u00e1 a descargar un archivo por cada una de las zonas que le corresponda. Si no consigue hacerlo, p\u00f3ngase en contacto con el administrador de la web. Si no encuentra el archivo descargado, busque en la carpeta descargas de la PC. \u00A1Gracias!")){
					<?php 
					$zona = explode(', ', $_SESSION["_usrzonas"]);
					for($i = 0;	$i < count($zona);	$i++){
						$_archivo	=	$_SERVER["DOCUMENT_ROOT"]."/pedidos/informes/archivos/comprobantes/".trim($zona[$i])."_Ventas_por_Vendedor.XLS";							
						if (file_exists($_archivo)){ ?>
							archivo	  = <?php echo trim($zona[$i]); ?>+'_Ventas_por_Vendedor.XLS';							
							direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/comprobantes/'+archivo;
							window.open(direccion, '_blank');	<?php  
						}else{ ?>	
							alert("No hay Ventas correspondiente a la zona <?php echo trim($zona[$i]); ?>"); <?php  
						} 
					} ?>
				}
				break;
			case 12:	
				if (confirm("ATENCI\u00d3N: Se proceder\u00e1 a descargar un archivo por cada una de las zonas que le corresponda. Si no consigue hacerlo, p\u00f3ngase en contacto con el administrador de la web. Si no encuentra el archivo descargado, busque en la carpeta descargas de la PC. \u00A1Gracias!")){
					<?php 
					$zona = explode(', ', $_SESSION["_usrzonas"]);							
					for($i = 0;	$i < count($zona);	$i++){							
						$_archivo	=	$_SERVER["DOCUMENT_ROOT"]."/pedidos/informes/archivos/comprobantes/".trim($zona[$i])."_Ventas_por_Vendedor.XLS";							
						if (file_exists($_archivo)){ ?>
							archivo	  = <?php echo trim($zona[$i]); ?>+'_Informe_de_Deudas.XLS';							
							direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/deudores/'+archivo;							
							window.open(direccion, '_blank'); <?php  
						}else{ ?>	
							alert("No hay Deudores correspondiente a la zona <?php echo trim($zona[$i]); ?>"); <?php  
						} 
					} ?>
				}
				break;	
		}
	}
</script>

<script type="text/javascript">
	new JsDatePick({
		useMode:2,
		target:"fechaDesde",
		dateFormat:"%d-%M-%Y"			
	});
	/*********************************/
	new JsDatePick({
		useMode:2,
		target:"fechaHasta",
		dateFormat:"%d-%M-%Y"			
	});	
</script>