<?php
################################################################################
# @Name : asset.php 
# @Description : page to display create and edit asset
# @Call : /dashboard.php
# @Parameters : 
# @Author : Flox
# @Create : 27/11/2015
# @Update : 28/11/2017
# @Version : 3.1.28
################################################################################

//initialize variables 
if(!isset($_POST['sn_internal'])) $_POST['sn_internal']= ''; 
if(!isset($_POST['sn_manufacturer'])) $_POST['sn_manufacturer']= ''; 
if(!isset($_POST['type'])) $_POST['type']= ''; 
if(!isset($_POST['manufacturer'])) $_POST['manufacturer']= ''; 
if(!isset($_POST['model'])) $_POST['model']= '';
if(!isset($_POST['department'])) $_POST['department']= ''; 
if(!isset($_POST['virtualization'])) $_POST['virtualization']= ''; 
if(!isset($_POST['user'])) $_POST['user']= ''; 
if(!isset($_POST['sn_indent'])) $_POST['sn_indent']= ''; 
if(!isset($_POST['state'])) $_POST['state']= ''; 
if(!isset($_POST['netbios'])) $_POST['netbios']= ''; 
if(!isset($_POST['description'])) $_POST['description']= ''; 
if(!isset($_POST['location'])) $_POST['location']= ''; 
if(!isset($_POST['socket'])) $_POST['socket']= ''; 
if(!isset($_POST['technician'])) $_POST['technician']= ''; 
if(!isset($_POST['maintenance'])) $_POST['maintenance']= '';  
if(!isset($_POST['date_stock'])) $_POST['date_stock']= '';  
if(!isset($_POST['date_install'])) $_POST['date_install']= '';  
if(!isset($_POST['date_stock'])) $_POST['date_stock']= '';  
if(!isset($_POST['date_standbye'])) $_POST['date_standbye']= '';  
if(!isset($_POST['date_recycle'])) $_POST['date_recycle']= '';  
if(!isset($_POST['date_end_warranty'])) $_POST['date_end_warranty']= ''; 
if(!isset($_POST['cursor'])) $_POST['cursor']= '';  

if(!isset($_GET['findip'])) $_GET['findip']= '';  
if(!isset($_GET['findip2'])) $_GET['findip2']= '';  
$db_id=strip_tags($db->quote($_GET['id']));

//core asset actions
include('./core/asset.php');

//default values for new asset
if(!isset($globalrow['sn_internal'])) $globalrow['sn_internal']= ''; 
if(!isset($globalrow['sn_manufacturer'])) $globalrow['sn_manufacturer']= ''; 
if(!isset($globalrow['type'])) $globalrow['type']= ''; 
if(!isset($globalrow['manufacturer'])) $globalrow['manufacturer']= ''; 
if(!isset($globalrow['model'])) $globalrow['model']= ''; 
if(!isset($globalrow['department'])) $globalrow['department']= ''; 
if(!isset($globalrow['virtualization'])) $globalrow['virtualization']= ''; 
if(!isset($globalrow['net_scan'])) $globalrow['net_scan']='1'; 
if(!isset($globalrow['user'])) $globalrow['user']= ''; 
if(!isset($globalrow['sn_indent'])) $globalrow['sn_indent']= ''; 
if(!isset($globalrow['state'])) $globalrow['state']= '2'; 
if(!isset($globalrow['netbios'])) $globalrow['netbios']= ''; 
if(!isset($globalrow['description'])) $globalrow['description']= ''; 
if(!isset($globalrow['location'])) $globalrow['location']= ''; 
if(!isset($globalrow['socket'])) $globalrow['socket']= ''; 
if(!isset($globalrow['technician'])) $globalrow['technician']= $_SESSION['user_id']; 
if(!isset($globalrow['maintenance'])) $globalrow['maintenance']= ''; 
if(!isset($globalrow['u_group'])) $globalrow['u_group']= ''; 
if(!isset($globalrow['date_stock'])) $globalrow['date_stock']= '';  
if(!isset($globalrow['date_standbye'])) $globalrow['date_standbye']= '';  
if(!isset($globalrow['date_install'])) $globalrow['date_install']= date('Y-m-d');  
if(!isset($globalrow['date_recycle'])) $globalrow['date_recycle']= '';  
if(!isset($globalrow['date_end_warranty'])) $globalrow['date_end_warranty']= '';  

$ip_asset='0';
$wifi_asset='0';
$ping_ip='';
$wol_mac='';
$debug_error='';

if(!isset($globalrow['cursor'])) $globalrow['cursor']= '';

//avoid problem new asset check iface
if ($_GET['action']=='new') {if(!isset($globalrow['id'])) $globalrow['id']= ''; }

//get iface to check if asset have ip
if($_GET['action']!='new' && $_GET['id'])
{
	$query = $db->query("SELECT ip FROM tassets_iface WHERE asset_id=$db_id AND disable='0'"); 
	$iface = $query->fetch();
	$query->closeCursor();	
} else {$iface='';}

//test if ip asset to display specific input
if (($_GET['id']!='') && ($_GET['action']!='new'))
{
	$query = $db->query("SELECT tassets_model.ip FROM `tassets_model`,`tassets` WHERE tassets.model=tassets_model.id AND tassets.id LIKE $db_id"); 
	$ripmodel = $query->fetch();
	$query->closeCursor();
	if ($ripmodel['ip']=='1') {$ip_asset='1';} else {$ip_asset='0';}
} elseif($_POST['model']!='') {
	$query = $db->query("SELECT tassets_model.ip FROM `tassets_model` WHERE id LIKE '$_POST[model]'"); 
	$ripmodel = $query->fetch();
	$query->closeCursor();
	if ($ripmodel['ip']=='1') {$ip_asset='1';} else {$ip_asset='0';}
}

