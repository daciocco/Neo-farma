<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){ 	
	 $_nextURL = sprintf("%s", "/pedidos/login/index.php");
	 header("Location: $_nextURL");
	 exit;
 }
 
 $_fecha_planif	= empty($_REQUEST['fecha_planif'])	? date("d-m-Y") : $_REQUEST['fecha_planif'];
 
 $_button_print		= 	sprintf( "<a id=\"imprime\" title=\"Imprimir Parte Diario\"  onclick=\"javascript:dac_imprMuestra('muestra_parte')\">%s</a>", "<img src=\"/pedidos/images/icons/icono-print.png\" border=\"0\" align=\"absmiddle\" />");

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
?>

<!DOCTYPE html>
<html>
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
    <script type="text/javascript">
		function dac_imprMuestra(muestra){
			var ficha=document.getElementById(muestra);
			var ventimp=window.open(' ','popimpr');
			ventimp.document.write(ficha.innerHTML);
			ventimp.document.close();
			ventimp.print();
			ventimp.close();
		}
	</script>
</head>

<body>	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php
        $_section		=	"pedidos";
        $_subsection 	=	"mis_pedidos";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
    
	<main class="cuerpo">
		<div class="cbte">
			<div id="muestra_parte">
				<div class="cbte_header">
					<div class="cbte_boxheader" style="text-align: left;
		float: left; max-width: 400px; min-width: 300px;">
						<?php echo $cabeceraPedido; ?>
					</div>

					<div class="cbte_boxheader" style="text-align: left;
		float: left; max-width: 400px; min-width: 300px;">
						<h2 style="font-size: 18px;">PARTE DIARIO (<?php echo $_fecha_planif; ?>)</br>
						<?php echo $_SESSION["_usrname"]; ?></h2>
					</div>
				</div> 

				<?php							
				$_partediario	= DataManager::getDetalleParteDiario($_fecha_planif, $_SESSION["_usrid"]);
				if (count($_partediario)){ ?>                                    
					<div class="cbte_boxcontent2">
						<table class="datatab_detalle" width="100%" cellpadding="0" cellspacing="0" style="border:2px solid #999;">
							<thead>
								<tr align="left">
									<th scope="col" width="10%" height="20" style="border-left: 1px solid #999; text-align: center;" >Cliente</th>
									<th scope="col" width="12%" style="border-left: 1px solid #999; text-align: center;">Nombre</th>
									<th scope="col" width="12%" style="border-left: 1px solid #999; text-align: center;">Domicilio</th>
									<th scope="col" width="11%" style="border-left: 1px solid #999; text-align: center;">Acci&oacute;n</th>
									<th scope="col" width="15%" style="border-left: 1px solid #999; text-align: center;">Trabaja con...</th>
									<th scope="col" width="40%" style="border-left: 1px solid #999; text-align: center;">Observaci&oacute;n</th>  
								</tr>
							</thead>
							<?php	
							foreach ($_partediario as $k => $_parte){
								$_parte 		= $_partediario[$k];
								if($_parte["parteidcliente"]==0){	$_partecliente	=	"NUEVO";
								}else{								$_partecliente	=	$_parte["parteidcliente"];};					
								$_partenombre	= $_parte["parteclinombre"];
								$_partedir		= $_parte["parteclidireccion"];
								$_parteacciones = explode(',', $_parte["parteaccion"]);								
								$_acc = '';
								for ($i=0; $i < count($_parteacciones); $i++){
									if($_parteacciones[$i]){
										$_acciones	= DataManager::getAccion($_parteacciones[$i]);
										$_accion 	= $_acciones[0];
										if($i==0){	$_acc	= $_accion["acnombre"];
										}else{		$_acc	= $_acc."</br>".$_accion["acnombre"];}
									}
								}									
								$_partetrabaja	= $_parte["partetrabajocon"];
								$_parteobserv	= $_parte["parteobservacion"];

								echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
								echo sprintf("<td height=\"100\" align=\"center\"  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td align=\"center\"  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td style=\"border:1px solid #999; border-right: none;\">%s</td>", $_partecliente, $_partenombre, $_partedir, $_acc,$_partetrabaja,$_parteobserv);
								echo sprintf("</tr>");  
							} ?>
						</table>
					</div>  <!-- cbte_boxcontent2 --> <?php
				} ?>       

				<div class="cbte_boxcontent2" align="center"> 
					<?php echo $piePedido; ?>
				</div>  <!-- cbte_boxcontent2 --> 
			</div>  <!-- muestra -->  
			
			<div class="cbte_boxcontent2" align="center"> 
				<?php echo $_button_print; ?>
			</div>  <!-- cbte_boxcontent2 -->     	
		</div>  <!-- cbte_box -->
		
		
	</main> <!-- fin cuerpo -->
	
	<footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>