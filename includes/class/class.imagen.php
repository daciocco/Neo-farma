<?php
require_once('class.PropertyObject.php');
//if (!defined('BASEPATH')) exit('No direct script access allowed');

class TImagen extends PropertyObject {
	protected $_tablename	= 'imagen';
	protected $_fieldid		= 'imgid';
	protected $_timestamp	= 0;
    protected $_id			= 0;
    //private $table = 'imagenes';
	
	public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'imgid';
		$this->propertyTable['Imagen']		= 'imgnombre';
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

   /* function insert($data) {
        if ($this->db->insert($this->table, $data))
            return $this->db->insert_id();
        return NULL;
    }

    function save($id, $data) {
        $this->db->where('mrc_id', $id);
        return $this->db->update($this->table, $data);
    }*/

}
