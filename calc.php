<?php
/**
 * Created by PhpStorm.
 * User: nikshev
 * Date: 11.11.14
 * Time: 17:24
 */
require_once("iva.php");
$params=array();
$params["debt"]=addslashes($_POST["lp"]);
$params["unique"]=addslashes($_POST["unique"]);
$iva=new Iva();
//$iva->update_debt($params);
//$iva->calc($params["unique"]);
$result=$iva->fill_documents($params["unique"]);

?>