<?php
require_once('class.PropertyObject.php');

class TPedido extends PropertyObject {	
    protected $_tablename		= 'pedido';
	protected $_fieldid			= 'pid'; 
	protected $_fieldactivo		= 'pactivo';
	protected $_timestamp		= 'pfechapedido';
    protected $_id				= 0;
	protected $_autenticado		= 0;
	
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);
	   		parent::__construct($arData);
		}	
		
		$this->propertyTable['ID'] 					= 'pid';
		$this->propertyTable['Usuario']				= 'pidusr'; //id de usuario o vendedor
		//$this->propertyTable['Zona']				= 'pzona'; //Zona del cliente??
		$this->propertyTable['Cliente']				= 'pidcliente';
		$this->propertyTable['Pedido']				= 'pidpedido';			
		$this->propertyTable['Pack']				= 'pidpack';	
		$this->propertyTable['Lista']				= 'pidlista';		
		$this->propertyTable['Empresa']				= 'pidemp';	
		$this->propertyTable['Laboratorio']			= 'pidlab';	
		$this->propertyTable['IDArt']				= 'pidart';
		$this->propertyTable['Articulo']			= 'pnombreart';
		$this->propertyTable['Cantidad']			= 'pcantidad';
		$this->propertyTable['Precio']				= 'pprecio';
		$this->propertyTable['Bonificacion1']		= 'pbonif1';	
		$this->propertyTable['Bonificacion2']		= 'pbonif2';
		$this->propertyTable['Descuento1']			= 'pdesc1';		
		$this->propertyTable['Descuento2']			= 'pdesc2';	
		$this->propertyTable['Descuento3']			= 'pdesc3';	
		$this->propertyTable['CondicionPago']		= 'pidcondpago';
		$this->propertyTable['OrdenCompra']			= 'pordencompra';	
		$this->propertyTable['Observacion']			= 'pobservacion';
		$this->propertyTable['FechaPedido']			= 'pfechapedido';		
		$this->propertyTable['IDAdmin']				= 'pidadmin';	
		$this->propertyTable['Administrador']		= 'pnombreadmin';
		$this->propertyTable['FechaExportado']		= 'pfechaexport';		
		$this->propertyTable['Propuesta']			= 'pidpropuesta';
		$this->propertyTable['CondicionComercial']	= 'pidcondcomercial';		
		$this->propertyTable['Negociacion']			= 'pnegociacion';
		$this->propertyTable['IDResp']				= 'pidresp';
		$this->propertyTable['Responsable']			= 'presponsable';
		$this->propertyTable['FechaAprobado']		= 'pfechaaprob';
		$this->propertyTable['Aprobado']			= 'paprobado';		
		$this->propertyTable['Propuesta']			= 'pidpropuesta';		
		$this->propertyTable['Activo'] 				= 'pactivo';
		
		//Para pedidos SP, VALE, ClienteParticular, etc
		$this->propertyTable['Tipo'] 				= 'ptipo';
		$this->propertyTable['Nombre'] 				= 'pnombre';
		$this->propertyTable['Provincia']			= 'pprovincia';
		$this->propertyTable['Localidad']			= 'plocalidad';
		$this->propertyTable['Direccion']			= 'pdireccion';
		$this->propertyTable['CP']	 				= 'pcp';
		$this->propertyTable['Telefono']			= 'ptelefono';
		//----------
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