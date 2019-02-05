<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/PHPExcel/PHPDacExcel.php");

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_drogueria		=	empty($_POST['drog']) 		?	0 :	$_POST['drog'];
$_fecha_liq		= 	empty($_POST['fecha_liq']) 	?	0 :	$_POST['fecha_liq'];

if ($_drogueria == 0){	echo "Error en la carga de la droguería";	}
if ($_fecha_liq == 0){	echo "Error en la carga de la fecha de liquidación.";	}

$_fecha_l		=	dac_invertirFecha($_fecha_liq);	

$mensage = '';//Declaramos una variable mensaje que almacenara el resultado de las operaciones.
if ($_FILES){
	foreach ($_FILES as $key){ //Iteramos el arreglo de archivos
		if($key['error'] == UPLOAD_ERR_OK ){//Si el archivo se paso correctamente Continuamos 
			$archivo_temp = $key['tmp_name']; //Obtenemos la ruta Original del archivo
			//$options = array ('start' => 1, 'limit'=>5);		
			//$array	=	PHPDacExcel::xls2sql( $archivo_temp, array ("liqid", "liqdrogid", "liqfechafact", "liqnrofact", "liqean", "liqcant", "liqunitario", "liqdescuento", "liqimportenc"), "liquidacion", $options );
			
			//antes de importar el EXCEL debería eliminar en esa fecha y droguería, cualquier dato existente.
			$_liqobject		=	DataManager::deleteFromLiquidacion($_drogueria, $_fecha_l, 'TL');
	
			//Convierto el excel en un array de arrays
			$arrayxls	=	PHPDacExcel::xls2array($archivo_temp);	
			for($j=1; $j < count($arrayxls); $j++){				
				if(count($arrayxls[$j]) != 9){ 				
					echo 'Está intentando importar un archivo con diferente cantidad de campos (deben ser 9)'; exit;
				} else {
					//procedo a cargar los datos
					$_liqobject		= 	DataManager::newObjectOfClass('TLiquidacion');
					$_liqobject->__set('ID', 		$_liqobject->__newID());
					$_liqobject->__set('Drogueria',	$_drogueria); //$arrayxls[$j][0]
					$_liqobject->__set('Tipo'		, 'TL');
					$_liqobject->__set('Transfer',	$arrayxls[$j][1]);
					
					// La fecha la toma en número por lo que la convierto a fecha nuevamente
					$fechafact	=	date("Y-m-d", mktime(null, null, null, null, $arrayxls[$j][2] - '36494', null, null));
					$_liqobject->__set('FechaFact',	$fechafact);
					
					$_liqobject->__set('NroFact', 	$arrayxls[$j][3]);
					$_liqobject->__set('EAN', 		str_replace(" ", "", $arrayxls[$j][4]));
					$_liqobject->__set('Cantidad', 	$arrayxls[$j][5]);
					$_liqobject->__set('Unitario', 	$arrayxls[$j][6]);
					
					//Buscar y Reemplazar el símbolo % por vacío
					$_liqobject->__set('Descuento',	str_replace("%", "", $arrayxls[$j][7])); 				
					$_liqobject->__set('ImporteNC',	$arrayxls[$j][8]);
													
					$_liqobject->__set('Fecha',	$_fecha_l);		
					
					$_liqobject->__set('UsrUpdate'	, $_SESSION["_usrid"]);
					$_liqobject->__set('LastUpdate'	, date("Y-m-d H:m:s"));	
								
					$ID = DataManager::insertSimpleObject($_liqobject);
				}
			}		
		}
 
		if ($key['error']==''){ //Si no existio ningun error, retornamos un mensaje por cada archivo subido
			$mensage .= 'Archivo Subido correctamente con '.(count($arrayxls)-1).' registros';
		} else {
			//if ($key['error']!=''){//Si existio algún error retornamos un el error por cada archivo.	
			$mensage .= '-> No se pudo subir el archivo debido al siguiente Error: \n'.$key['error']; 
		}	
	}
} else { $mensage .= 'Debe seleccionar algún archivo para enviar.'; }

echo $mensage;// Regresamos los mensajes generados al cliente
?>