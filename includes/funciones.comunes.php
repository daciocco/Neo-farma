<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/recaptchalib.php");
/*
-----------------------------------
Cioccolanti, Diego Ariel Functions
-----------------------------------
*/

//DEFINICIÓN DE CONSTANTES
//-- Montos para pedidos
define("IVA", 21);
define("MONTO_MINIMO", 999);
define("MONTO_MAXIMO", 200000);
define("MAX_FILE", ((1024 * 1024) * 4)); //4MB Máximo tolerable por archivo
define("PORC_RETENCION", 0.05); //Se tiene en cuenta el mayor % de Retención que actualmente es 5%
/*
define("DEFAULT_CURRENCY_SYMBOL", "$");	
define("DEFAULT_CURRENCY_ACRONYM", "ARG");
define("DEFAULT_MON_DECIMAL_POINT", ".");
define("DEFAULT_MON_THOUSANDS_SEP", ",");
define("DEFAULT_POSITIVE_SIGN", "");
define("DEFAULT_NEGATIVE_SIGN", "-");
define("DEFAULT_FRAC_DIGITS", 2);
define("DEFAULT_P_CS_PRECEDES", true);
define("DEFAULT_P_SEP_BY_SPACE", false);
define("DEFAULT_N_CS_PRECEDES", true);
define("DEFAULT_N_SEP_BY_SPACE", false);
define("DEFAULT_P_SIGN_POSN", 3);
define("DEFAULT_N_SIGN_POSN", 3);
*/

function dac_enviarDatosCaptcha($_valor_captcha) {
	// clave secreta
	$secret = "6LfNHR0TAAAAAFyO1hNAMzBKSKtUTTk50h0aiPRA"; 
	// respuesta vacía
	$response = NULL; 
	// comprueba la clave secreta
	$reCaptcha = new ReCaptcha($secret);
	
	// si se detecta la respuesta como enviada
	if ($_valor_captcha) {
		$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_valor_captcha
		);	
	}
	
	if ($response != NULL && $response->success) {
		return TRUE;
	} else {
		return FALSE;
	}	
}

function dac_invertirFecha( $_fecha ) {
	list($dia, $mes, $ano) = explode('-', str_replace('/', '-', $_fecha));	
 	$_infoFecha = $ano."-".$mes."-".$dia;
	return $_infoFecha;
}

/*
// PHPMaker DEFAULT_DATE_FORMAT:
/* "yyyy/mm/dd"(default)  or "mm/dd/yyyy" or "dd/mm/yyyy" */ /*
//define("DEFAULT_DATE_FORMAT", "dd/mm/yyyy");


// Convert a date to MySQL format
function OriginalDateToSQL($dateStr)
{
	@list($datePt, $timePt) = explode(" ", $dateStr);
	$arDatePt = explode("/", $datePt);
	if (count($arDatePt) == 3) {
		switch (DEFAULT_DATE_FORMAT) {
		case "yyyy/mm/dd":
	    list($year, $month, $day) = $arDatePt;
	    break;
		case "mm/dd/yyyy":
	    list($month, $day, $year) = $arDatePt;
	    break;
		case "dd/mm/yyyy":
	    list($day, $month, $year) = $arDatePt;
	    break;
		}
		return trim($year . "-" . $month . "-" . $day . " " . $timePt);
	} else {
		return $dateStr;
	}
}

// Conversi�n de formato 'aaaa-mm-dd" a dd-mm-yyyy'
//
function DateToSQL($fecha=NULL)
{
	if (empty($fecha)) return "";
	@list($dd,$mm,$aa)= explode('-',$fecha);
	$_return = (@checkdate($mm,$dd,$aa)) ? date("Y-m-d",mktime(0,0,0,$mm,$dd,$aa)): "";
	return $_return;
}*/

