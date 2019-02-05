<?php 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );/*
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}*/

header("Content-Type: application/vnd.ms-excel");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition:attachment;filename=Localidades-".date('d-m-y').".xls");
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<style>
		.datatab{
			font-size:14px;
		}
		tr th {
			font-weight:bold;
			height: 20px;	
		}
		td.par {
			background-color: #fff;
			height: 20px;
		}
		td.impar {
			background-color: #cfcfcf;
			height: 20px;
			font-weight:bold;
		}
	</style>
</head>
<body>
	<table border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed;">
		<thead>
			<tr>
				<TD style="font-size:16px; color:#117db6; border:1px solid #666">Provincia</TD>
				<TD style="font-size:16px; color:#117db6; border:1px solid #666">Localidad</TD>
				<TD style="font-size:16px; color:#117db6; border:1px solid #666">C&oacute;digo Postal</TD>
				<TD style="font-size:16px; color:#117db6; border:1px solid #666">Zona Vendedor</TD>
				<TD style="font-size:16px; color:#117db6; border:1px solid #666; word-wrap:break-word" >Excepciones</TD>
				<TD style="font-size:16px; color:#117db6; border:1px solid #666">Zona Entrega</TD>				
			</tr>
		</thead>			
		<?php	
		$localidades= DataManager::getLocalidades();
		if($localidades){
			foreach ($localidades as $k => $loc) {						
				$idProv		= 	$loc["locidprov"];	
				$provincia	=	DataManager::getProvincia('provnombre', $idProv);
				$idLoc		= 	$loc["locidloc"];	
				$localidad	= 	$loc["locnombre"];	
				$cp			= 	$loc["loccodpostal"];	
				$zonaV		= 	$loc["loczonavendedor"];	
				$zonaD		= 	$loc["loczonaentrega"];
				$zonaDNombre=	DataManager::getZonaDistribucion('NombreZN', 'IdZona', $zonaD);
				$zonaDistribucion = $zonaD."|".$zonaDNombre;
				
				$excepciones = '';
				$zonasExpecion	= DataManager::getZonasExcepcion($idLoc);
				if(count($zonasExpecion)){
					foreach ($zonasExpecion as $k => $ze) {
						$zeCtaIdDDBB= $ze['zeCtaId'];
						$zeZonaDDBB	= $ze['zeZona'];						
						$idCuenta	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $zeCtaIdDDBB);
						$nombre		= DataManager::getCuenta('ctanombre', 'ctaid', $zeCtaIdDDBB);
						$excepciones .= "$zeZonaDDBB|$idCuenta|$nombre <br>";
					}
				}
				
				echo sprintf("<tr align=\"left\">");
				if(($k % 2) == 0){					
					echo sprintf("<td class='par'>%s</td><td class='par'>%s</td><td class='par'>%s</td class='par'><td class='par'>%s</td><td class='par'>%s</td><td class='par'>%s</td>", $provincia, $localidad, $cp, $zonaV, $excepciones, $zonaDistribucion);					
				} else {
					echo sprintf("<td class='impar'>%s</td><td class='impar'>%s</td><td class='impar'>%s</td class='impar'><td class='impar'>%s</td><td class='impar'>%s</td><td class='impar'>%s</td>", $provincia, $localidad, $cp, $zonaV, $excepciones, $zonaDistribucion);	
				}
				echo sprintf("</tr>");
				
			}
		} ?>
	</table>	

</body>
</html>                
               
               