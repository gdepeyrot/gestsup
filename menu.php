<?php
################################################################################
# @Name : /menu.php
# @Description : display left panel menu
# @Call : /index.php
# @Parameters : 
# @Author : Flox
# @Create : 06/09/2013
# @Update : 14/09/2017
# @Version : 3.1.26
################################################################################

//initialize variables 
if(!isset($_GET['viewid'])) $_GET['viewid'] = '';
if(!isset($_GET['userid'])) $_GET['userid'] = ''; 
if(!isset($_GET['state'])) $_GET['state'] = ''; 
if(!isset($_GET['techread'])) $_GET['techread'] = ''; 
if(!isset($_GET['companyview'])) $_GET['companyview'] = ''; 
if(!isset($_GET['warranty'])) $_GET['warranty'] = ''; 
if(!isset($_GET['techgroup'])) $_GET['techgroup'] = ''; 
if(!isset($state)) $state = ''; 
?>
<div class="sidebar" id="sidebar">
	<script type="text/javascript">
		try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
	</script>
	<div class="sidebar-shortcuts" id="sidebar-shortcuts">
		<?php
		if (($rright['side_open_ticket']!=0) && (($_GET['page']!='asset_list') || ($rright['side_asset_create']==0)) && ($_GET['page']!='asset')  && ($_GET['page']!='procedure'))
		{
			if ($ruser['default_ticket_state']!='')
			{
				if ($ruser['default_ticket_state']=='meta_all')
				{
					$target_url='./index.php?page=ticket&amp;action=new&amp;userid=%&amp;state=meta&view='.$_GET['view'].'&date_start='.$_GET['date_start'].'&date_end='.$_GET['date_end'];
				} else {
					$target_url='./index.php?page=ticket&amp;action=new&amp;userid='.$_SESSION['user_id'].'&amp;state='.$ruser['default_ticket_state'].'&view='.$_GET['view'].'&date_start='.$_GET['date_start'].'&date_end='.$_GET['date_end'];
				}
			} else {
				$target_url='./index.php?page=ticket&amp;action=new&amp;userid='.$_SESSION['user_id'].'&amp;state='.$_GET['state'].'&view='.$_GET['view'].'&date_start='.$_GET['date_start'].'&date_end='.$_GET['date_end'];
			}
			echo'
			<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<a href="'.$target_url.'">
						<button accesskey="n" title="'.T_("Ajoute un nouveau ticket").' (SHIFT+ALT+n)" onclick=\'window.location.href="'.$target_url.'"\' class="btn btn-sm btn-success">
							&nbsp;
							<i class="icon-plus bigger-120"></i> '.T_('Nouveau ticket').'
						</button>
					</a>
			</div>
			<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
				<a href="'.$target_url.'"><span class="btn btn-success"></span></a>
			</div>
			';
		}
		if (($rright['side_asset_create']!=0) && ($rparameters['asset']==1) && (($_GET['page']=='asset_list') || ($_GET['page']=='asset') ) && ($_GET['state']!='1'))
		{
			echo'
			<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<a href="./index.php?page=asset&amp;action=new">
						<button accesskey="n" title="'.T_('Ajoute un nouvel équipement').' (SHIFT+ALT+n)" onclick=\'window.location.href="./index.php?page=asset&amp;action=new"\' class="btn btn-sm btn-success">
							&nbsp;
							<i class="icon-plus bigger-120"></i> '.T_('Nouvel équipement').'
						</button>
					</a>
			</div>
			<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
				<a href="./index.php?page=asset&amp;action=new"><span class="btn btn-success"></span></a>
			</div>
			';
		}
		if (($rright['side_asset_create']!=0) && ($rparameters['asset']==1) && (($_GET['page']=='asset_list') || ($_GET['page']=='asset') ) && ($_GET['state']=='1'))
		{
			echo'
			<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<a href="./index.php?page=asset_stock">
						<button title="'.T_('Permet l\'ajout de plusieurs équipements à la fois').'" onclick=\'window.location.href="./index.php?page=asset_stock"\' class="btn btn-sm btn-warning">
							&nbsp;
							<i class="icon-plus bigger-120"></i> '.T_('Ajouter un lot').'
						</button>
					</a>
			</div>
			<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
				<a href="./index.php?page=asset_stock"><span class="btn btn-warning"></span></a>
			</div>
			';
		}
		if (($rright['procedure_add']!=0) && ($_GET['page']=='procedure'))
		{
			echo'
			<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<a href="index.php?page=procedure&amp;action=add">
						<button accesskey="n" title="'.T_('Ajoute une nouvelle procédure').' (SHIFT+ALT+n)" class="btn btn-sm btn-success">
							&nbsp;
							<i class="icon-plus bigger-120"></i> '.T_('Nouvelle procédure').'
						</button>
					</a>
			</div>
			<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
				<a href="index.php?page=procedure&amp;action=add"><span class="btn btn-success"></span></a>
			</div>
			';
		}
		?>
	</div>
	<!--#sidebar-shortcuts-->
	<ul class="nav nav-list">
		<?php
		//display tickets of current user
		if ($rright['side_your']!=0)
		{
			//special case to count technician ticket, included ticket where technician is sender 
			if($_SESSION['profile_id']==0 && $rparameters['user_limit_service']==1 && $_GET['userid']!='%')
			{
				$where_profil="(user='$uid' OR technician='$uid')";
			} else {
				$where_profil="$profile='$uid'";
			}
			$query="SELECT count(*) FROM `tincidents` WHERE $where_profil $where_service_your $where_agency_your AND disable='0'";
			$query=$db->query($query);
			$cntall=$query->fetch();
			$query->closeCursor(); 
			
			echo "<li "; if(($_GET['page']=='dashboard' || $_GET['page']=='ticket') && $_GET['userid']!='%' && $_GET['userid']!='0') {echo 'class="active"';} echo ">
				<a href=\"./index.php?page=dashboard&amp;userid=$_SESSION[user_id]&amp;state=%\" class=\"dropdown-toggle\" >
					<i class=\"icon-ticket\"></i>
					<span class=\"menu-text\">
						"; echo T_('Vos tickets');
							if ($cnt3[0]>0 && $rright['side_your_not_read']!=0) echo '<span class="badge badge-transparent tooltip-error" title="" data-original-title="'.$cnt3[0].' Non lus"><i title="'.T_('Tickets non lus sont en attente').'" class="icon-warning-sign light-orange bigger-130"></i></span>';
						echo " 
					</span>
					<b class=\"arrow icon-angle-down\"></b>
				</a>
				
				<ul class=\"submenu\" >";
				    //display all states link
					if ($_GET['userid']!='%' && $_GET['state']=='%') {echo '<li class="active">';} else {echo "<li>";} echo "
						    <a href=\"./index.php?page=dashboard&amp;userid=$_SESSION[user_id]&amp;state=%&amp;ticket=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%\">
							<i class=\"icon-double-angle-right\"></i>
							"; echo T_('Tous les états'); echo " ($cntall[0])
						</a>
					</li>";
					 //display meta states link
					if ($rparameters['meta_state']==1 && $rright['side_your_meta']!=0)
					{
						$query=$db->query("SELECT count(*) FROM `tincidents` WHERE $where_profil $where_service_your $where_agency_your AND disable='0' AND (state=1 OR state=2 OR state=6)");
						$cntmeta=$query->fetch();
						$query->closeCursor();  
    					if ($_GET['userid']!='%' && $_GET['state']=='meta') {echo '<li class="active">';} else {echo "<li>";} echo "
    						<a title=\" "; echo T_('Meta-état regroupant les états: Attente de PEC, En cours, et Attente de retour.'); echo "\" href=\"./index.php?page=dashboard&amp;userid=$_SESSION[user_id]&amp;state=meta&amp;ticket=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%\">
    							<i class=\"icon-double-angle-right\"></i>
    							"; echo T_('A traiter '); echo "($cntmeta[0])
    						</a>
    					</li>";
					}
					//display unread ticket
					if ($cnt3[0]>0 && $rright['side_your_not_read']!=0)
					{
						if ($_GET['techread']!='' && $_GET['page']!='searchengine') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;techread=0">
								<i class="icon-double-angle-right"></i>
								'.T_('Non lus').' ('.$cnt3[0].')&nbsp;&nbsp;&nbsp;<i title="'.T_('Tickets non lus sont en attente').'" class="icon-warning-sign light-orange bigger-130"></i>
							</a>
						</li>';
						
					}
					//for each state display in sub-menu
					$query = $db->query("SELECT * FROM `tstates` WHERE id NOT LIKE 5 ORDER BY number");
					while ($row = $query->fetch())
					{
						$query2=$db->query("SELECT count(id) FROM `tincidents` WHERE $where_profil $where_service_your $where_agency_your AND state='$row[id]' AND disable='0'");
						$cnt=$query2->fetch();
						$query2->closeCursor(); 
						echo '
						<li';  
						if ($_GET['userid']!='%' && $_GET['state']==$row['id']) echo ' class="active"';
						echo '>
							<a title="'.T_($row['description']).'" href="./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;state='.$row['id'].'&amp;ticket=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%">
								<i class="icon-double-angle-right"></i>
								'.T_($row['name']).' ('.$cnt[0].') 
							</a>
						</li>';
					}
					$query->closeCursor();
					//display technician group ticket
					if ($rright['side_your_tech_group']!=0 && ($_SESSION['profile_id']==4 || $_SESSION['profile_id']==0 || $_SESSION['profile_id']==3) )
					{
						//check if technician have group
						$query=$db->query("SELECT `group` FROM `tgroups_assoc` WHERE user='$uid'");
						while ($row = $query->fetch())
						{
							//count number of tickets present in this group
							$query2=$db->query("SELECT count(id) FROM `tincidents` WHERE t_group=$row[group] AND disable='0' ");
							$cntgrp=$query2->fetch();
							$query2->closeCursor();  
							//get group name
							$query2=$db->query("SELECT `name` FROM `tgroups` WHERE id='$row[group]'");
							$group_name=$query2->fetch();
							$query2->closeCursor(); 
							if ($row['group']==$_GET['techgroup']) echo '<li class="active">'; else echo '<li>'; echo '
								<a href="./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;techgroup='.$row['group'].'">
									<i class="icon-double-angle-right"></i>
									[G] '.$group_name['name'].' ('.$cntgrp[0].')
								</a>
							</li>';
						}
						$query->closeCursor(); 
					}
					echo "
				</ul>
			</li>
			";
		}
		
		//display side menu for company view, all tickets of current connected user company
		if ($rparameters['user_company_view']==1 && $rright['side_company']!=0 && $ruser['company']!=0)
		{
			//count all company tickets
			$query=$db->query("SELECT count(*) FROM `tincidents`,`tusers` WHERE tincidents.user=tusers.id AND tincidents.disable='0' AND tusers.company='$ruser[company]' AND tincidents.disable='0'");
			$cntall=$query->fetch();
			$query->closeCursor(); 
			//count all ticket not attribute of current user company
			$query=$db->query("SELECT count(*) FROM tincidents, tusers WHERE tincidents.user=tusers.id AND tusers.company=$ruser[company] AND technician='0' AND t_group='0' AND tincidents.disable='0'");
			$cnt6=$query->fetch();
			$query->closeCursor(); 
			if ($_GET['page']=='dashboard' && ($_GET['userid']=='%' || $_GET['userid']=='0') && $_GET['viewid']=='' && $_GET['companyview']!='') echo '<li  class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=dashboard&amp;userid=%&amp;state=%" class="dropdown-toggle">
					<i class="icon-ticket"></i>
						<span class="menu-text"> 
							'.T_('Ma société').'
						</span>
						<b class="arrow icon-angle-down"></b>
				</a>
				<ul class="submenu" >';
					if ($_GET['page']=='dashboard' && $_GET['userid']=='%' && $_GET['state']=='%') echo '<li class="active">'; else echo '<li>'; echo '
						<a href="./index.php?page=dashboard&amp;userid=%&amp;state=%&amp;ticket=%&amp;technician=%&amp;user=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;companyview=1">
							<i class="icon-double-angle-right"></i>
							'.T_('Tous les états').' ('.$cntall[0].')
						</a>
					</li>';
					 //display meta  states link
					if ($rparameters['meta_state']==1  && $rright['side_all_meta']!=0)
					{
						$query=$db->query("SELECT count(*) FROM `tincidents`,`tusers` WHERE tincidents.user=tusers.id AND tincidents.disable='0' AND (tincidents.state=1 OR tincidents.state=2 OR tincidents.state=6)AND tusers.company='$ruser[company]'");
						$cntmetaall=$query->fetch();
						$query->closeCursor(); 
    					if ($_GET['page']=='dashboard' && $_GET['userid']=='%' && $_GET['state']=='meta') {echo '<li class="active">';} else {echo "<li>";} echo "
    						<a title=\"Meta-état regroupant les états: Attente de PEC, En cours, et Attente de retour.\" href=\"./index.php?page=dashboard&amp;userid=%&amp;state=meta&amp;ticket=%&amp;technician=%&amp;user=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;companyview=1\">
    							<i class=\"icon-double-angle-right\"></i>
    							"; echo T_('A traiter'); echo " ($cntmetaall[0])
    						</a>
    					</li>";
					}	
					//for each state display in sub-menu
					$query = $db->query("SELECT * FROM `tstates` WHERE id not like 5 ORDER BY number");
					while ($row = $query->fetch())
					{
						$query2=$db->query("SELECT count(*) FROM `tincidents`,`tusers`  WHERE tincidents.user=tusers.id AND state LIKE '$row[id]' AND tusers.company='$ruser[company]' AND tincidents.disable='0'");
						$cnt=$query2->fetch();
						$query2->closeCursor(); 
						echo '
						<li';  
						if ($_GET['page']=='dashboard' && $_GET['userid']=='%' && $_GET['state']==$row['id']) echo ' class="active"';
						echo '>
							<a title="'.T_($row['description']).'" href="./index.php?page=dashboard&amp;userid=%&amp;state='.$row['id'].'&amp;ticket=%&amp;technician=%&amp;user=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;companyview=1">
								<i class="icon-double-angle-right"></i>
								'.T_($row['name']).' ('.$cnt[0].')
							</a>
						</li>';
					}
					$query->closeCursor(); 
					echo'
				</ul>
			</li>';
		}
		//display side menu for all tickets of current connected user
		if (($rright['side_all']!=0 && $rparameters['user_limit_service']==0) || ($rright['side_all']!=0 && $rparameters['user_limit_service']==1 && ($cnt_service!=0 || $cnt_agency!=0)) || ($rright['side_all']!=0 && $rparameters['user_limit_service']==1 && $rright['admin'])) //not display all tickets for supervisor without service or agency, whithout user_limit_service tech must view all tickets
		{
			$query=
			$query=$db->query("SELECT count(*) FROM `tincidents` WHERE disable='0' $where_agency $where_service $parenthese2");
			$cntall=$query->fetch();
			$query->closeCursor(); 
			if (($_GET['userid']=='%' || $_GET['userid']=='0') && $_GET['viewid']=='' && $_GET['companyview']=='') echo '<li  class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=dashboard&amp;userid=%&amp;state=2" class="dropdown-toggle">
					<i class="icon-ticket"></i>
						<span class="menu-text"> 
							'.T_('Tous les tickets');
								if ($cnt5[0]>0 && $rright['side_your_not_attribute']!=0) echo '<span class="badge badge-transparent tooltip-error" title="" data-original-title="'.$cnt5[0].'&nbsp;Nouveaux&nbsp;tickets"><i title="De nouveaux tickets sont à attribuer" class="icon-warning-sign red bigger-130"></i></span>';
							echo '
						</span>
						<b class="arrow icon-angle-down"></b>
				</a>
				<ul class="submenu" >';
					if ($_GET['userid']=='%' && $_GET['state']=='%') echo '<li class="active">'; else echo '<li>'; echo '
						<a href="./index.php?page=dashboard&amp;userid=%&amp;state=%&amp;ticket=%&amp;technician=%&amp;user=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%">
							<i class="icon-double-angle-right"></i>
							'.T_('Tous les états').' ('.$cntall[0].')
						</a>
					</li>';
					//display new tickets if exist
					if ($cnt5[0]>0 && $rright['side_your_not_attribute']!=0)
					{
						if ($_GET['userid']=='0' && $_GET['state']=='%') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=dashboard&amp;userid=0&amp;t_group=0&amp;state=%">
								<i class="icon-double-angle-right"></i>
								'.T_('Nouveaux').' ('.$cnt5[0].')&nbsp;&nbsp;&nbsp;<i title="'.T_('Des nouveaux tickets sont à attribuer').'" class="icon-warning-sign red bigger-130"></i>
							</a>
						</li>';
						
					}
					//display meta states link
					if ($rparameters['meta_state']==1  && $rright['side_all_meta']!=0)
					{
						$query=$db->query("SELECT count(*) FROM `tincidents` WHERE disable='0' AND (state=1 OR state=2 OR state=6) $where_agency $where_service $parenthese2");
						$cntmetaall=$query->fetch();
						$query->closeCursor(); 
    					if ($_GET['userid']=='%' && $_GET['state']=='meta') {echo '<li class="active">';} else {echo "<li>";}
						echo '
    						<a title="'.T_('Meta-état regroupant les états: Attente de PEC, En cours, et Attente de retour').'." href="./index.php?page=dashboard&amp;userid=%&amp;state=meta&amp;ticket=%&amp;technician=%&amp;user=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%">
    							<i class="icon-double-angle-right"></i>
    							'.T_('A traiter').' ('.$cntmetaall[0].')
    						</a>
    					</li>';
					}
					//for each state display in sub-menu
					$query = $db->query("SELECT * FROM `tstates` WHERE id not like 5 ORDER BY number");
					while ($row = $query->fetch())
					{
						$query2=$db->query("SELECT count(id) FROM `tincidents` WHERE state='$row[id]' $where_agency $where_service $parenthese2 AND disable='0'");
						$cnt=$query2->fetch();
						$query2->closeCursor(); 
						echo '
						<li';  
						if ($_GET['userid']=='%' && $_GET['state']==$row['id']) echo ' class="active"';
						echo '>
							<a title="'.$row['description'].'" href="./index.php?page=dashboard&amp;userid=%&amp;state='.$row['id'].'&amp;ticket=%&amp;technician=%&amp;user=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%">
								<i class="icon-double-angle-right"></i>
								'.T_($row['name']).' ('.$cnt[0].')
							</a>
						</li>';
					}
					$query->closeCursor(); 
					echo	'
				</ul>
			</li>';
		}
		if ($rright['side_view']!=0)
		{
			//if exist view for connected user then display link view
			$query=$db->query("SELECT * FROM `tviews` WHERE uid='$_SESSION[user_id]' ORDER BY 'name' ");
			$row=$query->fetch();
			$query->closeCursor();		
			if ($row[0]!='')
			{
				if($_GET['viewid']!='' || $_GET['page']=='view') echo '<li class="active">'; else echo '<li>'; echo '
					<a href="./index.php?page=dashboard&viewid=1" class="dropdown-toggle">
						<i class="icon-eye-open"></i>
						<span class="menu-text"> '.T_('Vos vues').' </span>
						<b class="arrow icon-angle-down"></b>
					</a>
					<ul class="submenu">';
					//get view of connected user
					$query = $db->query("SELECT * FROM `tviews` WHERE uid='$_SESSION[user_id]' ORDER BY 'name' ");
					while ($row = $query->fetch())
					{
						//case for no sub categories
						if ($row['subcat']==0) $subcat='%'; else $subcat=$row['subcat']; 
						//count entries
						$query2="SELECT COUNT(*) FROM `tincidents` WHERE category='$row[category]' AND subcat LIKE '$subcat' AND (state='1' OR state='2' OR state='6') $where_agency $where_service $parenthese2 AND disable='0'";
						$query2=$db->query($query2);
						$n=$query2->fetch();
						$query2->closeCursor();
						// echo '<li '; if ($_GET['viewid']==$row['id'])  echo'class="active"'; echo '><a href="./index.php?page=dashboard&amp;userid=%&amp;category='.$row['category'].'&amp;subcat='.$subcat.'&amp;viewid='.$row['id'].'">Vue '.$row['name'].' ('.$n[0].')</a></li>';
						 if ($_GET['viewid']==$row['id']) echo '<li class="active">'; else  echo'<li>'; 
						echo '
							<a href="./index.php?page=dashboard&amp;userid=%&amp;category='.$row['category'].'&amp;subcat='.$subcat.'&amp;viewid='.$row['id'].'&amp;state=%&amp;ticket=%&amp;technician=%&amp;user=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%">
								<i class="icon-double-angle-right"></i>
								'.$row['name'].' ('.$n[0].')
							</a>
						</li>';
					}
					$query->closeCursor();
					echo '
					</ul>
				</li>';
			}
		}
		if ($rright['asset']!=0 && $rparameters['asset']==1)
		{
			if($_GET['page']=='asset_list' || $_GET['page']=='asset_stock' || $_GET['page']=='asset') echo '<li class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=asset_list&amp;state=2">
					<i class="icon-desktop"></i>
					<span class="menu-text">'.T_('Équipements').'</span>
				</a>';
				if ($rright['side_asset_all_state']!=0)
				{
					echo '
					<ul class="submenu">
						<li';
							//query count all assets or assets of company
							if($rright['asset_list_company_only']!=0)
							{
								$query2="SELECT count(tassets.id) FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tassets.disable='0' AND tusers.company='$ruser[company]'";
							} else {
								$query2="SELECT count(id) FROM `tassets` WHERE disable='0'";
							}
							$query2=$db->query($query2);
							$cnt=$query2->fetch();
							$query2->closeCursor();
							if (($_GET['page']=='asset_list' || $_GET['page']=='asset') && $_GET['state']=='%') echo ' class="active"';
							echo '>
								<a title="'.T_('Tous les équipements').'" href="./index.php?page=asset_list&amp;state=%">
									<i class="icon-double-angle-right"></i>
									'.T_('Tous').' ('.$cnt[0].')
								</a>
						</li>
						';
						//for each state display in sub-menu
						$query = $db->query("SELECT * FROM `tassets_state` WHERE disable='0' ORDER BY `order`");
						while ($row = $query->fetch())
						{
							//query count all assets or assets of company
							if($rright['asset_list_company_only']!=0)
							{
								$query2="SELECT count(tassets.id) FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tassets.state LIKE '$row[id]' AND tassets.disable='0' AND tusers.company='$ruser[company]'";
							} else {
								$query2="SELECT count(id) FROM `tassets` WHERE state LIKE '$row[id]' AND disable='0'";
							}
							$query2=$db->query($query2);
							$cnt=$query2->fetch();
							$query2->closeCursor(); 
							echo '
							<li';  
							if (($_GET['page']=='asset_list' || $_GET['page']=='asset') && $_GET['state']==$row['id'] && $_GET['warranty']!=1) echo ' class="active"';
							echo '>
								<a title="'.T_($row['description']).'" href="./index.php?page=asset_list&amp;state='.$row['id'].'">
									<i class="icon-double-angle-right"></i>
									'.T_($row['name']).' ('.$cnt[0].')
								</a>
							</li>';
						}
						$query->closeCursor(); 
						//display warranty link if parameter is enable
						if($rparameters['asset_warranty']==1)
						{
							$today=date('Y-m-d');
							//query count all assets or assets of company
							if($rright['asset_list_company_only']!=0)
							{
								$query2="SELECT count(tassets.id) FROM `tassets`,`tusers` WHERE tassets.user=tusers.id AND tassets.state LIKE '2' AND tassets.date_end_warranty > '$today' AND tassets.disable='0' AND tusers.company='$ruser[company]'";
							} else {
								$query2="SELECT count(id) FROM `tassets` WHERE state LIKE '2' AND date_end_warranty > '$today' AND disable='0'";
							}
							$query2=$db->query($query2);
							$cnt=$query2->fetch();
							$query2->closeCursor(); 
							echo '
							<li';  
							if ($_GET['page']=='asset_list' && $_GET['warranty']==1) echo ' class="active"';
							echo '>
								<a title="'.T_('Liste des équipements en fonction de leurs garanties').'" href="./index.php?page=asset_list&amp;state=2&amp;warranty=1">
									<i class="icon-double-angle-right"></i>
									'.T_('Garanties').'  ('.$cnt[0].')
								</a>
							</li>';
						}
						echo'
					</ul>';
				}
				echo '
			</li>';
		}
		if ($rright['procedure']!=0 && $rparameters['procedure']==1)
		{
			 if($_GET['page']=='procedure') echo '<li class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=procedure">
					<i class="icon-book"></i>
					<span class="menu-text">'.T_('Procédures').'</span>
				</a>
			</li>
			';
		}
		if ($rright['planning']!=0 && $rparameters['planning']==1)
		{
			if($_GET['page']=='planning') echo '<li class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=planning">
					<i class="icon-calendar"></i>
					<span class="menu-text">'.T_('Calendrier').'</span>
				</a>
			</li>';
		}
		if ($rright['availability']!=0 && $rparameters['availability']==1)
		{
			if($_GET['page']=='availability') echo '<li class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=plugins/availability/index">
					<i class="icon-time"></i>
					<span class="menu-text">'.T_('Disponibilité').'</span>
				</a>
			</li>';
		}
		if ($rright['stat']!=0)
		{
			if($_GET['page']=='stat') echo '<li class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=stat&tab=ticket">
					<i class="icon-bar-chart"></i>
					<span class="menu-text">'.T_('Statistiques').'</span>
				</a>
			</li>';
		}
		if ($rright['admin']!=0 || $rright['admin_groups']!=0 || $rright['admin_lists']!=0 )
		{
			//select destination page by rights
			if($rright['admin']!=0) {$dest_subpage='parameters';}
			if($rright['admin_groups']!=0) {$dest_subpage='group';}
			if($rright['admin_lists']!=0) {$dest_subpage='list';}
			 if($_GET['page']=='admin') echo '<li class="active">'; else echo '<li>'; echo '
				<a href="./index.php?page=admin&subpage='.$dest_subpage.'">
					<i class="icon-cogs"></i>
					<span class="menu-text"> '.T_('Administration').' </span>
					<b class="arrow icon-angle-down"></b>
				</a>
				<ul class="submenu">';
					if($rright['admin']!=0)
					{
						if($_GET['page']=='admin' && $_GET['subpage']=='parameters') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=parameters">
								<i class="icon-cog"></i>
								'.T_('Paramètres').'
							</a>
						</li>';
						if($_GET['page']=='admin' && $_GET['subpage']=='user') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=user">
								<i class="icon-user"></i>
								'.T_('Utilisateurs').'
							</a>
						</li>';
					}
					if($rright['admin_groups']!=0 || $rright['admin']!=0)
					{
						if($_GET['page']=='admin' && $_GET['subpage']=='group') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=group">
								<i class="icon-group"></i>
								'.T_('Groupes').'
							</a>
						</li>';
					}
					if($rright['admin']!=0)
					{
						if($_GET['page']=='admin' && $_GET['subpage']=='profile') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=profile">
								<i class="icon-lock"></i>
								'.T_('Droits').'
							</a>
						</li>';
					}
					if($rright['admin_lists']!=0 || $rright['admin']!=0)
					{
						if($_GET['page']=='admin' && $_GET['subpage']=='list') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=list">
								<i class="icon-list"></i>
								'.T_('Listes').'
							</a>
						</li>';
					}
					if($rright['admin']!=0)
					{
						if($_GET['page']=='admin' && $_GET['subpage']=='backup') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=backup">
								<i class="icon-save"></i>
								'.T_('Sauvegardes').'
							</a>
						</li>';
						if($_GET['page']=='admin' && $_GET['subpage']=='update') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=update">
								<i class="icon-circle-arrow-up"></i>
								'.T_('Mise à jour').'
							</a>
						</li>';
						if($_GET['page']=='admin' && $_GET['subpage']=='system') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=system">
								<i class="icon-desktop"></i>
								'.T_('Système').'
							</a>
						</li>';
						if($_GET['page']=='admin' && $_GET['subpage']=='infos') echo '<li class="active">'; else echo '<li>'; echo '
							<a href="./index.php?page=admin&subpage=infos">
								<i class="icon-info-sign"></i>
								'.T_('Informations').'
							</a>
						</li>';
					}
					echo '
				</ul>
			</li>';
		}
		?>
	</ul><!--/.nav-list-->
	<div class="sidebar-collapse" id="sidebar-collapse">
		<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
	</div>
	<script type="text/javascript">
		try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
	</script>
</div>