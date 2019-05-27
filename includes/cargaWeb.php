	<div id="imgBloqueo" class="parpadeo">
		<img id="bloqueoContenido">
	</div>
	
	<script>
		$('html, body, .cuerpo').css('overflow', 'hidden');
		
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