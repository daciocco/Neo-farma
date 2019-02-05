<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	echo "SU SESIÓN HA EXPIRADO."; exit;
}

$tkid		=	empty($_POST['tkid']) ? 0 	: $_POST['tkid'];
$idSector	=	empty($_POST['tkidsector']) ? 0 	: $_POST['tkidsector'];
$idMotivo	=	empty($_POST['tkidmotivo']) ? "" 	: $_POST['tkidmotivo'];
$mensaje	=	empty($_POST['tkmensaje']) 	? "" 	: $_POST['tkmensaje'];
$estado		=	empty($_POST['tkestado']) 	? "" 	: $_POST['tkestado'];
$usrCreated	=	empty($_POST['usrCreated']) ? "" 	: $_POST['usrCreated'];

//$correo		=	empty($_POST['tkcopia']) 	? "" 	: $_POST['tkcopia'];
  
if (empty($idSector)) {
	echo "Error al seleccionar un motivo de consulta."; exit;
}

if (empty($idMotivo)) {
	echo "Seleccione un motivo de servicio."; exit;
} 

if (empty($mensaje)) {
	echo "Indique un mensaje."; exit;
}

$imagenNombre	=	$_FILES["imagen"]["name"]; 
$imagenPeso		= 	$_FILES["imagen"]["size"]; 
if ($imagenPeso != 0){
	if($imagenPeso > MAX_FILE){ 
		echo "El archivo no debe superar los 4 MB"; exit;
	}
}

if ($imagenPeso	 != 0){
	if(dac_fileFormatControl($_FILES["imagen"]["type"], 'imagen')){
		$ext	=	explode(".", $imagenNombre);
		$name	= 	dac_sanearString($ext[0]);	
	} else {
		echo "La imagen debe ser .JPG o .PDF"; exit;		
	}		
}

$objectMsg	= DataManager::newObjectOfClass('TTicketMensaje');
$objectMsg->__set('IDTicket'	, $tkid);
$objectMsg->__set('Descripcion'	, $mensaje);
$objectMsg->__set('UsrCreated'	, $_SESSION["_usrid"]);
$objectMsg->__set('DateCreated'	, date("Y-m-d H:m:s"));
$objectMsg->__set('Activo'		, 1);
$objectMsg->__set('ID'			, $objectMsg->__newID());
$IDMsg = DataManager::insertSimpleObject($objectMsg);

if($usrCreated == $_SESSION["_usrid"]){
	//Si es el mismo que lo creó, se envía un mail al responsable del sector
	switch($estado){
		case 0: //RESPONDIDO
			$estado = 1;
			break;
		case 1: //ACTIVO
			$estado = 1;
			break;
	}
} else {
	//Si es distinto, se envía un mail al creador
	switch($estado){
		case 1: //ACTIVO
			$estado = 2;
			break;
		case 0: //RESPONDIDO
			$estado = 2;
			break;
	}
}


$objectTicket	= DataManager::newObjectOfClass('TTicket', $tkid);
$objectTicket->__set('Estado'		, $estado); //1 ACTIVO //0 RESPONDIDO
$objectTicket->__set('UsrUpdate'	, $_SESSION["_usrid"]);
$objectTicket->__set('LastUpdate'	, date("Y-m-d H:m:s"));
$IDTicket = DataManager::updateSimpleObject($objectTicket);


//**********//
//	CORREO	//
//**********//
//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");

require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );


$sectores	=	DataManager::getTicketSector();
foreach( $sectores as $k => $sec ) {	
	$id		= $sec['tksid'];
	if($id == $idSector){	
		$sector = $sec['tksnombre']; 
	}
}

