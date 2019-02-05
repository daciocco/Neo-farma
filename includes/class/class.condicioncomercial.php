<?php
require_once('class.PropertyObject.php');

class TCondicionComercial extends PropertyObject {
    protected $_tablename	= 'condicion';
	protected $_fieldid		= 'condid';
	protected $_fieldactivo	= 'condactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 					= 'condid';
		$this->propertyTable['Empresa']				= 'condidemp';
		$this->propertyTable['Laboratorio']			= 'condidlab';		
		$this->propertyTable['Cuentas']				= 'condidcuentas';
		 /*Se trata de condicionde uso único para los clientes separados por coma indicados en un string (Cuentas)*/		 
		$this->propertyTable['Nombre']				= 'condnombre';
		$this->propertyTable['Tipo']				= 'condtipo';	/*Pack, listas, condiciones, kits, etc*/	
		$this->propertyTable['CondicionPago']		= 'condcondpago';
		$this->propertyTable['CantidadMinima']		= 'condcantmin'; //Cantidad de unidades mñínima por condición 			 
		$this->propertyTable['MinimoReferencias'] 	= 'condminreferencias'; 
		$this->propertyTable['MinimoMonto'] 		= 'condminmonto';					
		$this->propertyTable['FechaInicio']			= 'condfechainicio';
		$this->propertyTable['FechaFin']			= 'condfechafin';
		$this->propertyTable['Observacion']			= 'condobservacion';		
		//Condición Habitual
		$this->propertyTable['Cantidad']			= 'condhabcant';
		$this->propertyTable['Bonif1']				= 'condhabbonif1';
		$this->propertyTable['Bonif2']				= 'condhabbonif2';
		$this->propertyTable['Desc1']				= 'condhabdesc1';
		$this->propertyTable['Desc2']				= 'condhabdesc2';
		$this->propertyTable['UsrUpdate']			= 'condusrupdate';
		$this->propertyTable['LastUpdate']			= 'condlastupdate';
		$this->propertyTable['Activa'] 				= 'condactiva';
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