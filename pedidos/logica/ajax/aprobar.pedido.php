e<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" &&  $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	echo 'SU SESION HA EXPIRADO.'; exit;
}

$_nropedido	=	(isset($_POST['nropedido']))	? $_POST['nropedido']	:	NULL;
$_estado	=	(isset($_POST['estado']))		? $_POST['estado']  	:	NULL;

$_detalles	= 	DataManager::getPedidos($_usr, NULL, $_nropedido);
if ($_detalles) { 	
	foreach ($_detalles as $k => $_detalle) {
		$_pid			=	$_detalle["pid"];
		$_pidusr		=	$_detalle["pidusr"];
		$_negociacion	=	$_detalle["pnegociacion"];
		//$_aprobado		=	$_detalle["paprobado"];				
		
		if($_pid){			
			$_pedidoobject	= DataManager::newObjectOfClass('TPedido', $_pid);
			$_pedidoobject->__set('IDResp',			$_SESSION["_usrid"]);
			$_pedidoobject->__set('Responsable',	$_SESSION["_usrname"]);	
			$_pedidoobject->__set('FechaAprobado',	date("Y-m-d H:i:s"));
			$_pedidoobject->__set('Aprobado',		$_estado);	
					
			if ($_pid) {
				$ID = DataManager::updateSimpleObject($_pedidoobject);
				if(empty($ID)){ 
					echo "Ocurrió un error y no se grabó el pedido $_nropedido. Pongase en contacto con el administrador de la web"; exit;
				}	
			}	
		} else {
			echo "X Error al intentar aprobar el pedido."; exit;
		}
	}
}

//******************************//
//	NOTIFICO RECHAZO DE PEDIDO	//
//******************************//
if($_estado == 2){	
	//*******************//
	// Armado del CORREO // 
	//*******************// 
	require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
	
	$_emailVen		= 	DataManager::getUsuario('uemail', $_pidusr);
	
	/*********************/
	$mail->From     =	"infoweb@neo-farma.com.ar"; //mail del emisor 
	$mail->FromName	=	"InfoWeb GEZZI";	//"Vendedor: ".$_SESSION["_usrname"];
	$mail->Subject 	=	"Datos de negociacion del pedido web ".$_nropedido.", guarde este email.";
	
	//header And footer
	include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
	
	$_total	= '
		<html>
			<head>
				<title>Notificación de Negociación</title>
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
									Su <strong>SOLICITUD DE APROBACI&Oacute;N DE NEGOCIACI&Oacute;N</strong> fue rechazada.<br />
								<div />
							</td >
						</tr> 
						
						<tr bgcolor="#597D92"> 
							<td>
								<div class="texto" style="color:#FFFFFF; font-size:14; font-weight: bold;">
									<strong>Estos son los datos de negociaci&oacute;n</strong>
								</div>
							</td> 
						</tr> 
						
						<tr> 
							<td height="95" valign="top">
								<div class="texto">						
									<table width="600px" style="border:1px solid #597D92">
										<tr>
											<th rowspan="2" align="left" width="100">				
												Nro de Pedido:<br />
												Estado:
											</th>	
											<th rowspan="2" align="left" width="250">
												'.$_nropedido.'<br />
												RECHAZADO	
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
										Por cualquier consulta del estado de su pedido, p&oacute;ngase en contacto con el encargado de aprobaciones.<br />
									</font>
																					
									<div style="color:#000000; font-size:12px;"> 
										<strong>El pedido quedar&aacute; en su listado de pedidos pendientes hasta que ser eliminado por el solicitante.</strong></a></br></br>
									</div>		
								</div>
							</td> 
						</tr>  		
						
						<tr align="center" class="saludo">
							<td valign="top">
								<div class="saludo">									
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
	$mail->AddAddress($_emailVen, "$_emailVen"); 	
	$mail->AddAddress($_email, "$_email"); //'Estado de Negociaci&oacute;n del pedido '.$_nropedido
	$mail->AddBCC("infoweb@neo-farma.com.ar", "Infoweb");
	$mail->AddBCC("controldegestion@neo-farma.com.ar", "Control de Gestion");
	if(!$mail->Send()) {
		//Si el envío falla, que mande otro mail indicando que la solicittud no fue correctamente enviada?=?
		echo 'Fallo en el envío de la notificación de rechazo de negociación';
		exit;
	}
	
	echo "2"; exit; 
}			

echo "1"; exit; 

?>