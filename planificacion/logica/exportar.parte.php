<?php
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/funciones.comunes.php");

if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
  	header("Location: $_nextURL");
  	exit;
}

$_tipo_exp			=	(isset($_POST['tipo_exportado']))	? $_POST['tipo_exportado'] 		: NULL;
$_fecha_final 		= 	(isset($_POST['fecha_destino']))	? $_POST['fecha_destino'] 		: NULL;
$_fecha_inicio		= 	(isset($_POST['fecha_planificado']))? $_POST['fecha_planificado'] 	: NULL;

$titulo = ($_tipo_exp == "parte") ? "parte" : "Planificaciones";

//*----------------------
//Duplicado de registros
//*----------------------
$_fecha_i = dac_invertirFecha( $_fecha_inicio );
$_fecha_f = dac_invertirFecha( $_fecha_final );

//---------------------------------
//	Exportar datos de php a Excel
//---------------------------------
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
switch($_tipo_exp) {
	case 'parte':
		header("content-disposition: attachment;filename=Parte-diario_$_fecha_inicio-al-$_fecha_final.xls");
		break;
	case 'planificado':
		header("content-disposition: attachment;filename=Planificaciones_$_fecha_inicio-al-$_fecha_final.xls");
		break;
	case 'reporte':
		header("content-disposition: attachment;filename=Reporte_Partes_$_fecha_inicio-al-$_fecha_final.xls");
		break;
	default:
		header("content-disposition: attachment;filename=Error_$_fecha_inicio-al-$_fecha_final.xls");
		break;
} ?>
 
 <HTML LANG="es"><TITLE>::. Exportacion de Datos .::</TITLE>
 	<meta charset="UTF-8">
	 <body>
		<table border=1 align="center" cellpadding=1 cellspacing=1>
			<?php
			switch($_tipo_exp) {
				case 'parte': ?>
					<tr><td colspan="11" align="center" style="font-weight:bold; border:none;"> Detalle <?php echo $titulo; ?> </td></tr>
					<tr><td colspan="11" style="font-weight:bold; border:none;"> DESDE: <?php echo $_fecha_inicio; ?></td></tr>
					<tr><td colspan="11" style="font-weight:bold; border:none;"> HASTA: <?php echo $_fecha_final; ?></td></tr>
					<tr>
						<td>Fecha</td><td>Id</td><td>Nombre</td><td>Cliente</td><td>Nombre</td><td>Domicilio</td><td>Localidad</td><td>Acci&oacute;n</td><td>Trabaj&oacute; con...</td><td>Observaci&oacute;n</td><td>Env&Iacute;o <?php echo $titulo; ?></td>
					</tr>
					<?php
					$_detalle_partes	= DataManager::getDetalleParteExportar($_fecha_i, $_fecha_f);
					if (count($_detalle_partes)){
						foreach ($_detalle_partes as $k => $_partes) {
							$_partes 			= $_detalle_partes[$k];
							$_partefecha		= $_partes["partefecha"];
							$_parteidvend		= $_partes["parteidvendedor"];
							$_partenombrevend	= DataManager::getUsuario('unombre', $_partes["parteidvendedor"]);
							$_partecliente		= $_partes["parteidcliente"];								
							$_partenombre		= $_partes["parteclinombre"];
							$_partedir			= $_partes["parteclidireccion"];
							$_parteloc			= DataManager::getCuenta('ctalocalidad', 'ctaidcuenta', $_partecliente, 1);
							$_partetrabajo		= $_partes["partetrabajocon"];	
							$_parteobs			= $_partes["parteobservacion"];				
							$_parteenvioparte	= $_partes["partefechaenvio"];
							$_fecha_envio		= ($_parteenvioparte == "0000-00-00 00:00:00" || $_parteenvioparte == "2001-01-01 00:00:00" || $_parteenvioparte == "1900-01-01 00:00:00") ? 'SIN ENVIAR' : $_parteenvioparte;
							$_idcliente			= ($_partecliente) ? $_partecliente	: 'NUEVO';
							$_parteacciones		= "";
							if (!empty($_partes["parteaccion"])){
								$_accion			=	explode(',', $_partes["parteaccion"]);
								for($j=0; $j < count($_accion); $j++){
									$_partes_ac		=	DataManager::getAccion($_accion[$j]);
									if($_partes_ac){ 
										$_parteacciones	= 	$_partes_ac[0]["acnombre"]; ?>
										<tr>
											<td><?php echo $_partefecha;?></td>
											<td align="center"><?php echo $_parteidvend; ?></td>
											<td><?php echo $_partenombrevend;?></td>
											<td><?php echo $_idcliente ?></td>
											<td><?php echo $_partenombre; ?></td>
											<td><?php echo $_partedir; ?></td>
											<td><?php echo $_parteloc; ?></td>
											<td><?php echo $_parteacciones; ?></td>
											<td><?php echo $_partetrabajo; ?></td>
											<td><?php echo $_parteobs; ?></td>
											<td><?php echo $_fecha_envio; ?></td>
										</tr> <?php
									}
								}
							} else { ?>
								<tr>
									<td><?php echo $_partefecha;?></td>
									<td align="center"><?php echo $_parteidvend; ?></td>
									<td><?php echo $_partenombrevend;?></td>
									<td><?php echo $_idcliente ?></td>
									<td><?php echo $_partenombre; ?></td>
									<td><?php echo $_partedir; ?></td>
									<td><?php echo $_parteloc; ?></td>
									<td><?php echo $_parteacciones; ?></td>
									<td><?php echo $_partetrabajo; ?></td>
									<td><?php echo $_parteobs; ?></td>
									<td><?php echo $_fecha_envio; ?></td>
								</tr> <?php
							}
						}
					}
					break;
				case 'planificado': ?>
					<tr><td colspan="11" align="center" style="font-weight:bold; border:none;"> Detalle <?php echo $titulo; ?> </td></tr>
					<tr><td colspan="11" style="font-weight:bold; border:none;"> DESDE: <?php echo $_fecha_inicio; ?></td></tr>
					<tr><td colspan="11" style="font-weight:bold; border:none;"> HASTA: <?php echo $_fecha_final; ?></td></tr>
					<tr>
						<td>Fecha</td><td>Id</td><td>Nombre</td><td>Cliente</td><td>Nombre</td><td>Domicilio</td><td>Localidad</td><td>Acci&oacute;n</td><td>Trabaj&oacute; con...</td><td>Observaci&oacute;n</td><td>Env&Iacute;o <?php echo $titulo; ?></td>
					</tr>
					<?php
					$_detalle_planif	= DataManager::getDetallePlanifExportar($_fecha_i, $_fecha_f);
					if (count($_detalle_planif)){
						foreach ($_detalle_planif as $k => $_planificados){															
							$_planificados 		= $_detalle_planif[$k];
							$_planiffecha		= $_planificados["planiffecha"];
							$_planifidvend		= $_planificados["planifidvendedor"];
							$_planifnombrevend	= DataManager::getUsuario('unombre', $_planificados["planifidvendedor"]);
							$_planifcliente		= $_planificados["planifidcliente"];								
							$_planifnombre		= $_planificados["planifclinombre"];
							$_planifdir			= $_planificados["planifclidireccion"];	
							//FALTA HACERLO APTO PARA CONSULTAR SEGÚN DIFERENTE EMPRESA, sino el nombre del cliente no será del todo correcto ???
							$_planifloc			= DataManager::getCuenta('ctalocalidad', 'ctaidcuenta', $_planifcliente, 1);
							$_planifenvioplanif	= $_planificados["planiffechaenvio"];
							$_fecha_envio		= ($_planifenvioplanif == "0000-00-00 00:00:00" || $_planifenvioplanif == "2001-01-01 00:00:00" || $_planifenvioplanif == "1900-01-01 00:00:00") ? 'SIN ENVIAR' : $_planifenvioplanif;
							?>                    
							<tr>
								<td><?php echo $_planiffecha;?></td>
								<td align="center"><?php echo $_planifidvend; ?></td>
								<td><?php echo $_planifnombrevend;?></td>
								<td><?php echo $_planifcliente ?></td>
								<td><?php echo $_planifnombre; ?></td>
								<td><?php echo $_planifdir; ?></td>
								<td><?php echo $_planifloc; ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo $_fecha_envio; ?></td>
							</tr>
							<?php
						}
					}
					break;				
				case 'reporte': ?>
					<tr>
						<td>Fecha</td><td>Id</td><td>Nombre</td><td>Planificado</td><td>VCD</td><td>Sin Novedad</td><td>No realizado</td><td>Otros</td><td>Visitas</td>
					</tr>
					<?php
					$_partecontPanificado = 0;
					$_partecontVisita	= 0;				
					$_partecont_VCD		= 0; //Ventas, Cobranzas y/o Devolución
					$_partecont_Otros	= 0;
					$_partecont_SN		= 0;
					$_partecont_NR		= 0;
					$_parteclienteAntes	= 0;

					$_detalle_partes	= DataManager::getDetalleParteExportar($_fecha_i, $_fecha_f);
					if (count($_detalle_partes)){
						foreach ($_detalle_partes as $k => $_partes){	
							$_partefecha		= $_partes["partefecha"];
							$_parteidvend		= $_partes["parteidvendedor"];
							$_partenombrevend	= DataManager::getUsuario('unombre', $_partes["parteidvendedor"]);
							
							$_partecliente		= $_partes["parteidcliente"];
							
							if($_partecliente != $_parteclienteAntes){
								$_partecontPanificado += 1;
								$_parteclienteAntes = $_partecliente;
							} 

							$_parteacciones		=	"";
							if (!empty($_partes["parteaccion"])){
								$_accion	=	explode(',', $_partes["parteaccion"]);								
								for($j=0; $j < count($_accion); $j++){								
									$_partes_ac		=	DataManager::getAccion($_accion[$j]);
									if($_partes_ac){ 
										$_parteacciones	= 	$_partes_ac[0]["acnombre"];
										switch($_parteacciones){
											//Ventas, cobranzas y Devoluciones
											case 'Venta':
											case 'Cobranza':
											case 'Devolución':
												$_partecont_VCD		+=	1;	
												break;
											//Sin Novedad
											case 'Sin Novedades':
												$_partecont_SN		+=	1;	
												break;
											//No realizado
											case 'No Realizada':
												$_partecont_NR		+=	1;	
												break;
											//OTROS Casos
											case 'POP':
											case 'Reemplazo':
											case 'Adhesión Club':
											case 'Troquel Club':
											case 'Premios Club':
												$_partecont_Otros	+=	1;
												break;
										}	
									}
								}
							}	

							if(($k+1) < count($_detalle_partes)){
								$_partes_sig		=	$_detalle_partes[$k+1];
								$_parteidvend_sig	= 	$_partes_sig["parteidvendedor"];

								if($_parteidvend != $_parteidvend_sig){
									$_parteidvendante 	= 	$_parteidvend; 
			
									$visitas = $_partecont_VCD - $_partecont_SN - $_partecont_NR - $_partecont_Otros;
									
									$_partecontVisitas = ($visitas == 0) ? 0 : $_partecontPanificado - $_partecont_NR;
			
									?>
									<tr>
										<td><?php echo $_partefecha;?></td>
										<td align="center"><?php echo $_parteidvend; ?></td>
										<td><?php echo $_partenombrevend;?></td>

										<td><?php echo $_partecontPanificado; ?></td>
										<td><?php echo $_partecont_VCD; ?></td>
										<td><?php echo $_partecont_SN; ?></td>
										<td><?php echo $_partecont_NR; ?></td>
										<td><?php echo $_partecont_Otros; ?></td>
										<td><?php echo $_partecontVisitas; ?></td>
									</tr> <?php
									$_partecontPanificado=	0;
									$_partecontVisita	=	0;				
									$_partecont_VCD		=	0; //Ventas, Cobranzas y/o Devolución
									$_partecont_Otros	=	0;
									$_partecont_SN		=	0;
									$_partecont_NR		=	0;
								} 	
							}

						}
					} 
				break;

				default: ?>
					<tr><td colspan="8" align="center" style="font-weight:bold; border:none;"> Detalle <?php echo $titulo; ?> </td></tr>
					<tr><td colspan="8" style="font-weight:bold; border:none;"> DESDE: <?php echo $_fecha_inicio; ?></td></tr>
					<tr><td colspan="8" style="font-weight:bold; border:none;"> HASTA: <?php echo $_fecha_final; ?></td></tr>
					<tr>
						<td colspan="8">Ocurri&oacute; un ERROR al intentar exportar. Vuelva a intentarlo</td>
					</tr> <?php
				break;	


			} ?>
		</table> 
	 </body>
 </html>