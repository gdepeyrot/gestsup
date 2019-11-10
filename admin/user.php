<?php
################################################################################
# @Name : user.php 
# @Description : admin user
# @Call : admin.php
# @Author : Flox
# @Create : 12/01/2011
# @Update : 06/02/2017
# @Version : 3.1.30
################################################################################

//initialize variables 
if(!isset($_SERVER['QUERY_URI'])) $_SERVER['QUERY_URI'] = '';
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = '';
if(!isset($_POST['Ajouter'])) $_POST['Ajouter'] = '';
if(!isset($_POST['cancel'])) $_POST['cancel'] = '';
if(!isset($_POST['addview'])) $_POST['addview'] = '';
if(!isset($_POST['profil'])) $_POST['profil'] = '';
if(!isset($_POST['name'])) $_POST['name'] = '';
if(!isset($_POST['company'])) $_POST['company'] = '';
if(!isset($_POST['address1'])) $_POST['address1'] = '';
if(!isset($_POST['address2'])) $_POST['address2'] = '';
if(!isset($_POST['zip'])) $_POST['zip'] = '';
if(!isset($_POST['city'])) $_POST['city'] = '';
if(!isset($_POST['custom1'])) $_POST['custom1'] = '';
if(!isset($_POST['custom2'])) $_POST['custom2'] = '';
if(!isset($_POST['password'])) $_POST['password'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '%';
if(!isset($_POST['subcat'])) $_POST['subcat'] = '';
if(!isset($_POST['firstname'])) $_POST['firstname'] = '';
if(!isset($_POST['lastname'])) $_POST['lastname'] = '';
if(!isset($_POST['viewname'])) $_POST['viewname'] = '';
if(!isset($_POST['service'])) $_POST['service'] = '';
if(!isset($_POST['service_1'])) $_POST['service_1'] = '';
if(!isset($_POST['service_2'])) $_POST['service_2'] = '';
if(!isset($_POST['service_3'])) $_POST['service_3'] = '';
if(!isset($_POST['service_4'])) $_POST['service_4'] = '';
if(!isset($_POST['service_5'])) $_POST['service_5'] = '';
if(!isset($_POST['agency'])) $_POST['agency'] = '';
if(!isset($_POST['agency_1'])) $_POST['agency_1'] = '';
if(!isset($_POST['agency_2'])) $_POST['agency_2'] = '';
if(!isset($_POST['agency_3'])) $_POST['agency_3'] = '';
if(!isset($_POST['agency_4'])) $_POST['agency_4'] = '';
if(!isset($_POST['agency_5'])) $_POST['agency_5'] = '';
if(!isset($_POST['function'])) $_POST['function'] = '';
if(!isset($_POST['limit_ticket_number'])) $_POST['limit_ticket_number'] = '';
if(!isset($_POST['limit_ticket_days'])) $_POST['limit_ticket_days'] = '';
if(!isset($_POST['limit_ticket_date_start'])) $_POST['limit_ticket_date_start'] = '';
if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['login'])) $_POST['login'] = '';
if(!isset($_POST['phone'])) $_POST['phone'] = '';
if(!isset($_POST['fax'])) $_POST['fax'] = '';
if(!isset($_POST['default_ticket_state'])) $_POST['default_ticket_state'] = '';
if(!isset($_POST['dashboard_ticket_order'])) $_POST['dashboard_ticket_order'] = '';
if(!isset($user1['company'])) $user1['company'] = '';
if(!isset($password)) $password = '';
if(!isset($addeview)) $addview = '';
if(!isset($category)) $category = '%';
if(!isset($maxline)) $maxline = '';
if(!isset($_POST['chgpwd'])) $_POST['chgpwd'] = '';
if(!isset($_GET['userid'])) $_GET['userid'] = '';
if(!isset($_GET['deleteview'])) $_GET['deleteview'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['ldap'])) $_GET['ldap'] = '';
if(!isset($_GET['attachmentdelete'])) $_GET['attachmentdelete'] = '';
if(!isset($_GET['disable'])) $_GET['disable'] = '';
if(!isset($_GET['cursor'])) $_GET['cursor'] = '';
if(!isset($_GET['order'])) $_GET['order'] = '';
if(!isset($_GET['way'])) $_GET['way'] = '';
if(!isset($_GET['tab'])) $_GET['tab'] = '';
if(!isset($_GET['delete_assoc_service'])) $_GET['delete_assoc_service'] = '';
if(!isset($_GET['delete_assoc_agency'])) $_GET['delete_assoc_agency'] = '';
if(!isset($_GET['profileid'])) $_GET['profileid'] = '';

//defaults values
if(!$_GET['tab']) $_GET['tab'] = 'infos';
if($_GET['disable']=='') $_GET['disable'] = '0';
if($_GET['cursor']=='') $_GET['cursor'] = '0';
if($_GET['order']=='') $_GET['order'] = 'lastname';
if($_GET['way']=='') $_GET['way'] = 'ASC';
if($maxline=='') $maxline = 15;
if($_POST['userkeywords']=='') $userkeywords='%'; else $userkeywords=$_POST['userkeywords'];

$_GET['delete_assoc_service']=strip_tags($_GET['delete_assoc_service']);
$_GET['delete_assoc_agency']=strip_tags($_GET['delete_assoc_agency']);
$_GET['userid']=strip_tags($_GET['userid']);
$_GET['viewid']=strip_tags($_GET['viewid']);
$_GET['disable']=strip_tags($_GET['disable']);
$_GET['attachmentdelete']=strip_tags($_GET['attachmentdelete']);
$_GET['profileid']=strip_tags($_GET['profileid']);
$db_order=strip_tags($db->quote($_GET['order']));
$db_order=str_replace("'","",$db_order);
if($_GET['way']=='ASC' || $_GET['way']=='DESC') {$db_way=$_GET['way'];} else {$db_way='DESC';}
if(is_numeric($_GET['cursor'])) {$db_cursor=$_GET['cursor'];} else {$db_cursor=0;}

//special char rename
if($_POST['viewname']){$_POST['viewname']=strip_tags($_POST['viewname']);}

//secure string
$_POST['firstname']=strip_tags($_POST['firstname']);
$_POST['lastname']=strip_tags($_POST['lastname']);
$_POST['address1']=strip_tags($_POST['address1']);
$_POST['address2']=strip_tags($_POST['address2']);
$_POST['login']=strip_tags($_POST['login']);
$_POST['mail']=strip_tags($_POST['mail']);
$_POST['phone']=strip_tags($_POST['phone']);
$_POST['fax']=strip_tags($_POST['fax']);
$_POST['city']=strip_tags($_POST['city']);
$_POST['zip']=strip_tags($_POST['zip']);
$_POST['custom1']=strip_tags($_POST['custom1']);
$_POST['custom2']=strip_tags($_POST['custom2']);
$_POST['function']=strip_tags($_POST['function']);

//delete association user > service
if ($_GET['delete_assoc_service'] && $rright['admin']!=0)
{
	$qry=$db->prepare("DELETE FROM `tusers_services` WHERE id=:id");
	$qry->execute(array(
		'id' => $_GET['delete_assoc_service']
		));
}

//delete assoc user > agency
if ($_GET['delete_assoc_agency'] && $rright['admin']!=0)
{
	$qry=$db->prepare("DELETE FROM `tusers_agencies` WHERE id=:id");
	$qry->execute(array(
		'id' => $_GET['delete_assoc_agency']
		));
}

