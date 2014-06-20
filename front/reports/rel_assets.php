<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB, $row_count, $type;

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

if(!isset($_REQUEST["sel_item"])) {
	$id_item = $_GET["sel_item"];		
}

else {
	$id_item = $_POST["sel_item"];
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


if(isset($_REQUEST['itemtype'])) {

	$type = $_REQUEST['itemtype']; }
	
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

?>

<html> 
<head>
<title> GLPI - <?php echo __('Assets') ?> </title>
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
  
  <script src="./manufac.js"></script>
  <script src="./model.js"></script>
  
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
	
.carregando {
display: none;
}	

.sel_fab .sel_mod {
display: block;
}	
</style>

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_graf"> <?php echo __('Assets') ?> </div>
	
		<div id="datas-tec" class="span12 row-fluid" >
 
		<form id="form1" name="form1" class="form_rel" method="post" action="rel_assets.php?con=1" style="margin-left: 25%;"> 
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
		<td style="margin-top:2px; width:100px;"><?php echo __('Type'); ?>: </td>		
		<td style="margin-top:2px;">
		
		<?php
		
		// lista de tipos
		
		echo "
		<select id='sel_item' name='sel_item' class='chosen-selec' tabindex='-1' style='width: 300px; height: 27px;' autofocus onChange=\"ajaxComboBox('manufac.php','sel_fab');\">
			<option value='0'> ---- </option>
			<option value='1'>".__('Computer')."</option>
			<option value='2'>".__('Monitor')."</option>
			<option value='3'>".__('Software')."</option>
			<option value='4'>".__('Network')."</option>
			<option value='5'>".__('Device')."</option>
			<option value='6'>".__('Printer')."</option>
			<option value='7'>".__('Phone')."</option>
		</select>
		";	
		?>
		</td>
		</tr>
				
		<tr>
		<td style="margin-top:2px; width:100px;"><?php echo __('Manufacturer'); ?>:  </td>
		
		<td style="margin-top:5px;">
		<span class="carregando">Aguarde, carregando...</span>
			<select name="sel_fab" id="sel_fab" class="chosen-selec sel_fab" tabindex="-1" style="width: 300px; height: 27px;" autofocus onChange="ajaxComboBox2('model.php','sel_mod');">
				<option value="0">-- Escolha um tipo --</option> 
			</select>		
		</td>
		</tr>
				
		<tr>
		<td style="margin-top:2px; width:165px;"><?php echo __('Model')."/". __('Version'); ?>: </td>
		
		<td style="margin-top:2px;">	  
			<select name="sel_mod" id="sel_mod" class="chosen-selec sel_mod" tabindex="-1" style="width: 300px; height: 27px;" autofocus>
				<option value="0">-- Escolha um fabricante --</option>
			</select>			

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


if(isset($_GET['con'])) {
	$con = $_GET['con'];
}
else {
	$con = 0;	
}

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


if(isset($_REQUEST['itemtype'])) {
	$type = $_REQUEST['itemtype']; 
	}
	
else {	

$itemtype = $id_item;

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

if(!isset($_REQUEST["sel_item"])) { $id_item = 0; }
else { $id_item = $_REQUEST["sel_item"]; }

if(isset($_REQUEST["sel_fab"]) && $_REQUEST["sel_fab"] != '0') { $id_fab = $_REQUEST["sel_fab"]; }
else { $id_fab = ''; }

if(isset($_REQUEST["sel_mod"]) && $_REQUEST["sel_mod"] != '0')
	{ 
		if($_REQUEST["sel_mod"] != '') {
			 $id_mod = $_REQUEST["sel_mod"]; $model = "AND ".$type."models_id = ".$id_mod.""; 
		} 
	}

else { $id_mod = ''; $model = '';}

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
if($id_mod == '') {

	if($type != 'software') {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			ORDER BY name
			 ";
			 //LIMIT ". $primeiro_registro .", ". $num_por_pagina ."
			
			$result_cham = $DB->query($sql_cham);
			
			//fim paginacao 1
			
			$consulta1 = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			ORDER BY name";
			
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;	
		}	
		
		else {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			ORDER BY name
			 ";
			 //LIMIT ". $primeiro_registro .", ". $num_por_pagina ."
			
			$result_cham = $DB->query($sql_cham);
			
			//fim paginacao 1
			
			$consulta1 = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			ORDER BY name";
			
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;
			}
	
	}

else {

	if($type != 'software') {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND ".$type."models_id = ".$id_mod."
			AND is_deleted = 0
			ORDER BY name
			 ";
			 //LIMIT ". $primeiro_registro .", ". $num_por_pagina ."
			
			$result_cham = $DB->query($sql_cham);
			
			//fim paginacao 1	
			$consulta1 = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND ".$type."models_id = ".$id_mod."
			AND is_deleted = 0
			ORDER BY name ";
		
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;
		}
		
		else {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE id = ".$id_mod."			
			ORDER BY name
			 ";
			//LIMIT ". $primeiro_registro .", ". $num_por_pagina ."
			
			$result_cham = $DB->query($sql_cham);
			
			//fim paginacao 1	
			$consulta1 = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE id = ".$id_mod."			
			ORDER BY name ";
		
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;
		}		
		
	}


if($consulta > 0) {

if(!isset($_GET['pagina'])) {
	$primeiro_registro = 0;
	$pagina = 1;
}
else {
	$pagina = $_GET['pagina'];
	$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
}


//fabricante
	$sql_fab = "SELECT name
			 		FROM glpi_manufacturers
			 		WHERE id = ".$id_fab." ";
	
	$result_fab = $DB->query($sql_fab);
	$fab = $DB->fetch_assoc($result_fab);	

//listar chamados

echo "

<div class='well info_box row-fluid span12' style='margin-top:25px; margin-left: -1px;'>

<table class='row-fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Type').": </span>". __(ucfirst($type)) ." </td>
<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Manufacturer').": </span>". $fab['name'] ." </td>
<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Quantity','dashboard').": </span>".$consulta." </td>
<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>
".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)." 
</td>
</table>

<table id='asset' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
<thead>
<tr>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Name')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Model')."/". __('Version')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer;'> ".__('Serial')." </th>
<th style='font-size: 12px; font-weight:bold; color:#000; cursor:pointer; text-align:center;'> ".__('Tickets','dashboard')." </th>
</tr>
</thead>
<tbody>";


while($row = $DB->fetch_array($result_cham)){

if($type == 'software') {	
	
	$sql_item = "SELECT id, name
			 		FROM glpi_softwares
			 		WHERE id = " . $row['id'] . "			 		
			 		";
}
else	{

	$sql_item = "SELECT id, name, serial
			 		FROM glpi_".$type."s
			 		WHERE id = " . $row['id'] . "			 		
			 		AND is_deleted = 0
			 		". $model ."";
}

	$result_item = $DB->query($sql_item);		
	$row_item = $DB->fetch_assoc($result_item);
	
	//contar chamados
	$sql_count = "SELECT count(id) AS conta
			 		FROM glpi_tickets
			 		WHERE itemtype = '" . ucfirst($type) . "'
			 		AND items_id = " . $row['id'] . " 
					AND is_deleted = 0 
					AND date ".$datas2." ";
	
	$result_count = $DB->query($sql_count);
	$row_count = $DB->fetch_assoc($result_count);	
	
	
	//fabricantes
	if($id_fab != '') {	
	 
		$sql_fab = "SELECT name
				 		FROM glpi_manufacturers
				 		WHERE id = ".$id_fab." ";
		
		$result_fab = $DB->query($sql_fab);
		$row_fab = $DB->fetch_assoc($result_fab);	
	}
	
	//modelo	 
	if($id_mod != '' ) {
		
		if($type != 'software') {
			$sql_mod = "SELECT gtm.name AS name
							FROM glpi_".$type."s gt, glpi_".$type."models gtm
							WHERE gt.".$type."models_id = ".$id_mod."
							AND gt.is_deleted = 0
							AND gt.".$type."models_id = gtm.id
							AND gt.id = ".$row['id']." ";
						
			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);		
		}
		
		else {
			$sql_mod = "SELECT id, name
							FROM `glpi_softwareversions`
							WHERE `softwares_id` = ".$row['id']."";
				
			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);			
			  }
	}	
	
	else { 
	
		if($type != 'software') {
			$sql_mod = "SELECT gtm.id AS id, gtm.name AS name
							FROM glpi_".$type."models gtm, glpi_".$type."s gt						
							WHERE gt.".$type."models_id = gtm.id
							AND gt.manufacturers_id = ".$id_fab."
							AND gt.id = ".$row['id']."
							AND gt.is_deleted = 0 ";		
			
			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);
		}	
		else {
			$sql_mod = "SELECT id, name
			FROM glpi_softwareversions
			WHERE id = ".$row['id']."			
			ORDER BY name";		

			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);	
		
		}	
	}	
		 				
