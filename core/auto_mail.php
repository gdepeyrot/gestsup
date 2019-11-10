<?php
################################################################################
# @Name : /core/auto_mail.php
# @Description : page to send automail
# @Call : ./core/ticket.php
# @Parameters : ticket id 
# @Author : Flox
# @Update : 21/02/2018
# @Version : 3.1.30 p2
################################################################################

//initialize variables 
if(!isset($send)) $send = ''; 

//secure string
$db_id=strip_tags($db->quote($_GET['id']));

//check if mail is already sent
$qry = $db->prepare("SELECT * FROM `tmails` WHERE incident=:id");
$qry->execute(array('id' => $_GET['id']));
$row=$qry->fetch();
$qry->closeCursor();

//check user group defined as sender on ticket 
$qry = $db->prepare("SELECT u_group FROM `tincidents` WHERE tincidents.id=:id");
$qry->execute(array('id' => $_GET['id']));
$mail_u_group=$qry->fetch();
$qry->closeCursor();
if($mail_u_group['u_group']!=0)
{
	
	//check if group members have mail
	$qry = $db->prepare("SELECT `tusers`.mail FROM `tusers`,`tgroups_assoc` WHERE `tusers`.id=`tgroups_assoc`.user and `tgroups_assoc`.group=:group");
	$qry->execute(array('group' => $mail_u_group['u_group']));
	$mail_u_group_members=$qry->fetch();
	$qry->closeCursor();
	if($mail_u_group_members)
	{
		$usermail['mail']=1;
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL SENDER:</b> group detected with mail adresses <br />";}
	} else {
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL SENDER:</b> group detected without mail adresses <br />";}
	}

} else {
	//check if user have mail
	$qry = $db->prepare("SELECT tusers.mail FROM `tusers`,`tincidents` WHERE tincidents.user=tusers.id AND tincidents.id=:id");
	$qry->execute(array('id' => $_GET['id']));
	$usermail=$qry->fetch();
	$qry->closeCursor();
	if($usermail && $rparameters['debug']==1) {echo "<b>AUTO MAIL SENDER:</b> user detected <br />";}
}

//debug
if($rparameters['debug']==1) {echo "<b>AUTO MAIL VAR:</b> SESSION[profile_id]=$_SESSION[profile_id] mail_auto_user_modify=$rparameters[mail_auto_user_modify] _POST[resolution]=$_POST[resolution] _POST[private]=$_POST[private] <br />";}

