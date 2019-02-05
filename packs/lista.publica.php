<?php

/*************************************************************************************************/
$_fecha	=	empty($_REQUEST['fecha'])	?	date("d-m-Y")	:	$_REQUEST['fecha'];
/*************************************************************************************************/
$_boton_exportar	=	sprintf( "<a id=\"exporta\" href=\"logica/exportar.pack.php?fecha=%s\" title=\"Exportar Packs del mes seleccionado\"><img src=\"/pedidos/images/icons/export_excel.png\" border=\"0\" align=\"absmiddle\"/></a> ", $_fecha);
?>
<div class="box_body">
	<div class="barra">
       	<div class="buscadorizq">
			<h1>Packs</h1> 
            <input id="f_fecha" name="fecha" type="text" placeholder="FECHA" value="<?php echo $_fecha;?>" size="14" readonly/>  
            <?php echo $_boton_exportar; ?>          	
        </div>
        <hr>
	</div> <!-- Fin barra -->
    
    <div class="lista_super"> 
    	<?php 	
		//CUANDO CAMBIE LA FECHA, DEBERÍA RECARGAR LA WEB O ALGO ASI CON JAVASCRIPT Y AJAX
		$_packs	= DataManager::getPacks(0, 100, NULL, dac_invertirFecha($_fecha)); 
		if($_packs){
			foreach($_packs as $k => $_pack) {
				$_pack 			= 	$_packs[$k];
				$_packid		=	$_pack['packid'];
				$_nombre		= 	$_pack['packnombre'];
				$_cantidad		= 	$_pack['packcantmin'];
				$_condicionArray= 	($_pack['packcondpago']) ? explode(",", $_pack['packcondpago']) : '';
				$_fechainicio	= 	dac_invertirFecha( $_pack['packfechainicio']);
				$_fechafin		= 	dac_invertirFecha( $_pack['packfechafin']);                             
				$_observacion	= 	$_pack['packobservacion']; 
				$_activo		=	($_pack['packactiva'])	?	"ACTIVO"	:	"INACTIVO";?>

				<table class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th scope="colgroup" height="25"><?php echo $_activo; ?></th>
							<th scope="colgroup" colspan="3"></th>
							<th scope="colgroup" >Desde</th>
							<th scope="colgroup" ><?php echo $_fechainicio; ?></th>
						</tr>
						<tr>
							<th scope="colgroup" height="18" colspan="4"></th>
							<th scope="colgroup" >Hasta</th>
							<th scope="colgroup" ><?php echo $_fechafin;	?></th>
						</tr>
						<tr>
							<th scope="colgroup" height="30" colspan="6" align="left" style="font-size:26px; color:#1977BC"><?php echo $_nombre;?></th>
						</tr>                            
						<tr>
							<th scope="colgroup" height="25" align="left">C&Oacute;DIGO</th>
							<th scope="colgroup" align="left">DESCRIPCI&Oacute;N</th>
							<th scope="colgroup" align="right">PRECIO DROG</th>
							<th scope="colgroup">CANTIDAD</th>
							<th scope="colgroup">BONIFICACI&Oacute;N</th>
							<th scope="colgroup" align="right">IMPORTE</th>
						</tr>
					</thead>
				<?php  					
					//Leo los detalles del pack
					$_pack_detalle	= DataManager::getDetallePack($_packid);
					if ($_pack_detalle) {	
						$_total = 0;	
						$_cant_total = 0;						 
						foreach($_pack_detalle as $j => $_detalle) {	
							$_det_idart		= 	$_detalle['pdartid'];	
							$_det_cant		= 	($_detalle["pdcant"] == 0)? '' : $_detalle["pdcant"];	
							$_det_bonif1	= 	($_detalle["pdbonif1"] == 0)? '' : $_detalle["pdbonif1"];								
							$_det_bonif2	= 	($_detalle["pdbonif2"] == 0)? '' : $_detalle["pdbonif2"];
							$_det_bonif		= 	($_detalle["pddesc"])? $_detalle["pddesc"] : $_det_bonif1."x".$_det_bonif2;

							$_det_nombre	= 	DataManager::getArticulo('artnombre', $_det_idart, 1, 1);
							$_det_precio	= 	DataManager::getArticulo('artprecio', $_det_idart, 1, 1);

							$_cant_total	+=	$_det_cant;
							$_total			+=	$_det_cant * $_det_precio;

							((($j % 2) == 0)? $clase="par" : $clase="impar")
							?>

							<tr class="<?php echo $clase; ?>">
								<td scope="colgroup" height="18"><?php echo $_det_idart; ?></td>
								<td scope="colgroup"><?php echo $_det_nombre; ?></td>
								<td scope="colgroup" align="right"><?php echo  number_format($_det_precio, 2 ); ?></td>
								<td scope="colgroup" align="center"><?php echo $_det_cant; ?></td>
								<td scope="colgroup" align="center"><?php echo $_det_bonif; ?></td>

								<td scope="colgroup" align="right"><?php echo number_format(($_det_cant * $_det_precio), 2); ?></td>
							</tr>		

							<?php	
						} 
					} ?> 
					<tr>
						<th scope="colgroup" height="20px;"></th>
						<th scope="colgroup" align="right">TOTAL</th>
						<th scope="colgroup"></th>
						<th scope="colgroup"><?php echo $_cant_total; ?></th>
						<th scope="colgroup"></th>
						<th scope="colgroup" align="right"><?php echo number_format($_total, 2 ); ?></th>
					</tr>

					<tr><th scope="colgroup" colspan="6" height="20px;"></th></tr>
					<tr>
						<th scope="colgroup" height="18" colspan="6" align="left">CONDICI&Oacute;N DE PAGO</th>
					</tr> 
					<?php						
					$_condiciones	=	DataManager::getCondicionesDePago(); 
					if (count($_condiciones)) {	
						foreach ($_condiciones as $q => $_cond) {	
							$_idcond		=	$_cond["condid"];
							$_condcodigo	=	$_cond["condcodigo"];
							$_condnombre	=	$_cond["condnombre"];
							$_conddias		=	$_cond["conddias"];
							$_condporc		=	$_cond["condporcentaje"]; 

							if (in_array($_condcodigo, $_condicionArray)) {									
								?>
								<tr>
									<td scope="colgroup"></td>
									<td scope="colgroup" colspan="5"><?php echo $_condnombre." - ".$_conddias." - [".$_condporc."%]"; ?></td>
								</tr>
								<?php
							}
						}                           
					} ?>
					<tr><th scope="colgroup" colspan="6" height="20px;"></th></tr>   
					<tr>
						<th scope="colgroup" height="18" colspan="6"><?php echo $_observacion; ?></th>
					</tr> 
					<tr><th scope="colgroup" colspan="6" height="20px;" style="border-bottom:2px solid #333;"></th></tr>    
				</table>
				<?php 
			}

		} else { echo "No se verifican packs en la fecha indicada."; }?>
	</div>
</div>

<!-- Scripts para calendario -->
<script type="text/javascript">
	g_globalObject = new JsDatePick({
		useMode:2,
		target:"f_fecha",
		dateFormat:"%d-%M-%Y"			
	});

	g_globalObject.setOnSelectedDelegate(function(){
		var obj = g_globalObject.getSelectedDay();
		var fecha	=	("0" + obj.day).slice (-2) + "-" + ("0" + obj.month).slice (-2) + "-" + obj.year;			
		document.getElementById("f_fecha").value	= fecha;
		
		var url = window.location.origin+'/pedidos/packs/index.php?fecha=' + fecha;	
		document.location.href=url;			
	});
</script>

