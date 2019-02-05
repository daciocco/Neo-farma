<?php
require_once('class.PropertyObject.php');

class TPropuestaDetalle extends PropertyObject {	
    protected $_tablename		= 'propuesta_detalle';
	protected $_fieldid			= 'pdid'; 
	protected $_fieldactivo		= 'pdactivo';
	protected $_timestamp		= 0;
    protected $_id				= 0;
	protected $_autenticado		= 0;
	
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);
	   		parent::__construct($arData);
		}	
		
		$this->propertyTable['ID']				= 'pdid';
		$this->propertyTable['IDPropuesta'] 	= 'pdpropid';
		$this->propertyTable['CondicionPago']	= 'pdcondpago';
		$this->propertyTable['IDArt']			= 'pdidart';
		$this->propertyTable['Cantidad']		= 'pdcantidad';
		$this->propertyTable['Precio']			= 'pdprecio';
		$this->propertyTable['Bonificacion1']	= 'pdbonif1';	
		$this->propertyTable['Bonificacion2']	= 'pdbonif2';
		$this->propertyTable['Descuento1']		= 'pddesc1';		
		$this->propertyTable['Descuento2']		= 'pddesc2';
				
		$this->propertyTable['Estado'] 			= 'pdestado'; //estado de la propuesta PENDIENTE/APROBADA/RECHAZADA
		$this->propertyTable['Fecha'] 			= 'pdfecha';	
		$this->propertyTable['Activo']			= 'pdactivo'; //Si est áinactivo, es de una propuesta viehaja	
		
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
	
	/*
	public function Autenticado() {
		return $this->_autenticado;
	}*/	
	
	public function __newID()  {
		// Devuelve '#petid' para el alta de un nuevo registro
		return ('#'.$this->_fieldid);
	}

	public function __validate()  {
		return true;
	}
}
?>