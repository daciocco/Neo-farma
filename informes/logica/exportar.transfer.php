<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

//******************************************* 
 $fechaDesde	=	(isset($_POST['fechaDesde']))	? $_POST['fechaDesde']	: NULL;
 $fechaHasta	= 	(isset($_POST['fechaHasta']))	? $_POST['fechaHasta'] 	: NULL;
//******************************************* 
if(empty($fechaDesde) || empty($fechaHasta)){
	echo "Debe completar las fechas de exportación"; exit;
}

 $fechaInicio		=	new DateTime(dac_invertirFecha($fechaDesde));
 $fechaFin			=	new DateTime(dac_invertirFecha($fechaHasta));
 $fechaFin->modify("+1 day");
 //*************************************************
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=PedidosTransfers-".date('d-m-y').".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos (Pedidos Transfers) .::</TITLE>
<head></head>
<body>
	<table border="0">
	<thead>
		<tr>
       		<td scope="col" >Empresa</td>
       		<td scope="col" >Cliente</td>
			<td scope="col" >Raz&oacute;n Social</td>
       		<td scope="col" >Domicilio</td>
      	    <td scope="col" >Localidad</td>
      	    <td scope="col" >Provincia</td>
      	    <td scope="col" >Tipo</td>
      	    <td scope="col" >Suc</td>
       	    <td scope="col" >Nro Transfer</td>
       	    <td scope="col" >IdLab</td>
       	    <td scope="col" >ID Art&iacute;culo</td>
       	    <td scope="col" >Fecha</td>
       	    <td scope="col" >IdStock</td>
       	    <td scope="col" >Cantidad</td>  
       	    <td scope="col" >Bonificadas</td>  
       	    <td scope="col" >Importe Total</td> 
       	    <td scope="col" >Precio</td> 
       	    <td scope="col" >Precio Final</td> 
       	    <td scope="col" >Descuento</td> 
       	    <td scope="col" ></td> 
       	    <td scope="col" ></td> 
       	    <td scope="col" ></td> 
       	    <td scope="col" ></td> 
       	    <td scope="col" ></td> 
       	    <td scope="col" ></td> 
       	    <td scope="col" ></td> 
       	    <td scope="col" >Categ.</td>      	    
        	<td scope="col" >Dni Asig</td>
            <td scope="col" >Asignado a</td> 
            <td scope="col" >Mes</td>           
            <td scope="col" >A&ntilde;o</td>           
            <td scope="col" >Total</td>            
            <td scope="col" >Rubro</td>              
            <td scope="col" >Familia</td> 
            <td scope="col" >Canal</td>     
            <td scope="col" >Cuit</td>       
            <td scope="col" >Cadena</td>
           	<td scope="col" >Dni</td>
            <td scope="col" >Vendedor</td>          
            <td scope="col" >Id Drogueria</td>
            <td scope="col" >Drogueria</td>
		</tr>
	</thead>			
	<?php
	$_transfers_recientes	= DataManager::getTransfers(0, NULL, $fechaInicio->format('Y-m-d'), $fechaFin->format('Y-m-d')); 
	if($_transfers_recientes){
		for( $k=0; $k < count($_transfers_recientes); $k++ ){	
			$_transfer_r 	= 	$_transfers_recientes[$k];
			$fecha 		= 	explode(" ", $_transfer_r["ptfechapedido"]);
							list($ano, $mes, $dia) 	= 	explode("-", $fecha[0]);
			$_fecha 	= 	$dia."-".$mes."-".$ano;						
			$_nropedido	= 	$_transfer_r["ptidpedido"];				
			$_nombre	= 	$_transfer_r["ptclirs"];
			
			$_detalles	= 	DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_nropedido);
				//DataManager::getDetallePedidoTransfer($_nropedido);
			if ($_detalles) {
				for( $j=0; $j < count($_detalles); $j++ ){	
					$_precio_final 	= 0;
					$_importe_final = 0;
					$cadNombre		= '';
										
					$_detalle 		= 	$_detalles[$j];	
					$_nombreven		= 	DataManager::getUsuario('unombre', $_detalle['ptidvendedor']);
					$_nombreven		= 	utf8_decode($_nombreven);
					$_dniven		= 	DataManager::getUsuario('udni', $_detalle['ptidvendedor']);						
					$nombreAsignado	=	DataManager::getUsuario('unombre', $_detalle['ptparaidusr']);
					$nombreAsignado	= 	utf8_decode($nombreAsignado);
					$dniAsignado	=	DataManager::getUsuario('udni', $_detalle['ptparaidusr']);			
					$empresa	= 1;		
									
					
					$_idcliente_drog=	$_detalle['ptnroclidrog'];							
					if ($_detalle['ptidclineo'] != 0) {
						$ctaId		= 	$_detalle['ptidclineo'];
						$_ruteo		= 	DataManager::getCuenta('ctaruteo', 'ctaid', $ctaId);
						$_categoria	= 	DataManager::getCuenta('ctacategoriacomercial', 'ctaid', $ctaId);
						
						$cuit		= 	DataManager::getCuenta('ctacuit', 'ctaid', $ctaId);
						$empresa	= 	DataManager::getCuenta('ctaidempresa', 'ctaid', $ctaId);
						$cuenta		= 	DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaId);
						
						//-----------------						
						$idCadenaCad= 0;
						$cuentasCad	= DataManager::getCuentasCadena($empresa, NULL, $cuenta);
						if (count($cuentasCad)) {
							foreach ($cuentasCad as $q => $ctaCad) {
								$idCadenaCad = $ctaCad['IdCadena'];
							}
						}			
						
						$cadNombre	= '';
						if($idCadenaCad){
							$cadenas	= DataManager::getCadenas($empresa, $idCadenaCad); 
							if (count($cadenas)) { 
								foreach ($cadenas as $q => $cad) {
									$cadNombre	= $cad["NombreCadena"];  
								}                              
							}
						}
						
						$canal = '';
						if(empty($cadNombre)){
							$cadNombre 	= $_nombre;
							$canal 		= 'Minorista';
						} else {
							$canal = 'Cadena';
						}
						//----------------
						
						$_ruteo		=	($_ruteo == 0) ? '' : $_ruteo;
						$_categoria	=	($_categoria == 0) ? '' : $_categoria;
						$categoria	=	$_ruteo.$_categoria;
					} else { 
						$ctaId 		= 	$_idcliente_drog; 
						$categoria	=	"Transfer";
					}
					
					$_iddrogueria	=	$_detalle['ptiddrogueria'];					
					$_nombredrog	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_iddrogueria, $empresa);	
						
					$_contacto		= $_detalle['ptcontacto'];						
					$_unidades		= $_detalle['ptunidades'];
					$_descuento		= $_detalle['ptdescuento'];						
					$_ptidart		= $_detalle['ptidart'];
					$_ptprecio		= $_detalle['ptprecio'];
					
					$_descripcion	=	DataManager::getArticulo('artnombre', $_ptidart, $empresa, 1);	
					$artIdRubro		=	DataManager::getArticulo('artidrubro', $_ptidart, $empresa, 1);
					$artIdFamilia	=	DataManager::getArticulo('artidfamilia', $_ptidart, $empresa, 1);
					
					$rubroDesc		= '';
					$rubros			= DataManager::getRubros($artIdRubro); 
					if (count($rubros)) {
						foreach ($rubros as $q => $rub) {
							$rubroDesc	= $rub["Descripcion"];		
						}    
					}

					$nombreFlia		= '';
					$familias	= DataManager::getCodFamilias(0,0,$empresa, $artIdFamilia); 
					if (count($familias)) {
						foreach ($familias as $q => $flia) {
							$nombreFlia	= $flia["Nombre"];		
						}    
					} 
								
					$_precio_final	=	round( ($_ptprecio - (($_descuento/100)*$_ptprecio)), 3);	
					$_importe_final	=	round( $_precio_final * $_unidades, 3);
						
					echo sprintf("<tr align=\"left\">");
					echo sprintf("<td>1</td><td >%s</td><td>%s</td><td>0</td><td>0</td><td>0</td><td>Transfer</td><td>0</td><td>%s</td><td>1</td><td>%s</td><td>%s</td><td>0</td><td>%s</td><td>0</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $cuenta, $_nombre, $_nropedido, $_ptidart, $_fecha, $_unidades, $_importe_final, $_ptprecio, $_precio_final, $_descuento, $categoria, $dniAsignado, $nombreAsignado, $mes, $ano, $_unidades, $rubroDesc, $nombreFlia, $canal, $cuit, $cadNombre, $_dniven, $_nombreven, $_iddrogueria, $_nombredrog);
					echo sprintf("</tr>");
				}
			}	
		}
	}
	?>
	</table>	

</body>
</html>                
               
               