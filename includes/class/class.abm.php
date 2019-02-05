<?php
require_once('class.PropertyObject.php');

class TAbm extends PropertyObject {
    protected $_tablename	= 'abm';
	protected $_fieldid		= 'abmid';
	protected $_fieldactivo	= 'abmactivo';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'abmid';
		$this->propertyTable['Drogueria']	= 'abmdrogid';
		$this->propertyTable['Tipo']		= 'abmtipo';
		$this->propertyTable['Mes'] 		= 'abmmes';
		$this->propertyTable['Anio'] 		= 'abmanio';
		$this->propertyTable['Articulo']	= 'abmartid';
		$this->propertyTable['Descuento'] 	= 'abmdesc';
		$this->propertyTable['Plazo'] 		= 'abmcondpago';
		$this->propertyTable['Diferencia']	= 'abmdifcomp'; //Diferencia de compensación		
		$this->propertyTable['Activo'] 		= 'abmactivo';
		$this->propertyTable['Empresa']		= 'abmidempresa';
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
	
	public function __getFieldActivo() {
		return $this->_fieldactivo;
	}
	
	public function __newID()  {
		return ('#'.$this->_fieldid);
	}

	public function __validate()  {
		return true;
	}
	
	public function __getClassVars() {
		$_classname = get_class($this);
		foreach ($this->propertyTable as $k => $v) {
			$array[] = $k;
		}
		return $array;
    }
} ?>