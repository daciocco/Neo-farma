<?php
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/noticias/'	:	$_REQUEST['backURL'];
$_LPP		= 20;
$_total 	= DataManager::getNumeroFilasTotales('TNoticia', 0); 
$_paginas 	= ceil($_total/$_LPP);
$_pag		= isset($_REQUEST['pag']) ? min(max(1,$_REQUEST['pag']),$_paginas) : 1;

$_GOFirst	= sprintf("<a class=\"icon-go-first\" href=\"%s?pag=%d\"></a>", $backURL, 1);
$_GOPrev	= sprintf("<a class=\"icon-go-previous\" href=\"%s?pag=%d\"></a>", $backURL, $_pag-1);
$_GONext	= sprintf("<a class=\"icon-go-next\" href=\"%s?pag=%d\"></a>", $backURL, $_pag+1);
$_GOLast	= sprintf("<a class=\"icon-go-last\" href=\"%s?pag=%d\"></a>", $backURL, $_paginas);

$btnNuevo	= sprintf( "<a href=\"editar.php\" title=\"Nuevo\"><img class=\"icon-new\"/></a>");
?>

<div class="box_body"> 
	<div class="barra">
       	<div class="bloque_5">
			<h1>Noticias</h1>                	
        </div>
        <div class="bloque_7">
          	<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblNoticias" hidden/>
        </div> 
        <div class="bloque_7">	 
			<?php echo $btnNuevo; ?>
		</div>
        <hr>     
	</div> <!-- Fin barra -->

	<div class="lista_super" >                    
        <table id="tblNoticias">
            <thead>
                <tr>
                    <th scope="col" width="20%" height="18">Titular</th>
                    <th scope="col" width="10%">Fecha Publicaci&oacute;n</th>
                    <th scope="col" width="55%">Descripci&oacute;n</th>
                    <th scope="colgroup" colspan="3" width="15%" align="center">Acciones</th>
                </tr>	
            </thead>			
            <?php
            $_noticias	= DataManager::getNoticias($_pag,$_LPP); 
            $_max	 	= count($_noticias); 
            for( $k=0; $k < $_LPP; $k++ ){
                if ($k < $_max){
                    $_noticia 		= $_noticias[$k];						
						$fecha 		= 	explode(" ", $_noticia["ntfecha"]);
                        list($ano, $mes, $dia) 	= 	explode("-", $fecha[0]);
                    $_fecha 		= 	$dia."-".$mes."-".$ano;
                    $_titulo 		= 	$_noticia["nttitulo"];	
                    $_descripcion 	= substr($_noticia["ntdescripcion"], 0, 100)."...";
                
                    $_status	= 	($_noticia['ntactiva']) ? "<img class=\"icon-status-active\" title=\"desactivar\"/>" : "<img class=\"icon-status-inactive\" title=\"Activar\"/>";
                
                    $_editar	= 	sprintf( "<a href=\"editar.php?idnt=%d&backURL=%s\" title=\"editar noticia\">%s</a>", $_noticia['idnt'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit\" />");
                
                    $_borrar	= sprintf( "<a href=\"logica/changestatus.php?idnt=%d&backURL=%s&pag=%s\" title=\"borrar noticia\">%s</a>", $_noticia['idnt'], $_SERVER['PHP_SELF'], $_pag, $_status);
                    
                    $_eliminar 	= 	sprintf ("<a href=\"logica/eliminar.noticia.php?idnt=%d&backURL=%s\" title=\"eliminar noticia\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR LA NOTICIA?')\"> <img class=\"icon-delete\"/></a>", $_noticia['idnt'], $_SERVER['PHP_SELF'], "eliminar");								
                } else {
                    $_fecha			= "&nbsp;";
                    $_titulo		= "&nbsp;";
                    $_editar		= "&nbsp;";
                    $_borrar		= "&nbsp;";
                    $_eliminar		= "&nbsp;";
                    $_descripcion	= "&nbsp;";
                }
                echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
                echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_titulo, $_fecha, $_descripcion, $_editar, $_borrar, $_eliminar);
                echo sprintf("</tr>");
            } ?>
        </table>
	</div> <!-- Fin listar -->	
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
</div> <!-- Fin datos -->


