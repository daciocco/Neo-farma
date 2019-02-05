<script type="text/javascript">
	/*** Funcion que muestra el div en la posicion del mouse*/	
	function showdiv(event, id){
		document.getElementById('flotante'+id).style.display="inline";
		//determina un margen de pixels del div al raton
		margin=5;
		var tempX = 0;
		var tempY = 0;
		//window.pageYOffset = devuelve el tamaño en pixels de la parte superior no visible (scroll) de la pagina
		document.captureEvents(Event.MOUSEMOVE);
		tempX = event.pageX;
		tempY = event.pageY;		
		if (tempX < 0){tempX = 0;}
		if (tempY < 0){tempY = 0;}
		// Modificamos el contenido de la capa
		/*document.getElementById('flotante'+id).innerHTML=text;*/
		// Posicionamos la capa flotante
		document.getElementById('flotante'+id).style.top = (tempY+margin)+"px";
		document.getElementById('flotante'+id).style.left = (tempX+margin)+"px";
		document.getElementById('flotante'+id).style.display='block';
		return;
	}
</script>

<div class="temas_noticias" style="margin-right:10px;" align="left">             	
	<div class="tituloazul">Noticias <a class="noti" href="/pedidos/noticias/ver.php" title="Ver m&aacute;s noticias" style="color:#666; float:right;" ><?php echo " Ver m&aacute;s..."; ?></a></div> 
    <?php 
	$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/noticias/': $_REQUEST['backURL'];
	$_noticias	= DataManager::getNoticiasActivas(0, 0, false); 
	if (count($_noticias )) {	
		foreach ($_noticias as $k => $_noticia) {
			if ($x < 10){
				$x ++;				
				$fecha 			= 	explode(" ", $_noticia["ntfecha"]);	
				//calcula días transcurridos a la fecha
				$fecha_actual	=	date("Y-m-d");			
				$dias	= (strtotime($fecha[0])-strtotime($fecha_actual))/86400;
				$dias 	= abs($dias); $dias = floor($dias);
								
				list($ano, $mes, $dia) 	= 	explode("-", $fecha[0]);
				$Nfecha 		= 	$dia."-".$mes."-".$ano;
				
				$Nid	 		= 	$_noticia["idnt"];
				$Ntitulo 		= 	$_noticia["nttitulo"];
				$Ndescripcion 	= 	$_noticia["ntdescripcion"];
				//$Nlink			=	$_noticia["ntlink"];
				$NID 			= 	$_noticia["ntid"];
				?>
				<div class="box_noticia">
					<div class="box_noticia_titulo">           				
                        <a class="noti" href="/pedidos/noticias/ver.php?idnt=<?php echo $Nid; ?>&backURL=<?php echo $backURL; ?>" onmouseover="showdiv(event, <?php echo $Nid; ?>);" onmousemove="showdiv(event, <?php echo $Nid; ?>);" onmouseout="javascript:document.getElementById('flotante<?php echo $Nid; ?>').style.display='none';"><?php echo $Ntitulo; ?></a>                        
                        
                        <label id="fecha" style="color:#999; font-size:10px; float: right;"><?php echo "(".$Nfecha.")"; ?>
							<?php if($dias <= 7){ ?>
                                <img src="/pedidos/images/icons/icono-news30.png" alt="nueva noticia" style="float:right;"/>
                            <?php } ?>
                        </label>
                        
                        <br/>
           			</div>
                               
					<div id="flotante<?php echo $Nid; ?>" class="flotante">
                   		<p class="itemcheck" style="font-size:11px;"><span><strong>
						<div class="titulo"><?php echo $Ntitulo; ?></div> 
						<div class="noticia"><?php echo $Ndescripcion; ?></div>                  
					</div>
                </div>									
				<?php
			}												
		}
	}else {?>
       	<div class="box_noticia" align="center" style="background-color:#EBEBEB">
			<div class="box_noticia_titulo">
           		<?php echo "ACTUALMENTE NO HAY NOTICIAS ACTIVAS. Gracias."; ?><br/>
           	</div>
    	</div>
        <?php 
	}?> 
</div> <!-- Fin temas noticias --> 