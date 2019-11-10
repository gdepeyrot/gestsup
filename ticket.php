<?php
################################################################################
# @Name : ticket.php
# @Description : page to display create and edit ticket
# @call : dashboard
# @parameters : 
# @Author : Flox
# @Create : 07/01/2007
# @Update : 19/02/2018
# @Version : 3.1.30 p4
################################################################################

//initialize variables 
if(!isset($userreg)) $userreg = ''; 
if(!isset($category)) $category = ''; 
if(!isset($subcat)) $subcat = ''; 
if(!isset($title)) $title = ''; 
if(!isset($date_hope)) $date_hope = ''; 
if(!isset($date_create)) $date_create = ''; 
if(!isset($state)) $state = ''; 
if(!isset($description)) $description = ''; 
if(!isset($resolution)) $resolution = ''; 
if(!isset($priority)) $priority = '';
if(!isset($percentage)) $percentage = '';
if(!isset($id)) $id = '';
if(!isset($id_in)) $id_in = '';
if(!isset($save)) $save = '';
if(!isset($techread)) $techread = '';
if(!isset($techread_date)) $techread_date = '';
if(!isset($next)) $next = '';
if(!isset($previous)) $previous = '';
if(!isset($user)) $user = '';
if(!isset($down)) $down = '';
if(!isset($u_group)) $u_group = '';
if(!isset($t_group)) $t_group = '';
if(!isset($userid)) $userid = '';
if(!isset($u_service)) $u_service = '';
if(!isset($date_hope_error)) $date_hope_error = '';
if(!isset($selected_time)) $selected_time = '';

if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['upload'])) $_POST['upload'] = '';
if(!isset($_POST['title'])) $_POST['title'] = '';
if(!isset($_POST['description'])) $_POST['description'] = '';
if(!isset($_POST['resolution'])) $_POST['resolution'] = '';
if(!isset($_POST['Submit'])) $_POST['Submit'] = '';
if(!isset($_POST['subcat'])) $_POST['subcat'] = '';
if(!isset($_POST['user'])) $_POST['user'] = '';
if(!isset($_POST['type'])) $_POST['type'] = '';
if(!isset($_POST['modify'])) $_POST['modify'] = '';
if(!isset($_POST['quit'])) $_POST['quit'] = '';
if(!isset($_POST['date_create'])) $_POST['date_create'] = '';
if(!isset($_POST['date_hope'])) $_POST['date_hope'] = '';
if(!isset($_POST['date_res'])) $_POST['date_res'] = '';
if(!isset($_POST['priority'])) $_POST['priority'] = '';
if(!isset($_POST['criticality'])) $_POST['criticality'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '';
if(!isset($_POST['time'])) $_POST['time'] = '';
if(!isset($_POST['time_hope'])) $_POST['time_hope'] = '';
if(!isset($_POST['state'])) $_POST['state'] = '';
if(!isset($_POST['cancel'])) $_POST['cancel'] = '';
if(!isset($_POST['technician'])) $_POST['technician'] = '';
if(!isset($_POST['ticket_places'])) $_POST['ticket_places'] = '';
if(!isset($_POST['text2'])) $_POST['text2'] = '';
if(!isset($_POST['start_availability_d'])) $_POST['start_availability_d'] = '';
if(!isset($_POST['end_availability_d'])) $_POST['end_availability_d'] = '';
if(!isset($_POST['private'])) $_POST['private'] = '';
if(!isset($_POST['u_service'])) $_POST['u_service'] = '';
if(!isset($_POST['asset_id'])) $_POST['asset_id'] = '';
if(!isset($_POST['asset'])) $_POST['asset'] = '';
if(!isset($_POST['u_agency]'])) $_POST['u_agency]'] = '';
if(!isset($_POST['sender_service'])) $_POST['sender_service'] = '';

if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['threadedit'])) $_GET['threadedit'] = '';
if(!isset($_GET['lock_thread'])) $_GET['lock_thread'] = '';
if(!isset($_GET['unlock_thread'])) $_GET['unlock_thread'] = '';
$db_id=strip_tags($db->quote($_GET['id']));
$db_lock_thread=strip_tags($db->quote($_GET['lock_thread']));
$db_unlock_thread=strip_tags($db->quote($_GET['unlock_thread']));
$db_threadedit=strip_tags($db->quote($_GET['threadedit']));

if(!isset($globalrow['technician'])) $globalrow['technician'] = '';
if(!isset($globalrow['time'])) $globalrow['time'] = '';

//core ticket actions
include('./core/ticket.php');

//defaults values for new tickets
if(!isset($globalrow['creator'])) $globalrow['creator'] = '';
if(!isset($globalrow['t_group'])) $globalrow['t_group'] = '';
if(!isset($globalrow['u_group'])) $globalrow['u_group'] = '';
if(!isset($globalrow['category'])) $globalrow['category'] = '';
if(!isset($globalrow['subcat'])) $globalrow['subcat'] = '';
if(!isset($globalrow['title'])) $globalrow['title'] = '';
if(!isset($globalrow['description'])) $globalrow['description'] = '';
if(!isset($globalrow['date_create'])) $globalrow['date_create'] = date("Y-m-d").' '.date("H:i:s");
if(!isset($globalrow['date_hope'])) $globalrow['date_hope'] = '';
if(!isset($globalrow['date_res'])) $globalrow['date_res'] = '';
if(!isset($globalrow['time_hope'])) $globalrow['time_hope'] = '5';
if(!isset($globalrow['time'])) $globalrow['time'] = '';
if(!isset($globalrow['priority'])) $globalrow['priority'] = ''; 
if(!isset($globalrow['state'])) $globalrow['state'] = '1';
if($rparameters['user_limit_service']==1 && $rright['dashboard_service_only']!=0)
{
	if(!isset($globalrow['type'])) $globalrow['type'] = '0';
} else {
	if(!isset($globalrow['type'])) $globalrow['type'] = '1';
}
if(!isset($globalrow['start_availability'])) $globalrow['start_availability'] = date("Y-m-d").' '.date("H:i:s");
if(!isset($globalrow['end_availability'])) $globalrow['end_availability'] = date("Y-m-d").' '.date("H:i:s");
if(!isset($globalrow['availability_planned'])) $globalrow['availability_planned'] = 0;
if(!isset($globalrow['place'])) $globalrow['place'] = '0';
if(!isset($globalrow['criticality'])) $globalrow['criticality'] = '0';
if(!isset($globalrow['u_service'])) $globalrow['u_service'] = '0';
if(!isset($globalrow['asset_id'])) $globalrow['asset_id'] = '';
if(!isset($globalrow['u_agency'])) $globalrow['u_agency'] = '0';
if(!isset($globalrow['sender_service'])) $globalrow['sender_service'] = '0';

//default values for tech and admin and super
if($_SESSION['profile_id']==4 || $_SESSION['profile_id']==0 || $_SESSION['profile_id']==3)
{
	if(!isset($globalrow['technician'])) $globalrow['technician']=$_SESSION['user_id'];
	if(!isset($globalrow['user'])) $globalrow['user']=0;
} else {
	if(!isset($globalrow['technician'])) $globalrow['technician']='';
	if(!isset($globalrow['user'])) $globalrow['user']=$_SESSION['user_id'];
}