//modify case
if($_POST['Modifier'])
{
	//case user sync from AD without pwd and not already connected
	if($rparameters['ldap']==1 && $rparameters['ldap_auth']==1 && $_POST['password']=='')
	{
		
		$qry = $db->prepare("SELECT `password`,`salt` FROM `tusers` WHERE id=:id");
		$qry->execute(array(
			'id' => $_GET['userid']
			));
		$row=$qry->fetch();
		$qry->closeCursor();
		if($row['password']=='' && $row['salt']=='')
		{
			echo 'CASE';
			$salt = substr(md5(uniqid(rand(), true)), 0, 5); //generate a random key as salt
			$pwd=substr(str_shuffle(strtolower(sha1(rand() . time() . $salt))),0, 50);
			$pwd=md5($salt . md5($pwd)); 
			
			//update pwd
			$qry=$db->prepare("UPDATE `tusers` SET `password`=:password,`salt`=:salt WHERE `id`=:id");
			$qry->execute(array(
				'password' => $pwd,
				'salt' => $salt,
				'id' => $_GET['userid']
				));
			
			$_POST['password']=$pwd;
		}
	}
	
	$error=0;
	if ($_POST['password']=='')
	{
		$error='<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Pour des raisons de sécurité, votre mot de passe ne doit pas être vide').' </div>';
	}
	
	if (!$error)
	{
		//no update already crytped password if no change
		$qry = $db->prepare("SELECT `password`,`salt` FROM `tusers` WHERE id=:id");
		$qry->execute(array(
			'id' => $_GET['userid']
			));
		$row=$qry->fetch();
		$qry->closeCursor();
		
		if($_POST['password']!=$row['password']) 
		{
			$salt = substr(md5(uniqid(rand(), true)), 0, 5); //generate a random key as salt
			$_POST['password']=md5($salt . md5($_POST['password'])); //store in md5, md5 password + salt
		} else {
			$salt=$row['salt'];
		}
		
		$qry=$db->prepare("
		UPDATE tusers SET
		firstname=:firstname,
		lastname=:lastname,
		password=:password,
		salt=:salt,
		mail=:mail,
		phone=:phone,
		login=:login,
		fax=:fax,
		function=:function,
		company=:company,
		address1=:address1,
		address2=:address2,
		zip=:zip,
		city=:city,
		custom1=:custom1,
		custom2=:custom2,
		limit_ticket_number=:limit_ticket_number,
		limit_ticket_days=:limit_ticket_days,
		limit_ticket_date_start=:limit_ticket_date_start,
		skin=:skin,
		dashboard_ticket_order=:dashboard_ticket_order,
		default_ticket_state=:default_ticket_state,
		chgpwd=:chgpwd,
		language=:language
		WHERE id=:id
		");
		$qry->execute(array(
			'firstname' => $_POST['firstname'],
			'lastname' => $_POST['lastname'],
			'password' => $_POST['password'],
			'salt' => $salt,
			'mail' => $_POST['mail'],
			'phone' => $_POST['phone'],
			'login' => $_POST['login'],
			'fax' => $_POST['fax'],
			'function' => $_POST['function'],
			'company' => $_POST['company'],
			'address1' => $_POST['address1'],
			'address2' => $_POST['address2'],
			'zip' => $_POST['zip'],
			'city' => $_POST['city'],
			'custom1' => $_POST['custom1'],
			'custom2' => $_POST['custom2'],
			'limit_ticket_number' => $_POST['limit_ticket_number'],
			'limit_ticket_days' => $_POST['limit_ticket_days'],
			'limit_ticket_date_start' => $_POST['limit_ticket_date_start'],
			'skin' => $_POST['skin'],
			'dashboard_ticket_order' => $_POST['dashboard_ticket_order'],
			'default_ticket_state' => $_POST['default_ticket_state'],
			'chgpwd' => $_POST['chgpwd'],
			'language' => $_POST['language'],
			'id' => $_GET['userid']
			));
		$qry->closeCursor();
		
		//special case profil update check admin right
		if($rright['admin']!=0)
		{
			$qry=$db->prepare("UPDATE tusers SET profile=:profile WHERE id=:id ");
			$qry->execute(array('profile' => $_POST['profile'],'id' => $_GET['userid']));
			$qry->closeCursor();
		}
		
		//multi service update association
		if ($_POST['service_1']) {
			$qry = $db->prepare("SELECT `service_id` FROM `tusers_services` WHERE `user_id`=:user_id AND `service_id`=:service_id");
			$qry->execute(array(
				'user_id' => $_GET['userid'],
				'service_id' => $_POST['service_1']
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if (!$row) {
				$qry=$db->prepare("INSERT INTO `tusers_services` (`user_id`,`service_id`) VALUES (:user_id,:service_id)");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'service_id' => $_POST['service_1']
					));
			} 	
		}
		if ($_POST['service_2']) {
			$qry = $db->prepare("SELECT `service_id` FROM `tusers_services` WHERE `user_id`=:user_id AND `service_id`=:service_id");
			$qry->execute(array(
				'user_id' => $_GET['userid'],
				'service_id' => $_POST['service_2']
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if (!$row) {
				$qry=$db->prepare("INSERT INTO `tusers_services` (`user_id`,`service_id`) VALUES (:user_id,:service_id)");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'service_id' => $_POST['service_2']
					));
			} 	
		}
		if ($_POST['service_3']) {
			$qry = $db->prepare("SELECT `service_id` FROM `tusers_services` WHERE `user_id`=:user_id AND `service_id`=:service_id");
			$qry->execute(array(
				'user_id' => $_GET['userid'],
				'service_id' => $_POST['service_3']
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if (!$row) {
				$qry=$db->prepare("INSERT INTO `tusers_services` (`user_id`,`service_id`) VALUES (:user_id,:service_id)");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'service_id' => $_POST['service_3']
					));
			} 	
		}
		if ($_POST['service_4']) {
			$qry = $db->prepare("SELECT `service_id` FROM `tusers_services` WHERE `user_id`=:user_id AND `service_id`=:service_id");
			$qry->execute(array(
				'user_id' => $_GET['userid'],
				'service_id' => $_POST['service_4']
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if (!$row) {
				$qry=$db->prepare("INSERT INTO `tusers_services` (`user_id`,`service_id`) VALUES (:user_id,:service_id)");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'service_id' => $_POST['service_4']
					));
			} 	
		}
		if ($_POST['service_5']) {
			$qry = $db->prepare("SELECT `service_id` FROM `tusers_services` WHERE `user_id`=:user_id AND `service_id`=:service_id");
			$qry->execute(array(
				'user_id' => $_GET['userid'],
				'service_id' => $_POST['service_5']
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if (!$row) {
				$qry=$db->prepare("INSERT INTO `tusers_services` (`user_id`,`service_id`) VALUES (:user_id,:service_id)");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'service_id' => $_POST['service_5']
					));
			} 	
		}
		if ($rparameters['user_agency'])
		{
			//multi agency update association
			if ($_POST['agency_1']) {
				$qry = $db->prepare("SELECT `agency_id` FROM `tusers_agencies` WHERE `user_id`=:user_id AND `agency_id`=:agency_id");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'agency_id' => $_POST['agency_1']
					));
				$row=$qry->fetch();
				$qry->closeCursor();
				if (!$row) {
					$qry=$db->prepare("INSERT INTO `tusers_agencies` (`user_id`,`agency_id`) VALUES (:user_id,:agency_id)");
					$qry->execute(array(
						'user_id' => $_GET['userid'],
						'agency_id' => $_POST['agency_1']
						));
				} 	
			}
			if ($_POST['agency_2']) {
				$qry = $db->prepare("SELECT `agency_id` FROM `tusers_agencies` WHERE `user_id`=:user_id AND `agency_id`=:agency_id");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'agency_id' => $_POST['agency_2']
					));
				$row=$qry->fetch();
				$qry->closeCursor();
				if (!$row) {
					$qry=$db->prepare("INSERT INTO `tusers_agencies` (`user_id`,`agency_id`) VALUES (:user_id,:agency_id)");
					$qry->execute(array(
						'user_id' => $_GET['userid'],
						'agency_id' => $_POST['agency_2']
						));
				} 	
			}
			if ($_POST['agency_3']) {
				$qry = $db->prepare("SELECT `agency_id` FROM `tusers_agencies` WHERE `user_id`=:user_id AND `agency_id`=:agency_id");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'agency_id' => $_POST['agency_3']
					));
				$row=$qry->fetch();
				$qry->closeCursor();
				if (!$row) {
					$qry=$db->prepare("INSERT INTO `tusers_agencies` (`user_id`,`agency_id`) VALUES (:user_id,:agency_id)");
					$qry->execute(array(
						'user_id' => $_GET['userid'],
						'agency_id' => $_POST['agency_3']
						));
				} 	
			}
			if ($_POST['agency_4']) {
				$qry = $db->prepare("SELECT `agency_id` FROM `tusers_agencies` WHERE `user_id`=:user_id AND `agency_id`=:agency_id");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'agency_id' => $_POST['agency_4']
					));
				$row=$qry->fetch();
				$qry->closeCursor();
				if (!$row) {
					$qry=$db->prepare("INSERT INTO `tusers_agencies` (`user_id`,`agency_id`) VALUES (:user_id,:agency_id)");
					$qry->execute(array(
						'user_id' => $_GET['userid'],
						'agency_id' => $_POST['agency_4']
						));
				} 	
			}
			if ($_POST['agency_5']) {
				$qry = $db->prepare("SELECT `agency_id` FROM `tusers_agencies` WHERE `user_id`=:user_id AND `agency_id`=:agency_id");
				$qry->execute(array(
					'user_id' => $_GET['userid'],
					'agency_id' => $_POST['agency_5']
					));
				$row=$qry->fetch();
				$qry->closeCursor();
				if (!$row) {
					$qry=$db->prepare("INSERT INTO `tusers_agencies` (`user_id`,`agency_id`) VALUES (:user_id,:agency_id)");
					$qry->execute(array(
						'user_id' => $_GET['userid'],
						'agency_id' => $_POST['agency_5']
						));
				} 	
			}
		}
		if($_POST['viewname'])
		{
			$qry=$db->prepare("INSERT INTO `tviews` (`uid`,`name`,`category`,`subcat`) VALUES (:uid,:name,:category,:subcat)");
			$qry->execute(array(
				'uid' => $_GET['userid'],
				'name' => $_POST['viewname'],
				'category' => $_POST['category'],
				'subcat' => $_POST['subcat']
				));
		}
		//tech attachement insert
		if($_POST['attachment'])
		{
			$qry=$db->prepare("INSERT INTO `tusers_tech` (`user`,`tech`) VALUES (:user,:tech)");
			$qry->execute(array(
				'user' => $_POST['attachment'],
				'tech' => $_GET['userid']
				));
		}
	}
	//redirect
	if($error)
	{
		echo "
		$error
		<SCRIPT LANGUAGE='JavaScript'>
			<!--
			function redirect()
			{
				window.location='$_SERVER[QUERY_URI]'	
			}
			setTimeout('redirect()',$rparameters[time_display_msg]+500);
			-->
		</SCRIPT>";
	} else {
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$_SERVER['QUERY_URI'].'");
		// -->
		</script>';
	}
	
}

