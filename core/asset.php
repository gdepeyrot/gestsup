<?php
################################################################################
# @Name : ./core/asset.php 
# @Description : actions page for assets
# @call : ./asset.php
# @Author : Flox
# @Create : 28/10/2013
# @Update : 30/05/2018
# @Version : 3.1.30 p2
################################################################################

//initialize variable
if(!isset($_POST['close'])) $_POST['close'] = '';
if(!isset($_POST['text'])) $_POST['text'] = '';
if(!isset($_POST['send'])) $_POST['send'] = '';
if(!isset($_POST['action'])) $_POST['action'] = '';
if(!isset($_POST['modify'])) $_POST['modify'] = '';
if(!isset($_POST['quit'])) $_POST['quit'] = '';
if(!isset($_POST['cancel'])) $_POST['cancel'] = '';
if(!isset($_POST['netbios_lan_new'])) $_POST['netbios_lan_new'] = '';
if(!isset($_POST['ip_lan_new'])) $_POST['ip_lan_new'] = '';
if(!isset($_POST['mac_lan_new'])) $_POST['mac_lan_new'] = '';
if(!isset($_POST['netbios_wifi_new'])) $_POST['netbios_wifi_new'] = '';
if(!isset($_POST['ip_wifi_new'])) $_POST['ip_wifi_new'] = '';
if(!isset($_POST['mac_wifi_new'])) $_POST['mac_wifi_new'] = '';
if(!isset($_POST['virtualization'])) $_POST['virtualization'] = '';

if(!isset($_GET['disable'])) $_GET['disable'] = '';
if(!isset($_GET['fromnew'])) $_GET['fromnew'] = '';	
if(!isset($_GET['state'])) $_GET['state'] = '';	
if(!isset($_GET['date_stock'])) $_GET['date_stock'] = '';	
if(!isset($_GET['department'])) $_GET['department'] = '';	
if(!isset($_GET['model'])) $_GET['model'] = '';	
if(!isset($_GET['virtualization'])) $_GET['virtualization'] = '';	
if(!isset($_GET['type'])) $_GET['type'] = '';	
if(!isset($_GET['user'])) $_GET['user'] = '';	
if(!isset($_GET['netbios'])) $_GET['netbios'] = '';	
if(!isset($_GET['ip'])) $_GET['ip'] = '';	
if(!isset($_GET['sn_internal'])) $_GET['sn_internal'] = '';	
if(!isset($_GET['description'])) $_GET['description'] = '';	
if(!isset($_GET['iptoping'])) $_GET['iptoping'] = '';	
if(!isset($_GET['scan'])) $_GET['scan'] = '';	
if(!isset($_GET['iface'])) $_GET['iface'] = '';	

$_GET['scan']=strip_tags($_GET['scan']);
$_GET['iface']=strip_tags($_GET['iface']);
$_GET['findip']=strip_tags($_GET['findip']);
$_GET['disable']=strip_tags($_GET['disable']);

if(!isset($error)) $error="0";

//display find and iface modalbox
if( preg_match( '/^findip.*/',$_GET['action'])) include('./asset_findip.php');

if($_GET['action']=='addiface' || $_GET['action']=='editiface') include('./asset_iface.php');

if ($rparameters['debug']==1) {echo "<b><u>DEBUG MODE:</u></b><br />";}

//use stock asset if exist
if($_POST['model']!='' && $_GET['action']=='new')
{
	$qry = $db->prepare("SELECT `id` FROM `tassets` WHERE `sn_internal`=(SELECT MIN(sn_internal) FROM `tassets` WHERE state=:state AND model=:model AND disable=:disable)");
	$qry->execute(array(
		'state' => 1,
		'model' => $_POST['model'],
		'disable' => 0,
		));
	$row=$qry->fetch();
	$qry->closeCursor();
	
	if ($row[0])
	{
		//redirect
		echo "<SCRIPT LANGUAGE='JavaScript'>
					<!--
					function redirect()
					{
					window.location='./index.php?page=asset&id=$row[id]&fromnew=1&$url_get_parameters'
					}
					setTimeout('redirect()');
					-->
			</SCRIPT>";
	}
}

