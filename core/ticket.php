<?php
################################################################################
# @Name : ./core/ticket.php 
# @Description : actions page for tickets
# @Call : ./ticket.php
# @Author : Flox
# @Create : 28/10/2013
# @Update : 16/02/2018
# @Version : 3.1.30
################################################################################

//initialize variable
if(!isset($_POST['close'])) $_POST['close'] = '';
if(!isset($_POST['text'])) $_POST['text'] = '';
if(!isset($_POST['send'])) $_POST['send'] = '';
if(!isset($_POST['action'])) $_POST['action'] = '';
if(!isset($_POST['edituser'])) $_POST['edituser'] = '';
if(!isset($_POST['editcat'])) $_POST['editcat'] = '';
if(!isset($_POST['start_availability'])) $_POST['start_availability'] = '';
if(!isset($_POST['end_availability'])) $_POST['end_availability'] = '';
if(!isset($_POST['availability_planned'])) $_POST['availability_planned'] = '';
if(!isset($_POST['u_agency'])) $_POST['u_agency'] = '';

$db_action=strip_tags($db->quote($_GET['action']));

if(!isset($start_availability)) $start_availability = '';
if(!isset($end_availability)) $end_availability = '';
if(!isset($error)) $error="0";

//display user modalbox
if($_GET['action']=='adduser' || $_GET['action']=='edituser') include('./ticket_useradd.php');
//display category modalbox
if($_GET['action']=='addcat' || $_GET['action']=='editcat') include('./ticket_catadd.php');
//display template modalbox
if($_GET['action']=='template') include('./ticket_template.php');

//find incident number for new ticket
if($_GET['action']=='new')
{
	$query=$db->query("SELECT MAX(id) FROM tincidents");
	$row=$query->fetch();
	$query->closeCursor(); 
	$_GET['id'] =$row[0]+1;
	$db_id=$row[0]+1;
}

//action delete ticket
if (($_GET['action']=="delete") && ($rright['ticket_delete']!=0))
{
	//disable ticket
	$db->exec('UPDATE tincidents SET disable=1 WHERE id='.$db_id.'');
	//display delete message
	echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i>	'.T_('Ticket supprimé').'.</center></div>';
	//redirect
	echo "<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php?page=dashboard&state=$_GET[state]&userid=$_GET[userid]'
				}
				setTimeout('redirect()',$rparameters[time_display_msg]);
				-->
		</SCRIPT>";
}

//action to lock thread
if ($_GET['lock_thread'] && $rright['ticket_thread_private']!=0) 
{
	$db->exec('UPDATE tthreads SET private=1 WHERE id='.$db_lock_thread.'');
}

//action to unlock thread
if ($_GET['unlock_thread'] && $rright['ticket_thread_private']!=0) 
{
	$db->exec('UPDATE tthreads SET private=0 WHERE id='.$db_unlock_thread.'');
}

//master query
$globalquery = $db->query("SELECT * FROM tincidents WHERE id=$db_id");
$globalrow=$globalquery->fetch();
$query->closeCursor();

//user group detection switch values
if(substr($_POST['user'], 0, 1) =='G') 
{
 	$u_group=explode("_", $_POST['user']);
	$u_group=$u_group[1];
	$_POST['user']='';
} elseif ($globalrow['u_group']!=0 && $_POST['user']=='')
{
	$u_group=$globalrow['u_group'];
	$_POST['user']='';
}
//technician group detection switch values
if(substr($_POST['technician'], 0, 1) =='G') 
{
 	$t_group=explode("_", $_POST['technician']);
	$t_group=$t_group[1];
	$_POST['technician']='';
} elseif ($globalrow['t_group']!=0 && $_POST['technician']=='')
{
	$t_group=$globalrow['t_group'];
	$_POST['technician']='';
} 

