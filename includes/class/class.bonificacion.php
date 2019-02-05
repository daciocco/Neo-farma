<?php
require_once('class.PropertyObject.php');

class TBonificacion extends PropertyObject {
    protected $_tablename	= 'bonificacion';
	protected $_fieldid		= 'bonifid';
	protected $_fieldactivo	= 'bonifactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'bonifid';
		$this->propertyTable['Empresa']		= 'bonifempid';
		$this->propertyTable['Articulo'] 	= 'bonifartid';
		$this->propertyTable['Mes'] 		= 'bonifmes';
		$this->propertyTable['Anio'] 		= 'bonifanio';
		$this->propertyTable['Precio'] 		= 'bonifpreciodrog';
		$this->propertyTable['Publico'] 	= 'bonifpreciopublico';
		$this->propertyTable['Iva']			= 'bonifiva';
		$this->propertyTable['Digitado'] 	= 'bonifpreciodigitado';
		$this->propertyTable['Oferta']		= 'bonifoferta';
		$this->propertyTable['1A'] 			= 'bonif1a';
		$this->propertyTable['1B'] 			= 'bonif1b';
		$this->propertyTable['1C'] 			= 'bonif1c';
		$this->propertyTable['3A'] 			= 'bonif3a';
		$this->propertyTable['3B'] 			= 'bonif3b';
		$this->propertyTable['3C'] 			= 'bonif3c';
		$this->propertyTable['6A'] 			= 'bonif6a';
		$this->propertyTable['6B'] 			= 'bonif6b';
		$this->propertyTable['6C'] 			= 'bonif6c';
		$this->propertyTable['12A'] 		= 'bonif12a';
		$this->propertyTable['12B'] 		= 'bonif12b';
		$this->propertyTable['12C'] 		= 'bonif12c';
		$this->propertyTable['24A'] 		= 'bonif24a';
		$this->propertyTable['24B'] 		= 'bonif24b';
		$this->propertyTable['24C'] 		= 'bonif24c';
		$this->propertyTable['36A'] 		= 'bonif36a';
		$this->propertyTable['36B'] 		= 'bonif36b';
		$this->propertyTable['36C'] 		= 'bonif36c';
		$this->propertyTable['48A'] 		= 'bonif48a';
		$this->propertyTable['48B'] 		= 'bonif48b';
		$this->propertyTable['48C'] 		= 'bonif48c';
		$this->propertyTable['72A'] 		= 'bonif72a';
		$this->propertyTable['72B'] 		= 'bonif72b';
		$this->propertyTable['72C'] 		= 'bonif72c';
		$this->propertyTable['Activa'] 		= 'bonifactiva';
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