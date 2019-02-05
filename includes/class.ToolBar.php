<?php
//require_once('constantes.php');

class ToolBar
{  
  /**
   * $_config es un array asociativo con estructura
   * 'estilo' 	= estilo
   * 'modo'		= [alumno|profesor|administrador]
   *
   * @var array
   * @author Julian Arevalo
   * @param $_config (array asociativo)
   */
  private $objID;
  private $objConfig = array();
  
  private function __construct($_config = array()) {    
  	$this->objConfig = $_config;
  }
   
  static public function factory($_config = array())  {
//  	if (!isset($objID)) {
    $_toolbar = new ToolBar($_config);
//	}
	return $_toolbar;
  }
  
  private function makeLinks() {
  	$_items 	= array();
  	$_links 	= $this->getLinks();
  	foreach ($_links as $i => $_link) {
  		$_items[] = sprintf("<a href=\"%s\" title=\"%s\" class=\"%s\">%s</a>", $_link['url'], $_link['texto'], $_link['class'], $_link['texto'] );
  	}
  	return (implode($this->objConfig['separador'], $_items));
  }

  function getLinks() {
  	return $this->objConfig['links'];
  }

  function show() {
  //	echo sprintf( "<div class=\"%s\">%s</div>", $this->objConfig['estilo'],$this->makeLinks());
   	echo $this->makeLinks();
  }
}
?>