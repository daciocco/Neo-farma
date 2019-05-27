<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M" ){?>
<header>
	<div id="navegador" align="center">
    	<nav2>
            <ul>
            <?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){ ?> 
                <li class="<?php echo ($_section=="movimientos") ? "current_menu" : "boton_menu";?>">
                	<a href="/pedidos/movimientos/">Movimientos</a>
                </li>
            <?php  } ?>     
            
            <?php  if ($_SESSION["_usrrol"]=="A" ){ ?>                
                <li class="<?php echo ($_section=="contactos") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Contactos</a>
                    <ul id="uno">
                        <li class="<?php echo ($_subsection=="nuevo_contacto") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/contactos/editar.php">Nuevo Contacto</a>
                        </li> 
                    </ul>
                </li>
                
                <li class="<?php echo ($_section=="newsletter") ? "current_menu" : "boton_menu";?>">
					<a href="#">Enviar Newsletter</a> <!-- /pedidos/newsletter/logica/newsletter.php -->
				</li>
                
            	<li class="<?php echo ($_section=="noticias") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/noticias">Noticias</a>
                </li> 
                
                <li class="<?php echo ($_section=="reportes") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/reportes/">Reportes</a>
                </li>
                
                <li class="<?php echo ($_section=="soporte") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Soporte</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="soporte_resolver") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/soporte/tickets/resolver/">Resolver Tickets</a>
                        </li>
                    	<li class="<?php echo ($_subsection=="soporte_motivos") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/soporte/motivos/">Motivos</a>
                        </li>
                    </ul>
                </li> 
                
                <li class="<?php echo ($_section=="usuarios") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/usuarios">Usuarios</a>
                </li> 
                
                <li class="<?php echo ($_section=="localidades") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/localidades/">Localidades</a>
                </li> 
                
            <?php  } ?> 
            
            </ul>
        </nav>
	</div>
</header>
<?php } ?>