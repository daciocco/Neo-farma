<?php
	$_CtrlPSLUnit	=	'';	
	$_CtrlDescPSL	=	'';
	$_CtrlImpNT		=	'';
	$_Estado		=	'';
	
	if(!empty($_idart)){	
		//**************************//
		//#2 Control "PSL Unitario" == a PrecioDrog de la Bonificacion de ese mes
		//**************************//
		$_bonifarticulo	=	DataManager::getBonificacionArticulo($_idart, $_mes, $_anio);
		$_preciodrog	=	$_bonifarticulo[0]['bonifpreciodrog'];
		$_bonifiva		=	$_bonifarticulo[0]['bonifiva'];
		if(empty($_preciodrog)){			
			$_CtrlPSLUnit	=	"#ErrorPSLUnit </br>";
		} else {	
			if($_preciodrog != $_liqunit){				
				//Si diferencia es > o < a 2%. la muestre	
				$_porc_dif	=	100 - (($_liqunit * 100) / $_preciodrog);
				if ($_porc_dif < -2 || $_porc_dif > 2) {
					$_CtrlPSLUnit	=	"$_preciodrog";
				}
			}
			
			//SI EL Producto es Cosmético, le vuelvo a retirar un 21%	
			if($_bonifiva != 0) {
				$_preciodrog	=	round(($_preciodrog / 1.21),2);	
			}
			
			if($_drogidemp == 3) {
				$_preciodrog	=	round(($_preciodrog / 1.21),2); //$_preciodrog - ($_preciodrog * 0.21);
			}	
		}
							
		//***********************************//
		//#3 Control "% Desc PSL" con "% Desc" del ABM
		//***********************************//
		$_abmart		=	DataManager::getDetalleArticuloAbm($_mes, $_anio, $_drogid, $_idart, 'TD');
		if(!$_abmart){
			echo "IMPORTANTE: No hay ABM cargado para la fecha que intenta importar."; exit;
		}
		
		$_abmdesc		=	$_abmart[0]['abmdesc'];
		$_abmdifcomp	=	$_abmart[0]['abmdifcomp']; //para el punto #4
		if(empty($_abmdesc)){				$_CtrlDescPSL	=	"#ErrorDescPSL </br>";
		} else {
			//SI EL Producto es Cosmético, le vuelvo a retirar un 21%	
			/*if($_bonifiva != 0) {
				$_preciodrog	=	$_preciodrog / 1.21;	
			}*/
					
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
			$_preciodrog	=	$_preciodrog / 1.21; //$_preciodrog - ($_preciodrog * 0.21);//precio/1.21
		}*/
		
		//$_liqunit PRECIO UNITARIO DE LA BONIFICACION
		$_ImporteNC		=	round(($_liqcant * $_preciodrog * (($_abmdifcomp) / 100)), 2);
		
		$_CtrlTotalNC	+=	$_ImporteNC;
		
		//Si diferencia es > o < a 2%. la muestre	
		//if($_ImporteNC != $_liqimportenc){	$_CtrlImpNT	=	$_ImporteNC; }	
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
			$_Estado .= "Pendiente";	
		}
	} else {
		$_Estado	.=	"#ErrorEAN";
	}
	
?>