//case send mail to user where ticket open by technician.
if(($rparameters['mail_auto']==1) && ($row['open']=='') && ($_POST['modify'] || $_POST['quit']) && ($_SESSION['profile_id']!=2 && $_SESSION['profile_id']!=3 && $_SESSION['profile_id']!=1))
{
	if($usermail['mail'] || $rparameters['mail_cc'])
	{
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b> FROM tech TO user (Reason: mail_auto enable, and open detect by technician.) <br />";}
		//auto send open notification mail
		$send=1;
		include('./core/mail.php');
		//insert mail table
		$qry=$db->prepare("INSERT INTO `tmails` (`incident`,`open`,`close`) VALUES (:incident,:open,:close)");
		$qry->execute(array('incident' => $_GET['id'],'open' => 1,'close' => 0));
	} else {
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b> FROM tech TO user (Reason: mail_auto enable, and open detect by technician.) but user have no mail and mail_cc empty message not sent<br />";}
	}
//case send mail to user where ticket close by technician.
} elseif(($rparameters['mail_auto']==1) && ($_POST['state']=='3') && ($_POST['modify'] || $_POST['quit']) && ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==4)) 
{
	if($usermail['mail'] || $rparameters['mail_cc'])
	{
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b> FROM tech TO user (Reason: mail_auto enable, and close detect by technician.)<br />";}
		
		if ($row['open']=='1')
		{
			//check if is the first close mail
			if ($row['close']=='0')
			{
				$send=1;
				//auto send close notification mail
				include('./core/mail.php');
				//update mail table
				$qry=$db->prepare("UPDATE tmails SET close=:close WHERE incident=:incident");
				$qry->execute(array('close' => 1,'incident' => $_GET['id']));
			} else {
				//close mail already sent
			}
		} else {
			//close not sent because no open mail was sent
		}
	} else {
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b> FROM tech TO user (Reason: mail_auto enable, and close detect by technician.) but user have no mail and mail_cc empty message not sent<br />";}
	}
//case send mail to user where technician add thread in ticket.
} elseif (($rparameters['mail_auto_user_modify']==1) && ($_POST['resolution']!='') && ($_POST['resolution']!='\'\'') &&  ($_POST['private']!=1) && ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==4)) 
{
	if($usermail['mail'] || $rparameters['mail_cc'])
	{
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b>  FROM tech TO user (Reason: mail_auto_user_modify enable and technician add thread.<br> ";}
		//check if user is the technician and not user
		if ($globalrow['user']!=$_SESSION['user_id'])
		{
			$send=1;
			include('./core/mail.php');
		}
	} else {
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b>  FROM tech TO user (Reason: mail_auto_user_modify enable and technician add thread) but user have no mail and mail_cc empty, message not sent.<br> ";}
	}
	
//send mail to admin where user open new ticket
} elseif(($rparameters['mail_newticket']==1) && $_POST['send'] && ($_SESSION['profile_id']!=0 && $_SESSION['profile_id']!=4)) 
{
	//debug
	if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b>  FROM user TO tech OR parameter_cc (Reason: mail_newticket enable and user open ticket.<br> ";}
	
	//find user name
	$qry = $db->prepare("SELECT * FROM tusers WHERE id=:id");
	$qry->execute(array('id' => $uid));
	$userrow=$qry->fetch();
	$qry->closeCursor();
	
	//mail parameters
	if($rparameters['mail_from_adr']=='')
	{
		if ($userrow['mail']!='') $from=$userrow['mail']; else $from=$rparameters['mail_cc'];
	} else {
		$from=$rparameters['mail_from_adr'];
	}
	
	$to=$rparameters['mail_newticket_address'];
	$object=T_('Un nouveau ticket à été déclaré par ').$userrow['lastname'].' '.$userrow['firstname'].': '.$_POST['title'];
	$message = '
	'.T_('Le ticket').' n°'.$_GET['id'].' '.T_('à été déclaré par l\'utilisateur').' '.$userrow['lastname'].' '.$userrow['firstname'].'.<br />
	<br />
	<u>'.T_('Objet').':</u><br />
	'.$_POST['title'].'<br />		
	<br />	
	<u>'.T_('Description').':</u><br />
	'.$_POST['text'].'<br />
	<br />
	'.T_('Pour plus d\'informations vous pouvez consulter le ticket sur').' <a href="'.$rparameters['server_url'].'/index.php?page=ticket&id='.$_GET['id'].'">'.$rparameters['server_url'].'/index.php?page=ticket&id='.$_GET['id'].'</a>.
	';
	require('./core/message.php');
//send mail to technician where user add thread in ticket
} elseif (($rparameters['mail_auto_tech_modify']==1) && $_POST['modify'] &&  (($_POST['resolution']!='') && ($_POST['resolution']!='\'\'')) && ($_SESSION['profile_id']!=0 && $_SESSION['profile_id']!=4))
{
	//debug
	if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b>  FROM user TO tech  (Reason: mail_auto_tech_modify enable and user add thread in ticket.)<br> ";}

	//check if current user add this thread
	if ($globalrow['user']==$_SESSION['user_id'])
	{
		//find user name
		$qry = $db->prepare("SELECT * FROM tusers WHERE id=:id");
		$qry->execute(array('id' => $uid));
		$userrow=$qry->fetch();
		$qry->closeCursor();
		
		//get user mail
		if($rparameters['mail_from_adr']=='')
		{
			if ($userrow['mail']!='') $from=$userrow['mail']; else $from=$rparameters['mail_cc'];
		} else {
			$from=$rparameters['mail_from_adr'];
		}
		//get tech mail 
		$qry = $db->prepare("SELECT * FROM tusers WHERE id=:id");
		$qry->execute(array('id' => $globalrow['technician']));
		$techrow=$qry->fetch();
		$qry->closeCursor();
		
		$to=$techrow['mail'];
		//check if tech have mail
		if($to) 
		{
			$object=T_('Votre ticket').' n°'.$_GET['id'].': '.$_POST['title'].' '.T_('à été modifié par').' '.$userrow['lastname'].' '.$userrow['firstname'];
			//remove single quote in post data
			$resolution = str_replace("'", "", $_POST['resolution']);
			$title = str_replace("'", "", $_POST['title']);
			$message = '
			'.T_('Le ticket').' n°'.$_GET['id'].' '.T_('à été modifié par l\'utilisateur').' '.$userrow['lastname'].' '.$userrow['firstname'].'.<br />
			<br />
			<u>'.T_('Objet').':</u><br />
			'.$title.'<br />		
			<br />	
			<u>'.T_('Ajout du commentaire').':</u><br />
			'.$resolution.'<br />
			<br />
			'.T_('Pour plus d\'informations vous pouvez consulter le ticket sur').' <a href="'.$rparameters['server_url'].'/index.php?page=ticket&id='.$_GET['id'].'">'.$rparameters['server_url'].'/index.php?page=ticket&id='.$_GET['id'].'</a>.
			';
			require('./core/message.php');
		} else {if($rparameters['debug']==1) {echo "technician mail is empty or no technician associated with this ticket";}}
	}
}