echo "	
<tr>
<td style='vertical-align:middle;'><a href=".$CFG_GLPI['root_doc']."/front/".$type.".form.php?id=". $row_item['id'] ." target=_blank >".$row_item['name']." (".$row_item['id'].")</a></td>
<td style='vertical-align:middle;'> ". $row_mod['name'] ." </td>

<td style='vertical-align:middle;'> ". $row_item['serial'] ." </td>			
	
<td style='vertical-align:middle; text-align:center;'> <a href='rel_assets_tickets.php?con=1&itemtype=". $type."&sel_item=".$row['id']."&sel_fab=".$id_fab."&date1=".$data_ini2."&date2=".$data_fin2."' target=_blank>". $row_count['conta'] ." </a></td>
</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    oTable = $('#asset').dataTable({
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
	        }]
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
    $prev_link = "<a href=".$url2."?con=1&itemtype=".$type."&date1=".$data_ini2."&date2=".$data_fin2."&sel_item=".$id_item."&sel_fab=".$id_fab."&sel_mod=".$id_mod."&pagina=".$prev."&npage=".$num_por_pagina.">". __('Previous', 'dashboard') ."</a>";
  }
  else { // senão não há link para a página anterior  
    $prev_link = "<a href='#'>".__('Previous', 'dashboard')."</a>";
  }
