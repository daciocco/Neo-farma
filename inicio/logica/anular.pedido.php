<?php
 session_start();

 $_idcliente	= (isset($_POST['idcliente2']))	? $_POST['idcliente2'] : NULL;
 $_idpedido		= (isset($_POST['idpedido']))	? $_POST['idpedido'] : NULL; 
 $_motivo		= (isset($_POST['motivo']))		? $_POST['motivo'] : NULL; 

require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
 $mail    			= new PHPMailer();
 $mail->From     	= "infoweb@neo-farma.com.ar"; //$_SESSION["_usremail"];
 $mail->FromName 	= "Información Neo-farma"; //Vendedor: ".$_SESSION["_usrname"]
 $mail->Subject 	= "Solicitud de ANULACIÓN de pedido.";
 
 $_total= "
		Hola, se envían los datos de solicitud de ANULACI&Oacute;N de pedido.<br /><br />
	<table border='1' cellpadding='10' width='500px'>
		<tr>
			<th colspan='2'>
				Vendedor:".$_SESSION["_usrname"]."<br />
				E-mail:  ".$_SESSION["_usremail"]."<br />
			</th>				
		</tr>
		<tr>
			<th rowspan='2' align='right' width=450px>				
				Nro. Cliente:<br />
				Id Pedido Web:<br />
				Motivo:
			</th>	
			<th rowspan='2' align='left' width=450px>
				".$_idcliente."<br />
				".$_idpedido."	<br />
				".$_motivo."	<br />
			</th>
		</tr>	
	</table>
<br />Saludos."; 
 
 $mail->msgHTML($_total);
 $mail->AddAddress("pedidos@neo-farma.com.ar", "Solicitud de ANULACIÓN de pedido");
 $mail->AddAddress("infoweb@neo-farma.com.ar"); 
 $mail->AddAddress($_SESSION["_usremail"], "Solicitud de ANULACIÓN(comprobante)"); 

 if(!$mail->Send()) {
   	echo 'Fallo en el envío';
 } else {
 	$_goURL = "/pedidos/index.php";
 	header('Location:'.$_goURL);
 }
 exit;
?>
