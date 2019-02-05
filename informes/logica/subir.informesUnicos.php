<?php
$tipo	=	(isset($_POST['tipo']))	? $_POST['tipo'] : NULL;
if(empty($tipo)){
	echo "Indique el tipo de informes a subir.";
} else {
	$ruta = $_SERVER['DOCUMENT_ROOT'].'/pedidos/informes/';
	$mensage = '';
	if ($_FILES){
		foreach ($_FILES as $key){
			if($key['error'] == UPLOAD_ERR_OK ){
				
				$NombreOriginal	= $key['name']; //Obtenemos el nombre original del archivo
				$partido 		= explode(".", $key["name"]); 
				$extension		= end($partido);
				$nombreArchivo = $tipo.".".$extension;
				
				switch($tipo) {
					case "Cantidades": 
					case "Minoristas": 
					case "NotasValor": 
					case "PedidosPendientes": 
					case "DevolucionesNeo": 
					case "Stock":
						$ruta = $ruta."/archivos/" ;
						break;						
					case "PedidosPendientesGezzi": 
						$nombreArchivo = "PedidosPendientes.".$extension;
						$ruta = $ruta."/archivosgezzi/" ;
						break;	
					case "DevolucionesGezzi": 
						$ruta = $ruta."/archivosgezzi/" ;
						break;
					case "Ofertas":
						$nombreArchivo = "Oferta.".$extension;
						$ruta = $ruta."/archivos/precios/";
						break;
				}
				
				$destino 		= $ruta.$nombreArchivo;			
				$temporal 		= $key['tmp_name']; 
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