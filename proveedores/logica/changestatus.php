<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}
 
$_pag		= empty($_REQUEST['pag']) 		? 0 			: $_REQUEST['pag'];
$_provid	= empty($_REQUEST['provid']) 	? 0 			: $_REQUEST['provid'];
$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/proveedores/'	: $_REQUEST['backURL'];

if ($_provid) {
	$_provobject	=	DataManager::newObjectOfClass('TProveedor', $_provid);
	/*************************************************************/
	//Al cambiar el estatus, si llega a ser nro 3 (solicitud de activación realizada)
	//se procederá a enviar mail al email del proveedor, infoweb y compras@neo-farma.com.ar que el usuario fue dado de alta.
	if($_provobject->__get('Activo') == 3){
		//**************************************************
		// Armado del CORREO para el envío
		//**************************************************
		$_usuario	=	$_provobject->__get('Login');
		$_email		=	$_provobject->__get('Email');
		$_empresa	=	$_provobject->__get('Empresa');		
		
		$_empresas	= DataManager::getEmpresas(1);
		if (count($_empresas)) {	
			foreach ($_empresas as $k => $_emp) {
				$_idemp		=	$_emp["empid"];
				if ($_idemp == $_empresa){                        		
					$_nombreemp	=	$_emp["empnombre"];
				} 
			}                            
		}		
		
		require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
		$mail->From     =	"infoweb@neo-farma.com.ar";	 //mail de solicitante
		$mail->FromName	=	"InfoWeb GEZZI";	//"Vendedor: ".$_SESSION["_usrname"];
		$mail->Subject 	=	"Datos de registro de ".$_usuario." en ".$_nombreemp.", guarde este email.";
		
		//header And footer
		include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
		
		$_total	= '
			<html>
				<head>
					<title>Confirmación de Alta de Proveedor</title>
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
										El usuario <b>'.$_usuario.' </b> <strong>HA SIDO ACTIVADO</strong> en <strong>'.$_nombreemp.'</strong> <br />
										Puede acceder a la web haciendo clic en <a href="https://www.neo-farma.com.ar/pedidos/login/">acceso web</a> o desde el Uso Interno en web www.neo-farma.com.ar
									<div />
								</td >
							</tr> 
							
							<tr> 
								<td> 
									<div class="texto">
										<font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif">
											Por cualquier consulta, p&oacute;ngase en contacto con la empresa al 4555-3366 de Lunes a Viernes de 10 a 17 horas. 
										</font>	
										</br></br>	
									</div>
								</td> 
							</tr>  			
							
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
		$mail->AddAddress($_email, "InfoWeb");
		$mail->AddBCC("infoweb@neo-farma.com.ar", "InfoWeb");
		$mail->AddBCC("compras@neo-farma.com.ar", "InfoWeb");		
		switch($_empresa) {
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
		
		//*****************************************************************// 
		//*******************************************************************
		//no se controla error de correo
		if(!$mail->Send()) {
			echo "Ocurri&oacute; un ERROR al intentar activar el Usuario. Consulte con el administrador de la web antes de volver a intentarlo."; exit;
			//Si el envío falla, que mande otro mail indicando que la solicittud no fue correctamente enviada?=?
			//echo 'Fallo en el envío';
			/*$_goURL = "../index.php?sms=21";
			header('Location:' . $_goURL);
			exit;*/
		} 
		//*******************************************************************
		$_status		=	1;
	} else {
		$_status		=	($_provobject->__get('Activo')) ? 0 : 1;		
	}
	$_provobject->__set('Activo',	$_status);
	$ID = DataManager::updateSimpleObject($_provobject);
}

header('Location: '.$backURL.'?pag='.$_pag);
?>