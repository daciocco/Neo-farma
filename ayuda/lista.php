<div class="box_body"> 
	<fieldset>
		<legend>Instructivo</legend>  
    	<div class="bloque_1">   
    		<div id="file" style="overflow:auto;"></div>
    	</div>
    </fieldset>
</div> <!-- Fin box_body -->

<div class="box_seccion">   
    <div class="barra">
        <div class="bloque_5">
            <h1>Instructivos</h1>                	
        </div>
        <hr>
    </div> <!-- Fin barra -->
    
    <div class="lista"><?php	
        $ruta 	= $_SERVER['DOCUMENT_ROOT'].'/pedidos/ayuda/archivos/';
        $data	= dac_listar_directorios($ruta);	
        if($data){ ?>
            <table align="center">
                <thead>
                    <tr align="left">
                        <th>Subido</th>
                        <th>Instructivo</th>
                    </tr>
                </thead>
                <tbody>	
                    <?php
                    $row = 0;
                    foreach ($data as $file => $timestamp) {
                        $name 		=	explode("-", $timestamp);	
						$archivo = trim($name[3]);
						$row += 1;
						(($row % 2) == 0)? $clase="par" : $clase="impar"; 
					
						?>                    
						<tr class="<?php echo $clase;?> cursor-pointer" onclick="javascript:dac_ShowFactura('<?php echo $archivo;?>')">
							<td><?php echo $name[0]."/".$name[1]."/".$name[2]; ?></td>
							<td><?php echo $archivo; ?></td>
						</tr>
						<?php
                    } ?>
                </tbody>
            </table> <?php
        } else { ?>
            <table>
                <thead>
                    <td colspan="3" align="center"><?php echo "No hay archivos cargados"; ?></td>	
                </thead>  
            </table><?php 
        } ?>
    </div> <!-- Fin listar -->	
</div> <!-- Fin box_seccion --> 
<hr>

<script language="JavaScript" type="text/javascript">
	function dac_ShowFactura(archivo){
		$("#file").empty();		
		campo	= 	'<iframe src=\"https://docs.google.com/gview?url=https://neo-farma.com.ar/pedidos/ayuda/archivos/'+archivo+'&embedded=true\" style=\"width:560px; height:800px;\" frameborder=\"0\"></iframe>';            
        $("#file").append(campo);
	}
</script>