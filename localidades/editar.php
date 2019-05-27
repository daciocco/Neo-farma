<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$idLoc		= empty($_REQUEST['idLoc']) 	? 0							: $_REQUEST['idLoc'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/localidades/'	: $_REQUEST['backURL'];

if ($idLoc) {
	$localObject	= DataManager::newObjectOfClass('TLocalidad', $idLoc);
	$provincia		= $localObject->__get('IDProvincia');
	$localidad		= $localObject->__get('Localidad');
	$cp 			= $localObject->__get('CP');	
	$zonaVenta 		= $localObject->__get('ZonaVenta');
	$zonaEntrega	= $localObject->__get('ZonaEntrega');
} else {
	$provincia		= '';
	$localidad		= '';
	$cp 			= '';	
	$zonaVenta 		= '';
	$zonaEntrega	= '';
} ?>

<!doctype html>
<html lang='es'>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>		
	<script type="text/javascript" src="/pedidos/js/provincias/selectProvincia.js"></script>
</head>

<body>	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
        <script language="JavaScript"  src="/pedidos/localidades/logica/jquery/jqueryUsr.js" type="text/javascript"></script>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
       	$_section		= 'localidades';
        $_subsection 	= 'editar_localidades';
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>	
    
    <main class="cuerpo">    
    	<div class="box_body">  
        	<form name="fmLocalidadesEdit" method="post" enctype="multipart/form-data">
                <input type="text" id="idLoc" name="idLoc" value="<?php echo $idLoc;?>" hidden="hidden">
                <fieldset>
                    <legend>Localidad</legend>
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
                        
                        <fieldset id='box_observacion' class="msg_alerta" style="display: block">
                    		<div id="msg_atencion"> IMPORTANTE. Si agrega una nueva localidad asegúrese de que la misma no exista con el mismo nombre o uno similar. Evite generar duplicados.</div>
                    	</fieldset>
                    </div>
                    					
                    <div class="bloque_6">
                        <label for="provincia">Provincia</label>
                        <select id="provincia" name="provincia" <?php echo $disabled; ?> > 
                            <option value="0" selected> Provincia... </option> <?php
                            $provincias	= DataManager::getProvincias(); 
                            if (count($provincias)) {	
                                foreach ($provincias as $k => $prov) {
									$selected = ($provincia == $prov["provid"]) ? "selected" : "";
									
									if($idLoc) { 
										if($selected) {  ?>  
                                    		<option id="<?php echo $prov["provid"]; ?>" value="<?php echo $prov["provid"]; ?>" <?php echo $selected; ?> ><?php echo $prov["provnombre"]; ?></option><?php
										}
									} else { ?>  
                                    	<option id="<?php echo $prov["provid"]; ?>" value="<?php echo $prov["provid"]; ?>" <?php echo $selected; ?> ><?php echo $prov["provnombre"]; ?></option><?php
									}
                                }                            
                            } ?> 
                        </select>
                    </div>
                    
                    <div class="bloque_6">	
                        <label for="localidad">Localidad</label>
                        <input id="localidad" name="localidad" value="<?php echo $localidad;?>">
                    </div>                                      
                                        
                    <div class="bloque_7">
                        <label for="codigopostal">C&oacute;digo Postal</label>
                        <input type="text" name="codigopostal" maxlength="10" value="<?php echo $cp;?>">
                    </div>
                    
                    <div class="bloque_6">
                        <label for="zonaVSelect" >Zona Vendedor</label>
                        <select name="zonaVSelect">
                            <option value="" selected></option> <?php
                            $zonas	= DataManager::getZonas();
                            if (count($zonas)) {	
                                foreach ($zonas as $k => $zon) {
                                    $zId		=	$zon["zid"];
                                    $nroZona	=	$zon["zzona"];
									$nombreZona	=	$zon["znombre"];
									$zActivo	=	$zon["zactivo"];
									if($zActivo){
										$selected = ($nroZona == $zonaVenta) ? "selected" : "";
										?>
                                    	<option id="<?php echo $nroZona; ?>" value="<?php echo $nroZona; ?>" <?php echo $selected;?> ><?php echo $nroZona." - ".$nombreZona; ?></option>
                                    	<?php
									}
                                }                              
                            } ?>
                        </select>
                    </div>
                    
                    <div class="bloque_6">
                        <label for="zonaDSelect">Zona Distribución</label>
                        <select name="zonaDSelect">
                            <option value="" selected></option> <?php
							$zonasDistribucion	= DataManager::getZonasDistribucion(); 
							if (count($zonasDistribucion)) {
								foreach ($zonasDistribucion as $k => $zonasD) {
									$zonaDId	=	$zonasD["IdZona"];
									$zonaDNombre=	$zonasD["NombreZN"]; 									
									$selected 	= ($zonaDId == $zonaEntrega) ? "selected" : ""; ?>
									<option id="<?php echo $zonaDId; ?>" value="<?php echo $zonaDId; ?>" <?php echo $selected;?> ><?php echo $zonaDId." - ".$zonaDNombre; ?></option>
									<?php
								}
							} ?>
                        </select>
                    </div>
                    
                    <div class="bloque_8">
                        <?php $urlSend	= '/pedidos/localidades/logica/update.localidad.php';?>
                        <?php $urlBack	= '/pedidos/localidades/';?>
                        <a id="btnSend" title="Enviar"> 
                        	<br>
                            <img class="icon-send" onclick="javascript:dac_sendForm(fmLocalidadesEdit, '<?php echo $urlSend;?>', '<?php echo $urlBack;?>');"/>
                        </a>
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend>Excepciones</legend> 	
                    <div id="excepciones"></div>
                </fieldset>    
                
            </form>
      	</div> <!-- END box_body -->
      	                            
        <div class="box_seccion">            
            <div class="barra">
                <div class="bloque_5">
                    <h1>Cuentas</h1>                	
                </div>
                <div class="bloque_5">
                	<input id="txtBuscar" type="search" autofocus placeholder="Buscar..."/>
                    <input id="txtBuscarEn" type="text" value="tblTablaCta" hidden/>
                </div> 
                <hr>     
            </div> <!-- Fin barra -->            
            <div class="lista"> 
                <div id='tablacuenta'></div> <?php
                $zonasExpecion	= DataManager::getZonasExcepcion($idLoc);
				if(count($zonasExpecion)){
					//------------------
					$zonas	= DataManager::getZonas(0, 0, 1);
					foreach($zonas as $k => $zona){
						$arrayZonas[]	= $zona['zzona'];
					}	
					$stringZonas = implode(",", $arrayZonas);	
					//-------------------
					foreach ($zonasExpecion as $k => $ze) {						
						$zeCtaId	= $ze['zeCtaId'];
						$zeZona		= $ze['zeZona'];						
						$idCuenta	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $zeCtaId);
						$empresa	= DataManager::getCuenta('ctaidempresa', 'ctaid', $zeCtaId);
						
						echo "<script>";
						echo "javascript:dac_cargarDatosCuenta('".$zeCtaId."', '".$empresa."', '".$idCuenta."', '".$zeZona."', '".$stringZonas."');";
						echo "</script>";
					}
				} ?>
                
            </div> <!-- Fin lista -->		
        </div> <!-- FIN box_seccion -->
        
    	<hr>
	</main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>

<script language="JavaScript"  src="/pedidos/localidades/logica/jquery/jqueryUsrFooter.js" type="text/javascript"></script>
