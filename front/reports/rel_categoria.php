<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", "r");

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {	
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");	
	}  

if(!isset($_POST["sel_cat"])) {

$id_cat = $_GET["cat"];	
}

else {
$id_cat = $_POST["sel_cat"];
}

function conv_data($data) {
	if($data != "") {
		$source = $data;
		$date = new DateTime($source);	
		return $date->format('d-m-Y');}
	else {
		return "";	
	}
}

function conv_data_hora($data) {
	if($data != "") {
		$source = $data;
		$date = new DateTime($source);	
		return $date->format('d-m-Y H:i:s');}
	else {
		return "";	
	}
}

function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select id="sel1" style="width: 300px; height: 27px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

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

?>

<html> 
<head>
<title> GLPI - <?php echo __('Tickets', 'dashboard') .'  '. __('by Category', 'dashboard') ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script language="javascript" src="../js/jquery.min.js"></script>  
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>
<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../less/datepicker.less" rel="stylesheet" type="text/css">

<script src="../js/media/js/jquery.dataTables.min.js"></script>
<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
<script src="../js/media/js/dataTables.bootstrap.js"></script> 
<link href="../js/extensions/TableTools/css/dataTables.tableTools.css" type="text/css" rel="stylesheet" />
<script src="../js/extensions/TableTools/js/dataTables.tableTools.js"></script>

<style type="text/css" title="currentStyle">		
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
</style>
   
</head>

<body style="background-color: #e5e5e5; margin-left:0%;">

<div id='content' >
<div id='container-fluid' style="margin: 0px 8% 0px 8%;"> 

<div id="charts" class="row-fluid chart"> 
<div id="pad-wrapper" >
<div id="head" class="row-fluid">

<style type="text/css">
a:link, a:visited, a:active {
	text-decoration: none
	}
a:hover {
	color: #000099;
	}
</style>

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_graf"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Category', 'dashboard') ?> </div>
	
		<div id="datas-tec" class="span12 row-fluid" >
 
	<form id="form1" name="form1" class="form_rel" method="post" action="rel_categoria.php?con=1" onsubmit="datai();dataf();"> 
	<table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" >
	<tr>
<td style="width: 310px;">
<?php
$url = $_SERVER['REQUEST_URI']; 
$arr_url = explode("?", $url);
$url2 = $arr_url[0];
    
echo'
			<table>
				<tr>
					<td>
					   <div class="input-group date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
					    	<input class="col-md-9 form-control" size="13" type="text" name="date1" value="'.$data_ini.'" >		    	
					    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
				    	</div>
					</td>
					<td>&nbsp;</td>
					<td>
				   	<div class="input-group date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
					    	<input class="col-md-9 form-control" size="13" type="text" name="date2" value="'.$data_fin.'" >		    	
					    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
				    	</div>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table> ';
?>

<script language="Javascript">

$('#dp1').datepicker('update');
$('#dp2').datepicker('update');

</script>
</td>

<td style="margin-top:2px;">
<?php

// lista de categorias
$sql_cat = "
SELECT id, completename AS name
FROM `glpi_itilcategories`
ORDER BY `name` ASC
";

$result_cat = $DB->query($sql_cat);

$arr_cat = array();
$arr_cat[0] = "-- ". __('Select a category', 'dashboard') . " --" ;

while ($row_result = $DB->fetch_assoc($result_cat))		
	{ 
	$v_row_result = $row_result['id'];
	$arr_cat[$v_row_result] = $row_result['name'] ;			
	} 
	
$name = 'sel_cat';
$options = $arr_cat;
$selected = "0";

echo dropdown( $name, $options, $selected );

?>
</td>
</tr>
<tr><td height="15px"></td></tr>
<tr>
	<td colspan="2" align="center">
		<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
		<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
	</td>
</tr>
	
	</table>
<?php Html::closeForm(); ?>
<!-- </form> -->
		</div>
	</div>

<?php 

//categorias

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

if(!isset($_POST["sel_cat"])) {
	$id_cat = $_GET["cat"];	
}

else {
	$id_cat = $_POST["sel_cat"];
}

