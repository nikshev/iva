<?php
/**
 * Created by PhpStorm.
 * User: nikshev
 * Date: 11.11.14
 * Time: 17:24
 */
$params=array();
$params["debt"]=addslashes($_POST["debt"]);
$params["unique"]=addslashes($_POST["unique"]);
$iva=new Iva();
$iva->update_debt($params);
$result=$iva->calc($params["unique"]);
?>