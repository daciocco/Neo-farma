<?php
require_once('class.PropertyObject.php');

class TPropuesta extends PropertyObject {		
    protected $_tablename	= 'propuesta';
	protected $_fieldid		= 'propid';
	protected $_fieldactivo	= 'propactiva';
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
		$this->propertyTable['ID'] 				= 'propid';
		$this->propertyTable['Nombre']			= 'propnombre';	//el nombre sería editable en otro tipo de propuestas
		$this->propertyTable['Tipo']			= 'proptipo';	//Podriá tipificarse en códigos 1 - Venta 2 - Alquiler - etc
		
		$this->propertyTable['Estado'] 			= 'propestado'; //estado ACTUAL de la propuesta PENDIENTE/APROBADA/RECHAZADA
		$this->propertyTable['Cuenta']			= 'propidcuenta';
				
		$this->propertyTable['Empresa']			= 'propidempresa';
		$this->propertyTable['Laboratorio']		= 'propidlaboratorio';
		
		$this->propertyTable['Fecha'] 			= 'propfecha';	
		$this->propertyTable['FechaCierre'] 	= 'propfechacierre';		
		$this->propertyTable['UsrCreate'] 		= 'propusr';
		$this->propertyTable['UsrAsignado'] 	= 'propusrasignado';	
		
		$this->propertyTable['LastUpdate'] 		= 'proplastupdate';
		$this->propertyTable['UsrUpdate'] 		= 'propusrupdate';
		
		$this->propertyTable['Observacion'] 	= 'propobservacion';	
		$this->propertyTable['Activa'] 			= 'propactiva';
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