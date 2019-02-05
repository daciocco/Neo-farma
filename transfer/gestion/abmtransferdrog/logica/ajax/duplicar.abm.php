<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_mes 		= empty($_POST['mes'])	?	date("m")	:	$_POST['mes'];
$_anio 		= empty($_POST['anio'])	?	date("Y")	:	$_POST['anio'];
$_mes_sig 	= $_POST['mes_sig'];
$_anio_sig	= $_POST['anio_sig'];
$_drogid	= $_POST['drogid'];
$_toAll		= $_POST['toAll'];

if(empty($_mes_sig) || empty($_anio_sig)){echo "Error al cargar el mes o año de destino."; exit;}
if(($_anio == $_anio_sig) && ($_anio_sig == $_mes)){echo "El mes y año deben ser diferentes al ABM a suplicar."; exit;}

if($_toAll == 0){ //duplico solo la droguería en custión al mes y anio indicado
	//borro cualquier registro que encuentre en el mes y anio siguiente
	$_abms_siguientes	=	DataManager::getDetalleAbm($_mes_sig, $_anio_sig, $_drogid, 'TD');
	if ($_abms_siguientes) {
		foreach ($_abms_siguientes as $k => $_abmsig){
			$_abmsig		=	$_abms_siguientes[$k];
			$_abmsigID		=	$_abmsig['abmid'];
		
			$_abmobject	= DataManager::newObjectOfClass('TAbm', $_abmsigID);
			$_abmobject->__set('ID',	$_abmsigID );
			$ID = DataManager::deleteSimpleObject($_abmobject);		
		}
	}

	//Recorro la abm actual para grabarla en la abm siguiente como nueva
	$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TD');
	if ($_abms) {
		foreach ($_abms as $k => $_abm){
			$_abm		=	$_abms[$k];
			$_abmID		=	$_abm['abmid'];
		
			//Creo el objeto nuevo para cargar la abm nueva
			$_abmobjectcopy	= 	DataManager::newObjectOfClass('TAbm');		
			$_abmobjectcopy->__set('Drogueria'	, $_abm['abmdrogid']);
			$_abmobjectcopy->__set('Tipo'		, 'TD');
 			$_abmobjectcopy->__set('Mes', 		$_mes_sig);	
 			$_abmobjectcopy->__set('Anio', 		$_anio_sig);
			$_abmobjectcopy->__set('Articulo', 	$_abm['abmartid']);
			$_abmobjectcopy->__set('Descuento',	$_abm['abmdesc']);
			$_abmobjectcopy->__set('Plazo',		$_abm['abmcondpago']);
 			$_abmobjectcopy->__set('Diferencia',$_abm['abmdifcomp']);
		
			//Inserto la abm nueva
 			$_abmobjectcopy->__set('ID', $_abmobjectcopy->__newID());
 			$ID = DataManager::insertSimpleObject($_abmobjectcopy);	
		}
		
		//**********************//	
		//Registro de movimiento//
		//**********************//
		$movimiento = 'ABM_TD_DUPLICATE_DROG_'.$_drogid."_".$_mes."-".$_anio."_TO_".$_mes_sig."-".$_anio_sig;
		$movTipo	= 'INSERT';		
		dac_registrarMovimiento($movimiento, $movTipo, "TAbm");		
			
		echo "1"; exit;
	}
} else { //Duplica el abm a todas las droguerías existentes como transfers al mes y anio indicado
	$_droguerias	=	DataManager::getDrogueria('');
	if ($_droguerias) {				
		foreach ($_droguerias as $j => $_drogueria){			
			$_drogueria		=	$_droguerias[$j];
			$_drogueriaID	=	$_drogueria['drogtcliid'];
						
			//borro cualquier registro que encuentre en el mes y anio siguiente de la droguería.
			$_abms_siguientes	=	DataManager::getDetalleAbm($_mes_sig, $_anio_sig, $_drogueriaID, 'TD');
			if ($_abms_siguientes) {
				foreach ($_abms_siguientes as $k => $_abmsig){
					$_abmsig		=	$_abms_siguientes[$k];
					$_abmsigID		=	$_abmsig['abmid'];							
					
					$_abmobject	= DataManager::newObjectOfClass('TAbm', $_abmsigID);
					$_abmobject->__set('ID',	$_abmsigID );
					$ID = DataManager::deleteSimpleObject($_abmobject);		
				}
			}			
			
			//Recorro la abm actual de cada droguería para grabarla en la abm de la dorgueria
			$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogueriaID, 'TD'); //$_drogid ==> esta es si quiero que la drogueria seleccionada se copie a todas las droguerias por igual.
			if ($_abms) {
				foreach ($_abms as $k => $_abm){
					$_abm		=	$_abms[$k];
					$_abmID		=	$_abm['abmid'];
		
					//Creo el objeto nuevo para cargar la abm nueva
					$_abmobjectcopy	= 	DataManager::newObjectOfClass('TAbm');		
					$_abmobjectcopy->__set('Drogueria', $_drogueriaID);
					$_abmobjectcopy->__set('Tipo'		, 'TD');
 					$_abmobjectcopy->__set('Mes', 		$_mes_sig);	
 					$_abmobjectcopy->__set('Anio', 		$_anio_sig);
					$_abmobjectcopy->__set('Articulo', 	$_abm['abmartid']);
					$_abmobjectcopy->__set('Descuento',	$_abm['abmdesc']);
					$_abmobjectcopy->__set('Plazo',		$_abm['abmcondpago']);
 					$_abmobjectcopy->__set('Diferencia',$_abm['abmdifcomp']);
		
					//Inserto la abm nueva a cada drogueria
					$_abmobjectcopy->__set('ID', $_abmobjectcopy->__newID());
 					$ID = DataManager::insertSimpleObject($_abmobjectcopy);	
				}	
			}
		}
		
		//**********************//	
		//Registro de movimiento//
		//**********************//
		$movimiento 	= 'ABM_TD_DUPLICATE_TO_ALL_DROG_'.$_drogid."__".$_mes."-".$_anio."_TO_".$_mes_sig."-".$_anio_sig;
		$movTipo	= 'INSERT';	
		dac_registrarMovimiento($movimiento, $movTipo, "TAbm");		
		
		echo "1"; exit;			
	}
}

echo "No hay datos de ABM en la página actual para duplicar."; exit;
?>