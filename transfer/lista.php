<div class="box_body2">  <!-- datos -->
	<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){ ?> 
	<div class="barra">
		<div class="buscadorizq">
			<h1>Transfers Pendientes</h1>              	
		</div>
		<div class="buscadorder">
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblPTPendientes" hidden/>
            
			<?php if ($_SESSION["_usrrol"]!= "G"){?>
				<a href="logica/enviar.pedido.php" title="enviar pedido"> 
					<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle"/>
				</a>
			<?php } ?>
		</div>  
		<hr>            
	</div> <!-- Fin barra -->

	<div class="lista">          
		<table id="tblPTPendientes" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td scope="col" width="20%" height="18">Fecha</td>
					<td scope="col" width="20%">Transfer</td>
					<td scope="col" width="50%">Cliente</td>
					<td scope="colgroup" colspan="3" width="10%" align="center">Acciones</td>
				</tr>
			</thead>			
			<?php
			$_transfers_recientes	= DataManager::getTransfers(1); 
			$_max	 	= count($_transfers_recientes);
			if ($_max != 0) {
				for( $k=0; $k < $_max; $k++ ) {
					if ($k < $_max){
						$_transfer_r 	= 	$_transfers_recientes[$k];						
						$_fecha 	=	$_transfer_r["ptfechapedido"];
						$_nropedido	= 	$_transfer_r["ptidpedido"];	
						$_nombre	= 	$_transfer_r["ptclirs"];
						
						$_eliminar 	= sprintf ("<a href=\"logica/eliminar.pedido.php?ptid=%d&backURL=%s\" title=\"eliminar pedido\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PEDIDO?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-eliminar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-eliminar-claro.png';\"  border=\"0\" align=\"absmiddle\" /> </a>", $_nropedido, $_SERVER['PHP_SELF'], "eliminar");		

						$_detalle	= 	sprintf( "<a href=\"detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-lista-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-lista.png';\" border=\"0\" align=\"absmiddle\" />");

						$_espacio	= 	sprintf("<img src=\"/pedidos/images/icons/icono-vacio.png\" border=\"0\" align=\"absmiddle\" />");										
					}	
					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_fecha, $_nropedido, $_nombre, $_eliminar, $_espacio, $_detalle);
					echo sprintf("</tr>");
				}
			} else { ?>
				<tr>
					<td scope="colgroup" colspan="3" height="25" align="center">No hay pedidos Transfer pendientes</td>
				</tr> <?php
			} ?>
		</table>
	</div> <!-- Fin listar -->	
	<?php }?>  

	<?php if ($_SESSION["_usrrol"]=="V" || $_SESSION["_usrrol"]== "A"){ ?>
	<div class="barra">
		<div class="buscadorizq">
			<h1>Mis Transfers Recientes</h1>    	
		</div>
		<div class="buscadorder">
			<input id="txtBuscar2" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn2" type="text" value="tblMisPTPendientes" hidden/> 
		</div>   
		<hr>   
	</div> <!-- Fin barra -->

	<div class="lista">        
		<table id="tblMisPTPendientes" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td scope="col" width="20%" height="18">Fecha</td>
					<td scope="col" width="20%">Transfer</td>
					<td scope="col" width="50%">Cliente</td>
					<td scope="colgroup" colspan="3" width="10%" align="center">Acciones</td>
				</tr>
			</thead>			
			<?php
			$_transfers_recientes	= DataManager::getTransfers(1, $_SESSION["_usrid"]); 

			$_max	 	= count($_transfers_recientes);
			if ($_max != 0) { 
				for( $k=0; $k < $_max; $k++ ){
					if ($k < $_max){
						$_transfer_r 	= 	$_transfers_recientes[$k];						
						$_fecha 	=	$_transfer_r["ptfechapedido"];
						$_nropedido	= 	$_transfer_r["ptidpedido"];	
						$_nombre	= 	$_transfer_r["ptclirs"];

						$_eliminar 	= sprintf ("<a href=\"logica/eliminar.pedido.php?ptid=%d&backURL=%s\" title=\"eliminar pedido\" onclick=\"return confirmar('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PEDIDO?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-eliminar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-eliminar-claro.png';\"  border=\"0\" align=\"absmiddle\" /> </a>", $_nropedido, $_SERVER['PHP_SELF'], "eliminar");		

						$_detalle	= 	sprintf( "<a href=\"detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-lista-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-lista.png';\" border=\"0\" align=\"absmiddle\" />");

						$_espacio	= 	sprintf("<img src=\"/pedidos/images/icons/icono-vacio.png\" border=\"0\" align=\"absmiddle\" />");

					}	
					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_fecha, $_nropedido, $_nombre, $_eliminar, $_espacio, $_detalle);
					echo sprintf("</tr>");
				}
			} else { ?>
				<tr>
					<td scope="colgroup" colspan="3" height="25" align="center">No hay pedidos Transfer pendientes</td>
				</tr> <?php
			} ?>
		</table>
	</div> <!-- Fin listar -->
	<?php }?>
</div> <!-- Fin datos -->

