<?php
################################################################################
# @Name : ./install/index.php 
# @Desc : Installation application page
# @call : /
# @Author : Flox
# @Version : 3.1.15
# @Create : 10/11/2007
# @Update : 05/01/2017
################################################################################

//initialize variables 
if(!isset($_POST['refresh'])) $_POST['refresh'] = '';
if(!isset($_POST['step'])) $_POST['step'] = '';
if(!isset($_POST['serveur'])) $_POST['serveur'] = '';
if(!isset($_POST['user'])) $_POST['user'] = '';
if(!isset($_POST['password'])) $_POST['password'] = '';
if(!isset($requetes)) $requetes= '';
if(!isset($valid)) $valid = '';
if(!isset($vphp)) $vphp = '';
if(!isset($i)) $i = '';
if(!isset($textension[$i])) $textension[$i] = '';
if(!isset($openssl)) $openssl = '';
if(!isset($phpinfo)) $phpinfo = '';
if(!isset($match)) $match = '';
if(!isset($ldap)) $ldap = '';
if(!isset($zip)) $zip = '';
if(!isset($imap)) $imap = '';
if(!isset($error)) $error = '';
if(!isset($e)) $e= '';

//locales
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if ($lang=='fr') {$_GET['lang'] = 'fr_FR';}
else {$_GET['lang'] = 'en_US';}

define('PROJECT_DIR', realpath('../'));
define('LOCALE_DIR', PROJECT_DIR .'/locale');
define('DEFAULT_LOCALE', '($_GET[lang]');
require_once('../components/php-gettext/gettext.inc');
$encoding = 'UTF-8';
$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;
T_setlocale(LC_MESSAGES, $locale);
T_bindtextdomain($_GET['lang'], LOCALE_DIR);
T_bind_textdomain_codeset($_GET['lang'], $encoding);
T_textdomain($_GET['lang']);

//default value
if(!isset($step)) $step=1;

