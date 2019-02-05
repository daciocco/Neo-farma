//Carga la ventana
$(document).ready(function() {	
	$('#abrir-slotmachine').click(function(){
		$('#slotmachine').fadeIn('slow');
		
		$('#slotmachine').css({
			'width': '100%',
			'height': '100%',
			'left': ($(window).width() / 2 - $(slotmachine).width() / 2) + 'px', 
			'top': ($(window).height() / 2 - $(slotmachine).height() / 2) + 'px'
		});
		$('#juego').fadeIn('slow');
		return false;
	});
	
	$('#cerrar-slotmachine').click(function(){
		$('#slotmachine').fadeOut('slow');		
		return false;
	});			
	//**********************************//
	
	$(window).resize();
});
	
$(window).resize(function () {
	$('#slotmachine').css({
		'width': '100%',
		'height': '100%',
		'left': ($(window).width() / 2 - $(slotmachine).width() / 2) + 'px', 
		'top': ($(window).height() / 2 - $(slotmachine).height() / 2) + 'px'
	});
});

