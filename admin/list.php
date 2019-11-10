<?php
################################################################################
# @Name : ./admin/list.php
# @Description : administration of tables
# @Call : /admin/admin.php
# @Parameters : 
# @Author : Flox
# @Create : 15/03/2011
# @Update : 20/12/2017
# @Version : 3.1.29
################################################################################

//initialize variables 
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = '';
if(!isset($_POST['Ajouter'])) $_POST['Ajouter'] = '';
if(!isset($_POST['cat'])) $_POST['cat'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '';
if(!isset($_POST['service'])) $_POST['service'] = '';
if(!isset($_POST['subcat'])) $_POST['subcat'] = '';
if(!isset($_POST['model'])) $_POST['model'] = '';
if(!isset($_POST['ip'])) $_POST['ip'] = '';
if(!isset($_POST['wifi'])) $_POST['wifi'] = '';
if(!isset($_POST['manufacturer'])) $_POST['manufacturer'] = '';
if(!isset($_POST['name'])) $_POST['name'] = '';
if(!isset($_POST['type'])) $_POST['type'] = '';
if(!isset($_POST['confirm'])) $_POST['confirm'] = '';
if(!isset($_GET['table'])) $_GET['table'] = '';
if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($nbchamp)) $nbchamp = '';
if(!isset($champ0)) $champ0 = '';
if(!isset($champ1)) $champ1 = '';
if(!isset($champ2)) $champ2 = '';
if(!isset($champ3)) $champ3 = '';
if(!isset($champ4)) $champ4 = '';
if(!isset($champ5)) $champ5 = '';
if(!isset($champ6)) $champ6 = '';
if(!isset($reqchamp)) $reqchamp = '';
if(!isset($set)) $set = '';
if(!isset($i)) $i = '';
if(!isset($extensionFichier)) $extensionFichier = '';
if(!isset($nomorigine)) $nomorigine = '';
if(!isset($number)) $number = '';
if(!isset($_FILES['file1']['name'])) $_FILES['file1']['name'] = '';

//default table
if ($_GET['table']=='') $_GET['table']='tcategory';

//default page
if ($_GET['action']=='') $_GET['action']='disp_list';

//escape special char and secure string before database insert
$champ0=strip_tags($db->quote($champ0));
$champ1=strip_tags($db->quote($champ1));
$champ2=strip_tags($db->quote($champ2));
$champ3=strip_tags($db->quote($champ3));
$champ4=strip_tags($db->quote($champ4));
$champ5=strip_tags($db->quote($champ5));
$champ6=strip_tags($db->quote($champ6));
$db_id=strip_tags($db->quote($_GET['id']));
$db_table=strip_tags($db->quote($_GET['table']));
$db_table=str_replace("'","`",$db_table);

//display debug informations
if($rparameters['debug']==1) {
	echo '<u><b>DEBUG MODE:</b></u><br /> <b>VAR:</b> cnt_service='.$cnt_service;
	if($user_services) {echo ' user_services=';foreach($user_services as $value) {echo $value.' ';}}
}

//retrieve selected table description
$qry = $db->prepare("DESC $db_table");
$qry->execute();
while($row=$qry->fetch()) {
	${'champ' . $nbchamp} =$row[0];
	$nbchamp++;
}
$qry->closeCursor();
$nbchamp1=$nbchamp;
$nbchamp=$nbchamp-1;

if ($_GET['action']=="delete") 
{
	//display confirm box before delete
	$boxtitle="<i class='icon-trash red'></i> ".T_('Confirmation');
	$boxtext='
	<form name="form" method="POST" action="" id="form">
		<input name="confirm" type="hidden" value="1">
		'.T_('Voulez-vous supprimer cette ligne ?').'
	</form>
	';
	$valid=T_('Continuer');
	$action1="$('form#form').submit();";
	$cancel=T_('Fermer');
	$action2="$( this ).dialog( \"close\" ); ";
	include "./modalbox.php"; 
	
	if($_POST['confirm']==1)
	{
		$db->exec("DELETE FROM $db_table WHERE id = $db_id");
		$www = "./index.php?page=admin&subpage=list&table=$_GET[table]&action=disp_list";
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		// -->
		</script>';
	}
}
if ($_GET['action']=="update") 
{
	if($_GET['table']=='tcategory') //special case category
	{
		//secure string
		$_POST['number']=strip_tags($_POST['number']);
		$_POST['category']=strip_tags($_POST['category']);
		
		$qry=$db->prepare("UPDATE `tcategory` SET `number`=:number,`name`=:name WHERE `id`=:id");
		$qry->execute(array(
			'number' => $_POST['number'],
			'name' => $_POST['category'],
			'id' => $_GET['id']
			));
		
		if ($rparameters['user_limit_service']==1)
		{
			$qry=$db->prepare("UPDATE `tcategory` SET `service`=:service WHERE `id`=:id");
			$qry->execute(array(
				'service' => $_POST['service'],
				'id' => $_GET['id']
				));
		}
	}elseif($_GET['table']=='tsubcat') //special case subcat
	{
		//secure string
		$_POST['subcat']=strip_tags($_POST['subcat']);
		
		$qry=$db->prepare("UPDATE `tsubcat` SET `cat`=:cat, `name`=:name WHERE id=:id");
		$qry->execute(array(
			'cat' => $_POST['cat'],
			'name' => $_POST['subcat'],
			'id' => $_GET['id']
			));
	}
	elseif($_GET['table']=='tassets_model') //special case  asset model
	{ 
		//secure string
		$_POST['model']=strip_tags($_POST['model']);
		$_POST['warranty']=strip_tags($_POST['warranty']);
		
		///upload file
		if($_FILES['file1']['name'])
		{
			//white list exclusion for extension
			$whitelist =  array('png','jpg','jpeg' ,'gif' ,'bmp','');
			$file_name = basename($_FILES['file1']['name']);
			//secure check for extension
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			if(in_array($ext,$whitelist) ) {
				$repertoireDestination = dirname(__FILE__)."../../images/model/$file_name";
				move_uploaded_file($_FILES['file1']['tmp_name'], $repertoireDestination);
			
				$qry=$db->prepare("UPDATE `tassets_model` SET `type`=:type, `manufacturer`=:manufacturer, `name`=:name, `image`=:image,`ip`=:ip,`wifi`=:wifi,`warranty`=:warranty WHERE `id`=:id");
				$qry->execute(array(
					'type' => $_POST['type'],
					'manufacturer' => $_POST['manufacturer'],
					'name' => $_POST['model'],
					'image' => $file_name,
					'ip' => $_POST['ip'],
					'wifi' => $_POST['wifi'],
					'warranty' => $_POST['warranty'],
					'id' => $_GET['id']
					));
				
			} else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Blocage de sécurité').':</strong> '.T_('Type de fichier interdit').'.<br></div>';}
		} else {
			$qry=$db->prepare("UPDATE `tassets_model` SET `type`=:type, `manufacturer`=:manufacturer, `name`=:name, `ip`=:ip,`wifi`=:wifi,`warranty`=:warranty WHERE `id`=:id");
			$qry->execute(array(
				'type' => $_POST['type'],
				'manufacturer' => $_POST['manufacturer'],
				'name' => $_POST['model'],
				'ip' => $_POST['ip'],
				'wifi' => $_POST['wifi'],
				'warranty' => $_POST['warranty'],
				'id' => $_GET['id']
				));
		}
	}
	else
	{
		for ($i=0; $i <= $nbchamp; $i++)
		{
			$reqchamp="${'champ' . $i}";
			if(!isset($_POST[$reqchamp])) $_POST[$reqchamp] = '';
			$_POST[$reqchamp] = strip_tags($db->quote($_POST[$reqchamp])); 
			if ($i=='1') $set="`$reqchamp`=$_POST[$reqchamp]"; else $set="$set, `$reqchamp`=$_POST[$reqchamp]";
		}
		$db->exec("UPDATE $db_table SET $set WHERE id=$db_id");
	}
	
	$www = "./index.php?page=admin&subpage=list&table=$_GET[table]&action=disp_list";
	echo '<script language="Javascript">
	<!--
	document.location.replace("'.$www.'");
	// -->
	</script>';
}

