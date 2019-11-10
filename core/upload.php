<?php
################################################################################
# @Name : upload.php
# @Description : upload attached files 
# @Call : ticket.php
# @Parameters : 
# @Author : Flox
# @Create : 12/08/2013
# @Update : 06/04/2018
# @Version : 3.1.30 p2
################################################################################

//initialize variables 
if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($number)) $number = '';
if(!isset($_FILES['file1']['name'])) $_FILES['file1']['name'] = '';
if(!isset($_FILES['file2']['name'])) $_FILES['file2']['name'] = '';
if(!isset($_FILES['file3']['name'])) $_FILES['file3']['name'] = '';
if(!isset($_FILES['file4']['name'])) $_FILES['file4']['name'] = '';
if(!isset($_FILES['file5']['name'])) $_FILES['file5']['name'] = '';
if(!isset($file1_rename)) $file1_rename = '';
if(!isset($file2_rename)) $file2_rename = '';
if(!isset($file3_rename)) $file3_rename = '';
if(!isset($file4_rename)) $file4_rename = '';
if(!isset($file5_rename)) $file5_rename = '';

//default value
$blacklistedfile=0;

//remove special char from file name
$file1_rename =  preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $_FILES['file1']['name']);
$file2_rename =  preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $_FILES['file2']['name']);
$file3_rename =  preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $_FILES['file3']['name']);
$file4_rename =  preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $_FILES['file4']['name']);
$file5_rename =  preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $_FILES['file5']['name']);

//black list exclusion for extension
$blacklist =  array('php', 'php1', 'php2','php3' ,'php4' ,'php5', 'php6', 'php7', 'php8', 'php9', 'php10', 'js', 'htm', 'html', 'phtml', 'exe', 'jsp' ,'pht', 'shtml', 'asa', 'cer', 'asax', 'swf', 'xap', 'phphp', 'inc', 'htaccess', 'sh', 'py', 'pl', 'jsp', 'asp', 'cgi', 'json', 'svn', 'git', 'lock', 'yaml', 'com', 'bat', 'ps1', 'cmd', 'vb', 'hta', 'reg', 'ade', 'adp', 'app', 'asp', 'bas', 'bat', 'cer', 'chm', 'cmd', 'com', 'cpl', 'crt', 'csh', 'der', 'exe', 'fxp', 'gadget', 'hlp', 'hta', 'inf', 'ins', 'isp', 'its', 'js', 'jse', 'ksh', 'lnk', 'mad', 'maf', 'mag', 'mam', 'maq', 'mar', 'mas', 'mat', 'mau', 'mav', 'maw', 'mda', 'mdb', 'mde', 'mdt', 'mdw', 'mdz', 'msc', 'msh', 'msh1', 'msh2', 'mshxml', 'msh1xml', 'msh2xml', 'msi', 'msp', 'mst', 'ops', 'pcd', 'pif', 'plg', 'prf', 'prg', 'pst', 'reg', 'scf', 'scr', 'sct', 'shb', 'shs', 'ps1', 'ps1xml', 'ps2', 'ps2xml', 'psc1', 'psc2', 'tmp', 'url', 'vb', 'vbe', 'vbs', 'vsmacros', 'vsw', 'ws', 'wsc', 'wsf', 'wsh', 'xnk');

//for new ticket	
if ($_GET['id']=="") $_GET['id']=$number;

$db_id=strip_tags($db->quote($_GET['id']));

