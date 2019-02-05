<?php
$tipo	=	(isset($_POST['tipo']))	? $_POST['tipo'] : NULL;
if(empty($tipo)){
	echo "Indique el tipo de informes a subir.";
} else {
	$ruta = $_SERVER['DOCUMENT_ROOT'].'/pedidos/informes/'.$tipo.'/';
	
	$mensage = '';
	if ($_FILES){
		foreach ($_FILES as $key){
			if($key['error'] == UPLOAD_ERR_OK ){
				
				
				//$partido = explode(".", $_FILES["inf_archivo"]["name"]); 
				// $extension = end($partido); 
				//$nombre_archivo = $_tipo_informe.".".$extension;
				
				
				
				$NombreOriginal	= $key['name']; //Obtenemos el nombre original del archivo
				$temporal 		= $key['tmp_name']; 
				$destino 		= $ruta.$NombreOriginal;
				move_uploaded_file($temporal, $destino);		
			}
			if ($key['error']==''){
				$mensage .= $NombreOriginal.' --> Subido correctamente. </br>';
			}
			if ($key['error']!=''){//Si existio algún error retornamos un el error por cada archivo.	
				$mensage .= '-> Error al subir archivo '.$NombreOriginal.' debido a: \n'.$key['error']; 
			}	
		}
	} else {
		echo 'Debe seleccionar algún archivo para enviar.';
	}
	echo $mensage;
}
?>