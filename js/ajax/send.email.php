<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

//*************************************************
$empresa	=	(isset($_POST['idemp']))		? $_POST['idemp']		:	NULL;
$from		=	(isset($_SESSION["_usremail"]))	? $_SESSION["_usremail"]:	"info@neo-farma.com.ar"; //mail del usuario
$fromName	=	(isset($_SESSION["_usremail"]))	? $_SESSION["_usremail"]:	"infoWeb"; //nombre del usuario
$email		=	(isset($_POST['email']))		? $_POST['email']		:	NULL;
$asunto		=	(isset($_POST['asunto']))		? $_POST['asunto']		:	'Sin asunto';
$mensaje	=	(isset($_POST['mensaje']))		? $_POST['mensaje']		:	NULL;
//*************************************************

if(empty($empresa)){
	echo "No se registró desde que empresa está haciendo el envío del corréo"; exit;
}

$from = trim($from, ' ');
if (!dac_validateMail($from)) {
	echo "El corréo del usuario es incorrecto para enviar emails"; exit;
}

$email = trim($email, ' ');
if (!dac_validateMail($email)) {
	echo "El corréo del destinatario es incorrecto"; exit;
}

if(empty($mensaje)){
	echo "Debe escribir algún mensaje"; exit;
}

//******************//	
//	Envío de Email	//
//******************//
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
$mail->From     =	$from; 
$mail->FromName	=	$fromName; //"InfoWeb GEZZI";	
$mail->Subject 	=	$asunto;

/*****************************/
//Manejo de Multifile Via Ajax
/*****************************/
if ($_FILES['multifile']){
	foreach($_FILES['multifile']['name'] as $key => $name){
		//Si el archivo se paso correctamente
		if($_FILES['multifile']['error'][$key] == UPLOAD_ERR_OK){ 
			$original	=	$_FILES['multifile']['name'][$key];
			$temporal 	= 	$_FILES['multifile']['tmp_name'][$key];
			
			if(filesize($temporal) > ((1024 * 1024) * 4)){ //(1MG == 1024KB && 1KB == 1024) ==> 1MB == 1048576 Bytes
				echo '<b>'.$original.'</b> no debe superar los 4 MB. <br>'; exit;	
			}			
			$mail->AddAttachment($temporal, $original); //($destino);
		} 
		
		if($_FILES['multifile']['error'][$key] != UPLOAD_ERR_OK && $_FILES['multifile']['error'][$key] != UPLOAD_ERR_NO_FILE){
			echo 'Error '.$_FILES['multifile']['error'][$key].' al subir el archivo <b>'.$original.'</b>'; exit;
		}
	}	
} else {
	echo '</b> Error al intentar cargar archivos. <br> Recuerde que no se pueden enviar más de 8MB totales en adjuntos'; exit; //Una opción es que esté intentando superar un archivo con 8 MB 
}
/*****************************/

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
	
$_total	= '
	<html>
		<head>
			<title>Email de Empresa</title>
			<style type="text/css"> 
				body {
					text-align: center;
				}
				.texto {
					float: left;
					height: auto;
					width: 90%;
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
					margin-top: 10px;
					margin-right: 10px;
					margin-bottom: 10px;
					margin-left: 0px;
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
		</head>
		<body>	
			<div align="center">
				<table width="600" border="0" cellspacing="1"> 
					<tr>
						<td>'.$cabecera.'</td>
					</tr>
					<tr>
						<td> 
							<div class="texto">
								'.$mensaje.'							
							<div />
						</td >
					</tr>
					
					<tr> 
						<td>
							<div class="texto">
								<font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif" style="font-size:12px">
									Por cualquier consulta, p&oacute;ngase en contacto con la empresa al 4555-3366 de Lunes a Viernes de 10 a 17 horas.
								</font>
							</div>
						</td> 
					</tr>
					<tr align="center" class="saludo">
						<td valign="top">
							<div class="saludo" align="left">
								Gracias por confiar en nosotros.<br/>
								Le saludamos atentamente,
							</div>
						</td>
					</tr>
					
					<tr>
						<td valign="top">'.$pie.'</td>					
					</tr>
				</table>
			<div/>
		</body>
';

$mail->msgHTML($_total);
$mail->AddAddress($email); //El PARA $para, saldrá en todos los destino BCC
$mail->AddBCC("infoweb@neo-farma.com.ar"); 

//*********************************//
if(!$mail->Send()) {
	echo "Hubo un error al intentar enviar el correo"; exit;
}	
//**************************//

echo 1;
?>