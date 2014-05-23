<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;


//chamados mensais

if($interval >= "31") {

$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

 $querym = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
GROUP BY day
ORDER BY day ";
}

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";


$querym = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
GROUP BY day
ORDER BY day ";
}

$resultm = $DB->query($querym) or die('erro');

$arr_grfm = array();
while ($row_result = $DB->fetch_assoc($resultm))		
	{ 
	$v_row_result = $row_result['day_l'];
	$arr_grfm[$v_row_result] = $row_result['nb'];			
	} 
	
$grfm = array_keys($arr_grfm) ;
$quantm = array_values($arr_grfm) ;

$grfm2 = implode("','",$grfm);
$grfm3 = "'$grfm2'";
$quantm2 = implode(',',$quantm);

//array to compare months

$DB->data_seek($resultm, 0);

$arr_month = array();
while ($row_result = $DB->fetch_assoc($resultm))		
	{ 
	$v_row_result = $row_result['day_l'];
	$arr_month[$v_row_result] = 0;			
	} 



$status = "('2','1','3','4')"	;	

//chamados abertos mensais

if($interval >= "31") {

$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

$querya = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets.status IN ". $status ."
GROUP BY day
ORDER BY day ";
}

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";


$querya = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets.status IN ". $status ."
GROUP BY day
ORDER BY day ";
}

$resulta = $DB->query($querya) or die('erro');

$arr_grfa = array();
while ($row_result = $DB->fetch_assoc($resulta))		
	{ 
	$v_row_result = $row_result['day_l'];
	$arr_grfa[$v_row_result] = $row_result['nb'];			
	} 

$arr_open = array_merge($arr_month, $arr_grfa);
	
$grfa = array_keys($arr_open) ;
$quanta = array_values($arr_open) ;

$grfa2 = implode("','",$grfa);
$grfa3 = "'$grfa2'";
$quanta2 = implode(',',$quanta);



// fechados mensais

if($interval >= "31") {

$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

$queryf = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets.status NOT IN ". $status ."
GROUP BY day
ORDER BY day ";
}

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

$queryf = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets.status NOT IN ". $status ."
GROUP BY day
ORDER BY day ";
}

$resultf = $DB->query($queryf) or die('erro');

$arr_grff = array();
while ($row_result = $DB->fetch_assoc($resultf))		
	{ 
	$v_row_result = $row_result['day_l'];
	$arr_grff[$v_row_result] = $row_result['nb'];			
	} 
	
$grff = array_keys($arr_grff) ;
$quantf = array_values($arr_grff) ;

$grff2 = implode("','",$grff);
$grff3 = "'$grff2'";
$quantf2 = implode(',',$quantf);


//satisfaction %     
   	
   		 
if($interval >= "31") {
	
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	

$query_sat = 
"SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%b-%Y' ) AS day_l, COUNT( glpi_tickets.id ) AS nb, DATE_FORMAT( glpi_tickets.date, '%y-%m' ) AS day , 
avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions`
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
GROUP BY day
ORDER BY day"; 

}  

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

$query_sat = 
"SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%b-%d' ) AS day_l, COUNT( glpi_tickets.id ) AS nb, DATE_FORMAT( glpi_tickets.date, '%Y-%m-%d' ) AS day , 
avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions`
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
GROUP BY day
ORDER BY day"; 

}       		 

$result_sat = $DB->query($query_sat) or die('erro');

//array with satisfaction average

$arr_grfsat = array();
while ($row_result1 = $DB->fetch_assoc($result_sat))		
	{ 
	$v_row_result1 = $row_result1['day_l'];
	$arr_grfsat[$v_row_result1] = round(($row_result1['media']/5)*100,1);			
	} 


$arr_sat = array_merge($arr_month, $arr_grfsat);

$grfsat = array_keys($arr_sat) ;
$quantsat = array_values($arr_sat);

$grfsat2 = implode("','",$grfsat);
$grfsat3 = "'$grfsat2'";
$quantsat2 = implode(',',$quantsat);


echo "
<script type='text/javascript'>
$(function () {		
	
        $('#graf_linhas').highcharts({
            chart: {";
            
if(array_sum($quantsat) != 0) {            	
	echo        "type: 'line', \n";
}
else {
	echo        "type: 'areaspline',";
}

echo           "height: 460
                
            },
            title: {
                text: '".__('Tickets','dashboard')."'
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                //floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                adjustChartSize: true
            },
            xAxis: {
                categories: [$grfm3],
                labels: {
                    rotation: -55,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            
            },	
          		 ";

if(array_sum($quantsat) != 0) {
       		 
     echo  "          
            yAxis: [{             
	 						minPadding: 0, 
   	 					maxPadding: 0,         
    						min: 0, 
    						//max:1,
   						showLastLabel:false,
    						//tickInterval:1,            
            
                title: { // Primary yAxis
                    text: '".__('Tickets','dashboard')."'
                }
             },          
          
          		{ // Secondary yAxis
                title: {
                    text: '".__('Satisfaction','dashboard')."',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: '#4572A7'
                    }
                },                       	 	
                opposite: true
            }],
            ";          
            
         } 
         
else {

echo "      yAxis: {             
	 						minPadding: 0, 
   	 					maxPadding: 0,         
    						min: 0, 
    						//max:1,
   						showLastLabel:false,
    						//tickInterval:1,            
            
                title: { // Primary yAxis
                    text: '".__('Tickets','dashboard')."'
                }
             },  ";
      }              
                                            
         echo  "plotOptions: {
                column: {
                    pointPadding: 0.2,
  		              borderWidth: 2,
      	           borderColor: 'white',
         	        shadow:true,           
                	  showInLegend: true
                },
                areaspline: {
                    fillOpacity: 0.5
                }
                }, 
            
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
                          
          series: [";

if(array_sum($quantsat) != 0) {
       		 
          echo  "                    
					{ // satisfacao
                name: '".__('Satisfaction','dashboard')."',
                
                type: 'column',
                yAxis: 1,         
          
          		data: [".$quantsat2."],	
                tooltip: {
                    valueSuffix: ' %'
                },
                    dataLabels: {
                    enabled: true,                    
                    color: '#000099',
                    align: 'center',
                    x: 1,
                    y: 1,  
                    format: '{y} %',                                     
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    },
                    formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '',''); 
                }                	
                },
                
                },";
            }    

echo "
          		 {
                name: '".__('Opened','dashboard')."', 
               
                 dataLabels: {
                    enabled: true,                    
                    color: '#000000',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif',
                        fontWeight: 'bold'
                    },
                    },               
                data: [$quantm2] 
                },
                                             
                {
                name: '".__('Closed','dashboard')."',  
                          
                data: [$quantf2]
                },  

                {
                name: '".__('Late','dashboard')."',
               
                dataLabels: {
                    enabled: true,                    
                    color: '#800000',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif',
                        fontWeight: 'bold'
                    },
                    },   
                data: [$quanta2] 
                }]
        });
    });
  </script>  
";
	 
		?>
