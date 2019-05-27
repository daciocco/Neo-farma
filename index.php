<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="P"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
 } ?>
 
<!DOCTYPE html>
<html xml:lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php"; ?>
</head>

<body>
	<header class="cabecera">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
	</header><!-- cabecera -->		

	<nav class="menuprincipal"> <?php
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
	</nav> <!-- fin menu -->

	<main class="cuerpo menu2">
		<?php 
		//	USUARIOS DE EMPRESA	//
		if ($_SESSION["_usrrol"]== "G" || $_SESSION["_usrrol"]== "V" || $_SESSION["_usrrol"]== "A" || $_SESSION["_usrrol"]== "M"){?> 
			<div class="box_down">  
				<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/inicio/contenido2.inc.php"); ?>   
			</div>
		<?php } ?>

		<?php  
		//	USUARIOS PROVEEDORES	//
		if ($_SESSION["_usrrol"]=="P"){ ?>
			<div class="box_down">
				<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/banner.header.inc.php"); ?>
			</div>
		<?php  } ?>
		<hr>
	</main> <!-- fin cuerpo --> 
	
	<offers>
		<div class="section-title text-center center">
			<h2>&darr; Ofertas Del Mes &darr;</h2>
		</div>
		<div class="offers"> <?php 
			$condiciones	= DataManager::getCondiciones( 0, 0, 1, 1, 1, date("Y-m-d"), "'Bonificacion'");
			if (count($condiciones)) {				
				$index = 0;
				foreach ($condiciones as $j => $cond) {	
					$condId			= $cond['condid'];
					$articulosCond	= DataManager::getCondicionArticulos($condId);
					if (count($articulosCond)) {
						foreach ($articulosCond as $k => $artCond) {	
							$condArtOAM		= $artCond["cartoferta"];
							if($condArtOAM == "1"){
								$condArtIdArt	= $artCond['cartidart'];
								$condArtNombre	= DataManager::getArticulo('artnombre', $condArtIdArt, 1, 1);
								$condArtImagen	= DataManager::getArticulo('artimagen', $condArtIdArt, 1, 1);
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
			}
			
			if(isset($arrayArticulos)){
				if(count($arrayArticulos['idart'])){ 
					for($i = 0; $i < count($arrayArticulos['idart']); $i ++ ) {
						$idArt = $arrayArticulos['idart'][$i];
						$nombre = $arrayArticulos['nombre'][$i];
						$imagen = $arrayArticulos['imagen'][$i];
						$palabras = explode(" ", $nombre); ?> 
						<div class="col-sm-6">
							<div class="portfolio-item">							
								<div class="hover-bg">
									<div class="hover-text">
										<i><?php echo $palabras[0]; ?></i>
										<h4><?php echo "Art. ".$idArt; ?><h4>
										<hr>
										<small><?php echo $nombre; ?></small>	
									</div>
									<img src="<?php echo $imagen; ?>" class="img-responsive" alt="Oferta">
								</div>
							</div>
						</div>  <?php
					}
				}  
			}?>			
		</div>
	</offers>
	
	<footer class="pie">
		<?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
	</footer> <!-- fin pie -->
        
</body>
</html>