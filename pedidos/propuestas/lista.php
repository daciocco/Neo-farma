<?php 
$_LPP	=	5000;
$_pag	=	1;

?>

<?php
if ($_SESSION["_usrrol"] == "A"  || $_SESSION["_usrid"] == "17" || $_SESSION["_usrrol"] == "M" || $_SESSION["_usrrol"] == "V" || $_SESSION["_usrrol"] == "G"){
?>
    <script language="JavaScript"  src="/pedidos/pedidos/logica/jquery/jquery.aprobar.js" type="text/javascript"></script>
	<div class="box_body2" align="center"> <!-- datos --> 
		<div class="barra">
			<div class="buscadorizq">
				<h1>Propuestas</h1>              	
			</div>  
			<hr>      
		</div> <!-- Fin barra -->
		
		<div class="lista">        
			<table class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed;">
				<thead>
					<tr>
						<td scope="col" height="18">Fecha</td>
                        <td scope="col" >Prop</td>
						<td scope="col" >Cuenta</td>
                        <td scope="col" >Nombre</td>
                        <td scope="col" >Vendedor</td>
						<td scope="col" >Estado</td>
                        <td colspan="2" scope="colgroup" align="center">Acciones</td>
					</tr>
				</thead>			
				<?php				
				$idUsr = ($_SESSION["_usrrol"] == "V") ? $_SESSION["_usrid"] : NULL;
	
				$propPendientes	= DataManager::getPropuestas(NULL, 1, $idUsr); 
				if ($propPendientes){
					foreach ($propPendientes as $k => $propP) {
						$idProp		= 	$propP["propid"];
						$idUsr		= 	$propP["propusr"];
                        $nombreUsr	= 	DataManager::getUsuario('unombre', $idUsr); 
						$fecha 		=	$propP["propfecha"];
						$idCuenta	= 	$propP["propidcuenta"];
						$propEmpresa= 	$propP["propidempresa"];
						$estado		= 	$propP["propestado"];
						$estadoName	=	DataManager::getEstado('penombre', 'peid', $estado);
						
						switch ($estado){
							case 0:
								$status	= "<img src=\"/pedidos/images/icons/icono-cerrado.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
								break;
							case 1:
								$status	= "<img src=\"/pedidos/images/icons/icono-pendiente.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
								break;
							case 2:
								$status	= "<img src=\"/pedidos/images/icons/icono-aprobado.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
								break;
							case 3:
								$status	= "<img src=\"/pedidos/images/icons/icono-rechazado.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
								break;
							default:
								$status	= $estadoName; 
								break;
						}
						
						$nombreCuenta= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, $propEmpresa);
								
						$clase	=	((($k % 2) == 0)? "par" : "impar");	
						
						$_detalle	=	sprintf("<a href=\"../detalle.propuesta.php?propuesta=%d\" target=\"_blank\" title=\"Detalle\">%s</a>", $idProp, "<img src=\"/pedidos/images/icons/icono-lista.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-lista-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-lista.png';\" border=\"0\" align=\"absmiddle\" />");
						
						$_editar	= sprintf( "<a href=\"../editar.php?propuesta=%d\" target=\"_blank\" title=\"Editar\">%s</a>", $idProp, "<img src=\"/pedidos/images/icons/icono-editar.png\" onmouseover=\"this.src='/pedidos/images/icons/icono-editar-hover.png';\" onmouseout=\"this.src='/pedidos/images/icons/icono-editar.png';\" border=\"0\" align=\"absmiddle\" />");
												
						echo sprintf("<tr class=\"%s\">", $clase);
						echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $fecha, $idProp, $idCuenta, $nombreCuenta, $nombreUsr, $status, $_detalle, $_editar);
						echo sprintf("</tr>");
					}
				} else {
					?>
					<tr>
						<td scope="colgroup" colspan="8" height="25" align="center">No hay registros.</td>
					</tr>
					<?php
				}
				?>
			</table>
		</div> <!-- Fin lista -->	
	</div> <!-- Fin datos --> 
	
	<div class="box_body2" align="center" > <!-- datos --> 
		<div class="barra">
			<div class="buscadorizq">
				<h1>Propuestas Finalizadas</h1>     	
			</div>   
			<hr>
		</div> <!-- Fin Barra -->
		
		<div class="lista">        
			<table class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td scope="col" height="18">Fecha</td>
                        <td scope="col" >Prop</td>
						<td scope="col" >Cuenta</td>
                        <td scope="col" >Nombre</td>
                        <td scope="col" >Vendedor</td>
						<td scope="col" >Estado</td>
					</tr>
				</thead>			
				<?php				
				$idUsr = ($_SESSION["_usrrol"] == "V") ? $_SESSION["_usrid"] : NULL;
	
				
				$dateTo 	= new DateTime('now');
				$dateFrom 	= new DateTime('now');
				$dateFrom ->modify('-6 month');
	
				$propPendientes	= DataManager::getPropuestas(NULL, 0, NULL, $dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d')); 
				if ($propPendientes){
					foreach ($propPendientes as $k => $propP) {	
						$idProp		= 	$propP["propid"];
						$idUsr		= 	$propP["propusr"];
                        $nombreUsr	= 	DataManager::getUsuario('unombre', $idUsr);
						$fecha 		=	$propP["propfecha"];
						
						$fechaDesde	= 	new DateTime();
						$fechaDesde->modify('-1 year');
						
						if($fecha > $fechaDesde->format('Y-m-d H:i:s')) {
						
							$idCuenta	= 	$propP["propidcuenta"];
							$estado		= 	(empty($propP["propestado"]) && $propP["propestado"] != 0) ? "" : $propP["propestado"];
							$estadoName	=	DataManager::getEstado('penombre', 'peid', $estado);
							$nombreCuenta= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, NULL);

							switch ($estado){
								case 0:
									$status	= "<img src=\"/pedidos/images/icons/icono-cerrado.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
									break;
								case 1:
									$status	= "<img src=\"/pedidos/images/icons/icono-pendiente.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
									break;
								case 2:
									$status	= "<img src=\"/pedidos/images/icons/icono-aprobado.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
									break;
								case 3:
									$status	= "<img src=\"/pedidos/images/icons/icono-rechazado.png\" border=\"0\" align=\"absmiddle\" title=".$estadoName." \"/>";
									break;
								default:
									$status	= $estadoName; 
									break;
							}

							$clase	=	((($k % 2) == 0)? "par" : "impar");	

							echo sprintf("<tr class=\"%s\" style=\"cursor:pointer;\" onclick=\"window.open('../detalle.propuesta.php?propuesta=%d')\" title=\"Detalle\">", $clase, $idProp);
							echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $fecha, $idProp, $idCuenta, $nombreCuenta, $nombreUsr, $status);
							echo sprintf("</tr>");
						}
					}
				} else { ?>
					<tr>
						<td scope="colgroup" colspan="6" height="25" align="center">No hay registros.</td>
					</tr>
					<?php
				} ?>
			</table>
		</div> <!-- Fin lista -->	
	</div> <!-- Fin datos -->		
