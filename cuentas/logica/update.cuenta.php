<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M") {
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
	exit;
}

$ctaId 				= (isset($_POST['ctaid']))			? $_POST['ctaid']		: NULL;
$tipo				= (isset($_POST['tiposelect']))		? $_POST['tiposelect']	: NULL;
$idCuenta 			= (isset($_POST['idCuenta']))		? $_POST['idCuenta'] 	: NULL;
$empresa			= (isset($_POST['empselect']))		? $_POST['empselect'] 	: NULL;
$estado				= (isset($_POST['estado']))			? $_POST['estado']		: NULL;
$nombre 			= (isset($_POST['nombre']))			? $_POST['nombre'] 		: NULL;
$asignado 			= (isset($_POST['asignado']))		? $_POST['asignado'] 	: NULL;
$zona				= (isset($_POST['zona']))			? $_POST['zona'] 		: NULL;
$ruteo 				= (isset($_POST['ruteo']))			? $_POST['ruteo'] 		: NULL;
$empleados 			= (isset($_POST['empleados']))		? $_POST['empleados'] 	: NULL;
$cuit 				= (isset($_POST['cuit']))			? $_POST['cuit'] 		: NULL;
$categoriaComercial = (isset($_POST['categoriaComer']))	? $_POST['categoriaComer']: NULL;
$categoriaIVA		= (isset($_POST['categoriaIva']))	? $_POST['categoriaIva']: NULL;
$condicionPago		= (isset($_POST['condicionPago']))	? $_POST['condicionPago'] : NULL;
$agenteRetPerc		= (isset($_POST['agenteRetPerc']))	? $_POST['agenteRetPerc'] : NULL;
$telefono 			= (isset($_POST['telefono']))		? $_POST['telefono'] 	: NULL;
$direccionEntrega 	= (isset($_POST['direccionEntrega'])) ? $_POST['direccionEntrega'] : NULL;
$correo 			= (isset($_POST['correo']))			? $_POST['correo'] 		: NULL;
$web 				= (isset($_POST['web']))			? $_POST['web'] 		: NULL;
$observacion 		= (isset($_POST['observacion']))	? $_POST['observacion']: NULL;
$imagen1 			= (isset($_POST['nombreFrente']))	? $_POST['nombreFrente']: NULL;
$imagen2 			= (isset($_POST['nombreInterior']))	? $_POST['nombreInterior'] : NULL;
//Domicilio
$provincia 			= (isset($_POST['provincia']))		? $_POST['provincia'] 	: NULL;
$localidad 			= (isset($_POST['localidad']))		? $_POST['localidad'] 	: NULL;
$direccion 			= (isset($_POST['direccion']))		? $_POST['direccion'] 	: NULL;
$nro 				= (isset($_POST['nro']))			? $_POST['nro'] 		: NULL;
$piso 				= (isset($_POST['piso']))			? $_POST['piso'] 		: NULL;
$dpto 				= (isset($_POST['dpto']))			? $_POST['dpto'] 		: NULL;
$cp 				= (isset($_POST['codigopostal']))	? $_POST['codigopostal']: NULL;
$longitud 			= (isset($_POST['longitud']))		? $_POST['longitud'] 	: NULL;
$latitud 			= (isset($_POST['latitud']))		? $_POST['latitud'] 	: NULL;
//Retenciones y Percepciones
$categIva 			= (isset($_POST['categoriaIva']))	? $_POST['categoriaIva'] : NULL;
$agenteRetPerc 		= (isset($_POST['agenteRetPerc']))	? $_POST['agenteRetPerc'] : NULL;
$ctoCompras 		= (isset($_POST['ctoCompras']))		? $_POST['ctoCompras'] 	: NULL;
$ctoImpositivo 		= (isset($_POST['ctoImpositivo']))	? $_POST['ctoImpositivo'] : NULL;
$ctoCobranza 		= (isset($_POST['ctoCobranza']))	? $_POST['ctoCobranza'] : NULL;
$ctoRecExp 			= (isset($_POST['ctoRecExp']))		? $_POST['ctoRecExp'] 	: NULL;
$cadena 			= (isset($_POST['cadena']))			? $_POST['cadena'] 		: NULL;
$tipoCadena			= (isset($_POST['tipoCadena']))		? $_POST['tipoCadena'] 	: NULL;
$tipoCadenaNombre	= ($tipoCadena == 1) ? 'Sucursal' 	: '';
$nroIngBrutos		= (isset($_POST['nroIngBrutos']))	? $_POST['nroIngBrutos'] : NULL;
//Arrays
$arrayCtaIdDrog		= (isset($_POST['cuentaId']))		? $_POST['cuentaId'] 	: NULL;
$arrayCtaCliente	= (isset($_POST['cuentaIdTransfer']))? $_POST['cuentaIdTransfer'] :	NULL;
$fechaAlta			= (isset($_POST['fechaAlta']))		? $_POST['fechaAlta'] 	: NULL;
$activa				= (isset($_POST['activa']))			? $_POST['activa'] 		: NULL;

$lista				= (isset($_POST['listaPrecio']))	? $_POST['listaPrecio']	: NULL;
$listaNombre		= DataManager::getLista('NombreLT', 'IdLista', $lista);

//-------------------------------------
// Controles obligatorios para todos  
//Un prospecto puede no estar obligado a ingresar los siguientes datos
if($tipo != "PS" && $tipo != "O"){
	if(empty($asignado)){ echo "La cuenta debe tener un usuario asignado. "; exit; }
	if (!dac_validarCuit($cuit)) { echo "El cuit es incorrecto."; exit; } 
	$cuit = dac_corregirCuit($cuit);
}

if (empty($empresa)) { echo "No se pudo cargar el dato de empresa"; exit; }
$empresaNombre	=	DataManager::getEmpresa('empnombre', $empresa);
if (empty($estado)) { echo "Seleccione un estado."; exit; }
if (empty($tipo)) { echo "Seleecione el tipo de cuenta."; exit; }
if(empty($nombre)){ echo "Indique la Raz&oacute;n Social."; exit; }
if(!empty($empleados) && !is_numeric($empleados)){ echo "Indique cantidad de empleados."; exit; }
if(!empty($categoriaIVA)){
	$categoriasIva	= DataManager::getCategoriasIva(1); 
	if (count($categoriasIva)) { 
		foreach ($categoriasIva as $k => $catIva) {
			$catIdcat	=	$catIva["catidcat"];
			$catNombre	=	$catIva["catnombre"];
			if($catIdcat == $categoriaIVA){
				$catIvaNombre = $catNombre;	
			}
		}                              
	}
}

//La zona debe corresponder a la zona relacionada a la localidad. Si no es la misma deberá solicitarlo luego como excepción para que se modifique o aclararlo en la observación para administración
if(empty($provincia) || empty($localidad) || empty($longitud) || empty($latitud)){
	echo "Complete los datos de domicilio."; exit;
} else {
	$zonaLocalidad = DataManager::getLocalidad('loczonavendedor', $localidad);
	if($zonaLocalidad != $zona){
		$nombreLocalidad	= DataManager::getLocalidad('locnombre', $localidad);
		echo "La zona correspondiente a la localidad $nombreLocalidad es la $zonaLocalidad. En caso de desear una diferente, deberá solicitarlo en la observación para cargar luego como excepción."; exit;
	} 
}

if(!empty($correo)){
	$correo = trim($correo, ' ');
	if (!dac_validateMail($correo)) {
		echo "El corr&eacute;o es incorrecto."; exit;
	}
}

if(empty($correo) && empty($telefono)){ echo "Debe indicar un correo o un tel&eacute;fono de contacto."; exit; }	

//***************************************//
//Control de cuentas transfers cargados  // //relacionadas (para droguerías transfers)
if(!empty($arrayCtaIdDrog)){	
	if(count($arrayCtaIdDrog)){ //$arrayCtaIdDrog = implode(",", $arrayCtaIdDrog);
		for($i = 0; $i < count($arrayCtaIdDrog); $i++){
			if (empty($arrayCtaCliente[$i])){
				echo "Indique cliente transfer para la cuenta ".$arrayCtaIdDrog[$i]; exit;
			}
		}
	}
	//Controla Droguerías duplicadas
	if(count($arrayCtaIdDrog) != count(array_unique($arrayCtaIdDrog))){
		echo "Hay droguer&iacute;as duplicadas."; exit;
	}	
}

$frenteNombre	=	$_FILES["frente"]["name"]; 
$frentePeso		= 	$_FILES["frente"]["size"]; 
$interiorNombre	=	$_FILES["interior"]["name"]; 
$interiorPeso	= 	$_FILES["interior"]["size"]; 
if ($frentePeso != 0){
	if($frentePeso > MAX_FILE){ 
		echo "El archivo Frente no debe superar los 4 MB"; exit;
	}
}

if ($interiorPeso != 0){
	if($interiorPeso > MAX_FILE){
		echo "El archivo Interior no debe superar los 4 MB"; exit;
	}
}

//control de otros arhivos	
$hayArchivos	= 	0;
if (isset($_FILES['multifile'])) {
	$myFiles 	= 	$_FILES['multifile'];	
	$fileCount 	= 	count($myFiles["name"]);		
	if($fileCount > 5){ echo "Demasiados archivos adjuntos"; exit; }
	
	for ($i = 0; $i < $fileCount; $i++) { 		
		if($myFiles['error'][$i] == UPLOAD_ERR_OK){
			$hayArchivos	= 	1;
			$nombreOriginal	=	$myFiles['name'][$i];
			$nombreTemporal	=	$myFiles['tmp_name'][$i];
			$peso	 		=	$myFiles['size'][$i];				
			if($peso > MAX_FILE){
				echo "El archivo ".$nombreOriginal." no debe superar los ".(MAX_FILE / 1024)." MB"; exit;
			}	
			if(!dac_fileFormatControl($myFiles["type"][$i], 'imagen')){
				echo "El archivo ".$nombreOriginal." debe tener formato imagen o pdf."; exit;		
			}			
		}				
		if ($myFiles['error'][$i] != 0 && $myFiles['error'][$i] != 4){ 
			echo 'No se pudo subir el archivo <b>'.$nombreOriginal.'</b> debido al siguiente Error: '.$myFiles['error'][$i];
		}
	}
}

