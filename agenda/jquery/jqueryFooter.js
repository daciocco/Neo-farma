$(window).resize(function() {
	"use strict";
	console.log();
	$('.ui-dialog').css({
		'left': ($(window).width() / 2 - $('.ui-dialog').width() / 2) + 'px', 
		'top': ($(window).height() / 2 - $('.ui-dialog').height() / 2) + 'px'
	});

});

$(document).ready(function() {
	"use strict";
	//Lenguaje de inicialización de agenda (por defecto está en ingles)
	var initialLocaleCode = 'es';

	$('#calendar').fullCalendar({			
		theme	: true,
		header	: {
			left	: 'prev,next today',
			center	: 'title',
			right	: 'month,agendaWeek,agendaDay, ,listDay,listWeek,listYear' /*agendaDay*/
		},	

		views: {
			listDay: { buttonText: 'list day' },
			listWeek: { buttonText: 'list week' },
			listYear: { buttonText: 'list year' }
		},		
		//defaultDate: '2016-12-12',	
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		//Lenguaje Local
		locale	: initialLocaleCode,			
		navLinks	: true, // can click day/week names to navigate views
		selectable	: true,
		selectHelper: true,

		//******************************//
		// Carga de datos vía JSON Ajax //
		//*****************************//
		events: {								
			url: '/pedidos/agenda/ajax/getEvents.php',
			//data: {defaultDate : defaultDate},		
			error: function() {
				$('#script-warning').show();
			}
		},
		loading: function(bool) {
			$('#loading').toggle(bool);
		},

		//**********************************//
		// Carga Ventana para INSERT NUEVO EVENTO//
		//**********************************//
		select: function(start, end/*, jsEvent, view, resource*/) {
			$("#dialogo").empty();
			$("#dialogo").dialog({
				modal: true,
				title: 'Nuevo Evento',
				zIndex: 100,
				autoOpen: true,
				resizable: false,
				width: 380,
				height: 450,
				buttons: {
					Guardar : function () {
						//Update del objeto							
						var fechaInicio	=	$( "#fechaInicio" ).val().split('/');
						var fechaFin	=	$( "#fechaFin" ).val().split('/');
						var horaInicio	=	$( "#horaInicio" ).val();
						var horaFin		=	$( "#horaFin" ).val();
						var id			=	0;
						var color		=	$( "#colorpicker" ).val().replace("#", "");

						//Creo el Evento
						var eventData;
						eventData = {
							id		:	id,
							color	:	color,
							title	:	$( "#title" ).val(),
							constraint : $( "#restringido" ).val(),
							texto	:	$( "#texto" ).val(),
							start	:	fechaInicio[1]+'/'+fechaInicio[0]+'/'+fechaInicio[2]+' '+horaInicio,
							end		:	fechaFin[1]+'/'+fechaFin[0]+'/'+fechaFin[2]+' '+horaFin,
						};					

						$.ajax({
							type: "POST",
							cache:	false,						
							url: "/pedidos/agenda/ajax/setEvents.php",	
							data: {	eventData : eventData,}, 
							beforeSend	: function () {
								$('#box_confirmacion').css({'display':'none'});
								$('#box_error').css({'display':'none'});
								$('#box_cargando').css({'display':'block'});
								$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
							},
							success: function(result){	
								if(result){
									if(!isNaN(result)){ 
										$('#box_cargando').css({'display':'none'});	
										$('#box_error').css({'display':'none'});

										//actualiza el id del evento antes de agregarlo
										eventData.id = result;	

										//Agrega el event a la AGENDA pero no lo guarda
										$('#calendar').fullCalendar('renderEvent', eventData, true);

										//Cierra el formulario							
										$("#dialogo").dialog("close");
									} else {
										$('#box_cargando').css({'display':'none'});	
										$('#box_error').css({'display':'block'});
										$("#msg_error").html(result);
									}
								}
							},
							error: function () {
								$('#box_cargando').css({'display':'none'});	
								$('#box_error').css({'display':'block'});
								$("#msg_error").html("Error en el proceso");	
							},	

						});
					},

					Cerrar : function () {	
						//deselecciona el evento si no se cargan datos.
						$('#calendar').fullCalendar('unselect');
						$(this).dialog("close"); 
					}
				},
			});

			$("#dialogo").dialog( "option", "title", "Evento");				

			var contenido	= 
				'<form class="fm_edit_iframe">'+
					'<input type="text" id="id" value="0" style="display:none">'+
					'<input type="text" id="restringido" value="" style="display:none">'+

					'<div class="bloque_2"><input type="text" id="title" name="title" placeholder="Evento"></div><div class="bloque_4"><input type="color" id="colorpicker" value="#3A87AD"></div>'+

					'<div class="bloque_3"><label>Fecha Inicio</label><input id="fechaInicio" type="text" name="fechaInicio" placeholder="Fecha Inicio"></div><div class="bloque_4"><label>Hora</label><input id="horaInicio" type="text" class="time" maxlength="5"/></div>'+

					'<div class="bloque_3"><label>Fecha Fin</label><input id="fechaFin" type="text" name="fechaFin" placeholder="Fecha Fin"></div><div class="bloque_4"><label>Hora</label><input id="horaFin" type="text" class="time" maxlength="5"/></div>'+

					'<div class="bloque_1"><label>Comentario</label><textarea id="texto" maxlength="250"></textarea></div>'	+

					'<div class="bloque_1" align="center">'+
						'<fieldset id="box_error" class="msg_error">'+          
							'<div id="msg_error" align="center"></div>'+
						'</fieldset>'+									 
						'<fieldset id="box_cargando" class="msg_informacion">'+
							'<div id="msg_cargando" align="center"></div>'+      
						'</fieldset>'+ 
					'</div>'+
				'</form>'
			;

			$(contenido).appendTo('#dialogo');		

			$( "#fechaInicio" ).datepicker({ inline: true });
			$( "#fechaInicio" ).datepicker( "option", "dateFormat", "dd/mm/yy" );				
			$( "#fechaInicio" ).val(start.format("DD/MM/YYYY"));				
			$( '#horaInicio').timepicker({  'timeFormat'	: 'H:i', });
			$( '#horaInicio').val(start.format("HH:mm"));				
			$( "#fechaFin" ).datepicker({ inline: true });				
			$( "#fechaFin" ).datepicker( "option", "dateFormat", "dd/mm/yy" );	
			$( "#fechaFin" ).val(end.format("DD/MM/YYYY"));				
			$( '#horaFin').timepicker({ 'timeFormat'	: 'H:i', });							
			$( '#horaFin').val(end.format("HH:mm"));

		},

		//**********************************//
		//	Evento clic UPDATE de objeto de datos	//
		//**********************************//
		eventClick: function(event, jsEvent, view) {
			$("#dialogo").empty();
			$("#dialogo").dialog({
				modal: true,
				title: 'Update Evento',
				zIndex: 100,
				autoOpen: true,
				resizable: false,
				width: 380,
				height: 450,
				buttons: {
					Guardar : function () {
						//Update del objeto, no lo entiendo aún			
						var id			=	event.id;							
						var fechaInicio	=	$( "#fechaInicio" ).val().split('/');
						var fechaFin	=	$( "#fechaFin" ).val().split('/');
						var horaInicio	=	$( "#horaInicio" ).val();
						var horaFin		=	$( "#horaFin" ).val();
						var color		=	$( "#colorpicker" ).val().replace("#", "");

						//Creo el Evento
						var eventData;
						eventData = {
							id		:	id,
							title	:	$( "#title" ).val(),
							texto	:	$( "#texto" ).val(),
							color	:	color,
							start	:	fechaInicio[1]+'/'+fechaInicio[0]+'/'+fechaInicio[2]+' '+horaInicio,
							end		:	fechaFin[1]+'/'+fechaFin[0]+'/'+fechaFin[2]+' '+horaFin,
							constraint : $( "#restringido" ).val(),
						};

						$.ajax({
							type: "POST",
							cache:	false,						
							url: "/pedidos/agenda/ajax/setEvents.php",
							data: {	eventData : eventData,}, 
							beforeSend	: function () {
								$('#box_confirmacion').css({'display':'none'});
								$('#box_error').css({'display':'none'});
								$('#box_cargando').css({'display':'block'});
								$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
							},
							success: function(result){	
								if(result){
									if(result.replace("\n","") === '1'){ 
										$('#box_cargando').css({'display':'none'});	
										$('#box_error').css({'display':'none'});

										//Agrega el event a la AGENDA pero no lo guarda???
										event.title 	= eventData.title;
										event.color 	= eventData.color;	
										event.texto		= eventData.texto;																					
										var dateInicio	= new Date(fechaInicio[2]+'-'+fechaInicio[1]+'-'+fechaInicio[0]+' '+horaInicio+':00');	
										event.start		= dateInicio;
										var dateFin 	= new Date(fechaFin[2]+'-'+fechaFin[1]+'-'+fechaFin[0]+' '+horaFin+':00');
										event.end 		= dateFin;
										event.constraint= eventData.constraint;

										$('#calendar').fullCalendar('updateEvent', event);

										//Cierra el formulario							
										$("#dialogo").dialog("close");
									} else {
										$('#box_cargando').css({'display':'none'});	
										$('#box_error').css({'display':'block'});
										$("#msg_error").html(result);
									}
								}
							},
							error: function () {
								$('#box_cargando').css({'display':'none'});	
								$('#box_error').css({'display':'block'});
								$("#msg_error").html("Error en el proceso");	
							},	

						});
					},

					Eliminar : function () {						
						var id	=	event.id;
						$('#calendar').fullCalendar('removeEvents',event._id); 
						$.ajax({
							type: "POST",
							cache:	false,						
							url: "/pedidos/agenda/ajax/deleteEvents.php",	
							data: {	id : id,}
						});
						/******************/							
						$(this).dialog("close"); 
					},

					Cerrar : function () {	
						//deselecciona el evento si no se cargan datos.
						$('#calendar').fullCalendar('unselect');
						$(this).dialog("close"); 
					}
				},
			});

			$("#dialogo").dialog( "option", "title", "Evento");			

			var contenido	= 
				'<form class="fm_edit_iframe">'+
					'<input type="text" id="id" value="'+event.id+'" style="display:none">'+
					'<input type="text" id="restringido" value="'+event.constraint+'" style="display:none">'+

					'<div class="bloque_2"><input type="text" id="title" name="title" placeholder="Evento" value="'+event.title+'"></div><div class="bloque_4"><input type="color" id="colorpicker" value="#'+event.color+'"></div>'+

					'<div class="bloque_3"><label>Fecha Inicio</label><input id="fechaInicio" type="text" name="fechaInicio" placeholder="Fecha Inicio" value="'+event.start.format("MM/DD/YYYY")+'"></div><div class="bloque_4"><label>Hora</label><input id="horaInicio" type="text" class="time" maxlength="5" value="'+event.start.format("HH:mm")+'"/></div>'+	

					'<div class="bloque_3"><label>Fecha Fin</label><input id="fechaFin" type="text" name="fechaFin" placeholder="Fecha Fin" value="'+event.end.format("MM/DD/YYYY")+'"></div><div class="bloque_4"><label>Hora</label><input id="horaFin" type="text" class="time" maxlength="5" value="'+event.end.format("HH:mm")+'"/></div>'+

					'<div class="bloque_1"><label>Comentario</label><textarea id="texto" maxlength="250">'+event.texto+'</textarea></div>' +

					'<div class="bloque_1" align="center">'+
						'<fieldset id="box_error" class="msg_error">'+          
							'<div id="msg_error" align="center"></div>'+
						'</fieldset>'+

						'<fieldset id="box_cargando" class="msg_informacion">'+
							'<div id="msg_cargando" align="center"></div>'+      
						'</fieldset>'+ 
					'</div>'+

				'</form>'
			;

			$(contenido).appendTo('#dialogo');	

			$( "#fechaInicio" ).datepicker({ inline: true });	
			$( "#fechaInicio" ).datepicker( "option", "dateFormat", "dd/mm/yy" );		
			$( '#horaInicio').timepicker({  'timeFormat'	: 'H:i', });
			$( "#fechaFin" ).datepicker({ inline: true });	
			$( "#fechaFin" ).datepicker( "option", "dateFormat", "dd/mm/yy" );				
			$( '#horaFin').timepicker({ 'timeFormat'	: 'H:i', });					

		}	
	});		
});