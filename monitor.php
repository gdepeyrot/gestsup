<?php
################################################################################
# @Name : monitor.php
# @Description : display new ticket current ticket for monitoring screen
# @Call : /stat.php
# @Parameters : 
# @Author : Flox
# @Create : 15/02/2014
# @Update : 27/01/2018
# @Version : 3.1.30
################################################################################

//initialize variables 
if(!isset($_GET['user_id'])) $_GET['user_id'] = ''; 
if(!isset($_GET['key'])) $_GET['key'] = ''; 

//connexion script with database parameters
require "connect.php";

//get userid to find language
if(!$_GET['user_id']) {$_GET['user_id']=1;}
$_SESSION['user_id']=$_GET['user_id'];

//get key
$query=$db->query("SELECT server_private_key FROM `tparameters`");
$key=$query->fetch();
$query->closeCursor(); 

if($_GET['key']==$key['server_private_key'])
{
	//load user table
	$quser=$db->query("SELECT * FROM tusers WHERE id=$_SESSION[user_id]");
	$ruser=$quser->fetch();
	$quser->closeCursor(); 

	//define current language
	require "localization.php";

	//switch SQL MODE to allow empty values with lastest version of MySQL
	$db->exec('SET sql_mode = ""');

	//get current date
	$daydate=date('Y-m-d');

	//query today open ticket
	$query=$db->query("SELECT COUNT(*) FROM `tincidents` WHERE date_create LIKE '$daydate%' AND disable='0'");
	$nbday=$query->fetch();
	$query->closeCursor(); 

	//query new ticket not associate to technician
	$query=$db->query("SELECT COUNT(*) FROM `tincidents` WHERE technician='0' and disable='0'");
	$cnt5=$query->fetch();
	$query->closeCursor(); 

	//query today resolve ticket
	$query=$db->query("SELECT COUNT(*) FROM `tincidents` WHERE date_res LIKE '$daydate%' AND state='3' AND disable='0'");
	$nbdayres=$query->fetch();
	$query->closeCursor(); 

	//query all open ticket 
	$query=$db->query("SELECT COUNT(*) FROM `tincidents` WHERE (state='5' OR state='1' OR state='2' OR state='6') AND disable='0'");
	$nbopen=$query->fetch();
	$query->closeCursor();

	//query all to do ticket
	$query=$db->query("SELECT COUNT(*) FROM `tincidents` WHERE (state='1' OR state='2' OR state='6') AND disable='0'");
	$nbtodo=$query->fetch();
	$query->closeCursor();

	//query all critical ticket
	$query=$db->query("SELECT COUNT(*) FROM `tincidents` WHERE (state='5' OR state='1' OR state='2' OR state='6') AND criticality=(SELECT id FROM tcriticality WHERE name='Critique') AND disable='0'");
	$nbcritical=$query->fetch();
	$query->closeCursor();

	//query ticket wait user state
	$query=$db->query("SELECT COUNT(*) FROM `tincidents` WHERE state='6' AND disable='0'");
	$nbwaituser=$query->fetch();
	$query->closeCursor();

	session_start();
	//initialize variables
	if(!isset($_SESSION['current_ticket'])) $_SESSION['current_ticket'] = '';

	//launch audio notification for new ticket
	if($_SESSION['current_ticket']<$cnt5[0]) {echo'<audio hidden="false" autoplay="true" src="./sounds/notify.ogg" controls="controls"></audio>';}

	//update current counter
	if($_SESSION['current_ticket']!=$cnt5[0]) {$_SESSION['current_ticket']=$cnt5[0];}

	echo '
	<!DOCTYPE html>
	<html lang="fr">
		<head>
			<?php header(\'x-ua-compatible: ie=edge\'); //disable ie compatibility mode ?>
			<meta charset="UTF-8" />
			<title>GestSup | '.T_('Gestion de Support').'</title>
			<link rel="shortcut icon" type="image/png" href="./images/favicon_ticket.png" />
			<meta name="description" content="gestsup" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<link href="./template/assets/css/bootstrap.min.css" rel="stylesheet" />
			<link rel="stylesheet" href="./template/assets/css/font-awesome.min.css" />
			<link rel="stylesheet" href="./template/assets/css/ace-fonts.css" />
			<link rel="stylesheet" href="./template/assets/css/jquery-ui-1.10.3.full.min.css" />
			<link rel="stylesheet" href="./template/assets/css/ace.min.css" />
			<link rel="stylesheet" href="./template/assets/css/ace-rtl.min.css" />
			<link rel="stylesheet" href="./template/assets/css/ace-skins.min.css" />
			<script src="./template/assets/js/ace-extra.min.js"></script>
			<meta http-equiv="Refresh" content="60">
		</head>
		<body>
			';
			//generate color
			if($cnt5[0]>0) $color='danger'; else $color='success';
			
			//add pluriel
			if($cnt5[0]>1) $new=T_('Nouveaux'); else $new=T_('Nouveau');
			if($cnt5[0]>1) $ticket=T_('tickets'); else $ticket=T_('ticket');
			if($nbday[0]>1) $open=T_('Ouverts'); else $open=T_('Ouvert');
			if($nbdayres[0]>1) $res=T_('Résolus'); else $res=T_('Résolu');
			echo '
			<a href="#" class="btn btn-<?php echo $color; ?> btn-app radius-4">
				'.$new.'<br />'.$ticket.' <br /><br />
				<i class="icon-ticket bigger-230"><br /><br />'.$cnt5[0].'</i>
				<br />
			</a>
			';
			
			if($nbcritical[0]>0)
			{
				echo '
				<a href="#" class="btn btn-warning btn-app radius-4">
					'.T_('Ouverts').'<br />'.T_('critique').'
					<br /><br />
					<i class="icon-warning-sign bigger-230"><br /><br />'.$nbcritical[0].'</i>
					<br />
				</a>
				';
			}
			echo '
			<a href="#" class="btn btn-primary btn-app radius-4">
				'.$open.'<br />'.T_('du jour').'
				<br /><br />
				<i class="icon-calendar bigger-230"><br /><br />'.$nbday[0].'</i>
				<br />
			</a>
			<a href="#" class="btn btn-purple btn-app radius-4">
				'.$res.'<br />'.T_('du jour').'
				<br /><br />
				<i class="icon-calendar bigger-230"><br /><br />'.$nbdayres[0].'</i>
				<br />
			</a>
			<a href="#" class="btn btn-grey btn-app radius-4">
				'.T_('Tous les <br />ouverts').'
				<br /><br />
				<i class="icon-plus bigger-230"><br /><br />'.$nbopen[0].'</i>
				<br />
			</a>
			<a href="#" class="btn btn-info btn-app radius-4">
				'.T_('Tickets').' <br />'.T_('à traiter').'
				<br /><br />
				<i class="icon-check bigger-230"><br /><br />'.$nbtodo[0].'</i>
				<br />
			</a>
			<a href="#" class="btn btn-pink btn-app radius-4">
				'.T_('Attente').'<br />'.T_('retour').'
				<br /><br />
				<i class="icon-reply bigger-230"><br /><br />'.$nbwaituser[0].'</i>
				<br />
			</a>
		</body>
	</html>
	';
} else {
	echo '<br /><br /><center><div style="color:red;">Cette page à été déplacé utiliser le lien présent dans Administration > Paramètres > Général "Ecran de supervision"</div></center>';
}

//close database access
$db = null;
?>