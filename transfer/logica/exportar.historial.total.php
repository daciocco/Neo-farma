<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}
	
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
			<td scope="col" >Fecha</td>
			<td scope="col" >Transfer</td>
            <td scope="col" >Dni</td>
            <td scope="col" >Vendedor</td>
            <td scope="col" >Id Drogueria</td>
            <td scope="col" >Drogueria</td>
            <td scope="col" >Cliente en Drog</td>
            <td scope="col" >Cliente en Neo</td>
			<td scope="col" >Cliente</td>
            <td scope="col" >ID Art&iacute;culo</td>
            <td scope="col" >Art&iacute;culo</td>
            <td scope="col" >Cantidad</td>
            <td scope="col" >Precio</td>
            <td scope="col" >Descuento</td>
            <td scope="col" >Precio Finalo</td>
            <td scope="col" >Importe Total</td>
		</tr>
	</thead>			
	<?php
	$_transfers_recientes	= DataManager::getTransfers(0); 
	if($_transfers_recientes){
		for( $k=0; $k < count($_transfers_recientes); $k++ ){	
			$_transfer_r 	= 	$_transfers_recientes[$k];
			$fecha 		= 	explode(" ", $_transfer_r["ptfechapedido"]);
							list($ano, $mes, $dia) 	= 	explode("-", $fecha[0]);
			$_fecha 	= 	$dia."-".$mes."-".$ano;						
			$_nropedido	= 	$_transfer_r["ptidpedido"];				
			$_nombre	= 	$_transfer_r["ptclirs"];
			
			$_detalles	= DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_nropedido); //DataManager::getDetallePedidoTransfer($_nropedido);
			if ($_detalles) { 
				for( $j=0; $j < count($_detalles); $j++ ){	
					$_precio_final = 0;
					$_importe_final = 0;
					
					$_detalle 		= 	$_detalles[$j];	
					$_idvendedor	=	$_detalle['ptidvendedor'];			
					$_nombreven		= 	DataManager::getUsuario('unombre', $_idvendedor);
					$_dniven		= 	DataManager::getUsuario('udni', $_idvendedor);									
					$_iddrogueria	=	$_detalle['ptiddrogueria'];
					
					$ctaId			= $_detalle['ptidclineo'];
						
					$ctaIdCuenta	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaId);
					$_nombredrog	= strtoupper(DataManager::getCuenta('ctanombre', 'ctaid', $ctaIdCuenta));	
					
																				
					$_idcliente_drog	=	$_detalle['ptnroclidrog'];
							
					if ($ctaId != 0){
						$_idcliente_neo	= 	$ctaId;
					} else {
						$_idcliente_neo = "";
					}									
					
					$_contacto			=	$_detalle['ptcontacto'];						
					$_unidades			=	$_detalle['ptunidades'];
					$_descuento			=	$_detalle['ptdescuento'];						
					$_ptidart			=	$_detalle['ptidart'];
					$_ptprecio			=	$_detalle['ptprecio'];								
					$_descripcion		=	DataManager::getArticulo('artnombre', $_ptidart, 1, 1);	
					$_precio_final		=	round( ($_ptprecio - (($_descuento/100)*$_ptprecio)), 3);	
					$_importe_final		=	round( $_precio_final * $_unidades, 3);
						
					echo sprintf("<tr align=\"left\">");
					echo sprintf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $_fecha, $_nropedido, $_dniven, $_nombreven, $_iddrogueria, $_nombredrog, $_idcliente_drog, $_idcliente_neo, $_nombre, $_ptidart, $_descripcion, $_unidades, $_ptprecio, $_descuento, number_format($_precio_final,2), number_format($_importe_final,2));
						echo sprintf("</tr>");
				}
			}	
		}
	}
	?>
	</table>	

</body>
</html>                
               
               