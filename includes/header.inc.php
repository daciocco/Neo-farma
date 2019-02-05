<?php
$_img			= "/pedidos/images/logo/logosHeaderWeb.png";

if (!empty($_SESSION['_usrname'])) {
   if ($_SESSION["_usrrol"]!="G" && $_SESSION["_usrrol"]!="A"){
	  $TOPRight[]	= sprintf("<span>%s - ( %s )</span>", $_SESSION["_usrname"], substr($_SESSION["_usrzonas"], 0, 15));
   } else {
	  $TOPRight[]	= sprintf("<span>%s</span>", $_SESSION["_usrname"]);
   }
}
$TOPRight[]	= sprintf("<span>%s</span>", infoFecha());
$TOPRightNav	= implode(" | ", $TOPRight);
?>

<div id="top2" align="center">
    <a href="/pedidos/index.php" title="Extranet">
        <img id="img_cabecera" src="<?php echo $_img;?>" alt="pedidos"/>
    </a>
</div>

<div id="top" align="center">
    <div id="topcenter" align="center"><?php echo $TOPRightNav;?></div>
</div>
