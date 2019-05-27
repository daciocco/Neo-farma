<div class="box_down">
	<div class="barra">
       	<div class="bloque_5">
			<h1>Registro de Movimientos</h1>                	
        </div>
        <hr>
	</div> <!-- Fin barra -->
  
  	<div class="lista_super"> 		
		<div id='tablaMovimientos'></div>
	</div> <!-- Fin listar -->
  
   
    <!--div class="lista_super">
        <table>
            <thead>
                <tr>
                    <th scope="colgroup" width="5%">ID</th>
                    <th scope="colgroup" width="15%">Origen</th>
                    <th scope="colgroup" width="10%">Id Origen</th>
                    <th scope="colgroup" width="10%">Transacción</th>
                    <th scope="colgroup" width="20%">Operación</th>
                    <th scope="colgroup" width="20%">Fecha</th>
                    <th scope="colgroup" width="20%">Usuario</th>
                </tr>
            </thead>
            
            <?php 	
            /*$movimientos	= DataManager::getMovimientos(1, 20);
			if($movimientos){
				foreach ($movimientos as $k => $mov) {
					$id			= $mov['movid'];
					$operacion	= $mov['movoperacion'];
					$transaccion= $mov['movtransaccion'];
					$origen		= $mov['movorigen'];
					$origenId	= $mov['movorigenid'];
					$fecha		= $mov['movfecha'];
					$usuario	= $mov['movusrid'];

					echo sprintf("<tr class=\"%s\">", ((($k % 2) == 0)? "par" : "impar"));
					
					echo sprintf("<td height=\"15\">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $id, $origen, $origenId, $transaccion, $operacion, $fecha, $usuario);
					echo sprintf("</tr>");	

				}
			}*/ ?>
        </table>
	</div-->
	
	<div class="barra">
		<div class="bloque_1" style="text-align: right;"> 
			<!-- paginador de jquery -->   
			<paginator></paginator>
			<input id="totalRows" hidden="hidden">
		</div>
		<hr>
	</div> <!-- Fin barra -->
	
</div>


<script src="/pedidos/js/jquery/jquery.paging.js"></script>	
<script>
	//#######################################
	// 			PAGING DACIOCCO
	//#######################################
	//Funcion que devuelve cantidad de Filas
	function dac_filas(callback) {
		/*var empresa = $('#empselect').val(),
			tipo	= $('#tiposelect').val(),
			activos	= $('#actselect').val();*/
		$.ajax({
			type	: "POST",
			cache	: false,						
			url		: '/pedidos/movimientos/logica/ajax/getFilasMovimientos.php',
			/*data:	{	empselect	:	empresa,
						tiposelect	: 	tipo,
						actselect	:	activos
					},*/
			success	: function(totalRows){
				if(totalRows){
					$("#totalRows").val(totalRows);
					callback(totalRows);
				}
			},						
		});	
	}
	
	//---------------------------------------
	//Cantidad de filas por página
	var rows = 25;		
	//Setea datos de acceso a datos vía AJAX
	var data = { //los indices deben ser iguales a los id de los select
		/*empselect	:	$('#empselect').val(),
		actselect	: 	$('#actselect').val(),
		tiposelect	:	$('#tiposelect').val()*/
	};
	var url 		= 'logica/ajax/getMovimientos.php';
	var tableName 	= 'tablaMovimientos';
	var selectChange= []; //'tiposelect', 'empselect', 'actselect'
	//---------------------------------------
	//Llamada a función generadora del paginado
	dac_filas(function(totalRows) {
		$("paginator").paging(rows, totalRows, data, url, tableName, selectChange);
	});	
</script>