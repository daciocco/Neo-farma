<?php
	$_CtrlCant		=	'';
	$_CtrlPSLUnit	=	'';	
	$_CtrlDescPSL	=	'';
	$_CtrlImpNT		=	'';
	$_Estado		=	'';
	$_CtrlCantArts 	= 	array();
	$_cantPedidas	=	0;
	$_cantUnidades	=	0;	
	$_cont_art		=	0;
	$_noControlar	=	0;
		
	if(!empty($_idart)){	
		$_detallestransfer	= DataManager::getTransfersPedido(NULL, NULL, NULL, NULL, NULL, NULL, $_liqTransfer); //DataManager::getDetallePedidoTransfer($_liqTransfer);
		if ($_detallestransfer) {
			foreach ($_detallestransfer as $j => $_dettrans){
				$_dettrans	=	$_detallestransfer[$j];
				$_ptiddrog	=	$_dettrans['ptiddrogueria'];
				$_ptidart	=	$_dettrans['ptidart'];
				$_ptunidades=	$_dettrans['ptunidades'];
					
				//Cantidad Pedida del TRANSFER Original del Vendedor				
				if($_ptidart == $_idart){
					$_cantPedidas 	= 	$_ptunidades;
				}
			}
			
			//Si la droguería del pedido transfer es diferente a la droguería de la liquidación
			if($_ptiddrog == $_drogid) {
				//******************************//
				//#1 DIFERENCIA DE CANTIDADES	//
				//******************************//
				$_liqtransfers	=	DataManager::getDetalleLiquidacionTransfer($_liqID, $_drogid, $_liqTransfer, $_liqean); //$_liqFecha
				if($_liqtransfers){
					foreach ($_liqtransfers as $j => $_liqtrans){
						$_liqtrans	=	$_liqtransfers[$j];
						$_liqtID	=	$_liqtrans['liqid'];
						$_liqtcant	=	$_liqtrans['liqcant'];
						
						$_cont_art		= 	$_cont_art+1;
						//Suma las unidades totales de dicho transfer en liquidaciones
						$_cantUnidades	+=	$_liqtcant;
					}								
				}
				
				// DIFERENCIA entre (Unidades LIQUIDADAS + actual) y Unidades PEDIDAS
				if(($_cantUnidades + $_liqcant) != $_cantPedidas){
					$_CtrlCant	=	($_cantUnidades + $_liqcant) - $_cantPedidas; //"Diferencia Cant : ".
				}
				
				//Si el artículo se repite en una liquidación y un mismo número de transfer
				if($_cont_art > 0){
					$_CtrlCant	= "*".$_CtrlCant;
				}				
					
				//**************************//
				//#2 Control "PSL Unitario" == a PrecioDrog de la Bonificacion de ese mes
				//**************************//
				$_bonifarticulo	=	DataManager::getBonificacionArticulo($_idart, $_mes, $_anio);
				$_preciodrog	=	$_bonifarticulo[0]['bonifpreciodrog'];
				$_bonifiva		=	$_bonifarticulo[0]['bonifiva'];
				if(empty($_preciodrog)){			$_CtrlPSLUnit	=	"#ErrorPSLUnit </br>";
				} else {
					//SI EL Producto es Cosmético, le agrego un 21%	
					if($_bonifiva != 0) {
						$_preciodrog	=	round(($_preciodrog * 1.21),2);	
					}
					
					if($_preciodrog != $_liqunit){				
						//Si la diferencia es mayor o menos al 2% que la muestre, sino que la discrimine	
						$_porc_dif	=	100 - (($_liqunit * 100) / $_preciodrog);
						if ($_porc_dif < -2 || $_porc_dif > 2) {
							$_CtrlPSLUnit	=	"$_preciodrog";
						}
					}
					
					//SI EL Producto es Cosmético, le vuelvo a retirar un 21%	
					/*if($_bonifiva != 0) {
						$_preciodrog	=	round(($_preciodrog / 1.21),2);	
					}*/
					
					if($_drogidemp == 3) {
						$_preciodrog	=	round(($_preciodrog / 1.21),2); //$_preciodrog - ($_preciodrog * 0.21);
					}	
					
				}
									
				//***********************************//
				//#3 Control "% Desc PSL" con "% Desc" del ABM
				//***********************************//
				$_abmart		=	DataManager::getDetalleArticuloAbm($_mes, $_anio, $_drogid, $_idart, 'TL');
				$_abmdesc		=	$_abmart[0]['abmdesc'];
				$_abmdifcomp	=	$_abmart[0]['abmdifcomp']; //para el punto #3
				if(empty($_abmdesc)){				$_CtrlDescPSL	=	"#ErrorDescPSL </br>";
				} else {
					if($_liqdesc < $_abmdesc){		$_CtrlDescPSL	=	"< $_abmdesc %</br>";
					} else{
						if($_liqdesc > $_abmdesc){	$_CtrlDescPSL	=	"> $_abmdesc %</br>";
						}
					}
				}		
													
				//***************************//
				//#4 Control  "Importe NC" == Cantidad * PSL Unitario * (Desc PSL / 100)	
				//**************************//
				//Si en TABLA BONIFICACION el ART "NO" TIENE % IVA, (Y la EMPRESA es 3), le resto el 21%
				/*if($_drogidemp == 3) {
					$_preciodrog	=	$_preciodrog / 1.21; //$_preciodrog - ($_preciodrog * 0.21);
				}	*/
				
				//$_liqunit PRECIO UNITARIO DE LA BONIFICACION
				$_ImporteNC		=	round(($_liqcant * $_preciodrog * (($_abmdifcomp) / 100)), 2);
				
				$_CtrlTotalNC	+=	$_ImporteNC;
				
				//Si diferencia es > o < a 2%. la muestre	
				if($_ImporteNC){
					$_porc_difNC	=	100 - (($_liqimportenc * 100) / $_ImporteNC);				
					if ($_porc_difNC < -2 || $_porc_difNC > 2) {
						$_CtrlImpNT	=	$_ImporteNC;
					}
				} else {
					$_CtrlImpNT = "#Error";	
				}
				
				//ESTADO DE LA LIQUIDACION
				//**********************//
				// CONTROLA POR ATÍCULO // las cantidades.
				//**********************//
				if($_liqactiva == 1){
					$_Estado .= "Liquidado";
				} else {		
					if($_cantPedidas > ($_cantUnidades + $_liqcant)){
						$_Estado .= "LP"; //Liquidado Parcial
					} elseif($_cantPedidas == ($_cantUnidades + $_liqcant)) {
						$_Estado .= "LT"; //Liquidado Total
					} else { //$_cantPedidas < ($_cantUnidades + $_liqcant)
						$_Estado .= "LE"; //Liquidado Excedente 
					}
				}
			} else {
				$_Estado	= "#ErrorDrog $_ptiddrog";
			}
		} else { //Si el transfer no existe!
			$_Estado	= "#ErrorNroTrans";
		}
	} else {
		$_Estado	.=	"#ErrorEAN";
	}
	
?>