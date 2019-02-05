/**
 * @license jQuery paging plugin v1.1.1 01/01/2019
 * La humildad es madre de la modestia
 * Copyright (c) 2019, Cioccolanti Diego A.
 **/
(function($, window, undefined) {
	"use strict";
	$.fn.paging = function(rows, totalRows, data, url, tableName, selectChange) {
		//Construye el paginador
		var PAGINATOR= $('paginator');
		PAGINATOR.empty();
		var tappage = '<select id="tabpage"><option></option> </select>',
			first	= '<pagFirst><img id="goFirst" class="paginadores" src="../images/icons/icono-first.png"/></pagFirst>',
			prev	= '<pagPrev><img id="goPrev" class="paginadores" src="../images/icons/icono-previous.png"/></pagPrev>',
			next	= '<pagNext><img id="goNext" class="paginadores" src="../images/icons/icono-next.png" /></pagNext>',
			last	= '<pagLast><img id="goLast" class="paginadores" src="../images/icons/icono-last.png" /></pagLast>',
			stat	= '<span id="stat"></span>';
		PAGINATOR.append(tappage);
		PAGINATOR.append(first);
		PAGINATOR.append(prev);
		PAGINATOR.append(stat);
		PAGINATOR.append(next);
		PAGINATOR.append(last);
		var TAPPAGE = $('#tabpage'),
			FIRST	= $('pagFirst'),
			PREV	= $('pagPrev'),
			NEXT 	= $('pagNext'),
			LAST 	= $('pagLast'),
			STAT 	= $('#stat');
		FIRST.click(function() { setPage(rows, 'first'); });
		PREV.click(function() { setPage(rows, 'prev'); });
		NEXT.click(function() { setPage(rows, 'next');  });
		LAST.click(function() { setPage(rows, 'last'); });

		if(selectChange){
			for(var i=0; i < selectChange.length; i++){
				$("#"+selectChange[i]).change(function(select) {
					var selected = select.currentTarget.id; 
					data[selected] = $("#"+select.currentTarget.id ).val();
					//------------------					
					dac_filas(function(totalRows) {
						$("#totalRows").val(totalRows);
						setPage(rows, 'first');
					});						
				});
			}	
		}

		function dac_paginar(page, rows, totalRows, paginas){
			//-----------------------	
			//edita select paginador inicial
			$("#tabpage option").remove();
			var selected = '';
			for(var k=1; k <= paginas; k++){
				selected = (page === k) ? 'selected' : '';
				TAPPAGE.append("<option value=" + k + " " + selected + ">" + k + "</option>");		
			}
			//------------------------			
			var data 		= [],
				inicial 	= 0,
				final 		= 0;			
			for(var q = 0; q <= totalRows; q++) {
				inicial = (page * rows) - (rows - 1);
				final = page * rows;
				final = (final > totalRows) ? totalRows : final;				
				data[q] = '(' + inicial + ' - ' + final + ')';
			}	
			STAT.html(data[page]);
		}			
		TAPPAGE.change(function() { setPage(rows, this.value);});		
		TAPPAGE.select(function() { setPage(rows, 'fill');	});	

		function dac_selectRegistros(data, url, tableName){
			$.ajax({
				type 	: 'POST',
				url		: url,
				data	: data,
				success	: function(result) {
					var tabla = result;	
					$( "#"+tableName ).html(tabla);
				},
			});
		}

		//setea número de páginas
		function setPage(rows, type) {	
			var page 	= parseInt(TAPPAGE.val());
			page 		= (undefined === page || page < 0) ? -1 : page;		
			var totalRows = parseInt($("#totalRows").val());	
			if(totalRows === 0){ PAGINATOR.hide();
			} else { 
				PAGINATOR.show();					
				var paginas		= totalRows / rows;
				paginas 		= Math.ceil(paginas);
				//----------------
				switch (type) {
					case 'first':					
						page = 1;
						PREV.hide(); LAST.show();
						FIRST.hide(); NEXT.show();
						break;
					case 'prev':
						page = page - 1;
						page = (page <= 0) ? 1 : page;
						if(page === 1){FIRST.hide(); PREV.hide();}
						LAST.show(); NEXT.show();
						break;
					case 'next':
						page = page + 1;
						page = (page > paginas) ? paginas : page;
						if(page === paginas){LAST.hide(); NEXT.hide();}
						FIRST.show(); PREV.show();
						break;
					case 'last':					
						page = paginas;
						NEXT.hide(); LAST.hide();
						FIRST.show(); PREV.show();
						break;
					case 'fill': //llenar	
						setPage(rows, 'first');
						break;
				}	
				if(paginas === 1){
					FIRST.hide();
					PREV.hide();
					NEXT.hide();
					LAST.hide();
					TAPPAGE.hide();
				} else {
					TAPPAGE.show();
				}					
				TAPPAGE.val(page);
				//-------------					
				dac_paginar(page, rows, totalRows, paginas);					
				for(var k = 0; k < data.length; k++) {
					$("#"+data[k]).val(); 
				}	
				//---------------
			}
			data.pag	= page;
			data.rows	= rows;
			dac_selectRegistros(data, url, tableName);
		}			
		setPage(rows, 'first');
	};
}(jQuery, this));