//case send auto mail to user where user open ticket
if(($rparameters['mail_auto_user_newticket']==1) && ($row['open']=='') && $_GET['action']=='new' && $_POST['send'] && ($_SESSION['profile_id']==2 || $_SESSION['profile_id']==3 || $_SESSION['profile_id']==1))
{
	if($usermail['mail'] || $rparameters['mail_cc'])
	{
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b>  FROM user TO user (Reason: mail_auto_user_newticket enable, and open detect by user.) <br />";}
		//auto send open notification mail
		$send=1;
		include('./core/mail.php');
		//insert mail table
		$qry=$db->prepare("INSERT INTO `tmails` (`incident`,`open`,`close`) VALUES (:incident,:open,:close)");
		$qry->execute(array('incident' => $_GET['id'],'open' => 1,'close' => 0));
	} else {
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b> FROM user TO user (Reason: mail_auto_user_newticket enable, and open detect by user.) but user have no mail and mail_cc empty message not sent<br />";}
	}
} else 

//send mail to user from tech for survey where ticket is in survey parameter state
if(($rparameters['survey']==1) && ($_POST['modify'] || $_POST['quit']) && ($_POST['state']==$rparameters['survey_ticket_state']))
{
	if($usermail['mail'])
	{
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b> FROM tech TO user (Reason: survey enable and technician switch ticket in state $rparameters[survey_ticket_state].)<br> ";}
		//check if survey answer already exist for this ticket
		$qry = $db->prepare("SELECT ticket_id FROM tsurvey_answers WHERE ticket_id=:ticket_id");
		$qry->execute(array('ticket_id' => $_GET['id']));
		$row=$qry->fetch();
		$qry->closeCursor();
		if(!$row)
		{
			//insert a token
			$token=uniqid(); 
			$qry=$db->prepare("INSERT INTO ttoken (token,action,ticket_id) VALUES (:token,:action,:ticket_id)");
			$qry->execute(array(
				'token' => $token,
				'action' => 'survey',
				'ticket_id' => $_GET['id']
				));
			//select sender address
			if($rparameters['mail_from_adr']=='')
			{
				//get tech mail 
				$qry = $db->prepare("SELECT mail FROM tusers WHERE id=(SELECT technician FROM tincidents WHERE id=:id)");
				$qry->execute(array('id' => $_GET['id']));
				$from_adr=$qry->fetch();
				$from_adr=$from_adr['mail'];
				$qry->closeCursor();
				
			} else {
				$from_adr=$rparameters['mail_from_adr'];
			}
			//add ticket link if ticket tag detected
			$rparameters['survey_mail_text']=str_replace("[ticket_link]","<a target=\"_blank\" href=\"$rparameters[server_url]/index.php?page=ticket&id=$_GET[id]\">$_GET[id]</a>", $rparameters['survey_mail_text']);
			
			if($rparameters['mail_from_adr']!='') {$from=$rparameters['mail_from_adr'];} else {$from=$from_adr;}
			$to=$usermail['mail'];
			$object=T_("Sondage concernant votre ticket n°").$_GET['id'];
			$message=$rparameters['survey_mail_text'].'
			<br />
			<a href="'.$rparameters['server_url'].'/survey.php?token='.$token.'">'.T_('Répondre au sondage').'</a>
			';
			require('./core/message.php');
		}
	} else {
		//debug
		if($rparameters['debug']==1) {echo "<b>AUTO MAIL DETECT:</b> FROM tech TO user (Reason: survey enable and technician switch ticket in state $rparameters[survey_ticket_state].) but user have no mail, message not sent.<br> ";}
	}
}
?>