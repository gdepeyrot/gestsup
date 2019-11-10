<?php
################################################################################
# @Name : ticket_stat.php
# @Description : Display Tickets Statistics
# @call : /stat.php
# @parameters : 
# @Author : Flox
# @Create : 25/01/2016
# @Update : 27/07/2017
# @Version : 3.1.24
################################################################################

if ($rparameters['debug']==1) {echo '<u><b>DEBUG MODE:</b></u><br /><b>VAR</b> where_service='.$where_service.' where_agency='.$where_agency.' POST_service='.$_POST['service'].' POST_agency='.$_POST['agency'].'';}
?>

<form method="post" action="" name="filter" >
	<center>
		<small><?php echo T_('Filtre global'); ?>:</small>
		<select name="tech" onchange="submit()">
			<?php
			if ($_POST['tech']=='%') {echo '<option value="%" selected >'.T_('Tous les techniciens').'</option>';} else {echo '<option value="%" >'.T_('Tous les techniciens').'</option>';}											
			//case limit user service
			if ($rparameters['user_limit_service']==1 && $rright['admin']==0)
			{
				//case technician with agency et service
				$where_service2=str_replace('AND tincidents.u_service','service_id', $where_service);
				$where_service2=str_replace('AND', '', $where_service2);
				if($cnt_service>1 && $cnt_agency!=0)
				{
					$where_service2=preg_replace('/OR/', '', $where_service2, 1); //case user have single service and agency
					echo "CASE1";
				} elseif($cnt_service==1) {
					$where_service2=str_replace('OR', '', $where_service2); //case user have single service and agency
				}
				//case user have service and agency
				if($cnt_service==1 && $cnt_agency!=0) {
					$where_service=str_replace('OR', 'AND', $where_service); 
				} elseif ($cnt_service>1 && $cnt_agency!=0)
				{
					$where_service=preg_replace('/OR/', 'AND', $where_service, 1); 
				}
				//case user with only-one agency
				if($cnt_agency!=0 && $cnt_service==0)
				{
					$where_service2=' 1=1';
				}
				
				$where_service2=str_replace('tincidents.u_service', 'service_id', $where_service2);
				$query="SELECT id,firstname,lastname FROM tusers WHERE id IN (SELECT user_id FROM tusers_services WHERE $where_service2 ) AND profile='0' AND disable='0' ORDER BY lastname";
				$query = $db->query($query);
			} else {
				//display admin in technician liste
				if($rright['ticket_tech_admin']!=0)
				{
					$query = $db->query("SELECT id,firstname,lastname FROM tusers WHERE (profile='0' OR profile='4') AND disable=0 ORDER BY lastname");
				} else {
					$query = $db->query("SELECT id,firstname,lastname FROM tusers WHERE profile='0' AND disable=0 ORDER BY lastname");
				}
			}				
			while ($row=$query->fetch()) {
				if ($row['id'] == $_POST['tech']) {$selected1='selected';} else {$selected1='';}
				echo '<option value="'.$row['id'].'" '.$selected1.'>'.$row['firstname'].' '.$row['lastname'].'</option>'; 
			} 
			$query->closeCursor();
			?>
		</select>
		<select name="service" onchange="submit()">
			<?php
			if ($_POST['service']=='%') {echo '<option value="%" selected>'.T_('Tous les services').'</option>';} else {echo '<option value="%" >'.T_('Tous les services').'</option>';}											
			//case limit user service
			if ($rparameters['user_limit_service']==1 && $rright['admin']==0)
			{
				$query = $db->query("SELECT id,name FROM tservices WHERE id IN (SELECT service_id FROM tusers_services WHERE user_id='$_SESSION[user_id]') AND disable=0 ORDER BY name");
			} else {
				$query = $db->query("SELECT id,name FROM tservices WHERE disable=0 ORDER BY name");
			}
			while ($row=$query->fetch()) {
				if ($row['id'] == $_POST['service']) {$selected2='selected';} else {$selected2='';} 
				echo '<option value="'.$row['id'].'" '.$selected2.'>'.$row['name'].'</option>';
			} 
			$query->closeCursor();
			?>
		</select>
		<?php
		if($rparameters['user_agency']==1)
		{
			echo ' 
			<select style="width:150px;" name="agency" onchange="submit()">';
				if ($_POST['agency']=='%') {echo '<option value="%" selected>'.T_('Toutes les agences').'</option>';} else {echo '<option value="%" >'.T_('Toutes les agences').'</option>';}											
				$query = $db->query("SELECT id,name FROM tagencies WHERE disable=0 AND id!=0 ORDER BY name");				
				while ($row=$query->fetch()) {
					if ($row['id']==$_POST['agency']) {$selected2='selected';} else {$selected2='';}
					echo '<option value="'.$row['id'].'" '.$selected2.'>'.$row['name'].'</option>'; 
				} 
				$query->closeCursor();
				echo'	
			</select>';
		}
		if($rparameters['ticket_type']==1)
		{
			echo ' 
			<select name="type" onchange="submit()">';
				if ($_POST['type']=='%') {echo '<option value="%" selected>'.T_('Tous les types').'</option>';} else {echo '<option value="%" >'.T_('Tous les type').'</option>';}											
				$query = $db->query("SELECT id,name FROM ttypes ORDER BY name");				
				while ($row=$query->fetch()) {
					if ($row['id']==$_POST['type']) {$selected2='selected';} else {$selected2='';}
					echo '<option value="'.$row['id'].'" '.$selected2.'>'.$row['name'].'</option>'; 
				} 
				$query->closeCursor();
				echo'	
			</select>';
		}
		?>
		<select name="criticality" onchange="submit()">
			<?php
			if ($_POST['criticality']=='%') {echo '<option value="%" selected>'.T_('Toutes les criticités').'</option>';} else {echo '<option value="%" >'.T_('Toutes les criticités').'</option>';}																					
			//case limit user service
			if ($rparameters['user_limit_service']==1 && $rright['admin']==0)
			{
				$query="SELECT id,name FROM tcriticality WHERE service IN (SELECT service_id FROM tusers_services WHERE user_id='$_SESSION[user_id]') ORDER BY number";
				$query = $db->query($query);
			} else {
				$query = $db->query("SELECT id,name FROM tcriticality ORDER BY number");
			}			
			while ($row=$query->fetch()) {
				if ($row['id'] == $_POST['criticality']) {$selected2='selected';} else {$selected2='';}
				echo '<option value="'.$row['id'].'" '.$selected2.'>'.$row['name'].'</option>'; 
			} 
			$query->closeCursor();
			?>
		</select> 
		<select name="category" onchange="submit()">
		<?php
			if ($_POST['category']=='%') {echo '<option value="%" selected>'.T_('Toutes les catégories').'</option>';} else {echo '<option value="%" >'.T_('Toutes les catégories').'</option>';}	
			//case limit user service
			if ($rparameters['user_limit_service']==1 && $rright['admin']==0)
			{
				echo "SELECT tcategory.id,tcategory.name FROM tcategory,tincidents WHERE tcategory.id=tincidents.category $where_service ORDER BY tcategory.name";
				$query = $db->query("SELECT id,name FROM tcategory WHERE service IN (SELECT service_id FROM tusers_services WHERE user_id='$_SESSION[user_id]') ORDER BY tcategory.name");
			} else {
				$query = $db->query("SELECT id,name FROM tcategory ORDER BY name");
			}				
			while ($row=$query->fetch()) {
				if ($row['id'] == $_POST['category']) {$selected2='selected';} else {$selected2='';}
				echo '<option value="'.$row['id'].'" '.$selected2.'>'.$row['name'].'</option>'; 
			} 
			$query->closeCursor();																			
			?>
		</select> 
		<select name="month" onchange="submit()">
			<option value="%" <?php if ($_POST['month'] == '%')echo "selected" ?>><?php echo T_('Tous les mois'); ?></option>
			<option value="01"<?php if ($_POST['month'] == '1')echo "selected" ?>><?php echo T_('Janvier'); ?></option>
			<option value="02"<?php if ($_POST['month'] == '2')echo "selected" ?>><?php echo T_('Février'); ?></option>
			<option value="03"<?php if ($_POST['month'] == '3')echo "selected" ?>><?php echo T_('Mars'); ?></option>
			<option value="04"<?php if ($_POST['month'] == '4')echo "selected" ?>><?php echo T_('Avril'); ?></option>
			<option value="05"<?php if ($_POST['month'] == '5')echo "selected" ?>><?php echo T_('Mai'); ?></option>
			<option value="06"<?php if ($_POST['month'] == '6')echo "selected" ?>><?php echo T_('Juin'); ?></option>
			<option value="07"<?php if ($_POST['month'] == '7')echo "selected" ?>><?php echo T_('Juillet'); ?></option>
			<option value="08"<?php if ($_POST['month'] == '8')echo "selected" ?>><?php echo T_('Aout'); ?></option>
			<option value="09"<?php if ($_POST['month'] == '9')echo "selected" ?>><?php echo T_('Septembre'); ?></option>
			<option value="10"<?php if ($_POST['month'] == '10')echo "selected" ?>><?php echo T_('Octobre'); ?></option>
			<option value="11"<?php if ($_POST['month'] == '11')echo "selected" ?>><?php echo T_('Novembre'); ?></option>	
			<option value="12"<?php if ($_POST['month'] == '12')echo "selected" ?>><?php echo T_('Décembre'); ?></option>	
		</select>
		<select name="year" onchange="submit()">
			<?php
			echo '<option value="%"'; if ($_POST['year'] == '%') {echo 'selected';} echo ' >'.T_('Toutes les années').'</option>';
			$q1= $db->query("SELECT distinct year(date_create) as year FROM `tincidents` WHERE date_create not like '0000-00-00 00:00:00' ORDER BY year(date_create)");
			while ($row=$q1->fetch()) 
			{ 
				$selected=0;
				if ($_POST['year']==$row['year']) $selected="selected";  
				echo "<option value=$row[year] $selected>$row[year]</option>";
			}
			$q1->closeCursor();
			?>
		</select>
	</center>
