<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
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
			<td scope="col" width="10px">Fecha</td>
			<td scope="col" width="10px">Transfer</td>
			<td scope="col" width="40px">Cliente NEO</td>
            <td scope="col" width="40px">Cliente Drog</td>
            <td scope="col" width="40px">Nombre</td>
            <td scope="col" width="40px">Domicilio</td>
		</tr>
	</thead>			
	<?php
	$_transfers_recientes	= DataManager::getTransfersVendedorXLS(0, $_SESSION["_usrid"]); 
	for( $k=0; $k < count($_transfers_recientes); $k++ ){	
		$_transfer_r 	= 	$_transfers_recientes[$k];
		$fecha 		= 	explode(" ", $_transfer_r["ptfechapedido"]);
						list($ano, $mes, $dia) 	= 	explode("-", $fecha[0]);
		$_fecha 	= 	$dia."-".$mes."-".$ano;			
		$_nropedido	= 	$_transfer_r["ptidpedido"];	
		$_clienteneo= 	empty($_transfer_r["ptidclineo"])	?	""	:	$_transfer_r["ptidclineo"]	;
		$_clientedrog= 	$_transfer_r["ptnroclidrog"];
		$_nombre	= 	$_transfer_r["ptclirs"];
		$_domicilio	= 	$_transfer_r["ptdomicilio"];
		
		echo sprintf("<tr>");
		echo sprintf("<td align=\"left\">%s</td><td align=\"left\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $_fecha, $_nropedido, $_clienteneo, $_clientedrog, $_nombre, $_domicilio);
		echo sprintf("</tr>");
	}
	?>
	</table>	

</body>
</html>                
               
               