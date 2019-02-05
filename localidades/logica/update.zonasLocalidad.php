<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo "LA SESION HA CADUCADO."; exit;
}

$zonasVProhibidas 	= [95, 99, 100, 199, 995];
$radioZonas			= (isset($_POST['radioZonas']))	?	$_POST['radioZonas']	:	NULL;
switch($radioZonas){
	case 'localidad':
		$zonaVDestino	=	(isset($_POST['zonaVSelect']))	?	$_POST['zonaVSelect']	:	NULL;
		$zonaDDestino	=	(isset($_POST['zonaDSelect']))	?	$_POST['zonaDSelect']	:	NULL;		
		if(empty($zonaVDestino) && empty($zonaDDestino)) {
			echo "Seleccione una Zona de Venta o Distribución para actualizar las localidades seleccionadas."; exit;
		}
		//---------------
		//Recorrer LOCALIDADES seleccionadas
		$editSelected	=	(isset($_POST['editSelected2']))? 	$_POST['editSelected2'] : 	NULL;
		if(empty($editSelected)) {
			echo "Seleccione alguna localidad para momdificar."; exit;
		}
		
		$localidades = explode(",", $editSelected);
		if(count($localidades)) {
			for($k=0; $k<count($localidades); $k++){		
				$localObject	= DataManager::newObjectOfClass('TLocalidad', $localidades[$k]);
				$zonaVOriginal 	= $localObject->__get('ZonaVenta');
				$zonaDOriginal 	= $localObject->__get('ZonaEntrega');	
				$movimiento 	= 'CAMBIO';
				
				if($zonaVDestino){					
					//consultar usuario asignado para cuentas
					$usrAssigned = 0;
					$zonas	= DataManager::getZonas();
					foreach($zonas as $q => $zona){
						$zZona	= $zona['zzona'];
						if($zZona == $zonaVDestino){
							$usrAssigned = $zona['zusrassigned'];
						}
					}	
					
					$localObject->__set('ZonaVenta', $zonaVDestino);
					$movimiento .= '_zonaV_'.$zonaVOriginal.'a'.$zonaVDestino;
				}
				if($zonaDDestino){
					$localObject->__set('ZonaEntrega', $zonaDDestino);
					$movimiento .= '_zonaD_'.$zonaDOriginal.'a'.$zonaDDestino;					
				}
				
				if($movimiento != 'CAMBIO'){
					//Actualzar Zona de Localidad
					DataManagerHiper::updateSimpleObject($localObject, $localidades[$k]);
					DataManager::updateSimpleObject($localObject);
					//--------------//	
					//	MOVIMIENTO	//	
					$movTipo	= 'UPDATE';	
					dac_registrarMovimiento($movimiento, $movTipo, "TLocalidad", $localidades[$k]);
				}
				
				
				//BUSCAR EXCEPCIONES
				$zeCtaIdDDBB= [];
				$zonasExpecion	= DataManager::getZonasExcepcion($localidades[$k]);
				if(count($zonasExpecion)) {
					foreach ($zonasExpecion as $j => $ze) {
						$zeCtaIdDDBB[]	= $ze['zeCtaId'];
					}
				}
				
				//Recorrer CUENTAS de c/Localidad
				$cuentas	= DataManager::getCuentaAll('*', 'ctaidloc', $localidades[$k]);
				if (count($cuentas)) {	
					foreach ($cuentas as $q => $cuenta) {
						$ctaId				= $cuenta['ctaid'];
						$ctaZona			= $cuenta['ctazona'];
						$ctaObjectHiper		= DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaId);
						$ctaObject			= DataManager::newObjectOfClass('TCuenta', $ctaId);
						$ctaZonaVOriginal 	= $ctaObject->__get('Zona');
						$ctaZonaDOriginal 	= $ctaObject->__get('ZonaEntrega');						
						$movimiento 		= 'CAMBIO';		
						
						//Si pasa con zona Y no es una zona prohibida Y no es una excepción
						if(!empty($zonaVDestino) && !in_array($ctaZona, $zonasVProhibidas) && !in_array($ctaId, $zeCtaIdDDBB)) {
							$ctaObject->__set('Zona', $zonaVDestino);
							$ctaObject->__set('UsrAssigned', $usrAssigned);
							$ctaObjectHiper->__set('Zona', $zonaVDestino);
							$ctaObjectHiper->__set('UsrAssigned', $usrAssigned);
							$movimiento .= '_zonaV_'.$ctaZonaVOriginal.'a'.$zonaVDestino;
						}
						if($zonaDDestino){
							$ctaObject->__set('ZonaEntrega', $zonaDDestino);
							$ctaObjectHiper->__set('ZonaEntrega', $zonaDDestino);
							$movimiento .= '_zonaD_'.$ctaZonaDOriginal.'a'.$zonaDDestino;
						}
						
						//Si hay CAMBIOS, hacer UPDATE y MOVIMIENTO
						if($movimiento != 'CAMBIO'){
							DataManagerHiper::updateSimpleObject($ctaObjectHiper, $ctaId);
							DataManager::updateSimpleObject($ctaObject);
							
							//	MOVIMIENTO	//
							$movTipo	= 'UPDATE';
							dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);
						}
					}
				}				
			}
		} else {
			echo "Error al seleccionar localidad para momdificar."; exit;
		}
		
		break;
	case 'provincia':
		$zonaVDestino	=	(isset($_POST['zonaVSelect']))	?	$_POST['zonaVSelect']	:	NULL;
		$zonaDDestino	=	(isset($_POST['zonaDSelect']))	?	$_POST['zonaDSelect']	:	NULL;		
		if(empty($zonaVDestino) && empty($zonaDDestino)) {
			echo "Seleccione una Zona de Venta o Distribución para actualizar las localidades seleccionadas."; exit;
		}
		//----------------
		$provincia		=	(isset($_POST['provincia']))	?	$_POST['provincia']		:	NULL;
		if(empty($provincia)){
			echo "Indique una provincia."; exit;
		}
		
		//BUSCAR EXCEPCIONES para NO realizar update
		$zeCtaIdDDBB= [];
		$localidades	=	DataManager::getLocalidades(NULL, $provincia);
		if (count($localidades)) {	
			foreach ($localidades as $k => $loc) {
				$idLoc			= $loc['locidloc'];
				$zonasExpecion	= DataManager::getZonasExcepcion($idLoc);
				if(count($zonasExpecion)){
					foreach ($zonasExpecion as $j => $ze) {
						$zeCtaIdDDBB[]	= $ze['zeCtaId'];
					}
				}				
			}
		}			
		
		$ctaZonasProhibidas	= implode(",", $zonasVProhibidas);
		$condicionExcepcion = "ctazona NOT IN (".$ctaZonasProhibidas.")";		
		$condicionExcepcionHiper = "IdVendedor NOT IN (".$ctaZonasProhibidas.")";		
		if (count($zeCtaIdDDBB)) {
			$ctaIdExcepciones 	= implode(",", $zeCtaIdDDBB);			
			$condicionExcepcion 	.= " AND ctaid NOT IN (".$ctaIdExcepciones.")";			
			$condicionExcepcionHiper .= " AND ctaid NOT IN (".$ctaIdExcepciones.")";
		}		
		
		
		//Realizo update en PROVINCIA Y en CUENTAS
		if($zonaVDestino){	
			//update de zona venta de las localidades de la provincia
			$fieldSet = "loczonavendedor=$zonaVDestino";
			$condition=	"locidprov=$provincia";			
			DataManagerHiper::updateToTable('localidad', $fieldSet, $condition);
			DataManager::updateToTable('localidad', $fieldSet, $condition);
			
			//	MOVIMIENTO	//
			$movimiento = 'CAMBIOLocalidadesDeProvId_'.$provincia.'_a_zonaVendedor_'.$zonaVDestino;	
			$movTipo	= 'UPDATE';
			dac_registrarMovimiento($movimiento, $movTipo, "TLocalidad", 0);			
			
			
			//consultar usuario asignado para cuentas
			$usrAssigned = 0;
			$zonas	= DataManager::getZonas();
			foreach($zonas as $k => $zona){
				$zZona	= $zona['zzona'];
				if($zZona == $zonaVDestino){
					$usrAssigned = $zona['zusrassigned'];
				}
			}			
			
			//update de zona venta de las cuentas de la provincia
			$fieldSet = "IdVendedor=$zonaVDestino, ctausrassigned=$usrAssigned";			
			$condition=	"IdProvincia=$provincia AND ($condicionExcepcionHiper)";
			DataManagerHiper::updateToTable('clientes', $fieldSet, $condition);			
			$fieldSet = "ctazona=$zonaVDestino, ctausrassigned=$usrAssigned";
			$condition=	"ctaidprov=$provincia AND ($condicionExcepcion)";
			DataManager::updateToTable('cuenta', $fieldSet, $condition);
			
			//	MOVIMIENTO	//
			$movimiento = 'CAMBIOLocalidadesDeProvId_'.$provincia.'_a_zonaVendedor_'.$zonaVDestino;
			$movTipo	= 'UPDATE';
			dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", 0);			
		}
		
		if($zonaDDestino){			
			//update de zona entrega de las localidades de la provincia			
			$fieldSet = "loczonaentrega=$zonaDDestino";
			$condition=	"locidprov=$provincia";			
			DataManagerHiper::updateToTable('localidad', $fieldSet, $condition);
			DataManager::updateToTable('localidad', $fieldSet, $condition);
			
			//	MOVIMIENTO	//
			$movimiento = 'CAMBIOLocalidadesDeProvId_'.$provincia.'_a_zonaEntrega_'.$zonaDDestino;	
			$movTipo	= 'UPDATE';
			dac_registrarMovimiento($movimiento, $movTipo, "TLocalidad", 0);
			
			//update de zona entrega de las cuentas de la provincia	
			$fieldSet = "Zona=$zonaVDestino"; //zona Entrega
			$condition=	"IdProvincia=$provincia";
			DataManagerHiper::updateToTable('clientes', $fieldSet, $condition);
			$fieldSet = "ctazonaentrega=$zonaVDestino";
			$condition=	"ctaidprov=$provincia";
			DataManager::updateToTable('cuenta', $fieldSet, $condition);
			
			//	MOVIMIENTO	//
			$movimiento = 'CAMBIOLocalidadesDeProvId_'.$provincia.'_a_zonaEntrega_'.$zonaDDestino;
			$movTipo	= 'UPDATE';
			dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", 0);
		}
		
		break;
	case 'vendedor':
		$zonaVOrigen	=	(isset($_POST['zonaVOrigen']))	?	$_POST['zonaVOrigen']	:	NULL;
		$zonaVDestino	=	(isset($_POST['zonaVDestino']))	?	$_POST['zonaVDestino']	:	NULL;
		if(empty($zonaVOrigen)){ echo "Indique una zona de origen."; exit; }		
		if(empty($zonaVDestino)){ echo "Indique una zona de destino."; exit; }	
		
		//consultar usuario asignado para cuentas
		$usrAssigned = 0;
		$zonas	= DataManager::getZonas();
		foreach($zonas as $k => $zona){
			$zZona	= $zona['zzona'];
			if($zZona == $zonaVDestino){
				$usrAssigned = $zona['zusrassigned'];
			}
		}
				
		//update de zona en LOCALIDADES
		$fieldSet = "loczonavendedor=$zonaVDestino";
		$condition=	"loczonavendedor=$zonaVOrigen";
		DataManagerHiper::updateToTable('localidad', $fieldSet, $condition);
		DataManager::updateToTable('localidad', $fieldSet, $condition);
		
		//	MOVIMIENTO	//
		$movimiento = 'CAMBIOLocalidadesDeProvId_'.$provincia.'_de_zonaVendedor_'.$zonaVOrigen.'_a_'.$zonaVDestino;	
		$movTipo	= 'UPDATE';
		dac_registrarMovimiento($movimiento, $movTipo, "TLocalidad", 0);
		
		//update de zona en CUENTAS
		$fieldSet = "IdVendedor=$zonaVDestino, ctausrassigned=$usrAssigned";			
		$condition=	"IdVendedor=$zonaVOrigen";
		DataManagerHiper::updateToTable('clientes', $fieldSet, $condition);
		$fieldSet = "ctazona=$zonaVDestino, ctausrassigned=$usrAssigned";
		$condition=	"ctazona=$zonaVOrigen";
		DataManager::updateToTable('cuenta', $fieldSet, $condition);		
		
		//	MOVIMIENTO	//
		$movimiento = 'CAMBIO_ZonaVendedor_de_'.$zonaVOrigen.'_a_'.$zonaVDestino;
		$movTipo	= 'UPDATE';
		dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", 0);	
		
		//update de zona y cuentas en EXCEPCIONES
		$zonasExpecion	= DataManager::getZonasExcepcion(NULL, $zonaVOrigen);
		if(count($zonasExpecion) > 0){
			foreach ($zonasExpecion as $j => $ze) {
				$zeIdLocDDBB= $ze['zeIdLoc'];
				$zeCtaIdDDBB= $ze['zeCtaId'];				
				
				//Redefinir ZONA en excepcion
				$fieldSet = "IdVendedor=$zonaVDestino";			
				$condition=	"zeCtaId=$zeCtaIdDDBB";
				DataManager::updateToTable('zona_excepcion', $fieldSet, $condition);			
				
				//	MOVIMIENTO	//
				$movimiento = 'CAMBIO_zonaV_'.$zonaVOrigen.'a'.$zonaVDestino.'_Cuenta_'.$zeCtaIdDDBB;
				$movTipo	= 'UPDATE';
				dac_registrarMovimiento($movimiento, $movTipo, "zona_excepcion", $zeCtaIdDDBB);
				
				//Redefinir ZONA en Cuenta excepcion
				$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $zeCtaIdDDBB);
				$ctaObjectHiper->__set('Zona', $zonaVDestino);
				$ctaObject		= DataManager::newObjectOfClass('TCuenta', $zeCtaIdDDBB);
				$ctaObject->__set('Zona', $zonaVDestino);				
				DataManagerHiper::updateSimpleObject($ctaObjectHiper, $zeCtaIdDDBB);
				DataManager::updateSimpleObject($ctaObject);	
				
				//	MOVIMIENTO	//
				dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $zeCtaIdDDBB);
			}
		}
		
		break;
	case 'distribucion':
		$zonaDOrigen	=	(isset($_POST['zonaDOrigen']))	?	$_POST['zonaDOrigen']	:	NULL;
		$zonaDDestino	=	(isset($_POST['zonaDDestino']))	?	$_POST['zonaDDestino']	:	NULL;		
		if(empty($zonaDOrigen)){ echo "Indique una zona de origen."; exit; }		
		if(empty($zonaDDestino)){ echo "Indique una zona de destino."; exit; }			
		
		//update de zona distribucion de localidades
		$fieldSet = "loczonaentrega=$zonaDDestino";
		$condition=	"loczonaentrega=$zonaDOrigen";			
		DataManagerHiper::updateToTable('localidad', $fieldSet, $condition);
		DataManager::updateToTable('localidad', $fieldSet, $condition);

		//	MOVIMIENTO	//
		$movimiento = 'CAMBIO_ZonaEntrega_'.$zonaDOrigen.'_a_'.$zonaDDestino;	
		$movTipo	= 'UPDATE';
		dac_registrarMovimiento($movimiento, $movTipo, "TLocalidad", 0);		
		
		//update de zona entrega de las cuentas de la provincia	
		$fieldSet = "Zona=$zonaVDestino"; //zona Entrega
		$condition=	"Zona=$zonaDOrigen";
		DataManagerHiper::updateToTable('clientes', $fieldSet, $condition);
		$fieldSet = "ctazonaentrega=$zonaVDestino";
		$condition=	"ctazonaentrega=$zonaDOrigen";
		DataManager::updateToTable('cuenta', $fieldSet, $condition);

		//	MOVIMIENTO	//
		$movimiento = 'CAMBIO_ZonaEntrega_'.$zonaDOrigen.'_a_'.$zonaDDestino;
		$movTipo	= 'UPDATE';
		dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", 0);		
		
		break;
	default:
		echo "Debe seleccionar una opción para modificar las zonas"; exit;
		break;
}

echo "1"; exit; ?>