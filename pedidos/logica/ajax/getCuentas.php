<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}
$empresa		= (isset($_POST['empresa']))		? $_POST['empresa']			: NULL;
$condidcuentas	= (isset($_POST['condidcuentas']))	? $_POST['condidcuentas']	: NULL;
$listaCondicion	= (isset($_POST['listaCondicion']))	? $_POST['listaCondicion']	: NULL;

$arrayCondIdCtas = array();
if($condidcuentas){ $arrayCondIdCtas = explode(",", $condidcuentas); }

if (!empty($_SESSION["_usrzonas"]))	{
	$cuentas	= DataManager::getCuentas( 0, 0, $empresa, 1, '"C","CT"', $_SESSION["_usrzonas"]);
	if (count($cuentas)) {
		echo '<table id="tblTablaCta" style="table-layout:fixed;">';
		echo '<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Localidad</th></tr></thead>';
		echo '<tbody>';
			
		foreach ($cuentas as $k => $cta) {
			$ctaId		= $cta["ctaid"];
			$idCuenta 	= $cta["ctaidcuenta"];
			$condPago 	= $cta["ctacondpago"];
			$nombre	 	= $cta["ctanombre"];
			$idLoc		= $cta["ctaidloc"];
			
			$listaCuenta= $cta["ctalista"]; 
			$listaNombre= DataManager::getLista('NombreLT', 'IdLista', $listaCuenta);
			
			$localidad	= 0;
			$localidad	= (empty($idLoc))	? DataManager::getCuenta('ctalocalidad', 'ctaid', $ctaId, $empresa) :	DataManager::getLocalidad('locnombre', $idLoc);
			$direccion	= $cta["ctadireccion"].' '.$cta["ctadirnro"];	
			$observacion= $cta["ctaobservacion"]; 
			((($k % 2) == 0)? $clase="par" : $clase="impar");
			
			if( $idCuenta != 0 ) {
				if(count($arrayCondIdCtas) > 0) {
					if(in_array($ctaId, $arrayCondIdCtas)){
						echo "<tr class=".$clase." onclick=\"javascript:dac_cargarDatosCuenta('$idCuenta', '$nombre', '$direccion', '$observacion', '$condPago', '$listaCuenta', '$listaNombre', '$listaCondicion')\" title='".$direccion."' style=\"cursor:pointer\"><td>".$idCuenta."</td><td>".$nombre."</td><td>".$localidad."</td></tr>";
					}
				} else {
					echo "<tr class=".$clase." onclick=\"javascript:dac_cargarDatosCuenta('$idCuenta', '$nombre', '$direccion', '$observacion', '$condPago', '$listaCuenta', '$listaNombre', '$listaCondicion')\" title='".$direccion."' style=\"cursor:pointer\"><td>".$idCuenta."</td><td>".$nombre."</td><td>".$localidad."</td></tr>";
				}	
			}
			
		}
		echo '</tbody></table>';
	}else { 
		echo '<table><tr><td align="center">No hay registros activos.</td></tr></table>';
	}
} else {
	echo '<table><tr><td align="center">Usuario sin zonas registradas.</td></tr></table>';
}  
  
?>