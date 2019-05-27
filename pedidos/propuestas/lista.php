<?php 
$_LPP	=	5000;
$_pag	=	1;
?>

<?php
if ($_SESSION["_usrrol"] == "A"  || $_SESSION["_usrid"] == "17" || $_SESSION["_usrrol"] == "M" || $_SESSION["_usrrol"] == "V" || $_SESSION["_usrrol"] == "G"){
?>
<script language="JavaScript"  src="/pedidos/pedidos/logica/jquery/jquery.aprobar.js" type="text/javascript"></script>

<div class="box_down"> <!-- datos --> 
	<div class="barra">
		<div class="bloque_1">
			<h1>Propuestas</h1>              	
		</div>  
		<hr>      
	</div> <!-- Fin barra -->

	<div class="lista_super">        
		<table>
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
							$status	= "<a title='".$estadoName."' ><img class=\"icon-status-close\"/></a>";
							break;
						case 1:
							$status	= "<a title=".$estadoName."><img class=\"icon-status-pending\" /></a>";
							break;
						case 2:
							$status	= "<a title=".$estadoName."><img class=\"icon-status-active\" /></a>";
							break;
						case 3:
							$status	= "<a title=".$estadoName."><img class=\"icon-status-inactive\" /></a>";
							break;
						default:
							$status	= $estadoName; 
							break;
					}

					$nombreCuenta= 	DataManager::getCuenta('ctanombre', 'ctaidcuenta', $idCuenta, $propEmpresa);

					$clase	=	((($k % 2) == 0)? "par" : "impar");	

					$_detalle	=	sprintf("<a href=\"../detalle.propuesta.php?propuesta=%d\" title=\"Detalle\">%s</a>", $idProp, "<img class=\"icon-detail\"/>");						
					$_editar	= sprintf( "<a href=\"../editar.php?propuesta=%d\" target=\"_blank\" title=\"Editar\">%s</a>", $idProp, "<img class=\"icon-edit\"/>");

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

<div class="box_down"> <!-- datos --> 
	<div class="barra">
		<div class="bloque_5">
			<h1>Propuestas Finalizadas</h1>     	
		</div>   
		<hr>
	</div> <!-- Fin Barra -->

	<div class="lista">        
		<table>
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
								$status	= "<a title=".$estadoName."><img class=\"icon-status-close\" /></a>";
								break;
							case 1:
								$status	= "<a title=".$estadoName."><img class=\"icon-status-pending\" /></a>";
								break;
							case 2:
								$status	= "<a title=".$estadoName."><img class=\"icon-status-active\" /></a>";
								break;
							case 3:
								$status	= "<a title=".$estadoName."><img class=\"icon-status-inactive\" /></a>";
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
<hr>