//database inputs if submit
if($rparameters['debug']==1){ echo "<b><u>DEBUG MODE:</u></b><br /> <b>VAR:</b> save=$save post_modify=$_POST[modify] post_quit=$_POST[quit] post_mail=$_POST[mail] post_upload=$_POST[upload] post_send=$_POST[send] post_action=$_POST[action] get_action=$db_action post_category=$_POST[category] post_subcat=$_POST[subcat] post_technician=$_POST[technician] globalrow_technician=$globalrow[technician] post_u_service=$_POST[u_service] globalrow_u_service=$globalrow[u_service] post_u_agency=$_POST[u_agency] globalrow_u_agency=$globalrow[u_agency] post_asset_id=$_POST[asset_id] globalrow[asset_id]=$globalrow[asset_id] post_sender_service=$_POST[sender_service] globalrow_sender_service=$globalrow[sender_service] post_priority=$_POST[priority]<br />";}
if($_POST['modify']||$_POST['quit']||$_POST['mail']||$_POST['upload']||$save=="1"||$_POST['send']||$_POST['action']) 
{
	//check mandatory fields
	if(($rright['ticket_date_hope_mandatory']!=0) && ($_POST['date_hope']=='') && ($_POST['technician']==$_SESSION['user_id'])) {$error=T_('Merci de renseigner la date de résolution estimé');}
    if(($rright['ticket_priority_mandatory']!=0) && ($_POST['priority']=='')) {$error=T_('Merci de renseigner la priorité');}
    if(($rright['ticket_criticality_mandatory']!=0) && ($_POST['criticality']=='')) {$error=T_('Merci de renseigner la criticité');}
	if(($rright['ticket_description_mandatory']!=0) && (ctype_space($_POST['text']) || $_POST['text']=='' || ctype_space(strip_tags($_POST['text']))==1 ) || strip_tags($_POST['text'])=='') {$error=T_('Merci de renseigner la description de ce ticket');} 
    if(($rright['ticket_asset_mandatory']!=0) && ($rparameters['asset']==1) && ($_POST['asset_id']==0)) {$error=T_("Merci de renseigner l'équipement");}
	if(($rright['ticket_title_mandatory']!=0) && ($_POST['title']=='')) {$error=T_('Merci de renseigner le titre de ce ticket');}
    if(($rright['ticket_agency_mandatory']!=0) && ($rparameters['user_agency']==1) && ($_POST['u_agency']==0)) {
		//check if current user have multiple agencies to display empty mandatory alert
		$query2=$db->query("SELECT count(*) FROM `tusers_agencies` WHERE user_id='$_SESSION[user_id]'");
		$row2=$query2->fetch();
		$query2->closeCursor();
		if ($row2[0]>1 && $_SESSION['profile_id']!=4) {$error=T_("Merci de renseigner l'agence");}
	}
	if(($rright['ticket_tech_mandatory']!=0) && ($_POST['technician']=='0')) {$error=T_('Merci de renseigner le technicien associé à ce ticket');}
	if(($rright['ticket_service_mandatory']!=0) && ($_POST['u_service']==0)) {$error=T_('Merci de renseigner le service');}
	//check user ticket limit 
	if ($rparameters['user_limit_ticket']==1 && $ruser['limit_ticket_number']!=0 && $ruser['limit_ticket_days']!=0 && $ruser['limit_ticket_date_start']!='0000-00-00' &&($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2))
	{
		//generate date start and date end
		$date_start=$ruser['limit_ticket_date_start'];
		
		//calculate end date	
		$date_start_conv = date_create($ruser['limit_ticket_date_start']);
		date_add($date_start_conv, date_interval_create_from_date_string("$ruser[limit_ticket_days] days"));
		$date_end=date_format($date_start_conv, 'Y-m-d');
	
		//count number of ticket remaining in period
		$query=$db->query("SELECT count(*) FROM tincidents WHERE user='$_SESSION[user_id]' AND date_create BETWEEN '$date_start' AND '$date_end' AND disable='0' AND disable='0'");
		$nbticketused=$query->fetch();
		$query->closeCursor();
		
		//check number of tickets in current range date
		if (date('Y-m-d')>$date_end || date('Y-m-d')<$date_start)
		{
			$nbticketremaining=0;
		} else {
			$nbticketremaining=$ruser['limit_ticket_number']-$nbticketused[0];
		}
		
		if($nbticketremaining<=0) {$error=T_('Votre limite de ticket est atteinte, prenez contact avec votre administrateur pour créditer votre compte').'.';}
	}
	//check company limit ticket
	if ($rparameters['company_limit_ticket']==1 &&($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2))
	{
		//get company limit ticket parameters
		$query=$db->query("SELECT * FROM tcompany WHERE id=$ruser[company]");
		$rcompany=$query->fetch();
		$query->closeCursor();
		if ($rcompany['limit_ticket_number']!=0 && $rcompany['limit_ticket_days']!=0 && $rcompany['limit_ticket_date_start']!='0000-00-00' )
		{
			//generate date start and date end
			$date_start=$rcompany['limit_ticket_date_start'];
			
			//calculate end date	
			$date_start_conv = date_create($rcompany['limit_ticket_date_start']);
			date_add($date_start_conv, date_interval_create_from_date_string("$rcompany[limit_ticket_days] days"));
			$date_end=date_format($date_start_conv, 'Y-m-d');
		
			//count number of ticket remaining in period
			$query=$db->query("SELECT count(*) FROM tincidents,tusers WHERE tusers.id=tincidents.user AND tusers.company='$rcompany[id]' AND date_create BETWEEN '$date_start' AND '$date_end' AND tincidents.disable='0'");
			$nbticketused=$query->fetch();
			$query->closeCursor();
			
			//check number of tickets in current range date
			if (date('Y-m-d')>$date_end || date('Y-m-d')<$date_start)
			{
				$nbticketremaining=0;
			} else {
				$nbticketremaining=$rcompany['limit_ticket_number']-$nbticketused[0];
			}
			
			if($nbticketremaining<=0) {$error=T_('La limite de ticket attribué pour votre société est atteinte, prenez contact avec votre administrateur pour créditer votre compte.');}
		}
	}
	
	//escape special char and secure string before database insert
	$_POST['description']=$db->quote($_POST['text']);
	$_POST['resolution']=$db->quote($_POST['text2']);
	if($error=='0') {$_POST['title']=strip_tags($db->quote($_POST['title']));}

	//remove <br><br><br> generate to space display 
	$_POST['description']=str_replace("<br><br><br>","","$_POST[description]");
	$_POST['resolution']=str_replace("<br><br><br>","","$_POST[resolution]");
	
	//merge hour and date from availability part
	if ($_POST['start_availability_d'])
	{
	    $start_availability = DateTime::createFromFormat('d/m/Y', $_POST['start_availability_d']);
	    $start_availability = $start_availability->format('Y-m-d');
	    $start_availability="$start_availability $_POST[start_availability_h]";
	    $end_availability = DateTime::createFromFormat('d/m/Y', $_POST['end_availability_d']);
	    $end_availability = $end_availability->format('Y-m-d');
	    $end_availability="$end_availability $_POST[end_availability_h]";
	}
	
	//thread generation when no error detected
	if($error=='0')
	{
		////Generate Thread to technician and technician group transfert
		//detect tech group change to group
		if ($t_group!=$globalrow['t_group'] && $globalrow['technician']==0 && $t_group!='' && $globalrow['t_group']!=0 ) {
			$db->exec("INSERT INTO tthreads (ticket,date,author,text,type,group1,group2) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', '',2,'$globalrow[t_group]','$t_group')");
		}
		//detect tech change to tech
		if ($_POST['technician']!=$globalrow['technician'] && $globalrow['technician']!=0 && $_POST['technician']!='') {
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,text,type,tech1,tech2) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', '',2,'$globalrow[technician]','$_POST[technician]')");
		}
		//detect techgroup change to tech
		if ($globalrow['t_group']!=0 && $_POST['technician']) {
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,text,type,group1,tech2) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', '',2,'$globalrow[t_group]','$_POST[technician]')");
		}
		//detect tech change to techgroup
		if ($globalrow['technician']!=0 && $t_group) {
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,text,type,tech1,group2) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', '',2,'$globalrow[technician]','$t_group')");
		}
		////Generate Thread to technician and technician group attribution
		//detect technician attribution
		if ($globalrow['technician']==0 && $_POST['technician']!='' && $_POST['technician']!='0' && $globalrow['t_group']==0 && $globalrow['creator']!=$_SESSION['user_id'])
		{
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,tech1) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 1, '$_POST[technician]')");
		}
		//detect group attribution
		if ($globalrow['t_group']==0 && $t_group!='' && $globalrow['technician']==0)
		{
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,group1) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 1, '$t_group')");
		}
		//generate thread for switch state 
		if ($globalrow['state']!=$_POST['state'] && $_POST['state']!=3 && $_POST['technician']!='')
		{
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,state) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 5, '$_POST[state]')");
		}
		
		//auto modify state from 5 to 1 if technician change (not attribute to wait tech)
		if ($globalrow['technician']==0 && $_POST['technician']!=0 && $globalrow['state']=='5' && $_POST['state']==$globalrow['state'] && $t_group=='') 
		{
			if($rparameters['debug']==1) {echo "<b>AUTO CHANGE STATE:</b> from 5 to 1 reason technician change detected (globalrow[state]=$globalrow[state] POST[state]=$_POST[state])<br />";}
			$_POST['state']='1';
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,state) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 5, '$_POST[state]')");
		}
		
		//auto modify state from 5 to 1 if technician group change (not attribute to wait tech)
		if ($globalrow['t_group']==0 && $globalrow['state']=='5' && $_POST['state']==$globalrow['state'] && $t_group!='') 
		{
			if($rparameters['debug']==1) {echo "<b>AUTO CHANGE STATE:</b> from 5 to 1 reason technician group change detected (globalrow[state]=$globalrow[state] POST[state]=$_POST[state] t_group=$t_group)<br />";}
			$_POST['state']='1';
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,state) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 5, '$_POST[state]')");
		}
		
		//auto modify state from 5 to 2 if technician add resolution thread (wait tech to current)
		if ((($_POST['resolution']!='') && ($_POST['resolution']!='\'\'')) && ($globalrow['technician']==$_SESSION['user_id']) && ($_POST['state']=='1')) 
		{
			if($rparameters['debug']==1) {echo "<b>AUTO CHANGE STATE</b> from 5 to 2 reason technician add resolution thread detected<br />";}
			$_POST['state']='2';
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,state) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 5, '$_POST[state]')");
		}
		
		//auto modify state from 5 to 2 if technician add resolution thread on new ticket(wait tech to current)
		if (($_POST['resolution']!='') && ($_POST['resolution']!='\'\'') && ($_POST['technician']==$_SESSION['user_id']) && ($_POST['state']=='1') && ($_GET['action']=='new')) 
		{
			if($rparameters['debug']==1) {echo "<b>AUTO CHANGE STATE</b> from 5 to 2 reason technician add resolution thread on new ticket detected<br />";}
			$_POST['state']='2';
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,state) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 5, '$_POST[state]')");
		}
		
		//auto modify state to default state from parameters if tech is null (attribution state)
		if ($_POST['technician']=='' && $t_group=='') 
		{
			if($rparameters['debug']==1) {echo "<b>AUTO CHANGE STATE</b> to default state $rparameters[ticket_default_state] reason no technician or technician group associated with ticket<br />";}
			$_POST['state']=$rparameters['ticket_default_state'];
			$query = $db->exec("INSERT INTO tthreads (ticket,date,author,type,state) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', '$rparameters[ticket_default_state]', '$_POST[state]')");
		}
	}
	
	//insert resolution date if state is change to resolve (3)
	if ($_POST['state']=='3' && $globalrow['state']!='3' && ($_POST['date_res']=='' || $_POST['date_res']=='0000-00-00 00:00:00')) $_POST['date_res']=date("Y-m-d H:i:s");
	
	//unread ticket if another technician add thread
	if (($_POST['resolution']!='') && ($globalrow['technician']!=$_SESSION['user_id'])) $techread=0; 
	
	//auto-attribute ticket to technician if user attachment is detected
	if ($_POST['user'])
	{
    	$query=$db->query("SELECT * FROM `tusers_tech` WHERE user='$_POST[user]'");
        $row=$query->fetch();
		$query->closeCursor(); 
        if($row['tech']!='') {
			if($rparameters['debug']==1) {echo '<br /><b>AUTO TECH CHANGE:</b> Auto assignement of this ticket, because technician attachment is detected.<br />';}
			$_POST['technician']=$row['tech'];
		}
	}
	
	//get user service to insert in tincidents table, or get selected service from field if ticket_service right
	if($_POST['user'] && !$_POST['u_service'] && $rright['ticket_service_disp']==0)
	{
    	$query=$db->query("SELECT service_id FROM `tusers_services` WHERE user_id='$_POST[user]'");
        $row=$query->fetch();
		$query->closeCursor(); 
        if($_POST['state']!=3) {$u_service=$row[0];} 
		elseif ($_POST['state']==3 && $_GET['action']=='new') {$u_service=$row[0];}
		else {$u_service=$globalrow['u_service'];} 
		if ($rparameters['debug']==1) {echo ' post_u_service='.$u_service.'<br />'; }
	} elseif ($_POST['u_service'] || $rright['ticket_service']!=0)
	{
		$u_service=$_POST['u_service'];
	} else {$u_service=$globalrow['u_service'];}
	
	//convert posted datetime to SQL format, if yyyy-mm-dd is detected
	if($_POST['date_create'] && !strpos($_POST['date_create'], "-"))
	{
		$_POST['date_create'] = DateTime::createFromFormat('d/m/Y H:i:s', $_POST['date_create']);
		$_POST['date_create']=$_POST['date_create']->format('Y-m-d H:i:s');
	}
	if($_POST['date_hope'] && !strpos($_POST['date_hope'], "-"))
	{
		$_POST['date_hope'] = DateTime::createFromFormat('d/m/Y', $_POST['date_hope']);
		$_POST['date_hope']=$_POST['date_hope']->format('Y-m-d');
	}
	if($_POST['date_res'] && !strpos($_POST['date_res'], "-"))
	{
		$_POST['date_res'] = DateTime::createFromFormat('d/m/Y H:i:s', $_POST['date_res']);
		$_POST['date_res']=$_POST['date_res']->format('Y-m-d H:i:s');
	}
	
	//SQL queries
	if (($_GET['action']=='new') && ($error=="0"))
	{
		//modify read state
		if($globalrow['technician']!=$_SESSION['user_id']) {$techread=0;} //unread ticket case when creator is not technician  
		if($_POST['technician']==$_SESSION['user_id']) {$techread=1; $techread_date=date("Y-m-d H:i:s");} //read ticket  
		
		//insert ticket
		$db->exec("INSERT INTO tincidents (user,type,u_group,u_service,u_agency,sender_service,technician,t_group,title,description,date_create,date_hope,date_res,priority,criticality,state,creator,time,time_hope,category,subcat,techread,techread_date,place,asset_id,start_availability,end_availability,availability_planned) VALUES ('$_POST[user]','$_POST[type]','$u_group','$u_service','$_POST[u_agency]','$_POST[sender_service]','$_POST[technician]','$t_group',$_POST[title],$_POST[description],'$_POST[date_create]','$_POST[date_hope]','$_POST[date_res]','$_POST[priority]','$_POST[criticality]','$_POST[state]','$_SESSION[user_id]','$_POST[time]','$_POST[time_hope]','$_POST[category]','$_POST[subcat]','$techread','$techread_date','$_POST[ticket_places]','$_POST[asset_id]','$start_availability','$end_availability','$_POST[availability_planned]')");
	    
	} elseif ($error=="0")  {	
		//modify read state
		if($_POST['technician']==$_SESSION['user_id']) {$techread=1; $techread_date=date("Y-m-d H:i:s");} //read ticket  
		if($globalrow['technician']=='') {$techread=1; $techread_date=date("Y-m-d H:i:s");} //read ticket case when it's an unassigned ticket.
	
		//update ticket
		$query = "UPDATE tincidents SET 
		user='$_POST[user]',
		type='$_POST[type]',
		u_group='$u_group',
		u_service='$u_service',
		u_agency='$_POST[u_agency]',
		sender_service='$_POST[sender_service]',
		technician='$_POST[technician]',
		t_group='$t_group',
		title=$_POST[title],
		description=$_POST[description],
		date_create='$_POST[date_create]',
		date_hope='$_POST[date_hope]',
		date_res='$_POST[date_res]',
		priority='$_POST[priority]',
		criticality='$_POST[criticality]',
		state='$_POST[state]',
		time='$_POST[time]',
		time_hope='$_POST[time_hope]',
		category='$_POST[category]',
		subcat='$_POST[subcat]',
		techread='$techread',
		techread_date='$techread_date',
		place='$_POST[ticket_places]',
		asset_id='$_POST[asset_id]',
		start_availability='$start_availability',
		end_availability='$end_availability',
		availability_planned='$_POST[availability_planned]'
		WHERE
		id LIKE $db_id";
		if ($rparameters['debug']==1) {echo "<br /><b>QUERY:</b><br /> $query<br />";}
		$db->exec($query);	
	}
	//threads text generation
	if(($_POST['resolution']!='') && ($_POST['resolution']!="'<br>'") && ($_POST['resolution']!='\'\'') && ($error=='0'))
	{
		if($_GET['threadedit'])
		{
			//get author from thread
			$query = $db->query('SELECT author FROM tthreads WHERE id='.$db_threadedit.'');
			$row=$query->fetch();
			$query->closeCursor(); 
			//check your own ticket for update thread right
			if($row['author']==$_SESSION['user_id']) 
			{
				if ($rright['ticket_thread_edit']!=0)  {$db->exec("UPDATE tthreads SET text=$_POST[resolution] WHERE id=$db_threadedit");}
			} else {
				if ($rright['ticket_thread_edit_all']!=0) {$db->exec("UPDATE tthreads SET text=$_POST[resolution] WHERE id=$db_threadedit");}
			}
			
		} elseif ($_POST['resolution']!='') {
			//generate new thread for this ticket
			$db->exec("INSERT INTO tthreads (ticket,date,author,text,type,private) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', $_POST[resolution],0,'$_POST[private]')");
		}
	}

	//threads insert close state
	if($_POST['state']=='3' && $globalrow['state']!='3')
	{
		$db->exec("INSERT INTO tthreads (ticket,date,author,type) VALUES ($db_id,'$datetime', '$_SESSION[user_id]', 4)");
	}
	//uploading files
	include "./core/upload.php";
	
	//auto send mail
	if(($rparameters['mail_auto_user_newticket']==1) || ($rparameters['survey']==1) || ($rparameters['mail_auto']==1) || ($rparameters['mail_auto_user_modify']==1) || ($rparameters['mail_auto_tech_modify']==1) || ($rparameters['mail_auto_tech_modify']==1) || ($rparameters['mail_newticket']==1) && ($_POST['upload']=='')){include('./core/auto_mail.php');}
	
	//display message
	if($error=="0")
	{
	    echo '<div class="alert alert-block alert-success"><center><i class="icon-ok green"></i>	'.T_('Ticket sauvegardé').'. </center></div>';
	} else {
	    // new page ticket redirect
        echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_($error).' </div>';
	}

	//redirect to ticket list for quit or send button
	if (($_POST['quit'] || $_POST['send']) && ($error=='0'))
	{
		echo '<script language="Javascript">
		<!--
		document.location.replace("./index.php?page=dashboard&'.$url_get_parameters.'");
		-->
		</script>';
	}
	
	//send mail
	if($_POST['mail'])
	{
		//redirect to preview mail page
		$www = "./index.php?page=preview_mail&id=$_GET[id]&userid=$_GET[userid]&state=$_GET[state]&category=$_GET[category]&subcat=$_GET[subcat]&viewid=$_GET[viewid]&view=$_GET[view]&date_start=$_GET[date_start]&date_end=$_GET[date_end]";
		echo '
		<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
		</script>
		';
	}
	
    if($error=="0")
    {
		//global redirect on current ticket
		echo "
		<SCRIPT LANGUAGE='JavaScript'>
			<!--
			function redirect()
			{
				window.location='./index.php?page=ticket&id=$_GET[id]&action=$_POST[action]&edituser=$_POST[edituser]&cat=$_POST[category]&editcat=$_POST[subcat]&$url_get_parameters$down'	
			}
			setTimeout('redirect()',$rparameters[time_display_msg]);
			-->
		</SCRIPT>";
    }		
}
//modify ticket state on close ticket button
if($_POST['close']) 
{
	//update tincidents
	$db->exec("UPDATE tincidents SET state='3', date_res='$datetime' WHERE id=$db_id");
	if($_SESSION['profile_id']!=0 && $_SESSION['profile_id']!=4)
	{
		$db->exec("UPDATE tincidents SET techread='0' WHERE id=$db_id"); //unread ticket for technician only if user close ticket
	}
	//update thread
	$db->exec("INSERT INTO tthreads (ticket, date, type, author) VALUES ($db_id,'$datetime','4','$_SESSION[user_id]')");
	//redirect to tickets list
	echo '<div class="alert alert-block alert-success"><center><i class="icon-ok green"></i>	'.T_('Ticket clôturé').'. </center></div>';
	echo "
	<SCRIPT LANGUAGE='JavaScript'>
		<!--
		function redirect()
		{
			window.location='./index.php?page=dashboard&$url_get_parameters'
		}
		setTimeout('redirect()',$rparameters[time_display_msg]);
		-->
	</SCRIPT>";
}
//redirect to tickets list
if($_POST['cancel']) 
{
	echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i>	'.T_('Annulation pas de modification').'.</center></div>';
	echo "
	<SCRIPT LANGUAGE='JavaScript'>
		<!--
		function redirect()
		{
		window.location='./index.php?page=dashboard&$url_get_parameters'
		}
		setTimeout('redirect()',$rparameters[time_display_msg]);
		-->
	</SCRIPT>";
}
  
