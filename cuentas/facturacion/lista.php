<div class="box_body"> 
	<fieldset>
		<legend>Factura</legend>  
    	<div class="bloque_1">   
    		<div id="fact" style="overflow:auto;"></div>
    	</div>
    </fieldset>
</div> <!-- Fin box_body -->

<div class="box_seccion">   
    <div class="barra">
        <div class="buscadorizq">
            <h1> Facturas Contrarreembolso</h1>                	
        </div>
        <hr>
    </div> <!-- Fin barra -->
    
    <div class="lista"><?php	
        $ruta 	= $_SERVER['DOCUMENT_ROOT'].'/pedidos/informes/archivos/facturas/contrareembolso/';
        $data	= dac_listar_directorios($ruta);	
        if($data){ ?>
            <table border="0" width="100%" align="center">
                <thead>
                    <tr align="left">
                        <th>Subido</th>
                        <th>Factura</th>
                    </tr>
                </thead>
                <tbody>	
                    <?php	
                    $fila 	= 0;
                    $zonas	= explode(", ", $_SESSION["_usrzonas"]);
                    foreach ($data as $file => $timestamp) {
                        $name 	=	explode("-", $timestamp);					
                        for($i = 0; $i < count($zonas); $i++){
                            $nro_zona	=	trim($name[3]);				
                            if($nro_zona == $zonas[$i]){
                                $archivo = trim($name[3])."-Factura-".$name[5]."-".$name[6]; 
                                
                                $fila = $fila + 1;
                                (($fila % 2) == 0)? $clase="par" : $clase="impar"; ?>                    
                                <tr class="<?php echo $clase;?>" onclick="javascript:dac_ShowFactura('<?php echo $archivo;?>')">
                                    <td><?php echo $name[0]."/".$name[1]."/".$name[2]; ?></td>
                                    <td><?php echo $name[5]."-".$name[6]; ?></td>
                                </tr>
                                <?php
                            }
                        }
                    } ?>
                </tbody>
            </table> <?php
        } else { ?>
            <table border="0" width="100%" align="center">
                <tr>
                    <td colspan="3"><?php echo "No hay facturas cargadas."; ?></td>	
                </tr>  
            </table><?php 
        } ?>
    </div> <!-- Fin listar -->	
</div> <!-- Fin box_seccion --> 
<hr>

<script language="JavaScript" type="text/javascript">
	function dac_ShowFactura(archivo){
		$("#fact").empty();		
		campo	= 	'<iframe src=\"https://docs.google.com/gview?url=https://neo-farma.com.ar/pedidos/informes/archivos/facturas/contrareembolso/'+archivo+'&embedded=true\" style=\"width:560px; height:800px;\" frameborder=\"0\"></iframe>';            
        $("#fact").append(campo);
		
	}
</script>