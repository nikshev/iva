<?php
/**
 * Created by PhpStorm.
 * User: nikshev
 * Date: 11.11.14
 * Time: 15:40
 */
require_once("iva.php");
$params=array();
$params["fn"]=addslashes($_POST["fn"]);
$params["sn"]=addslashes($_POST["sn"]);
$params["address"]=addslashes($_POST["address"]);
$params["tin"]=addslashes($_POST["tin"]);
$params["tar"]=floatval($_POST["tar"]);
$params["ac"]=addslashes($_POST["ac"]);
$params["asd"]=addslashes($_POST["asd"]);
$params["astd"]=addslashes($_POST["astd"]);
$params["pd"]=addslashes($_POST["pd"]);
$params["mps"]=floatval($_POST["mps"]);
$iva=new Iva();
$unique=$iva->post_parameters($params);
?>
<html>
<title>Lawsuit and calculation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Lawsuit and calculation" content="Lawsuit and calculation">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row clearfix">

        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6  column col-sm-offset-0 col-md-offset-2 col-lg-offset-3">

            <form class="form-horizontal" enctype="multipart/form-data" action="calc.php" method="POST">
                <fieldset>

                    <!-- Form Name -->
                    <legend>Settings (step №2)</legend>
                    <a href="http://ua.linkedin.com/pub/eugene-shkurnikov/79/b13/124">About me</a>
                    <input type="hidden" id="unique" name="unique" value="<?php echo $unique ?>"/>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="lp">Late payments</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="lp" name="lp">Please enter late payments</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="submit"></label>
                        <div class="col-md-8">
                            <button id="submit" name="submit">Calculate</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>
</html>