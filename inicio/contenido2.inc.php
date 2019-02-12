<div class="temas2"> 
	<a href="javascript:dac_exportar(4);" >
		<div class="box_mini2">
			Ofertas
		</div> <!-- box_mini -->
	</a>
	<a href="javascript:dac_exportar(5);" >
		<div class="box_mini2">
			Web <br> Cruz Del Sur
		</div> <!-- box_mini -->
	</a>
	<a href="javascript:dac_exportar(6);">
		<div class="box_mini2">
			Listados <br> Cartas de Porte
		</div> <!-- box_mini -->
	</a>		
	<a href="https://www.neo-farma.com.ar/pedidos/zonas/index.php" >
		<div class="box_mini2" >
			Zonas de <br> Cuentas
		</div> <!-- box_mini -->
	</a>
	<a href="javascript:dac_exportar(18);" >
		<div class="box_mini2" >
			Agenda de <br> Productos
		</div> <!-- box_mini -->
	</a>
	<a href="javascript:dac_exportar(7);" >
		<div class="box_mini2" >
			Localidades
		</div> <!-- box_mini -->
	</a>
	<hr>
</div> <!-- Fin temas  -->


<div class="temas2">
	<a href="javascript:dac_exportar(17);" >
		<div class="box_mini2">
			Video Tutoriales <br>
			<img src="/pedidos/images/icons/icono-youtube.png">
		</div> <!-- box_mini -->
	</a>
	<a href="javascript:dac_exportar(8);" >
		<div class="box_mini2">
			Instructivo <br> Web
		</div> <!-- box_mini -->
	</a>
	<a href="javascript:dac_exportar(9);" >
		<div class="box_mini2">
			Webmail
		</div> <!-- box_mini -->
	</a>		
	<hr>
</div> <!-- Fin temas  -->

<div class="temas2">
	<a href="javascript:dac_exportar(10);" >
		<div class="box_mini2">
			Cantidades <br> <p>Neo-farma</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(11);"  >
		<div class="box_mini2">
			Comprobantes <br> <p>Neo-farma</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(12);"  >
		<div class="box_mini2">
			Deudas <br> <p>Neo-farma</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(13);" >
		<div class="box_mini2">
			Minoristas <br> <p>Neo-farma</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(14);" >
		<div class="box_mini2">
			Notas de Valor <br> <p>Neo-farma</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(15);" >
		<div class="box_mini2">
			Pedidos Pendientes <br> <p>Neo-farma</p>
		</div>
	</a>   
	<hr>   
</div>  <!-- Fin temas  --> 

<div class="temas2">
	<a href="javascript:dac_exportar(16);" >
		<div class="box_mini2">
			Stock General
		</div>
	</a>
	<a href="javascript:dac_exportar(19);" >
		<div class="box_mini2">
			Devoluciones <br> <p>Neo-farma</p>
		</div>
	</a>		
	<a href="javascript:dac_exportar(20);" >
		<div class="box_mini2">
			Devoluciones <br> <p>Gezzi</p>
		</div>
	</a>
	<?php if($_SESSION["_usrdni"] == "3035" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "G") { ?>
	<a href="javascript:dac_exportar(22);" >
		<div class="box_mini2">
			Comprobantes <br> <p>Gezzi</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(23);" >
		<div class="box_mini2">
			Deudas <br> <p>Gezzi</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(26);" >
		<div class="box_mini2">
			Pedidos Pendientes <br> <p>Gezzi</p>
		</div>
	</a>	   
	<?php } ?> 
	<hr>
</div>  <!-- Fin temas  --> 

