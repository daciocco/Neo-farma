<?php
require_once('class.PropertyObject.php');

class TContacto extends PropertyObject {

    protected $_tablename	= 'contacto';
	protected $_fieldid		= 'ctoid';
	protected $_fieldactivo	= 'ctoactivo';
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
		$this->propertyTable['ID'] 				= 'ctoid';
		$this->propertyTable['Origen'] 			= 'ctoorigen'; //De donde sale el contacto (nombre Tabla) ejemplo, de un proveedor (tabla TProveedor)
		$this->propertyTable['IDOrigen']		= 'ctoorigenid'; // id de la tabla Origen			
		$this->propertyTable['Domicilio']		= 'ctodomicilioid';
		$this->propertyTable['Sector']			= 'ctosector';
		$this->propertyTable['Puesto']			= 'ctopuesto';			
		$this->propertyTable['Nombre'] 			= 'ctonombre';
		$this->propertyTable['Apellido'] 		= 'ctoapellido';
		$this->propertyTable['Genero']			= 'ctogenero';
		$this->propertyTable['Telefono'] 		= 'ctotelefono';
		$this->propertyTable['Interno'] 		= 'ctointerno';
		$this->propertyTable['Email']			= 'ctocorreo';
		$this->propertyTable['Activo'] 			= 'ctoactivo';
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
}
?>