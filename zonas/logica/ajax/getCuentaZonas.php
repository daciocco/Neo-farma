<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//*************************************************
$empresa= 	(isset($_POST['empresa']))	? 	$_POST['empresa']	:	NULL;
$activas= 	(isset($_POST['activas']))	? 	$_POST['activas']	:	NULL;
$tipos	= 	(isset($_POST['tipo']))		?	$_POST['tipo']		:	NULL; //"'C', 'T'";
$zonas	= 	(isset($_POST['zonas']))	?	$_POST['zonas']		:	NULL;
$geoloc	= 	(isset($_POST['geoloc']))	?	$_POST['geoloc']	:	NULL;
//$_SESSION["_usrzonas"]
//*************************************************
$zona 	= 	(count($zonas)>0) ? implode(',', $zonas) : NULL;
$tipo 	= 	(count($tipos)>0) ? implode(', ', $tipos) : NULL;
$activas= 	($activas!= 2) ? $activas : NULL;

$cuentas= DataManager::getCuentas(0, 0, $empresa, $activas, $tipo, $zona);
if (count($cuentas)) {	
	
	//registro de transfers de ultimo año
	$dateFrom	= new DateTime('now');
	$dateFrom->modify("-1 year");
	$dateTo 	= new DateTime('now');
	$transfers	= DataManager::getTransfersPedido(0, $dateFrom->format("Y-m-d"), $dateTo->format("Y-m-d"), NULL, NULL, NULL, NULL, NULL);
		
	foreach ($cuentas as $k => $cuenta) {
		$tipo		= $cuenta['ctatipo'];
		$zona		= $cuenta['ctazona'];
		if($tipo <> 'O') {		
			$latitud	= $cuenta['ctalatitud'];
			$longitud	= $cuenta['ctalongitud'];			
			$id			= $cuenta['ctaid'];
			$nombre		= $cuenta['ctanombre'];
			$cuentaId	= $cuenta['ctaidcuenta'];
			$activa		= $cuenta['ctaactiva'];
			$estado		= ($activa) ? 'Activa' : 'Inactiva' ;
			
			$provincia	= DataManager::getProvincia('provnombre', $cuenta['ctaidprov']);
			$localidad	= DataManager::getLocalidad('locnombre', $cuenta['ctaidloc']);
			
			$direccion 	= $cuenta['ctadireccion'];			
			$direccion	= ($cuenta['ctadirnro']) ? $direccion.' '.$cuenta['ctadirnro'] : $direccion;			
			$direccion	= str_replace('  ', ' ', $direccion);
			
			$direccion2 = ($provincia) ? $direccion.", ".$provincia : $direccion;
			$direccion2 = ($localidad) ? $direccion2.", ".$localidad : $direccion2;
			
			switch($tipo){
				case 'C':
				case 'CT':
					if($activa){
						$image = 'marcadorGreen.png';
						$color = "#3dc349"; //verde
					} else {
						//Si cuenta es inactiva, verificar pedidos transfers del último año.				
						$indice = '';
						$indice = array_search($id, array_column($transfers, 'ptidclineo'));
						if($indice){
							//inactiva Con pedido transfer		
							$image = 'marcadorGY.png';
							$color = "#fcfcfc"; //oscuro							
						} else {
							//inactiva
							$image = 'marcadorGreenHover.png';
							$color = "#328336"; //oscuro
						}
					}	
					break;
				case 'T':
					if($activa){
						$image = 'marcadorYellow.png';
						$color = "#d69a2c"; //amarillo
					} else {
						$image = 'marcadorYellowHover.png';
						$color = "#855f1b"; //oscuro
					}
					break;
				case 'TT':
					if($activa){
						$image = 'marcadorOrange.png';
						$color = "#efbe9a"; //orange
					} else {
						$image = 'marcadorOrangeHover.png';
						$color = "#E49044"; //dark orange
					}
					break;
				case 'PS':
					$image = 'marcadorRed.png';
					$color = "#ba140c"; //rojo
					break;
				default:
					$image = 'marcador.png';
					$color = '#fcfcfc';
					break;
			}
			
			if($geoloc == 1 && (empty($longitud) || empty($latitud))){ //&& $tipo == 'PS'
				//calcula lat y long en caso de tener dirección mal cargada
				if(!empty($direccion)){					
					$latLog 	= dac_getCoordinates($direccion2);
					$latitud 	= $latLog[0];
					$longitud 	= $latLog[1];
					
					$datos[] = array(
						"datos"		=> "<strong>Cuenta: </strong>".$cuentaId."<strong> Tipo: ".$tipo."</strong></br><strong> Estado: </strong>".$estado."</br><strong>Nombre: </strong>".$nombre."</br><strong>Provincia: </strong>".$provincia."</br><strong>Localidad: </strong>".$localidad."</br><strong>Direccion: </strong>".$direccion,
						"latitud"	=> $latitud,
						"longitud" 	=> $longitud,
						"cuenta" 	=> $nombre,
						"color"		=> $color,
						"imagen"	=> $image,
						"id"		=> $id,
						"direccion"	=> $direccion2,
						"idcuenta"	=> $cuentaId,
					);
				}
			} else {
				$datos[] = array(
					"datos"		=> "<strong>Cuenta: </strong>".$cuentaId."<strong> Tipo: ".$tipo."</strong></br><strong> Estado: </strong>".$estado."</br><strong>Nombre: </strong>".$nombre."</br><strong>Provincia: </strong>".$provincia."</br><strong>Localidad: </strong>".$localidad."</br><strong>Direccion: </strong>".$direccion,
					"latitud"	=> $latitud,
					"longitud" 	=> $longitud,
					"cuenta" 	=> $nombre,
					"color"		=> $color,
					"imagen"	=> $image,
					"id"		=> $id,
					"direccion"	=> $direccion2,
					"idcuenta"	=> $cuentaId,
				);	
			}
		}
	}
	echo json_encode($datos); exit;
} 

?>