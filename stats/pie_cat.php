<?php
################################################################################
# @Name : pie_cat.php
# @Description : Display Statistics of chart 2 number of tickets by categories
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
$libchart=T_('Répartition du nombre de tickets par catégories');
if ($_POST['category']!="%")
{
	$query1 = "
	SELECT tsubcat.name as cat, COUNT(*) as nb
	FROM tincidents INNER JOIN tsubcat ON (tincidents.subcat=tsubcat.id)
	WHERE 
	tincidents.category LIKE '$_POST[category]' AND
	tincidents.disable='0' AND
	tincidents.type LIKE '$_POST[type]' AND
	tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
	criticality like '$_POST[criticality]' AND
	tincidents.date_create LIKE '%-$_POST[month]-%' AND
	tincidents.date_create LIKE '$_POST[year]-%' AND
	tincidents.technician LIKE '$_POST[tech]'
	GROUP BY tsubcat.name 
	ORDER BY nb
	DESC limit 0,10
	";
}
else 
{
	$query1 = "
		SELECT tcategory.name as cat, COUNT(*) as nb
		FROM tincidents INNER JOIN tcategory ON (tincidents.category=tcategory.id)
		WHERE 
		tincidents.disable='0' AND
    	tincidents.type LIKE '$_POST[type]' AND
		tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
    	criticality like '$_POST[criticality]' AND
    	tincidents.date_create LIKE '%-$_POST[month]-%' AND
    	tincidents.date_create LIKE '$_POST[year]-%' AND
    	tincidents.technician LIKE '$_POST[tech]'
		GROUP BY tcategory.name 
		ORDER BY nb
		DESC limit 0,10";
}
$query=$db->query($query1);
while ($row = $query->fetch()) 
{
	$name=substr($row[0],0,35);
	$name=str_replace("'","\'",$name); 
	array_push($values, $row[1]);
	array_push($xnom, $name);
} 
$container='container4';
include('./stat_pie.php');
echo "<div id=\"$container\"></div>"; 
if ($rparameters['debug']==1)echo $query1;
?>