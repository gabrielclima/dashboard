<?php
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", "r");

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

   switch (date("w")) {
	    case "0": $dia = __('Sunday','dashboard'); break;    
	    case "1": $dia = __('Monday','dashboard'); break;
	    case "2": $dia = __('Tuesday','dashboard'); break;
	    case "3": $dia = __('Wednesday','dashboard'); break;
	    case "4": $dia = __('Thursday','dashboard'); break;
	    case "5": $dia = __('Friday','dashboard'); break;
	    case "6": $dia = __('Saturday','dashboard'); break;  
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>GLPI - Dashboard - Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content= "120"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="img/dash.ico" type="image/x-icon" />
	 <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />
    
    <!-- Bootstrap 
   <link href="css/bootstrap-responsive.css" rel="stylesheet" />
    <link href="css/bootstrap-overrides.css" type="text/css" rel="stylesheet" />    
    -->
    <link href="css/bootstrap3.css" rel="stylesheet">
 

    <!-- Styles -->
    <link href="css/styled.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/elements.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">

    <!-- Custom Styles -->
    <link href="css/custom.css" rel="stylesheet">
    
     <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />    

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/style-dash.css" rel="stylesheet" type="text/css" />
    <link href="css/dashboard.css" rel="stylesheet" type="text/css" />


    <link href="less/style.less" rel="stylesheet"  title="lessCss" id="lessCss">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
              <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
              <![endif]-->
              <script src="js/jquery.js"></script>

        </head>
        <body>

<script type="text/javascript">
$(function($) {
var options = {
timeNotation: '24h',
am_pm: false,
fontFamily: 'Open Sans',
fontSize: '11pt',
foreground: '#FFF'
}
$('#clock').jclock(options);
});
</script> 

        </head>
        <body style="background-color: #FFF;" >

            <div class="site-holder">
                <!-- .navbar -->
                <nav class="navbar  navbar-default nav-delighted" role="navigation">
                    <a href="#" class="toggle-left-sidebar">
                        <i class="fa fa-th-list"></i>
                    </a>

                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="<?php echo $CFG_GLPI['url_base'].'/glpi/front/ticket.php';?>" target="_blank">
                            <span>GLPI</span></a>
                    </div>
					<!-- NAVBAR LEFT  -->
					
					<ul id="navbar-left" class="nav navbar-nav pull-left hidden-xs">
					    <li class="dropdown">
					        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="margin-top: 6px;">           
					            <span class="name" style="color:#FFF; font-size:14pt;">
					                <?php echo __('Tickets Statistics','dashboard'); ?>  
					            </span>            
					        </a>
					        <ul class="dropdown-menu skins">
					            <li class="dropdown-title"></li>
					        </ul>
					    </li>
					</ul>
					<!-- /NAVBAR LEFT -->
					
					<ul class="nav navbar-nav pull-right hidden-xs">
						<li id="header-user" class="dropdown user">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:#FFF; font-size:11pt; margin-top:5px;">							
							<span class="username">
							
							<script type="text/javascript">
							var d_names = <?php echo '"'.$dia.'"' ; ?>;
							var m_names = <?php echo '"'.$mes.'"' ; ?>;
							
							var d = new Date();
							var curr_day = d.getDay();
							var curr_date = d.getDate();
							var curr_month = d.getMonth();
							var curr_year = d.getFullYear();
							
							document.write(d_names + ", " + curr_date + " " + m_names + " " + curr_year );
							
							</script> 
							</span>
							<span id="clock"></span>							
							
							</a>
						</li>
					</ul>                    
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    
                                </li>
                                <li>
                                   
                                </li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->                                                                      
                    </nav>

                    <!-- .box-holder -->
                    <div class="box-holder">

                        <!-- .left-sidebar -->
                        <div class="left-sidebar">
                            <div class="sidebar-holder">
                                <!-- User   -->
                                <div class="user-menu">
                                   
                                    <div class="user-info" style="margin-left: 30px;">
                                        <div class="welcome"><?php echo __('Welcome','dashboard'); ?> , </div>
                                        <div class="username"><?php echo $_SESSION["glpifirstname"]; ?></div>
                                    </div>
                                  
                                </div>
                                <!-- /.User   -->

                                <!-- Menu -->
                                <ul class="nav  nav-list">
                                
                                  <li class=''>
                                    <a href='./index.php' data-original-title='Dashboard'>
                                        <i class='fa fa-home'></i>
                                        <span class='hidden-minibar'>Dashboard</span>
                                    </a>
                                    <li class='submenu  '>
                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Chamados'>
                                        <i class='fa fa-edit'></i>
                                        <span class='hidden-minibar'><?php echo __('Tickets','dashboard');?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
													<ul  class="animated fadeInDown">
                                        <li class=' '>
                                            <a href="./tickets/chamados.php" data-original-title=' Geral' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                             <a href="./tickets/select_ent.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./tickets/select_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li>   
                                         <li class=' '>
                                              <a href="./map/index.php" data-original-title=' Mapa' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Map','dashboard'); ?> </span>
                                            </a>
                                        </li>                                 
                                    </ul>                                    
                                </li>
                                </li>
                                                               
											<li class='submenu  '>
                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Relatórios'>
                                        <i class='fa fa-list-alt'></i>
                                        <span class='hidden-minibar'><?php echo __('Reports','dashboard'); //<b class="caret"></b>?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
													<ul  class="animated fadeInDown">
                                        <li class=' '>
                                            <a href="./reports/rel_tecnico.php" data-original-title=' por Técnico' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                             <a href="./reports/rel_usuario.php" data-original-title=' por Usuário' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li> 
                                        <li class=' '>
                                              <a href="./reports/rel_entidade.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_localidade.php" data-original-title=' by Localization' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_categoria.php" data-original-title=' por Categoria' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Category','dashboard'); ?> </span>
                                            </a>
                                        </li> 
      											<li class=' '>
                                              <a href="./reports/rel_data.php" data-original-title=' por Data' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?> </span>
                                            </a>
                                        </li> 
                                        <li class=' '>
                                              <a href="./reports/rel_tarefa.php" data-original-title=' Tarefas' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Task','Tasks',2); ?> </span>
                                            </a>
                                        </li> 
													 <li class=' '>
                                              <a href="./reports/rel_tickets.php" data-original-title=' Tickets' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Ticket','Tickets',2); ?></span>
                                            </a>
                                        </li>                             
                                    </ul>                                    
                                </li>			                                
                                
                                <li class='submenu'>
                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Gráficos'>
                                        <i class="fa fa-bar-chart-o"></i>
                                        <span class='hidden-minibar'><?php echo __('Charts','dashboard'); ?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
                                    <ul  class="animated fadeInDown">
                                         <li class=' '>
                                              <a href="./graficos/geral.php" data-original-title=' Geral' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graficos/tecnicos.php" data-original-title=' Técnicos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/usuarios.php" data-original-title=' Usuários' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graficos/grupos.php" data-original-title=' Grupos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Group','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graficos/entidades.php" data-original-title=' Entidades' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/satisfacao.php" data-original-title=' Satisfação' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Satisfaction','dashboard'); ?></span>
                                            </a>
                                        </li>
								 					 <li class=' '>
                                              <a href="./graficos/graf_tecnico.php" data-original-title=' por Técnico' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/graf_usuario.php" data-original-title=' por Usuário' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
													 <li class=' '>
                                              <a href="./graficos/graf_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?></span>
                                            </a>
                                        </li>
  													 <li class=' '>
                                              <a href="./graficos/graf_entidade.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/graf_localidade.php" data-original-title=' por Localidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/geral_mes.php" data-original-title=' por Data' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?></span>
                                            </a>
                                        </li>
                           
                                    </ul>
                                </li><!-- delighted pages -->  

									     <li class=' '>
                                    <a href='assets/assets.php' target="_blank" data-original-title='Assets'>
                                        <i class='fa fa-desktop'></i>
                                        <span class='hidden-minibar'><?php echo __('Assets'); ?>
                                        </span>
                                    </a>  
                                </li>
                                
                                <li class=' '>
                                    <a href='info.php' target="_blank" data-original-title='Info'>
                                        <i class='fa fa-info-circle'></i>
                                        <span class='hidden-minibar'>Info
                                        </span>
                                    </a>  
                                </li>
                                
				 <li>
              <?php

              //version check	              								
              								
					$ver = explode(" ",implode(" ",plugin_version_dashboard())); 																																																			
					$urlv = "http://a.fsdn.com/con/app/proj/glpidashboard/screenshots/".$ver[1].".png";
					$headers = get_headers($urlv, 1);										

					if($headers[0] != '') {

					//if ($headers[0] == 'HTTP/1.1 200 OK') { }

					if ($headers[0] == 'HTTP/1.0 404 Not Found') {
						echo '<a href="https://sourceforge.net/projects/glpidashboard/files/?source=navbar" target="_blank">
					 	<i class="fa fa-refresh"></i>                   
                    <span>'. __('New version','dashboard').'</span>
                     <span>'.__('avaliable','dashboard').'</span></a>';		
							}
						}
				  ?>
            </li>                                 
                                
                                                              
            </ul>
            <!-- /.Menu -->
        </div>
        <!-- /.left-sidebar Holder-->

<?php
 
if(file_exists('/etc/hosts')) { 
        
echo '<h5 class="label1 label-default"> <i class="fa fa-info-circle"></i>  Server Info</h5>
	
	<ul class="list-unstyled list-info-sidebar" style="color: #cecece;">
		<li class="data-row">
			<span class="data-name" style="color: #cecece;">OS:</span>
			<span class="data-value">'; include './sh/issue.php'; 
		
echo		'</span>
		</li>

		<li class="data-row">
			<span class="data-name" style="color: #cecece;">UP:</span>
			<span class="data-value">'; include './sh/uptime.php'; 
			
echo		'</span>
		</li>

		<li class="data-row">
			<span class="data-name" style="color: #cecece;">MEM:</span>
			<span class="data-value">'; include './sh/mem.php'; 

echo '<div class="progress" style="height: 5px;">
    		<div class="progress-bar progress-striped '.$corm.' " style="width: '.$umem.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$umem.'" role="progressbar"></div>
		</div>
			</span>		
		</li>

		<li class="data-row">
			<span class="data-name" style="color: #cecece;">DISK:</span>
			<span class="data-value">'; include './sh/df.php'; 

echo '<div class="progress" style="height: 5px;">
    		<div class="progress-bar  progress-striped '.$cord.'" style="width: '.$udisk.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$udisk.'" role="progressbar"></div>
		</div>
			</span>			
		</li>		
		
	</ul>';  

}

?>	                              
    </div>
    <!-- /.left-sidebar -->

    <!-- .right-sidebar
    <div class="user-details user-details-close animated fadeInLeft">
        <div class="right-sidebar-holder">
            <div class="panel-group" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                      Personalize
                  </a>
                  <i class="fa fa-times close-right-user text-danger pull-right"></i>
              </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">
              <div class="panel-body">

                <hr>

                <h5 class="text-center">
                    Theme Options
                    <a  href="#"  class="theme-panel-close text-danger pull-right"><strong><i class="fa fa-times"></i></strong></a>
                </h5>

                <ul class="list-group theme-options">
                    <li class="list-group-item" >   
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="fixedTopSide" value=""> Fixed Top Side
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="miniSidebar" value=""> Mini Sidebar
                            </label>
                        </div>
                        
                    </li>
                    <li class="list-group-item" >Predefined Themes
                        <ul class="list-inline predefined-themes"> 
                            <li><a class="badge" style="background-color:#23bab5" data-color-primary="#23bab5" data-color-secondary="#232b2d" data-color-link="#80969c"> &nbsp; </a></li>
                            <li><a class="badge" style="background-color:#e96363" data-color-primary="#e96363" data-color-secondary="#232b2d" data-color-link="#AFB5AA"> &nbsp; </a></li>
                            <li><a class="badge" style="background-color:#5cb85c" data-color-primary="#5cb85c" data-color-secondary="#232b2d" data-color-link="#777e88"> &nbsp; </a></li>
                            <li><a class="badge" style="background-color:#e97436" data-color-primary="#e97436" data-color-secondary="#232b2d" data-color-link="#80969c"> &nbsp; </a></li>
                            <li><a class="badge" style="background-color:#2FA2D1" data-color-primary="#2FA2D1" data-color-secondary="#232b2d" data-color-link="#A5B1B7"> &nbsp; </a></li>
                            <li><a class="badge" style="background-color:#2f343b" data-color-primary="#2f343b" data-color-secondary="#FFFFFF" data-color-link="#232b2d"> &nbsp; </a></li>
                        </ul>
                    </li>
                </ul>
                <div class="list-group contact-list  animated fadeInBig">
                    <a class="list-group-item  text-primary">Profile <i class="fa fa-user pull-right"></i></a>
                    
                </div>
                <hr>
               
            </div>
        </div>
    </div>    
</div>


</div>
<!-- /.right-sidebar-holder 
</div>  -->
<!-- /.right-sidebar -->


<?php     

$ano = date("Y");
$month = date("Y-m");
$hoje = date("Y-m-d");

//chamados ano

$sql_ano =	"SELECT COUNT(glpi_tickets.id) as total        
      FROM glpi_tickets
      LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
      WHERE glpi_tickets.is_deleted = '0' ";

$result_ano = $DB->query($sql_ano);
$total_ano = $DB->fetch_assoc($result_ano);
      
//chamados mes

$sql_mes =	"SELECT COUNT(glpi_tickets.id) as total        
      FROM glpi_tickets
      LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
      WHERE glpi_tickets.date LIKE '$month%'      
      AND glpi_tickets.is_deleted = '0' ";

$result_mes = $DB->query($sql_mes);
$total_mes = $DB->fetch_assoc($result_mes);

//chamados dia

$sql_hoje =	"SELECT COUNT(glpi_tickets.id) as total        
      FROM glpi_tickets
      LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
      WHERE glpi_tickets.date like '$hoje%'      
      AND glpi_tickets.is_deleted = '0'";

$result_hoje = $DB->query($sql_hoje);
$total_hoje = $DB->fetch_assoc($result_hoje);

// total users

$sql_users = "SELECT COUNT(id) AS total
FROM `glpi_users`
WHERE is_deleted = 0
AND is_active = 1";

$result_users = $DB->query($sql_users);
$total_users = $DB->fetch_assoc($result_users);

?>

<!-- .content -->
<div class="content animated fadeInBig">
    <!-- main-content -->
   <div class="main-content masked-relative masked">
      
						<div class="row" style="margin-left: 3%;">
							<!-- COLUMN 1 -->															
								  <div class="col-sm-3 col-md-3 stat">
									 <div class="dashbox panel panel-default">
										<div class="panel-body">
										   <div class="panel-left red">
												<i class="fa fa-calendar-o fa-3x" style="color:#D9534F;"></i>
										   </div>
										   <div class="panel-right">
										     <div id="odometer1" class="odometer" style="color: #32a0ee; font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo __('Today','dashboard'); ?></b></span>												
										   </div>
										</div>
									 </div>
								  </div>
								  
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox panel panel-default">
										<div class="panel-body">
										   <div class="panel-left blue">
												<i class="fa fa-calendar fa-3x" style="color:#298EE3;"></i>
										   </div>
										   <div class="panel-right">										 
											<div id="odometer2" class="odometer" style="color: #32a0ee; font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo $mes ?></b></span>
										   </div>
										</div>
									 </div>
								  </div>																		
                     								
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox panel panel-default">
										<div class="panel-body">
										   <div class="panel-left red">
												<i class="fa fa-plus-square fa-3x" style="color:#F1B119;"></i>
										   </div>
										   <div class="panel-right">
												<div id="odometer3" class="odometer" style="color: #32a0ee; font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo __('Total','dashboard'); ?></b></span>
										   </div>										   
										</div>
									 </div>
								  </div>
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox panel panel-default">
										<div class="panel-body">
										   <div class="panel-left blue">
												<i class="fa fa-users fa-3x" style="color:#008F3A;"></i>
										   </div>
								   		<div class="panel-right">
												<div id="odometer4" class="odometer" style="color: #32a0ee; font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('users','dashboard'); ?></span><br>
                        				
										   </div>
										</div>
									 </div>
								  </div>																	                          				                           							
						</div>        
                
<div class="container-fluid">            
  
<script type="text/javascript" >
window.odometerOptions = {
   format: '( ddd).dd'
};

setTimeout(function(){
    odometer1.innerHTML = <?php echo $total_hoje['total']; ?>;
    odometer2.innerHTML = <?php echo $total_mes['total']; ?>;
    odometer3.innerHTML = <?php echo $total_ano['total']; ?>;
    odometer4.innerHTML = <?php echo $total_users['total']; ?>;
}, 1000);

</script> 

<div id='content' class="container-fluid" style="margin-left:55px;"> 
 
<!-- <div id="pad-wrapper"> -->

	<div class="row-fluid" style="margin-top: 30px;" >
      <h4> <?php echo __('Tickets Evolution','dashboard'); ?> </h4>
      <p id="choices" style="float:right; width:600px; margin-right: 0px; margin-top: 5px; text-align:right;"></p>	  	

		<div class="demo-container">
			<div id="graflinhas1" class="demo-placeholder col-sm-12 col-md-12" style="float:left;"></div>		
		</div>
	</div>

	   <?php 
			include ("graficos/inc/index/graflinhas_index_sel.inc.php");
		?>					
	
	<div class="row-fluid" style="margin-top: 75px;">
	
		<div class="col-sm-6 col-md-6 knob-wrapper">
			<div id="pie1" style="height:320px;"> 			
				<?php
				include ("graficos/inc/index/grafpie_index.inc.php");
				?> 	 						            
			</div> 
		</div>

		<div class="col-sm-6 col-md-6 knob-wrapper">
			<div id="pie2" style="height:320px;"> 
				<?php
				include ("graficos/inc/index/grafpie_stat_geral.inc.php");
				?> 	 				              
			</div> 
  		</div>  
  
  
  </div>

<div class="row" style="margin-top: 40px;">
	
	<div id="last_tickets" class="col-sm-6 col-md-6" style="margin-top:50px;"> 
 	 				              
		      <div class="widget widget-table action-table striped">
            <div class="widget-header"> <i class="fa fa-list-alt" style="margin-left:7px;"></i>

              <h3><a href="../../../front/ticket.php" target="_blank" style="color: #525252;"><?php echo __('Last Tickets','dashboard'); ?></a></h3>
             
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="height:322px;">
            <?php

		// last tickets
			$status = "('2','1','3','4')"	;	         
                        
            $query_wid = "
            SELECT glpi_tickets.id AS id, glpi_tickets.name AS name
				FROM glpi_tickets
				WHERE glpi_tickets.is_deleted = 0
				AND glpi_tickets.status IN $status
				ORDER BY id DESC
				LIMIT 10 ";
            
            $result_wid = $DB->query($query_wid);			            
            
            ?>    
              <table id="last_tickets" class="table table-hover table-bordered table-condensed" >
              <th style="text-align: center;"><?php echo __('Tickets','dashboard'); ?></th><th style="text-align: center;" ><?php echo __('Title','dashboard'); ?></th>
              
				<?php
					while($row = $DB->fetch_assoc($result_wid)) 
					{					
						echo "<tr><td style='text-align: center;'><a href=../../../front/ticket.form.php?id=".$row['id']." target=_blank style='color: #526273;'>".$row['id']."</a>
						</td><td>". substr($row['name'],0,60)."</td></tr>";											
					}				
				?>                                       
              </table>
              
            </div>
            <!-- /widget-content --> 
          </div>
	</div> 

<!--  open tickets by tech-->

	<div id="open_tickets" class="col-sm-6 col-md-6 " style="margin-top:50px;"> 
	
		<div class="widget widget-table action-table striped">
            <div class="widget-header"> <i class="fa fa-list-alt" style="margin-left:7px;"></i>

           		<h3><a href="../../../front/ticket.php" target="_blank" style="color: #525252;"><?php echo __('Open Tickets','dashboard'). " " .__('by Technician','dashboard') ?></a></h3>
           
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="height:322px;">
            <?php
                                
            $query_op = "
            SELECT DISTINCT glpi_users.id AS id, glpi_users.`firstname` AS name, glpi_users.`realname` AS sname, count(glpi_tickets_users.tickets_id) AS tick
				FROM `glpi_users` , glpi_tickets_users, glpi_tickets
				WHERE glpi_tickets_users.users_id = glpi_users.id
				AND glpi_tickets_users.type = 2
				AND glpi_tickets.is_deleted = 0
				AND glpi_tickets.id = glpi_tickets_users.tickets_id
				AND glpi_tickets.status IN ".$status."
				GROUP BY `glpi_users`.`firstname` ASC
				ORDER BY tick DESC
				LIMIT 10 ";
            
            $result_op = $DB->query($query_op);			            
            
            ?>    
              <table id="open_tickets" class="table table-hover table-bordered table-condensed" >
              <th style="text-align: center;"><?php echo __('Technician','dashboard'); ?></th><th style="text-align: center;">
              <?php echo __('Open Tickets','dashboard'); ?></th>
              
				<?php
					while($row = $DB->fetch_assoc($result_op)) 
					{					
						echo "<tr><td><a href=./reports/rel_tecnico.php?con=1&tec=".$row['id']."&stat=open target=_blank style='color: #526273;'>
						".$row['name']." ".$row['sname']."</a></td><td style='text-align: center;' >".$row['tick']."</td></tr>";											
					}				
				?>                                       
              </table>
              
            </div>
            <!-- /widget-content --> 
          </div>
       </div>   
</div>

<div class="row ">


<div id="events" class="col-sm-6 col-md-6 " style="margin-top:35px;"> 
 	 				              
		      <div class="widget widget-table action-table striped">
            <div class="widget-header"> <i class="fa fa-list-alt" style="margin-left:7px;"></i>

              <h3><a href="../../../front/event.php" target="_blank" style="color: #525252;"><?php echo __('Last Events','dashboard'); ?></a></h3>

            </div>
            <!-- /widget-header -->
            <div class="widget-content">
   
				<?php
				
				$query_evt = "
				SELECT *
				FROM `glpi_events`
				ORDER BY `glpi_events`.id DESC
				LIMIT 10 ";   
					
				$result_evt = $DB->query($query_evt);
				$number = $DB->numrows($result_evt);
				
				function tipo($type) {
				
				    switch ($type) {
				    case "system": $type 	  = __('System'); break;
				    case "ticket": $type 	  = __('Ticket'); break;
				    case "devices": $type 	  = _n('Component', 'Components', 2); break;
				    case "planning": $type 	  = __('Planning'); break;
				    case "reservation": $type = _n('Reservation', 'Reservations', 2); break;
				    case "dropdown": $type 	  = _n('Dropdown', 'Dropdowns', 2); break;
				    case "rules": $type 	  = _n('Rule', 'Rules', 2); break;
				   };
					return $type;
					}
				
				
				function servico($service) {
				
				    switch ($service) {
				    case "inventory": $service 	  = __('Assets'); break;
				    case "tracking": $service 	  = __('Ticket'); break;
				    case "maintain": $service 	  = __('Assistance'); break;
				    case "planning": $service  	  = __('Planning'); break;
				    case "tools": $service 	  	  = __('Tools'); break;
				    case "financial": $service 	  = __('Management'); break;
				    case "login": $service 	         = __('Connection'); break;
				    case "setup": $service 	  	  = __('Setup'); break;
				    case "security": $service 	  = __('Security'); break;
				    case "reservation": $service     = _n('Reservation', 'Reservations', 2); break;
				    case "cron": $service 	  	  = _n('Automatic action', 'Automatic actions', 2); break;
				    case "document": $service 	  = _n('Document', 'Documents', 2); break;
				    case "notification": $service    = _n('Notification', 'Notifications', 2); break;
				    case "plugin": $service 	  = __('Plugin'); break;
				   }
				
					return $service;
					}
					     ?>    
				            <table id="events" class="table table-hover table-bordered table-condensed" >
				            <th style="text-align: center;"><?php echo __('Type'); ?></th>
								<th style="text-align: center;"><?php echo __('Date'); ?></th>
								<!-- <th style="text-align: center;"><?php echo __('Service'); ?></th>  -->
								<th style="text-align: center;"><?php echo __('Message'); ?></th>                 
								<?php
							   $i = 0;	
							   while ($i < $number) {
							   
								  $type     = $DB->result($result_evt, $i, "type");
				       			  $date     = date_create($DB->result($result_evt, $i, "date"));
							        // $service  = $DB->result($result_evt, $i, "service");         
							        
							         $message  = $DB->result($result_evt, $i, "message");
								
								echo "<tr><td style='text-align: left;'>". tipo($type) ."</td>
										<td style='text-align: left;'>" . date_format($date, 'Y-m-d H:i:s') . "</td>					
										<td style='text-align: left;'>". substr($message,0,50) ."</td></tr>
								";
								++$i;													
								}
												
						?>                                       
              </table>  
              
            </div>
            <!-- /widget-content --> 
          </div>
	</div>


	<div id="logged_users" class="col-sm-6 col-md-6 " style="margin-top:35px;"> 
 	 				              
		      <div class="widget widget-table action-table">
            <div class="widget-header"> <i class="fa fa-group" style="margin-left:7px;"></i>
 <?php
//logged users

$path = "../../../files/_sessions/";
$diretorio = opendir($path);        

$arr_arq = array();
$arquivos = array();    
       
   while($arquivo = readdir($diretorio)){   
      
     $arr_arq[] = $path.$arquivo;           
   }
 //  $diretorio -> close();


foreach ($arr_arq as $listar) {
// retira "./" e "../" para que retorne apenas pastas e arquivos

  
   if ( is_file($listar) && $listar != '.' && $listar != '..'){ 
			$arquivos[]=$listar;
   }
}

$conta = count($arquivos);

if($conta > 0) {

for($i=0; $i < $conta; $i++) {

$file = $arquivos[$i];

$string = file_get_contents( $file ); 
// poderia ser um string ao invés de file_get_contents().

$list = preg_match( '/glpiID\|s:[0-9]:"(.+)/', $string, $matches );

$posicao = strpos($matches[0], 'glpiID|s:');
$string2 = substr($matches[0], $posicao, 25);
$string3 = explode("\"", $string2); 

$arr_ids[] = $string3[1];

}
}

$ids = array_values($arr_ids);
$ids2 = implode("','",$ids);

$query_name = 
"SELECT firstname AS name, realname AS sname, id AS uid, name AS glpiname 
FROM glpi_users
WHERE id IN ('".$ids2."')
ORDER BY name"; 

$result_name = $DB->query($query_name); 
$num_users = $DB->numrows($result_name);          
            
?>    
           <h3><?php echo __('Logged Users','dashboard')."  :  " .$num_users; ?></h3>

            </div>
            <!-- /widget-header -->
<?php
	          if($num_users <= 10) {
	          	echo '<div class="widget-content striped" style="min-height:318px;">'; }
	          else {
	          	echo '   <div class="widget-content striped">'; }	          	
?>        
              <table id="logged_users" class="table table-hover table-bordered table-condensed" >
        <!--      <th style="text-align: center;"><?php echo __('','dashboard'); ?></th> -->              
				<?php
								
				while($row_name = $DB->fetch_assoc($result_name)) 
	  			   {
						echo "<tr><td style='text-align: left;'><a href=../../../front/user.form.php?id=".$row_name['uid']." target=_blank style='color: #526273;'>
						".$row_name['name']." ".$row_name['sname']." (".$row_name['uid'].")</a>	</td></tr>";												
					}	

				?>                                       
              </table>
              
            </div>
            <!-- /widget-content --> 
          </div>
	</div>          
          
			
          <!-- content row 2 --> 
	<!-- </div> -->          
          
   </div>   
    
	</div> 
 
<script>
function scrollWin()
{
$('html, body').animate({ scrollTop: 0 }, 'slow');
}
</script>

<span class="go-top" onclick="scrollWin()">
   <i class="fa fa-chevron-up"></i>&nbsp; Top     						
</span>        
        
    </div>    
</div>
<!-- end main-content -->
</div>

</div>
<!-- /.box-holder -->
</div>

<!-- /.site-holder -->

 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

 <!-- Include all compiled plugins (below), or include individual files as needed -->
 <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
 <script src="js/less-1.5.0.min.js"></script>
 
 <script src="js/jquery.storage.js"></script>        
 <script src="js/jquery.accordion.js"></script>
 <script src="js/bootstrap-typeahead.js"></script>                
 <script src="js/bootstrap-progressbar.js"></script>
 <script src="js/galaxy/hovermenu.js" charset="utf-8"></script>
 <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
 <script src="js/jquery.easy-pie-chart.js"></script>

 <script src="js/bootstrap-switch.js"></script>
 <script src="js/jquery.address-1.6.min.js"></script>
 
 <script src="js/highcharts.js" type="text/javascript" ></script>
<script src="js/highcharts-3d.js" type="text/javascript" ></script>
<script src="js/modules/exporting.js" type="text/javascript" ></script>
<script src="js/themes/grid.js" type="text/javascript" ></script>

<!-- knob -->
<script src="js/jquery.knob.js"></script>

<!-- flot charts -->    
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.flot.pie.min.js"></script>
<script src="js/jquery.flot.valuelabels.js"></script>
<script src="js/theme.js"></script>         
<script src="js/jquery.jclock.js"></script>

<!-- odometer -->
<link href="css/odometer.css" rel="stylesheet">
<script src="js/odometer.js"></script>

                <script>
                    $('document').ready(function(){
                        $("[name='my-checkbox']").bootstrapSwitch();
                    });
                </script>

                <!-- Remove below two lines in production --> 
                
                <script src="js/theme-options.js"></script>
                
                <script src="js/core.js"></script> 
            </body>
            </html>