</form>
<br /><br />
<?php
	//call all graphics files from ./stats directory
	require('./stats/line_tickets.php');
	echo "<br />";
	//echo "<a name=\"chart4\"></a>";
	//require('./stats/line_tickets_activity.php');
	//echo "<br />";
	echo "<a name=\"chart1\"></a>";
	require('./stats/pie_tickets_tech.php');
	echo "<br />";
	echo "<a name=\"chart3\"></a>";
	echo "<hr />";
	require('./stats/pie_states.php');
	echo "<br ";
	echo "<a name=\"chart2\"></a>";
	echo "<hr />";
	require('./stats/pie_cat.php');
	echo "<br />";
	//display pie service if exist services
	$qservice = $db->query("SELECT count(*) FROM tservices WHERE id!=0");
	$rservice=$qservice->fetch();
	$query->closeCursor(); 
	if ($rservice[0]>0)
	{
		echo "<a name=\"chart7\"></a>";
		echo "<hr />";
		require('./stats/pie_services.php');
		echo "<br />";
	}
	if ($company_filter==1 && $rparameters['user_advanced']==1)
	{
		echo "<a name=\"chart8\"></a>";
		echo "<hr />";
		require('./stats/pie_company.php');
		echo "<br />";
	}
	echo "<a name=\"chart6\"></a>";
	echo "<hr />";
	require('./stats/pie_load.php');
	echo "<br />";
	echo "<hr />";
	require('./stats/histo_load.php');
	echo "<hr />";
	require('./stats/tables.php');	
?>