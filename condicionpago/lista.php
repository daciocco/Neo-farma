<?php
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/condicionpago/': $_REQUEST['backURL'];

//BARRA DE HERRAMIENTAS (condicion de pago)
$_links 		= array();
$_links[1][]	= array('url'=>'editar.php', 'texto'=>'<img src=../images/icons/icono-nuevo50.png border=0 align=absmiddle title=nueva_condicion />', 'class'=>'newitem');

$_params 		= array(
	'modo'		=> 1,
	'separador' => '',
	'estilo'	=> 'toolbar',
	'aspecto'	=> 'links',
	'links'		=> $_links[1]);
$bar = ToolBar::factory($_params);
//BARRA DE HERRAMIENTAS (condicion de pago transfer)
$_links2		= array();
$_links2[1][]	= array('url'=>'editar_transfer.php', 'texto'=>'<img src=../images/icons/icono-nuevo50.png border=0 align=absmiddle title=nueva_condicion_transfer />', 'class'=>'newitem');

$_params2 		= array(
	'modo'		=> 1,
	'separador' => '',
	'estilo'	=> 'toolbar',
	'aspecto'	=> 'links',
	'links'		=> $_links2[1]);
$bar2 = ToolBar::factory($_params2);
/****************************************/
$_LPP		= 25;
$_total 	= DataManager::getNumeroFilasTotales('TCondicionPago', 0); 
$_paginas 	= ceil($_total/$_LPP);
$_pag		= isset($_REQUEST['pag']) ? min(max(1,$_REQUEST['pag']),$_paginas) : 1;

$_imgFirst	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-first.png");
$_imgLast 	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-last.png");
$_imgNext	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-next.png");
$_imgPrev	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-previous.png");

$_GOFirst	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, 1,			$_imgFirst);
$_GOPrev	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag-1,	$_imgPrev);
$_GONext	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag+1,	$_imgNext);
$_GOLast	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_paginas,	$_imgLast);
/*****************************************/
$_total 	= DataManager::getNumeroFilasTotales('TCondiciontransfer', 0); 
$_paginas2 	= ceil($_total/$_LPP);
$_pag2		= isset($_REQUEST['pag2']) ? min(max(1,$_REQUEST['pag2']),$_paginas2) : 1;

$_GOFirst2	= sprintf("<a href=\"%s?pag2=%d\">%s</a>", $backURL, 1,			$_imgFirst);
$_GOPrev2	= sprintf("<a href=\"%s?pag2=%d\">%s</a>", $backURL, $_pag2-1,	$_imgPrev);
$_GONext2	= sprintf("<a href=\"%s?pag2=%d\">%s</a>", $backURL, $_pag2+1,	$_imgNext);
$_GOLast2	= sprintf("<a href=\"%s?pa2g=%d\">%s</a>", $backURL, $_paginas2,$_imgLast);


?>
<div class="box_body"> <!-- datos --> 
	<div class="barra">
		<div class="buscadorizq" align="left">
			<h1>Condiciones de Pago (Neo-farma)</h1>               	
		</div>
		<hr>
	</div> <!-- Fin barra -->
	
	<div class="lista_super">
		<table id="tblcondiciones" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
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
					$_dias		= "(";					
					$_dias		.= empty($_condicion['Dias1CP']) ? '' : $_condicion['Dias1CP'];
					$_dias		.= empty($_condicion['Dias2CP']) ? '' : ', '.$_condicion['Dias2CP'];
					$_dias		.= empty($_condicion['Dias3CP']) ? '' : ', '.$_condicion['Dias3CP'];
					$_dias		.= empty($_condicion['Dias4CP']) ? '' : ', '.$_condicion['Dias4CP'];
					$_dias		.= empty($_condicion['Dias5CP']) ? '' : ', '.$_condicion['Dias5CP'];
					$_dias		.= " D&iacute;as)";					
					$_porc		= ($_condicion['Porcentaje1CP']== '0.00') ? '' : $_condicion['Porcentaje1CP'];

					$_status	= 	($_condicion['condactiva']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Activar\"/>";					
					$_editar	= 	sprintf( "<a href=\"editar.php?condid=%d&backURL=%s\" title=\"Editar\">%s</a>", $_condicion['condid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");			
					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?condid=%d&backURL=%s&pag=%s\" title=\"borrar condición\">%s</a>", $_condicion['condid'], $_SERVER['PHP_SELF'], $_pag, $_status);				
					$_eliminar 	= 	sprintf ("<a href=\"logica/eliminar.condicion.php?condid=%d&backURL=%s&pag=%s\" title=\"Eliminar\" onclick=\"javascript:return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR LA CONDICI&Oacute;N?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /></a>", $_condicion['condid'], $_SERVER['PHP_SELF'], $_pag, "eliminar");								
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
	<div class="toolbar" style="margin:10px 0;padding-left:5px;" ><?php $bar->show(); ?></div>
</div> 

<div class="box_seccion">
	<div class="barra">
		<div class="buscadorizq">
			<h1>Condiciones de Pago Transfer</h1> 
		</div>
		<hr>
	</div> <!-- Fin barra -->
	
	<div class="lista">
		<table id="tblcondiciones" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
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

					$_status2	= 	($_condiciontrans['condactiva']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";					
					$_editar2	= 	sprintf( "<a href=\"editar_transfer.php?condid=%d&backURL=%s\" title=\"editar condicion transfer\">%s</a>", $_condiciontrans['condid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");			
					$_borrar2	= sprintf( "<a href=\"logica/changestatus.transfer.php?condid=%d&backURL=%s&pag2=%s\" title=\"borrar condición transfer\">%s</a>", $_condiciontrans['condid'], $_SERVER['PHP_SELF'], $_pag2, $_status2);								
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
	<div class="toolbar" style="margin:10px 0;padding-left:5px;" ><?php $bar2->show(); ?></div>
        
</div> 