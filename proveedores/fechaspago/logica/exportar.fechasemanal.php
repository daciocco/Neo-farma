<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_fecha		=	empty($_REQUEST['fecha']) 	? 0 : $_REQUEST['fecha'];
$backURL	= 	empty($_REQUEST['backURL'])	? '/pedidos/transfer/gestion/liquidacion/': $_REQUEST['backURL'];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment; filename=FechaSemanalPagos-".$_fecha.".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
<head></head>
<body>

<div id="cuadro-fechaspago">
	<div id="muestra_fechaspago">    
   	<table id="tabla_fechaspago" name="tabla_fechaspago" class="tabla_fechaspago" cellpadding="0" cellspacing="0" border="0">
       	<thead>
        	<tr>
               	<th colspan="10" align="left">  Fecha de Pago: <?php echo $_fecha;?></th>
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
			$_saldo_total	=	0;
			$_facturas_pago	=	DataManager::getFacturasProveedor(NULL, 1, dac_invertirFecha($_fecha));
			if($_facturas_pago) {				
				foreach ($_facturas_pago as $k => $_fact_pago) {
					$_idfact		= 	$_fact_pago['factid'];
					$_idempresa		= 	$_fact_pago['factidemp'];
					$_idprov		= 	$_fact_pago['factidprov'];
					//Saco el nombre del proveedor
					$_proveedor	 	= 	DataManager::getProveedor('providprov', $_idprov, $_idempresa);
					$_nombre		= 	$_proveedor['0']['provnombre'];
					$_plazo			= 	$_fact_pago['factplazo'];
					$_tipo			= 	$_fact_pago['facttipo'];
					$_factnro		= 	$_fact_pago['factnumero'];
					$_fechacbte		= 	dac_invertirFecha($_fact_pago['factfechacbte']);
					$_fechavto		= 	dac_invertirFecha($_fact_pago['factfechavto']);
					$_saldo			= 	$_fact_pago['factsaldo'];
					$_observacion	= 	$_fact_pago['factobservacion'];
					$_activa		= 	$_fact_pago['factactiva'];
					
					((($k % 2) != 0)? $clase="background-color:#CCC; color:#000; font-weight:bold;" : $clase="");
					
					$_saldo_total	+=	$_saldo; ?>
                    
					<tr id="rutfact<?php echo $k;?>">
                    	<td style=" <?php echo $clase; ?> " align="center"><?php echo $_idempresa;?></td>
                        <td style=" <?php echo $clase; ?> " align="left"><?php echo $_idprov;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $_nombre;?></td>
                        <td style=" <?php echo $clase; ?> " align="center"><?php echo $_plazo;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $_fechavto;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $_tipo;?></td>
                        <td style=" <?php echo $clase; ?> " align="left"><?php echo $_factnro;?></td>
                        <td style=" <?php echo $clase; ?> "><?php echo $_fechacbte;?></td>
                        <td style=" <?php echo $clase; ?> " align="right"><?php echo $_saldo;?></td>
                        <td style=" <?php echo $clase; ?> " align="left"><?php echo $_observacion;?></td>
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
                <th colspan="1" height="30px" style="border:none; font-weight:bold;" align="right"><?php echo $_saldo_total; ?></th>
                <th colspan="1" height="30px" style="border:none; font-weight:bold;"></th>
            </tr>
        </tfoot>
	</table>  
                  
	</div> <!-- FIN fechaspago  --> 
</div> <!-- FIN fechaspago -->

</body>
</html> 
          
               
               