if($tipo != 'O'){
	if (empty($longitud) || empty($latitud)) { echo "Indique el domicilio correcto usando el &iacutecono del mapa para registrar la longitud y latitud que corresponda."; exit; }
	if (!empty($nro) && !is_numeric($nro)) { echo "Verifique n&uacute;mero en la direcci&oacute;n de la cuenta."; exit; }
}

$provinciaNombre	= DataManager::getProvincia('provnombre', $provincia);
$localidadNombre	= DataManager::getLocalidad('locnombre', $localidad);

$zonaEntrega		= (empty($localidad)) ? 0 : DataManager::getLocalidad('loczonaentrega', $localidad);

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");

//---------------------
//	TIPO DE CUENTA		
$tipoDDBB	=	dac_consultarTipo($ctaId);
$estadoDDBB	= 	dac_consultarEstado($idCuenta, $empresa);

//Controlo cambio tipo cuenta
dac_controlesCambioTipoCuenta($tipoDDBB, $tipo, $estadoDDBB, $estado);
	
switch($tipo){	
	//	cliente	//	
	case 'C':
	case 'CT':
		if($estadoDDBB == 'CambioRazonSocial' || $estadoDDBB == 'CambioDomicilio' || $estadoDDBB == 'SolicitudBaja'){
			echo "La cuenta ya no puede ser modificada."; exit;
		}		
		//Controles Generales	
		if(empty($zona) || $zona==199){ echo "Indique la zona del vendedor."; exit; }
		if(empty($ruteo)){ echo "Indique el ruteo."; exit; }
		if(empty($lista)){ echo "Indique lista de precios."; exit; }
		if(empty($agenteRetPerc)){ echo "Indique si es agente de Retenci&oacute;n/Percepci&oacute;n."; exit; }
		if(empty($categoriaIVA)){ echo "Indique categor&iacute;a de iva."; exit; }		
				
		if(empty($correo)){ 
			echo "Debe indicar un correo"; exit;			
		} else {
			$correo = trim($correo, ' ');
			if (!dac_validateMail($correo)) {
				echo "El corr&eacute;o es incorrecto."; exit;
			}
		}				
		if(empty($telefono)){ echo "Debe indicar un tel&eacute;fono de contacto."; exit; }	
		if (empty($cp) || !is_numeric($cp)) { echo "Indique c&oacute;digo postal."; exit; }
		
		//----------//
		//	CORREO	//
		$from 			=	$_SESSION["_usremail"];
		$fromName		=	"Usuario ".$_SESSION["_usrname"];
		$cuerpoMail_3 	= 	'	
					<tr> 
                        <td>
                            <div class="texto">
                                <font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif">
                                    Por cualquier consulta, p&oacute;ngase en contacto con la empresa al 4555-3366 de Lunes a Viernes de 10 a 17 horas.
                                </font>
                                                                                
                                <div style="color:#000000; font-size:12px;"> 
                                    <strong>Si no recibe informaci&oacute;n del alta en 72 horas h&aacute;biles, podr&aacute; reclamar reenviando &eacute;ste mail a reclamosweb@neo-farma.com.ar </strong></br></br>
                                </div>		
                            </div>
                        </td> 
                    </tr>';
		
		switch($estado) {
			case 'SolicitudAlta':
				$est 		  	= 5;
				$estadoNombre 	= 'Solicitud de Alta';				
				
				//Busca las categorías de la lista de precios seleccionadas y consotrla que exista la condicion Comercial seleccionada en la cuenta
				$categoriasListaPrecios	= DataManager::getLista('CategoriaComercial', 'IdLista', $lista);
				if(!empty($categoriasListaPrecios)){
					$categoriasComerciales = explode(",", $categoriasListaPrecios);
					if(!in_array($categoriaComercial, $categoriasComerciales)) {						
						$categoriaNombre = DataManager::getCategoriaComercial('catnombre', 'catidcat', $categoriaComercial);						
						echo "La Categoría Comercial '$categoriaComercial - $categoriaNombre' NO corresponde a la Lista de Precios '$listaNombre'"; exit;
					}
				}
				//--------------------------------
								
				if(dac_controlesAlta($cadena, $ctaId, $empresa, $cuit, $estadoDDBB, $zona, $tipoDDBB, $tipo)){
					$idCuenta 	= dac_crearNroCuenta($empresa, $zona);					
					$fechaAlta	= "2001-01-01 00:00:00";
				};
				
				$cuerpoMail_2 	= '	
					<tr>
                        <td valign="top">
                            <div class="texto">						
                                <table width="580px" style="border:1px solid #117db6">
                                    <tr>
                                        <th align="left" width="200"> Usuario </th>	
                                        <td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuenta</th>	
                                        <td align="left" width="400">'.$idCuenta.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Zona - Ruteo</th>	
                                        <td align="left" width="400">'.$zona." - ".$ruteo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$_SESSION["_usremail"].'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Fecha de pedido</th>	
                                        <td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
                                    </tr>													
                                    <tr>
                                        <th align="left" width="200">&iquest;Es agente de retenci&oacute;n?</th>	
                                        <td align="left" width="400">'.$agenteRetPerc.'</td>
                                    </tr>
                                    <tr style="color:#FFFFFF; font-weight:bold;">
                                        <th colspan="2" align="center">	
                                            Datos de la Cuenta
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Raz&oacute;n Social</th>	
                                        <td align="left" width="400">'.$nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuit</th>	
                                        <td align="left" width="400">'.$cuit.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Categor&iacute;a Iva</th>	
                                        <td align="left" width="400">'.$catIvaNombre.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Lista de Precios</th>	
                                        <td align="left" width="400">'.$listaNombre.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Cadena</th>	
                                        <td align="left" width="400">'.$cadena.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Tipo Cadena</th>	
                                        <td align="left" width="400">'.$tipoCadenaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Provincia</th>	
                                        <td align="left" width="400">'.$provinciaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Localidad</th>	
                                        <td align="left" width="400">'.$localidadNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Domicilio</th>	
                                        <td align="left" width="400">'.$direccion.' '.$nro.' '.$piso.' '.$dpto.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$correo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono</th>	
                                        <td align="left" width="400">'.$telefono.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Web</th>	
                                        <td align="left" width="400">'.$web.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200"> Observaci&oacute;n </th>	
                                        <td align="left" width="400"> '.$observacion.' </td>
                                    </tr>	
                                    
                                    <tr>
                                        <th colspan="2" align="center">	
                                            Datos a Completar
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">	
                                            Habilitaci&oacute;n<br />
                                            Director T&eacute;cnico<br />
                                            Autorizaci&oacute;n alta
                                        </th>	
                                        <td align="left" width="400"></td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </td> 
                    </tr>
				';
				break;		
			case 'CambioRazonSocial':
				$est = 8;
				$estadoNombre 	= 'Cambio de Raz&oacuten Social';
				
				if(!$hayArchivos){							
					echo "Debe adjuntar documentaci&oacute;n para ".$estadoNombre."."; exit;
				}

				//La cuenta pasa automáticamente a ZONA INACTIVA
				$observacion2 	= "BAJA por ".$estado.". del CUIT ".$cuit.". ".$observacion;
				$ctaObject	= DataManager::newObjectOfClass('TCuenta', $ctaId);
				$ctaObject->__set('Estado'			, $estado);
				$ctaObject->__set('Zona'			, 95);
				$ctaObject->__set('Observacion'		, $observacion2);
				$ctaObject->__set('Activa'			, 0);
				$ctaObject->__set('CUIT'			, '');
				$ctaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
				$ctaObject->__set('LastUpdate'		, date("Y-m-d H:i:s"));
				// Procesar BAJA en HIPERWIN
				$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaId);
				$ctaObjectHiper->__set('Estado'		, $estado);
				$ctaObjectHiper->__set('Zona'		, 95);
				$ctaObjectHiper->__set('Observacion', $observacion2);
				$ctaObjectHiper->__set('Activa'		, 0);
				$ctaObjectHiper->__set('CUIT'		, '');
				$ctaObjectHiper->__set('UsrUpdate'	, $_SESSION["_usrid"]);
				$ctaObjectHiper->__set('LastUpdate'	,  date("Y-m-d H:i:s"));
				
				DataManagerHiper::updateSimpleObject($ctaObjectHiper, $ctaId);
				DataManager::updateSimpleObject($ctaObject);
				//----------------------------				
				
				//**********//
				//	CORREO	//
				require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );		
				$mail->From    	= $from;
				$mail->FromName	= $fromName;
				$mail->Subject 	= $estadoNombre." de Cuenta ".$tipo.", ".$nombre.". Guarde este email.";

				$headMail 		= '
					<html>
						<head>
							<title>'.$estadoNombre.' de CUENTA '.$tipo.'</title>
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
								Se env&iacute;an los datos de <strong>'.$estadoNombre.' de la CUENTA '.$tipo.'</strong> correspondiente a:<br/><br/>		
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
												<td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
											</tr>
											<tr>
												<th align="left" width="200">Cuenta</th>	
												<td align="left" width="400">'.$idCuenta.'</td>
											</tr>
											<tr>
												<th align="left" width="200">Zona - Ruteo</th>	
												<td align="left" width="400">95 - '.$ruteo.'</td>
											</tr>
											<tr>
												<th align="left" width="200">E-mail</th>	
												<td align="left" width="400">'.$_SESSION["_usremail"].'</td>
											</tr>
											<tr>
												<th align="left" width="200">Fecha de pedido</th>	
												<td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
											</tr>	
											<tr style="color:#FFFFFF; font-weight:bold;">
												<th colspan="2" align="center">	
													Datos de la Cuenta
												</th>	
											</tr>
											<tr>
												<th align="left" width="200">Nueva Raz&oacute;n Social</th>	
												<td align="left" width="400">'.$nombre.'</td>
											</tr>
											<tr>
												<th align="left" width="200">Nuevo Cuit</th>	
												<td align="left" width="400">'.$cuit.'</td>
											</tr>
											<tr>
												<th align="left" width="200"> Observaci&oacute;n </th>	
												<td align="left" width="400"> '.$observacion.' </td>
											</tr>
										</table>
									</div>
								</td> 
							</tr>
						';
				$pieMail 		= '	<tr align="center" class="saludo">
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
				$cuerpoMail2	= $headMail.$cuerpoMail_1.$cuerpoMail_2.$cuerpoMail_3.$pieMail;

				$mail->msgHTML($cuerpoMail2);

				$mail->AddBCC("infoweb@neo-farma.com.ar");	
				$mail->AddBCC("cuentas@neo-farma.com.ar");
				$mail->AddAddress($_SESSION["_usremail"], $estadoNombre.' de la cuenta '.$tipo.". Enviada");

				if($mail){
					if(!$mail->Send()) {
						echo 'Fallo en el env&iacute;o de notificaci&oacute;n por mail'; exit;
					}
				}			
				
				//******************//	
				//	Registro ESTADO	//
				dac_registrarEstado('TCuenta', $ctaId, $est, $tipo."_".$estado);

				//**********************//	
				//	Registro MOVIMIENTO	//
				$movimiento = 'CUENTA_'.$tipo;	
				$movTipo	= 'UPDATE';
				dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);
				
				$ctaId = 0;
				//Luego de modificar la cuenta actual, el envío pasa como Solicitud de alta
				$est 			= 5;
				$estado			= 'SolicitudAlta';
				$estadoNombre 	= 'Solicitud de Alta';				
				if(dac_controlesAlta($cadena, $ctaId, $empresa, $cuit, 'Cuenta Nueva', $zona, $tipoDDBB, $tipo)){
					$observacion= "ALTA por ".$estado.". de Cuenta ".$idCuenta.". ".$observacion;
					$idCuenta 	= dac_crearNroCuenta($empresa, $zona);
					$fechaAlta	= "2001-01-01 00:00:00";
				};	
				
				$cuerpoMail_2 	= '	
					<tr>
                        <td valign="top">
                            <div class="texto">						
                                <table width="580px" style="border:1px solid #117db6">
                                    <tr>
                                        <th align="left" width="200"> Usuario </th>	
                                        <td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuenta</th>	
                                        <td align="left" width="400">'.$idCuenta.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Zona - Ruteo</th>	
                                        <td align="left" width="400">'.$zona." - ".$ruteo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$_SESSION["_usremail"].'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Fecha de pedido</th>	
                                        <td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
                                    </tr>													
                                    <tr>
                                        <th align="left" width="200">&iquest;Es agente de retenci&oacute;n?</th>	
                                        <td align="left" width="400">'.$agenteRetPerc.'</td>
                                    </tr>
                                    <tr style="color:#FFFFFF; font-weight:bold;">
                                        <th colspan="2" align="center">	
                                            Datos de la Cuenta
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Raz&oacute;n Social</th>	
                                        <td align="left" width="400">'.$nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuit</th>	
                                        <td align="left" width="400">'.$cuit.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Categor&iacute;a Iva</th>	
                                        <td align="left" width="400">'.$catIvaNombre.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Lista de Precios</th>	
                                        <td align="left" width="400">'.$listaNombre.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Cadena</th>	
                                        <td align="left" width="400">'.$cadena.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Tipo Cadena</th>	
                                        <td align="left" width="400">'.$tipoCadenaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Provincia</th>	
                                        <td align="left" width="400">'.$provinciaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Localidad</th>	
                                        <td align="left" width="400">'.$localidadNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Domicilio</th>	
                                        <td align="left" width="400">'.$direccion.' '.$nro.' '.$piso.' '.$dpto.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$correo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono</th>	
                                        <td align="left" width="400">'.$telefono.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Web</th>	
                                        <td align="left" width="400">'.$web.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200"> Observaci&oacute;n </th>	
                                        <td align="left" width="400"> '.$observacion.' </td>
                                    </tr>	
                                    
                                    <tr>
                                        <th colspan="2" align="center">	
                                            Datos a Completar
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">	
                                            Habilitaci&oacute;n<br />
                                            Director T&eacute;cnico<br />
                                            Autorizaci&oacute;n alta
                                        </th>	
                                        <td align="left" width="400"></td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </td> 
                    </tr>
				';
					
				break;
			case 'CambioDomicilio':
				$est = 9;
				$estadoNombre 	= 'Cambio de Domicilio';
				
				if(!$hayArchivos){							
					echo "Debe adjuntar documentaci&oacute;n para ".$estadoNombre."."; exit;
				}
				
				//La cuenta pasa automáticamente a ZONA INACTIVA
				$observacion2 	= "BAJA por ".$estado.". Nueva Cuenta CUIT ".$cuit.". ".$observacion;
				$ctaObject	= DataManager::newObjectOfClass('TCuenta', $ctaId);
				$ctaObject->__set('Estado'			, $estado);
				$ctaObject->__set('Zona'			, 95);
				$ctaObject->__set('Observacion'		, $observacion2);
				$ctaObject->__set('Activa'			, 0);
				$ctaObject->__set('CUIT'			, '');
				$ctaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
				$ctaObject->__set('LastUpdate'		, date("Y-m-d H:i:s"));
				// Procesar BAJA en HIPERWIN
				$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaId);
				$ctaObjectHiper->__set('Estado'		, $estado);
				$ctaObjectHiper->__set('Zona'		, 95);
				$ctaObjectHiper->__set('Observacion', $observacion2);
				$ctaObjectHiper->__set('Activa'		, 0);
				$ctaObjectHiper->__set('CUIT'		, '');
				$ctaObjectHiper->__set('UsrUpdate'	, $_SESSION["_usrid"]);
				$ctaObjectHiper->__set('LastUpdate'	,  date("Y-m-d H:i:s"));
				
				DataManagerHiper::updateSimpleObject($ctaObjectHiper, $ctaId);
				DataManager::updateSimpleObject($ctaObject);
								
				//**********//
				//	CORREO	//
				require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
				$mail->From    = $from;
				$mail->FromName= $fromName;
				$mail->Subject = $estadoNombre." de Cuenta ".$tipo.", ".$nombre.". Guarde este email.";	

				$headMail 		= '
					<html>
						<head>
							<title>'.$estadoNombre.' de CUENTA '.$tipo.'</title>
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
								Se env&iacute;an los datos de <strong>'.$estadoNombre.' de la CUENTA '.$tipo.'</strong> correspondiente a:<br/><br/>		
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
                                        <td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuenta</th>	
                                        <td align="left" width="400">'.$idCuenta.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Zona - Ruteo</th>	
                                        <td align="left" width="400">'.$zona." - ".$ruteo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$_SESSION["_usremail"].'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Fecha de pedido</th>	
                                        <td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
                                    </tr>													
                                    <tr style="color:#FFFFFF; font-weight:bold;">
                                        <th colspan="2" align="center">	
                                            Datos de la Cuenta
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Raz&oacute;n Social</th>	
                                        <td align="left" width="400">'.$nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuit</th>	
                                        <td align="left" width="400">'.$cuit.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Provincia</th>	
                                        <td align="left" width="400">'.$provinciaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Localidad</th>	
                                        <td align="left" width="400">'.$localidadNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Domicilio</th>	
                                        <td align="left" width="400">'.$direccion.' '.$nro.' '.$piso.' '.$dpto.'</td>
                                    </tr>                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$correo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono</th>	
                                        <td align="left" width="400">'.$telefono.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200"> Observaci&oacute;n </th>	
                                        <td align="left" width="400"> '.$observacion.' </td>
                                    </tr>
                                </table>
                            </div>
                        </td> 
                    </tr>
				';
				$pieMail 		= '	<tr align="center" class="saludo">
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
				$cuerpoMail2 	= $headMail.$cuerpoMail_1.$cuerpoMail_2.$cuerpoMail_3.$pieMail;

				$mail->msgHTML($cuerpoMail2);

				$mail->AddBCC("infoweb@neo-farma.com.ar");	
				$mail->AddBCC("cuentas@neo-farma.com.ar");
				$mail->AddBCC("robertorodriguez@gezzi.com.ar");
				$mail->AddAddress($_SESSION["_usremail"], $estadoNombre.' de la cuenta '.$tipo.". Enviada");
				
				if($mail){
					if(!$mail->Send()) {
						echo 'Fallo en el env&iacute;o de notificaci&oacute;n por mail'; exit;
					}
				}
				
				//******************//	
				//	Registro ESTADO	//
				dac_registrarEstado('TCuenta', $ctaId, $est, $tipo."_".$estado);
								
				//*********************//	
				//	Registro MOVIMIENTO	//
				$movimiento = 'CUENTA_'.$tipo;	
				$movTipo	= 'UPDATE';
				dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);
				
				$ctaId = 0;
				
				//Luego de modificar la cuenta actual, el envío pasa como Solicitud de alta
				$est 			= 5;
				$estado			= 'SolicitudAlta';
				$estadoNombre 	= 'Solicitud de Alta';				
				if(dac_controlesAlta($cadena, $ctaId, $empresa, $cuit, 'Cuenta Nueva', $zona, $tipoDDBB, $tipo)){
					$observacion= "ALTA por ".$estado.". de Cuenta ".$idCuenta.". ".$observacion;
					$idCuenta 	= dac_crearNroCuenta($empresa, $zona);
					$fechaAlta	= "2001-01-01 00:00:00";
				};
				
				$cuerpoMail_2 	= '	
					<tr>
                        <td valign="top">
                            <div class="texto">						
                                <table width="580px" style="border:1px solid #117db6">
                                    <tr>
                                        <th align="left" width="200"> Usuario </th>	
                                        <td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuenta</th>	
                                        <td align="left" width="400">'.$idCuenta.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Zona - Ruteo</th>	
                                        <td align="left" width="400">'.$zona." - ".$ruteo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$_SESSION["_usremail"].'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Fecha de pedido</th>	
                                        <td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
                                    </tr>													
                                    <tr>
                                        <th align="left" width="200">&iquest;Es agente de retenci&oacute;n?</th>	
                                        <td align="left" width="400">'.$agenteRetPerc.'</td>
                                    </tr>
                                    <tr style="color:#FFFFFF; font-weight:bold;">
                                        <th colspan="2" align="center">	
                                            Datos de la Cuenta
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Raz&oacute;n Social</th>	
                                        <td align="left" width="400">'.$nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuit</th>	
                                        <td align="left" width="400">'.$cuit.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Categor&iacute;a Iva</th>	
                                        <td align="left" width="400">'.$catIvaNombre.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Lista de Precios</th>	
                                        <td align="left" width="400">'.$listaNombre.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Cadena</th>	
                                        <td align="left" width="400">'.$cadena.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Tipo Cadena</th>	
                                        <td align="left" width="400">'.$tipoCadenaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Provincia</th>	
                                        <td align="left" width="400">'.$provinciaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Localidad</th>	
                                        <td align="left" width="400">'.$localidadNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Domicilio</th>	
                                        <td align="left" width="400">'.$direccion.' '.$nro.' '.$piso.' '.$dpto.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$correo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono</th>	
                                        <td align="left" width="400">'.$telefono.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Web</th>	
                                        <td align="left" width="400">'.$web.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200"> Observaci&oacute;n </th>	
                                        <td align="left" width="400"> '.$observacion.' </td>
                                    </tr>	
                                    
                                    <tr>
                                        <th colspan="2" align="center">	
                                            Datos a Completar
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">	
                                            Habilitaci&oacute;n<br />
                                            Director T&eacute;cnico<br />
                                            Autorizaci&oacute;n alta
                                        </th>	
                                        <td align="left" width="400"></td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </td> 
                    </tr>
				';
				
				break;
				
			case 'ModificaDatos':
				if(empty($categoriaComercial)){ echo "Indique categor&iacute;a comercial."; exit; }	
				
				$est = 7;
				$estadoNombre = 'Modificado de Datos';			
				//Al modificar datos (pasa a activo)				
				if (empty($condicionPago)) { echo "Indique una condici&oacute;n de pago."; exit; }		
				if(dac_controlesModificacion($estadoDDBB, $fechaAlta, $cadena, $empresa, $cuit, $ctaId)){
					$fechaAlta = dac_controlesModificacion($estadoDDBB, $fechaAlta, $cadena, $empresa, $cuit, $ctaId);
				}
				
				//Busca las categorías de la lista de precios seleccionadas y consotrla que exista la condicion Comercial seleccionada en la cuenta
				$categoriasListaPrecios	= DataManager::getLista('CategoriaComercial', 'IdLista', $lista);
				if(!empty($categoriasListaPrecios)){
					$categoriasComerciales = explode(",", $categoriasListaPrecios);
					if(!in_array($categoriaComercial, $categoriasComerciales)) {						
						$categoriaNombre = DataManager::getCategoriaComercial('catnombre', 'catidcat', $categoriaComercial);						
						echo "La Categoría Comercial '$categoriaComercial - $categoriaNombre' NO corresponde a la Lista de Precios '$listaNombre'";
					}
				}
				//--------------------------------
				
				//**********//
				//	CORREO	//
				$from 			=	'infoweb@neo-farma.com.ar';
				$fromName		=	'Comunicado Web';
				$cuerpoMail_2 	= '	
					<tr>
                        <td valign="top">
                            <div class="texto">						
                                <table width="580px" style="border:1px solid #117db6">
                                    <tr>
                                        <th align="left" width="200"> Usuario </th>	
                                        <td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuenta</th>	
                                        <td align="left" width="400">'.$idCuenta.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Zona - Ruteo</th>	
                                        <td align="left" width="400">'.$zona." - ".$ruteo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$_SESSION["_usremail"].'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Fecha de pedido</th>	
                                        <td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
                                    </tr>													
                                    <tr>
                                        <th align="left" width="200">&iquest;Es agente de retenci&oacute;n?</th>	
                                        <td align="left" width="400">'.$agenteRetPerc.'</td>
                                    </tr>
                                    <tr style="color:#FFFFFF; font-weight:bold;">
                                        <th colspan="2" align="center">	
                                            Datos de la Cuenta
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Raz&oacute;n Social</th>	
                                        <td align="left" width="400">'.$nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuit</th>	
                                        <td align="left" width="400">'.$cuit.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Categor&iacute;a Iva</th>	
                                        <td align="left" width="400">'.$catIvaNombre.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Cadena</th>	
                                        <td align="left" width="400">'.$cadena.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Tipo Cadena</th>	
                                        <td align="left" width="400">'.$tipoCadenaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Provincia</th>	
                                        <td align="left" width="400">'.$provinciaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Localidad</th>	
                                        <td align="left" width="400">'.$localidadNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Domicilio</th>	
                                        <td align="left" width="400">'.$direccion.' '.$nro.' '.$piso.' '.$dpto.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$correo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono</th>	
                                        <td align="left" width="400">'.$telefono.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Web</th>	
                                        <td align="left" width="400">'.$web.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200"> Observaci&oacute;n </th>	
                                        <td align="left" width="400"> '.$observacion.' </td>
                                    </tr>	
                                    
                                    <tr>
                                        <th colspan="2" align="center">	
                                            Datos a Completar
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">	
                                            Habilitaci&oacute;n<br />
                                            Director T&eacute;cnico<br />
                                            Autorizaci&oacute;n alta
                                        </th>	
                                        <td align="left" width="400"></td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </td> 
                    </tr>
				';
				//**********//						
				break;
			case 'SolicitudBaja':
				$est = 10;
				$estadoNombre 	= 'Solicitud de Baja';
				
				if(empty($observacion)){							
					echo "Debe indicar el motivo de la baja de la cuenta"; exit;
				}
				
				//La cuenta pasa automáticamente a ZONA INACTIVA
				$observacion2 	= "Se da de BAJA por ".$estado.". con CUIT ".$cuit.". ".$observacion;
				$ctaObject	= DataManager::newObjectOfClass('TCuenta', $ctaId);
				$ctaObject->__set('Estado'			, $estado);
				$ctaObject->__set('Zona'			, 95);
				$ctaObject->__set('Observacion'		, $observacion2);
				$ctaObject->__set('Activa'			, 0);
				$ctaObject->__set('CUIT'			, '');
				$ctaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
				$ctaObject->__set('LastUpdate'		, date("Y-m-d H:i:s"));
				// Procesar BAJA en HIPERWIN
				$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaId);
				$ctaObjectHiper->__set('Estado'		, $estado);
				$ctaObjectHiper->__set('Zona'		, 95);
				$ctaObjectHiper->__set('Observacion', $observacion2);
				$ctaObjectHiper->__set('Activa'		, 0);
				$ctaObjectHiper->__set('CUIT'		, '');
				$ctaObjectHiper->__set('UsrUpdate'	, $_SESSION["_usrid"]);
				$ctaObjectHiper->__set('LastUpdate'	,  date("Y-m-d H:i:s"));
				
				DataManagerHiper::updateSimpleObject($ctaObjectHiper, $ctaId);
				DataManager::updateSimpleObject($ctaObject);
				//----------------------------
				
				$cuerpoMail_2 	= '	
					<tr>
                        <td valign="top">
                            <div class="texto">						
                                <table width="580px" style="border:1px solid #117db6">
                                    <tr>
                                        <th align="left" width="200"> Usuario </th>	
                                        <td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuenta</th>	
                                        <td align="left" width="400">'.$idCuenta.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Zona - Ruteo</th>	
                                        <td align="left" width="400">'.$zona." - ".$ruteo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$_SESSION["_usremail"].'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Fecha de pedido</th>	
                                        <td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
                                    </tr>	
                                    <tr style="color:#FFFFFF; font-weight:bold;">
                                        <th colspan="2" align="center">	
                                            Datos de la Cuenta
                                        </th>	
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Raz&oacute;n Social</th>	
                                        <td align="left" width="400">'.$nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuit</th>	
                                        <td align="left" width="400"></td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Provincia</th>	
                                        <td align="left" width="400">'.$provinciaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Localidad</th>	
                                        <td align="left" width="400">'.$localidadNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Domicilio</th>	
                                        <td align="left" width="400">'.$direccion.' '.$nro.' '.$piso.' '.$dpto.'</td>
                                    </tr>                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$correo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono</th>	
                                        <td align="left" width="400">'.$telefono.'</td>
                                    </tr>
                                    
                                    <tr>
                                        <th align="left" width="200"> Observaci&oacute;n </th>	
                                        <td align="left" width="400"> '.$observacion2.' </td>
                                    </tr>
                                </table>
                            </div>
                        </td> 
                    </tr>
				';
								
				//******************//	
				//	Registro ESTADO	//
				dac_registrarEstado('TCuenta', $ctaId, $est, $tipo."_".$estado);
				
				//*********************//	
				//	Registro MOVIMIENTO	//
				$movimiento = 'CUENTA_'.$tipo;	
				$movTipo	= 'UPDATE';
				dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);		
				break;
		}
				
		//**********//
		//	CORREO	//
		require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
		$mail->From     = $from;
		$mail->FromName	= $fromName;
		$mail->Subject 	= $estadoNombre." de Cuenta ".$tipo.", ".$nombre.". Guarde este email.";
		
		$headMail 		= '
			<html>
				<head>
					<title>'.$estadoNombre.' de CUENTA '.$tipo.'</title>
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
						Se env&iacute;an los datos de <strong>'.$estadoNombre.' de la CUENTA '.$tipo.'</strong> correspondiente a:<br/><br/>		
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
		$pieMail 		= '	<tr align="center" class="saludo">
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
		
		//Adjunta Archivos
		if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
			if($estado == 'SolicitudAlta' || $estado == 'CambioRazonSocial' || $estado == 'CambioDomicilio'){ //Debe adjuntar documentación			
				if($hayArchivos){							
					for ($i = 0; $i < $fileCount; $i++) { 		
						if($myFiles['error'][$i] == UPLOAD_ERR_OK){
							$nombreOriginal	=	$myFiles['name'][$i];
							$nombreTemporal	=	$myFiles['tmp_name'][$i];
							$peso	 		=	$myFiles['size'][$i];

							$mail->AddAttachment($nombreTemporal, $nombreOriginal);
						}											
						if ($myFiles['error'][$i] != 0 && $myFiles['error'][$i] != 4){ 
							echo 'No se pudo subir el archivo <b>'.$nombreOriginal.'</b> debido al siguiente Error: '.$myFiles['error'][$i]; exit;
						}
					}
				} else {
					echo "Debe adjuntar documentaci&oacute;n para ".$estadoNombre."."; exit;
				}
			}
		}
		
		$mail->msgHTML($cuerpoMail);
		$mail->AddBCC("infoweb@neo-farma.com.ar");		
		$mail->AddBCC("cuentas@neo-farma.com.ar");
		$mail->AddAddress($_SESSION["_usremail"], $estadoNombre.' de la cuenta '.$tipo.". Enviada");
		switch($estado){
			case 'SolicitudAlta':
				$mail->AddAddress("robertorodriguez@gezzi.com.ar");
				break;	
			case 'SolicitudBaja':
				$mail->AddBCC("leonardosenno@neo-farma.com.ar");	
				break;	
		}
		break;
		
	//**************//
	//	TRANSFER	//	
	case 'T':
	case 'TT':	
		//Controles generales
		if(empty($zona) || $zona==199){ echo "Indique la zona del vendedor."; exit; }
		if(empty($ruteo)){ echo "Indique el ruteo."; exit; }
		if (empty($cp) || !is_numeric($cp)) { echo "Indique c&oacute;digo postal."; exit; }	
		
		//**********//
		//	CORREO	//
		$from 			=	$_SESSION["_usremail"];
		$fromName		=	"Usuario ".$_SESSION["_usrname"];
		$cuerpoMail_3 	= 	'	
			<tr> 
				<td>
					<div class="texto">
						<font color="#999999" size="2" face="Geneva, Arial, Helvetica, sans-serif">
							Por cualquier consulta, p&oacute;ngase en contacto con la empresa al 4555-3366 de Lunes a Viernes de 10 a 17 horas.
						</font>
																		
						<div style="color:#000000; font-size:12px;"> 
							<strong>Si no recibe informaci&oacute;n del alta en 24 horas h&aacute;biles, podr&aacute; reclamar reenviando &eacute;ste mail a reclamosweb@neo-farma.com.ar </strong></br></br>
						</div>		
					</div>
				</td>
			</tr>
		';
				
		switch($estado){
			case 'SolicitudAlta':
				$est = 5;
				$estadoNombre = 'Solicitud de Alta';
				if(dac_controlesAlta($cadena, $ctaId, $empresa, $cuit, $estadoDDBB, $zona, $tipoDDBB, $tipo)){
					$idCuenta 	= dac_crearNroCuenta($empresa, $zona);
					$fechaAlta	= "2001-01-01 00:00:00";
				}			
				break;
			case 'CambioRazonSocial':
			case 'CambioDomicilio':
				echo "Ésta cuenta no requiere ésta solicitud."; exit;
				break;
			case 'ModificaDatos':
				$est = 7;
				$estadoNombre = 'Modificado de Datos';				
				if(dac_controlesModificacion($estadoDDBB, $fechaAlta, $cadena, $empresa, $cuit, $ctaId)){
					$fechaAlta = dac_controlesModificacion($estadoDDBB, $fechaAlta, $cadena, $empresa, $cuit, $ctaId);
				}				
				//**********//
				//	CORREO	//
				//**********//	
				$from 			=	'infoweb@neo-farma.com.ar';
				$fromName		=	'Comunicado Web';						
				//**********//								
				break;
			case 'SolicitudBaja':
				$est = 10;
				$estadoNombre 	= 'Solicitud de Baja';
				
				if(empty($observacion)){							
					echo "Debe indicar el motivo de la baja de la cuenta"; exit;
				}
				
				//La cuenta pasa automáticamente a ZONA INACTIVA
				$observacion2 	= "Se da de BAJA por ".$estado.". con CUIT ".$cuit.". ".$observacion;
				$ctaObject	= DataManager::newObjectOfClass('TCuenta', $ctaId);
				$ctaObject->__set('Estado'			, $estado);
				$ctaObject->__set('Zona'			, 95);
				$ctaObject->__set('Observacion'		, $observacion2);
				$ctaObject->__set('Activa'			, 0);
				$ctaObject->__set('CUIT'			, '');
				$ctaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
				$ctaObject->__set('LastUpdate'		, date("Y-m-d H:i:s"));
				DataManager::updateSimpleObject($ctaObject);								
				
				//******************//	
				//	Registro ESTADO	//
				dac_registrarEstado('TCuenta', $ctaId, $est, $tipo."_".$estado);
				
				//*********************//	
				//	Registro MOVIMIENTO	//
				$movimiento = 'CUENTA_'.$tipo;	
				$movTipo	= 'UPDATE';
				dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);		
				break;
		}
		
		//**********************//		
		if(count($arrayCtaIdDrog) < 1){
			echo "Debe cargar al menos una droguer&iacute;a con n&uacute;mero de cuenta transfer."; exit;	
		}
				
		//**********//
		//	CORREO	//
		require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
		$mail->From     = $from;
		$mail->FromName	= $fromName;
		$mail->Subject 	= $estadoNombre." de Cuenta ".$tipo.", ".$nombre.". Guarde este email.";
		
		$headMail 		= '
			<html>
				<head>
					<title>'.$estadoNombre.' de CUENTA '.$tipo.'</title>
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
						Se env&iacute;an los datos de <strong>'.$estadoNombre.' de la CUENTA '.$tipo.'</strong> correspondiente a:<br/><br/>		
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
                                        <td align="left" width="400"> '.utf8_decode($_SESSION["_usrname"]).' </td>
                                    </tr>                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$_SESSION["_usremail"].'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Fecha de pedido</th>	
                                        <td align="left" width="400">'.date("d-m-Y H:i:s").'</td>
                                    </tr>													
                                    
                                    <tr style="color:#FFFFFF; font-weight:bold;">
                                        <th colspan="2" align="center">	
                                            Datos de la Cuenta
                                        </th>	
                                    </tr>																	
									<tr>
                                        <th align="left" width="200">Cuenta</th>	
                                        <td align="left" width="400">'.$idCuenta.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Zona - Ruteo</th>	
                                        <td align="left" width="400">'.$zona." - ".$ruteo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Raz&oacute;n Social</th>	
                                        <td align="left" width="400">'.$nombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Cuit</th>	
                                        <td align="left" width="400">'.$cuit.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Cadena</th>	
                                        <td align="left" width="400">'.$cadena.'</td>
                                    </tr>
									<tr>
                                        <th align="left" width="200">Tipo Cadena</th>	
                                        <td align="left" width="400">'.$tipoCadenaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Provincia</th>	
                                        <td align="left" width="400">'.$provinciaNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Localidad</th>	
                                        <td align="left" width="400">'.$localidadNombre.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Domicilio</th>	
                                        <td align="left" width="400">'.$direccion.' '.$nro.' '.$piso.' '.$dpto.'</td>
                                    </tr>                                    
                                    <tr>
                                        <th align="left" width="200">E-mail</th>	
                                        <td align="left" width="400">'.$correo.'</td>
                                    </tr>
                                    <tr>
                                        <th align="left" width="200">Tel&eacute;fono</th>	
                                        <td align="left" width="400">'.$telefono.'</td>
                                    </tr>                                    
                                    <tr>
                                        <th align="left" width="200"> Observaci&oacute;n </th>	
                                        <td align="left" width="400"> '.$observacion.' </td>
                                    </tr>	
                                    
                                </table>
                            </div>
                        </td> 
                    </tr>
				';		
		
		$pieMail 		= '	<tr align="center" class="saludo">
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
		$mail->AddBCC("infoweb@neo-farma.com.ar");
		$mail->AddBCC("cuentatransfer@neo-farma.com.ar");
		$mail->AddAddress($_SESSION["_usremail"], $estadoNombre.' de la cuenta '.$tipo.". Enviada");
		switch($estado){
			case 'SolicitudBaja':
				$mail->AddBCC("leonardosenno@neo-farma.com.ar");	
				break;	
		}
		break;
		
	//**************//
	//	PROSPECTO	//	
	case 'PS':
		require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/mailConfig.php" );
		//Controlo campos generales
		if(count($arrayCtaIdDrog) > 0){ echo "Un prospecto no puede tener cuentas transfers relacionados."; exit; }	
		
		switch($estado){
			case 'SolicitudAlta':
				//El alta se realiza directa!
				$est = 5;
				$estadoNombre = 'Alta';							
				if(dac_controlesAlta($cadena, $ctaId, $empresa, $cuit, $estadoDDBB, $zona, $tipoDDBB, $tipo)){
					$fechaAlta	= 	"2001-01-01 00:00:00";
					$zona		=	empty($zona) ? 199 : $zona;
				};		
				//echo "Los prospectos no pueden solicitarse por alta."; exit;
				break;
			case 'CambioRazonSocial':
			case 'CambioDomicilio':
			case 'SolicitudBaja':
				echo "Ésta cuenta no requiere ésta solicitud."; exit;
				break;
			case 'ModificaDatos':				
				$est = 7;
				$estadoNombre = 'Modificado de Datos';	
				//********************//
				dac_controlesModificacion($estadoDDBB, $fechaAlta, $cadena, $empresa, $cuit, $ctaId);
				break;	
		}
		break;
	//**************//
	//	PROVEEDOR	//	
	//**************//	
	case 'PV':
		echo "No se puede realizar &eacutesta acci&oacuten."; exit;	
		break;
	//**********//
	//	OTROS	//	
	//**********//	
	case 'O':
		//Solo deberá ser en casos en que una cuenta NO SEA farmacia.
		//Solo losprospectos pueden pasar a estado Otro
		if(empty($observacion)){
			echo "Indique en observaciones el motivo del tipo de la cuenta."; exit;
		}
		
		switch($estado){
			case 'SolicitudAlta':
			case 'CambioRazonSocial':
			case 'CambioDomicilio':
			case 'SolicitudBaja':
				echo "Ésta cuenta no requiere ésta solicitud."; exit;
				break;
			case 'ModificaDatos':
				$est = 7;
				$estadoNombre = 'Modificado de Datos';	
				break;
		}
		$activa = 0;
		
		break;
		
	default:
		echo "Seleccione un tipo de cuenta."; exit;
		break;	
}

