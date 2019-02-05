<?php
require_once('class.PropertyObject.php');

class TRespuesta extends PropertyObject {		
    protected $_tablename	= 'respuesta';
	protected $_fieldid		= 'resid';
	protected $_fieldactivo	= 'resactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;
    protected $_autenticado	= 0;
    
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 				= 'resid';
		$this->propertyTable['IDRelevamiento']	= 'resrelid'; 
		$this->propertyTable['Respuesta']		= 'respuesta1'; 	
		$this->propertyTable['Origen']			= 'resorigen'; 	// Tabla donde sale quién respondió el relevamiento (nombre Tabla) ejemplo, de un prospecto (tabla TProspecto)
		$this->propertyTable['IDOrigen']		= 'resorigenid';// id de la tabla Origen. Id del prospecto que respondió.		
		$this->propertyTable['UsrUpdate'] 		= 'resuid';	 // Usuario que hace el relevo, podría ser también el mismo del origen (si fuera una autoencuesta)		
		$this->propertyTable['LastUpdate'] 		= 'resfecha';
		$this->propertyTable['Activa'] 			= 'resactiva';
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
		return ('#'.$this->propertyTable['ID']);
		//return ('#'.$this->_fieldid);
	}
	
	public function __validate()  {
		return true;
	}
}
?>