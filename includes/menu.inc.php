<script type="text/javascript">
	$(document).ready(main); 
	var contador = 1;
	function main() {
		$('.menu_bar').click(function(){
			// $('nav').toggle();
			
			$('.burguer').toggleClass('cruz');
			
			
			if(contador == 1){
				$('nav').animate({
					left: '0'
				});
				contador = 0;
			} else {
				$('nav').animate({
					left: '-100%'
				});		
				
				contador = 1;
			}
	 
		});
	 
	};
</script>
   
   
<style>
	.content-burguer {
		/*position: absolute;*/
		float: left;
		width: 30px;
		height: 30px;
		background-color: transparent;
		
		/* Flex-box */
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		
	}
	
	.burguer {
		position: relative;
		height: 15%;
		width: 100%;
		background: white;
		
		/* */
		transition: 1s;
	}	
	
	.burguer:after,
	.burguer:before,
	.hamburguesa {
		position: absolute;
		width: 100%;
		content: ''; /*se coloca para que aparezcan otras líneas de styles */
		background: white;
	}
	
	.burguer:after {
		top: 10px;
		height: 100%;
	}
	
	.burguer:before{
		top: -10px;
		height: 100%;
	}
	
	.cruz {
		transition: 1s;
		transform: rotateZ(45deg);
	}
	
</style>
   
    
<?php 
	$_section 		= 	empty($_section) 	? "inicio" : $_section;
	$_subsection 	= 	empty($_subsection) ? "inicio" : $_subsection;
?>