//-------------------//
//	GUARDAR CAMBIOS  //
$empleados 			= empty($empleados) 		? 0 : $empleados;
$condicionPago 		= empty($condicionPago) 	? 0 : $condicionPago;
$nro 				= empty($nro) 				? 0 : $nro;
$categoriaComercial = empty($categoriaComercial)? 0 : $categoriaComercial;
$ruteo				= empty($ruteo) 			? 0 : $ruteo;
$zona				= empty($zona) 				? 0 : $zona;
$localidad			= empty($localidad) 		? 0 : $localidad;
$cp					= empty($cp) 				? 0 : $cp;

$ctaObject	= ($ctaId) ? DataManager::newObjectOfClass('TCuenta', $ctaId) : DataManager::newObjectOfClass('TCuenta');
$ctaObject->__set('Empresa'			, $empresa);
$ctaObject->__set('Cuenta'			, $idCuenta);
$ctaObject->__set('Tipo'			, $tipo);
$ctaObject->__set('Estado'			, $estado);
$ctaObject->__set('Nombre'			, $nombre);
$ctaObject->__set('CUIT'			, $cuit);
$ctaObject->__set('NroIngresosBrutos', $nroIngBrutos);
$ctaObject->__set('Zona'			, $zona);
$ctaObject->__set('ZonaEntrega'		, $zonaEntrega);
$ctaObject->__set('Ruteo'			, $ruteo);
$ctaObject->__set('CondicionPago'	, $condicionPago);
$ctaObject->__set('CategoriaComercial', $categoriaComercial);
$ctaObject->__set('Empleados'		, $empleados);
$ctaObject->__set('CategoriaIVA'	, $categoriaIVA);
$ctaObject->__set('RetencPercepIVA'	, $agenteRetPerc);
$ctaObject->__set('FechaAlta'		, ($fechaAlta) ? $fechaAlta: "2001-01-01 00:00:00");
$ctaObject->__set('Email'			, $correo);
$ctaObject->__set('Telefono'		, $telefono);
$ctaObject->__set('Web'				, $web);
$ctaObject->__set('Observacion'		, $observacion);
$ctaObject->__set('UsrAssigned'		, $asignado);
$ctaObject->__set('Pais'			, 1);
$ctaObject->__set('Provincia'		, $provincia);
$ctaObject->__set('Localidad'		, $localidad);
$ctaObject->__set('LocalidadNombre'	, $localidadNombre);
$ctaObject->__set('Direccion'		, $direccion);
$ctaObject->__set('DireccionEntrega', $direccionEntrega);
$ctaObject->__set('Numero'			, $nro);
$ctaObject->__set('Piso'			, $piso);
$ctaObject->__set('Dpto'			, $dpto);
$ctaObject->__set('CP'				, $cp);
$ctaObject->__set('Longitud'		, $longitud);
$ctaObject->__set('Latitud'			, $latitud);
$ctaObject->__set('UsrUpdate'		, $_SESSION["_usrid"]);
$ctaObject->__set('LastUpdate'		, date("Y-m-d"));
$ctaObject->__set('Referencias'		, 0);
$ctaObject->__set('Imagen1'			, $imagen1);
$ctaObject->__set('Imagen2'			, $imagen2);
$ctaObject->__set('Bonif1'			, 0);
$ctaObject->__set('Bonif2'			, 0);
$ctaObject->__set('Bonif3'			, 0);
$ctaObject->__set('CuentaContable'	, '1.1.3.01.01'); 
$ctaObject->__set('Credito'			, 0);
$ctaObject->__set('NroEmpresa'		, 0);
$ctaObject->__set('Lista'			, $lista);

