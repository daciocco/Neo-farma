$(document).ready(function() {
	$("#btsend").click(function () {	// desencadenar evento cuando se hace clic en el botón
		var form	=	"form#fm_rel_edit";
		var url		=	"/pedidos/relevamiento/logica/update.relevamiento.php";
		var urlBack	=	"/pedidos/relevamiento/";
		dac_sendForm(form, url, urlBack);
        return false;
    });
});