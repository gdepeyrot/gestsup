<?php
################################################################################
# @Name : ticket.php 
# @Description : page to edit ticket
# @Call : /ticket.php
# @Author : Flox
# @Version : 3.1.28
# @Create : 09/02/2014
# @Update : 04/12/2017
################################################################################

//initialize variables 
if(!isset($_GET['token'])) $_GET['token'] = ''; 

//connexion script with database parameters
require "connect.php";

//switch SQL MODE to allow empty values with lastest version of MySQL
$db->exec('SET sql_mode = ""');

//get userid to find language
$_SESSION['user_id']=$_GET['user_id'];

$db_id=strip_tags($db->quote($_GET['id']));
$db_session_user_id=strip_tags($db->quote($_GET['user_id']));

//load user table
$quser=$db->query("SELECT * FROM tusers WHERE id=$db_session_user_id");
$ruser=$quser->fetch();
$quser->closeCursor(); 

//define current language
require "localization.php";

//initialize variables 
if(!isset($userreg)) $userreg = ''; 
if(!isset($u_group)) $u_group = ''; 
if(!isset($globalrow['u_group'])) $globalrow['u_group'] = ''; 
if(!isset($_POST['user'])) $_POST['user'] = ''; 
if(!isset($_POST['technician'])) $_POST['technician'] = ''; 

//master query
$globalquery = $db->query("SELECT * FROM tincidents WHERE id LIKE $db_id");
$globalrow=$globalquery->fetch(); 
$globalquery->closeCursor();

//get last token
$query = $db->query("SELECT token FROM `ttoken` WHERE action='ticket_print' ORDER BY id DESC LIMIT 1");
$token=$query->fetch(); 
$query->closeCursor();

//delete token
$query = $db->query("DELETE FROM `ttoken` WHERE action='ticket_print'");

//secure connect user or admin or tech
if ($_GET['token'] && $token['token']==$_GET['token'])
{
	echo '
	<!DOCTYPE html>
	<html lang="fr">
		<head>
			
		</head>
		<body onload="window.print()"; > 
	';
	echo T_('Impression du ticket').' n°'.$_GET['id'].':  '.$globalrow['title'].'';
	?>
	<br /><br />
	<u><?php echo T_('Demandeur'); ?>:</u>
	<?php
		if (($globalrow['u_group']==0 && $u_group=='') || $_POST['user']!="")
		{
			if ($_POST['user'])	{$user=$_POST['user'];}	else {$user=$globalrow['user'];}
			$query=$db->query("SELECT * FROM tusers WHERE id LIKE $user");
			$row=$query->fetch();
			$query->closeCursor(); 
			echo "$row[lastname] $row[firstname]";
		} else {
			if (($globalrow['u_group']!=$u_group) && $u_group!=''){$group=$u_group;}else {$group=$globalrow['u_group'];}
			$query=$db->query("SELECT * FROM `tgroups` WHERE id=$group");
			$row=$query->fetch();
			$query->closeCursor(); 
			echo "[G] $row[name]";
		}
	?>
	<br />
	<u><?php echo T_('Technicien'); ?>:</u>
	<?php
	//selected value
	if ($globalrow['t_group']!=0)
	{
		$query=$db->query("SELECT * FROM `tgroups` WHERE id=$globalrow[t_group]");
		$row=$query->fetch();
		$query->closeCursor(); 
		echo "[G] $row[name]";
	} else {
		if ($_POST['technician'])
		{
			$query=$db->query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$_POST[technician]' ");
		} else {
			$query=$db->query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$globalrow[technician]' ");		
		}
		$row=$query->fetch();
		$query->closeCursor(); 
		echo "$row[lastname] $row[firstname]";
	}
	?>
	<br />
	<u><?php echo T_('Catégorie'); ?>:</u>
	<?php
		$query=$db->query("SELECT * FROM `tcategory` WHERE id=$globalrow[category] ");
		$row=$query->fetch();
		$query->closeCursor(); 
		echo "$row[name]";	
		$query=$db->query("SELECT * FROM `tsubcat` WHERE id=$globalrow[subcat] ");
		$row=$query->fetch();
		$query->closeCursor(); 
		echo " - $row[name]";	
	?>		
	<br />
	<u><?php echo T_('Titre'); ?>:</u>
	<?php echo $globalrow['title']; ?>
	<br />
	<u><?php echo T_('Description'); ?>:</u><br />
	<?php echo $globalrow['description']; ?>
	<br />
	<u><?php echo T_('Résolution'); ?>:</u><br />
	<?php
		$query = $db->query("SELECT * FROM tthreads WHERE ticket=$db_id and type='0' ORDER BY date");
		while ($row = $query->fetch())
		{
			echo "- $row[text]<br />";
		}
		$query->closeCursor(); 

		if ($globalrow['date_res']!='0000-00-00 00:00:00')
		{
			echo '<u>Date de résolution:</u><br />'.$globalrow['date_res'].'';
		}
		
	echo '
			</body>
	</html>';
} else {
	echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès à cette page. Contacter votre administrateur').'.<br></div>';
}
$db = null;
?>