if($_FILES['file1']['name'])
{
	//if id directory not exist, create it
	if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0770);
	$filename=$_FILES['file1']['name'];
	//secure check for extension
    $ext=explode('.',$filename);
	foreach ($ext as &$value) {
		$value=strtolower($value);
		if(in_array($value,$blacklist) ) {
			$blacklistedfile=1;
		} 
	}
    if($blacklistedfile==0) {
        $destination_folder="./upload/$_GET[id]/";
		if (move_uploaded_file($_FILES['file1']['tmp_name'], $destination_folder.$file1_rename)) 
		{
			$db->exec("UPDATE tincidents SET img1='$file1_rename' WHERE id=$db_id");
		} else {
			echo T_('Erreur de transfert vérifier le chemin ').$destination_folder;
		}
    } else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Blocage de sécurité').':</strong> '.T_('Fichier interdit').'.<br></div>';}
} elseif($_FILES['file2']['name']) { 
	//if id directory not exist, create it
	if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0770);
	$filename=$_FILES['file2']['name'];
	//secure check for extension
    $ext=explode('.',$filename);
	foreach ($ext as &$value) {
		$value=strtolower($value);
		if(in_array($value,$blacklist) ) {
			$blacklistedfile=1;
		} 
	}
    if($blacklistedfile==0) {
       $destination_folder="./upload/$_GET[id]/";
    	if (move_uploaded_file($_FILES['file2']['tmp_name'], $destination_folder.$file2_rename)) 
		{
			$db->exec("UPDATE tincidents SET img2='$file2_rename' WHERE id=$db_id");
		} else {
			echo T_('Erreur de transfert vérifier le chemin ').$destination_folder;
		}
	} else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Blocage de sécurité').':</strong> '.T_('Fichier interdit').'.<br></div>';}
} elseif($_FILES['file3']['name']) { 
	//if id directory not exist, create it
	if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0770);
	$filename=$_FILES['file3']['name'];
	//secure check for extension
    $ext=explode('.',$filename);
	foreach ($ext as &$value) {
		$value=strtolower($value);
		if(in_array($value,$blacklist) ) {
			$blacklistedfile=1;
		} 
	}
    if($blacklistedfile==0) {
       $destination_folder="./upload/$_GET[id]/";
    	if (move_uploaded_file($_FILES['file3']['tmp_name'], $destination_folder.$file3_rename)) 
    	{
			$db->exec("UPDATE tincidents SET img3='$file3_rename' WHERE id=$db_id");
    	} else {
			echo T_('Erreur de transfert vérifier le chemin ').$destination_folder;
    	}
	} else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Blocage de sécurité').':</strong> '.T_('Fichier interdit').'.<br></div>';}
} elseif($_FILES['file4']['name']) { 
	//if id directory not exist, create it
	if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0770);
	$filename=$_FILES['file4']['name'];
	//secure check for extension
    $ext=explode('.',$filename);
	foreach ($ext as &$value) {
		$value=strtolower($value);
		if(in_array($value,$blacklist) ) {
			$blacklistedfile=1;
		} 
	}
    if($blacklistedfile==0) {
       	$destination_folder= dirname(__FILE__)."../../upload/$_GET[id]/";
    	if (move_uploaded_file($_FILES['file4']['tmp_name'], $destination_folder.$file4_rename)) 
    	{
			$db->exec("UPDATE tincidents SET img4='$file4_rename' WHERE id=$db_id");
    	} else {
			echo T_('Erreur de transfert vérifier le chemin ').$destination_folder;
    	}
	} else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Blocage de sécurité').':</strong> '.T_('Fichier interdit').'.<br></div>';}
} elseif($_FILES['file5']['name']) { 
	//if id directory not exist, create it
	if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0770);
	$filename=$_FILES['file5']['name'];
	//secure check for extension
    $ext=explode('.',$filename);
	foreach ($ext as &$value) {
		$value=strtolower($value);
		if(in_array($value,$blacklist) ) {
			$blacklistedfile=1;
		} 
	}
    if($blacklistedfile==0) {
      	$destination_folder= dirname(__FILE__)."../../upload/$_GET[id]/";
    	if (move_uploaded_file($_FILES['file5']['tmp_name'], $destination_folder.$file5_rename)) 
    	{
			$db->exec("UPDATE tincidents SET img5='$file5_rename' WHERE id=$db_id");
    	} else {
			echo T_('Erreur de transfert vérifier le chemin ').$destination_folder;
    	}
	} else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Blocage de sécurité').':</strong> '.T_('Fichier interdit').'.<br></div>';}
}
?>