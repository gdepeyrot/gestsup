<?php
################################################################################
# @Name : update.php
# @Description : page to update GestSup
# @Call : admin.php
# @Parameters : 
# @Author : Flox
# @Create : 20/01/2011
# @Update : 18/12/2017
# @Version : 3.1.29
################################################################################

//initialize variables 
if(!isset($contents[0])) $contents[0] = '';
if(!isset($_POST['update_channel'])) $_POST['update_channel'] = '';
if(!isset($_POST['check'])) $_POST['check'] = '';
if(!isset($_POST['download'])) $_POST['download'] = '';
if(!isset($_POST['install'])) $_POST['install'] = '';
if(!isset($_POST['install_update'])) $_POST['install_update'] = '';
if(!isset($_GET['install_update'])) $_GET['install_update'] = '';
if(!isset($argv[1])) $argv[1] = '';
if(!isset($argv[2])) $argv[2] = '';
if(!isset($findpatch)) $findpatch = '';
if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $_SERVER['HTTP_ACCEPT_LANGUAGE']='';

//parameters of GestSup update server 
$ftp_server="ftp.gestsup.fr";
$ftp_user_name="gestsup";
$ftp_user_pass="gestsup";

//check dedicated version
if(substr_count($rparameters['version'], '.')==3) {$dedicated=1;} else {$dedicated=0;}

//check autoinstall for command line options
if ($argv[1]=='autoinstall') {
	require(__DIR__ . '/../connect.php');
	//load parameters table
	$qry = $db->prepare("SELECT * FROM `tparameters`");
	$qry->execute();
	$rparameters=$qry->fetch();
	$qry->closeCursor();
	
	//locales
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	if ($lang=='fr') {$_GET['lang'] = 'fr_FR';}
	else {$_GET['lang'] = 'en_US';}
	define('PROJECT_DIR', realpath('../'));
	define('LOCALE_DIR', PROJECT_DIR .'/locale');
	define('DEFAULT_LOCALE', '($_GET[lang]');
	require_once(__DIR__.'/../components/php-gettext/gettext.inc');
	$encoding = 'UTF-8';
	$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;
	T_setlocale(LC_MESSAGES, $locale);
	T_bindtextdomain($_GET['lang'], LOCALE_DIR);
	T_bind_textdomain_codeset($_GET['lang'], $encoding);
	T_textdomain($_GET['lang']);
	
	if ($argv[2]==$rparameters['server_private_key'])
	{
		echo T_('Vérification de la clé: ok');
		$autoinstall=1;
	} else {
		echo T_("Votre clé est erronée l'installation automatique n'est pas possible");
		$autoinstall=0;
	}
} else {$autoinstall=0;}
 
//display title
echo '
<div class="page-header position-relative">
	<h1>
		<i class="icon-circle-arrow-up"></i>  '.T_('Mise à jour de GestSup').'
	</h1>
</div>
';

//check right permission on files
if (!is_writable('./core/ticket.php') || !is_writable('./index.php') || !is_writable('./admin/parameters.php') || !is_writable('./download/readme.txt'))
{
	echo '
	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="warning">
			<i class="icon-remove"></i>
		</button>
		<strong>
			<i class="icon-warning-sign"></i>
			'.T_('Attention').':
		</strong>
		'.T_("Les fichiers serveur ne sont pas accessible en écriture, l'installation semi-automatique ne fonctionnera pas, modifier les droits d'écriture temporairement pour l'installation puis remettre les droits par défaut").'
		<br>
	</div>
	';
}

//update update channel parameter
if($_POST['update_channel']) 
{
	$db->exec("UPDATE tparameters SET update_channel='$_POST[update_channel]'");
	$qry=$db->prepare("UPDATE `tparameters` SET `update_channel`=:update_channel");
	$qry->execute(array(
		'update_channel' => $_POST['update_channel']
		));
}

//get current channel 
$qry = $db->prepare("SELECT `update_channel` FROM `tparameters`");
$qry->execute();
$update_channel=$qry->fetch();
$update_channel= $update_channel[0];
$qry->closeCursor();

