<?php 

if(isset($_GET['mode']) && $_GET[('mode')]!=""){
	$mode=$_GET['mode'];
}
include_once("conv-header.html");
echo <<<_END
    <html><head><title>CSV table converter</title></head><body>
    <form method='post' action='converter.php' enctype='multipart/form-data'>
    Select file: <input type='file' name='filename' size='10'>
    <input type='submit' value='Convert'>
    </form>
_END;
include_once("table-conv.php");


if($_FILES){
    $name=$_FILES['filename']['name'];
    move_uploaded_file($_FILES['filename']['tmp_name'],$name);
    echo "<br>";
    echo "Converting file '$name'";
    echo "<br>";
    echo "<pre>";
    if(parse_file($name)==True){
        print_file($name);
        

        echo "</br>";
        echo "</br>";
        echo "</br>";
    }
    echo "</br>";
    print_sum_file($name);
    echo "</pre>";
}

echo "</body></html>"
?>





