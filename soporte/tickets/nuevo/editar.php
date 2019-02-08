<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="V" && $_SESSION["_usrrol"]!="M" && $_SESSION["_usrrol"]!="G"){	
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
 	exit;
}

$tkidsector	= empty($_REQUEST['tkidsector']) ? 0 : $_REQUEST['tkidsector'];
$backURL	= empty($_REQUEST['backURL']) ? '/pedidos/soporte/tickets/nuevo/': $_REQUEST['backURL'];

if($tkidsector){
	$sectores	=	DataManager::getTicketSector();
	foreach( $sectores as $k => $sec ) {	
		$idSec	= $sec['tksid'];
		if($tkidsector == $idSec){
			$titulo	= $sec['tksnombre'];
			
			$motivos	= DataManager::getTicketMotivos(); 
			if (count($motivos)) {
				foreach ($motivos as $j => $mot) {
					$sector	= $mot['tkmotidsector'];
					if($tkidsector == $sector){
						$idMotivo[]	= $mot['tkmotid'];
						$motivo[] 	= $mot['tkmotmotivo'];
					}
					
				}
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php require $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/metas.inc.php";?>
</head>
<body>
    <header class="cabecera">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/header.inc.php"); ?>
    </header><!-- cabecera -->	
    
    <nav class="menuprincipal"> <?php 
        $_section		=	"soporte";
        $_subsection 	=	"ticket";
        include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.inc.php"); ?>
    </nav>
        
    <main class="cuerpo">
    	<div class="box_body">	                				
            <form id="fmTicket" class="fm_edit2" method="post" enctype="multipart/form-data">
                <fieldset>
                	<legend><?php echo $titulo ?></legend>
                    
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
                    
                    <input type="hidden" name="tkidsector" value="<?php echo $tkidsector;?>" />
                    
                    <div class="bloque_1"> 
                        <label for="tkmotivo">Motivo del Servicio</label>
                        <select name="tkmotivo" >   
							<option id="0" value="0" selected></option> <?php
							foreach ($motivo as $k => $mot) { ?>  
								<option id="<?php echo $idMotivo[$k]; ?>" value="<?php echo $idMotivo[$k]; ?>"><?php echo $mot; ?></option><?php
							} ?>
                        </select>                        
                    </div>
                    
                    <div class="bloque_1">
                        <div class="inputfile"><input id="imagen" name="imagen" class="file" type="file"/></div>
                    </div> 
                    
                    <div class="bloque_3">
                        <label for="tkmensaje">Mensaje</label>
                        <textarea name="tkmensaje" type="text"/></textarea> 
                    </div>
                                       
                     <!--div class="bloque_3">
                        <label for="tkcopia">Copiar respuesta a:</label>
                        <input name="tkcopia" type="text"  maxlength="150" value="<?php //echo $copiar;?>"/>
                    </div--> 
                    
                    <div class="bloque_1"></div>
                     <div class="bloque_4"></div>
                      <div class="bloque_4"></div>
                       <div class="bloque_4"></div>
                    <div class="bloque_4"> 
                    
						<?php $urlSend	=	'/pedidos/soporte/tickets/nuevo/logica/update.ticket.php';?>
						<a id="btnSend" title="Enviar" style="cursor:pointer;"> 
							<img src="/pedidos/images/icons/icono-send.png" onmouseover="this.src='/pedidos/images/icons/icono-send-hover.png';" onmouseout="this.src='/pedidos/images/icons/icono-send.png';" border="0" align="absmiddle" onclick="javascript:dac_sendForm(fmTicket, '<?php echo $urlSend;?>');"/>
						</a>
                    </div> 
                </fieldset>		
            </form>		
    	</div> <!-- FIN box_body -->
    	<hr>	
    </main> <!-- fin cuerpo -->
                        
    <footer class="pie">
        <?php include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/footer.inc.php"); ?>
    </footer> <!-- fin pie -->
</body>
</html>