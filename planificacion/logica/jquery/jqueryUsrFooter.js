//--------------------
var g_globalObject = new JsDatePick({
	useMode		: 2,
	isStripped	: false, //borde gris
	cellColorScheme:"beige", //bananasplit
	//yearsRange: new Array (1971,2100),
	//limitToToday: true,
	target		: "fecha_planif",
	dateFormat	: "%d-%M-%Y"
});

g_globalObject.setOnSelectedDelegate(function(){
	"use strict";
	var obj = g_globalObject.getSelectedDay();
	var fecha_planif	=	("0" + obj.day).slice (-2) + "-" + ("0" + obj.month).slice (-2) + "-" + obj.year;			
	document.getElementById("fecha_planif").value	= fecha_planif;
	var url = window.location.origin+'/pedidos/planificacion/index.php?fecha_planif=' + fecha_planif;
	document.location.href=url;			
});

var g_globalObject2 = new JsDatePick({
	useMode		: 2,
	isStripped	: false,
	cellColorScheme:"orange",
	target		: "fecha_destino",
	dateFormat	: "%d-%M-%Y"
});	

g_globalObject2.setOnSelectedDelegate(function(){
	"use strict";
	var obj = g_globalObject2.getSelectedDay();
	var fecha_destino		=	("0" + obj.day).slice (-2) + "-" + ("0" + obj.month).slice (-2) + "-" + obj.year;
	document.getElementById("fecha_destino").value	= fecha_destino;
});	
//------------------	