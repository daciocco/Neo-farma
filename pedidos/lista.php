<?php 
$_LPP	= 5000;
$_pag	= 1;

if ($_SESSION["_usrrol"] == "A" || $_SESSION["_usrrol"] == "M" || $_SESSION["_usrrol"] == "G" ){ ?>
	<div class="box_body2" align="center"> <!-- datos --> 
		<div class="barra">
			<div class="buscadorizq">
				<h1>Pedidos Pendientes</h1>
            </div>
			<div class="buscadorder">
				<?php if ($_SESSION["_usrrol"]!= "G"){ ?>
				<a href="logica/exportar.pendientes.php" title="Exportar pendientes"> 
					<img src="/pedidos/images/icons/export_excel.png" onmouseover="this.src='/pedidos/images/icons/export_excel-hover.png';" onmouseout="this.src='/pedidos/images/icons/export_excel.png';" border="0" align="absmiddle"/>
				</a>
				<?php } ?>
			</div>    
			<hr>  
		</div> <!-- Fin barra -->
		
		<div class="lista">        
			<table id="tblPendientes" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
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
							echo sprintf("<tr class=\"%s\" onclick=\"window.open('detalle_pedido.php?nropedido=%s')\" style=\"cursor:pointer;\" target=\"_blank\" title=\"Detalle de Pedido\">", ((($_fila % 2) == 0)? "par" : "impar"), $_nropedido);
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
	</div> <!-- Fin datos -->
	
	<div class="box_body2" align="center"> <!-- datos --> 
		<div class="barra">
			<div class="buscadorizq">
				<h1>Pedidos Pre-Facturados</h1>     	
			</div>
			<div class="buscadorder">
				<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
                <input id="txtBuscarEn" type="text" value="tblPrefacturados" hidden/>
			</div>   
			<hr>   
		</div> <!-- Fin Barra -->
		
		<div class="lista">        
			<table id="tblPrefacturados"  class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td scope="col" width="20%" height="18">Fecha</td>
						<td scope="col" width="20%">Pedido</td>
						<td scope="col" width="50%">Cliente</td>
						<td colspan="1" scope="colgroup" width="10%" align="center">Acciones</td>
					</tr>
				</thead>			
				<?php
				$dateFrom 	= new DateTime('now');
				$dateFrom ->modify('-1 month');													
				$_pedidos_facturados	=	DataManager::getPedidos(NULL, 0, NULL, NULL, NULL, NULL, $_pag, $_LPP, $dateFrom->format('Y-m-d'));
				if ($_pedidos_facturados){
					$_nro	=	0;
					$_fila	=	0;
					foreach ($_pedidos_facturados as $k => $_pedidoFact) {
						$fecha 		=	$_pedidoFact["pfechapedido"];						
						$_nropedido	= 	$_pedidoFact["pidpedido"];
						$_idemp		= 	$_pedidoFact["pidemp"]; 
						$_cliente	= 	$_pedidoFact["pidcliente"];	
						$_nombre	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_cliente, $_idemp);												
						if($_nro != $_nropedido) {	
							$_fila	=	$_fila + 1;
							//$_changestatus	=	sprintf( "<a href=\"logica/changestatus.php?nropedido=%d&backURL=%s\" title=\"deshacer pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-undo.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-undo-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-undo.png';\" border=\"0\" align=\"absmiddle\" />");

							$_detalle	= 	sprintf( "<a href=\"detalle_pedido.php?nropedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-lista-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-lista.png';\" border=\"0\" align=\"absmiddle\" />");

							echo sprintf("<tr class=\"%s\">", ((($_fila % 2) == 0)? "par" : "impar"));
							echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td>", $fecha, $_nropedido, $_nombre, $_detalle);
							echo sprintf("</tr>");
						}											
						$_nro = $_nropedido;
					}
				} else { ?>
					<tr>
						<td scope="colgroup" colspan="4" height="25" align="center">No hay pedidos Pre-Facturados</td>
					</tr>
					<?php
				}
				?>
			</table>
		</div> <!-- Fin lista -->	
	</div> <!-- Fin datos -->		
