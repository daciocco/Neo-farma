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
		<div id="mapa" style="height: 600px;"></div> 
	</div>
	
	<fieldset>
		<legend>Ruta</legend>
		<div class="bloque_1">
			<select multiple id="waypoints2" style="height: 80px;"></select>
		</div>
		<div id="waypoints"></div>
	</fieldset>
</div> <!-- FIN box_body-->

<div class="box_seccion">
	<fieldset>
		<legend>Filtros</legend>
		<div class="bloque_5">
			<label>Empresa</label>
			<select id="empselect" name="empselect"><?php
				$empresas	= DataManager::getEmpresas(1); 
				if (count($empresas)) {	
					foreach ($empresas as $k => $emp) {
						$idEmp		=	$emp["empid"];
						$nombreEmp	=	$emp["empnombre"];
						if ($idEmp == $empresa){ 
							$selected="selected";										
						} else { $selected=""; } ?>
						<option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>" <?php echo $selected; ?>><?php echo $nombreEmp; ?></option><?php
					}                            
				} ?>
			</select>
		</div>		
		<div class="bloque_5">  
			<br>    
			<input id="filterMap" type="button" value="Filtrar">
		</div>
		<div class="bloque_1">
			<label>Zonas</label>	
			<div class="desplegable"> <?php
				$zonas	= DataManager::getZonas( 0, 0, 1);		
				for( $k = 0; $k <  count($zonas); $k++ ) {
					$zona 	= $zonas[$k];
					$numero	= $zona['zzona']; 
					$nombre	= $zona['znombre']; ?> 	
					<input name="zonas" type="checkbox" value="<?php echo $numero ?>">
					<?php echo $numero." - ".$nombre; ?>
					<br>
					<?php						
				} ?>
			</div>
		</div>
		<div class="bloque_1"> 	
			<label>Tipos de Cuentas</label>
			<div class="desplegable">
				<img src="/pedidos/images/icons/marcadorGreen.png" height="20px;" >
				<input type="checkbox" name="tipo" value="C">
				Cliente					
				<br>
				<img src="/pedidos/images/icons/marcadorGreen.png" height="20px;" >
				<input type="checkbox" name="tipo" value="CT">
				Cliente Telefonico
				<br>
				<img src="/pedidos/images/icons/marcadorYellow.png"  height="20px;">
				<input type="checkbox" name="tipo" value="T">
				Transfer
				<br>
				<img src="/pedidos/images/icons/marcadorYellow.png"  height="20px;">
				<input type="checkbox" name="tipo" value="TT">
				Transfer Telefonico
				<br>
				<img src="/pedidos/images/icons/marcadorRed.png"  height="20px;">
				<input type="checkbox" name="tipo" value="PS">
				Prospecto
				<br>
				<img src="/pedidos/images/icons/marcador.png"  height="20px;">
				Seleccionados 
				<br>
				<img src="/pedidos/images/icons/marcadorGreenHover.png"  height="20px;">
				Cliente	Inactivo
				<br>
				<img src="/pedidos/images/icons/marcadorGY.png" height="20px;">
				Cliente Inactivo con Transfer Activo
				<br>
				<img src="/pedidos/images/icons/marcadorYellowHover.png"  height="20px;">
				Transfer Inactivo
			</div>
		</div>
		<div class="bloque_5"> 						
			<input  type="checkbox" name="geoloc" title="Esta opcion puede tener una alta demora de carga" style="float: left;">	
			<label title="Esta opcion puede tener una alta demora de carga">Incluir sin <br> geolocalizar</label>				
		</div>
		<div class="bloque_5"> 
			<label>Estado</label>
			<div class="desplegable">
				<input type="radio" name="activas" value="2" checked>
				<label>Todas</label>
				<br>	
				<input type="radio" name="activas" value="1">
				<label>Activas</label>
				<br>
				<input type="radio" name="activas" value="0">
				<label>Inactivas</label>	
			</div>
		</div>
	</fieldset>

	<div class="barra">
		<div class="buscadorizq">
			<h1>Cuentas</h1>                	
		</div>
		<div class="buscadorder">
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
			<input id="txtBuscarEn" type="text" value="tblTablaCta" hidden/>
		</div> 
		<hr>     
	</div> <!-- Fin barra -->            
	<div class="lista"> 
		<div id='tablacuenta'></div>
	</div> <!-- Fin lista -->
	
	<fieldset>	
		<div id="listCuentas"></div>
	</fieldset>
</div>

<script src="/pedidos/zonas/logica/jquery/jquery.map.js"></script> 
<script>
	$("#filterMap").click(function () {	
		$("#waypoints").empty();
		var tipo = [];
		$.each($("input[name='tipo']:checked"), function(){
			tipo.push("'"+$(this).val()+"'");
		});	
		var zonas = [];
		$.each($("input[name='zonas']:checked"), function(){
			zonas.push($(this).val());
		});

		var geoloc  = ($("input[name='geoloc']:checked").val()) ? 1 : 0;
		var activas = $('input[name=activas]:checked').val();
		var empresa = $('#empselect').val();

		var data = {
			tipo 	: tipo, //'"C", "T"',
			activas : activas, //1,
			empresa : empresa,
			zonas 	: zonas,
			geoloc	: geoloc,
		};			
		google.maps.event.addDomListener(window, 'load', initialize(data));
	});		
</script> 