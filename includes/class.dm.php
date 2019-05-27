<?php
require_once('class.Database.php');
require_once('class.phpmailer.php');
require_once('class.smtp.php');
require_once('class/class.usuario.php');
require_once('class/class.noticias.php');
require_once('class/class.zona.php');
require_once('class/class.articulo.php');
require_once('class/class.articulodispone.php');
require_once('class/class.articuloformula.php');
require_once('class/class.pedido.php');
require_once('class/class.pedidotransfer.php');
require_once('class/class.pack.php');
require_once('class/class.planificacion.php');
require_once('class/class.partediario.php');
require_once('class/class.accion.php');
require_once('class/class.rendicion.php');
require_once('class/class.cheques.php');
require_once('class/class.facturas.php'); 
require_once('class/class.recibos.php');
require_once('class/class.bonificacion.php');
require_once('class/class.abm.php');
require_once('class/class.drogueria.php');
require_once('class/class.drogueriaCAD.php');
require_once('class/class.liquidacion.php');
require_once('class/class.condicion.php');
require_once('class/class.condicionesdepago.php');
require_once('class/class.condiciontransfer.php');
require_once('class/class.listas.php'); //listas de precios
require_once('class/class.condicionespecial.php');
require_once('class/class.proveedor.php');
require_once('class/class.facturaprov.php');
require_once('class/class.contacto.php');
require_once('class/class.prospecto.php');
require_once('class/class.relevamiento.php');
require_once('class/class.respuesta.php');
require_once('class/class.llamada.php');
require_once('class/class.condicioncomercial.php');
require_once('class/class.condicioncomercial.art.php');
require_once('class/class.condicioncomercial.bonif.php');
require_once('class/class.agenda.php');
require_once('class/class.cuenta.php');
require_once('class/class.cuentarelacionada.php');
require_once('class/class.propuesta.php');
require_once('class/class.propuesta.detalle.php');
require_once('class/class.estado.php');
require_once('class/class.imagen.php');
require_once('class/class.cadena.php');
require_once('class/class.cadenacuentas.php');
require_once('class/class.ticket.php');
require_once('class/class.ticketmotivo.php');
require_once('class/class.ticketmensaje.php');
require_once('class/class.localidad.php');
require_once('class/class.areas.php');
require_once('class/class.movimiento.php');

class DataManager {
	//--------------------------
	// 	FUNCIONES GENERICAS
	//--------------------------
	public static function _getConnection($dsn = '') {
		static $hDB;
		try {
			//$hDB = Database::instance($dsn);
			if(!$dsn){
  				$hDB = Database::instance();
			} else {
				$hDB = Database::instanceHiper();
			}
		} catch (Exception $e) {
	  		die("Imposible conexion con BBDD<BR>");
		}
		return $hDB; 
  	}

		

	// Busca el id de la tabla que desee!?

	public static function getIDByField($_class=null,$_field=null,$_value=null) {

    	$_id	 = 0;

  		$_object = DataManager::newObjectOfClass($_class);	

		if ($_object && $_field && $_value) {

  			$sql = "SELECT {$_object->__getFieldID()} FROM {$_object->__getTableName()} WHERE {$_field}='{$_value}' LIMIT 1";

			$hDB = DataManager::_getConnection(); //$dbname

			try {

  				$_id = $hDB->getOne($sql);

      		} catch (Exception $e) {

      			die("Error Q = $sql<br/>");

      		}

    	}		

    	return $_id;

  	}

	

	public static function newObjectOfClass($_class=null, $_id=NULL) {
		$_object = null;
  		if (!empty($_class)) {
  			if (class_exists($_class)) {
  				$_object = new $_class($_id);
  			}
    	}
    	return $_object;
  	}

	

  	public static function loadFromDatabase($_object=null, $_id=null) {
		if (!empty($_object)) {

  			$_sql = sprintf("SELECT * FROM %s WHERE %s='%d' LIMIT 1", $_object->__getTableName(), $_object->__getFieldID(), $_id);		
			
			$hDB = DataManager::_getConnection();

	    	try {

				$data = $hDB->getAll($_sql);

	    	} catch (Exception $e) {

	    		die("error ejecutando $_sql<br>");

	    	}

	    	return $data;

  		}

  		return null;

  	}

	

	//CUENTA NÚMERO DE FILAS según la tabla del objeto enviado

	public static function getNumeroFilasTotales($_class=NULL) {

    $_numero = 0;

    if ($_class) {

	  	$_object = DataManager::newObjectOfClass($_class);

	  	if ($_object) {

	  			$sql = "SELECT COUNT(1) FROM {$_object->__getTableName()}";

			}

	  		$_numero = DataManager::getCount($sql);

	    }

    	return $_numero;

  	}

	

	//*****************************

	// CONSULTA SI EXISTEN DATOS CON UN ID Y COLUMNA INDICADO 

	//*****************************

	public static function ExistFromDatabase($_object, $_field, $_ID) {

		if (!empty($_ID)) {

  			$_sql = sprintf("SELECT COUNT(*) FROM %s WHERE %s=%s", $_object->__getTableName(), $_field, $_ID);

			$hDB = DataManager::_getConnection();

	    	$_total = DataManager::getCount($_sql); 

  		}

 		return $_total;

	}

	

	//CUENTA FILAS DE LA CONSULTA SQL ENVIADA	$sql = select count(*) ....

  	public static function getCount( $sql ) {

		$hDB  = DataManager::_getConnection();

		$data = 0;

		try {

			$data = $hDB->getOne($sql);

		} catch (Exception $e) {

			//Este Error ejecutando lo da también cuando la tabla está vacía,

			//por lo cual habría que controlar eso para que no de error en esos casos.

	  		die("error ejecutando $sql<br>"); 

		}

		return $data;

  	}	

  		

	// SIMPLE OBJECT
  	// CLASES DERIVADAS DE PropertyObject y que tengan propiedad == ID
  	public static function updateSimpleObject($_object) {
    	$hDB 	 = DataManager::_getConnection();
    	$_rows	 = 0;		
    	$_fields = $_object->__getUpdated();
		if (count($_fields) > 0) {
      		$hDB->startTransaction();
      		try {
        		$_ID		= $_object->__get('ID');
				$_fieldID 	= $_object->__getFieldName('ID');
        		$_rows 		= $hDB->update($_object->__getTableName(), $_fields, "$_fieldID=$_ID");
				$hDB->commit();
      		} catch (Exception $e) {
        		$hDB->abort();
        		print "Transacci&oacute;n abortada. ERR=" . $e->getMessage();
      		}
    	}
    	return $_rows;
  	}


  	public static function deleteSimpleObject($_object) {
    	$hDB = DataManager::_getConnection();
    	$hDB->startTransaction();
    	$_rows = 0;
    	try {
      		$_ID		= $_object->__get('ID');
	  		$_fieldID 	= $_object->__getFieldName('ID');
      		$_theSQL	= sprintf("DELETE FROM %s WHERE %s=%s", $_object->__getTableName(), $_fieldID, $_ID);
      		$_rows 		= $hDB->select($_theSQL);
      		$hDB->commit();
    	} catch (Exception $e) {
      		$hDB->abort();
      		print "Transaccion abortada. ERR=" . $e->getMessage();
    	}
    	return $_rows;

  	}



  	public static function insertSimpleObject($_object) {    	

    	$hDB = DataManager::_getConnection();

		$hDB->startTransaction();

		$_ID = 0;

    	try {

      		$_ID = $hDB->insert($_object->__getTableName(), $_object->__getData());

			$hDB->commit();

      		$_object->__set('ID', $_ID); // Para que el objeto quede consistente con la BD

    	} catch (Exception $e) {

			$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_ID;

  	}

	

	//*****************************

	// CONSULTA COUNT COLS DE TABLA

	//*****************************

	public static function informationSchema($tableName = NULL) {

		$hDB = DataManager::_getConnection();

  		$sql = sprintf("SELECT * FROM information_schema.columns WHERE table_name = %s", $tableName);

 		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

	}

	//*******************************

	

	//********************************
	// FUNCIONES SIN USO DE CLASES
	//********************************
	public static function deletefromtabla($_tabla, $_fieldID, $_ID) {
    	$hDB = DataManager::_getConnection();
    	$hDB->startTransaction();
    	$_rows = 0;
    	try {
      		$_theSQL	= sprintf("DELETE FROM %s WHERE %s=%s", $_tabla, $_fieldID, $_ID);
      		$hDB->select($_theSQL);
      		$hDB->commit();
    	} catch (Exception $e) {
      		$hDB->abort();
      		print "Transaccion abortada. ERR=" . $e->getMessage();
    	}
    	return $_rows;
  	}
	
	public static function insertfromtabla($_tabla, $_fieldID, $_ID, $_values) {
    	$hDB = DataManager::_getConnection();
		$hDB->startTransaction();
    	try {
			$_theSQL	= sprintf("INSERT INTO %s (%s) VALUES (%s, %s)", $_tabla, $_fieldID, $_ID, $_values);
      		$hDB->select($_theSQL);
	      	$hDB->commit();
    	} catch (Exception $e) {
	      	$hDB->abort();
      		print "Transaccion abortada. ERR=" . $e->getMessage();
    	}
    	return $_ID;
  	}

	//****************************************************************
	//Para INSERTAR DATOS en tablas que no tienen Campo UNIQUE
	//****************************************************************
	public static function insertToTable($_tabla, $_fieldID, $_values, $_ID=0) {
    	$hDB = DataManager::_getConnection();
		$hDB->startTransaction();
    	try {
			$_theSQL	= sprintf("INSERT INTO %s (%s) VALUES (%s)", $_tabla, $_fieldID, $_values);
			$hDB->select($_theSQL);
	      	$hDB->commit();
    	} catch (Exception $e) {
	      	$hDB->abort();
      		print "Transaccion abortada. ERR=" . $e->getMessage();
    	}
    	return $_ID;
  	}
		
	public static function updateToTable($_tabla, $_fieldID, $_condition="TRUE") {
    	$hDB = DataManager::_getConnection();
		$hDB->startTransaction();
		$_rows = 0;
		if($_fieldID){
			try {
				$_theSQL = sprintf("UPDATE %s SET %s WHERE %s", $_tabla, $_fieldID, $_condition);
				$hDB->select($_theSQL);
				$hDB->commit();
			} catch (Exception $e) {
				$hDB->abort();
				print "Transaccion abortada. ERR=" . $e->getMessage();
			}
		}
		return $_rows;
  	}	
	

	public static function deleteToTable($_tabla, $_campos = NULL) {

		if  (empty($_campos)  || is_null($_campos)){ $_condicionWhere 	= 	"TRUE";

		} else {$_condicionWhere 	= 	$_campos;}

    	$hDB = DataManager::_getConnection();

    	try {

			$_theSQL	= sprintf("DELETE FROM %s WHERE (%s)", $_tabla, $_condicionWhere);

      		$hDB->select($_theSQL);

	      	$hDB->commit();

    	} catch (Exception $e) {

	      	$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_ID;

  	}	

	

	

	//****************************************************************

	// FUNCIONES DEFINIDAS POR TABLAS

	//****************************************************************		  

  	//********************

	// LISTAR PEDIDOS

	//********************

	public static function getPedidos($idUsr=NULL, $mostrarTodos=NULL, $_nrosPedidos=NULL, $empresa=NULL, $_negociacion=NULL, $_aprobado=NULL, $_pag=NULL, $_rows=NULL, $dateFrom=NULL, $dateTo=NULL){

		$hDB = DataManager::_getConnection();

		if  ((empty($idUsr) && $idUsr != 0) || is_null($idUsr)){ $_condicionUsr 	= 	"TRUE";

		} else { $_condicionUsr			= 	"pidusr=".$idUsr;}

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){	$_condicionActivos 	= 	"TRUE";

		} else { $_condicionActivos 	= 	"pactivo=".$mostrarTodos;}

		if  ((empty($_nrosPedidos) && $_nrosPedidos != 0) || is_null($_nrosPedidos)){	$_condicionNroPedido 	= 	"TRUE";

		} else { $_condicionNroPedido 	= 	"pidpedido IN (".$_nrosPedidos.")";}

		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){	$_condicionEmpresa	=	"TRUE"; 

		} else { $_condicionEmpresa 	= 	"pidemp=".$empresa;}	

		if  ((empty($_negociacion) && $_negociacion != 0)  || is_null($_negociacion)){	$_condicionNegociacion = "TRUE";

		} else { $_condicionNegociacion = 	"pnegociacion=".$_negociacion;}	

		if  ((empty($_aprobado) && $_aprobado != 0)  || is_null($_aprobado)){	$_condicionAprobado	= 	"TRUE";

		} else { $_condicionAprobado 	= 	"paprobado=".$_aprobado;}

		

		if  (empty($dateFrom) || is_null($dateFrom)){ $_conditionFrom 	= 	"TRUE";

		} else {$_conditionFrom 	= 	"pfechapedido >= '".$dateFrom."'";}

		if  (empty($dateTo) || is_null($dateTo)){ $_conditionTo 	= 	"TRUE";

		} else {$_conditionTo 	= 	"pfechapedido <= '".$dateTo."'";}	

		

		$sql	=	"SELECT * FROM pedido WHERE ($_condicionNroPedido) AND ($_condicionActivos) AND ($_condicionUsr) AND ($_condicionEmpresa) AND ($_condicionNegociacion) AND ($_condicionAprobado) AND ($_conditionFrom) AND ($_conditionTo) ORDER BY pidpedido DESC ";

		

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getPedidosEntre($mostrarTodos = NULL, $desde = NULL, $hasta = NULL){

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){	$_condicionActivos 	= 	"TRUE";

		} else { $_condicionActivos 	= 	"pactivo=".$mostrarTodos;}

		if  (empty($desde) || is_null($desde)){ 

			$_fecha_inicio 		= strtotime ( '-1 year' , strtotime ( date("Y-m-d H:m:s") ) ) ;

			$_fecha_inicio 		= date ( "Y-m-d H:m:s" , $_fecha_inicio );

			$_condicionDesde 	= $_fecha_inicio;

		} else {

			$_condicionDesde 	= $desde;

		}

		$_condicionHasta	= $hasta;

		

		$_condicionFecha 	= "pfechapedido BETWEEN '".($_condicionDesde)."' AND '".($_condicionHasta)."'";

		

		$sql	=	"SELECT DISTINCT pidpedido FROM pedido WHERE ($_condicionActivos) AND ($_condicionFecha) ORDER BY pidpedido DESC ";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;		

  	}
	