//test if wifi asset to display specific row
if (($_GET['id']!='') && ($_GET['action']!='new'))
{
	$query = $db->query("SELECT tassets_model.wifi FROM `tassets_model`,`tassets` WHERE tassets.model=tassets_model.id AND tassets.id LIKE $db_id"); 
	$ripmodel = $query->fetch();
	$query->closeCursor();
	if ($ripmodel['wifi']=='1') {$wifi_asset='1';} else {$wifi_asset='0';}
} elseif($_POST['model']!='') {
	$query = $db->query("SELECT tassets_model.wifi FROM `tassets_model` WHERE id LIKE '$_POST[model]'"); 
	$ripmodel = $query->fetch();
	$query->closeCursor();
	if ($ripmodel['wifi']=='1') {$wifi_asset='1';} else {$wifi_asset='0';}
} 
if ($rparameters['debug']==1) {echo "VAR: IP_ASSET=$ip_asset | WIFI_ASSET=$wifi_asset";}

//convert YYYY-mm-dd date to FR format
if ($globalrow['date_stock']=='0000-00-00' || $globalrow['date_stock']=='') {
	$globalrow['date_stock']='';
} else {
	$globalrow['date_stock'] = DateTime::createFromFormat('Y-m-d', $globalrow['date_stock']);
	$globalrow['date_stock']=$globalrow['date_stock']->format('d/m/Y');
}
if ($globalrow['date_install']=='0000-00-00' || $globalrow['date_install']=='') {
	$globalrow['date_install']='';
} else {
	$globalrow['date_install'] = DateTime::createFromFormat('Y-m-d', $globalrow['date_install']);
	$globalrow['date_install']=$globalrow['date_install']->format('d/m/Y');
}
if ($globalrow['date_end_warranty']=='0000-00-00' || $globalrow['date_end_warranty']=='') {
	$globalrow['date_end_warranty']='';
} else {
	$globalrow['date_end_warranty'] = DateTime::createFromFormat('Y-m-d', $globalrow['date_end_warranty']);
	$globalrow['date_end_warranty']=$globalrow['date_end_warranty']->format('d/m/Y');
}
if ($globalrow['date_standbye']=='0000-00-00' || $globalrow['date_standbye']=='') {
	$globalrow['date_standbye']='';
} else {
	$globalrow['date_standbye'] = DateTime::createFromFormat('Y-m-d', $globalrow['date_standbye']);
	$globalrow['date_standbye']=$globalrow['date_standbye']->format('d/m/Y');
}
if ($globalrow['date_recycle']=='0000-00-00' || $globalrow['date_recycle']=='') {
	$globalrow['date_recycle']='';
} else {
	$globalrow['date_recycle'] = DateTime::createFromFormat('Y-m-d', $globalrow['date_recycle']);
	$globalrow['date_recycle']=$globalrow['date_recycle']->format('d/m/Y');
}
?>
<div id="row">
	<div class="col-xs-12">
		<div class="widget-box">
			<form class="form-horizontal" name="myform" id="myform" enctype="multipart/form-data" method="post" action="" onsubmit="loadVal();" >
				<div class="widget-header">
					<h4>
						<i class="icon-desktop"></i>
						<?php
    						//display widget title
    						if($_GET['action']=='new') {
								echo T_('Ajout d\'un équipement'); 
							} else { 
								//get internal id of this asset
								$query = $db->query("SELECT * FROM `tassets` WHERE id LIKE $db_id"); 
								$rsn = $query->fetch();
								$query->closeCursor();
								
								if ($mobile==0)
								{
									echo T_('Édition de l\'équipement').' n°'.$rsn['sn_internal'].' : '.$rsn['netbios'].'</i>';
								} else {
									echo 'n°'.$rsn['sn_internal'].': '.$rsn['netbios'].'</i>';
								}
							}
						?>
					</h4>
					<span class="widget-toolbar">
						<?php 
							//display specific buttons for IP asset
							if(($ip_asset==1) || ($wifi_asset==1) || $iface)
							{
								if($rparameters['asset_vnc_link']==1){
									echo '<a target="_blank" href="http://'.$iface['ip'].':5800"><img title="'.T_('Ouvre un nouvel onglet sur le prise de contrôle distant web VNC').'" src="./images/remote.png" /></a>&nbsp;&nbsp;';
								}
								if($rright['asset_net_scan']!=0){
									if ($globalrow['net_scan']==1)
									{
										echo '<a href="./index.php?page=asset&id='.$_GET['id'].'&scan=0&'.$url_get_parameters.'"><img title="'.T_('Scan IP activé sur cet équipement, cliquer pour désactiver').'" src="./images/scan_on.png" /></a>&nbsp;&nbsp;';
									} else {
										echo '<a href="./index.php?page=asset&id='.$_GET['id'].'&scan=1&'.$url_get_parameters.'"><img title="'.T_('Scan IP désactivé sur cet équipement, cliquer pour activer').'" src="./images/scan_off.png" /></a>&nbsp;&nbsp;';
									}
								}
								echo '<a href="./index.php?page=asset&id='.$_GET['id'].'&action=addiface&'.$url_get_parameters.'"><img title="'.T_('Ajouter une interface IP').'" src="./images/plug.png" /></a>&nbsp;&nbsp;';
								//select IP display ping button
								$query = $db->query("SELECT * FROM tassets_iface WHERE asset_id='$globalrow[id]' AND disable='0'");
								while ($row = $query->fetch()) 
								{
									if($row['role_id']==1 && $row['ip']!='') {$ping_ip=$row['ip'];}
									if($ping_ip=='') {$ping_ip=$row['ip'];}
									if($row['role_id']==1 && $row['mac']!='') {$wol_mac=$row['mac'];}
									if($wol_mac=='') {$wol_mac=$row['mac'];}
								}
								$query->closeCursor();
								if ($ping_ip) {echo '<a href="./index.php?page=asset&id='.$globalrow['id'].'&action=ping&iptoping='.$ping_ip.'&'.$url_get_parameters.'"><i title="'.T_("Ping de cet équipement sur l'adresse IP:").' '.$ping_ip.' " class="icon-exchange info bigger-130"></i></a>&nbsp;&nbsp;';}
								if ($wol_mac) {echo '<a href="./index.php?page=asset&id='.$globalrow['id'].'&action=wol&mac='.$wol_mac.'&'.$url_get_parameters.'"><i title="'.T_("Allumer cet équipement avec l'adresse MAC:").' '.$wol_mac.' " class="icon-power-off orange bigger-130"></i></a>&nbsp;&nbsp;';}
							}
							if ($rright['asset_delete']!=0) {
								echo '<a href="./index.php?page=asset&id='.$_GET['id'].'&action=delete&'.$url_get_parameters.'"><i title="'.T_('Supprimer cet équipement').'" class="icon-trash red bigger-130"></i></a>&nbsp;&nbsp;';
								echo '<button class="btn btn-minier btn-success" title="'.T_('Sauvegarder').'" name="modify" value="modify" type="submit" id="modify"><i class="icon-save bigger-140"></i></button>&nbsp;&nbsp;';
								echo '<button class="btn btn-minier btn-purple" title="'.T_('Sauvegarder et quitter').'" name="quit" value="quit" type="submit" id="quit"><i class="icon-save bigger-140"></i></button>';
							}
						?>
					</span>
				</div>
				<div class="widget-body">
					<div class="widget-main">
						<div class="row">
							<div class="col-sm-8">
								<!-- START sn_internal part -->
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right" for="sn_internal"><?php echo T_('Numéro'); ?>:</label>
									<div class="col-sm-6">
										<input  name="sn_internal" id="sn_internal" type="text" size="25"  value="<?php if ($_POST['sn_internal']) echo $_POST['sn_internal']; else echo $globalrow['sn_internal']; ?>"  />
									</div>
								</div>
								<!-- END sn_internal part -->
								
								<!-- START type model part -->
								<div class="form-group ">
									<label class="col-sm-4 control-label no-padding-right" for="type">
										<?php if(($globalrow['type']==0) && ($_POST['type']==0)) echo '<i title="Aucun type sélectionné." class="icon-warning-sign red bigger-130"></i>&nbsp;'; ?>
										<?php echo T_('Type'); ?>:
									</label>
									<div class="col-sm-8">
										<select id="type" name="type" onchange="submit();" >
											<?php
												$query= $db->query("SELECT * FROM `tassets_type` ORDER BY name ");
												while ($row = $query->fetch()) 
												{
													if ($_POST['type'])
													{
														if ($_POST['type']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
													}
													else
													{
														if ($globalrow['type']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
													}
												}
												$query->closeCursor();
												if ($globalrow['type']==0 && $_POST['type']==0) echo "<option value=\"\" selected></option>";
											?>
										</select>
										<select id="manufacturer" name="manufacturer" onchange="submit();" >
											<?php
												if ($_POST['type'])
												{$query= $db->query("SELECT DISTINCT tassets_manufacturer.id, tassets_manufacturer.name FROM `tassets_manufacturer`,tassets_model WHERE tassets_manufacturer.id=tassets_model.manufacturer AND tassets_model.type='$_POST[type]' ORDER BY name ASC");}
												elseif ($globalrow['type']!='')
												{$query= $db->query("SELECT DISTINCT tassets_manufacturer.id, tassets_manufacturer.name FROM `tassets_manufacturer`,tassets_model WHERE tassets_manufacturer.id=tassets_model.manufacturer AND tassets_model.type='$globalrow[type]' ORDER BY name ASC");}
												else
												{$query= $db->query("SELECT * FROM `tassets_manufacturer` ORDER BY name ASC");}
												while ($row = $query->fetch()) 
												{
													if ($_POST['type'])
													{
														if ($_POST['manufacturer']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
													}
													else
													{
														if ($globalrow['manufacturer']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
													}
												} 
												$query->closeCursor();
												if ($globalrow['manufacturer']==0 && $_POST['manufacturer']==0) echo "<option value=\"\" selected></option>";
											?>
										</select>
										<select  id="model" name="model" onchange="submit();" >
										<?php
											if ($_POST['manufacturer'])
											{$query= $db->query("SELECT * FROM `tassets_model` WHERE manufacturer LIKE '$_POST[manufacturer]' AND type='$_POST[type]' ORDER BY name ASC");}
											elseif ($globalrow['manufacturer']!='')
											{$query= $db->query("SELECT * FROM `tassets_model` WHERE manufacturer LIKE '$globalrow[manufacturer]' AND type='$globalrow[type]' ORDER BY name ASC");}
											else
											{$query= $db->query("SELECT * FROM `tassets_model` ORDER BY name ASC");}
											
											while ($row = $query->fetch()) 
											{
												if ($_POST['model'])
												{
													if ($_POST['model']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
												else
												{
													if ($globalrow['model']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
											} 
											$query->closeCursor();
											if ($globalrow['model']==0 && $_POST['model']==0) echo "<option value=\"\" selected></option>";
										?>
										</select>
									</div>
								</div>
								<!-- END type model part -->
								
								<!-- START virtualization part -->
								<?php
								if ($rright['asset_virtualization_disp']!=0)
								{
									//check if type is virtual
									$query=$db->query("SELECT virtualization FROM tassets_type WHERE id='$globalrow[type]'");
									$row=$query->fetch();
									$query->closeCursor();
									if ($row['virtualization']==1)
									{
										echo '
										<div class="form-group">
											<label class="col-sm-4 control-label no-padding-right" for="virtualization">'.T_('Équipement virtuel').'</label>
											<div class="col-sm-8">
												<label>
													<input name="virtualization" id="virtualization" type="checkbox" ';if ($globalrow['virtualization']==1) {echo "checked";} echo ' class="ace" value="1">
													<span class="lbl"></span>
												</label>
											</div>
										</div>
										';
									}
								}
								?>
								<!-- END virtualization part -->
								
								<!-- START netbios part -->
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right" for="netbios"><?php echo T_('Nom'); ?>:</label>
									<div class="col-sm-8">
										<input  name="netbios" id="netbios" type="text" size="25"  value="<?php if ($_POST['netbios']) echo $_POST['netbios']; else echo $globalrow['netbios']; ?>"  />
									</div>
								</div>
								<!-- END netbios part -->
								
								<!-- START user part -->	
								<div class="form-group" >
									<label class="col-sm-4 control-label no-padding-right" for="user">
										<?php echo T_('Utilisateur'); ?>:
									</label>
									<div class="col-sm-4">
										<select <?php if($mobile==0) {echo 'class="chosen-select"';}?> id="user" name="user" style="width:195px" onchange="loadVal(); submit();">
											<?php
											//limit select list to users who have the same company than current connected user
											if($rright['asset_list_company_only']!=0)
											{
												$query="SELECT * FROM `tusers` WHERE disable='0' AND company='$ruser[company]' ORDER BY lastname ASC, firstname ASC";
											} else {
												$query="SELECT * FROM `tusers` WHERE disable='0' ORDER BY lastname ASC, firstname ASC";
											}
											//display user list
											$query = $db->query($query);
											while ($row = $query->fetch()) {echo "<option value=\"$row[id]\">$row[lastname] $row[firstname]</option>";}
											//selection
											if ($_POST['user'])	{$user=$_POST['user'];}	elseif ($globalrow['user']!=''){$user=$globalrow['user'];} else {$user=0;}
											$query=$db->query("SELECT * FROM tusers WHERE id LIKE '$user'");
											$row=$query->fetch();
											$query->closeCursor();
											if ($user==0) {echo '<option selected value="0">'.T_('Aucun').'</option>';} else  {echo "<option selected value=\"$user\">$row[lastname] $row[firstname]</option>";}
											?>
										</select>
									</div>
								</div>
								<!-- END user part -->
								
								<!-- START department part -->	
								<div class="form-group" >
									<label class="col-sm-4 control-label no-padding-right" for="department">
										<?php echo T_('Service'); ?>:
									</label>
									<div class="col-sm-4">
										<select id="department" name="department" style="width:195px" onchange="loadVal(); submit();">
											<?php
											echo "<option value=\"0\">Aucun</option>";
											//display service list
											$query = $db->query("SELECT * FROM `tservices` WHERE disable='0' ORDER BY name ASC");
											while ($row = $query->fetch()) 
											{
												if ($row['id']==0) {$row['name']=T_($row['name']);} // translate none value from db
												if ($globalrow['department']==$row['id']) {
													echo "<option selected value=\"$row[id]\">$row[name]</option>";
												} else {
													echo "<option value=\"$row[id]\">$row[name]</option>";
												}
											}
											$query->closeCursor();
											?>
										</select>
									</div>
								</div>
								<!-- END service part -->
								
								<!-- START iface part -->
								<?php
									//check if asset iface exist and display it
									$query = $db->query("SELECT COUNT(id) FROM `tassets_iface` WHERE asset_id='$globalrow[id]' AND disable='0'");
									$iface_counter=$query->fetch();
									$query->closeCursor();
									if ($iface_counter[0]>0)
									{
										if ($rparameters['debug']==1) {$debug_error=$iface_counter[0].' IFACE DETECTED: Display each iface';}
										//display each iface
										$query = $db->query("SELECT * FROM `tassets_iface` WHERE asset_id='$globalrow[id]' AND disable='0' ORDER BY role_id ASC");
										while ($row = $query->fetch()) 
										{
											//init var
											if(!isset($_POST["netbios_$row[id]"])) $_POST["netbios_$row[id]"] = '';
											if(!isset($_POST["ip_$row[id]"])) $_POST["ip_$row[id]"] = '';
											if(!isset($_POST["mac_$row[id]"])) $_POST["mac_$row[id]"] = '';
											//get name of role of current iface
											$query2 = $db->query("SELECT name FROM `tassets_iface_role` WHERE id=$row[role_id]");
											$row2=$query2->fetch();
											$query2->closeCursor();
											$iface_name=$row2[0];
											//display if bloc
											echo '
											<div class="form-group">
												<label class="col-sm-4 control-label no-padding-right" for="iface">
													';
													//display ping flags
													if($row['date_ping_ok']>$row['date_ping_ko'])
													{
														echo '<i title="'.T_('Dernier ping réussi le').' '.date("d/m/Y H:i:s", strtotime($row['date_ping_ok'])).'" class="icon-flag green"></i>';
													} elseif($row['date_ping_ko']>$row['date_ping_ok']) 
													{
														echo '<i title="'.T_('Dernier ping échoué le').' '.date("d/m/Y H:i:s", strtotime($row['date_ping_ko'])).'" class="icon-flag red"></i>';
													}
													echo '
													'.T_('Interface IP').' '.$iface_name.':
												</label>
												<div class="col-sm-8">
													<input name="netbios_'.$row['id'].'" id="netbios_'.$row['id'].'" type="text" placeholder="Nom NetBIOS" size="12" value="';if($_POST["netbios_$row[id]"]) {echo $_POST["netbios_$row[id]"];} else { echo $row['netbios'];} echo'" />
													<input name="ip_'.$row['id'].'" id="ip_'.$row['id'].'" type="text" size="14" placeholder="Adresse IP" value="';if($_GET['findip'] && $_GET['iface']==$row['id']) {echo $_GET['findip'];} elseif($_POST["ip_$row[id]"]) {echo $_POST["ip_$row[id]"];} else { echo $row['ip'];} echo'" />
													<input title="'.T_('Noter sans séparateurs : ou - ').'" name="mac_'.$row['id'].'" id="mac_'.$row['id'].'" type="text" size="14" placeholder="Adresse MAC" value="';if($_POST["mac_$row[id]"]) {echo $_POST["mac_$row[id]"];} else { echo $row['mac'];} echo'" />
													&nbsp;<i class="icon-search green bigger-130" title="'.T_('Trouver une adresse IP pour cette interface').'" onclick="document.forms[\'myform\'].action.value=\'findip_'.$row['id'].'\';document.forms[\'myform\'].submit();"></i>
													&nbsp;<a href="./index.php?page=asset&id='.$globalrow['id'].'&state='.$_GET['state'].'&action=editiface&iface='.$row['id'].'&'.$url_get_parameters.'"><i class="icon-pencil orange bigger-130" title="'.T_('Modifier le rôle de l\'interface').'" onclick="loadVal(); document.forms[\'myform\'].action.value=\'editcat\';document.forms[\'myform\'].submit();"></i></a>
													&nbsp;<a href="./index.php?page=asset&id='.$globalrow['id'].'&state='.$_GET['state'].'&action=delete_iface&iface='.$row['id'].'&'.$url_get_parameters.'"><i class="icon-trash red bigger-130"  title="'.T_('Supprimer l\'interface').'"></i></a>
												</div>
											</div>
											';
										}
										$query->closeCursor();
									}

									//display default iface fields when asset not have iface and when it's ip asset
									$query = $db->query("SELECT COUNT(id) FROM `tassets_iface` WHERE asset_id='$globalrow[id]' AND disable='0'");
									$iface_lan_counter=$query->fetch();
									$query->closeCursor();
									$query = $db->query("SELECT COUNT(id) FROM `tassets_iface` WHERE asset_id='$globalrow[id]' AND role_id='2' AND disable='0'");
									$iface_wifi_counter=$query->fetch();
									$query->closeCursor();
									
									if ($ip_asset=='1' && $iface_lan_counter[0]==0)
									{
										if ($rparameters['debug']==1) {$debug_error='NO LAN IFACE DETECTED: display default LAN input';}
										echo '
										<div class="form-group">
											<label class="col-sm-4 control-label no-padding-right" for="ip">'.T_('Interface IP LAN').':</label>
											<div class="col-sm-8">
												<input name="netbios_lan_new" id="netbios_lan_new" type="text" placeholder="Nom NetBIOS" size="12" value="'.$_POST['netbios_lan_new'].'" />
												<input name="ip_lan_new" id="ip_lan_new" type="text" size="14" placeholder="Adresse IP" value="'; if($_GET['findip'] && $_GET['iface']=='ip_lan_new') {echo $_GET['findip'];} else {echo $_POST['ip_lan_new'];} echo'" />
												<input title="'.T_('Noter sans les séparateurs : ou - ').'" name="mac_lan_new" id="mac_lan_new" type="text" size="14" placeholder="Adresse MAC" value="'.$_POST['mac_lan_new'].'" />
												&nbsp;<i class="icon-search green bigger-130" title="'.T_('Trouver une adresse IP pour cette interface').'" onclick="document.forms[\'myform\'].action.value=\'findip1\';document.forms[\'myform\'].submit();"></i>
											</div>
										</div>
										';
									}
									if ($wifi_asset=='1' && $iface_wifi_counter[0]==0)
									{
										if ($rparameters['debug']==1) {$debug_error='NO WIFI IFACE DETECTED: display default WIFI input';}
										echo '
										<div class="form-group">
											<label class="col-sm-4 control-label no-padding-right" for="wifi">'.T_('Interface IP WIFI').':</label>
											<div class="col-sm-8">
												<input name="netbios_wifi_new" id="netbios_wifi_new" type="text" placeholder="Nom NetBIOS" size="12" value="'.$_POST['netbios_wifi_new'].'" />
												<input name="ip_wifi_new" id="ip_wifi_new" type="text" size="14" placeholder="Adresse IP" value="';if($_GET['findip'] && $_GET['iface']=='ip_wifi_new') {echo $_GET['findip'];} else {echo $_POST['ip_wifi_new'];} echo'" />
												<input title="'.T_('Noter sans séparateurs : ou - ').'" name="mac_wifi_new" id="mac_wifi_new" type="text" size="14" placeholder="Adresse MAC" value="'.$_POST['mac_wifi_new'].'" />
												&nbsp;<i class="icon-search green bigger-130" title="'.T_('Trouver une adresse IP pour cette interface').'" onclick="document.forms[\'myform\'].action.value=\'findip2\';document.forms[\'myform\'].submit();"></i>
											</div>
										</div>
										';
									}
									//need to use onclick action of findip
									echo'<input type="hidden" name="action" value="">'; 
								?>
								<!-- END iface part -->
								
								<!-- START sn_manufacturer part -->
								<?php
								if ($globalrow['virtualization']==0)
								{
									if($mobile==0) {$sn_manufacturer_size='41';} else {$sn_manufacturer_size='30';}
									echo '
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="sn_manufacturer">'.T_('Numéro de série fabricant').':</label>
										<div class="col-sm-8">
											<input  name="sn_manufacturer" id="sn_manufacturer" type="text" size="'.$sn_manufacturer_size.'"  value="'; if ($_POST['sn_manufacturer']) echo $_POST['sn_manufacturer']; else echo $globalrow['sn_manufacturer']; echo '"  />
										</div>
									</div>
									';
								}
								?>
								<!-- END sn_manufacturer part -->
								
								<!-- START sn_indent part -->
								<?php 
								if ($globalrow['virtualization']==0)
								{
									if($mobile==0) {$sn_indent_size='41';} else {$sn_indent_size='30';}
									echo '
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="sn_indent">'.T_('Numéro de commande').':</label>
										<div class="col-sm-8">
											<input  name="sn_indent" id="sn_indent" type="text" size="'.$sn_indent_size.'"  value="'; if ($_POST['sn_indent']) echo $_POST['sn_indent']; else echo $globalrow['sn_indent']; echo '"  />
										</div>
									</div>
									';
								}
								?>
								<!-- END sn_indent part -->
								
								<!-- START description part -->
								<?php if($mobile==0) {$description_size='40';} else {$description_size='30';} ?>
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right" for="description"><?php echo T_('Description'); ?>:</label>
									<div class="col-sm-8">
										<textarea name="description" id="description"  cols="<?php echo $description_size; ?>" rows="4"><?php if ($_POST['description']) echo $_POST['description']; else echo $globalrow['description']; ?></textarea>
									</div>
								</div>
								<!-- END description part -->
								
								<!-- START location part -->
								<?php
								if ($rright['asset_location_disp']!=0 && $globalrow['virtualization']==0)
								{
									echo '
									<div class="form-group" >
										<label class="col-sm-4 control-label no-padding-right" for="location">
											'.T_('Localisation').'
										</label>
										<div class="col-sm-4">
											<select '; if($mobile==0) {echo 'class="chosen-select"';} echo ' id="location" name="location" style="width:195px" onchange="loadVal(); submit();">
												';
												//display service list
												$query = $db->query("SELECT * FROM `tassets_location` WHERE disable='0' ORDER BY id!=0,name ASC");
												while ($row = $query->fetch()) 
												{
													if ($globalrow['location']==$row['id']) {
														echo "<option selected value=\"$row[id]\">$row[name]</option>";
													} else {
														echo "<option value=\"$row[id]\">$row[name]</option>";
													}
												}
												echo '
											</select>
										</div>
									</div>
									';
								}
								?>
								<!-- END location part -->
								
								<!-- START socket part -->
								<?php
								if ($ip_asset=='1' && $globalrow['virtualization']==0)
									{
										echo '
										<div class="form-group">
											<label class="col-sm-4 control-label no-padding-right" for="socket">'.T_('Numéro de prise').':</label>
											<div class="col-sm-8">
												<input  name="socket" id="socket" type="text" size="25"  value="'; if ($_POST['socket']) echo $_POST['socket']; else echo $globalrow['socket']; echo '"  />
											</div>
										</div>
										';
									}
								?>
								<!-- END socket part -->
								
								<!-- START technician part -->	
								<div class="form-group" >
									<label class="col-sm-4 control-label no-padding-right" for="technician">
										<?php if (($_POST['technician']==0) && ($globalrow['technician']==0)) echo '<i title="'.T_('Sélectionner un technicien').'." class="icon-warning-sign red bigger-130"></i>&nbsp;'; ?>
										<?php echo T_('Installateur'); ?>:
									</label>
									<div class="col-sm-4">
										<select id="technician" name="technician" style="width:195px" onchange="loadVal(); submit();">
											<?php
											//display technician list
											$query = $db->query("SELECT * FROM `tusers` WHERE (profile='0' || profile='4') AND disable='0' ORDER BY lastname ASC, firstname ASC");
											while ($row = $query->fetch()) {echo "<option value=\"$row[id]\">$row[lastname] $row[firstname]</option>";}
											//selection
											if ($_POST['technician'])	{$user=$_POST['technician'];}	elseif ($globalrow['technician']!=''){$user=$globalrow['technician'];} else {$user=0;}
											$query=$db->query("SELECT * FROM tusers WHERE (profile='0' || profile='4') AND id LIKE '$user'");
											$row=$query->fetch();
											$query->closeCursor();
											echo "<option selected value=\"$user\">$row[lastname] $row[firstname]</option>";
											if ($user==0) echo '<option selected value="0">'.T_('Aucun').'</option>';
											?>
										</select>
									</div>
								</div>
								<!-- END technician part -->
								
								<!-- START maintenance part -->	
								<div class="form-group" >
									<label class="col-sm-4 control-label no-padding-right" for="maintenance">
										<?php echo T_('Maintenance'); ?>:
									</label>
									<div class="col-sm-4">
										<select id="maintenance" name="maintenance" style="width:195px" onchange="loadVal(); submit();">
											<?php
											echo '<option selected value="0">'.T_('Aucun').'</option>';
											//display service list
											$query = $db->query("SELECT * FROM `tservices` WHERE disable='0' ORDER BY name ASC");
											while ($row = $query->fetch()) 
											{
												if ($globalrow['maintenance']==$row['id']) {
													echo "<option selected value=\"$row[id]\">$row[name]</option>";
												} else {
													echo "<option value=\"$row[id]\">$row[name]</option>";
												}
											}
											?>
										</select>
									</div>
								</div>
								<!-- END maintenance part -->
								
								<!-- START stock date part -->
								<?php
								if ($globalrow['virtualization']==0)
								{
									echo '
									<div class="form-group ">
										<label class="col-sm-4 control-label no-padding-right" for="date_stock">'.T_("Date d'achat").':</label>
										<div class="col-sm-8">
											<input type="text" size="25" name="date_stock" id="date_stock" value="'; if ($_POST['date_stock']) echo $_POST['date_stock']; else echo $globalrow['date_stock']; echo '" >
										</div> 
									</div>
									';
								}
								?>
								<!-- END stock date part -->
								
								<!-- START install date part -->
								<?php
									echo '
										<div class="form-group ">
											<label class="col-sm-4 control-label no-padding-right" for="date_install">'.T_('Date d\'installation').':</label>
											<div class="col-sm-8">
												<input type="text" size="25" name="date_install" id="date_install" value="'; if ($_POST['date_install']) echo $_POST['date_install']; else echo $globalrow['date_install']; echo'" >
											</div> 
										</div>
									';
								?>
								<!-- END install date part -->
								
								<!-- START end warranty date part -->
								<?php
									if ($globalrow['virtualization']==0)
									{
										if ($_POST['date_end_warranty']) {$globalrow['date_end_warranty']=$_POST['date_end_warranty'];}

										//define color of warranty
										if ($globalrow['date_end_warranty'])
										{
											//convert date format to calculate if incorrect format is detected
											if (strpos($globalrow['date_end_warranty'], '-') !== false) {$date_end_warranty_conv=$globalrow['date_end_warranty'];} 
											else {
												$date_end_warranty_conv=DateTime::createFromFormat('d/m/Y', $globalrow['date_end_warranty']);
												$date_end_warranty_conv=$date_end_warranty_conv->format('Y-m-d');
											}
											
											if ($globalrow['date_stock']=='0000-00-00')
											{
												$warranty_icon='<i title="'.T_('La date d\'achat n\'est pas renseignée').'" class="icon-certificate orange bigger-130"></i>';
											}elseif ($date_end_warranty_conv >  date('Y-m-d')){
												$warranty_icon='<i title="'.T_('Équipement sous garantie').'" class="icon-certificate green bigger-130"></i>';
											}elseif ($globalrow['state']==4)  {
												$warranty_icon='';
											}else{
												$warranty_icon='<i title="'.T_('Équipement hors garantie').'" class="icon-certificate red bigger-130"></i>';
											}
										} else {
											$warranty_icon='';
										}
										echo '
											<div class="form-group ">
												<label class="col-sm-4 control-label no-padding-right" for="date_end_warranty">'.$warranty_icon.' '.T_('Date de fin de garantie').':</label>
												<div class="col-sm-8">
													<input type="text" size="25" name="date_end_warranty" id="date_end_warranty" value="'.$globalrow['date_end_warranty'].'" >
												</div> 
											</div>
										';
									}
								?>
								<!-- END end warranty date part -->
								
								<!-- START Standbye date part -->
								<?php
									if ($_GET['action']!='new' && $globalrow['state']!='2' && $globalrow['state']!='1' )
									{
										echo '
											<div class="form-group ">
												<label class="col-sm-4 control-label no-padding-right" for="date_standbye">'.T_('Date de standbye').'</label>
												<div class="col-sm-8">
													<input type="text" size="25" name="date_standbye" id="date_standbye" value="'; if ($_POST['date_standbye']) echo $_POST['date_standbye']; else echo $globalrow['date_standbye']; echo '" >
												</div> 
											</div>
										';
									}
								?>
								<!-- END Standbye date part -->
								
								<!-- START recycle date part -->
								<?php
								if ($globalrow['state']=='4' || $_POST['state']=='4')
								{
									echo '
										<div class="form-group ">
											<label class="col-sm-4 control-label no-padding-right" for="date_recycle">'.T_('Date de recyclage').':</label>
											<div class="col-sm-8">
												<input type="text" size="25" name="date_recycle" id="date_recycle" value="'; if ($_POST['date_recycle']) echo $_POST['date_recycle']; else echo $globalrow['date_recycle']; echo '" >
											</div> 
										</div>
									';
								}
								?>
								<!-- END recycle date part -->
								
								<!-- START state part -->	
								<div class="form-group" >
									<label class="col-sm-4 control-label no-padding-right" for="state">
										<?php echo T_('État'); ?>:
									</label>
									<div class="col-sm-4">
										<select id="state" name="state" style="width:195px" onchange="loadVal(); submit();">
											<?php
											//display states list
											$query = $db->query("SELECT * FROM `tassets_state` WHERE disable='0' ORDER BY `order` ASC");
											while ($row = $query->fetch()) 
											{
												if ($globalrow['state']==$row['id']) {
													echo '<option selected value="'.$row['id'].'">'.T_($row['name']).'</option>';
												} else {
													echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';
												}
											}
											?>
										</select>
									</div>
								</div>
								<!-- END state part -->
								
							</div>
							<!-- SECOND COLUMN PART -->
							<div class="col-sm-4" >
								<br /><br /><br /><br /><br />
								<?php 
									//display model image if exist, display in priority model if image exist for more precision
									if($_POST['model']!='') 
									{
										$query=$db->query("SELECT image FROM tassets_model WHERE id LIKE '$_POST[model]'");
										$row=$query->fetch();
										$query->closeCursor();
										$model=$_POST['model'];
									} elseif($_POST['type']!='') 
									{
										$query=$db->query("SELECT image FROM tassets_model WHERE type LIKE '$_POST[type]'");
										$row=$query->fetch();
										$query->closeCursor();
										$model=$_POST['model'];
									} else {
										$query=$db->query("SELECT image FROM tassets_model WHERE id LIKE '$globalrow[model]'");
										$row=$query->fetch();
										$query->closeCursor();
										$model=$globalrow['model'];
									}
									if ($ip_asset==1) {
										//find ip lan to create link
										$query2=$db->query("SELECT ip FROM tassets_iface WHERE asset_id='$globalrow[id]' AND role_id='1' AND disable='0'");
										$row2=$query2->fetch();
										$query2->closeCursor();
										if ($row2[0] && $row['image']!='') {echo '<a href="http://'.$row2['ip'].'" target="_blank" title="'.T_('Accédez à l\'interface web de cet équipement:').' http://'.$row2['ip'].'" >';}
									}
									//display and re-size asset too large image
									if ($row['image']!='') 
									{
										//check if file exist before display it
										if(file_exists("./images/model/$row[image]"))
										{
											$img_size = getimagesize("./images/model/$row[image]");
											$img_width=$img_size[0];
											if ($img_width>250) {$img_width='width="250"';} else {$img_width='';}
											echo '<img border="1" alt="image du modèle" '.$img_width.' src="./images/model/'.$row['image'].'" />';
										} 
									}
									if ($ip_asset==1) {if ($row2[0] && $row['image']!='') {echo '</a>'; }}	
								?>
							</div>
						</div> <!-- div row -->
						<div class="row" align="center">
							<div class="clearfix form-actions" >	

								<button title="ALT+SHIFT+s" accesskey="s" name="modify" id="modify" value="modify" type="submit" class="btn btn-sm btn-success">
									<i class="icon-save icon-on-right bigger-110"></i> 
									&nbsp;<?php echo T_('Enregistrer'); ?>
								</button>
								&nbsp;
								<?php if($mobile==1) {echo '<br /><br />';} ?>
								<button title="ALT+SHIFT+c" accesskey="c" name="quit" id="quit" value="quit" type="submit" class="btn btn-sm btn-purple">
									<i class="icon-save icon-on-right bigger-110"></i> 
									&nbsp;<?php echo T_('Enregistrer et Fermer'); ?>
								</button>
								&nbsp;
								<?php if($mobile==1) {echo '<br /><br />';} ?>
								<button title="ALT+SHIFT+x" accesskey="x" name="cancel" id="cancel" value="cancel" type="submit" class="btn btn-sm btn-danger">
									<i class="icon-remove icon-on-right bigger-110"></i> 
									&nbsp;<?php echo T_('Annuler'); ?>
								</button>
							</div> <!-- div form-actions -->
						</div> <!-- div row -->
					</div> <!-- div widget main -->
				</div> <!-- div widget body -->
			</form>
		</div> <!-- div end sm -->
	</div> <!-- div end x12 -->
</div> <!-- div end row -->
<?php if ($rparameters['debug']==1 && $debug_error) {echo "<u><b>DEBUG MODE:</b></u><br /> $debug_error";} ?>

<?php include ('./wysiwyg.php'); ?>

<!-- date picker script -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>
	<script src="template/assets/js/date-time/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
jQuery(function($) {
    <?php
		
		echo '
			$.datepicker.setDefaults( $.datepicker.regional["fr"] );
			jQuery(function($){
			   $.datepicker.regional["fr"] = {
				  closeText: "Fermer",
				  prevText: "'.T_('<Préc').'",
				  nextText: "'.T_('Suiv>').'",
				  currentText: "Courant",
				  monthNames: ["'.T_('Janvier').'","'.T_('Février').'","'.T_('Mars').'","'.T_('Avril').'","'.T_('Mai').'","'.T_('Juin').'","'.T_('Juillet').'","'.T_('Août').'","'.T_('Septembre').'","'.T_('Octobre').'","'.T_('Novembre').'","'.T_('Décembre').'"],
				  monthNamesShort: ["Jan","Fév","Mar","Avr","Mai","Jun",
				  "Jul","Aoû","Sep","Oct","Nov","Déc"],
				  dayNames: ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"],
				  dayNamesShort: ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],
				  dayNamesMin: ["'.T_('Di').'","'.T_('Lu').'","'.T_('Ma').'","'.T_('Me').'","'.T_('Je').'","'.T_('Ve').'","'.T_('Sa').'"],
				  weekHeader: "Sm",
				  dateFormat: "dd/mm/yy",
				  timeFormat:  "hh:mm:ss",
				  firstDay: 1,
				  isRTL: false,
				  showMonthAfterYear: false,
				  yearSuffix: ""};
			   $.datepicker.setDefaults($.datepicker.regional["fr"]);
				});
		';
	?>
		$( "#date_install" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
		$( "#date_end_warranty" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
		$( "#date_stock" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
		$( "#date_recycle" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
		$( "#date_standbye" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
	});		
</script>		