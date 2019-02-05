<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!= "M"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$_drogid	=	empty($_REQUEST['drogid'])	?	0	:	$_REQUEST['drogid'];
if(empty($_REQUEST['fecha_abm'])){ 
	$_mes	=	date("m"); $_anio	=	date("Y");
} else { 
	list($_mes, $_anio) = explode('-', str_replace('/', '-', $_REQUEST['fecha_abm']));
}

//Consultar el estado del primer registro de la fecha y droguería, para saber si está activo o inactivo
$_ABMestado	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TL');
if ($_ABMestado) {
	$_contador_filas = count($_ABMestado);
	foreach ($_ABMestado as $k => $_ABMest){
		$_ABMest	=	$_ABMestado[$k];
		$_activo 	=	$_ABMest['abmactivo'];
		break;
	}
} else {
	$_contador_filas = 2;
	$_activo = 0;
}

if($_drogid){
	$_guardar		=	sprintf( "<img id=\"guardar_abm\" title=\"Guardar Cambios\" src=\"/pedidos/images/icons/icono-save50.png\" border=\"0\" align=\"absmiddle\" />");
	//$_guardar2		=	sprintf( "<img id=\"guardar_abm2\" title=\"Guardar Cambios\" src=\"/pedidos/images/icons/icono-save50.png\" border=\"0\" align=\"absmiddle\" onclick=\"document.getElementById('guardar_abm').click();\"/>");
	$_boton_exportar=	sprintf( "<a href=\"logica/exportar.abm.php?mes=%d&anio=%d&drogid=%d\" title=\"exportar abm\"><img src=\"/pedidos/images/icons/export_excel.png\" border=\"0\" align=\"absmiddle\"/></a> ", $_mes, $_anio, $_drogid);		
	$_boton_copy	= 	sprintf( "<img src=\"/pedidos/images/icons/ico-copy.png\" border=\"0\" align=\"absmiddle\" title=\"duplicar abm\" onclick=\"javascript:dac_Duplicarabm(%d, %d, %d, 0)\"/>", $_mes, $_anio, $_drogid);	
	$_boton_copyAll	= 	sprintf( "<img src=\"/pedidos/images/icons/icono-copytoall.png\" border=\"0\" align=\"absmiddle\" title=\"duplicar abm para todos\" onclick=\"javascript:dac_Duplicarabm(%d, %d, %d, 1)\"/>", $_mes, $_anio, $_drogid);
} else {
	$_guardar = $_boton_exportar = $_boton_copy = $_boton_copyAll = "";
}
?>

