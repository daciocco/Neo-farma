<?php
require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.hiper.php");
if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="G"  && $_SESSION["_usrrol"]!="M"){
	echo "SU SESIÓN HA CADUCADO"; exit;
}


$artId			= (isset($_POST['artid']))			?	$_POST['artid'] 		: 	NULL;
$idEmpresa		= (isset($_POST['artidempresa']))	?	$_POST['artidempresa'] 	: 	NULL;
$artIdlab		= (isset($_POST['artidlab']))		?	$_POST['artidlab'] 		: 	NULL;
$artIdart		= (isset($_POST['artidart']))		?	$_POST['artidart'] 		: 	NULL;
$nombre			= (isset($_POST['artnombre']))		?	$_POST['artnombre'] 	: 	NULL;
$descripcion	= (isset($_POST['artdescripcion']))	?	$_POST['artdescripcion']: 	NULL;

$precio			= (isset($_POST['artprecio']))		?	$_POST['artprecio'] 	: 	NULL;
$precioCompra	= (isset($_POST['artpreciocompra']))?	$_POST['artpreciocompra'] 	: 	NULL;
$precioLista	= (isset($_POST['artpreciolista']))	?	$_POST['artpreciolista'] 	: 	NULL;
$precioReposicion = (isset($_POST['artprecioreposicion']))?	$_POST['artprecioreposicion'] 	: 	NULL;
$fechaCompra 	= (isset($_POST['artfechacompra']))	?	$_POST['artfechacompra']: 	NULL;

$ean			= (isset($_POST['artean']))			?	$_POST['artean'] 		: 	NULL;
$ganancia		= (isset($_POST['artporcentaje']))	?	$_POST['artporcentaje'] 	: 	NULL;
$medicinal		= (isset($_POST['artmedicinal']))	?	$_POST['artmedicinal'] 	: 	NULL;
$iva			= (isset($_POST['artiva']))	?	$_POST['artiva'] 	: 	NULL;
$oferta			= (isset($_POST['artoferta']))	?	$_POST['artoferta'] 	: 	NULL;
$idImagen 		= (isset($_POST['artimagen']))		?	$_POST['artimagen'] 	: 	NULL;
$rubro	 		= (isset($_POST['artrubro']))		?	$_POST['artrubro'] 		: 	NULL;
$familia 		= (isset($_POST['artfamilia']))		?	$_POST['artfamilia'] 	: 	NULL;
$lista	 		= (isset($_POST['artlista']))		?	$_POST['artlista'] 		: 	NULL;
$dispone 		= (isset($_POST['artiddispone']))	?	$_POST['artiddispone'] 	: 	NULL;

//dispone
$nombreGenerico = (isset($_POST['artnombregenerico']))		?	$_POST['artnombregenerico'] 	: 	NULL;
$via			= (isset($_POST['artvia']))			?	$_POST['artvia'] 		: 	NULL;
$forma 			= (isset($_POST['artforma']))		?	$_POST['artforma'] 		: 	NULL;
$envase			= (isset($_POST['artenvase']))		?	$_POST['artenvase'] 	: 	NULL;
$unidad 		= (isset($_POST['artunidad']))		?	$_POST['artunidad'] 	: 	NULL;
$cantidad 		= (isset($_POST['artcantidad']))	?	$_POST['artcantidad'] 	: 	NULL;
$unidadMedida	= (isset($_POST['artmedida']))		?	$_POST['artmedida'] 	: 	NULL;
$accion 		= (isset($_POST['artaccion']))		?	$_POST['artaccion'] 	: 	NULL;
$uso 			= (isset($_POST['artuso']))			?	$_POST['artuso'] 		: 	NULL;
$noUsar 		= (isset($_POST['artnousar']))		?	$_POST['artnousar'] 	: 	NULL;
$cuidadosPre 	= (isset($_POST['artcuidadospre']))	?	$_POST['artcuidadospre']: 	NULL;
$cuidadosPost	= (isset($_POST['artcuidadospost']))?	$_POST['artcuidadospost'] 	: 	NULL;
$comoUsar 		= (isset($_POST['artcomousar']))	?	$_POST['artcomousar'] 	: 	NULL;
$conservacion 	= (isset($_POST['artconservacion']))?	$_POST['artconservacion'] 	: 	NULL;
$fechaVersion 	= (isset($_POST['artfechaultversion']))		?	$_POST['artfechaultversion'] 	: 	NULL;
//array de formula
$formId 		= (isset($_POST['formId']))			?	$_POST['formId'] 		: 	NULL;
$formIfa 		= (isset($_POST['formIfa']))		?	$_POST['formIfa'] 		: 	NULL;
$formCant 		= (isset($_POST['formCant']))		?	$_POST['formCant'] 		: 	NULL;
$formMedida 	= (isset($_POST['formMedida']))		?	$_POST['formMedida'] 	: 	NULL;