// se número total de páginas for maior que a página corrente, 
// então temos link para a próxima página  

if ($total_paginas > $pagina) {
    $next_link = "<a href=".$url2."?con=1&itemtype=".$type."&date1=".$data_ini2."&date2=".$data_fin2."&sel_item=".$id_item."&sel_fab=".$id_fab."&sel_mod=".$id_mod."&pagina=".$next."&npage=".$num_por_pagina.">".__('Next', 'dashboard')."</a>";
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
      
      $painel .= " <a style=color:#000999; href=".$url2."?con=1&itemtype=".$type."&date1=".$data_ini2."&date2=".$data_fin2."&sel_item=".$id_item."&sel_fab=".$id_fab."&sel_mod=".$id_mod."&pagina=".$x."&npage=".$num_por_pagina.">$x</a>";
    } else {
      $painel .= " <a href=".$url2."?con=1&itemtype=".$type."&date1=".$data_ini2."&date2=".$data_fin2."&sel_item=".$id_item."&sel_fab=".$id_fab."&sel_mod=".$id_mod."&pagina=".$x."&npage=".$num_por_pagina.">$x</a>";
    }
  }
// exibir painel na tela
//echo "$prev_link  $painel  $next_link";
echo "</div><br>";
// fim paginacao 2
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

<script type='text/javascript' >
$('.chosen-select').chosen({disable_search_threshold: 10});
</script>	

			</div>
		</div>
	</div>
</div>

</body> 
</html>
