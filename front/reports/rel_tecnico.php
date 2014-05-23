<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", "r");

if(!empty($_POST['submit']))
{
    $data_ini =  $_REQUEST['date1'];
    $data_fin = $_REQUEST['date2'];
}

else {
    $data_ini = date("Y-m-01");
    $data_fin = date("Y-m-d");
    }

if(!isset($_POST["sel_tec"])) {
    $id_tec = $_GET["tec"];
}

else {
    $id_tec = $_POST["sel_tec"];
}


function conv_data($data) {
    if($data != "") {
        $source = $data;
        $date = new DateTime($source);
        return $date->format('Y-m-d');}
    else {
        return "";
    }
}

function conv_data_hora($data) {
    if($data != "") {
        $source = $data;
        $date = new DateTime($source);
        return $date->format('Y-m-d H:i:s');}
    else {
        return "";
    }
}

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



function time_ext($solvedate)
{
// TEST
// 1 Day 6 Hours 50 Minutes 31 Seconds ~ 111031 seconds

$time = $solvedate; // time duration in seconds

 if ($time == 0){
        return '';
    }


$days = floor($time / (60 * 60 * 24));
$time -= $days * (60 * 60 * 24);

$hours = floor($time / (60 * 60));
$time -= $hours * (60 * 60);

$minutes = floor($time / 60);
$time -= $minutes * 60;

$seconds = floor($time);
$time -= $seconds;

$return = "{$days}d {$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s

return $return;

}

?>

<html>
<head>
<title> GLPI - <?php echo __('Tickets','dashboard') .'  '. __('by Technician','dashboard') ?> </title>
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

<!-- gauge -->
<script src="../js/raphael.2.1.0.min.js"></script>
<script src="../js/justgage.1.0.1.min.js"></script>

<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../less/datepicker.less" rel="stylesheet" type="text/css">

<script src="../js/sorttable.js"></script>

</head>

<body style="background-color: #e5e5e5;">

<?php

$sql_tec = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname
FROM `glpi_users` , glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 2
ORDER BY `glpi_users`.`firstname` ASC
";

$result_tec = $DB->query($sql_tec);
$tec = $DB->fetch_assoc($result_tec);

?>
<div id='content' >
<div id='container-fluid' style="margin: 0px 8% 0px 8%;">

<div id="charts" class="row-fluid chart" >
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

    <div id="titulo_graf" > <?php echo __('Tickets','dashboard') .'  '. __('by Technician','dashboard') ?> </div>

        <div id="datas-tec" class="span12 row-fluid" >

    <form id="form1" name="form1" class="form_rel" method="post" action="rel_tecnico.php?con=1">
    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" >
    <tr>
<td style="width: 250px;">
<?php
$url = $_SERVER['REQUEST_URI'];
$arr_url = explode("?", $url);
$url2 = $arr_url[0];

echo'
<table style="margin-top:6px;" ><tr><td>
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

<td style="margin-top:2px;">
<?php

// lista de técnicos

$res_tec = $DB->query($sql_tec);
$arr_tec = array();
$arr_tec[0] = "-- ". __('Select a technician','dashboard') . " --" ;

$DB->data_seek($result_tec, 0) ;

while ($row_result = $DB->fetch_assoc($result_tec))
    {
    $v_row_result = $row_result['id'];
    $arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname'] ;
    }

$name = 'sel_tec';
$options = $arr_tec;
$selected = 0;

echo dropdown( $name, $options, $selected );

//Dropdown::showFromArray( $name, $options, $selected );

?>
</td>
</tr>
<tr><td height="15px"></td></tr>
<tr>
<td colspan="2" align="center">

<button class="btn btn-primary btn-small" type="submit" name="submit" value="Atualizar" ><i class="icon-white icon-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?></button>
<button class="btn btn-primary btn-small" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="icon-white icon-trash"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button></td>
</td>
</tr>

    </table>
<?php Html::closeForm(); ?>
<!-- </form> -->
</div>

</div>

<?php
//tecnico2

