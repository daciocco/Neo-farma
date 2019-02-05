<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	echo '<table border="0" width="100%"><tr><td align="center">SU SESION HA EXPIRADO.</td></tr></table>'; exit;
}

echo	'<table id="tblCuentasTransfer" border="0" align="center">';
echo	'<thead><tr align="left"><th>Id</th><th>Nombre</th><th>Localidad</th></tr></thead>';
echo 	'<tbody>';

$drogueriasTransfer	= DataManager::getDrogueria(1);
if (count($drogueriasTransfer)) {
	foreach ($drogueriasTransfer as $k => $drogTrans) {
		$id			= $drogTrans['drogtid'];
		
		$idEmpCtaDrog= $drogTrans['drogtidemp'];
		$idCtaDrog	= $drogTrans['drogtcliid'];			
		
		$ctaId		= DataManager::getCuenta('ctaid', 'ctaidcuenta', $idCtaDrog, $idEmpCtaDrog);
		$idCuenta	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $ctaId);
		$nombre		= DataManager::getCuenta('ctanombre', 'ctaid', $ctaId);		
		
		$idLoc		= DataManager::getCuenta('ctaidloc', 'ctaid', $ctaId);
		$localidad	= DataManager::getLocalidad('locnombre', $idLoc);		
		
		$clienteTransfer = "";
					
		((($k % 2) == 0)? $clase="par" : $clase="impar");		
		echo "<tr id=cuenta".$id." class=".$clase."  onclick=\"javascript:dac_cargarCuentaTransferRelacionada2('$id', '$ctaId', '$idCuenta', '$nombre', '$clienteTransfer' )\" style=\"cursor:pointer;\"><td>".$idCuenta."</td><td>".$nombre."</td><td>".$localidad."</td></tr>";
	}
} else { 
	echo "<tr><td colspan=\"3\"></br>No hay cuentas activas en la empresa seleccionada</td></tr>";
}
echo 	'</tbody></table>';
exit;
?>