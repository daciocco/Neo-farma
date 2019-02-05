<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/PHPExcel/PHPDacExcel.php");

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	exit;
}

$tipo	=	(isset($_POST['tipo']))	? $_POST['tipo'] : NULL;
if(empty($tipo)){
	echo "Indique el tipo de informes a importar.";
} else {
	$ruta = $_SERVER['DOCUMENT_ROOT'].'/pedidos/informes/';
	$mensage = '';
	if ($_FILES){
		foreach ($_FILES as $key){
			if($key['error'] == UPLOAD_ERR_OK ){
				$fileTemp	=	$key['tmp_name'];				
				$fileSize	=	$key['size'];
				if($fileSize > 800000){
					echo "El archivo no debe superar los 800000 bytes"; exit;
				}
				
				//Convierto el excel en un array de arrays
				$arrayXLS	=	PHPDacExcel::xls2array($fileTemp);				
				//consulta cantidad de columnas del XLS
				$namesXLS 	= 	array_keys($arrayXLS[0]);				
				//consulta cantidad de columnas según la base de datos (y los nombres de columnas de la tabla)
				$schemaDDBB = 	DataManager::informationSchema("'".$tipo."'");
				
				switch($tipo){
					case "abm": 
						$tableName = "TAbm";
						break;
				}
				
				//consulto los nombre de la clase tabla
				$objectColumnas	=	DataManager::newObjectOfClass($tableName);
				$namesClass 	= 	$objectColumnas->__getClassVars();
				if(count($namesXLS) != count($namesClass) || $tipo != $schemaDDBB[0]['TABLE_NAME']) { 	
					echo 'Está intentando importar un archivo con diferente cantidad de campos o nombre de tablas'; exit;
				} else {
					foreach ($arrayXLS as $j => $registroXLS) {							
						if($j != 0) {
							//saca el ID de cada registro
							$idXLS	=	$registroXLS[$namesXLS[0]]; 
							//Consulto por registro
							
							$object	=	DataManager::newObjectOfClass($tableName, $idXLS);
							$ID		= 	$object->__get('ID');
							foreach ($namesClass as $q => $cname){
								$object->__set($cname, $registroXLS[$q]);
							}
							
							if($ID){ //Update
								$ID = DataManager::updateSimpleObject($object);
							} else { //Insert
								$ID = DataManager::insertSimpleObject($object);
							}
						}
					}
				}
			}
			if ($key['error']==''){
				$mensage .= $NombreOriginal.' --> Subido correctamente. </br>';
			}
			if ($key['error']!=''){//Si existio algún error retornamos un el error por cada archivo.	
				$mensage .= '-> Error al subir archivo '.$NombreOriginal.' debido a: \n'.$key['error']; 
			}	
		}
	} else {
		echo 'Debe seleccionar algún archivo.';
	}
	echo $mensage;
}
?>