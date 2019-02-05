<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/pedidos/includes/class/class.PropertyObject.php');

class THiperArticulo extends PropertyObject {
    protected $_tablename	= 'Articulos';
	protected $_fieldid		= 'artid';
	protected $_fieldactivo	= 'artactivo';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManagerHiper::loadFromDatabase($this, $_id);				
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 			= 'artid';
		
		$this->propertyTable['Empresa']		= 'IdEmpresa';
		$this->propertyTable['Laboratorio']	= 'IdLab';	
		$this->propertyTable['Articulo']	= 'IdArt';		
		$this->propertyTable['Nombre'] 		= 'NombreART';
		
		$this->propertyTable['Precio']		= 'PrecioART';
		$this->propertyTable['PrecioLista'] = 'PrecioListaART';
		$this->propertyTable['PrecioCompra']= 'PrecioComART';		
		$this->propertyTable['PrecioReposicion']= 'PrecioRepART';	
		$this->propertyTable['FechaCompra'] = 'FechaComART';
		
		$this->propertyTable['IVA']			= 'Cod_IvaART';
		$this->propertyTable['Rubro'] 		= 'IdRubro';		
		$this->propertyTable['Lista'] 		= 'IdLista';	
		$this->propertyTable['Medicinal']	= 'Medicinal';
		$this->propertyTable['CodigoBarra']	= 'CodigoBarras';
		$this->propertyTable['UsrUpdate']	= 'artusrupdate';	
		$this->propertyTable['LastUpdate']	= 'artlastupdate';	
		$this->propertyTable['Stock']	 	= 'artstock'; 
		$this->propertyTable['Imagen']		= 'artimagen';	
		$this->propertyTable['Activo'] 		= 'artactivo';
		$this->propertyTable['Descripcion']	= 'artdescripcion';		
		$this->propertyTable['Dispone'] 	= 'artiddispone';		
		$this->propertyTable['Familia'] 	= 'artidfamilia';
		$this->propertyTable['Ganancia'] 	= 'artganancia';
		
		$this->propertyTable['Oferta'] 		= 'Cond_OfertaART';
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