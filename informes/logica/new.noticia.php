<?php
 session_start(); 
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
 	$_nextURL = sprintf("%s", "/pedidos/informes/");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }
 
 //**********************************
 //envío por MAIL la misma información
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
 $mail->From     	= "mailing@neo-farma.com.ar";
 $mail->FromName 	= "Notificacion automatica."; //"Notificaci&oacute;n autom&aacute;tica.";
 $mail->Subject 	= "Se han actualizado informes web";
 
  $_total= "Hola, se han realizado actualizaciones de precios y/o informes.<br />
  			Para ver m&aacute;s accede a la web para controlar lo que necesites.<br /><br />
	
			Saludos.<br /><br />			
			
			(no respondas a &eacute;ste mail)";

 $mail->msgHTML($_total);
 
 $_usuarios	= DataManager::getUsuarios( 0, 0, 1, NULL, '"V"');
 for( $k=0; $k < count($_usuarios); $k++ ) {
 	$_usuario 	= $_usuarios[$k];
	$_email		= $_usuario['uemail'];
	$_rol		= $_usuario['urol'];
	
	if ($_rol == "V"){
		$mail->AddAddress($_email, "Noticias Web");
	}	
 }
 
 $mail->AddAddress('diegocioccolanti@neo-farma.com.ar', "Noticias Web");
 
 if(!$mail->Send()) {
   	echo 'Fallo en el envío';
 } else {
 	$_goURL = "../index.php?sms=6";
 	header('Location:'.$_goURL);
	exit;
 }
 exit;
?>