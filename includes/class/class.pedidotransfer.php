<?php
require_once('class.PropertyObject.php');

class TPedidostransfer extends PropertyObject {	
    protected $_tablename		= 'pedidos_transfer';
	protected $_fieldid			= 'ptid'; 
	protected $_fieldactivo		= 'ptactivo';
	protected $_timestamp		= 'ptfechapedido'; //0;
    protected $_id				= 0;
	protected $_autenticado		= 0;
	
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);
	   		parent::__construct($arData);
		}	
		
		$this->propertyTable['ID'] 					= 'ptid';
		$this->propertyTable['IDVendedor']			= 'ptidvendedor';
		$this->propertyTable['ParaIdUsr']			= 'ptparaidusr';//Para quién se registra el pedido
		$this->propertyTable['IDPedido']			= 'ptidpedido';
		$this->propertyTable['IDDrogueria']			= 'ptiddrogueria';
		$this->propertyTable['ClienteDrogueria']	= 'ptnroclidrog';
		$this->propertyTable['ClienteNeo']			= 'ptidclineo';
		$this->propertyTable['RS'] 					= 'ptclirs';
		$this->propertyTable['Cuit']				= 'ptclicuit';
		$this->propertyTable['Domicilio']			= 'ptdomicilio';
		$this->propertyTable['Contacto']			= 'ptcontacto';
		$this->propertyTable['Articulo']			= 'ptidart';
		$this->propertyTable['Unidades']			= 'ptunidades';
		$this->propertyTable['Precio']				= 'ptprecio';
		$this->propertyTable['Descuento']			= 'ptdescuento';
		$this->propertyTable['FechaPedido']			= 'ptfechapedido';
		
		$this->propertyTable['CondicionPago']		= 'ptcondpago';
		
		$this->propertyTable['IDAdmin']				= 'ptidadmin';
		$this->propertyTable['IDNombreAdmin']		= 'ptnombreadmin';
		$this->propertyTable['FechaExportado']		= 'ptfechaexp';
		
		$this->propertyTable['Liquidado']			= 'ptliquidado';
		
		$this->propertyTable['Activo'] 				= 'ptactivo';
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