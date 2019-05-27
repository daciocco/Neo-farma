<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");

$empresa	 = $_GET['empresa'];
$idCatComerc = $_GET['idCatComerc'];
$datosJSON;	

//Buscar las condiciones comerciales actuales para verificar que LISTAS existen asignadas
$condiciones	= DataManager::getCondiciones(0, 0, 1, $empresa, NULL, date("Y-m-d"));
if (count($condiciones)) {
	foreach ($condiciones as $k => $cond) {
		if($cond['condlista']){
			$condListas[]= $cond['condlista'];
		}
		
	}
	
	unset($listas);
	$listas	= DataManager::getListas(1); 
	if (count($listas)) {
		foreach ($listas as $k => $list) {
			$listId			= $list["IdLista"];
			$listNombre		= $list["NombreLT"];
			$listCatComerc	= $list["CategoriaComercial"];
			
			$catComerc = explode(',',$listCatComerc);
			
			if(in_array($listId, $condListas)){
				if(in_array($idCatComerc, $catComerc)){
					$datosJSON[] = $listId."|".$listNombre; 
				}
			}			
		}                           
	}
}

/*una vez tenemos un array con los datos los mandamos por json a la web*/
header('Content-type: application/json'); 
echo json_encode($datosJSON);
?>