<?php
################################################################################
# @Name : tables.php
# @Description : Display Statistics in table format with different queries
# @Call : /stat.php
# @Parameters : 
# @Author : Flox
# @Create : 15/02/2014
# @Update : 04/01/2018
# @Version : 3.1.29
################################################################################

echo '
<div id="container" style="min-width: 300px; min-height: 300px; margin: 0 auto">
	<div class="col-xs-12 col-sm-3 ui-sortable">
		<div class="widget-box" style="opacity: 1; z-index: 0;">
			<div class="widget-header header-color-blue">
				<h5 class="bigger lighter">
					'.T_('Délais moyen de résolution').'
				</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<th>
									<i class="icon-user"></i>
									'.T_('Techniciens').'
								</th>
								<th>
									<i class="icon-calendar"></i>
									'.T_('jours').'
								</th>
							</tr>
						</thead>
						<tbody>
						';
						$query="
						SELECT tusers.firstname, tusers.lastname, ROUND(AVG(TO_DAYS(date_res) - TO_DAYS(date_create))) as jour
						FROM tincidents
						INNER JOIN tusers ON (tincidents.technician=tusers.id )
						WHERE tincidents.technician NOT LIKE '0' AND
						tincidents.date_res NOT LIKE '0000-00-00' AND
						tincidents.date_create NOT LIKE '0000-00-00' AND
						tincidents.state='3' AND
						tincidents.type LIKE '$_POST[type]' AND
						tincidents.criticality like '$_POST[criticality]' AND
						tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
						tincidents.category LIKE '$_POST[category]' AND
						tincidents.date_create LIKE '%-$_POST[month]-%' AND
						tincidents.date_create LIKE '$_POST[year]-%' AND
						tincidents.disable='0' AND 
						tusers.disable='0' 
						GROUP BY tincidents.technician 
						ORDER BY jour ASC";
						if ($rparameters['debug']==1) {echo $query;}
						$query = $db->query($query);
						while ($row=$query->fetch()) {
							if($row[2]!=''){echo "<tr><td>$row[0] $row[1]</td><td>$row[2]j</td></tr>";}
						} 
						$query->closecursor();
						echo '
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-3 widget-container-span ui-sortable">
		<div class="widget-box" style="opacity: 1; z-index: 0;">
			<div class="widget-header header-color-blue">
				<h5 class="bigger lighter">
					'.T_('Tickets par priorité').'
				</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<th>
									<i class="icon-user"></i>
									'.T_('Priorité').'
								</th>

								<th>
									<i class="icon-bullseye"></i>
									'.T_('Nombre').'
								</th>
							</tr>
						</thead>
						<tbody>
						';
						$query="
						SELECT tpriority.name, count(*) AS number
						FROM tincidents INNER JOIN tpriority ON (tincidents.priority=tpriority.id ) 
						WHERE tincidents.disable='0' AND
						tincidents.technician LIKE '$_POST[tech]' AND
						tincidents.type LIKE '$_POST[type]' AND
						tincidents.criticality like '$_POST[criticality]' AND
						tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
						tincidents.category LIKE '$_POST[category]' AND
						tincidents.date_create LIKE '%-$_POST[month]-%' AND
						tincidents.date_create LIKE '$_POST[year]-%' 
						GROUP BY tincidents.priority
						ORDER BY tpriority.number ASC";
						if ($rparameters['debug']==1) {echo $query;}
						$query = $db->query($query);
						while ($row=$query->fetch()) {echo '<tr><td>'.T_($row[0]).'</td><td>'.$row[1].'</td></tr>';} 
						$query->closecursor();
						echo '
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-3 widget-container-span ui-sortable">
		<div class="widget-box" style="opacity: 1; z-index: 0;">
			<div class="widget-header header-color-blue">
				<h5 class="bigger lighter">
					'.T_('Tickets par criticité').'
				</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<th>
									<i class="icon-user"></i>
									'.T_('Criticité').'
								</th>

								<th>
									<i class="icon-bullseye"></i>
									'.T_('Nombre').'
								</th>
								
							</tr>
						</thead>
						<tbody>
						';
						$query="
						SELECT tcriticality.name, count(*) AS number
						FROM tincidents INNER JOIN tcriticality ON (tincidents.criticality=tcriticality.id ) 
						WHERE tincidents.disable='0' AND
						tincidents.technician LIKE '$_POST[tech]' AND
						tincidents.type LIKE '$_POST[type]' AND
						tincidents.criticality like '$_POST[criticality]' AND
						tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
						tincidents.category LIKE '$_POST[category]' AND
						tincidents.date_create LIKE '%-$_POST[month]-%' AND
						tincidents.date_create LIKE '$_POST[year]-%' 
						GROUP BY tincidents.criticality
						ORDER BY tcriticality.number ASC";
						if ($rparameters['debug']==1) {echo $query;}
						$query = $db->query($query);
						while ($row=$query->fetch()) {echo '<tr><td>'.T_($row[0]).'</td><td>'.$row[1].'</td></tr>';} 
						$query->closecursor();
						echo '
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<br />
<br />
<br />
<br />
<br />
';

