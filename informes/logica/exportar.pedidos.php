<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php" );
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
	echo "Debe completar las fechas para exportar"; exit;
}

$fechaInicio		=	new DateTime(dac_invertirFecha($fechaDesde));
$fechaFin			=	new DateTime(dac_invertirFecha($fechaHasta));
$fechaFin->modify("+1 day");
//*************************************************

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=PedidosWeb-".date('d-m-y').".xls");

?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>
	<table border="0">
	<thead>
		<tr>
            <TD width="1px">Fecha</TD>
			<TD width="1px">Vendedor</TD>
			<TD width="1px">Cliente</TD>
			<TD width="1px">Pedido</TD>
			<TD width="1px">Lab</TD>
			<TD width="1px">IdArt</TD>
			<TD width="1px">Cant</TD>
			<TD width="1px">Precio</TD>
			<TD width="1px">B1</TD> <TD width="1px">B2</TD>
			<TD width="1px">D1</TD> <TD width="1px">D2</TD> <TD width="1px">D3</TD>
			<TD width="1px">CondPago</TD>
			<TD width="1px">OC</TD>
			<TD width="1px">Observaci&oacute;n</TD>
		</tr>
	</thead>			
	<?php	
	$pedidos	= DataManager::getPedidosEntre(0, $fechaInicio->format("Y-m-d"), $fechaFin->format("Y-m-d")); 
	if($pedidos){
		foreach ($pedidos as $k => $pedido) {					
			$nroPedido	= 	$pedido["pidpedido"];
			
			$detalles	= 	DataManager::getPedidos(NULL, 0, $nroPedido, NULL, NULL, NULL);
			if ($detalles) {
				foreach ($detalles as $j => $detalle) {	
					$_idusuario		= 	$detalle["pidusr"];
											
					//datos para control
					$_idemp			=	$detalle["pidemp"];
					$_idpack		=	$detalle["pidpack"];
					$_idlista		=	$detalle["pidlista"];
					//*****************//

					$_fecha_pedido	=	substr($detalle['pfechapedido'], 0, 10);				
					$_nombreusr		= 	DataManager::getUsuario('unombre', $_idusuario); 						
					$_idcli			= 	$detalle["pidcliente"];  
					$_nropedido		=	$detalle["pidpedido"]; 
					$_idlab			=	$detalle["pidlab"];  
					$_idart			=	$detalle['pidart'];
					//$_nombreart		=	DataManager::getArticulo('artnombre', $_idart, $_idemp, $_idlab);
					$_cantidad		=	$detalle['pcantidad'];							
					$_precio		=	str_replace('EUR','', money_format('%.3n', $detalle['pprecio']));
					$_b1			=	($detalle['pbonif1']) ? $detalle['pbonif1'] : '';
					$_b2			=	($detalle['pbonif2']) ? $detalle['pbonif2'] : '';
					$_desc1			=	($detalle['pdesc1']) ? $detalle['pdesc1'] : '';
					$_desc2			=	($detalle['pdesc2']) ? $detalle['pdesc2'] : '';
					$_desc3			=	'';
					$_condpago		=	$detalle["pidcondpago"];
					$_ordencompra	= 	($detalle["pordencompra"] == 0)	?	''	:	$detalle["pordencompra"];
					$_observacion	= 	$detalle["pobservacion"];		
						
					echo sprintf("<tr align=\"left\">");
					echo sprintf("<td >%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $_fecha_pedido, $_nombreusr, $_idcli, $_nropedido, $_idlab, $_idart, $_cantidad, $_precio, $_b1, $_b2, $_desc1, $_desc2, $_desc3, $_condpago, $_ordencompra, $_observacion);
					echo sprintf("</tr>");
				}
			}
		}
	}
	?>
	</table>	

</body>
</html>                
               
               