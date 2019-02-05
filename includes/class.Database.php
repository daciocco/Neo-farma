<?php
class Database {  //extends PropertyDB {
	private $conn;	
	protected static $instance;
	protected static $instanceHiper;
	
	public function __construct($dsn = '') {
		try {
			$this->conn = $this->connect($dsn);
		} catch(PDOException $e) {
			echo "Error: ".$e." Mensage: ".$e->getMessage();
		}
	}
	
	public static function instance() {
		if ( !isset( self::$instance ) ) {
			try {
            	self::$instance = new Database();				
			} catch(PDOException $e) {
				echo "Error: ".$e." Mensage: ".$e->getMessage();
			}
        }
        return self::$instance;	
	}
	
	public static function instanceHiper() {
		if ( !isset( self::$instanceHiper ) ) {
			try {
            	self::$instanceHiper = new Database('Database');				
			} catch(PDOException $e) {
				echo "Error: ".$e." Mensage: ".$e->getMessage();
			}
        }
        return self::$instanceHiper;	
	}
	
	public function connect($dsn='') {
		$config	= 	(require 'config.php');	
		$dbConnectionName = ($dsn==="Database") ? "Database" : "dbname";		
		$dbconn = $config[$dbConnectionName]['connection'].";".$dbConnectionName."=".$config[$dbConnectionName]['name'].";".$config[$dbConnectionName]['charset'];
		$dbusr	= $config[$dbConnectionName]['username'];
		$dbpass = $config[$dbConnectionName]['password'];
		$dbconf	= $config[$dbConnectionName]['options'];
		try {
			$this->conn = new PDO($dbconn, $dbusr, $dbpass, $dbconf);
			//Si option no funciona, debo usar: para el control de excepciones funcione
			//$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->conn;
		} catch (PDOException $e){			
			echo "ERROR de conexión a DDBB ".$config[$dbConnectionName]['name'].". Consulte con el administrador web."; exit;//  . $e->getMessage(); //die ($e->getMessage());
			//Redirigir a una página de error de conexión a DDBB
			//include($_SERVER['DOCUMENT_ROOT']."/pedidos/errorConnectionDDBB.php"); die();	
		}
		catch(Exception $e) {            
            //TODO: flag to disable errors?
            throw $e;            
        }
	}
	
	//returns 2D assoc array
	function getAll($sql) {				
		//$result = $this->ejecutar($sql);		
		try{	
			# Prepare query
			$sth 	= $this->conn->prepare($sql);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException  $e ){
			echo "Error: ".$e." Mensage: ".$e->getMessage();
		}
		return $result;		
	}
  
  	//returns single scalar value from the first column, first record
	function getOne($sql) {
		$result = "";
		try{
			//$result = $this->ejecutar($sql);
			# Prepare query
			$sth 	= $this->conn->prepare($sql);
			$sth->execute();
			$result 	= $sth->fetch(PDO::FETCH_NUM)[0];
		//$result = $result->fetch_row()[0];
		} catch(PDOException  $e ) {
			echo "Error: ".$e." Mensage: ".$e->getMessage();
		}			
		return $result;
  	}
	
	//ERROR
	public static function isError($result){
		if (!$result) {
			return true;
		} else {
			return false;
		}
	}
  
	public function __destruct() {
		$this->disconnect();
	}
	
	public function disconnect(){	
		$this->conn = null;
	}	
  
	//returns a DB_result object  
	function select($sql) {
		try {
			$result = $this->conn->query($sql);
		} catch (PDOException $e) {
			echo "Error: ".$e." Mensage: ".$e->getMessage();
		}		
		return $result;
	}
	
	//returns numerically indexed 1D array of values from the first column
	function getColumn($sql) {
		try {
			$result = $this->conn->getCol($sql);
		} catch (PDOException $e) {
			echo "Error: ".$e." Mensage: ".$e->getMessage();
		}
		return $result;
	}
  
