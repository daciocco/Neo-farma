<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_drogid	= empty($_REQUEST['drogid'])? 0 : $_REQUEST['drogid'];
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/transfer/gestion/liquidacion/': $_REQUEST['backURL'];

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Liquidacion-Unidades-Pendientes-".$_drogid.".xls");	
?>

<HTML LANG="es">
<TITLE>::. Exportacion de Datos (Liquidacion) .::</TITLE>
<head></head>
<body>

<div id="cuadro-liquidacion">
	<div id="muestra_liquidacion">    
    	<table id="tblliquidaciones" class="datatab" border="0" cellpadding="0" cellspacing="0">
            <thead>
            	<tr>
                	<th scope="col" colspan="3" align="center">UNIDADES PENDIENTES</th>	
                </tr>
            	<tr>
                	<th scope="col" colspan="3" align="left">Fecha Exportado: <?php echo date("d-m-Y"); ?></th>	
                </tr>
                <tr>
                    <th colspan="3" align="left"> <?php
                        $_droguerias	= DataManager::getDrogueria('');						
                        if (count($_droguerias)) { 
                            foreach ($_droguerias as $k => $_drogueria) {
                                $_drogueria		=	$_droguerias[$k];
                                $_Did			=	$_drogueria["drogtid"];
                                $_Didcliente 	= 	$_drogueria["drogtcliid"];
                                $_Dnombre	 	= 	$_drogueria["drogtnombre"];
                                $_Dlocalidad	=	$_drogueria["drogtlocalidad"]; 
                                                                                
                                if($_drogid == $_Didcliente){			
                                    echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; 
                                }
                            }
                        } ?>
                    </th>
                </tr>
                <tr>
                	<th scope="col" colspan="3"></th>	
                </tr>
                <tr>
                    <th scope="col" height="18">Fecha</th>
                    <th scope="col" >Nro. Transfer</th>
                    <th scope="col" >Cliente</th>
                </tr>
            </thead> 
			
			<tbody id="lista_liquidacion">
			
				<?php		
                $_transfers_liquidados	= DataManager::getTransfersLiquidados(NULL, 'LP', $_drogid); 
                if ($_transfers_liquidados) {
                    for( $k=0; $k < count($_transfers_liquidados); $k++ ){
                        $_detalle	= 	$_transfers_liquidados[$k];					
                        $_fecha 	=	$_detalle["ptfechapedido"];
                        $_nropedido	= 	$_detalle["ptidpedido"];	
                        $_nombre	= 	$_detalle["ptclirs"];
                        
                        ((($k % 2) != 0)? $clase="par" : $clase="impar");
                        ?>
                        <tr id="lista_liquidacion<?php echo $k;?>" class="<?php echo $clase;?>"> 
                            <td align="center"><?php echo $_fecha; ?></td>
                            <td align="center"><?php echo $_nropedido; ?></td>
                            <td align="center"><?php echo $_nombre; ?></td>
                        </tr>
                        <?php 
                    }
                } else { ?>
                    <tr>
                        <td scope="colgroup" colspan="3" height="25" align="center">No hay Transfer con UNIDADES PENDIENTES</td>
                    </tr> <?php
                } ?>
        	</tbody>
        </table>
        
        <div hidden="hidden"><button id="btnExport" hidden="hidden"></button></div>
	</div> <!-- FIN muestra_bonif --> 
</div> <!-- FIN liquidacion -->

</body>
</html> 
          
               
               