function dac_dias_transcurridos($fecha_i,$fecha_f){
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

function dac_fecha_xlsToDate( $fecha_int ){
	$date = date("Y-m-d", mktime(NULL, NULL, NULL, NULL, $fecha_int - '36494', NULL));	
	return $date;
}

function dac_meses(){
	$meses = array('01','02','03','04','05','06','07', '08','09','10','11','12');
	return $meses;
}

function dac_anios(){
	$anios = array('2015','2016','2017','2018','2019','2020','2021', '2022','2023','2024','2025');
	return $anios;
}

function listar_mes_anio($nombre, $_anio, $_mes, $_onchange, $_style){
	$array_meses = dac_meses();
	$array_anios = dac_anios();
	$txt= "<select name='$nombre' id='$nombre' style='$_style' onChange='$_onchange'>"; 
	for ($i=0; $i<sizeof($array_anios); $i++){
		for ($j=0; $j<sizeof($array_meses); $j++){
			if($_anio == $array_anios[$i] && $_mes == $array_meses[$j]){				
				$txt .= "<option value=".$array_meses[$j]."-".$array_anios[$i]." selected>".$array_meses[$j]."-".$array_anios[$i]. "</option>";
			} else {
				$txt .= "<option value=".$array_meses[$j]."-".$array_anios[$i].">". $array_meses[$j]."-".$array_anios[$i]. "</option>";
			}
		}
	}
	$txt .= '</select>';
	return $txt; 
}


function dac_listar_directorios($ruta){ 
	// abrir un directorio y listarlo recursivo 
	$entradas 	= array();
	$data 		= array();
	if (is_dir($ruta)) { 
		if ($dh = opendir($ruta)) { 
	  		//echo "<b>Directorio actual:</b> <br>$ruta<br>";		
			while (($file = readdir($dh)) !== false) { 
				//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio 
				//mostraría tanto archivos como directorios
				if ($file!="." && $file!="..") {	
					//$file = str_replace("_", "&nbsp;", $file);  
					
					$entradas[$file] = filemtime($ruta.$file);
					/*$data[] = array($file, date("Y-m-d H:i:s",filemtime($ruta.'/'.$file)));
					$files[] = $file;
					$dates[] = date("Y-m-d H:i:s",filemtime($ruta.'/'.$file));*/				
					
					//echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file); 
					//echo "<br><div style=\"margin:2px 0 0 20px;\">".$file."</div>";
				}
				
				if (is_dir($ruta . $file) && $file!="." && $file!=".."){ 
					//PAra mostrar carpetas dentro del directorio
				   	//solo si el archivo es un directorio, distinto que "." y ".." 
				   //	echo "<br>Directorio: $ruta$file"; 
				   	dac_listar_directorios($ruta . $file . "/"); 
				} 
			}
			
			if($entradas){
				asort($entradas); closedir($dh); 
				//Ordeno el array por fechas de archivos de forma descendiente
				array_multisort($entradas, SORT_DESC, $entradas);
				
				foreach ($entradas as $file => $timestamp) {  
					$data[] =  date("d-m-Y", $timestamp)." - $file";
					/*echo date("d-m-Y", $timestamp)." - <b>$file</b><br>\n";  
					echo "<br><br>";  */
					
					//ordenamos el array data según las fechas almacenadas en $dates
					//array_multisort($dates, SORT_DESC, $data);
				} 
			}			
	  	} 
	} else  {
		return false;
		//echo "No hay ruta valida."; 
	}
		
	return ($data);
}

function dac_deleteDirectorio($dir) {
    if(!$dh = @opendir($dir)) return;
    while (false !== ($current = readdir($dh))) {
        if($current != '.' && $current != '..') {
            //echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
            if (!@unlink($dir.'/'.$current)) 
                dac_deleteDirectorio($dir.'/'.$current);
        }       
    }
    closedir($dh);
    //echo 'Se ha borrado el directorio '.$dir.'<br/>';
    if(@rmdir($dir)){
		return true;
	} else {
		return false;
	}	
}

function infoFecha( $_thetime = 0, $_showtime = false ) {
	$_time = ($_thetime) ? $_thetime : time();
	$_dia = Dia(date('N',$_time));
	$_mes = Mes(date('n',$_time));
	if ($_showtime) {
		$_infoFecha = sprintf( "%d/%s/%d (%s:%s GMT %s)", date('j',$_time), $_mes, date('Y',$_time), date('H',$_time), date('i',$_time), substr(date('O',$_time),0,3).':'.substr(date('O',$_time),3,2));
	} else {	
		$_infoFecha = sprintf( "%d/%s/%d", date('j',$_time), date('m',$_time), date('Y',$_time));
	}
	return $_infoFecha;
}

function Dia($numero=0){
	$_diaSemana = array (
		0 => '',
		1 => 'lunes',
		2 => 'martes',
		3 => 'mi&eacute;rcoles',
		4 => 'jueves',
		5 => 'viernes',
		6 => 's&aacute;bado',
		7 => 'domingo'
	);
	return ($_diaSemana[abs($numero) % 8]);
}

function Mes($numero=0){
	$_mes = array (
		0 	=> '',
		1 	=> 'enero',
		2 	=> 'febrero',
		3 	=> 'marzo',
		4	=> 'abril',
		5 	=> 'mayo',
		6 	=> 'junio',
		7 	=> 'julio',
		8 	=> 'agosto',
		9 	=> 'septiembre',
		10 	=> 'octubre',
		11 	=> 'noviembre',
		12 	=> 'diciembre'
	);
	return ($_mes[abs($numero) % 13]);
}


/** SANEAR STRING
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 * Reemplazar la ñ, acentos, espacios y caracteres especiales
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */
function dac_sanearString($string) {
    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":"), // quité de la lista lo siguiente entre paréntecis (, ".", " ") para que no elimine los espacios vacíos.  
        '',
        $string
    );
    return $string;
}