<?php } ?>

<?php if ($_SESSION["_usrrol"] == "A" || $_SESSION["_usrrol"] == "M" || $_SESSION["_usrrol"] == "V" || $_SESSION["_usrrol"] == "G"){ ?>
<div class="box_body2" align="center"> <!-- datos --> 
	<div class="barra">
    	<div class="buscadorizq">
			<h1>Mis Pedidos Pendientes</h1>  
    	</div>  
    	<hr>       
	</div> <!-- Fin barra -->
    
    <div class="lista">        
		<table id="tblPendientes" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
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
						$_eliminar	=	sprintf ("<a href=\"logica/eliminar.pedido.php?nropedido=%d&backURL=%s\" title=\"eliminar pedido\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PEDIDO?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-eliminar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-eliminar-claro.png';\" border=\"0\" align=\"absmiddle\" /> </a>", $_nropedido, $_SERVER['PHP_SELF'], "eliminar");						
						
						$_fila	=	$_fila + 1;
						
						echo sprintf("<tr class=\"%s\" onclick=\"window.open('detalle_pedido.php?nropedido=%s')\" style=\"cursor:pointer;\" target=\"_blank\" title=\"Detalle de Pedido\">", ((($_fila % 2) == 0)? "par" : "impar"), $_nropedido);
						
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
</div> <!-- Fin datos -->

<div class="box_body2" align="center"> <!-- datos --> 
	<div class="barra">
    	<div class="buscadorizq">
			<h1>Mis Pedidos Pre-Facturados</h1>     	
    	</div>
       	<div class="buscadorder">
        	<input id="txtBuscar2" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn2" type="text" value="tblMisPrefacturados" hidden/> 
        </div>  
        <hr>    
	</div> <!-- Fin barra -->
    
    <div class="lista">        
		<table id="tblMisPrefacturados"  class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td scope="col" width="20%" height="18">Fecha</td>
					<td scope="col" width="20%">Pedido</td>
					<td scope="col" width="50%">Cliente</td>
				</tr>
			</thead>			
			<?php
			$dateFrom 	= new DateTime('now');
			$dateFrom ->modify('-3 month');	
			
			$_pedidos_facturados	= DataManager::getPedidos($_SESSION["_usrid"], 0, NULL, NULL, NULL, NULL, $_pag, $_LPP, $dateFrom->format('Y-m-d'));
			if ($_pedidos_facturados){
				$_nro	=	0;
				$_fila	=	0;
				foreach ($_pedidos_facturados as $k => $_pedidoFact) {
					$fecha 		=	$_pedidoFact["pfechapedido"];					
					$_nropedido	= 	$_pedidoFact["pidpedido"];
					$_idemp		= 	$_pedidoFact["pidemp"]; 
					$_cliente	= 	$_pedidoFact["pidcliente"];
					$_nombre	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_cliente, $_idemp);				

					if($_nro != $_nropedido) {	
						$_fila	=	$_fila + 1;					
						echo sprintf("<tr class=\"%s\" onclick=\"window.open('detalle_pedido.php?nropedido=%s')\" style=\"cursor:pointer;\" target=\"_blank\" title=\"Detalle de Pedido\">", ((($_fila % 2) == 0)? "par" : "impar"),  $_nropedido);
						echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td>", $fecha, $_nropedido, $_nombre);
						echo sprintf("</tr>");
					}				
					$_nro = $_nropedido;
				}
			} else {
				?>
        		<tr>
					<td scope="colgroup" colspan="3" height="25" align="center">No hay pedidos Pre-Facturados</td>
				</tr>
        		<?php
			}
			?>
		</table>
	</div> <!-- Fin lista -->	
</div> <!-- Fin datos -->
<?php } ?>

<hr>