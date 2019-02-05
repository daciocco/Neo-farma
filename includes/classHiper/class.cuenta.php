<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/pedidos/includes/class/class.PropertyObject.php');

class THiperCuenta extends PropertyObject {
    protected $_tablename	= 'Clientes';
	protected $_fieldid		= 'ctaid';
	/*
	protected $_fieldid	= 'IdEmpresa';
	protected $_fieldidTwo	= 'IdCliente';
	*/
    protected $_fieldactivo	= 'ctaactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;
	//protected $_idTwo		= '';
	//protected $_idThree	= 0;
	
    public function __construct($_id=NULL) { /*, $_idTwo=NULL/*, $_idThree=NULL*/
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		//$this->_idTwo		= $_idTwo;
		//$this->_idThree		= $_idThree;
		if ($_id) {
			$arData = DataManagerHiper::loadFromDatabase($this, $_id); /*, $_idTwo/*, $_idThree*/
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 					= 'ctaid';		
		$this->propertyTable['Empresa']				= 'IdEmpresa';
		$this->propertyTable['Cuenta']				= 'IdCliente';
		$this->propertyTable['Tipo']				= 'ctatipo'; //CLIENTE/TRANSFER/PROSPECTO/PROVEEDOR
		$this->propertyTable['Estado']				= 'ctaestado'; //Estado actual Ej: Solicita Alta
		$this->propertyTable['Nombre']				= 'NombreCLI'; 
		$this->propertyTable['CUIT']				= 'CuitCLI';
		$this->propertyTable['Zona']				= 'IdVendedor'; //idvendedor ASIGNADO en HIPERWIN!
		$this->propertyTable['ZonaEntrega']			= 'Zona'; //Zona de entrega depósito		
		$this->propertyTable['Pais']				= 'IdPais';
		$this->propertyTable['Provincia']			= 'IdProvincia';
		$this->propertyTable['Localidad'] 			= 'ctaidloc';
		$this->propertyTable['LocalidadNombre']		= 'LocalidadCLI';
		$this->propertyTable['Direccion']			= 'ctadireccion';
		$this->propertyTable['DireccionCompleta']	= 'DomicilioCLI';
		$this->propertyTable['DireccionEntrega']	= 'EntregaCLI';
		$this->propertyTable['Numero'] 				= 'ctadirnro';		
		$this->propertyTable['Piso'] 				= 'ctadirpiso';
		$this->propertyTable['Dpto'] 				= 'ctadirdpto';
		$this->propertyTable['CP']					= 'CPostalCLI';
		$this->propertyTable['Longitud']			= 'ctalongitud';
		$this->propertyTable['Latitud']				= 'ctalatitud';
		$this->propertyTable['Ruteo'] 				= 'ctaruteo'; 
		$this->propertyTable['CategoriaComercial']	= 'Categoria';
		$this->propertyTable['Referencias'] 		= 'ctareferencias';
		$this->propertyTable['CuentaContable']		= 'CuentaContable'; //tabla cuentascontables Hiper	
		$this->propertyTable['CondicionPago']		= 'IdCondPago';
		$this->propertyTable['Empleados']			= 'ctacantempleados';
		$this->propertyTable['Bonif1'] 				= 'Bonif1';
		$this->propertyTable['Bonif2'] 				= 'Bonif2';
		$this->propertyTable['Bonif3'] 				= 'Bonif3';
		$this->propertyTable['CategoriaIVA']		= 'TipoIvaCLI';
		$this->propertyTable['RetencPercepIVA'] 	= 'Ret_IVA';
		$this->propertyTable['Credito']				= 'ctacredito'; //límite aceptado al cliente	
		$this->propertyTable['NroEmpresa']			= 'NroEmpresa'; //Empresa con qse relaciona la cta	
		$this->propertyTable['NroIngresosBrutos']	= 'NroIBruCLI';
		$this->propertyTable['FechaAlta']			= 'FechaAlta';
		$this->propertyTable['FechaCompra'] 		= 'FechaUcom';
		$this->propertyTable['Email']				= 'EmailCLI';
		$this->propertyTable['Telefono']			= 'TelefonoCLI';
		$this->propertyTable['Web'] 				= 'ctaweb';
		$this->propertyTable['Observacion']			= 'ObservCLI';
		$this->propertyTable['Imagen1']				= 'ctaimagen1'; //id de imagen
		$this->propertyTable['Imagen2']				= 'ctaimagen2'; //id de imagen
		$this->propertyTable['IDCuentaRelacionada']	= 'ctaidctarelacionada'; //tabla "cuenta_relacionada"
		$this->propertyTable['UsrCreated'] 			= 'ctausrcreated';		
		$this->propertyTable['DateCreated'] 		= 'ctacreated';
		$this->propertyTable['UsrAssigned'] 		= 'ctausrassigned';
		$this->propertyTable['UsrUpdate'] 			= 'ctausrupdate';
		$this->propertyTable['LastUpdate'] 			= 'ctaupdate';
		$this->propertyTable['Activa'] 				= 'ctaactiva';		
		//campos que están solo en Hiper
		//$this->propertyTable['Lista']				= 'Lista';
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
	
	/*public function __getFieldIDTwo()  {
		return $this->_fieldidTwo;
	}
	/*
	public function __getFieldIDThree()  {
		return $this->_fieldidThree;
	}*/
}
?>