if (empty($idEmpresa)) { 
	echo "La empresa es obligatoria."; exit; 
}
if (empty($artIdlab)) { 
	echo "El laboratorio es obligatorio."; exit; 
} 
if (empty($artIdart) || !is_numeric($artIdart)) { 
	echo "El código de artículo es obligatorio y numérico."; exit;
} else {
	if (empty($artId)) {
		$articulo	=	DataManager::getArticulo('artid', $artIdart, $idEmpresa, $artIdlab);
		if(count($articulo) > 0){
			echo "El código de artículo que desea crear ya existe."; exit;
		}
	}
}
if (empty($nombre)) {
	echo "El nombre es obligatorio."; exit; 
}
if(empty($familia)) { 
	echo "Indique la familia."; exit;
}
if(empty($rubro)) { 
	echo "Indique el rubro."; exit;
}
if (empty($precio)) { 
	echo "El precio es obligatoria."; exit; 
}
if (empty($ean)) { 
	echo "El c&oacute;digo de barras es obligatoria."; exit; 
}
$imagenNombre	=	$_FILES["imagen"]["name"]; 
$imagenPeso		= 	$_FILES["imagen"]["size"]; 
$imagenType		=	$_FILES["imagen"]["type"];
if ($imagenPeso != 0){
	if($imagenPeso > MAX_FILE){ 
		echo "El archivo no debe superar los 4 MB"; exit;
	}	
	if($imagenType!="image/png"){
		echo "La imagen debe ser PNG."; exit;		
	}
}

if(empty($fechaVersion)){
	$fechaVersion = '2001-01-01';
}

if(empty($unidad)){
	$unidad = 0;
}
if(empty($cantidad)){
	$cantidad = 0;
}

for($k=0; $k < count($formId); $k++) {
	if(empty($formIfa[$k])){
		echo "Indique IFA"; exit;
	}
	if(empty($formCant[$k]) || $formCant[$k] <= 0){
		echo "Indique una cantidad"; exit;
	}
	if(empty($formMedida[$k])){
		echo "Indique unidad de medida"; exit;
	}
}

//Dispone
$dispObject	= ($dispone) ? DataManager::newObjectOfClass('TArticuloDispone', $dispone) : DataManager::newObjectOfClass('TArticuloDispone');
$dispObject->__set('NombreGenerico'	, $nombreGenerico);
$dispObject->__set('Via'			, $via);
$dispObject->__set('Forma'			, $forma);
$dispObject->__set('Envase'			, $envase);
$dispObject->__set('Unidades'		, $unidad);
$dispObject->__set('Cantidad'		, $cantidad);
$dispObject->__set('UnidadMedida'	, $unidadMedida);
$dispObject->__set('Accion'			, $accion);
$dispObject->__set('Uso'			, $uso);
$dispObject->__set('NoUsar'			, $noUsar);
$dispObject->__set('CuidadosPre'	, $cuidadosPre);
$dispObject->__set('CuidadosPost'	, $cuidadosPost);
$dispObject->__set('ComoUsar'		, $comoUsar);
$dispObject->__set('Conservacion'	, $conservacion);
$dispObject->__set('FechaUltVersion', $fechaVersion);
if ($dispone) {
	DataManager::updateSimpleObject($dispObject);
	$idDispone = $dispone;	
} else {
	$dispObject->__set('ID', $dispObject->__newID());
	$idDispone = DataManager::insertSimpleObject($dispObject);
}

