<?php
require_once('class.PropertyObject.php');

class TLlamada extends PropertyObject {

    protected $_tablename	= 'llamada';
	protected $_fieldid		= 'llamid';
    protected $_fieldactivo	= 'llamactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;
	
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 					= 'llamid';
		$this->propertyTable['Origen'] 				= 'llamorigen';
		$this->propertyTable['IDOrigen'] 			= 'llamorigenid';
		$this->propertyTable['Telefono']			= 'llamtelefono';
		$this->propertyTable['Fecha'] 				= 'llamfecha';
		$this->propertyTable['TipoResultado'] 		= 'llamtiporesultado';
		$this->propertyTable['Resultado'] 			= 'llamresultado';
		$this->propertyTable['Observacion'] 		= 'llamobservacion';
		$this->propertyTable['UsrUpdate'] 			= 'llamusrupdate';
		$this->propertyTable['LastUpdate'] 			= 'llamlastupdate';		
		$this->propertyTable['Activa'] 				= 'llamactiva';
    }
     
	function __toString() {
		$_classname = get_class($this);
		echo "<h2>=== $_classname ===</h2><br/>";
		echo "ID=" . $this->_id . "<br/>";
		echo "tabla=" . $this->_tablename . "<br/>";
		echo "timestamp=" . date('Y-m-d H:i:s',$this->_timestamp) . "<br/>";
		foreach ($this->propertyTable as $k => $v) {
			echo $k . '=>' . $this->__get($k) . '<br>';
		}
	}    

	public function __getTableName()  {
		return $this->_tablename;
	}
	
	public function __getFieldID()  {
		return $this->_fieldid;
	}

	public function __getFieldActivo()  {
		return $this->_fieldactivo;
	}
	
	public function __newID()  {
		return ('#'.$this->_fieldid);
	}

	public function __validate()  {
		return true;
	}
}
?>