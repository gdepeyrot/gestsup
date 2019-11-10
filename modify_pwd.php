<?php
################################################################################
# @Name : modify_pwd.php
# @Description : change password popup
# @Call : /dashboard.php
# @Parameters : 
# @Author : Flox
# @Create : 05/02/2012
# @Update : 27/12/2015
# @Version : 3.1.15
################################################################################

//initialize variables 
if(!isset($_POST['modifypwd'])) $_POST['modifypwd'] = ''; 
if(!isset($_POST['oldpwd'])) $_POST['oldpwd'] = ''; 
if(!isset($_POST['newpwd1'])) $_POST['newpwd1'] = ''; 
if(!isset($_POST['newpwd2'])) $_POST['newpwd2'] = ''; 
if(!isset($updated)) $updated = ''; 
if(!isset($oldpassword)) $oldpassword = ''; 
if(!isset($secure_password)) $secure_password = ''; 
if(!isset($boxtext)) $boxtext = ''; 
 
$query=$db->query("SELECT * FROM tusers WHERE id=$_SESSION[user_id]");
$ru=$query->fetch();
$query->closeCursor();
 
if($_POST['modifypwd'])
{
	//find uncrypted or crypted old password
	$oldpassword=0;
	if ($_POST['oldpwd']==$ru['password']) $oldpassword=1;
	if (md5($ru['salt'] . md5($_POST['oldpwd']))==$ru['password']) $oldpassword=1;
		
	// check empty password
	if ($_POST['oldpwd']=="" || $_POST['newpwd1']=="" || $_POST['newpwd2']=="")
	{
		$boxtext='<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> <b>'.T_('Erreur').':</b> '.T_('Veuillez remplir tous les champs').'.</center></div>';
	}
	// check old password
	else if ($oldpassword!='1')
	{
		$boxtext='<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> <b>'.T_('Erreur').':</b> '.T_('Votre ancien mot de passe est erroné').'.</center></div>';
	}
	// check new passwords
	else if ($_POST['newpwd1']!=$_POST['newpwd2'])
	{
		$boxtext='<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> <b>'.T_('Erreur').':</b> '.T_('Les deux nouveaux mot de passes sont différents').'.</center></div>';
	}
	else
	{
		//crypt password md5 + salt
		if($_POST['newpwd1']!='') {
			$salt = substr(md5(uniqid(rand(), true)), 0, 5); //generate a random key
			$_POST['newpwd1']=md5($salt . md5($_POST['newpwd1'])); //store in md5, md5 password + salt.
		}
		$db->exec("UPDATE tusers SET chgpwd='0' where id like '$_SESSION[user_id]'");
		$db->exec("UPDATE tusers SET password='$_POST[newpwd1]', salt='$salt' where id like '$_SESSION[user_id]'");
		$updated=1;
	} 
}
if ($updated==1)
{
	$boxtitle="<i class='icon-lock blue bigger-120'></i> ".T_('Modification du mot de passe');
	$boxtext= '<div class="alert alert-block alert-success"><center><i class="icon-ok green"></i>	'.T_('Votre mot de passe à été changé avec succès').'.</center></div>';
	$cancel=T_('Fermer');
	$action2="$( this ).dialog( \"close\" ); ";
}
else
{
	$boxtext=$boxtext.'
	<form name="form" method="POST" action="" id="form">
		<input name="modifypwd" type="hidden" value="1">
		<label for="oldpwd" >'.T_('Ancien mot de passe').':</label> 
		<input  name="oldpwd" type="password" >
		<label for="newpwd1" >'.T_('Nouveau mot de passe').':</label> 
		<input  name="newpwd1" type="password" >
		<label for="newpwd2" >'.T_('Nouveau mot de passe').':</label> 
		<input  name="newpwd2" type="password" >
	</form>
	';
}
$boxtitle="<i class='icon-lock blue bigger-120'></i> ".T_('Modification du mot de passe');
$valid=T_('Modifier');
$action1="$('form#form').submit();";
$cancel=T_('Fermer');
$action2="$( this ).dialog( \"close\" ); ";
include "./modalbox.php"; 
?>