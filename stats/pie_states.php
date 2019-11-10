<?php
################################################################################
# @Name : pie_states.php
# @Description : Display Statistics chart 3
# @call : /stat.php
# @parameters : 
# @Author : Flox
# @Create : 15/02/2014
# @Update : 19/04/2017
# @Version : 3.1.20
################################################################################

$values = array();
$xnom = array();
$query = $db->query("SELECT count(*) FROM tincidents WHERE disable='0'");
$rtotal=$query->fetch();

$libchart=T_('Tickets par états');
$unit=T_('tickets');
$query1 = "
SELECT tstates.name as state, COUNT(*) as nb
FROM tincidents INNER JOIN tstates ON (tincidents.state=tstates.id)
WHERE tincidents.disable LIKE '0' AND
tincidents.technician LIKE '$_POST[tech]' AND
tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
tincidents.type LIKE '$_POST[type]' AND
criticality like '$_POST[criticality]' AND
tincidents.category LIKE '$_POST[category]' AND
tincidents.date_create LIKE '%-$_POST[month]-%' AND
tincidents.date_create LIKE '$_POST[year]-%'
GROUP BY tstates.number
ORDER BY nb
DESC
";
$query=$db->query($query1);
while ($row = $query->fetch()) 
{
	array_push($values, $row[1]);
	array_push($xnom, T_($row['state']));
} 
$container='container3';
include('./stat_pie.php');
echo "<div id=\"$container\" ></div>";
if ($rparameters['debug']==1)echo $query1;
?>