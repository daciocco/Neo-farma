<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="V"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
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

 //*************************************************
 //Duplicado de registros
 //************************************************* 
 $fechaInicio	=	new DateTime(dac_invertirFecha($fechaDesde));
 $fechaFin		=	new DateTime(dac_invertirFecha($fechaHasta));
 $fechaFin->modify("+1 day");
 //*************************************************
 //			Exportar datos de php a Excel
 //************************************************* 
 header("Content-Type: application/vnd.ms-excel");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("content-disposition: attachment;filename=Llamadas_$fechaDesde-al-$fechaHasta.xls");
 ?>
 <HTML LANG="es"><TITLE>::. Exportacion de Datos .::</TITLE></head>
 <body>
	<table border=1 align="center" cellpadding=1 cellspacing=1 >
		<tr><td colspan="10" align="center" style="font-weight:bold; border:none;"> Registro de Llamadas </td></tr>
		<tr><td colspan="10" style="font-weight:bold; border:none;"> DESDE: <?php echo $fechaDesde; ?></td></tr>
		<tr><td colspan="10" style="font-weight:bold; border:none;"> HASTA: <?php echo $fechaHasta; ?></td></tr>
		<tr>
        	<td>Id</td><td>IdOrigen</td><td>Fecha Llamada </td><td>Tipo</td><td>Resultado</td><td>Observaci&oacute;n</td><td>Usuario</td><td>Asignado</td><td>Cuenta</td><td>Nombre</td>
        </tr> <?php
		
		$llamadas	= DataManager::getLlamadas(NULL, NULL, NULL, "TCuenta", 0, $fechaInicio->format("Y-m-d 00:00:00"), $fechaFin->format("Y-m-d 23:59:59")); 
		if (count($llamadas)) {
			foreach ($llamadas as $k => $llam) {
				$id			=	$llam["llamid"];
				$origenId	=	$llam["llamorigenid"];
				$fecha		=	new DateTime($llam["llamfecha"]);
				$tipo		=	$llam["llamtiporesultado"];
				$resultado	=	$llam["llamresultado"];
				$observacion=	$llam["llamobservacion"];
				$usr		= 	DataManager::getUsuario('unombre', $llam["llamusrupdate"]);
				
				$usrAsignado=	DataManager::getCuenta('ctausrassigned', 'ctaid', $origenId, 1);
				if($usrAsignado){
					$nombreAsignado	=	DataManager::getUsuario('unombre', $usrAsignado);
					$idCuenta		=	DataManager::getCuenta('ctaidcuenta', 'ctaid', $origenId, 1);
					$nombreCuenta	=	DataManager::getCuenta('ctanombre', 'ctaid', $origenId, 1);	
				} else {
					$usrAsignado=	"";	
					$nombreAsignado	=	"";
					$idCuenta		=	"";
					$nombreCuenta	=	"";	
				}
				
				
				if($fecha->format("Y-m-d") >= $fechaInicio->format("Y-m-d") && $fecha->format("Y-m-d") < $fechaFin->format("Y-m-d")){ //&&  $fecha < $fechaFin?>
                    <tr>
                        <td><?php echo $id;?></td>
                        <td><?php echo $origenId; ?></td>
                        <td><?php echo $fecha->format("Y-m-d H:i:s"); ?></td>
                        <td><?php echo $tipo;?></td>						
                        <td><?php echo $resultado; ?></td>
                        <td width="150px"><?php echo $observacion; ?></td>
                        <td><?php echo $usr; ?></td>
                        <td><?php echo $nombreAsignado; ?></td>
                        <td><?php echo $idCuenta; ?></td>
                        <td><?php echo $nombreCuenta; ?></td>
                    </tr> <?php
				}	
			}
		} ?>
	</table> 
 </body>
 </html>