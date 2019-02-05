!function(e){
	"function"==typeof define&&define.amd?define(["jquery","moment"],e):"object"==typeof exports?module.exports=e(require("jquery"),require("moment")):e(jQuery,moment)}(function(e,a){!function(){!function(){var e=a.defineLocale("af",{months:"Januarie_Februarie_Maart_April_Mei_Junie_Julie_Augustus_September_Oktober_November_Desember".split("_"),monthsShort:"Jan_Feb_Mrt_Apr_Mei_Jun_Jul_Aug_Sep_Okt_Nov_Des".split("_"),weekdays:"Sondag_Maandag_Dinsdag_Woensdag_Donderdag_Vrydag_Saterdag".split("_"),weekdaysShort:"Son_Maa_Din_Woe_Don_Vry_Sat".split("_"),weekdaysMin:"So_Ma_Di_Wo_Do_Vr_Sa".split("_"),meridiemParse:/vm|nm/i,isPM:function(e){return/^nm$/i.test(e)},meridiem:function(e,a,t){return e<12?t?"vm":"VM":t?"nm":"NM"},longDateFormat:{LT:"HH:mm",LTS:"HH:mm:ss",L:"DD/MM/YYYY",LL:"D MMMM YYYY",LLL:"D MMMM YYYY HH:mm",LLLL:"dddd, D MMMM YYYY HH:mm"},calendar:{sameDay:"[Vandag om] LT",nextDay:"[Môre om] LT",nextWeek:"dddd [om] LT",lastDay:"[Gister om] LT",lastWeek:"[Laas] dddd [om] LT",sameElse:"L"},relativeTime:{future:"oor %s",past:"%s gelede",s:"'n paar sekondes",m:"'n minuut",mm:"%d minute",h:"'n uur",hh:"%d ure",d:"'n dag",dd:"%d dae",M:"'n maand",MM:"%d maande",y:"'n jaar",yy:"%d jaar"},ordinalParse:/\d{1,2}(ste|de)/,ordinal:function(e){return e+(1===e||8===e||e>=20?"ste":"de")},week:{dow:1,doy:4}});return e
}(),e.fullCalendar.datepickerLocale("af","af",{closeText:"Selekteer",prevText:"Vorige",nextText:"Volgende",currentText:"Vandag",monthNames:["Januarie","Februarie","Maart","April","Mei","Junie","Julie","Augustus","September","Oktober","November","Desember"],monthNamesShort:["Jan","Feb","Mrt","Apr","Mei","Jun","Jul","Aug","Sep","Okt","Nov","Des"],dayNames:["Sondag","Maandag","Dinsdag","Woensdag","Donderdag","Vrydag","Saterdag"],dayNamesShort:["Son","Maa","Din","Woe","Don","Vry","Sat"],dayNamesMin:["So","Ma","Di","Wo","Do","Vr","Sa"],weekHeader:"Wk",dateFormat:"dd/mm/yy",firstDay:1,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""}), e.fullCalendar.locale("af",{buttonText:{year:"Jaar",month:"Maand",week:"Week",day:"Dag",list:"Agenda"},allDayHtml:"Heeldag",eventLimitText:"Addisionele",noEventsMessage:"Daar is geen gebeurtenis"})}(),
		
	
	function(){!function(){
		var e="ene_feb_mar_abr_may_jun_jul_ago_sep_oct_nov_dic".split("_"),
		t="ene_feb_mar_abr_may_jun_jul_ago_sep_oct_nov_dic".split("_"),
		n=a.defineLocale("es",{
			months:"Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre".split("_"),
			monthsShort:function(a,n){
				return/-MMM-/.test(n)?t[a.month()]:e[a.month()]
			},
			monthsParseExact:!0,weekdays:"domingo_lunes_martes_mi\u00e9rcoles_jueves_viernes_s\u00e1bado".split("_"),
			weekdaysShort:"Dom_Lun_Mar_Mi\u00e9_Jue_Vie_S\u00e1b".split("_"),
			weekdaysMin:"do_lu_ma_mi_ju_vi_s\u00e1".split("_"),
			weekdaysParseExact:!0,
			longDateFormat:{
				LT:"H:mm",
				LTS:"H:mm:ss",
				L:"DD/MM/YYYY",
				LL:"D [de] MMMM [de] YYYY",
				LLL:"D [de] MMMM [de] YYYY H:mm",
				LLLL:"dddd, D [de] MMMM [de] YYYY H:mm"
			},
			calendar:{
				sameDay:function(){return"[hoy a la"+(1!==this.hours()?"s":"")+"] LT"},
				nextDay:function(){return"[mañana a la"+(1!==this.hours()?"s":"")+"] LT"},
				nextWeek:function(){return"dddd [a la"+(1!==this.hours()?"s":"")+"] LT"},
				lastDay:function(){return"[ayer a la"+(1!==this.hours()?"s":"")+"] LT"},
				lastWeek:function(){return"[el] dddd [pasado a la"+(1!==this.hours()?"s":"")+"] LT"},
				sameElse:"L"
			},
			relativeTime:{
				future:"en %s",
				past:"hace %s",
				s:"unos segundos",
				m:"un minuto",
				mm:"%d minutos",
				h:"una hora",
				hh:"%d horas",
				d:"un d\u00eda",
				dd:"%d d\u00edas",
				M:"un mes",
				MM:"%d meses",
				y:"un año",
				yy:"%d años"
			},
			
			ordinalParse:/\d{1,2}º/,
			ordinal:"%dº",
			week:{dow:1,doy:4}
		});
		
		return n
	}(),
	
	e.fullCalendar.datepickerLocale("es","es",{
		closeText:"Cerrar",
		prevText:"&#x3C;Ant",
		nextText:"Sig&#x3E;",
		currentText:"Hoy"
		,monthNames:["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"],
		monthNamesShort:["ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic"],
		dayNames:["domingo","lunes","martes","mi\u00e9rcoles","jueves","viernes","s\u00e1bado"],
		dayNamesShort:["dom","lun","mar","mi\u00e9","jue","vie","s\u00e1b"],
		dayNamesMin:["D","L","M","X","J","V","S"],
		weekHeader:"Sm",
		dateFormat:"dd/mm/yy",
		firstDay:1,
		isRTL:!1,
		showMonthAfterYear:!1,
		yearSuffix:""
	}),
	
	e.fullCalendar.locale("es",{
		buttonText:{
			month:"Mes",
			week:"Semana",
			day:"D\u00eda",
			list:"Agenda"
		},
		allDayHtml:"Todo<br/>el d\u00eda",
		eventLimitText:"m\u00e1s",
		noEventsMessage:"No hay eventos para mostrar"
	})
}(),



//Ésto uñltimno vale para todos los idiomas
a.locale("en"),e.fullCalendar.locale("en"),e.datepicker&&e.datepicker.setDefaults(e.datepicker.regional[""])});