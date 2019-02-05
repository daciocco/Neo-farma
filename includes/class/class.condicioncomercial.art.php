<?php
require_once('class.PropertyObject.php');

class TCondicionComercialArt extends PropertyObject {
    protected $_tablename	= 'condicion_art';
	protected $_fieldid		= 'cartid';
	protected $_fieldactivo	= 'cartactivo';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 				= 'cartid';
		$this->propertyTable['Condicion']		= 'cartidcond';
		$this->propertyTable['Articulo']		= 'cartidart';
		$this->propertyTable['Precio']			= 'cartprecio';
		$this->propertyTable['Digitado']		= 'cartpreciodigitado';
		//$this->propertyTable['Iva']					= 'cartiva';		
		$this->propertyTable['CantidadMinima']	= 'cartcantmin'; //Cantidad de unidades mínima por artículo
		$this->propertyTable['OAM']				= 'cartoam'; //Oferta, Alta y/o Modificación
		$this->propertyTable['Activo'] 			= 'cartactivo';
	
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