//find next asset number
if($_GET['action']=='new')
{
	$qry = $db->prepare("SELECT MAX(CONVERT(sn_internal, SIGNED INTEGER)) FROM tassets");
	$qry->execute();
	$row_sn_internal=$qry->fetch();
	$qry->closeCursor();

	$qry = $db->prepare("SELECT MAX(id) FROM tassets");
	$qry->execute();
	$row_id=$qry->fetch();
	$qry->closeCursor(); 
	
	$_POST['sn_internal'] =$row_sn_internal[0]+1;
	$_GET['id'] =$row_id[0]+1;
}

//action delete asset
if (($_GET['action']=="delete") && ($rright['asset_delete']!=0))
{
	//disable asset
	$qry=$db->prepare("UPDATE `tassets` SET `disable`=:disable WHERE `id`=:id");
	$qry->execute(array('disable' => 1,'id' => $_GET['id']));
	
	//disable iface
	$qry=$db->prepare("UPDATE `tassets_iface` SET `disable`=:disable WHERE `asset_id`=:id");
	$qry->execute(array('disable' => 1,'id' => $_GET['id']));

	//display delete message
	echo '<div class="alert alert-block alert-success"><center><i class="icon-ok green"></i>	'.T_('Équipement supprimé').'.</center></div>';
	//redirect
	echo "<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php?page=asset_list&url_get_parameters'
				}
				setTimeout('redirect()',$rparameters[time_display_msg]);
				-->
		</SCRIPT>";
}

//action for enable or disable network scan
if($rright['asset_net_scan']!=0 && $_GET['scan']!='') {
	$qry=$db->prepare("UPDATE `tassets` SET `net_scan`=:net_scan WHERE `id`=:id");
	$qry->execute(array('net_scan' => $_GET['scan'],'id' => $_GET['id']));
}

//master query
$qry = $db->prepare("SELECT * FROM `tassets` WHERE `id`=:id");
$qry->execute(array('id' => $_GET['id']));
$globalrow=$qry->fetch();
$qry->closeCursor();

//auto convert state if new asset
if ($globalrow['state']==1 && $_GET['fromnew']==1) {$globalrow['state']=2;}

//action ping this asset
if ($_GET['action']=="ping") 
{

	require('./core/ping.php');
	$time=$rparameters['time_display_msg']+2000;
	//redirect
	echo "<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php?page=asset&id=$_GET[id]&$url_get_parameters'
				}
				setTimeout('redirect()',$time);
				-->
		</SCRIPT>";
}

//action wake on lan this asset
if ($_GET['action']=="wol") 
{
	require('./core/wol.php');
	$time=$rparameters['time_display_msg']+2000;
	//redirect
	echo "<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php?page=asset&id=$_GET[id]&$url_get_parameters'
				}
				setTimeout('redirect()',$time);
				-->
		</SCRIPT>";
}

//delete selected interface
if($_GET['action']=='delete_iface' && $_GET['iface'] && $rright['asset_delete']!=0) {
	//disable iface 
	$qry=$db->prepare("UPDATE `tassets_iface` SET `disable`=:disable WHERE `id`=:id");
	$qry->execute(array('disable' => 1,'id' => $_GET['iface']));
	//display delete message
	echo '<div class="alert alert-block alert-success"><center><i class="icon-ok green"></i>	'.T_('Interface supprimé').'.</center></div>';
	//redirect
	echo "<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php?page=asset&id=$_GET[id]&$url_get_parameters'
				}
				setTimeout('redirect()',$rparameters[time_display_msg]);
				-->
		</SCRIPT>";
} 

//convert posted date to SQL format, if yyyy-mm-dd is detected
if($_POST['date_stock'] && !strpos($_POST['date_stock'], "-"))
{
	$_POST['date_stock'] = DateTime::createFromFormat('d/m/Y', $_POST['date_stock']);
	$_POST['date_stock']=$_POST['date_stock']->format('Y-m-d');
}
if($_POST['date_install'] && !strpos($_POST['date_install'], "-"))
{
	$_POST['date_install'] = DateTime::createFromFormat('d/m/Y', $_POST['date_install']);
	$_POST['date_install']=$_POST['date_install']->format('Y-m-d');
}
if($_POST['date_end_warranty'] && !strpos($_POST['date_end_warranty'], "-"))
{
	$_POST['date_end_warranty'] = DateTime::createFromFormat('d/m/Y', $_POST['date_end_warranty']);
	$_POST['date_end_warranty']=$_POST['date_end_warranty']->format('Y-m-d');
}
if($_POST['date_standbye'] && !strpos($_POST['date_standbye'], "-"))
{
	$_POST['date_standbye'] = DateTime::createFromFormat('d/m/Y', $_POST['date_standbye']);
	$_POST['date_standbye']=$_POST['date_standbye']->format('Y-m-d');
}
if($_POST['date_recycle'] && !strpos($_POST['date_recycle'], "-"))
{
	$_POST['date_recycle'] = DateTime::createFromFormat('d/m/Y', $_POST['date_recycle']);
	$_POST['date_recycle']=$_POST['date_recycle']->format('Y-m-d');
}

