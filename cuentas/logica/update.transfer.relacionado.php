<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$ctaId 				= 	(isset($_POST['ctaId']))			? 	$_POST['ctaId']				: 	NULL;
$tipo				= 	(isset($_POST['tipo']))				? 	$_POST['tipo']				: 	NULL;
//Arrays
$arrayCtaIdDrog		= 	(isset($_POST['cuentaIdDrog']))		? 	$_POST['cuentaIdDrog'] 		: 	NULL;
$arrayCtaCliente	= 	(isset($_POST['cuentaIdTransfer']))	? 	$_POST['cuentaIdTransfer'] 	: 	NULL;

if(empty($tipo) || $tipo == "PS" || $tipo == "O"){
	echo 'El tipo de cuenta no admite cuentas transfers relacionadas.'; exit;
}

if(!empty($arrayCtaIdDrog)){	
	if(count($arrayCtaIdDrog)){
		for($i = 0; $i < count($arrayCtaIdDrog); $i++){
			if (empty($arrayCtaCliente[$i])){
				echo "Indique cliente transfer para la cuenta ".$i; exit;
			}
		}
	}
	//Controla Droguerías duplicadas
	if(count($arrayCtaIdDrog) != count(array_unique($arrayCtaIdDrog))){
		echo "Hay droguer&iacute;as duplicadas."; exit;
	}	
} 

//-------------------//
//	GUARDAR CAMBIOS  //
if ($ctaId) {
	//--------------------------------//	
	// UPDATE TRANSFERS RELACIONADAS //
	if(!empty($arrayCtaIdDrog)){
		if(count($arrayCtaIdDrog)){
			$cuentasRelacionadas = DataManager::getCuentasRelacionadas($ctaId); //$empresa, $idCuenta
			if (count($cuentasRelacionadas)) {
				foreach ($cuentasRelacionadas as $k => $ctaRel) {
					$ctaRel 	=	$cuentasRelacionadas[$k];
					$relId		=	$ctaRel['ctarelid'];
					$relIdDrog	= 	$ctaRel['ctarelidcuentadrog'];	
					
					//Creo Array de Droguerias Relacionadas de BBDD
					$arrayDrogDDBB[] = $relIdDrog;

					if (in_array($relIdDrog, $arrayCtaIdDrog)) {
						//UPDATE 
						$key	=	array_search($relIdDrog, $arrayCtaIdDrog); //Indice donde se encuentra la cuenta

						$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada', $relId);
						$ctaRelObject->__set('Transfer'		, $arrayCtaCliente[$key]); //nro de cliente para la droguería					
						DataManager::updateSimpleObject($ctaRelObject);

					} else {
						//DELETE de cuentas relacionadas
						$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada', $relId);
						$ctaRelObject->__set('ID',	$relId);
						DataManager::deleteSimpleObject($ctaRelObject);
					}			
				}

				foreach ($arrayCtaIdDrog as $k => $ctaIdDrog) {
					if (!in_array($ctaIdDrog, $arrayDrogDDBB)) {
						//INSERT				
						$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada');	
						$ctaRelObject->__set('Cuenta'		, $ctaId);
						$ctaRelObject->__set('Drogueria'	, $ctaIdDrog);
						$ctaRelObject->__set('Transfer'		, $arrayCtaCliente[$k]);
						$ctaRelObject->__set('ID'			, $ctaRelObject->__newID());
						$IDRelacion	= DataManager::insertSimpleObject($ctaRelObject);	
					}	
				}
			} else { //INSERT - Si no hay cuentas relacionadas, las crea
				foreach ($arrayCtaIdDrog as $k => $ctaIdDrog) {			
					$ctaRelObject	= DataManager::newObjectOfClass('TCuentaRelacionada');	
					$ctaRelObject->__set('Cuenta'		, $ctaId);
					$ctaRelObject->__set('Drogueria'	, $ctaIdDrog); //nro iddrogueria
					$ctaRelObject->__set('Transfer'		, $arrayCtaCliente[$k]); //nro cliente transfer
					$ctaRelObject->__set('ID'			, $ctaRelObject->__newID());
					$IDRelacion = DataManager::insertSimpleObject($ctaRelObject);	
				}
			}
		}
	} else {
		$cuentasRelacionadas = DataManager::getCuentasRelacionadas($ctaId); //$empresa, $idCuenta
		if (count($cuentasRelacionadas)) {
			//DELETE de cuentas relacionadas
			foreach ($cuentasRelacionadas as $k => $ctaRel) {
				$ctaRel 	=	$cuentasRelacionadas[$k];
				$relId		=	$ctaRel['ctarelid'];

				$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada', $relId);
				$ctaRelObject->__set('ID',	$relId);
				DataManager::deleteSimpleObject($ctaRelObject);
			}
		}
	}
	
	//*********************//	
	//	Registro MOVIMIENTO	//
	//**********************//
	$movimiento = 'UPDATE_TRANSFERS_RELACIONADOS';	
	$movTipo	= 'UPDATE';	
	dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);
	
	echo '1'; exit;
	
} else {
	echo "La cuenta aún no fue creada."; exit;
}


?>