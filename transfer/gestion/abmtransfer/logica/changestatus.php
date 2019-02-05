<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
 }
 
 $_mes		= empty($_REQUEST['mes']) 		? 0	: $_REQUEST['mes'];
 $_anio		= empty($_REQUEST['anio']) 		? 0 : $_REQUEST['anio'];
 $_drogid	= empty($_REQUEST['drogid']) 	? 0 : $_REQUEST['drogid'];
 $_activo	= empty($_REQUEST['activo']) 	? 0 : $_REQUEST['activo'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/transfer/gestion/abmtransfer/'	: $_REQUEST['backURL'];
 
 if ($_mes && $_anio && $_drogid) {
	if($_activo == 0){ //Activar
		//1er Consulto los abm activos de la droguería para desactivarlos
		$_abms	=	DataManager::getDetalleAbmDrogueria($_drogid, 1);
 		if ($_abms) {
			foreach ($_abms as $k => $_abm){
				$_abm		=	$_abms[$k];
				$_abmID		=	$_abm['abmid'];
								
				//desactivo				
				$_abmobject=	DataManager::newObjectOfClass('TAbm', $_abmID);
				$_abmobject->__set('Activo',	0);	
				$ID = DataManager::updateSimpleObject($_abmobject);						
			}
 		}
		
		//2do Activo los abm de la drog, mes y año indicado.	
		$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TL');
		if ($_abms) {
			foreach ($_abms as $k => $_abm){
				$_abm	 	=	$_abms[$k];
				$_abmID		=	$_abm['abmid'];
				/*****************************/
				$_abmobject	=	DataManager::newObjectOfClass('TAbm', $_abmID);
				$_abmobject->__set('Activo',	1);
				$ID = DataManager::updateSimpleObject($_abmobject);	
			}
		}							
	} else { //Desactivar // Solo desactivo los abm's del mes indicado	
		$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TL');
		if ($_abms) {
			foreach ($_abms as $k => $_abm){
				$_abm		=	$_abms[$k];
				$_abmID		=	$_abm['abmid'];
				/*****************************/
				$_abmobject	=	DataManager::newObjectOfClass('TAbm', $_abmID);
				$_abmobject->__set('Activo',	0);
				$ID = DataManager::updateSimpleObject($_abmobject);	
			}
		}		
	}
 }	
 /***************************************************************/
 header('Location: '.$backURL.'?fecha_abm='.$_mes.'-'.$_anio."&drogid=".$_drogid);
 ?>