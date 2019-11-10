<?php
################################################################################
# @Name : histo_load.php
# @Description : Display Statistics by categories
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
//count
$qtotal = $db->query("SELECT count(*) FROM tincidents");
$rtotal=$qtotal->fetch();
$libchart=T_('Charge de travail actuelle par technicien');
$query = $db->query("
	SELECT CONCAT_WS('. ', left(tusers.firstname, 1),  tusers.lastname) as Technicien, ROUND((SUM(tincidents.time_hope-tincidents.time))/60) as Charge
	FROM
	tincidents 
	INNER JOIN tusers 
	ON
	(tincidents.technician=tusers.id ) WHERE 
	tusers.disable='0' AND
	tincidents.disable='0' AND
	tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
	tincidents.time_hope-tincidents.time>0 AND
	(tincidents.state='1' OR tincidents.state='2' OR tincidents.state='6')
	GROUP BY tusers.firstname ORDER BY Charge DESC
");
while ($row = $query->fetch()) 
{
	$r=$row[1];
	$name=substr($row[0],0,42);
	array_push($values, $r);
	array_push($xnom, $name);
} 
$container="container5";
include('./stat_histo.php');
echo "<div id=\"$container\" style=\"min-width: 300px; height: 400px; margin: 0 auto\"></div>";
?>