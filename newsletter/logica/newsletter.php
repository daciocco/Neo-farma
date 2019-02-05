<?php
 session_start(); 
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
 if ($_SESSION["_usrrol"]!="A"){
 	$_nextURL = sprintf("%s", "/pedidos/");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }
 
 //**********************************
 //envío por MAIL
 //**********************************
 
//lista clientes activos de empresa 1 neo-farma
$pag			= 0; 
$rows			= 9999;
$empresa		= 1;
$mostrarTodos 	= 1;
$newsletter 	= '<img src="../felicesfiestas-neo.jpg" width="600"  height="600"/>';



$clientes	= DataManager::getClientes($pag, $rows, $empresa, $mostrarTodos); //la última página vendrá incompleta
for( $k=0; $k < count($clientes); $k++ ) {
	if ($k > 1150){ //&& $k < 1152 //El if se usa por si el proceso de envío se cuelga, para continuar desde el último K enviado.
	//Son 120 por hora
		$cliente 	= $clientes[$k];		
		$idCliente	= $cliente['cliidcliente'];	
		//$correoNombre = $cliente['clinombre'];				
		$correo		= $cliente['clicorreo'];	 //"diegocioccolanti@neo-farma.com.ar";//
		
		if (!empty($correo)){	
			require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
			$mail->From     	= "mailing@neo-farma.com.ar";
			$mail->FromName 	= "Neo-farma - Distrubuidor de Laboratorio GEZZI";			
			$mail->Subject 		= "Felices Fiestas!";
			
			$mensaje = "&iexcl;Felices Fiestas! Es un deseo de Neo-farma de Laboratorio GEZZI";
		
			$total	= '
				<html>
					<head>
						<title>Email de Empresa</title>
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
					<body style="text-align: center;">	
						<div align="center">
							<table width="600" border="0" cellspacing="1"> 								
								<tr>
									<td> 
										<div class="texto" style="float: left;
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
								margin-left: 0px;">
											'.$mensaje.'							
										<div />
									</td >
								</tr>
								
								<tr>
									<td>'.$newsletter.'</td>
								</tr>
								
								<tr align="center" class="saludo" style="width: 350px;
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
								font-size: 12px;">
									<td valign="top">
										<div class="saludo" align="left" style="width: 350px;
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
								font-size: 12px;">
											Gracias por confiar en nosotros.<br/>
											Le saludamos atentamente,
										</div>
									</td>
								</tr>
							</table>
						<div/>
					</body>
			';
			
			$mail->msgHTML($total); 
			//$mail->AddAddress('diegocioccolanti@neo-farma.com.ar', "PARA" $para);
			//$mail->AddAddress($correo);
						
			$mail->AddBCC($correo); //, $correoNombre);
			//$mail->AddBCC("gimenagomez@neo-farma.com.ar");
			
			echo "</br>".($k+1)." de ".count($clientes)." clientes enviado. </br>";
			
			//Sobreescribe en una tabla de servido, el último K recorrido por si luego hay que hacerlo manual. 		
			if(!$mail->Send()) {
				echo "</br>Fallo de envío del cliente: ".$idCliente."</br>";
			}	
			echo "</br>";
									
			sleep(30);
			
		} else {
			echo "</br>".($k+1)." de ".count($clientes)." Cliente con correo VACIO. </br>";
		}///fin control correo vacío
	}
} 
 
 echo "</br></br> ¡SE HAN ENVIADO TODOS LOS NEWSLATTERS!</br></br>"; 
 
 ?> <a href="<?php echo $_SERVER['HTTP_REFERER']."/"; ?>">Volver</a><?php echo "</br></br></br>"; 
 
 exit;
?>