<?php
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/soporte/tickets/': $_REQUEST['backURL'];
$_LPP		= 10;
$_total 	= count(DataManager::getTicket(0, 0, NULL, $_SESSION["_usrid"]));
$_LPP		= ($_total < $_LPP) ? $_total : $_LPP;
$_paginas 	= (empty($_total)) ? 1 : ceil($_total/$_LPP);
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
<div class="box_down">
    <div class="barra">
    	<div class="buscadorizq">
            <h1>Mis Consultas</h1>
        </div>
        <div class="buscadorder">
            <a href="nuevo/"><input type="button" value="NUEVA CONSULTA"/></a> 
        </div> 
        <hr>     
    </div> <!-- Fin barra -->
    
	<table id="tblSoporte" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0"> <?php
		$ticket	= DataManager::getTicket($_pag, $_LPP, NULL, $_SESSION["_usrid"]);
		$_max	= count($ticket);
		for( $k=0; $k < $_LPP; $k++ ) {
			if ($k < $_max) {
				$tk 		= $ticket[$k];	
				$tkId		= "Nro. ".$tk['tkid'];
				$idSector	= $tk['tkidsector'];
				$idMotivo	= $tk['tkidmotivo'];
				$estado		= $tk['tkestado'];  
				$creado		= $tk['tkcreated'];
				
				$sectores	=	DataManager::getTicketSector();
				if (count($sectores)) {
					foreach( $sectores as $j => $sec ) {	
						$idSec		= $sec['tksid'];
						if($idSector == $idSec){
							$sector	= $sec['tksnombre']; 
						}
					}
				}

				$motivos	= DataManager::getTicketMotivos($idSector); 
				if (count($motivos)) {
					foreach ($motivos as $j => $mot) {
						$idMot	= $mot['tkmotid'];
						if($idMotivo == $idMot){
							$motivo	= $mot['tkmotmotivo']; 
						}
					}
				}

				switch($estado){
					case 0:
						$_status	= "<input type=\"button\" value=\"RESPONDIDO\" style=\"background-color: gray;\">";
						break;
					case 1: 
						$_status	= "<input type=\"button\" value=\"ACTIVO\" style=\"background-color:green;\">";
						break;
				}

				$_lista	= sprintf( "<a href=\"../mensajes/?tkid=%d&pag=%s\">%s</a>", $tk['tkid'], $_pag, "<img src=\"/pedidos/images/icons/icono-next.png\" border=\"0\" align=\"absmiddle\" />");

			} else {
				$tkId	= "&nbsp;";
				$sector	= "&nbsp;";
				$motivo	= "&nbsp;";
				$_status= "&nbsp;";
				$creado	= "&nbsp;";
				$_lista	= "&nbsp;";
			}

			echo sprintf("<tr class=\"%s\" >", ((($k % 2) == 0)? "par" : "impar"));
			echo sprintf("<td height=\"100\" >%s <br> %s </td><td>%s</td><td>%s </td><td>%s</td><td align=\"center\">%s</td>", $creado, $tkId, $_status, $sector, $motivo, $_lista);
			echo sprintf("</tr>");

		} ?>
	</table>
    
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
	
	<hr>
    
</div>