//--------------------------------//
//	PREPARO CUENTA PARA HiperWin
$ctaObjectHiper	= ($ctaId) ? DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaId) : DataManagerHiper::newObjectOfClass('THiperCuenta');
$ctaObjectHiper->__set('Empresa'			, $empresa);
$ctaObjectHiper->__set('Cuenta'				, $idCuenta);
$ctaObjectHiper->__set('Tipo'				, $tipo);
$ctaObjectHiper->__set('Estado'				, $estado);
$ctaObjectHiper->__set('Nombre'				, strtoupper($nombre) );
$ctaObjectHiper->__set('CUIT'				, $cuit);
$ctaObjectHiper->__set('Zona'				, $zona);
$ctaObjectHiper->__set('ZonaEntrega'		, $zonaEntrega);
$ctaObjectHiper->__set('Pais'				, 1);
$ctaObjectHiper->__set('Provincia'			, $provincia); 
$ctaObjectHiper->__set('Localidad'			, $localidad);
$ctaObjectHiper->__set('LocalidadNombre'	, $localidadNombre);
$ctaObjectHiper->__set('Direccion'			, $direccion);
$direccionCompleta	=	$direccion.(empty($nro)? '' : ' '.$nro).(empty($piso) ? '' : ' '.$piso).(empty($dpto) ? '' : $dpto);	
$ctaObjectHiper->__set('DireccionCompleta'	, $direccionCompleta);
$ctaObjectHiper->__set('DireccionEntrega'	, $direccionEntrega);
$ctaObjectHiper->__set('Numero'				, $nro);
$ctaObjectHiper->__set('Piso'				, $piso);
$ctaObjectHiper->__set('Dpto'				, $dpto);
$ctaObjectHiper->__set('CP'					, $cp);
$ctaObjectHiper->__set('Longitud'			, $longitud);
$ctaObjectHiper->__set('Latitud'			, $latitud);
$ctaObjectHiper->__set('Ruteo'				, $ruteo);
$ctaObjectHiper->__set('CategoriaComercial'	, $categoriaComercial);
$ctaObjectHiper->__set('CondicionPago'		, $condicionPago);
$ctaObjectHiper->__set('Empleados'			, $empleados);
$ctaObjectHiper->__set('CategoriaIVA'		, $categoriaIVA);
$ctaObjectHiper->__set('RetencPercepIVA'	, $agenteRetPerc);
$ctaObjectHiper->__set('NroIngresosBrutos'	, $nroIngBrutos);
$ctaObjectHiper->__set('FechaAlta'			, ($fechaAlta) ? $fechaAlta: "2001-01-01 00:00:00");
$ctaObjectHiper->__set('Email'				, $correo);
$ctaObjectHiper->__set('Telefono'			, $telefono);
$ctaObjectHiper->__set('Web'				, $web);
$ctaObjectHiper->__set('Observacion'		, $observacion);
$ctaObjectHiper->__set('Imagen1'			, $imagen1);
$ctaObjectHiper->__set('Imagen2'			, $imagen2);
$ctaObjectHiper->__set('UsrAssigned'		, $asignado);
$ctaObjectHiper->__set('UsrUpdate'			, $_SESSION["_usrid"]);
$ctaObjectHiper->__set('LastUpdate'			, date("Y-m-d"));
$ctaObjectHiper->__set('Referencias'		, 0);
$ctaObjectHiper->__set('Bonif1'				, 0);
$ctaObjectHiper->__set('Bonif2'				, 0);
$ctaObjectHiper->__set('Bonif3'				, 0);
$ctaObjectHiper->__set('CuentaContable'		, '1.1.3.01.01'); 
$ctaObjectHiper->__set('Credito'			, 0);
$ctaObjectHiper->__set('NroEmpresa'			, 0);
$ctaObjectHiper->__set('Lista'				, $lista);

