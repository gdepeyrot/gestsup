<?php
################################################################################
# @Name : index.php
# @Description : main page include all sub-pages
# @call : 
# @parameters : 
# @Author : Flox
# @Create : 07/03/2010
# @Update : 15/02/2018
# @Version : 3.1.30
################################################################################

//cookies initialization
session_name(md5_file('connect.php'));
session_start();

//initialize variables
if(!isset($_GET['page'])) $_GET['page'] = '';
if(!isset($_GET['company'])) $_GET['company'] = '';
if(!isset($currentpage)) $currentpage = '';
if(!isset($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT'] = '';

if ($_GET['page']!='ticket' && $_GET['page']!='admin' && $_GET['page'] && $_GET['page']!='procedure') //avoid upload problems
{
    //avoid back problem with browser
    if(!empty($_POST) OR !empty($_FILES))
    {
        $_SESSION['bkp_post'] = $_POST;
        if(!empty($_SERVER['QUERY_STRING']))
        {
            $currentpage .= '?' . $_SERVER['QUERY_STRING'] ;
        }
        header('Location: ' . $currentpage);
        exit;
    }
    if(isset($_SESSION['bkp_post']))
    {
        $_POST = $_SESSION['bkp_post'] ;
        unset($_SESSION['bkp_post']);
    }
}

//mobile detection
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr(isset($_SERVER['HTTP_USER_AGENT']),0,4)))
{$mobile=1;} else {$mobile=0;}

//initialize variables
if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';
if(!isset($_SESSION['profile_id'])) $_SESSION['profile_id'] = '';

if(!isset($_POST['keywords'])) $_POST['keywords'] = '';
if(!isset($_POST['userkeywords'])) $_POST['userkeywords'] = '';
if(!isset($_POST['assetkeywords'])) $_POST['assetkeywords'] = '';

if(!isset($_GET['userkeywords'])) $_GET['userkeywords'] = '';
if(!isset($_GET['assetkeywords'])) $_GET['assetkeywords'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['keywords'])) $_GET['keywords'] = '';
if(!isset($_GET['page'])) $_GET['page'] = '';
if(!isset($_GET['searchengine'])) $_GET['searchengine'] = '';
if(!isset($_GET['download'])) $_GET['download'] = '';
if(!isset($_GET['subpage'])) $_GET['subpage'] = '';
if(!isset($_GET['ldap'])) $_GET['ldap'] = '';
if(!isset($_GET['category'])) $_GET['category'] = '';
if(!isset($_GET['subcat'])) $_GET['subcat'] = '';
if(!isset($_GET['technician'])) $_GET['technician'] = '';
if(!isset($_GET['title'])) $_GET['title'] = '';
if(!isset($_GET['date_create'])) $_GET['date_create'] = '';
if(!isset($_GET['priority'])) $_GET['priority'] = '';
if(!isset($_GET['criticality'])) $_GET['criticality'] = '';
if(!isset($_GET['type'])) $_GET['type'] = '';
if(!isset($_GET['place'])) $_GET['place'] = '';
if(!isset($_GET['way'])) $_GET['way'] = '';
if(!isset($_GET['cursor'])) $_GET['cursor'] = '';
if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($_GET['disable'])) $_GET['disable'] = '';
if(!isset($_GET['date_range'])) $_GET['date_range'] = '';
if(!isset($_GET['view'])) $_GET['view'] = '';
if(!isset($_GET['tab'])) $_GET['tab'] = '';
if(!isset($_GET['date_start'])) $_GET['date_start'] = '';
if(!isset($_GET['date_end'])) $_GET['date_end'] = '';
if(!isset($_GET['state'])) $_GET['state'] = '';
if(!isset($_GET['userid'])) $_GET['userid'] = '';

if(!isset($keywords)) $keywords = '';

//redirect to home page on log-off
if ($_GET['action'] == 'logout')
{
	$_SESSION = array();
	session_destroy();
	session_start();
}

//initialize variables
if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';

//detect https connection
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {$http='https';} else {$http='http';}

//connexion script with database parameters
require "connect.php";

//switch SQL MODE to allow empty values with lastest version of MySQL
$db->exec('SET sql_mode = ""');

$db_userid=strip_tags($db->quote($_GET['userid']));
$db_id=strip_tags($db->quote($_GET['id']));

//load parameters table
$query=$db->query("SELECT * FROM tparameters");
$rparameters=$query->fetch();
$query->closeCursor(); 

//timeout
if($rparameters['timeout'])
{
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 60*$rparameters['timeout'])) {
    session_unset();    
    session_destroy();
	if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';
	}
	$_SESSION['LAST_ACTIVITY'] = time(); 
}

//define timezone
if($rparameters['server_timezone']) {date_default_timezone_set($rparameters['server_timezone']);}

//load common variables
$daydate=date('Y-m-d');
$datetime = date("Y-m-d H:i:s");

//display error parameter
if ($rparameters['debug']==1) {
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 'Off');
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

//if user is connected
if ($_SESSION['user_id'])
{
	//load variables
	$uid=$_SESSION['user_id'];
	
	//load user table
	$quser=$db->query("SELECT * FROM tusers WHERE id=$_SESSION[user_id]");
	$ruser=$quser->fetch();
	$quser->closeCursor(); 
	
	//find profile id of connected user 
	$qprofile=$db->query("SELECT profile FROM tusers WHERE id LIKE $uid");
	$_SESSION['profile_id']=$qprofile->fetch();
	$qprofile->closeCursor(); 
	$_SESSION['profile_id']=$_SESSION['profile_id'][0];

	//load rights table
	$query=$db->query("SELECT * FROM trights WHERE profile=$_SESSION[profile_id]");
	$rright=$query->fetch();
	$query->closeCursor();
}

//define current language
require "localization.php";

//put keywords in variable
if($_POST['keywords']||$_GET['keywords']) $keywords="$_GET[keywords]$_POST[keywords]";
if($_POST['userkeywords']||$_GET['userkeywords']) $userkeywords="$_GET[userkeywords]$_POST[userkeywords]"; else  $userkeywords='';
if($_POST['assetkeywords']||$_GET['assetkeywords']) $assetkeywords="$_GET[assetkeywords]$_POST[assetkeywords]"; else  $assetkeywords='';