if ($_GET['action']=="add")
{
	if($_GET['table']=='tcategory') //special case category
	{
		//secure string
		$_POST['category']=strip_tags($_POST['category']);
		
		$qry=$db->prepare("INSERT INTO `tcategory` (`name`,`service`) VALUES (:name,:service)");
		$qry->execute(array(
			'name' => $_POST['category'],
			'service' => $_POST['service']
			));
	}
	elseif($_GET['table']=='tsubcat') //special case subcat 
	{
		//secure string
		$_POST['subcat']=strip_tags($_POST['subcat']);
		
		$qry=$db->prepare("INSERT INTO `tsubcat` (`cat`,`name`) VALUES (:cat,:name)");
		$qry->execute(array(
			'cat' => $_POST['cat'],
			'name' => $_POST['subcat']
			));
	}
	elseif($_GET['table']=='tassets_model') //special case and asset model
	{
		//secure string
		$_POST['model']=strip_tags($_POST['model']);
		$_POST['warranty']=strip_tags($_POST['warranty']);
		
		///upload file
		if($_FILES['file1'])
		{
			//white list exclusion for extension
			$whitelist =  array('png','jpg','jpeg' ,'gif' ,'bmp','');
			$file_name = basename($_FILES['file1']['name']);
			//secure check for extension
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			if(in_array($ext,$whitelist) ) {
				$repertoireDestination = dirname(__FILE__)."../../images/model/$file_name";
				move_uploaded_file($_FILES['file1']['tmp_name'], $repertoireDestination);
				$qry=$db->prepare("INSERT INTO `tassets_model` (`type`,`manufacturer`,`image`,`name`,`ip`,`wifi`,`warranty`) VALUES (:type,:manufacturer,:image,:name,:ip,:wifi,:warranty)");
				$qry->execute(array(
					'type' => $_POST['type'],
					'manufacturer' => $_POST['manufacturer'],
					'image' => $file_name,
					'name' => $_POST['model'],
					'ip' => $_POST['ip'],
					'wifi' => $_POST['wifi'],
					'warranty' => $_POST['warranty']
					));
			} else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Blocage de sécurité').':</strong> '.T_('Type de fichier interdit').'.<br></div>';}
		} else{
			$db->exec("INSERT INTO `tassets_model` (type,manufacturer,name,ip,wifi,warranty) VALUES ('$_POST[type]','$_POST[manufacturer]',$_POST[model],'$_POST[ip]','$_POST[wifi]','$_POST[warranty]')");
			$qry=$db->prepare("INSERT INTO `tassets_model` (`type`,`manufacturer`,`name`,`ip`,`wifi`,`warranty`) VALUES (:type,:manufacturer,:name,:ip,:wifi,:warranty)");
			$qry->execute(array(
				'type' => $_POST['type'],
				'manufacturer' => $_POST['manufacturer'],
				'name' => $_POST['model'],
				'ip' => $_POST['ip'],
				'wifi' => $_POST['wifi'],
				'warranty' => $_POST['warranty']
				));
		}
	}
	else
	{
		//generate sql row name for selected table
		for ($i=1; $i <= $nbchamp; $i++)
		{
			if ($i!="1") {$reqchamp="$reqchamp,${'champ' . $i}";} else {$reqchamp="`${'champ' . $i}`";}
		}
		//generate sql value for selected table
		for ($i=1; $i <= $nbchamp; $i++)
		{
			$nomchamp="${'champ' . $i}";
			$_POST[$nomchamp] = strip_tags($db->quote($_POST[$nomchamp])); 
			if ($i!="1") {$reqvalue="$reqvalue,$_POST[$nomchamp]";} else {$reqvalue="$_POST[$nomchamp]";}
		}
		$db->exec("INSERT INTO $db_table ($reqchamp) VALUES ($reqvalue)");
	}

	$www = "./index.php?page=admin&subpage=list&table=$_GET[table]&action=disp_list";
	echo '<script language="Javascript">
	<!--
	document.location.replace("'.$www.'");
	// -->
	</script>';
	
}
?>
<div class="page-header position-relative">
	<h1>
		<i class="icon-list"></i>  <?php echo T_('Gestion des listes'); ?>
	</h1>