if($id_cat == "" || $id_cat == 0) {
	echo '<script language="javascript"> alert(" ' . __('Select a category', 'dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="rel_categoria.php"; </script>';
}

if($data_ini2 == $data_fin2) {
	$datas2 = "LIKE '".$data_ini2."%'";	
}	

else {
	$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";	
}

//status
$status = "";
$status_open = "('2','1','3','4')";
$status_close = "('5','6')";	
$status_all = "('2','1','3','4','5','6')";


if(isset($_GET['stat'])) {
	
	if($_GET['stat'] == "open") {		
		$status = $status_open;
	}
	elseif($_GET['stat'] == "close") {
		$status = $status_close;
	}
	else {
	$status = $status_all;	
	}
}

else {
	$status = $status_all;
	}

// Chamados
$sql_cham = 
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate, glpi_tickets.status
FROM glpi_tickets
WHERE glpi_tickets.itilcategories_id = ".$id_cat."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
ORDER BY id DESC ";

$result_cham = $DB->query($sql_cham);


$consulta1 = 
"SELECT glpi_tickets.id AS total
FROM glpi_tickets
WHERE glpi_tickets.itilcategories_id = ".$id_cat."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
";

$result_cons1 = $DB->query($consulta1);

$conta_cons = $DB->numrows($result_cons1);

$consulta = $conta_cons;


if($consulta > 0) {

if(!isset($_GET['pagina'])) {
	$primeiro_registro = 0;
	$pagina = 1;
}
else {
	$pagina = $_GET['pagina'];
	$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
}

//montar barra
$sql_ab = "SELECT glpi_tickets.id AS total
FROM glpi_tickets
WHERE glpi_tickets.itilcategories_id = ".$id_cat."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status_open ;

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->numrows($result_ab);

$abertos = $data_ab; 

//barra de porcentagem
if($conta_cons > 0) {

//barra de porcentagem
if($status == $status_close ) {
    $barra = 100;
    $cor = "progress-bar-success";
}

else {

	//porcentagem
	$perc = round(($abertos*100)/$conta_cons,1);
	$barra = 100 - $perc;
	
	// cor barra
	if($barra == 100) { $cor = "progress-bar-success"; }
	if($barra >= 80 and $barra < 100) { $cor = " "; }
	if($barra > 51 and $barra < 80) { $cor = "progress-bar-warning"; }
	if($barra > 0 and $barra <= 50) { $cor = "progress-bar-danger"; }

	}
}
else { $barra = 0;}

// nome da categoria
$sql_nm = "
SELECT id , completename AS name
FROM `glpi_itilcategories`
WHERE id = ".$id_cat."";

$result_nm = $DB->query($sql_nm);
$ent_name = $DB->fetch_assoc($result_nm);


//listar chamados

echo "
<div class='well info_box row-fluid span12' style='margin-top:25px; margin-left: -1px;'>

<table class='row-fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<tr>
		<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Category').": </span>".$ent_name['name']." </td>
	</tr>
	<tr>
		<td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
		<td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)." </td>

		<td style='vertical-align:middle; width: 190px; '>
		<div class='progress' style='margin-top: 19px;' >
			<div class='progress-bar ". $cor ." progress-bar-striped active' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
    			".$barra." % ".__('Closed', 'dashboard') ."	
    		</div>		
		</div>		   
		</td>
	</tr> 
</table>

<table align='right' style='margin-bottom:10px; border=1'>
	<tr>
		<td colspan='3'>
		<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_categoria.php?con=1&stat=open&cat=".$id_cat."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard')." </button>
		<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_categoria.php?con=1&stat=close&cat=".$id_cat."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>
		<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_categoria.php?con=1&stat=all&cat=".$id_cat."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
		</td> 
	</tr>
</table>

<table>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
</table>

<table id='cat' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
	<thead>
		<tr>
			<td style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Tickets', 'dashboard')." </td>
			<td> </td>
			<td style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Title', 'dashboard')." </td>
			<td style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Requester', 'dashboard')." </td>
			<td style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Technician', 'dashboard')." </td>
			<td style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Opened', 'dashboard')."</td>
			<td style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Closed', 'dashboard')." </td>
		</tr>
	</thead>
<tbody>
";

while($row = $DB->fetch_assoc($result_cham)){
	
	$status1 = $row['status']; 

	if($status1 == "1" ) { $status1 = "new";} 
	if($status1 == "2" ) { $status1 = "assign";} 
	if($status1 == "3" ) { $status1 = "plan";} 
	if($status1 == "4" ) { $status1 = "waiting";} 
	if($status1 == "5" ) { $status1 = "solved";}  	            
	if($status1 == "6" ) { $status1 = "closed";}	

//requerente	

	$sql_user = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users` , glpi_tickets, glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.id = ". $row['id'] ."
AND glpi_tickets_users.`users_id` = glpi_users.id
AND glpi_tickets_users.type = 1
";
$result_user = $DB->query($sql_user);
		
	$row_user = $DB->fetch_assoc($result_user);
			
//tecnico	

	$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users` , glpi_tickets, glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.id = ". $row['id'] ."
AND glpi_tickets_users.`users_id` = glpi_users.id
AND glpi_tickets_users.type = 2
";
$result_tec = $DB->query($sql_tec);	

	$row_tec = $DB->fetch_assoc($result_tec);
	
echo "	

<tr>
<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
<td style='vertical-align:middle;' align='center'><img src=".$CFG_GLPI['root_doc']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> </td>
<td> ". substr($row['descr'],0,55) ." </td>
<td> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
<td> ". $row_tec['name'] ." ".$row_tec['sname'] ." </td>
<td> ". conv_data($row['date']) ." </td>
<td> ". conv_data($row['solvedate']) ." </td>
<!-- <td> ". Ticket::getStatus($row['status']) ." </td> -->
</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#cat')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered');

$(document).ready(function() {
    oTable = $('#cat').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "aaSorting": [[0,'desc']], 
        "iDisplayLength": 25,
    	  "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]], 

        "sDom": 'T<"clear">lfrtip',
         "oTableTools": {
         "aButtons": [
             {
                 "sExtends": "copy",
                 "sButtonText": "<?php echo __('Copy'); ?>"
             },
             {
                 "sExtends": "print",
                 "sButtonText": "<?php echo __('Print','dashboard'); ?>",
					  "sMessage": "<div class='info_box row-fluid span12' style='margin-top:20px; margin-bottom:12px; margin-left: -1px;'><table class='row-fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><tr><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Category'); ?> : </span><?php echo $ent_name['name']; ?> </td></tr><tr><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td></tr></table></div>"
             },
             {
                 "sExtends":    "collection",
                 "sButtonText": "<?php echo __('Export'); ?>",
                 "aButtons":    [ "csv", "xls",
                  {
                 "sExtends": "pdf",
                 "sPdfOrientation": "landscape",
                 "sPdfMessage": ""
                  } ]
             }
         ]
        }
		  
    });    
} );
		
</script> 

<?php

echo '</div><br>';
}

else {
	
echo "
<div class='well info_box row-fluid span12' style='margin-top:30px; margin-left: -3px;'>
<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
<tr></tr>
</table></div>";	

}	

}
?>

<script type="text/javascript" >
$(document).ready(function() { $("#sel1").select2(); });
</script>
</div>
</div>

</div>
</div>

</body> 
</html>