if($tipo == "C" || $tipo == "CT") { 	
	if($estado == 'ModificaDatos') {
		//Si modifica un usr Vendedor, no se modifican los datos de la cuenta
		//Solo se actualizan documentos y  cuentas relacionadas
		if($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
			$ctaObjectHiper	= DataManagerHiper::newObjectOfClass('THiperCuenta', $ctaId);
			$ctaObject		= DataManager::newObjectOfClass('TCuenta', $ctaId);				
		}
	}
}

if(!empty($frenteNombre)) {		
	$ctaObject->__set('Imagen1', $frenteNombre); 
	$ctaObjectHiper->__set('Imagen1', $frenteNombre); 
}
if(!empty($interiorNombre)){	
	$ctaObject->__set('Imagen2', $interiorNombre); 
	$ctaObjectHiper->__set('Imagen2', $interiorNombre); 
}

if ($ctaId){
	//UPDATE
	$ctaObject->__set('UsrUpdate'	, $_SESSION["_usrid"]);
	$ctaObject->__set('LastUpdate'	, date("Y-m-d H:i:s"));	
	$ctaObjectHiper->__set('UsrUpdate'	, $_SESSION["_usrid"]);
	$ctaObjectHiper->__set('LastUpdate'	, date("Y-m-d H:i:s"));
		
	if($estado != 'SolicitudBaja'){
		if($tipo == "C" || $tipo == "CT") {			
			if($tipoDDBB != "C" && $tipoDDBB != "CT"){				
				//si la cuenta es cliente pero viene de otro tipoDDBB se crea en hiperwin
				$ctaObjectHiper->__set('FechaCompra', date("2001-01-01"));
				$ctaObjectHiper->__set('UsrCreated'	, $_SESSION["_usrid"]);
				$ctaObjectHiper->__set('DateCreated', date("Y-m-d H:i:s"));
				$ctaObjectHiper->__set('Activa'		, 1);				
				$ctaObjectHiper->__set('ID'	, $ctaId);	
				$IDHiper	= DataManagerHiper::insertSimpleObject($ctaObjectHiper);
				if(!$IDHiper){
					echo "Error al insertar la cuenta Hiper."; exit;
				}
			} else { //Si solo es modificacion se hace update
				DataManagerHiper::updateSimpleObject($ctaObjectHiper, $ctaId);
			}			
		}		
		DataManager::updateSimpleObject($ctaObject);
		
		//Registro de cadenas
		if($cadena){	
			$cuentasCadena 	= [];
			$idCtaRel		= [];
			$cuentas	=	DataManager::getCuentasCadena($empresa, $cadena);
			if (count($cuentas)) {
				foreach ($cuentas as $k => $cta) {	
					$idCtaRel[]			= $cta['cadid'];
					$cuentasCadena[]	= $cta['IdCliente'];
				}
			}			
			if(in_array($idCuenta, $cuentasCadena) ){
				//UPDATE cadena
				$key 			= array_search($idCuenta, $cuentasCadena);
				$cadRelObject	= DataManager::newObjectOfClass('TCadenaCuentas', $idCtaRel[$key]);	
				$cadRelObject->__set('TipoCadena', $tipoCadena);				
				$ID 			= $cadRelObject->__get('ID');
				DataManagerHiper::updateSimpleObject($cadRelObject, $ID);					
				DataManager::updateSimpleObject($cadRelObject);	
			} else {
				//INSERT cadena
				$cadRelObject	=	DataManager::newObjectOfClass('TCadenaCuentas');	
				$cadRelObject->__set('Empresa'	, $empresa);	
				$cadRelObject->__set('Cadena'	, $cadena);
				$cadRelObject->__set('Cuenta'	, $idCuenta);
				$cadRelObject->__set('TipoCadena', $tipoCadena);
				$cadRelObject->__set('ID'		, $cadRelObject->__newID());
				
				DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin
				$IDCuentaRel	= DataManager::insertSimpleObject($cadRelObject);	
				DataManagerHiper::insertSimpleObject($cadRelObject, $IDCuentaRel);
			}
		}
		
		
		// MOVIMIENTO //
		$movimiento = 'CUENTA_'.$tipo;	
		$movTipo	= 'UPDATE';	
	}	
} else {
	//INSERT
	$ctaObject->__set('FechaCompra'	, date("2001-01-01")); 
	$ctaObject->__set('UsrCreated'	, $_SESSION["_usrid"]);
	$ctaObject->__set('DateCreated'	, date("Y-m-d H:i:s"));
	$ctaObject->__set('ID'			, $ctaObject->__newID());
	
	$ctaObjectHiper->__set('FechaCompra', date("2001-01-01"));
	$ctaObjectHiper->__set('UsrCreated'	, $_SESSION["_usrid"]);
	$ctaObjectHiper->__set('DateCreated', date("Y-m-d H:i:s"));
	
	if($tipo != "PS" && $tipo != "T" && $tipo != "TT"){
		$ctaObject->__set('Activa'		, 0);	
		$ctaObjectHiper->__set('Activa'	, 0);
	} else {
		$ctaObject->__set('Activa'		, 1);
		$ctaObjectHiper->__set('Activa'	, 1);
	}
	
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin
	$ID	= DataManager::insertSimpleObject($ctaObject);	
	if(!$ID){
		echo "Error al insertar la cuenta."; exit;
	}
	
	if($tipo == "C" || $tipo == "CT") {
		$ctaObjectHiper->__set('ID'	, $ID);			
		$IDHiper	= DataManagerHiper::insertSimpleObject($ctaObjectHiper);
		if(!$IDHiper){
			echo "Error al insertar la cuenta Hiper."; exit;
		}
	}	
	//MOVIMIENTO de CUENTA
	$ctaId		=  $ID;
	$movimiento = 'CUENTA_'.$tipo;
	$movTipo	= 'INSERT';
}