$headMail 		= '
		<html>
			<head>
				<title>CONSULTA Nro. '.$tkid.'</title>
				<style type="text/css"> 
					body {
						text-align: center;
					}
					.texto {
						float: left;
						height: auto;
						width: 580px;
						padding: 10px;
						font-family: Arial, Helvetica, sans-serif;
						text-align: left;
						border-top-width: 0px;
						border-bottom-width: 0px;
						border-top-style: none;
						border-right-style: none;
						border-left-style: none;
						border-right-width: 0px;
						border-left-width: 0px;
						border-bottom-style: none;
					}
					.saludo {
						width: 350px;
						float: none;
						margin-right: 0px;
						margin-left: 25px;
						border-bottom-width: 1px;
						border-bottom-style: solid;
						border-bottom-color: #409FCB;
						height: 35px;
						font-family: Arial, Helvetica, sans-serif;
						padding: 0px;
						text-align: left;
						margin-top: 15px;
						font-size: 12px;

				</style>
			</head>';
$pieMail 		= '	
						<tr align="center" class="saludo">
							<td valign="top">
								<div class="saludo">
									Gracias por confiar en nosotros.<br/>
									Le saludamos atentamente,
								</div>
							</td>
						</tr>

						<tr>
							<td valign="top">' . $pie . '</td>					
						</tr>
					</table>
				<div/>
			</body>	
		</html>	
		';	

if($usrCreated != $_SESSION["_usrid"]){
	//*******************
	$mail->From     = "infoweb@neo-farma.com.ar";
	$mail->FromName	= "Soporte Neo-farma";
	$mail->Subject 	= "Respuesta a tu consulta Nro: ". $tkid;	
			
	$cuerpoMail_1 	= '
			<body>
				<div align="center">
					<table width="580" border="0" cellspacing="1"> 
						<tr>
							<td>'.$cabecera.'</td>
						</tr>
						<tr>
							<td> 
								<div class="texto">
									Nuestro equipo de soporte respondi&oacute; a tu consulta Nro. '.$tkid.'<br/><br/>		
								<div />
							</td >
						</tr> 

						<tr> 
							<td>
								<div class="texto" style="color:#FFFFFF; font-size:14; font-weight:bold; background-color:#117db6">
									<strong>Detalles:</strong>
								</div>
							</td> 
						</tr> 
			';	
	
	$cuerpoMail_2 	= '	
						<tr>
							<td valign="top">
								<div class="texto">						
									<table width="580px" style="border:1px solid #117db6">
										<tr>
											<th align="left" width="200"> Sector </th>	
											<td align="left" width="400"> '.$sector.' </td>
										</tr>                                      
										<tr>
											<th align="left" width="200"> Detalles </th>	
											<td align="left" width="400"> '.$mensaje.' </td>
										</tr>
									</table>
								</div>
							</td> 
						</tr>
				';	
	
	$usrEmail = DataManager::getUsuario('uemail', $usrCreated);
	$mail->AddAddress($usrEmail);
	
	
} else {
	//*******************
	$mail->From     = "infoweb@neo-farma.com.ar";
	$mail->FromName	= "Consulta a Soporte Neo-farma";
	$mail->Subject 	= "Tiene la consulta Nro: " . $tkid . "pendiente.";	
	
	$cuerpoMail_1 	= '
			<body>
				<div align="center">
					<table width="580" border="0" cellspacing="1"> 
						<tr>
							<td>'.$cabecera.'</td>
						</tr>
						<tr>
							<td> 
								<div class="texto">
									Nueva consulta Nro. '.$tkid.'<br/><br/>		
								<div />
							</td >
						</tr> 

						<tr> 
							<td>
								<div class="texto" style="color:#FFFFFF; font-size:14; font-weight:bold; background-color:#117db6">
									<strong>Detalles:</strong>
								</div>
							</td> 
						</tr> 
			';
	
	$cuerpoMail_2 	= '	
						<tr>
							<td valign="top">
								<div class="texto">						
									<table width="580px" style="border:1px solid #117db6">
										<tr>
											<th align="left" width="200"> Sector </th>	
											<td align="left" width="400"> '.$sector.' </td>
										</tr> 
									</table>
								</div>
							</td> 
						</tr>
				';	
	
	//email al responsable del sector
	$motivos	= DataManager::getTicketMotivos(); 
	if (count($motivos)) {
		foreach ($motivos as $k => $mot) {
			$id				= $mot['tkmotid'];
			$usrResponsable	= $mot['tkmotusrresponsable'];
			
			if($id == $idMotivo){	
				$usrEmail = DataManager::getUsuario('uemail', $usrResponsable);
				$mail->AddAddress($usrEmail);
			}
		}
	}
	
}

$cuerpoMail = $headMail.$cuerpoMail_1.$cuerpoMail_2.$pieMail;	
$mail->msgHTML($cuerpoMail);
$mail->AddBCC("infoweb@neo-farma.com.ar");

if($mail){
	if(!$mail->Send()) {
		echo 'Fallo en el env&iacute;o de notificaci&oacute;n por mail'; exit;
	}
}
	
echo "1"; exit;
 
?>