if(isset($_GET['con'])) {

$con = $_GET['con'];

if($con == "1") {

if(!isset($_POST['date1']))
{
    $data_ini2 = $_REQUEST['date1'];
    $data_fin2 = $_REQUEST['date2'];
}

else {
    $data_ini2 = $_REQUEST['date1'];
    $data_fin2 = $_REQUEST['date2'];
}

if(!isset($_POST["sel_tec"])) {

$id_tec = $_GET["tec"];
}

else {
$id_tec = $_POST["sel_tec"];
}

if($id_tec == 0) {
echo '<script language="javascript"> alert(" ' . __('Select a technician','dashboard') . ' "); </script>';
echo '<script language="javascript"> location.href="rel_tecnico.php"; </script>';
}

if($data_ini2 === $data_fin2) {
$datas2 = "LIKE '".$data_ini2."%'";
}

else {
$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
}


//status

$status = "";

    $status_open = "('1','2','3','4')";
    $status_close = "('5','6')";
    $status_all = "('1','2','3','4','5','6')";


if(isset($_GET['stat'])) {

    if($_GET['stat'] == "open") {
        $status = $status_open;
        $stat = "open";
    }
    elseif($_GET['stat'] == "close") {
        $status = $status_close;
        $stat = "close";
    }
    else {
        $status = $status_all;
        $stat = "all";
    }
}

else {
        $status = $status_all;
        $stat = "all";
    }


//order

if(isset($_REQUEST['order'])) {
    $order = $_REQUEST['order'];
}
else {
    $order = 'id';
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
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS name, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate,
glpi_tickets.status, FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`solvedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time
FROM `glpi_tickets_users` , glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.type =2
AND glpi_tickets_users.users_id = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
GROUP BY id
ORDER BY id DESC
LIMIT ". $primeiro_registro .", ". $num_por_pagina ."
";

$result_cham = $DB->query($sql_cham);

//fim paginacao 1

$consulta1 =
"SELECT glpi_tickets.id AS id, glpi_tickets.name, glpi_tickets.date AS adate, glpi_tickets.solvedate AS sdate,
FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`solvedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time
FROM `glpi_tickets_users` , glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.users_id = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
GROUP BY id
ORDER BY id DESC
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


//abertos

$sql_ab = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.`users_id` AS id
FROM `glpi_tickets_users`, glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets.status IN ".$status_open."
AND glpi_tickets.is_deleted = 0" ;

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->fetch_assoc($result_ab);

$abertos = $data_ab['total'];


//satisfação por tecnico

$query_sat = "
SELECT glpi_users.id, avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions`, glpi_tickets_users, glpi_users
WHERE glpi_tickets.is_deleted = '0'
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets_users.tickets_id
AND `glpi_users`.id = glpi_tickets_users.users_id
AND glpi_tickets_users.type = 2
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec."
";

$result_sat = $DB->query($query_sat) or die('erro');
$media = $DB->fetch_assoc($result_sat);

$satisfacao = round(($media['media']/5)*100,1);
$nota = $media['media'];

//barra de porcentagem

if($conta_cons > 0) {

if($status == $status_close ) {
    $barra = 100;
    $cor = "progress-success";
}

else {

//porcentagem

$perc = round(($abertos*100)/$conta_cons,1);
$barra = 100 - $perc;

// cor barra

if($barra == 100) { $cor = "progress-success"; }

if($barra >= 80 and $barra < 100) { $cor = ""; }

if($barra > 51 and $barra < 80) { $cor = "progress-warning"; }

if($barra > 0 and $barra <= 50) { $cor = "progress-danger"; }

}
}
else { $barra = 0;}

//$satisfacao = 0;

//nome e total

$sql_nome = "
SELECT `firstname` , `realname`, `name`
FROM `glpi_users`
WHERE `id` = ".$id_tec."
";

$result_nome = $DB->query($sql_nome) ;

while($row = $DB->fetch_assoc($result_nome)){

echo "

<script>
function pagina()
{
var page=document.getElementById('npage').value;
location.href = 'rel_tecnico.php?con=1&stat=".$status1."&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec ."&npage='+page;
}
</script>

<div class='well info_box row-fluid span12' style='margin-top:25px; margin-left: -1px;'>

<table class='row-fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
<tr style='width: 450px;'><td style='vertical-align:middle;'> <span style='color: #000;'>".__('Technician','dashboard').": </span>
 ". $row['firstname'] ." ". $row['realname']. "</td>

<td style='vertical-align:middle; ' colspan=2> <span style='color: #000;'>".__('Tickets','dashboard').": </span>". $conta_cons ."</td>
<td style='vertical-align:middle; width: 180px; '>
    <div class='progress ". $cor ." progress-striped active' style='margin-top: 15px;'>
    <div class='bar' style='width:".$barra."%;'><div style='text-align: rigth; margin-top: 3px; margin-top:2px;'>".$barra." % ".__('Closed','dashboard') ." </div></div>
    </div>
</td>
</tr>

</table> ";

if($satisfacao != '' || $satisfacao > 0) {

    echo "
<table align='right' style='margin-bottom:10px;' width=100%>
<tr>

<td width=5% style='vertical-align:bottom;'>
<select id='npage' class='chosen-select' style='width:80px' onchange='pagina();'>
  <option value='0'>".__('Show')."</option>
  <option value='20'>20</option>
  <option value='30'>30</option>
  <option value='50'>50</option>
  <option value='100'>100</option>
</select>
</td>

<td colspan=4 >
<div id='gauge' style='width:130px; height:100px; margin-left: 120px;'></div>


<!-- gauge -->
    <script>
    var g = new JustGage({
    id: \"gauge\",
    value: ".$satisfacao.",
    min: 0,
    max: 100,
    title: \" ". __('Satisfaction','dashboard') ." - %\",
    label: \" \",
       levelColors: [
          \"#ff0000\",
          \"#FB8300\",
          \"#F9C800\",
          \"#9FCA0C\"
        ]

    });
    </script>

<td></td>
<td></td>
<td><button class='btn btn-primary btn-small' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_tecnico.php?con=1&stat=open&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Opened','dashboard'). " </button> </td>
<td><button class='btn btn-primary btn-small' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_tecnico.php?con=1&stat=close&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Closed','dashboard')." </button> </td>
<td><button class='btn btn-primary btn-small' type='button' name='todos' value='Todos' onclick='location.href=\"rel_tecnico.php?con=1&stat=all&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('All','dashboard')." </button> </td>
</tr>
</table>

<table class='table table-hover table-striped sortable' style='font-size: 13px; font-weight:bold;' cellpadding = 2px >

<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Tickets','dashboard') ." </th>
<th></th>
<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Title','dashboard') ."</th>
<th style=' color: #000; cursor:pointer;'> ". __('Opening date','dashboard') ."</th>
<th style=' color: #000; cursor:pointer;'> ". __('Close date','dashboard') ."</th>
<th style=' color: #000; cursor:pointer;'> ". __('Resolution time') ."</th>
<th style=' color: #000;' class='sorttable_nosort'> ". __('Satisfaction','dashboard') ."</th>
";
}

else {

echo "
<table align='right' style='margin-bottom:10px;'>
<tr><td>&nbsp;</td></tr>
<tr>

<td width=90%;>
<select id='npage' class='chosen-select' style='width:80px' onchange='pagina();'>
  <option value='0'>Select</option>
  <option value='20'>20</option>
  <option value='30'>30</option>
  <option value='50'>50</option>
  <option value='100'>100</option>
</select>
</td>

<td><button class='btn btn-primary btn-small' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_tecnico.php?con=1&stat=open&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Opened','dashboard'). " </button> </td>
<td><button class='btn btn-primary btn-small' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_tecnico.php?con=1&stat=close&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Closed','dashboard')." </button> </td>
<td><button class='btn btn-primary btn-small' type='button' name='todos' value='Todos' onclick='location.href=\"rel_tecnico.php?con=1&stat=all&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('All','dashboard')." </button> </td>
</tr>
</table>

<table class='table table-hover table-striped sortable' style='font-size: 13px; font-weight:bold;' cellpadding = 2px >

<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Tickets','dashboard') ." </th>
<th></th>
<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Title','dashboard') ."</th>
<th style=' color: #000; cursor:pointer;'> ". __('Opening date','dashboard') ."</th>
<th style=' color: #000; cursor:pointer;'> ". __('Close date','dashboard') ."</th>
<th style=' color: #000; cursor:pointer;'> ". __('Resolution time') ."</th>
";
}

}

//listar chamados

while($row = $DB->fetch_assoc($result_cham)){

$status1 = $row['status'];

    if($status1 == "1" ) { $status1 = "new";}
    if($status1 == "2" ) { $status1 = "assign";}
    if($status1 == "3" ) { $status1 = "plan";}
    if($status1 == "4" ) { $status1 = "waiting";}
    if($status1 == "5" ) { $status1 = "solved";}
    if($status1 == "6" ) { $status1 = "closed";}


if($satisfacao != '' || $satisfacao > 0) {

$query_satc = "SELECT `glpi_ticketsatisfactions`.satisfaction AS sat
FROM `glpi_ticketsatisfactions`
WHERE glpi_ticketsatisfactions.tickets_id = ". $row['id'] ."
";

$result_satc = $DB->query($query_satc);
$satc = $DB->fetch_assoc($result_satc);

$satc1 = $satc['sat'];



echo "
<tr>
<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
<td style='vertical-align:middle;'><img src=../../../../pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> </td>
<td> ". substr($row['name'],0,75) ." </td>
<td> ". conv_data_hora($row['date']) ." </td>
<td> ". conv_data_hora($row['solvedate']) ." </td>
<td> ". time_ext($row['time']) ."</td>
<td> <img src=./img/s". $satc1 .".png> </td>
</tr>";
    }
//}

else {


echo "
<tr>
<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
<td style='vertical-align:middle;'><img src=../../../../pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> </td>
<td> ". substr($row['name'],0,75) ." </td>
<td> ". conv_data_hora($row['date']) ." </td>
<td> ". conv_data_hora($row['solvedate']) ." </td>
<td> ". time_ext($row['time']) ."</td>

</tr>";

    }

}

echo "</table></div>"; ?>

<?php
// paginacao 2

echo '<div id=pag align=center class="paginas navigation row-fluid">';

$total_paginas = $conta_cons/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;
// se página maior que 1 (um), então temos link para a página anterior

if ($pagina > 1) {
    $prev_link = "<a href=".$url2."?con=1&stat=".$stat."&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$prev."&npage=".$num_por_pagina.">". __('Previous','dashboard') ."</a>";
  }
  else { // senão não há link para a página anterior
    $prev_link = "<a href='#'>".__('Previous','dashboard')."</a>";
  }
// se número total de páginas for maior que a página corrente,
// então temos link para a próxima página

if ($total_paginas > $pagina) {
    $next_link = "<a href=".$url2."?con=1&stat=".$stat."&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$next."&npage=".$num_por_pagina.">".__('Next','dashboard')."</a>";
  } else {
// senão não há link para a próxima página
    $next_link = "<a href='#'> " .__('Next','dashboard')."</a>";
  }

$total_paginas = ceil($total_paginas);
  $painel = "";
  for ($x=1; $x<=$total_paginas; $x++) {
    if ($x==$pagina) {
// se estivermos na página corrente, não exibir o link para visualização desta página
      //$painel .= "$x";

      $painel .= " <a style=color:#000999; href=".$url2."?con=1&stat=".$stat."&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$x."&npage=".$num_por_pagina.">$x</a>";
    } else {
      $painel .= " <a href=".$url2."?con=1&stat=".$stat."&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$x."&npage=".$num_por_pagina.">$x</a>";
    }
  }
// exibir painel na tela
echo "$prev_link  $painel  $next_link";
echo '</div><br>';
// fim paginacao 2
}
//}

else {

echo "
<div class='well info_box row-fluid span12' style='margin-top:30px; margin-left: -3px;'>
<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found','dashboard') . "</td></tr>
<tr></tr>
</table></div>";

}

}

}
?>

<script type="text/javascript" >
$('.chosen-select').chosen({disable_search_threshold: 10});
</script>

</div>
</div>

</div>
</div>

</body>
</html>

