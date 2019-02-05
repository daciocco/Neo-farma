<?php
$ruta = $_SERVER['DOCUMENT_ROOT'].'/pedidos/informes/archivos/facturas/contrareembolso/';  //Decalaramos una variable con la ruta en donde vaciaré los archivos

$files = glob($ruta.'*'); // get all file names
if ($files) {
	$cont = 0;
	foreach($files as $file){ // iterate files
		if(is_file($file)) {
			if((time()-filemtime($file) > 3600*24*15) and !(is_dir($file))){
				unlink($file); // delete file
				$cont++;
			}
	  	}
	}
	$mensage = 'Se eliminaron '.$cont.' facturas';
} else {
	$mensage = 'No existen facturas para borrar';
}

echo $mensage;// Regresamos los mensajes generados al cliente*/

//Si desea borrar todo de la carpeta (incluidas las subcarpetas) utiliza esta combinación de array_map , unlink y glob :
//array_map('unlink', glob("path/to/temp/*"));


 	/**
     * Delete a file or recursively delete a directory
     *
     * @param string $str Path to file or directory
     */
    /*function recursiveDelete($str){
        if(is_file($str)){
            return @unlink($str);
        }
        elseif(is_dir($str)){
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path){
                recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }*/

?>