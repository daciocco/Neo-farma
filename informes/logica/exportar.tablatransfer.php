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
	echo "Debe completar las fechas de exportaci&oacute;n"; exit;
}

 $fechaInicio		=	new DateTime(dac_invertirFecha($fechaDesde));
 $fechaFin			=	new DateTime(dac_invertirFecha($fechaHasta));
 $fechaFin->modify("+1 day");
 //*************************************************
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=TablaTransfers-".date('d-m-y').".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos (Tabla Transfers) .::</TITLE>
<head></head>
<body>
	<table border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
        	<td scope="col" >ptid</td>
            <td scope="col" >ptidpedido</td>            
            <td scope="col" >ptidvendedor</td>
			<td scope="col" >ptparaidusr</td>            
            <td scope="col" >ptiddrogueria</td>            
            <td scope="col" >ptnroclidrog</td>
            <td scope="col" >ptidclineo</td>
            <td scope="col" >ptclirs</td>            
            <td scope="col" >ptclicuit</td>
            <td scope="col" >ptdomicilio</td>
            <td scope="col" >ptcontacto</td>
            <td scope="col" >ptidart</td>                        
            <td scope="col" >ptunidades</td>  
            <td scope="col" >ptprecio</td>
            <td scope="col" >ptdescuento</td>          
            <td scope="col" >ptcondpago</td>
            <td scope="col" >ptfechapedido</td>
            <td scope="col" >ptidadmin</td>
            <td scope="col" >ptnombreadmin</td>
            <td scope="col" >ptfechaexp</td>
            <td scope="col" >ptliquidado</td>
            <td scope="col" >ptactivo</td>
		</tr>
	</thead>			
	<?php
	$transfers	= DataManager::getTransfersPedido(0, $fechaInicio->format("Y-m-d"), $fechaFin->format("Y-m-d"));
		//DataManager::getTransfers2(0, $fechaInicio->format("Y-m-d"), $fechaFin->format("Y-m-d")); 
	if($transfers){
		foreach ($transfers as $k => $transfer) {
			$ptid 			= 	$transfer["ptid"];
			$ptidpedido 	= 	$transfer["ptidpedido"];
			$ptidvendedor 	= 	$transfer["ptidvendedor"];
			$ptparaidusr 	= 	$transfer["ptparaidusr"];
			$ptiddrogueria 	= 	$transfer["ptiddrogueria"];
			$ptnroclidrog 	= 	$transfer["ptnroclidrog"];
			$ptidclineo		=	$transfer["ptidclineo"];
			$ptclirs 		= 	$transfer["ptclirs"];
			$ptclicuit 		= 	$transfer["ptclicuit"];
			$ptdomicilio 	= 	$transfer["ptdomicilio"];
			$ptcontacto 	= 	$transfer["ptcontacto"];
			$ptidart 		= 	$transfer["ptidart"];
			$ptunidades 	= 	$transfer["ptunidades"];
			$ptprecio 		= 	$transfer["ptprecio"];
			$ptdescuento 	= 	$transfer["ptdescuento"];
			$ptcondpago 	= 	$transfer["ptcondpago"];
			$ptfechapedido	= 	$transfer["ptfechapedido"];
			$ptidadmin		= 	$transfer["ptidadmin"];
			$ptnombreadmin 	= 	$transfer["ptnombreadmin"];
			$ptfechaexp 	= 	$transfer["ptfechaexp"];
			$ptliquidado 	= 	$transfer["ptliquidado"];
			$ptactivo 		= 	$transfer["ptactivo"];
			
			echo sprintf("<tr align=\"left\">");
			echo sprintf("<td >%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td >%s</td><td >%s</td><td >%s</td><td >%s</td>", $ptid, $ptidpedido, $ptidvendedor, $ptparaidusr, $ptiddrogueria, $ptnroclidrog, $ptidclineo, $ptclirs, $ptclicuit, $ptdomicilio, $ptcontacto, $ptidart, $ptunidades, $ptprecio, $ptdescuento, $ptcondpago, $ptfechapedido, $ptidadmin, $ptnombreadmin, $ptfechaexp, $ptliquidado, $ptactivo);
			echo sprintf("</tr>");
		}
	}
	?>
	</table>	

</body>
</html>                
               
               