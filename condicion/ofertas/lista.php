<?php 
$condiciones	= DataManager::getCondiciones( 0, 0, 1, 1, 1, date("Y-m-d"), "'Bonificacion'");
if (count($condiciones)) {				
	$index = 0;
	foreach ($condiciones as $j => $cond) {	
		$condId				=	$cond['condid'];
		$articulosCond		= DataManager::getCondicionArticulos($condId);
		if (count($articulosCond)) {
			foreach ($articulosCond as $k => $artCond) {	
				$condArtOAM		= $artCond["cartoam"];
				if($condArtOAM == "oferta" || $condArtOAM == "altaoff" || $condArtOAM == "modifoff"){
					$condArtIdArt	= $artCond['cartidart'];
					$condArtNombre		= DataManager::getArticulo('artnombre', $condArtIdArt, 1, 1);
					$condArtImagen		= DataManager::getArticulo('artimagen', $condArtIdArt, 1, 1);
					$imagenObject	= DataManager::newObjectOfClass('TImagen', $condArtImagen);
					$imagen			= $imagenObject->__get('Imagen');
					$img			= ($imagen) ?	"/pedidos/images/imagenes/".$imagen : "/pedidos/images/sin_imagen.png";
					$arrayArticulos['idart'][$index] = $condArtIdArt;
					$arrayArticulos['nombre'][$index] = $condArtNombre;
					$arrayArticulos['imagen'][$index] = $img;
					$index ++;
				}						
			}
		}
	}
} ?>

<script src="/pedidos/js/jquery/html2canvas.js" type="text/javascript"></script>
	<offers>
		<br>
		<div class="bloque_1">	
			<div class="section-title text-center center">
				<h2>&darr; Ofertas Del Mes &darr;</h2>
				<h3>Ofertas increibles de <?php echo strtoupper(Mes(date("m"))); ?></h3>
			</div>
		</div>
		
		<div class="bloque_1" align="center">	
			<h3>Con la compra de los siguientes productos</h3>
		</div>
		
		<br>

		<?php 
		if(isset($arrayArticulos)){
			if(count($arrayArticulos['idart'])){ 
				for($i = 0; $i < count($arrayArticulos['idart']); $i ++ ) {
					$idArt = $arrayArticulos['idart'][$i];
					$nombre = $arrayArticulos['nombre'][$i];
					$imagen = $arrayArticulos['imagen'][$i];
					$palabras = explode(" ", $nombre); ?> 

					<div class="bloque_1">
						<h4><?php echo "Art. N&deg; ".$idArt." | ".$palabras[0];?><h4>
						<small><h4><?php echo $nombre; ?></h4></small>
						<br>
						<img src="<?php echo $imagen; ?>" class="img-responsive" alt="Oferta">
					</div> <?php
				}
			}  
		} ?>
		
		<input id="btn-Preview-Image" type="button" value="Preview" />
		<a id="btn-Convert-Html2Image" href="#">Download</a>
		<br/>
		<h3>Preview:</h3>
		<div id="previewImage"></div>
		<hr>
	</offers>

<script>
	var element = $("#offers"); // global variable
	var getCanvas; // global variable

	$("#btn-Preview-Image").on('click', function () {
		 html2canvas(element, {
		 onrendered: function (canvas) {
				$("#previewImage").append(canvas);
				getCanvas = canvas;
			 }
		 });
	});
	
	//----------------------
	
	$("#btn-Convert-Html2Image").on('click', function () {
		var imgageData = getCanvas.toDataURL("image/png");
		// Now browser starts downloading it instead of just showing it
		var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
		$("#btn-Convert-Html2Image").attr("download", "your_pic_name.png").attr("href", newData);
	});
	
	//----------------------
	/*
	
	function exportChart() {
       
             html2canvas($('#offers'), {
                   useCORS: true,
                   allowTaint: true,
                   onrendered: function (canvas) {
                       var img = document.createElement("a");
                       
                       img.href = canvas.toDataURL();
                       img.download = "chart.png";
                       img.click();
                       
                       }
                   });
               }   
	
	exportChart();
	*/
	
</script>