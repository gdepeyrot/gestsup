<?php
################################################################################
# @Name : stat.php
# @Description : Display Statistics
# @call : /menu.php
# @parameters : 
# @Author : Flox
# @Create : 12/01/2011
# @Update : 13/09/2017
# @Version : 3.1.26
################################################################################

//initialize variables 
if(!isset($select)) $select = '';
if(!isset($libgraph)) $libgraph = '';
if(!isset($selected)) $selected= '';
if(!isset($selected1)) $selected1= '';
if(!isset($selected2)) $selected2= '';
if(!isset($find)) $find= '';
if(!isset($subcat)) $subcat= '%';
if(!isset($category)) $category= '';
if(!isset($result)) $result= '';
if(!isset($monthm)) $monthm= '';
if(!isset($container)) $container= '';
if(!isset($_POST['tech'])) $_POST['tech']='';
if(!isset($_POST['type'])) $_POST['type']='';
if(!isset($_POST['criticality'])) $_POST['criticality']='';
if(!isset($_POST['category'])) $_POST['category']='';
if(!isset($_POST['subcat'])) $_POST['subcat']= '';
if(!isset($_POST['year'])) $_POST['year'] = '';
if(!isset($_POST['month'])) $_POST['month'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '';
if(!isset($_POST['service'])) $_POST['service'] = '';
if(!isset($_POST['company'])) $_POST['company'] = '';
if(!isset($_POST['agency'])) $_POST['agency'] = '';
if(!isset($_POST['model'])) $_POST['model'] = '';
if(!isset($_GET['tab'])) $_GET['tab'] = 'ticket';

//default values 
if ($_POST['tech']=="") $_POST['tech']="%";
if ($_POST['criticality']=="") $_POST['criticality']="%";
if ($_POST['year']=="") $_POST['year']=date('Y');
if ($_POST['month']=="") $_POST['month']=date('m');
if ($_POST['type']=="") $_POST['type']='%';
if ($_POST['category']=="") $_POST['category']='%';
if ($_POST['service']=="") $_POST['service']='%';
if ($_POST['company']=="") $_POST['company']='%';
if ($_POST['agency']=="") $_POST['agency']='%';
if ($_POST['model']=="") $_POST['model']='%';

//count company from company list to display company filter or not
$query = $db->query("SELECT count(id) FROM tcompany WHERE disable='0'"); 
$company_cnt = $query->fetch();
$query->closeCursor();
if($company_cnt[0]>1 && $rparameters['user_advanced']==1) {$company_filter=1;} else {$company_filter=0;}

//case agency parameter is enabled
if ($rparameters['user_agency']==1){$where_agency="AND tincidents.u_agency LIKE '$_POST[agency]'";} else {$where_agency='';}

//month & day table 
$mois = array();
$mois = array("01" => T_('Janvier'), "02"=> T_('Février'), "03"=> T_('Mars'), "04"=> T_('Avril'), "05"=> T_('Mai'), "06"=> T_('Juin'), "07"=> T_('Juillet'), "08"=> T_('Aout'), "09"=> T_('Septembre'), "10"=> T_('Octobre'), "11"=> T_('Novembre'), "12"=> T_('Décembre'));
$jour= array();
$jour = array(1 => "1", 2=> "2", 3=> "3", 4=> "4", 5=> "5", 6=> "6", 7=> "7", 8=> "8", 9=> "9", 10=> "10", 11=> "11", 12=> "12", 13=> "13", 14=> "14", 15=> "15", 16=> "16", 17=> "17", 18=> "18", 19=> "19", 20=> "20", 21=> "21", 22=> "22", 23=> "23", 24=> "24", 25=> "25", 26=> "26", 27=> "27", 28=> "28", 29=> "29", 30=> "30", 31=> "31");

//call highcharts scripts
echo'
<script type="text/javascript" src="./template/assets/js/jquery-2.0.3.min.js"></script>
<script src="./components/Highcharts/js/highcharts.js"></script>
<script src="./components/Highcharts/js/modules/exporting.js"></script>
';

if (($rparameters['user_limit_service']==1 && $cnt_service!=0) || $rright['stat']!=0)
{
	echo '
	<div class="page-header position-relative">
		<h1>
			<i class="icon-bar-chart"></i>  '.T_('Statistiques').'
			<div class="pull-right">
				';
				if ($_GET['tab']=='asset')
				{
					//generate token
					$token=uniqid(); 
					$db->exec("DELETE FROM ttoken WHERE action='export_asset'");
					$db->exec("INSERT INTO ttoken (token,action) VALUES ('$token','export_asset')");
					echo'
						<a title="'.T_("Télécharge un fichier au format CSV avec l'ensemble des équipements").'" target="_blank" href="./core/export_assets.php?token='.$token.'&technician='.$_POST['tech'].'&service='.$_POST['service'].'&type='.$_POST['type'].'&criticality='.$_POST['criticality'].'&category='.$_POST['category'].'&month='.$_POST['month'].'&year='.$_POST['year'].'&company='.$_POST['company'].'">
							<button  class="btn btn-xs btn-purple">
								<i align="right" class="icon-list "></i>
								'.T_('Export des équipements en CSV').'
							</button>
						</a>
					';
				} else {
					//generate token
					$token=uniqid(); 
					$db->exec("DELETE FROM ttoken WHERE action='export_ticket'");
					$db->exec("INSERT INTO ttoken (token,action) VALUES ('$token','export_ticket')");
					echo'
						<a title="'.T_("Télécharge un fichier au format CSV avec l'ensemble des tickets").'" target="_blank" href="./core/export_tickets.php?token='.$token.'&technician='.$_POST['tech'].'&service='.$_POST['service'].'&agency='.$_POST['agency'].'&type='.$_POST['type'].'&criticality='.$_POST['criticality'].'&category='.$_POST['category'].'&month='.$_POST['month'].'&year='.$_POST['year'].'&userid='.$_SESSION['user_id'].'">
							<button  class="btn btn-xs btn-purple">
								<i align="right" class="icon-list "></i>
								'.T_('Export des tickets en CSV').'
							</button>
						</a>
					';
				}
				echo '
			</div>
		</h1>
	</div>
	<div class="col-sm-12">	
		<div class="tabbable">
			<ul class="nav nav-tabs" id="myTab">
				<li '; if ($_GET['tab']=='ticket') echo 'class="active"'; echo '>
					<a href="./index.php?page=stat&tab=ticket">
						<i class="green icon-ticket bigger-110"></i>
						'.T_('Tickets').'
					</a>
				</li>
				';
				//if asset function is enable
				if ($rparameters['asset']==1)
				{
					echo '
					<li '; if ($_GET['tab']=='asset') echo 'class="active"';  echo ';>
						<a href="./index.php?page=stat&tab=asset">
							<i class="blue icon-desktop bigger-110"></i>
							'.T_('Équipements').'
						</a>
					</li>
					';
				}
				echo '
			</ul>
			<div class="tab-content">
				<div id="ticket" class="tab-pane '; if ($_GET['tab']=='ticket') echo 'active'; echo ' ">
					'; include('./ticket_stat.php'); echo '
				</div>
				<div id="asset" class="tab-pane '; if ($_GET['tab']=='asset') echo 'active'; echo ' ">
					'; include('./asset_stat.php'); echo '
				</div>
			</div>
		</div>
	</div>
	';
} else {
	echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous devez posséder au moins un service associé pour afficher cette page, contacter votre administrateur").'.<br></div>';
}
?>