<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$empresa	= empty($_REQUEST['idempresa']) ? 0 : $_REQUEST['idempresa'];
$_idprov 	= empty($_REQUEST['idprov']) 	? 0 : $_REQUEST['idprov'];
$_fechapago	= empty($_REQUEST['fechapago']) ? 0 : $_REQUEST['fechapago'];
$_nrofact	= empty($_REQUEST['factnro']) 	? 0 : $_REQUEST['factnro'];


if(empty($empresa) || empty($_idprov) || empty($_fechapago) || empty($_nrofact)){
	echo "Ocurrió un ERROR al verificar el proveedor."; exit;
}

$empresaNombre	=	DataManager::getEmpresa('empnombre', $empresa);
if(!$empresaNombre){
	echo "No existen empresas activas en GEZZI."; exit;
}
//$_empresas	=	DataManager::getEmpresas(1); 
/*
if (count($_empresas)) {	
	foreach ($_empresas as $k => $_emp) {
		if ($empresa == $_emp['empid']){                       		
			$_nombreemp = $_emp["empnombre"];
		} 
	}                            
} else {
	echo "No existen empresas activas en GEZZI."; exit;
}*/

//Busco registros ya guardados en ésta fecha y pongo en cero si no están en el array (si fueron eliminados)
$_proveedor		=	DataManager::getProveedor('providprov', $_idprov, $empresa);
if($_proveedor){
	$_idorigen	=	$_proveedor['0']['provid'];
	$_activo	=	$_proveedor['0']['provactivo'];
	$_nombre	= 	$_proveedor['0']['provnombre'];
	$_emailprov	= 	$_proveedor['0']['provcorreo']; //email de razón social
	$_telefono	= 	$_proveedor['0']['provtelefono'];	
	
	if(!$_activo){
		echo "El proveedor '".$_idprov." - ".$_nombre."' no se encuentra activo."; exit;
	}
	
	//$_idorigen	=	$_provid;
	$_origen	=	'TProveedor';
	$_ctocorreo	=	"";
	$_emails	= 	array();
	
	//Esto registrará SOLO el último correo de los que esté como sector COBRANZAS..
	$_contactos	=	DataManager::getContactosPorCuenta( $_idorigen, $_origen, 1);
	if($_contactos){		
		$_email = '';
		foreach ($_contactos as $k => $_cont){
			$_ctoid		=	$_cont["ctoid"];																							
			$_sector	=	$_cont["ctosector"]; 
			if($_sector == 2){	//2 = Cobranza 			
				$_email	= $_cont["ctocorreo"];
				array_push($_emails, $_email);
			}                 
		}		
		if (empty($_email)){
			echo "No se encuentra un correo de Cobranzas para notificar al Proveedor"; exit;
		}
	} else {
		if(empty($_emailprov)){
			echo "El Proveedor no tiene correos para notificar.";
			exit;			
		} else {
			$_email	=	$_emailprov;
		}		
	}
	
	//**************************//	
	//	NOTIFICA FECHA DE PAGO	//
	//**************************//
	require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
	$mail->From     =	"infoweb@neo-farma.com.ar"; 
	$mail->FromName	=	"InfoWeb GEZZI";	
	$mail->Subject 	=	"Notificacion de Fecha de Pago para ".$_nombre.", guarde este email.";
	
	//header And footer
	include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
	
	$headMail 		= '
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
										Notificaci&oacute;n de <strong>FECHA DE PAGO</strong><br />			
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
				';	
	
	$cuerpoMail_2 	= '	
					<tr>
                        <td valign="top">
                            <div class="texto">						
                                <table width="580px" style="border:1px solid #117db6">
									<tr>
                                        <th align="left" width="200">FECHA DE PAGO:</th>	
                                        <td align="left" width="400">'.$_fechapago.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Horario:</th>	
                                        <td align="left" width="400">de 15:00 a 16:30 hs</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Proveedor: </th>	
                                        <td align="left" width="400">'.$_nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Nro. de Factura:</th>	
                                        <td align="left" width="400">'.$_nrofact.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Correo:</th>	
                                        <td align="left" width="400">'.$_emailprov.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono:</th>	
                                        <td align="left" width="400">'.$_telefono.'</td>
                                    </tr>
                                </table>
                            </div>
                        </td> 
                    </tr>
				';
	
	$cuerpoMail_3 	= 	'	
					<tr> 
						<td>
							<div class="texto">
								<font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif">
									Por cualquier consulta, p&oacute;ngase en contacto con la empresa al 4555-3366 de Lunes a Viernes de 10 a 17 horas.
								</font>

								<div style="color:#000000; font-size:12px;"> 
									<strong>Le enviaremos un email notificando la fecha de pago, al contacto de su cuenta de usuario.</br></br>
								</div>		
							</div>
						</td> 
					</tr>
				';
	
	$pieMail 	= '	<tr align="center" class="saludo">
						<td valign="top">
							<div class="saludo">
								Gracias por confiar en '.$empresaNombre.'<br/>
								Le saludamos atentamente,
							</div>
						</td>
					</tr>

					<tr>
						<td valign="top">'.$pie.'</td>					
					</tr>
				</table>
			</body>	
		</html>	
	';	
	$_total = $headMail.$cuerpoMail_1.$cuerpoMail_2.$cuerpoMail_3.$pieMail;	
	
	$mail->msgHTML($_total);
	
	//$mail->AddAddress($_email, 'InfoWeb GEZZI');
	if(count($_emails)){
		foreach ($_emails as $k => $_email){
			$mail->AddAddress($_email, 'InfoWeb GEZZI');
		}
	}
	
	$mail->AddBCC("infoweb@neo-farma.com.ar", 'InfoWeb GEZZI');
	$mail->AddBCC("pagoproveedores@neo-farma.com.ar", 'InfoWeb GEZZI');
	
	//*********************************//
	if(!$mail->Send()) {
		echo "Hubo un error al intentar enviar la notificación."; exit;
	}	
	//**************************//
} else {
	echo "El proveedor no existe cargado"; exit;
}

echo "1"; exit;

?>