<?php } ?>

<?php
/*
if ($_SESSION["_usrrol"] == "A" || $_SESSION["_usrrol"] == "G"){ ?>	
	<div class="box_body2" align="center"> <!-- datos --> 
		<div class="barra">
			<div class="buscadorizq">
				<h1>Negociaciones Aprobadas</h1>     	
			</div>
			<hr>
		</div> <!-- Fin Barra -->
		
		<div class="lista">        
			<table id="tblNegociacionesAprobadas" class="datatab" width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td scope="col" width="15%" height="18">Fecha</td>
						<td scope="col" width="15%">Pedido</td>
						<td scope="col" width="30%">Cliente</td>
                        <td scope="col" width="30%">Vendedor</td>
					</tr>
				</thead>			
				<?php
				$_pedidos_facturados	=	DataManager::getPedidos(NULL, 0, NULL, NULL, 1, 0);
				if ($_pedidos_facturados){
					$_nro	=	0;
					$_fila	=	0;
					foreach ($_pedidos_facturados as $k => $_pedidoFact) {
						$_pidusr	= 	$_pedidoFact["pidusr"];		
                        $_nombreusr	= 	DataManager::getUsuario('unombre', $_pidusr); 						 
						$fecha 		=	$_pedidoFact["pfechapedido"];
						$_nropedido	= 	$_pedidoFact["pidpedido"];
						$_idemp		= 	$_pedidoFact["pidemp"]; 
						$_cliente	= 	$_pedidoFact["pidcliente"];	
						$_nombre	= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $_cliente, $_idemp);
						
						if($_nro != $_nropedido) {	
							$_fila	=	$_fila + 1;		
							echo sprintf("<tr class=\"%s\" onclick=\"window.open('detalle_pedido.php?nropedido=%s')\" style=\"cursor:pointer;\" target=\"_blank\" title=\"Detalle\">", ((($_fila % 2) == 0)? "par" : "impar"), $_nropedido);
							echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td>%s</td>", $fecha, $_nropedido, $_nombre, $_nombreusr);
							echo sprintf("</tr>");
						}				
						$_nro = $_nropedido;
					}
				} else {
					?>
					<tr>
						<td scope="colgroup" colspan="5" height="25" align="center">No hay negociaciones aprobadas</td>
					</tr>
					<?php
				}
				?>
			</table>
		</div> <!-- Fin lista -->	
	</div> <!-- Fin datos -->		
<?php }  */?>

<hr>