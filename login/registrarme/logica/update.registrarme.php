<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/detect.Browser.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php");

//---------------------------------------------
/*Forma de ller todo el form sin llamar por sus normbres*/
/*foreach ($_POST as $key => $value) {
  echo '<p><strong>' . $key.':</strong> '.$value.'</p>';
}*/
$empresa		=	(isset($_POST['empselect']))	? 	$_POST['empselect'] 	: NULL;
$_razonsocial	=	(isset($_POST['razonsocial']))	? 	$_POST['razonsocial'] 	: NULL;
$_provincia		=	(isset($_POST['provincia']))	? 	$_POST['provincia']		: NULL;
$_localidad		=	(isset($_POST['localidad']))	? 	$_POST['localidad'] 	: NULL;
$_direccion		=	(isset($_POST['direccion']))	? 	$_POST['direccion'] 	: NULL;
$_codpostal		=	(isset($_POST['codpostal']))	? 	$_POST['codpostal'] 	: NULL;
$_cuit			=	(isset($_POST['cuit']))			? 	$_POST['cuit'] 			: NULL;
$_nroIBB		=	(isset($_POST['nroIBB']))		? 	$_POST['nroIBB'] 		: NULL;
$_telefono		=	(isset($_POST['telefono']))		? 	$_POST['telefono'] 		: NULL;
$_usuario		=	(isset($_POST['usuario']))		? 	$_POST['usuario'] 		: NULL;
$_email			=	(isset($_POST['email']))		? 	$_POST['email'] 		: NULL;
$_emailconfirm	=	(isset($_POST['emailconfirm']))	? 	$_POST['emailconfirm'] 	: NULL;
$_clave			=	(isset($_POST['clave']))		? 	$_POST['clave'] 		: NULL;
$_web			=	(isset($_POST['web']))			? 	$_POST['web'] 			: NULL;
$_comentario	=	(isset($_POST['comentario']))	?	$_POST['comentario']	: NULL;
//$_valor_captcha	=	(isset($_POST['g-recaptcha-response']))	?	$_POST['g-recaptcha-response']	: NULL;

$_empresas	= DataManager::getEmpresas(1); 
if (count($_empresas)) {	
	foreach ($_empresas as $k => $_emp) {
		$_idemp		=	$_emp["empid"];
		if ($_idemp == $empresa){                        		
			$_nombreemp	=	$_emp["empnombre"];
		} 
	}                            
}
//**********************************************************
$_SESSION['razonsocial']	=    $_razonsocial;
$_SESSION['provincia']		=    $_provincia;
$_SESSION['localidad']		=    $_localidad;
$_SESSION['direccion']		=    $_direccion;
$_SESSION['codpostal']		=    $_codpostal;
$_SESSION['cuit'] 			=	 $_cuit;
$_SESSION['nroIBB'] 		=	 $_nroIBB;
$_SESSION['usuario'] 		=	 $_usuario;
$_SESSION['telefono'] 		=	 $_telefono; 
$_SESSION['email']			=	 $_email;
$_SESSION['emailconfirm']	= 	 $_emailconfirm;
$_SESSION['clave'] 			= 	 $_clave;
$_SESSION['web'] 			= 	 $_web;
$_SESSION['comentario'] 	=	 $_comentario;
//**********************************************************
if (empty($_razonsocial)) {
	$_goURL = "../index.php?sms=1"; 
	header('Location:' . $_goURL); exit;
}

if (!dac_validarCuit($_cuit)) { 
	$_goURL = "../index.php?sms=2";
	header('Location:' . $_goURL); exit;
}
$_cuit = dac_corregirCuit($_cuit);

if (empty($_nroIBB)) {
	$_goURL = "../index.php?sms=24";
	header('Location:' . $_goURL); exit;
}


if ($_provincia == 0) {	
	$_goURL = "../index.php?sms=13";
	header('Location:' . $_goURL); exit;
} else {
	$_provincias	= DataManager::getProvincias(); 
	if (count($_provincias)) {	
		foreach ($_provincias as $k => $_prov) {
			$_provid		=	$_prov["provid"];
			$_provnombre	= 	$_prov["provnombre"];						
			if ($_provincia == $_provid){
				$_provincia = $_provnombre;
			} 
		}                  
	}
}

