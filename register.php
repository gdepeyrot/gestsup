<?php
################################################################################
# @Name : ./register.php 
# @Description : create gestsup user
# @Call : /index.php
# @Author : Flox
# @Version : 3.1.29
# @Create : 20/03/2014
# @Update : 18/12/2017
################################################################################

//init language
require('localization.php');

//initialize variable
if(!isset($message)) $message = '';
if(!isset($info)) $info = '';

if(!isset($_POST['login'])) $_POST['login'] = '';
if(!isset($_POST['password'])) $_POST['password'] = '';
if(!isset($_POST['password2'])) $_POST['password2'] = '';
if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['firstname'])) $_POST['firstname'] = '';
if(!isset($_POST['lastname'])) $_POST['lastname'] = '';
if(!isset($_POST['company'])) $_POST['company'] = '';

if(!isset($user1['company'])) $user1['company'] = '';

//secure string
$_POST['login']=strip_tags($_POST['login']);
$_POST['password']=strip_tags($_POST['password']);
$_POST['password2']=strip_tags($_POST['password2']);
$_POST['mail']=strip_tags($_POST['mail']);
$_POST['firstname']=strip_tags($_POST['firstname']);
$_POST['lastname']=strip_tags($_POST['lastname']);

//default values
$defaultprofile=1; //1 is poweruser, 2 is single user 

//connexion script with database parameters
require "connect.php";

//switch SQL MODE to allow empty values with lastest version of MySQL
$db->exec('SET sql_mode = ""');

//load parameters table
$qry = $db->prepare("SELECT * FROM `tparameters`");
$qry->execute();
$rparameters=$qry->fetch();
$qry->closeCursor();

