<?php $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL']; ?>

<div class="box_body">
	<div class="barra">
       	<div class="buscadorizq">
			<h1>Vendedores</h1>                	
        </div>
        <div class="buscadorder">
          	<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblUsuarios" hidden/>
        </div>   
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista_super">     
		<table id="tblUsuarios" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" width="20%" height="18">Usuario</th>
					<th scope="colgroup" width="50%">Nombre</th>
					<th scope="colgroup" width="10%">Dni</th>
					<th scope="colgroup" colspan="4" align="center" width="20%">Acciones</th>
				</tr>
			</thead>
			<?php
			$_LPP		= 50;
			$_total 	= count(DataManager::getUsuarios( 0, 0, NULL, NULL, '"V"'));
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
			
			$usuarios	= DataManager::getUsuarios( $_pag, $_LPP, NULL, NULL, '"V"');
			$_max	 	= count($usuarios);
			for( $k=0; $k < $_max; $k++ ) {
				if ($k < $_LPP) {
					
					$usuario 	= $usuarios[$k];
					$nombre		= $usuario['unombre'];
					$usr		= $usuario['ulogin'];
					$dni		= $usuario['udni'];

					$_status	= ($usuario['uactivo']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";

					$_editar	= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");
					
					$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono_zona.png\" border=\"0\" align=\"absmiddle\" />");	

					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"borrar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);
					
					$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
				} else {
					$nombre	= "&nbsp;";
					$usr		= "&nbsp;";
					$dni		= "&nbsp;";
					$_editar	= "&nbsp;";
					$_zona		= "&nbsp;";
					$_borrar	= "&nbsp;";
					$_eliminar	= "&nbsp;";
				}
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $usr,  $nombre, $dni, $_editar, $_zona, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			}?>
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
	?>

	<div class="toolbar"><?php $bar->show(); ?></div>
</div> <!-- Fin datos -->

<div class="box_seccion">
	<div class="barra">
       	<div class="buscadorizq">
			<h1>Administrador Web</h1>                	
        </div>
        <div class="buscadorder">
          	<input id="txtBuscar2" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn2" type="text" value="tblAdmWeb" hidden/>
        </div>   
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista">     
		<table id="tblAdmWeb" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" width="20%" height="18">Usuario</th>
					<th scope="colgroup" width="50%">Nombre</th>
					<th scope="colgroup" colspan="4" align="center" width="30%">Acciones</th>
				</tr>
			</thead>
			<?php 	
			$usuarios	= DataManager::getUsuarios( 0, 0, NULL, NULL, '"A"');	
			foreach ($usuarios as $k => $usuario) {
				$nombre	= $usuario['unombre'];
				$usr	= $usuario['ulogin'];

				$_status= ($usuario['uactivo']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";

				$_editar= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");

				$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono_zona.png\" border=\"0\" align=\"absmiddle\" />");	
				
				$_borrar= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"borrar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);

				$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
				
				
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $usr, $nombre, $_editar, $_zona, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			}?>
		</table>
	</div> <!-- Fin listar -->	
	
	<div class="barra">
       	<div class="buscadorizq">
			<h1>Gerencias</h1>                	
        </div>
        <div class="buscadorder">
          	<input id="txtBuscar3" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn3" type="text" value="tblGerencias" hidden/>
        </div>   
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista">     
		<table id="tblGerencias" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" width="20%" height="18">Usuario</th>
					<th scope="colgroup" width="50%">Nombre</th>
					<th scope="colgroup" colspan="4" align="center" width="30%">Acciones</th>
				</tr>
			</thead>
			<?php 	
			$usuarios	= DataManager::getUsuarios( 0, 0, NULL, NULL, '"G"');	
			foreach ($usuarios as $k => $usuario) {
				$nombre	= $usuario['unombre'];
				$usr	= $usuario['ulogin'];

				$_status= ($usuario['uactivo']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";

				$_editar= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");

				$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono_zona.png\" border=\"0\" align=\"absmiddle\" />");	
				
				$_borrar= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"borrar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);

				$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
				
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $usr, $nombre, $_editar, $_zona, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			}?>
		</table>
	</div> <!-- Fin listar -->	
	
	<div class="barra">
       	<div class="buscadorizq">
			<h1>Administracion</h1>                	
        </div>
        <div class="buscadorder">
          	<input id="txtBuscar4" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn4" type="text" value="tblAdministracion" hidden/>
        </div>   
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista">     
		<table id="tblAdministracion" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" width="20%" height="18">Usuario</th>
					<th scope="colgroup" width="50%">Nombre</th>
					<th scope="colgroup" colspan="4" align="center" width="30%">Acciones</th>
				</tr>
			</thead>
			<?php 	
			$usuarios	= DataManager::getUsuarios( 0, 0, NULL, NULL, '"M"');	
			foreach ($usuarios as $k => $usuario) {
				$nombre	= $usuario['unombre'];
				$usr	= $usuario['ulogin'];

				$_status= ($usuario['uactivo']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";

				$_editar= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");

				$_borrar= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"borrar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);
				
				$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono_zona.png\" border=\"0\" align=\"absmiddle\" />");	

				$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
				
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $usr, $nombre, $_editar, $_zona, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			}?>
		</table>
	</div> <!-- Fin listar -->	
</div> <!-- Fin datos -->



