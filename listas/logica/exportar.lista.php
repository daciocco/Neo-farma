<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
}

$_listaid	=	empty($_GET['listaid'])	? 0 : $_GET['listaid']; 

if ($_listaid) {
	$_lista 				= DataManager::newObjectOfClass('TLista', $_listaid);
	$_listanombre 			= $_lista->__get('Nombre');
	$_listacondpago 		= $_lista->__get('CondicionPago');
					
	$_condicionespago	=	explode(",", $_listacondpago);
	if($_condicionespago){										
		for( $j=0; $j < count($_condicionespago); $j++ ) {	
			$_nombre		=	DataManager::getCondicionDePago('condnombre', 'condcodigo', trim($_condicionespago[$j]));	
			$_dias			=	DataManager::getCondicionDePago('conddias', 'condcodigo', trim($_condicionespago[$j]));				
			$_condnombre	= 	empty($_condnombre) ? $_nombre." ".$_dias : $_condnombre." - ".$_nombre." ".$_dias;					
		}
	}	
	
	$_listafechainicio	 	= $_lista->__get('FechaInicio');
	$_listafechafin 		= $_lista->__get('FechaFin');		
	$_listafechainicio 		= dac_invertirFecha( $_listafechainicio );	
	list($dia, $mes, $anio) 	= explode("-", $_listafechainicio);
	$_listafechafin 		= dac_invertirFecha( $_listafechafin );
		
	$_listaobservacion 		= $_lista->__get('Observacion');
}

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=ListaEspecial-".date("d-m-Y").".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head>	
	<style>
		.datatab{
			font-size:14px;
		}
		tr th {
			background-color: #f6f6f6;
			font-weight:bold;
			height: 40px;	
		}	
		
		td.par {
			background-color: #fff;
			height: 40px;
		}
		td.impar {
			background-color: #f6f6f6;
			height: 40px;
			font-weight:bold;
		}
	</style>
