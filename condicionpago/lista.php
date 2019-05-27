<?php
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/condicionpago/': $_REQUEST['backURL'];
$btnNuevo	= 	sprintf( "<a href=\"editar.php\" title=\"Nuevo\"><img class=\"icon-new\"/></a>");	
$btnNuevo2	= 	sprintf( "<a href=\"editar_transfer.php\" title=\"Nuevo\"><img class=\"icon-new\"/></a>");		
$_LPP		= 25;
$_total 	= DataManager::getNumeroFilasTotales('TCondicionPago', 0); 
$_paginas 	= ceil($_total/$_LPP);
$_pag		= isset($_REQUEST['pag']) ? min(max(1,$_REQUEST['pag']),$_paginas) : 1;
$_GOFirst	= sprintf("<a class=\"icon-go-first\" href=\"%s?pag=%d\"></a>", $backURL, 1);
$_GOPrev	= sprintf("<a class=\"icon-go-previous\" href=\"%s?pag=%d\"</a>", $backURL, $_pag-1);
$_GONext	= sprintf("<a class=\"icon-go-next\" href=\"%s?pag=%d\"></a>", $backURL, $_pag+1);
$_GOLast	= sprintf("<a class=\"icon-go-last\" href=\"%s?pag=%d\"></a>", $backURL, $_paginas);

$_total 	= DataManager::getNumeroFilasTotales('TCondiciontransfer', 0); 
$_paginas2 	= ceil($_total/$_LPP);
$_pag2		= isset($_REQUEST['pag2']) ? min(max(1,$_REQUEST['pag2']),$_paginas2) : 1;
$_GOFirst2	= sprintf("<a class=\"icon-go-first\" href=\"%s?pag2=%d\"></a>", $backURL, 1);
$_GOPrev2	= sprintf("<a class=\"icon-go-previous\" href=\"%s?pag2=%d\"></a>", $backURL, $_pag2-1);
$_GONext2	= sprintf("<a class=\"icon-go-next\" href=\"%s?pag2=%d\"></a>", $backURL, $_pag2+1);
$_GOLast2	= sprintf("<a class=\"icon-go-last\" href=\"%s?pa2g=%d\"></a>", $backURL, $_paginas2);


?>
<div class="box_body"> <!-- datos --> 
	<div class="barra">
		<div class="bloque_5">
			<h1>Condiciones de Pago (Neo-farma)</h1>               	
		</div>
		<div class="bloque_5">
			<?php echo $btnNuevo; ?>                	
        </div>
		<hr>
	</div> <!-- Fin barra -->
	
	<div class="lista_super">
		<table id="tblcondiciones">
			<thead>
				<tr>
					<td scope="col" width="15%">C&oacute;digo</td>
					<td scope="col" width="35%">Nombre</td>
					<td scope="col" width="20%">D&iacute;as</td>
					<td scope="col" width="20%" align="center">%</td>
					<td scope="colgroup" colspan="3" width="30%" align="center">Acciones</td>
				</tr>	
			</thead> <?php
			$_condiciones	= DataManager::getCondicionesDePago($_pag, $_LPP); 
			$_max	 		= count($_condiciones); 
			for( $k=0; $k < $_LPP; $k++ ){
				if ($k < $_max){
					$_condicion = $_condiciones[$k];
					$_id		= $_condicion['condid'];	
					$_codigo	= $_condicion['IdCondPago'];					
					$_nombre	= DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $_condicion['condtipo']);
					
					$_dias = "(";					
					$_dias .= empty($_condicion['Dias1CP']) ? '0' : $_condicion['Dias1CP'];
					$_dias .= empty($_condicion['Dias2CP']) ? '' : ', '.$_condicion['Dias2CP'];
					$_dias .= empty($_condicion['Dias3CP']) ? '' : ', '.$_condicion['Dias3CP'];
					$_dias .= empty($_condicion['Dias4CP']) ? '' : ', '.$_condicion['Dias4CP'];
					$_dias .= empty($_condicion['Dias5CP']) ? '' : ', '.$_condicion['Dias5CP'];
					$_dias .= " D&iacute;as) ";	
					$_dias .= ($_condicion['Porcentaje1CP'] == '0.00') ? '' : ' '.$_condicion['Porcentaje1CP'].' %';
					
					$_porc		= ($_condicion['Porcentaje1CP']== '0.00') ? '' : $_condicion['Porcentaje1CP'];

					$_status	= 	($_condicion['condactiva']) ? "<img class=\"icon-status-active\"/>" : "<img class=\"icon-status-inactive\"/>";				
					$_editar	= 	sprintf( "<a href=\"editar.php?condid=%d&backURL=%s\" title=\"Editar\">%s</a>", $_condicion['condid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit\"/>");			
					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?condid=%d&backURL=%s&pag=%s\" title=\"Cambiar Estado\">%s</a>", $_condicion['condid'], $_SERVER['PHP_SELF'], $_pag, $_status);				
					$_eliminar 	= 	sprintf ("<a href=\"logica/eliminar.condicion.php?condid=%d&backURL=%s&pag=%s\" title=\"Eliminar\" onclick=\"javascript:return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR LA CONDICI&Oacute;N?')\"> <img class=\"icon-delete\"/></a>", $_condicion['condid'], $_SERVER['PHP_SELF'], $_pag, "eliminar");								
				} else {
					$_codigo	= "&nbsp;";			
					$_nombre	= "&nbsp;";
					$_dias		= "&nbsp;";
					$_porc		= "&nbsp;";
					$_editar	= "&nbsp;";
					$_borrar	= "&nbsp;";
					$_eliminar	= "&nbsp;";
				}
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_codigo, $_nombre, $_dias, $_porc, $_editar, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			} ?>
		</table>
	</div> 
	<?php        
	if ( $_paginas > 1 ) {
		$_First = ($_pag > 1) ? $_GOFirst : "&nbsp;";
		$_Prev	= ($_pag > 1) ? $_GOPrev : "&nbsp;";
		$_Last	= ($_pag < $_paginas) ? $_GOLast : "&nbsp;";
		$_Next	= ($_pag < $_paginas) ? $_GONext : "&nbsp;";
		echo("<table class=\"paginador\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>"); 
		echo sprintf("<td height=\"16\">Mostrando p&aacute;gina %d de %d</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td>", $_pag, $_paginas, $_First, $_Prev, $_Next, $_Last); 
		echo("</tr></table>"); 
	} ?>        
