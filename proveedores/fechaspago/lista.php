<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$fecha	= empty($_REQUEST['fecha'])	?	date('d-m-Y')	:	$_REQUEST['fecha'];

if($fecha){
	$_guardar		=	sprintf( "<a title=\"Guardar Pagos\" ><img id=\"guardar_pagos\" class=\"icon-save\"/></a>");
	$_importarXLS	=	sprintf( "<a title=\"Importar Plazos Facturas\"><img id=\"importar\" class=\"icon-xls-import\"/></a>");
	$_exportarXLS	= sprintf( "<a href=\"logica/exportar.fechasemanal.php?fecha=%s\" title=\"Exportar fecha semanal de pago\">%s</a>", $fecha, "<img class=\"icon-xls-export\"/>");	
	$exportHistorial = sprintf( "<a id=\"btnExporHistorial\" title=\"Exportar historial\">%s</a>", "<img class=\"icon-xls-export\"/>");
	
}
?>

<script type="text/javascript" src="logica/jquery/jqueryHeader.js"></script>

<div class="box_body">
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
	
	<div class="bloque_7">
		<label>Fecha de Pago </label>
		<input id="f_fecha" name="fecha" type="text" value="<?php echo $fecha;?>" readonly/>
	</div>
	<div class="bloque_7">
		<input type="file" name="file" id="file">
	</div>
	
	<div class="bloque_9">
		<?php echo $_importarXLS; ?> 
	</div>
	<div class="bloque_9">
		<?php echo $_guardar; ?>
	</div>
	<div class="bloque_9">
		<?php echo $_exportarXLS; ?>
	</div>
	<hr>
	
	<div class="bloque_1">
		<div class="bloque_7">
			<label>Exportar Desde</label>
			<input id="fechaDesde" type="text" readonly/>
		</div>
		<div class="bloque_7">
			<label>Hasta </label>
			<input id="fechaHasta" type="text" readonly/>
		</div>
		<div class="bloque_9">
			<br>
			<?php echo $exportHistorial; ?>
		</div>
	</div>
</div>

<div class="box_seccion"> <!-- datos --> 
    <div class="barra">
        <div class="bloque_5">
            <h1>Facturas</h1>             	
        </div>
        <div class="bloque_5">
        	<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblTablaFacts" hidden/>
        </div>
        <hr>
    </div> <!-- Fin barra -->

    <div class="lista">
        <table id="tblTablaFacts" border="0" width="100%" align="center">
            <thead>
                <tr align="left">
                	<th>Empresa</th>
                    <th>C&oacute;digo</th>
                    <th >Proveedor</th>
                    <th >Nro Factura</th>
                </tr>
            </thead>
            
            <tbody id="bodyFactList">	
                  <?php
                  $facturas	=	DataManager::getFacturasProveedor(NULL, 0, NULL);
                  if($facturas){
                      foreach ($facturas as $k => $fact) {
						  $_idfact		= 	$fact['factid'];
                          $_idempresa	= 	$fact['factidemp'];
						  //nombre de empresa
						  $_empresa		= 	DataManager::getEmpresa('empnombre', $_idempresa);	
                          $_idprov		= 	$fact['factidprov'];
                          //saco el nombre del proveedor
                          $_proveedor	= 	DataManager::getProveedor('providprov', $_idprov, $_idempresa);
                          $_nombre		= 	isset($_proveedor[0]['provnombre']) ? $_proveedor[0]['provnombre'] : 'Proveedor desconocido';	
                          $_plazo		= 	$fact['factplazo'];
                          $_tipo		= 	$fact['facttipo'];
                          $factnro		= 	$fact['factnumero'];
                          $fechacbte	= 	dac_invertirFecha($fact['factfechacbte']);
                          $fechavto	= 	dac_invertirFecha($fact['factfechavto']);
						  $_observacion	= 	$fact['factobservacion'];
                          $_saldo		= 	$fact['factsaldo'];
                          $_activa		= 	$fact['factactiva'];
                          ((($k % 2) == 0)? $clase="par" : $clase="impar"); ?>
                          
                          <tr id="listafact<?php echo $k;?>" class="<?php echo $clase;?>" onclick="javascript:dac_ControlProveedor('<?php echo $_idempresa;?>', '<?php echo $_idprov;?>'); dac_CargarDatosPagos('<?php echo $k;?>', '<?php echo $_idfact;?>', '<?php echo $_idempresa;?>', '<?php echo $_idprov;?>', '<?php echo $_nombre;?>', '<?php echo $_plazo;?>', '<?php echo $fechavto;?>', '<?php echo $_tipo;?>', '<?php echo $factnro;?>', '<?php echo $fechacbte;?>', '<?php echo $_saldo;?>', '<?php echo $_observacion;?>', '<?php echo $_activa;?>')" style="cursor: pointer;">
                          	  <td><?php echo substr($_empresa, 0, 3); ?></td>
                              <td><?php echo $_idprov; ?></td>
                              <td ><?php echo $_nombre; ?></td>
                              <td ><?php echo $factnro; ?></td>
                          </tr>  <?php
                      }				
                  } else { ?>
                      <tr>
                          <td scope="colgroup" colspan="4" height="25" align="center">No hay facturas cargadas</td>
                      </tr> <?php
                  } ?>
            </tbody>
        </table>
	</div> <!-- Fin listar -->		       
