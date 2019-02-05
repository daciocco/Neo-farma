<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");

$_provid	=	(isset($_POST['provid']))	? $_POST['provid'] : NULL;
$ruta 		= 	$_SERVER['DOCUMENT_ROOT'].'/pedidos/login/registrarme/archivos/proveedor/'.$_provid.'/'; 

if(empty($_provid)){
	echo "Ocurrió un error en el código del proveedor"; exit;
}

$mensage = '';//Declaramos una variable mensaje quue almacenara el resultado de las operaciones.

if ($_FILES){
	foreach ($_FILES as $key){ //Iteramos el arreglo de archivos
		$temporal = '';
		if($key['error'] == UPLOAD_ERR_OK ){//Si el archivo se paso correctamente Ccontinuamos 
			
						
			$nombreOriginal = dac_sanearString($key['name']); //Obtenemos el nombre original del archivo
			$temporal 		= $key['tmp_name'];//$key['tmp_name']; //Obtenemos la ruta Original del archivo
			//$Destino = $ruta.$nombreOriginal;	//Creamos una ruta de destino con la variable ruta y el nombre original del archivo	
			$destino 		= 	$ruta;
			
			if(file_exists($destino) || @mkdir($destino, 0777, true))  {
				$info 		= 	new SplFileInfo($nombreOriginal);
				$destino 	= 	$destino.'documentoF'.date("dmYHms").".".$info->getExtension();
				
				move_uploaded_file($temporal, $destino);
			} else {echo "Error al intentar crear el archivo."; exit;}
			//move_uploaded_file($temporal, $Destino); //Movemos el archivo temporal a la ruta especificada		
		}
	 	
		if ($key['error']==''){ //Si no existio ningun error, retornamos un mensaje por cada archivo subido
			$mensage .= '<b>'.$nombreOriginal.'</b> Subido correctamente. <br>';
		}
		if ($key['error']!=''){//Si existio algún error retornamos un el error por cada archivo.
			echo 'No se pudo subir el archivo <b>'.$nombreOriginal.'</b> debido al siguiente Error: \n'.$key['error']; exit;	
			//$mensage .= '-> No se pudo subir el archivo <b>'.$nombreOriginal.'</b> debido al siguiente Error: \n'.$key['error']; 
		}	
	}
		
} else {
	echo '<b> Debe seleccionar algún archivo para enviar</b>  <br>'; exit;
	//$mensage .= '</b> Debe seleccionar algún archivo para enviar. <br>';
}

echo $mensage;// Regresamos los mensajes generados al cliente
?>