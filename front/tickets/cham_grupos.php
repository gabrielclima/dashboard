<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
global $DB, $CFG_GLPI;

Session::checkLoginUser();
Session::checkRight("profile", "r");

?>

<html> 
<head>
<title> GLPI - <?php echo __('Open Tickets','dashboard'); ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta http-equiv="refresh" content= "45"/>

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />  
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />

<script src="../js/jquery.min.js" type="text/javascript" ></script>
<script src="../js/jquery.jclock.js"></script>

<script type="text/javascript">
$(function($) {
var options = {
timeNotation: '24h',
am_pm: true,
fontFamily: 'Open Sans',
fontSize: '30px',
foreground: 'black',
background: 'white'
}
$('#clock').jclock(options);
});
</script>

</head>
<body style="background-color: #fff;">
<?php

$status = "('2','1','3','4')"	;	

$id_grp = $_REQUEST['ent'];


$sql =
"SELECT count(glpi_tickets.id) AS total
FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
AND glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.status IN ".$status."
";

$result = $DB->query($sql);
$data = $DB->fetch_assoc($result);

$abertos = $data['total']; 

//insert if not exist Group

$query_i = "
INSERT IGNORE INTO glpi_plugin_dashboard_count (type, id, quant) 
VALUES ('3','". $id_grp ."', '" . $abertos ."')  ";

$result_i = $DB->query($query_i);

// get quantity

$query = "SELECT quant 
FROM glpi_plugin_dashboard_count
WHERE id = ".$id_grp." 
AND type = 3 ";

$result = $DB->query($query);
$quant = $DB->fetch_assoc($result);

$atual = $quant['quant']; 


//update tickets count

$query_up = "UPDATE glpi_plugin_dashboard_count 
SET quant=".$data['total']."
WHERE id = ".$id_grp." 
AND type = 3 ";

$result_up = $DB->query($query_up);

if($abertos > $atual) {

if($_SESSION['glpilanguage'] == "pt_BR") {	

    // IE

    echo '<!--[if IE]>';
    echo '<embed src="../sounds/novo_chamado.mp3" autostart="true" width="0" height="0" type="application/x-mplayer2"></embed>';
    echo '<![endif]-->';

    // Browser HTML5

    echo '<audio preload="auto" autoplay>';
    echo '<source src="../sounds/novo_chamado.ogg" type="audio/ogg"><source src="sounds/novo_chamado.mp3" type="audio/mpeg">';
    echo '</audio>';
}

else {
	
    // IE

    echo '<!--[if IE]>';
    echo '<embed src="../sounds/new_ticket.mp3" autostart="true" width="0" height="0" type="application/x-mplayer2"></embed>';
    echo '<![endif]-->';

    // Browser HTML5

    echo '<audio preload="auto" autoplay>';
    echo '<source src="../sounds/new_ticket.ogg" type="audio/ogg"><source src="sounds/new_ticket.mp3" type="audio/mpeg">';
    echo '</audio>';
}

}	


//contar chamados do dia 

$datahoje = date("Y-m-d");

$sql = "
SELECT count(glpi_tickets.id) AS total
FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
AND glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date LIKE '".$datahoje."%'
" ;

$result = $DB->query($sql);
$hoje=$DB->fetch_assoc($result);



// chamados de ontem - yesterday tickets

$dataontem = date('Y-m-d', strtotime('-1 day'));

$sql = "
SELECT count(glpi_tickets.id) AS total
FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
AND glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date LIKE '".$dataontem."%' ";

$result = $DB->query($sql);
$ontem = $DB->fetch_assoc($result);

//$cham_ontem = "'Chamados de ontem: " . $ontem['total'] . "'";

if ($ontem['total'] > $hoje['total']) { $up_down = "../img/down.gif"; }
if ($ontem['total'] < $hoje['total']) { $up_down = "../img/up.gif"; }
if ($ontem['total'] == $hoje['total']) { $up_down = "../img/blank.gif"; }


//Group name

$query_name = "
SELECT name 
FROM glpi_groups
WHERE glpi_groups.id = ".$id_grp." " ;

$result_n = $DB->query($query_name);
$group_name = $DB->result($result_n, 0, 'name');

