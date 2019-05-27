<div class="temas2"> 
	<a href="javascript:dac_exportar(4);" >
		<div class="box_mini2">
			Ofertas
		</div> <!-- box_mini -->
	</a>
	<a href="https://drive.google.com/drive/folders/1kQ-rViJRSwmE1NnxWWEyTTFj2VFGreIX" target="_blank">
		<div class="box_mini2" >
			Agenda de <br> Productos
		</div> <!-- box_mini -->
	</a>
	<a href="/pedidos/localidades/logica/exportar.localidades.php" title="Exportar localidades">
		<div class="box_mini2" >
			Localidades
		</div> <!-- box_mini -->
	</a>
</div> <!-- Fin temas  -->

<div class="temas2">
	<a href="javascript:dac_exportar(10);" >
		<div class="box_mini2">
			Cantidades <br> <p>Neo-farma</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(13);" >
		<div class="box_mini2">
			Minoristas <br> <p>Neo-farma</p>
		</div>
	</a>
	<a href="javascript:dac_exportar(16);" >
		<div class="box_mini2">
			Stock General
		</div>
	</a> 
</div>  <!-- Fin temas  --> 

<script type="text/javascript">
	function dac_exportar(nro){
		switch (nro){
			case 4: 	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/precios/Oferta.JPG';
						window.open( direccion, '_blank'); break;				
			case 7: 	direccion = 'https://neo-farma.com.ar/pedidos/localidades/logica/exportar.localidades.php';
						window.open( direccion, '_blank'); break;
			case 9:		direccion = 'https://webmail.ferozo.com/appsuite/';
						window.open( direccion, '_blank'); break;
			case 10:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/Cantidades.xls';
						window.open( direccion, '_blank'); break;			
			case 13:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/Minoristas.xls'; 
						window.open( direccion, '_blank'); break;
			case 16:	direccion = 'https://neo-farma.com.ar/pedidos/informes/archivos/Stock.XLS';
						window.open( direccion, '_blank');break;
			//case 17:	direccion = 'https://www.youtube.com/playlist?list=PLLQ6QFsaFoch1igplVDvn64dwyUsfqeNa';
			//			window.open( direccion, '_blank'); break;		
			default: 	direccion = 'https://neo-farma.com.ar/pedidos/index.php'; 
						window.open( direccion );break;
		}
	}
</script>