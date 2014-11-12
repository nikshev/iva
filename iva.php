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
            $params["asd"]=$now->format('d.m.Y');

        if (!isset($params["astd"]))
            $params["astd"]=$now->format('d.m.Y');

        if (!isset($params["pd"]))
            $params["pd"]=$now->format('d.m.Y');

        if (!isset($params["mps"]))
            $params["mps"]=0.0;

        $this->connect();
        $query="INSERT INTO lawsuit (fn,sn,address,tin,tar,ac,asd,astd,pd,mps)
              VALUES ('".$params["fn"]."','".$params["sn"]."','".$params["address"]."','".$params["tin"].
              "',".$params["tar"].",'".$params["ac"]."',STR_TO_DATE('".$params["asd"]."','%d.%m.%Y'),
              STR_TO_DATE('".$params["astd"]."','%d.%m.%Y'),STR_TO_DATE('".
               $params["pd"]."','%d.%m.%Y'),".$params["mps"].");";
        mysql_query($query) or die('Query in post_parameters fault: ' . mysql_error());
        $unique=mysql_insert_id();
        $this->disconnect();
        return $unique;
    }

    /**
     * @param array $params
     */
    function update_debt($params=array()){
      if (isset($params["debt"])&&isset($params["unique"])) {
          $str=$params["debt"];
          $result=$this->explode_new($str);
          foreach ($result as $row){
              $this->connect();
              $query="INSERT INTO lawsuit_debt (lawsuit_id,start_date,amount)
              VALUES (".$params["unique"].",STR_TO_DATE('".$row["date"]."','%d.%m.%Y'),".$row["amount"].");";
              mysql_query($query) or die('Query in update_debt fault: ' . mysql_error());
              $this->disconnect();
          }
      }
    }



    /**
     * @return array
     */
    function fill_documents($unique){
        require_once("../PHPWord.php");
        $result=array();

        //Prepare lawsuit
        $this->connect();
        $query = "SELECT * FROM lawsuit WHERE id=".$unique.";";
        $result = mysql_query($query) or die('Query: '.$query.' in calc fault: ' . mysql_error());
        $row=mysql_fetch_assoc($result);
        $this->disconnect();


        $PHPWord = new PHPWord();
        $document = $PHPWord->loadTemplate('../templates/template1.docx');

        $now=new DateTime();
        $document->setValue('item1', $row["fn"]);
        $document->setValue('item2', $row["sn"]);
        $document->setValue('item3', $row["address"]);
        $document->setValue('item4', $row["tin"]);
        $document->setValue('item5', $row["tar"]); //this field from Excel calculation
        $document->setValue('item6', $row["ac"]);
        $document->setValue('item7', $row["asd"]);
        $document->setValue('item8', $row["astd"]);
        $document->setValue('item9', $row["pd"]);
        $document->setValue('item10', $row["mps"]);
        $document->setValue('item16', $now->format('d.m.Y'));
        $document->save('../results/'.$now->format('Y-m-d h:i:s').'-'.$row["sn"].'.docx');

        return $result;
    }

    /**
     * @param $unique
     */
    function calc($unique){

    }

    /**
     * @param $str
     * @return array
     */
    function explode_new($str){
        $result=array();
        $tmp_str="";
        $date="";

        for ($i=0;$i<strlen($str);$i++) {
            if (strcmp($str[$i], ' ') > 0) {
                $tmp_str .= $str[$i];
                // echo "Str=".strcmp($str[$i],' ')."</br>";
            } else {
                if (strlen($date) > 0) {
                    $result[] = array("date" => $date, "amount" => $tmp_str);
                    $date = "";
                } else
                    $date = $tmp_str;
                $tmp_str = "";
            }
        }
        return $result;
    }

    /**
     * @param $str
     */
    function update_rates($str){
        if (isset($str)) {
            $result=$this->explode_new($str);
            foreach ($result as $row){
                $this->connect();
                $query="INSERT INTO rates (period_start,rate)
              VALUES (STR_TO_DATE('".$row["date"]."','%d.%m.%Y'),".$row["amount"].");";
                mysql_query($query) or die('Query in update_rates fault: ' . mysql_error());
                $this->disconnect();
            }
        }
    }

    /**
     * @return array
     */
    function get_rates(){
        $this->connect();
        $query = "SELECT * FROM rates ORDER BY period_start DESC";
        $mysql_result = mysql_query($query) or die('Query in get_rates fault: ' . mysql_error());
        $result=array();
        while ($row=mysql_fetch_assoc($mysql_result))
          $result[]=array("date"=>$row["period_start"],"rate"=>$row["rate"]);
        $this->disconnect();
        return $result;
    }

    /**
     * @param $str
     */
    function update_inflation($str){
        if (isset($str)) {
            $result=$this->explode_new($str);
            foreach ($result as $row){
                $this->connect();
                $query="INSERT INTO inflation (period_start,inflation)
              VALUES (STR_TO_DATE('".$row["date"]."','%d.%m.%Y'),".$row["amount"].");";
                mysql_query($query) or die('Query in update_inflation fault: ' . mysql_error());
                $this->disconnect();
            }
        }
    }

    /**
     * @return array
     */
    function get_inflation(){
        $this->connect();
        $query = "SELECT * FROM inflation ORDER BY period_start DESC";
        $mysql_result = mysql_query($query) or die('Query in get_inflation fault: ' . mysql_error());
        $result=array();
        while ($row=mysql_fetch_assoc($mysql_result))
            $result[]=array("date"=>$row["period_start"],"inflation"=>$row["inflation"]);
        $this->disconnect();
        return $result;
    }

}