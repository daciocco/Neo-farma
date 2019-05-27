<div class="box_down">  <!-- datos -->
	<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){ ?> 
	<div class="barra">
		<div class="bloque_5" align="left">
			<h1>Pendientes</h1>              	
		</div>	
		<div class="bloque_6" align="right">
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblPTPendientes" hidden/>
		</div>
		<div class="bloque_8" align="right">
			<?php if ($_SESSION["_usrrol"]!= "G"){ ?>
				<a href="logica/enviar.pedido.php" title="Enviar"> 
					<img class="icon-send"/>
				</a>
			<?php } ?>
		</div>  
		<hr>            
	</div> <!-- Fin barra -->

	<div class="lista_super">          
		<table id="tblPTPendientes">
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
						
						$_eliminar 	= sprintf ("<a href=\"logica/eliminar.pedido.php?ptid=%d&backURL=%s\" title=\"eliminar pedido\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PEDIDO?')\"> <img class=\"icon-delete\"/> </a>", $_nropedido, $_SERVER['PHP_SELF'], "eliminar");	
						
						$_detalle	= 	sprintf( "<a href=\"detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img class=\"icon-detail\"/>");

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
		<div class="bloque_5" align="left">
			<h1>Mis Pendientes</h1>    	
		</div>
		<div class="bloque_6" align="right">
			<input id="txtBuscar2" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn2" type="text" value="tblMisPTPendientes" hidden/> 
		</div>   
		<hr>   
	</div> <!-- Fin barra -->

	<div class="lista_super">        
		<table id="tblMisPTPendientes">
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

						$_eliminar 	= sprintf ("<a href=\"logica/eliminar.pedido.php?ptid=%d&backURL=%s\" title=\"eliminar pedido\" onclick=\"return confirmar('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PEDIDO?')\"> <img class=\"icon-delete\"/> </a>", $_nropedido, $_SERVER['PHP_SELF'], "eliminar");		

						$_detalle	= 	sprintf( "<a href=\"detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img class=\"icon-detail\"/>");

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