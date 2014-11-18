<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
 <title>Lawsuit and calculation</title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <meta name="Lawsuit and calculation" content="Lawsuit and calculation">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css" media="screen" />
 <link rel="stylesheet" type="text/css" href="../datetime/css/bootstrap-datetimepicker.min.css"/>
 <script src="../datetime/js/bootstrap-datetimepicker.min.js"></script>
 <script type="text/javascript">
  $(function() {
   $('#datetimepicker1').datetimepicker({
    language: 'pt-BR'
   });

   $('#datetimepicker2').datetimepicker({
    language: 'pt-BR'
   });

   $('#datetimepicker3').datetimepicker({
    language: 'pt-BR'
   });

  });
 </script>
</head>
 <body>
 <div class="container">
  <div class="row clearfix">

   <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6  column col-sm-offset-0 col-md-offset-2 col-lg-offset-3">

  <form class="form-horizontal" enctype="multipart/form-data" action="next.php" method="POST">
   <fieldset>
   <legend>Settings (step №1)</legend>


   <div class="control-group">
    <label class="control-label " for="fn">Full name</label>
    <div class="controls">
     <input type="text" id="fn" name="fn" placeholder="Please enter full name..." /><br/>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="sn">Short name</label>
    <div class="controls">
     <input type="text" id="sn" name="sn" placeholder="Please enter short name..." value=""/><br/>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="address">Address</label>
    <div class="controls">
     <input type="text" id="address" name="address" placeholder="Please enter address..." value=""/><br/>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="tin">Taxpayer identification</label>
    <div class="controls">
     <input type="text" id="tin" name="tin" placeholder="Please enter taxpayer identification number..." value=""/><br/>
    </div>
   </div>

   <!--<div class="control-group">
    <label class="control-label" for="tar">Amount to be recovered</label>
    <div class="controls">
     <input type="text" id="tar" name="tar" placeholder="Please enter total amount to be recovered..." value=""/><br/>
    </div>
   </div>-->

   <div class="control-group">
    <label class="control-label" for="ac">Agreement code</label>
    <div class="controls">
     <input type="text" id="ac" name="ac" placeholder="Please enter agreement code..." value=""/><br/>
    </div>
    </div>

   <div class="control-group">
    <label class="control-label" for="asd">Agreement start date</label>
    <div class="controls">
     <div id="datetimepicker1" class="input-append date">
      <input data-format="dd.MM.yyyy" type="text" id="asd" name="asd" placeholder="Please enter agreement start date..." value=""/>
       <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
        </i>
       </span>
    </div>
    </div>
   </div>


   <div class="control-group">
    <label class="control-label" for="astd">Agreement stop date</label>
    <div class="controls">
     <div id="datetimepicker2" class="input-append date">
      <input data-format="dd.MM.yyyy" type="text" id="astd" name="astd" placeholder="Please enter agreement stop date..." value=""/>
       <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
        </i>
       </span>
     </div>
    </div>
   </div>



   <div class="control-group">
    <label class="control-label" for="pd">Prolongation date</label>
    <div class="controls">
     <div id="datetimepicker3" class="input-append date">
      <input data-format="dd.MM.yyyy" type="text" id="pd" name="pd" placeholder="Please enter prolongation date..." value=""/>
       <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
        </i>
       </span>
     </div>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="mps">Monthly payment size</label>
    <div class="controls">
     <input type="text" id="mps" name="mps" placeholder="Please enter monthly payment size..." value=""/><br/>
    </div>
   </div>

    <div class="control-group">
     <div class="controls">
      <button type="submit" class="btn">Next step</button>
     </div>
    </div>

  <!-- <div class="control-group">
    <label class="control-label" for="pdt">Principal debt</label>
    <div class="controls">
     <input type="text" id="pdt" name="pdt" placeholder="Please enter principal debt..." value=""/><br/>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="tpa">3% total amount</label>
    <div class="controls">
     <input type="text" id="tpa" name="tpa" placeholder="Please enter 3% total amount..." value=""/><br/>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="ita">Inflation total amount</label>
    <div class="controls">
     <input type="text" id="ita" name="ita" placeholder="Please enter inflation total amount..." value=""/><br/>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="pta">Penaulty total amount</label>
    <div class="controls">
     <input type="text" id="pta" name="pta" placeholder="Please enter penaulty total amount..." value=""/><br/>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="cf">Court fee</label>
    <div class="controls">
     <input type="text" id="cf" name="cf" placeholder="Please enter court fee..." value=""/><br/>
    </div>
   </div>-->

    </div>
   </fieldset>
  </form>
  </div>
 </div>
 </body>

</html>