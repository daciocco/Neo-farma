<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){ 	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
	exit;
}

$_ptnropedido	= 	empty($_REQUEST['idpedido'])?	0 	: 	$_REQUEST['idpedido'];
$backURL		= 	empty($_REQUEST['backURL']) ? '/pedidos/transfer/gestion/liquidacion/': $_REQUEST['backURL']; 
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
		$_section 	= "transfer";
		$_subsection 	= "liquidacion_transfer";
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
	</nav>	
           	
    <main class="cuerpo">
    	<div  class="cbte">
    	
			<div class="cbte_header">
				<div class="cbte_boxheader">
					<?php
					//header And footer
					include_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/headersAndFooters.php");
					echo $cabeceraPedido;
					?>
				</div>  <!-- cbte_boxheader -->
				<div class="cbte_boxheader"> 
					<h1>LIQUIDACI&Oacute;N TRANSFER</h1> 
					Guevara 1347 - CP1427 - Capital Federal - Tel: 4555-3366
				</div>  <!-- cbte_boxheader -->  
			</div>
			<?php
			if ($_ptnropedido) {
				//******************************************//  
				//	Consulta liquidaciones del transfer		//
				$_liquidaciones		=	DataManager::getDetalleLiquidacionTransfer(NULL, NULL, $_ptnropedido, NULL);
				if ($_liquidaciones) { 
					foreach ($_liquidaciones as $k => $_liq){
						$_liq			=	$_liquidaciones[$k];
						if ($k==0){ ?>
								<div class="cbte_boxcontent"> 
									<div class="cbte_box">Nro. Transfer <?php echo $_ptnropedido;?></div>
								</div>  <!-- cbte_box_nro -->

							<div class="cbte_boxcontent2"> 
								<table id="tabla_liquidacion" name="tabla_liquidacion" class="tabla_liquidacion" border="0">
									<thead>
										<tr height="60px;">  <!-- Títulos de las Columnas -->
											<th align="center">Liquidaci&oacute;n</th>
											<th align="center">Transfer</th>
											<th align="center">Droguería</th>
											<th align="center">Fecha Factura</th>
											<th align="center">Art&iacute;culo</th> 
											<th align="center">Cantidad</th>    
											<th align="center">PSL Unit.</th>
											<th align="center">Desc. PSL</th>
											<th align="center">Importe NC</th>
										</tr>
									</thead>

									<tbody id="lista_liquidacion">  <?php 
						}

						$_liqID			=	$_liq['liqid'];
						$_drogid		=	$_liq['liqdrogid'];

						$_liqFecha		=	dac_invertirFecha($_liq['liqfecha']);
						$_liqFechaFact	=	dac_invertirFecha( $_liq['liqfechafact'] );

						$_liqean		=	str_replace("", "", $_liq['liqean']);
						$_articulo		=	DataManager::getFieldArticulo("artcodbarra", $_liqean) ;						
						$_nombreart		=	$_articulo['0']['artnombre'];
						$_idart			=	$_articulo['0']['artidart'];

						$_liqcant		=	$_liq['liqcant'];
						$_liqunit		=	$_liq['liqunitario'];
						$_liqdesc		=	$_liq['liqdescuento'];
						$_liqimportenc	=	$_liq['liqimportenc'];
						$_TotalNC		+=	$_liqimportenc;

						((($k % 2) != 0)? $clase="par" : $clase="impar");

						// CONTROLA las Condiciones de las Liquidaciones y Notas de Crédito//
						$_liqTransfer	=	$_ptnropedido;
						include($_SERVER['DOCUMENT_ROOT']."/pedidos/transfer/gestion/liquidacion/logica/controles.liquidacion.php");
						/*****************/
						?> 

						<tr id="lista_liquidacion<?php echo $k;?>" class="<?php echo $clase;?>">      
							<input id="idliquid" name="idliquid[]" type="text" value="<?php echo $_liqID;?>" hidden/> 
							<td> <input id="fecha" name="fecha[]" type="text" size="7px" value="<?php echo $_liqFecha;?>" readonly/> </td>
							<td><input id="transfer" name="transfer[]" type="text" size="7px" value="<?php echo $_liqTransfer;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="drogid" name="drogid[]" type="text" size="8px" value="<?php echo $_drogid;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="fechafact" name="fechafact[]" type="text" size="8px" value="<?php echo $_liqFechaFact;?>" style="border:none; text-align:center;" readonly/></td>

							<td><?php echo $_idart." - ".$_nombreart; ?>
								<input id="idart" name="idart[]" type="text" value="<?php echo $_idart;?>" readonly hidden/>
							</td>
							<td><input id="cant" name="cant[]" type="text" size="5px" value="<?php echo $_liqcant;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="unitario" name="unitario[]" type="text" size="8px" value="<?php echo $_liqunit;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="desc" name="desc[]" type="text" size="8px" value="<?php echo $_liqdesc;?>" style="border:none; text-align:center;" readonly/></td>
							<td><input id="importe" name="importe[]" type="text" size="8px" value="<?php echo $_liqimportenc;?>" style="border:none; text-align:center;" readonly/></td>
						</tr> <?php

					} ?>
						<tfoot>
							<tr>
								<th colspan="7" height="30px" style="border:none; font-weight:bold;"></th>
								<th colspan="1" height="30px" style="border:none; font-weight:bold;">Total</th>
								<th colspan="1" height="30px" style="border:none; font-weight:bold;"><?php echo $_TotalNC; ?></th>
							</tr>
						</tfoot>
					</tbody> <?php
				} else { ?>
					<tr class="impar"><td colspan="8" align="center">No hay liquidaciones cargadas</td></tr><?php           
				} ?>							
				</table>
				</div>  <!-- cbte_boxcontent2 -->
			<?php } ?>
			
			
		</div>
    </main> <!-- fin cuerpo -->
    
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>