if($_POST['Ajouter'] && $rright['admin']!=0)
{
	//crypt password md5 + salt
	$salt = substr(md5(uniqid(rand(), true)), 0, 5); // Generate a random key
	$_POST['password']=md5($salt . md5($_POST['password'])); // store in md5, md5 password + salt
	
	$qry=$db->prepare("
	INSERT INTO tusers (
	firstname,
	lastname,
	password,
	salt,
	mail,
	phone,
	fax,
	company,
	address1,
	address2,
	zip,
	city,
	custom1,
	custom2,
	profile,
	login,
	chgpwd,
	skin,
	function
	) VALUES (
	:firstname,
	:lastname,
	:password,
	:salt,
	:mail,
	:phone,
	:fax,
	:company,
	:address1,
	:address2,
	:zip,
	:city,
	:custom1,
	:custom2,
	:profile,
	:login,
	:chgpwd,
	:skin,
	:function
	)");
	$qry->execute(array(
		'firstname' => $_POST['firstname'],
		'lastname' => $_POST['lastname'],
		'password' => $_POST['password'],
		'salt' => $salt,
		'mail' => $_POST['mail'],
		'phone' => $_POST['phone'],
		'fax' => $_POST['fax'],
		'company' => $_POST['company'],
		'address1' => $_POST['address1'],
		'address2' => $_POST['address2'],
		'zip' => $_POST['zip'],
		'city' => $_POST['city'],
		'custom1' => $_POST['custom1'],
		'custom2' => $_POST['custom2'],
		'profile' => $_POST['profile'],
		'login' => $_POST['login'],
		'chgpwd' => $_POST['chgpwd'],
		'skin' => $_POST['skin'],
		'function' => $_POST['function']
		));
	
	
	//if post service insert new assoc
	if ($_POST['service'])
	{
		$last_user_id=$db->lastInsertId(); 
		$qry=$db->prepare("INSERT INTO `tusers_services` (`user_id`,`service_id`) VALUES (:user_id,:service_id)");
		$qry->execute(array(
			'user_id' => $last_user_id,
			'service_id' => $_POST['service']
			));
	}
	
	if($rparameters['user_agency'])
	{
		//if post agency insert new assoc
		if ($_POST['agency'])
		{
			$last_user_id=$db->lastInsertId(); 
			$qry=$db->prepare("INSERT INTO `tusers_agencies` (`user_id`,`agency_id`) VALUES (:user_id,:agency_id)");
			$qry->execute(array(
				'user_id' => $last_user_id,
				'agency_id' => $_POST['agency']
				));
		}
	}
	
	//redirect
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
//cancel
if($_POST['cancel'])
{
	//redirect
	$www = "./index.php?page=dashboard&userid=$uid&state=%";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
//view Part
if ($_GET['deleteview']=="1" && $rright['side_view']!=0)
{
	$qry=$db->prepare("DELETE FROM `tviews` WHERE id=:id");
	$qry->execute(array(
		'id' => $_GET['viewid']
		));
	//redirect
	$www = "./index.php?page=admin/user&subpage=user&action=edit&tab=parameters&userid=$_GET[userid]";
	echo '<script language="Javascript">
	<!--
	document.location.replace("'.$www.'");
	// -->
	</script>';
}
//delete tech attachement
if($_GET['attachmentdelete'] && ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==4))
{
	$qry=$db->prepare("DELETE FROM `tusers_tech` WHERE id=:id");
	$qry->execute(array(
		'id' => $_GET['attachmentdelete']
		));
}

//display head page
if ($rright['admin_user_profile']!='0')
{
	if(!$_GET['ldap'])
	{
		//count users
		$qry = $db->prepare("SELECT COUNT(*) FROM `tusers` WHERE disable=:disable");
		$qry->execute(array(
			'disable' => 0
			));
		$r1=$qry->fetch();
		$qry->closeCursor();
		
		$qry = $db->prepare("SELECT COUNT(*) FROM `tusers` WHERE disable=:disable");
		$qry->execute(array(
			'disable' => 1
			));
		$r2=$qry->fetch();
		$qry->closeCursor();
		
		echo '
		<div class="page-header position-relative">
			<h1>
				<i class="icon-user"></i>  '.T_('Gestion des utilisateurs').'
				<small>
					<i class="icon-double-angle-right"></i>
					&nbsp;'.T_('Nombre').': '.$r1[0].' '.T_('Activés et').' '.$r2[0].' '.T_('Désactivés').'
				</small>
			</h1>
		</div>';
	}
}
/////////////////////////////////////////////////// display edit user page  /////////////////////////////////////////////////////////////
if (($_GET['action']=='edit') && (($_SESSION['user_id']==$_GET['userid']) || ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==4))) 
{
	//get user data
	$qry = $db->prepare("SELECT * FROM `tusers` WHERE id=:id");
	$qry->execute(array(
		'id' => $_GET['userid']
		));
	$user1=$qry->fetch();
	$qry->closeCursor();
	
	//display edit form.
	echo '
		<div class="col-sm-12">
			<div class="widget-box">
				<div class="widget-header">
					<h4>'.T_('Fiche utilisateur').': '.$user1['firstname'].' '.$user1['lastname'].'</h4>
					<span class="widget-toolbar">
						<button value="Modifier" id="Modifier" name="Modifier" type="submit" form="1" class="btn btn-minier btn-success">
							<i class="icon-save bigger-140"></i>
						</button>
					</span>
				</div>
				<div class="widget-body">
					<div class="widget-main no-padding">
						<form id="1" name="form" method="POST" action="" class="form-horizontal">
                                <fieldset>
                                <div class="col-sm-12">
                            		<div class="tabbable">
                            			<ul class="nav nav-tabs" id="myTab">';
                            				if($_GET['tab']=='infos') {echo '<li class="active" >';} else {echo '<li>';} echo '
                            					<a href="./index.php?page=admin/user&subpage=user&action=edit&userid='.$_GET['userid'].'&tab=infos">
                            						<i class="icon-info bigger-110 blue"></i>
                            						'.T_('Informations').'
                            					</a>
                            				</li>';
                            				if($_GET['tab']=='parameters') {echo '<li class="active" >';} else {echo '<li>';} echo '
                            					<a href="./index.php?page=admin/user&subpage=user&action=edit&userid='.$_GET['userid'].'&tab=parameters">
                            						<i class="icon-cog bigger-110 orange"></i>
                            						'.T_('Paramètres').'
                            					</a>
                            				</li>';
                                            //display attachment tab if it's not a technician or admin
                            				if ((($user1['profile']==0) || ($user1['profile']==4)) && ($_SESSION['profile_id']!=1) && ($_SESSION['profile_id']!=2) && ($_SESSION['profile_id']!=3))
                            				{
                                				if($_GET['tab']=='attachment') {echo '<li class="active" >';} else {echo '<li>';} echo '
                                					<a href="./index.php?page=admin/user&subpage=user&action=edit&userid='.$_GET['userid'].'&tab=attachment">
                                						<i class="icon-user bigger-110 green"></i>
                                						'.T_('Rattachement à des utilisateurs').'
                                						<i title="'.T_('Permet d\'attribuer automatiquement un technicien lors de la création de ticket par un utilisateur').'" class="icon-question-sign blue bigger-110"></i>
                                					</a>
                                				</li>';
                            				}
                            				echo'
                            			</ul>
                            			<div class="tab-content">
                            			    <div id="attachment" class="tab-pane'; if ($_GET['tab']=='attachment' || $_GET['tab']=='') echo 'active'; echo '">
                                                <label class="control-label bolder blue" for="attachment">'.T_('Associer des utilisateurs à ce technicien').':</label>
                                                <div class="space-4"></div>
                                                <select name="attachment">
                                                    ';
                                                    //display list of user for attachment
													$qry = $db->prepare("SELECT tusers.* FROM `tusers` WHERE tusers.profile!=0 AND tusers.profile!=:profile AND tusers.disable=:disable AND tusers.id NOT IN (SELECT user FROM tusers_tech) ORDER BY tusers.lastname");
													$qry->execute(array(
														'profile' => 4,
														'disable' => 0
														));
													while ($row = $qry->fetch())
                                                    {
                                                        echo '<option value="'.$row['id'].'">'.$row['lastname'].' '.$row['firstname'].'</option>';
                                                    }
													$qry->closeCursor();
                                                    echo '
                                                    <option selected></option>
                                                </select>
                                                <hr />
                                                <label class="control-label bolder blue" for="skin">'.T_('Liste des utilisateurs associés à ce technicien').':</label>
                                                <div class="space-4"></div>
                                                ';
													$qry = $db->prepare("SELECT `id`,`user` FROM `tusers_tech` WHERE tech=:tech");
													$qry->execute(array(
														'tech' => $_GET['userid']
														));
                                                    while ($row = $qry->fetch())
                                                    {
                                                        //find tech name
														$qry2 = $db->prepare("SELECT `lastname`,`firstname` FROM `tusers` WHERE id=:id");
														$qry2->execute(array(
															'id' => $row['user']
															));
														$row2=$qry2->fetch();
														$qry2->closeCursor();
                                                    	echo'<i class="icon-caret-right blue"></i> '.$row2['lastname'].' '.$row2['firstname'].'';
                                                    	echo '<a title="Supprimer" href="./index.php?page=admin/user&subpage=user&action=edit&userid='.$_GET['userid'].'&tab=attachment&attachmentdelete='.$row['id'].'"> <i class="icon-trash red bigger-120"></i></a>';
                                                        echo '<br />';
                                                    }
													$qry->closeCursor();
                                                    echo '
                                                    
                            			    </div>
                                            <div id="parameters" class="tab-pane'; if ($_GET['tab']=='parameters' || $_GET['tab']=='') echo 'active'; echo '">
													<label class="control-label bolder blue" for="language">'.T_('Langue').':</label>
                                                    <div class="space-4"></div>
                    								<select name="language">
                    									<option '; if ($user1['language']=='fr_FR'){echo "selected";} echo ' value="fr_FR">'.T_('Français (France)').'</option>
                    									<option '; if ($user1['language']=='en_US'){echo "selected";} echo ' value="en_US">'.T_('Anglais (États Unis)').'</option>
                    									<option '; if ($user1['language']=='de_DE'){echo "selected";} echo ' value="de_DE">'.T_('Allemand (Allemagne)').'</option>
                    									<option '; if ($user1['language']=='es_ES'){echo "selected";} echo ' value="es_ES">'.T_('Espagnol (Espagne)').'</option>
                    								</select>
                    								<hr />
                                                    <label class="control-label bolder blue" for="skin">'.T_('Thème').':</label>
                                                    <div class="space-4"></div>
                    								<select name="skin">
                    									<option style="background-color:#438EB9;" '; if ($user1['skin']==''){echo "selected";} echo ' value="">'.T_('Bleu (Défaut)').'</option>
                    									<option style="background-color:#222A2D;" '; if ($user1['skin']=='skin-1'){echo "selected";} echo ' value="skin-1">'.T_('Noir').'</option>
                    									<option style="background-color:#C6487E;" '; if ($user1['skin']=='skin-2'){echo "selected";} echo ' value="skin-2">'.T_('Rose').'</option>
                    									<option style="background-color:#D0D0D0;" '; if ($user1['skin']=='skin-3'){echo "selected";} echo ' value="skin-3">'.T_('Gris').'</option>
                    								</select>
                    								';
                    								//display group attachment if exist
													$qry = $db->prepare("SELECT count(*) FROM `tgroups`, `tgroups_assoc` WHERE tgroups.id=tgroups_assoc.group AND tgroups_assoc.user=:user");
													$qry->execute(array(
														'user' => $_GET['userid']
														));
													$row=$qry->fetch();
													$qry->closeCursor();
                    								if ($row[0]!=0)
                    								{
                    									echo '<hr />';
                    									echo '<label class="control-label bolder blue" for="group">'.T_('Membre des groupes').':</label>';
														$qry = $db->prepare("SELECT tgroups.id AS id, tgroups.name AS name FROM tgroups, tgroups_assoc WHERE tgroups.id=tgroups_assoc.group AND tgroups_assoc.user=:user");
														$qry->execute(array(
															'user' => $_GET['userid']
															));
                    									while ($row = $qry->fetch())
                    									{
                    										echo "<div class=\"space-4\"></div><i class=\"icon-caret-right blue\"></i> <a href=\"./index.php?page=admin&subpage=group&action=edit&id=$row[id]\"> $row[name]</a>";
                    									}
														$qry->closeCursor();														
                    								}
                    								// Display profile list
                    								if ($rright['admin_user_profile']!='0')
                    								{
                    									echo '
                    									<hr />
                    									<label class="control-label bolder blue" for="profile">'.T_('Profil').':</label>
                    									<div class="controls">
                    										<div class="radio">
                    											<label>
                    												<input type="radio" class="ace" name="profile" value="4" '; if ($user1['profile']=='4')echo "checked"; echo '> <span class="lbl"> '.T_('Administrateur').' </span>
                    											</label>
                    										</div>
                    										<div class="radio">
                    											<label>
                    												<input type="radio" class="ace" name="profile" value="0" '; if ($user1['profile']=='0')echo "checked"; echo '> <span class="lbl"> '.T_('Technicien').' </span>
                    											</label>
                    										</div>
                    										<div class="radio">
                    											<label>
                    												<input type="radio" class="ace" name="profile" value="3" '; if ($user1['profile']=='3')echo "checked"; echo '> <span class="lbl"> '.T_('Superviseur').' </span>
                    											</label>
                    										</div>
                    										<div class="radio">
                    											<label>
                    												<input type="radio" class="ace" name="profile" value="1" '; if ($user1['profile']=='1')echo "checked"; echo '> <span class="lbl"> '.T_('Utilisateur avec pouvoir').' </span>
                    											</label>
                    										</div>
                    										<div class="radio">
                    											<label>
                    												<input type="radio" class="ace" name="profile" value="2" '; if ($user1['profile']=='2')echo "checked"; echo '> <span class="lbl"> '.T_('Utilisateur').' </span>
                    											</label>
                    										</div>
                    									</div>
                    									<hr />
                    									<label class="control-label bolder blue" for="chgpwd">'.T_('Forcer le changement du mot de passe').':</label>
                    									<br />
                    									<label>
                    											<input type="radio" class="ace" disable="disable" name="chgpwd" value="1" '; if ($user1['chgpwd']=='1')echo "checked"; echo '> <span class="lbl"> '.T_('Oui').' </span>
                    											<input type="radio" class="ace" name="chgpwd" value="0" '; if ($user1['chgpwd']=='0')echo "checked"; echo '> <span class="lbl"> '.T_('Non').' </span>
                    									</label>
                    									';
                    								}
                    								else
                    								{
                    									echo '<input type="hidden" name="profile" value="'.$user1['profile'].'" '; if ($user1['profile']=='2')echo "checked"; echo '>';
                    								}
                    								//display personal view
                    								if ($rright['admin_user_view']!='0')
                    								{
                    									echo '
                    										<hr />
                    										<label class="control-label bolder blue" for="view">'.T_('Vues personnelles').': </label>
                        									<i title="'.T_('associe des catégories à l\'utilisateur').'" class="icon-question-sign blue bigger-110"></i>
                    										<div class="space-4"></div>';
                    											//check if selected user have view
																$qry = $db->prepare("SELECT `id` FROM `tviews` WHERE uid=:uid");
																$qry->execute(array(
																	'uid' => $_GET['userid']
																	));
																$row=$qry->fetch();
																$qry->closeCursor();
                    											if ($row[0]!='')
                    											{
                    												//display active views
																	$qry = $db->prepare("SELECT * FROM `tviews` WHERE uid=:uid ORDER BY uid");
																	$qry->execute(array(
																		'uid' => $_GET['userid']
																		));
                    												while ($row = $qry->fetch())
                    												{
																		//get cat name
																		$qry2 = $db->prepare("SELECT `name` FROM `tcategory` WHERE id=:id");
																		$qry2->execute(array(
																			'id' => $row['category']
																			));
																		$cname=$qry2->fetch();
																		$qry2->closeCursor();
																		
                    													if ($row['subcat']!='')
                    													{
																			$qry2 = $db->prepare("SELECT `name` FROM `tsubcat` WHERE id=:id");
																			$qry2->execute(array(
																				'id' => $row['subcat']
																				));
																			$sname=$qry2->fetch();
																			$qry2->closeCursor();
                    													} else {$sname='';}
                    													echo '<i class="icon-caret-right blue"></i> '.$row['name'].': ('.$cname['name'].' > '.$sname[0].') 
                    													<a title="'.T_('Supprimer cette vue').'" href="index.php?page=admin/user&subpage=user&action=edit&userid='.$_GET['userid'].'&viewid='.$row['id'].'&deleteview=1"><i class="icon-trash red bigger-120"></i></a>
                    													<br />';
                    												}
																	$qry->closeCursor();
                    												echo '<br />';
                    											}
                    											//display add view form
                    											echo '
                    												'.T_('Catégorie').':
                    												<select name="category" onchange="submit()" style="width:100px" >
                    													<option value="%"></option>';
																		//case to limit service parameters is enable
																		if ($rparameters['user_limit_service']==1 && $rright['admin']==0)
																		{
																			$qry = $db->prepare("SELECT `id`,`name` FROM `tcategory` WHERE `service` IN (SELECT `service_id` FROM `tusers_services` WHERE `user_id`=:user_id) ORDER BY `name`");
																			$qry->execute(array(
																				'user_id' => $_SESSION['user_id']
																				));
																		} else {
																			$qry = $db->prepare("SELECT `id`,`name` FROM `tcategory` ORDER BY `name`");
																			$qry->execute();
																		}
                    													while ($row=$qry->fetch())
                    													{
                    														echo "<option value=\"$row[id]\">$row[name]</option>";
                    														if ($_POST['category']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
                    													} 
																		$qry->closeCursor();
                    													echo '
                    												</select>
                    												<div class="space-4"></div>
																	';
																	if($_POST['category']!='%')
																	{
																		echo '
																		'.T_('Sous-catégorie').':
																		<select name="subcat" onchange="submit()" style="width:90px">
																			<option value="%"></option>';
																			$qry = $db->prepare("SELECT `id`,`name` FROM `tsubcat` WHERE `cat`=:cat ORDER BY `name`");
																			$qry->execute(array(
																				'cat' => $_POST['category']
																				));
																			while ($row = $qry->fetch())
																			{
																				echo "<option value=\"$row[id]\">$row[name]</option>";
																				if ($_POST['subcat']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
																			} 
																			$qry->closeCursor();
																			echo '
																		</select>';
																	}
																	echo '
                    												<div class="space-4"></div>
                    												Nom: <input name="viewname" type="" value="'.$_POST['name'].'" size="20" />';
                    											
                    											//display default ticket state
                    										    echo '
                        										    <hr />
                        										    <label class="control-label bolder blue" for="default_ticket_state">'.T_('État par défaut à la connexion').'</label>
                        										    <i title="'.T_("État qui est directement affiché, lors de la connexion à l'application, si ce paramètre n'est pas renseigné, alors l'état par défaut est celui définit par l'administrateur").'." class="icon-question-sign blue bigger-110"></i>
                        										    <div class="space-4"></div>
                        										    <select name="default_ticket_state">
                                    									<option '; if ($user1['default_ticket_state']==''){echo "selected";} echo ' value="">'.T_('Aucun (Géré par l\'administrateur)').'</option>';
																		if($rparameters['meta_state']==1) 
																		{
																			echo '<option '; if ($user1['default_ticket_state']=='meta'){echo "selected";} echo ' value="meta">'.T_('Vos tickets à traiter').'</option>';
																			echo '<option '; if ($user1['default_ticket_state']=='meta_all'){echo 'selected';} echo ' value="meta_all">'.T_('Tous les tickets à traiter').'</option>';
																		}
                                                                        $qry = $db->prepare("SELECT `id`,`name` FROM `tstates` ORDER BY `number`");
																		$qry->execute();
                    													while ($row = $qry->fetch())
                    													{
                    														if ($user1['default_ticket_state']==$row['id']) {echo '<option selected value="'.$row['id'].'">'.T_('Vos tickets').' '.$row['name'].'</option>';} else {echo '<option value="'.$row['id'].'">'.T_('Vos tickets').' '.$row['name'].'</option>';}
                    													}
																		$qry->closeCursor();
                    													echo '
																		<option '; if ($user1['default_ticket_state']=='all'){echo 'selected';} echo ' value="all">'.T_('Tous les tickets').'</option>
                                    								</select>
                    										    ';
                    										    //display default ticket order
                    										    echo '
                        										    <hr />
                        										    <label class="control-label bolder blue" for="dashboard_ticket_order">'.T_('Ordre de trie personnel par défaut').':</label>
                        										    <i title="'.T_('Modifie l\'ordre de trie des tickets dans la liste des tickets, si ce paramètre n\'est pas renseigné, c\'est le réglage par défaut dans la section administration qui est prit en compte').'." class="icon-question-sign blue bigger-110"></i>
                        										    <div class="space-4"></div>
                        										    <select name="dashboard_ticket_order">
                        										        <option '; if ($user1['default_ticket_state']==''){echo "selected";} echo ' value="">'.T_('Aucun (Géré par l\'administrateur)').'</option>
                                    									<option '; if ($user1['dashboard_ticket_order']=='tstates.number, tincidents.priority, tincidents.criticality, tincidents.date_create'){echo "selected";} echo ' value="tstates.number, tincidents.priority, tincidents.criticality, tincidents.date_create">'.T_('État  > Priorité > Criticité > Date de création').'</option>
                                    									<option '; if ($user1['dashboard_ticket_order']=='tstates.number, tincidents.priority, tincidents.criticality, tincidents.date_hope'){echo "selected";} echo ' value="tstates.number, tincidents.priority, tincidents.criticality, tincidents.date_hope">'.T_('État > Priorité > Criticité > Date de résolution estimé').'</option>
                                    									<option '; if ($user1['dashboard_ticket_order']=='tincidents.date_hope'){echo "selected";} echo ' value="tincidents.date_hope"> '.T_('Date de résolution estimé').'</option>
                                    									<option '; if ($user1['dashboard_ticket_order']=='tincidents.priority'){echo "selected";} echo ' value="tincidents.priority"> '.T_('Priorité').'</option>
                                    									<option '; if ($user1['dashboard_ticket_order']=='tincidents.criticality'){echo "selected";} echo ' value="tincidents.criticality"> '.T_('Criticité').'</option>
                                    									<option '; if ($user1['dashboard_ticket_order']=='id'){echo "selected";} echo ' value="id">'.T_('Numéro de ticket').'</option>
                                    								</select>
                    										    ';	
                    								    }
														//display ticket limit parameters
														if ($rparameters['user_limit_ticket']==1 )
														{
															if($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2) $readonly='readonly'; else $readonly='';
															echo '
																<hr />
																<label class="control-label bolder blue" for="limit_ticket_number">'.T_('Limite de tickets').':</label>
																<i title="'.T_('Permet de limiter un utilisateur a un nombre de ticket définit, passer la limite l\'ouverture de nouveau ticket n\'est plus possible').'." class="icon-question-sign blue bigger-110"></i>
																<div class="space-4"></div>
																<label for="limit_ticket_number">'.T_('Nombre limite de ticket').':</label>
																<input '.$readonly.' size="3" name="limit_ticket_number" type="text" value="'; if($user1['limit_ticket_number']) echo "$user1[limit_ticket_number]"; else echo ""; echo'" />
																<div class="space-4"></div>
																<label for="limit_ticket_days">'.T_('Durée de validé jours').':</label>
																<input '.$readonly.' size="4" name="limit_ticket_days" type="text" value="'; if($user1['limit_ticket_days']) echo "$user1[limit_ticket_days]"; else echo ""; echo'" />
																<div class="space-4"></div>
																<label for="limit_ticket_date_start">'.T_('Date de début de validité (YYYY-MM-DD)').':</label>
																<input '.$readonly.' size="10" name="limit_ticket_date_start" type="text" value="'; if($user1['limit_ticket_date_start']) echo "$user1[limit_ticket_date_start]"; else echo ""; echo'" />
															';
														}
                    								echo'
                                            </div>
                                            <div id="infos" class="tab-pane'; if ($_GET['tab']=='infos' || $_GET['tab']=='') echo 'active'; echo '">
                                    			<label for="firstname">'.T_('Prénom').':</label>
                								<input name="firstname" type="text" value="'; if($user1['firstname']) echo "$user1[firstname]"; else echo ""; echo'" />
                								<div class="space-4"></div>
                								<label for="lastname">'.T_('Nom').':</label>
                								<input name="lastname" type="text" value="'; if($user1['lastname']) echo "$user1[lastname]"; else echo ""; echo'" />
                								<div class="space-4"></div>';
                								//not display login field for users for security
                								if ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==4) $hide=''; else $hide='hidden';
                                                echo '
                								<label '.$hide.' for="login">'.T_('Identifiant').':</label>
                								<input autocomplete="off" '.$hide.' name="login" type="text" value="'; if($user1['login']) echo "$user1[login]"; else echo ""; echo'"  />
                								<div class="space-4"></div>
                								<label for="password">'.T_('Mot de passe').':</label>
                								<input  name="password" type="password" value="'; if($user1['password']) echo "$user1[password]"; else echo ""; echo'" />
                								<div class="space-4"></div>
                								<label for="mail">'.T_('Adresse mail').':</label>
                								<input name="mail" type="text" value="'; if($user1['mail']) echo "$user1[mail]"; else echo ""; echo'" />
                								<div class="space-4"></div>
                								<label for="phone">'.T_('Téléphone').':</label>
                								<input name="phone" type="text" value="'; if($user1['phone']) echo "$user1[phone]"; else echo ""; echo'" />
                								<div class="space-4"></div>
                								<label for="fax">'.T_('Fax').':</label>
                								<input name="fax" type="text" value="'; if($user1['fax']) echo "$user1[fax]"; else echo ""; echo'" />
                								<div class="space-4"></div>
                								<label for="service_1">'.T_('Service').':</label>
												';
												
												//service part
												$cnt=0;
												$qry = $db->prepare("SELECT `id`,`service_id` FROM `tusers_services` WHERE `user_id`=:user_id ");
												$qry->execute(array('user_id' => $user1['id']));
												while ($row=$qry->fetch())
												{
													$cnt++;
													echo'
													<select name="service_'.$cnt.'" '; if ($rright['user_profil_service']==0) {echo 'disabled="disabled"';} echo ' >
														<option value=""></option>';
														$qry2 = $db->prepare("SELECT `id`,`name` FROM `tservices` ORDER BY `name`");
														$qry2->execute();
														while ($row2 = $qry2->fetch())
														{
															if ($row2['id']==$row['service_id'] ) echo "<option selected value=\"$row2[id]\">$row2[name]</option>"; else echo "<option value=\"$row2[id]\">$row2[name]</option>";
														} 
														$qry2->closeCursor();
														echo '
													</select>
													';
													if ($rright['user_profil_service']!=0) {echo '<a href="./index.php?page=admin&subpage=user&action=edit&userid='.$_GET['userid'].'&tab=infos&delete_assoc_service='.$row['id'].'"><i title="'.T_("Supprimer l'association de ce service avec cet utilisateur").'" class="icon-trash red bigger-130"></i></a>';}
													
												}
												$qry->closeCursor();
												if($cnt==0) //case no service associated
												{
													echo '
													<select name="service_1" '; if ($rright['user_profil_service']==0) {echo 'disabled="disabled"';} echo ' >
														<option value=""></option>';
														$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY name");
														$qry->execute(array('disable' => 0));
														while ($row = $qry->fetch())
														{
															echo "<option value=\"$row[id]\">$row[name]</option>";
														} 
														$qry->closeCursor();
														echo '
													</select>
													';
												}
												//agency part
												if($rparameters['user_agency'])
												{
													echo '<div class="space-4"></div>
													<label for="agency_1">'.T_('Agence').':</label>
													';
													$cnt=0;
													$qry = $db->prepare("SELECT `id`,`agency_id` FROM `tusers_agencies` WHERE `user_id`=:user_id");
													$qry->execute(array('user_id' => $user1['id']));
													while ($row=$qry->fetch())
													{
														$cnt++;
														echo'
														<select name="agency_'.$cnt.'" '; if ($rright['user_profil_agency']==0) {echo 'disabled="disabled"';} echo ' >
															<option value=""></option>';
															$qry2 = $db->prepare("SELECT `id`,`name` FROM `tagencies` ORDER BY `name`");
															$qry2->execute();
															while ($row2 = $qry2->fetch())
															{
																if ($row2['id']==$row['agency_id'] ) echo "<option selected value=\"$row2[id]\">$row2[name]</option>"; else echo "<option value=\"$row2[id]\">$row2[name]</option>";
															} 
															$qry2->closeCursor();
															echo '
														</select>
														';
														if ($rright['user_profil_agency']!=0) { echo'<a href="./index.php?page=admin&subpage=user&action=edit&userid='.$_GET['userid'].'&tab=infos&delete_assoc_agency='.$row['id'].'"><i title="'.T_("Supprimer l'association de cette agence avec cet utilisateur").'" class="icon-trash red bigger-130"></i></a>';}
														
													}
													$qry->closeCursor();
													if($cnt==0) //case no service associated
													{
														echo '
														<select name="agency_1" '; if ($rright['user_profil_agency']==0) {echo 'disabled="disabled"';} echo ' >
															<option value=""></option>';
															$qry = $db->prepare("SELECT `id`,`name` FROM `tagencies` WHERE disable=:disable ORDER BY `name`");
															$qry->execute(array('disable' => 0));
															while ($row = $qry->fetch())
															{
																echo "<option value=\"$row[id]\">$row[name]</option>";
															} 
															$qry->closeCursor();
															echo '
														</select>
														';
													}
												}
												
												echo '
                								<div class="space-4"></div>
                								<label for="function">'.T_('Fonction').':</label>
                								<input name="function" size="25" type="text" value="'; if($user1['function']) {echo $user1['function'];} echo '" />
                								';
                								//display advanced user informations
                								if ($rparameters['user_advanced']!='0')
                								{
                								echo '
                									<div class="space-4"></div>
                									<label for="company">'.T_('Société').':</label>
                									<select name="company" '; if ($rright['user_profil_company']==0) {echo 'disabled="disabled"';} echo '>
                    									';
														$qry = $db->prepare("SELECT `id`,`name` FROM `tcompany` ORDER BY `name`");
														$qry->execute();
                    									while ($row = $qry->fetch())
                    									{
                    										if ($user1['company']==$row['id']) {echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option>';} else {echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';}
                    									} 
														$qry->closeCursor();
                    									echo '
                							    	</select>
													';
													//send company value in disabled state
													if ($rright['user_profil_company']==0)
													{
														echo '<input type="hidden" name="company" value="'.$user1['company'].'" />';
													}
													echo '
                									<div class="space-4"></div>
                									<label for="address1">'.T_('Adresse').' 1:</label>
                									<input name="address1" type="text" value="'; if($user1['address1']) echo "$user1[address1]"; else echo ""; echo'" />
                									<div class="space-4"></div>
                									<label  for="address2">'.T_('Adresse').' 2:</label>
                									<input name="address2" type="text" value="'; if($user1['address2']) echo "$user1[address2]"; else echo ""; echo'" />
                									<div class="space-4"></div>
                									<label  for="city">'.T_('Ville').':</label>
                									<input name="city" type="text" value="'; if($user1['city']) echo "$user1[city]"; else echo ""; echo'" />
                									<div class="space-4"></div>
                									<label for="zip">'.T_('Code Postal').':</label>
                									<input name="zip" type="text" value="'; if($user1['zip']) echo "$user1[zip]"; else echo ""; echo'" />
													<div class="space-4"></div>
                									<label for="custom1">'.T_('Champ personnalisé').' 1:</label>
                									<input name="custom1" type="text" value="'; if($user1['custom1']) echo "$user1[custom1]"; else echo ""; echo'" />
                									<div class="space-4"></div>
                									<label for="custom2">'.T_('Champ personnalisé').' 2:</label>
                									<input name="custom2" type="text" value="'; if($user1['custom2']) echo "$user1[custom2]"; else echo ""; echo'" />
                									<div class="space-4"></div>
                								';
                								}
                                            	echo '		
                            			    </div>
                            			</div>
                            		</div>
                            	</div>
							</fieldset>
							<div class="form-actions center">
								<button value="Modifier" id="Modifier" name="Modifier" type="submit"  class="btn btn-sm btn-success">
									<i class="icon-ok-sign bigger-120"></i>
									'.T_('Modifier').'
								</button>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<button name="cancel" value="cancel" type="submit" class="btn btn-sm btn-danger" >
									<i class="icon-reply bigger-120"></i>
									'.T_('Retour').'
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	';
}
else if ($_GET['action']=="add" && (($_SESSION['user_id']==$_GET['userid']) || ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==4)))
{
	//remove '' to display
	$_POST['firstname']=str_replace("'","",$_POST['firstname']);
	$_POST['lastname']=str_replace("'","",$_POST['lastname']);
	$_POST['login']=str_replace("'","",$_POST['login']);
	$_POST['password']=str_replace("'","",$_POST['password']);
	$_POST['mail']=str_replace("'","",$_POST['mail']);
	$_POST['phone']=str_replace("'","",$_POST['phone']);
	$_POST['fax']=str_replace("'","",$_POST['fax']);
	$_POST['function']=str_replace("'","",$_POST['function']);
	
	/////////////////////////////////////////////////////////////////////display add form///////////////////////////////////////////////////
	echo '
		<div class="col-sm-5">
			<div class="widget-box">
				<div class="widget-header">
					<h4>'.T_('Ajout d\'un utilisateur').':</h4>
					<span class="widget-toolbar">
						<button title="'.T_('Ajouter un utilisateur').'" value="Ajouter" id="Ajouter" name="Ajouter" type="submit" form="1" class="btn btn-minier btn-success">
							<i class="icon-save bigger-140"></i>
						</button>
					</span>
				</div>
				<div class="widget-body">
					<div class="widget-main no-padding">
						<form id="1" name="form" method="POST"  action="">
							<fieldset>
								<label for="firstname">'.T_('Prénom').':</label>
								<input name="firstname" type="text" value="'.$_POST['firstname'].'" />
								<div class="space-4"></div>
								<label  for="lastname">'.T_('Nom').':</label>
								<input name="lastname" type="text" value="'.$_POST['lastname'].'" />
								<div class="space-4"></div>
								<label for="login">'.T_('Identifiant').':</label>
								<input name="login" type="text" value="'.$_POST['login'].'" />
								<div class="space-4"></div>
								<label  for="password">'.T_('Mot de passe').':</label>
								<input name="password" type="password" value="'.$_POST['password'].'" />
								<div class="space-4"></div>
								<label for="mail">'.T_('Adresse mail').':</label>
								<input name="mail" type="text" value="'.$_POST['mail'].'" />
								<div class="space-4"></div>
								<label  for="phone">'.T_('Téléphone').':</label>
								<input name="phone" type="text" value="'.$_POST['phone'].'" />
								<div class="space-4"></div>
								<label  for="fax">'.T_('Fax').':</label>
								<input name="fax" type="text" value="'.$_POST['fax'].'" />
								<div class="space-4"></div>
								<label for="service">'.T_('Service').':</label>
								<select  name="service" '; if ($rright['user_profil_service']==0) {echo 'disabled="disabled"';} echo '>
									<option value=""></option>';
									$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` ORDER BY `name`");
									$qry->execute();
									while ($row = $qry->fetch()) 
									{
										if($_POST['service']) {echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option>';} else {echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';}
									} 
									$qry->closeCursor();
									echo '
								</select>
								';
								if($rparameters['user_agency'])
								{
									echo '
									<div class="space-4"></div>
									<label for="agency">'.T_('Agence').':</label>
									<select  name="agency" '; if ($rright['user_profil_agency']==0) {echo 'disabled="disabled"';} echo '>
										<option value=""></option>';
										$qry = $db->prepare("SELECT `id`,`name` FROM `tagencies` ORDER BY `name`");
										$qry->execute();
										while ($row = $qry->fetch()) 
										{
											if($_POST['agency']) {echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option>';} else {echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';}
										} 
										$qry->closeCursor();
										echo '
									</select>
									';
								}
								echo '
								<div class="space-4"></div>
								<label for="function">'.T_('Fonction').':</label>
								<input name="function" type="text" value="'.$_POST['function'].'" />
								';
								//display advanced user informations
								if ($rparameters['user_advanced']!='0')
								{
								echo '
									<div class="space-4"></div>
									<label  for="company">'.T_('Société').':</label>
									<select name="company" '; if ($rright['user_profil_company']==0) {echo 'disabled="disabled"';} echo '>
    									<option value=""></option>';
										$qry = $db->prepare("SELECT `id`,`name` FROM `tcompany` ORDER BY `name`");
										$qry->execute();
    									while ($row = $qry->fetch()) 
    									{
    										if ($user1['company']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
    									} 
										$qry->closeCursor(); 
    									echo '
							    	</select>
									
									<div class="space-4"></div>
									<label for="address1">'.T_('Adresse').' 1:</label>
									<input name="address1" type="text" value="" />
									<div class="space-4"></div>
									<label for="address2">'.T_('Adresse').' 2:</label>
									<input name="address2" type="text" value="" />
									<div class="space-4"></div>
									<label for="city">'.T_('Ville').':</label>
									<input name="city" type="text" value="" />
									<div class="space-4"></div>
									<label for="zip">'.T_('Code Postal').':</label>
									<input name="zip" type="text" value="" />
									<div class="space-4"></div>
									<label for="custom1">'.T_('Champ personnalisé').' 1:</label>
									<input name="custom1" type="text" value="" />
									<div class="space-4"></div>
									<label for="custom2">'.T_('Champ personnalisé').' 2:</label>
									<input name="custom2" type="text" value="" />
								';
								}
								//display theme selection
								echo '
								<hr />
								<label class="control-label bolder blue" for="skin">'.T_('Thème').':</label>
								<select name="skin">
									<option style="background-color:#438EB9;" value="">'.T_('Bleu (Défaut)').'</option>
									<option style="background-color:#222A2D;" value="skin-1">'.T_('Noir').'</option>
									<option style="background-color:#C6487E;" value="skin-2">'.T_('Rose').'</option>
									<option style="background-color:#D0D0D0;" value="skin-3">'.T_('Gris').'</option>
								</select>
								';
								// Display profile list
								if ($rright['admin_user_profile']!='0')
								{
									echo '
									<hr />
									<label class="control-label bolder blue" for="profile">'.T_('Profil').':</label>
									<div class="controls">
										<div class="radio">
											<label>
												<input type="radio" class="ace" name="profile" value="4"> <span class="lbl"> '.T_('Administrateur').' </span>
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" class="ace" name="profile" value="0"> <span class="lbl"> '.T_('Technicien').' </span>
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" class="ace" name="profile" value="3"> <span class="lbl"> '.T_('Superviseur').' </span>
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" class="ace" name="profile" value="1"> <span class="lbl"> '.T_('Utilisateur avec pouvoir').' </span>
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" class="ace" name="profile" checked value="2"> <span class="lbl"> '.T_('Utilisateur').' </span>
											</label>
										</div>
									</div>
									<hr />
									<label class="control-label bolder blue" for="chgpwd">'.T_('Forcer le changement du mot de passe').':</label>
									<div class="controls">
										<label>
											<input type="radio" class="ace" disable="disable" name="chgpwd" value="1"> <span class="lbl"> '.T_('Oui').' </span>
											<input type="radio" class="ace" name="chgpwd" checked value="0"> <span class="lbl"> '.T_('Non').' </span>
										</label>
									</div>
									';
								}
								else
								{
									echo '<input type="hidden" name="profile" value="">';
								}
								//display personal view
								if ($rright['admin_user_view']!='0')
								{
									echo '
										<hr />
										<label class="control-label bolder blue" for="view">'.T_('Vues personnelles').': <i>('.T_('associe des catégories à l\'utilisateur').')</i></label>
										<div class="controls">
											';
											//check if connected user have view
											$qry = $db->prepare("SELECT `id` FROM `tviews` WHERE uid=:uid");
											$qry->execute(array('uid' => $_GET['userid']));
											$row=$qry->fetch();
											$qry->closeCursor(); 
											if ($row[0]!='')
											{
												//display actives views
												$qry = $db->prepare("SELECT * FROM `tviews` WHERE uid=:uid ORDER BY uid");
												$qry->execute(array('uid' => $_GET['userid']));
												while ($row = $qry->fetch())
												{
													$qry2 = $db->prepare("SELECT `name` FROM `tcategory` WHERE id=:id");
													$qry2->execute(array('id' => $row['category']));
													$cname=$qry2->fetch();
													$qry2->closeCursor(); 
													
													if ($row['subcat']!=0)
													{
														$qry2 = $db->prepare("SELECT `name` FROM `tsubcat` WHERE id=:id");
														$qry2->execute(array('id' => $row['subcat']));
														$sname= $qry2->fetch();
														$qry2->closeCursor(); 
													} else {$sname='';}
													echo '- '.$row['name'].': ('.$cname['name'].' > '.$sname[0].') 
													<a title="'.T_('Supprimer cette vue').'" href="index.php?page=admin&subpage=user&action=edit&userid='.$_GET['userid'].'&viewid='.$row['id'].'&deleteview=1"><img alt="delete" src="./images/delete.png" style="border-style: none" /></a>
													<br />';
												}
												$qry->closeCursor(); 
												echo '<br />';
											}
											//display add view form
											echo '
												'.T_('Catégorie').':
												<select name="category" onchange="submit()" style="width:100px" >
													<option value="%"></option>';
													$qry = $db->prepare("SELECT `id`,`name` FROM `tcategory` ORDER BY name");
													$qry->execute();
													while ($row = $qry->fetch()) 
													{
														echo "<option value=\"$row[id]\">$row[name]</option>";
														if ($_POST['category']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
													} 
													$qry->closeCursor();
													echo '
												</select>
												<br />
												'.T_('Sous-catégorie').':
												<select name="subcat" onchange="submit()" style="width:90px">
													<option value="%"></option>';
													if($_POST['category']!='%')
													{
														$qry = $db->prepare("SELECT `id`,`name` FROM `tsubcat` WHERE cat=:cat ORDER BY `name`");
														$qry->execute(array('cat' => $_POST['category']));
													}
													else
													{
														$qry = $db->prepare("SELECT `id`,`name` FROM `tsubcat` ORDER BY `name`");
														$qry->execute();
													}
													while ($row = $qry->fetch())
													{
														echo "<option value=\"$row[id]\">$row[name]</option>";
														if ($_POST['subcat']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
													} 
													$qry->closeCursor();
													echo '
												</select>
												<br />
												'.T_('Nom').': <input name="viewname" type="" value="'.$_POST['name'].'" size="20" />
										</div>';
								}
								echo'
							</fieldset>
							<div class="form-actions center">
								<button value="Ajouter" id="Ajouter" name="Ajouter" type="submit" class="btn btn-sm btn-success">
									<i class="icon-ok-sign bigger-120"></i>
									'.T_('Ajouter').'
								</button>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<button name="cancel" value="cancel" type="submit" class="btn btn-sm btn-danger" >
									<i class="icon-reply bigger-120"></i>
									'.T_('Retour').'
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	';
}
elseif ($_GET['action']=="delete" && $rright['admin']!='0')
{
	$qry=$db->prepare("UPDATE `tusers` SET `disable`=:disable WHERE `id`=:id");
	$qry->execute(array(
		'disable' => 1,
		'id' => $_GET['userid']
		));
	//home page redirection
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
elseif ($_GET['action']=="disable" && $rright['admin']!='0')
{
	$qry=$db->prepare("UPDATE `tusers` SET `disable`=:disable WHERE `id`=:id");
	$qry->execute(array(
		'disable' => 1,
		'id' => $_GET['userid']
		));
	//home page redirection
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
else if ($_GET['action']=="enable" && $rright['admin']!='0')
{
	$qry=$db->prepare("UPDATE `tusers` SET `disable`=:disable WHERE `id`=:id");
	$qry->execute(array(
		'disable' => 0,
		'id' => $_GET['userid']
		));
	//home page redirection
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
elseif ($_GET['ldap']=="1")
{
	include('./core/ldap.php');
} elseif ($_GET['ldap']=="agencies")
{
	include('./core/ldap_agencies.php');
} elseif ($_GET['ldap']=="services")
{
	include('./core/ldap_services.php');
}
//display security warning for user who want access to edit another user profile
elseif (($_GET['action']=='edit') && ($_GET['userid']!='') && ($_SESSION['profile_id']!=0 || $_SESSION['profile_id']!=4) && ($_GET['userid']!=$_SESSION['user_id']))
{
   echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas le droit d'accéder au profil d'un autre utilisateur").'.</div>';
}
//display security warning for user who want access to add another user profile
elseif (($_GET['action']=='add') && ($_SESSION['profile_id']!=0 || $_SESSION['profile_id']!=4) && ($_GET['userid']!=$_SESSION['user_id']))
{
   echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas le droit d\'ajouter des utilisateurs').'.</div>';
}

//else display users list
else
{
	//display buttons
	echo '
		<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
			<p>
				<button onclick=\'window.location.href="index.php?page=admin&subpage=user&action=add";\' class="btn btn-sm btn-success">
					<i class="icon-plus"></i> '.T_('Ajouter un utilisateur').'
				</button>
				';
				if($_GET['disable']==0)
				{
			    	echo '
    				<button onclick=\'window.location.href="index.php?page=admin&subpage=user&disable=1";\' class="btn btn-sm btn-danger">
    					<i class="icon-ban-circle"></i> '.T_('Afficher les utilisateurs désactivés').'
    				</button>
			    	';
				} else {
			    echo '
    				<button onclick=\'window.location.href="index.php?page=admin&subpage=user&disable=0";\' class="btn btn-sm btn-success">
    					<i class="icon-ok"></i> '.T_('Afficher les utilisateurs activés').'
    				</button>
			    	';  
				}

		if($rparameters['ldap']==1 && $rparameters['ldap_agency']==0)
		{
			echo '
				<button onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;ldap=1";\' class="btn btn-sm btn-info">
					<i class="icon-refresh"></i> '.T_('Synchronisation LDAP').'
				</button>
			';
		}
		if($rparameters['ldap']==1 && $rparameters['ldap_agency']==1)
		{
			echo '
				<button onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;ldap=agencies";\' class="btn btn-sm btn-info">
					<i class="icon-refresh"></i> '.T_('Synchronisation des agences LDAP').'
				</button>
			';
		}
		if($rparameters['ldap']==1 && $rparameters['ldap_service']==1)
		{
			echo '
				<button onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;ldap=services";\' class="btn btn-sm btn-info">
					<i class="icon-refresh"></i> '.T_('Synchronisation des services LDAP').'
				</button>
			';
		}
	echo'
			</p>
		</div>
		<br />	
	';
	//Display user table
	if($_GET['way']=='DESC') $nextway='ASC'; else $nextway='DESC'; //find next way
	echo '
		<table id="sample-table-1" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=login&way='.$nextway.'">
					        <i class="icon-user"></i> '.T_('Identifiant').'&nbsp;&nbsp;
					        ';
					            if($_GET['order']=='login')
					            {
								   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
								   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
					            }
					        echo '
			            </a>
					</th>
					<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=lastname&way='.$nextway.'">
					        <i class="icon-male"></i> '.T_('Nom Prénom').'&nbsp;&nbsp;
					        ';
					            if($_GET['order']=='lastname')
					            {
								   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
								   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
					            }
					        echo '
			            </a>
					</th>
					';
					if ($rparameters['user_advanced']==1)
					{
						echo '
						<th>
							<a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=company&way='.$nextway.'">
								<i class="icon-building "></i> '.T_('Société').'&nbsp;&nbsp;
								';
									if($_GET['order']=='company')
									{
									   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
									   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
									}
								echo '
							</a>
						</th>
						';
					}
					if ($rparameters['user_agency']==1)
					{
						echo '
						<th>
							<a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=tagencies.name&way='.$nextway.'">
								<i class="icon-globe "></i> '.T_('Agences').'&nbsp;&nbsp;
								';
									if($_GET['order']=='tagencies.name')
									{
									   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
									   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
									}
								echo '
							</a>
						</th>
						';
					}
					echo '
					<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=tservices.name&way='.$nextway.'">
					        <i class="icon-group"></i> '.T_('Services').'&nbsp;&nbsp;
					        ';
					            if($_GET['order']=='tservices.name')
					            {
								   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
								   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
					            }
					        echo '
					</th>
					<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=mail&way='.$nextway.'">
					        <i class="icon-envelope-alt"></i> '.T_('Adresse Mail').'&nbsp;&nbsp;
					        ';
					            if($_GET['order']=='mail')
					            {
								   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
								   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
					            }
					        echo '
					</th>
					<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=phone&way='.$nextway.'">
					        <i class="icon-phone"></i> '.T_('Téléphone').'&nbsp;&nbsp;
					        ';
					            if($_GET['order']=='phone')
					            {
								   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
								   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
					            }
					        echo '
			            </a>
					</th>
						<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=profile&way='.$nextway.'">
					        <i class="icon-lock"></i> '.T_('Profil').'&nbsp;&nbsp;
					        ';
					            if($_GET['order']=='profile')
					            {
								   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
								   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
					            }
					        echo '
			            </a>
					</th>
					<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=last_login&way='.$nextway.'">
					        <i class="icon-key"></i> '.T_('Dernière connexion').'&nbsp;&nbsp;
					        ';
					            if($_GET['order']=='last_login')
					            {
								   if($_GET['way']=='ASC')  echo '<i class="icon-sort-up"></i>';
								   if($_GET['way']=='DESC') echo '<i class="icon-sort-down"></i>';
					            }
					        echo '
			            </a>
					</th>
					<th>
					    <a href="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'&order=lastname&way='.$nextway.'">
					        <i class="icon-play"></i> '.T_('Actions').'&nbsp;&nbsp;
			            </a>
					</th>
				</tr>
			</thead>
			<tbody>';
				//build query
				$from='tusers';
				$where='';
				if($rparameters['user_agency']) {
					$join="LEFT OUTER JOIN tusers_agencies ON tusers_agencies.user_id=tusers.id LEFT OUTER JOIN tagencies ON tagencies.id=tusers_agencies.agency_id ";
					
				} else {$join="";}
				$join.="LEFT OUTER JOIN tusers_services ON tusers_services.user_id=tusers.id LEFT OUTER JOIN tservices ON tservices.id=tusers_services.service_id ";
				$where.="
				profile LIKE :profile AND
				tusers.id!=:id AND
				tusers.disable=:disable AND
				(
				";
				if($rparameters['user_agency']) {$where.="tagencies.name LIKE :agency_name OR";}
				$where.="
    				tusers.lastname LIKE :lastname OR
    				tusers.firstname LIKE :firstname OR
    				tusers.mail LIKE :mail OR
    				tusers.phone LIKE :phone OR
    				tusers.login LIKE :login OR
    				tservices.name LIKE :service_name
				)
				ORDER BY $db_order $db_way
				LIMIT $db_cursor,$maxline ";
				if ($rparameters['debug']==1) {
					echo "
					<b><u>DEBUG MODE:</u></b><br />
					SELECT distinct tusers.* 
					FROM $from
					$join
					WHERE $where
					";
				}
				//build each line
				
				$qry = $db->prepare("
					SELECT distinct tusers.* 
					FROM $from
					$join
					WHERE $where
					");
				
				if($rparameters['user_agency']) //agency case add name in searchengine
				{
					$qry->execute(array(
						'profile' => $_GET['profileid'],
						'id' => 0,
						'disable' => $_GET['disable'],
						'agency_name' => "%$userkeywords%",
						'lastname' => "%$userkeywords%",
						'firstname' => "%$userkeywords%",
						'mail' => "%$userkeywords%",
						'phone' => "%$userkeywords%",
						'login' => "%$userkeywords%",
						'service_name' => "%$userkeywords%",
					));
				} else {
					$qry->execute(array(
						'profile' => $_GET['profileid'],
						'id' => 0,
						'disable' => $_GET['disable'],
						'lastname' => "%$userkeywords%",
						'firstname' => "%$userkeywords%",
						'mail' => "%$userkeywords%",
						'phone' => "%$userkeywords%",
						'login' => "%$userkeywords%",
						'service_name' => "%$userkeywords%",
					));
				}
				
				while ($row = $qry->fetch()) 
				{
					//find profile name
					$qry2 = $db->prepare("SELECT `name` FROM `tprofiles` WHERE level=:level");
					$qry2->execute(array('level' => $row['profile']));
					$r=$qry2->fetch();
					$qry2->closeCursor();
					//display last login if exist
					if($row['last_login']=='0000-00-00 00:00:00') $lastlogin=''; else $lastlogin=$row['last_login'];
					//display line
					echo '
						<tr>
							<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >'.$row['login'].'</td>
							<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >'.$row['lastname'].' '.$row['firstname'].' </td>
							';
							if ($rparameters['user_advanced']==1) {
								//get company name
								$qry2 = $db->prepare("SELECT `name` FROM `tcompany` WHERE id=:id");
								$qry2->execute(array('id' => $row['company']));
								$row2=$qry2->fetch();
								$qry2->closeCursor();
								echo '<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >'.$row2['name'].'</td>';
							}
							if ($rparameters['user_agency']==1) {
								//get agencies name
								echo '<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >';
								$qry2 = $db->prepare("SELECT `agency_id` FROM `tusers_agencies` WHERE user_id=:user_id");
								$qry2->execute(array('user_id' => $row['id']));
								while ($row2=$qry2->fetch())
								{
									$qry3 = $db->prepare("SELECT `name` FROM `tagencies` WHERE id=:id");
									$qry3->execute(array('id' => $row2['agency_id']));
									$row3 = $qry3->fetch();
									echo "$row3[name]<br />";
									$qry3->closecursor();
								}
								$qry2->closecursor();
								echo '</td>';
							}
							echo '
							<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >
							';
								$qry2 = $db->prepare("SELECT `service_id` FROM `tusers_services` WHERE user_id=:user_id");
								$qry2->execute(array('user_id' => $row['id']));
								while ($row2=$qry2->fetch())
								{
									$qry3 = $db->prepare("SELECT `name` FROM `tservices` WHERE id=:id");
									$qry3->execute(array('id' => $row2['service_id']));
									$row3 = $qry3->fetch();
									echo "$row3[name]<br />";
									$qry3->closecursor();
								}
								$qry2->closecursor();
							echo'
							</td>
							<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >'.$row['mail'].'</td>
							<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >'.$row['phone'].'</td>
							<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >'.$r['name'].'</td>
							<td onclick=\'window.location.href="index.php?page=admin&subpage=user&action=edit&userid='.$row['id'].'&tab=infos";\' >'.$lastlogin.'</td>
							<td>
							<div class="hidden-phone visible-desktop btn-group">';
								if (($row['disable']!=1) && ($row['id']!=$_SESSION['user_id']))
								{
									echo '
										<button title="'.T_('Désactiver l\'utilisateur').'" onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;userid='.$row['id'].'&amp;action=disable";\' class="btn btn-xs btn-danger">
											<i class="icon-ban-circle bigger-120"></i>
										</button>
									';
								} elseif ($row['id']!=$_SESSION['user_id'])
								{
									echo '
									<button title="'.T_('Activer l\'utilisateur').'" onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;userid='.$row['id'].'&amp;action=enable";\' class="btn btn-xs btn-success">
										<i class="icon-ok bigger-120"></i>
									</button>
									';
								}
								echo '
									<button title="'.T_('Éditer l\'utilisateur').'" onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;action=edit&amp;userid='.$row['id'].'&tab=infos";\' class="btn btn-xs btn-warning">
										<i class="icon-pencil bigger-120"></i>
									</button>
							</div>
							</td>
						</tr>
					';
				}
				$qry->closecursor();
				echo '
			</tbody>
		</table>
	';
	//multi-pages link
	if (!$_GET['cursor'])
	{
		$qry = $db->prepare("
			SELECT COUNT(DISTINCT tusers.id)
			FROM $from
			$join
			WHERE $where
			");
		
		if($rparameters['user_agency']) //agency case add name in searchengine
		{
			$qry->execute(array(
				'profile' => $_GET['profileid'],
				'id' => 0,
				'disable' => $_GET['disable'],
				'agency_name' => "%$userkeywords%",
				'lastname' => "%$userkeywords%",
				'firstname' => "%$userkeywords%",
				'mail' => "%$userkeywords%",
				'phone' => "%$userkeywords%",
				'login' => "%$userkeywords%",
				'service_name' => "%$userkeywords%",
			));
		} else {
			$qry->execute(array(
				'profile' => $_GET['profileid'],
				'id' => 0,
				'disable' => $_GET['disable'],
				'lastname' => "%$userkeywords%",
				'firstname' => "%$userkeywords%",
				'mail' => "%$userkeywords%",
				'phone' => "%$userkeywords%",
				'login' => "%$userkeywords%",
				'service_name' => "%$userkeywords%",
			));
		}
	}
	else
	{
		$qry = $db->prepare("
		SELECT COUNT(DISTINCT tusers.id)
		FROM $from
		$join
		WHERE 
		tusers.disable=:disable
		");
		$qry->execute(array('disable' => $_GET['disable']));
	}
				
    $resultcount = $qry->fetch();
	if  ($resultcount[0]>$maxline)
	{
		//count number of page
		$pagenum=ceil($resultcount[0]/$maxline);
		echo '
		<center>
			<ul class="pagination">';
				for ($i = 1; $i <= $pagenum; $i++) {
					if ($i==1) $cursor=0; 
					$selectcursor=$maxline*($i-1);
					if ($_GET['cursor']==$selectcursor)
					{
						$active='class="active"';
					} else	$active='';
					if($_GET['searchengine']==1)
					{echo "<li > <a href=\"./index.php?page=admin&subpage=user&amp;order=$_GET[order]&amp;way=$_GET[way]&amp;disable=$_GET[disable]&amp;cursor=$cursor\">&nbsp;$i&nbsp;</a></li> ";}
					else
					{echo "<li $active><a href=\"./index.php?page=admin&subpage=user&amp;order=$_GET[order]&amp;way=$_GET[way]&amp;disable=$_GET[disable]&amp;cursor=$cursor\">&nbsp;$i&nbsp;</a></li> ";}
					$cursor=$i*$maxline;
				}
				echo '
			</ul>
		</center>
	';
	}
}
?>