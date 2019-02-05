<?php
require_once('class.PropertyObject.php');

class TProveedor extends PropertyObject {

    protected $_tablename	= 'proveedor';
	protected $_fieldid		= 'provid';
	protected $_fieldactivo	= 'provactivo';
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
		$this->propertyTable['ID'] 				= 'provid';
		$this->propertyTable['Empresa'] 		= 'providempresa';
		$this->propertyTable['Proveedor']		= 'providprov';	
		
		$this->propertyTable['Login']			= 'provlogin';
		$this->propertyTable['Clave']			= 'provclave';
		$this->propertyTable['Web']				= 'provweb';
		
		$this->propertyTable['Nombre'] 			= 'provnombre';
		$this->propertyTable['Direccion'] 		= 'provdireccion';
		$this->propertyTable['Provincia']		= 'providprovincia';
		$this->propertyTable['Localidad'] 		= 'providloc';
		$this->propertyTable['CP']			 	= 'provcp';
		$this->propertyTable['Cuit'] 			= 'provcuit';
		$this->propertyTable['NroIBB'] 			= 'provnroIBB';
		$this->propertyTable['Telefono'] 		= 'provtelefono';
		$this->propertyTable['Email']			= 'provcorreo';
		$this->propertyTable['Observacion'] 	= 'provobservacion';
		$this->propertyTable['Activo'] 			= 'provactivo';
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
	}
	
	public function __validate()  {
		return true;
	}
	
	private function setAuth($_status=false) {
		$this->_autenticado = $_status;
	}
	
	public function login($_pwd) {		
		$this->setAuth(false);
	   	$_status = (strcmp($_pwd, $this->__get('Clave')) == 0);
		$this->setAuth($_status);
	   	return $_status;
	}
}
?>