</div> 

<div class="box_seccion">
	<div class="barra">
		<div class="bloque_5">
			<h1>Condiciones de Pago Transfer</h1> 
		</div>
		<div class="bloque_5">
			<?php echo $btnNuevo2; ?>                	
        </div>
		<hr>
	</div> <!-- Fin barra -->
	
	<div class="lista">
		<table id="tblcondiciones">
			<thead>
				<tr>
					<td scope="col" width="15%">C&oacute;digo</td>
					<td scope="col" width="35%">Nombre</td>
					<td scope="col" width="20%">D&iacute;as</td>
					<td scope="col" width="20%" align="center">%</td>
					<td scope="colgroup" colspan="3" width="30%" align="center">Acciones</td>
				</tr>	
			</thead>			
			<?php
			$_condicionestransfer	= DataManager::getCondicionesDePagoTransfer($_pag2, $_LPP); 
			$_max	 		= count($_condicionestransfer); 
			for( $k=0; $k < $_LPP; $k++ ){
				if ($k < $_max){
					$_condiciontrans = $_condicionestransfer[$k];
					$_id		= $_condiciontrans['condid'];	
					$_codigo	= $_condiciontrans['condcodigo'];	
					$_nombre	= $_condiciontrans['condnombre'];
					$_dias		= $_condiciontrans['conddias'];
					$_porc		= $_condiciontrans['condporcentaje'];

					$_status2	= 	($_condiciontrans['condactiva']) ? "<img class=\"icon-status-active\" />" : "<img class=\"icon-status-inactive\"/>";					
					$_editar2	= 	sprintf( "<a href=\"editar_transfer.php?condid=%d&backURL=%s\" title=\"editar condicion transfer\">%s</a>", $_condiciontrans['condid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit\" />");			
					$_borrar2	= sprintf( "<a href=\"logica/changestatus.transfer.php?condid=%d&backURL=%s&pag2=%s\" title=\"Cambiar Estado\">%s</a>", $_condiciontrans['condid'], $_SERVER['PHP_SELF'], $_pag2, $_status2);								
				} else {
					$_codigo	= "&nbsp;";			
					$_nombre	= "&nbsp;";
					$_dias		= "&nbsp;";
					$_porc		= "&nbsp;";
					$_editar2	= "&nbsp;";
					$_borrar2	= "&nbsp;";
				}
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_codigo, $_nombre, $_dias, $_porc, $_editar2, $_borrar2);
				echo sprintf("</tr>");
			}
			?>
        </table>
	</div> 
	
	<?php        
	if ( $_paginas2 > 1 ) {
		$_First2 = ($_pag2 > 1) ? $_GOFirst2 : "&nbsp;";
		$_Prev2	= ($_pag2 > 1) ? $_GOPrev2 : "&nbsp;";
		$_Last2	= ($_pag2 < $_paginas2) ? $_GOLast2 : "&nbsp;";
		$_Next2	= ($_pag2 < $_paginas2) ? $_GONext2 : "&nbsp;";
		echo("<table class=\"paginador\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>"); 
		echo sprintf("<td height=\"16\">Mostrando p&aacute;gina %d de %d</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td>", $_pag2, $_paginas2, $_First2, $_Prev2, $_Next2, $_Last2); 
		echo("</tr></table>"); 
	} ?>
        
</div> 