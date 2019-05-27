<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
if ($_SESSION["_usrrol"]!="P"){
 	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
}

$_sms	=	empty($_GET['sms']) ? 0 : $_GET['sms'];

if ($_sms) {
 	 $_nrofact	=	$_SESSION['nrofact'];
	 switch ($_sms) {
		case 1: $_info = "El n&uacute;mero de factura es obligatorio."; break;
		case 2: $_info = "El archivo adjunto no debe superar los 4MB."; break;
		case 3: $_info = "El archivo adjunto debe ser IMAGEN o PDF."; break;
		case 4: $_info = "Error al intentar cargar el archivo adjunto."; break;
	 	case 5: $_info = "Error al intentar enviar el mail de solicitud. Por favor, vuelva a intentarlo"; break;
		case 6: $_info = "La solicitud se realiz&oacute; con &eacute;xito."; break;
	 } // mensaje de error
} else {
	$_nrofact 	=	"";
}

$_button	=	sprintf("<input type=\"submit\" id=\"btsend\" name=\"_accion\" value=\"Solicitar\"/>");
$_action	=	"/pedidos/proveedores/pagos/solicitarfecha/logica/upload.php";
?>


<script type="text/javascript">
	function dac_MostrarSms(sms){
		document.getElementById('box_error').style.display 			= 'none';
		if(sms){	
			if(sms > 0 && sms < 6){
				document.getElementById('box_error').style.display 			= 'block';
				document.getElementById('box_confirmacion').style.display 	= 'none';
			} else {
				document.getElementById('box_confirmacion').style.display 	= 'block';
				document.getElementById('box_error').style.display 			= 'none';
			}
		}
	}
</script>

<div class="box_body">						
    <form action="<?php echo $_action;?>" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>&nbsp;Solicita Fecha de Pago</legend> 
            <div class="bloque_1"> 
                <fieldset id='box_error' class="msg_error"> 	
                    <div id="msg_error"> <?php  echo $_info; ?> </div>
                </fieldset>             
                <fieldset id='box_confirmacion' class="msg_confirmacion">
                    <div id="msg_confirmacion"><?php echo $_info;?></div>      
                </fieldset>                          
                <?php
                    echo "<script>";
                    echo "javascript:dac_MostrarSms(".$_sms.")";
                    echo "</script>";
                ?>       
            </div> 
            
            <div class="bloque_5">            
                <input name="archivo" type="file"/>
            </div>
            
            <div class="bloque_7"> 	
                <label for="nrofact">Nro de Factura *</label>
                <input name="nrofact"  id="nrofact" type="text" maxlength="10" value="<?php echo @$_nrofact;?>"/>
            </div>
            
            <div class="bloque_7"> <br><?php echo $_button;?></div>
        </fieldset>	
    </form>	
</div>

<hr>

<!-- Scripts para IMPORTAR ARCHIVO -->
<script type="text/javascript" src="/pedidos/proveedores/pagos/solicitarfecha/logica/jquery/jquery.script.file.js"></script>

