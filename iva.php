<?php
/**
 * Created by PhpStorm.
 * User: nikshev
 * Date: 11.11.14
 * Time: 13:22
 */

class Iva {

    var $link;
    var $host='localhost';
    var $dbname="iva";
    var $dbuser='iva';
    var $password="iva";

    /**
     * Constructor
     */
    function __constructor(){

    }


    /**
     * Connection to database
     */
    function connect(){
        $this->link = mysql_connect($this->host, $this->dbuser,$this->password);
        if (!$this->link) {
            die('Connection error: ' . mysql_error());
        }
        mysql_select_db($this->dbname) or die('Database error: ' . mysql_error());
    }

    /**
     * Disconnect
     */
    function disconnect(){
        if (isset($this->link))
            mysql_close($this->link);
    }

    /**
     * @param array $params
     * @return string
     */
    function post_parameters($params=array()){

        $unique=-1;
        $now=new DateTime();
        if (!isset($params["fn"]))
            $params["fn"]="unknown";

        if (!isset($params["sn"]))
            $params["sn"]="unknown";

        if (!isset($params["address"]))
            $params["address"]="unknown";

        if (!isset($params["tin"]))
            $params["tin"]="unknown";

        if (!isset($params["tar"]))
            $params["tar"]=0.0;

        if (!isset($params["ac"]))
            $params["ac"]="unknown";

        if (!isset($params["asd"]))
            $params["asd"]=$now->format('Y-m-d');

        if (!isset($params["astd"]))
            $params["astd"]=$now->format('Y-m-d');

        if (!isset($params["pd"]))
            $params["pd"]=$now->format('Y-m-d');

        if (!isset($params["mps"]))
            $params["mps"]=0.0;

        $this->connect();
        $query="INSERT INTO lawsuit (fn,sn,address,tin,tar,ac,asd,astd,pd,mps)
              VALUES ('".$params["fn"]."','".$params["sn"]."','".$params["address"]."','".$params["tin"].
              "',".$params["tar"].",'".$params["ac"]."','".$params["asd"]."','".$params["astd"]."','".
               $params["pd"]."',".$params["mps"].");";
        mysql_query($query) or die('Query in post_parameters fault: ' . mysql_error());
        $unique=mysql_insert_id();
        $this->disconnect();
        return $unique;
    }

    /**
     * @param array $params
     */
    function update_debt($params=array()){

    }

    /**
     * @return array
     */
    function calc($unique){
        require_once("../PHPWord.php");
        $result=array();

        //Prepare lawsuit
        $this->connect();
        $query = "SELECT * FROM lawsuit WHERE id=".$unique.";";
        $result = mysql_query($query) or die('Query in calc fault: ' . mysql_error());
        $row=mysql_fetch_assoc($result);
        $this->disconnect();

        $PHPWord = new PHPWord();
        $document = $PHPWord->loadTemplate('../templates/template1.docx');

        $document->setValue('@1', $row["fn"]);
        $document->setValue('@2', $row["sn"]);
        $document->setValue('@3', $row["address"]);
        $document->setValue('@4', $row["tin"]);
        $document->setValue('@5', $row["tar"]); //this field from Excel calculation
        $document->setValue('@6', $row["ac"]);
        $document->setValue('@7', $row["asd"]);
        $document->setValue('@8', $row["astd"]);
        $document->setValue('@9', $row["pd"]);
        $document->setValue('@10', $row["mps"]);

        $now=new DateTime();
        $document->save('../results/'.$now->format('Y-m-d').'-'.$row["sn"].'.docx');

        return $result;
    }

}