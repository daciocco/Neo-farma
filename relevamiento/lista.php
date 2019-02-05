
<div class="box_body"> <!-- datos --> 
	<?php
	$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/relevamiento/': $_REQUEST['backURL'];
	$_LPP		= 1000;
	$_total 	= DataManager::getNumeroFilasTotales('TRelevamiento', 0); 
	//count(DataManager::getRelevamientos($_pag, $_LPP, NULL)); //
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
	<div class="barra">
       	<div class="buscadorizq">
			<h1>Relevamiento</h1>                	
        </div>
        <hr>
	</div> <!-- Fin barra -->
    
    <div class="lista_super">
        <table id="tblRelevamientos" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th scope="col" align="left" height="18">Relev</th>
                <th scope="col" align="left" >Orden</th>
                <th scope="col" align="left" >Pregunta</th>
                <th scope="col" align="left" >Tipo Respuesta</th>
                <th scope="colgroup" colspan="3" align="center" width="15">Acciones</th>
            </tr>
        </thead>
        <?php 	
    
        $_relevamientos	= DataManager::getRelevamientos($_pag, $_LPP, NULL);
        $_max	 		= count($_relevamientos); 	// la última página vendrá incompleta
        for( $k=0; $k < $_LPP; $k++ ) {
            if ($k < $_max) {
                $_rel 		= $_relevamientos[$k];			
                $_relid		= $_rel['relid'];
                $_nrorel	= $_rel['relidrel'];
                $_orden		= $_rel['relpregorden'];
                $_direccion = $_rel['relpregunta'];
                $_etiporesp	= $_rel['reltiporesp'];		
                
				$_status	= ($_rel['relactivo']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Activar\"/>";							
                
				$_borrar	= sprintf( "<a href=\"logica/changestatus.php?relid=%d&backURL=%s&pag=%s\" title=\"Borrar Relevamiento\">%s</a>", $_relid, $_SERVER['PHP_SELF'], $_pag, $_status);
				
				
				$_editar	= sprintf( "<a href=\"editar.php?relid=%d&backURL=%s&pag=%s\" title=\"Editar Relevamiento\" target=\"_blank\">%s</a>", $_relid, $_SERVER['PHP_SELF'], $_pag, "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");
                
				$_eliminar	= sprintf ("<a href=\"logica/eliminar.relevamiento.php?relid=%d&backURL=%s&pag=%s\" title=\"Eliminar Relevamiento\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL RELEVAMIENTO?')\"> <img src=\"../images/icons/icono-eliminar.png\" border=\"0\" align=\"absmiddle\" /></a>", $_rel['relid'], $_SERVER['PHP_SELF'], $_pag, "Eliminar");
                echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
                echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_nrorel, $_orden, $_direccion, $_etiporesp, $_editar, $_borrar, $_eliminar);
                echo sprintf("</tr>");					
            } else {
                $_relid		= "&nbsp;";
                $_nrorel	= "&nbsp;";
                $_orden		= "&nbsp;";
                $_direccion = "&nbsp;";
                $_etiporesp	= "&nbsp;";	
                $_editar	= "&nbsp;";
                $_borrar	= "&nbsp;";
				$_eliminar	= "&nbsp;";
            }
        } ?>
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
	
	<div class="toolbar" style="margin:10px 0;padding-left:5px;"><?php $bar->show(); ?></div>
	
</div> <!-- Fin datos -->


