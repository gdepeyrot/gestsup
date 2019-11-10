<?php
################################################################################
# @Name : pie_tickets_tech.php
# @Description : Display Statistics chart 1
# @call : /stat.php
# @parameters : 
# @Author : Flox
# @Create : 15/02/2014
# @Update : 19/04/2017
# @Version : 3.1.20
################################################################################

$values = array();
$xnom = array();
$libchart=T_('Tickets par techniciens');
$unit=T_('tickets');

//total
$query=$db->query("SELECT count(*) FROM tincidents WHERE disable='0'");
$month1=$query->fetch();

$query1 = "
SELECT CONCAT_WS('. ', left(tusers.firstname, 1),  tusers.lastname) as Technicien, count(*) as resolve 
FROM tincidents 
INNER JOIN tusers 
ON (tincidents.technician=tusers.id ) 
WHERE tusers.disable=0 AND
tincidents.technician LIKE '$_POST[tech]' AND
tincidents.type LIKE '$_POST[type]' AND
tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
criticality like '$_POST[criticality]' AND
tincidents.category LIKE '$_POST[category]' AND
tincidents.date_create LIKE '%-$_POST[month]-%' AND
tincidents.date_create LIKE '$_POST[year]-%' AND
tincidents.disable LIKE '0'
GROUP BY tusers.id
ORDER by resolve DESC";

$query=$db->query($query1);
while ($row = $query->fetch()) 
{
	$name=substr($row[0],0,42);
	array_push($values, $row[1]);
	array_push($xnom, $name);
} 	
$container='container2';
include('./stat_pie.php');
echo "<div id=\"$container\"></div>";
if ($rparameters['debug']==1) echo $query1;
?>