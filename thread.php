<?php
################################################################################
# @Name : thread.php
# @Call: ticket.php
# @Description : display tickets thread
# @Author : Flox
# @Create : 27/01/2013
# @Update : 04/12/2017
# @Version : 3.1.28
################################################################################

// initialize variables 
if(!isset($_GET['threaddelete'])) $_GET['threaddelete'] = ''; 
if(!isset($_GET['threadedit'])) $_GET['threadedit'] = ''; 
if(!isset($rcreator['firstname'])) $rcreator['firstname']= ''; 
if(!isset($rcreator['lastname'])) $rcreator['lastname']= ''; 

$db_threaddelete=strip_tags($db->quote($_GET['threaddelete']));
$db_threadedit=strip_tags($db->quote($_GET['threadedit']));
$db_id=strip_tags($db->quote($_GET['id']));

///// actions for threads

//thread delete
if ($_GET['threaddelete']!='' && $rright['ticket_thread_delete']!=0)
{
	$db->exec("DELETE FROM tthreads WHERE id=$db_threaddelete");
} 

//call date conversion function from index
$date_start=date_convert($globalrow['date_create']);

//find firstname et lastname of creator
if ($globalrow['creator']!='')
{
    $query = $db->query('SELECT * FROM tusers WHERE id='.$globalrow['creator'].' AND disable=0');
    $rcreator=$query->fetch();
	$query->closeCursor();
}

