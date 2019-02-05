
	<div id="imgBloqueo" class="parpadeo">
		<img id="bloqueoContenido" src="https://www.neo-farma.com.ar/pedidos/images/gif/loadingPage.gif">
	</div>
	
	<script>
		$('html, body, .cuerpo').css('overflow', 'hidden'); 
		
		var url = window.location.origin+'/pedidos/images/gif/loadingPage.gif';		
		document.getElementById("bloqueoContenido").src = url;
		
		$('#imgBloqueo').css({
			'position': 'absolute',
			'z-index': '100',
			'width': '100%', 
			'height': '100%'
		});
		
		$('#bloqueoContenido').css({
			'margin-left': ($(window).width() / 2 - $(bloqueoContenido).width() / 2) + 'px', 
			'margin-top': ($(window).height() / 2 - $(bloqueoContenido).height() / 2) + 'px'
		});

		$(window).resize(function(){
			$('#bloqueoContenido').css({
				'margin-left': ($(window).width() / 2 - $(bloqueoContenido).width() / 2) + 'px', 
				'margin-top': ($(window).height() / 2 - $(bloqueoContenido).height() / 2) + 'px'
			});
		});
		
		window.onload = function() {
			$('#imgBloqueo').fadeOut('slow');	
			$('#imgBloqueo').remove;
			$('html, body, .cuerpo').css('overflow', 'auto'); 
		}
	</script>