//IMPORTANTE: Send Mail debe estar antes de subir los archivos a la raiz de la web,
//de lo contrario no aparecerán adjuntos en el mail
if($ctaId){	
	if($mail){
		if(!$mail->Send()) {
			echo '! Fallo en el env&iacute;o de notificaci&oacute;n por mail'.$mail->ErrorInfo; exit;
		}
	}	
	//-----------------------
	//	Registro MOVIMIENTO	
	dac_registrarMovimiento($movimiento, $movTipo, "TCuenta", $ctaId);
} else {
	echo "El registro de datos no se realizó correctamente."; exit;
}

//******************//	
//	Registro ESTADO	//
$tipoEstado = $tipo."_".$estado;
dac_registrarEstado('TCuenta', $ctaId, $est, $tipoEstado);

//******************************//
//	UPLOAD de FRENTE E INTERIOR	//
$rutaFile	=	"/pedidos/cuentas/archivos/".$ctaId."/";	
if ($frentePeso != 0){		
	if(dac_fileFormatControl($_FILES["frente"]["type"], 'imagen') && $_FILES["frente"]["type"] != "application/pdf"){
		$ext	=	explode(".", $frenteNombre);
		$name	= 	$ext[0];		
		if(!dac_fileUpload($_FILES["frente"], $rutaFile, $name)){
			echo "Error al intentar subir el archivo."; exit;
		}
	} else {
		echo "El archivo frente debe tener formato imagen."; exit;		
	}		
}