</div> <!-- Fin boxdatosuper -->

<hr>
<div class="box_down">   
	<div class="bloque_10">Emp</div>   
	<div class="bloque_9">C&oacute;digo</div>
	<div class="bloque_7">Proveedor</div>
	<div class="bloque_10">Plazo</div>
	<div class="bloque_8">Vencimiento</div> 
	<div class="bloque_10">Tipo</div> 
	<div class="bloque_9">Nro</div>    
	<div class="bloque_8">Fecha Cbte</div>
	<div class="bloque_9">Saldo</div>
	<div class="bloque_8">Observaci&oacute;n</div>   
	<div class="bloque_10"></div> 
	<div class="bloque_10"></div>  
	<hr class="hr-line">  
	
	<form id="fm_fechaspago_edit" name="fm_fechaspago_edit" method="POST" enctype="multipart/form-data">  
		<input id="fecha" name="fecha" type="text" value="<?php echo $fecha; ?>" hidden> 
		<div id="lista_fechaspago">
			<?php 
			//hago consulta de fechas de pago en la fecha actual ordenada por proveedor				
			$saldoTotal	=	0;
			$facturas_pago	=	DataManager::getFacturasProveedor(NULL, 1, dac_invertirFecha($fecha));
			if($facturas_pago) {
				foreach ($facturas_pago as $k => $factPago) {
					$_idfact		= 	$factPago['factid'];
					$_idempresa		= 	$factPago['factidemp'];
					$_idprov		= 	$factPago['factidprov'];
					//Saco el nombre del proveedor
					$_proveedor	 	= 	DataManager::getProveedor('providprov', $_idprov, $_idempresa);
					$_nombre		= 	$_proveedor['0']['provnombre'];
					$_plazo			= 	$factPago['factplazo'];
					$_tipo			= 	$factPago['facttipo'];
					$factnro		= 	$factPago['factnumero'];
					$fechacbte		= 	dac_invertirFecha($factPago['factfechacbte']);
					$fechavto		= 	dac_invertirFecha($factPago['factfechavto']);
					$_saldo			= 	$factPago['factsaldo'];
					$_observacion	= 	$factPago['factobservacion'];
					$_activa		= 	$factPago['factactiva'];
					$saldoTotal	+=	$_saldo;
					echo "<script>";
					echo "javascript:dac_CargarDatosPagos('', '".$_idfact."', '".$_idempresa."','".$_idprov."','".$_nombre."','".$_plazo."','".$fechavto."','".$_tipo."' ,'".$factnro."', '".$fechacbte."' , '".$_saldo."', '".$_observacion."', '".$_activa."')";		
					echo "</script>";
				}				
			} ?>
		</div>
	</form>                   
		
	<div class="bloque_4"><strong>TOTAL $</strong></div>   
	<div class="bloque_7" id="saldo_total" style="float:right"><strong><?php echo $saldoTotal; ?></strong></div> 
	<div hidden="hidden"><button id="btnExport" hidden="hidden"></button></div>                  
</div>

<script type="text/javascript" src="logica/jquery/jqueryFooter.js"></script>
