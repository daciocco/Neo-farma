//Cargas a Inicio de Rendición
$(document).ready(function() {		
	abrirVentana();	
});
	
function abrirVentana() {
	//******************************************//
	$('#open_talonario').click(function(){
		$('#popup-flotante').fadeIn('slow');
		$('#popup-talonario').fadeIn('slow');
		$('#popup-talonario').css({
			'left': ($(window).width() / 2 - $(window).width() / 2) + 'px', 
			'top': ($(window).height() / 2 - $(window).height() / 2) + 'px'
		});
		$('.popup-overlay').fadeIn('slow');
		$('.popup-overlay').height($(window).height());
		return false;
	});
	
	$('#close-talonario').click(function(){
		$('#popup-flotante').fadeOut('slow');
		$('#popup-talonario').fadeOut('slow');
		$('.popup-overlay').fadeOut('slow');
		$('#fm_nvo_recibo')[0].reset();
		$('#close-recibo').click();		
		return false;
	});			
	//**********************************//
	$('#open-recibo').click(function(){
		$('#popup-recibo').fadeIn('slow');
		$('.popup-overlay2').fadeIn('slow');
		$('.popup-overlay2').height($(window).height());
		return false;
	});
				
	$('#close-recibo').click(function(){
		$('#popup-recibo').fadeOut('slow');
		$('.popup-overlay2').fadeOut('slow');
		return false;
	});
	//******************************************//	
}
