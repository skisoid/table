<?php
$myurl=$_SERVER['PHP_SELF'];
$mode="help.html";
if(isset($_GET['mode']) && $_GET[('mode')]!=""){
	$mode=$_GET['mode'];
}
//Siin lisatakse "ühekordselt" päis
include_once("conv-header.html");
//Siin läheb siis mode kontrollimiseks ja kuvatavasse htmli
//lisatakse vastav html
	switch($mode){
         case "convert":
        	include("converter.php");
			break;
		case "about":
            include("about.html");
            break;
       	case "help":
            include("help.html");
            break;
		default:
			include("about.html");
			break;
	}
include_once("footer.html");
?>