<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$_fecha		=	empty($_REQUEST['fecha'])	?	date('d-m-Y')	:	$_REQUEST['fecha'];

if($_fecha){
	$_guardar		=	sprintf( "<img id=\"guardar_pagos\" title=\"Guardar Pagos\" src=\"/pedidos/images/icons/icono-save50.png\" border=\"0\" align=\"absmiddle\" />");
	$_importarXLS	=	sprintf( "<img id=\"importar\" src=\"/pedidos/images/icons/icono-importxls.png\" border=\"0\" align=\"absmiddle\"  title=\"Importar Plazos Facturas\"/>");
	$_exportarXLS	= sprintf( "<a href=\"logica/exportar.fechasemanal.php?fecha=%s&backURL=%s\" title=\"Exportar fecha semanal de pago\">%s</a>", $_fecha, $_SERVER['PHP_SELF'], "<img src=\"/pedidos/images/icons/export_excel.png\" border=\"0\" align=\"absmiddle\"/>");
} ?>

<script type="text/javascript" src="logica/jquery/jqueryHeader.js"></script>

<div class="box_down">
	<div id="muestra_fechaspago">     
		<table id="tabla_fechaspago" name="tabla_fechaspago" class="tabla_fechaspago" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th colspan="3" align="left">  
						<form id="fm_pagos" method="post" enctype="multipart/form-data"> 														
							<label for="f_fecha" >Fecha de Pago: </label>
							<input id="f_fecha" name="fecha" type="text" value="<?php echo $_fecha;?>" size="16" style="font-weight:bold;" readonly/>
					</th>
					<th colspan="6" align="center">
							<fieldset id='box_cargando' class="msg_informacion">                  
								<div id="msg_cargando" align="center"></div>      
							</fieldset>  
							<fieldset id='box_error' class="msg_error">
								<legend>&iexcl;ERROR!</legend>                     
								<div id="msg_error" align="center"></div>
							</fieldset>

							<fieldset id='box_confirmacion' class="msg_confirmacion">
								<div id="msg_confirmacion" align="center"></div>      
							</fieldset>
							<div id="inputfile" class="inputfile">
								<input type="file" name="file" id="file">
							</div>
							<?php echo $_importarXLS; ?> <?php echo $_guardar; ?><?php echo $_exportarXLS; ?>
						</form>     
					</th>
					<th colspan="3"></th>
				</tr>

				<tr height="60px;">  <!-- Títulos de las Columnas -->
					<th align="center" >Emp</th>   
					<th align="center">C&oacute;digo</th>
					<th align="center">Proveedor</th>
					<th align="center">Plazo</th>
					<th align="center">Vencimiento</th> 
					<th align="center">Tipo</th> 
					<th align="center">Nro</th>    
					<th align="center">Fecha Cbte</th>
					<th align="center">Saldo</th>
					<th align="center">Observaci&oacute;n</th>   
					<th align="center"></th> 
					<th align="center"></th>             
				</tr>
			</thead>

			<tbody id="lista_fechaspago">
				<form id="fm_fechaspago_edit" name="fm_fechaspago_edit" method="POST" enctype="multipart/form-data">  
					<input id="fecha" name="fecha" type="text" value="<?php echo $_fecha; ?>" hidden/> 				
					<?php 
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

							$_saldo_total	+=	$_saldo;

							echo "<script>";
							echo "javascript:dac_CargarDatosPagos('', '".$_idfact."', '".$_idempresa."','".$_idprov."','".$_nombre."','".$_plazo."','".$_fechavto."','".$_tipo."' ,'".$_factnro."', '".$_fechacbte."' , '".$_saldo."', '".$_observacion."', '".$_activa."')";		
							echo "</script>";
						}				
					} ?>
				</form>                   
			</tbody>

			<tfoot>
				<tr>
					<th colspan="7" height="30px" style="border:none; font-weight:bold;"></th>
					<th colspan="1" height="30px" style="border:none; font-weight:bold;">Total</th>
					<th colspan="1" height="30px" style="border:none; font-weight:bold;" align="right"><div id="saldo_total"><?php echo $_saldo_total; ?></div></th>
					<th colspan="1" height="30px" style="border:none; font-weight:bold;"></th>
					<th colspan="1" height="30px" style="border:none; font-weight:bold;"></th>
					<th colspan="1" height="30px" style="border:none; font-weight:bold;"></th>
				</tr>
			</tfoot>
		</table>  
		<div hidden="hidden"><button id="btnExport" hidden="hidden"></button></div>                  
	</div> <!-- FIN muestra_liquidacion -->
</div>

<div class="box_body"> </div>

<div class="box_seccion"> <!-- datos --> 
    <div class="barra">
        <div class="buscadorizq">
            <h1>Facturas</h1>             	
        </div>
        <div class="buscadorder">
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
                  $_facturas	=	DataManager::getFacturasProveedor(NULL, 0, NULL);
                  if($_facturas){
                      foreach ($_facturas as $k => $_fact) {
						  $_idfact		= 	$_fact['factid'];
                          $_idempresa	= 	$_fact['factidemp'];
						  //nombre de empresa
						  $_empresa		= 	DataManager::getEmpresa('empnombre', $_idempresa);	
						  
                          $_idprov		= 	$_fact['factidprov'];
                          //saco el nombre del proveedor
                          $_proveedor	= 	DataManager::getProveedor('providprov', $_idprov, $_idempresa);	
						  						  
                          $_nombre		= 	isset($_proveedor[0]['provnombre']) ? $_proveedor[0]['provnombre'] : 'Proveedor desconocido';	
                          $_plazo		= 	$_fact['factplazo'];
                          $_tipo		= 	$_fact['facttipo'];
                          $_factnro		= 	$_fact['factnumero'];
                          $_fechacbte	= 	dac_invertirFecha($_fact['factfechacbte']);
                          $_fechavto	= 	dac_invertirFecha($_fact['factfechavto']);
						  $_observacion	= 	$_fact['factobservacion'];
                          $_saldo		= 	$_fact['factsaldo'];
                          $_activa		= 	$_fact['factactiva'];
						                            
                          ((($k % 2) == 0)? $clase="par" : $clase="impar"); ?>
                          
                          <tr id="listafact<?php echo $k;?>" class="<?php echo $clase;?>" onclick="javascript:dac_ControlProveedor('<?php echo $_idempresa;?>', '<?php echo $_idprov;?>'); dac_CargarDatosPagos('<?php echo $k;?>', '<?php echo $_idfact;?>', '<?php echo $_idempresa;?>', '<?php echo $_idprov;?>', '<?php echo $_nombre;?>', '<?php echo $_plazo;?>', '<?php echo $_fechavto;?>', '<?php echo $_tipo;?>', '<?php echo $_factnro;?>', '<?php echo $_fechacbte;?>', '<?php echo $_saldo;?>', '<?php echo $_observacion;?>', '<?php echo $_activa;?>')" style="cursor: pointer;">
                          	  <td><?php echo substr($_empresa, 0, 3); ?></td>
                              <td><?php echo $_idprov; ?></td>
                              <td ><?php echo $_nombre; ?></td>
                              <td ><?php echo $_factnro; ?></td>
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

<script type="text/javascript" src="logica/jquery/jqueryFooter.js"></script>
