<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.ToolBar.php");
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/localidades/': $_REQUEST['backURL'];
?>

<script type="text/javascript">
	function dac_addLocalidad(){
		var selected = [];
	 	$("input:checkbox[name=editSelected]:checked").each(function() {
			selected.push( $(this).val() );
        });		
		$("#editSelected2").val(selected);
	}
	
	function dac_searchSelect(idProv, idZonaV, idZonaD){
		$.ajax({
			type : 	'POST',
			cache:	false,
			url : 	'/pedidos/localidades/logica/ajax/getLocalidades.php',				
			data:	{	idProv	:	idProv,
						idZonaV	: 	idZonaV,
						idZonaD	:	idZonaD
					},
			beforeSend	: function () {
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
			},
			success : 	function (resultado) {
				if (resultado){
					var tabla = resultado;	
					document.getElementById('tablaLocalidades').innerHTML = tabla;
					$('#box_cargando').css({'display':'none'});
				} else {
					$('#box_cargando').css({'display':'none'});
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

<div class="box_body">   	
	<div class="bloque_1">     
		<fieldset id='box_error' class="msg_error">          
			<div id="msg_error" align="center"></div>
		</fieldset>  
		<fieldset id='box_cargando' class="msg_informacion" style="alignment-adjust:central;">   
			<div id="msg_cargando" align="center"></div>      
		</fieldset>
		<fieldset id='box_confirmacion' class="msg_confirmacion">
			<div id="msg_confirmacion" align="center"></div>     
		</fieldset>
	</div>
	
	<div class="barra">
		<div class="bloque_7">
			<h1>Localidades</h1>
		</div>
		
		<div class="bloque_8">
        	<a href="logica/exportar.localidades.php" title="Exportar"> 
				<img src="/pedidos/images/icons/export_excel.png" onmouseover="this.src='/pedidos/images/icons/export_excel-hover.png'" onmouseout="this.src='/pedidos/images/icons/export_excel.png'" border="0" align="absmiddle"/>
			</a>
		</div>			  
		<div class="bloque_7">
			<select id="provSelect" onchange="javascript:dac_searchSelect(provSelect.value, zonaVSelect.value, zonaDSelect.value);"><?php
                $provincias	= DataManager::getProvincias(); 
                if (count($provincias)) { ?>					
					<option id="0" value="0">Provincia</option> <?php
                    foreach ($provincias as $k => $prov) {
                        $provId		=	$prov["provid"];
                        $provNombre	=	$prov["provnombre"]; ?>                        
                        <option id="<?php echo $provId; ?>" value="<?php echo $provId; ?>"><?php echo $provNombre; ?></option>
                        <?php
                    }                       
                } ?>
            </select>
      	</div>		  
		<div class="bloque_7">            
            <select id="zonaVSelect" onchange="javascript:dac_searchSelect(provSelect.value, zonaVSelect.value, zonaDSelect.value);"><?php
                $zonasVenta	= DataManager::getZonas(0, 0, 1); 
                if (count($zonasVenta)) { ?>
					<option id="0" value="0">Zona Venta</option> <?php
                    foreach ($zonasVenta as $k => $zonasV) {
                        $zonaVNro	=	$zonasV["zzona"];
                        $zonaVNombre=	$zonasV["znombre"]; ?>
                        <option value="<?php echo $zonaVNro; ?>"><?php echo $zonaVNro." - ".$zonaVNombre; ?></option> <?php
                    }                       
                } ?>
            </select>  
        </div>		  
		<div class="bloque_7">
                <select id="zonaDSelect" onchange="javascript:dac_searchSelect(provSelect.value, zonaVSelect.value, zonaDSelect.value);"><?php
                $zonasDistribucion	= DataManager::getZonasDistribucion(); 
                if (count($zonasDistribucion)) {?>
					<option id="0" value="0">Zona Distribuci&oacute;n</option> <?php
                    foreach ($zonasDistribucion as $k => $zonasD) {
						$zonaDId	=	$zonasD["IdZona"];
                        $zonaDNombre=	$zonasD["NombreZN"]; ?>
                        <option value="<?php echo $zonaDId; ?>"><?php echo $zonaDId." - ".$zonaDNombre; ?></option>
                        <?php
                    }                       
                } ?>
            </select>
      	</div>		  
			  
		<div class="bloque_7">	 
			<input id="txtBuscar" type="search" autofocus placeholder="Buscar por P&aacute;gina">
			<input id="txtBuscarEn" type="text" value="tblLocalidades" hidden>
		</div>
		<hr>
	</div> <!-- Fin barra -->

	<div class="lista_super">
        <div id='tablaLocalidades'></div>       
        <?php
		echo "<script>";
		echo "javascript:dac_searchSelect('1')";
		echo "</script>"; ?>
    </div>
    
    <?php
   	//BARRA DE HERRAMIENTAS
	$_links 		= array();
	$_links[1][]	= array('url'=>'editar.php', 'texto'=>'<img src=../images/icons/icono-nuevo.png border=0 align=absmiddle title=Nuevo />', 'class'=>'newitem');
	$_params 		= array(
		'modo'		=> 1,
		'separador' => '',
		'estilo'	=> 'toolbar',
		'aspecto'	=> 'links',
		'links'		=> $_links[1]);
	$bar = ToolBar::factory($_params); ?>
	<div class="toolbar"><?php $bar->show(); ?></div>   		
		
		
	<div class="barra">
		<div class="buscadorizq">
			<h1>Excepciones</h1>                	
		</div>
		<div class="buscadorder">
			<input id="txtBuscar2" type="search" autofocus placeholder="Buscar..."/>
			<input id="txtBuscarEn2" type="text" value="tblExcepciones" hidden/>
		</div> 
		<hr>     
	</div> <!-- Fin barra -->            
	<div class="lista"> <?php
		$zonasExpecion	= DataManager::getZonasExcepcion();
		if(count($zonasExpecion)){
			echo	"<table id=\"tblExcepciones\" class=\"datatab\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"table-layout:fixed;\">";

			echo	"<thead><tr align=\"left\"><th>Localidad</th><th>Zona</th><th>Emp</th><th>Cuenta</th><th>Zona Excepción</th></thead>";
			echo	"<tbody>";
			
			foreach ($zonasExpecion as $k => $ze) {
				$zeIdLoc	= $ze['zeIdLoc'];
				$zeCtaId	= $ze['zeCtaId'];
				$zeZona		= $ze['zeZona'];						
				$idCuenta	= DataManager::getCuenta('ctaidcuenta', 'ctaid', $zeCtaId);
				$empresa	= DataManager::getCuenta('ctaidempresa', 'ctaid', $zeCtaId);
				$localidad	= DataManager::getLocalidad('locnombre', $zeIdLoc);
				$zonasLoc	= DataManager::getLocalidad('loczonavendedor', $zeIdLoc);
				
				((($k % 2) == 0)? $clase="par" : $clase="impar");

				echo "<tr class=".$clase.">";
				echo "<td height=\"15\">".$localidad."</td><td >".$zonasLoc."</td><td >".$empresa."</td><td >".$idCuenta."</td><td >".$zeZona."</td>";
				echo "</tr>";	
			}			
			echo "</tbody></table>";
		} ?>
	</div> <!-- Fin lista -->	
</div> <!-- Fin box_cuerpo --> 

<div class="box_seccion"> 	
	<div class="barra">
		<div class="buscadorizq">
			<h1>Zonas</h1>
		</div>
		<hr>	
	</div>
	<div class="lista_super">
		<?php
		$_LPP		= 10;
		$_total 	= DataManager::getNumeroFilasTotales('TZonas', 0);
		$_paginas 	= ceil($_total/$_LPP);
		$_pag		= isset($_REQUEST['pag']) ? min(max(1,$_REQUEST['pag']),$_paginas) : 1;
		$_imgFirst	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-first.png");
		$_imgLast 	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-last.png");
		$_imgNext	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-next.png");
		$_imgPrev	= sprintf("<img src=\"%s\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" id=\"go_first\" />","../images/icons/icono-previous.png");
		$_GOFirst	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, 1		,	$_imgFirst);
		$_GOPrev3	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag-3	,	$_pag-3);
		$_GOPrev2	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag-2	,	$_pag-2);
		$_GOPrev	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag-1	,	$_imgPrev);
		$_GOActual	= sprintf("%s", $_pag);
		$_GONext	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag+1	,	$_imgNext);
		$_GONext2	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag+2	,	$_pag+2);
		$_GONext3	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_pag+3	,	$_pag+3);
		$_GOLast	= sprintf("<a href=\"%s?pag=%d\">%s</a>", $backURL, $_paginas,	$_imgLast);
		?>
		<table class="datatab" border="0" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" align="center" width="25%" height="18">Zona</th>
					<th scope="col" width="50%">Nombre</th>
					<th scope="colgroup" colspan="3" align="center" width="25%">Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php 	
				$_zonas	= DataManager::getZonas($_pag, $_LPP, 1);
				$_max	 	= count($_zonas); 	// la última página vendrá incompleta
				for( $k=0; $k < $_LPP; $k++ ) {
					if ($k < $_max) {
						$_zona 		= $_zonas[$k];
						$_numero	= $_zona['zzona'];
						$_nombre	= $_zona['znombre'];

						$_status	= ($_zona['zactivo']) ? "<img src=\"../images/icons/icono-activo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"desactivar\"/>" : "<img src=\"../images/icons/icono-desactivo-claro.png\" border=\"0\" align=\"absmiddle\" title=\"activar\"/>";

						if ($_SESSION["_usrrol"]=="A"){
							$_editar	= sprintf( "<a href=\"/pedidos/zonas/editar.php?zid=%d&backURL=%s\" title=\"editar zona\">%s</a>", $_zona['zid'], $_SERVER['PHP_SELF'], "<img src=\"../images/icons/icono-editar.png\" border=\"0\" align=\"absmiddle\" />");

							$_borrar	= sprintf( "<a href=\"/pedidos/zonas/logica/changestatus.php?zid=%d&backURL=%s&pag=%s\" title=\"borrar zona\">%s</a>", $_zona['zid'], $_SERVER['PHP_SELF'], $_pag, $_status);

							$_eliminar 	= sprintf ("<a href=\"/pedidos/zonas/logica/eliminar.zona.php?zid=%d&backURL=%s&nrozona=%s\" title=\"eliminar zona\" onclick=\"return confirm('&iquest;Est&aacute; Seguro que desea ELIMINAR LA ZONA?')\"> <img src=\"../images/icons/icono-eliminar-claro.png\" border=\"0\" align=\"absmiddle\" /> </a>", $_zona['zid'], $_SERVER['PHP_SELF'], $_numero, "eliminar");
						} else {
							$_editar	= "&nbsp;";
							$_borrar	= "&nbsp;";
							$_eliminar 	= "&nbsp;";
						}

					} else {
						$_numero	= "&nbsp;";
						$_nombre	= "&nbsp;";
						$_editar	= "&nbsp;";
						$_borrar	= "&nbsp;";
						$_eliminar	= "&nbsp;";
					}

					if ($_SESSION["_usrrol"]=="V"){
						$_editar	= "&nbsp;";
						$_borrar	= "&nbsp;";
						$_eliminar	= "&nbsp;";	
					}

					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					echo sprintf("<td height=\"15\" align=\"center\">%s</td><td>%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td><td align=\"center\">%s</td>", $_numero, $_nombre, $_editar, $_borrar, $_eliminar);
					echo sprintf("</tr>");
				} ?>
			</tbody>
		</table>  
	</div>
	
	<?php
    if ( $_paginas > 1 ) {
        $_First = ($_pag > 1) ? $_GOFirst : "&nbsp;";
        $_Prev	= ($_pag > 1) ? $_GOPrev : "&nbsp;";
        $_Last	= ($_pag < $_paginas) ? $_GOLast : "&nbsp;";
        $_Next	= ($_pag < $_paginas) ? $_GONext : "&nbsp;";
		$_Prev2 = $_Next2 = $_Prev3 = $_Next3 = '';
        $_Actual= $_GOActual;
        if ( $_paginas > 4 ) {
            $_Prev2	= ($_pag > 2) ? $_GOPrev2 : "&nbsp;";
            $_Next2	= ($_pag < $_paginas-2) ? $_GONext2 : "&nbsp;";
        }
		
        if ( $_paginas > 6 ) {
            $_Prev3	= ($_pag > 3) ? $_GOPrev3 : "&nbsp;";
            $_Next3	= ($_pag < $_paginas-3) ? $_GONext3 : "&nbsp;";
        }
		
        echo("<table class=\"paginador\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>"); 
        echo sprintf("<td height=\"16\">Mostrando p&aacute;gina %d de %d</td><td width=\"20\">%s</td><td width=\"20\">%s</td><td width=\"20\">%s</td><td width=\"20\">%s</td><td width=\"20\">%s</td><td width=\"20\">%s</td><td width=\"20\">%s</td><td width=\"20\">%s</td><td width=\"20\">%s</td>", $_pag, $_paginas, $_First, $_Prev3, $_Prev2, $_Prev, $_Actual, $_Next, $_Next2, $_Next3, $_Last); 
        echo("</tr></table>"); 
    }
	
    //BARRA DE HERRAMIENTAS
    $_links 		= array();
    $_links[1][]	= array('url'=>'/pedidos/zonas/editar.php', 'texto'=>'<img src=../images/icons/icono-nuevo.png border=0 align=absmiddle title=Nuevo />', 'class'=>'newitem');
    $_params 		= array(
        'modo'		=> 1,
        'separador' => '',
        'estilo'	=> 'toolbar',
        'aspecto'	=> 'links',
        'links'		=> $_links[1]);
    $bar = ToolBar::factory($_params); ?> 

	<div class="toolbar" style="margin:10px 0; padding-left:5px;"><?php $bar->show(); ?></div>
	
	
	<?php if ($_SESSION["_usrrol"]!="V"){ ?>
	<form id="frmZonaLocalidades" class="fm_edit_seccion" method="POST"> 
		<fieldset>
			<fieldset id='box_observacion' class="msg_alerta" style="display: block;">          
				<div id="msg_atencion" align="center">Las zonas de venta [95, 99, 100, 199, 995] serán discriminadas en el cambio. <br> Ante cualquier duda revise el listado de zonas.</div>
			</fieldset> 
			<legend>Cambiar ZONAS</legend>			
			<div class="bloque_2">
				<input type="radio" name="radioZonas" value="localidad" style="width:20px; height:20px; margin-left: 0px;">
				<input type="text" id="editSelected2" name="editSelected2" hidden="hidden">
				<laber><h2>POR LOCALIDAD</h2></laber>
			</div>

			<div class="bloque_2">	
				<input type="radio" name="radioZonas" value="provincia" style="width:20px; height:20px; margin-left: 0px;">
				<laber><h2>POR PROVINCIA</h2></laber>
			</div>

			<div class="bloque_1">				
				<select name="provincia"> 
					<option value="0" selected> Provincia... </option> <?php
					$provincias	= DataManager::getProvincias(); 
					if (count($provincias)) {	
						$idprov = 0;
						foreach ($provincias as $k => $prov) {	
							$selected = ($provincia == $prov["provid"]) ? "selected" : ""; ?>
							<option id="<?php echo $prov["provid"]; ?>" value="<?php echo $prov["provid"]; ?>" <?php echo $selected; ?>><?php echo $prov["provnombre"]; ?></option>   <?php
						}                            
					} ?> 
				</select>
			</div>

			<div class="bloque_2">
				<select name="zonaVSelect"><?php
					$zonasVenta	= DataManager::getZonas(0, 0, 1); 
					if (count($zonasVenta)) { ?>
						<option id="0" value="0">Zona Venta</option> <?php
						foreach ($zonasVenta as $k => $zonasV) {
							$zonaVNro	=	$zonasV["zzona"];
							$zonaVNombre=	$zonasV["znombre"]; ?>
							<option value="<?php echo $zonaVNro; ?>"><?php echo $zonaVNro." - ".$zonaVNombre; ?></option> <?php
						}                       
					} ?>
				</select>  
			</div>

			<div class="bloque_2">
				<select name="zonaDSelect"><?php
					$zonasDistribucion	= DataManager::getZonasDistribucion(); 
					if (count($zonasDistribucion)) {?>
						<option id="0" value="0">Zona Distribuci&oacute;n</option> <?php
						foreach ($zonasDistribucion as $k => $zonasD) {
							$zonaDId	=	$zonasD["IdZona"];
							$zonaDNombre=	$zonasD["NombreZN"]; ?>
							<option value="<?php echo $zonaDId; ?>"><?php echo $zonaDId." - ".$zonaDNombre; ?></option>
							<?php
						}                       
					} ?>
				</select>  
			</div>

			<div class="bloque_1"></div>

			<div class="bloque_1">
				<input type="radio" name="radioZonas" value="vendedor" style="width:20px; height:20px; margin-left: 0px;">
				<laber><h2>POR ZONA DE VENTA (VENDEDOR DE HIPER)</h2></laber>				
			</div>	

			<div class="bloque_2">
				<label>Origen</label>
				<select name="zonaVOrigen"><?php
					$zonasVenta	= DataManager::getZonas(0, 0, 1); 
					if (count($zonasVenta)) { ?>
						<option id="0" value="0">Zona Venta 1</option> <?php
						foreach ($zonasVenta as $k => $zonasV) {
							$zonaVNro	=	$zonasV["zzona"];
							$zonaVNombre=	$zonasV["znombre"]; ?>
							<option value="<?php echo $zonaVNro; ?>"><?php echo $zonaVNro." - ".$zonaVNombre; ?></option> <?php
						}                       
					} ?>
				</select>  
			</div>

			<div class="bloque_2">
				<label>Destino</label>
				<select name="zonaVDestino"><?php
					$zonasVenta	= DataManager::getZonas(0, 0, 1); 
					if (count($zonasVenta)) { ?>
						<option id="0" value="0">Zona Venta 2</option> <?php
						foreach ($zonasVenta as $k => $zonasV) {
							$zonaVNro	=	$zonasV["zzona"];
							$zonaVNombre=	$zonasV["znombre"]; ?>
							<option value="<?php echo $zonaVNro; ?>"><?php echo $zonaVNro." - ".$zonaVNombre; ?></option> <?php
						}                       
					} ?>
				</select>  
			</div>	

			<div class="bloque_1"></div>

			<div class="bloque_1">
				<input type="radio" name="radioZonas" value="distribucion" style="width:20px; height:20px; margin-left: 0px;">
				<laber><h2>POR ZONA DE DISTRIBUCIÓN</h2></laber>
			</div>	

			<div class="bloque_2">
				<label>Origen</label>
				<select name="zonaDOrigen"><?php
					$zonasDistribucion	= DataManager::getZonasDistribucion(); 
					if (count($zonasDistribucion)) {?>
						<option id="0" value="0">Zona Distribuci&oacute;n 1</option> <?php
						foreach ($zonasDistribucion as $k => $zonasD) {
							$zonaDId	=	$zonasD["IdZona"];
							$zonaDNombre=	$zonasD["NombreZN"]; ?>
							<option value="<?php echo $zonaDId; ?>"><?php echo $zonaDId." - ".$zonaDNombre; ?></option> <?php
						}                       
					} ?>
				</select>  
			</div>

			<div class="bloque_2">
				<label>Destino</label>
				<select name="zonaDDestino"><?php
					$zonasDistribucion	= DataManager::getZonasDistribucion(); 
					if (count($zonasDistribucion)) {?>
						<option id="0" value="0">Zona Distribuci&oacute;n 2</option> <?php
						foreach ($zonasDistribucion as $k => $zonasD) {
							$zonaDId	=	$zonasD["IdZona"];
							$zonaDNombre=	$zonasD["NombreZN"]; ?>
							<option value="<?php echo $zonaDId; ?>"><?php echo $zonaDId." - ".$zonaDNombre; ?></option>
							<?php
						}                       
					} ?>
				</select>  
			</div>

			<div class="bloque_1">
				<?php $urlSend	=	'/pedidos/localidades/logica/update.zonasLocalidad.php';?>
				<?php $urlBack	=	'/pedidos/localidades/';?>
				<a id="btnSend" title="Enviar" style="cursor:pointer;"> 
					<img src="/pedidos/images/icons/icono-save.png" onmouseover="this.src='/pedidos/images/icons/icono-save-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-save.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(frmZonaLocalidades, '<?php echo $urlSend;?>', '<?php echo $urlBack;?>');"/>
				</a>
			</div>  

		</fieldset> 
	</form>
	
	<?php } ?>
	
	
	
</div> <!-- Fin box_seccion -->

<hr>
