<?php 
session_start();
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!= "M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
 	header("Location: $_nextURL");
	exit;
}

$_mes		= 	empty($_REQUEST['mes']) 	? 0	: $_REQUEST['mes'];
$_anio		= 	empty($_REQUEST['anio']) 	? 0 : $_REQUEST['anio'];
$_drogid	= 	empty($_REQUEST['drogid']) 	? 0 : $_REQUEST['drogid'];

$_drogueria	=	strtoupper(DataManager::getDrogueriaField('drogtnombre', $_drogid));
$_tipodrog	=	strtoupper(DataManager::getDrogueriaField('drogttipoabm ', $_drogid));

$_style_tit	=	'align="center" style="background-color:#1876bc; color:#FFF; font-weight:bold;"';
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=".$_drogueria."-TFR-".$_mes.$_anio.".xls");	
?>
<?php echo $_style_tit; ?>
<HTML LANG="es">
<TITLE>::. Exportacion de Datos (ABM Transfer) .::</TITLE>
<head></head>
<body>
	<table id="tabla_bonif" name"tabla_bonif" class="tabla_bonif" border="2">
       	<thead>         
        	<?php if($_tipodrog == 'B'){
				?>
				<tr>  <!-- Títulos de las Columnas -->
                	<th <?php echo $_style_tit; ?>>MODULO (si aplica)</th>
                	<th <?php echo $_style_tit; ?>>CODIGO DE BARRAS</th>
                	<th <?php echo $_style_tit; ?>>DESCRIPCION</th>
                	<th <?php echo $_style_tit; ?>>% DESCUENTO A TRASLADAR SOBRE PSL</th>                
                	<th <?php echo $_style_tit; ?>>MIN</th>
                	<th <?php echo $_style_tit; ?>>MAX</th>
                    <th <?php echo $_style_tit; ?>>MULT</th>
                	<th <?php echo $_style_tit; ?>>PLAZO</th>
                	<th <?php echo $_style_tit; ?>>COMPENSACION EN NC %</th>   
                    <th <?php echo $_style_tit; ?>>COMPENSACION EN FACTURA LIBRE %</th>       
				</tr> <?php			
			} else { ?>
				<tr>  <!-- Títulos de las Columnas -->
                	<th <?php echo $_style_tit; ?>>LABORATORIO</th>
                	<th <?php echo $_style_tit; ?>>TIPO</th> <!-- TIPO TRF TD o TL -->
                	<th <?php echo $_style_tit; ?>>EAN</th> <!-- CODIGO DE BARRAS -->
                	<th <?php echo $_style_tit; ?>>DESCRIPCION</th>                
                	<th <?php echo $_style_tit; ?>>% DESC SOBRE PSL</th> <!-- % DESCUENTO A TRASLADAR SOBRE PSL -->
                	<th <?php echo $_style_tit; ?>>MIN</th>
                	<th <?php echo $_style_tit; ?>>PLAZO</th>
                	<th <?php echo $_style_tit; ?>>% COMP NC</th> <!-- COMPENSACION EN NC % -->    
				</tr> <?php
			} ?>
		</thead>
                 
        <tbody><?php 
			/**************************************/  
			/*Consulta ABM del mes año y drogueria*/
			/**************************************/
			$_abms	=	DataManager::getDetalleAbm($_mes, $_anio, $_drogid, 'TD');
			if ($_abms) {
				foreach ($_abms as $k => $_abm){
					$_abm		=	$_abms[$k];
					$_abmID		=	$_abm['abmid'];
					$_abmArtid	= 	$_abm['abmartid'];		
					$_abmDesc	= 	$_abm['abmdesc'];
					$_abmDifComp= 	$_abm['abmdifcomp'];											
					//$estilo_fila	=	"background-color:#666; color:#FFF; font-weight:bold;";
					?>
                    <?php if($_tipodrog == 'B'){ ?>
                   		<tr style=" <?php /*echo $estilo;*/ ?> ">
                       		<td></td>
                            <?php $_artean	= DataManager::getArticulo('artcodbarra', $_abmArtid, 1, 1); ?>
                        	<td align="center" style="mso-style-parent:style0; mso-number-format:\@;"><?php echo $_artean; ?> </td>
                        	<?php $_artnombre	= DataManager::getArticulo('artnombre', $_abmArtid, 1, 1); ?>
                        	<td><?php echo $_artnombre; ?></td>   
                        	<td align="center" ><?php echo $_abmDesc." %"; ?></td>                     
                        	<td align="center">1</td>
                            <td ></td><td ></td>
                        	<td align="center">Habitual</td>
                        	<td align="center"><?php echo ($_abmDesc - $_abmDifComp)." %"; ?></td>
                            <td ></td>
						</tr> <?php 			
					} else { ?>
                   		<tr style=" <?php /*echo $estilo;*/ ?> ">
                       		<td align="center">Laboratorios Gezzi SRL</td>
                        	<td align="center" style="width:50px;">TD</td>                         
							<?php $_artean	= DataManager::getArticulo('artcodbarra', $_abmArtid, 1, 1); ?>
                        	<td align="center" style="mso-style-parent:style0; mso-number-format:\@; width:120px;"><?php echo $_artean; ?> </td>                        	<?php $_artnombre	= DataManager::getArticulo('artnombre', $_abmArtid, 1, 1); ?>
                        	<td><?php echo $_artnombre; ?></td>   
                        	<td align="center" style="width:50px;"><?php echo $_abmDesc." %"; ?></td>                     
                        	<td align="center">1</td>
                        	<td align="center">Habitual</td>
                        	<td align="center" style="width:60px;"><?php echo $_abmDifComp." %";?></td>
                            <!--  
                            La última columna tenía el siguiente cálculo -> *echo ($_abmDesc - $_abmDifComp)."%";*
                            pero estaba incorrecto ya que el valor de Compensación en NC% del excel es el % que se le paga a la droguería
                            y es el mismo que se ingresa en el ABM que estaba indicado como "Diferencia de Copmpensación" PERO???
                            se SUPONE que yo entendí que era DIF. de COMP. NUESTRO, cuando en realidad es DIF de COMP de DROGUERÍA.
                            -->
						</tr> <?php 
					} 
				} //FIN del FOR 
			} ?>                    
		</tbody>                
        <tfoot></tfoot>
	</table> 
</body>
</html>                
               
               