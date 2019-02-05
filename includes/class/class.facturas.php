<?php
require_once('class.PropertyObject.php');

class TFacturas extends PropertyObject {	
    protected $_tablename		= 'facturas';
	protected $_fieldid			= 'factid'; 
	//protected $_fieldactivo	= 'cheqactivo';
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
		$this->propertyTable['ID'] 					= 'factid'; //Relación con tabla "rec_fact" para IDRecibo 
																// También relación con tabla "fact_cheq" para IDFactura
		$this->propertyTable['Numero'] 				= 'factnro';
		$this->propertyTable['Cliente'] 			= 'factidcliente'; //Relación con tabla "cliente"		
		$this->propertyTable['Fecha'] 				= 'factfecha';
		$this->propertyTable['Bruto'] 				= 'factbruto';
		$this->propertyTable['Descuento']			= 'factdesc';
		$this->propertyTable['Neto'] 				= 'factneto';
		$this->propertyTable['Efectivo']			= 'factefectivo';
		$this->propertyTable['Transfer']			= 'facttransfer';
		$this->propertyTable['Retencion']			= 'factretencion';
		//$this->propertyTable['Activa'] 			= 'cheqactiva';
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