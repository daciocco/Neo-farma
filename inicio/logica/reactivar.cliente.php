<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 $_idcliente= (isset($_POST['idcliente']))	? $_POST['idcliente'] : null;
 $_ruteo	= (isset($_POST['ruteo']))		? $_POST['ruteo'] : null;
 $_rs		= (isset($_POST['rs']))			? $_POST['rs'] : null;
 $_email 	= (isset($_POST['email'])) 		? strtolower($_POST['email']) : null; 
 
 /***************************************************/
 /* Uso datos de SESSION del Vendedor para el correo*/
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
 $mail->From     	= $_SESSION["_usremail"];
 $mail->FromName 	= "Vendedor: ".$_SESSION["_usrname"];
 $mail->Subject 	= "Solicitud de REACTIVACIÓN de Cliente.";
 
//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
 
 $_total= '
	<html>
		<head>
			<title>Notificación de Solicitud</title>
			<style type="text/css"> <!--
				body {
					text-align: center;
				}
				.texto {
					float: left;
					height: auto;
					width: 350px;
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
					width: 250px;
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
				} -->
			</style>
		</head>
		<body>	
			<table width="750" border="0" align="center">
				<tr>
					<td>'.$cabecera.'</td>
			  	</tr>
			  	<tr>
				
				
					<td>
						<p>
							<div class="texto" style="width:500px">
								<font face="Geneva, Arial, Helvetica, sans-serif" size="2">
									Estimad@, se envían los datos de <strong>SOLICITUD DE REACTIVACIÓN</strong> del siguiente cliente:
								</font>
							</div>
						</p>
						
						<div class="texto" align="center">
							<p>
								<table width="500px" style="border:2px solid #597D92">
									<tr>
										<th colspan="2" style="border:2px solid #597D92">
											Zonas: 		'.$_SESSION["_usrzonas"].'<br />
											Vendedor: 	'.$_SESSION["_usrname"].'<br />
											'.$_SESSION["_usremail"].'<br />
										</th>				
									</tr>
									<tr>
										<th rowspan="2" align="right" width="450px" style="border:2px solid #597D92">				
											Nro. Cliente:<br />
											Razón Social:<br />
											Rutéo:<br />
											E-mail:		
										</th>	
										<th rowspan="2" align="left" width="450px" style="border:2px solid #597D92">
											'.$_idcliente.'<br />
											'.$_rs.'<br />
											'.$_ruteo.'<br />
											'.$_email.'			
										</th>
									</tr>	
								</table>
							</p>
							
							<p>
								<font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif">
									Por cualquier consulta, póngase en contacto con el Administrador de la Web del Sector de Sistemas al 4555-3366 de lunes a viernes de 10 a 17.00 horas o por email escribiendo a: <a href="mailto:diegocioccolanti@neo-farma.com.ar">diegocioccolanti@neo-farma.com.ar</a>
								</font>
							</p>
						</div>
					</td>
				</tr>
				<tr align="center" class="saludo">
					<td valign="top">
						<div class="saludo">Los Saludamos atentamente,<br />					 
							Dpto. de Sistemas - NEO FARMA S.A.
						</div>
					</td>
				</tr>
				<tr align="right">
					<td valign="top">'.$pie.'</td>
				</tr>			  
			</table>
		</body>
	</html>
'; 
 
 $mail->msgHTML($_total);
 $mail->AddAddress("pedidos@neo-farma.com.ar");
 $mail->AddAddress("infoweb@neo-farma.com.ar"); 
 $mail->AddAddress("controldegestion@neo-farma.com.ar");
 $mail->AddAddress($_SESSION["_usremail"]); 

 if(!$mail->Send()) {
   	echo 'Fallo en el envío';
 } else {
	//*******************************************************************
	//Grabo datos en cambios_cliente de pedido de reactivación
	$_cambioscliobject	= DataManager::newObjectOfClass('TCambiosCliente');
	$_cambioscliobject->__set('ID', 			$_cambioscliobject->__newID());
	$_cambioscliobject->__set('FechaSolicitud',	date("Y-m-d"));
	$_cambioscliobject->__set('Usuario',		$_SESSION["_usrid"]);
	$_cambioscliobject->__set('Cliente',		$_idcliente);
	$_cambioscliobject->__set('Estado',			"REACTIVACION");
	$ID = DataManager::insertSimpleObject($_cambioscliobject);	
	//*******************************************************************
 	$_goURL = "/pedidos/index.php"; //?sms=4
 	header('Location:'.$_goURL);
 }
 exit;
?>