//extract parameters from phpinfo
ob_start();
phpinfo();
$phpinfo = array('phpinfo' => array());
if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
foreach($matches as $match)
	if(strlen($match[1]))
		$phpinfo[$match[1]] = array();
	elseif(isset($match[3])) {
		$ak=array_keys($phpinfo);
		$phpinfo[end($ak)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
	}
	else {
		$ak=array_keys($phpinfo);
		$phpinfo[end($ak)][] = $match[2];
	}
//case for old version php, php info tab is PHP CORE 			
if (isset($phpinfo['Core'])!='') $vphp='Core'; else $vphp='HTTP Headers Information';

//initialize variables 
if(!isset($phpinfo[$vphp]['register_globals'][0])) $phpinfo[$vphp]['register_globals'][0] = '';
if(!isset($phpinfo[$vphp]['magic_quotes_gpc'][0])) $phpinfo[$vphp]['magic_quotes_gpc'][0] = '';
if(!isset($phpinfo[$vphp]['file_uploads'][0])) $phpinfo[$vphp]['file_uploads'][0] = '';
if(!isset($phpinfo[$vphp]['memory_limit'][0])) $phpinfo[$vphp]['memory_limit'][0] = '';
if(!isset($phpinfo[$vphp]['upload_max_filesize'][0])) $phpinfo[$vphp]['upload_max_filesize'][0] = '';

////actions on submit

//step1
if($_POST['step']==1)
{	
	//write connect.php file with parameter
	$fichier = fopen('../connect.php','w+');
	fputs($fichier,"<?php\r\n");
	fputs($fichier,"################################################################################\r\n");
	fputs($fichier,"# @Name : connect.php\r\n");
	fputs($fichier,"# @Desc : database connection parameters\r\n");
	fputs($fichier,"# @call : \r\n");
	fputs($fichier,"# @parameters : \r\n");
	fputs($fichier,"# @Author : Flox\r\n");
	fputs($fichier,"# @Create : 07/03/2007\r\n");
	fputs($fichier,"# @Update : 05/01/2017\r\n");
	fputs($fichier,"# @Version : 3.1.15\r\n");
	fputs($fichier,"################################################################################\r\n");
	fputs($fichier,"\r\n");
	fputs($fichier,"//database connection parameters\r\n");
	fputs($fichier,"\$host='$_POST[serveur]'; //SQL server name\r\n");
	fputs($fichier,"\$port='$_POST[port]'; //SQL server port\r\n");
	fputs($fichier,"\$db_name='$_POST[dbname]'; //database name\r\n");
	fputs($fichier,"\$charset='utf8'; //database charset default utf8\r\n");
	fputs($fichier,"\$user='$_POST[user]'; //database user name\r\n");
	fputs($fichier,"\$password='$_POST[password]'; //database password\r\n");
	fputs($fichier,"\r\n");
	fputs($fichier,"//database connection\r\n");
	fputs($fichier,'try {$db = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=$charset", "$user", "$password" , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));}'."\r\n");
	fputs($fichier,'catch (Exception $e)'."\r\n");
	fputs($fichier,'{die(\'Error : \' . $e->getMessage());}'."\r\n");
	fputs($fichier,"?>");
	fclose($fichier);
	
	//db connect
	$host="$_POST[serveur]"; //SQL server name
	$port="$_POST[port]"; //SQL server port
	$db_name="$_POST[dbname]"; //database name
	$charset="utf8"; //database charset default utf8
	$user="$_POST[user]"; //database user name
	$password="$_POST[password]"; //database password
	
	//create and connect to database
	try 
	{
		$db = new PDO("mysql:host=$host;port=$port;charset=$charset", $user, $password , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$db->exec("CREATE DATABASE IF NOT EXISTS `$_POST[dbname]`");
		$db->query("use `$_POST[dbname]`");
		//import sql skeleton
		$sql_file=file_get_contents('../_SQL/skeleton.sql');
		$sql_file=explode(";", $sql_file);
		foreach ($sql_file as $value) {
			if($value!='') $db->exec($value);
		}
		$step=2;
	} catch (Exception $e) {
		$e->getMessage();
		$error = '<b>'.T_('Erreur').': '.T_('Vérifier vos paramètres de connexion à la base de donnée').'</b> <br />'; 
		$error = $error."<br />$e"; 
	}
}
//step2
if($_POST['step']==2)
{
	if($_POST['refresh']) $step=2; else $step=3;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<title>GestSup | <?php echo T_('Installation'); ?></title>
		<link rel="shortcut icon" type="image/png" href="./images/favicon_ticket.png" />
		<meta name="description" content="gestsup" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="../template/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="../template/assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="../template/assets/css/jquery-ui-1.10.3.full.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace-skins.min.css" />
		<script src="../template/assets/js/ace-extra.min.js"></script>
	</head>
	<body>
		<div class="navbar navbar-default" id="navbar">
			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<i class="icon-ticket "></i>
						GestSup
					</a><!--/.brand-->
				</div><!-- /.navbar-header -->
			</div><!--/.navbar-inner-->
		</div>
		<div class="main-container" id="main-container">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-header widget-header-blue widget-header-flat">
							<h4 class="lighter"><?php echo T_('Installation de l\'application'); ?></h4>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div id="fuelux-wizard" class="row-fluid" data-target="#step-container">
									<ul class="wizard-steps">
										<li data-target="#step1" <?php if ($step==1) echo 'class="active"'; ?>>
											<span class="step">1</span>
											<span class="title"><?php echo T_('Base de données'); ?></span>
										</li>
										<li data-target="#step2" <?php if ($step==2) echo 'class="active"'; ?>>
											<span class="step">2</span>
											<span class="title"><?php echo T_('Vérification de la configuration serveur'); ?></span>
										</li>
										<li data-target="#step3" <?php if ($step==3) echo 'class="active"'; ?>>
											<span class="step">3</span>
											<span class="title"><?php echo T_('Fin'); ?></span>
										</li>
									</ul>
								</div>
								<hr>
								<form method="post" id="form" action="" class="form-horizontal" id="sample-form" >
									<div class="step-content row-fluid position-relative" id="step-container">
										<div class="step-pane active" id="step1">
											<?php
												//display error box
												if ($error) echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> '.$error.'.</center></div>';
												//display STEP 1 form
												if ($step=='1')
												{
												echo '
													<h3 class="lighter block green">'.T_('Entrer les paramètres de connexion à votre base de données').':</h3>
													<div class="space-8"></div>
													<input type="hidden" name="step" value="1">
													<div class="form-group has-info">
														<label for="serveur" class="col-xs-12 col-sm-3 control-label no-padding-right">'.T_('Serveur de base données').':</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="text" name="serveur" value="localhost" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline"> <i>'.T_('Nom netbios ou adresse IP du serveur de base de données').'</i> </div>
													</div>
													<div class="form-group has-info">	
														<label for="dbname" class="col-xs-12 col-sm-3 control-label no-padding-right">'.T_('Nom de la base données').':</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="text" name="dbname" value="bsup" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
													</div>
													<div class="form-group has-info">	
														<label for="port" class="col-xs-12 col-sm-3 control-label no-padding-right">'.T_('Port de la base données').':</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="text" name="port" value="3306" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline"> <i>'.T_('Pour MySQL et MariaDB 3307').'</i> </div>
													</div>
													<div class="form-group has-info">		
														<label for="user" class="col-xs-12 col-sm-3 control-label no-padding-right">'.T_('Utilisateur de la base données').':</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="text" name="user" value="root" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline">  </div>
													</div>
													<div class="form-group has-info">		
														<label for="password" class="col-xs-12 col-sm-3 control-label no-padding-right">'.T_('Mot de passe de la base données').':</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="password" name="password" value="" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline"> <i>'.T_('Pour WAMP laisser vide').'</i> </div>
													</div>
												';
												}
												if ($step=='2')
												{	
													echo '
													<input type="hidden" name="step" value="2">
													<h3 class="lighter block green">'.T_('Vérification de la configuration serveur').':</h3>
													<div class="space-8"></div>
													';
													include('../system.php');
												}
												if ($step=='3')
												{	
													echo '
													<input type="hidden" name="step" value="3">
													<h3 class="lighter block green">'.T_('Installation terminée').':</h3>
													<div class="space-8"></div>';
													//find server url
													$url=$_SERVER['HTTP_REFERER'];
													$url=(parse_url($url));
													$path=$url['path'];
													$path=explode("/",$path);
													$path=$path[1];
													if ($path=='install') {$path='';}
													$url='http://'.$url['host'].'/'.$path.'';
													echo '
													'.T_('L\'application à été installée avec succès').'.<br />
													'.T_('Les identifiants initiaux sont').' <b>admin</b> / <b>admin</b> <br />
													'.T_('Vous pouvez vous connecter via l\'url').': <a href="'.$url.'">'.$url.'</a>. <br /><br />
													<font color="red">!!! '.T_('Attention pour des raisons de sécurité nous vous conseillons de supprimer le répertoire /install, et de modifier les mots de passes des utilisateurs existants').'.</font>
													';
												}
											?>
										</div>
										<hr>
										<div class="row-fluid wizard-actions">
											<?php
												if ($step==2)
												{
													echo '
													<button type="submit" name="refresh" id="refresh" value="refresh" class="btn btn-primary btn-next">
														<i class="icon-refresh"></i>
														'.T_('Actualiser').'
													</button>';
												}
												if ($step!=3)
												{
													echo '
													<button type="submit" class="btn btn-success btn-next" data-last="Finish ">
														'.T_('Suivant').'
														<i class="icon-arrow-right icon-on-right"></i>
													</button>';
												}
											?>
										</div>
									</div>
								</form>
							</div><!-- /widget-main -->
						</div><!-- /widget-body -->
					</div>
				</div>
			</div>
		</div>
	</body>
</html>