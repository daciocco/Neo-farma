<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$pag		= empty($_REQUEST['pag']) 		? 0 					:	$_REQUEST['pag'];
$ctaId		= empty($_REQUEST['ctaid']) 	? 0						:	$_REQUEST['ctaid'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/cuentas/'	:	$_REQUEST['backURL'];

if ($ctaId) {
	$cuenta			= DataManager::newObjectOfClass('TCuenta', $ctaId);
	$idc 			= $cuenta->__get('ID');
	if(empty($idc)){ 
		header('Location:' . $backURL); 
	} else {	
		$empresa 		= $cuenta->__get('Empresa');
		$idCuenta 		= $cuenta->__get('Cuenta');
		$tipo	 		= $cuenta->__get('Tipo');	
		$estado 		= $cuenta->__get('Estado');
		$nombre 		= strtoupper($cuenta->__get('Nombre'));
		$ruteo			= $cuenta->__get('Ruteo');
		$categoriaComer	= $cuenta->__get('CategoriaComercial');
		$categoriaIva	= $cuenta->__get('CategoriaIVA');
		$agenteRetPerc	= $cuenta->__get('RetencPercepIVA');
		$condicionPago	= $cuenta->__get('CondicionPago');
		$empleados		= ($cuenta->__get('Empleados') == 0) ? "" : $cuenta->__get('Empleados');
		$provincia 		= $cuenta->__get('Provincia');
		$localidad 		= $cuenta->__get('Localidad');
		$localidadNombre= $cuenta->__get('LocalidadNombre');
		$direccion 		= $cuenta->__get('Direccion');
		$direccionEntrega= $cuenta->__get('DireccionEntrega');
		$nro			= ($cuenta->__get('Numero') == 0) ? "" : $cuenta->__get('Numero');
		$piso 			= $cuenta->__get('Piso');
		$dpto 			= $cuenta->__get('Dpto');
		$cp 			= $cuenta->__get('CP');
		$longitud		= $cuenta->__get('Longitud');
		$latitud		= $cuenta->__get('Latitud');
		$cuit 			= $cuenta->__get('CUIT');
		$zona 			= $cuenta->__get('Zona');	 
		$asignadoA		= $cuenta->__get('UsrAssigned');
		$nroIngBrutos	= $cuenta->__get('NroIngresosBrutos');	
		$fechaAlta		= $cuenta->__get('FechaAlta');	
		$fechaCompra	= $cuenta->__get('FechaCompra');	
		$telefono 		= $cuenta->__get('Telefono');
		$correo			= $cuenta->__get('Email');
		$web			= $cuenta->__get('Web');
		$observacion 	= $cuenta->__get('Observacion');	
		$imagen1		= ($cuenta->__get('Imagen1')) ? $cuenta->__get('Imagen1') : "sin_imagen.png";
		$imagen2		= ($cuenta->__get('Imagen2')) ? $cuenta->__get('Imagen2') : "sin_imagen.png";
		$activa			= $cuenta->__get('Activa');
		$lista			= $cuenta->__get('Lista');
	}
} else {
	$asignadoA		= $_SESSION["_usrid"];
	$empresa		= 1;
	$activa			= $lista			= 0;
	$idCuenta		= $tipo	 			= 
	$estado 		= $nombre 			= 
	$ruteo			= $categoriaComer	= 
	$categoriaIva	= $agenteRetPerc	= 
	$condicionPago	= $provincia 		= 
	$localidad 		= $localidadNombre	= 
	$direccion 		= $direccionEntrega	=
	$nro			= $piso 			= 
	$dpto 			= $cp 				= 
	$longitud		= $latitud			= 
	$cuit 			= $zona 			= 
	$nroIngBrutos	= $fechaAlta		= 
	$fechaCompra	= $telefono 		= 
	$correo			= $web				= 
	$observacion 	= $imagen1			= 
	$imagen2		= $empleados		= "";
}

$img1 = "/pedidos/cuentas/archivos/".$ctaId."/".$imagen1;
if(!file_exists($_SERVER['DOCUMENT_ROOT']."/pedidos/cuentas/archivos/".$ctaId."/".$imagen1)){
	$img1 =  "/pedidos/images/sin_imagen.png";
}
$img2 = "/pedidos/cuentas/archivos/".$ctaId."/".$imagen2;
if(!file_exists($_SERVER['DOCUMENT_ROOT']."/pedidos/cuentas/archivos/".$ctaId."/".$imagen2)){
	$img2 =  "/pedidos/images/sin_imagen.png";
}
?>
<!doctype html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyDtfbXVeMTz05KI-lowid64Am2bQ2GwYB8" type="text/javascript"></script>  
    
    <script type="text/javascript" src="/pedidos/cuentas/logica/jquery/jqueryHeader.js"></script>
    <script type="text/javascript" src="/pedidos/js/provincias/selectProvincia.js"></script>
    <script type="text/javascript" src="/pedidos/js/provincias/selectLocalidad.js"></script>
    <script type="text/javascript" src="/pedidos/cuentas/logica/jquery/jquery.transfer.relacionado.js"></script>
    <script type="text/javascript" src="/pedidos/js/funciones_comunes.js"></script>
</head>

<body>	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
       	$_section	= 'cuentas';
        $_subsection= 'editar_cuenta';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>	
    
    <main class="cuerpo">    
    	<div id="img_ampliada" align="center" onclick="javascript:dac_CloseImgZoom()">
            <img id="imagen_ampliada"/>    
        </div>
        <div id="pdf_ampliado" align="center" onclick="javascript:dac_ClosePdfZoom()">
             <div id="pdf_amp"></div>
        </div> 
        
        <div class="box_body">  
        	<form id="fmCuentaEdit" name="fmCuentaEdit" method="post" enctype="multipart/form-data"> 
                <input type="text" id="ctaid" name="ctaid" value="<?php echo $ctaId;?>" hidden="hidden" />           <input type="text" id="idorigen" name="idorigen" value="<?php echo $ctaId;?>" hidden="hidden"/>
                <input type="text" id="origen" name="origen" value="TCuenta" hidden="hidden"/>                
                <input type="text" id="activa" name="activa" value="<?php echo $activa;?>" hidden="hidden"/>         <input type="text" name="pag" value="<?php echo $pag;?>" hidden="hidden"/>
				
                <fieldset>
                    <legend>Cuenta</legend>                     
                    <div class="bloque_1">     
                        <fieldset id='box_error' class="msg_error">          
                            <div id="msg_error"></div>
                        </fieldset> 
                        <fieldset id='box_cargando' class="msg_informacion"> 
                            <div id="msg_cargando"></div>      
                        </fieldset> 
                        <fieldset id='box_confirmacion' class="msg_confirmacion">
                            <div id="msg_confirmacion"></div>      
                        </fieldset>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="asignado">Asignado a</label>
                        <select id="asignado" name="asignado" >   
							<option id="0" value="0" selected></option> <?php
                            $vendedores	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
                            if (count($vendedores)) {	
                                foreach ($vendedores as $k => $vend) {
                                    $idVend		=	$vend["uid"];
                                    $nombreVend	=	$vend['unombre'];
									if ($idVend ==  $asignadoA){ ?>                        		
										<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>" selected><?php echo $nombreVend; ?></option><?php
									} else { ?>
										<option id="<?php echo $idVend; ?>" value="<?php echo $idVend; ?>"><?php echo $nombreVend; ?></option><?php
									}
                                }                            
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_9">                    
                        <br>
                        <a id="relevar" title="Relevar" style="cursor:pointer;">
                        	<img class="icon-relieve"/>
                        </a>
                    </div>
					
					<div class="bloque_9">
                        <?php $urlSend = '/pedidos/cuentas/logica/update.cuenta.php';?>
                        <?php $urlBack = '/pedidos/cuentas/';?>
                        <br>
                        <a id="btnSend" title="Enviar" style="cursor:pointer;"> 
                            <img class="icon-send" onclick="javascript:dac_sendForm(fmCuentaEdit, '<?php echo $urlSend;?>', '<?php echo $urlBack;?>');"/>
                        </a>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="empresa">Empresa </label>
                        <select id="empselect" name="empselect" onchange="javascript:dac_changeEmpresa(this.value);"><?php
                            $empresas	= DataManager::getEmpresas(1); 
                            if (count($empresas)) {	
                                foreach ($empresas as $k => $emp) {
                                    $idEmp		=	$emp["empid"];
                                    $nombreEmp	=	$emp["empnombre"];
                                    if ($idEmp == $empresa){ 
										$selected="selected";										
										echo "<script>";
										echo "javascript:dac_cuentaTransferRelacionada()";
										echo "</script>";										
                                    } else { $selected=""; } ?>                                                                        
                                    <option id="<?php echo $idEmp; ?>" value="<?php echo $idEmp; ?>" <?php echo $selected; ?>><?php echo $nombreEmp; ?></option><?php  
                                }                            
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado">
                            <option value="" selected></option>
                            <option value="SolicitudAlta" <?php if($estado=="SolicitudAlta"){ echo "selected";}?>>Solicitud de Alta</option>                 
                            <?php
                            if($ctaId) { ?>
								<option value="CambioRazonSocial" <?php if($estado=="CambioRazonSocial"){ echo "selected";}?>>Solicitud de Cambio Raz&oacute;n Social</option> 
                                <option value="CambioDomicilio" <?php if($estado=="CambioDomicilio"){ echo "selected";}?>>Solicitud de Cambio Domicilio</option> 	
                               	<option value="ModificaDatos" <?php if($estado=="ModificaDatos"){ echo "selected";}?> >Modificaci&oacute;n de datos</option> 
                               	<option value="SolicitudBaja" <?php if($estado=="SolicitudBaja"){ echo "selected";}?>>Solicitud de Baja</option> 
                               	<?php		
                            }?>
                        </select>
                    </div>  
                    
                    <div class="bloque_7">
                        <label for="tiposelect">Tipo</label>
                        <select id="tiposelect" name="tiposelect"/> <?php
                            $tiposCuenta	= DataManager::getTiposCuenta(1); 
                            if (count($tiposCuenta)) {?>	
                                <option value="" selected></option> <?php
                                foreach ($tiposCuenta as $k => $tipoCta) {
                                    $ctaTipoId		=	$tipoCta["ctatipoid"];
                                    $ctaTipo		=	$tipoCta["ctatipo"];
                                    $ctaTipoNombre	=	$tipoCta["ctatiponombre"];	
                                    ?><option id="<?php echo $ctaTipoId; ?>" value="<?php echo $ctaTipo; ?>" <?php if ($tipo == $ctaTipo){ echo "selected"; } ?>><?php echo $ctaTipoNombre; ?></option><?php
                                }                          
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_7">
                        <label for="idCuenta">C&oacute;digo</label>
                        <input type="text" name="idCuenta" id="idCuenta" maxlength="10" value="<?php echo $idCuenta;?>" placeholder="C&oacute;digo" readonly/>
                    </div> 
                    
                    <div class="bloque_7">
                        <label for="categoriaComer">Cat. Comercial</label>
                        <select id="categoriaComer" name="categoriaComer"/> <?php
                            $categoriasComerc	= DataManager::getCategoriasComerciales(1); 
                            if (count($categoriasComerc)) {	 ?>
                                <option id="" value=""></option><?php
                                foreach ($categoriasComerc as $k => $catComerc) {
                                    $catComIdcat	= $catComerc["catidcat"];
                                    $catNombre		= $catComerc["catnombre"];									
									$selected = ($catComIdcat == $categoriaComer) ? "selected" : "" ; ?>
                                    <option id="<?php echo $catComIdcat; ?>" value="<?php echo $catComIdcat; ?>" <?php echo $selected;?>><?php echo $catComIdcat." - ".$catNombre; ?></option><?php
                                }                              
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_8">
                        <label for="zona">Zona</label>
                        <select id="zona" name="zona"/>
                            <option value="" selected></option>
                            <?php
                            $zonas	= DataManager::getZonas(1, 1000, NULL); 
                            if (count($zonas)) {	
                                foreach ($zonas as $k => $zon) {
                                    $zId		=	$zon["zid"];
                                    $nroZona	=	$zon["zzona"];
									$nombreZona	=	$zon["znombre"];
									if($nombreZona != 'SIN ZONA'){
										if ($nroZona == $zona){ $selected="selected";
										} else { $selected=""; } ?>
                                    	<option id="<?php echo $nroZona; ?>" value="<?php echo $nroZona; ?>" <?php echo $selected;?>><?php echo $nroZona; ?></option>
                                    	<?php
									}
                                }                              
                            } ?>
                        </select>
                    </div> 
                    
                    <div class="bloque_8">
                        <label for="ruteo">Rut&eacute;o</label>
                        <select id="ruteo" name="ruteo"/>
                            <option value="" selected></option>
                            <option value="15"	<?php if($ruteo==15){ echo "selected";}?>>15 d&iacute;as</option>
                            <option value="30" <?php if($ruteo==30){ echo "selected";}?>>30 d&iacute;as</option>
                            <option value="45" <?php if($ruteo==45){ echo "selected";}?>>45 d&iacute;as</option>
                            <option value="60" <?php if($ruteo==60){ echo "selected";}?>>60 d&iacute;as</option>
                            <option value="90" <?php if($ruteo==90){ echo "selected";}?>>90 d&iacute;as</option>
                            <option value="120" <?php if($ruteo==120){ echo "selected";}?>>120 d&iacute;as</option>
                        </select>
                    </div>                   
                    
                    <div class="bloque_3">
                        <label for="nombre">Raz&oacute;n Social</label>
                        <input type="text" name="nombre" id="nombre" maxlength="100" placeholder="Raz&oacute;n Social" value="<?php echo $nombre;?>" onkeypress="return dac_ValidarCaracteres(event)" class="text-uppercase"/>
                    </div>  
                    
                    <div class="bloque_7">
                        <label for="empleados">Empleados</label>
                        <input type="text" name="empleados" id="empleados" maxlength="10" value="<?php echo $empleados;?>" placeholder="Empleados"/>
                    </div>
                    
                    <div class="bloque_7">
                        <label for="listaPrecio">Lista</label>
                        <select id="listaPrecio" name="listaPrecio"/>
                           <?php
                            $listas = DataManager::getListas(1); 
                            if (count($listas)) { ?>
                                <option value="0" selected></option><?php
								if($categoriaComer){
									foreach ($listas as $k => $list) {
										$listId		= $list["IdLista"];
										$listNombre	= $list["NombreLT"];
										$selected	= ($listId == $lista) ? 'selected' : ''; ?>
										<option value="<?php echo $listId; ?>" <?php echo $selected; ?>><?php echo $listNombre; ?></option> <?php
									}   
								}
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_7">
                        <label for="agenteRetPerc">Ag Retenci&oacute;n</label>
                        <select id="agenteRetPerc" name="agenteRetPerc"/>
                            <option value=""></option>
                            <option value="N" <?php if($agenteRetPerc=="N"){ echo "selected";}?>>NO</option>
                            <option value="S"	<?php if($agenteRetPerc=="S"){ echo "selected";}?>>SI</option>
                        </select>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="categoriaIva">Categor&iacute;a Iva <?php echo $categoriaIva; ?> </label>
                        <select id="categoriaIva" name="categoriaIva"/> <?php
                            $categoriasIva	= DataManager::getCategoriasIva(1); 
                            if (count($categoriasIva)) { ?>
                                <option id="" value=""></option><?php
                                foreach ($categoriasIva as $k => $catIva) {
                                    $catIdcat	=	$catIva["catidcat"];
                                    $catNombre	=	$catIva["catnombre"];
                                    if ($catIdcat == $categoriaIva){ $selected='selected';
                                    } else { $selected=""; } ?>
                                    <option id="<?php echo $catIdcat; ?>" value="<?php echo $catIdcat; ?>" <?php echo $selected; ?>><?php echo $catNombre; ?></option> <?php
                                }                              
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="cadena">Cadena</label>
                        <select id="cadena" name="cadena"> <?php
							$idCadenaCad = 0;
							if($idCuenta){
								$cuentasCad	= DataManager::getCuentasCadena($empresa, NULL, $idCuenta);
								if (count($cuentasCad)) {
									foreach ($cuentasCad as $j => $ctaCad) {
										$idCadenaCad	= 	$ctaCad['IdCadena'];
										$tipoCadena		= 	$ctaCad['TipoCadena'];
									}
								} 
							}

							$cadenas	= DataManager::getCadenas($empresa); 
							if (count($cadenas)) { ?>
								<option id="" value=""></option><?php
								foreach ($cadenas as $k => $cad) {
									$idcadena	=	$cad["IdCadena"];
									$cadnombre	=	$cad["NombreCadena"];
									$selected = ($idcadena == $idCadenaCad) ? "selected" : "";  ?>
									
									<option id="<?php echo $idcadena; ?>" value="<?php echo $idcadena; ?>" <?php echo $selected;?>><?php echo $cadnombre; ?></option><?php
								}                              
							}  ?>
                        </select>
                    </div> 
                    
                    <div class="bloque_7">
                        <label for="tipoCadena">Tipo Cadena</label>
                        <select id="tipoCadena" name="tipoCadena"> <?php
							switch($tipoCadena){
								case 1: ?>
									<option id="0" value="0"></option>
									<option id="1" value="1"  selected>Sucursal</option> <?php
									break;
								default: ?>
									<option id="0" value="0" selected></option>
									<option id="1" value="1">Sucursal</option> <?php
									break;
							} ?>
                        </select>
                    </div> 
                    
                    <div class="bloque_7">
                        <label for="nroIngBrutos">Nro Ing Brutos</label>
                        <input id="nroIngBrutos" name="nroIngBrutos" type="text"  value="<?php echo $nroIngBrutos;?>" maxlength="13" placeholder="Nro Ing Brutos"/>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="condicionPago">Condici&oacute;n de Pago </label>
                        <select id="condicionPago" name="condicionPago"> <?php
                            $condicionesPago = DataManager::getCondicionesDePago(0, 100, 1); 
                            if (count($condicionesPago)) { ?>
                                <option id="" value=""></option><?php
                                foreach ($condicionesPago as $k => $condPago) {									
                                    $condPagoCodigo	=	$condPago["IdCondPago"];
									$condPagoNombre	= 	DataManager::getCondicionDePagoTipos('Descripcion', 'ID', $condPago['condtipo']);										
									$condPagoDias = "(";					
									$condPagoDias .= empty($condPago['Dias1CP']) ? '0' : $condPago['Dias1CP'];
									$condPagoDias .= empty($condPago['Dias2CP']) ? '' : ', '.$condPago['Dias2CP'];
									$condPagoDias .= empty($condPago['Dias3CP']) ? '' : ', '.$condPago['Dias3CP'];
									$condPagoDias .= empty($condPago['Dias4CP']) ? '' : ', '.$condPago['Dias4CP'];
									$condPagoDias .= empty($condPago['Dias5CP']) ? '' : ', '.$condPago['Dias5CP'];
									$condPagoDias .= " D&iacute;as)";
									
									$condPagoDias .= ($condPago['Porcentaje1CP'] == '0.00') ? '' : ' '.$condPago['Porcentaje1CP'].' %';
									
                                    if ($condPagoCodigo == $condicionPago){ $selected="selected";
                                    } else { $selected=""; } ?>
                                    <option id="<?php echo $condPagoCodigo; ?>" value="<?php echo $condPagoCodigo; ?>" <?php echo $selected;?>><?php echo $condPagoCodigo.", ".$condPagoNombre." ".$condPagoDias; ?></option>
                                    <?php
                                }				  
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_7">
                        <label for="cuit">CUIT</label>
                        <input id="cuit" name="cuit" type="text"  value="<?php echo $cuit;?>" maxlength="13" placeholder="Cuit"/>
                    </div>
                    
                    <div class="bloque_7"> 
                        <label for="cuit">Fecha Alta</label>               
                        <input type="text" id="fechaAlta" name="fechaAlta" size="15" maxlength="10" value="<?php echo $fechaAlta;?>" <?php if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){ echo 'readonly'; } ?> readonly />
                    </div>
                    
                    <div class="bloque_5">
                        <label for="direccionEntrega">Direcci&oacute;n ALTERNATIVA</label>
                        <input id="direccionEntrega" name="direccionEntrega" type="text"  value="<?php echo $direccionEntrega;?>" maxlength="50" placeholder="Direcci&oacute; de entrega" onkeypress="return dac_ValidarCaracteres(event)"/>
                    </div>
                    
                    <div class="bloque_5">
                        <label for="web">Web</label>
                        <input type="text" name="web" id="web" maxlength="150" value="<?php echo $web;?>" placeholder="Web"/>
                    </div> 
                    
                    <div class="bloque_5">
                        <label for="correo">Correo electr&oacute;nico</label>
                        <input type="text" name="correo" id="correo" maxlength="50" value="<?php echo $correo;?>" placeholder="Correo electr&oacute;nico"/>
                    </div>
                    
                    <div class="bloque_7">
                        <label for="telefono">Tel&eacute;fono</label>
                        <input type="text" name="telefono" id="telefono" maxlength="20" value="<?php echo $telefono;?>" placeholder="Tel&eacute;fono"/>
                    </div>
                    
                    <div class="bloque_7">
                        <label for="telefono">Compr&oacute;</label>
                        <input type="text" value="<?php echo $fechaCompra;?>" readonly disabled>
                    </div>
                    
                    <div class="bloque_1">
                        <label for="observacion">Observaci&oacute;n</label>
                        <textarea name="observacion" id="observacion" type="text" onkeyup="javascript:dac_LimitaCaracteres(event, 'observacion', 180);" onkeydown="javascript:dac_LimitaCaracteres(event, 'observacion', 180);" maxlength="180" value="<?php echo $observacion;?>" onkeypress="return dac_ValidarCaracteres(event)"/><?php echo $observacion;?></textarea> 
                        </br>
                        <fieldset id='box_informacion' class="msg_informacion">
                        	<div id="msg_informacion" align="center"></div> 
                        </fieldset>   
                    </div>
                    
                    <div class="bloque_5">
                        <label for="frente">Imagen Frente</label><br/>
                        <input name="nombreFrente" type="text" value="<?php echo $imagen1; ?>" hidden/>
						<input id="frente" name="frente" class="file" type="file"/>
                        <img src="<?php echo $img1; ?>" alt="Imagen Frente" width="200" height="200" />
                    </div>
                    
                    <div class="bloque_5">
                        <label for="interior">Imagen Interior</label><br/>
                       	<input name="nombreInterior" type="text" value="<?php echo $imagen2; ?>" hidden/>
						<input id="interior" name="interior" class="file" type="file"/>
                    	<img src="<?php echo $img2; ?>" alt="Imagen Interior" width="200" height="200"/>
                    </div>
                    
                    <div class="bloque_1">
                        <label for="multifile">Otros Archivos (M&aacute;ximo de 5 archivos por vez)</label><br/>
                        <div class="inputfile"><input id="multifile" name="multifile[]" class="file" type="file" multiple/></div>
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend>Domicilio</legend>                    
                    <div class="bloque_5">
                        <label for="provincia">Provincia</label>
                        <select id="provincia" name="provincia"> 
                            <option value="0" selected> Provincia... </option> <?php
                            $provincias	= DataManager::getProvincias(); 
                            if (count($provincias)) {	
                                $idprov = 0;
                                foreach ($provincias as $k => $prov) {		
                                    if ($provincia == $prov["provid"]){ 
                                        $selected = "selected";										
                                    } else { 
                                        $selected = ""; 
                                    } ?>                        		
                                    <option id="<?php echo $prov["provid"]; ?>" value="<?php echo $prov["provid"]; ?>" <?php echo $selected; ?>><?php echo $prov["provnombre"]; ?></option>   <?php
                                }                            
                            } ?> 
                        </select>
                    </div>
                    
                    <div class="bloque_5">	
                        <label for="localidad">Localidad <?php if(!$localidad) {echo $localidadNombre; }?></label>
                        <select id="localidad" name="localidad"></select>
                    </div>                    
                    <?php 
                       if ($provincia != 0){
                            echo "<script>";
                            echo "dac_changeLocalidad($localidad)";
                            echo "</script>";
                        }
                    ?>
                    
                    <div class="bloque_5">
                        <label for="direccion">Direcci&oacute;n</label>
                        <input type="text" name="direccion" id="direccion" maxlength="200" value="<?php echo $direccion;?>" onkeypress="return  dac_ValidarCaracteres(event)"/>
                    </div>
                    
                    <div class="bloque_8">
                        <label for="nro">N&uacute;mero </label>
                        <input type="text" name="nro" id="nro" maxlength="6" value="<?php echo $nro;?>"/>
                    </div>
                    
                    <div class="bloque_8">
                        <label for="piso">Piso </label>
                        <input type="text" name="piso" id="piso" maxlength="3" value="<?php echo $piso;?>"/>
                    </div>
                    
                    <div class="bloque_8">
                        <label for="dpto">Dpto</label>
                        <input type="text" name="dpto" id="dpto" maxlength="6" value="<?php echo $dpto;?>"/>
                    </div>
                    
                    <div class="bloque_8">
                        <label for="codigopostal">CP</label>
                        <input type="text" name="codigopostal"  id="codigopostal" maxlength="10" value="<?php echo $cp;?>"/>
                    </div>
                    
                    <div class="bloque_6">
                        <label for="longitud">Longitud</label>
                        <input type="text" name="longitud" id="longitud" value="<?php echo $longitud;?>" readonly/>
                    </div>
                    
                    <div class="bloque_6">
                        <label for="latitud">Latitud</label>
                        <input type="text" name="latitud" id="latitud" value="<?php echo $latitud;?>" readonly/>
                    </div>
                    
                    <div class="bloque_7">
                        <img class="icon-locate" alt="Google Maps" onClick="javascript:dac_getLatitudLongitud(provincia.options[provincia.selectedIndex].text, localidad.options[localidad.selectedIndex].text, direccion.value, nro.value)"/>                         
                    </div> 
                    
                    <div class="bloque_1"> <?php
						if(!empty($latitud) && !empty($longitud)) {
							echo "<script>";
							echo "dac_showMap( '$latitud', '$longitud' )";
							echo "</script>"; 
						} ?>
                        <div id="googleMap"></div>  
                    </div>                         
                </fieldset>
                
                <fieldset>
                    <legend>Droguer&iacute;as relacionadas</legend>
                    <fieldset id='box_cargando2' class="msg_informacion"> 
						<div id="msg_cargando2" align="center"></div>      
					</fieldset>
                    <div id="detalle_cuenta2"></div>
                    
                    <div class="bloque_1">
						<a id="btnSendTransfer" title="Enviar" style="cursor:pointer;"> 
							<img class="icon-save"/>
						</a>
                	</div>                  
                </fieldset>
                
                <?php
				//Las cuentas pueden trbaajar con cuentas droguerías
                if ($ctaId) {
                    $cuentasRelacionadas	=	DataManager::getCuentasRelacionadas($ctaId);
                    if (count($cuentasRelacionadas)) {	
                        foreach ($cuentasRelacionadas as $k => $ctaRel) {
                            $ctaRelId			=	$ctaRel["ctarelid"];
                            $ctaRelDrogId		=	$ctaRel["ctarelidcuentadrog"];		
							$ctaRelIdCuenta		= DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaRelDrogId);	
							$ctaRelNombre 		= DataManager::getCuenta('ctanombre', 'ctaid', $ctaRelDrogId);							
                            $ctaRelClienteTrans	=	$ctaRel["ctarelnroclientetransfer"];
                            echo "<script>";
                            echo "javascript:dac_cargarCuentaTransferRelacionada2('".$ctaRelId."', '".$ctaRelDrogId."', '".$ctaRelIdCuenta."', '".$ctaRelNombre."', '".$ctaRelClienteTrans."');";
                            echo "</script>";
                        }
                    }
                } ?>
            </form>
      	</div> <!-- END box_body --> 
                            
        <div class="box_seccion"> <?php
			if ($ctaId) {
				$_nroRel	=	1;
				$_origen	=	'TCuenta';
				$_idorigen	=	$ctaId;	include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.televenta.php");
				include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.accord.php"); 
			}
			?> 
            <hr>
            <fieldset>
                <legend>Documentaci&oacute;n</legend>  
                <div class="bloque_1">  <?php
                    $ruta 	=	$_SERVER['DOCUMENT_ROOT'].'/pedidos/cuentas/archivos/'.$ctaId.'/';
                    $data	=	dac_listar_directorios($ruta);
                    if($data){ ?>
                        <table align="center" >
                            <thead>
                                <tr align="left">
                                    <th>Subido</th>
                                    <th>Archivo</th>
                                    <th>Imagen</th>
                                    <?php if ($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M"){ ?>	
                                        <th>Acciones</th>
                                    <?php } ?>	
                                </tr>
                            </thead>
                            <tbody>	
                                <?php	
                                $fila 	= 0;
                                foreach ($data as $file => $timestamp) {
                                    $fila = $fila + 1;
                                    (($fila % 2) == 0)? $clase="par" : $clase="impar";
									
									//saco la extensión del archivo
                                    $extencion	=	explode(".", $timestamp);
                                    $ext		=	$extencion[1];																	
                                    $name 		=	explode("-", $timestamp, 4);
                                    $archivo 	= 	trim($name[3]);
									?> 
                                    <tr id="archivo<?php echo $file;?>" class="<?php echo $clase;?>">
                                        <td><?php echo $name[0]."-".$name[1]."-".$name[2]; ?></td>
                                        <td><?php echo wordwrap($name[3], 12, "<br />\n", true); ?></td>
                                        <td align="center"> <?php         
                                            if($ext == "pdf"){ ?>                                                
                                                <a href="<?php echo "/pedidos/cuentas/archivos/".$ctaId."/".$archivo; ?>" target="_blank">
                                                    <img id="imagen" class="icon-pdf"/>
                                                </a> <?php 
                                            } else { ?>   
                                                <img id="imagen" src="<?php echo "/pedidos/cuentas/archivos/".$ctaId."/".$archivo; ?>" onclick="javascript:dac_ShowImgZoom(this.src)" height="50px" style="cursor:pointer;"/> <?php  
                                            } ?>
                                        </td>
                                        <td align="center">
                                        	<input type="button" value=" - " onclick="dac_fileDelete('archivo<?php echo $file;?>', '/pedidos/js/ajax/eliminar.archivo.php', '<?php echo "/pedidos/cuentas/archivos/".$ctaId."/".$archivo; ?>')">
                                        </td>
                                	</tr> <?php
                                } ?>
                            </tbody>
                        </table> <?php                                                        
                    } else {?>
                        <table align="center">
                            <tr>
                                <td colspan="3"><?php echo "No hay documentaci&oacute;n disponible"; ?></td>	
                            </tr>  
                        </table><?php 
                    }?>
                </div> 
            </fieldset>
            
            <div class="barra">
               	<div class="bloque_1">
					<fieldset id='box_cargando3' class="msg_informacion"> 
						<div id="msg_cargando3" align="center"></div>      
					</fieldset>  
                </div>
                <div class="bloque_5">
                    Droguer&iacute;as 
                </div>
                <div class="bloque_5">
                    <input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
                    <input id="txtBuscarEn" type="text" value="tblCuentasTransfer" hidden/>
                </div> 
                <hr>
            </div> <!-- Fin barra -->                        
            <div class="lista">
                <div id='tablaCuentasTransfer2'></div>
            </div> <!-- Fin listar -->
        </div> <!-- FIN box_seccion -->
		        
    	<hr>
	</main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>

<script language="javascript" type="text/javascript">
	$("#relevar").click(function () {
		var url		=	"/pedidos/prospectos/logica/update.prospecto.php";		
		var origenid=	$("#ctaid").val();
		var nroRel	=	1;
		var origen	=	"TCuenta";
		var empresa =	$("#empselect").val();
		href ='/pedidos/relevamiento/relevar/index.php?origenid='+origenid+'&origen='+origen+'&nroRel='+nroRel+'&empresa='+empresa;
		window.open(href, '_blank');		
	});
</script>
