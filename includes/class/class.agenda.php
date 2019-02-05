<?php
require_once('class.PropertyObject.php');

class TAgenda extends PropertyObject {
    protected $_tablename	= 'agenda';
	protected $_fieldid		= 'agid';
	protected $_fieldactivo	= 'agactiva';
	protected $_timestamp	= 0;
    protected $_id			= 0;

    public function __construct($_id=NULL) {
		$this->_timestamp	= time();		
		$this->_id			= $_id;
		if ($_id) {
			$arData = DataManager::loadFromDatabase($this, $_id);		
	   		parent::__construct($arData);
		}
		$this->propertyTable['ID'] 			= 'agid';
		$this->propertyTable['IdUsr'] 		= 'agidusr';	
		//$this->propertyTable['Tipo'] 		= 'agtipo';	
		$this->propertyTable['Color'] 		= 'agcolor';		
		$this->propertyTable['StartDate']	= 'agstartdate';
		$this->propertyTable['EndDate']		= 'agenddate';
		$this->propertyTable['Title'] 		= 'agtitle';	
		$this->propertyTable['Texto'] 		= 'agtexto';
		$this->propertyTable['Url'] 		= 'agurl';
		$this->propertyTable['Restringido'] = 'agrestringido';			
		
		//$this->propertyTable['Start_Date']	= 'ag_startdate';
		//$this->propertyTable['End_Date'] 	= 'ag_enddate';
		
		//$this->propertyTable['RecType']	 	= 'agrectype'; //day - week
		//$this->propertyTable['']	 		= '';
		$this->propertyTable['UsrUpdate']	= 'agusr';
		$this->propertyTable['LastUpdate']	= 'adlastupdate';
		$this->propertyTable['Activa'] 		= 'agactiva';
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