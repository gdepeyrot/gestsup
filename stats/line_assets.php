<?php
################################################################################
# @Name : line_asset.php
# @Description : Display statistics
# @Call : /stat.php
# @Parameters : 
# @Author : Flox
# @Create : 26/01/2016
# @Update : 13/09/2017
# @Version : 3.1.26
################################################################################

$user_id=$_SESSION['user_id'];

//count create asset in period to display in graphic title
$query="SELECT count(tassets.id) FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.type LIKE '$_POST[type]' AND tassets.department LIKE '$_POST[service]' AND tassets.date_install LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0'";
$query=$db->query($query);
$row=$query->fetch();
$count=$row[0];
$query->closeCursor(); 

//count recycled asset in period to display in graphic title
$query="SELECT count(tassets.id) FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.type LIKE '$_POST[type]' AND tassets.department LIKE '$_POST[service]' AND tassets.date_recycle LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0'";
$query=$db->query($query);
$row=$query->fetch();
$count2=$row[0];
$query->closeCursor(); 

//count total asset to display in graphic title
$query="SELECT count(tassets.id) FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.type LIKE '$_POST[type]' AND tassets.department LIKE '$_POST[service]' AND tassets.disable='0'";
$query=$db->query($query);
$row=$query->fetch();
$count4=$row[0];
$query->closeCursor();

//query for year selection
if (($_POST['month'] == '%') && ($_POST['year']!=='%'))
{
    $values1 = array();
    $values2 = array();
    $xnom1 = array();
    $xnom2 = array();
	$libchart=T_("Évolution des équipements installés et recyclés sur").' '.$_POST['year'];	
	$query1=$db->query("SELECT month(tassets.date_install) AS x,count(*) AS y FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.department LIKE '$_POST[service]' AND tassets.type LIKE '$_POST[type]' AND tassets.type LIKE '$_POST[type]' AND tassets.date_install LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0' GROUP BY x ");
	$query2=$db->query("SELECT month(tassets.date_recycle) AS x,count(*) AS y FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.department LIKE '$_POST[service]' AND tassets.type LIKE '$_POST[type]' AND tassets.type LIKE '$_POST[type]' AND tassets.date_recycle NOT LIKE '0000-00-00 00:00:00' AND tassets.date_recycle LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0' GROUP BY x ");
	
	//push data in table
	while($data = $query1->fetch())
	{
		array_push($values1 ,$data['y']);
		array_push($xnom1 ,$data['x']);
	}
	$query1->closeCursor(); 
	while($data = $query2->fetch())
	{
		array_push($values2 ,$data['y']);
		array_push($xnom2 ,$data['x']);
	}
	$query2->closeCursor(); 
}
//query for month selection
else if ($_POST['month']!='%')
{
	
    $values1 = array();
    $values2 = array();
    $xnom1 = array();
    $xnom2 = array();
	$monthm=$_POST['month'];
	if($_POST['year']=='%') {$postyear=T_('de toutes les années');} else {$postyear=$_POST['year'];}
	$libchart=T_('Évolution des équipements installés et recyclés pour le mois de').' '.$mois[$monthm].' '.$postyear;
	$query1=$db->query("SELECT day(tassets.date_install) AS x,count(*) AS y FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.department LIKE '$_POST[service]' AND tassets.type LIKE '$_POST[type]' AND tassets.date_install LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0' GROUP BY x ");
	$query2=$db->query("SELECT day(tassets.date_recycle) AS x,count(*) AS y FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.department LIKE '$_POST[service]' AND tassets.type LIKE '$_POST[type]' AND tassets.date_recycle LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0' GROUP BY x ");

	//push data in table
	while($data = $query1->fetch())
	{
    	array_push($values1 ,$data['y']);
    	array_push($xnom1 ,$jour[$data['x']]);
	}
	$query1->closeCursor(); 
	while($data = $query2->fetch())
	{
    	array_push($values2 ,$data['y']);
    	array_push($xnom2 ,$jour[$data['x']]);
	}
	$query2->closeCursor(); 
}
//query for all years selection
else if ($_POST['year']=='%')
{
    $values1 = array();
    $values2 = array();
    $xnom1 = array();
    $xnom2 = array();
	$libchart=T_('Évolution des équipements installés et recyclés sur toutes les années');
	$query1=$db->query("SELECT YEAR(tassets.date_install) AS x,count(*) AS y FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.department LIKE '$_POST[service]' AND tassets.type LIKE '$_POST[type]' AND tassets.date_install LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0' GROUP BY x ");
	$query2=$db->query("SELECT YEAR(tassets.date_recycle) AS x,count(*) AS y FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tusers.company LIKE '$_POST[company]' AND tassets.technician LIKE '$_POST[tech]' AND tassets.department LIKE '$_POST[service]' AND tassets.type LIKE '$_POST[type]' AND tassets.date_recycle NOT LIKE '0000-00-00' AND tassets.date_recycle LIKE '$_POST[year]-$_POST[month]-%' AND tassets.disable='0' GROUP BY x ");
	// push data in table
	while($data = $query1->fetch())
	{
		array_push($values1 ,$data['y']); array_push($xnom1 ,$data['x']);	
	}	
	$query1->closeCursor(); 
	while($data = $query2->fetch())
	{
		array_push($values2 ,$data['y']); array_push($xnom2 ,$data['x']);
	}
	$query2->closeCursor(); 
}

if ($count!=0 || $count2!=0) 
{
	$liby=T_("Nombre d\'équipements");
	$container="container20";		
	include('./stat_line.php');
	echo '<div id="'.$container.'" style="min-width: 400px; height: 400px; margin: 0 auto"></div>';
}
else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_('Aucun équipement installé ou recyclé dans la plage indiqué').'.</div>';}

//display query on debug mode
if($rparameters['debug']==1)
{
    print_r($values1);echo "<br />";
    for($i=0;$i<sizeof($values1);$i++) 
    { 
		$last=sizeof($values1)-1;
		if ($i!=$last) echo '['.$xnom1[$i].','.$values1[$i].'],'; else echo '['.$xnom1[$i].','.$values1[$i].']';
    } 
    echo "<br />";
    print_r($values2);echo "<br />";
    for($i=0;$i<sizeof($values2);$i++) 
    { 
		$last=sizeof($values2)-1;
		if ($i!=$last) echo '['.$xnom2[$i].','.$values2[$i].'],'; else echo '['.$xnom2[$i].','.$values2[$i].']';
    } 
}
?>	