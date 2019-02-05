<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_mes		= 	(isset($_POST['mes_abm']))			? 	$_POST['mes_abm']			: 	NULL;
$_anio		= 	(isset($_POST['anio_abm']))			? 	$_POST['anio_abm']			: 	NULL;
$_drogid	= 	(isset($_POST['drogid']))			? 	$_POST['drogid']			: 	NULL;

//Arrays 
$_idabm		= 	(isset($_POST['idabm']))	? 	$_POST['idabm']		: 	NULL;
$_selectArt	= 	(isset($_POST['art']))		? 	$_POST['art']		: 	NULL;
$_descuento	=	(isset($_POST['desc']))		? 	$_POST['desc']		: 	NULL;
$_plazo		=	(isset($_POST['plazoid']))	? 	$_POST['plazoid']	: 	NULL;
$_difcompens=	(isset($_POST['difcompens']))? 	$_POST['difcompens']: 	NULL;

/*****************/
//Control de Datos
/*****************/
if(empty($_drogid) || $_drogid==0){
	echo "Debe seleccionar una droguería ".$_drogid; exit;
}	

//Saco el valor de cada artículo y controlo que haya seleccionado uno
if(count($_selectArt)){
	for($i=0; $i<count($_selectArt); $i++){  
		if(empty($_selectArt[$i])){
			echo "Debe indicar un artículo en la fila ".($i+1); exit;
		} /*else {
			echo "asd: ".$_selectArt[$i]; exit;
			list($_id, $_art) = explode(' - ', $_selectArt[$i]);
			$_idart[]	=	$_id;
		}*/
		
		//Controlar que no haya repetido artículos 
		for($j=0; $j<count($_selectArt); $j++){
			if($_selectArt[$i] == $_selectArt[$j]){
				if($i != $j){
					echo "El artículo ".$_selectArt[$i]." de la fila ".($i+1)." está repetido en la fila ".($j+1); exit;
				}
			} 
		}
		
		//Realiza control de descuento
		if(empty($_descuento[$i]) || !is_numeric($_descuento[$i])){
			echo "Debe indicar un descuento correcto en la fila ".($i+1). " artículo ".$_selectArt[$i]; exit;
		}
		
		//Realiza control de plazos
		if(empty($_plazo[$i]) || !is_numeric($_plazo[$i])){
			echo "Debe indicar un plazo correcto en la fila ".($i+1). " artículo ".$_selectArt[$i]; exit;
		}	
		
		//Realiza control de compensacion
		if(empty($_difcompens[$i]) || !is_numeric($_difcompens[$i])){
			echo "Debe indicar una diferencia de compensación correcta en la fila ".($i+1). " artículo ".$_selectArt[$i]; exit;
		}
	}
} else {
	echo "Debe cargar algún artículo en el ABM."; exit;
}

//borro los registros que están en la ddbb y ya no están en la tabla
$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TD');
if ($_abms) {
	foreach ($_abms as $k => $_abm){
		$_abm		=	$_abms[$k];
		$_abmID		=	$_abm['abmid'];
		
		//busco artículo que no esté en la tabla
		$encontrado = 0;
		$i = 0;
		while(($i<count($_selectArt)) && ($encontrado == 0)){
			if(($_abmID == $_idabm[$i])){ $encontrado = 1; }
			$i++;
		}
		
		if ($encontrado == 0){ //borrar de la ddbb $_abmID
			$_abmobject	= DataManager::newObjectOfClass('TAbm', $_abmID);
			$_abmobject->__set('ID',	$_abmID );
			$ID = DataManager::deleteSimpleObject($_abmobject);
		}
	}
}

//Recorro nuevamente cada registro para grabar y/o insertar los nuevos registros de abm
//update para los que tengan IDabm e insert para los que no
$_activa		=	0;
$movimiento 	= 	'ERROR';
$movTipo 	= 	'';

for($i=0; $i<count($_selectArt); $i++){
	$_abmid		=	empty($_idabm[$i])	?	0	:	$_idabm[$i];	
	$_abmobject	= 	($_abmid) ? DataManager::newObjectOfClass('TAbm', $_abmid) : DataManager::newObjectOfClass('TAbm');
 	$_abmobject->__set('Drogueria', 	$_drogid);
	$_abmobject->__set('Articulo', 		$_selectArt[$i]);
	$_abmobject->__set('Tipo', 			'TD');
 	$_abmobject->__set('Mes', 			$_mes);
 	$_abmobject->__set('Anio', 			$_anio);
 	$_abmobject->__set('Descuento', 	$_descuento[$i]);
	$_abmobject->__set('Plazo', 		$_plazo[$i]);
 	$_abmobject->__set('Diferencia', 	$_difcompens[$i]);
	
	if($_abmobject->__get('Activo') == 1){ $_activa	=	1;}
	if ($_abmid) { //UPDATE ABM
		$ID = DataManager::updateSimpleObject($_abmobject);
		
		$movimiento = 'ABM_TD_DROG_'.$_drogid."_".$_mes."-".$_anio;
		$movTipo = 'UPDATE';		
 	} else { //INSERT ABM
		if ($_activa == 1){	$_abmobject->__set('Activo',	1);	}
 		$_abmobject->__set('ID', $_abmobject->__newID());
 		$ID = DataManager::insertSimpleObject($_abmobject);
		
		$movimiento 	= 'ABM_TD_DROG_'.$_drogid."_".$_mes."-".$_anio;
		$movTipo	= 'INSERT';
	}	
}

//**********************//	
//Registro de movimiento//
//**********************//
dac_registrarMovimiento($movimiento, $movTipo, "TAbm");

echo "1";
?>