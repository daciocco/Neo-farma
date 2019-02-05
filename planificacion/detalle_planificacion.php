<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
 	header("Location: $_nextURL");
	 exit;
 }
 
 $_fecha_planif	= empty($_REQUEST['fecha_planif'])	? date("d-m-Y") : $_REQUEST['fecha_planif'];
 
 $_button_print		= 	sprintf( "<a id=\"imprime\" title=\"Imprimir Planificaci&oacute;n\" onclick=\"javascript:dac_imprimirMuestra('muestra_planif')\">%s</a>", "<img src=\"/pedidos/images/icons/icono-print.png\" border=\"0\" align=\"absmiddle\" />");

//header And footer
include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
?>

<!DOCTYPE html>
<html >
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>

<body>	
	<header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php
        $_section		=	"planificacion";
        $_subsection 	=	"mis_pedidos";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav> <!-- fin menu -->
    
	<main class="cuerpo">	
		<div class="cbte">
			<div id="muestra_planif">
				<div class="cbte_header">
					<div class="cbte_boxheader" style="text-align: left;
		float: left; max-width: 400px; min-width: 300px;">
						<?php echo $cabeceraPedido; ?>
					</div>

					<div class="cbte_boxheader" style="text-align: left;
		float: left; max-width: 400px; min-width: 300px;" >
						<h2 style="font-size: 18px;">PLANIFICACI&Oacute;N (<?php echo $_fecha_planif; ?>)</br>
						<?php echo $_SESSION["_usrname"]; ?></h2>
					</div>
				</div> 

				<?php							
				$_planificacion	= DataManager::getDetallePlanificacion($_fecha_planif, $_SESSION["_usrid"]);
				if (count($_planificacion)){ ?>                                    
					<div class="cbte_boxcontent2">
						<table class="datatab_detalle" width="100%" cellpadding="0" cellspacing="0" style="border:2px solid #999;">
							<thead>
								<tr align="left">
									<th scope="col" width="8%" height="30" style="border-left: 1px solid #999; text-align: center;">Cliente</th>
									<th scope="col" width="10%" style="border-left: 1px solid #999; text-align: center;">Nombre</th>
									<th scope="col" width="10%" style="border-left: 1px solid #999; text-align: center;">Domicilio</th>
									<th scope="col" width="10%" style="border-left: 1px solid #999; text-align: center;">Acci&oacute;n</th>
									<th scope="col" width="13%" style="border-left: 1px solid #999; text-align: center;">Trabaja con...</th>
									<th scope="col" width="39%" style="border-left: 1px solid #999; text-align: center;">Observaci&oacute;n</th> 
									<th scope="col" width="10%" style="border-left: 1px solid #999; text-align: center;">Sello</th>                                       
								</tr>
							</thead>
							<?php								
							foreach ($_planificacion as $k => $_planif){
								$_planif 		= $_planificacion[$k];
								$_planifcliente	= $_planif["planifidcliente"];								
								$_planifnombre	= $_planif["planifclinombre"];
								$_planifdir		= $_planif["planifclidireccion"];															
								echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
								echo sprintf("<td height=\"100\" align=\"center\" style=\"border:1px solid #999; border-right: none;\" >%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td><td  style=\"border:1px solid #999; border-right: none;\">%s</td>", $_planifcliente, $_planifnombre, $_planifdir, '','','','');
								echo sprintf("</tr>");  
							} ?>
							<tr height="30" align="right">
								<td colspan="7" style="border:none; padding-right:150px;">  Firma Vendedor: </td>
							</tr>
							<tr height="30" bordercolor="#FFFFFF">
								<td colspan="7" style="border:none;"></td>
							</tr>  
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
		</div>  <!-- cbte --> 
		      
	</main> <!-- fin cuerpo -->
	
	<footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>