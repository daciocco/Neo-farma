<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.phpmailer.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.smtp.php" );

try {
	$mail    			= new PHPMailer();
	$mail->isSMTP();
	$mail->Host 		= 'mail.neo-farma.com.ar';
	$mail->Port 		= 587;
	$mail->SMTPAuth 	= true;
	$mail->SMTPSecure 	= 'tls'; 
	$mail->SMTPOptions 	= array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	$mail->Username 	= 'infoweb@neo-farma.com.ar';
	$mail->Password 	= '';
	$mail->SMTPDebug 	= 0;  //2 es el recomendado
} catch (Exception $e) {
	echo 'Excepción Mail: ',  $e->getMessage(), "\n";
}


?>
