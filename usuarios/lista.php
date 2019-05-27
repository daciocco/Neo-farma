<?php 
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL']; 
$btnNuevo	= sprintf( "<a href=\"editar.php\" title=\"Nuevo\"><img class=\"icon-new\"/></a>");
?>

<div class="box_body">
	<div class="barra">
       	<div class="bloque_5">
			<h1>Vendedores</h1>                	
        </div>
        <div class="bloque_7">
          	<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblUsuarios" hidden/>
        </div> 
        <div class="bloque_7">
			<?php echo $btnNuevo; ?>                	
        </div>  
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista_super">     
		<table id="tblUsuarios">
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
			$_GOFirst	= sprintf("<a class=\"icon-go-first\" href=\"%s?pag=%d\"></a>", $backURL, 1);
			$_GOPrev	= sprintf("<a class=\"icon-go-previous\" href=\"%s?pag=%d\"></a>", $backURL, $_pag-1);
			$_GONext	= sprintf("<a class=\"icon-go-next\" href=\"%s?pag=%d\"></a>", $backURL, $_pag+1);
			$_GOLast	= sprintf("<a class=\"icon-go-last\" href=\"%s?pag=%d\"></a>", $backURL, $_paginas);
			
			$usuarios	= DataManager::getUsuarios( $_pag, $_LPP, NULL, NULL, '"V"');
			$_max	 	= count($usuarios);
			for( $k=0; $k < $_max; $k++ ) {
				if ($k < $_LPP) {
					
					$usuario 	= $usuarios[$k];
					$nombre		= $usuario['unombre'];
					$usr		= $usuario['ulogin'];
					$dni		= $usuario['udni'];

					$_status	= ($usuario['uactivo']) ? "<img class=\"icon-status-active\"/>" : "<img class=\"icon-status-inactive\"/>";

					$_editar	= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit\"/>");
					
					$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit-zone\"/>");	

					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"Cambiar Estado\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);
					
					$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img class=\"icon-delete\" /> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
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
</div> <!-- Fin datos -->

<div class="box_seccion">
	<div class="barra">
       	<div class="bloque_5">
			<h1>Administrador Web</h1>                	
        </div>
        <div class="bloque_5">
          	<input id="txtBuscar2" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn2" type="text" value="tblAdmWeb" hidden/>
        </div>   
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista">     
		<table id="tblAdmWeb">
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

				$_status= ($usuario['uactivo']) ? "<img class=\"icon-status-active\"/>" : "<img class=\"icon-status-inactive\"/>";

				$_editar= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit\" />");

				$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit-zone\" />");	
				
				$_borrar= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"Cambiar Estado\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);

				$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img class=\"icon-delete\" /> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
				
				
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $usr, $nombre, $_editar, $_zona, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			}?>
		</table>
	</div> <!-- Fin listar -->	
	
	<div class="barra">
       	<div class="bloque_5">
			<h1>Gerencias</h1>                	
        </div>
        <div class="bloque_5">
          	<input id="txtBuscar3" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn3" type="text" value="tblGerencias" hidden/>
        </div>   
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista">     
		<table id="tblGerencias">
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

				$_status= ($usuario['uactivo']) ? "<img class=\"icon-status-active\"/>" : "<img class=\"icon-status-inactive\"/>";
				$_editar= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit\"/>");
				$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit-zone\" />");	
				$_borrar= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"Cambiar Estado\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);

				$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img class=\"icon-delete\"/> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
				
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $usr, $nombre, $_editar, $_zona, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			}?>
		</table>
	</div> <!-- Fin listar -->	
	
	<div class="barra">
       	<div class="bloque_5">
			<h1>Administracion</h1>                	
        </div>
        <div class="bloque_5">
          	<input id="txtBuscar4" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn4" type="text" value="tblAdministracion" hidden/>
        </div>   
        <hr>   
	</div> <!-- Fin barra -->

	<div class="lista">     
		<table id="tblAdministracion">
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

				$_status= ($usuario['uactivo']) ? "<img class=\"icon-status-active\"/>" : "<img class=\"icon-status-inactive\"/>";
				$_editar= sprintf( "<a href=\"editar.php?uid=%d&backURL=%s\" title=\"editar usuario\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit\"/>");
				$_borrar= sprintf( "<a href=\"logica/changestatus.php?uid=%d&backURL=%s&pag=%s\" title=\"Cambiar estado\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], $_pag, $_status);				
				$_zona	= sprintf( "<a href=\"editar.zona.php?uid=%d&backURL=%s\" title=\"Editar Zona\">%s</a>", $usuario['uid'], $_SERVER['PHP_SELF'], "<img class=\"icon-edit-zone\" />");
				$_eliminar 	= sprintf ("<a href=\"logica/eliminar.usuario.php?uid=%d&backURL=%s\" title=\"eliminar cliente\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR EL USUARIO?')\"> <img class=\"icon-delete\"/> </a>", $usuario['uid'], $_SERVER['PHP_SELF'], "eliminar");
				
				echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
				echo sprintf("<td height=\"15\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $usr, $nombre, $_editar, $_zona, $_borrar, $_eliminar);
				echo sprintf("</tr>");
			}?>
		</table>
	</div> <!-- Fin listar -->	
</div> <!-- Fin datos -->



