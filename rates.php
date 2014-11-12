<?php
/**
 * Created by PhpStorm.
 * User: nikshev
 * Date: 12.11.14
 * Time: 14:25
 */
require_once("iva.php");
$iva=new Iva();
if (isset($_POST["rates"])) {
    $rates_text = addslashes($_POST["rates"]);
    $iva->update_rates($rates_text);
}
$rates=$iva->get_rates();
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
            <legend>Cental bank rates</legend>
            <table class="table table-bordered" width="10%">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Rate</th>
                  </tr>
                </thead>
                <tbody>
                <?php if (count($rates)>1):?>
                 <?php foreach ($rates as $row):?>
                     <tr>
                         <td>
                            <?php echo $row["date"];?>
                         </td>
                         <td>
                             <?php echo $row["rate"];?>
                         </td>
                     </tr>
                 <?php endforeach?>
                <?php endif ?>
                </tbody>
            </table>
            <form class="form-horizontal" enctype="multipart/form-data" action="rates.php" method="POST">
                <fieldset>

                    <!-- Form Name -->
                    <legend>Cental bank rates update form</legend>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="rates">Rates</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="rates" name="rates"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="submit"></label>
                        <div class="col-md-8">
                            <button id="submit" name="submit">Update</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>
</html>