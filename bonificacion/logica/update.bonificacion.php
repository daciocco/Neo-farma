<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

//if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){ echo "Invalido"; exit; }
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_mes 	= $_POST['mes'];
$_anio 	= $_POST['anio'];

//Arrays 
$_bonifID	 	= explode("-", substr($_POST['idbonif'], 1));
$_articulos 	= explode("-", substr($_POST['articulos'], 1));
$_preciosDrog 	= explode("-", substr($_POST['drog'], 1));
$_preciosPublico= explode("-", substr($_POST['publico'], 1));
$_iva			= explode("-", substr($_POST['iva'], 1));
$_digitado		= explode("-", substr($_POST['digitado'], 1));
$_oferta		= explode("-", substr($_POST['oferta'], 1));
$_a1	= explode("-", substr($_POST['a1'], 1)); 
$_b1	= explode("-", substr($_POST['b1'], 1)); 
$_c1	= explode("-", substr($_POST['c1'], 1));
$_a3	= explode("-", substr($_POST['a3'], 1)); $_b3	= explode("-", substr($_POST['b3'], 1)); $_c3	= explode("-", substr($_POST['c3'], 1));
$_a6	= explode("-", substr($_POST['a6'], 1)); $_b6	= explode("-", substr($_POST['b6'], 1)); $_c6	= explode("-", substr($_POST['c6'], 1));
$_a12	= explode("-", substr($_POST['a12'], 1)); $_b12	= explode("-", substr($_POST['b12'], 1)); $_c12	= explode("-", substr($_POST['c12'], 1));
$_a24	= explode("-", substr($_POST['a24'], 1)); $_b24	= explode("-", substr($_POST['b24'], 1)); $_c24	= explode("-", substr($_POST['c24'], 1));
$_a36	= explode("-", substr($_POST['a36'], 1)); $_b36	= explode("-", substr($_POST['b36'], 1)); $_c36	= explode("-", substr($_POST['c36'], 1));
$_a48	= explode("-", substr($_POST['a48'], 1)); $_b48	= explode("-", substr($_POST['b48'], 1)); $_c48	= explode("-", substr($_POST['c48'], 1));
$_a72	= explode("-", substr($_POST['a72'], 1)); $_b72	= explode("-", substr($_POST['b72'], 1)); $_c72	= explode("-", substr($_POST['c72'], 1));

/*****************/
//Control de Datos
/*****************/
//Saco el valor de cada artículo y controlo que haya seleccionado uno
for($i=0; $i<count($_bonifID); $i++){  
	if(empty($_articulos[$i])){ 
		echo "Debe indicar el artículo de la fila ".($i+1); exit;
	} else {
		list($_id, $_art) = explode(' - ', $_articulos[$i]);
		$_idart[]	=	$_id;
		//$_detalle[]	=	$_art;
	}
}
 
//Controlar que no haya repetido artículos 
for($i=0; $i<count($_bonifID); $i++){
	$_cont	=	0;
	for($j=0; $j<count($_bonifID); $j++){
		if($_articulos[$i] == $_articulos[$j]){
			$_cont++;
			if($_cont > 1){
				echo "El artículo ".$_articulos[$i]." de la fila ".($i+1)." está repetido en la fila ".($j+1); exit;
			}
		} 
	}	
}

