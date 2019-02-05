<?php 
$cabecera	= '';
$pie		= '';
$empresa	= (!isset($empresa)) ? '' : $empresa;

switch($empresa){
	case '1':
		//NEO-FARMA
		$cabecera	= '<img src="https://www.neo-farma.com.ar/pedidos/images/mail/CabezalNeo.png" width="600" height="97"/>';
		$pie		= '<img src="https://www.neo-farma.com.ar/pedidos/images/mail/PieNeo.png" width="600" height="97"/>';
		
		$cabeceraPropuesta	= '<img src="https://www.neo-farma.com.ar/pedidos/images/logo/CabeceraNeoPropuesta.png"/>';
		$piePropuesta		= '<img src="https://www.neo-farma.com.ar/pedidos/images/logo/PiePedidoNeo.png"/>';
		
		$cabeceraPedido	= '<img src="https://www.neo-farma.com.ar/pedidos/images/logo/LogoNeoPedido.png" alt="Neo-farma"/>';
		$piePedido		= '<img src="https://www.neo-farma.com.ar/pedidos/images/pie/PieNeoPedidoConContacto.png" alt="Neo-farma"/>';
		
		break;
	case '3':
		//GEZZI
		$cabecera	= '<img src="https://www.neo-farma.com.ar/pedidos/images/mail/CabezalGezzi.png" width="600" height="97"/>';
		$pie		= '<img src="https://www.neo-farma.com.ar/pedidos/images/mail/PieGezzi.png" width="600" height="97"/>';
		
		$cabeceraPropuesta	= '<img src="https://www.neo-farma.com.ar/pedidos/images/logo/CabeceraGezziPropuesta.png"/>';
		$piePropuesta		= '<img src="https://www.neo-farma.com.ar/pedidos/images/logo/PiePedidoGezzi.png"/>';
		
		$cabeceraPedido	= '<img src="https://www.neo-farma.com.ar/pedidos/images/logo/LogoGezziPedido.png"/>';
		$piePedido		= '<img src="https://www.neo-farma.com.ar/pedidos/images/pie/PieGezziPedidoConContacto.png"/>';
		
		break;
	default:
		$cabecera	= '<img src="https://www.neo-farma.com.ar/pedidos/images/mail/CabezalNeo.png" width="600" height="97"/>';
		$pie		= '<img src="https://www.neo-farma.com.ar/pedidos/images/mail/PieNeo.png" width="600" height="97"/>';	
		
		$cabeceraPedido	= '<img src="https://www.neo-farma.com.ar/pedidos/images/logo/LogoNeoPedido.png" alt="Neo-farma"/>';
		$piePedido		= '<img src="https://www.neo-farma.com.ar/pedidos/images/pie/PieNeoPedidoConContacto.png" alt="Neo-farma"/>';
		
		break;
}

?>