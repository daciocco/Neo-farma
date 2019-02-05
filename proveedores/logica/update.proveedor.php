<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/pedidos/includes/start.php");
 if ($_SESSION["_usrrol"]!="A" && $_SESSION["_usrrol"]!="M"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	echo $_SESSION["_usrol"];
	header("Location: $_nextURL");
 	exit;
 }

 $_pag		= empty($_REQUEST['pag']) 		? 0	: $_REQUEST['pag'];
 $_provid	= empty($_REQUEST['provid']) 	? 0 : $_REQUEST['provid'];
 $backURL	= empty($_REQUEST['backURL']) 	? '/pedidos/proveedores/': $_REQUEST['backURL'];
  
 $_idempresa		= $_POST['idempresa'];
 $_idproveedor		= $_POST['idproveedor'];
 $_nombre			= $_POST['nombre'];
 $_direccion		= $_POST['direccion'];
 $_idprov			= $_POST['idprovincia'];
 $_idloc			= $_POST['idloc'];
 $_cp				= $_POST['cp'];
 $_cuit				= $_POST['cuit'];
 $_nroIBB			= $_POST['nroIBB'];
 $_correo			= $_POST['correo'];
 $_telefono			= $_POST['telefono'];
 $_observacion		= $_POST['observacion'];
 $_activo			= $_POST['activo'];
  
 $_SESSION['s_empresa']			=	$_idempresa;
 $_SESSION['s_idproveedor']		=	$_idproveedor;
 $_SESSION['s_nombre']			=	$_nombre;
 $_SESSION['s_direccion']		=	$_direccion;
 $_SESSION['s_provincia']		=	$_idprov;
 $_SESSION['s_localidad']		=	$_idloc;
 $_SESSION['s_cp']				=	$_cp;
 $_SESSION['s_cuit']			=	$_cuit;
 $_SESSION['s_nroIBB']			=	$_nroIBB;
 $_SESSION['s_correo']			=	$_correo;
 $_SESSION['s_telefono']		=	$_telefono;
 $_SESSION['s_observacion']		=	$_observacion;
 $_SESSION['s_activo']			=	$_activo;
   
 if (empty($_idempresa) || !is_numeric($_idempresa)) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 1);
 	header('Location:' . $_goURL);
	exit;
 }
 
 if (empty($_idproveedor) || !is_numeric($_idproveedor)) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 2);
 	header('Location:' . $_goURL);
	exit;
 }
 
 if (empty($_nombre)) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 3);
 	header('Location:' . $_goURL);
	exit;
 }
 
  if (empty($_direccion)) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 4);
 	header('Location:' . $_goURL);
	exit;
 }
 
 if (empty($_idprov) || !is_numeric($_idprov)) {
	 if($_idprov != 0){
		$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 5);
 		header('Location:' . $_goURL);
		exit;
	 }
 } 
 
 if (empty($_idloc)) { //|| !is_numeric($_idloc)
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 6);
 	header('Location:' . $_goURL);
	exit;
 }
 
 if (!is_numeric($_cp)) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 7);
 	header('Location:' . $_goURL);
	exit;
 } 
 
 if (!dac_validarCuit($_cuit)) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 8);
 	header('Location:' . $_goURL);
	exit;
 }  
 $_cuit = dac_corregirCuit($_cuit);

//Si proveedor se está por registrar, controla que cuit no exista.
if($_activo == 3){
	$_proveedores	= DataManager::getProveedores(NULL, NULL, $_idempresa, NULL);
	if($_proveedores){
		foreach ($_proveedores as $k => $_prov) {	
			$_activoprov =	$_prov['provactivo'];
						
			if($_activoprov != 3){
				$_cuitprov 	 =  dac_corregirCuit($_prov['provcuit']);
				
				if($_cuitprov == $_cuit){
					$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 14);
					header('Location:' . $_goURL);
					exit;
					
				}
			}
		}
		
		foreach ($_proveedores as $k => $_prov){
			$_activoprov 	=	$_prov['provactivo'];
			if($_activoprov != 3){
				$_idempresaprov	=	$_prov['providempresa'];
				$_providprov	= 	$_prov['providprov'];		
				
				if(($_idempresaprov == $_idempresa) && ($_providprov == $_idproveedor)){
					$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 15);
					header('Location:' . $_goURL);
					exit;
					
				}
			}
		}
	}	 
}  
 
 if (empty($_correo) || !preg_match( "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,4})$/", $_correo )) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 9);
	header('Location:' . $_goURL);
	exit;
 }
 
 if (empty($_telefono)) {
	$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 10);
 	header('Location:' . $_goURL);
	exit;
 } 
  
 $_provobject	= ($_provid) ? DataManager::newObjectOfClass('TProveedor', $_provid) : DataManager::newObjectOfClass('TProveedor');
 $_provobject->__set('Empresa', 	$_idempresa);
 $_provobject->__set('Proveedor', 	$_idproveedor);
 $_provobject->__set('Nombre', 		$_nombre);
 $_provobject->__set('Direccion', 	$_direccion);
 $_provobject->__set('Provincia', 	$_idprov);
 $_provobject->__set('Localidad', 	$_idloc);
 $_provobject->__set('CP', 			$_cp);
 $_provobject->__set('Cuit', 		$_cuit);
 $_provobject->__set('NroIBB', 		$_nroIBB);
 $_provobject->__set('Email', 		$_correo);
 $_provobject->__set('Telefono', 	$_telefono);
 $_provobject->__set('Observacion', $_observacion);  
 if ($_provid) {
	//Modifica Cliente
	$ID = DataManager::updateSimpleObject($_provobject);
 } else {
	//Nuevo Proveedor
	//***************************************//
	//Controlo si ya existe proveedor en empresa
	$_proveedor 	= 	DataManager::getProveedor('providprov', $_idproveedor, $_idempresa);
	if ($_proveedor) {
		$_goURL = sprintf("/pedidos/proveedores/editar.php?provid=%d&sms=%d", $_provid, 2);
		header('Location:' . $_goURL);
		exit;
	}		
	//***************************************//
 	$_provobject->__set('ID', $_provobject->__newID());
 	$_provobject->__set('Activo', 1);
 	$ID = DataManager::insertSimpleObject($_provobject);
 }
 
 unset($_SESSION['s_empresa']);
 unset($_SESSION['s_idproveedor']);
 unset($_SESSION['s_nombre']);
 unset($_SESSION['s_direccion']);
 unset($_SESSION['s_provincia']);
 unset($_SESSION['s_localidad']);
 unset($_SESSION['s_cp']);
 unset($_SESSION['s_cuit']);
 unset($_SESSION['s_nroIBB']);
 unset($_SESSION['s_correo']);
 unset($_SESSION['s_telefono']);
 unset($_SESSION['s_observacion']);
 unset($_SESSION['s_activo']);
 
 header('Location:' . $backURL.'?pag='.$_pag);
?>