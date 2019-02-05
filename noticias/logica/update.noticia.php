<?php
 session_start(); 
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

 if ($_SESSION["_usrrol"]!="A"){
	$_nextURL = sprintf("%s", "/pedidos/login/index.php");
	header("Location: $_nextURL");
  	exit;
 }

 $_idnt		= empty($_REQUEST['idnt']) ? 0 : $_REQUEST['idnt'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/noticias/': $_REQUEST['backURL'];

 $_titulo	= $_POST['ntitulo'];
 $_fecha	= $_POST['nfecha'];
 $_noticia	= $_POST['nnoticia'];
 $_link		= $_POST['nlink'];
  
 $_SESSION['s_titulo']	= $_titulo;
 $_SESSION['s_fecha']	= $_fecha;
 $_SESSION['s_noticia']	= $_noticia;
 $_SESSION['s_link']	= $_link;
  
 if (empty($_titulo)) {
	$_goURL = sprintf("/pedidos/noticias/editar.php?idnt=%d&sms=%d", $_idnt, 1);
 	header('Location:' . $_goURL);
	exit;
 }
 
 if (empty($_fecha)) {
	$_goURL = sprintf("/pedidos/noticias/editar.php?idnt=%d&sms=%d", $_idnt, 2);
 	header('Location:' . $_goURL);
	exit;
 }
 
 if (empty($_noticia)) {
	$_goURL = sprintf("/pedidos/noticias/editar.php?idnt=%d&sms=%d", $_idnt, 3);
 	header('Location:' . $_goURL);
	exit;
 }
 
 $date = $_fecha;
 list($día, $mes, $año) = explode('[/.-]', $date);		
 $_fecha = $año."-".$mes."-".$día;
 
 $_noticiaobject	= ($_idnt) ? DataManager::newObjectOfClass('TNoticia', $_idnt) : DataManager::newObjectOfClass('TNoticia');
 $_noticiaobject->__set('Titulo', 			$_titulo);
 $_noticiaobject->__set('Fecha', 			$_fecha);
 $_noticiaobject->__set('Descripcion',		$_noticia);
 $_noticiaobject->__set('Link', 			$_link);
 if ($_idnt) {
	 $ID = DataManager::updateSimpleObject($_noticiaobject);
 } else {
 	$_noticiaobject->__set('ID',		$_noticiaobject->__newID());
 	$_noticiaobject->__set('Activa', 	1);
 	$ID = DataManager::insertSimpleObject($_noticiaobject);
 }
 
 unset($_SESSION['s_titulo']);
 unset($_SESSION['s_fecha']);
 unset($_SESSION['s_noticia']);
 unset($_SESSION['s_link']);
 
 header('Location:' . $backURL);
?>