//Formulas INSERT, UPDATE O DELETE
$fmId = [];
$formulas 	= DataManager::getArticuloFormula( $idDispone );
if (count($formulas)) {
	foreach ($formulas as $k => $form) {
		$fmId[]			=	$form["afid"];
		$fmIfa[]		=	$form["afifa"];
		$fmCant[]		=	$form["afcantidad"];
		$fmMedida[]		=	$form["afumedida"];				
	}                       
}
if(count($formId)){
	//^^^Returns all records of $new_ids ($formId) that aren't present in $old_ids
	$insert = array_diff($formId, $fmId);	
	if(count($insert)) {
		foreach ($insert as $k => $key) {	
			$formObject	= DataManager::newObjectOfClass('TArticuloFormula');
			$formObject->__set('IDArticuloDispone'	, $idDispone);
			$formObject->__set('IFA'				, $formIfa[$k]);
			$formObject->__set('Cantidad'			, $formCant[$k]);
			$formObject->__set('UnidadMedida'		, $formMedida[$k]);	
			$formObject->__set('ID'					, $formObject->__newID());
			$idFormula = DataManager::insertSimpleObject($formObject);
		}
	}	
	//^^^Returns all records of $new_ids that were present in $old_ids
	$update = array_intersect($formId, $fmId);
	if(count($update)){
		foreach ($update as $k => $key) {			
			$formObject	= DataManager::newObjectOfClass('TArticuloFormula', $key);
			$formObject->__set('IDArticuloDispone'	, $idDispone);
			$formObject->__set('IFA'				, $formIfa[$k]);
			$formObject->__set('Cantidad'			, $formCant[$k]);
			$formObject->__set('UnidadMedida'		, $formMedida[$k]);	
			DataManager::updateSimpleObject($formObject);
		}
	}
}
//^^^Returns all records of $old_ids that aren't present in $new_ids
$delete = (count($formId)) ? array_diff($fmId, $formId) : $fmId;
if(count($delete)>0){
	for($k=0; $k < count($delete); $k++) {
		$formObject	= DataManager::newObjectOfClass('TArticuloFormula', $fmId[$k]);
		$formObject->__set('ID',	$fmId[$k] );
		DataManager::deleteSimpleObject($formObject);
	}
}

$rutaFile		=	"/pedidos/images/imagenes/";	
if ($imagenPeso	 != 0){	
	$ext	=	explode(".", $imagenNombre);
	$name	= 	dac_sanearString($ext[0]);
	
	$imagenObject	= ($idImagen) ? DataManager::newObjectOfClass('TImagen', $idImagen) : 
	DataManager::newObjectOfClass('TImagen');	
	$imagenNombre 	= md5($name).".".$ext[1];	
	$imagenObject->__set('Imagen' , $imagenNombre);
	if($idImagen){
		$imagenVieja	= $imagenObject->__get('Imagen');
		DataManager::updateSimpleObject($imagenObject);		
		$dir	=	$_SERVER['DOCUMENT_ROOT'].$rutaFile.$imagenVieja;
		if (file_exists($dir)) { unlink($dir); }
	} else {
		$imagenObject->__set('ID', $imagenObject->__newID());				
		$idImagen	= DataManager::insertSimpleObject($imagenObject);
	}	
	
	if(!dac_fileUpload($_FILES["imagen"], $rutaFile, md5($name))){
		echo "Error al intentar subir la imagen."; exit;
	}		
}

