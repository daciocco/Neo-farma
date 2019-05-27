<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="V"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$idPropuesta=	(isset($_POST['propuesta']))	? $_POST['propuesta']	:	NULL;
$estado		=	(isset($_POST['estado']))		? $_POST['estado']  	:	NULL;

$propuesta	= 	DataManager::getPropuesta($idPropuesta);
if ($propuesta) { 
	foreach ($propuesta as $k => $prop) {	
		$empresa	=	$prop["propidempresa"]; 
		$fecha		=	$prop['propfecha'];
		$usrProp	=	$prop['propusr'];
		$estadoDDBB	=	$prop["propestado"];
		$activa		=	$prop["propactiva"];
		$nroCuenta	=	$prop["propidcuenta"];		
		
		$propuestaObject	= DataManager::newObjectOfClass('TPropuesta', $idPropuesta);
		$propuestaObject->__set('Estado'		, $estado);
		$propuestaObject->__set('FechaCierre'	, date("Y-m-d H:i:s"));			
		$propuestaObject->__set('LastUpdate'	, date("Y-m-d H:i:s"));	
		$propuestaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);		
		
		switch ($estadoDDBB){
			case 1: //PENDIENTE
				if ($_SESSION["_usrid"]=="7" || $_SESSION["_usrid"]=="20" || $_SESSION["_usrid"]=="33" || $_SESSION["_usrid"]=="82" || $_SESSION["_usrid"]=="28" || $_SESSION["_usrid"]=="40" || $_SESSION["_usrid"]=="69"){
					switch ($estado){			
						case 2:
							/*if ($_SESSION["_usrrol"]!="A" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
								echo "No puede realizar el proceso."; exit;
							} else {
								$resultado = "APROBADA";	
							}	*/
							$resultado = "APROBADA";
							break;
						case 3:
							$resultado = "RECHAZADA";
							$propuestaObject->__set('Activa' , 0);
							break;
					}
				} else {
					echo "No puede realizar el proceso."; exit;
				}
				break;
			case 2: //APROBADA
				switch ($estado){			
					case 2:
						echo "No puede realizar el proceso."; exit;
						break;
					case 3:
						$resultado = "RECHAZADA";
						$propuestaObject->__set('Activa' , 0);
						break;
				}				
				break;
			case 3: //rechazada
				echo "No puede realizar el proceso."; exit;
				break;
		}
			
		DataManager::updateSimpleObject($propuestaObject);		
		//Cargo NUEVO ESTADO Propuesta APROBADA/RECHAZADO
		$estadoNombre 	= 	DataManager::getEstado('penombre', 'peid', $estado); 
		
		$estadoObject	=	DataManager::newObjectOfClass('TEstado');
		$estadoObject->__set('Origen'	, 'TPropuesta');
		$estadoObject->__set('IDOrigen'	, $idPropuesta);
		$estadoObject->__set('Fecha'	, date("Y-m-d H:i:s"));
		$estadoObject->__set('UsrCreate', $_SESSION["_usrid"]);
		$estadoObject->__set('Estado'	, $estado); //rechazado o aceptado
		$estadoObject->__set('Nombre'	, $estadoNombre);
		$estadoObject->__set('ID'		, $estadoObject->__newID());		
		$IDEst	= DataManager::insertSimpleObject($estadoObject);
		if(empty($IDEst)){ 
			echo "No se grab&oacute; correctamente el estado de la propuesta."; exit;
		}
		//**********//
		//	CORREO	//  Notifica aprobacion/Rechazo
		//**********//
		$cuentaName		= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $nroCuenta, $empresa);	
		$empresaNombre	=	DataManager::getEmpresa('empnombre', $empresa);
		
		//header And footer
		include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
		
		$usrName		= 	DataManager::getUsuario('unombre', $usrProp);	
		$usrMail		= 	DataManager::getUsuario('uemail', $usrProp);
		
		require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
		$mail->From     =	"infoweb@neo-farma.com.ar";
		$mail->FromName	=	"InfoWeb GEZZI";
		$mail->Subject 	= 	"La propuesta ".$idPropuesta." fue ".$resultado;
		
		$headMail 		= '
									<html>
										<head>
											<title>PROPUESTA '.$idPropuesta.' '.$resultado.'</title>
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
															Se env&iacute;an los datos de <strong>PROPUESTA '.$resultado.' </strong> correspondiente a:<br/><br/>		
														<div />
													</td >
												</tr> 
												
												<tr> 
													<td>
														<div class="texto" style="color:#FFFFFF; font-size:14; font-weight:bold; background-color:#117db6">
															<strong>Datos de la solicitud</strong>
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
														<th align="left" width="200"> Usuario </th>	
														<td align="left" width="400"> '.$usrName.' </td>
													</tr>
													<tr>
														<th align="left" width="200">Propuesta</th>	
														<td align="left" width="400">'.$idPropuesta.'</td>
													</tr>
													<tr>
														<th align="left" width="200">E-mail</th>	
														<td align="left" width="400">'.$usrMail.'</td>
													</tr>
													<tr>
														<th align="left" width="200">Fecha de pedido</th>	
														<td align="left" width="400">'.$fecha.'</td>
													</tr>
													<tr style="color:#FFFFFF; font-weight:bold;">
														<th colspan="2" align="center">	
															Datos de la Propuesta
														</th>	
													</tr>
													<tr>
														<th align="left" width="200">Estado</th>	
														<td align="left" width="400">'.$resultado.'</td>
													</tr>
													<tr>
														<th align="left" width="200">Cuenta</th>	
														<td align="left" width="400">'.$nroCuenta.'</td>
													</tr>
													<tr>
														<th align="left" width="200">Nombre</th>	
														<td align="left" width="400">'.$cuentaName.'</td>
													</tr>
												</table>
											</div>
										</td> 
									</tr>
								';	
		$cuerpoMail_3 	= '	
									<tr> 
										<td>
											<div class="texto">
												<font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif">
													Por cualquier consulta, p&oacute;ngase en contacto con la empresa al 4555-3366 de Lunes a Viernes de 10 a 17 horas.
												</font>
																								
												<div style="color:#000000; font-size:12px;"> 
													<strong>Puede reclamar reenviando &eacute;ste mail a controldegestion@neo-farma.com.ar. </strong></br></br>
												</div>		
											</div>
										</td> 
									</tr>
								';	
		$pieMail 		= '	
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
		
		$cuerpoMail 	= $headMail.$cuerpoMail_1.$cuerpoMail_2.$cuerpoMail_3.$pieMail;	
		
		$mail->msgHTML($cuerpoMail);
		$mail->AddBCC("infoweb@neo-farma.com.ar", "Infoweb");
		//$mail->AddBCC("controldegestion@neo-farma.com.ar", "Control de Gestion");
		$mail->AddAddress($usrMail, "$usrMail");		
		if(!$mail->Send()) {
			echo 'Fallo en el envío de mail de la propuesta'; exit;
		}
		
		/*********************/	
		switch ($estado){			
			case 2:				
			case 3:
				echo "1"; exit;
				break;
			default: 
				echo "Error al indicar el resultado."; exit;
				break;
		}	
		
		
	}
} else {
	echo "No se encontr&oacute; la propuesta para modificar."; exit;
}
?>