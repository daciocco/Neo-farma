<?php
require_once('class.Database.php');
require_once('classHiper/class.cuenta.php');
require_once('classHiper/class.articulo.php');

class DataManagerHiper {
	//-----------------------------------------------------------------------------
	// 	FUNCIONES GENERICAS
	//-----------------------------------------------------------------------------
	public static function _getConnection() {
		static $hDB;
		try {
			$hDB = Database::instanceHiper();
		} catch (Exception $e) {
	  		die("Imposible conexion con BBDD<BR>");
		}
		return $hDB; 
  	}
	
	public static function newObjectOfClass($_class=null, $_id=NULL, $_idTwo=NULL, $_idThree=NULL) {
		$_object = null;
  		if (!empty($_class)) {
  			if (class_exists($_class)) {
				if(empty($_idTwo)){
					$_object = new $_class($_id);
				}elseif(empty($_idThree)){
					$_object = new $_class($_id, $_idTwo);
				} else {
					$_object = new $_class($_id, $_idTwo, $_idThree);
				}
			}
    	}
    	return $_object;
  	}
	
  	public static function loadFromDatabase($_object=null, $_id=NULL, $_idTwo=NULL, $_idThree=NULL) {
		if (!empty($_object)) {
			
			if(empty($_idTwo) || is_null($_idTwo)){
				$_sql = sprintf("SELECT TOP 1 * FROM %s WHERE %s='%d'", $_object->__getTableName(), $_object->__getFieldID(), $_id);
			}elseif(empty($_idThree) || is_null($_idThree)){
				$_sql = sprintf("SELECT TOP 1 * FROM %s WHERE %s='%d' AND %s='%d'", $_object->__getTableName(), $_object->__getFieldID(), $_id, $_object->__getFieldIDTwo(), $_idTwo);
			} else {
				$_sql = sprintf("SELECT TOP 1 * FROM %s WHERE %s='%d' AND %s='%d' AND %s='%d'", $_object->__getTableName(), $_object->__getFieldID(), $_id, $_object->__getFieldIDTwo(), $_idTwo, $_object->__getFieldIDThree(), $_idTThree);	
			}			
			$hDB = DataManagerHiper::_getConnection();
	    	try {
				$data = $hDB->getAll($_sql);
	    	} catch (Exception $e) {
	    		die("error ejecutando $_sql<br>");
	    	}
	    	return $data;
  		}
  		return null;
  	}
	  		
	// SIMPLE OBJECT
  	// CLASES DERIVADAS DE PropertyObject y que tengan propiedad == ID
  	//  
  	public static function updateSimpleObject($_object, $_ID=NULL) { //, $_fieldTwo=NULL, $_fieldThree=NULL
    	$hDB 	 = DataManagerHiper::_getConnection();
    	$_rows	 = 0;
    	$_fields = $_object->__getUpdated();
		if (count($_fields) > 0) {
      		$hDB->startTransaction();
      		try {
				//$_ID		= $_object->__get('ID');
				$_fieldID 	= $_object->__getFieldName('ID');				
				/*if(empty($_fieldTwo) || is_null($_fieldTwo)){
					$sWhere = "$_fieldID";
				}elseif(empty($_fieldThree) || is_null($_fieldThree)){
					$sWhere = "$_fieldID AND $_fieldTwo";
				} else {
					$sWhere = "$_fieldID AND $_fieldTwo AND $_fieldThree";
				}	*/				
				//$_rows 		= $hDB->update($_object->__getTableName(), $_fields, "$_fieldID=$_ID");
        		$_rows 		= $hDB->update($_object->__getTableName(), $_fields, "$_fieldID=$_ID"); //$sWhere
				$hDB->commit();
      		} catch (Exception $e) {
        		$hDB->abort();
        		print "Transacci&oacute;n abortada. ERR=" . $e->getMessage();
      		}
    	}
    	return $_rows;
  	}
	
  	public static function deleteSimpleObject($_object, $_ID=NULL) { //, $_fieldTwo=NULL, $_fieldThree=NULL
    	$hDB = DataManagerHiper::_getConnection();
    	$hDB->startTransaction();
    	$_rows = 0;
    	try {	
			//$_ID		= $_object->__get('ID');
	  		$_fieldID 	= $_object->__getFieldName('ID');
			/*
			if(empty($_fieldTwo)){
				$sWhere = "$_fieldID";
			}elseif(empty($_fieldThree)){
				$sWhere = "$_fieldID AND $_fieldTwo";
			} else {
				$sWhere = "$_fieldID AND $_fieldTwo AND $_fieldThree";
			}*/
			$_theSQL	= sprintf("DELETE FROM %s WHERE %s=%s", $_object->__getTableName(), $_fieldID, $_ID);
      		//$_theSQL	= sprintf("DELETE FROM %s WHERE %s", $_object->__getTableName(), $sWhere);
			$_rows 		= $hDB->select($_theSQL);
      		$hDB->commit();
    	} catch (Exception $e) {
      		$hDB->abort();
      		print "Transaccion abortada. ERR=" . $e->getMessage();
    	}
    	return $_rows;
  	}

