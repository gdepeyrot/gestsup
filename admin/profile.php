<?php
################################################################################
# @Name : profile.php
# @Description : rights management for all profiles
# @call : admin.php
# @parameters : 
# @Author : Flox
# @Create : 06/07/2013
# @Update : 18/12/2017
# @Version : 3.1.29
################################################################################

// initialize variables 
if(!isset($_GET['value'])) $_GET['value'] = '';
if(!isset($_GET['profile'])) $_GET['profile'] = '';
if(!isset($_GET['object'])) $_GET['object'] = '';

$db_value=strip_tags($db->quote($_GET['value']));
$db_profile=strip_tags($db->quote($_GET['profile']));
$db_object=strip_tags($db->quote($_GET['object']));
$db_object=str_replace("'","`",$db_object);

if($_GET['value']!='')
{
	$db->exec("UPDATE trights SET $db_object=$db_value WHERE profile=$db_profile");
	//redirect
		$www = "./index.php?page=admin&subpage=profile#$_GET[object]";
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		// -->
		</script>';
}

//dynamic right table
echo '
<div class="page-header position-relative">
	<h1>
		<i class="icon-lock"></i>  '.T_('Gestion des droits').'
	</h1>
</div><!--/.page-header-->
';
echo '
	<div class="col-sm-12">
		<table id="sample-table-1" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>'.T_('Nom').'</th>
					<th>'.T_('Description').'</th>
					<th>'.T_('Utilisateur').'</th>
					<th>'.T_('Utilisateur avec pouvoir').'</th>
					<th>'.T_('Superviseur').'</th>
					<th>'.T_('Technicien').'</th>
					<th>'.T_('Administrateur').'</th>
				</tr>
			</thead>
			<tbody>';					
			$qry = $db->prepare("SHOW FULL COLUMNS FROM `trights`");
			$qry->execute();
			while ($row=$qry->fetch()) 
			{	
				//exclude id and profile
				if ($row[0]!='id' && $row[0]!='profile')
				{
					//special char 
					$row['Comment']=$row['Comment'];
					echo '
					<tr id="'.$row['0'].'">
						<td>'.$row['0'].'</td>
						<td>'.T_($row['Comment']).'</td>
						<td>
							<center>';
								//find value
								$qry2 = $db->prepare("SELECT * FROM `trights` WHERE profile=:profile");
								$qry2->execute(array(
									'profile' => 2
									));
								$rv=$qry2->fetch();
								$qry2->closeCursor();
								if($rv[$row[0]]!=0)
								{
									echo'	
										<button title="'.T_('Désactiver pour le profil utilisateur').'" onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=0&object='.$row[0].'&profile=2";\'  class="btn btn-xs btn-success">
											<i class="icon-ok bigger-120"></i>
										</button>
									';
								} else {
									echo'
									<button title="'.T_('Activer pour le profil utilisateur').'" onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=2&object='.$row[0].'&profile=2";\' class="btn btn-xs btn-danger">
										<i class="icon-ban-circle bigger-120"></i>
									</button>
									';
								}
								echo'
							</center>	
						</td>
						<td>
							<center>';
								//find value
								$qry2 = $db->prepare("SELECT * FROM `trights` WHERE profile=:profile");
								$qry2->execute(array(
									'profile' => 1
									));
								$rv=$qry2->fetch();
								$qry2->closeCursor();
								if($rv[$row[0]]!=0)
								{
									echo'
										<button title="'.T_('Désactiver pour le profil utilisateur avec pouvoir').'"  onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=0&object='.$row[0].'&profile=1";\' class="btn btn-xs btn-success">
										<i class="icon-ok bigger-120"></i>
										</button>
									';
								} else {
									echo'
									<button title="'.T_('Activer pour le profil utilisateur avec pouvoir').'"  onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=2&object='.$row[0].'&profile=1";\' class="btn btn-xs btn-danger">
										<i class="icon-ban-circle bigger-120"></i>
									</button>
									';
								}
								echo'
							</center>	
						</td>
						<td>
							<center>';
								//find value
								$qry2 = $db->prepare("SELECT * FROM `trights` WHERE profile=:profile");
								$qry2->execute(array(
									'profile' => 3
									));
								$rv=$qry2->fetch();
								$qry2->closeCursor();
								if($rv[$row[0]]!=0)
								{
									echo'
										<button title="'.T_('Désactiver pour le profil superviseur').'"  onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=0&object='.$row[0].'&profile=3";\' class="btn btn-xs btn-success">
										<i class="icon-ok bigger-120"></i>
										</button>
									';
								} else {
									echo'
									<button title="'.T_('Activer pour le profil superviseur').'" onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=2&object='.$row[0].'&profile=3";\' class="btn btn-xs btn-danger">
										<i class="icon-ban-circle bigger-120"></i>
									</button>
									';
								}
								echo'
							</center>	
						</td>
						<td>
							<center>';
								//find value
								$qry2 = $db->prepare("SELECT * FROM `trights` WHERE profile=:profile");
								$qry2->execute(array(
									'profile' => 0
									));
								$rv=$qry2->fetch();
								$qry2->closeCursor();
								if($rv[$row[0]]!=0)
								{
									echo'
										<button title="'.T_('Désactiver pour le profil technicien').'"  onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=0&object='.$row[0].'&profile=0";\' class="btn btn-xs btn-success">
										<i class="icon-ok bigger-120"></i>
										</button>
									';
								} else {
									echo'
									<button title="'.T_('Activer pour le profil technicien').'"  onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=2&object='.$row[0].'&profile=0";\' class="btn btn-xs btn-danger">
										<i class="icon-ban-circle bigger-120"></i>
									</button>
									';
								}
								echo'
							</center>	
						</td>
						<td>
							<center>';
								if ($row[0]!='admin') //avoid disable admin right problem for admin profile
								{
									//find value
									$qry2 = $db->prepare("SELECT * FROM `trights` WHERE profile=:profile");
									$qry2->execute(array(
										'profile' => 4
										));
									$rv=$qry2->fetch();
									$qry2->closeCursor();
									if($rv[$row[0]]!=0)
									{
										echo'
											<button title="'.T_('Désactiver pour le profil administrateur').'"  onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=0&object='.$row[0].'&profile=4";\' class="btn btn-xs btn-success">
											<i class="icon-ok bigger-120"></i>
											</button>
										';
									} else {
										echo'
										<button title="'.T_('Activer pour le profil administrateur').'" onclick=\'window.location.href="./index.php?page=admin&subpage=profile&value=2&object='.$row[0].'&profile=4";\' class="btn btn-xs btn-danger">
											<i class="icon-ban-circle bigger-120"></i>
										</button>
										';
									}
								}
								echo'
							</center>	
						</td>
					</tr>
					';
				}
			}
			$qry->closeCursor(); 
			echo'
			</tbody>
		</table>
	</div><!--/span-->';
?>