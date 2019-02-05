		 
        <div id="banner">  
        	<div><img src="/pedidos/images/fondos/fondo-home.jpg"/></div>
            <div><img src="/pedidos/images/fondos/fondo-arceligasol.jpg"/></div> 
            <div><img src="/pedidos/images/fondos/fondo-manzan.jpg"/></div> 
            <div><img src="/pedidos/images/fondos/fondo-pulmosan.jpg"/></div> 
            <div><img src="/pedidos/images/fondos/fondo-salicrem.jpg"/></div> 
            <div><img src="/pedidos/images/fondos/fondo-sinamida.jpg"/></div> 
        </div> 
  
		<style>
			/*#rotadordebanners {
			*/#banner {
				position: relative;			
				margin-top:-20px;
				height: 340px;
				/*
				width: 655px;
				height: 170px;*/
				background-color:transparent;
			}
			
			/*#rotadordebanners > div {*/
			#banner > div {	
				position: absolute;				
			}
				
			/*#rotadordebanners img {*/
			#banner img {	
				-webkit-background-size: cover;
               	-moz-background-size: cover;
               	-o-background-size: cover;
               	background-size: cover;
               	height: 340px;
               	width: 100%;
               	border-radius: 0px 0px 10px 10px;		
				background:none; 				
			}
			
			
			
			@media screen and (max-width:800px ) {
				#banner {
					height: 300px;
				}
				
				#banner img {					
					height: auto;
				}
				
				#banner > div {
					margin-top:40px;
				}
			}
			
			@media screen and (max-width:600px ) {
				#banner {
					height: 240px;
				}
			}
			
			@media screen and (max-width:400px ) {
				#banner {
					height: 180px;
				}
			}
		</style>
        
		<script src="//code.jquery.com/jquery-latest.js"></script>
  		<script>
			(function() {
				$("#banner > div:gt(0)").hide();
				
				setInterval(function() { 
				  $('#banner > div:first')
					.fadeOut(3000)
					.next()
					.fadeIn(3000)
					.end()
					.appendTo('#banner');
				},  10000);
			})();
		</script>