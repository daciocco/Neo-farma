<?php
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/acciones/': $_REQUEST['backURL'];
$_LPP		= 10;
$_total 	= DataManager::getNumeroFilasTotales('TAccion', 0); 
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
?>

<div class="box_body">
	<div class="barra">
       	<div class="buscadorizq">
			<h1>Acciones</h1>                	
        </div>
        <hr>
	</div> <!-- Fin barra -->
    
    <div class="lista_super">
		<table id="tblAcciones" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td scope="col" >id Acci&oacute;n</td>
				<td scope="col" >Nombre</td>
				<td scope="col" >Siglas</td>
				<td scope="colgroup" colspan="3" align="center">Acciones</td>
			</tr>	
		</thead>			
		<?php
		$_acciones	= DataManager::getAcciones($_pag, $_LPP, FALSE); 
		$_max	 	= count($_acciones); 
		for( $k=0; $k < $_LPP; $k++ ){
			if ($k < $_max){
				$_accion 		= $_acciones[$k];
				$_id			= $_accion['acid'];	
				$_nombre		= $_accion['acnombre'];
				$_sigla			= $_accion['acsigla'];

				$_status	= 	($_accion['acactiva']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";					
				$_editar	= 	sprintf( "<a href=\"editar.php?acid=%d&backURL=%s\" title=\"editar acción\">%s</a>", $_accion['acid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");			
				$_borrar	= sprintf( "<a href=\"logica/changestatus.php?acid=%d&backURL=%s&pag=%s\" title=\"borrar acción\">%s</a>", $_accion['acid'], $_SERVER['PHP_SELF'], $_pag, $_status);				
				$_eliminar 	= 	sprintf ("<a href=\"logica/eliminar.accion.php?acid=%d&backURL=%s&pag=%s\" title=\"eliminar acción\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR LA ACCI&Oacute;N?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /></a>", $_accion['acid'], $_SERVER['PHP_SELF'], $_pag, "eliminar");								
			} else {
				$_id			= "&nbsp;";			
				$_nombre		= "&nbsp;";
				$_sigla			= "&nbsp;";
				$_editar		= "&nbsp;";
				$_borrar		= "&nbsp;";
				$_eliminar		= "&nbsp;";
			}
			echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
			echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_id, $_nombre, $_sigla, $_editar, $_borrar, $_eliminar);
			echo sprintf("</tr>");
		}
		?>
		</table>
		
	</div> <!-- Fin lista -->	
	
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
	<div class="toolbar" ><?php $bar->show(); ?></div>
</div> <!-- Fin body -->
