<?php
################################################################################
# @Name : wol.php
# @Description : wake on lan ip asset
# @call : ./core/asset.php
# @parameters : $_GET[mac]
# @Author : Flox
# @Create : 19/12/2015
# @Update : 04/12/2017
# @Version : 3.1.28
################################################################################

if (ctype_xdigit($_GET['mac'])) {
	//OS detect
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$rootfolder=dirname(__FILE__);
		$rootfolder=str_replace('\\', '\\\\',$rootfolder);
		$rootfolder=str_replace('core', '',$rootfolder);
		$rootfolder=$rootfolder.'components\\\\wol';
		$result=exec("\"$rootfolder\\\\wol.exe\" $_GET[mac]");
	} else {
		$mac=str_split($_GET['mac'], 2);
		$mac="$mac[0]:$mac[1]:$mac[2]:$mac[3]:$mac[4]:$mac[5]";
		$result=exec("wakeonlan $mac");
	}
} else {
	$result=" No hexadecimal digit detected";
}


//test result
if(($result=="Wake-up packet sent successfully.") || (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN'))
{
	//display result
	echo '<div class="alert alert-block alert-success"><center><i class="icon-bolt bigger-130 green"></i>	'.T_('Allumage de').' <b>'.$globalrow['netbios'].'</b> : OK <span style="font-size: x-small;">('.$result.')</span> </center></div>';
} else {
	//display result
    echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.T_('Vérifier le wake on lan est bien installé (LINUX: apt-get install wakeonlan)').''.$result.' </div>';
}
?>