	function update($tableName, $arUpdates, $sWhere = null) {
		$arSet = array();
		foreach($arUpdates as $name => $value) {
			if(empty($value) && $value != 0){$value = "";}
			$arSet[] = $name . ' = ' . $this->conn->quote($value) ; //PERL->quoteSmart($value) //php 7  real_escape_string($value)
		}		
		$sSet = implode(', ', $arSet);
		//make sure the table name is properly escaped
		//$tableName = $this->conn->quote($tableName);   //quoteIdentifier($tableName)		
		$sql = "UPDATE $tableName SET $sSet";
		if($sWhere) {
			$sql .= " WHERE $sWhere";
		}
		try {
			$sth 	= $this->conn->prepare($sql);
			$result	= $sth->execute();
			//$result = $this->conn->query($sql);
		} catch (PDOException $e) {
			echo "Error: ".$e." Mensage: ".$e->getMessage();
		}		
		return $sth->rowCount();
	}
  	
	function nextId($seqname) { //$seqname, $ondemand = true
		$tableName = $seqname."_seq";
		
		//consulto si existe la tabla de sequencia
		$sql 		= "SHOW TABLES LIKE '$tableName'";
		$result 	= $this->getOne($sql);
		
		//NO HAY RESULTADO DE LA CONSULTA
		if($result){
			//consulto nextID
			$sql2 	= 	"SELECT AUTO_INCREMENT
						FROM information_schema.tables
						WHERE table_name = '$tableName'
						"; //AND table_schema = DATABASE( ) 
			$_ID 	= $this->getOne($sql2);	
			
			//Modifica el autoincremental
			try {
				$sql3 	= "ALTER TABLE $tableName AUTO_INCREMENT=$_ID";
				$sth 	= $this->conn->prepare($sql3);
				$sth->execute();
			} catch (PDOException $e) {
				echo "Error: ".$e." Mensage: ".$e->getMessage();
			}	
			
			//modifico la tabla del autoincremental
			try {
				$arUpdates = array();
				$arUpdates['id'] = $_ID;			
				$data	=	$this->update($tableName, $arUpdates);
			} catch (PDOException $e) {
				echo "Error: ".$e." Mensage: ".$e->getMessage();
			}	
			return $_ID;
		} else {			
			//crear la tabla
			try {
				$sql 	= "CREATE TABLE $tableName (id int NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))";
				$sth	= $this->conn->prepare($sql);
				$sth->execute();
			} catch (PDOException $e) {
				echo "Error: ".$e." Mensage: ".$e->getMessage();
			}
			
			//insertar primer registro
			try {
				$sql 	= "INSERT INTO $tableName (id) VALUES (1)";
				$sth	= $this->conn->prepare($sql);
				$sth->execute();
				return 1;
			} catch (PDOException $e) {
				echo "Error: ".$e." Mensage: ".$e->getMessage();
			}			
		}
    }

	function insert($tableName, $arValues) {
		$id 		 = null;
		$sFieldList	 = join(', ', array_keys($arValues));
		$arValueList = array();
		
		foreach ($arValues as $value){
			if(!empty($value)){
				if($value[0] == '#') { //if($value{0} == '#') {
					//we need to get the next value from this table's sequence					
					$value = $id = $this->nextID($tableName . '_' . strtolower(substr($value,1)));
				}
			}
			$arValueList[] = $this->conn->quote($value);
		}
		$sValueList = implode(', ', $arValueList);		
		
		//make sure the table name is properly escaped
		$sql = "INSERT INTO $tableName ( $sFieldList ) VALUES ( $sValueList )";			
		try {
			$sth 	= $this->conn->prepare($sql);
			$sth->execute();
		} catch (PDOException $e) {
			echo "Error: ".$e." Mensage: ".$e->getMessage(); exit;
		}
		//return the ID, if there was one, or the number of rows affected
		//return $id ? $id : $this->conn->affected_rows;
		return $id ? $id : $sth->rowCount();
		
	}
  
	function startTransaction() {
		// PDO beginTransaction, deshabilita el modo de confirmación automática
		$this->conn->beginTransaction();
	}
  
	function commit() {
		try {
			$result = $this->conn->commit();
		} catch (PDOException $e) {
			echo "Error: ".$e." Mensage: ".$e->getMessage();
			$this->	abort();
		}
		return true;
	}
  
	function abort() {
		try {
			$result = $this->conn->rollback();
		} catch (PDOException $e) {
			echo "Error: ".$e." Mensage: ".$e->getMessage();
		}
		return true;
  	}
}
?>
