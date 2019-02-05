<?php
require_once('class.PropertyObject.php');

class TCondicionPago extends PropertyObject {
    protected $_tablename	= 'condiciones_de_pago';
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
		$this->propertyTable['ID'] 			= 'condid';
		$this->propertyTable['Codigo'] 		= 'IdCondPago';	
		$this->propertyTable['Tipo'] 		= 'condtipo';
		
		$this->propertyTable['Tipo1'] 		= 'condtipo1';	
		$this->propertyTable['Tipo2'] 		= 'condtipo2';
		$this->propertyTable['Tipo3'] 		= 'condtipo3';
		$this->propertyTable['Tipo4'] 		= 'condtipo4';
		$this->propertyTable['Tipo5'] 		= 'condtipo5';	
		
		$this->propertyTable['Nombre'] 		= 'Nombre1CP';
		$this->propertyTable['Nombre2'] 	= 'Nombre2CP';
		$this->propertyTable['Nombre3'] 	= 'Nombre3CP';
		$this->propertyTable['Nombre4'] 	= 'Nombre4CP';
		$this->propertyTable['Nombre5'] 	= 'Nombre5CP';	
		
		$this->propertyTable['Dias'] 		= 'Dias1CP';
		$this->propertyTable['Dias2'] 		= 'Dias2CP';
		$this->propertyTable['Dias3'] 		= 'Dias3CP';
		$this->propertyTable['Dias4'] 		= 'Dias4CP';
		$this->propertyTable['Dias5'] 		= 'Dias5CP';
		
		$this->propertyTable['Porcentaje']	= 'Porcentaje1CP';
		$this->propertyTable['Porcentaje2']	= 'Porcentaje2CP';
		$this->propertyTable['Porcentaje3']	= 'Porcentaje3CP';
		$this->propertyTable['Porcentaje4']	= 'Porcentaje4CP';
		$this->propertyTable['Porcentaje5']	= 'Porcentaje5CP';
		
		$this->propertyTable['Signo']		= 'Signo1CP';
		$this->propertyTable['Signo2']		= 'Signo2CP';
		$this->propertyTable['Signo3']		= 'Signo3CP';
		$this->propertyTable['Signo4']		= 'Signo4CP';
		$this->propertyTable['Signo5']		= 'Signo5CP';
		
		$this->propertyTable['Cuotas']		= 'Cuotas';
		$this->propertyTable['Cantidad']	= 'Cantidad';
		//Se define en caso de que deba descontar los días.
		$this->propertyTable['Decrece']		= 'conddecrece';
		
		$this->propertyTable['FechaFinDec']	= 'condfechadec1';
		$this->propertyTable['FechaFinDec2']= 'condfechadec2';
		$this->propertyTable['FechaFinDec3']= 'condfechadec3';
		$this->propertyTable['FechaFinDec4']= 'condfechadec4';
		$this->propertyTable['FechaFinDec5']= 'condfechadec5';
		
		$this->propertyTable['UsrCreated']	= 'condusrcreated';
		$this->propertyTable['Created']		= 'condcreated';
		$this->propertyTable['UsrUpdate']	= 'condusrupdate';
		$this->propertyTable['Update']		= 'condupdate';
		
		$this->propertyTable['Activa'] 		= 'condactiva';
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