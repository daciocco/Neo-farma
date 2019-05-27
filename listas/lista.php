<?php
	$btnNuevo = sprintf( "<a href=\"\" title=\"Nuevo\"><img class=\"icon-new\"/></a>");
?>

<div class="box_body">
	<div class="bloque_1" align="center">
		<fieldset id='box_error' class="msg_error">          
			<div id="msg_error"></div>
		</fieldset>                                                                         
		<fieldset id='box_cargando' class="msg_informacion">    
			<div id="msg_cargando"></div>      
		</fieldset>
		<fieldset id='box_confirmacion' class="msg_confirmacion">
			<div id="msg_confirmacion"></div>      
		</fieldset>
	</div>
	
		<form id="fmLista" method="post">
			<input type="text" id="idLista" name="idLista" hidden="hidden">
			<fieldset>
				<legend>Lista de Precio</legend>		
				<div class="bloque_8">
					<label for="id">Lista</label>	
					<input type="text" id="id" name="id" readonly>
				</div>

				<div class="bloque_6">
					<label for="nombre">Nombre</label>	
					<input type="text" id="nombre" name="nombre" maxlength="50" class="text-uppercase">	
				</div>

				<div class="bloque_9">	
					<br>	
					<?php $urlSend	= '/pedidos/listas/logica/update.php';?>
					<a title="Enviar"> 
						<img class="icon-send" onclick="javascript:dac_sendForm(fmLista, '<?php echo $urlSend;?>');"/>
					</a>
				</div>

				<div class="bloque_1">
					<div id="desplegable" class="desplegable"> <?php
						$categoriasComerc = DataManager::getCategoriasComerciales(1); 
						if (count($categoriasComerc)) {	
							foreach ($categoriasComerc as $k => $catComerc) {
								$catComIdcat= $catComerc["catidcat"];
								$catNombre	= $catComerc["catnombre"]; ?>

								<input id="categoriaComer<?php echo $catComIdcat; ?>" type="checkbox" name="categoriaComer[]" value="<?php echo $catComIdcat; ?>" style="float:left;"><label><?php echo $catComIdcat." - ".$catNombre; ?></label><hr>

								<?php
							}                              
						} ?>	
					</div>			

				</div>
			</fieldset>
		</form>
	
</div>

<div class="box_seccion">
	<div class="barra">
       	<div class="bloque_5">
			<h1>Listas de Precios</h1>                	
        </div>
        <div class="bloque_5" align="right">
			<?php echo $btnNuevo ?>                	
        </div>
        <hr>
	</div> <!-- Fin barra -->
   
    <div class="lista_super">
        <table>
            <thead>
                <tr>
                    <th scope="colgroup" width="10%">Lista</th>
                    <th scope="colgroup" width="60%">Descripci&oacute;n</th>
                    <th scope="colgroup" width="10%">Categor&iacute;s</th>
                    <th scope="colgroup" align="center" width="20%">Acciones</th>
                </tr>
            </thead>
            
            <?php 	
            $listas	= DataManager::getListas();
			if($listas){
				foreach ($listas as $k => $lista) {
					$id				= $lista['IdLista'];
					$nombre			= $lista['NombreLT'];
					$catComercial 	= $lista['CategoriaComercial'];					
					$categorias		= str_replace(",", ", ", $catComercial);
					$activa			= $lista['Activa'];

					$_onClick = sprintf( "onClick='dac_newList(\"%s\", \"%s\", \"%s\")'", $id, $nombre, $catComercial);
					$_status  = ($activa) ? "<img class=\"icon-status-active\"/>" : "<img class=\"icon-status-inactive\"/>";
					$_borrar	= sprintf( "<a href=\"logica/changestatus.php?id=%d&backURL=%s\" title=\"Cambiar Estado\">%s</a>", $id, $_SERVER['PHP_SELF'], $_status);	

					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\" class=\"cursor-pointer\" %s >%s</td><td class=\"cursor-pointer\" %s>%s</td><td class=\"cursor-pointer\" %s>%s</td><td>%s</td>", $_onClick, $id, $_onClick, $nombre, $_onClick, $categorias, $_borrar);
					echo sprintf("</tr>");				
				} 
			}?>
        </table>
	</div>
</div>

<script>
	function dac_newList(id, nombre, catComercial){
		$('#id').val(id);
		$('#nombre').val(nombre);
		
		//	Categorías		
		if(catComercial){
			var categorias	= catComercial.split(",");
		}	
		
		//$('input:checkbox').removeAttr('checked');
		$("input[name='categoriaComer[]']").prop('checked', false);
		
		for (var i=0; i<categorias.length; i++){
			$('#categoriaComer'+categorias[i]).prop('checked', true);
		}	
	
	
	}
</script>


        
