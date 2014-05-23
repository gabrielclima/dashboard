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

if(!isset($_POST["sel_tec"])) {
    $id_tec = $_GET["tec"];
}

else {
    $id_tec = $_POST["sel_tec"];
}

//paginacao

$num_por_pagina = 20;

if(!isset($_GET['pagina'])) {
    $primeiro_registro = 0;
    $pagina = 1;
}
else {
    $pagina = $_GET['pagina'];
    $primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
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
<title> GLPI - <?php echo _n('Task','Tasks',2) .'  '. __('by Technician', 'dashboard') ?> </title>
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

<script src="../js/sorttable.js"></script>

</head>
<body style="background-color: #e5e5e5;">
<?php

$sql_tec = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname
FROM `glpi_users`, glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 2
AND glpi_users.is_deleted = 0
AND glpi_users.is_active = 1
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

    <div id="titulo_graf"> <?php echo _n('Task','Tasks',2) .'  '. __('by Technician','dashboard') ?>  </div>

        <div id="datas-tec" class="span12 row-fluid" >

    <form id="form1" name="form1" class="form_rel" method="post" action="rel_tarefa.php?con=1">
    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef">
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
$arr_tec[0] = "-- ". __('Select a technician', 'dashboard') . " --" ;

$DB->data_seek($result_tec, 0);

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

<button class="btn btn-primary btn-small" type="submit" name="submit" value="Atualizar" ><i class="icon-white icon-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
<button class="btn btn-primary btn-small" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="icon-white icon-trash"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
</td>
</tr>

    </table>
<?php Html::closeForm(); ?>
<!-- </form> -->

        </div>
    </div>
</div>

<script type="text/javascript" >
$('.chosen-select').chosen();
</script>

<?php

if(isset($_GET['con'])) {

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
echo '<script language="javascript"> alert(" ' . __('Select a technician', 'dashboard') . ' "); </script>';
echo '<script language="javascript"> location.href="rel_tarefa.php"; </script>';
}

if($data_ini2 === $data_fin2) {
    $datas2 = "LIKE '".$data_ini2."%'";
}

else {
    $datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
}

// Chamados

$sql_cham =
"SELECT glpi_tickets.id AS id, glpi_tickettasks.taskcategories_id AS tipo, glpi_tickettasks.date AS date, glpi_tickettasks.content,
glpi_tickettasks.users_id, glpi_tickettasks.actiontime, glpi_tickettasks.begin, glpi_tickettasks.end
FROM `glpi_tickets` , glpi_tickettasks
WHERE glpi_tickets.id = glpi_tickettasks.`tickets_id`
AND glpi_tickettasks.users_id_tech = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND glpi_tickettasks.date ". $datas2 ."
GROUP BY id
ORDER BY id DESC
LIMIT ". $primeiro_registro .", ". $num_por_pagina ."
";

$result_cham = $DB->query($sql_cham);

//fim paginacao 1

$consulta1 =
"SELECT glpi_tickets.id AS id, glpi_tickettasks.taskcategories_id AS tipo, glpi_tickettasks.date AS date, glpi_tickettasks.content,
glpi_tickettasks.users_id, glpi_tickettasks.actiontime, glpi_tickettasks.begin, glpi_tickettasks.end
FROM `glpi_tickets` , glpi_tickettasks
WHERE glpi_tickets.id = glpi_tickettasks.`tickets_id`
AND glpi_tickettasks.users_id_tech = ". $id_tec ."
AND glpi_tickets.is_deleted =0
AND glpi_tickettasks.date ". $datas2 ."
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


//nome e total

$sql_nome = "
SELECT `firstname` , `realname`, `name`
FROM `glpi_users`
WHERE `id` = ".$id_tec."
";

$result_nome = $DB->query($sql_nome) ;


//total time of tasks

while($row = $DB->fetch_assoc($result_cons1)){
    $tempoTotal += $row['actiontime'];
}


//table thread

while($row = $DB->fetch_assoc($result_nome)){

echo "
<div class='well info_box row-fluid span12' style='margin-top:25px; margin-left: -1px;'>

<table class='row-fluid'  style='font-size: 18px; font-weight:bold; margin-bottom: 30px;' cellpadding = 1px>
<tr>
<td style='vertical-align:middle; width:45%;'> <span style='color: #000;'>".__('Technician', 'dashboard').": </span>  ". $row['firstname'] ." ". $row['realname']. "</td>
<td style='vertical-align:middle;'> <span style='color: #000;'>"._n('Task', 'Tasks',2).": </span>". $conta_cons ."</td>
<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Time').": </span>". time_ext($tempoTotal) ."</td>
</tr>
</table>

<table class='table table-hover table-striped sortable' style='font-size: 13px; font-weight:bold;' cellpadding = 2px>
<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Ticket') ."  </th>
<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Date') ." </th>
<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Description') ."</th>
<th style='color: #000; cursor:pointer;'> ". __('Duration') ." </th>

<th style='color: #000; cursor:pointer;'> ". __('Begin') ." </th>
<th style='color: #000; cursor:pointer;'> ". __('End') ."  </th>
";
}


//listar chamados

$DB->data_seek($result_cham, 0);
while($row = $DB->fetch_assoc($result_cham)){

echo "
<tr>
<td style='text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
<td> ". conv_data_hora($row['date']) ." </td>
<td> ". $row['content'] ." </td>
<td> ". time_ext($row['actiontime']) ."</td>";



echo "
<td> ". $row['begin'] ."</td>
<td> ". $row['end'] ."</td>
</tr>";
}

echo "</table></div>";


// paginacao 2

echo '<div id=pag align=center class="paginas navigation row-fluid">';

$total_paginas = $conta_cons/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;
// se página maior que 1 (um), então temos link para a página anterior

if ($pagina > 1) {
    $prev_link = "<a href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$prev.">". __('Previous', 'dashboard') ."</a>";
  }
  else { // senão não há link para a página anterior
    $prev_link = "<a href='#'>".__('Previous', 'dashboard')."</a>";
  }
// se número total de páginas for maior que a página corrente,
// então temos link para a próxima página

if ($total_paginas > $pagina) {
    $next_link = "<a href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$next.">".__('Next', 'dashboard')."</a>";
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

      $painel .= " <a style=color:#000999; href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$x.">$x</a>";
    } else {
      $painel .= " <a href=".$url2."?con=1&date1=".$data_ini2."&date2=".$data_fin2."&tec=".$id_tec."&pagina=".$x.">$x</a>";
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
<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
<tr></tr>
</table></div>";

}

}

}
?>

</div>

</div>
</div>

</body>
</html>