<?php if ($_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="V" || $_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M" || $_SESSION["_usrrol"]=="P"){?>
<header>
	<div class="menu_bar">
        <a href="#" class="bt-menu">
        	
        	<div class="content-burguer">
        		<div class="burguer">
        		</div>
        	</div>
        	
        	<script>
				/*$(document).on('ready', function()
					$('.content-burguer').on('click', function()
						$('.burguer').toggleClass('cruz');
					})
				})*/
			</script>
        	
			Menu 
        
        </a>
        
    </div>

	<div id="navegador" align="center">
        <nav>
            <ul>
                <li>
                    <a href="/pedidos/index.php" title="Home">
                    	<img class="icon-home"/>
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
                       
                        <li class="<?php echo ($_subsection=="pendientes") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/pedidos/pendientes/" title="Ver pedidos pendientes">
                            Seguimiento de Pedidos</a>
                        </li>
                        
                        <li class="<?php echo ($_subsection=="prefacturados") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/pedidos/prefacturados/" title="Ver pedidos prefacturados">
                            Pedidos Pre Facturados </a>
                        </li>
                        
                        <li class="<?php echo ($_subsection=="facturacion") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/cuentas/facturacion/" title="Facturación de Cuentas">
                            Facturaci&oacute;n </a>
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
                            <a href="/pedidos/transfer/" title="Ver transfers pendientes">
                            Transfers Pendientes </a>
                        </li>
                        
                        <li class="<?php echo ($_subsection=="enviados") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/transfer/enviados/" title="Ver transfers enviados">
                            Transfers Enviados </a>
                        </li>
                        
                    </ul>
                </li> 
                
                <li class="<?php echo ($_section=="cuentas") ? "current_menu" : "boton_menu";?>">
                    <a href="#">CUENTAS</a>
                    <ul>
                        <?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?>
							<li class="<?php echo ($_subsection=="listar_cadenas") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/cadenas/" title="Cadenas">
								Cadenas </a>
							</li>
						<?php  } ?>
                      
                      	<?php  if ($_SESSION["_usrrol"]=="A" ){ ?>
							<li class="<?php echo ($_subsection=="lista_droguerias") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/droguerias">
								Droguer&iacute;as</a>
							</li>
                     	
                     		<li class="<?php echo ($_subsection=="lista_relevamientos") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/relevamiento/">
								Relevamientos</a>
							</li> 
                      	<?php  } ?>
                       
                        <li class="<?php echo ($_subsection=="listar_cuentas") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/cuentas/" title="Cuentas">
                            Cuentas </a>
                        </li>                        
                        <li class="<?php echo ($_subsection=="editar_cuenta") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/cuentas/editar.php" title="Nueva Cuenta">
                            Nueva Cuenta</a>
                        </li>
                    </ul>
                </li>
                
                <li class="<?php echo ($_section=="condiciones") ? "current_menu" : "boton_menu";?>">
                	<a href="#">CONDICIONES</a>
                    <ul>
                    	<li class="<?php echo ($_subsection=="condiciones_comerciales") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/condicion/" title="Condiciones Comerciales">
                            Condiciones Comerciales </a>
                        </li> 
                        
                        <?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="G" || $_SESSION["_usrrol"]=="M"){ ?>
							<li class="<?php echo ($_subsection=="condiciones_pago") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/condicionpago/">
								Condiciones de Pago</a>
							</li>
                        <?php  } ?>
                        
                        <?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?> 
							<li class="<?php echo ($_subsection=="listas_precios") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/listas/">Listas de precios</a>
							</li>
						<?php  } ?> 
                       
                    </ul>
                </li>
                
                <li class="<?php echo ($_section=="informes") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/informes/" title="Informes">
                    INFORMES </a> 
                </li>
                
                <li class="<?php echo ($_section=="planificar") ? "current_menu" : "boton_menu";?>">
                    <a href="#">
                    PLANIFICAR </a> 
                    
                    <ul>
                    	<li class="<?php echo ($_subsection=="planificar") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/planificacion/" title="Planificar visitas">
                            Planificar </a>
                        </li>
                        
                        <li class="<?php echo ($_subsection=="lista_zonas") ? "current_submenu" : "current_menu";?>">
							<a href="/pedidos/zonas">
							Zonas de cuentas</a>
						</li> 
                   
                   		<?php  if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?>
                   			<li class="<?php echo ($_subsection=="nueva_zona") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/zonas/editar.php">
								Nueva Zona de Cuenta</a>
							</li> 
                   		<?php  } ?> 
                   		
                   		<?php  if ($_SESSION["_usrrol"]=="A" ){ ?>  
                   			<li class="<?php echo ($_subsection=="lista_acciones") ? "current_submenu" : "current_menu";?>">
								<a href="/pedidos/acciones/">Acciones</a>
							</li> 
                   		<?php  } ?>
                    </ul>
                </li>	
                
                <li class="<?php echo ($_section=="cuentas_corrientes") ? "current_menu" : "boton_menu";?>">
                    <a href="#">
                    CUENTAS CORRIENTES</a>
                    
                    <ul>
                    	<li class="<?php echo ($_subsection=="rendicion") ? "current_submenu" : "current_menu";?>">
                            <a href="/pedidos/rendicion/" title="Rendici&oacute;n de cobranzas">
                            Rendici&oacute;n </a>
                        </li>
                    </ul>
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
						<a href="/pedidos/juegos/" title="Juegos"> 
                    		<img class="icon-game"/>
                    	</a>  
					</li>
               
               		<li class="<?php echo ($_section=="soporte") ? "current_menu" : "boton_menu";?>">
						<a href="/pedidos/soporte/tickets/" title="Soporte"> 
                    		<img class="icon-support"/>
                    	</a>  
					</li>
                <?php }?>
                
                <li class="<?php echo ($_section=="agenda") ? "current_menu" : "boton_menu";?>">
                    <a href="/pedidos/agenda/" title="Agenda"> 
                    	<img class="icon-calendar-menu"/>
                    </a> 
                </li>
                
                <li class="<?php echo ($_section=="webmail") ? "current_menu" : "boton_menu";?>">
					<a href="https://webmail.ferozo.com/appsuite/" title="Webmail" target="_blank"> 
                    	<img class="icon-mail-menu"/>
                    </a>                             
				</li>
                
                <li class="<?php echo ($_section=="ayuda") ? "current_menu" : "boton_menu";?>">
					<a href="/pedidos/ayuda/" title="Ayuda"> 
                    	<img class="icon-help"/>
                    </a>                             
				</li>
            <?php }?>
            
            <?php  
			//	MENÚ USUARIOS PROVEEDORES	//	
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
                    <a href="/pedidos/login/logout.php" title="Logout"> 
                    	<img class="icon-logout"/>
                    </a> 
                </li>
            <?php }?>  
            </ul>
        </nav>
	</div>
</header>
<?php } ?>