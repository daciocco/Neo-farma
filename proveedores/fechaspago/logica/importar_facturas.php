<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/PHPExcel/PHPDacExcel.php");

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$mensage = '';//Declaramos una variable mensaje que almacenara el resultado de las operaciones.
if ($_FILES){
	foreach ($_FILES as $key){ //Iteramos el arreglo de archivos
		if($key['error'] == UPLOAD_ERR_OK ){//Si el archivo se paso correctamente Continuamos 		
						
			//antes de importar el EXCEL debería eliminar en esa fecha y droguería, cualquier dato existente de facturas inactivas
			$_factobject		=	DataManager::deletefromtabla('facturas_proveedor', 'factactiva', 0);		
						
			//Convierto el excel en un array de arrays
			$archivo_temp = $key['tmp_name']; //Obtenemos la ruta Original del archivo	
			$arrayxls	=	PHPDacExcel::xls2array($archivo_temp);				
			for($j=1; $j < count($arrayxls); $j++){				
				if(count($arrayxls[$j]) != 10){ 				
					echo 'Está intentando importar un archivo con diferente cantidad de campos (deben ser 10)'; exit;
				} else {					
					//procedo a cargar los datos
					$_factobject	= 	DataManager::newObjectOfClass('TFacturaProv');
					$_factobject->__set('ID'			, $_factobject->__newID());
					$_factobject->__set('Empresa'		, $arrayxls[$j][0]);
					$_factobject->__set('Proveedor'		, $arrayxls[$j][1]);
					$_factobject->__set('Plazo'			, $arrayxls[$j][3]);
					$_factobject->__set('Tipo'			, $arrayxls[$j][4]);
					$_factobject->__set('Sucursal'		, $arrayxls[$j][5]);
					$_factobject->__set('Numero'		, $arrayxls[$j][6]);
					
					$_factobject->__set('Comprobante'	, dac_fecha_xlsToDate($arrayxls[$j][7]));
					$_factobject->__set('Vencimiento'	, dac_fecha_xlsToDate($arrayxls[$j][8]));																			
					$_factobject->__set('Saldo'			, $arrayxls[$j][9]);
					
					$_factobject->__set('Pago'			, '2001-01-01');
					$_factobject->__set('Observacion'	, '');
					$_factobject->__set('Activa'		, 0);
					
					
					$ID = DataManager::insertSimpleObject($_factobject);
				}
			}		
		}
 
		if ($key['error']==''){ //Si no existio ningun error, retornamos un mensaje por cada archivo subido
			$mensage .= count($arrayxls)-1;
			//$mensage .= 'Archivo Subido correctamente con '.(count($arrayxls)-1).' registros';
		} else {
			//if ($key['error']!=''){//Si existio algún error retornamos un el error por cada archivo.	
			$mensage .= '-> No se pudo subir el archivo debido al siguiente Error: \n'.$key['error']; 
		}	
	}
} else { $mensage .= 'Debe seleccionar algún archivo para enviar.'; }

echo $mensage;// Regresamos los mensajes generados al cliente
?>