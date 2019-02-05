<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$pag	=	empty($_REQUEST['pag'])	? 0	: $_REQUEST['pag'];
$ctaId	=	empty($_REQUEST['id']) 	? 0 : $_REQUEST['id'];

if ($ctaId) {
	$ctaObject	= DataManager::newObjectOfClass('TCuenta', $ctaId);	
	
	//**************************//
	//	Consulto Datos Cuenta	// --> envía mail de notificación
	$cuenta		=	$ctaObject->__get('Cuenta');
	$tipo		=	$ctaObject->__get('Tipo');
	$empresa	=	$ctaObject->__get('Empresa');
	$zona		=	$ctaObject->__get('Zona');	
	$ruteo		=	$ctaObject->__get('Ruteo');	
	$nombre		=	$ctaObject->__get('Nombre');
	$cuit		=	$ctaObject->__get('CUIT');	
	$provincia	=	$ctaObject->__get('Provincia');	
	$localidad	= 	$ctaObject->__get('Localidad');	
	$direccion	=	$ctaObject->__get('Direccion')." ".$ctaObject->__get('Numero')." ".$ctaObject->__get('Piso')." ".$ctaObject->__get('Dpto');	
	$idLocalidad=	$ctaObject->__get('Localidad');	
	$correo		=	$ctaObject->__get('Email');	
	$telefono	=	$ctaObject->__get('Telefono');	
	$observacion=	$ctaObject->__get('Observacion');
	
	$empresaNombre		=	DataManager::getEmpresa('empnombre', $empresa);
	$provinciaNombre	=	DataManager::getProvincia('provnombre', $provincia);
	$localidadNombre	=	DataManager::getLocalidad('locnombre', $localidad);	
	
	//header And footer
	include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
	
	if($_SESSION["_usrrol"] != "V"){
		//**********************************//
		//	UPDATE de datos de la cuenta	//
		$_status	= ($ctaObject->__get('Activa')) ? 0 : 1;
		$ctaObject->__set('UsrUpdate'	, $_SESSION["_usrid"]);
		$ctaObject->__set('LastUpdate'	, date("Y-m-d H:m:s"));
		$ctaObject->__set('Activa'		, $_status);		
		DataManager::updateSimpleObject($ctaObject);
		
		//si es Cliente, que lo cambie en hiperwin
		if($tipo == "C" || $tipo == "CT") {
			$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaId);	
			$ctaObjectHiper->__set('UsrUpdate'	, $_SESSION["_usrid"]);
			$ctaObjectHiper->__set('LastUpdate'	, date("Y-m-d H:m:s"));
			$ctaObjectHiper->__set('Activa'		, $_status);
			DataManagerHiper::updateSimpleObject($ctaObjectHiper, $ctaId);
		}
		
		
		//**************************//
		//	Consulto Datos Cuenta	// --> envía mail de notificación
		$estado		= 	($ctaObject->__get('Activa')) ? "ACTIVADA" : "DESACTIVADA";	
		
		//********//
		// CORREO //
		require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );				
		$mail->From     	= "infoweb@neo-farma.com.ar"; // Dirección de correo del remitente
		$mail->FromName 	= "Comunicado Web";	// Nombre del remitente
		$mail->Subject 		= "Constancia de Reactivacion de Cuenta"; //"Asunto del correo";
		$cuerpoMail = '
			<html>
				<head>
					<title>Solicitud de REACTIVACION de CUENTA '.$tipo.'</title>
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
				</head>
				<body>
					<div align="center">
						<table width="580" border="0" cellspacing="1"> 
							<tr>
								<td>'.$cabecera.'</td>
							</tr>
							<tr>
								<td> 
									<div class="texto">
										La <strong>SOLICITUD de REACTIVACI&Oacute;N de CUENTA '.$tipo.'</strong> ha sido <strong>'.$estado.'</strong><br/><br/>		
									<div />
								</td >
							</tr> 
							
							<tr> 
								<td>
									<div class="texto" style="color:#FFFFFF; font-size:14; font-weight:bold; background-color:#117db6">
										<strong>Datos de solicitud</strong>
									</div>
								</td> 
							</tr> 
							
							<tr>
								<td valign="top">
									<div class="texto">						
										<table width="580px" style="border:1px solid #117db6">
											<tr>
												<th align="left" width="200"> Usuario </th>	
												<td align="left" width="400"> '.$_SESSION["_usrname"].' </td>
											</tr>
											<tr>
												<th align="left" width="200">Zona - Ruteo</th>	
												<td align="left" width="400">'.$zona." - ".$ruteo.'</td>
											</tr>
											<tr>
												<th align="left" width="200">Fecha </th>	
												<td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
											</tr>
											<tr>
												<th align="left" width="200">Cuenta</th>	
												<td align="left" width="400">'.$cuenta.'</td>
											</tr>
											<tr>
												<th align="left" width="200">Raz&oacute;n Social</th>	
												<td align="left" width="400">'.$nombre.'</td>
											</tr>										
											<tr>
												<th align="left" width="200">E-mail</th>	
												<td align="left" width="400">'.$correo.'</td>
											</tr>
											<tr>
												<th align="left" width="200">Tel&eacute;fono</th>	
												<td align="left" width="400">'.$telefono.'</td>
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
											<strong>Si no recibe informaci&oacute;n del reactivaci&oacute;n en 24 horas h&aacute;biles, podr&aacute; reclamar reenviando &eacute;ste mail a reclamosweb@neo-farma.com.ar </strong></a></br></br>
										</div>		
									</div>
								</td> 
							</tr>
							<tr align="center" class="saludo">
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
					<div />
				</body>	
			</html>	
		';	
		$mail->msgHTML($cuerpoMail);
		
		//**********************//
		//	REGISTRO DE ESTADOS	// de Cuenta	
		//**********************//
		$estadoNombre	=	($ctaObject->__get('Activa')) ? "Desactiva" : "Reactiva";	
		$origen			=	'TCuenta';
		$origenId		=	$ctaId;
		
		$mail->AddBCC("infoweb@neo-farma.com.ar"); //AddBCC Copia Oculta
		$mail->AddAddress($_SESSION["_usremail"], $estadoNombre.' de la cuenta '.$tipo.". Enviada");
		
		//**********************//	
		//	Registro MOVIMIENTO	//
		//**********************//
		$movimiento 	= 'CUENTA_'.$ctaId;	
		$movTipo		= 'UPDATE';
		dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);
		
	} else { 
		//SOLICITUD DE REACTIVACIÓN
		//**************************//
		//	Consulto Datos Cuenta	// --> envía mail de notificación
		//**************************//	
		if(!$ctaObject->__get('Activa')){
			$fechaActual	=	new DateTime();
			$fechaAtras 	=	clone $fechaActual;
			$fechaAtras->modify("-1 year");		
			$fechaCompra	=	new DateTime($ctaObject->__get('FechaCompra'));
						
			if($tipo == "PS"){
				echo "Un prospecto solo se activa en un cambio de tipo de cuenta."; exit;
			}
			
			//Si la última compra es de más de un año, no se podrá solicita ni Activar la cuenta. Solo desactivar
			if($fechaCompra < $fechaAtras && $fechaCompra->format('Y-m-d') <> '2001-01-01') {
				echo "La cuenta lleva más de un año sin registrar compras. Solicite reactivar enviando la documentación que corresponda."; exit; //$fechaAtras->format("Y-m-d H:i:s")
			}
		
			$estado		= 	($ctaObject->__get('Activa')) ? "ACTIVADA" : "DESACTIVADA";			
			
			require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
			$mail->From     	= $_SESSION["_usrmail"]; // Dirección de correo del remitente
			$mail->FromName 	= $_SESSION["_usrname"]; // Nombre del remitente
			$mail->Subject 		= "Solicitud de Reactivacion de Cuenta"; //"Asunto del correo";
			
			$cuerpoMail = '
				<html>
					<head>
						<title>Solicitud de REACTIVACION de CUENTA '.$tipo.'</title>
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
					</head>
					<body>
						<div align="center">
							<table width="580" border="0" cellspacing="1"> 
								<tr>
									<td>'.$cabecera.'</td>
								</tr>
								<tr>
									<td> 
										<div class="texto">
											La <strong>SOLICITUD de REACTIVACI&Oacute;N de CUENTA '.$tipo.'</strong> ha sido <strong>ENVIADA</strong><br/><br/>		
										<div />
									</td >
								</tr> 
								
								<tr> 
									<td>
										<div class="texto" style="color:#FFFFFF; font-size:14; font-weight:bold; background-color:#117db6">
											<strong>Datos de solicitud</strong>
										</div>
									</td> 
								</tr> 
								
								<tr>
									<td valign="top">
										<div class="texto">						
											<table width="580px" style="border:1px solid #117db6">
												<tr>
													<th align="left" width="200"> Usuario </th>	
													<td align="left" width="400"> '.$_SESSION["_usrname"].' </td>
												</tr>
												<tr>
													<th align="left" width="200">Zona - Ruteo</th>	
													<td align="left" width="400">'.$zona." - ".$ruteo.'</td>
												</tr>
												<tr>
													<th align="left" width="200">Fecha </th>	
													<td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
												</tr>
												<tr>
													<th align="left" width="200">Cuenta</th>	
													<td align="left" width="400">'.$cuenta.'</td>
												</tr>
												<tr>
													<th align="left" width="200">Raz&oacute;n Social</th>	
													<td align="left" width="400">'.$nombre.'</td>
												</tr>										
												<tr>
													<th align="left" width="200">E-mail</th>	
													<td align="left" width="400">'.$correo.'</td>
												</tr>
												<tr>
													<th align="left" width="200">Tel&eacute;fono</th>	
													<td align="left" width="400">'.$telefono.'</td>
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
												<strong>Si no recibe informaci&oacute;n de reactivaci&oacute;n en 24 horas h&aacute;biles, podr&aacute; reclamar reenviando &eacute;ste mail a reclamosweb@neo-farma.com.ar </strong></a></br></br>
											</div>		
										</div>
									</td> 
								</tr>
								<tr align="center" class="saludo">
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
						<div />
					</body>	
				</html>		
			';	
				
			$mail->AddBCC("infoweb@neo-farma.com.ar"); //AddBCC Copia Oculta		
			$mail->AddAddress($_SESSION["_usremail"], $estadoNombre.' de la cuenta '.$tipo.". Enviada");
			$mail->msgHTML($cuerpoMail);
			
			//**********************//
			//	REGISTRO DE ESTADOS	// de Cuenta	
			//**********************//
			$estadoNombre	=	($ctaObject->__get('Activa')) ? "Desactiva" : "Reactiva";
			$origen			=	'TCuenta';
			$origenId		=	$ctaId;
			
		} else {
			echo "La cuenta se encuentra activa"; exit;
		}
	}
	
	if(!$mail->Send()) {
		echo "Hubo un Error en el envío de mails para notificar cambios."; exit;				
	}
	
	//******************//	
	//	Registro ESTADO	//
	//******************//
	$estadoObject	=	DataManager::newObjectOfClass('TEstado');	
	$estadoObject->__set('Origen'	, $origen);		
	$estadoObject->__set('IDOrigen'	, $origenId);
	$estadoObject->__set('Fecha'	, date("Y-m-d h:i:s"));
	$estadoObject->__set('UsrCreate', $_SESSION["_usrid"]);
	$estadoObject->__set('Estado'	, 0);
	$estadoObject->__set('Nombre'	, $estadoNombre);
	$estadoObject->__set('ID'		, $estadoObject->__newID());
	$IDEstado	= DataManager::insertSimpleObject($estadoObject);	
	if(!$IDEstado){
		echo "Error al intentar registrar el estado de la solicitud. Consulte con el administrador de la web."; exit;
	}
} else {
	echo "No se pudo cambiar el estado de la cuenta."; exit;
}

echo "1";
?>