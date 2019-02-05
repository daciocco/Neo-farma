<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
/*if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="P" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
 }*/

function dac_generar_password($longitud) {
	$caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$password = '';
	for ($i=0; $i<$longitud; ++$i) $password .= substr($caracteres, (mt_rand() % strlen($caracteres)), 1);
	return $password;
}

$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/login/': $_REQUEST['backURL'];

$_usuario			= $_POST['rec_usuario'];
$_email				= $_POST['rec_mail'];

$_SESSION['s_usuario']	= $_usuario;
$_SESSION['s_email'] 	= $_email;

//Consulta si existe el usuario en cualquiera de las tablas con posibilidad de usuario
$_ID = DataManager::getIDByField('TUsuario', 'ulogin', $_usuario);
if ($_ID > 0) {
	$_usrobject = DataManager::newObjectOfClass('TUsuario', $_ID); 	
} else {
	$_ID = DataManager::getIDByField('TProveedor', 'provlogin', $_usuario);
	if ($_ID > 0) {
		$_usrobject = DataManager::newObjectOfClass('TProveedor', $_ID); 
	}
}

if ($_ID > 0) {		
	//$_usrobject = DataManager::newObjectOfClass('TUsuario', $_ID);
	$_uemail 	= $_usrobject->__get('Email');
		//if ($_usrobject->login(md5($_password))) {			
	if (!preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $_email )) {
		$_goURL = sprintf("/pedidos/login/index.php?sms=%d", 2);
		header('Location:' . $_goURL);
		exit;
	} else {
		if (strcmp ($_email , $_uemail ) != 0) {
			$_goURL = sprintf("/pedidos/login/index.php?sms=%d", 3);
			header('Location:' . $_goURL);
			exit;
		}
	}

	$_uusuario	=	$_usrobject->__get('Login');

	/*********************************/
	/*Genero una clave aleatorioa para que el usuario luego la modifique si lo desea*/
	$_clave		=	dac_generar_password(7);
	$_usrobject->__set('Clave',	md5($_clave));
	$ID 		=	DataManager::updateSimpleObject($_usrobject);											
	/*********************************/
	//		ENVIAR CLAVE POR MAIL	 */
	//********************************/
	require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
	$mail->From     	= "infoweb@neo-farma.com.ar";
	$mail->Subject 		= 'Clave Recuperada';

	/*$_total= "
		Alguien ha solicitado la recuperaci&oacute;n de contrase&ntilde;a de la siguiente cuenta:<br /><br />
		http://www.neo-farma.com.ar/<br /><br />
		Nombre de Usuario: ".$_uusuario."<br />
		Nueva Clave: ".$_clave."<br /><br />
		". /*Si ha sido un error, ignora este correo y no pasará nada.<br /><br />
		Para restaurar la contraseña, visita la siguiente dirección:<br /><br />
		<http://www.neo-farma.com.ar/wp-login.php?action=rp&key=kr9IvLn2uJVinYcy9u23&login=".$_nombre.">*//*"<br />
		No responda a &eacute;ste mail.<br /><br />
		Saludos.";*/	
	
	//header And footer
	include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
	
	$_total	= '
			  <html>
				  <head>
					  <title>Clave Recuperada</title>
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
										  Notificaci&oacute;n de <strong>NUEVA CLAVE DE ACCESO</strong><br />
										  Alguien ha solicitado la recuperaci&oacute;n de contrase&ntilde;a de la siguiente cuenta:<br />								
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
												  <th rowspan="2" align="left" width="250">
													  Usuario:<br />		
													  Clave:
												  </th>	
												  <th rowspan="2" align="left" width="450">
													  '.$_usuario.'<br />
													  '.$_clave.'
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
											  <strong>No responda a &eacute;ste mail.</br></br>
										  </div>		
									  </div>
								  </td> 
							  </tr>
							  <tr align="center" class="saludo">
								  <td valign="top">
									  <div class="saludo" align="left">
										  Gracias por confiar en nosotros.<br/>
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
	$mail->AddAddress($_uemail, "Clave Recuperada");
	$mail->AddAddress("infoweb@neo-farma.com.ar", "[neo-farma.com.ar] Clave Recuperada.");

	/************************************/
	unset($_SESSION['s_usuario']);
	unset($_SESSION['s_email']);
	/**********************************/

	if(!$mail->Send()) {
		echo 'Fallo en el envío del correo.';
	} else {
		$_goURL = sprintf("/pedidos/login/index.php?sms=%d", 4);
		header('Location:'.$_goURL);
	}
	exit;
} else { 
	$_goURL = sprintf("/pedidos/login/index.php?sms=%d", 1);
	header('Location:' . $_goURL);
	exit;
}

header('Location: '.$backURL);
?>