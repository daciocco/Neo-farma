<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }
 
 $_mes		= empty($_REQUEST['mes']) 		? 0	: $_REQUEST['mes'];
 $_anio		= empty($_REQUEST['anio']) 		? 0 : $_REQUEST['anio'];
 $_activa	= empty($_REQUEST['activa']) 	? 0 : $_REQUEST['activa'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/bonificacion/'	: $_REQUEST['backURL'];
 
 if ($_mes && $_anio) {
	if($_activa == 0){
		//1er Consulto las bonificaciones activas
		$_bonificaciones	=	DataManager::getDetalleBonificaciones(1);
 		if ($_bonificaciones) {
			foreach ($_bonificaciones as $k => $_bonifics){
				$_bonifics		=	$_bonificaciones[$k];
				$_bonificsID	=	$_bonifics['bonifid'];
				$_bonificsmes	=	$_bonifics['bonifmes'];
				$_bonificsanio	=	$_bonifics['bonifanio'];
				$_bonificsactiva=	$_bonifics['bonifactiva'];	
				
				//2do Desactivo cualquiera bonificacion que sea de otro mes	
				if($_bonificsmes != $_mes || $_bonificsanio != $_anio){					
					$_bonificsobject=	DataManager::newObjectOfClass('TBonificacion', $_bonificsID);
					$_bonificsobject->__set('Activa',	0);	
					$ID = DataManager::updateSimpleObject($_bonificsobject);						
				} 
			}
 		}
		
		//3ro Activo las bonif del mes y año indicado	
		$_bonificacion	=	DataManager::getDetalleBonificacion($_mes, $_anio);
		if ($_bonificacion) {
			foreach ($_bonificacion as $k => $_bonif){
				$_bonif	 =	$_bonificacion[$k];
				$_bonifID=	$_bonif['bonifid'];
				/*****************************/
				$_bonificsobject	=	DataManager::newObjectOfClass('TBonificacion', $_bonifID);
				$_bonificsobject->__set('Activa',	1);
				$ID = DataManager::updateSimpleObject($_bonificsobject);	
			}
		}
		
		$movimiento 	= 'BONIF_CHANGE_STATUS_TO_ACTIVE'.$_mes."-".$_anio;							
	} else { 
		// Solo desactivo las bonificaciones del mes
		$_bonificacion	=	DataManager::getDetalleBonificacion($_mes, $_anio);
		if ($_bonificacion) {
			foreach ($_bonificacion as $k => $_bonif){
				$_bonif	 =	$_bonificacion[$k];
				$_bonifID=	$_bonif['bonifid'];
				/*****************************/
				$_bonificsobject	=	DataManager::newObjectOfClass('TBonificacion', $_bonifID);
				$_bonificsobject->__set('Activa',	0);
				$ID = DataManager::updateSimpleObject($_bonificsobject);	
			}
		}
		
		$movimiento 	= 'BONIF_CHANGE_STATUS_TO_INACTIVE'.$_mes."-".$_anio;		
	}
	
	 //**********************//	
	//Registro de movimiento//
	//**********************//
	$movTipo	= 'UPDATE';
	dac_registrarMovimiento($movimiento, $movTipo, "TBonificacion");
 }	
 /***************************************************************/

 header('Location: '.$backURL.'?fecha_bonif='.$_mes.'-'.$_anio);
?>