<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="P"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}
 
$_nrofact	=	(isset($_POST['nrofact']))	? $_POST['nrofact'] : NULL;
$_ID 		=	$_SESSION["_usrid"];

$_proveedor		=	DataManager::getProveedor('provid', $_ID, $_SESSION["_usridemp"]); 
$empresa		= 	$_proveedor['0']['providempresa'];
$_nombreprov	= 	$_proveedor['0']['provnombre'];
$_telefono		=	$_proveedor['0']['provtelefono'];

$_empresas	= DataManager::getEmpresas(1); 
if (count($_empresas)) {	
	foreach ($_empresas as $k => $_emp) {
		if ($empresa == $_emp["empid"]){                       		
			$_nombreemp = $_emp["empnombre"];
		} 
	}                            
}

//*****************************************
// Consulto Email de contacto Cobranzas
//*****************************************
$_contactos	=	DataManager::getContactosPorCuenta( $_idorigen, $_origen, 1);
if (count($_contactos)) {
	foreach ($_contactos as $k => $_cont){
		$_ctoid		=	$_cont["ctoid"];																						
		$_sector	=	$_cont["ctosector"];
		
		$_sectores	= 	DataManager::getSectores(1);
		if($_sectores){ 
			foreach ($_sectores as $k => $_sect) {
				$_sectid		= $_sect['sectid'];
				$_sectnombre	= $_sect['sectnombre']; 
				if($_sect['sectnombre'] == 'Cobranzas'){ 				
					$_telefono	=	$_cont["ctotelefono"]." Int: ".$_cont["ctointerno"];		
					$_email		=	$_cont["ctocorreo"];	
				} 
			}
		}
	}
} else {
	//**************************//
	// Uso Email del usuario  	//, si no tiene, indico en el mail que hay que notificar por teléfono
	//**************************//
	$_email		=	(empty($_SESSION["_usremail"])) ?	0	:	$_SESSION["_usremail"];		
}

//*****************************************
$_SESSION['nrofact']	=	$_nrofact;
//*****************************************

if(empty($_nrofact)) {
	$_goURL = "../index.php?sms=1";
	header('Location:' . $_goURL);
	exit;		
}

//******************//
// control arhivo 	//
//******************//
$archivo_nombre		=	$_FILES["archivo"]["name"]; 
$archivo_peso		= 	$_FILES["archivo"]["size"]; 
$archivo_temporal	= 	$_FILES["archivo"]["tmp_name"];

if ($archivo_peso == 0){
	$archivo_nombre	=	"Sin archivo adjunto";
}

//*********************************//
// Armado del CORREO para el envío //
//*********************************// 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
$mail->From     =	"infoweb@neo-farma.com.ar"; //mail de solicitante ¿o de quien envía el mail?
$mail->FromName	=	"InfoWeb GEZZI";	//"Vendedor: ".$_SESSION["_usrname"];
$mail->Subject 	=	"Solicitud de Fecha de Pago para ".$_nombreemp.", guarde este email.";

if ($archivo_peso !=0) {	
	$mail->AddAttachment($archivo_temporal, $archivo_nombre);
}

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");