//display time line
if($_GET['action']!='new') //case for edit ticket not new ticket
{
	echo '
    <table '; if($mobile==0) {echo 'border="1" style="border: 1px solid #D8D8D8;"';} echo ' CELLPADDING="15">
		<tr>
			<td>
				<div id="timeline-1">
					<div class="row">
							<div class="timeline-container">
								<div class="timeline-items">
									<div class="timeline-item clearfix">
											<div class="timeline-label">
												<span class="label label-primary arrowed-in-right label-lg">
													';
														//compress text for mobile display
														if($mobile==1)
														{
															echo '<i class="icon-circle"></i> '.$date_start.': <b>'.T_('Ouverture').'</b>';
														} else {
															echo '<i class="icon-circle"></i> '.$date_start.': <b>'.T_('Ouverture').'</b> '.T_('du ticket').' <span style="font-size: x-small;">('.T_('Effectué par').' '.$rcreator['firstname'].' '.$rcreator['lastname'].')</span>';
														}
													echo'
												</span>
											</div>
										<div class="timeline-items">';
											$query = $db->query('SELECT * FROM tthreads WHERE ticket='.$db_id.' ORDER BY date');
											while ($row = $query->fetch()) 
											{
												////for each type of thread display line
												
												//call date conversion function
												$date_thread=date_convert($row['date']);
												
												//author name
												$query2=$db->query("SELECT * FROM tusers WHERE id='$row[author]'");
												$ruser=$query2->fetch();
												$query2->closeCursor(); 
												
												//state name
												$query2=$db->query("SELECT name FROM tstates WHERE id='$row[state]'");
												$rstate=$query2->fetch();
												$query2->closeCursor(); 
												
												//find author profile
												$query2=$db->query("SELECT tprofiles.img FROM tprofiles,tusers WHERE tusers.profile=tprofiles.level and tusers.id=$row[author]");
												$ruserprofile=$query2->fetch();
												$query2->closeCursor(); 
												
												//if it's text message
												if ($row['type']==0)
												{
													//check if user have right to read thread case of private message
													if ($row['private']==0 || $rright['ticket_thread_private']!=0)
													{
													echo '
														<div class="timeline-item clearfix">
															<div class="timeline-info">
																<img title="'.$ruser['firstname'].' '.$ruser['lastname'].'" alt="avatar" src="./images/avatar/'.$ruserprofile[0].'">
															</div>
															<div class="widget-box transparent">
																<div class="widget-header widget-header-small hidden"></div>
																<div class="widget-header widget-header-small">
																	<h5 class="smaller">
																		<a href="#" class="blue"><i class="icon-user bigger-110"></i> '; if($mobile==0) {echo $ruser['firstname'];} echo ' '.$ruser['lastname'].'</a>&nbsp;
																			';
																			//compress text for mobile display
																			if($mobile==0)
																			{
																			echo '<span class="grey"><i class="icon-time bigger-110"></i> '.$date_thread.'</span>';
																			}
																			echo '
																	</h5>
																	<span class="widget-toolbar">
																		<a title="'.T_('Réduire').'" href="#" data-action="collapse">
																			<i class="icon-chevron-up"></i>
																		</a>
																		&nbsp;
																		';
																		//private message actions
																		if ($rright['ticket_thread_private']!=0) {
																			if ($row['private']==1)
																			{
																				echo '<a href="'.basename($_SERVER['REQUEST_URI']).'&unlock_thread='.$row['id'].'#down"><i title="'.T_('Message non visible pour le demandeur').'" class="icon-eye-close red bigger-130"></i></a>&nbsp;';
																			} else {
																				echo '<a href="'.basename($_SERVER['REQUEST_URI']).'&lock_thread='.$row['id'].'#down"><i title="'.T_('Message visible pour le demandeur').'" class="icon-eye-open green bigger-130"></i></a>&nbsp;';																			
																			}
																		} 
																		//check your own tickets
																		if($row['author']==$_SESSION['user_id']) 
																		{
																			if ($rright['ticket_thread_edit']!=0) echo '<a href="./index.php?page=ticket&id='.$_GET['id'].'&threadedit='.$row['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&category='.$_GET['category'].'&subcat='.$_GET['subcat'].'&viewid='.$_GET['viewid'].'#down"><i title="'.T_('Modifier').'" class="icon-pencil orange bigger-130"></i></a>&nbsp;';
																		} else  {
																			if ($rright['ticket_thread_edit_all']!=0) echo '<a href="./index.php?page=ticket&id='.$_GET['id'].'&threadedit='.$row['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&category='.$_GET['category'].'&subcat='.$_GET['subcat'].'&viewid='.$_GET['viewid'].'#down"><i title="'.T_('Modifier').'" class="icon-pencil orange bigger-130"></i></a>&nbsp;';
																		}
																		if ($rright['ticket_thread_delete']!=0) echo '<a href="./index.php?page=ticket&id='.$_GET['id'].'&threaddelete='.$row['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&category='.$_GET['category'].'&subcat='.$_GET['subcat'].'&viewid='.$_GET['viewid'].'#down"><i title="'.T_('Supprimer').'" class="icon-remove red bigger-130"></i></a>';
																		echo '
																	</span>
																</div>
																';
																//detect <br> for wysiwyg transition from 2.9 to 3.0
																$findbr=stripos($row['text'], '<br>');
																if ($findbr === false) {$threadtext=nl2br($row['text']);} else {$threadtext=$row['text'];}
																//insert html link if http is detected in text
																if (preg_match('#http://#',$threadtext) || preg_match('#https://#',$threadtext))
																{
																	$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
																	$threadtext = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $threadtext);
																}
																echo '
																<div class="widget-body">
																	<div class="widget-main">'.$threadtext.'</div>
																</div>
															</div>
														</div>
														';
													}
												}
												//if it's attribution type
												if ($row['type']==1)
												{
													if ($row['group1'])
													{
														//find group name 
														$query2=$db->query("SELECT * FROM tgroups WHERE id='$row[group1]'");
														$rgroup=$query2->fetch();
														$query2->closeCursor();
														$name=T_('au groupe').' <b>'.$rgroup['name'].'</b>';
													} else {
														//find technician name 
														$query2=$db->query("SELECT * FROM tusers WHERE id='$row[tech1]'");
														$rtech=$query2->fetch();
														$query2->closeCursor(); 
														if ($rtech['lastname']!='')
														{
															$name=T_('au technicien ').' <b>'.$rtech['firstname'].' '.T_($rtech['lastname']).'</b>';
														} else {
															$name=T_('au technicien ').' <b>'.$rtech['firstname'].' '.$rtech['lastname'].'</b>';
														}
													}
													echo '
													<div class="timeline-label">
														<span class="label label-purple arrowed-in-right label-lg">
															';
															//compress text for mobile display
															if($mobile==1)
															{
																echo'<i class="icon-user"></i> '.$date_thread.': <b>'.T_('Attribution').'</b>';
															} else {	
																echo'<i class="icon-user"></i> '.$date_thread.': <b>'.T_('Attribution').'</b> '.T_('du ticket').' '.T_($name).'  <span style="font-size: x-small;">('.T_('Effectué par').'  '.$ruser['firstname'].' '.$ruser['lastname'].')</span>';
															}
															echo '
														</span>
													</div>
													';
												}
												//if it's transfert type
												if ($row['type']==2)
												{
													//find technician group name 
													$query2=$db->query("SELECT * FROM tgroups WHERE id='$row[group1]'");
													$rgroup1=$query2->fetch();
													$query2->closeCursor(); 
													$query2=$db->query("SELECT * FROM tgroups WHERE id='$row[group2]'");
													$rgroup2=$query2->fetch();
													$query2->closeCursor(); 
													//find technicians name
													$query2=$db->query("SELECT CONCAT_WS('. ', left(tusers.firstname, 1),  tusers.lastname) as name FROM tusers WHERE id='$row[tech1]' AND id!='0'");
													$rtech1=$query2->fetch();
													$query2->closeCursor();
													$query2=$db->query("SELECT CONCAT_WS('. ', left(tusers.firstname, 1),  tusers.lastname) as name FROM tusers WHERE id='$row[tech2]' AND id!='0'");
													$rtech2=$query2->fetch();
													$query2->closeCursor();
													$dispname=T_('de').' <b>'.$rtech1['name'].$rgroup1['name'].'</b> '.T_('vers').' <b>'.$rtech2['name'].$rgroup2['name'].'</b>';
													echo '
													<div class="timeline-label">
														<span class="label label-yellow arrowed-in-right label-lg">
															<i class="icon-exchange"></i> '.$date_thread.': <b>'.T_('Transfert').'</b> '.T_('du ticket').' '.$dispname.'  <span style="font-size: x-small;">('.T_('Effectué par').'  '.$ruser['firstname'].' '.$ruser['lastname'].')</span>
														</span>
													</div>
													';
												}
												//if it's mails type
												if ($row['type']==3)
												{
													//find technicians name 
													$query2=$db->query("SELECT * FROM tusers WHERE id='$row[tech1]'");
													$rtech1=$query2->fetch();
													$query2->closeCursor();
													$query2=$db->query("SELECT * FROM tusers WHERE id='$row[tech2]'");
													$rtech2=$query2->fetch();
													$query2->closeCursor();
													echo '
													<div class="timeline-label">
														<span class="label label-grey arrowed-in-right label-lg">
														';
															//compress text for mobile display
															if($mobile==1)
															{
																echo '<i class="icon-envelope"></i> '.$date_thread.': <b>'.T_('Envoi de mail').'</b>';
															} else {
																echo '<i class="icon-envelope"></i> '.$date_thread.': <b>'.T_('Envoi de mail').'</b> <span style="font-size: x-small;">('.T_('Effectué par').'  '.$ruser['firstname'].' '.$ruser['lastname'].')</span>';
															}
															echo '
														</span>
													</div>
													';
												}
												//if it's close type
												if ($row['type']==4)
												{
													echo '
													<div class="timeline-label">
														<span class="label label-success arrowed-in-right label-lg">
														';
															//compress text for mobile display
															if($mobile==1)
															{
																echo '<i class="icon-ok"></i> '.$date_thread.': <b>'.T_('Clôture').'</b>';
															} else {
																echo '<i class="icon-ok"></i> '.$date_thread.': <b>'.T_('Clôture').'</b> <span style="font-size: x-small;">('.T_('Effectué par').'  '.$ruser['firstname'].' '.$ruser['lastname'].')</span>';
															}
															echo '
														</span>
													</div>
													';
												}
												//if it's switch state type
												if ($row['type']==5)
												{
													echo '
													<div class="timeline-label">
														<span class="label label-light arrowed-in-right label-lg">
														';
															//compress text for mobile display
															if($mobile==1)
															{
																echo '<i class="icon-adjust"></i> '.$date_thread.': <b>'.T_('Modif. état').'</b>';
															} else {
																echo '<i class="icon-adjust"></i> '.$date_thread.': <b>'.T_('Changement d\'état').'</b> '.T_($rstate['name']).' <span style="font-size: x-small;">('.T_('Effectué par').'  '.$ruser['firstname'].' '.$ruser['lastname'].')</span>';
															}
															echo '
														</span>
													</div>
													';
												}
											}
											echo '
										</div>
										
									</div><!-- /.timeline-items -->	
								</div><!-- /.timeline-items -->
							</div><!-- /.timeline-container -->
					
					</div>
				</div>
				';
}
				if ($rright['ticket_thread_add']!=0)
				{
					//display text input
					if($_GET['action']!='new') //query only in edit ticket mode to display new ticket faster
					{
						$query=$db->query("SELECT text FROM `tthreads` WHERE id=$db_threadedit");
						$row=$query->fetch();
						$query->closeCursor();
					}
					//find name for submit button
					if ($mobile==0)
					{
						if($_GET['threadedit']) $button=T_('Modifier'); else $button=T_('Ajouter');
					} else {$button='';}
					//detect <br> for wysiwyg transition from 2.9 to 3.0
					$findbr=stripos($row[0], '<br>');
					if ($findbr === false) {$text=nl2br($row[0]);} else {$text=$row[0];}
					echo '
					<table border="0" width="';if($mobile==0) {echo '732';} else {echo '285';} echo '" >
						<tr>
							<td>
								<table border="1" style="border: 1px solid #D8D8D8;" >
									<tr>
										<td>
											<div id="editor2" class="wysiwyg-editor" style="min-height:80px;">';
										    	if($_POST['text2']!='') {echo $_POST['text2'];} elseif($text) {echo "	$text";} else {echo "";}
											echo '</div>
											<input type="hidden" name="text2" />
										</td>
									</tr>
								</table>
							</td>
							<td>	
								';
								//add button for private message, case to send auto mail when technician add resolution
								if (($rright['ticket_thread_private_button']!=0) && ($rparameters['mail_auto_user_modify']!=0))
								{
									echo '
									<label><input type="checkbox" id="private" name="private" value="1" class="ace"><span class="lbl">&nbsp;Privé</span></label>
									<i class="icon-question-sign blue bigger-110" title="Le demandeur ne recevra pas de mail concernant ce message."></i>
									<br><br>
									';
								}
								echo '&nbsp;&nbsp;<button class="btn btn-sm btn-success" title="'.$button.'" name="modify" value="modify" type="submit" id="modify">'.$button.' <i class="icon-arrow-right icon-on-right"></i></button>';
								echo '
							</td>
						</tr>
					</table>
					';
				}
if($_GET['action']!='new') 
{
			echo '
			</td>
		</tr>
	</table>
	';	
}
?>