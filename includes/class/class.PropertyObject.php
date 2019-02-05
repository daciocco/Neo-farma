<?php
  require_once('interface.Validator.php');

  abstract class PropertyObject implements Validator {   
    protected $data;                        	//datos actuales de la BBDD (Sin cambios)
    protected $propertyTable 		= array();  //almacena pares nombre-valor asociando propiedades a campos de la BBDD.
    protected $changedProperties 	= array(); 	//lista de propiedades que han sido cambiadas
    protected $errors 				= array();  //Errores de validación
    protected $updatePending		= FALSE;    //Actualización pendiente
   
    public function __construct($arData) {
      $this->data = $arData;
    }
   
	function __get($propertyName) {
		if(!array_key_exists($propertyName, $this->propertyTable)) throw new Exception("Propiedad inexistente: \"$propertyName\"!");
		if(method_exists($this, 'get' . $propertyName)) {	
			return call_user_func(array($this, 'get' . $propertyName));
		} else {
			return $this->data[0][$this->propertyTable[$propertyName]];
		}
	}
 
    function __set($propertyName, $value) {
      if(!array_key_exists($propertyName, $this->propertyTable)) throw new Exception("Propiedad inexistente: \"$propertyName\"!");

      if(method_exists($this, 'set' . $propertyName)) {
		$this->updatePending = TRUE;
	  	return call_user_func( array($this, 'set' . $propertyName), $value );
      } else {      
        //Si el valor de la propiedad ha cambiado y la propiedad no se h incluido entre las cambiadas, lo hacemos aquí.
//        if($this->data[$this->propertyTable[$propertyName]] != $value && !in_array($propertyName, $this->changedProperties)) {
		  
        if(!in_array($propertyName, $this->changedProperties)) {
          $this->changedProperties[] = $propertyName;
        }
        //Now set the new value
		$this->updatePending = TRUE;
        $this->data[$this->propertyTable[$propertyName]] = $value;
      }
    }

	// Devuelve el nombre de campo de tabla que corresponde con una propiedad;
	//
	function __getFieldName( $_propertyName ) {
		return $this->propertyTable[$_propertyName];
	}

	// Devuelve el array (nombre_campo_tabla => valor_campo) para inserciones en la BBDD
	//
	function __getData() {
		return $this->data;
	}

	// Necesario Actualizar?
	//
	function __updatePending() {
		return $this->updatePending;
	}
	
	// Devuelve el array (nombre_campo_tabla => valor_campo) únicamente de aquellos campos modificados
	//
	function __getUpdated() {
		$_updated = array();
		foreach($this->changedProperties as $k => $property) {
			$_updated[$this->propertyTable[$property]] = $this->data[$this->propertyTable[$property]];
		}
		return $_updated;
	}

    function validate() {
		return true;
    }
}
?>
