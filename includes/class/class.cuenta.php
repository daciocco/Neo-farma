<?php
require_once('class.PropertyObject.php');

class TCuenta extends PropertyObject {
    protected $_tablename	= 'cuenta';
	protected $_fieldid		= 'ctaid';
    protected $_fieldactivo	= 'ctaactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;
	
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);
	   		parent::__construct($arData);
		}
		
		$this->propertyTable['ID'] 					= 	'ctaid';
		$this->propertyTable['Empresa']				= 	'ctaidempresa';
		$this->propertyTable['Cuenta']				= 	'ctaidcuenta';	//(NroVendedor + nrolibre)
		$this->propertyTable['Tipo']				= 	'ctatipo'; //CLIENTE/TRANSFER/PROSPECTO/PROVEEDOR
		$this->propertyTable['Estado']				= 	'ctaestado'; //Estado actual Ej: Solicita Alta	
		$this->propertyTable['Nombre'] 				= 	'ctanombre';
		$this->propertyTable['CUIT'] 				= 	'ctacuit'; //CUIT		
		$this->propertyTable['Zona']				= 	'ctazona'; //idvendedor Hiper y ZONA GEOGRÁFICA
		$this->propertyTable['ZonaEntrega'] 		= 	'ctazonaentrega'; //para deposito		
		$this->propertyTable['Pais'] 				= 	'ctaidpais';
		$this->propertyTable['Provincia'] 			= 	'ctaidprov';
		$this->propertyTable['Localidad'] 			= 	'ctaidloc';	
		$this->propertyTable['LocalidadNombre'] 	= 	'ctalocalidad';	
		$this->propertyTable['Direccion'] 			= 	'ctadireccion';
		$this->propertyTable['DireccionEntrega'] 	= 	'ctadireccionentrega';
		$this->propertyTable['Numero'] 				= 	'ctadirnro';		
		$this->propertyTable['Piso'] 				= 	'ctadirpiso';
		$this->propertyTable['Dpto'] 				= 	'ctadirdpto';
		$this->propertyTable['CP'] 					= 	'ctacp';		
		$this->propertyTable['Longitud']			= 	'ctalongitud';
		$this->propertyTable['Latitud']				= 	'ctalatitud';
		$this->propertyTable['Ruteo'] 				= 	'ctaruteo'; //cada cuantos días vender al cliente. 
		$this->propertyTable['CategoriaComercial'] 	= 	'ctacategoriacomercial'; // Categoría Comercial
		$this->propertyTable['Referencias'] 		= 	'ctareferencias'; //cantidad vendidas 12 meses
		$this->propertyTable['CuentaContable']		= 	'ctacuentacontable'; //cuentascontables deHiperwin
		$this->propertyTable['CondicionPago']		= 	'ctacondpago';		
		$this->propertyTable['Empleados']			= 	'ctacantempleados';
		$this->propertyTable['Bonif1'] 				= 	'ctabonif1';
		$this->propertyTable['Bonif2'] 				= 	'ctabonif2';
		$this->propertyTable['Bonif3'] 				= 	'ctabonif3';
		$this->propertyTable['CategoriaIVA'] 		= 	'ctacategoriaiva'; //"TipoIva" Tabla "Categorias"
		$this->propertyTable['RetencPercepIVA'] 	= 	'ctaretperciva'; //Ret(Cliente)-Perc(Proveedor)
		$this->propertyTable['Credito']				= 	'ctacredito'; //límite aceptado al cliente		
		$this->propertyTable['NroEmpresa']			= 	'ctanroempresa';
		$this->propertyTable['NroIngresosBrutos']	= 	'ctanroingbruto';		
		$this->propertyTable['FechaAlta'] 			= 	'ctafechaalta'; //SI pasa solicitud Alta a ALTA)? 
		$this->propertyTable['FechaCompra'] 		= 	'ctafechacompra';		
		$this->propertyTable['Email'] 				= 	'ctacorreo';
		$this->propertyTable['Telefono'] 			= 	'ctatelefono';
		$this->propertyTable['Web'] 				=	'ctaweb';
		$this->propertyTable['Observacion']			= 	'ctaobservacion';
		$this->propertyTable['Imagen1']				= 	'ctaimagen1'; //id de imagen
		$this->propertyTable['Imagen2']				= 	'ctaimagen2'; //id de imagen
		$this->propertyTable['IDCuentaRelacionada']	= 	'ctaidctarelacionada'; //tabla"cuenta_relacionada"
		$this->propertyTable['UsrCreated'] 			= 	'ctausrcreated';		
		$this->propertyTable['DateCreated'] 		= 	'ctacreated';
		$this->propertyTable['UsrAssigned'] 		= 	'ctausrassigned';
		$this->propertyTable['UsrUpdate'] 			= 	'ctausrupdate';
		$this->propertyTable['LastUpdate'] 			= 	'ctaupdate';
		$this->propertyTable['Activa'] 				=	'ctaactiva';
		$this->propertyTable['Lista']				= 	'ctalista';
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