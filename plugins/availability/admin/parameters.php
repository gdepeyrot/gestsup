<?php
################################################################################
# @Name : ./plugins/availability/admin/parameters.php
# @Description : admin parameters for availability plugins
# @call : /admin/parameters.php
# @Author : Flox
# @Create : 28/04/2015
# @Update : 28/03/2016
# @Version : 3.1.19
################################################################################

//initialize variables 
if(!isset($_POST['subcat'])) $_POST['subcat']= ''; 
if(!isset($_POST['depsubcat'])) $_POST['depsubcat']= ''; 

?>
<div class="profile-info-row">
	<div class="profile-info-name"> 
		<i class="icon-time"></i>
		<?php echo T_('Disponibilité'); ?>:
	</div>
	<div class="profile-info-value">
			<label>
				<input class="ace" type="checkbox" <?php if ($rparameters['availability']==1) echo "checked"; ?> name="availability" value="1">
				<span class="lbl">&nbsp;<?php echo T_('Activer la fonction Disponibilité'); ?></span>
				<i title="<?php echo T_('Active le suivi des catégories afin de produire des statistiques de disponibilité '); ?>." class="icon-question-sign blue"></i>
			</label>
			<?php
			    if ($rparameters['availability']==1)
			    {
		    	    echo '
		    	        <div class="space-4"></div>
		    	        &nbsp; &nbsp; &nbsp;<i class="icon-circle green"></i> 1 - '.T_('Surveiller toutes les catégories').':&nbsp;
		    	        <label for="availability_all_cat">
							<input type="radio" class="ace" value="1" name="availability_all_cat"'; if ($rparameters['availability_all_cat']==1) {echo "checked";} echo '> <span class="lbl"> '.T_('Oui').' </span>
							<input type="radio" class="ace" value="0" name="availability_all_cat"'; if ($rparameters['availability_all_cat']==0) {echo "checked";} echo '  > <span class="lbl"> '.T_('Non').' </span>
						</label>
		    	    ';
		    	    if ($rparameters['availability_all_cat']==0)
		    	    {
		    	        echo '
		    	            <div class="space-4"></div>
		    	            &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		    	            <i class="icon-caret-right blue"></i> '.T_('Sélection des catégories ou sous-catégories à surveiller').':<br />
		    	            ';
		    	            //display availability list
					        $query = $db->query("SELECT * FROM `tavailability`");
					    	while ($row = $query->fetch())
							{
								$cname= $db->query("SELECT name FROM `tcategory` WHERE id='$row[category]'"); 
								$cname= $cname->fetch();
								if ($row['subcat']!='')
								{
									$sname= $db->query("SELECT name FROM `tsubcat` WHERE id='$row[subcat]'"); 
									$sname= $sname->fetch();
								} else {$sname='';}
								echo '
									&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
									<i class="icon-caret-right green"></i> ('.$cname['name'].' > '.$sname[0].') 
									<a title="'.T_('Supprimer cette catégorie').'" href="./index.php?page=admin&subpage=parameters&tab=function&deleteavailability='.$row['id'].'"><i class="icon-trash red bigger-120"></i></a>
									<br />
								';
                             }
							//display add category form
							echo'
							&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		    	            <i class="icon-caret-right blue"></i> 
							'.T_('Ajouter la catégorie').':
							<select name="category" onchange="submit()" style="width:100px; display:inline;" >
								<option value="%"></option>';
								$query = $db->query("SELECT * FROM tcategory ORDER BY name");
								while ($row=$query->fetch()) 
								{
								if ($_POST['category']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
								}
							echo'
							</select>
						    	&nbsp;'.T_('et la Sous-Catégorie').':
							<select name="subcat" onchange="submit()" style="width:90px display:inline;">
								<option value="%"></option>';
								echo "-$_POST[category]-";
								if($_POST['category']!='%' && $_POST['category']!='')
								{
									$query = $db->query("SELECT * FROM tsubcat WHERE cat LIKE $_POST[category] ORDER BY name");}
								else
								{$query = $db->query("SELECT * FROM tsubcat ORDER BY name");}
								
								while ($row = $query->fetch())
								{
									if ($_POST['subcat']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
								} 
								echo '
							</select>
							<div class="space-4"></div>
							';
		    	    }
		    	    echo '
		    	    <div class="space-4"></div>
		    	    &nbsp; &nbsp; &nbsp;<i class="icon-circle green"></i>
		    	    2 - '.T_('Condition de prise en compte d\'un ticket').':&nbsp;
		    	    <select name="availability_condition_type" onchange="submit()" style="display:inline;">
		    	        <option value="" '; if($_POST['availability_condition_type']=='' && $rparameters['availability_condition_type']=='') {echo ' selected';} echo ' ></option>
		    	        <option value="types" '; if($_POST['availability_condition_type']=='types' ) {echo ' selected';} echo ' >'.T_('Type').'</option>
		    	        <option value="criticality" '; if($_POST['availability_condition_type']=='criticality') {echo ' selected';} echo '>'.T_('Criticité').'</option>
		    	    </select> 
		    	    ';
			    	if($_POST['availability_condition_type'] || $rparameters['availability_condition_value']!='' )
		    	    {
		    	    	if ($_POST['availability_condition_type']) 
						{
							if ($_POST['availability_condition_type']!='') {$table="$_POST[availability_condition_type]";} else {$table='criticality';}
						} else {
							if ($rparameters['availability_condition_type']!='') {$table="$rparameters[availability_condition_type]";} else {$table='criticality';}
						}
						//check $table value
						if ($table!='criticality' && $table!='types') {$table='criticality';}
						
		    	        $query = $db->query("SELECT * FROM t$table ORDER BY name");
		    	        echo 'est ';
		    	        echo '<select name="availability_condition_value" >';
							while ($row = $query->fetch())
							{
								echo '<option '; if($rparameters['availability_condition_value']==$row['id']) {echo 'selected';} echo ' value="'.$row['id'].'">'.T_($row['name']).'</option>';
							} 
						echo '</select>';
		    	    }
		    	    echo'
		    	    <div class="space-4"></div>
		    	    &nbsp; &nbsp; &nbsp;<i class="icon-circle green"></i>
		    	    3 - '.T_('Dépendances').':&nbsp;
		    	    <label for="availability_dep">
							<input type="radio" class="ace" value="1" name="availability_dep"'; if ($rparameters['availability_dep']==1) {{echo "checked";}} echo '> <span class="lbl"> '.T_('Oui').' </span>
							<input type="radio" class="ace" value="0" name="availability_dep"'; if ($rparameters['availability_dep']==0) echo "checked"; echo '  > <span class="lbl"> '.T_('Non').' </span>
					</label>
					<i title="'.T_('Permet de définir des sous-catégories qui seront comptabilisées dans toutes les statistiques si elles possèdent la même condition. (ex: un ticket réseau critique, entraine une indisponibilité d\'une application)').'" class="icon-question-sign blue"></i>
					';
				    if ($rparameters['availability_dep']==1)
		    	    {
		    	        echo '
		    	            <div class="space-4"></div>
		    	            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		    	            <i class="icon-caret-right blue"></i> '.T_('Liste des sous-catégories impactant toutes les catégories surveillées').':<br />
		    	            ';
		    	            //display availability dependency list
					        $query = $db->query("SELECT * FROM `tavailability_dep`");
					    	while ($row = $query->fetch())
							{
								$cname= $db->query("SELECT name FROM `tcategory` WHERE id='$row[category]'"); 
								$cname= $cname->fetch();
								if ($row['subcat']!='')
								{
									$sname= $db->query("SELECT name FROM `tsubcat` WHERE id='$row[subcat]'"); 
									$sname= $sname->fetch();
								} else {$sname='';}
								echo '
									&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
									<i class="icon-caret-right green"></i> ('.$cname['name'].' > '.$sname[0].') 
									<a title="'.T_('Supprimer cette catégorie').'" href="./index.php?page=admin&subpage=parameters&tab=function&deleteavailabilitydep='.$row['id'].'"><i class="icon-trash red bigger-120"></i></a>
									<br />
								';
                             }
		    	            //display add cat form
							echo'
							&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		    	            <i class="icon-caret-right blue"></i> 
							'.T_('Ajouter la catégorie').':
							<select name="depcategory" onchange="submit()" style="width:100px; display:inline;" >
								<option value="%"></option>';
								$query = $db->query("SELECT * FROM tcategory ORDER BY name");
								while ($row = $query->fetch()) 
								{
									if ($_POST['depcategory']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
								}
							echo'
							</select>
						    	&nbsp;'.T_('et la Sous-Catégorie').':
							<select name="depsubcat" onchange="submit()" style="width:90px display:inline;">
								<option value="%"></option>';
								if($_POST['depcategory'])
								{
									echo "SELECT * FROM tsubcat WHERE cat LIKE '$_POST[depcategory]' ORDER BY name";
									$query = $db->query("SELECT * FROM tsubcat WHERE cat LIKE '$_POST[depcategory]' ORDER BY name");}
								else
								{$query = $db->query("SELECT * FROM tsubcat ORDER BY name");}
								while ($row = $query->fetch())
								{
									if ($_POST['depsubcat']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
								} 
								echo '
							</select>
							<div class="space-4"></div>
							';
		    	    }
		    	    //target tx part
		    	   	echo'
		    	    <div class="space-4"></div>
		    	    &nbsp; &nbsp; &nbsp;<i class="icon-circle green"></i>
		    	    4 - '.T_('Définition des taux de disponibilités cible par années, par sous-catégories').':&nbsp;
					<i title="'.T_('Permet de fixer des objectifs de disponibilité, pour chaque sous catégorie, qui peuvent fluctuer chaque année').'." class="icon-question-sign blue"></i>
				   	<br />
				   ';
					//find tickets year and display subcat 
					$queryyears = $db->query("SELECT DISTINCT YEAR(date_create) FROM tincidents ORDER BY YEAR(date_create) DESC");
					while ($rowyear=$queryyears->fetch())
					{
						echo ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i class="icon-caret-right blue"></i> <b>'.$rowyear[0].'</b> <br />';
						$querysubcat = $db->query("SELECT * FROM `tavailability`");
					    while ($rowsubcat=$querysubcat->fetch())
					    {
					    	//get subcat name
					   		$sname= $db->query("SELECT name FROM `tsubcat` WHERE id='$rowsubcat[subcat]'"); 
							$sname= $sname->fetch();
							
							//get target tx data from tavailability_target table
							$targettx= $db->query("SELECT * FROM `tavailability_target` WHERE subcat='$rowsubcat[subcat]' AND year='$rowyear[0]'"); 
							$targettx= $targettx->fetch();
							
							echo ' &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; <i class="icon-caret-right green"></i> <u>'.$sname[0].'</u> 
							'.T_('taux de disponibilité cible').': 
							<input type="text" size="4" name="target_'.$rowyear[0].'_'.$rowsubcat['subcat'].'" value="'.$targettx['target'].'" />
							%
							<br />';
					    }
					} 
			    }
		?>
	</div>
</div>