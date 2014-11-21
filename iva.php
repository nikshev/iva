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
        $now=new \DateTime("now");
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
    function fill_documents($unique=1){
        require_once("../PHPWord.php");
        require_once("../PHPExcel.php");
        require_once ('../PHPExcel/IOFactory.php');
        //ini_set('memory_limit', '512M');

        $result_array=array();

        //Prepare lawsuit
        if ($unique>0) {
            $this->connect();
            $query = "SELECT * FROM lawsuit WHERE id=" . $unique . ";";
            $result = mysql_query($query) or die('Query: ' . $query . ' in calc fault: ' . mysql_error());
            $row = mysql_fetch_assoc($result);
            $this->disconnect();

            if (isset($row["fn"])) {
                $total_amount = $this->get_total_amount($unique);

                if (!isset($total_amount["percent"]))
                    $total_amount["percent"] = 0;

                if (!isset($total_amount["inflation_sum"]))
                    $total_amount["inflation_sum"] = 0;

                if (!isset($total_amount["rate_summ"]))
                    $total_amount["rate_summ"] = 0;

                if (!isset($total_amount["total_amount"]))
                    $total_amount["total_amount"] = 0;

                //Word part
                $PHPWord = new PHPWord();
                $document = $PHPWord->loadTemplate('../templates/template1.docx');

                $now=new \DateTime("now");
                $document->setValue('item1', $row["fn"]);
                $result_array["fn"] = $row["fn"];
                $document->setValue('item2', $row["sn"]);
                $result_array["sn"] = $row["sn"];
                $document->setValue('item3', $row["address"]);
                $result_array["address"] = $row["address"];
                $document->setValue('item4', $row["tin"]);
                $result_array["tin"] = $row["tin"];
                $result_array["tar"] = round($total_amount["total_amount"] + $total_amount["percent"] + $total_amount["inflation_sum"] + $total_amount["rate_summ"], 2);
                $document->setValue('item5', $result_array["tar"]); //this field from Excel calculation
                $document->setValue('item6', $row["ac"]);
                $result_array["ac"] = $row["ac"];
                $document->setValue('item7', $row["asd"]);
                $result_array["asd"] = $row["asd"];
                $document->setValue('item8', $row["astd"]);
                $result_array["astd"] = $row["astd"];
                $document->setValue('item9', $row["pd"]);
                $result_array["pd"] = $row["pd"];
                $document->setValue('item10', $row["mps"]);
                $result_array["mps"] = $row["mps"];
                $document->setValue('item11', round($total_amount["total_amount"], 2));
                $result_array["total_amount"] = round($total_amount["total_amount"], 2);
                $document->setValue('item12', round($total_amount["percent"], 2));
                $result_array["percent"] = round($total_amount["percent"], 2);
                $document->setValue('item13', round($total_amount["inflation_sum"], 2));
                $result_array["inflation_sum"] = round($total_amount["inflation_sum"], 2);
                $document->setValue('item14', round($total_amount["rate_summ"], 2));
                $result_array["rate_summ"] = round($total_amount["rate_summ"], 2);
                $document->setValue('item16', $now->format('d.m.Y'));
                $document->save('../results/' . $now->format('Y-m-d h:i:s') . '-' . $row["sn"] . '.docx');
                $result_array["docx"] = '../results/' . $now->format('Y-m-d h:i:s') . '-' . $row["sn"] . '.docx';
            }
                //Excel part
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load("../templates/template1.xlsx");
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objPHPExcel->getActiveSheet()->setCellValue('D6', $now->format('d.m.Y'));
                $row_index=16;
                $total_amount=0.0;
                $total_percent_sum=0.0;
                $total_inflation_sum=0.0;
                $total_rate_sum=0.0;
                $this->connect();
                $query = "SELECT lawsuit_calc. * , lawsuit_debt.start_date, lawsuit_debt.amount ".
                         " FROM lawsuit_calc ".
                         " LEFT JOIN lawsuit_debt ON lawsuit_calc.debt_id = lawsuit_debt.id ".
                         " WHERE lawsuit_calc.lawsuit_id =" . $unique . ";";
                $result = mysql_query($query) or die('Query: ' . $query . ' in calc fault: ' . mysql_error());
                while ($row = mysql_fetch_assoc($result)){
                   // var_dump($row);
                    if (isset($row["start_date"])) {
                        $tmp_date=new DateTime($row["start_date"]);
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row_index, $tmp_date->format('d.m.Y'));
                        $royalti=$this->getRoyaltiFromNumber($tmp_date->format('m'));
                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row_index, $royalti." ".$tmp_date->format('Y')." року");
                    }

                    if (isset($row["amount"])){
                        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row_index, $row["amount"]);
                        $total_amount+=floatval($row["amount"]);
                    }

                    if (isset($row["days"])&&intval($row["days"])>0){
                        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row_index, $row["days"]);
                    }

                    if (isset($row["percent"])&&floatval($row["percent"])>0){
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . $row_index, $row["percent"]);
                        $total_percent_sum+=floatval($row["percent"]);
                    }

                    if (isset($row["inflation_av"])&&floatval($row["inflation_av"])>0){
                        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row_index, $row["inflation_av"]);
                    }

                    if (isset($row["inflation_sum"])&&floatval($row["inflation_sum"])!=0){
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . $row_index, $row["inflation_sum"]);
                        $total_inflation_sum+=$row["inflation_sum"];
                    }

                    if (isset($row["rate_start"])){
                        $tmp_date=new DateTime($row["rate_start"]);
                        $objPHPExcel->getActiveSheet()->setCellValue('H' . $row_index, $tmp_date->format('d.m.Y'));
                    }

                    if (isset($row["rate_stop"])){
                        $tmp_date=new DateTime($row["rate_stop"]);
                        $objPHPExcel->getActiveSheet()->setCellValue('I' . $row_index, $tmp_date->format('d.m.Y'));
                    }

                    if (isset($row["rate"])&&floatval($row["rate"])>0){
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . $row_index, $row["rate"]);
                    }

                    if (isset($row["rate_summ"])&&floatval($row["rate_summ"])>0){
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $row_index, $row["rate_summ"]);
                        $total_rate_sum+=floatval($row["rate_summ"]);
                    }
                    $row_index++;
                }
                $this->disconnect();
                //Total sum
                $row_index++;
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $row_index, round($total_amount,2));
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $row_index, round($total_percent_sum,2));
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $row_index, round($total_inflation_sum,2));
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $row_index, round($total_rate_sum,2));

                $objWriter->save('../results/' . $now->format('Y-m-d h:i:s') . '-' . $row["sn"] . '.xlsx');
                $result_array["xlsx"] = '../results/' . $now->format('Y-m-d h:i:s') . '-' . $row["sn"] . '.xlsx';

        }
        return $result_array;
    }

    function getRoyaltiFromNumber($month){
        $ret_val="";
        $int_month=intval($month);
        switch ($int_month){
            case 1: $ret_val="Роялтi за Ciчень ";
                break;
            case 2: $ret_val="Роялтi за Лютий ";
                break;
            case 3: $ret_val="Роялтi за Березень ";
                break;
            case 4: $ret_val="Роялтi за Квiтень ";
                break;
            case 5: $ret_val="Роялтi за Травень ";
                break;
            case 6: $ret_val="Роялтi за Червень ";
                break;
            case 7: $ret_val="Роялтi за Липень ";
                break;
            case 8: $ret_val="Роялтi за Серпень ";
                break;
            case 9: $ret_val="Роялтi за Вересень ";
                break;
            case 10: $ret_val="Роялтi за Жовтень ";
                break;
            case 11: $ret_val="Роялтi за Листопад ";
                break;
            case 12: $ret_val="Роялтi за Грудень ";
                break;
        }
        return $ret_val;
    }

    /**
     * @param $unique
     */
    function calc($unique){
        $result_array=array();
        $now=new \DateTime("now");
      if (isset($unique)){
          $this->connect();
          $query = "SELECT * FROM lawsuit_debt WHERE lawsuit_id=".$unique." order by start_date;";
          $result = mysql_query($query) or die('Query: '.$query.' in calc fault: ' . mysql_error());
          $this->disconnect();
          while ($row=mysql_fetch_assoc($result)){
              $result_array["debt_id"]=$row["id"];
              $result_array["days"]=$now->diff(new DateTime($row["start_date"]))->days;
              if (intval($result_array["days"])>0) {
                  $result_array["percent"] = (intval($result_array["days"])*0.03/365)*$row["amount"];
              }
              $result_array["inflation_av"]=$this->get_inflation_av($row["start_date"]);
              $result_array["inflation_sum"]=(($result_array["inflation_av"]*$row["amount"])/100)-$row["amount"];
              $result_array["rates"]=$this->get_rates_calc($row["start_date"],$row["amount"]);
            //  echo "----------------------------------".$row["start_date"]."--------------------------------------<br/>";
             // var_dump($result_array);
              //echo "</br>";
              $first_row=true;
              foreach ($result_array["rates"] as $rrow) {
                  if ($first_row) {
                      $query = "INSERT INTO lawsuit_calc(debt_id,lawsuit_id, days, percent,".
                          " inflation_av, inflation_sum, rate_start," .
                          " rate_stop, rate_days, rate, rate_summ)" .
                          " VALUES (".$result_array["debt_id"]."," . $unique . "," . $result_array["days"] . ","
                          . $result_array["percent"] . "," . $result_array["inflation_av"] .",".
                          $result_array["inflation_sum"].
                          ",STR_TO_DATE('". $rrow["period_start"]."','%d.%m.%Y'),".
                          "STR_TO_DATE('". $rrow["period_stop"]."','%d.%m.%Y'),".
                          $rrow["days"].",".$rrow["rate"].",".$rrow["amount"].")";
                          $first_row=false;
                      } else {
                       $query = "INSERT INTO lawsuit_calc(lawsuit_id, rate_start," .
                          " rate_stop, rate_days, rate, rate_summ)" .
                          " VALUES (" . $unique .
                          ",STR_TO_DATE('". $rrow["period_start"]."','%d.%m.%Y'),".
                          "STR_TO_DATE('". $rrow["period_stop"]."','%d.%m.%Y'),".
                          $rrow["days"].",".$rrow["rate"].",".$rrow["amount"].")";
                  }
                  $this->connect();
                  mysql_query($query) or die('Query: '.$query.' in calc fault: ' . mysql_error());
                  $this->disconnect();
              }
          }
      }
    }

    /**
     * @param $date
     * @return float
     */
    function get_inflation_av($date){
        $this->connect();
        $new_date=new DateTime($date);
        $interval=new DateInterval('P1M');
        $cond_date=$new_date->sub($interval);
        $query = "SELECT * FROM inflation WHERE period_start>STR_TO_DATE('". $cond_date->format('d.m.Y')."','%d.%m.%Y')";
        //echo "<br/> query inflation=".$query."<br/>";
        $result = mysql_query($query) or die('Query: '.$query.' in calc fault: ' . mysql_error());
        $i=0;
        $inf_summ=0.0;
        while ($row=mysql_fetch_assoc($result)){
            $inf_summ+=floatval($row["inflation"]);
            $i++;
        }
        $this->disconnect();
        //echo "summ=".$inf_summ." i=".$i."<br/>";
        if ($i>0)
            return $inf_summ/$i;
        else
            return 0.0;
    }

    /**
     * @param $date
     * @param $amount
     * @return array
     */
    function get_rates_calc($date,$amount){
        $this->connect();
        $new_date=new DateTime($date);
        $interval=new DateInterval('P15D');
        $cond_date=$new_date->sub($interval);
        $query = "SELECT * FROM rates WHERE period_start>STR_TO_DATE('". $cond_date->format('d.m.Y')."','%d.%m.%Y') order by period_start;";
        $result = mysql_query($query) or die('Query: '.$query.' in get_rates_calc fault: ' . mysql_error());
        $result_array=array();
        while ($row=mysql_fetch_assoc($result)){
            $old_date=$new_date;
            $new_date=new DateTime($row["period_start"]);
            $days=$new_date->diff($old_date)->days;
            $result_array[]=array("period_start"=>$old_date->format("d.m.Y"),
                                  "period_stop"=>$new_date->format("d.m.Y"),
                                  "days"=>$days,
                                  "rate"=>$row["rate"],
                                  "amount"=>($amount*$days*($row["rate"]/100)*2)/365);
        }
        $this->disconnect();
        return $result_array;
    }

    function get_total_amount($unique){
        $result_array=array();
        //Get data from lawsuit_calc
        $this->connect();
        $query = "SELECT SUM(percent) as percent_sum, SUM(inflation_sum) as inflation_summ ".
                 ",SUM(rate_summ) as rate_sum FROM lawsuit_calc WHERE lawsuit_id=".$unique.";";
        $result = mysql_query($query) or die('Query: '.$query.' in get_total_amount fault: ' . mysql_error());
        $row=mysql_fetch_assoc($result);
        $result_array["percent"]=$row["percent_sum"];
        $result_array["inflation_sum"]=$row["inflation_summ"];
        $result_array["rate_summ"]=$row["rate_sum"];
        $this->disconnect();

        //Get data from lawsuit_debt
        $this->connect();
        $query = "SELECT SUM(amount) as amount_sum FROM lawsuit_debt WHERE lawsuit_id=".$unique.";";
        $result = mysql_query($query) or die('Query: '.$query.' in get_total_amount fault: ' . mysql_error());
        $row=mysql_fetch_assoc($result);
        $result_array["total_amount"]=$row["amount_sum"];
        $this->disconnect();

        return $result_array;
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