?>

<div id="clock" style="align:right; position:absolute; margin-top:5px;"></div>

<div id='content'>
<div id='container-fluid' > 

<table style="width: 100%; margin-left: auto; margin-right: auto;">
<tr><td>&nbsp;</td></tr>
<tr>
<td align="center"><span class="titulo_cham"><?php echo  $group_name; ?></span> </td>
</tr>

<tr>
<td align="center"><span class="titulo_cham"><?php echo __('Open Tickets','dashboard'); ?>:</span> 
<span style="color:#8b1a1a; font-size:40pt; font-weight:bold;"> <?php echo "&nbsp; ".$data['total'] ; ?> </span> </td>

</tr>
<tr><td></td></tr>

<table style="color:#000099; font-size:25pt; font-weight:bold; width: 100%; margin-left: auto; margin-right: auto;"><tr><td align="center" ><span> <?php echo __('Today Tickets','dashboard'); ?>: 
<a href="../front/ticket.php" target="_blank" style="color:#8b1a1a;"> <?php echo "&nbsp; ".$hoje['total'] ; ?> </a>
<img src= <?php echo $up_down ;?>  alt="" title= <?php echo __('Yesterday','dashboard'). ':';  echo $ontem['total'] ;?>  > </span> </td></tr>
</table>

</table>
<p></p>

<div class="well info_box" style="width: 95%; margin-left: auto; margin-right: auto;">

<?php 

//$status = "('2','1','3','4')"	;	

echo "<table class='table table-hover table-striped' style='font-size: 18px; font-weight:bold;' cellpadding = 2px >";

$sql_cham = "SELECT glpi_tickets.id, glpi_tickets.name AS descri, glpi_tickets.status AS status, glpi_tickets.date_mod
FROM glpi_tickets, glpi_groups,`glpi_groups_tickets` 
WHERE  glpi_tickets.status IN  ".$status." 
AND glpi_tickets.is_deleted = 0
AND glpi_groups_tickets.`groups_id` = ".$id_grp."
AND glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
ORDER BY glpi_tickets.date_mod DESC";

$result_cham = $DB->query($sql_cham);

echo "<tr>
<td style='text-align:center; width:65px;'>". __('Tickets','dashboard')."</td>
<td style='text-align:center; width:240px;'>Status</td>
<td style='text-align:center;'>". __('Title','dashboard')."</td>
<td style='text-align:center;'>". __('Technician','dashboard')."</td>
</tr>";


while($row = $DB->fetch_assoc($result_cham)){ 

//$status1 = Ticket::getStatus($row['status']);

$status1 = $row['status']; 

if($status1 == "1" ) { $status1 = "new";} 
if($status1 == "2" ) { $status1 = "assign";} 
if($status1 == "3" ) { $status1 = "plan";} 
if($status1 == "4" ) { $status1 = "waiting";} 
if($status1 == "5" ) { $status1 = "solved";}  	            
if($status1 == "6" ) { $status1 = "closed";}


$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users` , glpi_tickets, glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.id = ". $row['id'] ."
AND glpi_tickets_users.`users_id` = glpi_users.id
AND glpi_tickets_users.type = 2
";
$result_tec = $DB->query($sql_tec);	

	$row_tec = $DB->fetch_assoc($result_tec);



echo "<tr>
<td style='text-align:center; vertical-align:middle;'> 
<a href=../../../../front/ticket.form.php?id=". $row['id'] ." target=_blank > <span style='color:#000099';>" . $row['id'] . "</span> </a>
</td>
<td style='vertical-align:middle;'>
<span style='color:#000099';><img src=../../../../pics/".$status1.".png />  ".Ticket::getStatus($row['status'])."</span >
</td>
<td style='vertical-align:middle;'>
<a href=../../../../front/ticket.form.php?id=". $row['id'] ." target=_blank > <span >" . $row['descri'] . "</span> </a>
</td>
<td style='vertical-align:middle;'>
<span >". $row_tec['name'] ." ".$row_tec['sname'] ."</span> 
</td>
</tr>"; 
 
 } 
 
echo "</table>"; 

//}
?>

</div>
</div>
</div>
</body>
</html>
