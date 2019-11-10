<?php
################################################################################
# @Name : pie_companys.php
# @Description : Display Statistics of chart 8 
# @call : /stat.php
# @parameters : 
# @Author : Flox
# @Create : 08/03/2014
# @Update : 19/09/2017
# @Version : 3.1.20
################################################################################

//array declaration
$values = array();
$xnom = array();

//display title
$libchart=T_('Répartition du nombre de tickets par sociétés');
$unit=T_('tickets');

//query
$query1 = "
SELECT tcompany.name AS company, COUNT(*) AS nb
FROM tincidents, tcompany, tusers
WHERE 
tcompany.id=tusers.company AND
tusers.id=tincidents.user AND
tincidents.disable='0' AND
tusers.company!='0' AND
tincidents.type LIKE '$_POST[type]' AND
tincidents.criticality like '$_POST[criticality]' AND
tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
tincidents.category LIKE '$_POST[category]' AND
tincidents.date_create LIKE '%-$_POST[month]-%' AND
tincidents.date_create LIKE '$_POST[year]-%' AND
tincidents.technician LIKE '$_POST[tech]'
GROUP BY tcompany.name 
ORDER BY nb
DESC ";

if ($rparameters['debug']==1) {echo $query1;}
$query = $db->query($query1);
while ($row=$query->fetch()) 
{
	$name=substr($row[0],0,35);
	$name=str_replace("'","\'",$name); 
	array_push($values, $row[1]);
	array_push($xnom, $name);
} 
$query->closecursor();
$container='container8';
include('./stat_pie.php');
echo "<div id=\"$container\"></div>";
if ($rparameters['debug']==1)echo $query1;
?>