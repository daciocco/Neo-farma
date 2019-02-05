<?php
 session_start(); 
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M"){ 	
	echo 'SU SESION HA EXPIRADO.'; exit;
 }

 
 $_nro_rendicion	=	$_REQUEST['nro_rendicion'];
 
//******************//	
//		CONTROL		//
//******************// 
//Controlo que los recibos cargados sean consecutivos para aceptar el envío
/*$_detrendiciones	=	DataManager::getDetalleRendicion($_SESSION["_usrid"], $_nro_rendicion);		
if (count($_detrendiciones)) {
	$_talAnt = 0;
	$_recAnt = 0;
	foreach ($_detrendiciones as $k => $_detrend){
		$_detrend		=	$_detrendiciones[$k];
		$_rendTal		= 	$_detrend['Tal'];
		$_rendRnro		= 	$_detrend['RNro'];
		
		if($_talAnt == 0){$_talAnt = $_rendTal;}
		if($_recAnt == 0){$_recAnt = $_rendRnro - 1;}
		
		if ($_talAnt == $_rendTal){
			if ($_recAnt != ($_rendRnro - 1)){
				echo "El Talonario: $_rendTal - Recibo: ".($_rendRnro - 1).", no fue ingresado para enviar la rendición."; exit;
			}
		}else{
			$_talAnt = $_rendTal;
			if ($_recAnt != ($_rendRnro - 1)){
				echo "2_ El Talonario: $_rendTal - Recibo: ".($_rendRnro - 1).", no fue ingresado para enviar la rendición."; exit;
			}
		}		
	}
}*/
 
//*******************************************	
//		PARA GRABAR LA FECHA DE ENVÍO
//******************************************* 
//Consultar si la rendición fue enviada.
$_rendiciones	=	DataManager::getRendicion($_SESSION["_usrid"], $_nro_rendicion, '1');
if (count($_rendiciones)){
	foreach ($_rendiciones as $k => $_rendicion) {
		$_rendid		=	$_rendicion['rendid'];
	
		$_rendicionbject= 	DataManager::newObjectOfClass('TRendicion', $_rendid);
		$_rendicionbject->__set('Envio',	date('Y-m-d'));
 		$_rendicionbject->__set('Activa',	0);
		$ID = DataManager::updateSimpleObject($_rendicionbject);
		
		//**********************************//
		// CORREO para notificar el envío
		//**********************************//
		if ($ID){	
			require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
			$mail->From     	= $_SESSION["_usremail"];
 			$mail->FromName 	= "Vendedor: ".$_SESSION["_usrname"];
 			$mail->Subject 		= "Rendicion de Cobranza Nro. ".$_nro_rendicion;
			
			//header And footer
			include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
		
			$_total= '
					<html>
						<head>
							<title>Notificación de Envío</title>
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
							<table width="600" border="0" align="center">
								<tr>
									<td>'.$cabecera.'</td>
								</tr>
								<tr>
									<td>
										<p>
											<div class="texto" style="width:500px">
												<font face="Geneva, Arial, Helvetica, sans-serif" size="2">
													Estimad@, se envían los datos de <strong>RENDICIÓN DE COBRANZAS Nro $_nro_rendicion</strong> de ".$_SESSION["_usrname"]."
												</font>
											</div>
										</p>
										
										<div class="texto" align="center">
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
			$mail->AddAddress("cobranzas@neo-farma.com.ar", "Rendicion de Cobranza Nro. $_nro_rendicion");
			$mail->AddCC("pedidos@neo-farma.com.ar", "Rendicion de Cobranza Nro. $_nro_rendicion");
			$mail->AddCC("antonellatorres@neo-farma.com.ar", "Rendicion de Cobranza Nro. $_nro_rendicion");
			//$mail->AddAddress("diegocioccolanti@neo-farma.com.ar", "Rendicion de Cobranza Nro. $_nro_rendicion");
			
			if(!$mail->Send()) {
				echo 'Rendición enviada, pero falló la notificación del envío'; exit;
			} else {
				echo "1"; exit;
			}		
 		}
	}	
} else {
	echo "No puede enviar la rendición porque ya fue enviada con anterioridad."; exit;
}

?>
 