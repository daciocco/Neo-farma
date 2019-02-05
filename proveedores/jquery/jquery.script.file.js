//PAra subir un solo archivo se realiza el mismo código, solo que el input no es multifile
$(function(){ $('#btfile_send').click(uploadFile);});
/****************************/
//		UPLOAD FILE			//
/****************************/
function uploadFile(){		
	var archivos = document.getElementById("archivo");
	var archivo = archivos.files;
	var archivos = new FormData();
	
	for(i=0; i<archivo.length; i++){
		archivos.append('archivo'+i,archivo[i]); 
	}	
		
	var provid	=	document.getElementById("provid").value;
	archivos.append("provid", provid);	
	
	$.ajax({
		url:'/pedidos/proveedores/logica/upload.file.php',
		type:'POST',
		contentType:false,
		data:archivos,
		processData:false,
		cache:false,
		beforeSend: function () {
					$('#box_confirmacion').css({'display':'none'});
					$('#box_error').css({'display':'none'});
					$('#box_cargando').css({'display':'block'});					
					$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
				},
	}).done(function(msg){
		Mensaje(msg)
	});
}
 
function Mensaje(msg){
	$('.msg_informacion').html(msg);
	$('.msg_informacion').show('slow');
}