<?php
/**
 * Created by PhpStorm.
 * User: nikshev
 * Date: 11.11.14
 * Time: 17:24
 */
require_once("iva.php");
$params=array();

if (isset($_POST["lp"]))
   $params["debt"]=addslashes($_POST["lp"]);
else
    $params["debt"]="";

if (isset($_POST["unique"]))
    $params["unique"]=addslashes($_POST["unique"]);
else
    $params["unique"]=-1;

$iva=new Iva();
//$iva->update_debt($params);
//$iva->calc($params["unique"]);
//$result=$iva->fill_documents($params["unique"]);
$result=$iva->fill_documents(1);
var_dump($result);
echo "<a href='".$result["docx"]."'>Download docx</a><br/>";
echo "<a href='".$result["xlsx"]."'>Download xlsx</a><br/>";
?>