//****************************************//
// Devuelve el valor entero de un numero  //
//****************************************//
function dac_extraer_entero($_numero) {  	
	// declaro el array para sacar el nro entero
	$arr 		= explode(".", $_numero);	
	$entero		= $arr[0];
	return $entero;
}

/******************/
/*	CONTROL CUIT  */
/******************/
function dac_validarCuit($_cuit) {	
	$_cuit_array = str_split(str_replace(array(' ', '-'), array(), $_cuit));

    if ((count($_cuit_array) < 11) OR (count($_cuit_array) > 11)) {
        // Usando lang
        // $this->form_validation->set_message('valid_cuit', $this->lang->line('form_cuit_error'));
        //$this->form_validation->set_message('valid_cuit', 'Error al validar el CUIT');
        return FALSE;
    }

    $result  = $_cuit_array[0] * 5;
    $result += $_cuit_array[1] * 4;
    $result += $_cuit_array[2] * 3;
    $result += $_cuit_array[3] * 2;
    $result += $_cuit_array[4] * 7;
    $result += $_cuit_array[5] * 6;
    $result += $_cuit_array[6] * 5;
    $result += $_cuit_array[7] * 4;
    $result += $_cuit_array[8] * 3;
    $result += $_cuit_array[9] * 2;

    $div    = intval($result / 11);
    $resto  = $result - ($div * 11);

    if ($resto == 0) {
        return ($resto == $_cuit_array[10]) ? TRUE : FALSE;
    }
    elseif ($resto == 1) {
        if (($_cuit_array[10] == 9) AND ($_cuit_array[0] == 2) AND ($_cuit_array[1] == 3)) {
            return TRUE;
        } elseif (($_cuit_array[10] == 4) AND ($_cuit_array[0] == 2) AND ($_cuit_array[1] == 3)) {
            return TRUE;
        }
    } elseif ($_cuit_array[10] == (11 - $resto)) {
        return TRUE;
    } else {
        // Usando lang
        // $this->form_validation->set_message('valid_cuit', $this->lang->line('form_cuit_error'));
        //$this->form_validation->set_message('valid_cuit', 'Error al validar el CUIT');
        return FALSE;
    }
}

