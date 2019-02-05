<?php
$_LPP		= 10;
$_total 	= DataManager::getNumeroFilasTotales('TPack', 0);
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
			<h1>Listado de Packs</h1>                	
        </div>
        <hr>
	</div> <!-- Fin barra -->
    
    <div class="lista_super"> 
    	<table class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="colgroup" width="20%" height="18">Pack</th>
                    <th scope="colgroup" width="10%">Cant. Mínimia</th>
                    <th scope="colgroup" width="10%">Cond. de Pago</th>
                    <th scope="colgroup" width="10%">Fecha Inicio</th>
                    <th scope="colgroup" width="10%">Fecha Fin</th>
                    <th scope="colgroup" width="25%">Observación</th>
                    <th scope="colgroup" colspan="4" align="center" width="15%">Acciones</th>
                </tr>
            </thead>
            <?php 	
            $_packs	= DataManager::getPacks($_pag, $_LPP);
            $_max	 	= count($_packs); 	// la última página vendrá incompleta
            for( $k=0; $k < $_LPP; $k++ ) {
                if ($k < $_max) {
                    $_pack 			= $_packs[$k];
                    $_nombre		= $_pack['packnombre'];
                    $_cantidad		= $_pack['packcantmin'];
                    $_condicion		= $_pack['packcondpago'];
                    $_fechainicio	= dac_invertirFecha( $_pack['packfechainicio']);
                    $_fechafin		= dac_invertirFecha( $_pack['packfechafin']);
                   
                    $_observacion	= $_pack['packobservacion'];
                    
                    $_status	= 	($_pack['packactiva']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";  
                    
                    $_borrar	= sprintf( "<a href=\"logica/changestatus.php?packid=%d&backURL=%s&pag=%s\" title=\"borrar pack\">%s</a>", $_pack['packid'], $_SERVER['PHP_SELF'], $_pag, $_status);
					
					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_nombre, $_cantidad, $_condicion, $_fechainicio, $_fechafin, $_observacion, $_editar, $_borrar, $_boton_copy, $_eliminar);
					echo sprintf("</tr>");
                } else {
                    $_nombre		= "&nbsp;";
                    $_cantidad		= "&nbsp;";
                    $_condicion		= "&nbsp;";
                    $_fechainicio	= "&nbsp;";
                    $_fechafin		= "&nbsp;";
                    $_observacion	= "&nbsp;";
                    $_editar		= "&nbsp;";
                    $_borrar		= "&nbsp;";
                    $_eliminar		= "&nbsp;";
					$_boton_copy	= "&nbsp;";
                }
                
            } 
            ?>
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
	<div class="toolbar" style="margin:10px 0;padding-left:5px;"></div>
</div>

<hr>
<script type="text/javascript">
	function dac_DuplicarPack(packid){	
		if(confirm("¿Desea duplicar el pack?")){		
			$.ajax({
				type : 'POST',
				url : 'logica/ajax/duplicar.pack.php',					
				data:{	packid	:	packid, },				
				success : function (resultado) { 								
					if (resultado){
						if (resultado == "1"){
							alert("El Pack fue duplicado");
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

