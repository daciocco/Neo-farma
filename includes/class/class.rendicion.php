<?php
require_once('class.PropertyObject.php');

class TRendicion extends PropertyObject {	
    protected $_tablename		= 'rendicion';
	protected $_fieldid			= 'rendid'; 
	protected $_fieldactivo		= 'rendactiva';
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
		
		$this->propertyTable['ID'] 				= 'rendid'; //Relación con tabla "rend_rec" para IDRecibo
		$this->propertyTable['Fecha']			= 'rendfecha';
		$this->propertyTable['Numero']			= 'rendnumero';
		$this->propertyTable['IdUsr']			= 'rendidusr'; //Relación con tabla "talonario_idusr" para NroTalonario
		$this->propertyTable['NombreUsr']		= 'rendnombreusr';
		$this->propertyTable['Retencion']		= 'rendretencion';
		$this->propertyTable['Deposito']		= 'renddeposito';
		$this->propertyTable['Envio']			= 'rendfechaenvio';	
		$this->propertyTable['Activa'] 			= 'rendactiva';
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
	
	
	public function Autenticado() {
		return $this->_autenticado;
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