	//------------------------
	// LISTADO TRANSFERS 2	
	//-------------------------
	public static function getTransfersPedido($mostrarTodos = NULL, $desde = NULL, $hasta = NULL, $idDrog = NULL, $tipo = NULL, $idVendedor = NULL, $idPedido = NULL, $idCuenta = NULL) {
		$hDB = DataManager::_getConnection();	
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"ptactivo=".$mostrarTodos;}
		if  (empty($idDrog) || is_null($idDrog)){ $_condicionDrogueria 	= 	"TRUE";
		} else {$_condicionDrogueria 	= 	"ptiddrogueria=".$idDrog;}
		if  (empty($tipo) || is_null($tipo)){ $_condicionTipo 	= 	"TRUE";
		} else {$_condicionTipo 	= 	"ptliquidado=".$tipo;}		
		if  (empty($desde) || is_null($desde) || empty($hasta) || is_null($hasta)){ $_condicionDate 	= 	"TRUE";
		} else {	$_condicionDate 	= 	"ptfechapedido BETWEEN '".$desde."' AND '".$hasta."'";}		
		if  ((empty($idVendedor) && $idVendedor != '0')  || is_null($idVendedor)){ $_condicionVendedor = "TRUE";
		} else {$_condicionVendedor = "ptidvendedor=".$idVendedor;}	
		if  ((empty($idPedido) && $idPedido != '0')  || is_null($idPedido)){ $_condicionNroTransfer = "TRUE";
		} else {$_condicionNroTransfer = "ptidpedido=".$idPedido;}	
		if  ((empty($idCuenta) && $idCuenta != '0')  || is_null($idCuenta)){ $_condicionCuenta = "TRUE";
		} else {$_condicionCuenta = "ptidclineo=".$idCuenta;}
		
