<?php
$_LPP		= 10;
$_total 	= DataManager::getNumeroFilasTotales('TLista', 0);
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
			<h1>Listas Especiales</h1>                	
        </div>
        <hr>
	</div> <!-- Fin barra -->
    
    <div class="lista_super">
        <table class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="colgroup" width="20%" height="18">Lista</th>
                    <th scope="colgroup" width="10%">Cond. de Pago</th>
                    <th scope="colgroup" width="10%">Fecha Inicio</th>
                    <th scope="colgroup" width="10%">Fecha Fin</th>
                    <th scope="colgroup" width="25%">Observación</th>
                    <th scope="colgroup" colspan="5" align="center" width="15%">Acciones</th>
                </tr>
            </thead>
            <?php 	
            $_listas	= DataManager::getListasEspeciales($_pag, $_LPP);
			$_max	 	= count($_listas); 	// la última página vendrá incompleta
            for( $k=0; $k < $_LPP; $k++ ) {
                if ($k < $_max) {			
						$_lista 		= 	$_listas[$k];
						$_nombre		= 	$_lista['listanombre'];
						$_condicion		= 	$_lista['listacondpago'];
						$_fechainicio	=	dac_invertirFecha( $_lista['listafechainicio'] );
						$_fechafin		=	dac_invertirFecha( $_lista['listafechafin'] );
						
						$_observacion	= $_lista['listaobservacion'];
						
						//$_boton_copy	= 	sprintf( "<img src=\"/pedidos/images/icons/ico-copy.png\" border=\"0\" align=\"absmiddle\" title=\"Duplicar Lista\" onclick=\"javascript:dac_DuplicarLista(%d)\"/>", $_lista['listaid']);	
						
						$_status	= 	($_lista['listaactiva']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";			
						
						//$_editar	= 	sprintf( "<a href=\"editar.php?listaid=%d&backURL=%s\" title=\"editar lista\">%s</a>", $_lista['listaid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");
						
						$_exportar	= 	sprintf( "<a href=\"logica/exportar.lista.php?listaid=%d\" title=\"editar lista\">%s</a>", $_lista['listaid'], "<img src=\"../images/icons/export_excel.png\" border=\"0\" align=\"absmiddle\" />");
						
						$_borrar	= sprintf( "<a href=\"logica/changestatus.php?listaid=%d&backURL=%s\" title=\"borrar lista\">%s</a>", $_lista['listaid'], $_SERVER['PHP_SELF'], $_status);				
						
						//$_eliminar 	= 	sprintf ("<a href=\"logica/eliminar.lista.php?listaid=%d&backURL=%s\" title=\"eliminar lista\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR LA LISTA?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /></a>", $_lista['listaid'], $_SERVER['PHP_SELF'], "eliminar");
						
						echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
						echo sprintf("<td height=\"15\" align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_nombre, $_condicion, $_fechainicio, $_fechafin, $_observacion, $_editar, $_borrar, $_boton_copy, $_exportar, $_eliminar);
						echo sprintf("</tr>");
				} else {
                    $_nombre		= "&nbsp;";
                    $_cantidad		= "&nbsp;";
                    $_condicion		= "&nbsp;";
                    $_fechainicio	= "&nbsp;";
                    $_fechafin		= "&nbsp;";
                    $_observacion	= "&nbsp;";
                    $_editar		= "&nbsp;";
					$_exportar		= "&nbsp;";
                    $_borrar		= "&nbsp;";
                    $_eliminar		= "&nbsp;";
					$_boton_copy	= "&nbsp;";
                }
				
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
		echo sprintf("<td height=\"17\">Mostrando p&aacute;gina %d de %d</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td>", $_pag, $_paginas, $_First, $_Prev, $_Next, $_Last); 
		echo("</tr></table>"); 
	} ?>
	<div class="toolbar" style="margin:10px 0;padding-left:5px;"><?php //$bar->show(); ?></div>
	
</div>

<script type="text/javascript">
function dac_DuplicarLista(listaid){	
	if(confirm("¿Desea duplicar la Lista?")){		
		$.ajax({
			type : 'POST',
			url : 'logica/ajax/duplicar.lista.php',					
			data:{	listaid	:	listaid, },				
			success : function (resultado) { 								
				if (resultado){
					if (resultado == "1"){
						alert("La Lista fue duplicada");
						location.reload();
					} else {
						alert(resultado);
					}						
				}															
			},
			error: function () {alert("Error en el proceso de duplicado.");}								
		});		
	}
}
</script>


        