</div>
<!------------------------------------------------ Display list of tables to edit with right ------------------------------------------------------>
<div class="tabbable tabs-left">
	<ul class="nav nav-tabs" id="myTab3">
		<?php
		if($rright['admin_lists_category']!=0 || $rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='tcategory') {echo 'class="active"';} echo ' >
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=tcategory">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Catégories').'
				</a>
			</li>
			';
		}
		if($rright['admin_lists_subcat']!=0 || $rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='tsubcat') {echo 'class="active"';} echo' >
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=tsubcat">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Sous-catégorie').'
				</a>
			</li>
			';
		}
		//for user agency parameter
		if($rparameters['user_agency']==1 && $rright['admin']!=0)
		{
			echo '
    		 <li '; if($_GET['table']=='tagencies') echo 'class="active"'; echo ' >
    			<a href="./index.php?page=admin&amp;subpage=list&amp;table=tagencies">
    				<i class="blue icon-table bigger-110"></i>
    				'.T_('Agences').'
    			</a>
	    	</li>
			';
		}
		if($rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='tservices') {echo 'class="active"';} echo' >
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=tservices">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Services').'
				</a>
			</li>
			';
		}
		if($rright['admin_lists_criticality']!=0 || $rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='tcriticality') {echo 'class="active"';} echo '>
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=tcriticality">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Criticités').'
				</a>
			</li>
			';
		}
		if($rright['admin_lists_priority']!=0 || $rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='tpriority') {echo 'class="active"';} echo '>
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=tpriority">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Priorités').'
				</a>
			</li>
			';
		}
		if(($rparameters['ticket_type']=='1' && $rright['admin_lists_type']!=0) || $rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='ttypes') {echo 'class="active"';} echo '>
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=ttypes">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Types des tickets').'
				</a>
			</li>
			';
		}
		if($rright['admin']!=0)
		{
			echo '
			<li ';if($_GET['table']=='tstates') {echo 'class="active"';} echo ' >
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=tstates">
					<i class="blue icon-table bigger-110"></i>
					'.T_('États des tickets').'
				</a>
			</li>
			';
		}
		if($rparameters['ticket_places']==1 && $rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='tplaces') {echo 'class="active"';} echo ' >
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=tplaces">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Lieux').'
				</a>
			</li>
			';
		}
		if($rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='ttemplates') {echo 'class="active"';} echo ' >
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=ttemplates">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Modèles des tickets').'
				</a>
			</li>
			';
		}
		//for advanced user parameter
		if($rparameters['user_advanced']==1 && $rright['admin']!=0)
		{
			echo '
    		 <li '; if($_GET['table']=='tcompany') echo 'class="active"'; echo ' >
    			<a href="./index.php?page=admin&amp;subpage=list&amp;table=tcompany">
    				<i class="blue icon-table bigger-110"></i>
    				'.T_('Sociétés').'
    			</a>
	    	</li>
			';
		}
		if($rright['admin']!=0)
		{
			echo '
			<li '; if($_GET['table']=='ttime') {echo 'class="active"';} echo '>
				<a href="./index.php?page=admin&amp;subpage=list&amp;table=ttime">
					<i class="blue icon-table bigger-110"></i>
					'.T_('Temps').'
				</a>
			</li>
			';
		}

		if($rright['admin']!=0)
		{
			if($rparameters['asset']=='1')
		    {
				echo '
    		        <li '; if($_GET['table']=='tassets_type') {echo 'class="active"';} echo'>
            			<a href="./index.php?page=admin&amp;subpage=list&amp;table=tassets_type">
            				<i class="blue icon-table bigger-110"></i>
            				'.T_('Types des équipements').'
            			</a>
            		</li>
					<li '; if($_GET['table']=='tassets_manufacturer') {echo 'class="active"';} echo'>
            			<a href="./index.php?page=admin&amp;subpage=list&amp;table=tassets_manufacturer">
            				<i class="blue icon-table bigger-110"></i>
            				'.T_('Fabricants des équipements').'
            			</a>
            		</li>
    		        <li '; if($_GET['table']=='tassets_model') {echo 'class="active"';} echo'>
            			<a href="./index.php?page=admin&amp;subpage=list&amp;table=tassets_model">
            				<i class="blue icon-table bigger-110"></i>
            				'.T_('Modèles des équipements').'
            			</a>
            		</li>
					<li '; if($_GET['table']=='tassets_state') {echo 'class="active"';} echo'>
            			<a href="./index.php?page=admin&amp;subpage=list&amp;table=tassets_state">
            				<i class="blue icon-table bigger-110"></i>
            				'.T_('États des équipements').'
            			</a>
            		</li>
					<li '; if($_GET['table']=='tassets_location') {echo 'class="active"';} echo'>
            			<a href="./index.php?page=admin&amp;subpage=list&amp;table=tassets_location">
            				<i class="blue icon-table bigger-110"></i>
            				'.T_('Localisation des équipements').'
            			</a>
            		</li>
					';
					if ($rparameters['asset_ip']==1)
					{
						echo '
						<li '; if($_GET['table']=='tassets_iface_role') {echo 'class="active"';} echo'>
							<a href="./index.php?page=admin&amp;subpage=list&amp;table=tassets_iface_role">
								<i class="blue icon-table bigger-110"></i>
								'.T_('Rôle des interfaces IP des équipements').'
							</a>
						</li>
						<li '; if($_GET['table']=='tassets_network') {echo 'class="active"';} echo'>
							<a href="./index.php?page=admin&amp;subpage=list&amp;table=tassets_network">
								<i class="blue icon-table bigger-110"></i>
								'.T_('Réseaux des équipements').'
							</a>
						</li>
						';
					}
			}
		}
		?>
	</ul>
	<!------------------------------------------------ display edit entry page ------------------------------------------------>
	<div class="tab-content">
		<?php
		//Display 
		if ($_GET['action']=="disp_edit")
		{
			//check right before display list
			if (
				$rright['admin']!='0' ||
				($_GET['table']=='tcategory' && $rright['admin_lists_category']!='0' && $cnt_service!=0) ||
				($_GET['table']=='tsubcat' && $rright['admin_lists_subcat']!='0') ||
				($_GET['table']=='tcriticality' && $rright['admin_lists_criticality']!='0') ||
				($_GET['table']=='tpriority' && $rright['admin_lists_priority']!='0') ||
				($_GET['table']=='ttypes' && $rright['admin_lists_type']!='0')
			)
			{
				echo '
					<div class="col-sm-5">
						<div class="widget-box">
							<div class="widget-header">
								<h4>'.T_('Édition d\'une entrée').':</h4>
							</div>
							<div class="widget-body">
								<div class="widget-main no-padding">
									<form method="post" enctype="multipart/form-data" action="./index.php?page=admin&subpage=list&table='.$_GET['table'].'&action=update&id='.$_GET['id'].'" >
									';
										//specific views 
										if($_GET['table']=='tcategory')
										{
											//find value
											$qry = $db->prepare("SELECT `name`,`number` FROM `tcategory` WHERE id=:id");
											$qry->execute(array(
												'id' => $_GET['id']
												));
											$row=$qry->fetch();
											$qry->closeCursor();
											
											echo'
											<fieldset>
												<label for="number">'.T_('Ordre').'</label>
												<input name="number" type="text" value="'.$row['number'].'" />
												<br /><br />
												<label for="category">'.T_('Catégorie').'</label>
												<input name="category" type="text" value="'.$row['name'].'" />
											</fieldset>
											<div class=\"space-4\"></div>
											';
											
											if ($rparameters['user_limit_service'])
											{
												//find service value
												$qry = $db->prepare("SELECT `service` FROM `tcategory` WHERE id=:id");
												$qry->execute(array(
													'id' => $_GET['id']
													));
												$row=$qry->fetch();
												$qry->closeCursor();
												
												if ($cnt_service==1) //not show select field, if there are only one service, send data in background
												{
													echo '<input type="hidden" name="service" value="'.$row['service'].'" />'; 
												} else { //display select box for service
													echo '
														<fieldset>
															<label for="service">'.T_('Service').'</label>
															<select name="service" id="form-field-select-1" >
															';
																if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
																	//display only service associated with this user
																	$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
																	$qry->execute(array(
																		'user_id' => $_SESSION['user_id'],
																		'disable' => 0
																		));
																} else {
																	//display all services
																	$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
																	$qry->execute(array(
																		'disable' => 0
																		));
																}
																while ($row2=$qry->fetch()) 
																{
																	echo '
																	<option '; if ($row['service']==$row2['id']) {echo 'selected';} echo ' value="'.$row2['id'].'">
																		'.$row2['name'].'
																	</option>';
																}
																$qry->closeCursor();
															echo '
															</select>
														</fieldset>
														<div class=\"space-4\"></div>
													';
												}
											}
										}elseif($_GET['table']=='tcriticality')
										{
											//find value
											$qry = $db->prepare("SELECT `number`,`name`,`color` FROM `tcriticality` WHERE id=:id");
											$qry->execute(array(
												'id' => $_GET['id']
												));
											$row=$qry->fetch();
											$qry->closeCursor();
												
											echo'
											<fieldset>
												<label for="number">'.T_('Numéro').'</label>
												<input name="number" type="text" value="'.$row['number'].'" />
												<br /><br />
												<label for="name">'.T_('Nom').'</label>
												<input name="name" type="text" value="'.$row['name'].'" />
												<br /><br />
												<label for="color">'.T_('Couleur').'</label>
												<input name="color" type="text" value="'.$row['color'].'" />
												<br /><br />
											';
											
											if ($rparameters['user_limit_service'])
											{
												$qry = $db->prepare("SELECT `service` FROM `tcriticality` WHERE id=:id");
												$qry->execute(array(
													'id' => $_GET['id']
													));
												$row=$qry->fetch();
												$qry->closeCursor();
												
												if ($cnt_service==1) //not show select field, if there are only one service, send data in background
												{
													echo '<input type="hidden" name="service" value="'.$row['service'].'" />'; 
												} else {
													echo '
													<label for="service">'.T_('Service').'</label>
													<select name="service" id="form-field-select-1" >
													';
														if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
															//display only service associated with this user
															$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
															$qry->execute(array(
																'user_id' => $_SESSION['user_id'],
																'disable' => 0
																));
															
														} else {
															//display all services
															$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
															$qry->execute(array(
																'disable' => 0
																));
														}
														while ($row2=$qry->fetch()) 
														{
															echo '
															<option '; if ($row['service']==$row2['id']) {echo 'selected';} echo ' value="'.$row2['id'].'">
																'.$row2['name'].'
															</option>';
														}
														$qry->closeCursor();
													echo '
													</select>
													';
												}
											}
											echo '
											<fieldset>
											<div class=\"space-4\"></div>';
										}elseif($_GET['table']=='tpriority')
										{
											//find value
											$qry = $db->prepare("SELECT `number`,`name`,`color` FROM `tpriority` WHERE id=:id");
											$qry->execute(array(
												'id' => $_GET['id']
												));
											$row=$qry->fetch();
											$qry->closeCursor();
											
											echo'
											<fieldset>
												<label for="number">'.T_('Numéro').'</label>
												<input name="number" type="text" value="'.$row['number'].'" />
												<br /><br />
												<label for="name">'.T_('Nom').'</label>
												<input name="name" type="text" value="'.$row['name'].'" />
												<br /><br />
												<label for="color">'.T_('Couleur').'</label>
												<input name="color" type="text" value="'.$row['color'].'" />
												<br /><br />
											';
											
											if ($rparameters['user_limit_service'])
											{
												//find value
												$qry = $db->prepare("SELECT `service` FROM `tpriority` WHERE id=:id");
												$qry->execute(array(
													'id' => $_GET['id']
													));
												$row=$qry->fetch();
												$qry->closeCursor();
												
												if ($cnt_service==1) //not show select field, if there are only one service, send data in background
												{
													echo '<input type="hidden" name="service" value="'.$row['service'].'" />'; 
												} else {
													echo '
													<label for="service">'.T_('Service').'</label>
													<select name="service" id="form-field-select-1" >
													';
														if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
															//display only service associated with this user
															$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
															$qry->execute(array(
																'user_id' => $_SESSION['user_id'],
																'disable' => 0
																));
														} else {
															//display all services
															$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
															$qry->execute(array(
																'disable' => 0
																));
														}
														while ($row2=$qry->fetch()) 
														{
															echo '
															<option '; if ($row['service']==$row2['id']) {echo 'selected';} echo ' value="'.$row2['id'].'">
																'.$row2['name'].'
															</option>';
														}
														$qry->closeCursor();
													echo '
													</select>
													';
												}
											}
											echo '
											<fieldset>
											<div class=\"space-4\"></div>';
										}elseif($_GET['table']=='ttypes')
										{
											//find value
											$qry = $db->prepare("SELECT `name` FROM `ttypes` WHERE `id`=:id");
											$qry->execute(array(
												'id' => $_GET['id']
												));
											$row=$qry->fetch();
											$qry->closeCursor();
											echo'
											<fieldset>
												<label for="name">'.T_('Nom').'</label>
												<input name="name" type="text" value="'.$row['name'].'" />
												<br /><br />
											';
											
											if ($rparameters['user_limit_service'])
											{
												//find value
												$qry = $db->prepare("SELECT `service` FROM `ttypes` WHERE `id`=:id");
												$qry->execute(array(
													'id' => $_GET['id']
													));
												$row=$qry->fetch();
												$qry->closeCursor();
												
												if ($cnt_service==1) //not show select field, if there are only one service, send data in background
												{
													echo '<input type="hidden" name="service" value="'.$row['service'].'" />'; 
												} else {
													echo '
													<label for="service">'.T_('Service').'</label>
													<select name="service" id="form-field-select-1" >
													';
														if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
															//display only service associated with this user
															$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
															$qry->execute(array(
																'user_id' => $_SESSION['user_id'],
																'disable' => 0
																));
														} else {
															//display all services
															$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
															$qry->execute(array(
																'disable' => 0
																));
														}
														while ($row2=$qry->fetch()) 
														{
															echo '
															<option '; if ($row['service']==$row2['id']) {echo 'selected';} echo ' value="'.$row2['id'].'">
																'.$row2['name'].'
															</option>';
														}
														$qry->closeCursor();
													echo '
													</select>
													';
												}
											}
											echo '
											<fieldset>
											<div class=\"space-4\"></div>';
										}elseif($_GET['table']=='tsubcat')
										{
												//find value
												$qry = $db->prepare("SELECT `id`,`name`,`cat` FROM `tsubcat` WHERE `id`=:id");
												$qry->execute(array(
													'id' => $_GET['id']
													));
												$row=$qry->fetch();
												$qry->closeCursor();
												
												//find category name
												$qry = $db->prepare("SELECT `id` FROM `tcategory` WHERE `id`=:id");
												$qry->execute(array(
													'id' => $row['cat']
													));
												$rowcatfind=$qry->fetch();
												$qry->closeCursor();
											
												echo '
													<fieldset>
														<label for="cat">'.T_('Catégorie').'</label>
														<select name="cat" id="form-field-select-1">
														';
															if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
																//display only category associated services of this current user
																$qry = $db->prepare("SELECT `tcategory.id`,`tcategory.name` FROM `tcategory` WHERE `tcategory.service` IN (SELECT `service_id` FROM `tusers_services` WHERE `user_id`=:user_id) ORDER BY `tcategory.name`");
																$qry->execute(array(
																	'user_id' => $_SESSION['user_id']
																	));
															} else {
																//display all category
																$qry = $db->prepare("SELECT `id`,`name` FROM `tcategory` ORDER BY `name`");
																$qry->execute();
															}
														
															while ($row2=$qry->fetch()) 
															{
																echo '
																<option '; if ($rowcatfind['id']==$row2['id']) {echo 'selected';} echo ' value="'.$row2['id'].'">
																	'.$row2['name'].'
																</option>';
															}
															$qry->closeCursor();
														echo '
														</select>
														<div class="space-4"></div>
														<label for="subcat">'.T_('Sous-catégorie').'</label>
														<input name="subcat" type="text" value="'.$row['name'].'" />
													</fieldset>
												';
										} 
										elseif($_GET['table']=='tassets_model')
										{
												//find value
												$qry = $db->prepare("SELECT * FROM `tassets_model` WHERE `id`=:id");
												$qry->execute(array(
													'id' => $_GET['id']
													));
												$req=$qry->fetch();
												$qry->closeCursor();
												
												//find type name
												$qry = $db->prepare("SELECT `id` FROM `tassets_type` WHERE `id`=:id");
												$qry->execute(array(
													'id' => $req['type']
													));
												$row=$qry->fetch();
												$qry->closeCursor();
												
												//find manufacturer name
												$qry = $db->prepare("SELECT `id` FROM `tassets_manufacturer` WHERE `id`=:id");
												$qry->execute(array(
													'id' => $req['manufacturer']
													));
												$rowmodelfind=$qry->fetch();
												$qry->closeCursor();
												
												echo '
													<fieldset>
														<label for="type">'.T_('Type').'</label>
														<select name="type" id="form-field-select-1">
														';
															$qry = $db->prepare("SELECT `id`,`name` FROM `tassets_type` ORDER BY `name`");
															$qry->execute();
															while ($rtype=$qry->fetch()) 
															{
																echo '
																<option '; if ($row['id']==$rtype['id']) {echo 'selected';} echo ' value="'.$rtype['id'].'">
																	'.$rtype['name'].'
																</option>';
															}
															$qry->closeCursor();
														echo '
														</select>
														<div class="space-4"></div>
														<label for="manufacturer">'.T_('Fabriquant').'</label>
														<select name="manufacturer" id="form-field-select-1">
														';
															$qry = $db->prepare("SELECT `id`,`name` FROM `tassets_manufacturer` ORDER BY `name`");
															$qry->execute();
															while ($rman=$qry->fetch()) 
															{
																echo '
																<option '; if ($rowmodelfind['id']==$rman['id']) {echo 'selected';} echo ' value="'.$rman['id'].'">
																	'.$rman['name'].'
																</option>';
															}
															$qry->closeCursor();
														echo '
														</select>
														<div class="space-4"></div>
														<label for="file1">'.T_('Image').': <span style="font-size: x-small;"><i>(250px x 250px max)</i></span></label>
														';
														//display existing image
														if ($req['image']) {echo '<br /><img src="./images/model/'.$req['image'].'" /> <br /><br />';}
														echo '
														<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
														<input name="file1" type="file"  />
														<div class="space-4"></div>
														<label for="model">'.T_('Modèle').'</label>
														<input name="model" type="text" value="'.$req['name'].'" />
														<div class="space-4"></div>
														';
														if ($rparameters['asset_ip']==1)
														{
															echo '
															<label for="ip">'.T_('Équipement IP').'&nbsp;</label>
															<input type="radio" class="ace" value="1" name="ip"'; if ($req['ip']==1) {echo "checked";} echo ' > <span class="lbl"> '.T_('Oui').' </span>
															<input type="radio" class="ace" value="0" name="ip"'; if ($req['ip']==0) {echo "checked";} echo ' > <span class="lbl"> '.T_('Non').' </span>
															<div class="space-4"></div>
															<label for="wifi">'.T_('Équipement Wifi').'&nbsp;</label>
															<input type="radio" class="ace" value="1" name="wifi"'; if ($req['wifi']==1) {echo "checked";} echo ' > <span class="lbl"> '.T_('Oui').' </span>
															<input type="radio" class="ace" value="0" name="wifi"'; if ($req['wifi']==0) {echo "checked";} echo ' > <span class="lbl"> '.T_('Non').' </span>
															<div class="space-4"></div>
															';
														} else {echo '<input type="hidden" name="ip" value="0" /><input type="hidden" name="wifi" value="0" />';}
														echo '
														<label for="warranty">'.T_('Nombre d\'années de garantie').'</label>
														<input name="warranty" type="text" size="2" value="'.$req['warranty'].'" />
													</fieldset>
												';
										} 
										else 
										{
											for ($i=1; $i <= $nbchamp; $i++)
											{
												$query2 = $db->query("SELECT `${'champ' . $i}` FROM $db_table WHERE id=$db_id"); 
												$req = $query2->fetch();
												$query2->closeCursor();
												
												//translate label name
												$label_name=${'champ' . $i}; //default value
												if(${'champ' . $i}=='id') {$label_name=T_('Identifiant');}
												if(${'champ' . $i}=='name') {$label_name=T_('Libellé');}
												if(${'champ' . $i}=='name') {$label_name=T_('Libellé');}
												if(${'champ' . $i}=='cat') {$label_name=T_('Catégorie');}
												if(${'champ' . $i}=='disable') {$label_name=T_('Désactivé');}
												if(${'champ' . $i}=='number') {$label_name=T_('Ordre');}
												if(${'champ' . $i}=='color') {$label_name=T_('Couleur');}
												if(${'champ' . $i}=='description') {$label_name=T_('Description');}
												if(${'champ' . $i}=='mail_object') {$label_name=T_('Objet du mail');}
												if(${'champ' . $i}=='display') {$label_name=T_("Couleur d'affichage");}
												if(${'champ' . $i}=='incident') {$label_name=T_("Numéro ticket");}
												if(${'champ' . $i}=='address') {$label_name=T_("Adresse");}
												if(${'champ' . $i}=='zip') {$label_name=T_("Code postal");}
												if(${'champ' . $i}=='city') {$label_name=T_("Ville");}
												if(${'champ' . $i}=='country') {$label_name=T_("Pays");}
												if(${'champ' . $i}=='limit_ticket_number') {$label_name=T_("Nombre de limite de ticket");}
												if(${'champ' . $i}=='limit_ticket_days') {$label_name=T_("Nombre de limite de jours");}
												if(${'champ' . $i}=='limit_ticket_date_start') {$label_name=T_("Date de début de la limite de jours");}
												if(${'champ' . $i}=='min') {$label_name=T_("Minutes");}
												if(${'champ' . $i}=='virtualization') {$label_name=T_("Virtualisation");}
												if(${'champ' . $i}=='manufacturer') {$label_name=T_("Fabricant");}
												if(${'champ' . $i}=='image') {$label_name=T_("Image");}
												if(${'champ' . $i}=='ip') {$label_name=T_("Équipement IP");}
												if(${'champ' . $i}=='type') {$label_name=T_("Type");}
												if(${'champ' . $i}=='wifi') {$label_name=T_("Équipement WIFI");}
												if(${'champ' . $i}=='warranty') {$label_name=T_("Années de garantie");}
												if(${'champ' . $i}=='order') {$label_name=T_("Ordre");}
												if(${'champ' . $i}=='block_ip_search') {$label_name=T_("Blocage de recherche IP");}
												if(${'champ' . $i}=='mail') {$label_name=T_("Adresse mail");}
												if(${'champ' . $i}=='service') {$label_name=T_("Service");}
												if(${'champ' . $i}=='network') {$label_name=T_("Réseau");}
												if(${'champ' . $i}=='netmask') {$label_name=T_("Masque");}
												if(${'champ' . $i}=='scan') {$label_name=T_("Scan");}
												
												echo "
												<fieldset>
													<label for=\"${'champ' . $i}\">$label_name</label>
														<input name=\"${'champ' . $i}\" type=\"text\" value=\"$req[0]\" />
												</fieldset>
												<div class=\"space-4\"></div>
												";
											}
										}
										echo '
										<div class="form-actions center">
											<button type="submit" class="btn btn-sm btn-success">
												'.T_('Modifier').'
												<i class="icon-arrow-right icon-on-right bigger-110"></i>
											</button>
										</div>
									</form>
								</div>
							</div>
								
						</div>
					</div>
				';
			} else {
				echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas le droit de modifier une entrée sur cette liste, contacter votre administrateur").'.<br></div>';
			}
		}
		// ------------------------------------------------ display add entry page ------------------------------------------------
		if ($_GET['action']=="disp_add")
		{
			//check right before display list
			if (
				$rright['admin']!='0' ||
				($_GET['table']=='tcategory' && $rright['admin_lists_category']!='0') ||
				($_GET['table']=='tsubcat' && $rright['admin_lists_subcat']!='0') ||
				($_GET['table']=='tcriticality' && $rright['admin_lists_criticality']!='0') ||
				($_GET['table']=='tpriority' && $rright['admin_lists_priority']!='0') ||
				($_GET['table']=='ttypes' && $rright['admin_lists_type']!='0')
			)
			{
				echo '
					<div class="col-sm-5">
						<div class="widget-box">
							<div class="widget-header">
								<h4>'.T_('Ajout d\'une entrée').':</h4>
							</div>
							<div class="widget-body">
								<div class="widget-main no-padding">
									<form method="post" enctype="multipart/form-data" action="./index.php?page=admin&subpage=list&table='.$_GET['table'].'&action=add" >';
										if($_GET['table']=='tcategory') //special case for limit service parameters 
										{
											echo'
											<fieldset>
												<label for="category">'.T_('Catégorie').'</label>
												<input name="category" type="text" value="" />
											</fieldset>
											<div class=\"space-4\"></div>
											';
											if ($rparameters['user_limit_service'])
											{
												if ($cnt_service==1)
												{
													echo '<input type="hidden" name="service" value="'.$user_services[0].'" />'; 
													
												} else {
													echo '
														<fieldset>
															<label for="service">'.T_('Service').'</label>
															<select name="service" id="form-field-select-1" >
															';
																if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
																	//display only service associated with this user
																	$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
																	$qry->execute(array(
																		'user_id' => $_SESSION['user_id'],
																		'disable' => 0
																		));
																} else {
																	//display all services
																	$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
																	$qry->execute(array(
																		'disable' => 0
																		));
																}
																while ($row=$qry->fetch()) 
																{
																	echo '
																	<option value="'.$row['id'].'">
																		'.$row['name'].'
																	</option>';
																}
																$qry->closeCursor();
															echo '
															</select>
														</fieldset>
														<div class=\"space-4\"></div>
													';
												}
											}
										}elseif($_GET['table']=='tsubcat') //special case subcat 
										{
											echo '
											<fieldset>
												<label for="cat">'.T_('Catégorie').'</label>
												<select name="cat" id="form-field-select-1">
												';
													if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
														//display only category associated services of this current user
														$qry = $db->prepare("SELECT `tcategory.id`,`tcategory.name` FROM `tcategory` WHERE `tcategory.service` IN (SELECT `service_id` FROM `tusers_services` WHERE user_id=:user_id) ORDER BY `tcategory.name`");
														$qry->execute(array(
															'user_id' => $_SESSION['user_id']
															));
													} else {
														//display all category
														$qry = $db->prepare("SELECT `id`,`name` FROM `tcategory` ORDER BY `name`");
														$qry->execute();
													}
													while ($row=$qry->fetch()) 
													{
														echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
													}
													$query->closeCursor();
													echo '
												</select>
												<div class="space-4"></div>
												<label for="subcat">'.T_('Sous-catégorie').'</label>
												<input name="subcat" type="text" value="" />
											</fieldset>
											';
										}
										elseif($_GET['table']=='tcriticality') //special case for limit service parameters 
										{
											echo'
											<fieldset>
												<label for="number">'.T_('Numéro').'</label>
												<input name="number" type="text" value="" />
												<br /><br />
												<label for="name">'.T_('Nom').'</label>
												<input name="name" type="text" value="" />
												<br /><br />
												<label for="color">'.T_('Couleur').'</label>
												<input name="color" type="text" value="" />
												<br /><br />
											';
											if ($rparameters['user_limit_service'])
											{
												if ($cnt_service==1)
												{
													echo '<input type="hidden" name="service" value="'.$user_services[0].'" />'; 
													
												} else {
													echo '
													<label for="service">'.T_('Service').'</label>
													<select name="service" id="form-field-select-1">
													';
														if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
															//display only service associated with this user
															$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
															$qry->execute(array(
																'user_id' => $_SESSION['user_id'],
																'disable' => 0
																));
														} else {
															//display all services
															$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
															$qry->execute(array(
																'disable' => 0
																));
														}
														while ($row=$qry->fetch()) 
														{
															echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
														}
														$qry->closeCursor();
													echo '
													</select>
													';
												}
											}
											echo '
											<fieldset>
											<div class=\"space-4\"></div>';
										}
										elseif($_GET['table']=='tpriority') //special case for limit service parameters 
										{
											echo'
											<fieldset>
												<label for="number">'.T_('Numéro').'</label>
												<input name="number" type="text" value="" />
												<br /><br />
												<label for="name">'.T_('Nom').'</label>
												<input name="name" type="text" value="" />
												<br /><br />
												<label for="color">'.T_('Couleur').'</label>
												<input name="color" type="text" value="" />
												<br /><br />
											';
											if ($rparameters['user_limit_service'])
											{
												if ($cnt_service==1)
												{
													echo '<input type="hidden" name="service" value="'.$user_services[0].'" />'; 
													
												} else {
													echo '
													<label for="service">'.T_('Service').'</label>
													<select name="service" id="form-field-select-1">
													';
														if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
															//display only service associated with this user
															$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
															$qry->execute(array(
																'user_id' => $_SESSION['user_id'],
																'disable' => 0
																));
														} else {
															//display all services
															$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
															$qry->execute(array(
																'disable' => 0
																));
														}
														while ($row=$qry->fetch()) 
														{
															echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
														}
														$qry->closeCursor();
													echo '
													</select>
													';
												}
											}
											echo '
											<fieldset>
											<div class=\"space-4\"></div>';
										}
										elseif($_GET['table']=='ttypes') //special case for limit service parameters 
										{
											echo'
											<fieldset>
												<label for="name">'.T_('Nom').'</label>
												<input name="name" type="text" value="" />
												<br /><br />
											';
											if ($rparameters['user_limit_service'])
											{
												if ($cnt_service==1)
												{
													echo '<input type="hidden" name="service" value="'.$user_services[0].'" />'; 
													
												} else {
													echo '
													<label for="service">'.T_('Service').'</label>
													<select name="service" id="form-field-select-1">
													';
														if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) {
															//display only service associated with this user
															$qry = $db->prepare("SELECT `tservices.id`,`tservices.name` FROM `tservices`,`tusers_services` WHERE `tservices.id`=`tusers_services.service_id` AND `tusers_services.user_id`=:user_id AND `tservices.disable`=:disable ORDER BY `tservices.name`");
															$qry->execute(array(
																'user_id' => $_SESSION['user_id'],
																'disable' => 0
																));
														} else {
															//display all services
															$qry = $db->prepare("SELECT `id`,`name` FROM `tservices` WHERE `disable`=:disable ORDER BY `name`");
															$qry->execute(array(
																'disable' => 0
																));
														}
														while ($row=$qry->fetch()) 
														{
															echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
														}
														$qry->closeCursor();
													echo '
													</select>
													';
												}
											}
											echo '
											<fieldset>
											<div class=\"space-4\"></div>';
										}
										elseif($_GET['table']=='tassets_model') //special case assets_model
										{
											echo '
											<fieldset>
												<label for="type">'.T_('Type').'</label>
												<select name="type" id="form-field-select-1">
												';
													$qry = $db->prepare("SELECT `id`,`name` FROM `tassets_type` ORDER BY `name`");
													$qry->execute();
													while ($rtype=$qry->fetch()) 
													{
														echo '<option value="'.$rtype['id'].'">'.$rtype['name'].'</option>';
													}
													$qry->closeCursor();
													echo '
												</select>
												<div class="space-4"></div>
												<label for="manufacturer">'.T_('Fabriquant').'</label>
												<select name="manufacturer" id="form-field-select-1">
												';
													$qry = $db->prepare("SELECT `id`,`name` FROM `tassets_manufacturer` ORDER BY `name`");
													$qry->execute();
													while ($rman=$qry->fetch()) 
													{
														echo '<option value="'.$rman['id'].'">'.$rman['name'].'</option>';
													}
													$qry->closeCursor();
													echo '
												</select>
												<div class="space-4"></div>
												<label for="model">'.T_('Modèle').'</label>
												<input name="model" type="text" value="" />
												<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
												<div class="space-4"></div>
												<label for="file1">'.T_('Image').' <span style="font-size: x-small;"><i>(250px x 250px max)</i></span></label>
												<input name="file1" type="file" />
												<div class="space-4"></div>
												';
												if ($rparameters['asset_ip']==1)
												{
													echo '
													<label for="ip">'.T_('Équipement IP').'&nbsp;</label>
													<input type="radio" class="ace" value="1" name="ip"> <span class="lbl"> '.T_('Oui').' </span>
													&nbsp;
													<input type="radio" class="ace" value="0" name="ip"> <span class="lbl"> '.T_('Non').' </span>
													<div class="space-4"></div>
													<label for="wifi">'.T_('Équipement WIFI').'&nbsp;</label>
													<input type="radio" class="ace" value="1" name="wifi"> <span class="lbl"> '.T_('Oui').' </span>
													&nbsp;
													<input type="radio" class="ace" value="0" name="wifi"> <span class="lbl"> '.T_('Non').' </span>
													<div class="space-4"></div>
													';
												} else {echo '<input type="hidden" name="ip" value="0" /><input type="hidden" name="wifi" value="0" />';}
												echo '
												<label for="warranty">'.T_('Nombre d\'années de garantie').'</label>
												<input name="warranty" type="text" size="2" value="0" />
												<br />
											</fieldset>
											';
										} else
										{
											echo "<fieldset>";
											for ($i=1; $i <= $nbchamp; $i++)
											{
												//translate label name
												$label_name=${'champ' . $i}; //default value
												if(${'champ' . $i}=='id') {$label_name=T_('Identifiant');}
												if(${'champ' . $i}=='name') {$label_name=T_('Libellé');}
												if(${'champ' . $i}=='name') {$label_name=T_('Libellé');}
												if(${'champ' . $i}=='cat') {$label_name=T_('Catégorie');}
												if(${'champ' . $i}=='disable') {$label_name=T_('Désactivé');}
												if(${'champ' . $i}=='number') {$label_name=T_('Ordre');}
												if(${'champ' . $i}=='color') {$label_name=T_('Couleur');}
												if(${'champ' . $i}=='description') {$label_name=T_('Description');}
												if(${'champ' . $i}=='mail_object') {$label_name=T_('Objet du mail');}
												if(${'champ' . $i}=='display') {$label_name=T_("Couleur d'affichage");}
												if(${'champ' . $i}=='incident') {$label_name=T_("Numéro ticket");}
												if(${'champ' . $i}=='address') {$label_name=T_("Adresse");}
												if(${'champ' . $i}=='zip') {$label_name=T_("Code postal");}
												if(${'champ' . $i}=='city') {$label_name=T_("Ville");}
												if(${'champ' . $i}=='country') {$label_name=T_("Pays");}
												if(${'champ' . $i}=='limit_ticket_number') {$label_name=T_("Nombre de limite de ticket");}
												if(${'champ' . $i}=='limit_ticket_days') {$label_name=T_("Nombre de limite de jours");}
												if(${'champ' . $i}=='limit_ticket_date_start') {$label_name=T_("Date de début de la limite de jours");}
												if(${'champ' . $i}=='min') {$label_name=T_("Minutes");}
												if(${'champ' . $i}=='virtualization') {$label_name=T_("Virtualisation");}
												if(${'champ' . $i}=='manufacturer') {$label_name=T_("Fabricant");}
												if(${'champ' . $i}=='image') {$label_name=T_("Image");}
												if(${'champ' . $i}=='ip') {$label_name=T_("Équipement IP");}
												if(${'champ' . $i}=='type') {$label_name=T_("Type");}
												if(${'champ' . $i}=='wifi') {$label_name=T_("Équipement WIFI");}
												if(${'champ' . $i}=='warranty') {$label_name=T_("Années de garantie");}
												if(${'champ' . $i}=='order') {$label_name=T_("Ordre");}
												if(${'champ' . $i}=='block_ip_search') {$label_name=T_("Blocage de recherche IP");}
												if(${'champ' . $i}=='mail') {$label_name=T_("Adresse mail");}
												if(${'champ' . $i}=='service') {$label_name=T_("Service");}
												if(${'champ' . $i}=='network') {$label_name=T_("Réseau");}
												if(${'champ' . $i}=='netmask') {$label_name=T_("Masque");}
												if(${'champ' . $i}=='scan') {$label_name=T_("Scan");}
												
												echo "
												<label for=\"${'champ' . $i}\">$label_name</label>
												<input name=\"${'champ' . $i}\" type=\"text\" value=\"\" />
												<div class=\"space-4\"></div>
											
												";
											}
											echo '</fieldset>';
										}
										echo '
										<div class="form-actions center">
											<button type="submit" class="btn btn-sm btn-success">
												'.T_('Ajouter').'
												<i class="icon-arrow-right icon-on-right bigger-110"></i>
											</button>
										</div>
									</form>
								</div>
							</div>
								
						</div>
					</div>
				';
			} else  {
				echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas le droit d'ajouter une entrée sur cette liste, contacter votre administrateur").'.<br></div>';
			}
		}
		// ------------------------------------------------ display selected table ------------------------------------------------
		if ($_GET['action']=="disp_list")
		{
			//check right before display list
			if (
				$rright['admin']!='0' ||
				($cnt_service!='0' && $rparameters['user_limit_service']==1) &&
				(
					($_GET['table']=='tcategory' && $rright['admin_lists_category']!='0') ||
					($_GET['table']=='tsubcat' && $rright['admin_lists_subcat']!='0') ||
					($_GET['table']=='tcriticality' && $rright['admin_lists_criticality']!='0') ||
					($_GET['table']=='tpriority' && $rright['admin_lists_priority']!='0') ||
					($_GET['table']=='ttypes' && $rright['admin_lists_type']!='0')
				)
			)
			{
				echo '
				<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<p>
						<button onclick=\'window.location.href="./index.php?page=admin&amp;subpage=list&amp;table='.$_GET['table'].'&amp;action=disp_add";\' class="btn btn-sm btn-success">
							<i class="icon-plus"></i> '.T_('Ajouter une entrée').'
						</button>
					</p>
				</div>
				<table id="sample-table-1" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>';
							//build title line
							$query = $db->query("DESC $db_table");
							while ($row=$query->fetch())
							{	
								if (($_GET['table']=='tcategory' || $_GET['table']=='tcriticality' || $_GET['table']=='tpriority' || $_GET['table']=='ttypes') && $rparameters['user_limit_service']==0 && $row['Field']=='service') {} else 
								{
									if ($row['Field']!='ldap_guid')
									{
										//translate column name
										$col_name=$row['Field']; //default value
										if($row['Field']=='id') {$col_name=T_('Identifiant');}
										if($row['Field']=='name') {$col_name=T_('Libellé');}
										if($row['Field']=='cat') {$col_name=T_('Catégorie');}
										if($row['Field']=='disable') {$col_name=T_('Désactivé');}
										if($row['Field']=='number') {$col_name=T_('Ordre');}
										if($row['Field']=='color') {$col_name=T_('Couleur');}
										if($row['Field']=='description') {$col_name=T_('Description');}
										if($row['Field']=='mail_object') {$col_name=T_('Objet du mail');}
										if($row['Field']=='display') {$col_name=T_("Couleur d'affichage");}
										if($row['Field']=='incident') {$col_name=T_("Numéro ticket");}
										if($row['Field']=='address') {$col_name=T_("Adresse");}
										if($row['Field']=='zip') {$col_name=T_("Code postal");}
										if($row['Field']=='city') {$col_name=T_("Ville");}
										if($row['Field']=='country') {$col_name=T_("Pays");}
										if($row['Field']=='limit_ticket_number') {$col_name=T_("Nombre de limite de ticket");}
										if($row['Field']=='limit_ticket_days') {$col_name=T_("Nombre de limite de jours");}
										if($row['Field']=='limit_ticket_date_start') {$col_name=T_("Date de début de la limite de jours");}
										if($row['Field']=='min') {$col_name=T_("Minutes");}
										if($row['Field']=='virtualization') {$col_name=T_("Virtualisation");}
										if($row['Field']=='manufacturer') {$col_name=T_("Fabricant");}
										if($row['Field']=='image') {$col_name=T_("Image");}
										if($row['Field']=='ip') {$col_name=T_("Équipement IP");}
										if($row['Field']=='type') {$col_name=T_("Type");}
										if($row['Field']=='wifi') {$col_name=T_("Équipement WIFI");}
										if($row['Field']=='warranty') {$col_name=T_("Années de garantie");}
										if($row['Field']=='order') {$col_name=T_("Ordre");}
										if($row['Field']=='block_ip_search') {$col_name=T_("Blocage de recherche IP");}
										if($row['Field']=='mail') {$col_name=T_("Adresse mail");}
										if($row['Field']=='service') {$col_name=T_("Service");}
										if($row['Field']=='network') {$col_name=T_("Réseau");}
										if($row['Field']=='netmask') {$col_name=T_("Masque");}
										if($row['Field']=='scan') {$col_name=T_("Scan");}
										
										echo '<th>'.$col_name.'</th>';
									}
								}
							}
							$query->closeCursor();
							echo '
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>';
					
					//define order
					if($_GET['table']=='tassets_model'){$order='ORDER BY tassets_model.type,tassets_model.manufacturer ';} 
					elseif ($_GET['table']=='tcategory'){$order='ORDER BY number,service,name';} 
					elseif ($_GET['table']=='tcriticality'){$order='ORDER BY service,number';} 
					elseif ($_GET['table']=='tstates'){$order='ORDER BY number';} 
					elseif ($_GET['table']=='tpriority'){$order='ORDER BY number';} 
					elseif ($_GET['table']=='tassets_state'){$order='ORDER BY `order`';} 
					elseif ($_GET['table']=='tassets_network'){$order='ORDER BY `network`';} 
					elseif ($_GET['table']=='ttime'){$order='ORDER BY min';} 
					else {$order='ORDER BY name';}
					
					
					if ($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1){
						$where_service_list=str_replace('tincidents.u_service','service',$where_service);
						if ($_GET['table']=='tsubcat') {
							$query="SELECT tsubcat.id,tsubcat.cat,tsubcat.name FROM `tsubcat`,`tcategory` WHERE tsubcat.cat=tcategory.id $where_service_list ORDER BY tsubcat.name";
						} else {
							$query="SELECT * FROM $db_table WHERE 1=1 $where_service_list $order";
						}
					} else {$query="SELECT * FROM $db_table $order";} 
					
					//build each line
					if ($rparameters['debug']==1) {echo '<b>QUERY:</b> '.$query;}
					$query = $db->query($query);
					while ($row=$query->fetch()) 
					{
						echo '
						<tr >
						';
						for($i=0; $i < $nbchamp1; ++$i)
						{
							//special case to customize table display, $i var represent column
							if($_GET['table']=='tsubcat' && $i==1)
							{
								$qry2 = $db->prepare("SELECT `name` FROM `tcategory` WHERE id=:id");
								$qry2->execute(array(
									'id' => $row[$i]
									));
								$rcat=$qry2->fetch();
								$qry2->closeCursor();
								echo '<td onclick=\'window.location.href="index.php?page=admin&subpage=list&table='.$_GET['table'].'&action=disp_edit&id='.$row['id'].'";\' >'.$rcat['name'].'</td>';
							} 
							elseif($_GET['table']=='tassets_model' && $i==1)
							{
								$qry2 = $db->prepare("SELECT `name` FROM `tassets_type` WHERE id=:id");
								$qry2->execute(array(
									'id' => $row[$i]
									));
								$ratype=$qry2->fetch();
								$qry2->closeCursor();
								echo '<td onclick=\'window.location.href="index.php?page=admin&subpage=list&table='.$_GET['table'].'&action=disp_edit&id='.$row['id'].'";\' >'.$ratype['name'].'</td>';
							} 
							elseif($_GET['table']=='tassets_model' && $i==2)
							{
								$qry2 = $db->prepare("SELECT `name` FROM `tassets_manufacturer` WHERE id=:id");
								$qry2->execute(array(
									'id' => $row[$i]
									));
								$raman=$qry2->fetch();
								$qry2->closeCursor();
								echo '<td onclick=\'window.location.href="index.php?page=admin&subpage=list&table='.$_GET['table'].'&action=disp_edit&id='.$row['id'].'";\' >'.$raman['name'].'</td>';
							}elseif((($_GET['table']=='tcategory' && $i==3) || ($_GET['table']=='tcriticality' && $i==4) || ($_GET['table']=='tpriority' && $i==4) || ($_GET['table']=='ttypes' && $i==2)) && $rparameters['user_limit_service']==0)
							{
							}elseif((($_GET['table']=='tcategory' && $i==3) || ($_GET['table']=='tcriticality' && $i==4) || ($_GET['table']=='tpriority' && $i==4) || ($_GET['table']=='ttypes' && $i==2)) && $rparameters['user_limit_service']==1)
							{
								$qry2 = $db->prepare("SELECT `name` FROM `tservices` WHERE id=:id");
								$qry2->execute(array(
									'id' => $row[$i]
									));
								$row2=$qry2->fetch();
								$qry2->closeCursor();
								echo '<td onclick=\'window.location.href="index.php?page=admin&subpage=list&table='.$_GET['table'].'&action=disp_edit&id='.$row['id'].'";\' >'.$row2['name'].'</td>';
							}elseif(($_GET['table']=='tagencies' && $i==3) || ($_GET['table']=='tservices' && $i==2)) //hide ldap_guid
							{
							}else{
								echo '<td onclick=\'window.location.href="index.php?page=admin&subpage=list&table='.$_GET['table'].'&action=disp_edit&id='.$row['id'].'";\' >';
								if($row[$i]!='') {echo T_($row[$i]);} else {echo $row[$i];}
								echo '</td>';
							}
						}
						echo '
							<td>
								<button title="'.T_('Éditer').'" onclick=\'window.location.href="./index.php?page=admin&amp;subpage=list&amp;table='.$_GET['table'].'&amp;action=disp_edit&amp;id='.$row['id'].'";\'class="btn btn-xs btn-warning">
									<i class="icon-pencil bigger-120"></i>
								</button>
								';
								if(($_GET['table']!='tstates' || $row['id']>6) && $row['id']!=0 && ($_GET['table']!='tassets_iface_role' || $row['id']>2) && ($_GET['table']!='tassets_state' || $row['id']>4)) 
								{
									echo '
									<button title="'.T_('Supprimer').'" onclick=\'window.location.href="./index.php?page=admin&amp;subpage=list&amp;table='.$_GET['table'].'&amp;id='.$row['id'].'&amp;action=delete";\' class="btn btn-xs btn-danger">
										<i class="icon-trash bigger-120"></i>
									</button>
									';
								}							
								 echo "
							</td>
						</tr>";
					}
					$query->closeCursor();
					echo '
					</tbody>
				</table>
				<br /><br /><br /><br /><br /><br /><br /><br /><br />
				';
			} else {
				echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas accès à cette liste ou vous ne disposer d'aucun service associé, contacter votre administrateur").'.<br></div>';
			}
		}
		//display color informations and information on critical table
		if(($_GET['table']=='tcriticality' || $_GET['table']=='tpriority') && ($_GET['action']=='disp_edit' || $_GET['action']=='disp_add'))
		{
			echo '<u>'.T_('Liste des couleurs par défaut').':</u><br />';
			echo '<b><div style="color:#82af6f">#82af6f</div></b>';
			echo '<b><div style="color:#f8c806">#f8c806</div></b>';
			echo '<b><div style="color:#f89406">#f89406</div></b>';
			echo '<b><div style="color:#d15b47">#d15b47</div></b>';
			echo '<br /><i class="icon-question-sign blue bigger-110"></i> '.T_("Le numéro permet de sélectionner l'ordre de trie");
		}
		if($_GET['table']=='tstates' && ($_GET['action']=='disp_edit' || $_GET['action']=='disp_add'))
		{
			echo '<u>'.T_('Liste des styles par défaut').':</u><br />';
			echo '<span class="label label-sm label-important arrowed-in arrowed-right arrowed-left">label label-sm label-important arrowed-in arrowed-right arrowed-left</span><br />';
			echo '<span class="label label-sm label-info arrowed-in">label label-sm label-info arrowed-in</span><br />';
			echo '<span class="label label-sm label-warning arrowed-in">label label-sm label-warning arrowed-in</span><br />';
			echo '<span class="label label-sm label-pink arrowed arrowed-right arrowed-left">label label-sm label-pink arrowed arrowed-right arrowed-left</span><br />';
			echo '<span class="label label-sm label-success arrowed arrowed-right arrowed-left">label label-sm label-success arrowed arrowed-right arrowed-left</span><br />';
			echo '<span class="label label-sm label-inverse arrowed arrowed-right arrowed-left">label label-sm label-inverse arrowed arrowed-right arrowed-left</span><br />';
		}
		?>
	</div>
</div>