//display second line of tables part
echo '
<div id="container" style="min-width: 300px; height: 500px; margin: 0 auto">
	<div class="col-xs-12 col-sm-3 widget-container-span ui-sortable">
		<div class="widget-box" style="opacity: 1; z-index: 0;">
			<div class="widget-header header-color-blue">
				<h5 class="bigger lighter">
					'.T_('Top 10 des demandeurs').'
				</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<th>
									<i class="icon-user"></i>
									'.T_('Utilisateurs').'
								</th>

								<th>
									<i class="icon-ticket"></i>
									'.T_('Tickets').'
								</th>
								
							</tr>
						</thead>
						<tbody>
						';
						$query="
						SELECT tusers.firstname AS Util, tusers.lastname, count(*) AS demandes
						FROM tincidents INNER JOIN tusers ON (tincidents.user=tusers.id )
						WHERE tincidents.disable='0' AND
						tincidents.type LIKE '$_POST[type]' AND
						tincidents.criticality like '$_POST[criticality]' AND
						tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
						tincidents.category LIKE '$_POST[category]' AND
						tincidents.date_create LIKE '%-$_POST[month]-%' AND
						tincidents.date_create LIKE '$_POST[year]-%' 
						GROUP BY tincidents.user
						ORDER BY demandes DESC LIMIT 10";
						if ($rparameters['debug']==1) {echo $query;}
						$query = $db->query($query);
						while ($row=$query->fetch()) {echo "<tr><td>$row[0] $row[1]</td><td>$row[2]</td></tr>";}
						$query->closecursor();
						echo '
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-3 widget-container-span ui-sortable">
		<div class="widget-box" style="opacity: 1; z-index: 0;">
			<div class="widget-header header-color-blue">
				<h5 class="bigger lighter">
					'.T_('TOP 10 demandeurs de temps').'
				</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-striped table-bordered table-hover">
						<thead class="thin-border-bottom">
							<tr>
								<th>
									<i class="icon-user"></i>
									'.T_('Utilisateurs').'
								</th>

								<th w>
									<i class="icon-time"></i>
									'.T_('Heures').'
								</th>
								
							</tr>
						</thead>
						<tbody>
						';
						$query="
						SELECT tusers.firstname AS Util, tusers.lastname, sum(time) AS temps 
						FROM tincidents 
						INNER JOIN tusers ON (tincidents.user=tusers.id)  
						WHERE tincidents.time NOT LIKE '0' AND
						tincidents.time NOT LIKE '0' AND
						tincidents.disable='0' AND
						tincidents.type LIKE '$_POST[type]' AND
						tincidents.criticality like '$_POST[criticality]' AND
						tincidents.category LIKE '$_POST[category]' AND
						tincidents.date_create LIKE '%-$_POST[month]-%' AND
						tincidents.date_create LIKE '$_POST[year]-%' AND 
						tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency
						GROUP BY tincidents.user
						ORDER BY sum(time) DESC limit 10";
						if ($rparameters['debug']==1) {echo $query;}
						$query = $db->query($query);
						while ($row = $query->fetch())
						{
							$tps=$row[2]/60;
							$tps=round($tps);
							echo "<tr><td>$row[0] $row[1]</td><td>$tps h</td></tr>";
						} 
						$query->closecursor();
						echo '
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 widget-container-span ui-sortable">
		<div class="widget-box" style="opacity: 1; z-index: 0;">
			<div class="widget-header header-color-blue">
				<h5 class="bigger lighter">
					'.T_('Répartition des temps par statuts').'
				</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-striped table-bordered table-hover" ">
						<thead class="thin-border-bottom">
							<tr>
								<th>
									<i class="icon-adjust"></i>
									'.T_('États').'
								</th>
								<th w>
									<i class="icon-calendar"></i>
									'.T_('Inférieur 1 jour').'
								</th>
								<th w>
									<i class="icon-calendar"></i>
									'.T_('2 à 4 jours').'
								</th>
								<th w>
									<i class="icon-calendar"></i>
									'.T_('5 à 10 jours').'
								</th>
								<th w>
									<i class="icon-calendar"></i>
									'.T_('10 à 30 jours').'
								</th>
								<th w>
									<i class="icon-calendar"></i>
									'.T_('30 à 90 jours').'
								</th>
								<th w>
									<i class="icon-calendar"></i>
									'.T_('Plus de 90 jours').'
								</th>
							</tr>
						</thead>
						<tbody>';
							//init counter
							$cnt_state_wait_pec_1=0;
							$cnt_state_wait_pec_2_4=0;
							$cnt_state_wait_pec_5_10=0;
							$cnt_state_wait_pec_10_30=0;
							$cnt_state_wait_pec_30_90=0;
							$cnt_state_wait_pec_90=0;
							
							$cnt_state_current_1=0;
							$cnt_state_current_2_4=0;
							$cnt_state_current_5_10=0;
							$cnt_state_current_10_30=0;
							$cnt_state_current_30_90=0;
							$cnt_state_current_90=0;
							
							$cnt_state_wait_user_1=0;
							$cnt_state_wait_user_2_4=0;
							$cnt_state_wait_user_5_10=0;
							$cnt_state_wait_user_10_30=0;
							$cnt_state_wait_user_30_90=0;
							$cnt_state_wait_user_90=0;
							
							$cnt_state_attribution_1=0;
							$cnt_state_attribution_2_4=0;
							$cnt_state_attribution_5_10=0;
							$cnt_state_attribution_10_30=0;
							$cnt_state_attribution_30_90=0;
							$cnt_state_attribution_90=0;
							
							$count=0;
							
							//get ticket for selected period
							$query2="
							SELECT id 
							FROM tincidents 
							WHERE 
							tincidents.disable='0' AND
							tincidents.type LIKE '$_POST[type]' AND
							tincidents.technician LIKE '$_POST[tech]' AND
							tincidents.criticality like '$_POST[criticality]' AND
							tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
							tincidents.category LIKE '$_POST[category]' AND
							(
								tincidents.date_create LIKE '$_POST[year]-$_POST[month]-%' OR
								tincidents.date_res LIKE '$_POST[year]-$_POST[month]-%'
							)
							";
							$query2 = $db->query($query2);
							while ($row2 = $query2->fetch())
							{
								$count++;
								//detect switch state for current ticket
								$query3="SELECT tthreads.* FROM tthreads INNER JOIN tincidents ON tincidents.id=tthreads.ticket WHERE tincidents.technician LIKE '$_POST[tech]' AND tthreads.ticket=$row2[id] AND tthreads.type!=0 AND tthreads.type!=3 AND tthreads.type!=2 ORDER BY tthreads.id";
								$query3 = $db->query($query3);
								while ($row3 = $query3->fetch())
								{
									if (isset($previous_date))
									{
										if ($rparameters['debug']==1) {echo "ANALYSE TICKET: $row2[id] THREAD=$row3[id]: $row3[date] type $row3[type] state $row3[state] ";}
										if ($previous_type==5 && $previous_state==1)
										{
											$query4='SELECT datediff(\''.$row3['date'].'\',\''.$previous_date.'\')';
											$query4=$db->query($query4);
											$days=$query4->fetch();
											$query4->closeCursor(); 
											if($days[0]<=1) {$cnt_state_wait_pec_1=$cnt_state_wait_pec_1+1; if ($rparameters['debug']==1) {echo "[WAIT TECH 1]";}}
											if($days[0]>1 && $days[0]<=4) {$cnt_state_wait_pec_2_4=$cnt_state_wait_pec_2_4+1; if ($rparameters['debug']==1) {echo "[WAIT TECH 2_4]";}}
											if($days[0]>4 && $days[0]<=10) {$cnt_state_wait_pec_5_10=$cnt_state_wait_pec_5_10+1; if ($rparameters['debug']==1) {echo "[WAIT TECH 5_10]";}}
											if($days[0]>10 && $days[0]<=30) {$cnt_state_wait_pec_10_30=$cnt_state_wait_pec_10_30+1; if ($rparameters['debug']==1) {echo "[WAIT TECH 10_30]";}}
											if($days[0]>30 && $days[0]<=90) {$cnt_state_wait_pec_30_90=$cnt_state_wait_pec_30_90+1; if ($rparameters['debug']==1) {echo "[WAIT TECH 30_90]";}}
											if($days[0]>90) {$cnt_state_wait_pec_90=$cnt_state_wait_pec_90+1; if ($rparameters['debug']==1) {echo "[WAIT TECH 90]";}}
										}
										if ($previous_type==5 && $previous_state==2)
										{
											$query4='SELECT datediff(\''.$row3['date'].'\',\''.$previous_date.'\')';
											$query4=$db->query($query4);
											$days=$query4->fetch();
											$query4->closeCursor(); 
											if($days[0]<=1) {$cnt_state_current_1=$cnt_state_current_1+1; if ($rparameters['debug']==1) {echo "[CURRENT 1]";}}
											if($days[0]>1 && $days[0]<=4) {$cnt_state_current_2_4=$cnt_state_current_2_4+1; if ($rparameters['debug']==1) {echo "[CURRENT 2_4]";}}
											if($days[0]>4 && $days[0]<=10) {$cnt_state_current_5_10=$cnt_state_current_5_10+1; if ($rparameters['debug']==1) {echo "[CURRENT 5_10]";}}
											if($days[0]>10 && $days[0]<=30 ) {$cnt_state_current_10_30=$cnt_state_current_10_30+1; if ($rparameters['debug']==1) {echo "[CURRENT 10_30]";}}
											if($days[0]>30 && $days[0]<90 ) {$cnt_state_current_30_90=$cnt_state_current_30_90+1; if ($rparameters['debug']==1) {echo "[CURRENT 30_90]";}}
											if($days[0]>90) {$cnt_state_current_90=$cnt_state_current_90+1; if ($rparameters['debug']==1) {echo "[CURRENT 90]";}}
										}
										if ($previous_type==5 && $previous_state==6)
										{
											$query4='SELECT datediff(\''.$row3['date'].'\',\''.$previous_date.'\')';
											$query4=$db->query($query4);
											$days=$query4->fetch();
											$query4->closeCursor(); 
											if($days[0]<=1) {$cnt_state_wait_user_1=$cnt_state_wait_user_1+1; if ($rparameters['debug']==1) {echo "[WAIT USER 1]";}}
											if($days[0]>1 && $days[0]<=4) {$cnt_state_wait_user_2_4=$cnt_state_wait_user_2_4+1; if ($rparameters['debug']==1) {echo "[WAIT USER 2_4]<";}}
											if($days[0]>4 && $days[0]<=10) {$cnt_state_wait_user_5_10=$cnt_state_wait_user_5_10+1; if ($rparameters['debug']==1) {echo "[WAIT USER 5_10]";}}
											if($days[0]>10 && $days[0]<=30) {$cnt_state_wait_user_10_30=$cnt_state_wait_user_10_30+1; if ($rparameters['debug']==1) {echo "[WAIT USER 10_30]";}}
											if($days[0]>30 && $days[0]<=90) {$cnt_state_wait_user_30_90=$cnt_state_wait_user_30_90+1; if ($rparameters['debug']==1) {echo "[WAIT USER 30_90]";}}
											if($days[0]>90) {$cnt_state_wait_user_90=$cnt_state_wait_user_90+1; if ($rparameters['debug']==1) {echo "[WAIT USER 90]";}}
										}
										if ($previous_type==5 && $previous_state==5)
										{
											$query4='SELECT datediff(\''.$row3['date'].'\',\''.$previous_date.'\')';
											$query4=$db->query($query4);
											$days=$query4->fetch();
											$query4->closeCursor(); 
											if($days[0]<=1) {$cnt_state_attribution_1=$cnt_state_attribution_1+1; if ($rparameters['debug']==1) {echo "[ATTRIBUTION 1]";}}
											if($days[0]>1 && $days[0]<=4) {$cnt_state_attribution_2_4=$cnt_state_attribution_2_4+1; if ($rparameters['debug']==1) {echo "[ATTRIBUTION 2_4]";}}
											if($days[0]>4 && $days[0]<=10) {$cnt_state_attribution_5_10=$cnt_state_attribution_5_10+1; if ($rparameters['debug']==1) {echo "[ATTRIBUTION 5_10]";}}
											if($days[0]>10 && $days[0]<=30) {$cnt_state_attribution_10_30=$cnt_state_attribution_10_30+1; if ($rparameters['debug']==1) {echo "[ATTRIBUTION 10_30]";}}
											if($days[0]>30 && $days[0]<=90) {$cnt_state_attribution_30_90=$cnt_state_attribution_30_90+1; if ($rparameters['debug']==1) {echo "[ATTRIBUTION 30_90]";}}
											if($days[0]>90) {$cnt_state_attribution_90=$cnt_state_attribution_90+1; if ($rparameters['debug']==1) {echo "[ATTRIBUTION 90]";}}
										}
									}
									$previous_date=$row3['date'];
									$previous_type=$row3['type'];
									$previous_state=$row3['state'];
									if ($rparameters['debug']==1) {echo "<br />";}
								}
								$query3->closecursor();
							} 
							$query2->closecursor();
							//calculate percentage
							$total_cnt_state_wait_pec=$cnt_state_wait_pec_1+$cnt_state_wait_pec_2_4+$cnt_state_wait_pec_5_10+$cnt_state_wait_pec_10_30+$cnt_state_wait_pec_30_90+$cnt_state_wait_pec_90;
							if($total_cnt_state_wait_pec!=0)
							{
								$percent_wait_pec_1=round(($cnt_state_wait_pec_1*100)/$total_cnt_state_wait_pec,2);
								$percent_wait_pec_2_4=round(($cnt_state_wait_pec_2_4*100)/$total_cnt_state_wait_pec,2);
								$percent_wait_pec_5_10=round(($cnt_state_wait_pec_5_10*100)/$total_cnt_state_wait_pec,2);
								$percent_wait_pec_10_30=round(($cnt_state_wait_pec_10_30*100)/$total_cnt_state_wait_pec,2);
								$percent_wait_pec_30_90=round(($cnt_state_wait_pec_30_90*100)/$total_cnt_state_wait_pec,2);
								$percent_wait_pec_90=round(($cnt_state_wait_pec_90*100)/$total_cnt_state_wait_pec,2);
							} else {
								$percent_wait_pec_1=0;
								$percent_wait_pec_2_4=0;
								$percent_wait_pec_5_10=0;
								$percent_wait_pec_10_30=0;
								$percent_wait_pec_30_90=0;
								$percent_wait_pec_90=0;
							}
							$total_cnt_state_current=$cnt_state_current_1+$cnt_state_current_2_4+$cnt_state_current_5_10+$cnt_state_current_10_30+$cnt_state_current_30_90+$cnt_state_current_90;
							if($total_cnt_state_current!=0)
							{
								$percent_current_1=round(($cnt_state_current_1*100)/$total_cnt_state_current,2);
								$percent_current_2_4=round(($cnt_state_current_2_4*100)/$total_cnt_state_current,2);
								$percent_current_5_10=round(($cnt_state_current_5_10*100)/$total_cnt_state_current,2);
								$percent_current_10_30=round(($cnt_state_current_10_30*100)/$total_cnt_state_current,2);
								$percent_current_30_90=round(($cnt_state_current_30_90*100)/$total_cnt_state_current,2);
								$percent_current_90=round(($cnt_state_current_90*100)/$total_cnt_state_current,2);
							} else {
								$percent_current_1=0;
								$percent_current_2_4=0;
								$percent_current_5_10=0;
								$percent_current_10_30=0;
								$percent_current_30_90=0;
								$percent_current_90=0;
							}
							$total_cnt_state_wait_user=$cnt_state_wait_user_1+$cnt_state_wait_user_2_4+$cnt_state_wait_user_5_10+$cnt_state_wait_user_10_30+$cnt_state_wait_user_30_90+$cnt_state_wait_user_90;
							if($total_cnt_state_wait_user!=0)
							{
								$percent_wait_user_1=round(($cnt_state_wait_user_1*100)/$total_cnt_state_wait_user,2);
								$percent_wait_user_2_4=round(($cnt_state_wait_user_2_4*100)/$total_cnt_state_wait_user,2);
								$percent_wait_user_5_10=round(($cnt_state_wait_user_5_10*100)/$total_cnt_state_wait_user,2);
								$percent_wait_user_10_30=round(($cnt_state_wait_user_10_30*100)/$total_cnt_state_wait_user,2);
								$percent_wait_user_30_90=round(($cnt_state_wait_user_30_90*100)/$total_cnt_state_wait_user,2);
								$percent_wait_user_90=round(($cnt_state_wait_user_90*100)/$total_cnt_state_wait_user,2);
							} else {
								$percent_wait_user_1=0;
								$percent_wait_user_2_4=0;
								$percent_wait_user_5_10=0;
								$percent_wait_user_10_30=0;
								$percent_wait_user_30_90=0;
								$percent_wait_user_90=0;
							}
							$total_cnt_state_attribution=$cnt_state_attribution_1+$cnt_state_attribution_2_4+$cnt_state_attribution_5_10+$cnt_state_attribution_10_30+$cnt_state_attribution_30_90+$cnt_state_attribution_90;
							if($total_cnt_state_attribution!=0)
							{
								$percent_attribution_1=round(($cnt_state_attribution_1*100)/$total_cnt_state_attribution,2);
								$percent_attribution_2_4=round(($cnt_state_attribution_2_4*100)/$total_cnt_state_attribution,2);
								$percent_attribution_5_10=round(($cnt_state_attribution_5_10*100)/$total_cnt_state_attribution,2);
								$percent_attribution_10_30=round(($cnt_state_attribution_10_30*100)/$total_cnt_state_attribution,2);
								$percent_attribution_30_90=round(($cnt_state_attribution_30_90*100)/$total_cnt_state_attribution,2);
								$percent_attribution_90=round(($cnt_state_attribution_90*100)/$total_cnt_state_attribution,2);
							} else {
								$percent_attribution_1=0;
								$percent_attribution_2_4=0;
								$percent_attribution_5_10=0;
								$percent_attribution_10_30=0;
								$percent_attribution_30_90=0;
								$percent_attribution_90=0;
							}
							if ($rparameters['debug']==1) {	echo '<br />Ticket analysed: '.$count.'<br />';}
							
							$query="SELECT DISTINCT tstates.id,tstates.name FROM tstates WHERE id=1 OR id=2 OR id=5 OR id=6 ORDER BY tstates.number ASC";
							$query = $db->query($query);
							while ($row = $query->fetch())
							{
								echo '
								<tr>
									<td> '.T_('Temps passé').' '.T_($row['name']).'</td>
									<td align="center">';
										if($row['id']==1){echo $percent_wait_pec_1.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_pec_1.' tickets)';}} 
										if($row['id']==2){echo $percent_current_1.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_current_1.' tickets)';}} 
										if($row['id']==6){echo $percent_wait_user_1.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_user_1.' tickets)';}} 
										if($row['id']==5){echo $percent_attribution_1.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_attribution_1.' tickets)';}} 
									echo '</td>
									<td align="center">';
										if($row['id']==1){echo $percent_wait_pec_2_4.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_pec_2_4.' tickets)';}} 
										if($row['id']==2){echo $percent_current_2_4.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_current_2_4.' tickets)';}} 
										if($row['id']==6){echo $percent_wait_user_2_4.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_user_2_4.' tickets)';}} 
										if($row['id']==5){echo $percent_attribution_2_4.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_attribution_2_4.' tickets)';}} 
									echo '</td>
									<td align="center">';
										if($row['id']==1){echo $percent_wait_pec_5_10.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_pec_5_10.' tickets)';}} 
										if($row['id']==2){echo $percent_current_5_10.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_current_5_10.' tickets)';}} 
										if($row['id']==6){echo $percent_wait_user_5_10.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_user_5_10.' tickets)';}} 
										if($row['id']==5){echo $percent_attribution_5_10.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_attribution_5_10.' tickets)';}} 
									echo '</td>
									<td align="center">';
										if($row['id']==1){echo $percent_wait_pec_10_30.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_pec_10_30.' tickets)';}}
										if($row['id']==2){echo $percent_current_10_30.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_current_10_30.' tickets)';}}
										if($row['id']==6){echo $percent_wait_user_10_30.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_user_10_30.' tickets)';}}
										if($row['id']==5){echo $percent_attribution_10_30.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_attribution_10_30.' tickets)';}}
									echo '</td>
									<td align="center">';
										if($row['id']==1){echo $percent_wait_pec_30_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_pec_30_90.' tickets)';}}
										if($row['id']==2){echo $percent_current_30_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_current_30_90.' tickets)';}}
										if($row['id']==6){echo $percent_wait_user_30_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_user_30_90.' tickets)';}}
										if($row['id']==5){echo $percent_attribution_30_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_attribution_30_90.' tickets)';}}
									echo '</td>
									<td align="center">';
										if($row['id']==1){echo $percent_wait_pec_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_pec_90.' tickets)';}}
										if($row['id']==2){echo $percent_current_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_current_90.' tickets)';}}
										if($row['id']==6){echo $percent_wait_user_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_wait_user_90.' tickets)';}}
										if($row['id']==5){echo $percent_attribution_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_state_attribution_90.' tickets)';}}
									echo '</td>
									
								</tr>';
							} 
							$query->closecursor();
							//total for current selection
							$cnt_total_1=0;
							$cnt_total_2_4=0;
							$cnt_total_5_10=0;
							$cnt_total_10_30=0;
							$cnt_total_30_90=0;
							$cnt_total_90=0;
							if ($rparameters['debug']==1) {	echo '<hr />TOTAL<hr />';}
							$query="
							SELECT id, date_create, date_res
							FROM tincidents 
							WHERE tincidents.disable='0' AND
							tincidents.technician LIKE '$_POST[tech]' AND
							tincidents.type LIKE '$_POST[type]' AND
							tincidents.criticality like '$_POST[criticality]' AND
							tincidents.u_service LIKE '$_POST[service]' $where_service $where_agency AND
							tincidents.category LIKE '$_POST[category]' AND
							tincidents.state=3 AND
							(
								tincidents.date_create LIKE '$_POST[year]-$_POST[month]-%' OR
								tincidents.date_res LIKE '$_POST[year]-$_POST[month]-%'
							)
							";
							$query = $db->query($query);
							while ($row = $query->fetch())
							{
								$query2='SELECT datediff(\''.$row['date_res'].'\',\''.$row['date_create'].'\')';
								$query2=$db->query($query2);
								$days=$query2->fetch();
								$query2->closeCursor(); 
								if ($rparameters['debug']==1)  {echo "ANALYSE TICKET: $row[id] day $days[0] ";}
								if($days[0]<=1) {$cnt_total_1=$cnt_total_1+1; if ($rparameters['debug']==1) {echo "[CLOSE 1]";}}
								if($days[0]>1 && $days[0]<=4) {$cnt_total_2_4=$cnt_total_2_4+1; if ($rparameters['debug']==1) {echo "[CLOSE 2_4]";}}
								if($days[0]>4 && $days[0]<=10) {$cnt_total_5_10=$cnt_total_5_10+1; if ($rparameters['debug']==1) {echo "[CLOSE 5_10]";}}
								if($days[0]>10 && $days[0]<=30) {$cnt_total_10_30=$cnt_total_10_30+1; if ($rparameters['debug']==1) {echo "[CLOSE 10_30]";}}
								if($days[0]>30 && $days[0]<=90) {$cnt_total_30_90=$cnt_total_30_90+1; if ($rparameters['debug']==1) {echo "[CLOSE 30_90]";}}
								if($days[0]>90) {$cnt_total_90=$cnt_total_90+1; if ($rparameters['debug']==1) {echo "[CLOSE 90]";}}
								if ($rparameters['debug']==1) {	echo '<br />';}
							}
							$total_cnt_total=$cnt_total_1+$cnt_total_2_4+$cnt_total_5_10+$cnt_total_10_30+$cnt_total_30_90+$cnt_total_90;
							if($total_cnt_total!=0)
							{
								$percent_total_1=round(($cnt_total_1*100)/$total_cnt_total,2);
								$percent_total_2_4=round(($cnt_total_2_4*100)/$total_cnt_total,2);
								$percent_total_5_10=round(($cnt_total_5_10*100)/$total_cnt_total,2);
								$percent_total_10_30=round(($cnt_total_10_30*100)/$total_cnt_total,2);
								$percent_total_30_90=round(($cnt_total_30_90*100)/$total_cnt_total,2);
								$percent_total_90=round(($cnt_total_90*100)/$total_cnt_total,2);
							} else {
								$percent_total_1=0;
								$percent_total_2_4=0;
								$percent_total_5_10=0;
								$percent_total_10_30=0;
								$percent_total_30_90=0;
								$percent_total_90=0;
							}
							echo '<tr><td>'.T_('Temps total de traitement').'</td><td align="center">'.$percent_total_1.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_total_1.' tickets)';} echo '</td><td align="center">'.$percent_total_2_4.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_total_2_4.' tickets)';} echo '</td><td align="center">'.$percent_total_5_10.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_total_5_10.' tickets)';} echo '</td><td align="center">'.$percent_total_10_30.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_total_10_30.' tickets)';} echo '</td><td align="center">'.$percent_total_30_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_total_30_90.' tickets)';} echo '</td><td align="center">'.$percent_total_90.'%'; if ($rparameters['debug']==1) {echo ' ('.$cnt_total_90.' tickets)';} echo '</td></tr>';
							echo '
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
';
?>