if ($dedicated==0)
{
	//find current version
	$current_version=$rparameters['version'];
	$current_version2= explode('.',$current_version);
	if($rparameters['debug']==1) {echo "<b><u>DEBUG MODE:</u></b><br /> [CHANNEL] $update_channel<br /> [GET DATA] Local server version: $current_version (Version: $current_version2[0].$current_version2[1] Patch: $current_version2[2])<br />";}

	//find number of current patch
	$current_patch=$current_version2[2];

	//open ftp connection
	$conn_id = ftp_connect($ftp_server,21,2) or die(
	'
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">
			<i class="icon-remove"></i>
		</button>
		<strong>
			<i class="icon-remove"></i>
			'.T_('Erreur').':
		</strong>
		'.T_('Le serveur de mises à jour').' <b>'.$ftp_server.'</b> '.T_('est inaccessible, vérifier votre accès Internet ou l\'ouverture de votre firewall sur le port 21').'.
		</div>'
	);
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	$pasv = ftp_pasv($conn_id, true);

	//get ftp data of current update channel
	$ftp_list=ftp_nlist($conn_id, "./versions/current/$rparameters[update_channel]/");

	//get patch only
	$patch_ftp_list = preg_grep("/patch_$current_version2[0].$current_version2[1]/", $ftp_list);
	$patch_ftp_array = array();
	foreach($patch_ftp_list as $patch){
		$patch=explode("_",$patch);
		$patch=explode(".zip",$patch[1]);
		$patch=explode(".",$patch[0]);
		array_push($patch_ftp_array, $patch[2]);
	}
	asort($patch_ftp_array);
	$last_ftp_patch=end($patch_ftp_array);
	if ($last_ftp_patch) 
	{
		if($rparameters['debug']==1) {echo "[GET DATA] Last patch available on FTP server: $last_ftp_patch of version $current_version2[0].$current_version2[1]  <br />"; /*var_dump($patch_ftp_array);*/}
	} else {
		if($rparameters['debug']==1) {echo "[GET DATA] No patch available for version $current_version2[0].$current_version2[1]  <br />"; /*var_dump($patch_ftp_array);*/}
	}

	//get version only
	$version_ftp_list = preg_grep("/gestsup*/", $ftp_list);
	$version_ftp_array = array();
	foreach($version_ftp_list as $version){
		$version=explode("_",$version);
		$version=explode(".zip",$version[1]);
		$version=explode(".",$version[0]);
		array_push($version_ftp_array, $version);
	}
	asort($version_ftp_array);
	$last_ftp_version2=end($version_ftp_array);
	$last_ftp_version="$last_ftp_version2[0].$last_ftp_version2[1].$last_ftp_version2[2]";
	if ($last_ftp_version) 
	{
		if($rparameters['debug']==1) {echo "[GET DATA] Last version available on FTP server: $last_ftp_version<br />"; /*var_dump($version_ftp_array);*/}
	} else {
		if($rparameters['debug']==1) {echo "[GET DATA] No new version is available on FTP server. <br />"; /*var_dump($version_ftp_array)*/;}
	}

	//close ftp connection
	ftp_close($conn_id);

	//generate name of current version to display only
	$current_version_name='('.T_('Version').' '.$current_version2[0].'.'.$current_version2[1].' '.T_('avec patch').' '.$current_version2[2].')';
	 
	//check update server
	if ($last_ftp_version!=''){
		$serverstate='<i class="icon-ok-sign icon-large green"></i> <font color="green">'.T_('Serveur de mise à jour').' <b>'.$ftp_server.'</b> '.T_('est disponible').'.</font>';
		$findversion=0;
		$findpatch=0;
		//compare versions check two first number of version name
		if (($current_version2[0]==$last_ftp_version2[0]) && ($current_version2[1]==$last_ftp_version2[1]))
		{
			if($rparameters['debug']==1) {echo "[COMPARE VERSIONS] Local server version $current_version2[0].$current_version2[1] is the same as FTP server $last_ftp_version2[0].$last_ftp_version2[1] <br />"; }
			//compare patchs
			if ($current_patch==$last_ftp_patch)
			{
				if($rparameters['debug']==1) {echo "[COMPARE PATCH] Local server patch $current_patch is the same that FTP server $last_ftp_patch <br />"; }
				$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Votre version').' <strong class="green"><small>'.$current_version2[0].'.'.$current_version2[1].' Patch '.$current_patch.'</small></strong> '.T_('est à jour').'.	</div>';
			} 
			elseif ($current_patch>$last_ftp_patch)
			{
				if($rparameters['debug']==1) {echo "[COMPARE PATCH] Local server patch $current_patch is superior than FTP server $last_ftp_patch <br />"; }
				$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Votre version').' <strong class="green"><small>'.$current_version2[0].'.'.$current_version2[1].' Patch '.$current_patch.'</small></strong> '.T_('est supérieur à la dernière version disponible, vous devez avoir changé de canal de mises à jour').'.</div>';
			}
			elseif ($current_patch<$last_ftp_patch)
			{
				$findpatch=1;
				//generate n+1 name if more than one patch is available
				if (($last_ftp_patch-$current_patch)>1) {$next_ftp_patch=$current_patch+1;} else {$next_ftp_patch=$last_ftp_patch;}
				if($rparameters['debug']==1) {echo "[COMPARE PATCH] Local server patch $current_patch is inferior than FTP Server $last_ftp_patch <br />"; }
				$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Un nouveau patch').' <strong class="green"><small>'.$next_ftp_patch.'</small></strong> '.T_('pour votre version').' <strong class="green"><small>'.$current_version2[0].'.'.$current_version2[1].'</small></strong> '.T_('est disponible en téléchargement').'.</div>';
			}
		}
		else if (($current_version2[0]<$last_ftp_version2[0]) || ($current_version2[1]<$last_ftp_version2[1]))
		{
			if($rparameters['debug']==1) {echo "[COMPARE VERSIONS] Local server version $current_version2[0].$current_version2[1] is inferior than FTP server $last_ftp_version2[0].$last_ftp_version2[1]<br />"; }
			$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('La version').' <strong class="green"><small>'.$last_ftp_version.'</small></strong> '.T_('est disponible au téléchargement').'. </div>';
			$findversion=1;
		}
		else if (($current_version2[0]>$last_ftp_version2[0]) || (($current_version2[0]>$last_ftp_version2[0])&&($current_version2[1]>$last_ftp_version2[1])))
		{
			if($rparameters['debug']==1) {echo "[COMPARE VERSIONS] Local server version $current_version2[0].$current_version2[1] is superior than FTP server GestSup $last_ftp_version2[0].$last_ftp_version2[1], you are maybe a developper.<br />"; }
		}

		//display check message
		if($_POST['check']) echo $message;

		//downloads
		if($_POST['download'] || ($autoinstall==1))
		{
			if ($findversion==1) //version
			{
				$file_ftp_url="/versions/current/$update_channel/gestsup_$last_ftp_version.zip";
				$file_local_url=__DIR__ ."/../download/gestsup_$last_ftp_version.zip";
				$conn_id = ftp_connect($ftp_server);
				$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
				if ((!$conn_id) || (!$login_result)) {
					echo'<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_('Le téléchargement de la dernière version à échoué, vérifiez les droits d\'écriture sur le répertoire ./download de votre serveur web').'.</div>';
					die;
				}
				$pasv = ftp_pasv($conn_id, true);
				$download = ftp_get($conn_id, $file_local_url, $file_ftp_url, FTP_BINARY);
				if (!$download) 
				{
					echo'<div class="alert alert-danger"><i class="icon-remove"></i><strong> '.T_('Erreur').':</strong> '.T_('Le téléchargement de la dernière version à échoué').'.</div>';
				}
				else 
				{
					echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('La version').' <strong class="green"><small>'.$last_ftp_version.'</small></strong> '.T_('à été téléchargée dans le répertoire "./download" du serveur web').'.</div>';
				}
				ftp_quit($conn_id);
			}
			else if ($findpatch==1) //patch
			{
				$file_ftp_url="/versions/current/$update_channel/patch_$current_version2[0].$current_version2[1].$next_ftp_patch.zip";
				$file_local_url=__DIR__ ."/../download/patch_$current_version2[0].$current_version2[1].$next_ftp_patch.zip";
				$conn_id = ftp_connect($ftp_server);
				$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
				if ((!$conn_id) || (!$login_result)) {
					echo'<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_('Le téléchargement du dernier patch à échoué. (connexion impossible)').'</div>';
					die;
				} 
				$pasv = ftp_pasv($conn_id, true);
				$download = ftp_get($conn_id, $file_local_url, $file_ftp_url, FTP_BINARY);
				if (!$download) 
				{
					echo'<div class="alert alert-danger"><i class="icon-remove"></i><strong> '.T_('Erreur').':</strong> '.T_('Le téléchargement du dernier patch à échoué. (Téléchargement impossible)').'</div>';
				}
				else 
				{
					echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Le patch').'	<strong class="green"><small>'.$next_ftp_patch.'</small></strong> '.T_('à été téléchargé dans le répertoire').' "./download" '.T_('du serveur web').'.</div>';
				}
				ftp_quit($conn_id);
			} else {
				echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Votre version').' <strong class="green"><small>'.$current_version.'</small></strong>	'.T_('est à jour, pas de téléchargement nécessaire').'.</div>';
			}
		}
		//install version
		if($_POST['install'] || ($autoinstall==1))
		{
			if ($findpatch==0 && $findversion==0)
			{
				echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Installation impossible votre version est à jour').'.</div>';
			} 
			if($findversion!=0) 
			{
				if(file_exists(__DIR__ ."/../download/gestsup_$last_ftp_version.zip"))
				{
					$installfile="gestsup_$last_ftp_version.zip";
					echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Installation du fichier').' <strong class="green"><small>'.$installfile.'</small></strong>	'.T_('en cours...').'</div>';
					$type="version";
					include(__DIR__ ."/../core/install_update.php");
				} else {
					echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Vous devez d\'abord télécharger la dernière version').' '.$last_ftp_version.'.</div>';
				}
			}
			if($findpatch!=0)
			{
				if(file_exists(__DIR__ ."/../download/patch_$current_version2[0].$current_version2[1].$next_ftp_patch.zip"))
				{
					$installfile="patch_$current_version2[0].$current_version2[1].$next_ftp_patch.zip";
					echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Installation du fichier').' <strong class="green"><small>'.$installfile.'</small></strong> '.T_('en cours...').'</div>';
					$type="patch";
					include(__DIR__ ."/../core/install_update.php");
				} else {
					echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Vous devez d\'abord télécharger le dernier patch').' '.$next_ftp_patch.'</div>';
				}
			}
		}
		
	} else {
		$serverstate='<i class="icon-remove-sign icon-large red"></i> <font color="red">'.T_('Serveur de mise à jour GestSup indisponible, ou vous avez un problème de connection internet ou vous n\'avez pas autorisé le port 21 sur votre firewall').'.</font>';
	}

	//display informations
	echo'
		<div class="profile-user-info profile-user-info-striped">
			<div class="profile-info-row">
				<div class="profile-info-name">'.T_('Version actuelle').': </div>
				<div class="profile-info-value">
					<span id="username">'.$rparameters['version'].' <span style="font-size: x-small;">'.$current_version_name.'</span></span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> '.T_('Canal').': </div>
				<div class="profile-info-value">
					<span id="username">
						<form method="POST" name="form">
							<select name="update_channel" onchange="submit()">
								<option value="stable" '; if ($update_channel=='stable') echo 'selected'; echo '>'.T_('Stable').'</option>
								<option value="beta" '; if ($update_channel=='beta') echo 'selected'; echo '>'.T_('Bêta').'</option>
							</select>
						</form>
					</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> '.T_('Serveur de MAJ').': </div>
				<div class="profile-info-value">
					<span id="username">'.$serverstate.'</span>
				</div>
			</div>
		</div>
		<br />
		<br />
		<br />
		<br />
		<center>
			<form method="POST" action="">
				<button  title="'.T_('Vérifie sur le serveur FTP de GestSup si une version plus récente existe').'." name="check" value="check" type="submit" class="btn btn-primary">
					<i class="icon-ok-sign bigger-120"></i>
					1- '.T_('Vérifier').' 
				</button>
				<button title="'.T_('Lance le téléchargement depuis le serveur FTP de GestSup si une version plus récente existe').'." name="download" value="download" type="submit" class="btn btn-primary">
					<i class="icon-download-alt bigger-120"></i>
					2- '.T_('Télécharger').'
				</button>
				<button title="'.T_('Lance l\'installation de la version téléchargée').'" name="install" value="install" type="submit" class="btn btn-primary">
					<i class="icon-hdd bigger-120"></i>
					3- '.T_('Installation semi-automatique').'
				</button>
			</form>
				<br />
				<button title="'.T_('Lance le site web dans la section documentation').'" onclick=\'window.open("https://gestsup.fr/index.php?page=support&item1=update&item2=manual#53")\' type="submit" class="btn btn-grey">
					<i class="icon-hdd bigger-120"></i>
					'.T_('Installation manuelle').'
				</button>
				<button title="'.T_('Lance le site web dans la section documentation').'" onclick=\'window.open("https://gestsup.fr/index.php?page=support&item1=update&item2=auto#51")\' type="submit" class="btn btn-grey">
					<i class="icon-hdd bigger-120"></i>
					'.T_('Installation automatique').'
				</button>
				<button title="'.T_('Redirige vers la section sauvegarde de l\'application').'" onclick=\'window.open("./index.php?page=admin&subpage=backup")\' type="submit" class="btn btn-danger">
					<i class="icon-save bigger-120"></i>
					'.T_('Réaliser une sauvegarde').'
				</button>
		</center>
	';
} else { //dedicated version
	//find current version
	$current_version=$rparameters['version'];
	$current_version2= explode('.',$current_version);
	
	//find number of current patch
	$current_patch=$current_version2[3];
	
	if($rparameters['debug']==1) {echo "<b><u>DEBUG MODE:</u></b><br /> [VERSION] Dedicated <br />[GET DATA] Local server version: $current_version (Version: $current_version2[0].$current_version2[1].$current_version2[2] Patch: $current_patch)<br />";}

	//open ftp connection
	$conn_id = ftp_connect($ftp_server,21,2) or die(
	'
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">
			<i class="icon-remove"></i>
		</button>
		<strong>
			<i class="icon-remove"></i>
			'.T_('Erreur').':
		</strong>
		'.T_('Le serveur de mises à jour').' <b>'.$ftp_server.'</b> '.T_('est inaccessible, vérifier votre accès Internet ou l\'ouverture de votre firewall sur le port 21').'.
		</div>'
	);
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	$pasv = ftp_pasv($conn_id, true);
	
	if(!$conn_id) {
		$serverstate='<i class="icon-remove-sign icon-large red"></i> <font color="red">'.T_('Serveur de mise à jour GestSup indisponible, ou vous avez un problème de connection internet ou vous n\'avez pas autorisé le port 21 sur votre firewall').'.</font>';
	} else {
		$serverstate='<i class="icon-ok-sign icon-large green"></i> <font color="green">'.T_('Serveur de mise à jour').' <b>'.$ftp_server.'</b> '.T_('est disponible').'.</font>';
	}
	
	//check dedicated version exist
	if(!isset($command)) $command= '';
	if (!ftp_chdir($conn_id,"./versions/dedicated/$rparameters[server_private_key]/")){
		if($rparameters['debug']==1) {echo "[DEDICATED] Directory not found<br />";}
	} else {
		if($rparameters['debug']==1) {echo "[DEDICATED] Directory found<br />";}
		//list ftp data from dedicated version
		$ftp_list=ftp_nlist($conn_id, "");
		//get patch only
		$patch_ftp_list = preg_grep("/patch_$current_version2[0].$current_version2[1].$current_version2[2]/", $ftp_list);
		$patch_ftp_array = array();
		foreach($patch_ftp_list as $patch){
			$patch=explode("_",$patch);
			$patch=explode(".zip",$patch[1]);
			$patch=explode(".",$patch[0]);
			array_push($patch_ftp_array, $patch[3]);
		}
		asort($patch_ftp_array);
		$last_ftp_patch=end($patch_ftp_array);
		if ($last_ftp_patch) 
		{
			if($rparameters['debug']==1) {echo "[GET DATA] Last patch available on FTP server: $last_ftp_patch of version $current_version2[0].$current_version2[1].$current_version2[2]  <br />"; /*var_dump($patch_ftp_array);*/}
		} else {
			if($rparameters['debug']==1) {echo "[GET DATA] No patch available for version $current_version2[0].$current_version2[1].$current_version2[2]  <br />"; /*var_dump($patch_ftp_array);*/}
		}

		//close ftp connection
		ftp_close($conn_id);

		//generate name of current version to display only
		$current_version_name='('.T_('Version').' '.$current_version2[0].'.'.$current_version2[1].'.'.$current_version2[2].' '.T_('avec patch').' '.$current_version2[3].')';
		 
		//check update server
		$findversion=0;
		$findpatch=0;

		if(!$last_ftp_patch)
		{
			$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Aucun nouveau patch pour votre version').' <strong class="green"><small>'.$current_version2[0].'.'.$current_version2[1].'.'.$current_version2[2].' Patch '.$current_patch.'</small></strong> '.T_("n'est encore disponible").'.	</div>';
		}elseif ($current_patch==$last_ftp_patch)
		{
			if($rparameters['debug']==1) {echo "[COMPARE PATCH] Local server patch $current_patch is the same that FTP server $last_ftp_patch <br />"; }
			$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Votre version').' <strong class="green"><small>'.$current_version2[0].'.'.$current_version2[1].'.'.$current_version2[2].' Patch '.$current_patch.'</small></strong> '.T_('est à jour').'.	</div>';
		} 
		elseif ($current_patch>$last_ftp_patch)
		{
			if($rparameters['debug']==1) {echo "[COMPARE PATCH] Local server patch $current_patch is superior than FTP server $last_ftp_patch <br />"; }
			$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Votre version').' <strong class="green"><small>'.$current_version2[0].'.'.$current_version2[1].'.'.$current_version2[2].' Patch '.$current_patch.'</small></strong> '.T_('est supérieur à la dernière version disponible, vous devez avoir changé de canal de mises à jour').'.</div>';
		}
		elseif ($current_patch<$last_ftp_patch)
		{
			$findpatch=1;
			//generate n+1 name if more than one patch is available
			if (($last_ftp_patch-$current_patch)>1) {$next_ftp_patch=$current_patch+1;} else {$next_ftp_patch=$last_ftp_patch;}
			if($rparameters['debug']==1) {echo "[COMPARE PATCH] Local server patch $current_patch is inferior than FTP Server $last_ftp_patch <br />"; }
			$message='<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Un nouveau patch').' <strong class="green"><small>'.$next_ftp_patch.'</small></strong> '.T_('pour votre version').' <strong class="green"><small>'.$current_version2[0].'.'.$current_version2[1].'.'.$current_version2[2].'</small></strong> '.T_('est disponible en téléchargement').'.</div>';
		}
		//display check message
		if($_POST['check']) echo $message;

		//downloads
		if($_POST['download'] || ($autoinstall==1))
		{
			if ($findpatch==1) //patch
			{
				$file_ftp_url="/versions/dedicated/$rparameters[server_private_key]/patch_$current_version2[0].$current_version2[1].$current_version2[2].$next_ftp_patch.zip";
				$file_local_url=__DIR__ ."/../download/patch_$current_version2[0].$current_version2[1].$current_version2[2].$next_ftp_patch.zip";
				$conn_id = ftp_connect($ftp_server);
				$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
				if ((!$conn_id) || (!$login_result)) {
					echo'<div class="alert alert-danger"><strong><i class="icon-remove"></i> '.T_('Erreur').':</strong> '.T_('Le téléchargement du dernier patch à échoué. (connexion impossible)').'</div>';
					die;
				} 
				$pasv = ftp_pasv($conn_id, true);
				$download = ftp_get($conn_id, $file_local_url, $file_ftp_url, FTP_BINARY);
				if (!$download) 
				{
					echo'<div class="alert alert-danger"><i class="icon-remove"></i><strong> '.T_('Erreur').':</strong> '.T_('Le téléchargement du dernier patch à échoué. (Téléchargement impossible)').'</div>';
				}
				else 
				{
					echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Le patch').'	<strong class="green"><small>'.$next_ftp_patch.'</small></strong> '.T_('à été téléchargé dans le répertoire').' "./download" '.T_('du serveur web').'.</div>';
				}
				ftp_quit($conn_id);
			} else {
				echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Votre version').' <strong class="green"><small>'.$current_version.'</small></strong>	'.T_('est à jour, pas de téléchargement nécessaire').'.</div>';
			}
		}
		//install patch
		if($_POST['install'] || ($autoinstall==1))
		{
			if ($findpatch==0)
			{
				echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Installation impossible votre version est à jour').'.</div>';
			} 
			if($findpatch!=0)
			{
				if(file_exists(__DIR__ ."/../download/patch_$current_version2[0].$current_version2[1].$current_version2[2].$next_ftp_patch.zip"))
				{
					$installfile="patch_$current_version2[0].$current_version2[1].$current_version2[2].$next_ftp_patch.zip";
					echo '<div class="alert alert-block alert-success"><i class="icon-ok green"></i> '.T_('Installation du fichier').' <strong class="green"><small>'.$installfile.'</small></strong> '.T_('en cours...').'</div>';
					$type="patch";
					include(__DIR__ ."/../core/install_update.php");
				} else {
					echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Vous devez d\'abord télécharger le dernier patch').' '.$next_ftp_patch.'</div>';
				}
			}
		}

		//display informations
		echo'
			<div class="profile-user-info profile-user-info-striped">
				<div class="profile-info-row">
					<div class="profile-info-name">'.T_('Version actuelle').': </div>
					<div class="profile-info-value">
						<span id="username">'.$rparameters['version'].' <span style="font-size: x-small;">'.$current_version_name.'</span></span>
					</div>
				</div>
				<div class="profile-info-row">
					<div class="profile-info-name"> '.T_('Serveur de MAJ').': </div>
					<div class="profile-info-value">
						<span id="username">'.$serverstate.'</span>
					</div>
				</div>
			</div>
			<br />
			<br />
			<br />
			<br />
			<center>
				<form method="POST" action="">
					<button  title="'.T_('Vérifie sur le serveur FTP de GestSup si une version plus récente existe').'." name="check" value="check" type="submit" class="btn btn-primary">
						<i class="icon-ok-sign bigger-120"></i>
						1- '.T_('Vérifier').' 
					</button>
					<button title="'.T_('Lance le téléchargement depuis le serveur FTP de GestSup si une version plus récente existe').'." name="download" value="download" type="submit" class="btn btn-primary">
						<i class="icon-download-alt bigger-120"></i>
						2- '.T_('Télécharger').'
					</button>
					<button title="'.T_('Lance l\'installation de la version téléchargée').'" name="install" value="install" type="submit" class="btn btn-primary">
						<i class="icon-hdd bigger-120"></i>
						3- '.T_('Installation semi-automatique').'
					</button>
				</form>
					<br />
					<button title="'.T_('Lance le site web dans la section documentation').'" onclick=\'window.open("https://gestsup.fr/index.php?page=support&item1=update&item2=manual#53")\' type="submit" class="btn btn-grey">
						<i class="icon-hdd bigger-120"></i>
						'.T_('Installation manuelle').'
					</button>
					<button title="'.T_('Lance le site web dans la section documentation').'" onclick=\'window.open("https://gestsup.fr/index.php?page=support&item1=update&item2=auto#51")\' type="submit" class="btn btn-grey">
						<i class="icon-hdd bigger-120"></i>
						'.T_('Installation automatique').'
					</button>
					<button title="'.T_('Redirige vers la section sauvegarde de l\'application').'" onclick=\'window.open("./index.php?page=admin&subpage=backup")\' type="submit" class="btn btn-danger">
						<i class="icon-save bigger-120"></i>
						'.T_('Réaliser une sauvegarde').'
					</button>
			</center>
		';
	}
}
?>