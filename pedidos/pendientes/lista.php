<div class="box_body"> <!-- datos --> 
	<?php if ($_SESSION["_usrrol"] == "A" || $_SESSION["_usrrol"] == "M" || $_SESSION["_usrrol"] == "V" || $_SESSION["_usrrol"] == "G"){ ?>
		<div class="barra">
			<div class="bloque_1">
				<h1>Mis Pendientes</h1>  
			</div>  
			<hr>       
		</div> <!-- Fin barra -->

		<div class="lista_super">        
			<table id="tblPendientes">
				<thead>
					<tr>
						<td scope="col" width="20%" height="18">Fecha</td>
						<td scope="col" width="20%">Pedido</td>
						<td scope="col" width="50%">Cliente</td>
						<td scope="col" width="10%" align="center">Acciones</td>
					</tr>
				</thead>			
				<?php
				$_pedidos_recientes	= DataManager::getPedidos($_SESSION["_usrid"], 1, NULL, NULL, NULL, NULL); 
				if ($_pedidos_recientes){	
					$_nro	=	0;	
					$_fila	=	0;
					foreach ($_pedidos_recientes as $k => $_pedidoRec) {	
						$fecha 	=	$_pedidoRec["pfechapedido"];
						$_nropedido	= 	$_pedidoRec["pidpedido"];
						$_idemp		= 	$_pedidoRec["pidemp"]; 	
						$_cliente	= 	$_pedidoRec["pidcliente"];
						$_nombre	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_cliente, $_idemp);

						if($_nro != $_nropedido) {					
							$_eliminar	=	sprintf ("<a href=\"../logica/eliminar.pedido.php?nropedido=%d&backURL=%s\" title=\"Eliminar\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PEDIDO?')\"> <img class=\"icon-delete\"/> </a>", $_nropedido, $_SERVER['PHP_SELF'], "eliminar");										
							$_fila	=	$_fila + 1;

							echo sprintf("<tr class=\"%s\" onclick=\"window.open('../detalle_pedido.php?nropedido=%s')\" style=\"cursor:pointer;\" target=\"_blank\" title=\"Detalle\">", ((($_fila % 2) == 0)? "par" : "impar"), $_nropedido);

							echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td>", $fecha, $_nropedido, $_nombre, $_eliminar);
							echo sprintf("</tr>");
						}

						$_nro = $_nropedido;
					}
				} else {
					?>
					<tr>
						<td scope="colgroup" colspan="3" height="25" align="center">No hay pedidos pendientes</td>
					</tr>
					<?php
				}
				?>
			</table>
		</div> <!-- Fin lista -->	
	<?php } ?>
	
	<?php if ($_SESSION["_usrrol"] == "A" || $_SESSION["_usrrol"] == "M" || $_SESSION["_usrrol"] == "G" ){ ?>
		<div class="barra">
			<div class="bloque_5">
				<h1>Pendientes</h1>
			</div>
			<?php if ($_SESSION["_usrrol"]!= "G"){ ?>
				<div class="bloque_5" align="right">
					<a href="../logica/exportar.pendientes.php" title="Exportar pendientes"> 
						<img class="icon-xls-export"/>
					</a>
				</div>
			<?php } ?>
			<hr> 
		</div> <!-- Fin barra -->

		<div class="lista_super">        
			<table id="tblPendientes">
				<thead>
					<tr>
						<td scope="col" width="20%" height="18">Fecha</td>
						<td scope="col" width="20%">Pedido</td>
						<td scope="col" width="50%">Cliente</td>
					</tr>
				</thead>			
				<?php
				$_pedidos_recientes	= DataManager::getPedidos(NULL, 1); 
				if ($_pedidos_recientes){	
					$_nro	=	0;
					$_fila	=	0;
					foreach ($_pedidos_recientes as $k => $_pedidoRec) {	
						$fecha 		=	$_pedidoRec["pfechapedido"];
						$_nropedido	= 	$_pedidoRec["pidpedido"];
						$_idemp		= 	$_pedidoRec["pidemp"]; 	
						$_cliente	= 	$_pedidoRec["pidcliente"];
						$_nombre	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_cliente, $_idemp);

						if($_nro != $_nropedido) {		
							$_fila	=	$_fila + 1;							
							echo sprintf("<tr class=\"%s\" onclick=\"window.open('../detalle_pedido.php?nropedido=%s')\" style=\"cursor:pointer;\" target=\"_blank\" title=\"Detalle\">", ((($_fila % 2) == 0)? "par" : "impar"), $_nropedido);
							echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td>", $fecha, $_nropedido, $_nombre);
							echo sprintf("</tr>");
						}

						$_nro = $_nropedido;
					}
				} else {
					?>
					<tr>
						<td scope="colgroup" colspan="3" height="25" align="center">No hay pedidos pendientes</td>
					</tr>
					<?php
				}
				?>
			</table>
		</div> <!-- Fin lista -->
	<?php } ?>	

</div> <!-- Fin datos -->

	
<div class="box_seccion"> 
	<div class="barra">
		<h1>Estado de Pedidos</h1>
	</div>

	<div class="temas2">
		<a href="http://clientes.cruzdelsur.com/" target="_blank">
			<div class="box_mini2">
				Web <br> Cruz Del Sur
			</div> <!-- box_mini -->
		</a>
		<a href="javascript:dac_exportar(6);">
			<div class="box_mini2">
				Listados <br> Cartas de Porte
			</div> <!-- box_mini -->
		</a>			
	</div> 

	<div class="temas2">		
		<a href="https://neo-farma.com.ar/pedidos/informes/archivos/PedidosPendientes.xls" >
			<div class="box_mini2">
				Seguimiento Pedidos <br> <p>Neo-farma</p>
			</div>
		</a>			
		<a href="https://neo-farma.com.ar/pedidos/informes/archivosgezzi/PedidosPendientes.xls" >
			<div class="box_mini2">
				Seguimiento Pedidos <br> <p>Gezzi</p>
			</div>
		</a>
	</div>  

</div>

<hr>




<hr>


<script type="text/javascript">
	function dac_exportar(nro){
		switch (nro){
			case 6:
				if (confirm("ATENCI\u00d3N: Se proceder\u00e1 a descargar un archivo por cada una de las zonas que le corresponda. Si no consigue hacerlo, p\u00f3ngase en contacto con el administrador de la web. Si no encuentra el archivo descargado, busque en la carpeta descargas de la PC. \u00A1Gracias!")) {
					<?php 
					$zona = explode(', ', $_SESSION["_usrzonas"]);							
					for($i = 0;	$i < count($zona);	$i++){
						$_archivo	=	$_SERVER["DOCUMENT_ROOT"]."/pedidos/informes/archivos/cartasdeporte/".trim($zona[$i])."_Carta-de-Porte.XLS";							
						if (file_exists($_archivo)){ ?>	
							archivo	  = <?php echo trim($zona[$i]); ?>+'_Carta-de-Porte.XLS';							
							direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/cartasdeporte/'+archivo;
							window.open(direccion, '_blank'); 
							direccion = ""; <?php								
						}else{ ?>	
							alert("No hay Carta de Porte correspondiente a la zona <?php echo trim($zona[$i]); ?>"); <?php  
						} 
					} ?>
				}				
				break;
		}
	}
</script>