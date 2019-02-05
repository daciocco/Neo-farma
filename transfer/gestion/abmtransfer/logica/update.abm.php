	<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_mes 	= $_POST['mes'];
$_anio 	= $_POST['anio'];
$_drogid= $_POST['drogid'];

//Arrays 
$_idabm			= explode("-", substr($_POST['idabm'], 			1));
$_selectArt		= explode("-", substr($_POST['selectArt'], 		1));
$_descuento		= explode("-", substr($_POST['descuento'], 		1));
$_plazo			= explode("-", substr($_POST['plazo'], 			1));
$_difcompens	= explode("-", substr($_POST['difcompens'], 	1));


/*****************/
//Control de Datos
/*****************/
if(empty($_drogid) || $_drogid==0){
	echo "Debe seleccionar una droguería"; exit;
}	

//Saco el valor de cada artículo y controlo que haya seleccionado uno
for($i=0; $i<count($_selectArt); $i++){  
	if(empty($_selectArt[$i])){
		echo "Debe indicar un artículo en la fila ".($i+1); exit;
	} else {
		//list($_id, $_art) = explode(' - ', $_selectArt[$i]);
		$_idart[]	=	$_selectArt[$i]; //$_id;
	}
	
	//Controlar que no haya repetido artículos 
	$_cont	=	0;
	for($j=0; $j<count($_selectArt); $j++){
		if($_selectArt[$i] == $_selectArt[$j]){
			$_cont++;
			if($_cont > 1){
				echo "El artículo ".$_selectArt[$i]." de la fila ".($i+1)." está repetido en la fila ".($j+1); exit;
			}
		} 
	}
	
	//Realiza control de descuento
	if(empty($_descuento[$i]) || !is_numeric($_descuento[$i])){
		echo "Debe indicar un descuento correcto en la fila ".($i+1). " artículo ".$_idart[$i]; exit;
	}
	
	//Realiza control de plazos
	if(empty($_plazo[$i]) || !is_numeric($_plazo[$i])){
		echo "Debe indicar un plazo correcto en la fila ".($i+1). " artículo ".$_idart[$i]; exit;
	}	
	
	//Realiza control de compensacion
	if(empty($_difcompens[$i]) || !is_numeric($_difcompens[$i])){
		echo "Debe indicar una diferencia de compensación correcta en la fila ".($i+1). " artículo ".$_idart[$i]; exit;
	}
}
 
//Controlar que no haya repetido artículos 
/*for($i=0; $i<count($_selectArt); $i++){
	$_cont	=	0;
	for($j=0; $j<count($_selectArt); $j++){
		if($_selectArt[$i] == $_selectArt[$j]){
			$_cont++;
			if($_cont > 1){
				echo "El artículo ".$_selectArt[$i]." de la fila ".($i+1)." está repetido en la fila ".($j+1); exit;
			}
		} 
	}	
}*/

//Realiza control de datos por fila deartículo
/*for($i=0; $i<count($_selectArt); $i++){
	if(empty($_descuento[$i]) || !is_numeric($_descuento[$i])){
		echo "Debe indicar un descuento correcto en la fila ".($i+1); exit;
	}	
	
	if(empty($_difcompens[$i]) || !is_numeric($_difcompens[$i])){
		echo "Debe indicar una diferencia de compensación correcta en la fila ".($i+1); exit;
	}	
}*/


//borro los registros que están en la ddbb y ya no están en la tabla
$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TL');
if ($_abms) {
	foreach ($_abms as $k => $_abm){
		$_abm		=	$_abms[$k];
		$_abmID		=	$_abm['abmid'];
		
		//busco si hay algún artículo que no esté en la tabla
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

//Recoro nuevamente cada registro para grabar y/o insertar los nuevos registros de abm
//update para los que tengan IDabm e insert para los que no
$_activa	=	0; //Variable en caso de que se agreguen nuevos articulos en una abm ya activa, y que ésta se cargue como activa también
$movimiento = 'ERROR';
$movTipo = '';

for($i=0; $i<count($_selectArt); $i++){
	$_abmid		=	empty($_idabm[$i])	?	0	:	$_idabm[$i];
	$_abmobject	= 	($_abmid) ? DataManager::newObjectOfClass('TAbm', $_abmid) : DataManager::newObjectOfClass('TAbm');
 	$_abmobject->__set('Drogueria', 	$_drogid);
	$_abmobject->__set('Tipo', 			'TL');
	$_abmobject->__set('Articulo', 		$_selectArt[$i]);
 	$_abmobject->__set('Mes', 			$_mes);
 	$_abmobject->__set('Anio', 			$_anio);
 	$_abmobject->__set('Descuento', 	$_descuento[$i]);
	$_abmobject->__set('Plazo', 		$_plazo[$i]);
 	$_abmobject->__set('Diferencia', 	$_difcompens[$i]);
	
	if($_abmobject->__get('Activo') == 1){ $_activa	=	1;}
	if ($_abmid) { //UPDATE ABM
		$ID = DataManager::updateSimpleObject($_abmobject);
		
		$movimiento = 'ABM_TL_UPDATE_DROG_'.$_drogid."_".$_mes."-".$_anio;
		$movTipo = 'UPDATE';
		
 	} else { //INSERT ABM
		if ($_activa == 1){	$_abmobject->__set('Activo',	1);	}
 		$_abmobject->__set('ID', $_abmobject->__newID());
 		$ID = DataManager::insertSimpleObject($_abmobject);
		
		$movimiento 	= 'ABM_TL_INSERT_DROG_'.$_drogid."_".$_mes."-".$_anio;
		$movTipo	= 'INSERT';
	}	
}

//**********************//	
//Registro de movimiento//
//**********************//
dac_registrarMovimiento($movimiento, $movTipo, "TAbm", $_nrorel);

echo "1";
?>