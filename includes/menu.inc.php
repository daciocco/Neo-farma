<script type="text/javascript">
	$(document).ready(main); 
	var contador = 1;
	function main() {
		$('.menu_bar').click(function(){
			// $('nav').toggle();
			if(contador == 1){
				$('nav').animate({
					left: '0'
				});
				contador = 0;
			} else {
				contador = 1;
				$('nav').animate({
					left: '-100%'
				});
			}
	 
		});
	 
	};
</script>
    
<?php 
	$_section 		= 	empty($_section) 	? "inicio" : $_section;
	$_subsection 	= 	empty($_subsection) ? "inicio" : $_subsection;
	
	$icoLogoutHover	= 	"/pedidos/images/icons/icono-logout-hover.png";
	$icoLogout		= 	"/pedidos/images/icons/icono-logout.png";
	$icoHomeHover	= 	"/pedidos/images/icons/icono-home-hover.png";
	$icoHome		= 	"/pedidos/images/icons/icono-home.png";
?>

<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="V" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M" || $_SESSION["_usrrol"]=="P"){?>
<header>
	<div class="menu_bar">
        <a href="#" class="bt-menu">Menu</a>
    </div>

	<div id="navegador" align="center">
        <nav>
            <ul>
                <li>
                    <a href="/pedidos/index.php" title="Home" onmouseover="imgHome	.src='<?php echo $icoHome; ?>';" onmouseout="imgHome.src='<?php echo $icoHomeHover; ?>';">
                    	<img id="imgHome" src="<?php echo $icoHomeHover; ?>" />
                    </a>
                </li> 
               
            <?php 
			//---------------------//
			//	USUARIOS INTERNOS  //	
			if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="V" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?>
           
           		<?php 
				if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?>
           			<li class="<?php echo ($_section=="articulos") ? "current_menu" : "boton_menu";?>">
						<a href="/pedidos/articulos/">ART&Iacute;CULOS</a>
					</li>
           		<?php }?> 
            
                <li class="<?php echo ($_section=="pedidos") ? "current_menu" : "boton_menu";?>">
                    <a href="#">PEDIDOS</a>
                    <ul>
                        <?php if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M" || $_SESSION["_usrrol"]=="V"){ ?>
                        <li class="<?php echo ($_subsection=="nuevo_pedido") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/pedidos/editar.php" title="Realizar un nuevo pedido">
                            Nuevo Pedido </a>
                        </li>
                        <?php }?>                        
                       
                        <li class="<?php echo ($_subsection=="mis_pedidos") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/pedidos/" title="Ver pedidos realizados">
                            Mis Pedidos </a>
                        </li>
                        
                        <li class="<?php echo ($_subsection=="mis_propuestas") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/pedidos/propuestas/" title="Ver propuestas realizadas">
                            Propuestas </a>
                        </li>
                        
						<li class="<?php echo ($_subsection=="nuevo_transfer") ? "current_submenu" : "current_menu"; ?>">
							<a href="/pedidos/transfer/editar.php" title="Realizar un pedido transfer">
							Nuevo Transfer  </a>
						</li>  
                       
                        <li class="<?php echo ($_subsection=="mis_transfers") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/transfer/" title="Ver pedidos realizados">
                            Mis Transfers </a>
                        </li>
                    </ul>
                </li> 
                
                <li class="<?php echo ($_section=="cuentas") ? "current_menu" : "boton_menu";?>">
                    <a href="#">CUENTAS</a>
                    <ul>
                        <li class="<?php echo ($_subsection=="listar_cuentas") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/cuentas/" title="Cuentas">
                            Cuentas </a>
                        </li>                        
                        <li class="<?php echo ($_subsection=="editar_cuenta") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/cuentas/editar.php" title="Nueva Cuenta">
                            Nueva Cuenta</a>
                        </li>
                        <li class="<?php echo ($_subsection=="facturacion") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/cuentas/facturacion/" title="Facturación de Cuentas">
                            Facturaci&oacute;n </a>
                        </li>  
                    </ul>
                </li>
                
                <li class="<?php echo ($_section=="condiciones") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/condicion/">CONDICIONES</a>
                </li>
                
                <li class="<?php echo ($_section=="informes") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/informes/" title="Informes">
                    INFORMES </a>  
                </li>
                
                <li class="<?php echo ($_section=="planificar") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/planificacion/" title="Planificar futuras visitas a clientes">
                    PLANIFICAR </a>   
                </li>	
                
                <li class="<?php echo ($_section=="rendicion") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/rendicion/" title="Rendici&oacute;n Cobranzas">
                    RENDICI&Oacute;N</a>
                </li>
                
                <?php /*if ($_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "M"){ ?>  
					<li class="<?php echo ($_section=="transfer") ? "current_menu" : "boton_menu";?>">
						<a href="#">TRANSFER</a>
						<ul>
							<li class="<?php echo ($_subsection=="abm_transfer") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/transfer/gestion/abmtransfer/" title="ABM Transfer">
								ABM </a>
							</li>      
							<li class="<?php echo ($_subsection=="abm_transfer_drog") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/transfer/gestion/abmtransferdrog/" title="ABM Transfer">
								ABM Droguer&iacute;as</a>
							</li>                  
							<li class="<?php echo ($_subsection=="liquidacion_transfer") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/transfer/gestion/liquidacion/" title="Liquidaci&oacute;n Transfer">
								Liquidaci&oacute;n </a>
							</li>                        
							<li class="<?php echo ($_subsection=="liquidacion_transfer_drog") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/transfer/gestion/liquidaciondrog/" title="Liquidaci&oacute;n Transfer">
								Liquidaci&oacute;n Droguert&iacute;a</a>
							</li>
						</ul>                           
					</li>
                <?php } */ ?> 
                
                <?php /*if ($_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "V" || $_SESSION["_usrrol"]== "G"){?>
                <li class="<?php echo ($_section=="pdv") ? "current_menu" : "boton_menu";?>">
                    <a>PDV</a>                
                    <ul>
                        <li class="<?php echo ($_subsection=="pdv_infycam") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/pdv/infycam/" title="Informes y Campa&ntilde;as (propios)">
                            Informes y Campa&ntilde;as (propios)</a>
                        </li>
                        <li class="<?php echo ($_subsection=="pdv_acc_comp") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/pdv/accionescompe/" title="Acciones Competencia">
                            Acciones Competencia </a>
                        </li>
                    </ul>                              
                </li>
                <?php } */?> 
                
                <?php if ($_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){?>
					<li class="<?php echo ($_section=="proveedores") ? "current_menu" : "boton_menu";?>">
						<a href="#">PROVEEDORES</a>                
						<ul>
							<li class="<?php echo ($_subsection=="listado") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/proveedores/" title="Listado">
								 Listado</a>
							</li>
							<li class="<?php echo ($_subsection=="fechaspago") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/proveedores/fechaspago/" title="Fechas de Pago">
								Fechas de Pago </a>
							</li>
						</ul>                              
					</li>
                <?php }?> 
                
                <?php if ($_SESSION["_usrrol"]=="A"){?>
					<li class="<?php echo ($_section=="juegos") ? "current_menu" : "boton_menu";?>">
						<a href="/pedidos/juegos/" title="Juegos" onmouseover="imgJuegos.src='/pedidos/images/icons/icono-juegos.png';" onmouseout="imgJuegos.src='/pedidos/images/icons/icono-juegos-white.png';"> 
                    		<img id="imgJuegos" src="/pedidos/images/icons/icono-juegos-white.png"/>
                    	</a>  
					</li>
               
               		<li class="<?php echo ($_section=="soporte") ? "current_menu" : "boton_menu";?>">
						<a href="/pedidos/soporte/tickets/" title="Soporte" onmouseover="imgSoporte.src='/pedidos/images/icons/icono-float.png';" onmouseout="imgSoporte.src='/pedidos/images/icons/icono-float-white.png';"> 
                    		<img id="imgSoporte" src="/pedidos/images/icons/icono-float-white.png" />
                    	</a>  
					</li>
                <?php }?>
                
                <li class="<?php echo ($_section=="agenda") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/agenda/" title="Agenda" onmouseover="imgAgenda.src='/pedidos/images/icons/icono-calendario.png';" onmouseout="imgAgenda.src='/pedidos/images/icons/icono-calendario-white.png';"> 
                    	<img id="imgAgenda" src="/pedidos/images/icons/icono-calendario-white.png" />
                    </a> 
                </li>
            <?php }?>
            
            <?php  
            //******************************//
			//	MENÚ USUARIOS PROVEEDORES	//	
			//******************************//
			if ($_SESSION["_usrrol"]=="P") { ?>            
				<li class="<?php echo ($_section=="provpagos") ? "current_menu" : "boton_menu";?>">
                    <a href="#">
                    PAGOS</a> 
                    <ul>
                        <li class="<?php echo ($_subsection=="provfechapago") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/proveedores/pagos/solicitarfecha/" title="Solicitar Fecha de Pago">
                             Solicitar Fecha de Pago</a>
                        </li>
                    </ul>  
                </li>                
			<?php 
			}
			//******************************// ?>
            
            <?php if (!empty($_SESSION['_usrname'])) { ?> 
            	<li>
                    <a href="/pedidos/login/logout.php" title="Logout" onmouseover="imgLogout.src='<?php echo $icoLogout; ?>';" onmouseout="imgLogout.src='<?php echo $icoLogoutHover; ?>';"> 
                    	<img id="imgLogout" src="<?php echo $icoLogoutHover; ?>" />
                    </a> 
                </li>
            <?php }?>  
            </ul>
        </nav>
	</div>
</header>
<?php } ?>