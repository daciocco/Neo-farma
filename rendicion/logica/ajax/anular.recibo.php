<?php
session_start(); 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
 }

$_rendid		= 	(isset($_REQUEST['rendid']))	? $_REQUEST['rendid']	: NULL;
$_nro_rendicion = 	(isset($_REQUEST['nro_rend']))	? $_REQUEST['nro_rend']	: NULL;
$_nro_tal		= 	(isset($_REQUEST['nro_tal']))	? $_REQUEST['nro_tal']	: NULL;
$_nro_rec		= 	(isset($_REQUEST['nro_rec']))	? $_REQUEST['nro_rec']	: NULL;
$_nva_rendicion =	0;
	
if((empty($_nro_rendicion) or !is_numeric($_nro_rendicion))){
	echo "No existe una rendición. No se pueden cargar recibos anulados."; exit;
}

//Controla que EXISTA (para saber si insertar o modificar) la rendición Y QUE NO esté ENVIADA.
$_rendicion		=	DataManager::getRendicion($_SESSION["_usrid"], $_nro_rendicion);
if (count($_rendicion) > 0) {
	foreach ($_rendicion as $k => $_rend){
		$_rend		=	$_rendicion[$k];
		$_rendActiva=	$_rend['rendactiva'];
	}	
	
	if($_rendActiva != 1){
		echo "No se pueden enviar los datos con el Nro. de Rendición ".$_nro_rendicion." ya que fue enviado anteriormente."; exit;
	} 														
} else {
	//Si no existen datos, es que el nro de rendición no existe, por lo tanto es nueva
	$_nva_rendicion = 1; 
}

if((empty($_nro_tal) or !is_numeric($_nro_tal)) ){
	echo "Debe completar el número de talonario correctamente.\n"; exit;
}

if((empty($_nro_rec) or !is_numeric($_nro_rec)) ){
	echo "Debe completar el número de recibo correctamente.\n"; exit;
}

//BUSCA el talonario en talonario_idusr
$_talonario		=	DataManager::getBuscarTalonario($_nro_tal);
if (count($_talonario) > 0) {	
	foreach ($_talonario as $k => $_tal) {
		$_talnro	=	$_tal["nrotalonario"];	
		$_talidusr	=	$_tal["idusr"];	
				
		if ($_talidusr != $_SESSION["_usrid"]){		
			echo "El número de talonario corresponde a otro Vendedor"; exit;
		} else {
			//Busca el número MAX de recibo
			$max_rec		=	DataManager::getMaxRecibo($_nro_tal);
			$nro_siguiente 	= 	$max_rec + 1;	
			
			if (($_nro_rec <= $max_rec || $_nro_rec > $nro_siguiente) && $max_rec != 0){		
				echo "El número de recibo [ $_nro_rec ] no es válido. Debe ser $nro_siguiente.";
			} else { //ES VALIDO.
				if($_nva_rendicion == 1){ //Se cre la Rendición
					//Inserto datos en tabla rendicion
					$_rendicionobject	=	DataManager::newObjectOfClass('TRendicion');		
					$_rendicionobject->__set('Numero'		, $_nro_rendicion); 	
					$_rendicionobject->__set('Fecha'		, date('Y-m-d'));					
					$_rendicionobject->__set('IdUsr'		, $_SESSION["_usrid"]);
					$_rendicionobject->__set('NombreUsr'	, $_SESSION["_usrname"]);
					$_rendicionobject->__set('Activa'		, '1');
					$_rendicionobject->__set('Retencion'	, '0.00');
					$_rendicionobject->__set('Deposito'		, '0.00');
					$_rendicionobject->__set('Envio'		, date('2001-01-01'));
					$_rendicionobject->__set('ID', 			$_rendicionobject->__newID());
					$IDRendicion	= DataManager::insertSimpleObject($_rendicionobject);	
				} else {
					$IDRendicion	= $_rendid;
				}		
				
				//Inserto datos recibos.
				$_recobject		=	DataManager::newObjectOfClass('TRecibos');
				$_recobject->__set('ID', 			$_recobject->__newID());
				$_recobject->__set('Numero', 		$_nro_rec); 						
				$_recobject->__set('Talonario', 	$_nro_tal);
				$_recobject->__set('Observacion', 	'ANULADO');
				$_recobject->__set('Diferencia', 	'0.00');
				$IDRecibo		=	DataManager::insertSimpleObject($_recobject); 

				//Inserto datos en tabla rend_rec.
				$_IDRend_Rec	=	DataManager::insertToTable('rend_rec', 'rendid, recid', "'".$IDRendicion."','".$IDRecibo."'");

				//Finalizo OK
				echo "1"; exit;
			}
		}
	}                            
} else {
	echo "No existe el número de talonario, antes debe crearlo."; exit;
}
?>