if ($_localidad  == "Seleccione Localidad...") {
	$_goURL = "../index.php?sms=14";
	header('Location:' . $_goURL); exit;
}

if (empty($_direccion)) {
	$_goURL = "../index.php?sms=16";
	header('Location:' . $_goURL); exit;
}

if (!empty($_codpostal) && !is_numeric($_codpostal)) {
	$_goURL = "../index.php?sms=15";
	header('Location:' . $_goURL); exit;
} else {
	$_codpostal = 0;
}

if (empty($_telefono)) {
	$_goURL = "../index.php?sms=3";
	header('Location:' . $_goURL); exit;
}
//---------------------------------------------
//	Comprobamos si el nombre de usuario y/o la cuenta de correo ya existían 	(	$checkusuario	y	$email_exist	)
//	AGREGAR UN CONTROL DE EN QUÉ FORMA SE QUIERE VALIDAD COMO ES UN NOMBRE DE USUARIO
if (!empty($_usuario)){	
	if(!ctype_alnum($_usuario)){ //el usuario solo sea alfanumérico
		$_goURL = "../index.php?sms=18";
		header('Location:' . $_goURL); exit;
	}	
	$_ID	=	DataManager::getIDByField('TUsuario', 'ulogin', $_usuario);
	$_ID2	=	DataManager::getIDByField('TProveedor', 'provlogin', $_usuario);
	if ($_ID || $_ID2){ //El usuario ya existe
		$_goURL = "../index.php?sms=4";
		header('Location:' . $_goURL); exit;
	}
} else { //El usuario ya existe
	$_goURL = "../index.php?sms=12";
	header('Location:' . $_goURL); exit;
}

$_email	=	trim($_email, ' ');
if (!preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $_email )) {
	$_goURL = "../index.php?sms=6";
	header('Location:' . $_goURL); exit;
}

$_emailconfirm	=	trim($_emailconfirm, ' ');
if (strcmp ($_email , $_emailconfirm ) != 0){
	$_goURL = "../index.php?sms=7";
	header('Location:' . $_goURL); exit;
}

if (empty($_clave)) {	
	$_goURL = "../index.php?sms=8";
	header('Location:' . $_goURL); exit;
}

//---------------------------------------------
//Se declaran las variables de la dirección de los archivos enviados a éste PHP
//---------------------------------------------
$_cant_files 	= 4;
$_maximo 		= 1024 * 1024 * 4;
$cont = 0;

for($i = 1; $i <= $_cant_files; $i++){
	   
	$archivo_nombre[$i]		=	dac_Normaliza($_FILES["archivo".$i]["name"]);
	$archivo_peso[$i]		= 	$_FILES["archivo".$i]["size"]; 
	$archivo_temporal[$i]	= 	$_FILES["archivo".$i]["tmp_name"];
	
	if($archivo_peso[$i] != 0){
		$cont++;
		if($archivo_peso[$i] > $_maximo){			
			$_goURL = "../index.php?sms=11";
			header('Location:' . $_goURL); exit;
		}
	}
}

if ($cont < 1){
	$_goURL = "../index.php?sms=10";
	header('Location:' . $_goURL); exit;
}

//Controlo las extensiones de los archivos cargados que sean PDF o IMAGEN
for($i = 1; $i <= $_cant_files; $i++){
    # Si hay algun archivo que subir
	# Recorremos todos los arhivos que se han subido
    if($archivo_nombre[$i]){
		# Si es un formato de imagen/pdf
		if($_FILES["archivo".$i]["type"]!="image/jpeg" && $_FILES["archivo".$i]["type"]!="image/pjpeg" && $_FILES["archivo".$i]["type"]!="image/gif" && $_FILES["archivo".$i]["type"]!="image/png" &&  $_FILES["archivo".$i]["type"]!="application/pdf") {
			//Uno de los archivos no es imagen o pdf
			//echo "<br>".$_FILES["archivo".$i]["name"]." - NO es imagen jpg o archivo pdf";
			$_goURL = "../index.php?sms=22";
			header('Location:' . $_goURL);
			exit;
		}
    }
}

