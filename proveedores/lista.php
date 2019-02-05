<?php
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/proveedores/'	:	$_REQUEST['backURL'];
$_LPP		= 1000;
$_total 	= DataManager::getNumeroFilasTotales('TProveedor', 0);
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
$_total 	= count(DataManager::getProveedores($_pag, $_LPP, 3, NULL)); 
$_paginas2 	= ceil($_total/$_LPP);
$_pag2		= isset($_REQUEST['pag2']) ? min(max(1,$_REQUEST['pag2']),$_paginas2) : 1;

$_GOFirst2	= sprintf("<a href=\"%s?pag2=%d\">%s</a>", $backURL, 1,			$_imgFirst);
$_GOPrev2	= sprintf("<a href=\"%s?pag2=%d\">%s</a>", $backURL, $_pag2-1,	$_imgPrev);
$_GONext2	= sprintf("<a href=\"%s?pag2=%d\">%s</a>", $backURL, $_pag2+1,	$_imgNext);
$_GOLast2	= sprintf("<a href=\"%s?pa2g=%d\">%s</a>", $backURL, $_paginas2,$_imgLast);

//BARRA DE HERRAMIENTAS (PROVEEDORES)
$_links 		= array();
$_links[1][]	= array('url'=>'editar.php', 'texto'=>'<img src=../images/icons/icono-nuevo50.png border=0 align=absmiddle title=Nuevo Proveedor />', 'class'=>'newitem');
$_params 		= array(
	'modo'		=> 1,
	'separador' => '',
	'estilo'	=> 'toolbar',
	'aspecto'	=> 'links',
	'links'		=> $_links[1]);
$bar = ToolBar::factory($_params);
/****************************************/
?>

