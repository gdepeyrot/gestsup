<?php
################################################################################
# @Name : ./survey.php 
# @Description : display survey
# @Call : auto mail
# @Parameters : token
# @Author : Flox
# @Version : 3.1.28
# @Create : 22/04/2017
# @Update : 03/01/2018
################################################################################

//initialize variables 
if(!isset($error)) $error = '';
if(!isset($question_number)) $question_number = '';
if(!isset($_POST['next'])) $_POST['next'] = '';
if(!isset($_POST['previous'])) $_POST['previous'] = '';
if(!isset($_POST['answer'])) $_POST['answer'] = '';
if(!isset($_POST['question_number'])) $_POST['question_number'] = '';
if(!isset($_POST['question_id'])) $_POST['question_id'] = '';
if(!isset($_POST['validation'])) $_POST['validation'] = '';
if(!isset($_GET['token'])) $_GET['token'] = '';

//default value
if(!$question_number) {$question_number=1;}
if(!$_POST['question_id']) $_POST['question_id'] = 1;

//db connection
require "connect.php";
$db->exec('SET sql_mode = ""');

$db_token=strip_tags($db->quote($_GET['token']));

//load parameters table
$query=$db->query("SELECT * FROM tparameters");
$rparameters=$query->fetch();
$query->closeCursor();

if ($_GET['token'])
{
	//check if token exist
	$query=$db->query("SELECT ticket_id FROM ttoken WHERE token=$db_token");
	$row=$query->fetch();
	$query->closeCursor();
	if($row)
	{
		$token=true;
		$ticket_id=$row['ticket_id'];
	} else {
		$token=false;
		$ticket_id='';
	}
} else {
	$ticket_id='';
	$token=false;
}

//define PHP time zone
date_default_timezone_set('Europe/Paris');
$datetime=date('Y-m-d H:i:s');

//locales
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if ($lang=='fr') {$_GET['lang'] = 'fr_FR';}
else {$_GET['lang'] = 'en_US';}
define('PROJECT_DIR', realpath('./'));
define('LOCALE_DIR', PROJECT_DIR .'/locale');
define('DEFAULT_LOCALE', '($_GET[lang]');
require_once('./components/php-gettext/gettext.inc');
$encoding = 'UTF-8';
$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;
T_setlocale(LC_MESSAGES, $locale);
T_bindtextdomain($_GET['lang'], LOCALE_DIR);
T_bind_textdomain_codeset($_GET['lang'], $encoding);
T_textdomain($_GET['lang']);


//get ticket data
$query = $db->query("SELECT title,user FROM `tincidents` WHERE id='$ticket_id'");
$ticket=$query->fetch();
$query->closecursor();

//if survey is already begin then goto last empty question
$query = $db->query("SELECT number FROM `tsurvey_questions` WHERE id=(SELECT MAX(question_id) FROM tsurvey_answers WHERE ticket_id='$ticket_id')");
$current_question_number=$query->fetch();
$query->closecursor();
if($current_question_number[0]) {$question_number=$current_question_number[0]+1;}

//actions on submit
if($_POST['previous'] || $_POST['next'] || $_POST['validation']) {
	
	//check error
	if($_POST['answer'] && (strlen(trim($_POST['answer']))!=0) && ($_POST['next'] || $_POST['validation'])) {
		//check previous answer
		$query = $db->query("SELECT * FROM `tsurvey_answers` WHERE ticket_id='$ticket_id' AND question_id='$_POST[question_id]'");
		$row=$query->fetch();
		$query->closecursor();
		if($row)
		{
			if($row['answer']!=$_POST['answer']) //if answer is different than db row update it
			{
				$_POST['answer'] = $db->quote($_POST['answer']);
				//update answer
				$db->exec("UPDATE tsurvey_answers SET date='$datetime', answer=$_POST[answer] WHERE ticket_id='$ticket_id' AND question_id='$_POST[question_id]'");
			}
		} else {
			$_POST['answer'] = $db->quote($_POST['answer']);
			//insert answer
			$db->exec("INSERT INTO tsurvey_answers (date, ticket_id, question_id,answer) VALUES ('$datetime','$ticket_id','$_POST[question_id]',$_POST[answer])");
		}
		
	} else {if(!$_POST['previous']){$error='<b>'.T_('ERREUR').':</b> '.T_("Aucune réponse n'a été saisie");}}
	//change current question number
	if(!$error)
	{
		if($_POST['next']){$question_number=$_POST['question_number']+1;}
		if($_POST['previous']){$question_number=$_POST['question_number']-1;}
	} else {$question_number=$_POST['question_number'];}
	
}

