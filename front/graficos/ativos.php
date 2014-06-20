<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

Session::checkLoginUser();
Session::checkRight("profile", "r");

$mydate = isset($_POST["date1"]) ? $_POST["date1"] : "";

?>

<html> 
<head>
<title>GLPI - <?php echo __('Tickets') .'  '. __('by Assets','dashboard').'s' ?></title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />  
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
<link href="../inc/calendar/calendar.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../js/jquery.min.js"></script> 
<script language="javascript" src="../inc/calendar/calendar.js"></script>
<script src="../js/highcharts.js"></script>
<script src="../js/themes/grid-light.js"></script>
<script src="../js/modules/exporting.js"></script>

<link href="../inc/chosen/chosen.css" rel="stylesheet" type="text/css">
<script src="../inc/chosen/chosen.jquery.js" type="text/javascript" language="javascript"></script>

<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../less/datepicker.less" rel="stylesheet" type="text/css">

</head>

<body>

<?php

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");
}    

$month = date("Y-m");
$datahoje = date("Y-m-d");  

	  
?>
<div id='content' >
<div id='container-fluid' style="margin: 0px 8% 0px 8%;"> 

 <div id="pad-wrapper" >

<div id="charts" class="row-fluid chart"> 
<div id="head" class="row-fluid">

	<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo" style="margin-bottom:45px;"> <?php echo __('Tickets') .'  '. __('by Assets','dashboard');  ?>  

<div id="datas" class="span12 row-fluid" > 
<form id="form1" name="form1" class="form1" method="post" action="?con=1&date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>" style="width:360px;"> 

<table border="0" cellspacing="0" cellpadding="2" width="430px">
<tr>
<td>
<?php
    
echo'
<table style="margin-left: 16px; margin-top:6px; align:right;" border=0><tr><td>
    <div class="input-append date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
    <input class="span8" size="14" type="text" name="date1" value="'.$data_ini.'">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
	 </td>
	 <td>
    <div class="input-append date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
    <input class="span8" size="14" type="text" name="date2" value="'.$data_fin.'">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
    </tr></td>
</table>
    ';
?>

<script language="Javascript">
$('#dp1').datepicker('update');
$('#dp2').datepicker('update');
</script>

</td>
</tr>

<tr>
<td style="margin-top:2px; width:100px;">

<?php echo __('Type').":  

<select id='sel_item' name='sel_item' class='chosen-select' tabindex='-1' style='width: 300px; height: 27px;' autofocus onChange='javascript: document.form1.submit.focus()' >
	<option value='0'> -- ".__('Select a asset','dashboard')." -- </option>
	<option value='1'>".__('Computer')."</option>
	<option value='2'>".__('Monitor')."</option>
	<option value='3'>".__('Software')."</option>
	<option value='4'>".__('Network')."</option>
	<option value='5'>".__('Device')."</option>
	<option value='6'>".__('Printer')."</option>
	<option value='7'>".__('Phone')."</option>
</select> ";	

?>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr align="center">
<td>
	<button class="btn btn-primary btn-small" type="submit" name="submit" value="Atualizar" ><i class="icon-white icon-refresh"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
	<button class="btn btn-primary btn-small" type="button" name="Limpar" value="Limpar" onclick="location.href='ativos.php'" ><i class="icon-white icon-trash"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
</td>
</tr>

</table>
<p></p>
<?php Html::closeForm(); ?>

</div>

</div>
</div>

<div id="graf1" class="row-fluid">
<?php 

if(isset($_REQUEST['con']) && $_REQUEST['con'] == 1 ) {
	
	if(isset($_REQUEST['sel_item']) && $_REQUEST['sel_item'] == '0' ) {
		//$type = $_REQUEST['itemtype'];
		echo '<script language="javascript"> alert(" ' . __('Select a asset','dashboard') . ' "); </script>';
		//echo '<script language="javascript"> location.href="graf_entidade.php"; </script>';		
		 
		}
	
	else {	

		$itemtype = $_REQUEST['sel_item'];

		switch ($itemtype) {
	    case "1": $type = 'computer'; break;
	    case "2": $type = 'monitor'; break;
	    case "3": $type = 'software'; break;
	    case "4": $type = 'networkequipment'; break;
	    case "5": $type = 'peripheral'; break;
	    case "6": $type = 'printer'; break;
	    case "7": $type = 'phone'; break;
	} 
}

include ("./inc/grafbar_ativo_mes.inc.php");

}
?>
</div>

</div>

<script type="text/javascript" >
$('.chosen-select').chosen({disable_search_threshold: 10});
</script>


</div>
</div>
</div>
</body> </html>