if ($interiorPeso != 0){
	if(dac_fileFormatControl($_FILES["interior"]["type"], 'imagen') && $_FILES["interior"]["type"] != "application/pdf"){ 
		$ext	=	explode(".", $interiorNombre);
		$name	= 	$ext[0];
		if(!dac_fileUpload($_FILES["interior"], $rutaFile, $name)){
			echo "Error al intentar subir el archivo."; exit;
		}
	} else {
		echo "El archivo frente debe tener formato imagen."; exit;
	}
}

//******************************//
//	UPLOAD de OTROS ARCHIVOS	//
if (isset($_FILES['multifile'])) {
	$myFiles 	= 	$_FILES['multifile'];
	$fileCount 	= 	count($myFiles["name"]);
				
	for ($i = 0; $i < $fileCount; $i++) { 
		if($myFiles['error'][$i] == UPLOAD_ERR_OK ){
			$nombreOriginal	=	$myFiles['name'][$i];
			$temporal 		=	$myFiles['tmp_name'][$i];
			$peso	 		=	$myFiles['size'][$i];
			$destino 		= 	$_SERVER['DOCUMENT_ROOT'].$rutaFile;
			
			$info 			= 	new SplFileInfo($nombreOriginal);
			
			if(file_exists($destino) || @mkdir($destino, 0777, true))  {
				$destino = $destino.'documento'.$i.'F'.date("dmYHms").".".$info->getExtension();					
				move_uploaded_file($temporal, $destino);
			} else {echo "Error al intentar crear el archivo."; exit;}
		} else {
			if($myFiles['error'][$i] != 0){
				switch($myFiles['error'][$i]){
					case '0': //UPLOAD_ERR_OK 
						break;
					case '1': //UPLOAD_ERR_INI_SIZE
						//El fichero subido excede la directiva upload_max_filesize de php.ini.
						break;
					case '2': //UPLOAD_ERR_FORM_SIZE
						//El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.
						break;
					case '3': //UPLOAD_ERR_PARTIAL
						// El fichero fue sólo parcialmente subido.
						break;
					case '4': //UPLOAD_ERR_NO_FILE
						//No se subió ningún fichero.
						break;
					case '6': //UPLOAD_ERR_NO_TMP_DIR
						//Falta la carpeta temporal. Introducido en PHP 5.0.3.
						break;
					case '7': //UPLOAD_ERR_CANT_WRITE
						//No se pudo escribir el fichero en el disco. Introducido en PHP 5.1.0.
						break;
					case '8': //UPLOAD_ERR_EXTENSION
						//Una extensión de PHP detuvo la subida de ficheros. PHP no proporciona una forma de determinar la extensión que causó la parada de la subida de ficheros; el examen de la lista de extensiones cargadas con phpinfo() puede ayudar. Introducido en PHP 5.2.0.
						break;
					default:
						if ($myFiles['error'][$i] != 4){ 
							echo 'No se pudo subir el archivo <b>'.$nombreOriginal.'</b> debido al siguiente Error: '.$myFiles['error'][$i]; exit;
						}
						break;
				}
			}
		}
	}
}

//******************************//	
// UPDATE CUENTAS RELACIONADAS  //
if(!empty($arrayCtaIdDrog)){
	if(count($arrayCtaIdDrog)){
		$cuentasRelacionadas = DataManager::getCuentasRelacionadas($ctaId); //$empresa, $idCuenta
		if (count($cuentasRelacionadas)) {
			foreach ($cuentasRelacionadas as $k => $ctaRel) {
				$ctaRel 	=	$cuentasRelacionadas[$k];
				$relId		=	$ctaRel['ctarelid'];
				$relIdDrog	= 	$ctaRel['ctarelidcuentadrog'];						
				//Creo Array de Droguerias Relacionadas de BBDD
				$arrayDrogDDBB[] = $relIdDrog;
				
				if (in_array($relIdDrog, $arrayCtaIdDrog)) {
					//UPDATE 
					$key	=	array_search($relIdDrog, $arrayCtaIdDrog); //Indice donde se encuentra la cuenta
					
					$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada', $relId);
					$ctaRelObject->__set('Transfer'		, $arrayCtaCliente[$key]); //nro de cliente para la droguería					
					DataManager::updateSimpleObject($ctaRelObject);
					
				} else {
					//DELETE de cuentas relacionadas
					$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada', $relId);
					$ctaRelObject->__set('ID',	$relId);
					DataManager::deleteSimpleObject($ctaRelObject);
				}			
			}
			
			foreach ($arrayCtaIdDrog as $k => $ctaIdDrog) {
				if (!in_array($ctaIdDrog, $arrayDrogDDBB)) {
					//INSERT				
					$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada');	
					$ctaRelObject->__set('Cuenta'		, $ctaId);
					$ctaRelObject->__set('Drogueria'	, $ctaIdDrog);
					$ctaRelObject->__set('Transfer'		, $arrayCtaCliente[$k]);
					$ctaRelObject->__set('ID'			, $ctaRelObject->__newID());
					$IDRelacion	= DataManager::insertSimpleObject($ctaRelObject);	
				}	
			}
		} else { //INSERT - Si no hay cuentas relacionadas, las crea
			foreach ($arrayCtaIdDrog as $k => $ctaIdDrog) {			
				$ctaRelObject	= DataManager::newObjectOfClass('TCuentaRelacionada');	
				$ctaRelObject->__set('Cuenta'		, $ctaId);
				$ctaRelObject->__set('Drogueria'	, $ctaIdDrog); //nro iddrogueria
				$ctaRelObject->__set('Transfer'		, $arrayCtaCliente[$k]); //nro cliente transfer
				$ctaRelObject->__set('ID'			, $ctaRelObject->__newID());
				$IDRelacion = DataManager::insertSimpleObject($ctaRelObject);	
			}
		}
	}
} else {
	//Si no se envían datos de arrya de clientes transfers, consulta si existe para eliminar
	if($ctaId){
		$cuentasRelacionadas = DataManager::getCuentasRelacionadas($ctaId);
		if (count($cuentasRelacionadas)) {
			//DELETE de cuentas relacionadas
			foreach ($cuentasRelacionadas as $k => $ctaRel) {
				$ctaRel 	=	$cuentasRelacionadas[$k];
				$relId		=	$ctaRel['ctarelid'];

				$ctaRelObject	=	DataManager::newObjectOfClass('TCuentaRelacionada', $relId);
				$ctaRelObject->__set('ID',	$relId);
				DataManager::deleteSimpleObject($ctaRelObject);
			}
		}
	}
}

