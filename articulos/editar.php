<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"  && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$artId			= empty($_REQUEST['artid']) ? 0 : $_REQUEST['artid'];
$artIdFamilia 	= '';

if ($artId) {
	$artObject 		= DataManager::newObjectOfClass('TArticulo', $artId);
	$artIdEmpresa 	= $artObject->__get('Empresa');
	$artIdLab		= $artObject->__get('Laboratorio');
	$artIdArt		= $artObject->__get('Articulo');
	$artNombre 		= $artObject->__get('Nombre');
	$artDescripcion	= $artObject->__get('Descripcion');
	$artPrecio 		= $artObject->__get('Precio');//PrecioART	
	$artGanancia	= $artObject->__get('Ganancia');
	$artPrecioLista	= $artObject->__get('PrecioLista'); 	
	$artEan	 		= $artObject->__get('CodigoBarra');	
	$artMedicinal	= ($artObject->__get('Medicinal') == 'S') ? 1 : 0;	
	$artImagen	 	= $artObject->__get('Imagen');
	$imagenObject	= DataManager::newObjectOfClass('TImagen', $artImagen);	
	$imagen			= $imagenObject->__get('Imagen');
	$img			= ($imagen) ?	"/pedidos/images/imagenes/".$imagen : "/pedidos/images/sin_imagen.png";
	$artIdRubro		= $artObject->__get('Rubro');
	$artIdDispone	= $artObject->__get('Dispone');
	$artIdFamilia	= $artObject->__get('Familia');
	$artIdLista		= $artObject->__get('Lista');
	$artIva			= ($artObject->__get('IVA') == 'S') ? 1 : 0;	
	$artFechaCompra = new DateTime($artObject->__get('FechaCompra'));   
   
	//Calcular Precios
	if($artPrecio){
		$artPrecioVenta	= floatval($artPrecioLista)*floatval(1.450);		
		if($artIva == 0) {
			$artPrecioVenta = $artPrecioVenta * floatval(1.210);			
		} 
		if($artMedicinal == 0){
			$artPrecioVenta = $artPrecioVenta * floatval(1.210);
		}				
		if($artIdEmpresa == 3){
			if($artIva == 0) {
				$artPrecioVenta = $artPrecioVenta * floatval(1.210);
			}
			if($artMedicinal == 1){
				$artPrecioVenta = $artPrecioVenta * floatval(1.210);
			}
		}		
		if($artGanancia <> "0.00"){
			$porcGanancia 	= ($artGanancia / 100) + 1;			
			$artPrecioVenta = $artPrecioVenta / $porcGanancia;
		}		
		$artPrecioVenta = number_format($artPrecioVenta,2,'.','');
	}
	
	//consulta datos dispone
	if($artIdDispone){
		$dispObject = DataManager::newObjectOfClass('TArticuloDispone', $artIdDispone);
		if($dispObject) {
			$dispNombre				= $dispObject->__get('NombreGenerico');
			$dispVia				= $dispObject->__get('Via');
			$dispForma 				= $dispObject->__get('Forma');
			$dispEnvase				= $dispObject->__get('Envase');
			$dispUnidad 			= $dispObject->__get('Unidades');
			$dispCantidad	 		= $dispObject->__get('Cantidad');
			$dispUnidadMedida		= $dispObject->__get('UnidadMedida');
			$dispAccion	 			= $dispObject->__get('Accion');
			$dispUso	 			= $dispObject->__get('Uso');
			$dispNoUsar	 			= $dispObject->__get('NoUsar');
			$dispCuidadosPre		= $dispObject->__get('CuidadosPre');
			$dispCuidadosPost		= $dispObject->__get('CuidadosPost');
			$dispComoUsar	 		= $dispObject->__get('ComoUsar');
			$dispConservacion	 	= $dispObject->__get('Conservacion');
			$dispFechaUltVersion	= $dispObject->__get('FechaUltVersion');
		}
	} else {
		$dispNombre				= "";
		$dispVia				= "";
		$dispForma 				= "";
		$dispEnvase				= "";
		$dispUnidad 			= "";
		$dispCantidad	 		= "";
		$dispUnidadMedida		= "";
		$dispAccion	 			= "";
		$dispUso	 			= "";
		$dispNoUsar	 			= "";
		$dispCuidadosPre		= "";
		$dispCuidadosPost		= "";
		$dispComoUsar	 		= "";
		$dispConservacion	 	= "";
		$dispFechaUltVersion	= "";
	}	
} else {
	$artPrecioVenta	= 0;
	$artIdEmpresa 			= "";
	$artIdLab	 			= "";
	$artIdArt	 			= "";
	$artNombre 				= "";
	$artDescripcion			= "";
	$artPrecio 				= 0;
	$artEan	 				= "";
	$artGanancia			= "0.00";
	$artMedicinal			= 0;
	$artImagen				= 0;
	$imagen					= 0;
	$img					= ($imagen) ?	"/pedidos/images/imagenes/".$imagen : "/pedidos/images/sin_imagen.png";
	$artIdRubro				= "";
	$dispNombre				= "";
	$dispVia				= "";
	$dispForma 				= "";
	$dispEnvase				= "";
	$dispUnidad 			= "";
	$dispCantidad	 		= "";
	$dispUnidadMedida		= "";
	$dispAccion	 			= "";
	$dispUso	 			= "";
	$dispNoUsar	 			= "";
	$dispCuidadosPre		= "";
	$dispCuidadosPost		= "";
	$dispComoUsar	 		= "";
	$dispConservacion	 	= "";
	$dispFechaUltVersion	= "";
	$artIdLista				= 0;
	$artIva					= 0;
	$artFechaCompra 		= "";
	$artIdDispone 			= 0;
} 

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
	
	<script language="javascript" type="text/javascript">
		$(document).on('change','input[type="checkbox"]' ,function(e) {
			if(this.id=="artiva" || this.id=="artmedicinal") {
				dac_calcularPrecios();
			}
		});		
		$(document).on('change','input[id="artprecioVenta"]' ,function(e) {
			dac_calcularPrecios();
		});
		$(document).on('change','input[id="artporcentaje"]' ,function(e) {
			dac_calcularPrecios();
		});		
		
		function dac_calcularPrecios(){			
			"use strict";
			var iva			= $("#artiva").prop('checked');
			var medicinal 	= $('#artmedicinal').prop('checked');
			var precioVenta	= $('#artprecioVenta').val();
			var empresa		= $('#artidempresa').val();
			var laboratorio	= $('#artidlab').val();
			var artId		= $('#artidart').val(); 
			var porcentaje	= $('#artporcentaje').val();
			$.ajax({
				type 	: 	'POST',
				cache	:	false,
				url 	: 	'/pedidos/articulos/logica/ajax/calcularPrecios.php',				
				data	:	{
							artId		: artId,
							empresa		: empresa,
							laboratorio	: laboratorio,
							iva			: iva,
							medicinal	: medicinal,
							precioVenta	: precioVenta,
							porcentaje	: porcentaje,
						},
				beforeSend	: function () {
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
				},
				success : 	function (result) {
					$('#box_cargando').css({'display':'none'});
					if (result){
						var results = result.split("/");
						if(results.length === 1){
							$('#box_error').css({'display':'block'});
							$("#msg_error").html(results);
						} else {							
							$('#artpreciolista').val(results[0]) ;
							$('#artpreciocompra').val(results[1]);
							$('#artprecioreposicion').val(results[2]);
							$('#artprecio').val(results[3]) ;
						}				
					} else {
						$('#box_cargando').css({'display':'none'});	
						$('#box_error').css({'display':'block'});
						$("#msg_error").html("Error al consultar los registros.");
					}
				},		
				error: function () {
					$('#box_cargando').css({'display':'none'});	
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error al consultar los registros.");
				},
			});
		}
	</script>
	
	<script language="javascript" type="text/javascript">
		//---------------------//
		// Crea Div de Formula //
		var nextFormula = 0;
		function dac_cargarFormula(idForm, ifa, cant, medida, ifaComo, cantComo, medidaComo){
			nextFormula++;
			campo =	'<div id="rutformula'+nextFormula+'">';
				campo +='<input id="formId'+nextFormula+'" name="formId[]" value="'+idForm+'" hidden="hidden" >';
				campo +='<div class="bloque_1"><label for="formIfa">IFA</label> <input id="formIfa'+nextFormula+'" name="formIfa[]" type="text" maxlength="10" value="'+ifa+'" ></div>';				
				campo +='<div class="bloque_4"><label for="formCant">Cant.</label><input id="formCant'+nextFormula+'" name="formCant[]" type="number" value='+cant+'></div>';			
				campo +='<div class="bloque_4"><label for="formMedida">Medida</label><input id="formMedida'+nextFormula+'" name="formMedida[]" type="text" value="'+medida+'" ></div>';		
				/*campo +='<div class="bloque_1"><label for="formIfaComo">IFA (como)</label><input id="formIfaComo'+nextFormula+'" name="formIfaComo[]" type="text" maxlength="10" value="'+ifaComo+'" ></div>';
				campo +='<div class="bloque_4"><label for="formCantComo">Cant.</label><input id="formCantComo'+nextFormula+'" name="formCantComo[]" type="number" value='+cantComo+'></div>';
				campo +='<div class="bloque_4"><label for="formMedidaComo">Medida</label><input id="formMedidaComo'+nextFormula+'" name="formMedidaComo[]" type="text" value="'+medidaComo+'"></div>';*/
				campo +='<div class="bloque_4"><br><input id="btmenos" type="button" value=" - " onClick="dac_deleteFormula('+nextFormula+')"></div>';			
			campo +='</div><hr>';
			$("#tablaFormula").append(campo);	
		}
		
		// Botón eliminar registro
		function dac_deleteFormula(nextFormula){
			elemento	=	document.getElementById('rutformula'+nextFormula);
			elemento.parentNode.removeChild(elemento);
		}
		
		// Botón insertar registro
		function dac_insertFormula(){
			dac_cargarFormula('', '', '0', '', '', '0', '');
		}
		
	</script>
	
	<script type ="text/javascript">
		function dac_changeEmpresa(idEmpresa){
			$('#artfamilia').find('option').remove();
			$.getJSON('/pedidos/articulos/logica/json/getFamilia.php?idEmpresa='+idEmpresa, function(datos) {
				idFamilias = datos;			

				$('#artfamilia').append("<option value='' selected></option>");	

				$.each( idFamilias, function( key, value ) {
					var arr = value.split('-');
					familia = document.getElementById('artfamilia').value;
					if(arr[0] == familia){
						$('#artfamilia').append("<option value='" + arr[0] + "' selected>" + arr[1] + "</option>");	
					} else {
						$('#artfamilia').append("<option value='" + arr[0] + "'>" + arr[1] + "</option>");
					}
				});
			});
			dac_calcularPrecios();
		}	
	</script>
