<?php
require_once('class.PropertyObject.php');

class TPartediario extends PropertyObject {
    protected $_tablename	= 'parte_diario';
	protected $_fieldid		= 'parteid';
	protected $_fieldactivo	= 'parteactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 			= 'parteid';
		$this->propertyTable['IDVendedor'] 	= 'parteidvendedor';
		$this->propertyTable['Fecha'] 		= 'partefecha';		
		$this->propertyTable['Cliente'] 	= 'parteidcliente';
		$this->propertyTable['Nombre'] 		= 'parteclinombre';
		$this->propertyTable['Direccion']	= 'parteclidireccion';
		$this->propertyTable['Trabajocon']	= 'partetrabajocon';
		$this->propertyTable['Observacion']	= 'parteobservacion';
		$this->propertyTable['Acciones']	= 'parteaccion';
		$this->propertyTable['Envio'] 		= 'partefechaenvio';
		$this->propertyTable['Activa'] 		= 'parteactiva';
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
	}

	public function __validate()  {
		return true;
	}
}
?>