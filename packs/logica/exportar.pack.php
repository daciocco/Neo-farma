<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_fecha	=	empty($_REQUEST['fecha'])	?	date("d-m-Y")	:	$_REQUEST['fecha'];
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Packs-".$_fecha.".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos (Bonificaciones) .::</TITLE>
<head></head>
<body>
	<?php 
	//CUANDO CAMBIE LA FECHA, DEBERÍA RECARGAR LA WEB O ALGO ASI CON JAVASCRIPT Y AJAX
	$_packs	= DataManager::getPacks(0, 100, NULL, dac_invertirFecha($_fecha)); 
	if($_packs){
		foreach($_packs as $k => $_pack) {
			$_pack 			= 	$_packs[$k];
			$_packid		=	$_pack['packid'];
			$_nombre		= 	$_pack['packnombre'];
			$_cantidad		= 	$_pack['packcantmin'];
			$_condicionArray= 	($_pack['packcondpago']) ? explode(",", $_pack['packcondpago']) : '';
			$_fechainicio	= 	dac_invertirFecha( $_pack['packfechainicio']);
			$_fechafin		= 	dac_invertirFecha( $_pack['packfechafin']);                             
			$_observacion	= 	$_pack['packobservacion']; 
			$_activo		=	($_pack['packactiva'])	?	"ACTIVO"	:	"INACTIVO";?>
									
			<table class="datatab" border="0" cellpadding="0" cellspacing="0" style="font-size:14px; ">
				<thead>
					<tr>
						<th scope="colgroup" height="25" style="font-weight:bold;"><?php echo $_activo; ?></th>
						<th scope="colgroup" colspan="3"></th>
						<th scope="colgroup" style="background-color:#333; color:#FFF; font-weight:bold;" >Desde</th>
						<th scope="colgroup" style="background-color:#333; color:#FFF; font-weight:bold; font-size:16px;" ><?php echo $_fechainicio; ?></th>
					</tr>
					<tr>
						<th scope="colgroup" height="25" colspan="4"></th>
						<th scope="colgroup" style="background-color:#333; color:#FFF; font-weight:bold;">Hasta</th>
						<th scope="colgroup" style="background-color:#333; color:#FFF; font-weight:bold; font-size:16px;"><?php echo $_fechafin;	?></th>
					</tr>
					<tr>
						<th scope="colgroup" height="30" colspan="6" align="left" style="font-weight:bold; font-size:18px;"><?php echo $_nombre;?></th>
					</tr>                            
					<tr>
						<th scope="colgroup" height="18" align="left" style="background-color:#333; color:#FFF; font-weight:bold;">C&Oacute;DIGO</th>
						<th scope="colgroup" align="left" style="background-color:#333; color:#FFF; font-weight:bold; ">DESCRIPCI&Oacute;N</th>
						<th scope="colgroup" align="right" style="background-color:#333; color:#FFF; font-weight:bold;">PRECIO D</th>
						<th scope="colgroup" style="background-color:#333; color:#FFF; font-weight:bold;">CANTIDAD</th>
						<th scope="colgroup" style="background-color:#333; color:#FFF; font-weight:bold;">BONIFICACI&Oacute;N</th>
						<th scope="colgroup" align="right" style="background-color:#333; color:#FFF; font-weight:bold;">IMPORTE</th>
					</tr>
				</thead>
			<?php  					
				//Leo los detalles del pack
				$_pack_detalle	= DataManager::getDetallePack($_packid);
				if ($_pack_detalle) {	
					$_total = 0;		
					$_cant_total = 0;						 
					foreach($_pack_detalle as $j => $_detalle) {	
						$_det_idart		= $_detalle['pdartid'];	
						$_det_cant		= 	($_detalle["pdcant"] == 0)? '' : $_detalle["pdcant"];	
						$_det_bonif1	= ($_detalle["pdbonif1"] == 0)? '' : $_detalle["pdbonif1"];								
						$_det_bonif2	= ($_detalle["pdbonif2"] == 0)? '' : $_detalle["pdbonif2"];
						$_det_bonif		= ($_detalle["pddesc"])? $_detalle["pddesc"] : $_det_bonif1."x".$_det_bonif2;
						
						$_det_nombre	= DataManager::getArticulo('artnombre', $_det_idart, 1, 1);
						$_det_precio	= DataManager::getArticulo('artprecio', $_det_idart, 1, 1);
						
						$_cant_total	+=	$_det_cant;
						$_total			+=	$_det_cant * $_det_precio;
						
						((($j % 2) == 0)? $clase="" : $clase="background-color:#CCC; font-weight:700");
						?>
						
						<tr class="<?php echo $clase; ?>">
							<td scope="colgroup" height="18" style="width:25px; <?php echo $clase; ?>"><?php echo $_det_idart; ?></td>
							<td scope="colgroup" style="width:35px; <?php echo $clase; ?>"><?php echo substr($_det_nombre, 0, 30); ?></td>
							<td scope="colgroup" align="right" style="width:20px; <?php echo $clase; ?>"><?php echo number_format($_det_precio, 2 ); ?></td>
							<td scope="colgroup" align="center" style="width:25px; <?php echo $clase; ?>"><?php echo $_det_cant; ?></td>
							<td scope="colgroup" align="center" style="width:25px; <?php echo $clase; ?>"><?php echo $_det_bonif; ?></td>
							<td scope="colgroup" align="right" style="width:35px; <?php echo $clase; ?>"><?php echo number_format(($_det_cant * $_det_precio), 2); ?></td>
						</tr>		
						
						<?php	
					}
				} ?>    
                <tr>
                    <th scope="colgroup" height="20px;"></th>
                    <th scope="colgroup" align="right">TOTAL</th>
                    <th scope="colgroup"></th>
                    <th scope="colgroup"><?php echo $_cant_total; ?></th>
                    <th scope="colgroup"></th>
                    <th scope="colgroup" align="right"><?php echo number_format($_total, 2 ); ?></th>
                </tr>
				<tr><th scope="colgroup" colspan="6" height="20px;"></th></tr>
				<tr style="border-top:2px solid #333;">
					<th scope="colgroup" height="18" colspan="2" align="left" style="font-size:16px;">CONDICI&Oacute;N DE PAGO</th>
                    <th scope="colgroup" colspan="4"></th>
				</tr>
				<?php						
				$_condiciones	=	DataManager::getCondicionesDePago(); 
				if (count($_condiciones)) {	
					foreach ($_condiciones as $q => $_cond) {	
						$_idcond		=	$_cond["condid"];
						$_condcodigo	=	$_cond["condcodigo"];
						$_condnombre	=	$_cond["condnombre"];
						$_conddias		=	$_cond["conddias"];
						$_condporc		=	$_cond["condporcentaje"]; 
						
						if (in_array($_condcodigo, $_condicionArray)) {									
							?>
							<tr>
								<td scope="colgroup"></td>
								<td scope="colgroup" colspan="5"><?php echo $_condnombre." - ".$_conddias." - [".$_condporc."%]"; ?></td>
							</tr>
							<?php
						}
					}                           
				} ?>
				<tr><th scope="colgroup" colspan="6"></th></tr> 
                
                <?php				
				$texto 		= 	explode(" ", $_observacion);				
				$_frase 	= 	$texto[0]." ";
				$_palabras	=	10;
				
				for ($i = 1; $i < count($texto); $i++){
					$_frase .= $texto[$i]." ";
					
					if ($i == $_palabras) { ?>
						<tr>
                            <th scope="colgroup" colspan="6"> <?php
								echo $_frase;
								$_frase = '';
								$_palabras = $i + 10;
								?>
                            </th>     
                        </tr> 
						<?php
					}
				} ?>
                <tr style="border-bottom:2px solid #333;">
                    <th scope="colgroup" colspan="6"> <?php
                        echo $_frase;
                        $_frase = '';
                        ?>
                    </th>     
                </tr> 
                        
                
				<tr><th scope="colgroup" colspan="6" height="20px;"></th></tr>   
                <tr><th scope="colgroup" colspan="6"></th></tr> 
                <tr><th scope="colgroup" colspan="6"></th></tr> 
                 
			</table><?php 
		}		
	} ?>
</body>
</html>                
               
               