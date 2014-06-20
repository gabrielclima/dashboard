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

if(!isset($_POST["sel_ent"])) {

$id_ent = $_GET["ent"];	
}

else {
$id_ent = $_POST["sel_ent"];
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
    $dropdown = '<select class="chosen-select" tabindex="-1" style="width: 300px; height: 27px;" autofocus name="'.$name.'" id="'.$name.'">'."\n";

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
<title> GLPI - <?php echo __('Tickets', 'dashboard') ?> </title>
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
  <link href="../inc/chosen/chosen.css" rel="stylesheet" type="text/css">
  <script src="../inc/chosen/chosen.jquery.js" type="text/javascript" language="javascript"></script>

  <script src="../js/bootstrap-datepicker.js"></script>
   <link href="../css/datepicker.css" rel="stylesheet" type="text/css">
   <link href="../less/datepicker.less" rel="stylesheet" type="text/css">
   
   <!-- <script src="../js/sorttable.j"></script> -->
	<script src="../js/media/js/jquery.dataTables.min.js"></script>
	<script src="../js/extensions/TableTools/js/dataTables.tableTools.js"></script>
	<link href="../js/extensions/TableTools/css/dataTables.tableTools.css" type="text/css" rel="stylesheet" />
	
	<style type="text/css" title="currentStyle">	
		@import "../js/media/css/jquery.dataTables_themeroller.css";
		@import "../js/smoothness/jquery-ui-1.9.2.custom.css";
		
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
	</style>
   
</head>

<body style="background-color: #e5e5e5;">

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

	<div id="titulo_graf"> <?php echo __('Tickets', 'dashboard') ?> </div>
	
		<div id="datas-tec" class="span12 row-fluid" >
 
		<form id="form1" name="form1" class="form_rel" method="post" action="rel_tickets.php?con=1" onsubmit="datai();dataf();" style="margin-left: 25%;"> 
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" >
		<tr>
			
		<td style="margin-top:2px; width:110px;"><?php echo __('Period'); ?>: </td>	
		<td style="width: 200px;">
		<?php
		$url = $_SERVER['REQUEST_URI']; 
		$arr_url = explode("?", $url);
		$url2 = $arr_url[0];
		    
		echo'
		<table style="margin-top:6px;" >
		<tr><td>
		    <div class="input-append date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
		    <input class="span9" size="16" type="text" name="date1" value="'.$data_ini.'">
		    <span class="add-on"><i class="icon-th"></i></span>
		    </div>
		</td><td>
		   <div class="input-append date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
		    <input class="span9" size="16" type="text" name="date2" value="'.$data_fin.'">
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
		<td style="margin-top:2px; width:100px;"><?php echo __('Entity'); ?>: </td>
		
		<td style="margin-top:2px;">
		<?php
		
		// lista de entidades
		
		$sql_ent = "
		SELECT id , name
		FROM `glpi_entities`
		ORDER BY `name` ASC
		";
		
		$result_ent = $DB->query($sql_ent);
		//$ent = $DB->fetch_assoc($result_ent);
		
		//$res_ent = $DB->query($sql_ent);
		$arr_ent = array();
		$arr_ent[0] = "" ;
		
		//$DB->data_seek($result_ent, 0) ;
		
		while ($row_ent = $DB->fetch_assoc($result_ent))		
			{ 
			$v_row_ent = $row_ent['id'];
			$arr_ent[$v_row_ent] = $row_ent['name'] ;			
			} 
			
		$name = 'sel_ent';
		$options = $arr_ent;
		$selected = "0";
		
		echo dropdown( $name, $options, $selected );
		
		?>
		</td>
		</tr>
		
		
		<tr>
		<td style="margin-top:2px; width:100px;"><?php echo __('Status'); ?>:  </td>
		
		<td style="margin-top:2px;">
		<?php
		// lista de status
		
		$sql_sta = "
		SELECT DISTINCT status
		FROM glpi_tickets
		ORDER BY status ASC
		";
		
		$result_sta = $DB->query($sql_sta);
		
		$arr_sta = array();
		$arr_sta[0] = "-----";
		
		while ($row_sta = $DB->fetch_assoc($result_sta))		
			{ 
			$v_row_sta = $row_sta['status'];
			$arr_sta[$v_row_sta] = Ticket::getStatus($row_sta['status']) ;			
			} 
			
		$name = 'sel_sta';
		$options = $arr_sta;
		$selected = "0";
		
		echo dropdown( $name, $options, $selected );
		?>
		</td>
		</tr>
		
		
		<tr>
		<td style="margin-top:2px; width:165px;"><?php echo __('Request source'); ?>: </td>
		
		<td style="margin-top:2px;">
		<?php
		// lista de origem
		
		$sql_req = "
		SELECT id, name
		FROM glpi_requesttypes
		ORDER BY id ASC
		";
		
		$result_req = $DB->query($sql_req);
		
		$arr_req = array();
		$arr_req[0] = "-----";
		
		while ($row_req = $DB->fetch_assoc($result_req))		
			{ 
			$v_row_req = $row_req['id'];
			$arr_req[$v_row_req] = $row_req['name'] ;			
			} 
			
		$name = 'sel_req';
		$options = $arr_req;
		$selected = "0";
		
		echo dropdown( $name, $options, $selected );
		?>
		</td>
		</tr>
		
		
		<tr>
		<td style="margin-top:2px; width:100px;"><?php echo __('Priority'); ?>:  </td>
		
		<td style="margin-top:2px;">
		<?php
		// lista de tipos
		
		$arr_pri = array();
		$arr_pri[0] = "-----" ;
		$arr_pri[1] = _x('priority', 'Very low');
		$arr_pri[2] = _x('priority', 'Low');
		$arr_pri[3] = _x('priority', 'Medium');
		$arr_pri[4] = _x('priority', 'High');
		$arr_pri[5] = _x('priority', 'Very high');
		$arr_pri[6] = _x('priority', 'Major');
		
		
		$name = 'sel_pri';
		$options = $arr_pri;
		$selected = "0";
		
		echo dropdown( $name, $options, $selected );
		?>
		</td>
		</tr>
		
		
		<tr>
		<td style="margin-top:2px; width:100px;"><?php echo __('Category'); ?>:  </td>
		
		<td style="margin-top:2px;">
		<?php
		// lista de categorias
		
		$sql_cat = "
		SELECT id, name
		FROM glpi_itilcategories
		ORDER BY name ASC ";
		
		$result_cat = $DB->query($sql_cat);
		
		$arr_cat = array();
		$arr_cat[0] = "-----" ;
		
		while ($row_cat = $DB->fetch_assoc($result_cat))		
			{ 
			$v_row_cat = $row_cat['id'];
			$arr_cat[$v_row_cat] = $row_cat['name'] ;			
			} 
			
		$name = 'sel_cat';
		$options = $arr_cat;
		$selected = "0";
		
		echo dropdown( $name, $options, $selected );
		?>
		</td>
		</tr>
		
		
		<tr>
		<td style="margin-top:2px; width:100px;"><?php echo __('Type'); ?>:  </td>
		
		<td style="margin-top:2px;">
		<?php
		// lista de tipos
		
		$arr_tip = array();
		$arr_tip[0] = "-----" ;
		$arr_tip[1] = __('Incident') ;
		$arr_tip[2] = __('Request');
		
		$name = 'sel_tip';
		$options = $arr_tip;
		$selected = "0";
		
		echo dropdown( $name, $options, $selected );
		?>
		</td>
		</tr>
		
		<tr><td height="15px"></td></tr>
		<tr>
		<td colspan="2" align="center">
		 
		<button class="btn btn-primary btn-small" type="submit" name="submit" value="Atualizar" ><i class="icon-white icon-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
		<button class="btn btn-primary btn-small" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="icon-white icon-trash"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
		</td>
		</tr>
			
			</table>
		<?php Html::closeForm(); ?>

		</div>
	</div>	

<?php 

//entidades

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

if(!isset($_REQUEST["sel_ent"])) { $id_ent = 0; }
else { $id_ent = $_REQUEST["sel_ent"]; }

if(isset($_REQUEST["sel_sta"]) && $_REQUEST["sel_sta"] != '0') { $id_sta = $_REQUEST["sel_sta"]; }
else { $id_sta = ''; }

if(isset($_REQUEST["sel_req"]) && $_REQUEST["sel_req"] != '0') { $id_req = $_REQUEST["sel_req"]; }
else { $id_req = ''; }

if(isset($_REQUEST["sel_pri"]) && $_REQUEST["sel_pri"] != '0') { $id_pri = $_REQUEST["sel_pri"]; }
else { $id_pri = ''; }

if(isset($_REQUEST["sel_cat"]) && $_REQUEST["sel_cat"] != '0') { $id_cat = $_REQUEST["sel_cat"]; }
else { $id_cat = ''; }

if(isset($_REQUEST["sel_tip"]) && $_REQUEST["sel_tip"] != '0') { $id_tip = $_REQUEST["sel_tip"]; }
else { $id_tip = ''; }

$arr_param = array($id_ent, $id_sta, $id_req, $id_pri, $id_cat, $id_tip);

if($data_ini2 == $data_fin2) {
$datas2 = "LIKE '".$data_ini2."%'";	
}	

else {
$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";	
}


//paginacao 

if(isset($_GET['npage'])) { 
	$num_por_pagina = $_GET['npage']; }

else { 
	$num_por_pagina = 20; }


if(!isset($_GET['pagina'])) {
	$primeiro_registro = 0;
	$pagina = 1;

}
else {
	$pagina = $_GET['pagina'];
	$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
}


// Chamados

$sql_cham = 
"SELECT id, entities_id, name, date, closedate, solvedate, status, users_id_recipient, requesttypes_id, itemtype, priority, itilcategories_id, type   
FROM glpi_tickets
WHERE glpi_tickets.entities_id = ".$id_ent."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status LIKE '%".$id_sta."'
AND glpi_tickets.requesttypes_id LIKE '%".$id_req."'
AND glpi_tickets.priority LIKE '%".$id_pri."'
AND glpi_tickets.itilcategories_id LIKE '%".$id_cat."'
AND glpi_tickets.type LIKE '%".$id_tip."'
ORDER BY id DESC ";
//LIMIT ". $primeiro_registro .", ". $num_por_pagina ."

$result_cham = $DB->query($sql_cham);

//fim paginacao 1

$consulta1 = 
"SELECT glpi_tickets.id AS total
FROM glpi_tickets
WHERE glpi_tickets.entities_id = ".$id_ent."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status LIKE '%".$id_sta."'
AND glpi_tickets.requesttypes_id LIKE '%".$id_req."'
AND glpi_tickets.priority LIKE '%".$id_pri."'
AND glpi_tickets.itilcategories_id LIKE '%".$id_cat."'
AND glpi_tickets.type LIKE '%".$id_tip."'
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

// nome da entidade

$sql_nm = "
SELECT name
FROM `glpi_entities`
WHERE id = ".$id_ent."";

$result_nm = $DB->query($sql_nm);
$ent_name = $DB->fetch_assoc($result_nm);


//listar chamados

echo "

<div class='well info_box row-fluid span12' style='margin-top:25px; margin-left: -1px;'>

<table class='row-fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Entity', 'dashboard').": </span>".$ent_name['name']." </td>
<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>
".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)." 
</td>

