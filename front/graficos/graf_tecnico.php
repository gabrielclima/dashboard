<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

Session::checkLoginUser();
Session::checkRight("profile", "r");

global $DB;
   
    switch (date("m")) {
    case "01": $mes = __('January','dashboard'); break;
    case "02": $mes = __('February','dashboard'); break;
    case "03": $mes = __('March','dashboard'); break;
    case "04": $mes = __('April','dashboard'); break;
    case "05": $mes = __('May','dashboard'); break;
    case "06": $mes = __('June','dashboard'); break;
    case "07": $mes = __('July','dashboard'); break;
    case "08": $mes = __('August','dashboard'); break;
    case "09": $mes = __('September','dashboard'); break;
    case "10": $mes = __('October','dashboard'); break;
    case "11": $mes = __('November','dashboard'); break;
    case "12": $mes = __('December','dashboard'); break;
    }

?>

<html> 
<head>
<title>GLPI - <?php echo __('Charts','dashboard'). " " . __('by Technician','dashboard'); ?></title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<!--  <meta http-equiv="refresh" content= "120"/> -->

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />  
<link href="../inc/chosen/chosen.css" rel="stylesheet" type="text/css">
    
<script language="javascript" src="../js/jquery.min.js"></script>  
<script src="../inc/chosen/chosen.jquery.js" type="text/javascript" language="javascript"></script>

<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>
<script src="../js/modules/no-data-to-display.js"></script>
<script src="../js/themes/grid-light.js"></script>
<script src="../js/bootstrap-datepicker.js"></script>
    
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../less/datepicker.less" rel="stylesheet" type="text/css">

</head>
<body>

<?php

global $DB;

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];
	
	$data_fin = $_POST['date2'];
}

else {
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");
} 

$ano = date("Y");
$month = date("Y-m");
$datahoje = date("Y-m-d");

//seleciona técnico

$sql_tec = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname
FROM `glpi_users` , glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 2
ORDER BY `glpi_users`.`firstname` ASC
";

$result_tec = $DB->query($sql_tec);
$tec = $DB->fetch_assoc($result_tec);


// lista de tecnicos

function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select class="chosen-select" tabindex="-1" style="width: 300px; height: 27px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}



$res_tec = $DB->query($sql_tec);
$arr_tec = array();
$arr_tec[0] = "-- ". __('Select technician','dashboard') . " --" ;

$DB->data_seek($result_tec, 0) ;

while ($row_result = $DB->fetch_assoc($result_tec))		
	{ 
	$v_row_result = $row_result['id'];
	$arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname'] ;			
	} 

$name = 'sel_tec';
$options = $arr_tec;
$selected = 0;

?>

<div id='content' >
<div id='container-fluid' style="margin: 0px 8% 0px 8%;"> 

<div id="pad-wrapper" >

<div id="charts" class="row-fluid chart"> 
<div id="head" class="row-fluid">

	<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
	
<div id="titulo_graf" >
	
   <?php echo __('Tickets','dashboard') ." ". __('by Technician','dashboard');//." - ". $mes ." ".$ano.":" ; ?> 
	<span style="color:#8b1a1a; font-size:35pt; font-weight:bold;"> <?php //echo "&nbsp; ".$total_mes['total'] ; ?> </span> </div>

<?php

// lista de técnicos

$res_tec = $DB->query($sql_tec);
$arr_tec = array();
$arr_tec[0] = "-- ". __('Select technician','dashboard') . " --" ;

$DB->data_seek($result_tec, 0) ;

while ($row_result = $DB->fetch_assoc($result_tec))		
	{ 
	$v_row_result = $row_result['id'];
	$arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname'] ;			
	} 
	
$name = 'sel_tec';
$options = $arr_tec;
$selected = 0;
?>


<div id="datas-tec" class="span12 row-fluid" > 
<form id="form1" name="form1" class="form2" method="post" action="?date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>&con=1" onsubmit="datai();dataf();"> 
<table border="0" cellspacing="0" cellpadding="1" bgcolor="#efefef">

<tr>
<td>
<?php 
echo'
<table style="margin-top:6px;" ><tr><td>
    <div class="input-append date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
    <input class="span8" size="16" type="text" name="date1" value="'.$data_ini.'">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
</td><td>
   <div class="input-append date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
    <input class="span8" size="16" type="text" name="date2" value="'.$data_fin.'">
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

<td style="margin-top:2px;">
<?php

echo dropdown( $name, $options, $selected );

//Dropdown::showFromArray( $name, $options, $selected );

?>
</td>
</tr>
<tr><td height="15px"></td></tr>
<tr>
<td colspan="2" align="center" style="">
<button class="btn btn-primary btn-small" type="submit" name="submit" value="Atualizar" ><i class="icon-white icon-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?></button>
<button class="btn btn-primary btn-small" type="button" name="Limpar" value="Limpar" onclick="location.href='graf_tecnico.php'" > <i class="icon-white icon-trash"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button></td>
</td>
</tr>
	
	</table>
<?php Html::closeForm(); ?>
<!-- </form> -->
</div>

</div>


<!-- DIV's -->

<script type="text/javascript" >
$('.chosen-select').chosen();
</script>

<?php

//echo $_POST['sel_tec'];

$con = $_GET['con'];

if($con == "1") {

if(!isset($_POST['date1']))
{	
	$data_ini2 = $_GET['date1'];	
	$data_fin2 = $_GET['date2'];
}

else {	
	$data_ini2 = $_POST['date1'];	
	$data_fin2 = $_POST['date2'];	
}  

if(!isset($_POST["sel_tec"])) {

$id_tec = $_GET["tec"];	
}

else {
$id_tec = $_POST["sel_tec"];
}

if($id_tec == 0) {
echo '<script language="javascript"> alert(" ' . __('Select technician','dashboard') . ' "); </script>';
echo '<script language="javascript"> location.href="graf_tecnico.php"; </script>';
}


// nome do tecnico

$sql_nm = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname
FROM `glpi_users` , glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_users.id = ".$id_tec."
AND glpi_tickets_users.type = 2
ORDER BY `glpi_users`.`firstname` ASC
";

$result_nm = $DB->query($sql_nm);
$tec_name = $DB->fetch_assoc($result_nm);


if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}


$query_total = "SELECT count(*) AS total
FROM glpi_tickets_users, glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.tickets_id = glpi_tickets.id
";

$result_total = $DB->query($query_total);
$total_cham = $DB->fetch_assoc($result_total);

echo '<div id="entidade" class="span12 row-fluid" style="margin-top:25px;">';

echo $tec_name['name']." ".$tec_name['sname']." - <span style = 'color:#000;'> "
.$total_cham['total']." ".__('Tickets','dashboard')."</span>
</div>";

 ?>

<div id="graf_linhas" class="span12" style="height: 450px; margin-top: 25px; margin-left: -5px;">
<?php  include ("./inc/graflinhas_tec.inc.php"); ?>
</div>


<div id="graf2" class="span6" >
<?php  include ("./inc/grafpie_stat_tec.inc.php");  ?>
</div>


<div id="graf4" class="span6" >
<?php include ("./inc/grafcat_tec.inc.php"); ?>
</div>

<div id="graf_sat" class="span12" style="height: 450px; margin-top:30px; margin-bottom:30px; margin-left: -5px;">
<?php include ("./inc/grafcol_sat_tec.inc.php"); ?>
</div>

<?php 

}
?>

</div>

</div>

</div>
</div>
</div>
</body>
</html>