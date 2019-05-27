<?php
$empresa	= isset($_REQUEST['empresa']) 	? $_REQUEST['empresa'] 	: 1;
$tipo		= isset($_REQUEST['tipo']) 		? $_REQUEST['tipo'] 	: 'C';
$usrZonas	= isset($_SESSION["_usrzonas"]) ? $_SESSION["_usrzonas"]: '';
?>

<script language="javascript" type="text/javascript">
	// Select  Cuentas  // 
	function dac_selectCuentas(pag, rows, empresa, activos, tipo) {	
		$.ajax({
			type : 	'POST',
			cache:	false,
			url : 	'/pedidos/cuentas/logica/ajax/getCuenta.php',	
			data:	{	pag		:	pag,
						rows	:	rows,
						empresa	:	empresa,
						activos	: 	activos,
						tipo	:	tipo
					},
			beforeSend	: function () {
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success : 	function (resultado) {
				if (resultado){
					var tabla = resultado;	
					document.getElementById('tablaCuentas').innerHTML = tabla;	
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

<div class="box_body"> <?php
	$btnNuevo	= 	sprintf( "<a href=\"editar.php\" title=\"Nuevo\"><img class=\"icon-new\"/></a>");	
	$backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/cuentas/'	:	$_REQUEST['backURL'];
	$_LPP		= 500;
	$_total 	= count(DataManager::getCuentas(0, 0, $empresa, '', "'".$tipo."'", $usrZonas));
	$_paginas 	= ceil($_total/$_LPP);
	$_pag		= isset($_REQUEST['pag']) ? min(max(1,$_REQUEST['pag']),$_paginas) : 1;
	$_GOFirst	= sprintf("<a class=\"icon-go-first\" href=\"%s?pag=%d&empresa=%d&tipo=%s\"></a>", $backURL, 1		, $empresa, $tipo);
	$_GOPrev3	= sprintf("<a href=\"%s?pag=%d&empresa=%d&tipo=%s\">%s</a>", $backURL, $_pag-3	, $empresa, $tipo,	$_pag-3);
	$_GOPrev2	= sprintf("<a href=\"%s?pag=%d&empresa=%d&tipo=%s\">%s</a>", $backURL, $_pag-2	, $empresa, $tipo,	$_pag-2);
	$_GOPrev	= sprintf("<a class=\"icon-go-previous\" href=\"%s?pag=%d&empresa=%d&tipo=%s\"></a>", $backURL, $_pag-1	, $empresa, $tipo);
	$_GOActual	= sprintf("%s", $_pag);
	$_GONext	= sprintf("<a class=\"icon-go-next\" href=\"%s?pag=%d&empresa=%d&tipo=%s\"></a>", $backURL, $_pag+1	, $empresa, $tipo);
	$_GONext2	= sprintf("<a href=\"%s?pag=%d&empresa=%d&tipo=%s\">%s</a>", $backURL, $_pag+2	, $empresa, $tipo,	$_pag+2);
	$_GONext3	= sprintf("<a href=\"%s?pag=%d&empresa=%d&tipo=%s\">%s</a>", $backURL, $_pag+3	, $empresa, $tipo,	$_pag+3);
	$_GOLast	= sprintf("<a class=\"icon-go-last\" href=\"%s?pag=%d&empresa=%d&tipo=%s\"></a>", $backURL, $_paginas, $empresa, $tipo);
	?>
	<fieldset id='box_cargando' class="msg_informacion">
        <div id="msg_cargando"></div>      
    </fieldset>            
    <fieldset id='box_error' class="msg_error">
        <div id="msg_error"></div>
    </fieldset>
    <fieldset id='box_confirmacion' class="msg_confirmacion">
        <div id="msg_confirmacion"></div>      
    </fieldset>
	
    <div class="barra">
        <div class="bloque_7">        
            <input id="txtBuscar" type="search" autofocus placeholder="Buscar por P&aacute;gina"/>
            <input id="txtBuscarEn" type="text" value="tblCuentas" hidden/>
        </div>
        <div class="bloque_7">     
            <select id="empselect" name="empselect" onchange="javascript:dac_selectCuentas(<?php echo $_pag; ?>, <?php echo $_LPP; ?>, empselect.value, '', tiposelect.value);"><?php
                $empresas	= DataManager::getEmpresas(1); 
                if (count($empresas)) {
                    foreach ($empresas as $k => $emp) {
                        $idemp		=	$emp["empid"];
                        $nombreEmp	=	$emp["empnombre"];	
                        ?><option id="<?php echo $idemp; ?>" value="<?php echo $idemp; ?>" <?php if ($empresa == $idemp){ echo "selected"; } ?> ><?php echo $nombreEmp; ?></option><?php
                    }                       
                } ?>
            </select>
        </div>
        
        <div class="bloque_7">
            <select id="tiposelect" name="tiposelect" onchange="javascript:dac_selectCuentas(<?php echo $_pag; ?>, <?php echo $_LPP; ?>, empselect.value, '', tiposelect.value);"/> <?php
                $tiposCuenta	= DataManager::getTiposCuenta(1); 
                if (count($tiposCuenta)) {
                    foreach ($tiposCuenta as $k => $tipoCta) {
                        $ctaTipoId		=	$tipoCta["ctatipoid"];
                        $ctaTipo		=	$tipoCta["ctatipo"];
                        $ctaTipoNombre	=	$tipoCta["ctatiponombre"];	
                        ?><option id="<?php echo $ctaTipoId; ?>" value="<?php echo $ctaTipo; ?>" <?php if ($tipo == $ctaTipo){ echo "selected"; } ?>><?php echo $ctaTipoNombre; ?></option><?php
                    }                          
                } ?>
            </select>
        </div>
        <div class="bloque_7">
			<?php echo $btnNuevo ?>                	
        </div>
            
		<?php
		echo "<script>";
		echo "javascript:dac_selectCuentas($_pag, $_LPP, empselect.value, '', tiposelect.value)";
		echo "</script>";
		?> 
		<hr>
    </div> <!-- Fin barra -->
    
    <div class="lista_super">  
        <div id='tablaCuentas'></div> 
    </div> <!-- Fin listar -->	

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
    }  ?> 
</div> <!-- Fin box body -->

<div class="box_seccion"> 
	<div id="container"></div>
      
    <div class="barra">
    	<div class="bloque_7">
            <a title="Agenda" href="/pedidos/agenda/" target="_blank"> <img class="icon-calendar"/></a>
        </div>
		<div class="bloque_7">
			<select id="selectFiltro">
				<option value="ctaidcuenta">Cuenta</option>
				<option value="ctanombre">Nombre</option>
				<option value="ctacuit">Cuit</option>
			</select>
		</div>
		<div class="bloque_5">
			<input id="txtFiltro" onKeyPress="if (event.keyCode==13){ dac_mostrarFiltro(selectFiltro.value, this.value);return false;}" type="search" autofocus placeholder="Buscar..."/>
        </div>
        <hr>
    </div> <!-- Fin barra -->
 	  	
 	<div class="lista"> 
        <div id='tablaFiltroCuentas'></div> 
    </div> <!-- Fin listar -->
     	
	<?php		
	$cuentasPendientes	= DataManager::getCuentas(0, 0, $empresa, NULL, "'C', 'CT'", $usrZonas, 3, 'SolicitudAlta');
	if (count($cuentasPendientes)) { ?>	
		<div class="barra" style="background-color: #E49044;">
			<h1><strong>Existen Clientes con Solicitudes de Alta Pendientes</strong></h1>
			<hr>      
		</div>		
		<div class="lista"> 
			<?php
			echo	"<table id=\"tblFiltroCuentas\"  style=\"table-layout:fixed;\">";
			echo	"<thead><tr align=\"left\"><th>Emp</th><th>Cuenta</th><th>Nombre</th><th>Ult Fecha</th></tr></thead>";
			echo	"<tbody>";
			foreach ($cuentasPendientes as $k => $cuentaP) {				
				$id			= $cuentaP['ctaid'];
				$idCuenta	= $cuentaP['ctaidcuenta'];
				$fechaUpd	= $cuentaP['ctaupdate'];
				$idEmpresa	= $cuentaP['ctaidempresa'];
				$nombre		= $cuentaP['ctanombre'];

				$_editar	= sprintf( "onclick=\"window.open('editar.php?ctaid=%d')\" style=\"cursor:pointer;\"",$id);

				((($k % 2) == 0)? $clase="par" : $clase="impar");

				echo "<tr class=".$clase.">";
				echo "<td ".$_editar.">".$empresa."</td><td ".$_editar.">".$idCuenta."</td><td ".$_editar.">".$nombre."</td><td ".$_editar.">".$fechaUpd."</td>";
				echo "</tr>";
			} 
			echo "</tbody></table>";?>
		</div> <!-- Fin listar --> <?php 
	} ?>
</div> <!-- Fin box_seccion -->

<hr>

<?php
//----------------------------------------
$cantPS = DataManager::getCount("SELECT COUNT(*) FROM cuenta WHERE ctatipo='PS'"); 
$cantC 	= DataManager::getCount("SELECT COUNT(*) FROM cuenta WHERE (ctatipo='C' OR ctatipo='CT') AND ctaactiva='1' AND ctazona<>'95' AND (ctaidempresa='1' OR ctaidempresa='3')"); 
$cantCI	= DataManager::getCount("SELECT COUNT(*) FROM cuenta WHERE (ctatipo='C' OR ctatipo='CT') AND ctaactiva='0' AND ctazona<>'95' AND (ctaidempresa='1' OR ctaidempresa='3')");
$cantT 	= DataManager::getCount("SELECT COUNT(*) FROM cuenta WHERE (ctatipo='T' OR ctatipo='TT') AND ctazona<>'95' AND (ctaidempresa='1' OR ctaidempresa='3')"); 
$total  = $cantPS + $cantC + $cantCI + $cantT;

$cantPS	= round((($cantPS*100)/$total), 2);
$cantC	= round((($cantC*100)/$total), 2);
$cantCI	= round((($cantCI*100)/$total), 2);
$cantT	= round((($cantT*100)/$total), 2);
//------------------------------------------
?>  
<script language="javascript" type="text/javascript">
	$(document).ready(function() {	
		$('#empselect').change(function(){
			dac_redirectPaginacion();
		});	
				
		$('#tiposelect').change(function(){
			dac_redirectPaginacion();
		});	
		
		function dac_redirectPaginacion(){	
			var empresa = $('#empselect').val();
			var tipo 	= $('#tiposelect').val();		
			var redirect = '?empresa='+empresa+'&tipo='+tipo;
			document.location.href=redirect;
		}
	});	
	
	function dac_mostrarFiltro(tipo, filtro){
		$.ajax({
			type 	: 	'POST',
			cache	:	false,
			url 	: 	'/pedidos/cuentas/logica/ajax/getFiltroCuentas.php',				
			data	:	{	
						tipo 	: tipo,
						filtro 	: filtro
					},
			beforeSend	: function () {
				$('#box_error').css({'display':'none'});
				$('#box_cargando').css({'display':'block'});
				$("#msg_cargando").html('<img class="icon-loading"/>Cargando... espere por favor!');
			},
			success : 	function (resultado) {
				if (resultado){
					var tabla = resultado;											
					document.getElementById('tablaFiltroCuentas').innerHTML = tabla;
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
      
<script language="javascript" type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Cuentas'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                       // color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Prospecto',
                y: <?php echo $cantPS; ?>, 
				sliced: true,
                selected: true,
            }, {
                name: 'Cliente',
                y: <?php echo $cantC; ?> 
			}, {
				name: 'Cliente Inactivo',
                y: <?php echo $cantCI; ?>      
            }, {
                name: 'Transfer',
                y: <?php echo $cantT; ?> 
            }]
        }]
    });
});
</script>



