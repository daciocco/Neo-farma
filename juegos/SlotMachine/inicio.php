<link rel="stylesheet" type="text/css" href="/pedidos/juegos/SlotMachine/css/frameworks.css" media="screen" />
<script type="text/javascript" src="/pedidos/juegos/SlotMachine/jquery/jquery.ventana.js"></script>

<style>
	#boton-inicio:active {
		content:url(https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/icons/BotonInicioPress.png);
	}
	#boton-pare:active {
		content:url(https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/icons/BotonParePress.png);
	}
	
	#slotmachine {
		overflow:auto;
		position:fixed;
		display: none;
		z-index: 1;    
		background-color:#5c788e;
	}
	
	#juego {
		width:100%;
		height:100%;
		z-index: 2;	
		display: none;
		position: absolute; 
	}
	
	#barra_superior {
		width:100%;
		height:7%;
		background:#333;
	}
	
	#cuerpo-juego {
		width:100%;
		height:93%;
		background:#5c788e;
	}
	
	#cerrar-slotmachine {
		text-decoration:none;
	}
	
	#barra-izq {
		float:left;
		width:80%;height:100%;
	}
	
	#cuerpo-slot {
		height:65%; 
		width:100%;
		clear:left; 
		float:left; 
		background-image:url(https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/fondo.jpg); 
		background-size:cover;
	}
	
	#barra-img-slot {
	height:35%; width:100%; float:left;
	}
	
	#img-slot {
	height:99%; width:100%; float:left;
	}
	
	.imagen-slot {
	margin-left:5%;
	}
	
	#barra-der {
		float:left; width:20%; height:100%; background:#FFF;
	}
	
	#boton1 {
		float:left; width:100%; height:70%; margin-top:10%;
	}
	
	#boton2 {
		float:left; width:100%; height:20%;
	}
</style>

<div id="slotmachine" align="center"> <!-- marco -->
    <div id="juego"> 
    
    	<div id="pyro" class="pyro">
			<div class="before"></div>
			<div class="after"></div>
		</div>
   	
    	<div id="barra_superior" align="right">
        	<a id="cerrar-slotmachine" href="#" height="100%"><img src="https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/icons/icono-close-bco.png" style="height:100%;" title="Cerrar"/></a>
        </div>
        
		<div id="cuerpo-juego">                	
            <div id="barra-izq">            
             	<div id="cuerpo-slot"></div>                
                <div id="barra-img-slot">                                    
                    <div id="img-slot" align="center">
                        <img id="imagen1" class="imagen-slot" src="https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/0.jpg" height="80%" onclick="detener1()"/>
                        <img id="imagen2" class="imagen-slot" src="https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/1.jpg" height="80%" onclick="detener2()"/>
                        <img id="imagen3" class="imagen-slot" src="https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/0.jpg" height="80%" onclick="detener3()"/>
                    </div>                                    	
                </div>
            </div>
            
            <div id="barra-der" align="center">
            	<div id="boton1" align="center">
            	 	<img id="boton-inicio" src="https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/icons/BotonInicio.png" width="50%" onclick="javascript:dac_inicio()">
                 </div>
                <div id="boton2" align="center">
                 	<img id="boton-pare" src="https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/icons/BotonPare.png" width="50%" onclick="javascript:dac_pare()">
                 </div>
            </div>            
        </div> <!-- Fin cuerpo -->   
    </div><!-- Fin juego -->
</div> <!-- Fin marco -->

<script language="javascript">	
	var ctrl	= 0;	
	var iniciado = 0;
	function dac_cambiar() {
		if(ctrl == 1){	
			var images 		= ["1.jpg", "2.jpg", "3.jpg", "4.jpg", "5.jpg", "6.jpg", "7.jpg", "8.jpg"];	
			var aleatorio 	=	Math.round(Math.random()*(images.length - 1));
			var direccion	=	"https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/"+images[aleatorio];
			var aleatorio 	=	Math.round(Math.random()*(images.length - 1));
			var direccion2	=	"https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/"+images[aleatorio];
			var aleatorio 	=	Math.round(Math.random()*(images.length - 1));
			var direccion3	=	"https://neo-farma.com.ar/pedidos/juegos/SlotMachine/images/"+images[aleatorio];
			if(document.getElementById("imagen1")){document.getElementById("imagen1").src	=	direccion;	}
			if(document.getElementById("imagen2")){document.getElementById("imagen2").src	=	direccion2;	}
			if(document.getElementById("imagen3")){document.getElementById("imagen3").src	=	direccion3;	}
			
			if(document.getElementById("detener1") && document.getElementById("detener2") && document.getElementById("detener3")){
				if ((document.getElementById("detener1").src == document.getElementById("detener2").src) && (document.getElementById("detener1").src == document.getElementById("detener3").src)){
					//Ha ganado	
					document.getElementById("pyro").style.display = "inline";
					//alert("Ha ganado");
					dac_pare();			
				} else {
					alert("Siga participando");
					document.getElementById("detener1").id = "imagen1";
					document.getElementById("detener2").id = "imagen2";
					document.getElementById("detener3").id = "imagen3";
				}
			}
		}
	}
	
	function dac_inicio() {
		if(document.getElementById("detener1")){document.getElementById("detener1").id	=	"imagen1";	}
		if(document.getElementById("detener2")){document.getElementById("detener2").id	=	"imagen2";	}
		if(document.getElementById("detener3")){document.getElementById("detener3").id	=	"imagen3";	}				
		if(iniciado == 0){
			setInterval("dac_cambiar()", 550);			
			iniciado = 1;
		}
		ctrl = 1;
		document.getElementById("pyro").style.display = "none";
	}
	
	function dac_pare() {	
		ctrl = 0;
	}
	
	function detener1 () {
		var dir1 = document.getElementById("imagen1").value;
		document.getElementById("imagen1").id = "detener1";
	}
	function detener2 () {
		var dir2 = document.getElementById("imagen2").value;
		document.getElementById("imagen2").id = "detener2";
	}
	function detener3 () {
		var dir3 = document.getElementById("imagen3").value;
		document.getElementById("imagen3").id = "detener3";	
	}
</script> 