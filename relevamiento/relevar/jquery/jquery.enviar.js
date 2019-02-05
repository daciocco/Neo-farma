$(document).ready(function() {
	$("#btsend").click(function () {	// desencadenar evento cuando se hace clic en el botón
		var form	=	"form#fm_relevar";
		var url		=	"/pedidos/relevamiento/relevar/logica/update.relevar.php";
		dac_sendForm(form, url);
        return false;
    });
});