//download file for backup page
if ($_GET['download']!='')
{
	header("location: ./backup/$_GET[download]"); 
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
	    <?php header('x-ua-compatible: ie=edge'); //disable ie compatibility mode ?>
		<meta charset="UTF-8" />
		<meta name="theme-color" content="#2e6589">		
		<?php if (($rparameters['auto_refresh']!=0)&&($_GET['page']=='dashboard')&&($_POST['keywords']=='')) echo '<meta http-equiv="Refresh" content="'.$rparameters['auto_refresh'].';">'; ?>
		<title>GestSup | <?php echo T_('Gestion de Support'); ?></title>
		<link rel="shortcut icon" type="image/png" href="./images/
		<?php 
		if($_GET['page']=='asset_list' || $_GET['page']=='asset' || $_GET['page']=='asset_stock') {echo 'favicon_asset.png';} 
		elseif($_GET['page']=='procedure') {echo 'favicon_procedure.png';} 
		elseif($_GET['page']=='planning') {echo 'favicon_planning.png';} 
		elseif($_GET['page']=='plugins/availability/index') {echo 'favicon_availability.png';} 
		elseif($_GET['page']=='stat') {echo 'favicon_stat.png';} 
		elseif($_GET['page']=='admin') {echo 'favicon_admin.png';} 
		else {echo 'favicon_ticket.png';} 
		?>" 
		/>
		<meta name="description" content="gestsup" />
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- basic styles -->
		<link href="./template/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="./template/assets/css/font-awesome.min.css" />
		
		<!-- timepicker styles -->
		<link rel="stylesheet" href="template/assets/css/bootstrap-timepicker.css" />
		
		<link rel="stylesheet" href="./template/assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="./template/assets/css/jquery-ui-1.10.3.full.min.css" />
		
		<?php 
		//add css for chosen select
		if (($_GET['page']=='ticket') || ($_GET['page']=='asset')) 
		{
			echo '
			<!-- chosen styles -->
			<link rel="stylesheet" href="./template/assets/css/chosen.min.css" />
			';
		}
		?>
		
		<!-- ace styles -->
		<link rel="stylesheet" href="./template/assets/css/ace.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace-skins.min.css" />
		
		<script src="./template/assets/js/ace-extra.min.js"></script>
	</head>
	<?php
		//display navigation bar if user is connected
		if ($_SESSION['user_id'])
		{
			//temporary variables to migrate to trights table
			if ($_SESSION['profile_id']==0)	{$profile="technician";}
			elseif ($_SESSION['profile_id']==1)	{$profile="user";}
			elseif ($_SESSION['profile_id']==4)	{$profile="technician";}
			elseif ($_SESSION['profile_id']==3) {$profile="user";}
			else {$profile="user";}
			
			//get agencies associated with this user
			$query = $db->query("SELECT agency_id FROM `tusers_agencies` WHERE user_id='$_SESSION[user_id]'");  
			$cnt_agency=$query->rowCount();
			$query->closecursor();
			
			//get services associated with this user
			$query = $db->query("SELECT service_id FROM `tusers_services` WHERE user_id='$_SESSION[user_id]'"); 
			$cnt_service=$query->rowCount();
			$row=$query->fetch();
			$query->closecursor();
			
			//special case to technician with multi service and multi agencies
			if($rright['dashboard_service_only']!=0 && $rparameters['user_agency']==1 && $rparameters['user_limit_service']==1 && $cnt_service!=0 && $cnt_agency!=0) 
			{$operator='OR'; $parenthese1='('; $parenthese2=')';}
			else 
			{$operator='AND'; $parenthese1=''; $parenthese2='';}
			
			//special case to limit ticket to services
			if($rright['dashboard_service_only']!=0 && $rparameters['user_limit_service']==1) 
			{
				$where_service='';
				$where_service_your='';
				if(!isset($_GET['userid'])) $_GET['userid'] = '';
				$user_services=array();
				if($cnt_service==0) {$where_service.='';}
				elseif($cnt_service==1) {
					//$where_service_your.="AND (tincidents.u_service='$row[service_id]' OR tincidents.$profile LIKE '$_GET[userid]')  ";
					if ($_SESSION['profile_id']==0) //special case to allow technician to view ticket open for another service
					{
						$where_service_your.="AND (tincidents.u_service='$row[service_id]' OR tincidents.technician LIKE $db_userid OR tincidents.user LIKE $db_userid) ";
					} else {
						$where_service_your.="AND (tincidents.u_service='$row[service_id]' OR tincidents.$profile LIKE $db_userid) ";
					}
					if($_GET['page']=='dashboard' || $_GET['page']=='ticket') {
						$where_service.="$operator (tincidents.u_service='$row[service_id]' OR tincidents.user LIKE '$_SESSION[user_id]') "; //display service ticket + user tickets
					} else {
						$where_service.="$operator tincidents.u_service='$row[service_id]' "; //display service ticket + user tickets
					}
					array_push($user_services, $row['service_id']);
				} else {
					$cnt2=0;
					$query = $db->query("SELECT service_id FROM `tusers_services` WHERE user_id='$_SESSION[user_id]'");
					$where_service.="$operator (";
					while ($row=$query->fetch())	
					{
						$cnt2++;
						$where_service.="tincidents.u_service='$row[service_id]'";
						array_push($user_services, $row['service_id']);
						if ($cnt_service!=$cnt2) $where_service.=' OR '; 
					}
					$where_service.=') ';
					$query->closecursor();
					
				}
			} else {$where_service=''; $where_service_your=''; $user_services=''; $cnt_service='';}
			//special case to limit ticket to agency
			if($rright['dashboard_agency_only']!=0)
			{
				//get agencies associated with this user
				$query = $db->query("SELECT agency_id FROM `tusers_agencies` WHERE user_id='$_SESSION[user_id]'");  
				$row=$query->fetch();
				$query->closecursor();
				$where_agency='';
				$where_agency_your='';
				if(!isset($_GET['userid'])) $_GET['userid'] = '';
				$user_agencies=array();
				if($cnt_agency==0) {$where_agency.='';}
				elseif($cnt_agency==1) {
					if($_SESSION['profile_id']==0) //special case for technician to view only our ticket sender and tech (avoid limit display ticket problem)
					{
						$where_agency_your.=" ";
					} else {
						$where_agency_your.="AND (tincidents.u_agency='$row[agency_id]' OR tincidents.$profile LIKE $db_userid)  ";
					}
					
					$where_agency.="AND $parenthese1 tincidents.u_agency='$row[agency_id]' ";
					array_push($user_agencies, $row['agency_id']);
				} else {
					$cnt2=0;
					$query = $db->query("SELECT agency_id FROM `tusers_agencies` WHERE user_id='$_SESSION[user_id]'");
					$where_agency.="AND $parenthese1 (";
					while ($row=$query->fetch())	
					{
						$cnt2++;
						$where_agency.="tincidents.u_agency='$row[agency_id]'";
						array_push($user_agencies, $row['agency_id']);
						if ($cnt_agency!=$cnt2) $where_agency.=' OR '; 
					}
					$where_agency.=') ';
					$query->closecursor();
				}
			} else {$where_agency=''; $where_agency_your=''; $user_agencies=''; $cnt_agency='';}

			//user bar queries 
			$query=$db->query("SELECT count(*) FROM tincidents WHERE $profile='$_SESSION[user_id]' $where_service_your $where_agency_your AND techread='0' AND disable='0'");
			$cnt3=$query->fetch();
			$query->closeCursor(); 
			
			$query="SELECT count(*) FROM tincidents WHERE technician='0' AND t_group='0' $where_agency $where_service $parenthese2 AND disable='0'";
			$query=$db->query($query);
			$cnt5=$query->fetch();
			$query->closeCursor(); 
			
			$query="SELECT count(*) FROM tincidents WHERE $profile='$uid' AND (state LIKE '1' OR state LIKE '2' OR state LIKE '6') $where_agency $where_service $parenthese2 AND disable='0'";
			$query=$db->query($query);
			$nbatt=$query->fetch();
			$query->closeCursor(); 
						
			$query="SELECT count(*) FROM tincidents WHERE $profile='$uid' AND (state LIKE '1' OR state LIKE '2') $where_agency $where_service $parenthese2 AND disable='0'";
			$query=$db->query($query);
			$nbatt2=$query->fetch();
			$query->closeCursor(); 
			
			$query="SELECT count(*) FROM tincidents WHERE $profile='$uid' AND state LIKE '3' $where_agency $where_service $parenthese2 AND disable='0'";
			$query=$db->query($query);
			$nbres=$query->fetch();
			$query->closeCursor(); 
			
			$query=$db->query("SELECT * FROM tusers WHERE id LIKE '$uid'");
			$reqfname=$query->fetch();
			$query->closeCursor(); 
			
			$query=$db->query("SELECT SUM(time_hope-time) FROM tincidents WHERE time_hope-time>0 AND technician LIKE '$uid' AND disable='0' AND (state='1' OR state='2' OR state='6') $where_agency $where_service $parenthese2");
			$nbtps=$query->fetch();
			$query->closeCursor();
			
			$query=$db->query("SELECT SUM(time_hope-time) FROM tincidents WHERE time_hope-time>0 AND technician LIKE '$uid' AND disable='0' AND (state='1' OR state='2' OR state='6') $where_agency $where_service $parenthese2");
			$ra1=$query->fetch();
			$query->closeCursor();
			
			$query=$db->query("select count(*) from tincidents where technician LIKE '$uid' AND date_create LIKE '$daydate' $where_agency $where_service $parenthese2 AND disable='0';");
			$ra2=$query->fetch();
			$query->closeCursor();
			
			$query=$db->query("SELECT count(*) FROM tincidents WHERE technician='0' AND t_group='0' AND techread='0' $where_agency $where_service $parenthese2 AND disable='0'");
			$nbun=$query->fetch();
			$query->closeCursor();
			
			if ($nbun[0]!=0) {
				$new='<a href="./index.php?page=dashboard&userid=0&state=%"><img style="border-style: none" alt="img" title="'.$nbun[0].' nouvelles demandes" src="./images/wait_min.png" /></a>';
			} else {$new='';}
			if (($ra2[0]==0)&&($ra1[0]==0)){$ratio=0;}
			else if ($ra2[0]==0){$ratio=0;}
			else {
				$ratio=$ra1[0]/$ra2[0];
				$ratio= substr($ratio, 0, 3);
				}
			$nbtps=round($nbtps[0]/60);
			echo '
			<body class="'.$ruser['skin'].'">
				<div class="navbar navbar-default" id="navbar">
					<script type="text/javascript">
						try{ace.settings.check(\'navbar\' , \'fixed\')}catch(e){}
					</script>

					<div class="navbar-container" id="navbar-container">
						<div class="navbar-header pull-left">
							<a href="./index.php?page=dashboard&userid='.$_GET['userid'].'&state='.$_GET['state'].'" class="navbar-brand">
								<i class="icon-ticket" title="'.$rparameters['version'].'" ></i>
								';
								if($mobile==0){echo 'GestSup <span style="font-size: x-small;">'.$rparameters['version'].'</span>';}
								//re-size logo if height superior 40px
								if ($rparameters['logo']!='' && (file_exists("./upload/logo/$rparameters[logo]"))) 
								{
									$height = getimagesize("./upload/logo/$rparameters[logo]");
									$height=$height[1];
									if ($height>40) {$logo_size='height="40"';} else {$logo_size='';}
								} else {$logo_size='';}
								if (file_exists("./upload/logo/$rparameters[logo]"))
								{
									echo '&nbsp;<img style="border-style: none" '.$logo_size.' alt="logo" src="./upload/logo/'; if ($rparameters['logo']=='') echo 'logo.png'; else echo $rparameters['logo'];  echo '" />';
								}
								echo '
								<small>
									&nbsp;'; if (isset($rparameters['company'])) echo $rparameters['company']; echo '
								</small>
							</a><!--/.brand-->
						</div><!-- /.navbar-header -->
						<div class="navbar-header pull-right" role="navigation">
							<ul class="nav ace-nav">';
								if ($rright['userbar']!=0)
								{
									if ($cnt5[0]>0 && $rright['side_your_not_attribute']!=0)
									{
									echo'
										<li class="red">
											<a title="'.T_('Ticket en attente d\'attribution').'" href="./index.php?page=dashboard&amp;userid=0&amp;t_group=0&amp;state=%">
												<i class="icon-bell-alt icon-animated-bell"></i>
												<span class="badge badge-important">'.$cnt5[0].'</span>
											</a>
										</li>';
									}
									if ($cnt3[0]>0 && $rright['side_your_not_read']!=0)
									{
									echo'
										<li class="light-orange">
											<a title="'.T_('Tickets en attente de lecture').'" href="./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;techread=0">
												<i class="icon-bell-alt icon-animated-bell"></i>
												<span class="badge badge-yellow">'.$cnt3[0].'</span>
											</a>
										</li>';
									}
									echo'
									<li class="blue">
										<a title="'.T_('Tickets ouverts, fermés ou sur lesquels un élément de résolution a été ajouté').'" href="./index.php?page=dashboard&amp;userid=%&amp;state=%&amp;ticket=%&amp;technician=%&amp;user=%&amp;category=%&amp;subcat=%&amp;title=%&amp;date_create=%&amp;priority=%&amp;criticality=%&amp;company=%&amp;view=activity&amp;date_start='.date("d/m/Y").'&date_end='.date("d/m/Y").'">
											<i class="icon-calendar"></i>
											';
											if($mobile==0) {echo T_('Activité');}
											//echo '<span class="badge badge-blue">'.$nbday[0].'</span>';
											echo'
										</a>
									</li>
									';
									//generate in treatment state
									if ($rparameters['meta_state']==1) {$link_meta_state='./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;state=meta';} else {$link_meta_state='./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;state=1';}
									echo'
									<li class="grey">
										<a title="'.T_('Vos tickets en attente de prise en charge, en cours et en attente de retour').'" href="'.$link_meta_state.'">
											<i class="icon-flag"></i>
											';
											if($mobile==0) {
												echo T_('A traiter');
												echo ' <span title="'.T_('Vos tickets en attente de prise en charge et en cours').'" class="badge badge-grey">'.$nbatt2[0].'</span>';
											}
											echo '
											<span title="'.T_('Vos tickets en attente de prise en charge, en cours et en attente de retour').'" class="badge badge-grey">'.$nbatt[0].'</span>
										</a>
									</li>
									';
									//display current technician load if parameters are on
									if ($rright['ticket_time_disp']!=0 && $rright['ticket_time_hope_disp']!=0)
									{
										echo '
										<li class="purple">
											<a title="'.T_('Nombre d\'heures de travail estimé dans vos tickets ouverts').'" href="./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;state=1">
												<i class="icon-dashboard"></i>
												';
												if($mobile==0) {echo T_('Charge');}
												echo '
												<span class="badge badge-important">'.$nbtps.'h</span>
											</a>
										</li>
										';
									}
								}
								//display remain tickets for user ticket limit
								if ($rparameters['user_limit_ticket']==1 && $ruser['limit_ticket_number']!=0 && $ruser['limit_ticket_days']!=0 && $ruser['limit_ticket_date_start']!='0000-00-00' &&($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2))
								{
									//generate date start and date end
									$date_start=$ruser['limit_ticket_date_start'];
									
									//calculate end date	
									$date_start_conv = date_create($ruser['limit_ticket_date_start']);
									date_add($date_start_conv, date_interval_create_from_date_string("$ruser[limit_ticket_days] days"));
									$date_end=date_format($date_start_conv, 'Y-m-d');
								
									//count number of ticket remaining in period
									$query=$db->query("SELECT count(*) FROM tincidents WHERE user='$_SESSION[user_id]'  AND date_create BETWEEN '$date_start' AND '$date_end' AND disable='0'");
									$nbticketused=$query->fetch();
									$query->closeCursor();
									
									//check number of tickets in current range date
									if (date('Y-m-d')>$date_end || date('Y-m-d')<$date_start)
									{
										$nbticketremaining=0;
									} else {
										$nbticketremaining=$ruser['limit_ticket_number']-$nbticketused[0];
									}
									echo '
									<li class="purple">
										<a title="'.T_('Nombre de tickets restants disponible, valable du').' '.$date_start.' '.T_('au').' '.$date_end.'" href="">
											<i class="icon-dashboard"></i>
											'.T_('Tickets disponible').'
											<span class="badge badge-important">'.$nbticketremaining.'</span>
										</a>
									</li>
									';
								}
								//display remain tickets for company ticket limit
								if ($rparameters['company_limit_ticket']==1 &&($_SESSION['profile_id']==1 || $_SESSION['profile_id']==2))
								{
									//get company limit ticket parameters
									$query=$db->query("SELECT * FROM tcompany WHERE id=$ruser[company]");
									$rcompany=$query->fetch();
									$query->closeCursor();
									if ($rcompany['limit_ticket_number']!=0 && $rcompany['limit_ticket_days']!=0 && $rcompany['limit_ticket_date_start']!='0000-00-00' )
									{
										//generate date start and date end
										$date_start=$rcompany['limit_ticket_date_start'];
										
										//calculate end date	
										$date_start_conv = date_create($rcompany['limit_ticket_date_start']);
										date_add($date_start_conv, date_interval_create_from_date_string("$rcompany[limit_ticket_days] days"));
										$date_end=date_format($date_start_conv, 'Y-m-d');
									
										//count number of ticket remaining in period
										$query=$db->query("SELECT count(*) FROM tincidents,tusers WHERE tusers.id=tincidents.user AND tusers.company='$rcompany[id]' AND date_create BETWEEN '$date_start' AND '$date_end' AND tincidents.disable='0'");
										$nbticketused=$query->fetch();
										$query->closeCursor();
										
										//check number of tickets in current range date
										if (date('Y-m-d')>$date_end || date('Y-m-d')<$date_start)
										{
											$nbticketremaining=0;
										} else {
											$nbticketremaining=$rcompany['limit_ticket_number']-$nbticketused[0];
										}
										echo '
										<li class="purple">
											<a title="'.T_('Nombre de tickets restants disponible, valable du').' '.$date_start.' '.T_('jusqu\'au').' '.$date_end.'" href="">
												<i class="icon-dashboard"></i>
												'.T_('Tickets disponible').'
												<span class="badge badge-important">'.$nbticketremaining.'</span>
											</a>
										</li>
										';
									}
								}
								echo'
								<li class="green">
									<a title="'.T_('Vos tickets résolus').'" href="./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'&amp;state=3">
										<i class="icon-ok-circle "></i>
										';
										if($mobile==0) {echo T_('Résolus');}
										echo'
										<span class="badge badge-success">'.$nbres[0].'</span>
									</a>
								</li>';
								echo '
								<li class="light-blue">
									<a data-toggle="dropdown" href="#" class="dropdown-toggle">
										<img class="nav-user-photo" src="./images/avatar/';
											$query=$db->query("SELECT img FROM tprofiles WHERE level=$_SESSION[profile_id]");
											$rprofile_img=$query->fetch();
											$query->closeCursor();
											echo $rprofile_img[0];
										echo '
										" alt="img" />
										<span class="user-info">
											<small>'.T_('Bienvenue').',</small>
											'.$reqfname['firstname'].' '.$reqfname['lastname'].'
										</span>
										<i class="icon-caret-down"></i>
									</a>
									<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
										<li>
											<a href="./index.php?page=admin/user&amp;action=edit&amp;userid='.$_SESSION['user_id'].'">
												<i class="icon-user"></i>
												'.T_('Profil').'
											</a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="./index.php?action=logout">
												<i class="icon-off"></i>
												'.T_('Déconnexion').'
											</a>
										</li>
									</ul>
								</li>
							</ul><!--/.ace-nav-->
						</div><!--/.container-fluid-->
					</div><!--/.navbar-inner-->
				</div>
				<div class="main-container" id="main-container">
					<script type="text/javascript">
						try{ace.settings.check(\'main-container\' , \'fixed\')}catch(e){}
					</script>
					<div class="main-container-inner">
						<a class="menu-toggler" id="menu-toggler" href="#">
							<span class="menu-text"></span>
						</a>';
						//display menu and send parameters for the default page
						if ($_GET['page']=="") {$_GET['page']="dashboard"; $_GET['state']="%"; $_GET['userid']=$_SESSION['user_id'];}
						require('./menu.php'); echo'
						<div class="main-content">
							<div class="breadcrumbs" id="breadcrumbs">
								<script type="text/javascript">
									try{ace.settings.check(\'breadcrumbs\' , \'fixed\')}catch(e){}
								</script>
								<ul class="breadcrumb">
									<li>
										';
										//previous page on ticket page
										if($_GET['page']=='ticket' ) {
											//init var
											if(!isset($_GET['order'])) $_GET['order'] = '';
											if(!isset($_GET['user'])) $_GET['user'] = '';
											if(!isset($_GET['sender_service'])) $_GET['sender_service'] = '';
											if(!isset($_GET['company'])) $_GET['company'] = '';
											if(!isset($_GET['companyview'])) $_GET['companyview'] = '';
											if(!isset($_GET['service'])) $_GET['service'] = '';
											if(!isset($_GET['agency'])) $_GET['agency'] = '';
											if(!isset($_GET['asset'])) $_GET['asset'] = '';
											//url using in cancel button from ticket page and back arrow on ticket
											$url_get_parameters='state='.$_GET['state'].'&userid='.$_GET['userid'].'&technician='.$_GET['technician'].'&user='.$_GET['user'].'&sender_service='.$_GET['sender_service'].'&category='.$_GET['category'].'&subcat='.$_GET['subcat'].'&asset='.$_GET['asset'].'&title='.$_GET['title'].'&date_create='.$_GET['date_create'].'&priority='.$_GET['priority'].'&criticality='.$_GET['criticality'].'&viewid='.$_GET['viewid'].'&type='.$_GET['type'].'&place='.$_GET['place'].'&service='.$_GET['service'].'&agency='.$_GET['agency'].'&company='.$_GET['company'].'&view='.$_GET['view'].'&date_range='.$_GET['date_range'].'&date_start='.$_GET['date_start'].'&date_end='.$_GET['date_end'].'&keywords='.$_GET['keywords'].'&companyview='.$_GET['companyview'].'&order='.$_GET['order'].'&way='.$_GET['way'].'&cursor='.$_GET['cursor'].'';
											echo '<a title="'.T_('Retour à la liste').'" href="./index.php?page=dashboard&'.$url_get_parameters.'" ><i class="icon-arrow-left home-icon"></i></a>';
										}
										//previous page on asset page
										if($_GET['page']=='asset' ) {
											//init var
											if(!isset($_GET['date_end_warranty'])) $_GET['date_end_warranty'] = '';
											if(!isset($_GET['sn_internal'])) $_GET['sn_internal'] = '';
											if(!isset($_GET['order'])) $_GET['order'] = '';
											if(!isset($_GET['ip'])) $_GET['ip'] = '';
											if(!isset($_GET['netbios'])) $_GET['netbios'] = '';
											if(!isset($_GET['user'])) $_GET['user'] = '';
											if(!isset($_GET['model'])) $_GET['model'] = '';
											if(!isset($_GET['description'])) $_GET['description'] = '';
											if(!isset($_GET['department'])) $_GET['department'] = '';
											if(!isset($_GET['date_stock'])) $_GET['date_stock'] = '';
											if(!isset($_GET['virtual'])) $_GET['virtual'] = '';
											//url using to keep data from filter and sort of asset_list on asset page
											$url_get_parameters='sn_internal='.$_GET['sn_internal'].'&ip='.$_GET['ip'].'&netbios='.$_GET['netbios'].'&user='.$_GET['user'].'&type='.$_GET['type'].'&model='.$_GET['model'].'&description='.$_GET['description'].'&department='.$_GET['department'].'&date_stock='.$_GET['date_stock'].'&date_end_warranty='.$_GET['date_end_warranty'].'&assetkeywords='.$_GET['assetkeywords'].'&virtual='.$_GET['virtual'].'&state='.$_GET['state'].'&warranty='.$_GET['warranty'].'&order='.$_GET['order'].'&way='.$_GET['way'].'&cursor='.$_GET['cursor'];
											echo '<a title="'.T_('Retour à la liste').'" href="./index.php?page=asset_list&'.$url_get_parameters.'" ><i class="icon-arrow-left home-icon"></i></a>';
										}
										//first level
										echo '<a href="./index.php?page=dashboard&userid='.$_SESSION['user_id'].'&state=%"><i class="icon-home home-icon"></i></a>';
										if(($_GET['page']=='dashboard' || $_GET['page']=='ticket' || $_GET['page']=='preview_mail' ) && $_GET['viewid']=='') echo ' <a href="./index.php?page=dashboard&amp;userid='.$_SESSION['user_id'].'"&nbsp;>&nbsp;Tickets</a>&nbsp;';
										if($_GET['page']=='procedure') echo ' <a href="./index.php?page=procedure"&nbsp;>&nbsp;'.T_('Procédure').'</a>';
										if($_GET['page']=='planning') echo ' <a href="./index.php?page=planning"&nbsp;>&nbsp;'.T_('Calendrier').'</a>';
										if($_GET['page']=='stat') echo ' <a href="./index.php?page=stat&tab=ticket"&nbsp;>&nbsp;'.T_('Statistiques').'</a>';
										if($_GET['page']=='admin/user' && $_GET['action']=='edit') echo ' <a href="index.php?page=admin/user&action=edit&userid='.$_GET['userid'].'"&nbsp;>&nbsp;'.T_('Fiche utilisateur').'</a>';
										if($_GET['page']=='plugins/availability/index') echo ' <a href="index.php?page=plugins/availability/index"&nbsp;>&nbsp;'.T_('Disponibilité').'</a>';
										if($_GET['page']=='admin' || $_GET['page']=='changelog') echo ' <a href="./index.php?page=admin"&nbsp;>&nbsp;'.T_('Administration').'</a>';
										if($_GET['viewid']!='' || $_GET['page']=='view') echo ' <a href="index.php?page=dashboard"&nbsp;>&nbsp;'.T_('Vues').'</a>';
										if($_GET['page']=='asset' || $_GET['page']=='asset_list') echo ' <a href="index.php?page=asset_list"&nbsp;>&nbsp;'.T_('Équipements').'</a>';
										if($_GET['page']=='asset_stock') echo ' <a href="index.php?page=asset_stock"&nbsp;>&nbsp;'.T_('Entrée en stock').'</a>';
										//second level
										if($_GET['subpage']=='parameters' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=parameters"&nbsp;>&nbsp;'.T_('Paramètres').'</a> ';
										if($_GET['subpage']=='user' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=user"&nbsp;>&nbsp;'.T_('Utilisateurs').'</a> ';
										if($_GET['subpage']=='group' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=group"&nbsp;>&nbsp;'.T_('Groupes').'</a> ';
										if($_GET['subpage']=='profile' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=profile"&nbsp;>&nbsp;'.T_('Droits').'</a> ';
										if($_GET['subpage']=='list' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=list"&nbsp;>&nbsp;'.T_('Listes').'</a> ';
										if($_GET['subpage']=='backup' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=backup"&nbsp;>'.T_('Sauvegardes').'</a> ';
										if($_GET['subpage']=='update' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=update"&nbsp;>&nbsp;'.T_('Mise à jour').'</a> ';
										if($_GET['subpage']=='system' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=system"&nbsp;>&nbsp;'.T_('Système').'</a> ';
										if($_GET['subpage']=='infos' || $_GET['page']=='changelog' ) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=infos"&nbsp;>&nbsp;'.T_('Informations').'</a> ';
										if(($_GET['page']=='ticket' || $_GET['page']=='preview_mail') && $_GET['action']=='new') echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=ticket&action=new&userid=1"&nbsp;>&nbsp;'.T_('Nouveau').'</a> ';
										if(($_GET['page']=='ticket' || $_GET['page']=='preview_mail') && $_GET['action']!='new') echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=ticket&id='.$_GET['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&category='.$_GET['category'].'&subcat='.$_GET['subcat'].'&viewid='.$_GET['viewid'].'"&nbsp;>&nbsp;'.T_('Édition').'</a> ';
										if($_GET['page']=='asset' && $_GET['action']!='new') echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=asset&id='.$_GET['id'].'"&nbsp;>&nbsp;'.T_('Édition').'</a> ';
										if($_GET['page']=='asset' && $_GET['action']=='new') echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=asset&action=new"&nbsp;>&nbsp;'.T_('Nouveau').'</a> ';
										//third level
										if($_GET['page']=='admin' && $_GET['subpage']=='user' && $_GET['ldap']==1) echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=admin&subpage=user&ldap=1"&nbsp;>&nbsp;'.T_('Synchronisation LDAP').'</a> ';
										if($_GET['page']=='preview_mail') echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=preview_mail&id='.$_GET['id'].'&userid='.$_GET['userid'].'&state='.$_GET['state'].'&category='.$_GET['category'].'&subcat='.$_GET['subcat'].'&viewid='.$_GET['viewid'].'"&nbsp;>&nbsp;'.T_('Envoi de mail').'</a> ';
										if($_GET['page']=='changelog') echo '<span class="divider"><i class="icon-angle-right arrow-icon"></i></span><a href="index.php?page=changelog"&nbsp;>&nbsp;'.T_('Changelog').'</a> ';
										echo '
									</li>
								</ul>
								';
								if ($rright['search']!='0')
								{
									echo '
										<div class="nav-search" id="nav-search">
										';
										    if ($_GET['subpage']=='user')
											{
												echo '<form method="POST" action="index.php?page=admin&subpage=user&disable='.$_GET['disable'].'" class="form-search">';
											} elseif ($_GET['page']=='asset_list' || $_GET['page']=='asset') {
												echo '<form method="POST" action="index.php?page=asset_list" class="form-search">';
											} else { 
												echo '<form method="POST" action="./index.php?page=dashboard&userid='.$_GET['userid'].'&state='.$_GET['state'].'" class="form-search">';
											}
												echo '
														<span class="input-icon">
															';
															if ($_GET['subpage']=='user')
															{
																echo '<input type="text" title="'.T_("Lance une recherche dans la liste des utilisateurs").'" placeholder="'.T_('Recherche util...').'" class="input-small nav-search-input" id="userkeywords" name="userkeywords" class="keywords" autocomplete="on" value="'.$userkeywords.'" />';
															} elseif ($_GET['page']=='asset_list' || $_GET['page']=='asset' || $_GET['tab']=='asset') {
																echo '<input type="text" title="'.T_("Lance une recherche dans la liste des équipements").'" placeholder="'.T_('Recherche équipe...').'" class="input-small nav-search-input" id="assetkeywords" name="assetkeywords" class="keywords" autocomplete="on" value="'.$assetkeywords.'" />';
															} else {
																echo '<input type="text" title="'.T_("Lance une recherche dans la liste des tickets").'" placeholder="'.T_('Recherche ticket...').'" class="input-small nav-search-input" id="keywords" name="keywords" class="keywords" autocomplete="on" value="'.$keywords.'" />';
															}
															echo '
															<i class="icon-search nav-search-icon"></i>
														</span>
													</form>
										</div>
									';
								}
								echo '
							</div>
							<div class="page-content">
								';
								//security check own ticket right
								if(($_GET['page']=='ticket') && ($_GET['action']!='new')) 
								{
									$query=$db->query("SELECT user FROM tincidents WHERE id='$_GET[id]'");
									$rticket=$query->fetch();
									$query->closeCursor(); 
								} else $rticket[0]=$_SESSION['user_id'];
								
								//ACL security check for page
								if(
									(
										($_SESSION['profile_id']!=4 && $_SESSION['profile_id']!=0 && $_SESSION['profile_id']!=3) //user profil
										&&
										($_SESSION['user_id']!=$_GET['userid'] && $_GET['userid']!='')  
									) || (
										($_SESSION['profile_id']!=4 && $_SESSION['profile_id']!=0 && $_SESSION['profile_id']!=3) 
										&&
										($rticket[0]!=$_SESSION['user_id']) 
									)
								)
								{
									
									//check if ticket is deleted
									if ($_GET['page']=='ticket' && $_GET['id'])
									{
										$query=$db->query("SELECT disable FROM tincidents WHERE id=$db_id"); 
										$check_ticket_disable=$query->fetch();
										$query->closeCursor(); 
										$check_ticket_disable=$check_ticket_disable['disable'];
									} else {$check_ticket_disable=0;}
									if($check_ticket_disable==1) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Ce ticket à été supprimé, pour le restaurer contacter votre administrateur").'.<br></div>';}
									//allow display pages from availability function
									elseif ($_GET['page']=='plugins/availability/index' && $rright['availability']!=0 && $rparameters['availability']==1){include("$_GET[page].php");} 
									//allow display pages from asset function
									elseif ($_GET['page']=='asset_list' && $rright['asset']!=0 && $rparameters['asset']==1) {include("$_GET[page].php");}
									//allow display pages from template function
									elseif ($_GET['page']=='ticket' && $rright['ticket_template']!=0 && $_GET['action']=='template') {include("$_GET[page].php");}
									//allow display all ticket for user with display all service, if rights are enable
									elseif ($_GET['page']=='dashboard' && $rright['side_all_service_disp']!=0) {include("$_GET[page].php");}
									//allow modify ticket for user with same service service, if rights are enable (cnt_agency for case user have service and agency to allow edit)
									elseif ($_GET['page']=='ticket' && $rright['side_all_service_edit']!=0 && $cnt_service!=0) {
										//check if open ticket is associated to the same service as the current user services
										$query=$db->query("SELECT u_service FROM tincidents WHERE id=$db_id"); //get ticket service
										$check_ticket_service=$query->fetch();
										$query->closeCursor(); 
										$service_check=0;
										foreach($user_services as $value) {if($check_ticket_service[0]==$value){$service_check=1;}}
										if($service_check) {include("$_GET[page].php");}
										else {
											//check if current user is sender
											$query=$db->query("SELECT user FROM tincidents WHERE id=$db_id");
											$check_ticket_user_sender=$query->fetch();
											$query->closeCursor(); 
											if($check_ticket_user_sender[0]!=$_SESSION['user_id'])
											{
												//check if open ticket is associated to the same agency as the current user agencies
												$query=$db->query("SELECT u_agency FROM tincidents WHERE id=$db_id"); //get ticket agency
												$check_ticket_agency=$query->fetch();
												$query->closeCursor(); 
												$agency_check=0;
												foreach($user_agencies as $value) {
													if($check_ticket_agency[0]==$value){$agency_check=1;}
												}
												if($agency_check==1) {include("$_GET[page].php");}
												else {
													echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas les droits d'accès pour modifier le ticket de ce service, contacter votre administrateur").'.<br></div>';
												}
											} else {
												include("$_GET[page].php");
											}
										}
									}
									//allow modify ticket for user with same agency, if rights are enable
									elseif ($_GET['page']=='ticket' && $rright['side_all_agency_edit']!=0 && $cnt_agency!=0 ) {
										//check if open ticket is associated to the same agency as the current user agencies
										$query=$db->query("SELECT u_agency FROM tincidents WHERE id=$db_id"); //get ticket agency
										$check_ticket_agency=$query->fetch();
										$query->closeCursor(); 
										$agency_check=0;
										foreach($user_agencies as $value) {
											if($check_ticket_agency[0]==$value)
											{
												$agency_check=1;
											}
										}
										if($agency_check==1) {include("$_GET[page].php");}
										else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas les droits d'accès pour modifier le ticket de cette agence, contacter votre administrateur").'.<br></div>';}
									}
									//allow display pages to company view
									elseif ($rparameters['user_company_view']==1 && $rright['side_company']!=0 && $ruser['company']!=0)	
									{
										if($_GET['page']=='ticket' && $_GET['id'] && $_GET['action']!='template')
										{
											//check if ticket is the same company than connected user
											$query=$db->query("SELECT * FROM tincidents WHERE id=$db_id");
											$check_ticket_company=$query->fetch();
											$query->closeCursor(); 
											$query=$db->query("SELECT * FROM tusers WHERE id='$check_ticket_company[user]'");
											$check_ticket_company=$query->fetch();
											$query->closeCursor();
											if (($check_ticket_company['company']==$ruser['company']) && ($ruser['company']!=0))
											{
												include("$_GET[page].php");
											} else {
												echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits de consulter ce ticket, contacter votre administrateur').'.<br></div>';
											}
										} elseif ($_GET['page']=='dashboard' || $_GET['action']=='template') {include("$_GET[page].php");}
									}
									else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas les droits d'accès à cette page, contacter votre administrateur").'.<br></div>';}
								} else	{
									
									//check rights page before display
									if ($_GET['page']=='ticket' && $_GET['id']) //check if ticket is deleted
									{
										$query=$db->query("SELECT disable FROM tincidents WHERE id=$db_id"); 
										$check_ticket_disable=$query->fetch();
										$query->closeCursor(); 
												
									} else {$check_ticket_disable[0]=0;}
									if($check_ticket_disable[0]==1 && $_SESSION['profile_id']!=4) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Ce ticket à été supprimé, pour le restaurer contacter votre administrateur").'.<br></div>';}
									elseif ($_GET['page']=='procedure' && $rright['procedure']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès aux procédures, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='ticket_template' && $rright['ticket_template']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès aux modèles de tickets, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='preview_mail' && $rright['ticket_send_mail']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès à la prévisualisation des mails, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='asset_list' && $rright['asset']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès aux équipements, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='asset' && $rright['asset']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès aux équipements, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='asset' && $rright['asset_list_view_only']!=0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès à la fiche de cet équipement, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='asset_stock' && $rright['asset']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès aux équipements, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='planning' && $rright['planning']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès aux calendriers, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='admin/user' && $rright['admin']==0 && $_GET['userid']!=$_SESSION['user_id']) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas le droit de modifier un autre utilisateur, contacter votre administrateur").'.<br></div>';}
									elseif (preg_match( '/^admin.*/', $_GET['page']) && $_GET['page']!='admin/user' && $rright['admin']==0 && $rright['admin_lists']==0 && $rright['admin_groups']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas les droits d'accès à l'administration du logiciel, contacter votre administrateur").'<br></div>';}
									elseif (preg_match( '/^stat.*/', $_GET['page']) && $rright['stat']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès aux statistiques du logiciel, contacter votre administrateur').'.<br></div>';}
									elseif (preg_match( '/^core.*/', $_GET['page']) && $rright['admin']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès à cette page, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='plugins/availability/index' && $rright['availability']==0) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès au module de disponibilité, contacter votre administrateur').'.<br></div>';}
									elseif ($_GET['page']=='ticket' && $rright['dashboard_service_only']!=0 && $_GET['id'] && $rparameters['user_limit_service']==1 && $cnt_agency==0) //case to user profil super try to open another ticket of another service
									{
										//check if ticket service is the same as user
										$query=$db->query("SELECT id FROM tusers_services WHERE user_id='$_SESSION[user_id]' AND service_id=(SELECT u_service FROM tincidents WHERE id=$db_id)");
										$check_ticket_service=$query->fetch();
										$query->closeCursor(); 
										if (!$check_ticket_service) {
											//allow technician to view ticket when there is sender
											$query=$db->query("SELECT user,technician FROM tincidents WHERE id=$db_id");
											$check_ticket_tech_sender=$query->fetch();
											$query->closeCursor(); 
											if($check_ticket_tech_sender[0]!=$_SESSION['user_id'] && $check_ticket_tech_sender[1]!=$_SESSION['user_id']) {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_("Vous n'avez pas les droits de consulter le ticket de ce service, contacter votre administrateur").'.<br></div>';} else {include("$_GET[page].php");}
										} else {
											include("$_GET[page].php");
										}
									}
									elseif ($_GET['page']=='asset' && $rright['asset_list_company_only']!=0 && $_GET['action']!='new') // restrict user access to asset of our company only
									{
										//check is current user have right to display current asset
										$query=$db->query("SELECT company FROM tusers WHERE id=(SELECT user FROM tassets WHERE id=$db_id)");
										$check_asset_company=$query->fetch();
										$query->closeCursor(); 
										if($check_asset_company['company']!=$ruser['company'])
										{echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas les droits d\'accès à la fiche de cet équipement, contacter votre administrateur').'.<br></div>';}
										else {include("$_GET[page].php");} 
									}
									else{include("$_GET[page].php");}
								}
								
								echo '
							</div>
						</div>
					</div>
				</div>
				<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
					<i class="icon-double-angle-up icon-only bigger-110"></i>
				</a>
				';
				//display event modalbox
				include "./event.php"; 
				
				//display change user password modalbox
				$query=$db->query("SELECT * FROM tusers WHERE id='$_SESSION[user_id]'");
				$r=$query->fetch();
				$query->closeCursor(); 
				if ($r['chgpwd']=='1'){include "./modify_pwd.php";}
		}
		else 
		{
			//check SSO
			if($rparameters['ldap_sso']==1 && isset($_SERVER['REMOTE_USER']) && $_GET['action']!='logout')
			{
				$ssologin=explode('@',$_SERVER['REMOTE_USER']);
				//check SSO user exist un GestSup user DB
				$qry = $db->prepare("SELECT `id` FROM `tusers` WHERE login=:login AND disable=:disable");
				$qry->execute(array('login' => $ssologin[0],'disable' => 0));
				$row=$qry->fetch();
				$qry->closeCursor();
				if($row)
				{
					echo '<i class="icon-spinner icon-spin"></i>&nbsp;Connexion SSO...';
					$_SESSION['user_id']=$row['id'];
					//redirect
					if($ruser['default_ticket_state']) $redirectstate=$ruser['default_ticket_state']; else $redirectstate=1;
					if($_GET['id']) {
						$www='./index.php?page=ticket&id='.$_GET['id'].'';
					} else {
						if($redirectstate=='meta_all')
						{
							$www="./index.php?page=dashboard&userid=%&state=meta";
						} else {
							$www="./index.php?page=dashboard&userid=$user_id&state=$redirectstate";
						}
					}
					echo "<SCRIPT LANGUAGE='JavaScript'>
								<!--
								function redirect()
								{
								window.location='$www'
								}
								setTimeout('redirect()');
								-->
							</SCRIPT>";
				} else {
					require('./login.php');
				}
			} else {
				require('./login.php');
			}
		}
		//close database access
		$db = null;
		?>

		<script type="text/javascript">
			window.jQuery || document.write("<script src='./template/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<script src="./template/assets/js/bootstrap.min.js"></script>
		<script src="./template/assets/js/typeahead-bs2.min.js"></script>
		
		<!-- Modalbox -->
		<script src="./template/assets/js/jquery-ui-1.10.3.full.min.js"></script>
		<script src="./template/assets/js/jquery.ui.touch-punch.min.js"></script>
		
		<?php
		//add js for chosen select list
		if (($_GET['page']=='ticket') || ($_GET['page']=='asset')) 
		{
			echo '
					<script src="./template/assets/js/chosen.jquery.min.js"></script>
					<script>
						if($(".chosen-select"))
							$(\'.chosen-select\').chosen({allow_single_deselect:true}); 
					</script>
			';
		}
		
		//bugfix stat page
		if($_GET['page']!='stat'){ echo'<script src="./template/assets/js/ace.min.js"></script><script src="./template/assets/js/ace-elements.min.js"></script>';}
		
		//date conversion
		function date_convert ($date) 
		{return  substr($date,8,2) . '/' . substr($date,5,2) . '/' . substr($date,0,4) . ' '.T_('à').' ' . substr($date,11,2	) . 'h' . substr($date,14,2	);}
		?>
	</body>
</html>