function dac_corregirCuit($_cuit) {
	$_cuit_array = str_split(str_replace(array('', '-'), array(), $_cuit));
	
	$result  = $_cuit_array[0];
    $result .= $_cuit_array[1];
	$result .= '-';
    $result .= $_cuit_array[2];
    $result .= $_cuit_array[3];
    $result .= $_cuit_array[4];
    $result .= $_cuit_array[5];
    $result .= $_cuit_array[6];
    $result .= $_cuit_array[7];
    $result .= $_cuit_array[8];
    $result .= $_cuit_array[9];
	$result .= '-';
	$result .= $_cuit_array[10];
	
	return $result;
}

/********************/
//	VALIDAR ENLACE	*/
/*******************/
function dac_validarEnlace($link) {
	$pos = strpos (strtoupper(" ".$link),"HTTP://");
	if ($pos!=0) {
		$server = substr($link,7,strlen($link)-7);
	} else {
		$server = $link;
	}	
	//$ph = fsockopen($server, 80, &$errno, &$errstr, 30);		
	$ch = @curl_init($server);
	@curl_setopt($ch, CURLOPT_HEADER, TRUE);
	@curl_setopt($ch, CURLOPT_NOBODY, TRUE);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$status = array();
	preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch) , $status);
	return ($status[1] == 200);
	
	/*if(!$ph) { return false; }
	else {  return true;  }
	fclose ($ph);*/
  	
	
}

//***************************//
// Control de Montos Mínimos //
//***************************//
function dac_calcularPrecio($cantidad, $precio, $medicinal, $desc1, $desc2){
	$desc1 = (empty($desc1)) ? 0 : $desc1;
	$desc2 = (empty($desc2)) ? 0 : $desc2;
	
	$precio1	=	$cantidad * $precio;
	$precio2	=	$precio1 - ($precio1 * ($desc1/100));
	$precio3	=	$precio2 - ($precio2 * ($desc2/100));
	$iva		=	($medicinal) ? IVA : 0;
	$precioIva	=	$precio3 + ($precio3 * ($iva/100));	
	return $precioIva;
}

//--------------//
// Calcular PVP //
function dac_calcularPVP($precioLista, $iva, $medicinal, $empresa, $ganancia){
	$pvp	= floatval($precioLista)*floatval(1.450);		
	if($iva == 'N') {
		$pvp = $pvp * floatval(1.210);			
	} 
	if($medicinal == 'N'){
		$pvp = $pvp * floatval(1.210);
	}				
	if($empresa == 3){
		if($iva == 'N') {
			$pvp = $pvp * floatval(1.210);
		}
		if($medicinal == 'S'){
			$pvp = $pvp * floatval(1.210);
		}
	}		
	if($ganancia <> "0.00"){
		$porcGanancia 	= ($ganancia / 100) + 1;			
		$pvp = $pvp / $porcGanancia;
	}		
	$pvp = number_format($pvp,2,'.','');
	
	return $pvp;
}

//******************************//
//		Buscar coordenadas		//
//******************************//
function dac_urlExists( $url = NULL ) { 
    if( empty( $url ) ){
        return false;
    }
	 
    $options['http'] = array(
        'method' => "HEAD",
        'ignore_errors' => 1,
        'max_redirects' => 0
    );
	
    $body = @file_get_contents( $url, NULL, stream_context_create( $options ) );
    
    // Ver http://php.net/manual/es/reserved.variables.httpresponseheader.php
    if( isset( $http_response_header ) ) {
        sscanf( $http_response_header[0], 'HTTP/%*d.%*d %d', $httpcode ); 
        //Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
        $accepted_response = array( 200, 301, 302 );
        if( in_array( $httpcode, $accepted_response ) ) {
            return true;
        } else {
            return false;
        }
     } else {
         return false;
     }
}

