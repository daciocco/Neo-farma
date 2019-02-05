<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
} 

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=PedidosPendientes-".date('d-m-y').".xls");	
?>

<!DOCTYPE html>
<html>
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>

<body>
	<?php
	// Recorro por empresa //
	$_empresas	= 	DataManager::getEmpresas(1);
	if ($_empresas) { 
		foreach ($_empresas as $k => $_emp) {
			$_idempresa		= 	$_emp['empid'];											
			$_nombreemp		= 	$_emp['empnombre'];
			
			// Selecciono los Pedidos Activos por Empresa PARA Pre-facturar //
			$_pedidos 	= 	DataManager::getPedidos(NULL, 1, NULL, $_idempresa, NULL, 0); 
			if ($_pedidos) {   ?>
				<table id="tblExport_<?php echo $_idempresa;?>" border="1">
					<TR>
						<TD colspan="2">Fecha:</TD>
						<TD colspan="3" align="left"><?php echo date("d/m/y H:i:s"); ?></TD>
						<TD colspan="11"  align="center"><?php echo $_nombreemp;?></TD>
					</TR>
					<TR>
						<TD colspan="2">Exportado por:</TD>
						<TD colspan="3"><?php echo $_SESSION["_usrname"]; ?></TD>
						<TD colspan="11" align="center"></TD>
					</TR>			
					<TR>
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
					</TR> <?php	

					foreach ($_pedidos as $j => $_pedido) { 
						$_idpedido		= 	$_pedido["pid"];
						$_idusuario		= 	$_pedido["pidusr"];

						//datos para control
						$_idemp			=	$_pedido["pidemp"];
						$idCondComercial=	$_pedido["pidcondcomercial"];
						//*****************//

						$_fecha_pedido	=	substr($_pedido['pfechapedido'], 0, 10);				
						$_nombreusr		= 	DataManager::getUsuario('unombre', $_idusuario); 	
						$_idcli			= 	$_pedido["pidcliente"];  
						$_nropedido		=	$_pedido["pidpedido"]; 
						$_idlab			=	$_pedido["pidlab"];  
						$_idart			=	$_pedido['pidart'];
						$_nombreart		=	DataManager::getArticulo('artnombre', $_idart, $_idemp, $_idlab);
						$_cantidad		=	$_pedido['pcantidad'];							
						$_precio		=	str_replace('EUR','', money_format('%.3n', $_pedido['pprecio']));
						//$_precio		=	$_pedido['pprecio'];
						
						$_b1			=	$_pedido['pbonif1'];
						$_b2			=	$_pedido['pbonif2'];
						$_desc1			=	$_pedido['pdesc1'];
						$_desc2			=	$_pedido['pdesc2'];
						$_desc3			=	'';
						$_condpago		=	$_pedido["pidcondpago"];
						$_ordencompra	= 	($_pedido["pordencompra"] == 0)	?	''	:	$_pedido["pordencompra"];
						$_observacion	= 	$_pedido["pobservacion"];
						
						$_b1	=	($_b1 == 0)		?	''	:	$_b1;
						$_b2	=	($_b2 == 0)		?	''	:	$_b2;
						$_desc1	=	($_desc1 == 0)	?	''	:	$_desc1;
						$_desc2	=	($_desc2 == 0)	?	''	:	$_desc2;
						?>

						<TR>
							<TD><?php echo $_fecha_pedido; ?></TD><TD><?php echo $_nombreusr; ?></TD><TD><?php echo $_idcli; ?></TD><TD><?php echo $_nropedido; ?><TD><?php echo $_idlab; ?></TD><TD><?php echo $_idart; ?></TD><TD><?php echo $_cantidad; ?></TD><TD><?php echo $_precio; ?></TD><TD><?php echo $_b1; ?></TD><TD><?php echo $_b2; ?></TD><TD><?php echo $_desc1; ?></TD><TD><?php echo $_desc2; ?></TD><TD><?php echo $_desc3; ?></TD><TD><?php echo $_condpago; ?></TD><TD><?php echo $_ordencompra; ?></TD><TD><?php echo $_observacion; ?></TD>
						</TR>  <?php
					} ?>
				</table>
				<?php                                                        
			} //fin if pedido	
		} //fin for	             
	} else { echo "No se encuentran EMPRESAS ACTIVAS. Gracias."; } ?>
</body>
</html>