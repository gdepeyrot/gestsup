<?php
####################################################################################
# @Name : ldap_services.php 
# @Description : Synchronize LDAP group of service with GestSup service, and members 
# @Call : /admin/user.php
# @Parameters : 
# @Author : Flox
# @Create : 12/04/2017
# @Update : 05/05/2017
# @Version : 3.1.21
####################################################################################

if(!isset($ldap_query)) $ldap_query = '';
if(!isset($find)) $find = '';
if(!isset($dcgen)) $dcgen = '';
if(!isset($find2_login)) $find2_login= '';
if(!isset($update)) $update= '';
if(!isset($find_dpt)) $find_dpt= '';
if(!isset($find_company)) $find_company= '';
if(!isset($samaccountname)) $samaccountname= '';
if(!isset($ldap_type)) $ldap_type= '';
if(!isset($ldap_auth)) $ldap_auth= '';
if(!isset($g_company)) $g_company= '';
if(!isset($cnt_group)) $cnt_group= 0;
if(!isset($cnt_users)) $cnt_users= 0;
if(!isset($cnt_total_users)) $cnt_total_users= 0;
if(!isset($_GET['action'])) $_GET['action']='';
if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $_SERVER['HTTP_ACCEPT_LANGUAGE']='';
if(!isset($_GET['subpage'])) $_GET['subpage']='';

//call via external script for cron 
if(!isset($rparameters['ldap_user']))
{
	//database connection
	require_once(__DIR__."/../connect.php");
	
	//switch SQL MODE to allow empty values with lastest version of MySQL
	$db->exec('SET sql_mode = ""');
	
	//load parameters table
	$query=$db->query("SELECT * FROM tparameters");
	$rparameters=$query->fetch();
	$query->closeCursor();
	
	//variable
	$_GET['ldap']='1';
	$_GET['action']='run';
	
	//locales
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	if ($lang=='fr') {$_GET['lang'] = 'fr_FR';}
	else {$_GET['lang'] = 'en_US';}
	define('PROJECT_DIR', realpath('../'));
	define('LOCALE_DIR', PROJECT_DIR .'/locale');
	define('DEFAULT_LOCALE', '($_GET[lang]');
	require_once(__DIR__.'/../components/php-gettext/gettext.inc');
	$encoding = 'UTF-8';
	$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;
	T_setlocale(LC_MESSAGES, $locale);
	T_bindtextdomain($_GET['lang'], LOCALE_DIR);
	T_bind_textdomain_codeset($_GET['lang'], $encoding);
	T_textdomain($_GET['lang']);
	
	//define PHP time zone
	date_default_timezone_set('Europe/Paris');
	
	//logfile
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
	{
		$logfileurl=__DIR__.'\..\log\ldap_services.log';
	} else {
		$logfileurl=__DIR__.'/../log/ldap_services.log';
	}
} else {
	$logfileurl='./log/ldap_services.log';
}

	
//LDAP connection parameters
$user=$rparameters['ldap_user']; 
$password=$rparameters['ldap_password']; 
$hostname=$rparameters['ldap_server'];
$domain=$rparameters['ldap_domain'];

//Generate DC Chain from domain parameter
$dcpart=explode(".",$domain);
$i=0;
while($i<count($dcpart)) {
	$dcgen="$dcgen,dc=$dcpart[$i]";
	$i++;
}
	
//LDAP URL for service emplacement
$ldap_service_url="$rparameters[ldap_service_url]$dcgen";
$ldap_url="$rparameters[ldap_url]$dcgen";

//display head title
if ($rparameters['ldap_type']==0) $ldap_type='Active Directory'; else $ldap_type='OpenLDAP';
if ($_GET['subpage']=='user')
{
	echo '
	<div class="page-header position-relative">
		<h1>
			<i class="icon-refresh"></i>   
			'.T_('Synchronisation des groupes de service').': '.$ldap_type.' > GestSup 
		</h1>
	</div>';
}