if ($rparameters['user_register']==1)
{
    //actions on submit
	if (isset($_POST['submit']))
	{
	    //check inputs
	    if($_POST['firstname']) {
    	    if($_POST['lastname']) {
        	    if($_POST['login']) {
        	        if($_POST['password']) {
        	             if($_POST['password2']) {
            	             if($_POST['mail']) {
            	                 if($_POST['password2']==$_POST['password']) {
									//check if user id already exist
									$exist_user = false;
									$qry = $db->prepare("SELECT `id` FROM `tusers` WHERE `mail`=:mail OR `login`=:login ");
									$qry->execute(array(
										'mail' => $_POST['mail'],
										'login' => $_POST['login']
										));	
									while ($row = $qry->fetch()) 
									{
									   $exist_user = true;   
									}
									$qry->closeCursor();									
									if($exist_user==true)
									{
										$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("L'identifiant ou l'adresse mail renseignée existe déjà.").'<br></div>';
									} else {
										//crypt password md5 + salt
										$salt = substr(md5(uniqid(rand(), true)), 0, 5); // Generate a random key
										$_POST['password']=md5($salt . md5($_POST['password'])); // store in md5, md5 password + salt
										//insert user
										$qry=$db->prepare("INSERT INTO `tusers` (`firstname`,`lastname`,`password`,`salt`,`mail`,`profile`,`login`,`chgpwd`,`company`) VALUES (:firstname,:lastname,:password,:salt,:mail,:profile,:login,:chgpwd,:company)");
										$qry->execute(array(
											'firstname' => $_POST['firstname'],
											'lastname' => $_POST['lastname'],
											'password' => $_POST['password'],
											'salt' => $salt,
											'mail' => $_POST['mail'],
											'profile' => $defaultprofile,
											'login' => $_POST['login'],
											'chgpwd' => 0,
											'company' => $_POST['company']
											));
										//message to display
										$message='<div class="alert alert-block alert-success"><center><i class="icon-ok green"></i> '.T_('Votre compte à été crée avec succès').'.</center></div>';
									}
            	                } else {$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("Vos mots de passes ne sont pas identiques").'.<br></div>';}
            	              } else {$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("Vous devez spécifier une adresse mail").'.<br></div>';}
        	             } else {$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("Vous devez spécifier un mot de passe").'.<br></div>';}
        	        } else {$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("Vous devez spécifier un mot de passe").'.<br></div>';}
        	    } else {$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("Vous devez spécifier un identifiant").'.<br></div>';}
        	} else {$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("Vous devez spécifier un nom").'.<br></div>';}
        } else {$message='<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_("Vous devez spécifier un prénom").'.<br></div>';}
	}
    
    //display form
    echo'
    <!DOCTYPE html>
    <html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>GestSup | Gestion de Support</title>
		<link rel="shortcut icon" type="image/png" href="./images/favicon_ticket.png" />
		<meta name="description" content="gestsup" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- basic styles -->
		<link href="./template/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="./template/assets/css/font-awesome.min.css" />
		<!--[if IE 7]>
		  <link rel="stylesheet" href="./template/assets/css/font-awesome-ie7.min.css" />
		<![endif]-->
		<!-- page specific plugin styles -->
		<!-- fonts -->
		<link rel="stylesheet" href="./template/assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="./template/assets/css/jquery-ui-1.10.3.full.min.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="./template/assets/css/ace.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace-skins.min.css" />
		
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="./template/assets/css/ace-ie.min.css" />
		<![endif]-->
		<!-- inline styles related to this page -->
		<!-- ace settings handler -->
		<script src="./template/assets/js/ace-extra.min.js"></script>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="./template/assets/js/html5shiv.js"></script>
		<script src="./template/assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
		<body class="login-layout">
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<i class="icon-ticket green"></i>
									<span class="white">GestSup</span>
								</h1>
								<h4 class="blue">';if (isset($rparameters['company'])) echo $rparameters['company']; echo' </h4>
								<img style="border-style: none" alt="logo" src="./upload/logo/'; if ($rparameters['logo']=='') echo 'logo.png'; else echo $rparameters['logo'];  echo '" />
							</div>
							<br />
							'.$message.'
							<div class="space-6"></div>
							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="icon-user blue"></i>
												'.T_('Inscription').'
												'.$info.'
											</h4>
											
											<div class="space-6"></div>
											<form id="conn" method="post" action="">	
												<fieldset>
												    <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input class="form-control" type="text" id="firstname" name="firstname" class="span12" placeholder="'.T_('Prénom').'" value="'.$_POST['firstname'].'" />
															<i class="icon-user"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input class="form-control" type="text" id="lastname" name="lastname" class="span12" placeholder="'.T_('Nom').'" value="'.$_POST['lastname'].'" />
															<i class="icon-user"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input class="form-control" type="text" id="login" name="login" class="span12" placeholder="'.T_('Identifiant').'" value="'.$_POST['login'].'" />
															<i class="icon-user"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input class="form-control" type="password" id="password" name="password" class="span12" placeholder="'.T_('Mot de passe').'" value="'.$_POST['password'].'" />
															<i class="icon-lock"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input class="form-control" type="password" id="password2" name="password2" class="span12" placeholder="'.T_('Re-taper votre mot de passe').'" value="'.$_POST['password2'].'" />
															<i class="icon-retweet"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input class="form-control" type="text" id="mail" name="mail" class="span12" placeholder="'.T_('Adresse Mail').'" value="'.$_POST['mail'].'" />
															<i class="icon-envelope"></i>
														</span>
													</label>';
													//for advanced user parameter display company
                                            		if($rparameters['user_advanced']==1)
                                            		{
                                            		    echo '
                                                		<label class="block clearfix">
    														<span class="block input-icon input-icon-right">
    															<select class="form-control" type="text" id="company" name="company" class="span12" placeholder="'.T_('Adresse Mail').'" />
    															    <option value="">'.T_('Votre Société').':</option>';
																	$qry = $db->prepare("SELECT `id`,`name` FROM `tcompany` ORDER BY name");
																	$qry->execute();
                                									while ($row = $qry->fetch())
                                									{
                                										if ($user1['company']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
                                									}
																	$qry->closeCursor();																	
                                									echo '
    															</select>
    															<i class="icon-building"></i>
    														</span>
    													</label>
    													';
                                            		}
                                            		echo'
													<div class="space"></div>
													<div class="clearfix">
														<button onclick="submit()" type="submit" id="submit" name="submit" class="width-65 pull-right btn btn-sm btn-success">
															<i class="icon-ok"></i>
															'.T_('S\'enregistrer').'
														</button>
													</div>
													<div class="space-4"></div>
												</fieldset>
											</form>
										</div><!--/widget-main-->
										<div class="toolbar clearfix">
											<div>
												<a href="./"  class="forgot-password-link">
												<i class="icon-arrow-left"></i>
												'.T_('Retour').'
												</a>
											</div>
											<div>
												<a href="register.php"  class="user-signup-link">
												
												</a>
											</div>
										</div
									</div><!--/widget-body-->
								</div><!--/login-box-->
							</div><!--/position-relative-->
						</div>
					</div><!--/.span-->
				</div><!--/.row-fluid-->
			</div>
			<span style="position: absolute; bottom: 0; right: 0;"><a href="https://gestsup.fr">GestSup.fr</a></span>
		</div><!--/.main-container-->
		<script type="text/JavaScript">
			document.getElementById("login").focus();
		</script>
	';
		// Close database access
		unset($db); 
        echo '
	</body>
</html>';
} else {
    echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("La fonction d'enregistrement des utilisateurs est désactivée par votre administrateur").'.<br></div>';
}
?>