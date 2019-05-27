
<?php 
	if($_SESSION["_usrrol"]=="A" || $_SESSION["_usrrol"]=="M" || $_SESSION["_usrrol"]=="G") {
		include($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/menu.admin.php"); 
} ?>


<div id="pie_contenido">
	<?php 		
	$_password	= sprintf( "<a id=\"btsend\" href=\"/pedidos/usuarios/password/\" title=\"Cambiar Clave\">%s</a>", "<img class=\"icon-key\"/>");    

	if ($_SESSION["_usrrol"]!= "C"){ echo $_password; }
	?>

	<span class="copyright" title="Desarrollo Web + Dise&ntilde;o por Cioccolanti, Diego Ariel">Copyright &#169; 2014-2020&nbsp;<a href="https://www.neo-farma.com.ar" rel="copyright" title="Sitio web oficial de neo-farma.com.ar">neo-farma.com.ar</a>&nbsp;(daciocco)</span>   
</div>

 
	
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120786037-1"></script>
<script>
	/*  sistemasneofarma@gmail.com */
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-120786037-1');
</script>




