<?php
session_start();
$_SESSION 	= array();
$_toURL		= "/pedidos/index.php";
session_unset();
session_destroy();
header("location: " . $_toURL);
?>