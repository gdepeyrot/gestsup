<?php
################################################################################
# @Name : /core/message.php
# @Description : page to send mail
# @Call : /core/auto_mail.php 
# @parameters : $from, $to, $message, $object
# @Author : Flox
# @Create : 21/11/2012
# @Update : 04/09/2017
# @Version : 3.1.25
################################################################################

require_once("components/PHPMailer/src/PHPMailer.php");
require_once("components/PHPMailer/src/SMTP.php");
require_once("components/PHPMailer/src/Exception.php");
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

$mail->CharSet = 'UTF-8'; //ISO-8859-1 possible if string problems
if ($rparameters['mail_smtp_class']=='IsSendMail()') {$mail->IsSendMail();} else {$mail->IsSMTP();} 
if($rparameters['mail_secure']=='SSL') 
{$mail->Host = "ssl://$rparameters[mail_smtp]";} 
elseif($rparameters['mail_secure']=='TLS') 
{$mail->Host = "tls://$rparameters[mail_smtp]";} 
else 
{$mail->Host = "$rparameters[mail_smtp]";}
$mail->SMTPAuth = $rparameters['mail_auth'];
if ($rparameters['debug']==1) $mail->SMTPDebug = 4;
if ($rparameters['mail_secure']!=0) $mail->SMTPSecure = $rparameters['mail_secure'];
if ($rparameters['mail_port']!=25) $mail->Port = $rparameters['mail_port'];
$mail->Username = "$rparameters[mail_username]";
$mail->Password = "$rparameters[mail_password]";
$mail->IsHTML(true); // Envoi en html
$mail->From = "$from";
$mail->FromName = "$from";
$mail->AddAddress("$to");
$mail->AddReplyTo("$from");
$mail->Subject = "$object";
if ($rparameters['mail_ssl_check']==0)
{
	//bug fix 3559
	$mail->smtpConnect([
	'ssl' => [
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
		]
	]);
}
$mail->Body = "$message";
if (!$mail->Send())
{
	echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> <b>'.T_('Message non envoyé, vérifier la configuration de votre serveur de messagerie').'.</b> (';
		echo $mail->ErrorInfo;
	echo ')</center></div>';
}
$mail->SmtpClose();
?>