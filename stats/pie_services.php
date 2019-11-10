<?php
################################################################################
# @Name : pie_services.php
# @Description : Display Statistics of chart 7 
# @call : /stat.php
# @parameters : 
# @Author : Flox
# @Create : 15/02/2014
# @Update : 19/04/2017
# @Version : 3.1.20
################################################################################

//array declaration
$values = array();
$xnom = array();

//display title
$libchart=T_('Répartition du nombre de tickets par services');
$unit=T_('tickets');

//query
$query1 = "
SELECT tservices.name as service, COUNT(*) as nb
FROM tincidents, tservices
WHERE 
tservices.id=tincidents.u_service AND
tincidents.disable='0' AND
tincidents.u_service!='0' AND
tincidents.type LIKE '$_POST[type]' AND
criticality like '$_POST[criticality]' AND
tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
tincidents.category LIKE '$_POST[category]' AND
tincidents.date_create LIKE '%-$_POST[month]-%' AND
tincidents.date_create LIKE '$_POST[year]-%' AND
tincidents.technician LIKE '$_POST[tech]'
GROUP BY tservices.name 
ORDER BY nb
DESC ";
$query = $db->query($query1);
while ($row=$query->fetch()) 
{
	$name=substr($row[0],0,35);
	$name=str_replace("'","\'",$name); 
	array_push($values, $row[1]);
	array_push($xnom, $name);
}
$query->closecursor(); 
$container='container7';
include('./stat_pie.php');
echo "<div id=\"$container\"></div>";
if ($rparameters['debug']==1)echo $query1;
?>