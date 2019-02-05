<?php
require_once('class.PropertyObject.php');

class TProspecto extends PropertyObject {

    protected $_tablename	= 'prospecto';
	protected $_fieldid		= 'proid';
    protected $_fieldactivo	= 'proactivo';
	protected $_timestamp	= 0;
    protected $_id			= 0;
	
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 					= 'proid';
		$this->propertyTable['Categoria']			= 'procategoria';
		$this->propertyTable['Cadena']				= 'procadena'; //Si es cadena o no!
		$this->propertyTable['Nombre'] 				= 'pronombre';
		$this->propertyTable['Tipo'] 				= 'protipo'; //si es prospecto, cliente, transfer
		$this->propertyTable['DireccionCompleta'] 	= 'prodircompleta'; //Completa//
		$this->propertyTable['Direccion'] 			= 'prodireccion';
		$this->propertyTable['Localidad'] 			= 'prolocalidad';
		$this->propertyTable['Provincia'] 			= 'proprovincia';		
		$this->propertyTable['CP'] 					= 'procp';
		$this->propertyTable['CUIT']				= 'procuit';
		$this->propertyTable['Pais'] 				= 'propais';
		$this->propertyTable['Telefono'] 			= 'protelefono';
		$this->propertyTable['Web'] 				= 'proweb';
		$this->propertyTable['Email'] 				= 'procorreo';
		$this->propertyTable['MapLink'] 			= 'promaplink';
		$this->propertyTable['Longitud'] 			= 'prolongitud';
		$this->propertyTable['Latitud'] 			= 'prolatitud';
		$this->propertyTable['DetalleLink'] 		= 'prodetallelink';
		$this->propertyTable['Observacion']			= 'proobservacion';
		$this->propertyTable['UsrUpdate'] 			= 'prousrupdate';
		$this->propertyTable['LastUpdate'] 			= 'prolastupdate';
		$this->propertyTable['Activo'] 				= 'proactivo';
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