</table>

<table id='ticket' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
<thead>
<tr>
<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Tickets', 'dashboard')." </th>
<th> </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Type')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Request')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Priority')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Category')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Title', 'dashboard')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Requester', 'dashboard')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Technician', 'dashboard')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Opened', 'dashboard')."</th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Closed', 'dashboard')." </th>
</tr>
</thead>
<tbody>";


while($row = $DB->fetch_assoc($result_cham)){
	
	$status1 = $row['status']; 

	if($status1 == "1" ) { $status1 = "new";} 
	if($status1 == "2" ) { $status1 = "assign";} 
	if($status1 == "3" ) { $status1 = "plan";} 
	if($status1 == "4" ) { $status1 = "waiting";} 
	if($status1 == "5" ) { $status1 = "solved";}  	            
	if($status1 == "6" ) { $status1 = "closed";}	
	
	//type
	if($row['type'] == 1) { $type = __('Incident'); }
	else { $type = __('Request'); }

//priority
$prio = $row['priority'];

if($prio == "1" ) { $pri = _x('priority', 'Very low');} 
if($prio == "2" ) { $pri = _x('priority', 'Low');} 
if($prio == "3" ) { $pri = _x('priority', 'Medium');} 
if($prio == "4" ) { $pri = _x('priority', 'High');} 
if($prio == "5" ) { $pri = _x('priority', 'Very high');} 
if($prio == "6" ) { $pri = _x('priority', 'Major');} 

//requerente	

$sql_user = "SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_users.firstname AS name, glpi_users.realname AS sname
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
AND glpi_tickets_users.type = 2 ";

$result_tec = $DB->query($sql_tec);	

	$row_tec = $DB->fetch_assoc($result_tec);
	
	
//origem	

$sql_req = "SELECT glpi_tickets.id AS id, glpi_requesttypes.name AS name
FROM `glpi_tickets` , glpi_requesttypes
WHERE glpi_tickets.requesttypes_id = glpi_requesttypes.`id`
AND glpi_tickets.id = ". $row['id'] ." ";

$result_req = $DB->query($sql_req);	

	$row_req = $DB->fetch_assoc($result_req);
	
	
//categoria	

$sql_cat = "SELECT glpi_tickets.id AS id, glpi_itilcategories.name AS name
FROM `glpi_tickets` , glpi_itilcategories
WHERE glpi_tickets.itilcategories_id = glpi_itilcategories.`id`
AND glpi_tickets.id = ". $row['id'] ." ";

$result_cat = $DB->query($sql_cat);	

	$row_cat = $DB->fetch_assoc($result_cat);
	
echo "	
<tr>
<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
<td style='vertical-align:middle;'><img src=".$CFG_GLPI['root_doc']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> </td>

<td style='vertical-align:middle;'> ". $type ." </td>
<td style='vertical-align:middle;'> ". $row_req['name'] ." </td>
<td style='vertical-align:middle;'> ". $pri ." </td>
<td style='vertical-align:middle;'> ". $row_cat['name'] ." </td>

<td> ". substr($row_user['descr'],0,55) ." </td>
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
$(document).ready(function() {
    oTable = $('#ticket').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "aaSorting": [[0,'desc']], 
        "iDisplayLength": 25,
    	  "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]], 

        "sDom": 'T<"clear">lfrtip',
         "oTableTools": {
	       "aButtons": [
	       "copy",
	       "print",
	       {
	           "sExtends":    "collection",
	           "sButtonText": "Save",
	           "aButtons":    [ "csv", "xls",
	            {
	           "sExtends": "pdf",
	           "sPdfOrientation": "landscape",
	           "sPdfMessage": ""
	            } ]
	       } ]
        }
		  
    });    
} );		
</script>  

