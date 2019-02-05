function initialize(data) {
	"use strict";
	$.ajax({
		type 	: 	'POST',
		cache	:	false,
		data	: 	data,
		url 	: 	'/pedidos/zonas/logica/ajax/getCuentaZonas.php',
		beforeSend: function () {
			$('#box_confirmacion').css({'display':'none'});
			$('#box_error').css({'display':'none'});
			$('#box_cargando').css({'display':'block'});					
			$("#msg_cargando").html('<img src="/pedidos/images/gif/loading.gif" height="24" style="margin-right:10px;" />Cargando... espere por favor!');
			$("#btnSendTransfer").hide(100);
		},
		success : 	function (resultado) {
			if (resultado){
				var marcadores 		= [];	
				var pathCoordinates = [];
				var json 			= eval(resultado);
				for(i = 0; i < json.length; i++){
					marcadores.push([json[i].datos, json[i].latitud, json[i].longitud, json[i].cuenta, json[i].color, json[i].imagen, json[i].id, json[i].direccion, json[i].idcuenta]);
				}
				
				var map = new google.maps.Map(document.getElementById('mapa'), {
					zoom: 4,
					center: new google.maps.LatLng(-34, -64),
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				
				//-------------------------
				var directionsService = new google.maps.DirectionsService;
				var directionsDisplay = new google.maps.DirectionsRenderer;
				//-------------------------
				var infowindow = new google.maps.InfoWindow();
				var marker, i;
				
				//---------
				//List Records
				var listRecords = [];
				listRecords.headStart 	= '<table id="tblTablaCta" border="0" width="100%" align="center">';
				listRecords.titles 		= '<thead><tr align="left"><th>Id</th><th>Nombre</th></tr></thead>';
				listRecords.headEnd 	= '<tbody>';	
				listRecords.foot 		= '</tbody></table>';
				listRecords.records		= '';				
				//---------
				
				for(i = 0; i < marcadores.length; i++) {  
					/*var latitud		= marcadores[i][1];
					var longitud	= marcadores[i][2];*/						
					marker = new google.maps.Marker({
						position	: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
						icon		: 'https://www.neo-farma.com.ar/pedidos/images/icons/'+marcadores[i][5],//imagen marcador
						id 			: marcadores[i][6],
						title		: marcadores[i][3],
						map			: map,
						longitud	: marcadores[i][2],
						latitud		: marcadores[i][1],
						direccion 	: marcadores[i][7],
						contenido	: marcadores[i][0],
						idcuenta	: marcadores[i][8],
						//label: 'titulo junto al marcador',
						/*icon: { //define forma de marcado
							path: google.maps.SymbolPath.CIRCLE,
							scale: 4, //tamaño
							strokeColor: 'black',//'#ea4335', //color del borde
							strokeWeight: 1, //grosor del borde
							fillColor: marcadores[i][4], //color de relleno
							fillOpacity:1// opacidad del relleno
						},*/
					});

					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infowindow.setContent(marker.contenido);
							infowindow.open(map, marker);
							//map.setCenter(marker.getPosition());
						};
					})(marker, i));

					google.maps.event.addListener(marker, 'dblclick', (function(marker, i) {
						return function() {							
							//cambia icono del marcador
							var marcado 	= 'https://www.neo-farma.com.ar/pedidos/images/icons/marcador.png';
							var desmarcado 	= 'https://www.neo-farma.com.ar/pedidos/images/icons/'+marcadores[i][5];
							if(marker.icon === marcado){
								marker.setIcon(desmarcado);
								//Quitar elemento con index marker.id
								var result = $.grep(pathCoordinates, function(e){ 
									 return e.id !== marker.id; 
								});
								pathCoordinates = result;
								
								//-------------------
								$('#waypoints2 option[value="'+marker.direccion+'"]').remove();
								//-----------------
							} else {
								pathCoordinates.push({
									id	: marker.id,
									dir : marker.direccion,
                                	lat : marker.latitud,
                                    lng : marker.longitud
                                });
								marker.setIcon(marcado);
								
								dac_cargarDatosCuenta(marker.idcuenta, marker.title, marker.direccion);
								//-----------------
								$('#waypoints2').append('<option id="'+marker.id+'" value="'+marker.direccion+'" >'+marker.direccion+'</option>');								
								$('#'+marker.id).dblclick(function () { 
									this.remove();
									
									marker.setIcon(desmarcado);
									
									//Quita el registro de la cuenta
									dac_deleteRecord(marker.idcuenta);							
									//Quitar elemento con index marker.id
									var result = $.grep(pathCoordinates, function(e){ 
										return e.id !== marker.id; 
									});
									pathCoordinates = result;
									
									calculateAndDisplayRoute(directionsService, directionsDisplay);
								});
								//---------------
							}
							
							calculateAndDisplayRoute(directionsService, directionsDisplay);
							
							//calcular ruteo							
							function calculateAndDisplayRoute(directionsService, directionsDisplay) {	
								var waypts = [];
								if(pathCoordinates.length){
									for (var j = 0; j < pathCoordinates.length; j++) {
										if(j !== 0 && j !== (pathCoordinates.length-1)){
											waypts.push({
											  location: pathCoordinates[j].dir,
											  stopover: true
											});
										}
									}
									directionsService.route({
										origin				: pathCoordinates[0].dir,
										destination			: pathCoordinates[(pathCoordinates.length - 1)].dir,
										waypoints			: waypts,
										optimizeWaypoints	: true,
										travelMode			: 'DRIVING'
									}, function(response, status) {
										if (status === 'OK') {
											directionsDisplay.setDirections(response);
											var route 		= response.routes[0];
											var summaryPanel= $('#waypoints');
											summaryPanel.html();
											// For each route, display summary information.
											var routes = '';
											for (var j = 0; j < route.legs.length; j++) {
												var routeSegment = j + 1;
												routes += '<div class="bloque_1"><b>Segmento ' + routeSegment + ': ' + route.legs[j].distance.text + '</b></div>';
												routes += '<div class="bloque_5"> <b>Desde: </b>' + route.legs[j].start_address+ '</b></div>';
												routes += '<div class="bloque_5"> <b>Hasta: </b>' + route.legs[j].end_address+ '</div>';
												routes += '<hr style="border-bottom: 1px solid #117db6;">';
												summaryPanel.html(routes);
											}
										} else {
											$('#box_cargando').css({'display':'none'});
											$('#box_error').css({'display':'block'});
											$("#msg_error").html('La consulta de dirección ha fallado ' + status);
										}
									});
									directionsDisplay.setMap(map);
								} else {
									var routes = '';
									var summaryPanel= $('#waypoints');
									summaryPanel.html(routes);
								}
							}
							//actualizar en mapa
							infowindow.open(map, marker);
						};
					})(marker, i));	
					//Add list Records
					var clase = '';
					if((i % 2) === 0) { clase="par"; } else { clase="impar";}
					listRecords.records += "<tr id="+marker.id+" class="+clase+" title='"+marker.direccion+"' style=\"cursor:pointer\"><td>"+marker.idcuenta+"</td><td>"+marker.title+"</td></tr>";
				}
				
				//Show ListRecords
				$("#tablacuenta").empty();				$("#tablacuenta").html(listRecords.headStart+listRecords.titles+listRecords.headEnd+listRecords.records+listRecords.foot);
				//----------------				
				$('#tblTablaCta').on('click', 'tbody tr', function(event) {
					var id = $(this)[0].id; //id de la cuenta cliqueada			
					//console.log(marcadores[i][6]);
					for(i = 0; i < marcadores.length; i++) {  
						if(marcadores[i][6] === id){
							marker = new google.maps.Marker({
								position	: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
								contenido	: marcadores[i][0],
							});
							map.setCenter(marker.getPosition());
							//map.setZoom(16);
							infowindow.setContent(marker.contenido);
						}
					}
			  	});
				//----------
				$('#box_cargando').css({'display':'none'});
				$('#box_confirmacion').css({'display':'block'});
				$("#msg_confirmacion").html("Se han registrado "+json.length+" cuentas.");
			} else {
				$('#box_cargando').css({'display':'none'});
				$('#box_error').css({'display':'block'});
				$("#msg_error").html("No se encuentra registros con los datos indicados.");
			}
		},
		error: function () {
			$('#box_cargando').css({'display':'none'});
			$('#box_error').css({'display':'block'});
			$("#msg_error").html("No se encuentra registros con los datos indicados.");
		}								
	});
}

function dac_cargarDatosCuenta(idCuenta, nombre, direccion){
	"use strict";	
	var campo = '<div id="reg'+idCuenta+'"><div class="bloque_6"><label><b>Cuenta:</b></label> '+idCuenta+' </div><div class="bloque_4"><label><b>Razón Social:	</b></label> '+nombre+'</div><div class="bloque_1"><label><b>Dirección:</b></label> '+direccion+'</div><hr style="border-bottom: 1px solid #117db6;"></div>';	
	$("#listCuentas").before(campo);
}

function dac_deleteRecord(idCuenta){
	"use strict";
	console.log(idCuenta);
	var elemento = document.getElementById('reg'+idCuenta);
	elemento.parentNode.removeChild(elemento);
}