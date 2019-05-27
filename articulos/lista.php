<?php
$idEmpresa		= isset($_REQUEST['empresa']) 		? $_REQUEST['empresa'] 		: 1;
$idLaboratorio	= isset($_REQUEST['laboratorio']) 	? $_REQUEST['laboratorio'] 	: 1;
$activos		= isset($_REQUEST['activos']) 		? $_REQUEST['activos'] 		: 1;

$_LPP		=  40;
$_pag		=	0;
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/articulos/': $_REQUEST['backURL'];
$_total 	= DataManager::getArticulos(0, 0, '', $activos, $idLaboratorio, $idEmpresa);
$_paginas 	= ceil(count($_total)/$_LPP);
$_pag		= isset($_REQUEST['pag']) ? min(max(1,$_REQUEST['pag']),$_paginas) : 1;
$_GOFirst	= sprintf("<a class=\"icon-go-first\" href=\"%s?pag=%d&empresa=%d&laboratorio=%d&activos=%d\"></a>", $backURL, 1, $idEmpresa, $idLaboratorio, $activos);
$_GOPrev	= sprintf("<a class=\"icon-go-previous\" href=\"%s?pag=%d&empresa=%d&laboratorio=%d&activos=%d\"></a>", $backURL, $_pag-1, $idEmpresa, $idLaboratorio, $activos);
$_GONext	= sprintf("<a class=\"icon-go-next\" href=\"%s?pag=%d&empresa=%d&laboratorio=%d&activos=%d\"></a>", $backURL, $_pag+1, $idEmpresa, $idLaboratorio, $activos);
$_GOLast	= sprintf("<a class=\"icon-go-last\" href=\"%s?pag=%d&empresa=%d&laboratorio=%d&activos=%d\"></a>", $backURL, $_paginas, $idEmpresa, $idLaboratorio, $activos);
$btnPreciosCondiciones =	sprintf("<input type=\"button\" id=\"btPreciosCondiciones\" value=\"Actualizar $ Condiciones\" title=\"Actualizar Precio en Condiciones Comerciales\"/>");
$btnNuevo	= sprintf( "<a class=\"icon-new\" href=\"editar.php\" title=\"Nuevo\"><img title=\"Nuevo\"/></a>");
?>