//controlo el captcha 
/*
if (!dac_enviarDatosCaptcha($_valor_captcha)){
	$_goURL = "../index.php?sms=20";
	header('Location:' . $_goURL);
	exit;
}*/

//---------------------------------------------
//agregamos la variable $_activate que es un numero aleatorio de  
//20 digitos crado con la funcion genera_random de mas arriba
//para proveedores no la utilizao, ya que el alta la tienen que aprobar una vez hayan visto la documentación desde la web interna.
//$_activate = dac_generarRandom(20);

//aqui es donde insertamos los nuevos valosres en la BD  activate y el estado --> valor 1 que es desactivado      
//en el caso de proveedores coloco el campo activo en 3 
//---------------------------------------------
$_provobject	=	DataManager::newObjectOfClass('TProveedor');
$_provobject->__set('ID', 			$_provobject->__newID());
$_provobject->__set('Empresa', 		$empresa);
$_provobject->__set('Proveedor', 	'0'); 
$_provobject->__set('Nombre', 		$_razonsocial);
$_provobject->__set('Login', 		$_usuario);
$_provobject->__set('Clave', 		md5($_clave));
$_provobject->__set('Direccion', 	$_direccion);
$_provobject->__set('Provincia', 	$_provid); 	
$_provobject->__set('Localidad', 	$_localidad);		
$_provobject->__set('CP', 			$_codpostal);
$_provobject->__set('Cuit', 		$_cuit);
$_provobject->__set('NroIBB', 		$_nroIBB);
$_provobject->__set('Email', 		$_email);
$_provobject->__set('Web', 			$_web);
$_provobject->__set('Telefono', 	$_telefono);
$_provobject->__set('Observacion', 	$_comentario); 
$_provobject->__set('Activo', 		3);  
//El 3 es para que no salga en listado de activos ni inactivos, 
//y que en vez de crear nuevos proveedores con el +, 
//solo se pueda dar de alta con éstos clientes ya modificados desde un apartado diferente
//***************************************//
$ID = DataManager::insertSimpleObject($_provobject);
//Control si se grabaron los datos de solicitud
if(!$ID){
	//Error al registrar en la tabla proveedores
	$_goURL = "../index.php?sms=20";
	header('Location:' . $_goURL); exit;
}

//antes que insertar los datos, intento insertar la documentación
# definimos la carpeta destino
$_destino	=	"../archivos/proveedor/".$ID."/";
for($i = 1; $i <= $_cant_files; $i++){
    # Si hay algun archivo que subir # Recorremos todos los arhivos que se han subido
    if($archivo_nombre[$i]){ //$_FILES["archivo".$i]["name"]
		# Si es un formato de imagen/pdf
		if($_FILES["archivo".$i]["type"]=="image/jpeg" || $_FILES["archivo".$i]["type"]=="image/pjpeg" || $_FILES["archivo".$i]["type"]=="image/gif" || $_FILES["archivo".$i]["type"]=="image/png" ||  $_FILES["archivo".$i]["type"]=="application/pdf") {	
			# Si exsite la carpeta o se ha creado
			if(file_exists($_destino) || @mkdir($_destino, 0777, true))  {
				$info 		= 	new SplFileInfo($archivo_nombre[$i]);
				
				$origen		=	$archivo_temporal[$i]; //$_FILES["archivo".$i]["tmp_name"];
				$destino 	= 	$_destino.'documento'.$i.'F'.date("dmY").".".$info->getExtension(); //$_FILES["archivo".$i]["name"]
				
				if(@move_uploaded_file($origen, $destino)) {
					//echo "<br>".$_FILES["archivo".$i]["name"]." movido correctamente";
				}else{
					//echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo".$i]["name"];
					$_goURL = "../index.php?sms=23";
					header('Location:' . $_goURL); exit;
				}
			} else {
				//echo "<br>No se ha podido crear la carpeta: up/".$user;
				$_goURL = "../index.php?sms=23";
				header('Location:' . $_goURL); exit;
			}
		}else{
			//Uno de los archivos no es imagen o pdf
			$_goURL = "../index.php?sms=22";
			header('Location:' . $_goURL); exit;
		}
    }
}
                 