//unread ticket technician
if (($globalrow['techread']=="0")&&($globalrow['technician']==$_SESSION['user_id'])) 
{
	$current_date_hour=date("Y-m-d H:i:s");
	$db->exec("UPDATE tincidents SET techread='1',techread_date='$current_date_hour' WHERE id=$db_id");
}
//find previous and next ticket
$query=$db->query("SELECT MIN(id) FROM tincidents WHERE id > $db_id AND id IN (SELECT id FROM tincidents WHERE technician='$_SESSION[user_id]' AND state='$globalrow[state]' AND id not like $db_id)");
$next = $query->fetch();
$query->closeCursor();
$query=$db->query("SELECT MAX(id) FROM tincidents WHERE id < $db_id AND id IN (SELECT id FROM tincidents WHERE technician='$_SESSION[user_id]' AND state='$globalrow[state]' AND id not like $db_id)");
$previous = $query->fetch();

//calculate percentage of ticket resolution
if ($globalrow['time_hope']!=0 && ($rright['ticket_time_disp']!=0 && $rright['ticket_time_hope_disp']!=0))
{
	$percentage=($globalrow['time']*100)/$globalrow['time_hope'];
	$percentage=round($percentage);
	if (($globalrow['time']!='1') && ($globalrow['time_hope']!='1') && ($globalrow['time_hope']>=$globalrow['time'])) {$percentage=' <span title="'.T_("Pourcentage d'avancement du ticket basé sur le temps passé et estimé").'">('.$percentage.'%)</span> ';} else {$percentage='';}
}

//cut title for long case
$nbtitle=strlen($globalrow['title']);
if ($nbtitle>50)
{
	$title=substr($globalrow['title'], 0, 50);
	$title="$title...";
} else $title=$globalrow['title'];
?>