<script language="javascript" type="text/javascript">
	//--------------------
	// Select   Articulos
	function dac_selectArticulos(pag, rows, empresa, activos, laboratorio) {	
		$.ajax({
			type : 	'POST',
			cache:	false,
			url : 	'/pedidos/articulos/logica/ajax/getArticulo.php',				
			data:	{	pag			: pag,
						rows		: rows,
						empresa		: empresa,
						activos		: activos,
						laboratorio	: laboratorio
					},
			beforeSend	: function () {
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success : 	function (resultado) {
				$('#box_cargando').css({'display':'none'});
				if (resultado){
					var tabla = resultado;	
					document.getElementById('tablaArticulos').innerHTML = tabla;
				} else { 
					$('#box_error').css({'display':'block'});
					$("#msg_error").html("Error al consultar los registros.");
				}
			},		
			error: function () {
				$('#box_cargando').css({'display':'none'});	
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("Error al consultar los registros.");	
			},
		});	
	}
</script>

<div class="box_body"> <!-- datos --> 
	<div class="bloque_1"> 
		<fieldset id='box_cargando' class="msg_informacion">
			<div id="msg_cargando"></div>      
		</fieldset>            
		<fieldset id='box_error' class="msg_error">
			<div id="msg_error"></div>
		</fieldset>
		<fieldset id='box_confirmacion' class="msg_confirmacion">
			<div id="msg_confirmacion"></div>      
		</fieldset>
  	</div>  
  	
    <div class="barra">
		<div class="bloque_7">             
            <select id="empselect" name="empselect" onchange="javascript:dac_selectArticulos(<?php echo $_pag; ?>, <?php echo $_LPP; ?>, empselect.value, actselect.value, labselect.value);"><?php
                $empresas	= DataManager::getEmpresas(1); 
                if (count($empresas)) {
                    foreach ($empresas as $k => $emp) {
                        $idemp		=	$emp["empid"];
                        $nombreEmp	=	$emp["empnombre"];	
                        ?><option id="<?php echo $idemp; ?>" value="<?php echo $idemp; ?>" <?php if ($idEmpresa == $idemp){ echo "selected"; } ?> ><?php echo $nombreEmp; ?></option><?php
                    }                       
                } ?>
            </select>
		</div> 
		
		<div class="bloque_7">   
			<select id="labselect" name="labselect" onchange="javascript:dac_selectArticulos(<?php echo $_pag; ?>, <?php echo $_LPP; ?>, empselect.value, actselect.value, labselect.value);"><?php
                $laboratorios	= DataManager::getLaboratorios(); 
                if (count($laboratorios)) {
                    foreach ($laboratorios as $k => $lab) {
                        $idlab		=	$lab["idLab"];
                        $nombreLab	=	$lab["Descripcion"];	
                        ?><option id="<?php echo $idlab; ?>" value="<?php echo $idlab; ?>" <?php if ($idLaboratorio == $idlab){ echo "selected"; } ?> ><?php echo $nombreLab; ?></option><?php
                    }                       
                } ?>
            </select>
        </div>
        
		<div class="bloque_7">            
            <select id="actselect" name="actselect" onchange="javascript:dac_selectArticulos(<?php echo $_pag; ?>, <?php echo $_LPP; ?>, empselect.value, actselect.value, labselect.value);">
			   <option value="1" <?php if ($activos == "1"){ echo "selected"; } ?> >Activos</option>
			   <option value="0" <?php if ($activos == "0"){ echo "selected"; } ?> >Inactivos</option>
            </select>
        </div>
        
        <div class="bloque_8"> 
        	<?php echo $btnNuevo; ?>
		</div> 
		
		<?php
		echo "<script>";
		echo "javascript:dac_selectArticulos($_pag, $_LPP, empselect.value, actselect.value, labselect.value)";
		echo "</script>";
		?>    
        <hr>
    </div> <!-- Fin barra -->
        
    <div class="lista_super">
        <div id='tablaArticulos'></div> 
    </div> <!-- Fin listar -->
    
    <?php
	if ( $_paginas > 1 ) {
		$_First = ($_pag > 1) ? $_GOFirst : "&nbsp;";
		$_Prev	= ($_pag > 1) ? $_GOPrev : "&nbsp;";
		$_Last	= ($_pag < $_paginas) ? $_GOLast : "&nbsp;";
		$_Next	= ($_pag < $_paginas) ? $_GONext : "&nbsp;";
		echo("<table class=\"paginador\" cellpadding=\"0\" cellspacing=\"0\"><tr>"); 
		echo sprintf("<td>Mostrando p&aacute;gina %d de %d</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td><td width=\"25\">%s</td>", $_pag, $_paginas, $_First, $_Prev, $_Next, $_Last); 
		echo("</tr></table>"); 
	} ?>
</div> <!-- Fin box body -->

<div class="box_seccion"> 
	<div class="barra" align="center">
   		<?php echo $btnPreciosCondiciones; ?>
   		<hr>
    </div> <!-- Fin barra -->
    <div class="barra">
        <div class="bloque_7">
			<select id="selectFiltro">
				<option value="artidart">Art&iacute;culo</option>
				<option value="artnombre">Nombre</option>
				<option value="artcodbarra">EAN</option>
			</select>
		</div>
		<div class="bloque_3">
			<input id="txtFiltro" onKeyPress="if (event.keyCode==13){ dac_mostrarFiltro(selectFiltro.value, this.value);return false;}" type="search" autofocus placeholder="Buscar..."/>
        </div>
        <hr>
    </div> <!-- Fin barra -->
 	  	
 	<div class="lista"> 
        <div id='tablaFiltroArticulos'></div> 
    </div> <!-- Fin listar -->
</div> <!-- Fin box_seccion -->
<hr>

<script type="text/javascript" src="logica/jquery/jqueryFooter.js"></script>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {	
		$('#empselect').change(function(){
			dac_redirectPaginacion();
		});					
		$('#labselect').change(function(){
			dac_redirectPaginacion();
		});			
		$('#actselect').change(function(){
			dac_redirectPaginacion();
		});			
		function dac_redirectPaginacion(){	
			var empresa 	= $('#empselect').val();
			var activos		= $('#actselect').val();	
			var laboratorio	= $('#labselect').val();
			var redirect = '?empresa='+empresa+'&laboratorio='+laboratorio+'&activos='+activos;
			document.location.href=redirect;
		}
	});	
</script>


