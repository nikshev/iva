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
$iva->update_debt($params);
$iva->calc($params["unique"]);
$result=$iva->fill_documents($params["unique"]);
//$result=$iva->fill_documents(1);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Lawsuit and calculation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="Lawsuit and calculation" content="Lawsuit and calculation">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css" media="screen" />
</head>
<body>
<div style="margin: 0 auto; width:60%">
    <h1>Results</h1>
    <a href="http://ua.linkedin.com/pub/eugene-shkurnikov/79/b13/124">About me</a>
<table id="myTable" class="table table-bordered" width="10%">
 <thead>
  <tr>
    <th>Value name</th>
    <th>Value</th>
  </tr>
 </thead>
 <tbody>
  <tr>
      <td>Name</td>
      <td><?php echo $result["fn"]."(".$result["sn"].")";?></td>
  </tr>
  <tr>
      <td>Address</td>
      <td><?php echo $result["address"];?></td>
  </tr>
  <tr>
      <td>Taxpayer identification</td>
      <td><?php echo $result["tin"];?></td>
  </tr>
  <tr>
      <td>Agreement code</td>
      <td><?php echo $result["ac"];?></td>
  </tr>
  <tr>
      <td>Agreement start date</td>
      <td><?php echo $result["asd"];?></td>
  </tr>
  <tr>
      <td>Agreement stop date</td>
      <td><?php echo $result["astd"];?></td>
  </tr>
  <tr>
      <td>Prolongation date</td>
      <td><?php echo $result["pd"];?></td>
  </tr>
  <tr>
      <td>Monthly payment size</td>
      <td><?php echo $result["mps"];?></td>
  </tr>
  <tr>
      <td>Amount to be recovered</td>
      <td><?php echo $result["tar"];?></td>
  </tr>
  <tr>
      <td>Total sum</td>
      <td><?php echo $result["total_amount"];?></td>
  </tr>
  <tr>
      <td>Total sum (percent data)</td>
      <td><?php echo $result["percent"];?></td>
  </tr>
  <tr>
      <td>Total sum (inflation data)</td>
      <td><?php echo $result["inflation_sum"];?></td>
  </tr>
  <tr>
      <td>Total sum (rate data)</td>
      <td><?php echo $result["rate_summ"];?></td>
  </tr>
  <tr>
      <td>Lawsuit</td>
      <td><?php echo "<a href='".$result["docx"]."'>Download</a><br/>";?></td>
  </tr>
  <tr>
      <td>Calculation</td>
      <td><?php echo "<a href='".$result["xlsx"]."'>Download</a><br/>";?></td>
  </tr>
 </tbody>
</table>
</div>
</body>
</html>
