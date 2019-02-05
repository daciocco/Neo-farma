<?php
 session_start();
 require_once( $_SERVER['DOCUMENT_ROOT']."/pedidos/includes/class.dm.php" );

if ($_SESSION["_usrrol"]!="A"){
		$_nextURL = sprintf("%s", "/pedidos/login/index.php");
		echo $_SESSION["_usrol"];
	 	header("Location: $_nextURL");
 		exit;
}

 $_uid		= empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
 $backURL	= empty($_REQUEST['backURL']) ? '/pedidos/usuarios/': $_REQUEST['backURL'];

 $_zonas				= $_POST['zzonas'];  
 $_SESSION['s_zonas'] 	= $_zonas;
 
 if ($_uid) {
	 DataManager::deletefromtabla('zonas_vend', 'uid', $_uid);
	 $_campos 	= "uid, zona";
	 $_tabla	= "zonas_vend";
	 if(count($_zonas) > 0){
	 	foreach($_zonas as $nrozona){
			//echo "XXX $_tabla, $_campos, $_uid, $nrozona ZZZZ";
			DataManager::insertfromtabla($_tabla, $_campos, $_uid, $nrozona);
	 	}
	 }
 }
 
 unset($_SESSION['s_zonas'] );
 
 header('Location:' . $backURL);
?>