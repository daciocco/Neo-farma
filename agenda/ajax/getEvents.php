<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

//--------------------------------------------------------------------------------------------//
// This script reads event data from a JSON file and outputs those events which are within the range//
// supplied by the "start" and "end" GET parameters.
//
// An optional "timezone" GET parameter will force all ISO8601 date stings to a given timezone.
//
// Requires PHP 5.2.0 or higher.
//--------------------------------------------------------------------------------------------------//
// Require our Event class and datetime utilities
//require dirname(__FILE__) . '/utils.php';

date_default_timezone_set('UTC');

// Short-circuit if the client did not give us a date range.
/*
No se cómo pasar las variables desde y hasta para hacer dinámico por mes (o años!)
if (!isset($_GET['start']) || !isset($_GET['end'])) {
	die("Please provide a date range."); exit;
}
$range_start	=	date('Y-m-d h:m', $_GET['start']);
$range_end		=	date('Y-m-d h:m', $_GET['end']);

$range_start	=	'2016-12-03 00:00';
$range_end		=	'2017-01-19 00:00';*/

// Analiza el parámetro de zona horaria si está presente.
/*$timezone = NULL;
if (isset($_GET['timezone'])) {
	$timezone = new DateTimeZone($_GET['timezone']);
}*/

//ACÁ DEBO LEER LA BASE DE DATOS Select all from ssbb where fecha BETWEEN fecha inicio AND fecha Fin
$eventos	=	DataManager::getEventos($_SESSION["_usrid"]);  //, $range_start, $range_end
if ($eventos){		
	$input_arrays = array();	
	foreach ($eventos as $k => $even) {
		//Si el EVENTO solo tiene START, es un evento de todo un día.	
		$ideven			=	$even["agid"]; //"id": "999",
		$title 			=	$even["agtitle"]; //"title": "Long Event",
		$texto 			=	$even["agtexto"]; 
		$url 			=	$even["agurl"]; 
		$start			= 	$even["agstartdate"]; //"start": "2016-12-07", //"start": "2016-12-09T16:00:00-05:00"
		$end			= 	$even["agenddate"];  //"end": "2016-12-10", //
		$color			= 	$even["agcolor"];
		$restringido	= 	$even["agrestringido"]; //(empty($even["agrestringido"]))	? NULL : "Restringido";
		
		//$tipo	= 	$even["agrectype"];
		//"url": "http://google.com/"
		
		//EVENTO ALL DAY --> Cuando FECHA INICIO O FIN no tiene horas seteadas,
		//les quito esa parte del string para que reconozca como EVENTO ALL DAY
		if(substr($start, 11, 18) == '00:00:00'){
			$start = substr($start, 0, 10);
		}		
		if(substr($end, 11, 18) == '00:00:00'){
			$end = substr($end, 0, 10);
		}
		
		//****************//
		
		//Si la fecha de inicio o fin no tienen hora, se supone que es un evento de TODO EL DIA
		if(strlen($start) == 10 || strlen($end) == 10){
			//ALL DAY --> CUANDO NO TIENE HORA:MINUTO
			if($end == "0000-00-00"){
				//ALL DAY para un solo día
				$array = array(
					"id" 		=> $ideven,
					"title" 	=> $title,
					"texto" 	=> $texto,
					"url" 		=> $url,
					"start" 	=> $start,
					"color" 	=> $color,
					"constraint" => $restringido,
					//Un evento de TODO EL DIA implica no tener fecha de FIN, para que funcione, no debe cargarse en el array
					//if(!empty($end)) {"end" 	=> $end,} // substr($end, 0, 10)
				);		
			} else {
				//ALL DAY desde INICIO hasta un día antes del FIN (por que sería HASTA las 00:01 del último día)
				$array = array(
					"id" 	=> $ideven,
					"title" => $title,
					"texto" => $texto,
					"url" 	=> $url,
					"start" => $start,
					"end" 	=> $end,
					"color" => $color,
					"constraint" => $restringido,
				);
			}
		} else {			
			$array = array(
				"id" 	=> $ideven,
				"title" => $title,
				"texto" => $texto,
				"url" 	=> $url,
				"start" => $start,
				"end" 	=> $end,
				"color" => $color,
				"constraint" => $restringido,
			);
		}
		
		//Cargo el array de eventos para la Agenda
		array_push($input_arrays, $array);
	}
	
	//CODIGO PARA PRUEBAS
	/*$array = array(
			"id" => 999,
			"title"	=> 'All Day Event',
			"start" => '2017-01-07',
			"end" => '2017-01-10',
			//"color"	=> '#ff9f89', //COLOR DEL EVENTO
			//"backgroundColor" => '#ff9f89', //COLOR DE FONDO DE ¿TEXTO? del EVENTO, que es casi lo mismo que color
			//borderColor
			//textColor
			//className
			//editable
			//startEditable			
			//start, end, callback
			//--------------						
			//title/allDay/start/end/url
					
		);
	array_push($input_arrays, $array);*/
	
	//*******************
	
}

// Send JSON to the client.
echo json_encode($input_arrays);

/*events: [			
	//PAra que el evento sea ALL DAY NO DEBE TENER HORAS Y MINUTOS
	{
		id: 1,
		title: 'All Day Event',
		start: '2016-12-01'
	},

	{
		id: 2,
		title: 'Long Event',
		start: '2016-12-07',
		end: '2016-12-10'
	},
	
	//Si no se carga horario, será todo el día.
	//Si carga horas, tendrá también hora de inicio y fin
	{					
		title: 'Conference',
		start: '2016-12-11',
		end: '2016-12-13'
	},
	
	//Evento repetido sería si se repite el id!!!
	//Este ejemplo no se va a utilizar ya que en ddbb Agenda no permite repetir ID
	{
		id: 999,
		title: 'Repeating Event',
		start: '2016-12-09T16:00:00',
	},
	{
		id: 999,
		title: 'Repeating Event',
		start: '2016-12-16T16:00:00'
	},
	
					
	// rendering: AREA MARCADA EN COLOR DE FONDO, NO DEL EVENTO 
	// overlap: NO SE PUEDEN ARRASTRAR EVENTOS (aunque sí crearlos)
	// Si se realiza con hora y minuto, no se verá en el mensual, sino que solo donde corresponda
	{
		start: '2016-12-24',
		end: '2016-12-28',
		overlap: false, //No permite que otros evento, se coloquen en el día donde está éste
		rendering: 'background', //Hace que todo el día tenga color de fonto "color" //Sin ésto, el color de fondo irá al evento solamente.		
		color: '#ff9f89'
	},
	
	
	//constraint: NO PERMITE MOVERLO DE FECHA Y HORA
	//NULL si se quiere que no se tenga en cuenta d
	{
		title: 'Meeting',
		start: '2016-12-12T10:30:00',
		end: '2016-12-12T12:30:00',
		//restricción para una reunión. 
		//ESTO PODRÍA USARSE TAMBIÉN COMO FERIADOS??
		constraint: 'availableForMeeting', // defined below
		color: '#257e4a'
	},
	
	// El evento linkéa a la página que se indicó
	{
		title: 'Click for Google',
		url: 'http://google.com/',
		start: '2016-12-28'
		
	}
],*/


