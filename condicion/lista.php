<div class="box_body">
	<div class="bloque_1">
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
	
	<div class="barra">
		<div class="bloque_7">
			<select id="empselect" name="empselect"><?php
				$empresas	= DataManager::getEmpresas(1); 
				if (count($empresas)) {
					foreach ($empresas as $k => $emp) {
						$idemp		=	$emp["empid"];
						$nombreEmp	=	$emp["empnombre"];	
						?><option id="<?php echo $idemp; ?>" value="<?php echo $idemp; ?>"><?php echo $nombreEmp; ?></option><?php
					}
				} ?>
			</select>
		</div>		
		<div class="bloque_7">
			<select id="tiposelect" name="tiposelect">
				<option value="0">Tipos</option>
				<option value="Bonificacion">Bonificaciones</option>
				<option value="Pack">Packs</option>
				<option value="ListaEspecial">Listas Especiales</option>
				<option value="CondicionEspecial">Condiciones Especiales</option>			
			</select>
		</div>
		<div class="bloque_7">
			<select id="actselect" name="actselect">
				   <option id="" value="" >Todos</option>
				   <option id="1" value="1" >Activos</option>
				   <option id="0" value="0" >Inactivos</option>
			</select>
		</div>
		<div class="bloque_7">
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar por P&aacute;gina"/>
			<input id="txtBuscarEn" type="text" value="tblCondiciones" hidden/>
		</div>
		<?php 
		if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){
			$btnNuevo	= 	sprintf( "<a href=\"editar.php\" title=\"Nuevo\"><img src=\"/pedidos/images/icons/icono-nuevo50.png\" border=\"0\" align=\"absmiddle\" title=\"Nuevo\"/></a>");
			$btnStatus		= 	sprintf( "<img src=\"/pedidos/images/icons/icono-pendiente.png\" border=\"0\" align=\"absmiddle\" title=\"Cambiar Estado\" onclick=\"javascript:dac_ModificarSelect('status')\"/>");
			$btnDuplicar	= 	sprintf( "<img src=\"/pedidos/images/icons/icono-copytoall.png\" border=\"0\" align=\"absmiddle\" title=\"Duplicar\" onclick=\"javascript:dac_ModificarSelect('duplicate')\"/>");
			$btnPrecio		= 	sprintf( "<img src=\"/pedidos/images/icons/icono-precio.png\" border=\"0\" align=\"absmiddle\" title=\"Modificar Precios\" onclick=\"javascript:dac_ModificarSelect('price')\"/>");
			$btnEliminar	= 	sprintf( "<img src=\"/pedidos/images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Eliminar\" onclick=\"javascript:dac_eliminarCondicion()\" />");
			
			?>
			<div class="bloque_1">
				<?php echo $btnNuevo; ?>
				<?php echo $btnStatus; ?>
				<?php echo $btnDuplicar; ?>
				<?php echo $btnPrecio; ?>
				<?php echo $btnEliminar; ?>
			</div>
			<?php
		} ?> 		
		
		<hr>
	</div> <!-- Fin barra -->

	<div class="lista_super"> 	
		<form id='frmCondicion' method='post'>		
			<div id='tablaCondiciones'></div> 
		</form> 
	</div> <!-- Fin listar -->

	<div class="barra">
		<div class="bloque_1" style="text-align: right;"> 
			<!-- paginador de jquery -->   
			<paginator></paginator>
			<input id="totalRows" hidden="hidden">
		</div>
		<hr>
	</div> <!-- Fin barra -->
</div> <!-- Fin box_cuerpo --> 

<div class="box_seccion"> <?php
	if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){
	?>
	<fieldset class="fm_edit_seccion">
		<legend>Modificar Datos</legend>
		<div class="bloque_2">
			<input id="startDate" name="startDate" type="text" placeholder="INICIO" readonly/>
		</div>
		<div class="bloque_2">
			<input id="endDate" name="endDate" type="text" placeholder="FIN" readonly/>
		</div>
		<div class="bloque_2">
			<input type="button" id="btnEdit" value="Editar" title="Editar"/>
		</div>
	</fieldset>
	<?php } ?>
</div> <!-- Fin box_seccion -->
<hr>

<script src="logica/jquery/jqueryFooter.js"></script>
<script src="/pedidos/js/jquery/jquery.paging.js"></script>	
<script>
	//#######################################
	// 			PAGING DACIOCCO
	//#######################################
	
	//---------------------------------------
	//Funcion que devuelve cantidad de Filas
	function dac_filas(callback) {
		var empresa = $('#empselect').val(),
			tipo	= $('#tiposelect').val(),
			activos	= $('#actselect').val();
		$.ajax({
			type	: "POST",
			cache	: false,						
			url		: '/pedidos/condicion/logica/ajax/getFilasCondiciones.php',
			data:	{	empselect	:	empresa,
						tiposelect	: 	tipo,
						actselect	:	activos
					},
			success	: function(totalRows){
				if(totalRows){
					$("#totalRows").val(totalRows);
					callback(totalRows);
				}
			},						
		});	
	}
	
	//---------------------------------------
	//Cantidad de filas por página
	var rows = 25;		
	//Setea datos de acceso a datos vía AJAX
	var data = { //los indices deben ser iguales a los id de los select
		empselect	:	$('#empselect').val(),
		actselect	: 	$('#actselect').val(),
		tiposelect	:	$('#tiposelect').val()
	};
	var url 		= 'logica/ajax/getCondiciones.php';
	var tableName 	= 'tablaCondiciones';
	var selectChange= ['tiposelect', 'empselect', 'actselect'];
	//---------------------------------------
	//Llamada a función generadora del paginado
	dac_filas(function(totalRows) {
		$("paginator").paging(rows, totalRows, data, url, tableName, selectChange);
	});	
</script>