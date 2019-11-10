<?php
################################################################################
# @Name : asset_stat.php
# @Description : Display Assets Statistics
# @Call : /stat.php
# @Parameters : 
# @Author : Flox
# @Create : 25/01/2016
# @Update : 13/09/2017
# @Version : 3.1.26
################################################################################

//initialize variables 
if(!isset($_POST['model'])) $_POST['model']='';

?>

<form method="post" action="" name="filter" >
	<center>
	<small><?php echo T_('Filtre global'); ?>:</small>
	<select name="tech" onchange=submit()>
		<?php
		$query = $db->query("SELECT * FROM tusers WHERE (profile=0 OR profile=4) and disable=0 ORDER BY lastname");				
		while ($row=$query->fetch()) {
			if ($row['id'] == $_POST['tech']) $selected1="selected" ;
			if ($row['id'] == $_POST['tech']) $find="1" ;
			echo "<option value=\"$row[id]\" $selected1>$row[firstname] $row[lastname]</option>"; 
			$selected1="";
		} 
		$query->closeCursor();
		if ($find!="1") {echo '<option value="%" selected >'.T_('Tous les techniciens').'</option>';} else {echo '<option value="%" >'.('Tous les techniciens').'</option>';}											
		?>
	</select>
	<select name="service" onchange=submit()>
		<?php
		$query = $db->query("SELECT * FROM tservices WHERE disable=0 ORDER BY name");				
		while ($row=$query->fetch()) {
			if ($row['id'] == $_POST['service']) {$selected2="selected";}
			echo "<option value=\"$row[id]\" $selected2>$row[name]</option>"; 
			$selected2="";
		} 
		$query->closeCursor();
		if ($_POST['service']=="%") {echo '<option value="%" selected>'.T_('Tous les services').'</option>';} else {echo '<option value="%" >'.T_('Tous les services').'</option>';}											
		?>
	</select>
	<?php
	if($company_filter==1)
	{
		echo '
			<select name="company" onchange=submit()>
				';
				$query = $db->query("SELECT * FROM tcompany WHERE disable='0' ORDER BY name");				
				while ($row=$query->fetch()) {
					if ($row['id'] == $_POST['company']) {$selected2="selected";}
					echo "<option value=\"$row[id]\" $selected2>$row[name]</option>"; 
					$selected2="";
				} 
				$query->closeCursor();
				if ($_POST['company']=="%") {echo '<option value="%" selected>'.T_('Toutes les sociétés').'</option>';} else {echo '<option value="%" >'.T_('Toutes les sociétés').'</option>';}											
			echo '
			</select>
		';
	}
	?>
	<select name="type" onchange=submit()>
	<?php
	$query = $db->query("SELECT * FROM tassets_type ORDER BY name");				
	while ($row=$query->fetch()) {
		if ($row['id'] == $_POST['type']) $selected2="selected" ;
		if ($row['id'] == $_POST['type']) $find="1";
		echo "<option value=\"$row[id]\" $selected2>$row[name]</option>"; 
		$selected2="";
	} 
	$query->closeCursor();
	echo '<option '; if ($_POST['type']=='%') echo 'selected'; echo' value="%" >'.T_('Tous les types').'</option>';										
	?>
	</select> 
	<select name="model" onchange=submit()>
	<?php
	$query = $db->query("SELECT * FROM tassets_model ORDER BY type");				
	while ($row=$query->fetch()) {
		if ($row['id'] == $_POST['model']) $selected2="selected" ;
		if ($row['id'] == $_POST['model']) $find="1";
		echo "<option value=\"$row[id]\" $selected2>$row[name]</option>"; 
		$selected2="";
	} 
	$query->closeCursor();
	echo '<option '; if ($_POST['model']=='%') echo 'selected'; echo' value="%" >'.T_('Tous les modèles').'</option>';										
	?>
	</select> 
	
	<select name="month" onchange=submit()>
		<option value="%" <?php if ($_POST['month'] == '%')echo "selected" ?>><?php echo T_('Tous les mois'); ?></option>
		<option value="01" <?php if ($_POST['month'] == '1')echo "selected" ?>><?php echo T_('Janvier'); ?></option>
		<option value="02" <?php if ($_POST['month'] == '2')echo "selected" ?>><?php echo T_('Février'); ?></option>
		<option value="03" <?php if ($_POST['month'] == '3')echo "selected" ?>><?php echo T_('Mars'); ?></option>
		<option value="04" <?php if ($_POST['month'] == '4')echo "selected" ?>><?php echo T_('Avril'); ?></option>
		<option value="05" <?php if ($_POST['month'] == '5')echo "selected" ?>><?php echo T_('Mai'); ?></option>
		<option value="06" <?php if ($_POST['month'] == '6')echo "selected" ?>><?php echo T_('Juin'); ?></option>
		<option value="07" <?php if ($_POST['month'] == '7')echo "selected" ?>><?php echo T_('Juillet'); ?></option>
		<option value="08" <?php if ($_POST['month'] == '8')echo "selected" ?>><?php echo T_('Aout'); ?></option>
		<option value="09" <?php if ($_POST['month'] == '9')echo "selected" ?>><?php echo T_('Septembre'); ?></option>
		<option value="10" <?php if ($_POST['month'] == '10')echo "selected" ?>><?php echo T_('Octobre'); ?></option>
		<option value="11" <?php if ($_POST['month'] == '11')echo "selected" ?>><?php echo T_('Novembre'); ?></option>	
		<option value="12" <?php if ($_POST['month'] == '12')echo "selected" ?>><?php echo T_('Décembre'); ?></option>	
	</select>

	<select name="year" onchange=submit()>
		<?php
		$q1= $db->query("SELECT distinct year(date_install) as year FROM `tassets` WHERE date_install not like '0000-00-00' ORDER BY year(date_install)");
		while ($row=$q1->fetch()) 
		{ 
			$selected=0;
			if ($_POST['year']==$row['year']) $selected="selected";  
			echo "<option value=$row[year] $selected>$row[year]</option>";
		}
		$q1->closeCursor();
		?>
		<option value="%" <?php if ($_POST['year'] == '%')echo "selected" ?>><?php echo T_('Toutes les années'); ?></option>
	</select>
	</center>
</form>

<br /><br />
<?php
	//call all graphics files from ./stats directory
	require('./stats/line_assets.php');
	echo "<br />";
	echo "<hr />";
	require('./stats/pie_assets_service.php');
	echo "<br />";
	echo "<hr />";
	require('./stats/pie_assets_type.php');
?>