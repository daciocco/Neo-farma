<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"  && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }

$_nrorel 	= 	(isset($_POST['nrorel']))	?	$_POST['nrorel'] : NULL;
$_origenid	= 	(isset($_POST['origenid']))	?	$_POST['origenid'] : NULL;
$_origen	= 	(isset($_POST['origen']))	?	$_POST['origen'] : NULL;

$_relevamiento	= DataManager::getRelevamiento($_nrorel, 1); 
if (count($_relevamiento)) {
	foreach ($_relevamiento as $k => $_rel) { 
		$_reltiporesp	= 	$_rel["reltiporesp"];
		$_relpregunta	= 	$_rel["relpregunta"];
		$_reladmitenulo	= 	$_rel["reladmitenulo"];
		
		$_resid[$k]		=	(isset($_POST["resid".$k]))	?	$_POST["resid".$k] : NULL;	
		
		switch ($_reltiporesp){
			case 'sino': 
						$_respuesta[$k]	=	(isset($_POST["sino".$k]))	?	$_POST["sino".$k] : NULL;
						break;
			case 'cant': 
						$_respuesta[$k]	=	(isset($_POST["cant".$k]))	?	$_POST["cant".$k] : NULL;
						if (!empty($_respuesta[$k]) && !is_numeric($_respuesta[$k])) {
							echo "Responda a la pregunta ".($k+1)." correctamente. </br>".$_relpregunta; exit;
						}
						break;
			case 'abierto': 
						$_respuesta[$k]	=	(isset($_POST["respuesta".$k]))	?	$_POST["respuesta".$k] : NULL;
						if ($k == 2 || $k == 4 || $k == 6 || $k == 8){
							if($_POST["sino".($k-1)] == 1){
								if (empty($_respuesta[$k])) {
									echo "Debe indicar: ".$_relpregunta; exit;
								}	
							}
						}
						break;
			default:	echo "Error en el tipo de respuesta"; exit;
						break;
		} 
	}
} else {
	echo "Error al intenta grabar los datos. Consulte con el administrador de la web."; exit;
}

if(empty($_origenid) || empty($_origen)){
	echo "Error al intenta grabar los datos. Consulte con el administrador de la web."; exit;
}

//**************************//
//	Acciones Guardar	//
//**************************//
if (count($_relevamiento)) {
	foreach ($_relevamiento as $k => $_rel) {
		$_resobject	= ($_resid[$k]) ? DataManager::newObjectOfClass('TRespuesta', $_resid[$k]) : DataManager::newObjectOfClass('TRespuesta');			
		$_relid 		= 	$_rel["relid"];
		
		$_resobject->__set('IDRelevamiento'	, $_relid);
		$_resobject->__set('Respuesta'		, $_respuesta[$k]);
		$_resobject->__set('Origen'			, $_origen);
		$_resobject->__set('IDOrigen'		, $_origenid);
		$_resobject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
		$_resobject->__set('LastUpdate'		, date("Y-m-d"));		
		if ($_resid[$k]) {
			//Modifica Cliente			
			$ID = DataManager::updateSimpleObject($_resobject);
		} else {
			$_resobject->__set('ID'			, $_resobject->__newID());
			$_resobject->__set('Activa'		, 1);
			$ID = DataManager::insertSimpleObject($_resobject);
		}
	}
	
	//Registra el movimiento del usuario//
	$movimiento	=	'REL_RELEVADO_NROREL_'.$_nrorel;
	dac_registrarMovimiento($movimiento, "UPDATE", "TPedido", $_nrorel);
	
	echo "1"; exit;
}

echo "Error al intenta grabar los datos. Consulte con el administrador de la web."; exit;

?>