//Realiza control de datos por fila deartículo
for($i=0; $i<count($_bonifID); $i++){
	if(empty($_preciosDrog[$i]) || !is_numeric($_preciosDrog[$i])){
		echo "Debe completar un precio de droguería correcto en la fila ".($i+1); exit;
	}	
	
	if(empty($_preciosPublico[$i]) || !is_numeric($_preciosPublico[$i])){
		echo "Debe completar el precio público correcto en la fila ".($i+1); exit;
	}
	
	if(!empty($_iva[$i]) && !is_numeric($_iva[$i])){
		echo "El Iva no debe incluir letras ni el simbolo %. Verifique la fila ".($i+1);
	}
	
	if(!empty($_digitado[$i]) && !is_numeric($_digitado[$i])){
		echo "Debe completar el precio digitado correcto en la fila ".($i+1); exit;
	}
	
	//Controlo que las bonificaciones estén cargadas correctamente.
	if(!( (empty($_a1[$i]) && empty($_b1[$i]) && empty($_c1[$i])) || (empty($_a1[$i]) && !empty($_b1[$i]) && empty($_c1[$i])) || (!empty($_a1[$i]) && empty($_b1[$i]) && !empty($_c1[$i])))){
		echo "La bonificación 1 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b1[$i]) && !is_numeric($_b1[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}
	
	if(!((empty($_a3[$i]) && empty($_b3[$i]) && empty($_c3[$i])) || (empty($_a3[$i]) && !empty($_b3[$i]) && empty($_c3[$i])) || (!empty($_a3[$i]) && empty($_b3[$i]) && !empty($_c3[$i])))){
		echo "La bonificación 3 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b3[$i]) && !is_numeric($_b3[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}
	
	
	if(!((empty($_a6[$i]) && empty($_b6[$i]) && empty($_c6[$i])) || (empty($_a6[$i]) && !empty($_b6[$i]) && empty($_c6[$i])) || (!empty($_a6[$i]) && empty($_b6[$i]) && !empty($_c6[$i])))){
		echo "La bonificación 6 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b6[$i]) && !is_numeric($_b6[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}
	
	if(!((empty($_a12[$i]) && empty($_b12[$i]) && empty($_c12[$i])) || (empty($_a12[$i]) && !empty($_b12[$i]) && empty($_c12[$i])) || (!empty($_a12[$i]) && empty($_b12[$i]) && !empty($_c12[$i])))){
		echo "La bonificación 12 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b12[$i]) && !is_numeric($_b12[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}
	
	if(!((empty($_a24[$i]) && empty($_b24[$i]) && empty($_c24[$i])) || (empty($_a24[$i]) && !empty($_b24[$i]) && empty($_c24[$i])) || (!empty($_a24[$i]) && empty($_b24[$i]) && !empty($_c24[$i])))){
		echo "La bonificación 24 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b24[$i]) && !is_numeric($_b24[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}
	
	if(!((empty($_a36[$i]) && empty($_b36[$i]) && empty($_c36[$i])) || (empty($_a36[$i]) && !empty($_b36[$i]) && empty($_c36[$i])) || (!empty($_a36[$i]) && empty($_b36[$i]) && !empty($_c36[$i])))){
		echo "La bonificación 36 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b36[$i]) && !is_numeric($_b36[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}
	
	if(!((empty($_a48[$i]) && empty($_b48[$i]) && empty($_c48[$i])) || (empty($_a48[$i]) && !empty($_b48[$i]) && empty($_c48[$i])) || (!empty($_a48[$i]) && empty($_b48[$i]) && !empty($_c48[$i])))){
		echo "La bonificación 48 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b48[$i]) && !is_numeric($_b48[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}
	
	if(!((empty($_a72[$i]) && empty($_b72[$i]) && empty($_c72[$i])) || (empty($_a72[$i]) && !empty($_b72[$i]) && empty($_c72[$i])) || (!empty($_a72[$i]) && empty($_b72[$i]) && !empty($_c72[$i])))){
		echo "La bonificación 72 de las fila ".($i+1)." no es correcta"; exit;			
	} else {
		if(!empty($_b72[$i]) && !is_numeric($_b72[$i])){
			echo "El porcentaje bonificado no debe tener simbolo %."; exit;
		}
	}	
}

//borro los registros que están en la ddbb y ya no están en la tabla
$_bonificaciones	=	DataManager::getDetalleBonificacion($_mes, $_anio);
if ($_bonificaciones) {
	foreach ($_bonificaciones as $k => $_bonific){
		$_bonific		=	$_bonificaciones[$k];
		$_bonificID		=	$_bonific['bonifid'];
		//busco si hay algún artículo que no esté en la tabla
		$encontrado = 0;
		for($i=0; $i<count($_bonifID); $i++){
			if(($_bonificID == $_bonifID[$i]) && ($encontrado==0)){
				$encontrado = 1; break;
			}
		}		
		if ($encontrado == 0){ //borrar de la ddbb $_bonificID
			$_bobject	= DataManager::newObjectOfClass('TBonificacion', $_bonificID);
			$_bobject->__set('ID',	$_bonificID );
			$ID = DataManager::deleteSimpleObject($_bobject);
		}
	}
}

//Recoro nuevamente cada registro para grabar y/o insertar los nuevos registros de bonificacion
//update para los que tengan IDbonif e insert para los que no
$_activa	 =	0; //Variable en caso de que se agreguen nuevos articulos en una bonificación ya activa, y que ésta se cargue como activa también
$movimiento = 'ERROR';
$movTipo = '';

for($i=0; $i<count($_bonifID); $i++){
	$_bonificaid	=	empty($_bonifID[$i])	?	0	:	$_bonifID[$i];
	$_bonifobject	= 	($_bonificaid) ? DataManager::newObjectOfClass('TBonificacion', $_bonificaid) : DataManager::newObjectOfClass('TBonificacion');
	$_bonifobject->__set('Empresa', 	'1');
 	$_bonifobject->__set('Articulo', 	$_articulos[$i]);
 	$_bonifobject->__set('Mes', 		$_mes);
 	$_bonifobject->__set('Anio', 		$_anio);
 	$_bonifobject->__set('Precio', 		$_preciosDrog[$i]);
 	$_bonifobject->__set('Publico', 	$_preciosPublico[$i]);
 	$_bonifobject->__set('Iva', 		$_iva[$i]);
 	$_bonifobject->__set('Digitado',	$_digitado[$i]);
 	$_bonifobject->__set('Oferta', 		$_oferta[$i]);
 	$_bonifobject->__set('1A', 	$_a1[$i]);
 	$_bonifobject->__set('1B', 	$_b1[$i]);
 	$_bonifobject->__set('1C', 	$_c1[$i]);
 	$_bonifobject->__set('3A', 	$_a3[$i]);
 	$_bonifobject->__set('3B', 	$_b3[$i]);
 	$_bonifobject->__set('3C',	$_c3[$i]);
	$_bonifobject->__set('6A',	$_a6[$i]);
	$_bonifobject->__set('6B',	$_b6[$i]);
	$_bonifobject->__set('6C',	$_c6[$i]);
	$_bonifobject->__set('12A',	$_a12[$i]);
	$_bonifobject->__set('12B',	$_b12[$i]);
	$_bonifobject->__set('12C',	$_c12[$i]);
	$_bonifobject->__set('24A',	$_a24[$i]);
	$_bonifobject->__set('24B',	$_b24[$i]);
	$_bonifobject->__set('24C',	$_c24[$i]);
	$_bonifobject->__set('36A',	$_a36[$i]);
	$_bonifobject->__set('36B',	$_b36[$i]);
	$_bonifobject->__set('36C',	$_c36[$i]);
	$_bonifobject->__set('48A',	$_a48[$i]);
	$_bonifobject->__set('48B',	$_b48[$i]);
	$_bonifobject->__set('48C',	$_c48[$i]);
	$_bonifobject->__set('72A',	$_a72[$i]);
	$_bonifobject->__set('72B',	$_b72[$i]);
	$_bonifobject->__set('72C',	$_c72[$i]);
	
	if($_bonifobject->__get('Activa') == 1){ $_activa	=	1;}
		
	if ($_bonificaid) {//UPDATE bonif
		$ID = DataManager::updateSimpleObject($_bonifobject);
		
		$movimiento 	= 'BONIF_'.$_mes."-".$_anio;
		$movTipo = 'UPDATE';
		
 	} else { //INSERT bonif
		if ($_activa == 1){	
			$_bonifobject->__set('Activa',	1);				
		}
 		$_bonifobject->__set('ID', $_bonifobject->__newID());
 		$ID = DataManager::insertSimpleObject($_bonifobject);
		
		$movimiento 	= 'BONIF_'.$_mes."-".$_anio;
		$movTipo	= 'INSERT';
	}
}

//**********************//	
//Registro de movimiento//
//**********************//
dac_registrarMovimiento($movimiento, $movTipo, "TBonificacion");


echo "1";
?>