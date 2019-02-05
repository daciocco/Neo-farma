<?php
	session_start();
	require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
	if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="P" && $_SESSION["_usrrol"]!="G"){
		$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	 	header("Location: $_nextURL");
 		exit;
	}

	$_uid		= 	$_SESSION["_usrid"]; //empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
	$backURL	= 	empty($_REQUEST['backURL']) ? '/pedidos/index.php': $_REQUEST['backURL'];
	
	$empresa	= 	empty($_SESSION["_usridemp"]) ? 1 : $_SESSION["_usridemp"];
	$_empresas	=	DataManager::getEmpresas(1); 
	if (count($_empresas)) {	
		foreach ($_empresas as $k => $_emp) {
			if ($empresa == $_emp['empid']){                       		
				$_nombreemp = $_emp["empnombre"];
			} 
		}                            
	}
	
	$_nombre			= $_SESSION["_usrname"];
	
 	$_usuario			= $_POST['uusuario'];
	$_password			= $_POST['upassword'];
 	$_newpassword		= $_POST['unewpassword'];
 	$_newpasswordbis	= $_POST['unewpasswordbis'];
	
 	$_SESSION['s_usuario']			= $_usuario;
	$_SESSION['s_password']			= $_password;
 	$_SESSION['s_newpassword']		= $_newpassword;
 	$_SESSION['s_newpasswordbis']	= $_newpasswordbis;
	
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
		
	//$_ID = DataManager::getIDByField('TUsuario', 'ulogin', $_usuario);
	if ($_ID > 0) {
		//$_usrobject = DataManager::newObjectOfClass('TUsuario', $_ID); 
		if (empty($_password) || $_password == "") {
			$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 2);
 			header('Location:' . $_goURL);
			exit;
		}
		
		//echo "1-".md5($_password);
		//echo "2-".$_usrobject->login(md5($_password));
		if ($_usrobject->login(md5($_password))) {			
			if (empty($_newpassword)) {
				$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 4);
				header('Location:' . $_goURL);
				exit;
			}
			if (empty($_newpasswordbis)) {
				$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 5);
				header('Location:' . $_goURL);
				exit;
			}
					
			if ((0 != strcmp($_newpassword, $_newpasswordbis))) {
				$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 6);
				header('Location:' . $_goURL);
				exit;
			} else {				
				$_email	= $_usrobject->__get('Email');
				if (!preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $_email )) {
					$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 7);
					header('Location:' . $_goURL);
					exit;
				}
				
				//Si ya existe una clave, la lee de la ddbb cifrada y al querer grabar la vuelve a cifrar dando errores.
				//Por eso mismo hago el siguiente control previo.
				//echo "3-".strlen($_password) <= 15;
				/*********************************/
				//$_usrobject		= DataManager::newObjectOfClass('TUsuario', $_ID);
				$_usrobject->__set('Clave', 	md5($_newpassword));
				$ID 			= DataManager::updateSimpleObject($_usrobject);										
				/*********************************/
				//ENVIAR MAIL AL USUARIO NOTIFICANDO LA NUEVA CLAVE
				require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
				$mail->From     	= "infoweb@neo-farma.com.ar";
				$mail->FromName 	= "InfoWeb GEZZI";
				$mail->Subject 		= 'Solicitud de Cambio de Clave.';
				
				//header And footer
				include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
				
				$_total	= '
					<html>
						<head>
							<title>Cambio de clave</title>
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
															'.$_newpassword.'
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
				$mail->AddAddress("infoweb@neo-farma.com.ar", 'InfoWeb GEZZI');

				/************************************/
				unset($_SESSION['s_usuario']);
				unset($_SESSION['s_password']);
				unset($_SESSION['s_newpassword']);
				unset($_SESSION['s_newpasswordbis']);
				/**********************************/
				
				if(!$mail->Send()) {
					echo 'Fallo en el envío del correo.';
				} else {
					
					$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 8);
					header('Location:'.$_goURL); exit;
				}
				exit;
			}
		} else {
			$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 3);
			header('Location:' . $_goURL);
			exit;
		}
		
	} else { 
		$_goURL = sprintf("/pedidos/usuarios/password/index.php?sms=%d", 1);
 		header('Location:' . $_goURL);
		exit;
	}
	
	header('Location: '.$backURL);
?>