    	$sql = "SELECT * FROM pedidos_transfer WHERE ($_condicionDate) AND ($_condicionDrogueria) AND ($_condicionTipo)  AND ($_condicionActivos) AND ($_condicionVendedor) AND ($_condicionNroTransfer) AND ($_condicionCuenta) ORDER BY ptidpedido DESC, ptidart ASC";
				
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}

		return $data;
  	}	
	
	//---------------------
	// LISTADO TRANSFER por VENDEDOR AGRUPADO por pedido para exportar XLS
	//---------------------
	public static function getTransfersVendedorXLS($mostrarTodos = NULL, $_IDVendedor) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"ptactivo=".$mostrarTodos;}	
		$_condicionVendedor = "ptidvendedor=".$_IDVendedor;		
    	$sql = "SELECT * FROM pedidos_transfer WHERE ($_condicionActivos) AND ($_condicionVendedor) GROUP BY ptidpedido, ptclirs ORDER BY ptidpedido DESC";		
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//--------------------
	// LISTADO TRANSFER DISTINCT
	//--------------------
	public static function getTransfers($mostrarTodos = NULL, $_IDVendedor = NULL, $dateFrom = NULL, $dateTo = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"ptactivo=".$mostrarTodos;}	
		if  (empty($dateFrom) || is_null($dateFrom)){ $_conditionFrom 	= 	"TRUE";
		} else {$_conditionFrom 	= 	"ptfechapedido >= '".$dateFrom."'";}
		if  (empty($dateTo) || is_null($dateTo)){ $_conditionTo 	= 	"TRUE";
		} else {$_conditionTo 	= 	"ptfechapedido <= '".$dateTo."'";}
		if  (empty($_IDVendedor)  || is_null($_IDVendedor)){ $_condicionVendedor	= "TRUE";
		} else {$_condicionVendedor = "ptidvendedor=".$_IDVendedor;}

    	$sql = "SELECT DISTINCT ptidpedido, ptclirs, ptfechapedido FROM pedidos_transfer WHERE ($_condicionActivos) AND ($_conditionFrom) AND ($_conditionTo) AND ($_condicionVendedor) ORDER BY ptidpedido DESC";

		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}

		return $data;
  	}	

	//**********************************//

	// ULTIMO NÚMERO DEL CAMPO INDICADO //

	//**********************************//

	public static function dacLastId($_tabla, $_campo) {

		$max = 0;

		$hDB = DataManager::_getConnection();

		$sql = "SELECT MAX($_campo) AS MaxNroPedido FROM $_tabla"; 		

		try {

			$_numero = $hDB->getOne($sql);		

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}		

		return ($_numero+1);

  	}	

	//--------------------
	// TABLA DROGUERIAS p/TRANSFERs 
	//--------------------
  	public static function getDrogueria($mostrarTodos = NULL, $empresa = NULL, $drogueriaCad = NULL, $destino = NULL, $drogueriaCuenta = NULL ) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"drogtactiva=".$mostrarTodos;}
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"drogtidemp=".$empresa;}
		
		if  ((empty($drogueriaCuenta) && $drogueriaCuenta != 0)  || is_null($drogueriaCuenta)){ $_condicionCuenta 	= 	"TRUE";
		} else {$_condicionCuenta 	= 	"drogtcliid=".$drogueriaCuenta;}
		
		if  ((empty($drogueriaCad) && $drogueriaCad != 0)  || is_null($drogueriaCad)){ $_condicionDrogueria 	= 	"TRUE";
		} else {$_condicionDrogueria 	= 	"drgdcadId=".$drogueriaCad;}
		
		if  ((empty($destino) && $destino != 0)  || is_null($destino)){ $_condicionDestino 	= 	"TRUE";
		} else {$_condicionDestino 	= 	"drogtcorreotransfer = '".$destino."'";}
		
    	$sql = "SELECT * FROM droguerias WHERE ($_condicionActivos) AND ($_condicionEmpresa) AND ($_condicionDrogueria) AND ($_condicionDestino) AND ($_condicionCuenta) ORDER BY drogtcliid ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	

	//********************

	// SELECCIONA DROGUERIA agrupadas por destino de correo

	//********************

  	public static function getDrogueriaTransferTipo( $mostrarTodos=NULL ) {

		$hDB = DataManager::_getConnection();

		$_condicionActivos = ($mostrarTodos == NULL) ?  "true"	: "drogtactiva=$mostrarTodos";

    	$sql = "SELECT * FROM droguerias WHERE ($_condicionActivos) GROUP BY drogtcorreotransfer";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//********************

	// TABLA de las DROGUERIAS CAD

	//********************

  	public static function getDrogueriaCAD( $mostrarTodos = NULL, $empresa = NULL, $_ID = NULL ) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"drogtactiva=".$mostrarTodos;}

		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";

		} else {$_condicionEmpresa 	= 	"dcadIdEmpresa=".$empresa;}

		if  ((empty($_ID) && $_ID != '0')  || is_null($_ID)){ $_condicionID 	= 	"TRUE";

		} else {$_condicionID 	= 	"dcadId=".$_ID;}

    	$sql = "SELECT * FROM drogueriasCAD WHERE ($_condicionActivos) AND ($_condicionEmpresa) AND ($_condicionID) ORDER BY dcadNombre ASC, dcadActivo DESC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}



	//-----------------
	// Tabla USUARIOS
	public static function getUsuarios( $_pag=0, $_rows=0, $mostrarTodos = NULL, $_zona = NULL,  $_rol = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"uactivo=".$mostrarTodos;}
		if  ((empty($_zona) && $_zona != '0')  || is_null($_zona)){ $_condicionZona 	= 	"TRUE";
		} else {$_condicionZona 	= 	"zona IN (".$_zona.")";}
		if  ((empty($_rol) && $_zona != '0')  || is_null($_rol)){ $_condicionRol 	= 	"TRUE";
		} else {$_condicionRol 	= 	"urol IN (".$_rol.")";}

		$sql = "SELECT * 
				FROM usuarios 
				WHERE ($_condicionActivos)
				AND	($_condicionRol)
				ORDER BY uactivo DESC, unombre ASC";

		if (($_pag) && ($_rows)) {
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		}
		try {
			$data = $hDB->getAll($sql);
		} catch (Exception $e) {
			die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//---------------------
	// Tabla USUARIO
	public static function getUsuario($_field=NULL, $_ID) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM usuarios WHERE uid='$_ID' LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}
	
	//-----------------
	// Tabla AREAS
	public static function getAreas( $mostrarTodos = NULL ) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"activo=".$mostrarTodos;}
		$sql = "SELECT * 
				FROM areas
				WHERE ($_condicionActivos)
				ORDER BY descripcion DESC";
		try {
			$data = $hDB->getAll($sql);
		} catch (Exception $e) {
			die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//-----------------------
	// Tabla PROVEEDORES

  	public static function getProveedores( $_pag=0, $_rows=0, $empresa=NULL, $mostrarTodos = TRUE) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"provactivo=".$mostrarTodos;}

		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";

		} else {$_condicionEmpresa 	= 	"providempresa=".$empresa;}

    	$sql = "SELECT * FROM proveedor WHERE ($_condicionActivos) AND ($_condicionEmpresa) ORDER BY provactivo DESC, providempresa ASC, provnombre ASC";		

		//($_rows == NULL) ?	DataManager::getCount($sql)	:	$_rows;		

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//----------------------------------------
	// Tabla FACTURAS A PAGAR DE PROVEEDORES
  	public static function getFacturasProveedor( $empresa = NULL, $mostrarTodos = NULL, $fechaPago = NULL, $tipo = NULL, $factNumero = NULL, $idProv = NULL, $fechaDesde = NULL, $fechaHasta = NULL) {
		$hDB = DataManager::_getConnection();		
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"factidemp=".$empresa;}
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"factactiva=".$mostrarTodos;}		
		if  ((empty($fechaPago) && $fechaPago != '0000-00-00')  || is_null($fechaPago) ){ $_condicionFechaPago 	= 	"TRUE";
		} else {$_condicionFechaPago 	= 	"factfechapago='".$fechaPago."'";}
		
		if  (empty($tipo) || is_null($tipo) ){ $_condicionTipo = "TRUE";
		} else {$_condicionTipo = "facttipo='".$tipo."'";}
		if  ((empty($factNumero) && $factNumero != '0')  || is_null($factNumero) ){ $_condicionFactNumero 	= 	"TRUE";
		} else {$_condicionFactNumero 	= 	"factnumero='".$factNumero."'";}
		if  ((empty($idProv) && $idProv != '0')  || is_null($idProv) ){ $_condicionIdProv = "TRUE";
		} else {$_condicionIdProv 	= 	"factidprov='".$idProv."'";}
		
		if  ((empty($fechaDesde) && $fechaDesde != '0000-00-00')  || is_null($fechaDesde) ){ $_condicionFechaDesde 	= 	"TRUE";
		} else {$_condicionFechaDesde 	= 	"factfechapago >='".$fechaDesde."'";}
		if  ((empty($fechaHasta) && $fechaHasta != '0000-00-00')  || is_null($fechaHasta) ){ $_condicionFechaHasta 	= 	"TRUE";
		} else {$_condicionFechaHasta 	= 	"factfechapago <='".$fechaHasta."'";}

    	$sql = "SELECT * FROM facturas_proveedor WHERE ($_condicionEmpresa) AND ($_condicionActivos) AND ($_condicionFechaPago) AND ($_condicionTipo) AND ($_condicionFactNumero) AND ($_condicionIdProv) AND ($_condicionFechaDesde) AND ($_condicionFechaHasta) ORDER BY factidemp ASC, factidprov DESC, factnumero DESC";	
		
		try {
			$data = $hDB->getAll($sql);
		} catch (Exception $e) {
			die("error ejecutando $sql<br>");
		}

		return $data;
  	}

	//********************

	// Select datos de un proveedor

	public static function getProveedor($_field=NULL, $_ID, $empresa=NULL) {

		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";

		} else {$_condicionEmpresa 	= 	"providempresa=".$empresa;}	

    	if ($_ID) {

      		$sql = "SELECT * FROM proveedor WHERE ($_field='$_ID') AND ($_condicionEmpresa) LIMIT 1";

      		$hDB = DataManager::_getConnection();

      		try {

        		$data = $hDB->getAll($sql);

      		} catch (Exception $e) {

        		die("Error en el SGBD : Q = $sql<br>");

      		}

      		return $data;

    	}

  	}

		

	//********************

  	// Tabla NOTICIAS

	//********************

	public static function getNoticias( $_pag=0, $_rows=20, $mostrarTodos = TRUE ) {

		$hDB = DataManager::_getConnection();

    	$_condicionActivos = ($mostrarTodos) ? "true" : "ntactiva=1";

    	$sql = "SELECT * FROM noticias WHERE ($_condicionActivos) ORDER BY ntfecha DESC";

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

		

	//********************

	// Tabla NOTICIAS

	//********************

   	public static function getNoticiasActivas( $_pag=0, $_rows=20, $mostrarTodos = TRUE ) {

		$hDB = DataManager::_getConnection();

    	$_condicionActivos = ($mostrarTodos) ? "true" : "ntactiva=1";

		$sql = sprintf ("SELECT * FROM noticias WHERE ($_condicionActivos) ORDER BY ntfecha DESC");

		

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}
	
	//----------------------------
  	// Tabla ZONA  y por Vendedor
	//----------------------------
	public static function getZonas($_pag=0, $_rows=0, $mostrarTodos = TRUE) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ 		
			$_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"zactivo=".$mostrarTodos;}			
    	$sql = "SELECT * FROM zona WHERE ($_condicionActivos) ORDER BY zzona ASC";
		if (($_pag) && ($_rows)) { 
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		}
		try {
			$data = $hDB->getAll($sql);
		} catch (Exception $e) {
			die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//----------------------------
  	// Tabla ZONA Excepciones
	//----------------------------
	public static function getZonasExcepcion($idLoc = NULL, $zona = NULL, $ctaId = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($idLoc) && $idLoc != '0')  || is_null($idLoc)){ $_condicionLocalidad 	= 	"TRUE";
		} else {$_condicionLocalidad	= 	"zeIdLoc=".$idLoc;}
		if  ((empty($zona) && $zona != '0')  || is_null($zona)){ $_condicionZona 	= 	"TRUE";
		} else {$_condicionZona	= 	"zeZona=".$zona;}	
		if  (empty($ctaId) || is_null($ctaId)){ $_condicionCta = "TRUE";
		} else {$_condicionCta	= 	"zeCtaId=".$ctaId;}
    	$sql = "SELECT * FROM zona_excepcion WHERE ($_condicionZona) AND ($_condicionLocalidad) AND ($_condicionCta)";
		try {
			$data = $hDB->getAll($sql);
		} catch (Exception $e) {
			die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//**************************

	//Selecciona todas las zonas de un vendedor

	public static function getZonasVendedor($_ID) { //, $mostrarTodos = TRUE

		$hDB = DataManager::_getConnection();

    	//$_condicionActivos = ($mostrarTodos) ? "true" : "zactivo=1";

		$_condicion = "uid = $_ID";

    	$sql = "SELECT * FROM zonas_vend WHERE ($_condicion) ORDER BY zona";

		try {

			$data = $hDB->getAll($sql);

		} catch (Exception $e) {

			die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	
	//----------------------------
  	// Tabla ZONA DISTRIBUCION
	//----------------------------
	public static function getZonasDistribucion($_pag=0, $_rows=0) {
		$hDB = DataManager::_getConnection();			
    	$sql = "SELECT * FROM zonas ORDER BY IdZona ASC";
		if (($_pag) && ($_rows)) { 
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		}
		try {
			$data = $hDB->getAll($sql);
		} catch (Exception $e) {
			die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//----------------------------
  	// ZONA DISTRIBUCION
	//----------------------------
	public static function getZonaDistribucion($_field=NULL, $_field2=NULL, $_ID) {
		$hDB = DataManager::_getConnection();
		if  ((empty($_ID) && $_ID != '0')  || is_null($_ID)){ $_condicionID 	= 	"TRUE";
		} else {$_condicionID	= "IdZona=".$_ID;}
    	$sql = "SELECT $_field FROM zonas WHERE ($_field2='$_ID') LIMIT 1";
		try {
			$data = $hDB->getOne($sql);
		} catch (Exception $e) {
			die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//-------------------
	//	Tabla ARTICULOS
	//-------------------
	public static function getArticulos( $_pag=0, $_rows=20, $mostrarStock, $mostrarTodos=NULL, $laboratorio=NULL, $empresa=NULL) {
		$hDB = DataManager::_getConnection();
		$_condicionStock	= ($mostrarStock) 	? "artstock=1"	:	"TRUE"; //!?
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"artactivo=".$mostrarTodos;}
		if  ((empty($laboratorio) && $laboratorio != '0')  || is_null($laboratorio)){ $_condicionLab 	= 	"TRUE";
		} else {$_condicionLab 	= 	"artidlab=".$laboratorio;}
		if  ((empty($empresa) && $empresa != '0')  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"artidempresa=".$empresa;}			
    	$sql = "SELECT * FROM articulo WHERE ($_condicionActivos) AND ($_condicionStock) AND ($_condicionLab) AND ($_condicionEmpresa) ORDER BY  artactivo DESC, artidart ASC";
		if (($_pag) && ($_rows)) {
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		} 
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//----------------------
  	// CAMPO DE UN ARTICULO
	//----------------------
	public static function getArticulo($_field=NULL, $_ID, $empresa=NULL, $laboratorio=NULL) {
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"1"; //"TRUE";
		} else {$_condicionEmpresa 	= 	"artidempresa=".$empresa;}
		if  ((empty($laboratorio) && $laboratorio != 0)  || is_null($laboratorio)){ $_condicionLaboratorio 	= 	"1"; //"TRUE";
		} else {$_condicionLaboratorio 	= 	"artidlab=".$laboratorio;}
		
		$_condicionArticulo	=	"artidart=".$_ID;
		
    	if ($_ID) {
      		$sql = "SELECT $_field FROM articulo WHERE ($_condicionArticulo) AND ($_condicionEmpresa) AND ($_condicionLaboratorio) LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
				$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}

	//----------------------
  	// CAMPO DE UN ARTICULO
	//----------------------
	public static function getArticuloAll($_field=NULL, $_field2=NULL, $_ID) { 
    	if ($_ID) {
      		$sql = "SELECT $_field FROM articulo WHERE ($_field2 LIKE '$_ID') ORDER BY $_field2"; //AND ($_condicionEmpresa)
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getAll($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}
	
	//-------------------
  	// BUSCAR ARTICULO
	//-------------------
	public static function getFieldArticulo($_field = NULL, $_value = NULL) {
		$hDB = DataManager::_getConnection();
		$_condicionCampo	=	"$_field = $_value";
    	$sql = "SELECT * FROM articulo WHERE ($_condicionCampo) AND artidempresa=1 AND artidlab=1";		
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//-------------------------
  	// TABLA ARTICULO DISPONE
	//-------------------------
	public static function getArticuloDispone( $id=NULL ) {
		$hDB = DataManager::_getConnection();
		if  (empty($id) || is_null($id)){ $_condicionID 	= 	"FALSE";
		} else {$_condicionID 	= 	"adartid=".$id;}	
		$sql = "SELECT * FROM articulodispone WHERE ($_condicionID) LIMIT 1";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//-------------------------
  	// TABLA ARTICULO FORMULA
	//-------------------------
	public static function getArticuloFormula( $id=NULL ) {
		$hDB = DataManager::_getConnection();
		if  (empty($id) || is_null($id)){ $_condicionID 	= 	"FALSE";
		} else {$_condicionID 	= 	"afidartdispone=".$id;}
		$sql = "SELECT * FROM articuloformula WHERE ($_condicionID) ORDER BY afid ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//-------------------------
  	// TABLA CODIGO FAMILIA ARTICULOS
	//-------------------------
	public static function getCodFamilias( $_pag=0, $_rows=0, $empresa=NULL, $familia=NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"Idempresa=".$empresa;}	
		if  ((empty($familia) && $familia != 0)  || is_null($familia)){ $_condicionFamilia 	= 	"TRUE";
		} else {$_condicionFamilia 	= 	"IdFamilia=".$familia;}	
		$sql = "SELECT * FROM codfamilia WHERE ($_condicionEmpresa) AND ($_condicionFamilia) ORDER BY Nombre ASC";
		if (($_pag) && ($_rows)) {
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		} 
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
		
	//-----------------
  	// TABLA RUBROS
	//-----------------
	public static function getRubros($id=NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($id) && $id != 0)  || is_null($id)){ $_condicionId 	= 	"TRUE";
		} else {$_condicionId	= 	"IdRubro=".$id;}	
		$sql = "SELECT * FROM rubros WHERE ($_condicionId) ORDER BY Descripcion";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}	

	//--------------------
	// Tabla PROVINCIAS
	//--------------------
  	public static function getProvincias() {
		$hDB = DataManager::_getConnection();
    	$sql = "SELECT * FROM provincia ORDER BY provid ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//--------------------
	// Select PROVINCIA
	//--------------------
	public static function getProvincia($_field=NULL, $_ID) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM provincia WHERE provid='$_ID' LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}

	//--------------------
	// Tabla LOCALIDADES
	//--------------------
	public static function getLocalidades($idLoc = NULL, $idProv = NULL, $zonaVend = NULL, $zonaEntrega = NULL, $_pag=0, $_rows=0) {
		$hDB = DataManager::_getConnection();		
		if  (empty($idLoc) || is_null($idLoc)){ $_condicionLocalidad 	= 	"TRUE"; }
		else {$_condicionLocalidad 	= 'locidloc='.$idLoc;}
		if  (empty($idProv)  || is_null($idProv)){ $_condicionProvincia 	= 	"TRUE"; }
		else {$_condicionProvincia 	= 'locidprov='.$idProv;}
		if  (empty($zonaVend)  || is_null($zonaVend)){ $_condicionZonaVend 	= 	"TRUE"; }
		else {$_condicionZonaVend 	= 'loczonavendedor='.$zonaVend;}
		if  (empty($zonaEntrega)  || is_null($zonaEntrega)){ $_condicionZonaEnt 	= 	"TRUE"; }
		else {$_condicionZonaEnt 	= 'loczonaentrega='.$zonaEntrega;}
		
		$sql = "SELECT * FROM localidad WHERE ($_condicionLocalidad) AND ($_condicionProvincia) AND ($_condicionZonaVend) AND ($_condicionZonaEnt) ORDER BY locidprov, locnombre ASC";
	
		if (($_pag) && ($_rows)) {
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		}
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//--------------------
	// Select LOCALIDAD
	public static function getLocalidad($_field=NULL, $_ID) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM localidad WHERE locidloc='$_ID' LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}
	

	//********************

	// Tabla CUIDADES

	//********************

	public static function getDirecciones() {

		$hDB = DataManager::_getConnection();

    	$sql = "SELECT * FROM direccion ORDER BY dirnombre ASC";	

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}	

	//********************
	// Tabla EMPRESAS
	//********************
	public static function getEmpresas($mostrarTodos = NULL) {
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"empactiva=".$mostrarTodos;}
		$hDB = DataManager::_getConnection();
    	$sql = "SELECT * FROM empresas WHERE ($_condicionActivos) ORDER BY empid ASC";	
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//********************
	// EMPRESA
	//********************
	public static function getEmpresa($_field=NULL, $_ID) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM empresas WHERE empid='$_ID' LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}

	//********************
	// Tabla LABORATORIOS
	//********************
	public static function getLaboratorios() {
		$hDB = DataManager::_getConnection();
    	$sql = "SELECT * FROM laboratorios WHERE labactivo=1 ORDER BY idLab";	
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}	
	
	//------------------
	// LABORATORIO
	//------------------
	public static function getLaboratorio($_field=NULL, $_ID) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM laboratorios WHERE idLab='$_ID' LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}

	//********************
	// Tabla CONDICIONES DE PAGO
	//********************
	public static function getCondicionesDePago($_pag=0, $_rows=0, $mostrarTodos = NULL, $condPago = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != 0) || is_null($mostrarTodos)){  $_condicionActivos 	= 	"TRUE";	} else {$_condicionActivos	= 	"condactiva=".$mostrarTodos;}	
		if  ((empty($condPago) && $condPago != 0) || is_null($condPago)){  $_condicionPago 	= 	"TRUE";	} else {$_condicionPago	= 	"IdCondPago=".$condPago;}	

		$sql = "SELECT * FROM condiciones_de_pago WHERE ($_condicionActivos) AND ($_condicionPago) ORDER BY condactiva DESC, 	Nombre1CP, 	Dias1CP ASC";	
		if (($_pag) && ($_rows)) {
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		}
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//********************
  	// CAMPO CONDICION DE PAGO
	//********************	
	public static function getCondicionDePago($_field=NULL, $_field2=NULL, $_ID=0) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM condiciones_de_pago WHERE ($_field2='$_ID') LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}
	
	//---------------------------------
	// Tabla CONDICIONES DE PAGO TIPO
	//---------------------------------
	public static function getCondicionesDePagoTipo($mostrarTodos = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != 0) || is_null($mostrarTodos)){  $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos	= 	"condactiva=".$mostrarTodos;}		

		$sql = "SELECT * FROM condiciones_de_pago_tipos WHERE ($_condicionActivos) ORDER BY condactiva DESC, 	Descripcion, ID ASC";	
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//********************
  	// CAMPO CONDICION DE PAGO TIPO
	//********************	
	public static function getCondicionDePagoTipos($_field=NULL, $_field2=NULL, $_ID=0) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM condiciones_de_pago_tipos WHERE ($_field2='$_ID') LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}

	

	//********************
	// Tabla CONDICIONES DE PAGO TRANSFER
	//********************
	public static function getCondicionesDePagoTransfer($_pag=0, $_rows=10, $mostrarTodos = NULL) {
		$hDB = DataManager::_getConnection();		
		if  ((empty($mostrarTodos) && $mostrarTodos != '0') || is_null($mostrarTodos)){  $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos	= 	"condactiva=".$mostrarTodos;}		
    	$sql = "SELECT * FROM condicion_de_pago_transfer WHERE ($_condicionActivos) ORDER BY condactiva DESC, conddias ASC";	
		if (($_pag) && ($_rows)) {
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		}
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	

	//********************
  	// CAMPO DE UNA CONDICION
	//********************
	public static function getCondicionDePagoTransfer($_field=NULL, $_field2=NULL, $_ID=0) {
    	if ($_ID) {
      		$sql = "SELECT $_field FROM condicion_de_pago_transfer WHERE ($_field2='$_ID') AND condidemp=1 LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}

	

	//********************

	// Tabla PACKS

	//********************

	public static function getPacks( $_pag=0, $_rows=10, $mostrarTodos = NULL, $_date = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0') || is_null($mostrarTodos)){  $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos	= 	"packactiva=".$mostrarTodos;}

		$_condicionDate	=	empty($_date) ? "TRUE" : "'".$_date."'"." BETWEEN packfechainicio AND packfechafin";

		

    	$sql = "SELECT * FROM pack WHERE ($_condicionActivos) AND ($_condicionDate) ORDER BY packfechainicio DESC, packnombre ASC";

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

			$data = $hDB->getAll($sql);

		} catch (Exception $e) {

			die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//********************

	// DETALLE PACK

	//********************

	public static function getDetallePack($_idpack) {

		$hDB = DataManager::_getConnection();

		$_condicionPack	= "pdpackid=".$_idpack;

    	$sql = "SELECT * FROM pack_detalle WHERE ($_condicionPack) ORDER BY pdartid ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}



	//***********************

	// DETALLE PLANIFICACION

	//***********************

	public static function getDetallePlanificacion($_fecha_planif, $idUsr) {

		list($dia, $mes, $ano) = explode('-', str_replace('/', '-', $_fecha_planif));	

 		$_fecha = $ano."-".$mes."-".$dia;

 

		$hDB = DataManager::_getConnection();

		$_condicionUsuario	=	"planifidvendedor=".$idUsr;

		$_condicionFecha	=	"planiffecha LIKE '".$_fecha."'";

    	$sql = "SELECT * FROM planificado WHERE ($_condicionUsuario) AND ($_condicionFecha)";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getDetallePlanifExportar($_fecha_inicio, $_fecha_fin) { 

		$hDB = DataManager::_getConnection();

		$_condicionFecha	=	"planiffecha BETWEEN '".$_fecha_inicio."' AND '".$_fecha_fin."'";

    	$sql = "SELECT * FROM planificado WHERE ($_condicionFecha) ORDER BY planiffecha, planifidvendedor ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getControlEnvioPlanif($_fecha_inicio, $_fecha_fin, $_ID){ 

		$hDB = DataManager::_getConnection();

		$_condicionFecha	=	"planiffecha BETWEEN '".$_fecha_inicio."' AND '".$_fecha_fin."'";

		$_condicionUsr		= 	"planifidvendedor=".$_ID;

    	$sql = "SELECT * FROM planificado WHERE( $_condicionUsr) AND($_condicionFecha) ORDER BY planiffecha, planifidvendedor ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//***********************

	// DETALLE PARTE DIARIO

	//***********************

	public static function getDetalleParteDiario($_fecha_parte, $idUsr) {	

		list($dia, $mes, $ano) = explode('-', str_replace('/', '-', $_fecha_parte));	

 		$_fecha = $ano."-".$mes."-".$dia;

 

		$hDB = DataManager::_getConnection();

		$_condicionUsuario	=	"parteidvendedor=".$idUsr;

		$_condicionFecha	=	"partefecha LIKE '".$_fecha."'";

    	$sql = "SELECT * FROM parte_diario WHERE ($_condicionUsuario) AND ($_condicionFecha) ORDER BY parteid ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getDetalleParteExportar($_fecha_inicio, $_fecha_fin) { 

		$hDB = DataManager::_getConnection();

		$_condicionFecha	=	"partefecha BETWEEN '".$_fecha_inicio."' AND '".$_fecha_fin."'";

    	$sql = "SELECT * FROM parte_diario WHERE ($_condicionFecha) ORDER BY partefecha, parteidvendedor ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getControlEnvioPartes($_fecha_inicio, $_fecha_fin, $_ID){ 

		$hDB = DataManager::_getConnection();

		$_condicionFecha	=	"partefecha BETWEEN '".$_fecha_inicio."' AND '".$_fecha_fin."'";

		$_condicionUsr		= 	"parteidvendedor=".$_ID;

    	$sql = "SELECT * FROM parte_diario WHERE ($_condicionUsr) AND ($_condicionFecha) ORDER BY partefecha, parteidvendedor ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//***********************************************************//

  	// DELETE los registros planificacos en dicha fecha y vendedor

	public static function deleteFromPlanificado($_ID, $_fecha) { 

		$_tabla				=	"planificado";

		$_condicionVend		=	"planifidvendedor=$_ID";

		$_condicionFecha	=	"planiffecha='".$_fecha."'";

    	$hDB = DataManager::_getConnection();

    	$hDB->startTransaction();

    	$_rows = 0;

    	try {

      		$_theSQL	= sprintf("DELETE FROM %s WHERE (%s) AND (%s)", $_tabla, $_condicionVend, $_condicionFecha);

      		$hDB->select($_theSQL);

      		$hDB->commit();

    	} catch (Exception $e) {

      		$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_rows;

  	} 

	

	//***********************************************************//

  	// DELETE los registros DEL PARTE en dicha fecha y vendedor

	public static function deleteFromParte($_ID, $_fecha) { 

		$_tabla				=	"parte_diario";

		$_condicionVend		=	"parteidvendedor=$_ID";

		$_condicionFecha	=	"partefecha='".$_fecha."'";

    	$hDB = DataManager::_getConnection();

    	$hDB->startTransaction();

    	$_rows = 0;

    	try {

      		$_theSQL	= sprintf("DELETE FROM %s WHERE (%s) AND (%s)", $_tabla, $_condicionVend, $_condicionFecha);

      		$hDB->select($_theSQL);

      		$hDB->commit();

    	} catch (Exception $e) {

      		$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_rows;

  	} 

	

	//********************

	// Tabla ACCIONES

	//********************

  	public static function getAcciones($_pag=0, $_rows=20, $mostrarTodos) {

		$hDB = DataManager::_getConnection();

    	$_condicionActivos = ($mostrarTodos)?	"acactiva=".$mostrarTodos	:	TRUE;

    	$sql = "SELECT * FROM accion WHERE ($_condicionActivos) ORDER BY acid ASC";

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//********************

	// Selecciona ACCION

	//********************

  	public static function getAccion($_ID = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionAccion	= ($_ID) ? "acid=".$_ID: "true";

    	$sql = "SELECT acnombre FROM accion WHERE ($_condicionAccion)";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//********************

	// Selecciona RENDICION

	//********************

  	public static function getRendicion($_ID = NULL, $_NroRend, $mostrarTodos = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionUsr			= "rendidusr	=	".$_ID;

    	$_condicionRendicion	= "rendnumero	=	".$_NroRend;

		$_condicionActivos 		= ($mostrarTodos == NULL)	?	"true"	:	"rendactiva=".$mostrarTodos;

    	$sql = "SELECT * FROM rendicion WHERE ($_condicionUsr) AND ($_condicionRendicion) AND ($_condicionActivos)";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql");

		}

		return $data;

  	}

	

	//***********************************

	// Selecciona Detalle de RENDICIÓN

	//***********************************

  	public static function getDetalleRendicion($_ID = NULL, $_NroRend, $mostrarTodos = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionUsr	= ($_ID) 		? "rendicion.rendidusr	=".$_ID			:	"true";

		$_condicionRend	= ($_NroRend) 	? "rendicion.rendnumero =".$_NroRend	:	"true";

		$_condicionActivos 		= ($mostrarTodos == NULL)	?	"true"	:	"rendactiva=".$mostrarTodos;

		$sql = "SELECT rendicion.rendid AS IDR, rendicion.rendactiva AS Activa, rendicion.rendretencion AS RetencionVend, rendicion.renddeposito AS Deposito, cuenta.ctaidcuenta AS Codigo, cuenta.ctanombre AS Nombre, cuenta.ctazonaentrega AS Zona, recibos.recid AS IDRecibo, recibos.rectalonario AS Tal, recibos.recnro AS RNro, recibos.recobservacion AS Observacion, recibos.recdiferencia AS Diferencia, facturas.factnro AS FNro, facturas.factfecha AS FFecha, facturas.factbruto AS Bruto, facturas.factdesc AS Dto, facturas.factneto AS Neto, facturas.factefectivo AS Efectivo, facturas.facttransfer AS Transf, facturas.factretencion AS Retencion, cheques.cheqid AS IDCheque, cheques.cheqbanco AS Banco, cheques.cheqnumero AS Numero, cheques.cheqfecha AS Fecha, cheques.cheqimporte AS Importe

				FROM rendicion

					INNER JOIN rend_rec 		ON rendicion.rendid 		=	rend_rec.rendid

					INNER JOIN recibos 			ON rend_rec.recid 			= 	recibos.recid

					LEFT JOIN rec_fact 			ON recibos.recid 			= 	rec_fact.recid

					LEFT JOIN facturas			ON rec_fact.factid 			= 	facturas.factid

					LEFT JOIN fact_cheq 		ON facturas.factid			=	fact_cheq.factid

					LEFT JOIN cheques 			ON fact_cheq.cheqid			= 	cheques.cheqid

					LEFT JOIN cuenta 			ON facturas.factidcliente 	= 	cuenta.ctaidcuenta

				WHERE ($_condicionUsr) AND ($_condicionRend) AND ($_condicionActivos) AND (cuenta.ctaidempresa = 1 OR cuenta.ctaidempresa IS NULL)

				ORDER BY recibos.recid, cuenta.ctaidcuenta, facturas.factnro, cheques.cheqid ASC

		";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//******************//

  	// DELETE Rendicion //

	//******************//

	public static function deleteRendicion($_ID) { 

		$_condicionRend	=	"rendid = ".$_ID;

    	$hDB = DataManager::_getConnection();

    	$hDB->startTransaction();

    	$_rows = 0;

    	try {

      		$_theSQL	= sprintf("	DELETE FROM rendicion									

									WHERE %s", $_condicionRend);

      		$hDB->select($_theSQL);

      		$hDB->commit();

    	} catch (Exception $e) {

      		$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_rows;

  	} 

	

	//**************************************//

  	// DELETE Recibo Rendicion Sin Factura

	//**************************************//

	public static function deleteReciboSinFantura($_ID) { 

		$_condicionRec	=	"recibos.recid = ".$_ID;

    	$hDB = DataManager::_getConnection();

    	$hDB->startTransaction();

    	$_rows = 0;

    	try {

      		$_theSQL	= sprintf("	DELETE rend_rec, recibos

									FROM rend_rec

										JOIN recibos ON recibos.recid = rend_rec.recid	

									WHERE %s", $_condicionRec);

      		$hDB->select($_theSQL);

      		$hDB->commit();

    	} catch (Exception $e) {

      		$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_rows;

  	} 

	

	//***********************************************************//

  	// DELETE Recibo Rendicion Sin Cheques

	//***********************************************************//

	public static function deleteReciboSinCheque($_ID){

    	$hDB = DataManager::_getConnection();

    	$hDB->startTransaction();

    	$_rows = 0;

    	try {

			$_theSQL	= sprintf("	DELETE rec_fact, facturas 

									FROM rec_fact 

										JOIN facturas ON facturas.factid = rec_fact.factid 

									WHERE rec_fact.recid=%s", $_ID);	

      		$hDB->select($_theSQL);

			$_theSQL	= sprintf("DELETE FROM rend_rec WHERE recid = %s", $_ID);

			$hDB->select($_theSQL);

			$_theSQL	= sprintf("DELETE FROM recibos WHERE recid = %s", $_ID);

			$hDB->select($_theSQL);			

			$hDB->commit();

    	} catch (Exception $e) {

      		$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_rows;

  	}

	

	public static function deleteChequesFactura($factID) { 

    	$hDB = DataManager::_getConnection();

    	$hDB->startTransaction();

    	$_rows = 0;

    	try {

			$_theSQL	= sprintf("	DELETE fact_cheq, cheques

									FROM fact_cheq

										LEFT OUTER JOIN cheques	ON fact_cheq.cheqid = cheques.cheqid	

									WHERE fact_cheq.factid=%s", $factID);	

      		$hDB->select($_theSQL);		

			$hDB->commit();

    	} catch (Exception $e) {

      		$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_rows;

  	} 

	

	public static function getFacturasRecibo($_ID){

		$hDB = DataManager::_getConnection();

		$_theSQL	= sprintf("SELECT factid FROM rec_fact WHERE recid = %s", $_ID);

		try {

	  		$data = $hDB->getAll($_theSQL);

		} catch (Exception $e) {

	  		die("error ejecutando $_theSQL");

		}

		return $data;

  	}

	

	

	

	

	

	//********************

	// Consulta NRO MAX de RENDICIÓN del Usuario

	//********************	

	public static function getMaxRendicion($_ID = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionUsr	= ($_ID) ? "rendidusr=".$_ID: "true";

		$sql = "SELECT MAX(rendnumero) AS maximo FROM rendicion WHERE ($_condicionUsr)";

    	try {

	  		$_numero = $hDB->getOne($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	//********************

	// Consulta RECIBOS según el talonario

	//********************

  	public static function getRecibos($_nroTal, $_nroRec = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionTal	= "rectalonario=".$_nroTal;

		$_condicionRec	= ($_nroRec)	?	"recnro=".$_nroRec	:	"true";

    	$sql = "SELECT * FROM recibos WHERE ($_condicionTal) AND ($_condicionRec)";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql");

		}

		return $data;

  	}

	

	//********************

	// Consulta NRO MAX de RECIBOS utilizados según el talonario

	//********************	

	public static function getMaxRecibo($_nroTal) {

		$hDB = DataManager::_getConnection();

		$_condicionTal	= "rectalonario=".$_nroTal;

		$sql = "SELECT MAX(recnro) AS maximo FROM recibos WHERE ($_condicionTal)";

		try {

	  		$_numero = $hDB->getOne($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	//********************

	// Consulta NRO MIN de RECIBOS utilizados según el talonario

	//********************	

	public static function getMinRecibo($_nroTal) {

		$hDB = DataManager::_getConnection();

		$_condicionTal	= "rectalonario=".$_nroTal;

		$sql = "SELECT MIN(recnro) AS minimo FROM recibos WHERE ($_condicionTal)";

		try {

	  		$_numero = $hDB->getOne($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	//********************

	// Consulta talonarios Incompletos

	//********************	

	public static function getTalonariosIncompletos() {

		$hDB = DataManager::_getConnection();

		$sql = "

		SELECT `recibos`.`rectalonario`, `recibos`.`recnro`,  `usuarios`.`unombre`

		FROM recibos

		LEFT JOIN  `talonario_idusr` ON  `talonario_idusr`.`nrotalonario` =  `recibos`.`rectalonario` 

		LEFT JOIN  `usuarios` ON  `usuarios`.`uid` =  `talonario_idusr`.`idusr`

		WHERE `usuarios`.`uactivo`= 1

		GROUP BY rectalonario

		HAVING (

		COUNT( recnro ) <25

		)

		";

		try {

	  		$_numero = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	//********************

	// Consulta de Recibos ANULADOS de talonarios PENDIENTES

	//********************	

	public static function getRecibosAnuladosPendientes() {

		$hDB = DataManager::_getConnection();

		$sql = "

		SELECT `recibos`.`rectalonario`, `recibos`.`recnro`,  `usuarios`.`unombre`

		FROM recibos

		LEFT JOIN  `talonario_idusr` ON  `talonario_idusr`.`nrotalonario` =  `recibos`.`rectalonario` 

		LEFT JOIN  `usuarios` ON  `usuarios`.`uid` =  `talonario_idusr`.`idusr` 

		WHERE `usuarios`.`uactivo`= 1

		GROUP BY rectalonario, recobservacion, recnro

		HAVING (

		COUNT( recnro ) <25

		)

		AND (

		recobservacion LIKE  'ANULADO'

		)

		ORDER BY  `recibos`.`rectalonario` ASC 

		";

		try {

	  		$_numero = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	

	//********************

	// Saber si hay Recibos

	//********************

	public static function getContarRecibos($_recid) {

		$hDB = DataManager::_getConnection();

		$_condicionRecibo	=	"rendid = ".$_recid;

		$sql = "SELECT COUNT(*) FROM rend_rec

				WHERE ($_condicionRecibo)

				";

		try {

	  		$_numero = $hDB->getOne($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	//********************

	// Saber si hay FACTURAS

	//********************

	public static function getContarFacturas($_recid) {

		$hDB = DataManager::_getConnection();

		$_condicionRecibo	=	"recid = ".$_recid;

		$sql = "SELECT COUNT(*) FROM rec_fact

				WHERE ($_condicionRecibo)

				";

		try {

	  		$_numero = $hDB->getOne($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	//********************

	// Saber si hay CHEQUES

	//********************

	public static function getContarCheques($_recid) {

		$hDB = DataManager::_getConnection();

		$_condicionRecibo	=	"rec_fact.recid = ".$_recid;

		$sql = "SELECT COUNT(*) FROM rec_fact

				INNER JOIN fact_cheq ON rec_fact.factid = fact_cheq.factid

				WHERE ($_condicionRecibo)

				";

		try {

	  		$_numero = $hDB->getOne($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $_numero;

  	}

	

	//********************

	// Selecciona BANCOS

	//********************

  	public static function getBancos() {

		$hDB = DataManager::_getConnection();

    	$sql = "SELECT * FROM banco ORDER BY nombre ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//**************************

	// BUSCAR NRO DE TALONARIO

	//**************************

  	public static function getBuscarTalonario($_nroTal) {

		$hDB = DataManager::_getConnection();

		$_condicionTalon 	= 	"nrotalonario=$_nroTal";

    	$sql = "SELECT * FROM talonario_idusr WHERE ($_condicionTalon)";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//************************//

	// DETALLE BONIFICACION

	//************************//

	public static function getDetalleBonificacion($_mes, $_anio) {

		$hDB = DataManager::_getConnection();

		$_condicionMes	= ($_mes)	?	"bonifmes=".$_mes	:	"bonifmes=".date("d");

		$_condicionAnio	= ($_anio)	?	"bonifanio=".$_anio	:	"bonifanio=".date("Y");

    	$sql = "SELECT * FROM bonificacion WHERE ($_condicionMes) AND ($_condicionAnio) ORDER BY bonifartid ASC";

		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//************************//

	// DETALLE BONIFICACIONES

	//************************//

	public static function getDetalleBonificaciones($_activa=NULL) {

		$_condicionActiva	= ($_activa)	?	"bonifactiva=".$_activa	:	"TRUE";

		$hDB = DataManager::_getConnection();

    	$sql = "SELECT * FROM bonificacion WHERE ($_condicionActiva) ORDER BY bonifid ASC";

		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//************************//

	// BONIFICACION de Artículo

	//************************//

	public static function getBonificacionArticulo($_ID = NULL, $_mes = NULL, $_anio = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionArt	=	($_ID)	?	"bonifartid	=".$_ID		:	true;

		$_condicionMes	=	($_mes)	?	"bonifmes	=".$_mes	:	true;

		$_condicionAnio	=	($_anio)?	"bonifanio	=".$_anio	:	true;

    	$sql = "SELECT * FROM bonificacion WHERE ($_condicionArt) AND ($_condicionMes) AND ($_condicionAnio)";		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//************************//
	// 		DETALLE ABM		  //
	//************************//
	public static function getDetalleAbm($_mes, $_anio, $_drogid, $_tipo = NULL) {
		$hDB = DataManager::_getConnection();
		$_condicionMes			= ($_mes)	?	"abmmes=".$_mes			:	"abmmes=".date("d");
		$_condicionAnio			= ($_anio)	?	"abmanio=".$_anio		:	"abmanio=".date("Y");
		$_condicionDrogueria	= ($_drogid)?	"abmdrogid=".$_drogid	:	0;
		$_condicionTipo			= ($_tipo)	?	"abmtipo='".$_tipo."'"	:	TRUE;

    	$sql = "SELECT * FROM abm WHERE ($_condicionMes) AND ($_condicionAnio) AND ($_condicionDrogueria) AND ($_condicionTipo) ORDER BY abmartid ASC";			
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	

	//****************************//

	// 	DETALLE ABM	por Artículo  //

	//****************************//

	public static function getDetalleArticuloAbm($_mes, $_anio, $_drogid, $_artID, $_tipo = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionMes			= ($_mes)	?	"abmmes=".$_mes			:	"abmmes=".date("d");

		$_condicionAnio			= ($_anio)	?	"abmanio=".$_anio		:	"abmanio=".date("Y");

		$_condicionDrogueria	= ($_drogid)?	"abmdrogid=".$_drogid	:	FALSE;

		$_condicionIDart		= ($_artID)	?	"abmartid=".$_artID		:	FALSE;

		$_condicionTipo			= ($_tipo)	?	"abmtipo='".$_tipo."'"	:	TRUE;

    	$sql = "SELECT * FROM abm WHERE ($_condicionMes) AND ($_condicionAnio) AND ($_condicionDrogueria) AND ($_condicionIDart) AND ($_condicionTipo)";		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//*****************************//

	//	DETALLE ABM	POR DROGUERÍA  //

	//*****************************//

	public static function getDetalleAbmDrogueria($_drogid, $_activo) {

		$hDB = DataManager::_getConnection();

		$_condicionDrogueria	=	($_drogid)?	"abmdrogid=".$_drogid	:	0;

		$_condicionActivo		=	($_activo)?	"abmactivo=".$_activo	:	TRUE;

    	$sql = "SELECT * FROM abm WHERE ($_condicionDrogueria) AND ($_condicionActivo)";		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}	

	

	//**********************//

	// LISTADO Tabla ABM	//

	//**********************//

	public static function getAbms($mostrarTodos = NULL, $desde = NULL, $hasta = NULL, $drogid = NULL) {

		$hDB = DataManager::_getConnection();	

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ 

			$_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"abmactivo=".$mostrarTodos;}

		if  (empty($drogid) || is_null($drogid)){ $_condicionDrogueria 	= 	"TRUE";

		} else {$_condicionDrogueria 	= 	"abmdrogid=".$drogid;}			

		

		$fechaInicio		=	new DateTime($desde);		

		$_condicionDate			=	"abmmes = '".$fechaInicio->format("m")."' AND abmanio = '".$fechaInicio->format("Y")."'";

		

    	$sql = "SELECT * FROM abm WHERE ($_condicionDate) AND ($_condicionDrogueria) AND ($_condicionActivos) ORDER BY abmid DESC";

		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//************************//

	//	DETALLE LIQUIDACION	  //

	//************************//

	public static function getDetalleLiquidacion($_mes=NULL, $_anio=NULL, $_drogid, $_tipo=NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionMes			= ($_mes)	?	"MONTH(liqfecha)	=".$_mes	:	"TRUE"; //"MONTH(liqfecha)	=".date("d");

		$_condicionAnio			= ($_anio)	?	"YEAR(liqfecha) 	=".$_anio	:	"TRUE"; // "YEAR(liqfecha) 	=".date("Y");

		$_condicionDrogueria	= ($_drogid)?	"liqdrogid=".$_drogid	:	0;

		$_condicionTipo			= ($_tipo)	?	"liqtipo='".$_tipo."'"	:	TRUE;

    	$sql = "SELECT * FROM liquidacion WHERE ($_condicionMes) AND ($_condicionAnio) AND ($_condicionDrogueria) AND ($_condicionTipo) ORDER BY liqnrotransfer, liqid ASC";		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//************************//

	//	DETALLE LIQUIDACIONES	  //

	//************************//

	public static function getDetalleLiquidaciones($mostrarTodos=NULL, $desde=NULL, $hasta=NULL, $drogid, $tipo=NULL) {

		$hDB = DataManager::_getConnection();	

		$_condicionDate	=	"liqfecha BETWEEN '".$desde."' AND '".$hasta."'";		

		$_condicionDrogueria	= ($drogid)?	"liqdrogid=".$drogid	:	0;

		$_condicionTipo			= ($tipo)	?	"liqtipo='".$tipo."'"	:	TRUE;

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"liqactiva=".$mostrarTodos;}

		

    	$sql = "SELECT * FROM liquidacion WHERE ($_condicionDate) AND ($_condicionDrogueria) AND ($_condicionTipo)  AND ($_condicionActivos) ORDER BY liqnrotransfer, liqid ASC";		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	

	//************************************//

	//	Detalle Liquidacion	por TRANSFER //

	//***********************************//

	public static function getDetalleLiquidacionTransfer($_ID, $_drogid, $_nroTransfer, $_ean) {

		$hDB = DataManager::_getConnection();

		$_condicionID		 = ($_ID)			?	"liqid	<> ".$_ID				:	TRUE;

		//$_condicionFecha	 = ($_fecha)		?	"liqfecha <> '".$_fecha."'"		:	"liqfecha < ".date("Y-m-d");

		$_condicionDrogueria = ($_drogid)		?	"liqdrogid=".$_drogid			:	TRUE;

		$_condicionTransfer	 = ($_nroTransfer)	?	"liqnrotransfer=".$_nroTransfer	:	FALSE;

		$_condicionEan		 = ($_ean)			?	"liqean=".$_ean					:	TRUE;

    	$sql = "SELECT * FROM liquidacion WHERE ($_condicionID) AND ($_condicionDrogueria) AND ($_condicionTransfer) AND ($_condicionEan) ORDER BY liqnrotransfer, liqid ASC"; 		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}	

	

	//********************

	// LISTADO TRANSFER

	//********************

	public static function getTransfersLiquidados($mostrarTodos = NULL, $_estado = NULL, $_drogueria = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"ptactivo=".$mostrarTodos;}		

		if  ((empty($_estado) && $_estado != 0)  || is_null($_estado)){ $_condicionEstado 	= 	"TRUE";

		} else {$_condicionEstado 	= 	"ptliquidado='".$_estado."'";}

		

		//La consulta muestra el listado de pedidos dentro de todos los ID droguería que tenga en mismo correo de TRANSFER

		if  ((empty($_drogueria) && $_drogueria != 0)  || is_null($_drogueria)){ $_condicionDrogueria 	= 	"TRUE";

		} else {$_condicionDrogueria 	= 	"ptiddrogueria IN (SELECT drogtcliid FROM droguerias WHERE drogtcorreotransfer IN (SELECT drogtcorreotransfer FROM droguerias WHERE drogtcliid = ".$_drogueria."))";}

						

		$sql = "SELECT DISTINCT ptidpedido, ptclirs, ptfechapedido FROM pedidos_transfer WHERE ($_condicionActivos) AND ($_condicionEstado) AND ($_condicionDrogueria) ORDER BY ptidpedido DESC";		

		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//***********************

  	// DELETE LIQUIDACIONES

	//***********************

	public static function deleteFromLiquidacion($_ID, $_fecha, $_tipo) { 

		$_tabla				=	"liquidacion";

		$_condicionDrog		=	"liqdrogid=".$_ID;

		$_condicionFecha	=	"liqfecha='".$_fecha."'";

		$_condicionTipo		=	"liqtipo='".$_tipo."'";

    	$hDB = DataManager::_getConnection();

    	$hDB->startTransaction();

    	$_rows = 0;

    	try {

      		$_theSQL	= sprintf("DELETE FROM %s WHERE (%s) AND (%s) AND (%s)", $_tabla, $_condicionDrog, $_condicionFecha, $_condicionTipo);

      		$hDB->select($_theSQL);

      		$hDB->commit();

    	} catch (Exception $e) {

      		$hDB->abort();

      		print "Transaccion abortada. ERR=" . $e->getMessage();

    	}

    	return $_rows;

  	} 
		

	//******************************//

	// LISTADO CONDICIONES ESPECIAL //

	//******************************//

	public static function getCondicionesEspeciales($empresa = NULL, $laboratorio = NULL, $mostrarTodos = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";

		} else {$_condicionEmpresa 	= 	"condidemp=".$empresa;}	

		if  ((empty($laboratorio) && $laboratorio != 0)  || is_null($laboratorio)){ $_condicionLaboratorio 	= 	"TRUE";

		} else {$_condicionLaboratorio 	= 	"condidlab=".$laboratorio;}

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"condactiva=".$mostrarTodos;}	

    	$sql = "SELECT * FROM condicion_especial WHERE ($_condicionEmpresa) AND ($_condicionLaboratorio) AND ($_condicionActivos) ORDER BY condidemp, condidlab, condidcliente ASC";		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	

	//**********************//

	// LISTADO DE CONTACTOS //

	//**********************//	

	public static function getContactosPorCuenta( $_ID = NULL, $_origen = NULL, $mostrarTodos) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"ctoactivo=".$mostrarTodos;}		

		$_condicionID 		= 	"ctoorigenid=".$_ID;

		$_condicionOrigen 	= 	"ctoorigen='".$_origen."'";

		if (!empty($_ID) && !empty($_origen)) {

			$sql = "SELECT * FROM contacto WHERE ($_condicionActivos) AND ($_condicionID) AND ($_condicionOrigen) ORDER BY ctoapellido ASC, ctonombre ASC";

			try {

				$data = $hDB->getAll($sql);

			} catch (Exception $e) {

				die("error ejecutando $sql<br>");

			}

			return $data;

		}

  	}

	

	

	//********************

	// Tabla SECTOR

	//********************

  	public static function getSectores($mostrarTodos = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"sectactivo=".$mostrarTodos;}

		$sql = "SELECT * FROM sector WHERE ($_condicionActivos) ORDER BY sectnombre ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//********************

	// Tabla PUESTO

	//********************

  	public static function getPuestos($mostrarTodos = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"ptoactivo=".$mostrarTodos;}

		$sql = "SELECT * FROM puesto WHERE ($_condicionActivos) ORDER BY ptonombre ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//**********************//

	//	Tabla RELEVAMIENTOS	//		

	//**********************//

  	public static function getRelevamientos( $_pag=0, $_rows=20, $mostrarTodos = TRUE) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"relactivo=".$mostrarTodos;}

		

    	$sql = "SELECT * FROM relevamiento WHERE ($_condicionActivos) ORDER BY relidrel DESC, relpregorden ASC, relactivo DESC";

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getRelevamiento( $_nroRel = NULL, $mostrarTodos = TRUE) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"relactivo=".$mostrarTodos;}

		if  (empty($_nroRel)  || is_null($_nroRel)){ $_condicionRelev = "FALSE";

		} else {$_condicionRelev 	= 	"relidrel=".$_nroRel;}

		

    	$sql = "SELECT * FROM relevamiento WHERE ($_condicionActivos) AND ($_condicionRelev) ORDER BY relpregorden ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//**********************//

	//	Tabla RESPUESTAS	//		

	//**********************//

  	public static function getRespuesta( $_ID = NULL, $_origen = NULL, $_IDRelevamiento = NULL, $mostrarTodos = NULL) {

		$hDB = DataManager::_getConnection();

		$_condicionID 		= 	"resorigenid=".$_ID;

		$_condicionOrigen 	= 	"resorigen='".$_origen."'";	

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"resactiva=".$mostrarTodos;}

			

		if  (empty($_IDRelevamiento) || is_null($_IDRelevamiento)){ $_condicionIdRelev 	= 	"TRUE";

		} else {$_condicionIdRelev 	= 	"resrelid=".$_IDRelevamiento;}

		

    	$sql = "SELECT * FROM respuesta WHERE ($_condicionID) AND ($_condicionOrigen) AND ($_condicionIdRelev) AND ($_condicionActivos)";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//******************//

	//	Tabla LLAMADAS	//		

	//******************//

  	public static function getLlamadas( $_pag=NULL, $_rows=NULL, $_IDorigen = NULL, $_origen = NULL, $mostrarTodos = NULL, $desde = NULL, $hasta = NULL) {
		$hDB = DataManager::_getConnection();
		$_condicionOrigen 	= 	"llamorigen='".$_origen."'";
		if  (!$_IDorigen){ $_condicionID = 	"TRUE";
		} else {$_condicionID  	= 	"llamorigenid=".$_IDorigen;}
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"llamactiva=".$mostrarTodos;}
		if  (empty($desde) || is_null($desde) || empty($hasta) || is_null($hasta)){ $_condicionFecha 	= 	"TRUE";
		} else {	$_condicionFecha 	= 	"llamlastupdate BETWEEN '".$desde."' AND '".$hasta."'";}

		$sql = "SELECT * FROM llamada WHERE ($_condicionID) AND ($_condicionOrigen) AND ($_condicionActivos) AND ($_condicionFecha) ORDER BY llamid DESC";

		if (($_pag) && ($_rows)) {
			$_from 		= ($_pag-1)*$_rows;
			$_offset	= " LIMIT $_rows OFFSET $_from";
			$sql 		.= $_offset;
		}	
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//----------------------------------//
	//	Tabla CONDICIONES COMERCIALES	//	
  	public static function getCondiciones( $_pag=0, $_rows=0, $mostrarTodos=NULL, $empresa=NULL, $laboratorio=NULL, $fecha=NULL, $tipo=NULL, $fechaDesde=NULL, $fechaHasta=NULL, $id=NULL, $lista=NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($id) && $id != '0') || is_null($id)){ $_condicionId 	= 	"TRUE";
		} else {$_condicionId 	= 	"condid=".$id;}
		if  ((empty($mostrarTodos) && $mostrarTodos != '0') || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"condactiva=".$mostrarTodos;}
		if  (empty($empresa) || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"condidemp=".$empresa;}
		if  (empty($laboratorio) || is_null($laboratorio)){ $_condicionLaboratorio 	= 	"TRUE";
		} else {$_condicionLaboratorio 	= 	"condidlab=".$laboratorio;}
		if  (empty($fecha) || is_null($fecha)){ $_condicionFecha 	= 	"TRUE";
		} else {$_condicionFecha 	= 	"'".$fecha."' BETWEEN condfechainicio AND condfechafin";}
		if  (empty($fechaDesde) || is_null($fechaDesde)){ $_condicionDesde 	= 	"TRUE";
		} else {$_condicionDesde 	= 	"condfechainicio >= '".$fechaDesde."'";}
		if  (empty($fechaHasta) || is_null($fechaHasta)){ $_condicionHasta 	= 	"TRUE";
		} else {$_condicionHasta 	= 	"condfechafin >= '".$fechaHasta."'";}	
		if  (empty($tipo) || is_null($tipo)){ $_condicionTipo	=	"TRUE";
		} else {$_condicionTipo 	= 	"condtipo=".$tipo;}
		if  ((empty($lista) && $lista != '0') || is_null($lista)){ $_condicionLista = "TRUE";
		} else {$_condicionLista = "condlista=".$lista;}

		$sql = "SELECT * FROM condicion WHERE ($_condicionId) AND ($_condicionEmpresa) AND ($_condicionActivos) AND ($_condicionLaboratorio) AND ($_condicionFecha) AND ($_condicionTipo) AND ($_condicionDesde) AND ($_condicionHasta) AND ($_condicionLista) ORDER BY condfechafin DESC, condactiva DESC, condtipo ASC, condnombre ASC";
		
		if (($_pag) && ($_rows)) {
			$_from 		= ($_pag-1)*$_rows;
			$_offset	= " LIMIT $_rows OFFSET $_from";
			$sql 		.= $_offset;
		}
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//---------------------------------------//
	//	Artículos de CONDICION COMERCIAL	//		
	public static function getCondicionArticulos($idCond = NULL, $order = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($idCond) && $idCond != '0') || is_null($idCond)){ $_condicionId	= 	"TRUE"; 
		} else {$_condicionId	=	"cartidcond=".$idCond; }
		
		if  ((empty($order) && $order <= 0) || is_null($idCond)){ $_condicionOrder	= 	"TRUE"; 
		} else {
			switch($order){
				case 1: $_condicionOrder	=	"cartoferta DESC";
					break;
				default: $_condicionOrder	= 	"TRUE";
					break;
			}			
		}		
		
    	$sql = "SELECT * FROM condicion_art WHERE ($_condicionId) ORDER BY $_condicionOrder, cartidart ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	

	//****************************************//

	//	Bonificaciones de CONDICION COMERCIAL //		

	//****************************************//

	public static function getCondicionBonificaciones($idCond = NULL, $idArt = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($idCond) && $idCond != '0') || is_null($idCond)){ $_condicionId	= 	"TRUE";

		} else {$_condicionId	=	"cbidcond=".$idCond; } 

		if  (empty($idArt) || is_null($idArt)){ $_condicionArt	=	"TRUE"; //"FALSE"

		} else {$_condicionArt	= 	"cbidart=".$idArt;}

		

    	$sql = "SELECT * FROM condicion_bonif WHERE ($_condicionId) AND ($_condicionArt) ORDER BY cbcant, cbidart ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	

	//******************************//

	//	SELECT Eventos de AGENDA	//		

	//******************************//

  	public static function getEventos($idUsr) { //, $start, $end

		if  ((empty($idUsr) && $idUsr != 0)  || is_null($idUsr)){ 	$condicionUsr 	= 	"NULL";

		} else {													$condicionUsr 	= 	"agidusr=".$idUsr;}

		$dtInicio	=	new DateTime("now");	

		$dtInicio->modify("-6 month");

		$dtFin		=	new DateTime("now");

		$dtFin->modify("+6 month");		 

		

		$hDB = DataManager::_getConnection();

    	$sql = "SELECT * FROM agenda WHERE ($condicionUsr) AND agstartdate BETWEEN '".$dtInicio->format("Y-m-d")."' AND '".$dtFin->format("Y-m-d")."'"; 

		// agstartdate BETWEEN '".$start."' AND '".$end."'"; //agstartdate BETWEEN '".$start."' AND '".$end."'"

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	

	//----------------
	// Tabla CUENTAS	
  	public static function getCuentas( $_pag = 0, $_rows = 0, $empresa = NULL, $mostrarTodos = NULL, $tipo = NULL, $zonas = NULL, $sort = 1, $estado = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"ctaactiva=".$mostrarTodos;}
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"ctaidempresa=".$empresa;}
		if  (empty($tipo) || is_null($tipo)){ $_condicionTipo 	= 	"TRUE";
		} else {$_condicionTipo 	= 	"ctatipo IN (".$tipo.")";}		
		if  ((empty($zonas) && $zonas != '0') || is_null($zonas)){  $_condicionZonas 	= 	"ctazona LIKE ''"; //Devuelve vacío ya que no existen cuentas sin zona
		} else {$_condicionZonas 	= 	"ctazona IN (".$zonas.")";} 
		
		if  (empty($estado) || is_null($estado)){ $_condicionEstado 	= 	"TRUE";
		} else {$_condicionEstado 	= 	"ctaestado='$estado'";}

		switch ($sort){
			case 1: 
				$condicionOrder = "ctaactiva DESC, ctaidprov ASC, ctaidloc ASC";
				break;
			case 2: 
				$condicionOrder = "ctaactiva DESC, ctanombre ASC";
				break;
			case 3: 
				$condicionOrder = "ctaupdate DESC";
				break;
			default: 
				$condicionOrder = "ctaactiva DESC";
				break;
		}

    	$sql = "SELECT * FROM cuenta WHERE ($_condicionActivos) AND ($_condicionEmpresa) AND ($_condicionTipo) AND ($_condicionZonas) AND ($_condicionEstado) ORDER BY $condicionOrder";
		if (($_pag) && ($_rows)) {
			$_from = ($_pag-1)*$_rows;
			$_offset = " LIMIT $_rows OFFSET $_from";
			$sql .= $_offset;
		}
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//------------------------------------------//
	// Select 1 campo de cuentas pasando el uid	//
	//------------------------------------------//
	public static function getCuenta($_field=NULL, $_field2=NULL, $_ID, $empresa=NULL) {
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"ctaidempresa=".$empresa;}	
    	if ($_ID) {
      		$sql = "SELECT $_field FROM cuenta WHERE ($_field2='$_ID') AND ($_condicionEmpresa) LIMIT 1";
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}	

	//---------------------------------------------
	// Select 1 campo de cuentas pasando el uid
	public static function getCuentaAll($_field=NULL, $_field2=NULL, $_ID, $empresa=NULL, $zonas=NULL) {
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= "TRUE";
		} else {$_condicionEmpresa 	= 	"ctaidempresa=".$empresa;}		
		if  ((empty($zonas) && $zonas != '0') || is_null($zonas)){
			$_condicionZonas = "TRUE"; //OJO! Al pasar TRUE devuelve cualquier zona, ésto es IMPORTANTE al controlar existencia de CUIT EN ALTA DE CUENTAS. En FALSE devuelve todas las posibles ZONAS, tener cuidado si un usuario no tiene zonas registradas al mostrar los datos.
		} else {
			$_condicionZonas = "ctazona IN (".$zonas.")";
		}
    	if ($_ID) {
      		$sql = "SELECT $_field FROM cuenta WHERE ($_field2 LIKE '$_ID') AND ($_condicionEmpresa) AND ($_condicionZonas)";
			
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getAll($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}
	
	
	
	//**************************//

	// Tabla Tipos de cuentas	//

	//**************************//

	public static function getTiposCuenta($mostrarTodos) {

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"ctaactiva=".$mostrarTodos;}

		$hDB = DataManager::_getConnection();

    	$sql = "SELECT * FROM cuenta_tipo WHERE ($_condicionActivos) ORDER BY ctatipo ASC";		

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function dac_consultarNumerosCuenta($empresa=NULL, $zona) {
		$hDB = DataManager::_getConnection();
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"ctaidempresa=".$empresa;}	
		$_digitosZona = strlen($zona)+4;
		$_condicionZona = "'".$zona."%1'";
		
		$sql = "SELECT ctaidcuenta
				FROM cuenta 
				WHERE ctaidcuenta LIKE $_condicionZona
				AND LENGTH(ctaidcuenta) = $_digitosZona
				AND ($_condicionEmpresa)
				ORDER BY ctaidcuenta ASC;";
		
		try {
			$data = $hDB->getAll($sql);
		} catch (Exception $e) {
			die("Error en el SGBD : Q = $sql<br>");
		}
		return $data;
  	}

	

	//---------------------------
	// Tabla CUENTAS RELACIONADAS
	//---------------------------
  	public static function getCuentasRelacionadas($idCta) { //$empresa, $idCuenta
		$hDB = DataManager::_getConnection();
		$_condicionCuenta 	= 	($idCta) ? "ctarelctaid=".$idCta : FALSE;//"ctarelidcuenta=".$idCuenta;
    	$sql = "SELECT * FROM cuenta_relacionada WHERE ($_condicionCuenta) ORDER BY ctarelidcuentadrog ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//-------------------------------
	// Tabla CATEGORÍAS COMERCIALES	
  	public static function getCategoriasComerciales($mostrarTodos = TRUE) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"catactiva=".$mostrarTodos;}
		$sql = "SELECT * FROM categoriacomercial WHERE ($_condicionActivos) ORDER BY catactiva DESC, catidcat ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	public static function getCategoriaComercial($_field=NULL, $_field2=NULL, $_ID) {
		if  ((empty($_ID) && $_ID != '0') || is_null($_ID)){ $_condicionID	=	"";
		} else {$_condicionID = "$_field2='".$_ID."'";}	
    	if ($_condicionID) {
      		$sql = "SELECT $_field FROM categoriacomercial WHERE ($_condicionID) LIMIT 1";
			$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}

	

	//********************

	// Tabla CATEGORÍAS IVA

	//********************

  	public static function getCategoriasIva($mostrarTodos = TRUE) {

		$hDB = DataManager::_getConnection();

		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";

		} else {$_condicionActivos 	= 	"catactiva=".$mostrarTodos;}

		$sql = "SELECT * FROM categoriaIVA WHERE ($_condicionActivos) ORDER BY catactiva DESC, catnombre ASC";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	//--------------------
	// Tabla CADENAS
	//-------------------
  	public static function getCadenas($empresa=NULL, $idCadena=NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"IdEmpresa=".$empresa;}	
		if  ((empty($idCadena) && $idCadena != 0)  || is_null($idCadena)){ $_condicionCadena 	= 	"TRUE";
		} else {$_condicionCadena 	= 	"IdCadena=".$idCadena;}	
		
		$sql = "SELECT * FROM cadenas WHERE ($_condicionEmpresa) AND ($_condicionCadena) ORDER BY NombreCadena ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}

	//-------------------------
	// Select CUENTAS CADENAS
	//-------------------------
  	public static function getCuentasCadena($empresa=NULL, $cadena=NULL, $cuenta=NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmpresa 	= 	"TRUE";
		} else {$_condicionEmpresa 	= 	"IdEmpresa=".$empresa;}	
		if  ((empty($cadena) && $cadena != 0)  || is_null($cadena)){ $_condicionCadena 	= 	"TRUE";
		} else {$_condicionCadena 	= 	"IdCadena=".$cadena;}	
		if  ((empty($cuenta) && $cuenta != 0)  || is_null($cuenta)){ $_condicionCuenta 	= 	"TRUE";
		} else {$_condicionCuenta 	= 	"IdCliente=".$cuenta;}	
		$sql = "SELECT * FROM cnCadenasClientes WHERE ($_condicionEmpresa) AND ($_condicionCadena) AND ($_condicionCuenta) ORDER BY IdCadena ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}	

	//--------------------
	// Tabla PROPUESTAS
  	public static function getPropuestas($idCuenta=NULL, $mostrarTodos=NULL, $idUsr=NULL, $dateFrom=NULL, $dateTo=NULL) {
		$hDB = DataManager::_getConnection();
		if  (empty($idCuenta)  || is_null($idCuenta)){ $_condicionCuenta	= 	"TRUE";
		} else {$_condicionCuenta 	= 	"propidcuenta=".$idCuenta;}		
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= "propactiva=".$mostrarTodos;}	
		if  (empty($idUsr) || is_null($idUsr)){ $_condicionUsr 	= 	"TRUE";
		} else { $_condicionUsr		= 	"propusr=".$idUsr;}
		if  (empty($dateFrom) || is_null($dateFrom)){ $_conditionFrom 	= 	"TRUE";
		} else {$_conditionFrom 	= 	"propfecha >= '".$dateFrom."'";}
		if  (empty($dateTo) || is_null($dateTo)){ $_conditionTo 	= 	"TRUE";
		} else {$_conditionTo 	= 	"propfecha <= '".$dateTo."'";}			

		$sql = "SELECT * FROM propuesta WHERE ($_condicionCuenta) AND ($_condicionActivos) AND ($_condicionUsr) AND ($_conditionFrom) AND ($_conditionTo) ORDER BY propfecha DESC";

		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	

	public static function getPropuesta($idPropuesta=NULL) {

		$hDB = DataManager::_getConnection();

		if  (empty($idPropuesta)  || is_null($idPropuesta)){ $_condicionID	= 	NULL;

		} else {$_condicionID 	= 	"propid=".$idPropuesta;}		

		$sql = "SELECT * FROM propuesta WHERE ($_condicionID)";

		try {

	  		$data = $hDB->getAll($sql);

		} catch (Exception $e) {

	  		die("error ejecutando $sql<br>");

		}

		return $data;

  	}
	

	public static function getPropuestaDetalle($idPropuesta=NULL, $mostrarTodos = NULL) {
		$hDB = DataManager::_getConnection();
		if  (empty($idPropuesta)  || is_null($idPropuesta)){ $_condicionID	= 	NULL;
		} else {$_condicionID 	= 	"pdpropid=".$idPropuesta;}	
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos 	= 	"TRUE";
		} else {$_condicionActivos 	= 	"pdactivo=".$mostrarTodos;}
		
		$sql = "SELECT * FROM propuesta_detalle WHERE ($_condicionID) AND ($_condicionActivos) ORDER BY pdidart ASC";

		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}

		return $data;

  	}

	

	public static function getEstado($_field=NULL, $_field2=NULL, $_ID) {

		if  ((empty($_ID) && $_ID != '0')  || is_null($_ID)){ $_condicionID	=	"";

		} else {$_condicionID = "$_field2='".$_ID."'";}	

    	if ($_condicionID) {

      		$sql = "SELECT $_field FROM estados WHERE ($_condicionID) LIMIT 1"; //($_field2='$_ID')

      		$hDB = DataManager::_getConnection();

      		try {

        		$data = $hDB->getOne($sql);

      		} catch (Exception $e) {

        		die("Error en el SGBD : Q = $sql<br>");

      		}

      		return $data;

    	}

  	}

	

	public static function getTicket($_pag = 0, $_rows = 0, $_ID = NULL, $usrCreate = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($_ID) && $_ID != '0')  || is_null($_ID)){ $_condicionID	= 	TRUE;

		} else {$_condicionID 	= 	"tkid=".$_ID;}	

		if  ((empty($usrCreate) && $usrCreate != '0')  || is_null($usrCreate)){ $_condicionUsrId	= 	TRUE;

		} else {$_condicionUsrId 	= 	"tkusrcreated=".$usrCreate;}	

		$sql = "SELECT * FROM ticket WHERE ($_condicionID) AND ($_condicionUsrId) ORDER BY tkid DESC";

		if (($_pag) && ($_rows)) {

			$_from = ($_pag-1)*$_rows;

			$_offset = " LIMIT $_rows OFFSET $_from";

			$sql .= $_offset;

		}

		try {

			$data = $hDB->getAll($sql);

		} catch (Exception $e) {

			die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getTicketSector() {

		$hDB = DataManager::_getConnection();

		$sql = "SELECT * FROM ticket_sector";

		try {

			$data = $hDB->getAll($sql);

		} catch (Exception $e) {

			die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getTicketMotivos($_IdSector = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($_IdSector) && $_IdSector != '0')  || is_null($_IdSector)){ $_condicionSector	= 	TRUE;

		} else {$_condicionSector 	= 	"tkmotidsector=".$_IdSector;}	

		$sql = "SELECT * FROM ticket_motivo WHERE ($_condicionSector)";

		try {

			$data = $hDB->getAll($sql);

		} catch (Exception $e) {

			die("error ejecutando $sql<br>");

		}

		return $data;

  	}

	

	public static function getTicketMensajes($idTicket = NULL) {

		$hDB = DataManager::_getConnection();

		if  ((empty($idTicket) && $idTicket != '0')  || is_null($idTicket)){ $_condicionTicket 	= 	"FALSE";

		} else {$_condicionTicket 	= 	"tkmsgidticket=".$idTicket;}		

		$sql = "SELECT * FROM ticket_mensaje WHERE ($_condicionTicket)";

		try {

			$data = $hDB->getAll($sql);

		} catch (Exception $e) {

			die("error ejecutando $sql<br>");

		}

		return $data;

  	}
	
	//--------------------------
	// Tabla LISTAS de Precios
  	public static function getListas( $mostrarTodos = NULL, $id = NULL) {
		$hDB = DataManager::_getConnection();
		if  ((empty($mostrarTodos) && $mostrarTodos != '0')  || is_null($mostrarTodos)){ $_condicionActivos = "TRUE";
		} else {$_condicionActivos 	= 	"Activa=".$mostrarTodos; }
		if  ((empty($id) && $id != 0)  || is_null($id)){ $_condicionLista =	"TRUE";
		} else {$_condicionLista 	= 	"IdLista=".$id;}
    	$sql = "SELECT * FROM listas WHERE ($_condicionLista) AND ($_condicionActivos) ORDER BY IdLista ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	public static function getLista($_field=NULL, $_field2=NULL, $_ID) {
		if  ((empty($_ID) && $_ID != '0')  || is_null($_ID)){ $_condicionID	=	"";
		} else {$_condicionID = "$_field2='".$_ID."'";}	
    	if ($_condicionID) {
      		$sql = "SELECT $_field FROM listas WHERE ($_condicionID) LIMIT 1"; //($_field2='$_ID')
      		$hDB = DataManager::_getConnection();
      		try {
        		$data = $hDB->getOne($sql);
      		} catch (Exception $e) {
        		die("Error en el SGBD : Q = $sql<br>");
      		}
      		return $data;
    	}
  	}
	
	//--------------------------
	// Tabla MOVIMIENTOS
  	public static function getMovimientos( $_pag = 0, $_rows = 0 ) {
		$hDB = DataManager::_getConnection();
    	$sql = "SELECT * FROM movimiento ORDER BY movid DESC";
		
		if (($_pag) && ($_rows)) {
			$_from 		= ($_pag-1)*$_rows;
			$_offset	= " LIMIT $_rows OFFSET $_from";
			$sql 		.= $_offset;
		}
		
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
} ?>