<?php
require_once('class.PropertyObject.php');

class TFacturaProv extends PropertyObject {

    protected $_tablename	= 'facturas_proveedor';
	protected $_fieldid		= 'factid';
	protected $_fieldactivo	= 'factactiva';
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
		$this->propertyTable['ID'] 				= 'factid';
		$this->propertyTable['Empresa'] 		= 'factidemp';
		$this->propertyTable['Proveedor']		= 'factidprov';	
		$this->propertyTable['Plazo']			= 'factplazo';	
		$this->propertyTable['Tipo'] 			= 'facttipo';
		$this->propertyTable['Sucursal'] 		= 'factsuc';
		$this->propertyTable['Numero']			= 'factnumero';
		$this->propertyTable['Comprobante'] 	= 'factfechacbte';
		$this->propertyTable['Vencimiento']		= 'factfechavto';
		$this->propertyTable['Saldo'] 			= 'factsaldo';
		$this->propertyTable['Pago'] 			= 'factfechapago';
		$this->propertyTable['Observacion'] 	= 'factobservacion';
		$this->propertyTable['Activa'] 			= 'factactiva';
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
		// Devuelve '#$_ID' para el alta de un nuevo registro
		return ('#'.$this->propertyTable['ID']);
	}
}
?>