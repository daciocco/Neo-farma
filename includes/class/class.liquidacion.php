<?php
require_once('class.PropertyObject.php');

class TLiquidacion extends PropertyObject { //Para Liquidación Transfer
    protected $_tablename	= 'liquidacion';
	protected $_fieldid		= 'liqid';
	protected $_fieldactivo	= 'liqactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'liqid';
		$this->propertyTable['Drogueria']	= 'liqdrogid';
		$this->propertyTable['Tipo']		= 'liqtipo';
		$this->propertyTable['Fecha']		= 'liqfecha';
		$this->propertyTable['Transfer']	= 'liqnrotransfer';
		$this->propertyTable['FechaFact'] 	= 'liqfechafact';
		$this->propertyTable['NroFact']		= 'liqnrofact';
		$this->propertyTable['EAN']			= 'liqean';
		$this->propertyTable['Cantidad'] 	= 'liqcant';
		$this->propertyTable['Unitario']	= 'liqunitario';
		$this->propertyTable['Descuento']	= 'liqdescuento';
		$this->propertyTable['ImporteNC']	= 'liqimportenc';
						
		$this->propertyTable['Cliente'] 	= 'liqcliente';
		$this->propertyTable['RasonSocial']	= 'liqrs';
		$this->propertyTable['Cuit'] 		= 'liqcuit';
		
		$this->propertyTable['UsrUpdate'] 	= 'liqusrupdate';
		$this->propertyTable['LastUpdate'] 	= 'liqupdate';	
		
		$this->propertyTable['Activa']		= 'liqactiva';
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