<div class="box_body"> 
	<form id="fm_abm_edit" name="fm_abm_edit" class="fm_edit2" method="POST" >
		<fieldset>
            <div class="bloque_3">     
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
            
            <div class="bloque_1">  
				<label for="drogid">Droguer&iacute;a: </label> <?php
				$_droguerias	= DataManager::getDrogueria('');
				if (count($_droguerias)) { ?>
					<select name='drogid' id='drogid' onChange='javascript:dac_VerMesAnioDrog()'>
						<option value="0" selected>Seleccione Droguer&iacute;a...</option><?php
						foreach ($_droguerias as $k => $_drogueria) {
							$_Did		= $_drogueria["drogtid"];
							$_Didcliente= $_drogueria["drogtcliid"];
							$_Didempresa= $_drogueria["drogtidemp"];
							
							$_Dnombre	= DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_Didcliente, $_Didempresa);		
							$idLoc		= DataManager::getCuenta('ctaidloc', 'ctaidcuenta', $_Didcliente, $_Didempresa);
							$_Dlocalidad	= DataManager::getLocalidad('locnombre', $idLoc);	

							if($_drogid == $_Didcliente){ ?>			
								<option value="<?php echo $_Didcliente; ?>" selected><?php echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; ?></option> <?php	} else { ?>
								<option value="<?php echo $_Didcliente; ?>"><?php echo $_Didcliente." | ".$_Dnombre." | ".$_Dlocalidad; ?></option> <?php		}
						} ?>
					</select> <?php
				} ?>
			</div>
			
			<div class="bloque_2">
				<label for="fecha_abm" >Fecha ABM: </label>
				<?php echo listar_mes_anio('fecha_abm', $_anio, $_mes, 'javascript:dac_VerMesAnioDrog()', ''); ?>
				
				<input hidden id="vermesanio" name="vermesanio" type="text" value="<?php echo $_mes." - ".$_anio; ?>"/>
				<input hidden id="vermesaniodrog" name="vermesaniodrog" type="submit"/>
			</div>
			
			<div class="bloque_3">
				<?php echo $_guardar; ?> 
				<?php //echo $_boton_print; ?> 
				<?php echo $_boton_exportar; ?> 
				<?php echo $_boton_copy; ?> 
				<?php echo $_boton_copyAll; ?> 
			</div>
			
			<input id="mes_abm" name="mes_abm" type="text" value="<?php echo $_mes; ?>" hidden/>
			<input id="anio_abm" name="anio_abm" type="text" value="<?php echo $_anio; ?>" hidden/>
			<input id="drogid_abm" name="drogid_abm" type="text" value="<?php echo $_drogid; ?>" hidden/>  

			<div style="width:550px; height:350px; overflow-x:auto;">
				<table name="tabla_abm" class="tabla_abm" cellpadding="0" cellspacing="0" border="0">
					<thead>
						<?php if(!empty($_drogid)){ ?>
						<tr>  <!-- Títulos de las Columnas -->
							<th colspan="2" align="center">Art&iacute;culo</th>
							<th align="center" >% Desc.</th>
							<th align="center" >Plazo</th>
							<th align="center" >Dif. de Comp. p/ Droguer&iacute;a</th>
							<th></th>                
						</tr>
						<?php } ?> 
					</thead>

					<tbody id="lista">
						<?php 
						//******************************************//  
						//Consulta ABM del mes actual y su drogueria//
						//******************************************//				
						$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TL');
						if ($_abms) {
							foreach ($_abms as $k => $_abm){
								$_abm			=	$_abms[$k];
								$_abmID			=	$_abm['abmid'];
								$_abmDrog		=	$_abm['abmdrogid'];
								$_abmArtid		= 	$_abm['abmartid'];
								$_artnombre		= 	DataManager::getArticulo('artnombre', $_abmArtid, 1, 1);
								$_abmDesc		= 	$_abm['abmdesc'];
								$_abmPlazo		= 	$_abm['abmcondpago'];
								$_abmDifComp	= 	$_abm['abmdifcomp']; //Diferencia de Compensación									
								((($k % 2) != 0)? $clase="par" : $clase="impar"); ?>

								<tr id="lista_abm<?php echo $k;?>" class="<?php echo $clase;?>">  
									<td>
										<input id="idabm" name="idabm[]" type="text" value="<?php echo $_abmID;?>" hidden />
										<input id="art" name="art[]" type="text" value="<?php echo $_abmArtid;?>" hidden/>
										<?php echo $_abmArtid." - ".$_artnombre; ?></td>
									<td>
										<input id="desc" name="desc[]" type="text" maxlength="2" value="<?php echo $_abmDesc; ?>" align="center" placeholder="%" style="width:80px;"/></td>  

									<td> <?php
										$_plazos	= DataManager::getCondicionesDePagoTransfer(0,0,1);
										if (count($_plazos)) { ?>
											<select id='plazoid' name='plazoid[]'> <?php
												foreach ($_plazos as $j => $_plazo) {
													$_plazo			=	$_plazos[$j];
													$_condid		=	$_plazo["condid"];
													$_condcodigo	= 	$_plazo["condcodigo"];
													$_condnombre	= 	$_plazo["condnombre"];											
													$_conddias		= 	$_plazo["conddias"];

													if($_condid == $_abmPlazo){ ?>			
														<option value="<?php echo $_condid; ?>" selected><?php echo $_condnombre; ?></option> <?php	
													} else { ?>
														<option value="<?php echo $_condid; ?>"><?php echo $_condnombre; ?></option> <?php		
													}
												} ?>
											</select> <?php
										} ?>
									</td>

									<td>
										<input id="difcompens" name="difcompens[]" type="text" size="10px" maxlength="5" value="<?php echo $_abmDifComp; ?>" align="center" placeholder="%" onblur="javascript:ControlComa(this.id, this.value);"/></td>                          
									<td class="eliminar">
										<img src="/pedidos/images/icons/icono-eliminar-claro.png" border="0" align="absmiddle" onclick="javascript:dac_eliminarAmb(<?php echo $k;?>)" />
									</td>
								</tr> <?php 
							} //FIN del FOR 
						} else { ?>
						<?php			
						}  ?>
					</tbody>
				</table>
            </div> 
    	</fieldset>
	</form> 
</div> <!-- end cuerpo --> 

<div class="box_seccion">
	<div class="barra">
       	<div class="buscadorizq">
       		<h1>Art&iacute;culos</h1>
       	</div>
        <div class="buscadorder">
          	<input id="txtBuscar" type="search" autofocus placeholder="Buscar"/>
            <input id="txtBuscarEn" type="text" value="tblTablaArt" hidden/>
        </div>
        <hr>
	</div> <!-- Fin barra -->
            
	<div class="lista">
       	<table id="tblTablaArt" align="center" border="0">
			<thead>
				<tr align="center">
					<th>Id</th>
					<th>Nombre</th>
					<th>EAN</th>
				</tr>
			</thead>
            <tbody> <?php
				$_articulos	= DataManager::getArticulos(NULL, NULL, FALSE, NULL, 1, 1);
				if (count($_articulos)) {								 
					foreach ($_articulos as $k => $_articulo) {																			
						$_articulo 		= $_articulos[$k];
						$_idart			= $_articulo['artidart'];	
						$_nombre		= $_articulo['artnombre'];	
						$_artean		= $_articulo['artcodbarra'];												
						((($k % 2) == 0)? $clase="par" : $clase="impar")?>      
                        <tr class="<?php echo $clase;?>" onClick="javascript:dac_CargarArticuloAbm('<?php echo $_idart; ?>', '<?php echo $_nombre; ?>')">
							<td><?php echo $_idart; ?></td>
							<td><?php echo $_nombre; ?></td>
							<td><?php echo $_artean; ?></td>
						</tr> <?php	
					}
				} else { ?>
                	<tr>
						<td colspan="3"><?php echo "ACTUALMENTE NO HAY ART&Iacute;CULOS ACTIVOS. Gracias."; ?></td>	
					</tr> <?php 
				} ?>
	        </tbody>
		</table>
	</div> <!-- Fin listar -->	
