<?php
require_once('class.PropertyObject.php');

class TMovimientos extends PropertyObject {
    protected $_tablename	= 'movimiento';
	protected $_fieldid		= 'movid';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'movid';
		$this->propertyTable['Operacion']	= 'movoperacion';
		$this->propertyTable['Transaccion']	= 'movtransaccion';
    	$this->propertyTable['Origen']		= 'movorigen';
		$this->propertyTable['OrigenId']	= 'movorigenid';
		$this->propertyTable['Fecha']		= 'movfecha';
		$this->propertyTable['Usuario']		= 'movusrid';
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
	public function __newID()  {
		return ('#'.$this->_fieldid);
	}
	public function __validate()  {
		return true;
	}
}
?>