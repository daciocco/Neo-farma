<div class="box_down"> <!-- datos --> 
   <?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){ ?>
	<div class="barra">
		<div class="bloque_5" align="left">
			<h1>Enviados Recientes</h1>    	
		</div>
		<div class="bloque_6" align="right">
			<input id="txtBuscar3" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn3" type="text" value="tblPTEnviados" hidden/> 			   
		</div>		
		<div class="bloque_8" align="right">
			<a href="../logica/exportar.historial.total.php" title="Exportar Historial"><img class="icon-xls-export"/></a>            
		</div>      
		<hr> 
	</div> <!-- Fin barra -->

	<div class="lista_super">
		<table id="tblPTEnviados" >
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

						$_detalle	= 	sprintf( "<a href=\"../detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img class=\"icon-detail\"/>");
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
		<div class="bloque_5" align="left">
			<h1>Mi Transfers Enviado</h1>            	
		</div>
		<div class="bloque_6" align="right">
			<input id="txtBuscar4" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn4" type="text" value="tblMisPTEnviados" hidden/> 
        </div>		
		<div class="bloque_8" align="right">
			<a href="../logica/exportar.historial.php" title="Exportar Mi Historial"> 
				<img class="icon-xls-export"/>
			</a>    
		</div>  
		<hr>    
	</div> <!-- Fin barra -->

	<div class="lista_super"> 
		<table id="tblMisPTEnviados">
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

						$_detalle	= 	sprintf( "<a href=\"../detalle_transfer.php?idpedido=%d&backURL=%s\" target=\"_blank\" title=\"detalle pedido\">%s</a>", $_nropedido, $_SERVER['PHP_SELF'], "<img class=\"icon-detail\"/>");										
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