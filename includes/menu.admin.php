<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M" ){?>
<header>
	<div id="navegador" align="center">
    	<nav2>
            <ul>
            <?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?> 
            	<li class="<?php echo ($_section=="cadenas") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Cadenas</a>
                    <ul id="uno">
                    	<li class="<?php echo ($_subsection=="listar_cadenas") ? "current_submenu" : "current_menu";?>" >
                        	<a href="/pedidos/cadenas/">Cadenas</a>
                        </li> 
                    </ul>
                </li>	
            <?php  } ?>
            
            <?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){ ?> 
            	<li class="<?php echo ($_section=="condiciones_pago") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Condiciones de Pago</a>
                    <ul id="tres">
                    	<li class="<?php echo ($_subsection=="lista_condiciones") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/condicionpago/">Condiciones</a>
                        </li>
                        <li class="<?php echo ($_subsection=="nueva_condicion_neo") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/condicionpago/editar.php">Nueva Condici&oacute;n Neo</a>
                        </li>
                        <li class="<?php echo ($_subsection=="nueva_condicion_transfer") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/condicionpago/editar_transfer.php">Nueva Condici&oacute;n Transfer</a>
                        </li>
                    </ul>
                </li>
            <?php  } ?>    
                
            <?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?> 
            	<li class="<?php echo ($_section=="condiciones_viejas") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Condiciones al 09-2017</a>
                    <ul id="tres">
                    	<li class="<?php echo ($_subsection=="listas") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/listas/">Listas</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="packs") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/packs/">Packs</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="bonificaciones") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/bonificacion/"> Bonificaci&oacute;n</a>
                        </li> 
                    </ul>
                </li>
            <?php  } ?>
            
            <?php  if ($_SESSION["_usrrol"]=="A" ){ ?> 
            	<li class="<?php echo ($_section=="acciones") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Acciones</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="lista_acciones") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/acciones/">Acciones</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="nueva_accion") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/acciones/editar.php">Nueva Acci&oacute;n</a>
                        </li> 
                    </ul>
                </li>
                
                <li class="<?php echo ($_section=="contactos") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Contactos</a>
                    <ul id="uno">
                        <li class="<?php echo ($_subsection=="nuevo_contacto") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/contactos/editar.php">Nuevo Contacto</a>
                        </li> 
                    </ul>
                </li>
                
            	<li class="<?php echo ($_section=="droguerias") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Droguer&iacute;as</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="lista_droguerias") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/droguerias">Droguer&iacute;as</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="nueva_drogueria") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/droguerias/editar.php">Nueva droguer&iacute;a</a>
                        </li> 
                    </ul>
                </li>
                
                <li class="<?php echo ($_section=="newsletter") ? "current_menu" : "boton_menu";?>">
					<a href="#">Enviar Newsletter</a> <!-- /pedidos/newsletter/logica/newsletter.php -->
				</li>
                
            	<li class="<?php echo ($_section=="noticias") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Noticias</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="lista_noticias") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/noticias">Noticias</a> 
                        </li> 
                        <li class="<?php echo ($_subsection=="nueva_noticia") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/noticias/editar.php">Nueva Noticia</a>
                        </li> 
                    </ul>
                </li> 
                
                <li class="<?php echo ($_section=="relevamientos") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Relevamientos</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="lista_relevamientos") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/relevamiento/">Relevamientos</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="nuevo_relevamiento") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/relevamiento/editar.php">Nuevo Relevamiento</a>
                        </li> 
                    </ul>
                </li>
                
                <li class="<?php echo ($_section=="reportes") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Reportes</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="lista_reportes") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/reportes/">Reportes</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="nuevo_reporte") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/relevamiento/editar.php">Nuevo Reporte</a>
                        </li> 
                    </ul>
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
                    <a href="#">Usuarios</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="lista_usuarios") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/usuarios">Usuarios</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="nuevo_usuario") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/usuarios/editar.php">Nuevo usuario</a>
                        </li> 
                    </ul>
                </li> 
                
                <li class="<?php echo ($_section=="zonas") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Zonas</a>
                    <ul id="dos">
                    	<li class="<?php echo ($_subsection=="lista_zonas") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/zonas">Zonas</a>
                        </li> 
                        <li class="<?php echo ($_subsection=="nueva_zona") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/zonas/editar.php">Nueva Zona</a>
                        </li> 
                    </ul>
                </li> 
                
                <li class="<?php echo ($_section=="localidades") ? "current_menu" : "boton_menu";?>">
                    <a href="#">Localidades</a>
                    <ul id="uno">
                    	<li class="<?php echo ($_subsection=="lista_localidades") ? "current_submenu" : "current_menu";?>">
                        	<a href="/pedidos/localidades/">Localidades</a>
                        </li> 
                    </ul>
                </li> 
                
            <?php  } ?> 
            
            </ul>
        </nav>
	</div>
</header>
<?php } ?>