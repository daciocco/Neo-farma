<script type="text/javascript" src="/pedidos/reportes/jquery/selectCategoria.js"></script>

<div class="box_body">
    <form name="fmReporte" method="post">
		<fieldset>
			<legend>Selecci&oacute;n de reporte</legend>
			<div class="bloque_3" >     
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
			<div class="bloque_5" >
				<label for="rtecategoria">Categor&iacute;a de Informes</label>
				<select id="rtecategoria" name="rtecategoria">
				   <option id="0" value="0" selected></option>
				   <option id="1" value="abm">ABM</option>
				   <option id="2" value="agenda">Agenda</option>
				   <option id="3" value="articulo">Art&iacute;culo</option>
				   <option id="4" value="condicion">Condici&oacute;n Comercial</option>
				   <option id="5" value="cuenta">Cuenta</option>
				   <option id="6" value="llamada">Llamada</option>
				   <option id="7" value="parte_diario">Partes</option>
				   <option id="8" value="pedido">Pedido</option>
				   <option id="9" value="pedidos_transfer">Pedido Transfer</option>
				   <option id="10" value="planificado">Planificaci&oacute;n</option>
				   <option id="11" value="propuesta">Propuesta</option>
				   <option id="12" value="proveedor">Proveedor</option>
				   <option id="13" value="relevamiento">Relevamiento</option>
				   <option id="14" value="rendicion">Rendici&oacute;n</option>
				</select>
			</div>

			<div class="bloque_5" >
				<label for="rtereporte">Reporte</label>
				<select id="rtereporte" name="rtereporte">
				</select>
			</div>		
		</fieldset>

		<fieldset>
			<legend>Filtros de Reportes</legend>
			<div class="bloque_5" >
				<select id="rtefiltro" name="rtefiltro">
				   <option id="0" value="0" selected>(Filtros guardados previamente)</option>
				</select>
			</div>
			<div class="bloque_8" >
				<input value="nuevo" type="button">
			</div>
			<div class="bloque_8" >
				<input value="editar" type="button">
			</div>
			<div class="bloque_8" >
				<input value="eliminar" type="button">
			</div>
			<div class="bloque_5" >
				<input value="agregar condicion de filtro" type="button">
					
			</div>
			<div class="bloque_5" >
				<input value="agregar grupo de condicion de filtro" type="button">
			</div>
		</fieldset>

		<fieldset>
			<legend>Operacions</legend>
			<div class="bloque_8" >
				<input value="ejecutar" type="button">
			</div>
			<div class="bloque_8" >
				<input value="exportar" type="button">
			</div>

		</fieldset>


	</form>
</div> <!-- END box_body -->   
<hr>