$artObject	= ($artId) ? DataManager::newObjectOfClass('TArticulo', $artId) : DataManager::newObjectOfClass('TArticulo');
$artObject->__set('Empresa'		, $idEmpresa);
$artObject->__set('Laboratorio'	, $artIdlab);
$artObject->__set('Articulo'	, $artIdart);
$artObject->__set('Nombre'		, $nombre);
$artObject->__set('Descripcion'	, $descripcion);
$artObject->__set('Precio'		, $precio);
$artObject->__set('PrecioLista'		, $precioLista);
$artObject->__set('PrecioCompra'	, $precioCompra);
$artObject->__set('PrecioReposicion', $precioReposicion);
if(empty($fechaCompra)){
	$artObject->__set('FechaCompra', date("Y-m-d H:m:s"));
} else {
	$artObject->__set('FechaCompra', dac_invertirFecha($fechaCompra));
}
$artObject->__set('CodigoBarra'	, $ean);
$artObject->__set('UsrUpdate'	, $_SESSION["_usrid"]);
$artObject->__set('LastUpdate'	, date("Y-m-d H:m:s"));
$artObject->__set('Medicinal'	, ($medicinal) ? 'S' : 'N');
$artObject->__set('IVA'			, ($iva) ? 'S' : 'N');
$artObject->__set('Ganancia'	, $ganancia);
$artObject->__set('Imagen'		, $idImagen); 
$artObject->__set('Rubro'		, $rubro);
$artObject->__set('Dispone' 	, $idDispone);
$artObject->__set('Familia' 	, $familia);
$artObject->__set('Lista' 		, $lista);

$artObjectHiper	= ($artId) ? DataManagerHiper::newObjectOfClass('THiperArticulo', $artId) : DataManagerHiper::newObjectOfClass('THiperArticulo');
$artObjectHiper->__set('Empresa'		, $idEmpresa);
$artObjectHiper->__set('Laboratorio'	, $artIdlab);
$artObjectHiper->__set('Articulo'		, $artIdart);
$artObjectHiper->__set('Nombre'			, $nombre);
$artObjectHiper->__set('Descripcion'	, $descripcion);
$artObjectHiper->__set('Precio'			, $precio);
$artObjectHiper->__set('PrecioLista'	, $precioLista);
$artObjectHiper->__set('PrecioCompra'	, $precioCompra);
$artObjectHiper->__set('PrecioReposicion', $precioReposicion);
if(empty($fechaCompra)){
	$artObjectHiper->__set('FechaCompra', date("Y-m-d H:m:s"));
} else {
	$artObjectHiper->__set('FechaCompra', dac_invertirFecha($fechaCompra));
}
$artObjectHiper->__set('CodigoBarra'	, $ean);
$artObjectHiper->__set('UsrUpdate'		, $_SESSION["_usrid"]);
$artObjectHiper->__set('LastUpdate'		, date("Y-m-d H:m:s"));
$artObjectHiper->__set('Medicinal'		, ($medicinal) ? 'S' : 'N');
$artObjectHiper->__set('IVA'			, ($iva) 		? 'S' : 'N');
$artObjectHiper->__set('Oferta'			, ($oferta) ? 'S' : 'N');
$artObjectHiper->__set('Ganancia'		, $ganancia); 
$artObjectHiper->__set('Imagen'			, $idImagen); 
$artObjectHiper->__set('Rubro'			, $rubro);
$artObjectHiper->__set('Dispone' 		, $idDispone);
$artObjectHiper->__set('Familia' 		, $familia);
$artObjectHiper->__set('Lista' 			, $lista);

if ($artId) {
	//UPDATE	
	DataManagerHiper::updateSimpleObject($artObjectHiper, $artId);
	DataManager::updateSimpleObject($artObject);
} else {
	//INSERT
	$artObject->__set('Activo'	, 1);
	$artObject->__set('Stock'	, 1);
	$artObject->__set('ID'		, $artObject->__newID());
	DataManagerHiper::_getConnection('Hiper'); //Controla conexión a HiperWin
	$idArt = DataManager::insertSimpleObject($artObject);
	
	$artObjectHiper->__set('Activo'	, 1);
	$artObjectHiper->__set('Stock'	, 1);
	$artObjectHiper->__set('ID'		, $idArt);
	DataManagerHiper::insertSimpleObject($artObjectHiper, $idArt);
}
 
echo "1"; exit;
?>