//**************************************************
// Armado del CORREO para el envío
//************************************************** 
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
$mail->From     =	$_email;
$mail->FromName	=	"InfoWeb GEZZI";
$mail->Subject 	=	" Datos de registro de ".$_usuario." en ".$_nombreemp.", guarde este email.";

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
								Se enviaron los datos de <strong>SOLICITUD DE ACTIVACI&Oacute;N</strong> en '.$_nombreemp.' como <b>'.$_usuario.' </b>de manera satisfactoria.<br /> ';
								/*Le enviaremos ahora un email para activar su cuenta, al correo que nos facilito.<br />*/
			$_total	.=' 
							<div />
						</td >
					</tr> 
					
					<tr bgcolor="#597D92"> 
						<td>
							<div class="texto" style="color:#FFFFFF; font-size:14; font-weight: bold;">
								<strong>Estos son sus datos de registro, '.$_usuario.'</strong>
							</div>
						</td> 
					</tr> 
					
					<tr> 
						<td height="95" valign="top">
							<div class="texto">						
								<table width="600px" style="border:1px solid #597D92">
									<tr>
										<th rowspan="2" align="left" width="100">
											Nombre:<br />				
											Usuario:<br />
											Correo:
										</th>	
										<th rowspan="2" align="left" width="250">
											'.$_razonsocial.'<br />
											'.$_usuario.'<br />
											'.$_email.'	
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
									<strong>SE LE NOTIFICARA LA ACTIVACION DE SU CUENTA POR EL CORREO FACILITADO, cuando se hayan verificado y aceptado los datos de la solicitud.</strong></a></br></br>
								</div>		
							</div>
						</td> 
					</tr>  							
								';
								/*						
								<strong>SU LINK DE ACTIVACION:<br><a href="'.$_activateLink.'">'.$_activateLink.' </strong></a><br><br><br> 
								<strong>POR FAVOR HAGA CLICK EN LINK DE ARRIBA PARA ACTIVAR SU CUENRA Y ACCEDER A LA PAGINA SIN RESTRICCIONES</strong><br><br><br> 
								<strong>SI EL LINK NO FUNCIONA A LA PRIMERA INTENTELO UNA SEGUNDA, EL SERVIDOR A VECES TARDA EN PROCESAR LA PRIMERA ORDEN</strong><br><br><br> 
								*/
			$_total	.= 			'
					<tr align="center" class="saludo">
						<td valign="top">
							<div class="saludo">
								Gracias por registrarse en '.$_nombreemp.'<br />
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
$mail->AddAddress($_email, 'Solicitud de Alta en '.$_nombreemp);
$mail->AddBCC("infoweb@neo-farma.com.ar", "Infoweb");

switch($empresa) {
	case 1:
		$mail->AddBCC("altaproveedores@neo-farma.com.ar");
		break;
	case 3:
		$mail->AddBCC("altaproveedores@gezzi.com.ar");
		break;
	case 4:
		$mail->AddBCC("altaproveedores@laos.com.ar");
		break;
	default:
		$mail->AddBCC("altaproveedores@neo-farma.com.ar");
		break;
}

if(!$mail->Send()) {
	//Si el envío falla, que mande otro mail indicando que la solicittud no fue correctamente enviada?=?
	//echo 'Fallo en el envío';
	$_goURL = "../index.php?sms=21";
	header('Location:' . $_goURL);
	exit;
} 
	
//Envío exitoso
$_goURL = "../index.php?sms=30";
header('Location:' . $_goURL);

//*************************************//
unset($_SESSION['empselect']);
unset($_SESSION['razonsocial']);
unset($_SESSION['provincia']);
unset($_SESSION['localidad']);
unset($_SESSION['direccion']);
unset($_SESSION['codpostal']);
unset($_SESSION['cuit'] );
unset($_SESSION['nroIBB']);
unset($_SESSION['usuario']);
unset($_SESSION['telefono']);
unset($_SESSION['email']);
unset($_SESSION['emailconfirm']);
unset($_SESSION['clave']);
unset($_SESSION['web']);
unset($_SESSION['radio']);
unset($_SESSION['comentario']);
//*************************************//

exit;


?>