<div class="box_body2"> <!-- datos --> 
   <?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){ ?>
	<div class="barra">
		<div class="buscadorizq">
			<h1>Transfers Enviados </h1>    	
		</div>
		<div class="buscadorder">
			<input id="txtBuscar3" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn3" type="text" value="tblPTEnviados" hidden/> 			   
			<!--a href="logica/exportar.historial.total.php" title="Exportar &uacute;ltimos 60 d&iacute;as"><img src="/pedidos/images/icons/export_excel60.png" border="0" align="absmiddle"/></a-->
			<a href="logica/exportar.historial.total.php" title="Exportar Historial"><img src="/pedidos/images/icons/export_excel.png" border="0" onmouseover="this.src='/pedidos/images/icons/export_excel-hover.png';" onmouseout="this.src='/pedidos/images/icons/export_excel.png';" align="absmiddle"/></a>            
		</div>      
		<hr> 
	</div> <!-- Fin barra -->

	<div class="lista">
		<table id="tblPTEnviados"  class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td scope="col" width="20%" height="18">Fecha</td>
					<td scope="col" width="20%">Transfer</td>
					<td scope="col" width="50%">Cliente</td>
					<td scope="colgroup" width="10%" align="center">Acciones</td>
				</tr>
			</thead>
			<tbody>
			<?php
			$dateFrom 	= new DateTime('now');
			$dateFrom ->modify('-3 month');		
			$_transfers_recientes	= DataManager::getTransfers(0, NULL, $dateFrom->format('Y-m-d')); 
			$_max	 	= count($_transfers_recientes);
			if ($_max != 0) { 
				for( $k=0; $k < $_max; $k++ ){
					if ($k < $_max){
						$_transfer_r 	= 	$_transfers_recientes[$k];						
						$_fecha 	=	$_transfer_r["ptfechapedido"];
						$_nropedido	= 	$_transfer_r["ptidpedido"];	
						$_nombre	= 	$_transfer_r["ptclirs"];

						$_detalle	= 	sprintf( "<a href=\"detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-lista-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-lista.png';\" border=\"0\" align=\"absmiddle\" />");
					}	
					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td>", $_fecha, $_nropedido, $_nombre, $_detalle);
					echo sprintf("</tr>");
				}
			} else {
				?>
				<tr>
					<td scope="colgroup" colspan="3" height="25" align="center">No hay pedidos Transfer enviados</td>
				</tr>
				<?php
			} ?>
			</tbody>
		</table>
	</div> <!-- Fin listar -->	
	<?php }?>  

	<?php if ($_SESSION["_usrrol"]=="V" || $_SESSION["_usrrol"]== "A"){ ?>
	<div class="barra">
		<div class="buscadorizq">
			<h1>Mi Historial Transfers</h1>            	
		</div>
		<div class="buscadorder">
			<input id="txtBuscar4" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn4" type="text" value="tblMisPTEnviados" hidden/> 
			<a href="logica/exportar.historial.php" title="Exportar Mi Historial"> 
				<img src="/pedidos/images/icons/export_excel.png" onmouseover="this.src='/pedidos/images/icons/export_excel-hover.png';" onmouseout="this.src='/pedidos/images/icons/export_excel.png';" border="0" align="absmiddle"/>
			</a>    
		</div>  
		<hr>    
	</div> <!-- Fin barra -->

	<div class="lista"> 
		<table id="tblMisPTEnviados" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td scope="col" width="20%" height="18">Fecha</td>
					<td scope="col" width="20%">Transfer</td>
					<td scope="col" width="50%">Cliente</td>
					<td scope="colgroup" width="10%" align="center">Acciones</td>
				</tr>
			</thead>			
			<?php
			$_transfers_recientes	= DataManager::getTransfers(0, $_SESSION["_usrid"]); 
			$_max	 	= count($_transfers_recientes);
			if ($_max != 0) { 
				for( $k=0; $k < $_max; $k++ ){
					if ($k < $_max){
						$_transfer_r 	= 	$_transfers_recientes[$k];
						$fecha 		= 	explode(" ", $_transfer_r["ptfechapedido"]);
									list($ano, $mes, $dia) 	= 	explode("-", $fecha[0]);
						$_fecha 	= 	$dia."-".$mes."-".$ano;			
						$_nropedido	= 	$_transfer_r["ptidpedido"];	
						$_nombre	= 	$_transfer_r["ptclirs"];	

						$_detalle	= 	sprintf( "<a href=\"detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/icono-lista.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-lista-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-lista.png';\" border=\"0\" align=\"absmiddle\" />");										
					} else {
						$_fecha			= "&nbsp;";
						$_nropedido		= "&nbsp;";
						$_nombre		= "&nbsp;";
						$_detalle		= "&nbsp;";
					}

					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td>", $_fecha, $_nropedido, $_nombre, $_detalle);
					echo sprintf("</tr>");
				}
			} else { ?>
				<tr>
					<td scope="colgroup" colspan="5" height="25" align="center">No hay pedidos Transfer enviados</td>
				</tr>
				<?php
			} ?>
		</table>
	</div> <!-- Fin listar -->	
	<?php }?> 
</div> <!-- Fin datos -->