//*************//
echo 1; exit;

//**************//
//	FUNCIONES	//
//**************//
//Consulto Estado Actual de la cuenta
function dac_consultarEstado($idCuenta, $empresa){
	$ctaCtrl		= 	DataManager::getCuenta('ctaidcuenta', 'ctaidcuenta', $idCuenta, $empresa);	
	if(empty($ctaCtrl)){ return 'Cuenta Nueva'; }	
	$ctaCtrlEstado	= 	DataManager::getCuenta('ctaestado', 'ctaidcuenta', $idCuenta, $empresa);
	if($ctaCtrlEstado){ return $ctaCtrlEstado;	}
	return false;
}

//Consulto Tipo de cuenta actual en DDBB
function dac_consultarTipo($ctaId){ //$idCuenta, $empresa){
	$ctaCtrlTipo	= 	DataManager::getCuenta('ctatipo', 'ctaid', $ctaId);//'ctaidcuenta', $idCuenta, $empresa);	
	if($ctaCtrlTipo){
		return $ctaCtrlTipo;
	}
	return false;	
}

function dac_crearNroCuenta($empresa, $zona){
	$nrosCuentas	= 	DataManager::dac_consultarNumerosCuenta($empresa, $zona);	
	if($nrosCuentas){
		//busca el número de cuenta disponible
		foreach($nrosCuentas as $k => $nroCta){		
			$min 	= 0;
			$min 	= substr($nroCta['ctaidcuenta'], strlen($zona), 3);
			$minMas = $min + 1;			
			if(isset($nrosCuentas[$k+1]['ctaidcuenta'])){				
				$sig 	= substr($nrosCuentas[$k+1]['ctaidcuenta'], strlen($zona), 3);
			} else {
				$sig	= $minMas + 1;
			}
			if($sig != $minMas){
				//agregos los ceros necesario a minMas
				$ceros = "";
				for($i=0; $i < (3 - strlen($minMas)); $i++){
					$ceros .= "0";$minMas;
				}
				$minMas = $ceros.$minMas;
				$nvaCuenta = $zona.$minMas."1";
				return $nvaCuenta;
			}			
		}
		echo "No se pudo crear un n&uacute;mero de cuenta. Contacte con el administrador de la web."; exit;
	} else {
		//Si no se encuentran números de cuentas en la zona, debería crear el primero!
		$nvaCuenta = $zona."001"."1";
		return $nvaCuenta;
	}		
}

function dac_existeCuitCuenta($empresa, $cuit, $ctaId){
	$cont = 0;
	$cuentas = DataManager::getCuentaAll('ctaid', 'ctacuit', $cuit, $empresa);
	if(count($cuentas)){
		//SI no hay ctaid es que es alta. Si existen cuentas con el cuit es que ya existe
		if(empty($ctaId)){ return TRUE; }		
		//SI hay cuentas con cuits ver si coincide con la cuenta actual si es una sola, si son varias solo vale si es cadena.
		foreach ($cuentas as $k => $cuenta) {
			$cuentaId	= $cuenta['ctaid'];	
			$ctatipo	= DataManager::getCuenta('ctatipo', 'ctaid', $cuentaId, $empresa);
			//$ctacadena	= DataManager::getCuenta('ctaidcadena', 'ctaid', $cuentaId, $empresa);
			$ctacadena	= DataManager::getCuentasCadena($empresa, NULL, $cuentaId);
			
			if(($ctatipo == 'C' || $ctatipo == 'CT' || $ctatipo == 'T' || $ctatipo == 'TT' || $ctatipo == 'PS') && empty($ctacadena)) {
				if($ctaId != $cuentaId) {
					$cont =  $cont + 1;
				}
			}
		}
		if($cont >= 1) {			
			return TRUE;
		}
	}
	return FALSE;
}

function dac_controlesCambioTipoCuenta($tipoDDBB, $tipo, $estadoDDBB, $estado){
	if($tipoDDBB != $tipo){
		switch($tipoDDBB){
			case 'C':
			case 'CT':
				if($tipo != 'C' && $tipo != 'CT'){
					echo "Un cliente no puede cambiar tipo de cuenta."; exit;	
				}
				if($estadoDDBB != 'ModificaDatos'){
					echo "Para cambiar tipo de cuenta, el estado debe estar en modificaci&oacute;n."; exit;
				}
				
				break;
			case 'T':
				if($tipo != 'C' && $tipo != 'CT' && $tipo != 'TT'){
					echo "Un transfer solo puede pasar a cliente."; exit;
				}
				//Presentar como un alta de cliente
				if($estado != 'SolicitudAlta'){
					echo "Para cambiar tipo de cuenta, primero solicite el alta de la misma."; exit;
				}
				break;
			case 'TT':
				if($tipo != 'C' && $tipo != 'CT' && $tipo != 'T'){
					echo "Un transfer solo puede pasar a cliente."; exit;
				}
				//Presentar como un alta de cliente
				if($estado != 'SolicitudAlta'){
					echo "Para cambiar tipo de cuenta, primero solicite el alta de la misma."; exit;
				}
				break;
			case 'PS':
				/* puede pasar a cualquier estado */
				// si pasa a C o T deberá se rcomo alta. Si es a Otros, no importa
				if($tipo != 'O'){
					if($estado != 'SolicitudAlta'){
						echo "Para cambiar tipo de cuenta, primero solicite el alta de la misma."; exit;
					}
				}
				break;
			case 'O': 
				echo "La cuenta no puede cambiar de ese estado, consulte con el administrador de la web."; exit;
				break;
			default:
				if($estado != 'SolicitudAlta'){
					echo "Debe solicitar el alta de la cuenta."; exit;
				}
				break;
		}
	}
}

function dac_controlesAlta($cadena, $ctaId, $empresa, $cuit, $estadoDDBB, $zona, $tipoDDBB, $tipo) {
	if(empty($cadena)){
		if(dac_existeCuitCuenta($empresa, $cuit, $ctaId)){
			//las cuentas con mismo CUIT ¿Están en estado "cambioRazón o cambioDomicilio"? 
			//Si una no lo está, entonces sale el mensaje "el cuit ya existe".			
			if($estadoDDBB != 'CambioRazonSocial' && $estadoDDBB != 'CambioDomicilio') {
				echo "El cuit ya existe en una cuenta."; exit;
			} else {
				$estadoDDBB = 'Cuenta Nueva';	
			}
		}
	}
	
	if($estadoDDBB == 'Cuenta Nueva'){		
		return TRUE;									
	} else { 
		if($tipoDDBB == $tipo){
			echo "La cuenta ya existe, no puede solicitar un alta."; exit;
		}
	}
	return FALSE;
}

function dac_controlesModificacion($estadoDDBB, $fechaAlta, $cadena, $empresa, $cuit, $ctaId){
	//controla que el cuit no exista ya como cuenta que no sea prospecto.
	if($cuit){
		if(empty($cadena)){
			if(dac_existeCuitCuenta($empresa, $cuit, $ctaId)){
				echo "El cuit ya existe en una cuenta."; exit;
			};
		}
	}
	
	//Si cuenta actual es solicitud de Alta	
	if($estadoDDBB == 'SolicitudAlta'){ //$estadoCambio = 'ALTA';
		if($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
			echo "Debe esperar a que la cuenta est&eacute; dada de alta."; exit;
		}						
		//Si estado actual es Alta, se dará de alta la cuenta con los datos que faltan cargar.
		$fechaAlta = date("Y-m-d H:i:s");
		return $fechaAlta;
	} else {
		if (empty($fechaAlta) || $fechaAlta == "0000-00-00 00:00:00" || $fechaAlta == "1899-12-30 00:00:00" || $fechaAlta == "1899-01-01 00:00:00" || $fechaAlta == "1899-11-22 00:00:00" || $fechaAlta == "1899-12-23 00:00:00" || $fechaAlta == "1900-01-01 00:00:00") {
			if($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
				$fechaAlta == "2001-01-01 00:00:00";
				return $fechaAlta; 
			} /*else {
				echo "Debe colocar una fecha de alta de cuenta correcta."; exit;
			}*/
		}
	}	
	return FALSE;
}


function dac_registrarEstado($origen, $origenId, $est, $estado) {
	$estadoObject	=	DataManager::newObjectOfClass('TEstado');	
	$estadoObject->__set('Origen'	, $origen);		
	$estadoObject->__set('IDOrigen'	, $origenId);
	$estadoObject->__set('Fecha'	, date("Y-m-d h:i:s"));
	$estadoObject->__set('UsrCreate', $_SESSION["_usrid"]);
	$estadoObject->__set('Estado'	, $est);
	$estadoObject->__set('Nombre'	, $estado);
	$estadoObject->__set('ID'		, $estadoObject->__newID());
	$IDEstado	= DataManager::insertSimpleObject($estadoObject);	
	if(!$IDEstado){
		echo "Error al intentar registrar el estado de la solicitud. Consulte con el administrador de la web."; exit;
	}
}

//**************//

?>