</div> <!-- FIN boxdatos --> 

<script type="text/javascript">
	// función para crear un nuevo div de artículo
	var nextinput = <?php echo $_contador_filas-1;?>;
	
	function dac_CargarArticuloAbm(idart, nombre){
		nextinput++;
		(((nextinput % 2) != 0)	? clase="par" : clase="impar");
		campo = '<tr id="lista_abm'+nextinput+'" class='+clase+'><td><input id="idabm" name="idabm[]" type="text" hidden /></td><td><input id="art" name="art[]" type="text" value="'+idart+'" hidden/>'+idart+' - '+nombre+'</td><td><input id="desc" name="desc[]" type="text" size="10px" maxlength="2" align="center" placeholder="%"/></td>';
		campo +='<td><select id="plazoid" name="plazoid[]" style="width:100px; background-color:transparent;">'
		campo +='<option value="" selected>Seleccione plazo...</option>';
		
		<?php
		$_plazos	= DataManager::getCondicionesDePagoTransfer();
		if (count($_plazos)) { 
			foreach ($_plazos as $k => $_plazo) {
				$_plazo		=	$_plazos[$k];
				$_condid		=	$_plazo["condid"];
				$_condcodigo	= 	$_plazo["condcodigo"];
				$_condnombre	= 	$_plazo["condnombre"];?>			
				
				campo +=	'<option value="<?php echo $_condid; ?>"><?php echo $_condnombre; ?></option>'; <?php	
				
			}
		} ?>
		
		
		campo +='</select></td>';
		
		campo +='<td><input id="difcompens'+nextinput+'" name="difcompens[]" type="text" size="10px" maxlength="5" placeholder="%" onblur="javascript:ControlComa(this.id, this.value);"/></td><td class="eliminar"><img src="/pedidos/images/icons/icono-eliminar-claro.png" border="0" align="absmiddle" onClick="dac_eliminarAmb('+nextinput+')"/></td></tr>';
		
		$("#lista").append(campo);
	}		
		
	// función del botón eliminar para quitar un div de artículos
	function dac_eliminarAmb(id){
		elemento=document.getElementById('lista_abm'+id);
		elemento.parentNode.removeChild(elemento);
	}
</script>

<script type="text/javascript" src="logica/jquery/jquery.processabm.js"></script>

<script type="text/javascript">
	function dac_VerMesAnioDrog(){	document.getElementById("vermesaniodrog").click();}
</script>

<script type="text/javascript">
	function dac_Duplicarabm(mes, anio, drogid, toAll){
		if(confirm("Recuerde que se borrará cualquier dato en la fecha donde duplique el ABM. ¿Desea continuar?")){
			mes_sig	= prompt("Ingrese el mes a donde desea duplicar el ABM. (de 1 a 12)");
			if(mes_sig < 1 || mes_sig > 12){ 
				alert("El mes indicado es incorrecto. El duplicado no se realizará. Vuelva a Intentarlo.");
			} else {
				anio_sig= prompt("Ingrese el año a donde desea duplicar el ABM. (ejemplo 2017)");
				if(anio_sig < 2015 || anio_sig > 2025){ 
					alert("El año indicado es incorrecto. El duplicado no se realizará. Vuelva a Intentarlo.");
				}else{
					if(mes == mes_sig && anio == anio_sig){
						alert("Ha intentado duplicar un ABM en la misma fecha. Vuelva a Intentarlo.");
					} else {
						if(!(mes_sig < 1 || mes_sig > 12) && !(anio_sig < 2015 || anio_sig > 2025)){ 				
							$.ajax({
								type : 'POST',
								url : 'logica/ajax/duplicar.abm.php',					
								data:{	drogid	:	drogid,
										mes		:	mes,
										mes_sig	:	mes_sig,
										anio	:	anio,
										toAll	:	toAll,
										anio_sig:	anio_sig
								},
								beforeSend: function () {
									$('#box_confirmacion').css({'display':'none'});
									$('#box_error').css({'display':'none'});
									$('#box_cargando').css({'display':'block'});					
									$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
								},
								success : function (resultado) { 								
									if (resultado){
										$('#box_cargando').css({'display':'none'});
										if (resultado === "1"){
											$('#box_confirmacion2').css({'display':'block'});
											$("#msg_confirmacion2").html("El ABM fue duplicada.");
										} else {
											$('#box_error').css({'display':'block'});
											$("#msg_error").html(resultado);
										}						
									}															
								},
								error: function () {
									$('#box_cargando2').css({'display':'none'});
									$('#box_error2').css({'display':'block'});
									$("#msg_error2").html("Error en el proceso de duplicado.");
								}								
							});
						}
					}
				}
			}
		}
	}
</script>