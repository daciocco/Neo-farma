<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_fecha 		= $_POST['fecha'];

//Arrays 
$_idfact	 	= explode("-", substr($_POST['idfact'], 1));
$_empresa	 	= explode("-", substr($_POST['empresa'], 1));
$_idprov	 	= explode("-", substr($_POST['idprov'], 1));
$_nombre	 	= explode("-", substr($_POST['nombre'], 1));
$_plazo	 		= explode("-", substr($_POST['plazo'], 1));
$_fvto		 	= explode("-", substr($_POST['fechavto'], 1));
$_tipo	 		= explode("-", substr($_POST['tipo'], 1));
$_factnro	 	= explode("-", substr($_POST['factnro'], 1));
$_fcbte		 	= explode("-", substr($_POST['fechacbte'], 1));
$_saldo	 		= explode("-", substr($_POST['saldo'], 1));
$_observacion	= explode("-", substr($_POST['observacion'], 1));

for($i=0; $i < count($_fcbte); $i=$i+3){ 
	$_fechacbte[] 	= $_fcbte[$i]."-".$_fcbte[$i+1]."-".$_fcbte[$i+2];
	$_fechavto[] 	= $_fvto[$i]."-".$_fvto[$i+1]."-".$_fvto[$i+2];	
}

//Verifica que no haya duplicados
for($i=0; $i < count($_idfact); $i++){ 
	$_cont = 0; 
	for($j=0; $j < count($_idfact); $j++){  
		if($_empresa[$i] == $_empresa[$j] && $_idprov[$i] == $_idprov[$j] && $_tipo[$i] == $_tipo[$j] && $_factnro[$i] == $_factnro[$j]){ 
			$_cont++;
			if($_cont > 1){
				echo "El siguiente registro está repetido: </br> Emp: ".$_empresa[$i]." Código: ".$_idprov[$i]." Tipo: ".$_tipo[$i]." Nro: ".$_factnro[$i]; exit;
			}
		}
	}
}


//Busco registros ya guardados en ésta fecha y pongo en cero si no están en el array (si fueron eliminados)
$_facturas_pago	=	DataManager::getFacturasProveedor(NULL, 1, dac_invertirFecha($_fecha));
if($_facturas_pago) {
	foreach ($_facturas_pago as $k => $_fact_pago) {
		$_idfactura		= 	$_fact_pago['factid'];
		$_activa		= 	$_fact_pago['factactiva'];
		//si el idfact NO aparece en el array, se hace un UPDATE para ponerlo a cero
		if (!in_array($_idfactura, $_idfact)) {
			$_factobject	= DataManager::newObjectOfClass('TFacturaProv', $_idfactura);
			$_factobject->__set('Pago', 		'2001-01-01');		
			$_factobject->__set('Observacion',	' ');
			$_factobject->__set('Activa', 		0);	
			$ID = DataManager::updateSimpleObject($_factobject);
		}
	}
}


//******************************************//
//		Edicion de las fact como pagos 		//
//******************************************//
for($i=0; $i < count($_idfact); $i++){ 
	if ($_idfact[$i]){
		$_factobject	= DataManager::newObjectOfClass('TFacturaProv', $_idfact[$i]);
		$_factobject->__set('Pago', 		dac_invertirFecha($_fecha));		
		$_factobject->__set('Observacion',	$_observacion[$i]);
		$_factobject->__set('Activa', 		1);	
		$ID = DataManager::updateSimpleObject($_factobject);		
	}/* else {
		echo "Error al ingresar los datos de las facturas. Verifique el resultado."; exit;
	}*/
}
	
echo "1"; exit;

?>