<div class="box_down"> 
    <div class="barra">
    	<div class="buscadorizq">
            <h1>Proveedores</h1>                	
        </div>
        <div class="buscadorder">
            <input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblProveedores" hidden/>
        </div> 
        <hr>     
    </div> <!-- Fin barra -->
    
    <div class="lista_super">
		<table id="tblProveedores" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" align="left">Emp</th>
					<th scope="col" align="left" height="18">C&oacute;digo</th>
					<th scope="col" align="left" >Nombre</th>
					<th scope="col" align="left" >Localidad</th>
					<th scope="col" align="center">Correo</th>
					<th scope="col" align="center" >Tel&eacute;fono</th>
					<th scope="colgroup" colspan="2" align="center" width="15">Acciones</th>
				</tr>
			</thead>
			<?php 	

			$_proveedores	= DataManager::getProveedores($_pag, $_LPP, NULL, NULL);
			$_max	 		= count($_proveedores); 	// la última página vendrá incompleta
			for( $k=0; $k < $_LPP; $k++ ) {
				if ($k < $_max) {
					$_prov 		= $_proveedores[$k];	
					$_idempresa	= $_prov['providempresa'];
					$_providprov= $_prov['providprov'];
					$_nombre	= $_prov['provnombre'];
					$_localidad	= $_prov['providloc'];		
					$_correo	= $_prov['provcorreo'];
					$_telefono	= $_prov['provtelefono'];
					$_estado	= $_prov['provactivo'];                

					$_status	= ($_estado) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Activar\"/>";			
					$_editar	= sprintf( "<a href=\"editar.php?provid=%d&backURL=%s&pag=%s\" title=\"Editar Proveedor\">%s</a>", $_prov['provid'], $_SERVER['PHP_SELF'], $_pag, "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");
					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?provid=%d&backURL=%s&pag=%s\" title=\"Borrar Proveedor\">%s</a>", $_prov['provid'], $_SERVER['PHP_SELF'], $_pag, $_status);

					//$_eliminar	=($_SESSION["_usrrol"]=="A")	?	sprintf ("<a href=\"logica/eliminar.proveedor.php?provid=%d&backURL=%s&pag=%s\" title=\"Eliminar Proveedor\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PROVEEDOR?')\"> <img src=\"../images/icons/icono-eliminar.png\" border=\"0\" align=\"absmiddle\" /></a>", $_prov['provid'], $_SERVER['PHP_SELF'], $_pag, "Eliminar")	:	'';

				} else {
					$_idempresa	= "&nbsp;";
					$_providprov= "&nbsp;";
					$_nombre	= "&nbsp;";
					$_localidad	= "&nbsp;";	
					$_correo	= "&nbsp;";
					$_telefono	= "&nbsp;";
					$_editar	= "&nbsp;";
					$_borrar	= "&nbsp;";
					//$_eliminar 	= "&nbsp;";
				}

				if($_estado != 3){
					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\" align=\"center\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_idempresa, $_providprov, $_nombre, $_localidad, $_correo, $_telefono,  $_editar, $_borrar);
					echo sprintf("</tr>");
				}

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
	}

	if ($_SESSION["_usrrol"]=="A"){ ?>
		<div class="toolbar" style="margin:10px 0;padding-left:5px;"><?php $bar->show(); ?></div>
	<?php } ?>
	
	<hr>

    <div class="barra">
        <div class="buscadorizq">
            <h1>Solicitud de alta de Proveedores</h1>                	
        </div>
        <div class="buscadorder">
            <input id="txtBuscar2" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn2" type="text" value="tblProveedores2" hidden/>
        </div>   
        <hr>   
    </div> <!-- Fin barra -->
    
    <div class="lista_super">
        <table id="tblProveedores2" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
				<tr>
					<th scope="col" align="left">Emp</th>
					<th scope="col" align="left" height="18">C&oacute;digo</th>
					<th scope="col" align="left" >Nombre</th>
					<th scope="col" align="left" >Localidad</th>
					<th scope="col" align="left" >CP</th>
					<th scope="col" align="left" >CUIT</th>
					<th scope="col" align="center">Correo</th>
					<th scope="col" align="center" >Tel&eacute;fono</th>
					<th scope="colgroup" colspan="3" align="center" width="15">Acciones</th>
				</tr>
			</thead>
			<?php 	

			$_proveedores	= DataManager::getProveedores($_pag2, $_LPP, NULL, 3);
			$_max	 		= count($_proveedores); 	// la última página vendrá incompleta
			for( $k=0; $k < $_LPP; $k++ ) {
				if ($k < $_max) {
					$_prov 		= $_proveedores[$k];	
					$_idempresa	= $_prov['providempresa'];
					$_providprov= $_prov['providprov'];
					$_nombre	= $_prov['provnombre'];
					$_localidad	= $_prov['providloc'];
					$_cp		= $_prov['provcp'];
					$_cuit		= $_prov['provcuit'];			
					$_correo	= $_prov['provcorreo'];
					$_telefono	= $_prov['provtelefono'];            

					$_editar	= sprintf( "<a href=\"editar.php?provid=%d&backURL=%s&pag=%s\" title=\"Editar Proveedor\">%s</a>", $_prov['provid'], $_SERVER['PHP_SELF'], $_pag2, "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");

					$_borrar = '';				
					if($_providprov != 0){
						$_status	= "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"Activar\"/>";
						$_borrar	= sprintf( "<a href=\"logica/changestatus.php?provid=%d&backURL=%s&pag=%s\" title=\"Borrar Proveedor\">%s</a>", $_prov['provid'], $_SERVER['PHP_SELF'], $_pag2, $_status);
					}                

					$_eliminar	= sprintf ("<a href=\"logica/eliminar.proveedor.php?provid=%d&backURL=%s&pag=%s\" title=\"Eliminar Proveedor\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL PROVEEDOR?')\"> <img src=\"../images/icons/icono-eliminar.png\" border=\"0\" align=\"absmiddle\" /></a>", $_prov['provid'], $_SERVER['PHP_SELF'], $_pag2, "Eliminar");	                
				} else {
					$_idempresa	= "&nbsp;";
					$_providprov= "&nbsp;";
					$_nombre	= "&nbsp;";
					$_localidad	= "&nbsp;";			
					$_cp		= "&nbsp;";
					$_cuit		= "&nbsp;";	
					$_correo	= "&nbsp;";
					$_telefono	= "&nbsp;";
					$_editar	= "&nbsp;";
					$_borrar	= "&nbsp;";
					$_eliminar 	= "&nbsp;";
				}

				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\" align=\"center\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_idempresa, $_providprov, $_nombre, $_localidad, $_cp, $_cuit, $_correo, $_telefono,  $_editar, $_borrar, $_eliminar);
				echo sprintf("</tr>");

			}  ?>
        </table>
    </div> <!-- Fin listar -->	
    
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