?>
<div id="row">
	<div class="col-xs-12">
		<div class="widget-box">
			<form class="form-horizontal" name="myform" id="myform" enctype="multipart/form-data" method="post" action="" onsubmit="loadVal();" >
				<div class="widget-header">
					<h4>
						<i class="icon-ticket"></i>
						<?php
    						//display widget title
    						if($_GET['action']=='new') {
								if($mobile==1)
								{
									echo 'n° '.$_GET['id'].' ';
								} else {
									echo T_('Ouverture du ticket').' n° '.$_GET['id'].'';
								}
							} else {
								if($mobile==1)
								{
									echo 'n° '.$_GET['id'].' ';
								} else {
									echo T_('Édition du ticket').' '.$_GET['id'].' '.$percentage.':  '.$title.'';
								}
							}
    						//display clock if alarm 
							$query=$db->query('SELECT * FROM tevents WHERE incident='.$db_id.' and disable=0 and type=1');
							$alarm=$query->fetch();
							$query->closeCursor();
    						if($alarm) echo ' <i class="icon-bell-alt green" title="'.T_('Alarme activée le').' '.$alarm['date_start'].'" /></i>';
							//display calendar if planned
							$query=$db->query('SELECT * FROM tevents WHERE incident='.$db_id.' and disable=0 and type=2');
							$plan=$query->fetch();
							$query->closeCursor();
    						if($plan) echo ' <i class="icon-calendar green" title="'.T_('Ticket planifié dans le calendrier le').' '.$plan['date_start'].'" /></i>';
						?>
					</h4>
					<span class="widget-toolbar">
						<?php 
							if($rparameters['asset']==1 && $rparameters['asset_vnc_link']==1 && $_POST['user'] ){
								//check if user have asset with IP
								$query=$db->query("SELECT tassets_iface.ip FROM tassets_iface,tassets WHERE tassets_iface.asset_id=tassets.id AND user='$_POST[user]'");
								$row=$query->fetch();
								$query->closeCursor();
								if ($row) {echo '<a target="_blank" href="http://'.$row['ip'].':5800"><img title="'.T_('Ouvre un nouvel onglet sur le prise de contrôle distant web VNC').'" src="./images/remote.png" /></a>&nbsp;&nbsp;';}
							}
							if ($rright['ticket_next']!=0)
							{
								if($previous[0]!='') echo'<a href="./index.php?page=ticket&amp;id='.$previous[0].'&amp;state='.$state.'&amp;userid='.$userid.'"><i title="'.T_('Ticket précédent de cet état').'" class="icon-circle-arrow-left bigger-130"></i>&nbsp;'; 
								if($next[0]!='') echo'<a href="./index.php?page=ticket&amp;id='.$next[0].'&amp;state='.$state.'&amp;userid='.$userid.' "><i title="'.T_('Ticket suivant de cet état').'" class="icon-circle-arrow-right bigger-130"></i></a>';
							}
							if ($rright['ticket_print']!=0 && $_GET['action']!='new')
							{
								//generate token
								$token=uniqid(); 
								$db->exec("DELETE FROM ttoken WHERE action='ticket_print'");
								$db->exec("INSERT INTO ttoken (token,action) VALUES ('$token','ticket_print')");
								echo "&nbsp;";
								echo '<a target="_blank" href="./ticket_print.php?id='.$_GET['id'].'&user_id='.$_SESSION['user_id'].'&token='.$token.'"><i title="'.T_('Imprimer ce ticket').'" class="icon-print green bigger-130"></i></a>';
							}
							if ($rright['ticket_template']!=0 && $_GET['action']=='new')
							{
								echo "&nbsp;";
								echo '<a href="./index.php?page=ticket&id='.$_GET['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&action=template"><i title="'.T_('Modèle de tickets').'" class="icon-tags pink bigger-130"></i></a>';
							}
							if ($rright['ticket_event']!=0)
							{
								echo "&nbsp;&nbsp;";
								echo'<i onclick="parent.location=\'./index.php?page=ticket&id='.$_GET['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&action=addevent&technician='.$_SESSION['user_id'].'\'" title="'.T_('Créer un rappel pour ce ticket').'" class="icon-bell-alt bigger-130 orange"></i>';
							}
							if (($rright['planning']!=0) && ($rparameters['planning']==1) && ($rright['ticket_calendar']!=0)) 
							{
								echo "&nbsp;&nbsp;";
								echo'<i onclick="parent.location=\'./index.php?page=ticket&id='.$_GET['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&action=addcalendar&technician='.$_SESSION['user_id'].'\'" title="'.T_('Planifier une intervention dans le calendrier').'" class="icon-calendar bigger-130 purple"></i>';
							}
							if ($rright['ticket_delete']!=0 && $_GET['action']!='new')
							{
								echo "&nbsp;&nbsp;";
								echo '<a href="./index.php?page=ticket&id='.$_GET['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&action=delete"><i title="'.T_('Supprimer ce ticket').'" class="icon-trash red bigger-130"></i></a>';
							}
							if ($rright['ticket_save']!=0)
							{
								echo "&nbsp;&nbsp;";
								echo '<button class="btn btn-minier btn-success" title="'.T_('Sauvegarder').'" name="modify" value="submit" type="submit" id="modify"><i class="icon-save bigger-140"></i></button>';
                                echo "&nbsp;&nbsp;";
                                echo '<button class="btn btn-minier btn-purple" title="'.T_('Sauvegarder et quitter').'" name="quit" value="quit" type="submit" id="quit"><i class="icon-save bigger-140"></i></button>';
							}
							?>
					</span>
				</div>
				<div class="widget-body">
					<div class="widget-main">
						<!-- START sender part -->	
						<div class="form-group <?php if(($rright['ticket_user_disp']==0 && $_GET['action']!='new') || ($rright['ticket_new_user_disp']==0 && $_GET['action']=='new')) echo 'hide';?>" >
							<label class="col-sm-2 control-label no-padding-right" for="user">
								<?php if (($_POST['user']==0) && ($globalrow['user']==0) && ($u_group=='')) echo '<i title="'.T_('Sélectionner un demandeur').'" class="icon-warning-sign red bigger-130"></i>&nbsp;'; ?>
								<?php echo T_('Demandeur').':'; ?>
							</label>
							<div class="col-sm-9">
								<!-- START sender list part -->
								<!-- <select autofocus id="user" name="user" onchange="loadVal(); submit();" <?php if(($rright['ticket_user']==0 && $_GET['action']!='new') || ($rright['ticket_new_user']==0 && $_GET['action']=='new')) echo ' disabled="disabled" ';?> > -->
								<select autofocus <?php if($mobile==0) {echo 'class="chosen-select"';}  else {echo ' style="max-width: 225px;" ';} ?> id="user" name="user" onchange="loadVal(); submit();" <?php if(($rright['ticket_user']==0 && $_GET['action']!='new') || ($rright['ticket_new_user']==0 && $_GET['action']=='new')) echo ' disabled="disabled" ';?> >
									<?php
									//define order of user list in case with company prefix
									if($rright['ticket_user_company']!=0)
									{
										$query = $db->query("SELECT DISTINCT tusers.* FROM `tusers` LEFT JOIN tcompany ON tusers.company=tcompany.id WHERE (tusers.lastname!='' OR tusers.firstname!='')  ORDER BY tcompany.name, tusers.lastname");
									} else {
										$query = $db->query("SELECT * FROM `tusers` WHERE (lastname!='' OR firstname!='')  ORDER BY lastname ASC, firstname ASC");
									}
									//display user list and keep selected an disable user
									while ($row = $query->fetch()) 
									{
										if($rright['ticket_user_company']!=0 && $row['company']!=0)
										{
											//get company name of this user to display if exist
											$query2=$db->query("SELECT name FROM tcompany WHERE id='$row[company]'"); 
											$user_company=$query2->fetch();
											$query2->closeCursor();
											$user_company='['.$user_company[0].'] ';
										} else {$user_company='';}
										if ($_POST['user']==$row['id']) {$selected='selected';} elseif (($_POST['user']=='') && ($globalrow['user']==$row['id'])) {$selected='selected';} else {$selected='';} 
										if ($row['id']==0) {echo '<option '.$selected.' value="'.$row['id'].'">'.T_(" $row[lastname]").' '.$row['firstname'].'</option>';} //case no user
										if ($row['disable']==0) {echo '<option '.$selected.' value="'.$row['id'].'">'.$user_company.$row['lastname'].' '.$row['firstname'].'</option>';} //all enable users and technician
										if (($row['disable']==1) && ($selected=='selected') && $row['id']!=0) {echo '<option '.$selected.' value="'.$row['id'].'">'.$row['lastname'].' '.$row['firstname'].'</option>';} //case disable user always attached to this ticket
									}
									$query->closeCursor(); 
									//display group list and keep selected an disable group
									$query = $db->query("SELECT * FROM `tgroups` WHERE type='0' ORDER BY name");
									while ($row = $query->fetch()) 
									{
										if ($row['id']==$u_group) {$selected='selected';} else {$selected='';}
										if ($row['disable']==0) {echo '<option '.$selected.' value="G_'.$row['id'].'">[G] '.T_(" $row[name]").'</option>';}
										if (($row['disable']==1) && ($selected=='selected')) {echo '<option '.$selected.' value="G_'.$row['id'].'">[G] '.$row['name'].'</option>';}
									}
									$query->closeCursor(); 
									?>
								</select>
								
								<?php if(($rright['ticket_user']==0 && $_GET['action']!='new') || ($rright['ticket_new_user']==0 && $_GET['action']=='new')) echo ' <input type="hidden" name="user" value='.$globalrow['user'].' /> '; //send data in disabled case?>
								<!-- END sender list part -->
								<!-- START sender actions part -->
								<?php
								if ($rright['ticket_user_actions']!=0)
								{
								    echo'<input type="hidden" name="action" value="">';
								    echo'<input type="hidden" name="edituser" value="">';
									echo '&nbsp;&nbsp;<i class="icon-plus-sign green bigger-130" title="'.T_('Ajouter un utilisateur').'" onclick="loadVal(); document.forms[\'myform\'].action.value=\'adduser\';document.forms[\'myform\'].submit();"></i>&nbsp;&nbsp;';
									if ($u_group!=0)
									{
									    echo '<i class="icon-pencil orange bigger-130" title="'.T_('Modifier le groupe').'" value="useredit" onClick="parent.location=\'./index.php?page=ticket&id='.$_GET['id'].'&userid='.$_GET['userid'].'&action=edituser&edituser='.$u_group.'\'"  /></i>&nbsp;&nbsp;';
									}
									else
									{
										if ($_POST['user']) $selecteduser=$_POST['user']; else $selecteduser=$globalrow['user'];
										//hide modify link when none user selected
										if ($selecteduser!=0){echo '<i class="icon-pencil orange bigger-130" title="'.T_('Modifier un utilisateur').'" onclick="loadVal(); document.forms[\'myform\'].action.value=\'edituser\';document.forms[\'myform\'].edituser.value=\''.$selecteduser.'\';document.forms[\'myform\'].submit();"></i>&nbsp;&nbsp;';}
									}
								}	
								?>
								<!-- END sender actions part -->
								<!-- START user info part -->
									<?php
									//Display asset tel fax department if exist
									if ($u_group=='')
									{
										if ($_POST['user']) 
										{
											$query = $db->query("SELECT * FROM `tusers` WHERE id LIKE '$_POST[user]'"); 
										}
										else
										{
											$query = $db->query("SELECT * FROM `tusers` WHERE id LIKE '$globalrow[user]'"); 
										}
										$row=$query->fetch();
										$query->closeCursor(); 
										echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										if ($row['phone']!="") echo '&nbsp;&nbsp;&nbsp;<a href="tel:'.$row['phone'].'"><i title="'.T_('Téléphoner au').' '.$row['phone'].'" class="icon-phone-sign blue bigger-130"></i></a> <b>'.$row['phone'].'</b>';
										if ($row['mail']!="") echo '&nbsp;&nbsp;&nbsp;<a href="mailto:'.$row['mail'].'"><i title="'.T_("Envoyer un mail à l'adresse").' '.$row['mail'].'" class="icon-envelope blue bigger-130"></i></a>';
										if ($row['function']!="") echo '&nbsp;&nbsp;&nbsp;<i title="'.T_('Fonction').'" class="icon-user blue bigger-130"></i> '.$row['function'];
										if ($row['company']!=0) 
										{
											$query=$db->query("SELECT * FROM tcompany WHERE id='$row[company]'"); 
											$g_company_name=$query->fetch();
											$query->closeCursor();
											echo '&nbsp;&nbsp;&nbsp;<i title="'.T_('Société').': '.$g_company_name['name'].' '.$g_company_name['address'].' '.$g_company_name['zip'].' '.$g_company_name['city'].'" class="icon-building blue bigger-130"></i> '.$g_company_name['name'];
										}
										//get service from user and display it
										$service_name='';
										//$query2=$db->query("SELECT name FROM tservices WHERE id IN (SELECT service_id FROM tusers_services WHERE user_id=$row[id]) AND disable=0");
										if($row['id'])
										{
											$query2=$db->query("SELECT name FROM tservices,tusers_services WHERE tservices.id=tusers_services.service_id AND tusers_services.user_id=$row[id] AND tservices.disable=0");
											while ($row2=$query2->fetch()){$service_name.=$row2['name'].' ';}
											$query2->closeCursor();
											if($service_name){echo '&nbsp;&nbsp;&nbsp;<i title="'.T_('Service').'" class="icon-group blue bigger-120"></i> '.$service_name;}
										}
										
										if($rparameters['user_agency']==1)
										{
											//get agency from user and display it
											$agency_name='';
											$query2=$db->query("SELECT name FROM tagencies WHERE id IN (SELECT agency_id FROM tusers_agencies WHERE user_id=$row[id]) AND disable=0");
											while ($row2=$query2->fetch()){$agency_name.=$row2['name'].' ';}
											$query2->closeCursor();
											if($agency_name){echo '&nbsp;&nbsp;&nbsp;<i title="'.T_('Agence').'" class="icon-globe blue bigger-120"></i> '.$agency_name;}
										}
										//find associated asset
										if ($_POST['user']) {$query = $db->query("SELECT id,netbios FROM `tassets` WHERE user='$_POST[user]' AND state='2' AND user!='0' ORDER BY id DESC");} else {$query = $db->query("SELECT id,netbios FROM `tassets` WHERE user='$globalrow[user]' AND state='2' AND user!='0' ORDER BY id DESC");}
										$row=$query->fetch();
										if ($row['netbios']!='') {echo '&nbsp;&nbsp;&nbsp;<a target="_blank" href="./index.php?page=asset&id='.$row['id'].'"><i title="'.T_('Équipement associé').'" class="icon-desktop blue bigger-120"></i></a> '.$row['netbios'];}
										$query->closeCursor(); 
									}
									if($mobile==0)
									{
										//other demands for this user or group
										if ($u_group)
										{
											$umodif=$u_group;
											$usergroup="u_group";
										} else {
											if($_POST['user']) $umodif=$_POST['user']; else $umodif=$globalrow['user'];
											$usergroup="user";
										}
										if ($umodif!='') //case for new ticket without sender
										{
											$qn = $db->query("SELECT count(*) FROM `tincidents` WHERE $usergroup LIKE '$umodif' and (state='1' OR state='2' OR state='6' OR state='5') and id NOT LIKE $db_id and disable=0");
											$rn=$qn->fetch();
											$qn->closeCursor();
											$rnn=$rn[0];
											if ($rnn!=0) echo '&nbsp;&nbsp; <i title="'.T_('Autres tickets de cet utilisateur').'" class="icon-ticket blue bigger-130"></i> ';
											$c=0;
											$q = $db->query("SELECT id,title FROM `tincidents` WHERE $usergroup LIKE '$umodif' and (state='1' OR state='2' OR state='6' OR state='5') and id NOT LIKE $db_id and disable=0 ORDER BY id DESC"); 
											while (($r=$q->fetch()) && ($c<2)) {	
												$c=$c+1;
												echo "<a title=\"$r[title]\" href=\"./index.php?page=ticket&amp;id=$r[id]\">#$r[id]</a>";
												if ($c<$rnn) echo ", ";
												if ($c==2) echo "...";
											}  
											$query->closeCursor();
											if ($rnn!=0) echo "";
										}
									}
									?>
								<!-- START user info part -->
							</div>
						</div>
						<!-- END sender part -->
						<!-- START destination service part -->
						<?php
				            if($rright['ticket_service_disp']!=0)
				            {
								if ($rright['ticket_service_mandatory']!=0) {
									if (($_POST['u_service']==0) && ($globalrow['u_service']==0)){$service_mandatory='has-error';}elseif($_GET['action']=='new'){$service_mandatory='has-success';}else{$service_mandatory='';}
								} else {$service_mandatory='';}
				                echo'
				                <div class="form-group '; if ($rright['ticket_new_service_disp']==0 && $_GET['action']=='new') {echo 'hide';} echo ' '.$service_mandatory.'" >
        							<label class="col-sm-2 control-label no-padding-right" for="u_service">
        							    ';
											if (($_POST['u_service']==0) && ($globalrow['u_service']==0)) {if($rright['ticket_service_mandatory']!=0) {echo '<i title="'.T_('La saisie du service est obligatoire').'"';} else {echo '<i title="'.T_('Sélectionner un service').'"';} echo 'class="icon-warning-sign red bigger-130"></i>&nbsp;';} 
										echo'
        							    '.T_('Service').':
        							</label>
        							<div class="col-sm-8">
        							    <select id="u_service" name="u_service" '; if(($rright['ticket_service']==0 && $_GET['action']!='new') || ($rright['ticket_new_service']==0 && $_GET['action']=='new')) {echo 'disabled="disabled"';} echo' onchange="loadVal(); submit();">
											';
        									if ($_POST['u_service'])
        									{
												$query2=$db->query("SELECT * FROM `tservices` WHERE id='$_POST[u_service]'");
												$row2=$query2->fetch();
												$query2->closeCursor(); 
        										echo '<option value="'.$_POST['u_service'].'" selected >'.T_($row2['name']).'</option>';
        										$query2 = $db->query("SELECT * FROM `tservices` WHERE id!='$_POST[u_service]' AND disable='0' ORDER BY id!=0, name");
        							    		while ($row2 = $query2->fetch()) echo '<option value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
												$query2->closeCursor(); 
        									}
        									else
        									{
        										$query2=$db->query("SELECT * FROM `tservices` WHERE id='$globalrow[u_service]' ORDER BY id");
        										$row2=$query2->fetch();
												$query2->closeCursor();
        										echo '<option value="'.$globalrow['u_service'].'" selected >'.T_($row2['name']).'</option>';
        										$query2 = $db->query("SELECT * FROM `tservices` WHERE id!='$globalrow[u_service]' AND disable='0' ORDER BY id!=0, name");
        								    	while ($row2 = $query2->fetch()) echo '<option value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
												$query2->closeCursor(); 
        									}
        									echo'			
        								</select>
										';
										//send data in disabled case
										if($rright['ticket_service']==0 && $_POST['u_service']==0 && $globalrow['u_service']!=0) echo '<input type="hidden" name="u_service" value="'.$globalrow['u_service'].'" />'; 
										echo '
        							</div>
    					    	</div>
    					    	';
				            }
				        ?>
						<!-- END destination service part -->
				        <!-- START type part -->
				       <?php
				            if($rparameters['ticket_type']==1)
				            {
				                echo'
				                <div class="form-group '; if(($rright['ticket_type_disp']==0 && $_GET['action']!='new') || ($rright['ticket_new_type_disp']==0 && $_GET['action']=='new')) {echo 'hide';} echo'">
        							<label class="col-sm-2 control-label no-padding-right" for="type">
        							    ';
											if ((($_POST['type']==0) && ($globalrow['type']==0))) {echo '<i title="'.T_('Sélectionner un type').'" class="icon-warning-sign red bigger-130"></i>&nbsp;';} 
										echo'
        							    '.T_('Type').':
        							</label>
        							<div class="col-sm-8">
        							    <select  id="type" name="type"'; if(($rright['ticket_type']==0 && $_GET['action']!='new') || ($rright['ticket_new_type']==0 && $_GET['action']=='new')) {echo 'disabled="disabled"';} echo'>';
        									
											//limit service type
											if($rparameters['user_limit_service']==1 && $rright['ticket_type_service_limit']!=0)
											{
												if($_POST['u_service']) {$where=' service='.$_POST['u_service'].' ';} else {$where=' service='.$globalrow['u_service'].' ';}
												$old_type=1;
												$query2 = $db->query("SELECT * FROM `ttypes` WHERE $where OR id=0 ORDER BY id=0 ASC,name");
												while ($row2 = $query2->fetch()) {
													//select entry
													$selected='';
													if($_POST['type'] && $row2['id']==$_POST['type']) 
													{$selected='selected';}
													elseif($globalrow['type'] && $row2['id']==$globalrow['type']) 
													{$selected='selected'; }
													if($globalrow['type']==$row2['id']) {$old_type=0;}
													echo '<option '.$selected.' value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
												}
												$query2->closeCursor(); 
												//keep old data
												if($old_type==1 && $_GET['action']!='new') {
													$query2=$db->query("SELECT * FROM `ttypes` WHERE id='$globalrow[type]'");
													$row2=$query2->fetch();
													$query2->closeCursor(); 
													echo '<option selected value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
												}
											} else {
												if ($_POST['type'])
												{
													$query2=$db->query("SELECT * FROM `ttypes` WHERE id='$_POST[type]'");
													$row2=$query2->fetch();
													$query2->closeCursor(); 
													echo '<option value="'.$_POST['type'].'" selected >'.T_($row2['name']).'</option>';
													$query2 = $db->query("SELECT * FROM `ttypes` WHERE id!='$_POST[type]'");
													while ($row2 = $query2->fetch()) echo '<option value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
													$query2->closeCursor(); 
												}
												else
												{
													$query2=$db->query("SELECT * FROM `ttypes` WHERE id='$globalrow[type]' ORDER BY name");
													$row2=$query2->fetch();
													$query2->closeCursor();
													echo '<option value="'.$globalrow['type'].'" selected >'.T_($row2['name']).'</option>';
													$query2 = $db->query("SELECT * FROM `ttypes` WHERE id!='$globalrow[type]'");
													while ($row2 = $query2->fetch()) echo '<option value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
													$query2->closeCursor(); 
												}
											}
        									echo'			
        								</select>
										';
										//send data in disabled case
										if($rright['ticket_type']==0 && $_GET['action']!='new') echo '<input type="hidden" name="type" value="'.$globalrow['type'].'" />'; 
										echo '
        							</div>
    					    	</div>
    					    	';
				            }
				        ?>
					    <!-- END type part -->	
						<!-- START technician part -->
						<?php
						//display mandatory field if right is configured
						if ($rright['ticket_tech_disp']!=0) {
							if ($rright['ticket_tech_mandatory']!=0) {
								if(($_POST['technician']!='' && ($_POST['technician']==0 || $globalrow['technician']==0)) || ($_GET['action']=='new' && $_POST['technician']==0)){$ticket_tech_mandatory='has-error'; } else {$ticket_tech_mandatory='has-success';} //case gl
							}else{$ticket_tech_mandatory='';}
						} else {$ticket_tech_mandatory='';}
						
						//lock technician field if technician open ticket for another service and limit service is enable
						if($rparameters['user_limit_service']==1 && $rright['ticket_tech_service_lock']!=0)
						{
							if($_SESSION['profile_id']==0 || $_SESSION['profile_id']==3) //for technician and supervisor
							{
								//check if current technician or supervisor is member of selected service
								if(($_POST['u_service'] && $_POST['u_service']!=0 && $_GET['action']=='new') || ($_GET['action']!='new' && $globalrow['u_service']!=0))
								{
									if($_GET['action']=='new') {$chk_svc=$_POST['u_service'];} else {$chk_svc=$globalrow['u_service'];}
									$check_tech_svc=0;
									foreach($user_services as $value) {if($chk_svc==$value){$check_tech_svc=1;}}
									if ($check_tech_svc==0) {$lock_tech=1;} else {$lock_tech=0;}
								} else {$lock_tech=0;}
							} else {$lock_tech=0;}
						} else {$lock_tech=0;}
						?>
						<div class="form-group <?php if(($rright['ticket_tech_disp']==0 && $_GET['action']!='new') || ($rright['ticket_new_tech_disp']==0 && $_GET['action']=='new')) {echo 'hide';} echo $ticket_tech_mandatory; ?>">
							<label class="col-sm-2 control-label no-padding-right" for="technician">
							<?php 
								if ($lock_tech==0) //case lock field 
								{
									if(($_POST['technician']==0 && $_POST['technician']!='') || ($_POST['technician']=='' && $globalrow['technician']==0) && $globalrow['t_group']==0) 
									{
										echo '<i title="'.T_('Aucun technicien associé à ce ticket').'" class="icon-warning-sign red bigger-130"></i>&nbsp;';
									}
								}
								echo T_('Technicien').':'; 
							?>
							</label>
							<div class="col-sm-8 ">
								<select id="technician" name="technician" onchange="loadVal(); submit();" <?php if($rright['ticket_tech']==0 || $lock_tech==1) echo ' disabled="disabled" ';?> >
									<?php
									//filter is service is send for technicians
									if($rparameters['user_limit_service']==1 && $rright['dashboard_service_only']!=0) //case for user who open ticket to auto-select categories of the service
									{
										if($_POST['u_service']) {$where_service=$_POST['u_service'];} else {$where_service=$globalrow['u_service'];}
										if ($rright['ticket_tech_super']!=0) //display supervisor in technician list
										{
											$query="SELECT * FROM `tusers` WHERE (profile='0' || profile='4' || profile='3') AND ( id IN (SELECT user_id FROM tusers_services WHERE service_id=$where_service)) OR id='0' ORDER BY lastname ASC, firstname ASC";
										} elseif ($rright['ticket_tech_admin']!=0)  { //display technician and admin in technician list
											$query="SELECT * FROM `tusers` WHERE (profile='0' || profile='4') AND ( id IN (SELECT user_id FROM tusers_services WHERE service_id=$where_service)) OR id='0' ORDER BY lastname ASC, firstname ASC";
										} else { //display only technician in technician list
											$query="SELECT * FROM `tusers` WHERE profile='0' AND ( id IN (SELECT user_id FROM tusers_services WHERE service_id=$where_service)) OR id='0' ORDER BY lastname ASC, firstname ASC";
										}
									} else {
										//display technician and admin in technician list
										if ($rright['ticket_tech_super']!=0)
										{
											$query = "SELECT * FROM `tusers` WHERE (profile='0' || profile='4' || profile='3') OR id=0 ORDER BY lastname ASC, firstname ASC" ;
										} elseif ($rright['ticket_tech_admin']!=0)
										{
											$query = "SELECT * FROM `tusers` WHERE (profile='0' || profile='4') OR id=0 ORDER BY lastname ASC, firstname ASC" ;
										} else {
											$query="SELECT * FROM `tusers` WHERE profile='0' OR id='0' ORDER BY lastname ASC, firstname ASC";
										}
									}
									
									//display technician list
									$query = $db->query($query);
									$tech_selected='0';
									while ($row = $query->fetch()) 
									{
										//select technician
										if ($_POST['technician']==$row['id']) {
											$selected="selected";
											$tech_selected=$row['id'];
										} elseif (($_POST['technician']=='') && ($globalrow['technician']==$row['id']) && $selected=='') {
											$selected="selected";
											$tech_selected=$row['id'];
										} else {
											$selected='';
										}
										//display each entry
										if ($row['id']==0) {echo '<option '.$selected.' value="'.$row['id'].'">'.T_($row['lastname']).' '.$row['firstname'].'</option>';} //case no technician TEMP 3.1.20 && (($_POST['technician']==0) && ($globalrow['technician']!=$row['id']))
										if ($row['disable']==0) {echo '<option '.$selected.' value="'.$row['id'].'">'.$row['lastname'].' '.$row['firstname'].'</option>';} //all enable technician
										if (($row['disable']==1) && ($selected=='selected') && $row['id']!=0) {echo '<option '.$selected.' value="'.$row['id'].'">'.$row['lastname'].' '.$row['firstname'].'</option>';} //case disable technician always attached to this ticket
									} 
									$query->closeCursor(); 
									//display technician group list
									$query = $db->query("SELECT * FROM `tgroups` WHERE type='1' ORDER BY name");
									while ($row = $query->fetch()) {
										//echo "<option value=\"G_$row[id]\">[G] $row[name]</option>";
										if ($row['id']==$t_group) {$selected='selected';} else {$selected='';}
										if ($row['disable']==0) {echo '<option '.$selected.' value="G_'.$row['id'].'">[G] '.T_($row['name']).'</option>';}
										if (($row['disable']==1) && ($selected=='selected')) {echo '<option '.$selected.' value="G_'.$row['id'].'">[G] '.$row['name'].'</option>';}
									}
									$query->closeCursor(); 
									?>
								</select>
								<?php 
								//send data in disabled case
								if($rright['ticket_tech']==0) echo '<input type="hidden" name="technician" value="'.$globalrow['technician'].'" />'; 
								if($lock_tech==1) echo '<input type="hidden" name="technician" value="'.$tech_selected.'" />'; 
								//display open user
								if (($globalrow['creator']!=$globalrow['technician']) && ($globalrow['creator']!="0") && $_GET['action']!='new')
								{
									//select creator name
									$query = $db->query("SELECT * FROM `tusers` WHERE id LIKE '$globalrow[creator]'");
									$row=$query->fetch();
									$query->closeCursor(); 
									echo '&nbsp;<i class="icon-user blue bigger-130"></i>&nbsp;'.T_('Ouvert par').' '.$row['firstname'].' '.$row['lastname'];
								}
								?>
							</div>
						</div>
						<!-- END technician part -->
						<!-- START asset part -->
						<?php
							if($rparameters['asset']==1)
							{
								if ($rright['ticket_new_asset_disp']!=0)
								{
									if ($rright['ticket_asset_mandatory']!=0) {
										if (($_POST['asset_id']==0) && ($globalrow['asset_id']==0)){$asset_mandatory='has-error';}elseif($_GET['action']=='new'){$asset_mandatory='has-success';}else{$asset_mandatory='';}
									} else {$asset_mandatory='';}
									echo'
									<div class="form-group '; if (($rright['ticket_new_asset_disp']==0 && $_GET['action']=='new') || ($rright['ticket_asset_disp']==0 && $_GET['action']!='new')) {echo 'hide';} echo ' '.$asset_mandatory.'" >
										<label class="col-sm-2 control-label no-padding-right" for="asset">
											';
												if (($_POST['asset_id']==0) && ($globalrow['asset_id']==0)) {if($rright['ticket_asset_mandatory']!=0) {echo '<i title="'.T_("La saisie de l'équipement est obligatoire").'"';} else {echo '<i title="'.T_('Sélectionner un équipement').'"';} echo 'class="icon-warning-sign red bigger-130"></i>&nbsp;';} 
											echo'
											'.T_('Équipement').':
										</label>
										<div class="col-sm-8">
											<select id="asset_id" name="asset_id" '; if(($rright['ticket_asset']==0 && $_GET['action']!='new') || ($rright['ticket_new_asset_disp']==0 && $_GET['action']=='new')) {echo 'disabled="disabled"';} echo' onchange="loadVal(); submit();">
												';
												if ($_POST['asset_id'])
												{
													$query2=$db->query("SELECT * FROM `tassets` WHERE id='$_POST[asset_id]'");
													$row2=$query2->fetch();
													$query2->closeCursor(); 
													echo '<option value="'.$row2['id'].'" selected >'.T_($row2['netbios']).'</option>';
													if(($globalrow['asset_id'] && $globalrow['user']) || ($_SESSION['profile_id']==3 || $_SESSION['profile_id']==2))
													{
														$query2 = $db->query("SELECT * FROM `tassets` WHERE netbios!='' AND disable='0' AND user='$globalrow[user]' ORDER BY id!=0, netbios");
													} else {
														$query2 = $db->query("SELECT * FROM `tassets` WHERE netbios!='' AND disable='0'  ORDER BY id!=0, netbios");
													}
													while ($row2 = $query2->fetch()) echo '<option value="'.$row2['id'].'">'.T_($row2['netbios']).'</option>';
													$query2->closeCursor(); 
												}
												else
												{
													//find existing value
													$query2=$db->query("SELECT * FROM `tassets` WHERE id='$globalrow[asset_id]' ORDER BY id");
													$row2=$query2->fetch();
													$query2->closeCursor();
													//select none if new ticket 
													if ($_GET['action']=='new')
													{
														echo '<option value="0">'.T_('Aucun').'</option>';
														
													} else {
														echo '<option value="'.$row2['id'].'" selected>'.$row2['netbios'].'</option>';
													}
													//user restricted list
													if($_SESSION['profile_id']==3 || $_SESSION['profile_id']==2)
													{
														$query2 = $db->query("SELECT * FROM `tassets` WHERE id!='$globalrow[asset_id]' AND netbios!='' AND disable='0' AND user='$_SESSION[user_id]' ORDER BY id!=0, netbios");
													} elseif($_POST['user']) {
														$query2 = $db->query("SELECT * FROM `tassets` WHERE id!='$globalrow[asset_id]' AND netbios!='' AND user='$_POST[user]' AND disable='0' ORDER BY id!=0, netbios");
													} elseif($globalrow['user']) {
														$query2 = $db->query("SELECT * FROM `tassets` WHERE id!='$globalrow[asset_id]' AND netbios!='' AND user='$globalrow[user]' AND disable='0' ORDER BY id!=0, netbios");
													} else {
														$query2 = $db->query("SELECT * FROM `tassets` WHERE id!='$globalrow[asset_id]' AND netbios!='' AND disable='0' ORDER BY id!=0, netbios");
													}
													while ($row2 = $query2->fetch()) echo '<option value="'.$row2['id'].'">'.T_($row2['netbios']).'</option>';
													$query2->closeCursor(); 
												}
												echo'			
											</select>
											';
											//send data in disabled case
											if($rright['ticket_asset']==0 && $_GET['action']!='new') echo '<input type="hidden" name="asset_id" value="'.$globalrow['asset_id'].'" />'; 
											echo '
										</div>
									</div>
									';
								}
							}
						?>
						<!-- END asset part -->
						<!-- START category part -->
						<div class="form-group <?php if(($rright['ticket_cat_disp']==0 && $_GET['action']!='new') || ($rright['ticket_new_cat_disp']==0 && $_GET['action']=='new')) echo 'hide';?>">
							<label class="col-sm-2 control-label no-padding-right" for="category">
								<?php if(($globalrow['category']==0) && ($_POST['category']==0)) echo '<i title="'.T_('Aucune catégorie associée').'." class="icon-warning-sign red bigger-130"></i>&nbsp;'; ?>
								<?php echo T_('Catégorie').':'; ?>
							</label>
							<div class="col-sm-8">
								<select title="<?php echo T_('Catégorie'); ?>" id="category" name="category" onchange="loadVal(); submit();" <?php if(($rright['ticket_cat']==0 && $_GET['action']!='new') || ($rright['ticket_new_cat']==0 && $_GET['action']=='new')) echo ' disabled="disabled" ';?>>
								<?php
									//if user limit service restrict category to associated service
									//&& ($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2 || $_SESSION['profile_id']==3)
									if($rparameters['user_limit_service']==1 && $rright['dashboard_service_only']!=0) //case for user who open ticket to auto-select categories of the service
									{
										if($_POST['u_service']) {$where='WHERE service='.$_POST['u_service'].' OR id=0 ';} else {$where='WHERE service='.$globalrow['u_service'].' OR id=0 ';}
									}else {$where='';}
									/*
									elseif($rparameters['user_limit_service']==1 && $rright['dashboard_service_only']!=0) //case for technician view
									{
										$where='WHERE (';
										foreach ($user_services as $values) {$where.='service='.$values.' OR ';}
										$where.=' id=0';
										$where.=')';
									}
									echo "SELECT * FROM tcategory $where ORDER BY id!=0, name";
									*/
									$query= $db->query("SELECT * FROM `tcategory` $where ORDER BY id!='0', number,name"); //order to display none in first
									while ($row = $query->fetch()) 
									{
										if ($row['id']==0) {$row['name']=T_($row['name']);} //translate only none
										if ($_POST['category']!=''){if ($_POST['category']==$row['id']) echo '<option value="'.$row['id'].'" selected>'.T_($row['name']).'</option>'; else echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';}
										else
										{if ($globalrow['category']==$row['id']) echo '<option value="'.$row['id'].'" selected>'.T_($row['name']).'</option>'; else echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';}
									}
									$query->closeCursor();
								?>
								</select>
								<?php if(($rright['ticket_cat']==0 && $_GET['action']!='new') || ($rright['ticket_new_cat']==0 && $_GET['action']=='new'))  echo '<input type="hidden" name="category" value="'.$globalrow['category'].'" />'; //send data in disabled case ?>
								<select  title="<?php echo T_('Sous-catégorie'); ?>" id="subcat" name="subcat" onchange="loadVal(); submit();" <?php if(($rright['ticket_cat']==0 && $_GET['action']!='new') || ($rright['ticket_new_cat']==0 && $_GET['action']=='new')) echo ' disabled="disabled" ';?> >
								<?php
									if ($_POST['category'])
									{$query= $db->query("SELECT * FROM `tsubcat` WHERE cat LIKE '$_POST[category]' ORDER BY name ASC");}
									else
									{$query= $db->query("SELECT * FROM `tsubcat` WHERE cat LIKE '$globalrow[category]' ORDER BY name ASC");}
									
									while ($row = $query->fetch()) 
									{
										if ($row['id']==0) {$row['name']=T_($row['name']);}
										if ($_POST['subcat'])
										{
											if ($_POST['subcat']==$row['id']) echo '<option value="'.$row['id'].'" selected>'.T_($row['name']).'</option>'; else echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';
										}
										else
										{
											if ($globalrow['subcat']==$row['id']) echo '<option value="'.$row['id'].'" selected>'.T_($row['name']).'</option>'; else echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';
										}
									} 
									$query->closeCursor();
									if ($globalrow['subcat']==0 && $_POST['subcat']==0) echo "<option value=\"\" selected></option>";
								?>
								</select>
								<?php if(($rright['ticket_cat']==0 && $_GET['action']!='new') || ($rright['ticket_new_cat']==0 && $_GET['action']=='new'))  echo '<input type="hidden" name="subcat" value="'.$globalrow['subcat'].'" />'; //send data in disabled case?>
								<?php
								if ($rright['ticket_cat_actions']!=0)
								{
									echo '
									&nbsp;&nbsp;<i class="icon-plus-sign green bigger-130" title="'.T_('Ajouter une catégorie').'" onclick="loadVal(); document.forms[\'myform\'].action.value=\'addcat\';document.forms[\'myform\'].submit();"></i>
									&nbsp;&nbsp;<i class="icon-pencil orange bigger-130" title="'.T_('Modifier une catégorie').'" onclick="loadVal(); document.forms[\'myform\'].action.value=\'editcat\';document.forms[\'myform\'].submit();"></i>&nbsp;&nbsp;
									';
								}
								?>
							</div>
						</div>
						<!-- END category part -->
						<!-- START agency part -->
						<?php
						if ($rparameters['user_agency']==1)
						{
							//select color for mandatory fields
							if ($rright['ticket_agency_mandatory']!=0 && ($_SESSION["profile_id"]==1 || $_SESSION["profile_id"]==2)) {
								if (($_POST['u_agency']==0) && ($globalrow['u_agency']==0)){$agency_mandatory='has-error';}elseif($_GET['action']=='new'){$agency_mandatory='has-success';}else{$agency_mandatory='';}
							} else {$agency_mandatory='';}
							
							//check if current user have multiple agencies to display select, else no display select and get value of agency
							$query2=$db->query("SELECT count(*) FROM `tusers_agencies` WHERE user_id='$_SESSION[user_id]'");
							$row2=$query2->fetch();
							$query2->closeCursor();
							
							if (($row2[0]==0 && ($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2) || $rright['ticket_agency']==0)) //case no agency for current user
							{
								echo '<input type="hidden" name="u_agency" value="'.$globalrow['u_agency'].'" />'; //send data without display
							}
							elseif($row2[0]==1 && ($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2)) //case one agency for current user hide field and transmit data
							{
								$query3=$db->query("SELECT agency_id FROM `tusers_agencies` WHERE user_id='$_SESSION[user_id]'");
								$row3=$query3->fetch();
								$query3->closeCursor();
								echo '<input type="hidden" name="u_agency" value="'.$row3['agency_id'].'" />'; //send data without display
							} else //else display field to select agency
							{
								//display select agency field
								echo'
				                <div class="form-group '.$agency_mandatory.'">
        							<label class="col-sm-2 control-label no-padding-right" for="u_agency">
										';
										if (($_POST['u_agency']==0) && ($globalrow['u_agency']==0) && ($_SESSION["profile_id"]==1 || $_SESSION["profile_id"]==2)) {if($rright['ticket_agency_mandatory']!=0) {echo '<i title="'.T_("La saisie de l'agence est obligatoire").'"';} else {echo '<i title="'.T_('Sélectionner une agence').'"';} echo 'class="icon-warning-sign red bigger-130"></i>&nbsp;';} 
										echo'
        							    '.T_('Agence').':
        							</label>
        							<div class="col-sm-8">
        							    <select id="u_agency" name="u_agency" >
											';
											$find_agency_id=0;
											if ($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2) //display list of agency of current user if it's a user or poweruser
											{
												$query3=$db->query("SELECT agency_id FROM `tusers_agencies` WHERE user_id='$_SESSION[user_id]' AND agency_id IN (SELECT id AS agency_id FROM `tagencies` WHERE disable=0)");
											} elseif(($_SESSION['profile_id']==0 || $_SESSION['profile_id']==3) && $_POST['user']) { //case display only user agencies for technician or supervisor profile 
												$query3=$db->query("SELECT agency_id FROM `tusers_agencies` WHERE user_id='$_POST[user]' AND agency_id IN (SELECT id AS agency_id FROM `tagencies` WHERE disable=0)");												
											} elseif (($_SESSION['profile_id']==0 || $_SESSION['profile_id']==3) && $globalrow['user']) {
												$query3=$db->query("SELECT agency_id FROM `tusers_agencies` WHERE user_id='$globalrow[user]' AND agency_id IN (SELECT id AS agency_id FROM `tagencies` WHERE disable=0)");												
											} else {
												$query3=$db->query("SELECT id AS agency_id FROM `tagencies` WHERE disable=0 ORDER BY name");
											}
											$count = $query3->rowCount();
											
											while ($row3 = $query3->fetch())
											{
												//get agency name
												$query4=$db->query("SELECT id,name FROM `tagencies` WHERE id='$row3[agency_id]'");
												$row4=$query4->fetch();
												$query4->closeCursor();
												if($globalrow['u_agency']==$row4['id']) {$selected='selected';} else {$selected='';}
												if ($count==1 && $_GET['action']=='new') {$selected='selected'; $find_agency_id=1;}
												/*
												if($globalrow['u_agency']==0 && $_GET['action']=='new' && $_SESSION['profile_id']!=1 && $_SESSION['profile_id']!=2 && $_SESSION['profile_id']!=3) //special case auto-select agency for tech
												{
													$query5=$db->query("SELECT agency_id FROM tusers_agencies WHERE user_id='$_POST[user]'");
													$row5=$query5->fetch();
													$query5->closeCursor();
													if($row5['agency_id']==$row4['id']){$selected='selected'; $find_agency_id=1;}
												}
												*/
												echo '<option value="'.$row4['id'].'" '.$selected.' >'.T_($row4['name']).'</option>';
											}
											//case for no agency selected
											if($globalrow['u_agency']==0 && $find_agency_id==0 && $_POST['u_agency']==0) {echo '<option value="0" selected >'.T_("Aucune").'</option>';}
											$query3->closeCursor();
        									echo'			
        								</select>
        							</div>
    					    	</div>
								';
								if($_GET['action']!='new' && $_POST['u_agency']==$globalrow['u_agency'] && $_POST['u_agency']!=0) {echo '<input type="hidden" name="u_agency" value="'.$globalrow['u_agency'].'" />';} //send data in disabled case
							}
						}
						?>
						<!-- END agency part -->
						
						<!-- START sender service part -->
						<?php
				            if($rright['ticket_sender_service_disp']!=0 && ($_SESSION['profile_id']=='0' || $_SESSION['profile_id']=='3' || $_SESSION['profile_id']=='4'))
				            {
								//get service of selected sender
								if($_POST['user']) {
									$query2=$db->query("SELECT id,name FROM tservices WHERE id IN (SELECT service_id FROM tusers_services WHERE user_id=$_POST[user])");
									$cnt_sender_svc=$query2->rowCount();
									
								} elseif($globalrow['user'])
								{
									$query2=$db->query("SELECT id,name FROM tservices WHERE id IN (SELECT service_id FROM tusers_services WHERE user_id=$globalrow[user])");
									$cnt_sender_svc=$query2->rowCount();
								} else {
									$cnt_sender_svc=0;
								}
								
								if ($cnt_sender_svc>=1)
								{
									echo'
									<div class="form-group" >
										<label class="col-sm-2 control-label no-padding-right" for="sender_service">
											'.T_('Service du demandeur').':
										</label>
										<div class="col-sm-8">
											<select id="sender_service" name="sender_service" onchange="loadVal();">
												<option value="0">'.T_('Aucun').'</option>
												';
													//echo '<option value="'.$globalrow['sender_service'].'" selected >'.T_($row2['name']).'</option>';
													while ($row2 = $query2->fetch()) 
													{
														if ($_POST['sender_service']==$row2['id'])
														{
															echo '<option selected value="'.$row2['id'].'">'.T_($row2['name']).'</option>'; //selected case
														} elseif ($globalrow['sender_service']==$row2['id']) {
															echo '<option selected value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
														} elseif ($cnt_sender_svc==1){
															echo '<option selected value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
														} else {
															echo '<option value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
														}
													}
												echo'			
											</select>
											';
											//send data in disabled case
											echo '
										</div>
									</div>
									';
								} else {
									if($globalrow['sender_service']!=0) //if value exist keep value
									{
										echo '<input type="hidden" name="sender_service" value="'.$globalrow['sender_service'].'" />'; 
									} 
								}
								$query2->closeCursor();
				            } else { //single user case
								if($globalrow['sender_service']!=0) //if value exist keep value
								{
									//disable or hide case to keep value
									echo '<input type="hidden" name="sender_service" value="'.$globalrow['sender_service'].'" />';
								} else {
									//get sender service id to put in database
									$query2=$db->query("SELECT MAX(id) FROM tservices WHERE id IN (SELECT service_id FROM tusers_services WHERE user_id=$_SESSION[user_id])");
									$sender_svc_id=$query2->fetch();
									$query2->closeCursor();
									if($sender_svc_id[0]) {echo '<input type="hidden" name="sender_service" value="'.$sender_svc_id[0].'" />';}
								}
							}
				        ?>
						<!-- END sender service part -->
						<!-- START place part if parameter is on -->
						<?php
						if($rparameters['ticket_places']==1)
						{
							echo '
							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right" for="ticket_places">'.T_('Lieu').':</label>
								<div class="col-sm-8">
									<select class="textfield" id="ticket_places" name="ticket_places" '; if($rright['ticket_place']==0 && $_GET['action']!='new') {echo 'disabled="disabled"';} echo' > 
										';
										if($_POST['ticket_places'])
										{
										    $query = $db->query("SELECT * FROM `tplaces` ORDER BY name ASC");
    										while ($row = $query->fetch()) 
    										{
    											if ($_POST['ticket_places']==$row['id']) echo '<option selected value="'.$row['id'].'">'.T_($row['name']).'</option>'; else echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';
    										}
											$query->closeCursor();
										} else {
    										$query = $db->query("SELECT * FROM `tplaces` ORDER BY name ASC");
    										while ($row = $query->fetch()) 
    										{
    											if ($globalrow['place']==$row['id']) echo '<option selected value="'.$row['id'].'">'.T_($row['name']).'</option>'; else echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';
    										}
											$query->closeCursor();
										}
									echo '
									</select>
									';
									if($rright['ticket_place']==0 && $_GET['action']!='new')  echo '<input type="hidden" name="ticket_places" value="'.$globalrow['place'].'" />'; //send data in disabled case
									echo '
								</div>
							</div>
							';
						}
						?>
						<!-- END place part -->
						<!-- START title part -->
						<?php
						//display mandatory field if right is configured
						if ($rright['ticket_title_disp']!=0) {
							if ($rright['ticket_title_mandatory']!=0) {
								if($_POST['title']=='' && $globalrow['title']==''){$ticket_title_mandatory='has-error';} else {$ticket_title_mandatory='has-success';}
							}else{$ticket_title_mandatory='';}
						} else {$ticket_title_mandatory='';}
						?>
						<div class="form-group <?php if($rright['ticket_title_disp']==0) {echo 'hide';} echo $ticket_title_mandatory; ?>">
							<label class="col-sm-2 control-label no-padding-right" for="title">
							<?php
							if($rright['ticket_title_mandatory']!=0 && $ticket_title_mandatory=='has-error') {echo '<i title="'.T_("La saisie du champ titre est obligatoire.").'" class="icon-warning-sign red bigger-130"></i>&nbsp;';}
							echo T_('Titre'); 
							?>:
							</label>
							<div class="col-sm-8">
								<input  name="title" id="title" type="text" size="<?php if($mobile==0) {echo '50';} else {echo '30';}?>"  value="<?php if ($_POST['title']!='' && $_POST['title']!='\'\'') echo $_POST['title']; else echo htmlspecialchars($globalrow['title']); ?>" <?php if($rright['ticket_title']==0  && $_GET['action']!='new') echo 'readonly="readonly"';?> />
							</div>
						</div>
						<!-- END title part -->
						<!-- START description part -->
						<?php
						//display mandatory field if right is configured
						if ($rright['ticket_description_disp']!=0) {
							if ($rright['ticket_description_mandatory']!=0) {
								if((($_POST['text']=='' || ctype_space($_POST['text'])==1 || ctype_space(strip_tags($_POST['text']))==1))  && ($globalrow['description']=='' || (ctype_space(strip_tags($globalrow['description']))==1 || strip_tags($globalrow['description'])=='')))
								{$ticket_description_mandatory='has-error';} else {$ticket_description_mandatory='has-success';}
							}else{$ticket_description_mandatory='';}
						} else {$ticket_description_mandatory='';}
						?>
						<div class="form-group <?php if($rright['ticket_description_disp']==0) {echo 'hide';} echo $ticket_description_mandatory; ?>">
							<label class="col-sm-2 control-label no-padding-right" for="text">
							<?php 
							if($rright['ticket_description_mandatory']!=0 && $ticket_description_mandatory=='has-error') {echo '<i title="'.T_("La saisie du champ description est obligatoire.").'" class="icon-warning-sign red bigger-130"></i>&nbsp;';}
							echo T_('Description'); 
							?>:
							</label>
							<div class="col-sm-8">
								<table border="1" width="<?php if($mobile==0) {echo '732';} else {echo '285';}?>" style="border: 1px solid #D8D8D8;" <?php if ($rright['ticket_description']==0) echo 'cellpadding="10"'; ?> >
									<tr>
										<td>
											<?php
											if ($rright['ticket_description']!=0 || $_GET['action']=='new')
											{	
												//display editor
												echo '
												<div id="editor" class="wysiwyg-editor" style="min-height:80px; ">';
											    	if ($_POST['text'] && $_POST['text']!='') echo "$_POST[text]"; else echo $globalrow['description'];
										            if ($_GET['action']=='new' && !$_POST['user']) {echo '';}	 echo'
												</div>
												<input type="hidden" id="text" name="text" />
												';
											} else {
												echo $globalrow['description'];
												echo '<input type="hidden" name="text" value="'.htmlentities($globalrow['description']).'" />';
											}
											?>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<!-- END description part -->
						<!-- START resolution part -->
						<div class="form-group <?php if(($rright['ticket_resolution_disp']==0 && $_GET['action']!='new') || ($rright['ticket_new_resolution_disp']==0 && $_GET['action']=='new')) echo 'hide';?>" >
							<label class="col-sm-2 control-label no-padding-right" for="resolution"><?php echo T_('Résolution'); ?>:</label>
							<div class="col-sm-8">	
							<?php include "./thread.php";?>	
							</div>
						</div>
						<a id="down"></a>
						<!-- END resolution part -->
						<!-- START attachement part -->
						<?php
						if ($rright['ticket_attachment']!=0)
						{
							echo '
							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right" for="attachment">'.T_('Fichier joint').':</label>
									<div class="col-sm-8">
										<table border="1" style="border: 1px solid #D8D8D8;" cellpadding="10" >
										<tr>
											<td>';
										include "./attachement.php";
										echo '
										</td>
										</tr>
									</table>
									</div>
							</div>';
						}
						?>
						<!-- END attachement part -->
						<!-- START create date part -->
						<?php
						//datetime convert SQL format to display
						if ($globalrow['date_create'])
						{
							$globalrow['date_create'] = DateTime::createFromFormat('Y-m-d H:i:s', $globalrow['date_create']);
							$globalrow['date_create']=$globalrow['date_create']->format('d/m/Y H:i:s');
						}
						?>
						<div class="form-group  <?php if($rright['ticket_date_create_disp']==0) echo 'hide';?>">
							<label class="col-sm-2 control-label no-padding-right" for="date_create"><?php echo T_('Date de la demande'); ?>:</label>
							<div class="col-sm-8">
								<input type="hidden" name="hide" id="hide" value="1"/>
								<input type="text" name="date_create" id="date_create" value="<?php if ($_POST['date_create']) echo $_POST['date_create']; else echo $globalrow['date_create']; ?>" <?php if($rright['ticket_date_create']==0) echo 'readonly="readonly"';?> >
							</div> 
						</div>
						<!-- END create date part -->
						<!-- START hope date part -->
						<?php
						//datetime convert SQL format to display
						if ($globalrow['date_hope']=='0000-00-00')
						{
							$globalrow['date_hope']='';
						} elseif ($globalrow['date_hope'])
						{
							$globalrow['date_hope'] = DateTime::createFromFormat('Y-m-d', $globalrow['date_hope']);
							$globalrow['date_hope']=$globalrow['date_hope']->format('d/m/Y');
						}
						?>
						<?php if($rright['ticket_date_hope_mandatory']!=0) { if(($_POST['date_hope']=="" && $_GET['action']=='new') || $_POST['date_hope']=="0000-00-00" ||  $globalrow['date_hope']=="0000-00-00") {$date_hope_error="has-error";}  else {$date_hope_error="";}} //check empty field?>
						<div class="form-group <?php echo $date_hope_error; if($rright['ticket_date_hope_disp']==0) echo 'hide';?>">
							<label class=" col-sm-2 control-label no-padding-right" for="date_hope">
							    <?php if($rright['ticket_date_hope_mandatory']!=0) { if (($_POST['date_hope']==0) && ($globalrow['date_hope']==0)) {echo '<i title="'.T_("La sélection d'une date de résolution estimée est obligatoire.").'" class="icon-warning-sign red bigger-130"></i>&nbsp;';}} ?>
						    	<?php echo T_('Date de résolution estimée'); ?>:
							</label>
							<div class="col-sm-8">
								<input  type="text" id="date_hope" name="date_hope"  onchange="loadVal();" value="<?php  if ($_POST['date_hope']) echo $_POST['date_hope']; else echo $globalrow['date_hope']; ?>" <?php if($rright['ticket_date_hope']==0) echo 'readonly="readonly"';?>>
								<?php
									//display warning if hope date is passed
									$date_hope=$globalrow['date_hope'];
									$querydiff=$db->query("SELECT DATEDIFF(NOW(), '$date_hope') "); 
									$resultdiff=$querydiff->fetch();
									$query->closeCursor(); 
									if ($resultdiff[0]>0 && ($globalrow['state']!="3" && $globalrow['state']!="4")) echo "<i title=\"Date de résolution dépassée de $resultdiff[0] jours\" class=\"icon-warning-sign orange bigger-130\" ></i>";
								?>
							</div>
						</div>
						<!-- END hope date part -->
						<!-- START resolution date part -->
						<?php
						//datetime convert SQL format to display
						if ($globalrow['date_res']=='0000-00-00 00:00:00')
						{
							$globalrow['date_res']='';
						} elseif ($globalrow['date_res'])
						{
							$globalrow['date_res'] = DateTime::createFromFormat('Y-m-d H:i:s', $globalrow['date_res']);
							$globalrow['date_res']=$globalrow['date_res']->format('d/m/Y H:i:s');
						}
						?>
						<div class="form-group <?php if($rright['ticket_date_res_disp']==0) echo 'hide';?>">
							<label class="col-sm-2 control-label no-padding-right" for=""><?php echo T_('Date de résolution'); ?>:</label>
							<div class="col-sm-8">
								<input  type="text" id="date_res" name="date_res"  value="<?php  if ($_POST['date_res']) echo $_POST['date_res']; else echo $globalrow['date_res']; ?>" <?php if($rright['ticket_date_res']==0) echo 'readonly="readonly"';?>>
							</div>
						</div>
						<!-- END resolution date part -->
						<!-- START time part -->
						<div class="form-group <?php if($rright['ticket_time_disp']==0) echo 'hide';?>">
							<label class="col-sm-2 control-label no-padding-right" for="time"><?php echo T_('Temps passé'); ?>:</label>
							<div class="col-sm-8">
								<select  id="time" name="time" <?php if($rright['ticket_time']==0) echo 'disabled';?> >
								<?php
									$query = $db->query("SELECT * FROM `ttime` ORDER BY min ASC");
									while ($row = $query->fetch()) 
									{
										if (($_POST['time']==$row['min'])||($globalrow['time']==$row['min']))
										{
											echo '<option selected value="'.$row['min'].'">'.$row['name'].'</option>';
											$selected_time=$row['min'];
										} else {
											echo '<option value="'.$row['min'].'">'.$row['name'].'</option>';
										}
									}
									$query->closeCursor();
									//special case when time entry was modify or delete from admin time list
									$query=$db->query("SELECT * FROM `ttime` WHERE min='$globalrow[time]'");
									$row=$query->fetch();
									$query->closeCursor(); 
									if(!$row && $_GET['action']!='new') { echo '<option selected value="'.$globalrow['time'].'">'.$globalrow['time'].'m</option>';}
								?>
								</select>
								<?php
								//send value in lock select case 
								if($rright['ticket_time']==0) {echo '<input type="hidden" name="time" value="'.$selected_time.'" />';}
								?>
							</div>
						</div>
						<!-- END time part -->
						<!-- START time hope part -->
						<div class="form-group <?php if($rright['ticket_time_hope_disp']==0) echo 'hide';?>">
							<label class="col-sm-2 control-label no-padding-right" for="time_hope">
							<?php if (($globalrow['time_hope']<$globalrow['time']) && $globalrow['state']!='3') echo "<i class=\"icon-warning-sign red bigger-130\" title=\"Le temps est sous-estimé.\"></i>";//display error if time hope < time pass?>
							<?php echo T_('Temps estimé'); ?>:
							</label>
							<div class="col-sm-8">
								<select  id="time_hope" name="time_hope" <?php if($rright['ticket_time_hope']==0) echo 'disabled';?> >
									<?php
									$query = $db->query("SELECT * FROM `ttime` ORDER BY min ASC");
									while ($row = $query->fetch()) 
									{
										if (($_POST['time_hope']==$row['min']) || ($globalrow['time_hope']==$row['min']))
										{
											echo '<option selected value="'.$row['min'].'">'.$row['name'].'</option>'; 
											$selected_time_hope=$row['min'];
										} else {
											echo '<option value="'.$row['min'].'">'.$row['name'].'</option>';
											$selected_time_hope=$row['min'];
										}
									}
									$query->closeCursor(); 
									//special case when time entry was modify or delete from admin time list
									$query=$db->query("SELECT * FROM `ttime` WHERE min='$globalrow[time_hope]'");
									$row=$query->fetch();
									$query->closeCursor(); 
									if(!$row) { echo '<option selected value="'.$globalrow['time_hope'].'">'.$globalrow['time_hope'].'m</option>';}
									?>
								</select>
								<?php
								//send value in lock or hide case
								if($rright['ticket_time_hope']==0 || $rright['ticket_time_hope_disp']==0) {
									echo '<input type="hidden" name="time_hope" value="'.$globalrow['time_hope'].'" />';
								}
								?>
							</div>
						</div>
						<!-- END time hope part -->
						<!-- START priority part -->
						<?php if($rright['ticket_priority_mandatory']!=0) {if(($_POST['priority']=="" && $_GET['action']=='new') || ($globalrow['priority']=="" && $_GET['action']!='new') || ($globalrow['criticality']=="0")) {$priority_error="has-error";} else {$priority_error="";}}  else {$priority_error="";} ?>
						<div class="form-group <?php echo $priority_error; if($rright['ticket_priority_disp']==0) echo 'hide';?>">
							<label class="col-sm-2 control-label no-padding-right" for="priority">
							    <?php if($rright['ticket_priority_mandatory']!=0) { if (($_POST['priority']==0) && ($globalrow['priority']==0)) {echo '<i title="La sélection d\'une priorité est obligatoire." class="icon-warning-sign red bigger-130"></i>&nbsp;';}} ?>
						    	<?php echo T_('Priorité'); ?>:
							</label>
							<div class="col-sm-8">
								<select  id="priority" name="priority"  <?php if($rright['ticket_priority']==0) echo 'disabled';?>>
									<?php
									//if user limit service restrict priority to associated service
									if($rparameters['user_limit_service']==1 && $rright['dashboard_service_only']!=0)
									{
										if($_POST['u_service']) {$where=' service='.$_POST['u_service'].' ';} else {$where=' service='.$globalrow['u_service'].' ';}
										$old_priority=1;
										$query2 = $db->query("SELECT * FROM `tpriority` WHERE $where OR id=0 ORDER BY number DESC");
										while ($row2 = $query2->fetch()) {
											//select entry
											$selected='';
											if($_POST['priority'] && $row2['id']==$_POST['priority']) 
											{$selected='selected';}
											elseif($globalrow['priority'] && $row2['id']==$globalrow['priority']) 
											{$selected='selected';}
											if($globalrow['priority']==$row2['id']) {$old_priority=0;}
											echo '<option '.$selected.' value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
										}
										$query2->closeCursor(); 
										//keep old data
										if($old_priority==1 && $_GET['action']!='new') {
											$query2=$db->query("SELECT * FROM `tpriority` WHERE id='$globalrow[priority]'");
											$row2=$query2->fetch();
											$query2->closeCursor(); 
											echo '<option selected value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
										}
									} else { //case no limit service
										if ($_POST['priority'])
										{
											//find row to select
											$query = $db->query("SELECT * FROM `tpriority` WHERE id='$_POST[priority]' ORDER BY number DESC");
											$row=$query->fetch();
											$query->closeCursor(); 
											echo '<option value="'.$_POST['priority'].'" selected >'.T_($row['name']).'</option>';
											//display all entries without selected
											$selected_priority=$_POST['priority'];
											$query = $db->query("SELECT DISTINCT(id),name FROM `tpriority` WHERE id!='$_POST[priority]' ORDER BY number DESC");
											while ($row = $query->fetch()) echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>'; 
											$query->closeCursor(); 
											
										} else {
											if($globalrow['priority'])
											{
												//find row to select
												$query = $db->query("SELECT DISTINCT(id),name FROM `tpriority` WHERE id LIKE '$globalrow[priority]' ORDER BY number DESC ");
												$row=$query->fetch();
												$query->closeCursor(); 
												echo '<option value="'.$globalrow['priority'].'" selected >'.T_($row['name']).'</option>';
												$selected_priority=$globalrow['priority'];
											} else {$selected_priority='';}
											
											$query = $db->query("SELECT * FROM `tpriority` WHERE id!='$globalrow[priority]' ORDER BY number DESC");
											while ($row = $query->fetch()) echo '<option value="'.$row['id'].'" >'.T_($row['name']).'</option>';
											$query->closeCursor(); 
										}
									}
									?>			
								</select>
								<?php
								//send value in lock select case 
								if($rright['ticket_priority']==0 || $rright['ticket_priority_disp']==0) {echo '<input type="hidden" name="priority" value="'.$globalrow['priority'].'" />';}
								
								//display priority icon
								if($_POST['priority']) {$check_id=$_POST['priority'];} elseif($globalrow['priority']) {$check_id=$globalrow['priority'];} else {$check_id=6;}
								$query = $db->query("SELECT * FROM `tpriority` WHERE id='$check_id'");
								$row=$query->fetch();
								$query->closeCursor(); 
								if ($row['name']) {echo '<i title="'.T_($row['name']).'" class="icon-warning-sign bigger-130" style="color:'.T_($row['color']).'" ></i>';}
								?>
							</div>
						</div>
						<!-- END priority part -->
						<!-- START criticality part -->
						<?php if($rright['ticket_criticality_mandatory']!=0) {if(($_POST['criticality']=="" && $_GET['action']=='new') || ($globalrow['criticality']=="" && $_GET['action']!='new') || ($globalrow['criticality']=="0")) {$criticality_error="has-error";} else {$criticality_error="";}}  else {$criticality_error="";} ?>
						<div class="form-group <?php echo $criticality_error; if($rright['ticket_criticality_disp']==0) echo 'hide';?>">
							<label  class="col-sm-2 control-label no-padding-right" for="criticality" >
							    <?php if($rright['ticket_criticality_mandatory']!=0) { if (($_POST['criticality']==0) && ($globalrow['criticality']==0)) {echo '<i title="La sélection d\'une criticité est obligatoire." class="icon-warning-sign red bigger-130"></i>&nbsp;';}} ?>
						    	<?php echo T_('Criticité'); ?>:
							</label>
							<div class="col-sm-8">
								<select  id="criticality" name="criticality" <?php if($rparameters['availability']==1) {echo 'onchange="loadVal(); submit();"';}  if($rright['ticket_criticality']==0) {echo 'disabled';}?>>
									<?php
									//if user limit service restrict criticality to associated service
									if($rparameters['user_limit_service']==1 && $rright['dashboard_service_only']!=0 )
									{
										if($_POST['u_service']) {$where=' service='.$_POST['u_service'].' ';} else {$where=' service='.$globalrow['u_service'].' ';}
										$old_criticality=1;
										$query2 = $db->query("SELECT * FROM `tcriticality` WHERE $where OR id=0 ORDER BY number DESC");
										while ($row2 = $query2->fetch()) {
											//select entry
											$selected='';
											if($_POST['criticality'] && $row2['id']==$_POST['criticality']) 
											{$selected='selected';}
											elseif($globalrow['criticality'] && $row2['id']==$globalrow['criticality']) 
											{$selected='selected';}
											if($globalrow['criticality']==$row2['id']) {$old_criticality=0;}
											echo '<option '.$selected.' value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
										}
										$query2->closeCursor(); 
										//keep old data
										if($old_priority==1 && $_GET['action']!='new') {
											$query2=$db->query("SELECT * FROM `tcriticality` WHERE id='$globalrow[criticality]'");
											$row2=$query2->fetch();
											$query2->closeCursor(); 
											echo '<option selected value="'.$row2['id'].'">'.T_($row2['name']).'</option>';
										}
										$selected_criticality=''; //init var
									} else { //case no service limit
										if ($_POST['criticality'])
										{
											//find row to select
											$query = $db->query("SELECT * FROM `tcriticality` WHERE id='$_POST[criticality]' ORDER BY number DESC");
											$row=$query->fetch();
											$query->closeCursor(); 
											echo '<option value="'.$_POST['criticality'].'" selected >'.T_($row['name']).'</option>';
											//display all entries without selected
											$selected_criticality=$_POST['criticality'];
											$query = $db->query("SELECT DISTINCT(id),name FROM `tcriticality` WHERE id!='$_POST[criticality]'  ORDER BY number DESC");
											while ($row = $query->fetch()) echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>'; 
											$query->closeCursor(); 
										}
										else
										{
											if($globalrow['criticality'])
											{
												//find row to select
												$query = $db->query("SELECT DISTINCT(id),name FROM `tcriticality` WHERE id LIKE '$globalrow[criticality]' ORDER BY number DESC ");
												$row=$query->fetch();
												$query->closeCursor(); 
												echo '<option value="'.$globalrow['criticality'].'" selected >'.T_($row['name']).'</option>';
												$selected_criticality=$globalrow['criticality'];
											} else {$selected_criticality='';}
											
											//display all entries without selected
											$query = $db->query("SELECT * FROM `tcriticality` WHERE id!='$globalrow[criticality]' ORDER BY number DESC");
											while ($row = $query->fetch()) echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>'; 
											$query->closeCursor(); 
										}			
									}
									?>			
								</select>
								<?php
								//send value in lock select case 
								if($rright['ticket_criticality']==0) {echo '<input type="hidden" name="criticality" value="'.$selected_criticality.'" />';}
								
								//display criticality icon
								if($_POST['criticality']) {$check_id=$_POST['criticality'];} else { $check_id=$globalrow['criticality'];}
								$query = $db->query("SELECT * FROM `tcriticality` WHERE id='$check_id'");
								$row=$query->fetch();
								$query->closeCursor(); 
								if ($row['name']) {echo '<i title="'.T_($row['name']).'" class="icon-bullhorn bigger-130" style="color:'.$row['color'].'" ></i>';}
								?>
							</div>
						</div>
						<!-- END criticality part -->
						<!-- START state part -->
						<div class="form-group <?php if($rright['ticket_state_disp']==0) echo 'hide';?>">
							<label class="col-sm-2 control-label no-padding-right" for="state"><?php echo T_('État'); ?>:</label>
							<div class="col-sm-8">
								<select  id="state"  name="state" <?php if($rright['ticket_state']==0 || $lock_tech==1) echo 'disabled';?> >	
									<?php
									//selected value
									if ($_POST['state'])
									{
										$query = $db->query("SELECT * FROM `tstates` WHERE id='$_POST[state]'");
										$row=$query->fetch();
										$query->closeCursor(); 
										echo '<option value="'.$_POST['state'].'" selected >'.T_($row['name']).'</option>';
										$selected_state=$_POST['state'];
									}
									else
									{
										$query = $db->query("SELECT * FROM `tstates` WHERE id='$globalrow[state]'");
										$row=$query->fetch();
										$query->closeCursor(); 
										echo '<option value="'.$globalrow['state'].'" selected >'.T_($row['name']).'</option>';
										$selected_state=$globalrow['state'];
									}			
							    	$query = $db->query("SELECT * FROM `tstates` WHERE id!='$_POST[state]' AND id!='$globalrow[state]' ORDER BY number");
								    while ($row = $query->fetch()) {
										if ($_SESSION['profile_id']==2 && $row['id']==3){}  //special case to hide resolve state for user only
										else {echo '<option value="'.$row['id'].'">'.T_($row['name']).'</option>';}
									} 
									$query->closeCursor(); 
									?>
								</select>
								<?php
								//send value in lock select case 
								if($rright['ticket_state']==0 || $lock_tech==1) {echo '<input type="hidden" name="state" value="'.$selected_state.'" />';}
								
								//display state icon
								$query = $db->query("SELECT * FROM `tstates` WHERE id LIKE '$globalrow[state]'");
								$row=$query->fetch();
								$query->closeCursor(); 
								echo '<span class="'.$row['display'].'" title="'.T_($row['description']).'">&nbsp;</span>';
								?>
							</div>
						</div>
						<!-- END state part -->
						<!-- START availability part --> 
						<?php
						//check if the availability parameter is on and condition parameter
						if($rparameters['availability']==1)
						{
						        if(
									($rparameters['availability_condition_type']=='criticality' && ($globalrow['criticality']==$rparameters['availability_condition_value'] || $_POST['criticality']==$rparameters['availability_condition_value']))
									||
									($rparameters['availability_condition_type']=='types' && ($globalrow['type']==$rparameters['availability_condition_value'] || $_POST['type']==$rparameters['availability_condition_value']))
								)
						        {    
						        	//calculate time
        					    	if ($globalrow['start_availability']!='0000-00-00 00:00:00' && $globalrow['end_availability']!='0000-00-00 00:00:00')
        					    	{
        					    	    $t1 =strtotime($globalrow['start_availability']) ;
                                        $t2 =strtotime($globalrow['end_availability']) ;
                                       	$time=(($t2-$t1)/60)/60;
                                       	$time="soit $time h";
        					    	} else $time='';
        					    	//explode selected date and hour
        					    	if ($_POST['start_availability_d'])
        					    	{
        					    	    $start_availability_d=$_POST['start_availability_d'];
        					    	    $start_availability_h=$_POST['start_availability_h'];
        					    	} elseif ($globalrow['start_availability']!='0000-00-00 00:00:00') 
        					    	{
        					    	    $start_availability_d=date("d/m/Y",strtotime($globalrow['start_availability']));
        					    	    $start_availability_h=date("G:i:s",strtotime($globalrow['start_availability']));
        					    	} else {
        					    	    $start_availability_d=date("d/m/Y");
        					    	    $start_availability_h=date("H:i:s");
        					    	}
        					    	
        					    	if ($_POST['end_availability_d'])
        					    	{
        					    	    $end_availability_d=$_POST['end_availability_d'];
        					    	    $end_availability_h=$_POST['end_availability_h'];
        					    	} else
        					    	if ($globalrow['start_availability']!='0000-00-00 00:00:00') {
        					    	    $end_availability_d=date("d/m/Y",strtotime($globalrow['end_availability']));
        					    	    $end_availability_h=date("G:i:s",strtotime($globalrow['end_availability']));
        					    	} else {
        					    	    $end_availability_d=date("d/m/Y");
        					    	    $end_availability_h=date("H:i:s");
        					    	}
        						    echo'
        						   	<div class="form-group '; if($rright['ticket_availability_disp']==0) echo 'hide'; echo '">
        						    	<label class="col-sm-2 control-label no-padding-right" for="start_availability_d">Début de l\'indisponibilité:</label>
        						    	<div class="col-sm-8">
            						    	<input  type="text" id="start_availability_d" name="start_availability_d"  value="'.$start_availability_d.'"';                							    	    echo '"';
                							    	    if($rright['ticket_availability']==0) echo ' readonly="readonly" ';
                							echo '
                							>
        						    	    <div class="bootstrap-timepicker">
									        	<input id="start_availability_h" name="start_availability_h" value="'.$start_availability_h.'" type="text"  />
							    	        </div>	
        						    	</div>
        					    	</div>
        					    	<div class="form-group '; if($rright['ticket_availability_disp']==0) echo 'hide'; echo '">
        						    	<label class="col-sm-2 control-label no-padding-right" for="end_availability_d">Fin de l\'indisponibilité:</label>
        						    	<div class="col-sm-8">
        							    	<input  type="text" id="end_availability_d" name="end_availability_d"  value="'.$end_availability_d.'"';
        							    	    if($rright['ticket_availability']==0) echo ' readonly="readonly" ';
        							    	echo '
        							    	>
        							        <div class="bootstrap-timepicker">
									        	<input id="end_availability_h" name="end_availability_h" value="'.$end_availability_h.'" type="text"  />
							                </div>
							                '.$time.'
							             </div>
        						    </div>
        					    	<div class="form-group '; if($rright['ticket_availability_disp']==0) echo 'hide'; echo '">
        					    		<label class="col-sm-2 control-label no-padding-right" for="availability_planned">Indisponibilité planifiée:</label>
        					    		<div class="col-sm-8">
        					    			<input type="checkbox"'; if ($globalrow['availability_planned']==1) {echo "checked";} echo ' name="availability_planned" value="1" />
        					    		</div>
        					    	</div>
        					    	';
						        }
						}
						?>
						<!-- END availability part -->
						<div class="form-actions center">
							<?php
							if (($rright['ticket_save']!=0 && $_GET['action']!='new') || ($rright['ticket_new_save']!=0 && $_GET['action']=='new'))
							{
								echo '
								<button title="ALT+SHIFT+s" accesskey="s" name="modify" id="modify" value="modify" type="submit" class="btn btn-sm btn-success">
									<i class="icon-save icon-on-right bigger-110"></i> 
									&nbsp;'.T_('Enregistrer').'
								</button>
								&nbsp;
								';
								if($mobile==1) {echo '<br /><br />';}
							}
							if ($rright['ticket_save_close']!=0)
							{
								echo '
								<button title="ALT+SHIFT+f" accesskey="f" name="quit" id="quit" value="quit" type="submit" class="btn btn-sm btn-purple">
									<i class="icon-save icon-on-right bigger-110"></i> 
									&nbsp;'.T_('Enregistrer et Fermer').'
								</button>
								&nbsp;
								';
								if($mobile==1) {echo '<br /><br />';}
							}
							if ($rright['ticket_new_send']!=0 && $_GET['action']=='new')
							{
								echo '
								<button name="send" id="send" value="send" type="submit" class="btn btn-sm btn-success">
									'.T_('Envoyer').'
									&nbsp;<i class="icon-arrow-right icon-on-right bigger-110"></i> 
								</button>
								&nbsp;
								';
								if($mobile==1) {echo '<br /><br />';}
							}
							if ($rright['ticket_close']!=0 && $_POST['state']!='3' && $globalrow['state']!='3' && $_GET['action']!='new' && $lock_tech==0)
							{
								echo '
								<button name="close" id="close" value="close" type="submit" class="btn btn-sm btn-grey">
									<i class="icon-ok icon-on-right bigger-110"></i> 
									&nbsp;'.T_('Clôturer le ticket').'
								</button>
								&nbsp;
								';
								if($mobile==1) {echo '<br /><br />';}
							}
							if ($rright['ticket_send_mail']!=0)
							{
								echo '
								<button title="ALT+SHIFT+m" accesskey="m" name="mail" id="mail" value="mail" type="submit" class="btn btn-sm btn-primary">
									<i class="icon-envelope icon-on-right bigger-110"></i> 
									&nbsp;'.T_('Envoyer un mail').'
								</button>
								&nbsp;
								';
								if($mobile==1) {echo '<br /><br />';}
							}
							if ($rright['ticket_cancel']!=0)
							{
								echo '
								<button title="ALT+SHIFT+c" accesskey="c" name="cancel" id="cancel" value="cancel" type="submit" class="btn btn-sm btn-danger">
									<i class="icon-remove icon-on-right bigger-110"></i> 
									&nbsp;'.T_('Annuler').'
								</button>
								';
							}
							?>
						</div>
					</div>
				</div> <!-- div widget body -->
			</form>
		</div> <!-- div end sm -->
	</div> <!-- div end x12 -->
</div> <!-- div end row -->

<?php include ('./wysiwyg.php'); ?>

<!-- date picker script -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>
<script src="template/assets/js/date-time/bootstrap-timepicker.min.js" charset="UTF-8"></script>
<script type="text/javascript">
jQuery(function($) {
    
    	$('#start_availability_h').timepicker({
    	        minuteStep: 1,
				showSeconds: true,
				showMeridian: false
			});
		$('#end_availability_h').timepicker({
	        minuteStep: 1,
			showSeconds: true,
			showMeridian: false
		});
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

			if($rright['ticket_date_create']!=0)
			{
				echo '
				$( "#date_create" ).datepicker({ 
				dateFormat: \'dd/mm/yy\',
					onSelect: function(datetext){
					var d = new Date(); // for now
					datetext=datetext+" "+"00:00:00";
					$(\'#date_create\').val(datetext);
					},
				});
				';
			}
			if($rright['ticket_date_res']!=0)
			{
				echo '
				$( "#date_res" ).datepicker({ 
					dateFormat: \'dd/mm/yy\',
					onSelect: function(datetext){
					var d = new Date(); // for now
					datetext=datetext+" "+(\'0\'+d.getHours()).slice(-2)+":"+(\'0\'+d.getMinutes()).slice(-2)+":"+(\'0\'+d.getSeconds()).slice(-2);  
					$(\'#date_res\').val(datetext);
					},
				}); 
				';
			}
			if($rright['ticket_date_hope']!=0)
			{
				echo '
				$( "#date_hope" ).datepicker({ 
					dateFormat: \'dd/mm/yy\'
				}); 
				';
			}
		?>
		$( "#start_availability_d" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
		$( "#end_availability_d" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
	});		
</script>		