</head>
<body>
    <header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->
    
    <nav class="menuprincipal"> <?php 
        $_section 	= "articulos";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>
    
    <main class="cuerpo">
    	<div class="box_body">           				
            <form id="fmArticulo" class="fm_edit2" method="post" enctype="multipart/form-data">
                <fieldset>
                	<legend>Art&iacute;culo</legend>
                    <input type="hidden" name="artid" value="<?php echo $artId;?>" />
                    
                    <div class="bloque_3" align="center">
						<fieldset id='box_error' class="msg_error">          
							<div id="msg_error" align="center"></div>
						</fieldset>                                                                         
						<fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;">    
							<div id="msg_cargando" align="center"></div>      
						</fieldset>
						<fieldset id='box_confirmacion' class="msg_confirmacion">
							<div id="msg_confirmacion" align="center"></div>      
						</fieldset>
					</div>
                   
                   	<div class="bloque_3">
						<?php $urlSend	=	'/pedidos/articulos/logica/update.articulo.php';?>
						<a id="btnSend" title="Enviar" style="cursor:pointer; float: right;"> 
							<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(fmArticulo, '<?php echo $urlSend;?>');"/>
						</a>
					</div> 
                    
                    <div class="bloque_1"> 
                        <label for="artidempresa">Empresa</label>                        
						<select id="artidempresa" name="artidempresa" onchange="javascript:dac_changeEmpresa(this.value);"> <?php
							$empresas	= DataManager::getEmpresas(1); 
							if (count($empresas)) {	?>
								<option value="" selected></option> <?php
								foreach ($empresas as $k => $emp) {
									$idEmp		=	$emp["empid"];
									$nombreEmp	=	$emp["empnombre"];
									if ($idEmp == $artIdEmpresa){ ?>                        		
										<option value="<?php echo $idEmp; ?>" selected><?php echo $nombreEmp; ?></option><?php
									} else { ?>
										<option value="<?php echo $idEmp; ?>"><?php echo $nombreEmp; ?></option><?php
									}   
								}                            
							} ?>
						</select>
                    </div>
                                 
                    <div class="bloque_1"> 
						<label for="artidlab">Laboratorio</label> 
						<select id="artidlab" name="artidlab" onchange="javascript:dac_calcularPrecios();"><?php		
							$laboratorios	= DataManager::getLaboratorios(); 
							if (count($laboratorios)) {	?>
								<option value="" selected></option>
								<?php
								foreach ($laboratorios as $k => $lab) {
									$idLab		=	$lab["idLab"];
									$nombreLab	=	$lab["Descripcion"];
									$selected = ($artIdLab == $idLab) ? "selected" : "";
									?><option value="<?php echo $idLab; ?>" <?php echo $selected; ?> ><?php echo $nombreLab; ?></option><?php
								} 
							} ?>
						</select>
					</div>
                 	
                  	<div class="bloque_2">
                        <label for="artidart">Art&iacute;culo</label>
                        <input name="artidart" id="artidart" type="text"  maxlength="10" value="<?php echo $artIdArt;?>"/>
                    </div>
                   
                   	<div class="bloque_5">
                        <label for="artnombre">Nombre</label>
                        <input name="artnombre" id="artnombre" type="text" maxlength="50" value="<?php echo $artNombre;?>" onkeypress="return  dac_ValidarCaracteres(event)" />
                    </div>
                    
                    <div class="bloque_1"> 
						<label for="artrubro">Rubro</label>
						<select name="artrubro"><?php			
							$rubros	= DataManager::getRubros(); 
							if (count($rubros)) {	?>
								<option value="0" selected></option> <?php
								foreach ($rubros as $k => $rub) {
									$idRubro	= $rub["IdRubro"];
									$descripcion= $rub["Descripcion"];		
									$selected 	= ($idRubro == $artIdRubro) ? "selected" : "";  	
									?><option value="<?php echo $idRubro; ?>" <?php echo $selected; ?> ><?php echo $descripcion; ?></option><?php
								}    
							} ?>
						</select>
					</div>
                   
                    <div class="bloque_1"> 
						<label for="artfamilia">Familia</label> 
						<select id="artfamilia" name="artfamilia">
						<option value="0" selected></option> <?php	
							$familias	= DataManager::getCodFamilias(0,0,$artIdEmpresa); 
							if (count($familias)) {	?>
								<option value="" selected></option> <?php
								foreach ($familias as $k => $flia) {
									$idFlia		= $flia["IdFamilia"];
									$nombreFlia	= $flia["Nombre"];								
									$selected 	= ($artIdFamilia == $idFlia) ? "selected" : ""; ?>
									<option value="<?php echo $idFlia; ?>" <?php echo $selected; ?> ><?php echo $nombreFlia; ?></option><?php
								}    
							} ?>
						</select>
					</div>
                   
					<div class="bloque_1"> 
						<label for="artlista">Lista</label> 
						<select name="artlista"><?php	
							$listas	= DataManager::getListas(); 
							if (count($listas)) {	?>
								<option value="0" selected></option> <?php
								foreach ($listas as $k => $list) {
									$idLista	= $list["IdLista"];
									$nombreLista= $list["NombreLT"];								
									$selected 	= ($artIdLista == $idLista) ? "selected" : ""; ?>
									<option value="<?php echo $idLista; ?>" <?php echo $selected; ?> ><?php echo $nombreLista; ?></option><?php
								}    
							} ?>
						</select>
					</div>
                    
                    <div class="bloque_1">
                        <label for="artean">C&oacute;digo de Barras</label>
                        <input name="artean" id="artean" type="text"  maxlength="20" value="<?php echo $artEan;?>"/>
                    </div> 
                    
                    <div class="bloque_3"><br></div>                    
                    <div class="bloque_2">
                        <label for="artprecioVenta">Precio Venta Público</label>
                        <input name="artprecioVenta" id="artprecioVenta" type="text" maxlength="8" onkeydown="javascript:ControlComa(this.id, this.value);" onkeyup="javascript:ControlComa(this.id, this.value);" value="<?php echo $artPrecioVenta;?>">
                    </div>
                    
                    <div class="bloque_4">
                        <label for="artporcentaje">% Gcia.</label>
                        <input id="artporcentaje" name="artporcentaje" type="text" maxlength="6" onkeydown="javascript:ControlComa(this.id, this.value);" onkeyup="javascript:ControlComa(this.id, this.value);" value="<?php echo $artGanancia;?>" >
                    </div>
                    
                    <div class="bloque_2">
                        <label for="artprecio">Precio Artículo</label>
                        <input id="artprecio" name="artprecio" type="text" value="<?php echo $artPrecio;?>" readonly style="background-color: #EEE"> 
                    </div>
                    
                    <div class="bloque_4">
						<label for="artiva">IVA</label></br>
                        <input name="artiva" id="artiva" type="checkbox" <?php if($artIva){echo "checked='checked'";};?>>
                    </div>
                    
                    <div class="bloque_4">
						<label for="artmedicinal">Medicinal</label></br>
                        <input name="artmedicinal" id="artmedicinal" type="checkbox" <?php if($artMedicinal){echo "checked='checked'";};?> >
                    </div>
                    
                    <div class="bloque_4">
						<label for="artoferta">Oferta</label></br>
                        <input name="artoferta" id="artoferta" type="checkbox" checked >
                    </div>
                    
                    <div class="bloque_2">
                        <label for="artpreciocompra">Precio Compra</label>
                        <input id="artpreciocompra" name="artpreciocompra"  type="text" readonly style="background-color: #EEE">
                    </div>
                    
                    <div class="bloque_2">
                        <label for="artfechacompra">Fecha Compra</label>
                        <input name="artfechacompra" type="text" value="<?php if($artFechaCompra) { echo $artFechaCompra->format("d-m-Y"); } ?>" readonly style="background-color: #EEE">
                    </div>
                    
                    <div class="bloque_2">
                        <label for="artpreciolista">Precio Lista</label>
                        <input id="artpreciolista" name="artpreciolista" type="text" readonly style="background-color: #EEE">
                    </div>
                    
                    <div class="bloque_2">
                        <label for="artprecioreposicion">Precio Reposición</label>
                        <input id="artprecioreposicion" name="artprecioreposicion" type="text" readonly style="background-color: #EEE">
                    </div>   
                     
                    <?php 
                   	echo "<script>";
					echo "javascript:dac_calcularPrecios();";
					echo "</script>";
                    ?>
                                
                    <div class="bloque_3"><br></div>
                    
                    <div class="bloque_3">
                        <label for="artdescripcion">Descripci&oacute;n</label>
                        <textarea name="artdescripcion" id="artdescripcion" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'artdescripcion', 240);" onkeydown="javascript:dac_LimitaCaracteres(event, 'artdescripcion', 240);" onkeypress="return  dac_ValidarCaracteres(event)"  value="<?php echo $artDescripcion;?>" style="resize:none; height:100px;"/><?php echo $artDescripcion;?></textarea> 
                        </br>
                        <fieldset id='box_informacion' class="msg_informacion">
                            <div id="msg_informacion" align="center"></div> 
                        </fieldset>   
                    </div>
                    
                    <div class="bloque_1">
                    	<label for="imagen">Subir nueva imagen</label><br/>
                        <input name="artimagen" id="artimagen" type="text" value="<?php echo $artImagen;?>" hidden/>
                        <div class="inputfile"><input id="imagen" name="imagen" class="file" type="file"/></div>
                    </div>
                    
                    <div class="bloque_2">
                    	 <img src="<?php echo $img; ?>" alt="Imagen" width="100"/>
                    </div>
                    
                    <div class="bloque_2">
                    	La IMAGEN debe ser PNG y fondo transparente (preferentemente 384 x 295)
                    </div> 
                </fieldset>	
                
                <fieldset>
                	<legend>Dispone</legend>
                	
                	<input name="artiddispone" type="text" value="<?php echo $artIdDispone;?>" hidden >
                	
                	<div class="bloque_1">
                        <label for="artnombregenerico">Nombre Genérico</label>
                        <input name="artnombregenerico" type="text" maxlength="50" value="<?php echo $dispNombre;?>" onkeypress="return  dac_ValidarCaracteres(event)" />
                    </div>
                    
                    <div class="bloque_2"> 
						<label for="artforma">Forma Farmacéutica</label> 
						<select name="artforma">
						<?php //echo $dispForma; ?>
							<option value="" selected></option> 
							<option value="gel" >Gel</option>
							<option value="liquido" >Líquido</option>
							<option value="solido" >Sólido</option>
							<option value="shampoo" >Shampoo</option>
						</select>
					</div>
                   
                   	<div class="bloque_2">
                        <label for="artfechaultversion">Fecha Última Versión</label>
                        <input id="artfechaultversion" name="artfechaultversion" type="text" size="15" maxlength="10" value="<?php echo $dispFechaUltVersion;?>" />
                    </div>
                    
                    <div class="bloque_2"> 
						<label for="artvia">Vía</label> 
						<select name="artvia">
						<?php //echo $dispVia; ?>						
							<option value="" selected></option> 
							<option value="oral" >Oral</option>
							<option value="inhalatoria" >Inhalatoria</option>
							<option value="nasal" >Nasal</option>
							<option value="oftalmica" >Oftálmica</option>
							<option value="oftalmica" >Oftálmica</option>
							<option value="otica" >Ótica</option>
							<option value="topica" >Tópica</option>
							<option value="rectal" >Rectal</option>
							<option value="vaginal" >Vaginal</option>
						</select>
					</div>
                    
                    <div class="bloque_2"> 
						<label for="artenvase">Envase</label> 
						<select name="artenvase">
						<?php //echo $dispEnvase; ?>
							<option value="" selected></option> 
							<option value="frasco">Frasco</option>
							<option value="blister">Blister</option>
						</select>
					</div>
					
					<div class="bloque_4">
                        <label for="artunidad">Unidad</label>
                        <input name="artunidad" type="number" value="<?php echo $dispUnidad;?>" />
                    </div>
                    
                    <div class="bloque_4">
                        <label for="artcantidad">Cantidad</label>
                        <input name="artcantidad" type="number" value="<?php echo $dispCantidad;?>" />
                    </div>
                    
                    <div class="bloque_2"> 
						<label for="artmedida">Medida</label> 
						<select name="artmedida">
						<?php //echo $dispUnidadMedida; ?>
							<option value="" selected></option> 
							<option value="g">g</option>
							<option value="mg">mg</option>
							<option value="ml">ml</option>
						</select>
					</div>
					
					<div class="bloque_3">
                        <label for="artaccion">Acción Terapéutica</label>
                        <input name="artaccion" type="text" maxlength="250" value="<?php echo $dispAccion;?>" onkeypress="return  dac_ValidarCaracteres(event)" />
                    </div>
					
                    <div class="bloque_1">
                        <label for="artuso">Uso</label>
                        <textarea name="artuso" id="artuso" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'artuso', 240);" onkeydown="javascript:dac_LimitaCaracteres(event, 'artuso', 240);" onkeypress="return  dac_ValidarCaracteres(event)"  value="<?php echo $dispUso;?>" style="resize:none; height:100px;"/><?php echo $dispUso; ?></textarea> 
                        </br>
                        <fieldset id='box_informacion' class="msg_informacion">
                            <div id="msg_informacion" align="center"></div> 
                        </fieldset>   
                    </div>
                    
                    <div class="bloque_1">
                        <label for="artnousar">No Usar</label>
                        <textarea name="artnousar" id="artnousar" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'artnousar', 240);" onkeydown="javascript:dac_LimitaCaracteres(event, 'artnousar', 240);" onkeypress="return  dac_ValidarCaracteres(event)"  value="<?php echo $dispNoUsar; ?>" style="resize:none; height:100px;"/><?php echo $dispNoUsar; ?></textarea> 
                        </br>
                        <fieldset id='box_informacion' class="msg_informacion">
                            <div id="msg_informacion" align="center"></div> 
                        </fieldset>   
                    </div>
                                        
                    <div class="bloque_1">
                        <label for="artcuidadospre">Cuidados Pre</label>
                        <textarea name="artcuidadospre" id="artcuidadospre" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'artcuidadospre', 240);" onkeydown="javascript:dac_LimitaCaracteres(event, 'artcuidadospre', 240);" onkeypress="return  dac_ValidarCaracteres(event)"  value="<?php echo $dispCuidadosPre; ?>" style="resize:none; height:100px;"/><?php echo $dispCuidadosPre;?></textarea> 
                        </br>
                        <fieldset id='box_informacion' class="msg_informacion">
                            <div id="msg_informacion" align="center"></div> 
                        </fieldset>   
                    </div>
                    
                    <div class="bloque_1">
                        <label for="artcuidadospost">Cuidados Post</label>
                        <textarea name="artcuidadospost" id="artcuidadospost" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'artcuidadospost', 240);" onkeydown="javascript:dac_LimitaCaracteres(event, 'artcuidadospost', 240);" onkeypress="return  dac_ValidarCaracteres(event)"  value="<?php echo $dispCuidadosPost; ?>" style="resize:none; height:100px;"/><?php echo $dispCuidadosPost; ?></textarea> 
                        </br>
                        <fieldset id='box_informacion' class="msg_informacion">
                            <div id="msg_informacion" align="center"></div> 
                        </fieldset>   
                    </div>
                                                             
                    <div class="bloque_3">
                        <label for="artcomousar">Cómo Usar?</label>
                        <textarea name="artcomousar" id="artcomousar" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'artcomousar', 240);" onkeydown="javascript:dac_LimitaCaracteres(event, 'artcomousar', 240);" onkeypress="return  dac_ValidarCaracteres(event)" value="<?php echo $dispComoUsar; ?>" style="resize:none; height:100px;"/><?php echo $dispComoUsar; ?></textarea> 
                        </br>
                        <fieldset id='box_informacion' class="msg_informacion">
                            <div id="msg_informacion" align="center"></div> 
                        </fieldset>   
                    </div>

                    <div class="bloque_3">
                        <label for="artconservacion">Conservación</label>
                        <input name="artconservacion" type="text" maxlength="250" value="<?php echo $dispConservacion; ?>" onkeypress="return  dac_ValidarCaracteres(event)" />
                    </div>
                      
                </fieldset>	
                
                <fieldset id='tablaFormula'>
                	<legend>F&oacute;rmula</legend>                	
               		<input id="btnew" type="button" value=" + " onClick="dac_insertFormula()"><hr>
					<?php 
					//Fórmula
					if(isset($artIdDispone)){
						$formulas = DataManager::getArticuloFormula( $artIdDispone );
						if (count($formulas)) {
							foreach ($formulas as $k => $form) {
								$fmId			=	$form["afid"];
								$fmIfa			=	$form["afifa"];
								$fmCant			=	$form["afcantidad"];
								$fmMedida		=	$form["afumedida"];
								$fmIfaComo		=	$form["afifacomo"];
								$fmCantComo		=	$form["afcantidadcomo"];
								$fmMedidaComo	=	$form["afumedidacomo"];						
								echo "<script>";
								echo "javascript:dac_cargarFormula('".$fmId."', '".$fmIfa."', '".$fmCant."', '".$fmMedida."', '".$fmIfaComo."', '".$fmCantComo."', '".$fmMedidaComo."');";
								echo "</script>";							
							}                       
						} 
					}?> 
              		
                </fieldset>	
            </form>		
    	</div> <!-- FIN box_body -->
    	
		<div class="box_seccion"> 
			<div class="barra">
				<h2>Presupuesto de Ventas</h2> <hr>
			</div> <!-- Fin barra -->

			<div class="lista"> 
			
			</div> <!-- Fin listar -->

		</div> <!-- Fin box_seccion -->
    	<hr>	
    </main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>



<script language="javascript" type="text/javascript">
	new JsDatePick({
		useMode: 2,
		target:"artfechaultversion",
		dateFormat:"%d-%M-%Y"			
	});
</script>
