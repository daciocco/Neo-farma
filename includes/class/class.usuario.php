<?php
require_once('class.PropertyObject.php');

class TUsuario extends PropertyObject {
    protected $_tablename	= 'usuarios';
	protected $_fieldid		= 'uid';
	protected $_fieldactivo	= 'uactivo';
	protected $_timestamp	= 0;
    protected $_id			= 0;
    protected $_autenticado	= 0;
    
    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);	
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'uid';
		$this->propertyTable['Rol'] 		= 'urol';
		//---------
		//unombre habrá que adaptar a nombre?
		$this->propertyTable['Nombre'] 		= 'unombre';
		//$this->propertyTable['NombreyApellidos']	= 'NombreyApellidos';
		//$this->propertyTable['Nombre'] 		= 'Nombre';
		//$this->propertyTable['Apellido'] 		= 'Apellido';
		//----------		
		$this->propertyTable['Dni'] 		= 'udni';	
		$this->propertyTable['Login'] 		= 'ulogin';
		$this->propertyTable['Clave'] 		= 'uclave';
		$this->propertyTable['Email'] 		= 'uemail';
		$this->propertyTable['Obs'] 		= 'uobs';
		$this->propertyTable['Activo'] 		= 'uactivo';
		
		
		$this->propertyTable['Area'] 		= 'Area';
		$this->propertyTable['Roles']		= 'IdRoles';
		$this->propertyTable['Iniciales']	= 'Iniciales';
		
		/*
		$this->propertyTable['LastWebIp'] 		= 'ulastwebip';
		$this->propertyTable['LastWebLogin']	= 'ulastweblogin';
		$this->propertyTable['LastHiperIp'] 	= 'ulasthiperip';
		$this->propertyTable['LastHiperLogin']	= 'ulasthiperlogin';
		$this->propertyTable['Created']			= 'ucreated';
		$this->propertyTable['LastUpdate']		= 'uupdate';
		*/
		
				
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
		return ('#'.$this->propertyTable['ID']);
	}
	
	public function __validate()  {
		return true;
	}

	public function Autenticado() {
		return $this->_autenticado;
	}
	
	public function esAdministrador() {
		return ($this->__get('Rol')=='A');
	}
	
	public function esVendedor() {
		return ($this->__get('Rol')=='V');
	}
	
	public function esAuditoria() {
		return ($this->__get('Rol')=='U');
	}
	
	public function esAdministracion() {
		return ($this->__get('Rol')=='M');
	}
	
	public function esProveedor() {
		return ($this->__get('Rol')=='P'); //Proveedor
	}
	
	public function esCliente() {
		return ($this->__get('Rol')=='C');
	}

	public function getNombreYApellidos() {
		return ($this->__get('Nombre'));
	}
	
	private function setAuth($_status=false) {
		$this->_autenticado = $_status;
	}
	
	public function login($_pwd) {	
		$this->setAuth(false);		
	   	$_status = (strcmp($_pwd, $this->__get('Clave')) == 0);
		$this->setAuth($_status);
	   	return $_status;
	}
}
?>