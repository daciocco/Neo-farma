<?php
require_once('class.PropertyObject.php');

class TArticuloDispone extends PropertyObject {
    protected $_tablename	= 'articulodispone';
	protected $_fieldid		= 'adid';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);				
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 					= 'adid';
		$this->propertyTable['NombreGenerico']		= 'adnombregenerico';
		//--------------------
		$this->propertyTable['Via']					= 'advia'; //oral, intramuscular, intravenosa, subcutánea, inhalatoria, transdérmica, nasal, oftálmica, ótica, tópica, rectal, vaginal, 
		//presentacion
		$this->propertyTable['Forma']				= 'adforma';	//gel, liquido, solido, shampoo		
		$this->propertyTable['Envase']				= 'adenvase'; //frasco, blister
		$this->propertyTable['Unidades']			= 'adunidades'; //1 si es frasco
		$this->propertyTable['Cantidad']			= 'adcantidad'; //20, 120
		$this->propertyTable['UnidadMedida']		= 'adunidadmedida'; //g, mg, l, ml
		//--------------------
		$this->propertyTable['Accion'] 				= 'adaccion';
		$this->propertyTable['Uso']	 				= 'aduso';
		$this->propertyTable['NoUsar']				= 'adnousar';	
		$this->propertyTable['CuidadosPre']			= 'adcuidadospre';
		$this->propertyTable['CuidadosPost']		= 'adcuidadospost';
		$this->propertyTable['ComoUsar']			= 'adcomousar';
		$this->propertyTable['Conservacion']		= 'adconservacion';
		$this->propertyTable['FechaUltVersion']		= 'adfechaultversion';
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
	
	public function __newID()  {
		return ('#'.$this->_fieldid);
	}

	public function __validate()  {
		return true;
	}
}
?>