<hr>
<script type="text/javascript">
	function dac_exportar(nro){
		switch (nro){
			case 2: 	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/precios/Pack_mensual.xls';
						window.open( direccion, '_blank'); break;
			case 3: 	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/precios/ListaXercom.pdf';
						window.open( direccion, '_blank'); break;
			case 4: 	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/precios/Oferta.JPG';
						window.open( direccion, '_blank'); break;
			case 5: 	direccion = 'http://clientes.cruzdelsur.com/';
						window.open( direccion, '_blank'); break;
			case 6: 	alert("ATENCI\u00d3N: Se proceder\u00e1 a descargar un archivo por cada una de las zonas que le corresponda. Si no consigue hacerlo, p\u00f3ngase en contacto con el administrador de la web. Si no encuentra el archivo descargado, busque en la carpeta descargas de la PC. \u00A1Gracias!"); <?php 
						$zona = explode(', ', $_SESSION["_usrzonas"]);							
						for($i = 0;	$i < count($zona);	$i++){
							$_archivo	=	$_SERVER["DOCUMENT_ROOT"]."/pedidos/informes/archivos/cartasdeporte/".trim($zona[$i])."_Carta-de-Porte.XLS";							
							if (file_exists($_archivo)){ ?>	
								archivo	  = <?php echo trim($zona[$i]); ?>+'_Carta-de-Porte.XLS';							
								direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/cartasdeporte/'+archivo;
								window.open(direccion); 
								direccion = ""; <?php								
							}else{ ?>	
								alert("No hay Carta de Porte correspondiente a la zona <?php echo trim($zona[$i]); ?>"); <?php  
							} 
						} ?>
						break;						
			case 7: 	direccion = 'https://neo-farma.com.ar/pedidos/localidades/logica/exportar.localidades.php';
						window.open( direccion ); break;
			case 8:		direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/InstructivoWeb.pdf';
						window.open( direccion, '_blank'); break;
			case 9:		direccion = 'https://webmail.ferozo.com/appsuite/';
						window.open( direccion, '_blank'); break;
			case 10:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/Cantidades.xls';
						window.open( direccion, '_blank'); break;
			case 11:	alert("ATENCI\u00d3N: Se proceder\u00e1 a descargar un archivo por cada una de las zonas que le corresponda. Si no consigue hacerlo, p\u00f3ngase en contacto con el administrador de la web. Si no encuentra el archivo descargado, busque en la carpeta descargas de la PC. \u00A1Gracias!"); <?php 
						$zona = explode(', ', $_SESSION["_usrzonas"]);
						for($i = 0;	$i < count($zona);	$i++){
							$_archivo	=	$_SERVER["DOCUMENT_ROOT"]."/pedidos/informes/archivos/comprobantes/".trim($zona[$i])."_Ventas_por_Vendedor.XLS";							
							if (file_exists($_archivo)){ ?>
								archivo	  = <?php echo trim($zona[$i]); ?>+'_Ventas_por_Vendedor.XLS';							
								direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/comprobantes/'+archivo;
								window.open(direccion);	<?php  
							}else{ ?>	
								alert("No hay Ventas correspondiente a la zona <?php echo trim($zona[$i]); ?>"); <?php  
							} 
						} ?>
						break;				
			case 12:	alert("ATENCI\u00d3N: Se proceder\u00e1 a descargar un archivo por cada una de las zonas que le corresponda. Si no consigue hacerlo, p\u00f3ngase en contacto con el administrador de la web. Si no encuentra el archivo descargado, busque en la carpeta descargas de la PC. \u00A1Gracias!"); <?php 
						$zona = explode(', ', $_SESSION["_usrzonas"]);							
						for($i = 0;	$i < count($zona);	$i++){							
							$_archivo	=	$_SERVER["DOCUMENT_ROOT"]."/pedidos/informes/archivos/comprobantes/".trim($zona[$i])."_Ventas_por_Vendedor.XLS";							
							if (file_exists($_archivo)){ ?>
								archivo	  = <?php echo trim($zona[$i]); ?>+'_Informe_de_Deudas.XLS';							
								direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/deudores/'+archivo;							
								window.open(direccion); <?php  
							}else{ ?>	
								alert("No hay Deudores correspondiente a la zona <?php echo trim($zona[$i]); ?>"); <?php  
							} 
						} ?>
						break;	
			case 13:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/Minoristas.xls'; 
						window.open( direccion, '_blank'); break;
			case 14:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/NotasValor.xls';
						window.open( direccion, '_blank'); break;
			case 15:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/PedidosPendientes.xls';
						window.open( direccion, '_blank');break;
			case 16:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/Stock.XLS';
						window.open( direccion, '_blank');break;
			case 17:	direccion = 'https://www.youtube.com/playlist?list=PLLQ6QFsaFoch1igplVDvn64dwyUsfqeNa';
						window.open( direccion, '_blank'); break;
			case 18: 	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/agenda.pdf';
						window.open( direccion, '_blank'); break;
			case 19:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/DevolucionesNeo.xls';
						window.open( direccion, '_blank');break;
			case 20:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivosgezzi/DevolucionesGezzi.xls';
						window.open( direccion, '_blank');break;	
			case 22:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivosgezzi/comprobantes/30_Ventas_por_Vendedor.XLS';
						window.open( direccion, '_blank');break;
			case 23:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivosgezzi/deudores/30_Informe_de_Deudas.XLS';
						window.open( direccion, '_blank');break;
			case 26:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivosgezzi/PedidosPendientes.xls'; 
						window.open( direccion, '_blank');break;						
			case 27:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/CondicionesEspeciales.xls'; 
						window.open( direccion, '_blank');break;
			default: 	direccion = 'https://neo-farma.com.ar/pedidos/index.php'; 
						window.open( direccion, '_blank');break;
		}
	}
</script>