//get question id
$query = $db->query("SELECT id FROM `tsurvey_questions` WHERE number='$question_number'");
$question_id=$query->fetch();
$query->closecursor();
$question_id=$question_id[0];

//display debug
if ($rparameters['debug']==1) {echo '<u><b>DEBUG MODE:</b></u><br /><b>VAR:</b> POST_answer='.$_POST['answer'].' question_number='.$question_number.' post_question_number='.$_POST['question_number'].' question_id='.$question_id.' post_question_id='.$_POST['question_id']; }

if($_POST['validation'] && !$error)
{
	//delete token
	$db->exec("DELETE FROM ttoken WHERE ticket_id='$ticket_id'");
	
	if($rparameters['survey_auto_close_ticket']==1)
	{
		//modify ticket state in close and unread tag
		$db->exec("UPDATE tincidents SET state='3',techread='0',date_res='$datetime' WHERE id='$ticket_id'");
		//insert close thread
		$db->exec("INSERT INTO tthreads (ticket, date, type, author) VALUES ('$ticket_id','$datetime','4','$ticket[user]')");
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>GestSup | <?php echo T_('Sondage'); ?></title>
		<link rel="shortcut icon" type="image/png" href="./images/favicon_survey.png" />
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
	</head>
	<body>
		<div class="navbar navbar-default" id="navbar">
			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<span style="font-size:20px;"><i class="icon-check "></i> <?php echo T_('Sondage'); ?> 
						
						<?php 
						//re-size logo if height superior 40px
						if ($rparameters['logo']!='') 
						{
							$height = getimagesize("./upload/logo/$rparameters[logo]");
							$height=$height[1];
							if ($height>40) {$logo_size='height="40"';} else {$logo_size='';}
						} else {$logo_size='';}
						echo '&nbsp;<img style="border-style: none" '.$logo_size.' alt="logo" src="./upload/logo/'; if ($rparameters['logo']=='') echo 'logo.png'; else echo $rparameters['logo'];  echo '" />';
						echo '&nbsp;'.$rparameters['company']; 
						?></span>
					</a><!--/.brand-->
				</div><!-- /.navbar-header -->
			</div><!--/.navbar-inner-->
		</div>
		<div class="main-container" id="main-container">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-header widget-header-blue widget-header-flat">
							<h4 class="lighter"><?php if($ticket_id) {echo T_('Ticket').' n°'.$ticket_id.': '.$ticket['title'].'';} ?></h4>
						</div>
						<?php 
						if ($rparameters['survey']==1)
						{
							if ($token==true)
							{
								if ($_POST['validation'] && !$error)
								{
									
									echo '<br /><br /><br /><div class="alert alert-block alert-success"><center><i class="icon-ok green"></i>	'.T_('Votre sondage a été envoyé merci').'. </center></div><br /><br />';
								} 
								else
								{
									echo '
									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard" class="row-fluid" data-target="#step-container">
												<ul class="wizard-steps">
													';
													$query = $db->query("SELECT id,number FROM `tsurvey_questions` WHERE disable='0'");
													while ($row=$query->fetch())
													{
														if ($question_number>=$row['number']) {$active='active';} else {$active='';}
														echo '
														<li data-target="#step1" class="'.$active.'" >
															<span class="step">'.$row['number'].'</span>
														</li>
														';
													}
													$query->closecursor();
													echo '
												</ul>
											</div>
											<hr>
											<form method="post" id="form" action="" class="form-horizontal" id="sample-form" >
												<div class="step-content row-fluid position-relative" id="step-container">
													';
													//display error box
													if ($error) {echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> '.$error.'.</center></div>';} 
													echo '
													<div class="col-xs-6 col-sm-2"></div>
													<div class="col-xs-6 col-sm-10" id="step1">
															';
															//get question
															$query = $db->query("SELECT * FROM `tsurvey_questions` WHERE number='$question_number' AND disable='0'");
															$row=$query->fetch();
															$query->closecursor();
															
															//get question answer
															$query = $db->query("SELECT * FROM `tsurvey_answers` WHERE ticket_id='$ticket_id' AND question_id='$question_id'");
															$answer=$query->fetch();
															$query->closecursor();
															
															//display question
															echo '<h3 class="lighter block green">'.T_('Question').' n°'.$row['number'].': '.$row['text'].'</h3><div class="space-8"></div>';
															
															//yes / no question
															if($row['type']==1)
															{
																//check if an existing value is present in db
																if($answer['answer']==T_('Oui')) {$checked_yes='checked';} else {$checked_yes='';}
																if($answer['answer']==T_('Non')) {$checked_no='checked';} else {$checked_no='';}
																
																echo '
																	<div class="radio">
																		<label>
																			<input name="answer" '.$checked_yes.' value="'.T_('Oui').'" type="radio" class="ace">
																			<span class="lbl"> '.T_('Oui').'</span>
																		</label>
																	</div>
																	<div class="radio">
																		<label>
																			<input name="answer" '.$checked_no.' value="'.T_('Non').'" type="radio" class="ace">
																			<span class="lbl"> '.T_('Non').'</span>
																		</label>
																	</div>
																';
															}
															//text question
															if($row['type']==2)
															{
																echo '
																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	<textarea id="answer" name="answer" width="200" cols="100" rows="8">'.$answer['answer'].'</textarea>
																';
															}
															//select question
															if($row['type']==3)
															{
																echo '
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<select width="20" id="answer" name="answer" >
																	';
																	if ($row['select_1']) {echo '<option value="'.$row['select_1'].'" '; if($answer['answer']==$row['select_1']) {echo 'selected';} echo ' >'.$row['select_1'].'</option>';}	
																	if ($row['select_2']) {echo '<option value="'.$row['select_2'].'" '; if($answer['answer']==$row['select_2']) {echo 'selected';} echo ' >'.$row['select_2'].'</option>';}	
																	if ($row['select_3']) {echo '<option value="'.$row['select_3'].'" '; if($answer['answer']==$row['select_3']) {echo 'selected';} echo ' >'.$row['select_3'].'</option>';}	
																	if ($row['select_4']) {echo '<option value="'.$row['select_4'].'" '; if($answer['answer']==$row['select_4']) {echo 'selected';} echo ' >'.$row['select_4'].'</option>';}	
																	if ($row['select_5']) {echo '<option value="'.$row['select_5'].'" '; if($answer['answer']==$row['select_5']) {echo 'selected';} echo ' >'.$row['select_5'].'</option>';}	
																	echo '
																</select>
																';
															}
															if($row['type']==4)
															{
																for($i = 1; $i <= $row['scale']; $i++)
																{
																	echo '
																	<div class="radio">
																		<label>
																			<input name="answer" value="'.$i.'" type="radio" '; if($answer['answer']==$i) {echo 'checked';} echo ' class="ace">
																			<span class="lbl"> '.$i.'</span>
																		</label>
																	</div>
																	';
																}
															}
															echo '
													</div>
													<br /><br /><br />
													<br /><br /><br />
													<br /><br /><br />
													<br /><br /><br /><br /><br />
													<hr>
													<input type="hidden" name="question_number" value="'.$question_number.'">
													<input type="hidden" name="question_id" value="'.$row['id'].'">
													<div class="row-fluid wizard-actions">
														<center>
															';
															if($question_number!=1)
															{
																echo '
																<button type="submit" id="previous" name="previous" value="previous" class="btn btn-prev" data-last="Finish ">
																<i class="icon-arrow-left icon-on-right"></i>
																	'.T_('Précédent').'
																</button>
																&nbsp;&nbsp;&nbsp;
																';
															}
															//get last question number
															$query = $db->query("SELECT MAX(number) FROM `tsurvey_questions` WHERE disable='0'");
															$row=$query->fetch();
															$query->closecursor();
															if($row[0]==$question_number)
															{
																echo '
																<button type="submit" id="validation" name="validation" value="validation" class="btn btn-success btn-next" data-last="Finish">
																	'.T_('Valider').'
																	<i class="icon-arrow-right icon-on-right"></i>
																</button>
																';
															} else {
																echo '
																<button type="submit" id="next" name="next" value="next" class="btn btn-info btn-next" data-last="Finish">
																	'.T_('Suivant').'
																	<i class="icon-arrow-right icon-on-right"></i>
																</button>
																';
															}
															
															echo '
															
														</center>
													</div>
												</div>
											</form>
										</div><!-- /widget-main -->
									</div><!-- /widget-body -->
									';
								}
							} else {
								echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i><b> '.T_('Erreur').'</b>: '.T_('Jeton invalide, contacter votre administrateur').'</center></div>';
							}
						} else {
							echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i><b> '.T_('Erreur').'</b>: '.T_("La fonction sondage n'est pas activée contacter votre administrateur").'</center></div>';
						} 
					
						?>
					</div>
				</div>
			</div>
		</div>
		<span style="position: absolute; bottom: 0; right: 0; font-size:10px; "><a target="_blank" href="https://gestsup.fr"><?php echo T_('Sondage généré par'); ?> GestSup</a></span>
	</body>
</html>