function dac_getCoordinates($address){	
	$address 	= urlencode($address);
	$url 		= "https://maps.google.com/maps/api/geocode/json?key=AIzaSyDtfbXVeMTz05KI-lowid64Am2bQ2GwYB8&sensor=false&address=" . $address;
	
	if(dac_urlExists($url)){		
		$response 	= file_get_contents($url);		
		$json 		= json_decode($response,true);	
		
		if(isset($json['results'][0])){
			$lat = $json['results'][0]['geometry']['location']['lat'];
			$lng = $json['results'][0]['geometry']['location']['lng'];
			
			return array($lat, $lng);	
		} else {
			return false;
		}			
				
	} else {
		return false;
	}
}


function dac_validateMail($mail){ 	
	if(preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $mail)){
		return TRUE;
	} else {
		return FALSE;
	} 
}

//Ésta es la forma de llevar un archivo como parámetro dac_fileValidate($FILE) return $FILE["name"];
//Control Formato
function dac_fileFormatControl($type, $tipo){
	switch ($tipo) {
		case 'imagen':
			if($type=="image/jpeg" || $type=="image/pjpeg" || $type=="image/gif" || $type=="image/png" ||  $type=="application/pdf") { 
				return TRUE;
			} else { 
				return FALSE;
			}
			break;
		default:
			return FALSE;	
			break;
	}
}


function dac_fileUpload($FILE, $_destino, $filename){
	$_destino = $_SERVER['DOCUMENT_ROOT'].$_destino;
	if(file_exists($_destino) || @mkdir($_destino, 0777, true))  {		
		$origen		=	$FILE["tmp_name"];		
		$ext		=	explode(".", $FILE["name"]);
		$name		= 	$filename.".".$ext[1];
		$destino	=	$_destino.$name; //nombre del archivo	
		# movemos el archivo
		if(@move_uploaded_file($origen, $destino)) { return true; 
		} else { return false;}
	} else { return false; }
}


//***********************************//
//*	Control de duplicados en Array	*//
//***********************************//
function dac_duplicadoEnArray($array){
	if(count($array) > 1){
		$res = array_diff($array, array_diff(array_unique($array), array_diff_assoc($array, array_unique($array))));	 
		foreach(array_unique($res) as $v) {
			echo "Duplicado $v en la posicion: " .  implode(', ', array_keys($res, $v)) . '<br />';   exit;    
		}	
	}
	return false;
}

//*********************************//
//	Controla si Existe Nro Pedido  // 	Generar Pedido	 //
function dac_controlNroPedido() {
	$nroPedido	=	DataManager::dacLastId('pedido', 'pidpedido');
	$detalles	= 	DataManager::getPedidos(NULL, NULL, $nroPedido);
	if ($detalles) { 	
		foreach ($detalles as $k => $detalle) {
			$idUsr		=	$detalle['pidusr'];
			if($idUsr != $_SESSION["_usrid"]){
				dac_controlNroPedido();
				//Comento la siguiente línea ya que igual daba duplicados de pedidos web.
				//Se aplica la línea de RECURSIVIDAD. 29-01-2018
				//return DataManager::dacLastId('pedido', 'pidpedido');	
			}
		}
	} else {
		return $nroPedido;
	}
}

function dac_Normaliza($cadena){
	$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	$cadena = utf8_decode($cadena);
	$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	$cadena = strtolower($cadena);
	return $cadena;
}

//-------------------------------------------------
// Registra movimientos realizados por un usuario
//-------------------------------------------------
function dac_registrarMovimiento($movimiento, $movTipo, $movTabla, $movId=0) {
	$fieldID=	'movoperacion, movtransaccion, movorigen, movorigenid, movfecha, movusrid';
	$values	=	'"'.$movimiento.'", "'.$movTipo.'", "'.$movTabla.'", "'.$movId.'", "'.date("Y-m-d h:i:s").'", '.$_SESSION["_usrid"];
	DataManager::insertToTable('movimiento', $fieldID, $values);
}
?>