</head>
<body>
	<table class="datatab" border="0" cellpadding="0" cellspacing="0" width="600">
        <thead>
        	<tr>
                <th scope="colgroup" colspan="5" align="center" style="height:100px;"><?php echo $cabecera; ?></th>
            </tr>
        	<tr>
                <th scope="colgroup" colspan="2" align="center" style="font-size:24px; color:#117db6; border:1px solid #666" ><?php echo $_listanombre; ?></th>
                <th scope="colgroup" colspan="3" align="center" style="border:1px solid #666">Vigente desde <?php echo $_listafechainicio ; ?></th>
            </tr>
            <tr>
                <th scope="colgroup"  style="border:1px solid #666">Art</th>
                <th scope="colgroup"  style="border:1px solid #666">Descripci&oacute;n</th>
                <th scope="colgroup"  style="border:1px solid #666">Precio P&uacute;blico</th>
                <th scope="colgroup"  style="border:1px solid #666">Desc / Bonif</th>
                <th scope="colgroup"  style="border:1px solid #666">Precio Venta</th>
                <?php if($_listanombre == "FARMACITY") { ?>
                	<th scope="colgroup" >% Reinversi&oacute;n</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody> <?php 
            if ($_listaid) {	
                $_lista_detalle_art		= DataManager::getDetalleListaEspecialArt($_listaid);
                if (count($_lista_detalle_art)) {								 
                    foreach ($_lista_detalle_art as $k => $_detalle_art) {																		
                        $_detalle_art 	= $_lista_detalle_art[$k];
                        $_det_idart		= $_detalle_art['leaidart'];	                                        								
                        $_det_nombre	= DataManager::getArticulo('artnombre', $_det_idart, 1, 1);
						
						$_bonificacion	=	DataManager::getDetalleBonificacion($mes, $anio);
						if ($_bonificacion) {
							foreach ($_bonificacion as $k => $_bonif){
								$_bonif			=	$_bonificacion[$k];
								$_bonifArtid	= 	$_bonif['bonifartid'];
								
								if($_bonifArtid == $_det_idart){
									$_bonifPublico	= $_bonif['bonifpreciopublico'];
								}
							}
						} else {
							$_bonifPublico	= round(1.45*number_format(DataManager::getArticulo('artprecio', $_det_idart, 1, 1)), 3);	
						}
						
						$_det_precioArt = $_bonifPublico; 						
                        $_det_precio	= $_detalle_art["leaartprecio"];	
                        
						$_det_desc		= empty($_detalle_art['leaartdesc'])	?	'' :	$_detalle_art['leaartdesc'];
						if(empty($_det_desc)){
							$_det_bonif1	= 	empty($_detalle_art['leaartb1'])?	'' :	$_detalle_art['leaartb1'];
							$_det_bonif2	= 	empty($_detalle_art['leaartb2'])?	'' :	$_detalle_art['leaartb2'];
							
							if(!empty($_det_bonif1) && !empty($_det_bonif2)){
								$condPrecio	=	$_det_bonif1." X ".$_det_bonif2;
							}							
						} else {
							$condPrecio	=	$_det_desc." %";
						}
                        
                        $_estilo =	(($k % 2) == 0)? "par" : "impar";	
                    	?>
                        <tr>
                        	<td class="<?php echo $_estilo; ?>" align="center"><?php echo $_det_idart; ?></td>
                            <td class="<?php echo $_estilo; ?>" ><?php echo $_det_nombre; ?></td>
                            <td class="<?php echo $_estilo; ?>" align="center"><?php echo "$ ".$_det_precioArt; ?></td>
                            <?php if($_listanombre == "FARMACITY") { 
								switch($_det_idart){
									case 5: 
										$condPrecio = "% 44.13";
										break;
									case 200: 
										$condPrecio = "% 44.09";
										break;
									case 210:
									case 215:
										$condPrecio = "% 46.97";
										break;
									case 300:
										$condPrecio = "% 46.89";
										break;
									case 305:
										$condPrecio = "% 44.14";
										break;
									case 310: case 510:
										$condPrecio = "% 44.11";
										break;
									case 330:
										$condPrecio = "% 44.10";
										break;
									case 102: case 205: 
										$condPrecio = "% 43.13";
										break;
									case 140:
										$condPrecio = "% 41.79";
										break;
									case 150: case 155: case 170:
										$condPrecio = "% 45.00";
										break;
									case 500: 
										$condPrecio = "% 41.09";
										break;
									case 552:
										$condPrecio = "% 51.76";
										break;
									default:
										$condPrecio = "% ";
										break;
								} 
							} ?>
                            <td class="<?php echo $_estilo; ?>" align="center"><?php echo $condPrecio; ?></td-->
                            <td class="<?php echo $_estilo; ?>" align="center"><?php echo "$ ".$_det_precio; ?></td>
                            <?php if($_listanombre == "FARMACITY") { 
								switch($_det_idart){
									case 5: case 200: case 205: case 210: case 215: case 300: case 305: case 310: case 330:
										$_descReinv = "%4";
										break;
									case 102: case 140: case 150: case 155: case 170: case 500: case 510: case 552: 
										$_descReinv = "%5";
										break;
									default:
										$_descReinv = "%";
										break;
								}
								?> <td class="<?php echo $_estilo; ?>" align="center"><?php echo $_descReinv; ?></td> <?php
								?>                            	
                            <?php } ?>
                        </tr>	
                        <?php						
                    }
                }
            } 	else { ?>
				<tr>
                	<td scope="colgroup" colspan="5"  style="border:1px solid #666">No se encontraron condiciones.</td>
            	</tr>
                <?php
			}
            ?> 
            <tr>
                <td scope="colgroup" colspan="5"  style="border:1px solid #666">Condici&oacute;n de Pago: <?php echo $_condnombre; ?></td>
            </tr>    
            <tr>
                <td scope="colgroup" colspan="5"  style="border:1px solid #666"><?php echo $_listaobservacion ; ?></td>
            </tr>      
        </tbody>
        
        <tfoot>
        	<tr>
                <th scope="colgroup" colspan="5" align="center" style="height:100px;"><?php echo $pie; ?></th>
            </tr>	
        </tfoot>
        
        
    </table>
</body>
</html>                
               
               