//update ip send from searchip popup and save on iface
if($_GET['findip'] && $_GET['iface']) {
	$qry=$db->prepare("UPDATE `tassets_iface` SET `ip`=:ip WHERE `id`=:id");
	$qry->execute(array('ip' => $_GET['findip'],'id' => $_GET['id']));
}

//database inputs if submit
if($_POST['modify']||$_POST['quit']||$_POST['action']) 
{
	//secure string
	$_POST['netbios']=strip_tags($_POST['netbios']);
	$_POST['sn_internal']=strip_tags($_POST['sn_internal']);
	$_POST['sn_manufacturer']=strip_tags($_POST['sn_manufacturer']);
	$_POST['description']=strip_tags($_POST['description']);
	$_POST['sn_indent']=strip_tags($_POST['sn_indent']);
	$_POST['socket']=strip_tags($_POST['socket']);
	$globalrow['sn_internal']=strip_tags($globalrow['sn_internal']);  //avoid database simple quote
	
	//auto insert date if change state on editing ticket
	if ($_GET['action']!='new')
	{
		if ($globalrow['state']!='2' && $_POST['state']=='2') {if (!$_POST['date_install']) {$_POST['date_install']=date('Y-m-d');} }
		if ($globalrow['state']!='3' && $_POST['state']=='3') {$_POST['date_standbye']=date('Y-m-d'); }
		if ($globalrow['state']!='4' && $_POST['state']=='4') {$_POST['date_recycle']=date('Y-m-d'); }
	}

	//check duplicate sn_internal
	$qry = $db->prepare("SELECT `id` FROM `tassets` WHERE sn_internal=:sn_internal AND sn_internal!=:sn_internal2 AND state!=:state AND id!=:id AND disable=:disable");
	$qry->execute(array(
		'sn_internal' => $_POST['sn_internal'],
		'sn_internal2' => '',
		'state' => 4,
		'id' => $_GET['id'],
		'disable' => 0
		));
	$row=$qry->fetch();
	$qry->closeCursor();

	if ($row[0]!='' && ($_POST['sn_internal']!=$globalrow['sn_internal'])) {$error=T_('Un autre équipement possède déjà cet identifiant').'. (<a target="_blank" href="./index.php?page=asset&id='.$row['id'].'" >'.T_('Voir sa fiche').'</a>)';} 

	//check duplicate manufacturer
	$qry = $db->prepare("SELECT `id` FROM `tassets` WHERE `sn_manufacturer`=:sn_manufacturer AND sn_manufacturer!=:sn_manufacturer2 AND state!=:state AND id!=:id AND disable=:disable");
	$qry->execute(array(
		'sn_manufacturer' => $_POST['sn_manufacturer'],
		'sn_manufacturer2' => '',
		'state' => 4,
		'id' => $_GET['id'],
		'disable' => 0
		));
	$row=$qry->fetch();
	$qry->closeCursor();
	if ($row[0]!='' && ($_POST['sn_manufacturer']!=$globalrow['sn_manufacturer'])) {$error=T_('Un autre équipement possède déjà ce numéro de série fabriquant').' (<a target="_blank" href="./index.php?page=asset&id='.$row['id'].'" >'.T_('Voir sa fiche').'</a>).';} 
	
	//iface existing treatment

	$qry = $db->prepare("SELECT * FROM `tassets_iface` WHERE `asset_id`=:asset_id AND `disable`=:disable");
	$qry->execute(array(
		'asset_id' => $_GET['id'],
		'disable' => 0
		));
	while ($row = $qry->fetch()) 
	{
		//init post values
		if(!isset($iface_netbios)) $iface_netbios = '';
		if(!isset($iface_ip)) $iface_ip = '';
		if(!isset($iface_mac)) $iface_mac = '';
		
		if(!isset($_POST[$iface_netbios])) $_POST[$iface_netbios] = '';
		if(!isset($_POST[$iface_ip])) $_POST[$iface_ip] = '';
		if(!isset($_POST[$iface_mac])) $_POST[$iface_mac] = '';
		
		//get date from iface inputs
		$iface_netbios='netbios_'.$row['id'];
		$iface_netbios=$_POST[$iface_netbios];
		$iface_ip='ip_'.$row['id'];
		$iface_ip=$_POST[$iface_ip];
		$iface_mac='mac_'.$row['id'];
		$iface_mac=$_POST[$iface_mac];
		
		//check duplicate ip
		$qry2 = $db->prepare("
		SELECT tassets_iface.* 
		FROM tassets_iface
		INNER JOIN tassets ON tassets.id=tassets_iface.asset_id
		INNER JOIN tassets_state ON tassets_state.id=tassets.state
		WHERE 
		tassets_state.block_ip_search=:block_ip_search AND
		tassets_iface.ip=:ip AND
		tassets_iface.ip!=:ip2 AND
		tassets_iface.asset_id!=:asset_id AND
		tassets_iface.disable=:iface_disable AND
		tassets.disable=:asset_disable
		");
		$qry2->execute(array(
		'block_ip_search' => 1,
		'ip' => $iface_ip,
		'ip2' => '',
		'asset_id' => $globalrow['id'],
		'iface_disable' => 0,
		'asset_disable' => 0
		));
		$row2=$qry2->fetch();
		$qry2->closeCursor();
		if ($row2[0]!='') {$error=T_('Un autre équipement possède déjà cette l\'adresse IP').'. (<a target="_blank" href="./index.php?page=asset&id='.$row2['asset_id'].'" >'.T_('Voir sa fiche').'</a>)';} 
		
		//check duplicate mac
		$qry2 = $db->prepare("SELECT `id`,`asset_id` FROM `tassets_iface` WHERE `mac`=:mac AND `mac`!=:mac2 AND `asset_id`!=:asset_id AND `disable`=:disable");
		$qry2->execute(array(
			'mac' => $iface_mac,
			'mac2' => '',
			'asset_id' => $globalrow['id'],
			'disable' => 0
			));
		$row2=$qry2->fetch();
		$qry2->closeCursor();
		if ($row2[0]!='') {$error=T_('Un autre équipement possède déjà cette adresse MAC').'. (<a target="_blank" href="./index.php?page=asset&id='.$row2['asset_id'].'" >'.T_('Voir sa fiche').'</a>)';} 
		
		//control number of digit of MAC address
		if ((strlen($iface_mac)!=12) && $iface_mac!='') {$error=T_('Les adresses MAC doivent contenir 12 caractères').' ('.strlen($iface_mac).' '.T_('caractères détectés').').';} 
		
		//control number of digit of IP address
		if ((strlen($iface_ip)<7) && $iface_ip!='') {$error=T_('Les adresses IP doivent contenir au moins 7 caractères').' ('.strlen($iface_ip).' '.T_('caractères détectés').').';} 
		
		//escape special char and secure string before database update
		$iface_netbios=strip_tags($iface_netbios);
		$iface_ip=strip_tags($iface_ip);
		$iface_mac=strip_tags($iface_mac);
		
		//update tassets_iface table
		if($error=='0')
		{
			$qry2=$db->prepare("UPDATE `tassets_iface` SET `netbios`=:netbios,`ip`=:ip ,`mac`=:mac WHERE `id`=:id");
			$qry2->execute(array(
			'netbios' => $iface_netbios,
			'ip' => $iface_ip,
			'mac' => $iface_mac,
			'id' => $row['id']
			));
		}	
	}
	$qry->closeCursor();
	
	//check fields for new asset
	if($error=='0')
	{
		//find asset id to add iface
		if (!isset($globalrow['id'])) {
			$qry = $db->prepare("SELECT MAX(id) FROM tassets WHERE disable=:disable");
			$qry->execute(array(
				'disable' => 0
				));
			$asset_id=$qry->fetch();
			$qry->closeCursor();
			$asset_id=$asset_id[0]+1;
		} else {$asset_id=$globalrow['id'];}
		
		if(($_POST['netbios_lan_new'] || $_POST['ip_lan_new'] || $_POST['mac_lan_new']) && $error=='0')
		{
			//secure string
			$db_netbios_lan_new=strip_tags($_POST['netbios_lan_new']);
			$db_ip_lan_new=strip_tags($_POST['ip_lan_new']);
			$db_mac_lan_new=strip_tags($_POST['mac_lan_new']);
			
			//check fields for new asset LAN IP
			$qry = $db->prepare("
			SELECT tassets_iface.* 
			FROM tassets_iface
			INNER JOIN tassets ON tassets.id=tassets_iface.asset_id
			INNER JOIN tassets_state ON tassets_state.id=tassets.state
			WHERE 
			tassets_state.block_ip_search=:block_ip_search AND
			tassets_iface.ip=:ip AND
			tassets_iface.ip!=:ip2 AND
			tassets_iface.disable=:iface_disable AND
			tassets.disable=:asset_disable
			");
			$qry->execute(array(
				'block_ip_search' => 1,
				'ip' => $db_ip_lan_new,
				'ip2' => '',
				'iface_disable' => 0,
				'asset_disable' => 0
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if ($row[0]!='') {$error=T_('Un autre équipement possède déjà  l\'adresse IP').': '.$db_ip_lan_new.'. (<a target="_blank" href="./index.php?page=asset&id='.$row['asset_id'].'" >'.T_('Voir sa fiche').'</a>)';} 
			
			//check fields for new asset LAN MAC
			$qry = $db->prepare("SELECT * FROM tassets_iface WHERE mac=:mac AND mac!=:mac2 AND disable=:disable");
			$qry->execute(array(
				'mac' => $db_mac_lan_new,
				'mac2' => '',
				'disable' => 0
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if ($row[0]!='') {$error=T_('Un autre équipement possède déjà  l\'adresse MAC').': '.$db_mac_lan_new.'. (<a target="_blank" href="./index.php?page=asset&id='.$row['asset_id'].'" >'.T_('Voir sa fiche').'</a>)';} 
			
			//control number of digit of LAN MAC address
			if ((strlen($_POST['mac_lan_new'])!=12) && $_POST['mac_lan_new']!='') {$error=T_('Les adresses MAC doivent contenir 12 caractères').' ('.strlen($_POST['mac_lan_new']).' '.T_('caractères détectés').').';} 
		
			//control number of digit of LAN IP address
			if ((strlen($_POST['ip_lan_new'])<7) && $_POST['ip_lan_new']!='') {$error=T_('Les adresses IP doivent contenir au moins 7 caractères').' ('.strlen($_POST['ip_lan_new']).' '.T_('caractères détectés').').';} 
		
		}
		if(($_POST['netbios_wifi_new'] || $_POST['ip_wifi_new'] || $_POST['mac_wifi_new']) && $error=='0')
		{
			//escape special char and secure string before database insert
			$db_netbios_wifi_new=strip_tags($_POST['netbios_wifi_new']);
			$db_ip_wifi_new=strip_tags($_POST['ip_wifi_new']);
			$db_mac_wifi_new=strip_tags($_POST['mac_wifi_new']);
			
			//check fields for new asset WIFI IP
			$qry = $db->prepare("
			SELECT tassets_iface.* 
			FROM tassets_iface
			INNER JOIN tassets ON tassets.id=tassets_iface.asset_id
			INNER JOIN tassets_state ON tassets_state.id=tassets.state
			WHERE 
			tassets_state.block_ip_search=:block_ip_search AND
			tassets_iface.ip=:ip AND
			tassets_iface.ip!=:ip2 AND
			tassets_iface.disable=:iface_disable AND
			tassets.disable=:asset_disable
			");
			$qry->execute(array(
				'block_ip_search' => 1,
				'ip' => $db_ip_wifi_new,
				'ip2' => '',
				'iface_disable' => 0,
				'asset_disable' => 0
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if ($row[0]!='') {$error=T_('Un autre équipement possède déjà  l\'adresse IP').': '.$db_ip_wifi_new.'. (<a target="_blank" href="./index.php?page=asset&id='.$row['asset_id'].'" >'.T_('Voir sa fiche').'</a>)';} 
			
			//check fields for new asset WIFI MAC
			$qry = $db->prepare("SELECT * FROM tassets_iface WHERE mac=:mac mac!=:mac2 AND disable=:disable");
			$qry->execute(array(
				'mac' => $db_mac_wifi_new,
				'mac2' => '',
				'disable' => 0
				));
			$row=$qry->fetch();
			$qry->closeCursor();
			if ($row[0]!='') {$error=T_('Un autre équipement possède déjà  l\'adresse MAC').': '.$db_mac_wifi_new.'. (<a target="_blank" href="./index.php?page=asset&id='.$row['asset_id'].'" >'.T_('Voir sa fiche').'</a>)';} 
			
			//control number of digit of WIFI MAC address
			if ((strlen($_POST['mac_wifi_new'])!=12) && $_POST['mac_wifi_new']!='') {$error=T_('Les adresses MAC doivent contenir 12 caractères').' ('.strlen($_POST['mac_wifi_new']).' '.T_('caractères détectés').').';} 
		
			//control number of digit of WIFI IP address
			if ((strlen($_POST['ip_wifi_new'])<7) && $_POST['ip_wifi_new']!='') {$error=T_('Les adresses IP doivent contenir au moins 7 caractères').' ('.strlen($_POST['ip_wifi_new']).' '.T_('caractères détectés').').';} 
		}
	}
	
	//SQL insert for new asset
	if(($_POST['netbios_lan_new'] || $_POST['ip_lan_new'] || $_POST['mac_lan_new']) && $error=='0')
	{
		if ($rparameters['debug']==1) {echo "NEW LAN IFACE<br />";}
		$qry=$db->prepare("INSERT INTO tassets_iface (role_id,asset_id,netbios,ip,mac,disable) VALUES (:role_id,:asset_id,:netbios,:ip,:mac,:disable)");
		$qry->execute(array(
			'role_id' => 1,
			'asset_id' => $asset_id,
			'netbios' => $db_netbios_lan_new,
			'ip' => $db_ip_lan_new,
			'mac' => $db_mac_lan_new,
			'disable' => 0
			));
	}
	if(($_POST['netbios_wifi_new'] || $_POST['ip_wifi_new'] || $_POST['mac_wifi_new']) && $error=='0')
	{
		if ($rparameters['debug']==1) {echo "NEW WIFI IFACE<br />";}
		$qry=$db->prepare("INSERT INTO tassets_iface (role_id,asset_id,netbios,ip,mac,disable) VALUES (:role_id,:asset_id,:netbios,:ip,:mac,:disable)");
		$qry->execute(array(
			'role_id' => 12,
			'asset_id' => $asset_id,
			'netbios' => $db_netbios_wifi_new,
			'ip' => $db_ip_wifi_new,
			'mac' => $db_mac_wifi_new,
			'disable' => 0
			));
	}
	
	//SQL insert and update in tassets table
	if (($_GET['action']=='new') && ($error=="0"))
	{	
		//insert asset
		$qry=$db->prepare("
		INSERT INTO tassets (
		sn_internal,
		sn_manufacturer,
		sn_indent,
		netbios,
		description,
		type,
		manufacturer,
		model,
		virtualization,
		user,
		state,
		department,
		date_install,
		date_end_warranty,
		date_stock,
		date_standbye,
		date_recycle,
		location,
		socket,
		technician,
		maintenance,
		disable
		) VALUES (
		:sn_internal,
		:sn_manufacturer,
		:sn_indent,
		:netbios,
		:description,
		:type,
		:manufacturer,
		:model,
		:virtualization,
		:user,
		:state,
		:department,
		:date_install,
		:date_end_warranty,
		:date_stock,
		:date_standbye,
		:date_recycle,
		:location,
		:socket,
		:technician,
		:maintenance,
		:disable
		)
		");
		$qry->execute(array(
			'sn_internal' => $_POST['sn_internal'],
			'sn_manufacturer' => $_POST['sn_manufacturer'],
			'sn_indent' => $_POST['sn_indent'],
			'netbios' => $_POST['netbios'],
			'description' => $_POST['description'],
			'type' => $_POST['type'],
			'manufacturer' => $_POST['manufacturer'],
			'model' => $_POST['model'],
			'virtualization' => $_POST['virtualization'],
			'user' => $_POST['user'],
			'state' => $_POST['state'],
			'department' => $_POST['department'],
			'date_install' => $_POST['date_install'],
			'date_end_warranty' => $_POST['date_end_warranty'],
			'date_stock' => $_POST['date_stock'],
			'date_standbye' => $_POST['date_standbye'],
			'date_recycle' => $_POST['date_recycle'],
			'location' => $_POST['location'],
			'socket' => $_POST['socket'],
			'technician' => $_POST['technician'],
			'maintenance' => $_POST['maintenance'],
			'disable' => 0
			));
	} elseif ($error=="0")  {
		//update asset
		$qry=$db->prepare("
		UPDATE tassets SET 
		sn_internal=:sn_internal,
		sn_manufacturer=:sn_manufacturer,
		sn_indent=:sn_indent,
		netbios=:netbios,
		description=:description,
		type=:type,
		manufacturer=:manufacturer,
		model=:model,
		virtualization=:virtualization,
		user=:user,
		state=:state,
		department=:department,
		date_install=:date_install,
		date_end_warranty=:date_end_warranty,
		date_stock=:date_stock,
		date_standbye=:date_standbye,
		date_recycle=:date_recycle,
		location=:location,
		socket=:socket,
		technician=:technician,
		maintenance=:maintenance,
		disable=:disable
		WHERE
		id=:id
		");
		$qry->execute(array(
			'sn_internal' => $_POST['sn_internal'],
			'sn_manufacturer' => $_POST['sn_manufacturer'],
			'sn_indent' => $_POST['sn_indent'],
			'netbios' => $_POST['netbios'],
			'description' => $_POST['description'],
			'type' => $_POST['type'],
			'manufacturer' => $_POST['manufacturer'],
			'model' => $_POST['model'],
			'virtualization' => $_POST['virtualization'],
			'user' => $_POST['user'],
			'state' => $_POST['state'],
			'department' => $_POST['department'],
			'date_install' => $_POST['date_install'],
			'date_end_warranty' => $_POST['date_end_warranty'],
			'date_stock' => $_POST['date_stock'],
			'date_standbye' => $_POST['date_standbye'],
			'date_recycle' => $_POST['date_recycle'],
			'location' => $_POST['location'],
			'socket' => $_POST['socket'],
			'technician' => $_POST['technician'],
			'maintenance' => $_POST['maintenance'],
			'disable' => 0,
			'id' =>$_GET['id']
			));
		if ($rparameters['debug']==1) {echo "UPDATE ASSET<br />";}
	}
	
	//display message
	if($error=="0")
	{
	    echo '<div class="alert alert-block alert-success"><center><i class="icon-ok green"></i>	'.T_('Équipement sauvegardé').'. </center></div>';
	} else {
	    //new page asset redirect
        echo '<div class="alert alert-danger"><i class="icon-remove"></i> <strong>'.T_('Erreur').':</strong> '.$error.' </div>';
	}
	
	//switch state for redirect new asset 
	if ($_GET['state']=='') {$_GET['state']='2';}
	
	//redirect to asset list with save & quit button
	if ($_POST['quit'] && ($error=="0"))
	{
		//redirect
		$www = "./index.php?page=asset_list&$url_get_parameters";
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		-->
		</script>';
	}
	
    if($error=="0")
    {
	//global redirect on asset edit page
    echo "<SCRIPT LANGUAGE='JavaScript'>
			<!--
			function redirect()
			{
		    	window.location='./index.php?page=asset&action=$_POST[action]&id=$_GET[id]&$url_get_parameters'
			}
			setTimeout('redirect()',$rparameters[time_display_msg]);
			-->
		</SCRIPT>";
    }		
}
//redirect to asset list with cancel button
if($_POST['cancel']) 
{
echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i>	'.T_('Annulation pas de modification').'.</center></div>';
echo "<SCRIPT LANGUAGE='JavaScript'>
			<!--
			function redirect()
			{
		    	window.location='./index.php?page=asset_list&$url_get_parameters'
			}
			setTimeout('redirect()',$rparameters[time_display_msg]);
			-->
			</SCRIPT>";
}
?>