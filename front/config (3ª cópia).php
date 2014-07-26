<?php

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

Session::checkLoginUser();
Session::checkRight("profile", "r");
?>        

<html> 
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title> GLPI - <?php echo __('Setup'); ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<meta http-equiv="content-language" content="en-us">
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="./css/styles.css" rel="stylesheet" type="text/css">
<link href="./css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="./css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
<link href="./css/font-awesome.css" type="text/css" rel="stylesheet" />

<script src="./js/jquery.js" type="text/javascript"></script>
<link href="./inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="./inc/select2/select2.js" type="text/javascript" language="javascript"></script>
</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
<div id='container-fluid' style="margin: 0px 12% 0px 12%;"> 
		                                                            
<div id="tabela" class="row-fluid " >

	<div id="head" class="row-fluid span12" style="margin-bottom: 35px; margin-top:20px;;">	
	<a href="./index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px; color: #0088CC;"></i><span></span></a>
	<div id="titulo" style="margin-top: -5px; margin-bottom: 25px;"> <?php echo __('Setup')." ".__('Dashboard','dashboard'); ?> </div> 
	 
</div>
	<div id="pad-wrapper" >
		<div id="charts" class="row-fluid chart" style="margin-top:13%; background-color:#f2f2f2;">
		
		<?php
		
      echo "<div id='entity' class='center' style='margin-top: -15%; margin-left:-5%'> ";
      echo '<div id="datas-tec" class="span12 row-fluid" >';
                        
		function dropdown( $name, array $options, $selected=null )
		{
		    /*** begin the select ***/
		    $dropdown = '<select name="'.$name.'" id="'.$name.'" style="width: 300px; height: 27px;" onChange="this.form.submit()">'."\n";
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
                        
			// lista de entidades
		$sql_ent = "
		SELECT id, name
		FROM `glpi_entities`
		ORDER BY `name` ASC ";
		
		$result_ent = $DB->query($sql_ent);
		
		$arr_ent = array();
		$arr_ent[0] = "-- ". __('Select a entity', 'dashboard') . " --" ;
	   $arr_ent[1] = __('All', 'dashboard') ;		
		
		while ($row_result = $DB->fetch_assoc($result_ent))
		    {
		    $v_row_result = $row_result['id'];
		    $arr_ent[$v_row_result] = $row_result['name'] ;
		    }
		
		$name = 'sel_ent';
		$options = $arr_ent;
		$selected = "0";		
		//echo dropdown( $name, $options, $selected );			
		//$count = count($arr_ents);
		            
				      if(isset($_REQUEST['conf']) && $_REQUEST['conf'] == 1 ) {	      	
							if(isset($_REQUEST['sel_ent'])) {				
									$ent = $_REQUEST['sel_ent'];												
									
									$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
												  VALUES ('entity', '".$ent."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$ent."' ";																
									$result = $DB->query($query);																							
							}								
						}																														 
		                               
 		 
 		echo '<form id="form2" name="form2" class="form1" method="post" action="config.php?conf=1">';  
 						
		echo "<table border='0' style='width: 370px; margin-left: auto; margin-right: auto; margin-bottom: 20px; margin-top:20px;'>
				<tr>
					<td>-- ".__('Entity','dashboard').":&nbsp;";
	
		echo dropdown( $name, $options, $selected );	
		//echo $ent;
	
		echo "</td>
				</tr>
				</table>";
				Html::closeForm(); 				
		//echo "</div>"; 				
		
		
      echo "<div id='config' class='center' style='margin-top: 0%; margin-left:0%'> ";
                        
		//count years
		$query = "SELECT DISTINCT DATE_FORMAT( date, '%Y' ) AS year
			FROM glpi_tickets
			WHERE glpi_tickets.is_deleted = '0'
			AND date IS NOT NULL
			ORDER BY year DESC";
		
		$result = $DB->query($query);
		$conta_y = $DB->numrows($result);
		
		$arr_years = array();
		
		while ($row_y = $DB->fetch_assoc($result))		
			{ 
				$arr_years[] = $row_y['year'];			
			} 
			
		$count_y = count($arr_years);
		            
				      if(isset($_REQUEST['conf']) && $_REQUEST['conf'] == 1 ) {	      	
							if(isset($_REQUEST['num'])) {				
									$num = $_REQUEST['num'];												
									
									$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
												  VALUES ('num_years', '".$num."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$num."' ";																
									$result = $DB->query($query);														
							}								
						}		
						
						// color theme  	
							if(isset($_REQUEST['theme'])) {				
									$skin = $_REQUEST['theme'];													
									
									$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
												  VALUES ('theme', '".$skin."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$skin."' ";																
									$result = $DB->query($query);																																														
							}																						 
		                               
 		echo '<div id="datas-tecx" class="span12 row-fluid" > 
 				<form id="form1" name="form1" class="form1" method="post" action="config.php?conf=1">';  
 						
		echo "<table border='0' style='width: 370px; margin-left: auto; margin-right: auto; margin-bottom: 20px; margin-top:20px;'>
				<tr>
					<td>-- ".__('Period in index page','dashboard').":&nbsp; 
						<select id='num' name='num' style='width: 130px;' onchange='this.form.submit()'>
							<option value=''>".__('Select','dashboard')."</option>
							<option value='0'>".__('All')."</option>
							<option value='1'>".__('Current year','dashboard')."</option>";
								
						for($i=2; $i <= $conta_y; $i++) {	
							echo "<option value='".$i."'>2014 - ".($arr_years[0]-($i-1))."</option>";
					   }							   			
				
		echo "</td>
				</tr>
				</table>";
				Html::closeForm();  		
									 
 			 echo '<div id="skins" class="form1">';  
			 echo "<table border='0' width=375px>									
					<tr>
						<td>-- ".__('Theme','dashboard').":&nbsp;</td>
  				   </tr>
					<tr style='text-align:center;'>
						<td><div id='default-t' style='cursor:pointer;' ><img src='./img/default-t.png' alt='default'/></div></td>
						<td>&nbsp;&nbsp;</td>					
						<td><div id='glpi-t' style='cursor:pointer;' ><img src='./img/glpi-t.png' alt='glpi'/></div></td>	
					</tr>
					<tr><td height='10px'></td></tr>
					<tr style='text-align:center;'>				
						<td><button class='btn btn-primary btn-sm' type='button' id='skin-default' name='glpi_skin' value=\"Default\" onclick='location.href=\"config.php?theme=skin-default.css\"'> Default </button></td>
						<td>&nbsp;&nbsp;</td>						
						<td><button class='btn btn-primary btn-sm' type='button' id='skin-glpi' name='glpi_skin' value=\"GLPI\" onclick='location.href=\"config.php?theme=skin-glpi.css\"'>GLPI</button></td>
					</tr>
					
					<tr><td>&nbsp;</td></tr>
						
					<tr style='text-align:center;'>
						<td><div id='graphite-t' style='cursor:pointer;' ><img src='./img/graphite-t.png' alt='graphite'/></div></td>
						<td>&nbsp;&nbsp;</td>						
						<td><div id='nature-t' style='cursor:pointer;' ><img src='./img/nature-t.png' alt='nature'/></div></td>
					</tr>
					<tr><td height='10px'></td></tr>
					<tr style='text-align:center;'>
						<td><button class='btn btn-primary btn-sm' type='button' name='glpi_skin' value=\"Graphite\" id='skin-graphite' onclick='location.href=\"config.php?theme=skin-graphite.css\"'>Graphite</button></td>
						<td>&nbsp;&nbsp;</td>
						<td><button class='btn btn-primary btn-sm' type='button' name='glpi_skin' value=\"Nature\" id='skin-nature' onclick='location.href=\"config.php?theme=skin-nature.css\"'>Nature</button></td>
					</tr>					
				
				</table>	";			
		echo "</div>
				</div>
				"; 																		  
?>
		<style type="text/css">
			#default-s{ display:none; }
			#glpi-s{ display:none; }
			#graphite-s { display:none; }
			#nature-s { display:none; } 
		</style>  		
		  		
		<div id="default-s" style="position:absolute; margin-left:12%; margin-top:10%; cursor:pointer;">
			<img src="./img/default-s.png" alt="default" />
		</div>
		<div id="glpi-s" style="position:absolute; margin-left:12%; margin-top:10%; cursor:pointer;">
			<img src="./img/glpi-s.png" alt="glpi" />
		</div>   		
		<div id="graphite-s" style="position:absolute; margin-left:12%; margin-top:10%; cursor:pointer;">
			<img src="./img/graphite-s.png" alt="graphite" />
		</div>
		<div id="nature-s" style="position:absolute; margin-left:12%; margin-top:10%; cursor:pointer;">
			<img src="./img/nature-s.png" alt="nature" />
		</div>  		
  		
      </div>
	</div>
	
<script type="text/javascript" >
$(document).ready(function() { $("#num").select2(); });

$(document).ready(function() { $("#sel_ent").select2(); });

$(document).ready(function () {
	
	$('#default-t').on("click", "img", function () {
	    //alert('You Clicked Me');
	    $('#default-s').show(); 
	});	
	$('#default-s').on("click", "img", function () {    
	    $('#default-s').hide(); 
	});	
	
	$('#glpi-t').on("click", "img", function () {
	    $('#glpi-s').show(); 
	});	
	$('#glpi-s').on("click", "img", function () {    
	    $('#glpi-s').hide(); 
	});
	
	$('#graphite-t').on("click", "img", function () {   
	    $('#graphite-s').show(); 
	});	
	$('#graphite-s').on("click", "img", function () {    
	    $('#graphite-s').hide(); 
	});
	
	$('#nature-t').on("click", "img", function () {
	    $('#nature-s').show(); 
	});	
	$('#nature-s').on("click", "img", function () {    
	    $('#nature-s').hide(); 
	});
    
});

</script>
	
</div>
</div>
</div>
</body>
</html>