<?php
require_once('class.PropertyObject.php');

class TPlanificacion extends PropertyObject {
    protected $_tablename	= 'planificado';
	protected $_fieldid		= 'planifid';
	protected $_fieldactivo	= 'planifactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'planifid';
		$this->propertyTable['IDVendedor'] 	= 'planifidvendedor';
		$this->propertyTable['Fecha'] 		= 'planiffecha';		
		$this->propertyTable['Cliente'] 	= 'planifidcliente';
		$this->propertyTable['Nombre'] 		= 'planifclinombre';
		$this->propertyTable['Direccion']	= 'planifclidireccion';
		$this->propertyTable['Envio']		= 'planiffechaenvio';
		$this->propertyTable['Activa'] 		= 'planifactiva';
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
		// Devuelve '#petid' para el alta de un nuevo registro
		return ('#'.$this->_fieldid);
		//return ('#'.$this->propertyTable['ID']);
	}

	public function __validate()  {
		return true;
	}
}
?>