<?php
// paginacao 2

echo '<div id=pag align=center class="paginas navigation row-fluid">';

$total_paginas = $conta_cons/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;
// se página maior que 1 (um), então temos link para a página anterior

if ($pagina > 1) {
    $prev_link = "<a href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&sel_ent=".$id_ent."&sel_sta=".$id_sta."&sel_req=".$id_req."&sel_pri=".$id_pri."&sel_cat=".$id_cat."&sel_tip=".$id_tip."&pagina=".$prev."&npage=".$num_por_pagina.">". __('Previous', 'dashboard') ."</a>";
  }
  else { // senão não há link para a página anterior  
    $prev_link = "<a href='#'>".__('Previous', 'dashboard')."</a>";
  }
// se número total de páginas for maior que a página corrente, 
// então temos link para a próxima página  

if ($total_paginas > $pagina) {
    $next_link = "<a href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&sel_ent=".$id_ent."&sel_sta=".$id_sta."&sel_req=".$id_req."&sel_pri=".$id_pri."&sel_cat=".$id_cat."&sel_tip=".$id_tip."&pagina=".$next."&npage=".$num_por_pagina.">".__('Next', 'dashboard')."</a>";
  } else { 
// senão não há link para a próxima página
    $next_link = "<a href='#'> " .__('Next', 'dashboard')."</a>";
  }
 
$total_paginas = ceil($total_paginas);
  $painel = "";
  for ($x=1; $x<=$total_paginas; $x++) {
    if ($x==$pagina) { 
// se estivermos na página corrente, não exibir o link para visualização desta página 
      //$painel .= "$x";
      
      $painel .= " <a style=color:#000999; href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&sel_ent=".$id_ent."&sel_sta=".$id_sta."&sel_req=".$id_req."&sel_pri=".$id_pri."&sel_cat=".$id_cat."&sel_tip=".$id_tip."&pagina=".$x."&npage=".$num_por_pagina.">$x</a>";
    } else {
      $painel .= " <a href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&sel_ent=".$id_ent."&sel_sta=".$id_sta."&sel_req=".$id_req."&sel_pri=".$id_pri."&sel_cat=".$id_cat."&sel_tip=".$id_tip."&pagina=".$x."&npage=".$num_por_pagina.">$x</a>";
    }
  }
// exibir painel na tela
//echo "$prev_link  $painel  $next_link";
echo '</div><br>';
// fim paginacao 2
}
//}

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


<script type='text/javascript' >
$('.chosen-select').chosen({disable_search_threshold: 10});
</script>	

</div>
</div>

</div>
</div>

</body> 
</html>