//LDAP connect
$ldap = ldap_connect($hostname,$rparameters['ldap_port']) or die("Impossible de se connecter au serveur LDAP.");
ldap_set_option($ldap, LDAP_OPT_NETWORK_TIMEOUT, 1);
if ($rparameters['ldap_type']==1) {ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);}
//check LDAP type for bind
if ($rparameters['ldap_type']==0) $ldapbind = ldap_bind($ldap, "$user@$domain", $password); else $ldapbind = ldap_bind($ldap, "cn=$user$dcgen", $password);	
//check ldap authentication
if ($ldapbind) {$ldap_connection='<i title="'.T_('Connecteur opérationnel').'" class="icon-ok-sign icon-large green"></i> '.T_('Connecteur opérationnel').'.';} else {$ldap_connection='<i title="'.T_('Le connecteur ne fonctionne pas vérifier vos paramètres').'" class="icon-remove-sign icon-large red"></i> '.T_('Le connecteur ne fonctionne pas vérifier vos paramètres').'';}
if ($ldapbind) 
{
	$data = array();
	$data_temp = array();
	//ad group filter
	$filter="(&(objectCategory=group)(cn=*))";
	$query = ldap_search($ldap, $ldap_service_url, $filter);
	if($rparameters['debug']==1){
		echo "<u>DEBUG:</u><br />query group ldap_search($ldap, $ldap_service_url, $filter)<br /><br />";
	}
	//put all data to $data
	$data_temp = @ldap_get_entries($ldap, $query);
	$data = array_merge($data, $data_temp);
	//count LDAP number of groups
	$cnt_group += @ldap_count_entries($ldap, $query);
	
	//count GESTSUP number of services
	$q=$db->query("SELECT count(*) FROM tservices WHERE name!='Aucune' and disable='0'"); 
	$cnt_gestsup=$q->fetch();
	$q->closecursor();
	$q=$db->query("SELECT count(*) FROM tservices WHERE name!='Aucune' and disable='1'"); 
	$cnt_gestsup2=$q->fetch();
	$q->closecursor();
	
	echo '<i class="icon-book green"></i> <b><u>'.T_('Vérification des Annuaires').'</u></b><br />';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;'.T_("Nombre de groupes de services trouvés dans l'annuaire LDAP").' '.$ldap_type.': '.$cnt_group.'<br />';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;'.T_("Nombre de services trouvés dans GestSup").': '.$cnt_gestsup[0].' '.T_('activés et').' '.$cnt_gestsup2[0].' '.T_('désactivés').'<br /><br />';
	echo '<i class="icon-edit-sign red"></i> <b><u>'.T_('Modifications à apporter dans GestSup').':</u></b><br />';
	$array_ldap_group = array("");
	$array_ldap_user = array("");
	$cnt_total_users=0;
	//for each LDAP group 
	for ($i=0; $i < $cnt_group; $i++) 
	{
		//Initialize variable for empty data
		if(!isset($data[$i]['distinguishedname'][0])) $data[$i]['distinguishedname'][0] = '';
		if(!isset($data[$i]['samaccountname'][0])) $data[$i]['samaccountname'][0] = '';
		if(!isset($data[$i]['objectguid'][0])) $data[$i]['objectguid'][0] = '';
		
		//get group data from Windows AD & transform in UTF-8
		$LDAP_group_name=utf8_encode($data[$i]['distinguishedname'][0]);
		$LDAP_group_samaccountname=utf8_encode($data[$i]['samaccountname'][0]);
		$LDAP_group_samaccountname=str_replace ('','Œ', $LDAP_group_samaccountname); //special char oe treatment
		$LDAP_group_objectguid=unpack("H*hex",$data[$i]['objectguid'][0]);
		$LDAP_group_objectguid=$LDAP_group_objectguid['hex'];
		if($rparameters['debug']==1) {echo "<u>LDAP_group_name=<b>$LDAP_group_samaccountname</b> (<font size=\"1\">GUID: $LDAP_group_objectguid</font>):</u><br /> ";}
		
		//keep services guid to disable GS service
		array_push($array_ldap_group, "$LDAP_group_objectguid", "$LDAP_group_samaccountname");
		//compare GS database & LDAP directory
		$query2="SELECT id,name,ldap_guid FROM tservices WHERE ldap_guid='$LDAP_group_objectguid'";
		$query2=$db->query($query2);  //check if service guid exit in GS database
		$GS_group=$query2->fetch();
		$query2->closecursor();
		
		if($_GET['action']=='simul')
		{
			if(!$GS_group[0])
			{
				//insert new service in GS db
				echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_("Création du service").': <b>'.$LDAP_group_samaccountname.'</b> (<font size="1">'.T_("Raison: Le guid du groupe LDAP n'a pas été trouvé dans la liste des services GestSup").'</font>)</font><br />';
			} else {
				//check update group name in GS db
				if ($GS_group['name']!=$LDAP_group_samaccountname)
				{
					echo '<br /><i class="icon-circle-arrow-up green bigger-130"></i><font color="green"> '.T_("Mise à jour du nom du service").': <b>'.$LDAP_group_samaccountname.'</b> (<font size="1">'.T_("Raison: Un guid commun à été trouvé et le nom $GS_group[name] est différent").'</font>)</font><br />';
					if (preg_match("#_OLD#",$LDAP_group_samaccountname)) {echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("Désactivation du service").': <b>'.$LDAP_group_samaccountname.'</b> (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Le nom du service comporte les lettres _OLD").'.</span></font>)<br />';}
				}
			}
		} elseif($_GET['action']=='run')
		{
			if(!$GS_group[0])
			{
				//insert new service in GS db
				echo '<br /><i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_("Service").': <b>'.$LDAP_group_samaccountname.'</b> '.T_("crée").' (<font size="1">'.T_("Raison: Le guid du groupe LDAP n'a pas été trouvé dans la liste des services GestSup").'</font>)</font><br />';
				$LDAP_group_samaccountname= $db->quote($LDAP_group_samaccountname); 
				$db->exec("INSERT INTO tservices (name,ldap_guid) VALUES ($LDAP_group_samaccountname,'$LDAP_group_objectguid')");
				$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Create service $LDAP_group_samaccountname  \n"; file_put_contents($logfileurl, $logfile);
			} else {
				//check update group name in GS db
				if ($GS_group['name']!=$LDAP_group_samaccountname)
				{
					echo '<br /><i class="icon-circle-arrow-up green bigger-130"></i><font color="green"> '.T_("Le nom du service").': <b>'.$LDAP_group_samaccountname.'</b> '.T_("à été mis à jour").' (<font size="1">'.T_("Raison: Un guid commun à été trouvé et le nom $GS_group[name] est différent").'</font>)</font><br />';
					$LDAP_group_samaccountname= $db->quote($LDAP_group_samaccountname); 
					$db->exec("UPDATE tservices SET name=$LDAP_group_samaccountname,disable=0 WHERE ldap_guid='$LDAP_group_objectguid'");
					$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Update service name $LDAP_group_samaccountname  \n"; file_put_contents($logfileurl, $logfile);
					if (preg_match("#_OLD#",$LDAP_group_samaccountname)) {
						echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("Service").': <b>'.$LDAP_group_samaccountname.'</b> '.T_("désactivé").' (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Le nom du service comporte les lettres _OLD").'.</span></font>)<br />';
						$db->exec("UPDATE tservices SET disable=1 WHERE ldap_guid='$LDAP_group_objectguid'");
						$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Disable service $LDAP_group_samaccountname \n"; file_put_contents($logfileurl, $logfile);
					}

				}
			}
		}
		//get members of this group
		$cnt_users=0;
		$data2 = array();
		$data2_temp = array();
		$filter2="(&(objectCategory=user)(memberof:1.2.840.113556.1.4.1941:=$LDAP_group_name))";
		//$filter2=str_replace('ô','\\*', $filter2);
		//$query2 = ldap_search($ldap, $ldap_service_url, $filter2);
		$query2 = ldap_search($ldap, $ldap_url, $filter2);
		if($rparameters['debug']==1){echo "<font size='1'>query find users ldap_search($ldap, $ldap_url, $filter2)</font><br />";}
		//put all data to $data2
		$data2_temp = @ldap_get_entries($ldap, $query2);
		$data2 = array_merge($data2, $data2_temp);
		//count LDAP number of user
		$cnt_users += @ldap_count_entries($ldap, $query2);
		//display all group data for debug
		for ($i2=0; $i2 < $cnt_users; $i2++) 
		{
			
			//Initialize variable for empty data
			if(!isset($data2[$i2]['cn'][0])) $data2[$i2]['cn'][0] = '';
			if(!isset($data2[$i2]['samaccountname'][0])) $data2[$i2]['samaccountname'][0] = '';
			if(!isset($data2[$i2]['givenname'][0])) $data2[$i2]['givenname'][0] = '';
			if(!isset($data2[$i2]['sn'][0])) $data2[$i2]['sn'][0] = '';
			if(!isset($data2[$i2]['objectguid'][0])) $data2[$i2]['objectguid'][0] = '';
			if(!isset($data2[$i2]['useraccountcontrol'][0])) $data2[$i2]['useraccountcontrol'][0] = '';
			if(!isset($data2[$i2]['mail'][0])) $data2[$i2]['mail'][0] = '';
			if(!isset($data2[$i2]['telephonenumber'][0])) $data2[$i2]['telephonenumber'][0] = '';
			if(!isset($data2[$i2]['streetaddress'][0])) $data2[$i2]['streetaddress'][0] = '';
			if(!isset($data2[$i2]['streetaddress'][0])) $data2[$i2]['streetaddress'][0] = '';
			if(!isset($data2[$i2]['postalcode'][0])) $data2[$i2]['postalcode'][0] = '';
			if(!isset($data2[$i2]['l'][0])) $data2[$i2]['l'][0] = '';
			if(!isset($data2[$i2]['company'][0])) $data2[$i2]['company'][0] = '';
			if(!isset($data2[$i2]['facsimiletelephonenumber'][0])) $data2[$i2]['facsimiletelephonenumber'][0] = '';
			if(!isset($data2[$i2]['title'][0])) $data2[$i2]['title'][0] = '';
			//get data from table data to variables
			$LDAP_user_guid=utf8_encode($data2[$i2]['objectguid'][0]);
			$LDAP_user_guid=unpack("H*hex",$data2[$i2]['objectguid'][0]);
			$LDAP_user_guid=$LDAP_user_guid['hex'];
			$LDAP_user_guid=str_replace(' ','', $LDAP_user_guid);
			$LDAP_user_cn=utf8_encode($data2[$i2]['cn'][0]);
			$LDAP_user_samaccountname=utf8_encode($data2[$i2]['samaccountname'][0]);
			$LDAP_user_givenname=utf8_encode($data2[$i2]['givenname'][0]);
			$LDAP_user_sn=utf8_encode($data2[$i2]['sn'][0]);
			$LDAP_user_uac=$data2[$i2]['useraccountcontrol'][0];
			$LDAP_user_mail=$data2[$i2]['mail'][0];
			$LDAP_user_telephonenumber=$data2[$i2]['telephonenumber'][0];  
			$LDAP_user_streetaddress=utf8_encode($data2[$i2]['streetaddress'][0]);  
			$LDAP_user_postalcode=$data2[$i2]['postalcode'][0]; 
			$LDAP_user_l=utf8_encode($data2[$i2]['l'][0]); 
			$LDAP_user_company=utf8_encode($data2[$i2]['company'][0]); 
			$LDAP_user_fax=$data2[$i2]['facsimiletelephonenumber'][0]; 
			$LDAP_user_title=utf8_encode($data2[$i2]['title'][0]); 

			if($rparameters['debug']==1) {echo "<font size=\"1\"> [<b>$LDAP_user_samaccountname</b>] LDAP DATA: guid=$LDAP_user_guid cn=$LDAP_user_cn samaccountname=$LDAP_user_samaccountname givenname=$LDAP_user_givenname sn=$LDAP_user_sn uac=$LDAP_user_uac mail=$LDAP_user_mail telephonenumber=$LDAP_user_telephonenumber streetaddress=$LDAP_user_streetaddress postalcode=$LDAP_user_postalcode l=$LDAP_user_l company=$LDAP_user_company fax=$LDAP_user_fax title=$LDAP_user_title</font><br>";}
			$cnt_total_users++;
			
			//user update and create and assoc with service
			$find_user=0;
			$query2= $db->query("SELECT * FROM `tusers` WHERE ldap_guid='$LDAP_user_guid'"); //for each Gestsup user 
			$GS_user=$query2->fetch();
			$query2->closeCursor();
			if ($GS_user)
			{
				//push data in array to remove user from service in last part
				$GS_service_id=$GS_group['id'];
				$GS_user_id=$GS_user['id'];
				$array_service_members[$GS_service_id][]=$GS_user_id;
				
				//update user if LDAP information is available
				$user_update='';
				if ($_GET['action']=='simul')
				{	
					if($LDAP_user_samaccountname!=$GS_user['login']) {$user_update=T_("l'identifiant").',';}
					if($LDAP_user_givenname!=$GS_user['firstname']) {$user_update.=T_("le prénom").',';}
					if($LDAP_user_sn!=$GS_user['lastname']) {$user_update.=T_("le nom").',';}
					if($LDAP_user_mail!=$GS_user['mail']) {$user_update.=T_("le mail").',';}
					if($LDAP_user_telephonenumber!=$GS_user['phone']) {$user_update.=T_("le téléphone").',';}
					if($LDAP_user_streetaddress!=$GS_user['address1']) {$user_update.=T_("l'adresse").',';}
					if($LDAP_user_postalcode!=$GS_user['zip']) {$user_update.=T_("le code postal").',';}
					if($LDAP_user_l!=$GS_user['city']) {$user_update.=T_("la ville").',';}
					if($LDAP_user_fax!=$GS_user['fax']) {$user_update.=T_("le FAX").',';}
					if($LDAP_user_title!=$GS_user['function']) {$user_update.=T_("la fonction");}
				
					//update user service association
					$query2="SELECT id FROM tservices WHERE ldap_guid='$LDAP_group_objectguid'";
					$query2=$db->query($query2); 
					$GS_service=$query2->fetch();
					$query2->closecursor();
					
					$query2="SELECT id FROM tusers_services WHERE user_id='$GS_user[id]' AND service_id='$GS_service[id]'";
					$query2=$db->query($query2);  //check if service guid exit in GS database
					$assoc=$query2->fetch();
					$query2->closecursor();
					if ($assoc[0]==0){$user_update.=T_("l'association avec le service.");}
					if($user_update) {echo '<i class="icon-circle-arrow-up green bigger-130"></i><font color="green"> '.T_("Mise à jour de l'utilisateur").': <b>'.$LDAP_user_samaccountname.'</b> (<font size="1">'.T_("Raison: Le guid LDAP du service est identique à celui de GestSup et une différence à été trouvé dans ").' '.$user_update.'</font>)</font><br />';}

				} elseif($_GET['action']=='run') {
					//update GS user informations
					if($LDAP_user_samaccountname!=$GS_user['login']) {$LDAP_user_samaccountname = $db->quote($LDAP_user_samaccountname); $db->exec("UPDATE tusers SET login=$LDAP_user_samaccountname WHERE ldap_guid='$LDAP_user_guid'"); $user_update=T_("l'identifiant").',';}
					if($LDAP_user_givenname!=$GS_user['firstname']) {$LDAP_user_givenname = $db->quote($LDAP_user_givenname); $db->exec("UPDATE tusers SET firstname=$LDAP_user_givenname WHERE ldap_guid='$LDAP_user_guid'"); $user_update.=T_("le prénom").',';}
					if($LDAP_user_sn!=$GS_user['lastname']) {$LDAP_user_sn = $db->quote($LDAP_user_sn); $db->exec("UPDATE tusers SET lastname=$LDAP_user_sn WHERE ldap_guid='$LDAP_user_guid'"); $user_update.=T_("le nom").',';}
					if($LDAP_user_mail!=$GS_user['mail']) {$LDAP_user_mail = $db->quote($LDAP_user_mail); $db->exec("UPDATE tusers SET mail=$LDAP_user_mail WHERE ldap_guid='$LDAP_user_guid'");$user_update.=T_("le mail").',';}
					if($LDAP_user_telephonenumber!=$GS_user['phone']) {$LDAP_user_telephonenumber = $db->quote($LDAP_user_telephonenumber); $db->exec("UPDATE tusers SET phone=$LDAP_user_telephonenumber WHERE ldap_guid='$LDAP_user_guid'");$user_update.=T_("le téléphone").',';}
					if($LDAP_user_streetaddress!=$GS_user['address1']) {$LDAP_user_streetaddress = $db->quote($LDAP_user_streetaddress); $db->exec("UPDATE tusers SET address1=$LDAP_user_streetaddress WHERE ldap_guid='$LDAP_user_guid'");$user_update.=T_("l'adresse").',';}
					if($LDAP_user_postalcode!=$GS_user['zip']) {$LDAP_user_postalcode = $db->quote($LDAP_user_postalcode); $db->exec("UPDATE tusers SET zip=$LDAP_user_postalcode WHERE ldap_guid='$LDAP_user_guid'");$user_update.=T_("le code postal").',';}
					if($LDAP_user_l!=$GS_user['city']) {$LDAP_user_l = $db->quote($LDAP_user_l); $db->exec("UPDATE tusers SET city=$LDAP_user_l WHERE ldap_guid='$LDAP_user_guid'"); $user_update.=T_("la ville").',';}
					if($LDAP_user_fax!=$GS_user['fax']) {$LDAP_user_fax = $db->quote($LDAP_user_fax); $db->exec("UPDATE tusers SET fax=$LDAP_user_fax WHERE ldap_guid='$LDAP_user_guid'"); $user_update.=T_("le FAX").',';}
					if($LDAP_user_title!=$GS_user['function']) {$LDAP_user_title = $db->quote($LDAP_user_title); $db->exec("UPDATE tusers SET function=$LDAP_user_title WHERE ldap_guid='$LDAP_user_guid'"); $user_update.=T_("la fonction");}
					
					//update user service association
					$query2="SELECT id,name FROM tservices WHERE ldap_guid='$LDAP_group_objectguid'";
					$query2=$db->query($query2); 
					$GS_service=$query2->fetch();
					$query2->closecursor();
					
					$query2="SELECT id FROM tusers_services WHERE user_id='$GS_user[id]' AND service_id='$GS_service[id]'";
					$query2=$db->query($query2);  //check if service guid exit in GS database
					$assoc=$query2->fetch();
					$query2->closecursor();
					if ($assoc[0]==0)
					{
						$user_update.=T_("l'association avec le service.");
						$db->exec("INSERT INTO tusers_services (user_id,service_id) VALUES ('$GS_user[id]','$GS_service[id]')");
						$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Update association service between user $GS_user[login] and service $GS_service[name] \n"; file_put_contents($logfileurl, $logfile);
					} 
					if($user_update) {
						echo '<i class="icon-circle-arrow-up green bigger-130"></i><font color="green"> '.T_("Utilisateur").': <b>'.$LDAP_user_samaccountname.'</b> '.T_("mis à jour").' (<font size="1">'.T_("Raison: Le guid LDAP est identique à celui de GestSup et une différence à été trouvé dans ").' '.$user_update.'</font>)</font><br />';
						$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Update user informations from LDAP for user $LDAP_user_samaccountname ($user_update)\n"; file_put_contents($logfileurl, $logfile);
					}

				}
			} else {
				//create user
				if ($_GET['action']=='simul')
				{
					echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_("Création de l'utilisateur").': <b>'.$LDAP_user_samaccountname.'</b> (<font size="1">'.T_("Raison: Le guid de l'utilisateur trouvé dans l'annuaire LDAP n'est pas présent dans GestSup").'</font>)</font><br />';
				}elseif ($_GET['action']=='run')
				{
					echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_("Utilisateur").': <b>'.$LDAP_user_samaccountname.'</b> '.T_("crée").' (<font size="1">'.T_("Raison: Le guid de l'utilisateur trouvé dans l'annuaire LDAP n'est pas présent dans GestSup").'</font>)</font><br />';	
					//escape special characters
					$LDAP_user_samaccountname = $db->quote($LDAP_user_samaccountname); $LDAP_user_givenname = $db->quote($LDAP_user_givenname); $LDAP_user_sn = $db->quote($LDAP_user_sn); $LDAP_user_mail = $db->quote($LDAP_user_mail); $LDAP_user_telephonenumber = $db->quote($LDAP_user_telephonenumber);  $LDAP_user_streetaddress = $db->quote($LDAP_user_streetaddress); $LDAP_user_postalcode = $db->quote($LDAP_user_postalcode); $LDAP_user_l = $db->quote($LDAP_user_l); $LDAP_user_fax = $db->quote($LDAP_user_fax); $LDAP_user_guid = $db->quote($LDAP_user_guid); 
					//db insert
					$db->exec("INSERT INTO tusers (login,firstname,lastname,profile,mail,phone,address1,zip,city,fax,ldap_guid) VALUES ($LDAP_user_samaccountname,$LDAP_user_givenname,$LDAP_user_sn,'2',$LDAP_user_mail,$LDAP_user_telephonenumber,$LDAP_user_streetaddress,$LDAP_user_postalcode,$LDAP_user_l,$LDAP_user_fax,$LDAP_user_guid)");
					$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Create user $LDAP_user_samaccountname  \n"; file_put_contents($logfileurl, $logfile);
				}
			}	
		}
		if ($rparameters['debug']==1) {echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- TOTAL users for service '.$LDAP_group_samaccountname.': '.$cnt_users.'<br />';}
	}
	
	//get all users from root user LDAP UO to check both agency group and service group, place all result in array for next step 
	$cnt_users=0;
	$data3 = array();
	$data3_temp = array();
	$filter3="(&(objectClass=user)(objectCategory=person)(cn=*))";
	if($rparameters['debug']==1){echo "<font size='1'>query find users ldap_search($ldap, $ldap_url, $filter3)</font><br />";}
	$query3 = ldap_search($ldap, $ldap_url, $filter3);
	//put all data to $data3
	$data3_temp = @ldap_get_entries($ldap, $query3);
	$data3 = array_merge($data3, $data3_temp);
	//count LDAP number of user
	$cnt_users += @ldap_count_entries($ldap, $query3);
	//display all group data for debug
	for ($i3=0; $i3 < $cnt_users; $i3++) 
	{
		if(!isset($data3[$i3]['objectguid'][0])) $data3[$i3]['objectguid'][0] = '';
		$LDAP_user_guid=utf8_encode($data3[$i3]['objectguid'][0]);
		$LDAP_user_guid=unpack("H*hex",$data3[$i3]['objectguid'][0]);
		$LDAP_user_guid=$LDAP_user_guid['hex'];
		$LDAP_user_guid=str_replace(' ','', $LDAP_user_guid);
		//push in array all ldap user guid for delete check on next step
		array_push($array_ldap_user, "$LDAP_user_guid");
	}
	
	//for each GS user
	$query = $db->query("SELECT id,login,ldap_guid FROM `tusers` WHERE disable='0'"); //for each Gestsup user 
	while ($row=$query->fetch())	
	{
		//remove user from GS service if not present in LDAP service
		$query2 = $db->query("SELECT service_id FROM `tusers_services` WHERE user_id='$row[id]'"); //for each Gestsup service 
		while ($row2=$query2->fetch())	
		{
			//init var
			if(!array_key_exists($row2['service_id'], $array_service_members)){$array_service_members[$row2['service_id']]=array();}
			//$array_service_members[$row2['service_id']]=array();
			if (!in_array("$row[id]", $array_service_members[$row2['service_id']])) {
				//get service name to display
				$query3 = $db->query("select id,name FROM tservices where id='$row2[service_id]'");
				$row3 = $query3->fetch();
				$query3->closecursor();
				if($_GET['action']=='simul')
				{
					echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("Suppression de l'utilisateur ").': <b>'.$row['login'].'</b> '.T_("du service").' <b>'.$row3['name'].'</b> (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Utilisateur présent dans le service GestSup mais pas dans le service LDAP").'.</span></font>)<br />';
				} elseif($_GET['action']=='run')
				{
					echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("L'utilisateur ").': <b>'.$row['login'].'</b> '.T_("à été supprimé du service").' <b>'.$row3['name'].'</b> (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Utilisateur présent dans le service GestSup mais pas dans le service LDAP").'.</span></font>)<br />';
					$db->exec("DELETE FROM tusers_services WHERE user_id='$row[id]' AND service_id='$row2[service_id]'");
					$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Delete service association between user $row[login] and service $row3[name] \n"; file_put_contents($logfileurl, $logfile);
				}
			}
		}
		$query2->closecursor();
		
		//disable user in GS if not present in LDAP
		if (!in_array($row['ldap_guid'], $array_ldap_user)) {
			if($_GET['action']=='simul')
			{
				echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("Désactivation de l'utilisateur ").': <b>'.$row['login'].'</b> (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Utilisateur présent dans GestSup mais pas dans l'annuaire LDAP").'.</span></font>)<br />';
			} elseif($_GET['action']=='run')
			{
				echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("L'utilisateur ").': <b>'.$row['login'].'</b> '.T_("à été désactivé ").' (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Utilisateur présent dans GestSup mais pas dans l'annuaire LDAP").'.</span></font>)<br />';
				$db->exec("UPDATE tusers SET disable=1 WHERE id='$row[id]'");
				$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Disable user $row[login]  \n"; file_put_contents($logfileurl, $logfile);
			}
		}
	}
	$query->closecursor();
	
	//disable service in GS if not present in LDAP
	$query = $db->query("SELECT ldap_guid,name,id FROM `tservices` WHERE disable='0'"); //for each Gestsup service 
	while ($row=$query->fetch())	
	{
		$find=0;
		foreach($array_ldap_group as $ldap_group)
		{
			if ($row['ldap_guid']==$ldap_group) {$find=1;}
		}
		if ($find==0) {
			if($_GET['action']=='simul')
			{
				echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("Désactivation du service").': <b>'.$row['name'].'</b> (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Service non présent dans l'annuaire LDAP ou renommé en OLD").'.</span></font>)<br />';
			} elseif($_GET['action']=='run')
			{
				echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_("Service").': <b>'.$row['name'].'</b> '.T_("désactivée").' (<span style="font-size: x-small;">'.T_('Raison').': '.T_("Service non présent dans l'annuaire LDAP ou renommé en OLD").'.</span></font>)<br />';
				$db->exec("UPDATE tservices SET disable=1 WHERE id='$row[id]'");
				$logfile = file_get_contents($logfileurl); $logfile .= '['.date('Y-m-d H:i:s').'] '; $logfile .= "Disable service $row[name]  \n"; file_put_contents($logfileurl, $logfile);
			}
		}
	}
	$query->closecursor();
	
	//unbind LDAP server
	ldap_unbind($ldap);
}

echo'
	<br />
	<br />
	<br />
	<button onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;ldap=services&amp;action=simul"\' type="submit" class="btn btn-primary">
		<i class="icon-beaker bigger-120"></i>
		'.T_('Lancer une simulation').'
	</button>
	<button onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;ldap=services&amp;action=run"\' type="submit" class="btn btn-primary">
		<i class="icon-bolt bigger-120"></i>
		'.T_('Lancer la synchronisation').'
	</button>
	<button onclick=\'window.location.href="index.php?page=admin&subpage=user"\' type="submit" class="btn btn-primary btn-danger">
		<i class="icon-reply bigger-120"></i>
		'.T_('Retour').'
	</button>					
';


?>