  	public static function insertSimpleObject($_object) {    	
    	$hDB = DataManagerHiper::_getConnection();
		$hDB->startTransaction();
		$_ID = 0;
    	try {			
      		$_ID = $hDB->insert($_object->__getTableName(), $_object->__getData());
			$hDB->commit();
      		//$_object->__set('ID', $_ID); // Para que el objeto quede consistente con la BD
    	} catch (Exception $e) {
			$hDB->abort();
      		print "Transaccion abortada. ERR=" . $e->getMessage();
    	}
    	return $_ID;
  	}
	
	//------------------------------
	// FUNCIONES SIN USO DE CLASES
	//------------------------------
	/*public static function deletefromtabla($_tabla, $_fieldID, $_ID) {
    	$hDB = DataManagerHiper::_getConnection();
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
  	}*/
	
	/*public static function insertfromtabla($_tabla, $_fieldID, $_ID, $_values) {
    	$hDB = DataManagerHiper::_getConnection();
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
  	}*/
	
	//---------------------------------------------------------
	//Para INSERTAR DATOS en tablas que no tienen Campo UNIQUE
	//---------------------------------------------------------
	/*
	public static function insertToTable($_tabla, $_fieldID, $_values, $_ID=0) {
    	$hDB = DataManagerHiper::_getConnection();
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
    	$hDB = DataManagerHiper::_getConnection();
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
    	$hDB = DataManagerHiper::_getConnection();
    	try {
			$_theSQL	= sprintf("DELETE FROM %s WHERE (%s)", $_tabla, $_condicionWhere);
      		$hDB->select($_theSQL);
	      	$hDB->commit();
    	} catch (Exception $e) {
	      	$hDB->abort();
      		print "Transaccion abortada. ERR=" . $e->getMessage();
    	}
    	return $_ID;
  	}*/
	
	
	//----------------------------------------------------------------------------
	// 	RESTO DE FUNCIONES
	//----------------------------------------------------------------------------
	
	//------------------
	// Tabla Conddompra
	//------------------
  	public static function getCondCompra( $empresa = NULL, $laboratorio = NULL, $idArt = NULL) {
		$hDB = DataManagerHiper::_getConnection('Hiper');
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmp = "idEmpresa LIKE '%'";
		} else {$_condicionEmp 	= 	"idEmpresa=".$empresa;}	
		if  ((empty($laboratorio) && $laboratorio != 0)  || is_null($laboratorio)){ $_condicionLab = "idLab LIKE '%'";
		} else {$_condicionLab = "idLab=".$laboratorio;}		
		if  ((empty($idArt) && $idArt != 0)  || is_null($idArt)){ $_condicionArt = "idArt LIKE '%'";
		} else {$_condicionArt = "idArt=".$idArt;}
    	$sql = "SELECT * FROM CondCompra WHERE ($_condicionEmp) AND ($_condicionLab) AND ($_condicionArt) ORDER BY idArt ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//------------------
	// Tabla EquivUnid
	//------------------
  	public static function getEquivUnid( $empresa = NULL, $laboratorio = NULL, $idArt = NULL) {
		$hDB = DataManagerHiper::_getConnection('Hiper');
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmp = "idEmpresa LIKE '%'";
		} else {$_condicionEmp 	= 	"idEmpresa=".$empresa;}	
		if  ((empty($laboratorio) && $laboratorio != 0)  || is_null($laboratorio)){ $_condicionLab = "idLab LIKE '%'";
		} else {$_condicionLab = "idLab=".$laboratorio;}		
		if  ((empty($idArt) && $idArt != 0)  || is_null($idArt)){ $_condicionArt = "idArt LIKE '%'";
		} else {$_condicionArt = "idArt=".$idArt;}
    	$sql = "SELECT * FROM EquivUnid WHERE ($_condicionEmp) AND ($_condicionLab) AND ($_condicionArt) ORDER BY idArt ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	//------------------
	// Tabla Empresas
	//------------------
  	public static function getEmpresas( $empresa = NULL ) {
		$hDB = DataManagerHiper::_getConnection('Hiper');
		if  ((empty($empresa) && $empresa != 0)  || is_null($empresa)){ $_condicionEmp = "IdEmpresa LIKE '%'";
		} else {$_condicionEmp 	= 	"IdEmpresa=".$empresa;}	
		$sql = "SELECT * FROM Empresas WHERE ($_condicionEmp) ORDER BY IdEmpresa ASC";
		try {
	  		$data = $hDB->getAll($sql);
		} catch (Exception $e) {
	  		die("error ejecutando $sql<br>");
		}
		return $data;
  	}
	
	
	
	
} ?>