<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_mes 	= empty($_REQUEST['mes']) ?	date("m")	:	$_REQUEST['mes'];
$_anio 	= empty($_REQUEST['anio'])?	date("Y")	:	$_REQUEST['anio'];
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Bonificaciones-".$_mes."-".$_anio.".xls");	

?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos (Bonificaciones) .::</TITLE>
<head></head>
<body>
	<table id="tabla_bonif" name"tabla_bonif" class="tabla_bonif" cellpadding="0" cellspacing="0" border="1" style="font-size:12px;">
       	<thead>                                                          
            <tr>  <!-- Títulos de las Columnas -->
            	<th colspan="3" style="font-weight:bold;">Bonificaci&oacute;n del <?php echo $_mes." de ".$_anio; ?></th>
                <th align="center" style="background-color:#333; color:#FFF; font-weight:bold;">Droguer&iacute;a</th>
                <th align="center" style="background-color:#333; color:#FFF; font-weight:bold;">P&uacute;blico</th>
                <th align="center" style="background-color:#333; color:#FFF; font-weight:bold;">Iva</th>
                <th align="center" style="background-color:#333; color:#FFF; font-weight:bold;">Digitado</th>
                <th align="center" style="background-color:#333; color:#FFF; font-weight:bold;">OAM</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">1</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">3</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">6</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">12</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">24</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">36</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">48</th>
                <th align="center" colspan="3" style="background-color:#333; color:#FFF; font-weight:bold;">72</th>                
			</tr>
		</thead>
                 
        <tbody><?php 
			/**************************************/  
			/*Consulta Bonificación del mes actual*/
			/**************************************/
			$_bonificacion	=	DataManager::getDetalleBonificacion($_mes, $_anio);
			if ($_bonificacion) {
				foreach ($_bonificacion as $k => $_bonif){
					$_bonif			=	$_bonificacion[$k];
					$_bonifID		=	$_bonif['bonifid'];
					$_bonifEmp		=	$_bonif['bonifempid'];
					$_bonifArtid	= 	$_bonif['bonifartid'];
					$_bonifPrecio	= 	$_bonif['bonifpreciodrog'];
					$_bonifPublico	= 	$_bonif['bonifpreciopublico'];
					$_bonifIva		= 	$_bonif['bonifiva'];
					$_bonifDigitado	= 	$_bonif['bonifpreciodigitado'];
					$_bonifOferta	= 	$_bonif['bonifoferta'];
					$_bonif1a		= 	($_bonif['bonif1a']	== 0)	?	''	:	$_bonif['bonif1a'];							
					$_bonif1b		=	($_bonif['bonif1b']	== 0)	?	''	:	$_bonif['bonif1b'];
					$_bonif1c		= 	($_bonif['bonif1c'] == 0)	?	''	:	$_bonif['bonif1c'];
					$_bonif3a		=	($_bonif['bonif3a']	== 0)	?	''	:	$_bonif['bonif3a'];
					$_bonif3b		=	($_bonif['bonif3b']	== 0)	?	''	:	$_bonif['bonif3b'];
					$_bonif3c		=	($_bonif['bonif3c'] == 0)	?	''	:	$_bonif['bonif3c'];
					$_bonif6a		=	($_bonif['bonif6a']	== 0)	?	''	:	$_bonif['bonif6a'];
					$_bonif6b		=	($_bonif['bonif6b']	== 0)	?	''	:	$_bonif['bonif6b'];
					$_bonif6c		= 	($_bonif['bonif6c'] == 0)	?	''	:	$_bonif['bonif6c'];
					$_bonif12a		=	($_bonif['bonif12a']== 0)	?	''	:	$_bonif['bonif12a'];
					$_bonif12b		=	($_bonif['bonif12b']== 0)	?	''	:	$_bonif['bonif12b'];
					$_bonif12c		= 	($_bonif['bonif12c']== 0)	?	''	:	$_bonif['bonif12c'];
					$_bonif24a		= 	($_bonif['bonif24a']== 0)	?	''	:	$_bonif['bonif24a'];
					$_bonif24b		= 	($_bonif['bonif24b']== 0)	?	''	:	$_bonif['bonif24b'];	
					$_bonif24c		=	($_bonif['bonif24c']== 0)	?	''	:	$_bonif['bonif24c'];
					$_bonif36a		= 	($_bonif['bonif36a']== 0)	?	''	:	$_bonif['bonif36a'];
					$_bonif36b		= 	($_bonif['bonif36b']== 0)	?	''	:	$_bonif['bonif36b'];
					$_bonif36c		= 	($_bonif['bonif36c']== 0)	?	''	:	$_bonif['bonif36c'];
					$_bonif48a		= 	($_bonif['bonif48a']== 0)	?	''	:	$_bonif['bonif48a'];
					$_bonif48b		= 	($_bonif['bonif48b']== 0)	?	''	:	$_bonif['bonif48b'];
					$_bonif48c		= 	($_bonif['bonif48c']== 0)	?	''	:	$_bonif['bonif48c'];
					$_bonif72a		= 	($_bonif['bonif72a']== 0)	?	''	:	$_bonif['bonif72a'];
					$_bonif72b		= 	($_bonif['bonif72b']== 0)	?	''	:	$_bonif['bonif72b'];
					$_bonif72c		= 	($_bonif['bonif72c']== 0)	?	''	:	$_bonif['bonif72c'];
												
					((($k % 2) != 0)? $estilo="" : $estilo="background-color:#CCC; font-weight:700"); 					
					if($_bonifDigitado != "0.000"){
								$estilo_fila	=	"background-color:#666; color:#FFF; font-weight:bold;";
					} else { 	$estilo_fila	=	""; }					
					?>
                    
                   	<tr id="b_<?php echo ($k+1);?>" style=" <?php echo $estilo; ?>;">
                       	<td style="width:13px;">1</td>                    						
                        <td style="width:32px;"> <?php echo $_bonifArtid; ?></td>
                        
                        <?php $_nombre	= DataManager::getArticulo('artnombre', $_bonifArtid, $_bonifEmp, 1); ?>	
                        <td style="width:170px;"> <?php echo substr($_nombre, 0, 27); ?> </td>
						 
                    	<td style="width:57px;" align="right"><?php echo number_format($_bonifPrecio, 2); ?></td>
                 		<td style="width:46px;" align="right"><?php echo number_format($_bonifPublico, 2); ?></td>
                    	<td align="right" style="width:28px;"><?php if($_bonifIva != 0) {echo $_bonifIva."%";} ?></td>
                    	<td style="width:47px; <?php echo $estilo_fila; ?>" align="right"><?php if($_bonifDigitado != "0.000"){echo  number_format($_bonifDigitado, 2);} ?></td>
                    	<td style="width:37px; font-weight:bold; <?php echo $estilo_fila; ?>"><?php if($_bonifOferta == "modificado"){echo substr($_bonifOferta, 0, 4);} else {echo substr($_bonifOferta, 0, 6);} ?></td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>"><?php echo $_bonif1a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif1a){echo "X";} else {echo $_bonif1b;} ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif1b){echo "%";}else{echo $_bonif1c;} ?></td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>">
							<?php echo $_bonif3a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif3a){echo "X";} else {echo $_bonif3b;} ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif3b){echo "%";}else{echo $_bonif3c;} ?> </td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>">
							<?php echo $_bonif6a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif6a){echo "X";} else {echo $_bonif6b;} ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif6b){echo "%";}else{echo $_bonif6c;} ?> </td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>">
							<?php echo $_bonif12a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif12a){echo "X";} else {echo $_bonif12b;} ?></td>
                       	<td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif12b){echo "%";}else{echo $_bonif12c;} ?></td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>">
							<?php echo $_bonif24a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif24a){echo "X";} else {echo $_bonif24b;} ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif24b){echo "%";}else{echo $_bonif24c;} ?></td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>">
							<?php echo $_bonif36a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif36a){echo "X";} else {echo $_bonif36b;} ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif36b){echo "%";}else{echo $_bonif36c;} ?></td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>">
							<?php echo $_bonif48a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif48a){echo "X";} else {echo $_bonif48b;} ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif48b){echo "%";}else{echo $_bonif48c;} ?></td>
                    	<td align="center" style="width:20px; border-left: 2px solid; <?php echo $estilo_fila; ?>">
							<?php echo $_bonif72a; ?></td>
                        <td align="center" style="width:20px; <?php echo $estilo_fila; ?>">
							<?php if($_bonif72a){echo "X";} else {echo $_bonif72b;} ?></td>
                        <td align="center" style="width:20px; border-right: 2px solid; <?php echo $estilo_fila; ?>">
							<?php if($_bonif72b){echo "%";}else{echo $_bonif72c;} ?></td>                         
					</tr> <?php 
				} //FIN del FOR 
			} ?>                    
		</tbody>
                
        <tfoot>
        	<tr><th colspan="32" style="border:none;"></th></tr>
        	<tr>
               	<th colspan="32" align="left" style="border:2px solid #000;" align="left">
                	<strong>Plazo de Pago:</strong>
                   	* Pedidos generales 30 dias
                    * Pedidos mas de 10 referencias y mas de $ 3.000 (que cumplan las dos condiciones)  30  y 60 dias. O 45 dias
				</th>                 
            </tr>
            <tr><th colspan="32" style="border:none;"></th></tr>
            <tr>
               	<th colspan="32" align="left">                	
                	<strong>ATENCION:</strong> Los articulos con condici&oacute;n de IVA Gravado se encuentran identificados con su correspondiente alicuota en la columna IVA, y su precio P&uacute;blico tienen incluido este valor. A los precios Drogueria y Digitados se les deber&aacute; adicionar el IVA. OAM: Corresponde a Ofertas, Altas o Modificaciones
				</th>                                   
            </tr>
        </tfoot>
	</table> 
</body>
</html>                
               
               