$_total	= '
	<html>
		<head>
			<title>Notificación de Solicitud</title>
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
								Se enviaron los datos de <strong>SOLICITUD DE FECHA DE PAGO</strong> de manera satisfactoria.<br />
								Recuerde que podr&aacute; solicitar su fecha de pago pasados cinco d&iacute;as h&aacute;biles de entregada la factura. &Eacute;sta se tramitar&aacute; de Lunes a Miercoles de 09:30 a 12:00 hs.<br />				
							<div />
						</td >
					</tr> 
					
					<tr bgcolor="#597D92"> 
						<td>
							<div class="texto" style="color:#FFFFFF; font-size:14; font-weight: bold;">
								<strong>Estos son sus datos de solicitud</strong>
							</div>
						</td> 
					</tr> 
					
					<tr> 
						<td height="95" valign="top">
							<div class="texto">						
								<table width="600px" style="border:1px solid #597D92">
									<tr>
										<th rowspan="2" align="left" width="100">	
											Proveedor: 	<br />		
											Nro. de Factura:<br />
											Correo:<br />
											Tel&eacute;fono:<br />
											Archivo: 
										</th>	
										<th rowspan="2" align="left" width="250">
											'.$_nombreprov.'<br />
											'.$_nrofact.'<br />
											'.$_email.'<br />
											'.$_telefono.'<br />
											'.$archivo_nombre.'	
										</th>
									</tr>
								</table>
							</div>
						</td> 
					</tr>
					
					<tr> 
						<td>
							<div class="texto">
								<font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif">
									Por cualquier consulta, p&oacute;ngase en contacto con la empresa al 4555-3366 de Lunes a Viernes de 10 a 17 horas.
								</font>
																				
								<div style="color:#000000; font-size:12px;"> 
									<strong>Le enviaremos un email o tel&eacute;fono notificando la fecha de pago, al contacto de su cuenta de usuario.</strong></a></br></br>
								</div>		
							</div>
						</td> 
					</tr>
					<tr align="center" class="saludo">
						<td valign="top">
							<div class="saludo">
								Gracias por confiar en '.$_nombreemp.'<br/>
								Le Saludamos atentamente,
							</div>
						</td>
					</tr>
					
					<tr>
						<td valign="top">'.$pie.'</td>					
					</tr>
				</table>
			<div />
		</body>
';

$mail->msgHTML($_total);
$mail->AddAddress($_email, 'InfoWeb GEZZI');
$mail->AddBCC("infoweb@neo-farma.com.ar", 'InfoWeb GEZZI');
$mail->AddBCC("pagoproveedores@neo-farma.com.ar");

//*********************************//
if(!$mail->Send()) {
	//Si el envío falla, que mande otro mail indicando que la solicittud no fue correctamente enviada?=?
	//echo 'Fallo en el envío';
	$_goURL = "../index.php?sms=5";
	header('Location:' . $_goURL);
	exit;
}

//**********************************//
//	Envío el archivo  al servidor	//
//**********************************//
if ($archivo_peso != 0){
	if($archivo_peso > (2048*2048)) {
		$_goURL = "../index.php?sms=2";
		header('Location:' . $_goURL);
		exit;		
	} else {	
		//******************//
		// datos del arhivo //
		//******************//
		# definimos la carpeta destino
		$_destino	=	"../archivos/proveedor/".$_ID."/";
		if($_FILES["archivo"]["type"]=="image/jpeg" || $_FILES["archivo"]["type"]=="image/pjpeg" || $_FILES["archivo"]["type"]=="image/gif" || $_FILES["archivo"]["type"]=="image/png" ||  $_FILES["archivo"]["type"]=="application/pdf") {	
			# Si exsite la carpeta o se ha creado
			if(file_exists($_destino) || @mkdir($_destino, 0777, true))  {
				$origen		=	$archivo_temporal;
				
				$info 		= 	new SplFileInfo($archivo_nombre);
				$destino 	= 	$_destino.'documentoF'.date("dmYHms").".".$info->getExtension(); //nombre del archivo
	
				# movemos el archivo
				if(@move_uploaded_file($archivo_temporal, $destino)) {
					//echo "<br>".$_FILES["archivo".$i]["name"]." movido correctamente";
				}else{
					//echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo".$i]["name"];
					$_goURL = "../index.php?sms=4";
					header('Location:' . $_goURL);
					exit;
				}
			}else {
				//echo "<br>No se ha podido crear la carpeta: up/".$user;
				$_goURL = "../index.php?sms=4";
				header('Location:' . $_goURL);
				exit;
			}
		} else {
			//el archivo debe ser pdf o imagen
			$_goURL = "../index.php?sms=3";
			header('Location:' . $_goURL);
			exit;
		}
	}
} 

/****************************************/
unset($_SESSION['nrofact']);
/****************************************/

$_goURL = "../index.php?sms=6"; 
header('Location:'.$_goURL);
exit;
 
?>
