<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$fecha		=	empty($_REQUEST['fecha']) 	? 0 : $_REQUEST['fecha'];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment; filename=FechaSemanalPagos-".$fecha.".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>

<div id="cuadro-fechaspago">
	<div id="muestra_fechaspago">    
   	<table id="tabla_fechaspago" name="tabla_fechaspago" border="0">
       	<thead>
        	<tr>
               	<th colspan="10" align="left">  Fecha de Pago: <?php echo $fecha;?></th>
            </tr>
            
            <tr><th colspan="10" align="left"></th> </tr>
                                                      
            <tr height="60px;">  <!-- Títulos de las Columnas -->
           		<th align="center" style="background-color:#333; color:#FFF;">Emp</th>   
            	<th align="center" style="background-color:#333; color:#FFF;">C&oacute;digo</th>
                <th 			   style="background-color:#333; color:#FFF;">Proveedor</th>
                <th align="center" style="background-color:#333; color:#FFF;">Plazo</th>
                <th align="center" style="background-color:#333; color:#FFF;">Vencimiento</th> 
                <th align="center" style="background-color:#333; color:#FFF;">Tipo</th> 
                <th align="center" style="background-color:#333; color:#FFF;">Nro</th>    
                <th align="center" style="background-color:#333; color:#FFF;">Fecha de cbte</th>
                <th align="center" style="background-color:#333; color:#FFF;">Saldo</th>
                <th align="center" style="background-color:#333; color:#FFF;">Observaci&oacute;n</th>  
			</tr>
		</thead>
                 
        <tbody id="lista_fechaspago"> <?php 
			//hago consulta de fechas de pago en la fecha actual ordenada por proveedor			
			$saldoTotal	=	0;
			$facturasPago	=	DataManager::getFacturasProveedor(NULL, 1, dac_invertirFecha($fecha));
			if($facturasPago) {				
				foreach ($facturasPago as $k => $fact) {
					$idFact		= $fact['factid'];
					$idEmpresa	= $fact['factidemp'];
					$idProv		= $fact['factidprov'];
					//Saco el nombre del proveedor
					$proveedor	= DataManager::getProveedor('providprov', $idProv, $idEmpresa);
					$nombre		= $proveedor['0']['provnombre'];
					$plazo		= $fact['factplazo'];
					$tipo		= $fact['facttipo'];
					$factNro	= $fact['factnumero'];
					$fechacbte	= dac_invertirFecha($fact['factfechacbte']);
					$fechavto	= dac_invertirFecha($fact['factfechavto']);
					$saldo		= $fact['factsaldo'];
					$observacion= $fact['factobservacion'];
					$activa		= $fact['factactiva'];
					
					((($k % 2) != 0)? $clase="background-color:#CCC; color:#000; font-weight:bold;" : $clase="");
					
					$saldoTotal	+=	$saldo; ?>
                    
					<tr id="rutfact<?php echo $k;?>">
                    	<td style=" <?php echo $clase; ?> " align="center"><?php echo $idEmpresa;?></td>
                        <td style=" <?php echo $clase; ?> " align="left"><?php echo $idProv;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $nombre;?></td>
                        <td style=" <?php echo $clase; ?> " align="center"><?php echo $plazo;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $fechavto;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $tipo;?></td>
                        <td style=" <?php echo $clase; ?> " align="left"><?php echo $factNro;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $fechacbte;?></td>
                        <td style=" <?php echo $clase; ?> " align="right"><?php echo $saldo;?></td>
                        <td style=" <?php echo $clase; ?> " align="left"><?php echo $observacion;?></td>
                    </tr> <?php
				}				
			} else { ?>
				<tr class="impar"><td colspan="9" align="center">No hay pagos cargados</td></tr><?php           
			} ?>                
		</tbody>
                
        <tfoot>
        	<tr>
            	<th colspan="7" height="30px" style="border:none; font-weight:bold;"></th>
                <th colspan="1" height="30px" style="border:none; font-weight:bold;">Total</th>
                <th colspan="1" height="30px" style="border:none; font-weight:bold;" align="right"><?php echo $saldoTotal; ?></th>
                <th colspan="1" height="30px" style="border:none; font-weight:bold;"></th>
            </tr>
        </tfoot>
	</table>  
                  
	</div> <!-- FIN fechaspago  --> 
</div> <!-- FIN fechaspago -->

</body>
</html> 
          
               
               