<?php
################################################################################
# @Name : /core/ldap.php
# @Description : page to synchronize users from LDAP to GestSup
# @call : /admin/user.php
# @Author : Flox
# @Create : 15/10/2012
# @Update : 20/01/2018
# @Version : 3.1.29
################################################################################

//initialize variables
if(!isset($_POST['test_ldap'])) $_POST['test_ldap'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['subpage'])) $_GET['subpage'] = '';
if(!isset($_GET['ldaptest'])) $_GET['ldaptest'] = '';
if(!isset($_GET['ldap'])) $_GET['ldap'] = '';
if(!isset($cnt_ldap)) $cnt_ldap= 0;
if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $_SERVER['HTTP_ACCEPT_LANGUAGE']=0;

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
}

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

//LDAP connection parameters
$user=$rparameters['ldap_user']; 
$password=$rparameters['ldap_password']; 
$domain=$rparameters['ldap_domain'];
if($rparameters['ldap_port']==636) {$hostname='ldaps://'.$rparameters['ldap_server'];} else {$hostname=$rparameters['ldap_server'];}

//Generate DC Chain from domain parameter
$dcpart=explode(".",$domain);
$i=0;
while($i<count($dcpart)) {
	$dcgen="$dcgen,dc=$dcpart[$i]";
	$i++;
}
	
//LDAP URL for users emplacement
$ldap_url="$rparameters[ldap_url]$dcgen";

//display head title
if ($rparameters['ldap_type']==0) {$ldap_type='Active Directory';}
if ($rparameters['ldap_type']==1) {$ldap_type='OpenLDAP';}
if ($rparameters['ldap_type']==3) {$ldap_type='Samba4';}
if ($_GET['subpage']=='user')
{
	echo '
	<div class="page-header position-relative">
		<h1>
			<i class="icon-refresh"></i>   
			'.T_('Synchronisation').': '.$ldap_type.' > GestSup 
		</h1>
	</div>';
}
if(($_GET['action']=='simul') || ($_GET['action']=='run') || ($_GET['ldaptest']==1) || ($_GET['ldap']==1) || ($ldap_auth==1))
{
	//LDAP connect
	$ldap = ldap_connect($hostname,$rparameters['ldap_port']) or die("Impossible de se connecter au serveur LDAP.");
	ldap_set_option($ldap, LDAP_OPT_NETWORK_TIMEOUT, 1);
	if ($rparameters['ldap_type']==1) {ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);}
	//check LDAP type for bind
	if ($rparameters['ldap_type']==0 || $rparameters['ldap_type']==3) $ldapbind = ldap_bind($ldap, "$user@$domain", $password); else $ldapbind = ldap_bind($ldap, "cn=$user$dcgen", $password);	
	//check ldap authentication
	if ($ldapbind) {$ldap_connection='<i title="'.T_('Connecteur opérationnel').'" class="icon-ok-sign icon-large green"></i> '.T_('Connecteur opérationnel').'.';} else {$ldap_connection='<i title="'.T_('Le connecteur ne fonctionne pas vérifier vos paramètres').'" class="icon-remove-sign icon-large red"></i> '.T_('Le connecteur ne fonctionne pas vérifier vos paramètres').'';}
	if ($ldapbind) 
	{
		if(($_GET['action']=='simul') || ($_GET['action']=='run')) 
		{
				$list_dn = preg_split("/;/",$rparameters['ldap_url']);
				$data = array();
				$data_temp = array();
				foreach ($list_dn as $value) {
					//$ldap_url="$value$dcgen";
					$ldap_url=utf8_decode("$value$dcgen");
					//change query filter for OpenLDAP or AD
					if ($rparameters['ldap_type']==0 || $rparameters['ldap_type']==3) {$filter="(&(objectClass=user)(objectCategory=person)(cn=*))";} else {$filter="(uid=*)";}	
					$query = ldap_search($ldap, $ldap_url, $filter);
					if($rparameters['debug']==1){
						echo "<u>DEBUG:</u><br />query ldap_search($ldap, $ldap_url, $filter)<br /><br />";
					}
					//put all data to $data
					$data_temp = @ldap_get_entries($ldap, $query);
					$data = array_merge($data, $data_temp);
					//count LDAP number of users
					$cnt_ldap += @ldap_count_entries($ldap, $query);
				}
				//count GESTSUP number of users
				$q=$db->query("SELECT count(*) FROM tusers WHERE disable='0'"); 
				$cnt_gestsup=$q->fetch();
				$q->closecursor();
				
				echo '<i class="icon-book green"></i> <b><u>'.T_('Vérification des Annuaires').'</u></b><br />';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;'.T_('Nombre d\'utilisateurs trouvés dans l\'annuaire').' '.$ldap_type.': '.$cnt_ldap.'<br />';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;'.T_('Nombre d\'utilisateurs actif trouvés dans GestSup').': '.$cnt_gestsup[0].'<br /><br />';
				echo '<i class="icon-edit-sign red"></i> <b><u>'.T_('Modifications à apporter dans GestSup').':</u></b><br /><br />';
				
				//Initialize counter
				$cnt_maj=0;
				$cnt_create=0;
				$cnt_disable=0;
				$cnt_enable=0;
				
				//display all data for debug
				//print_r($data);
				
				//for each LDAP user 
				for ($i=0; $i < $cnt_ldap; $i++) 
				{
					//Initialize variable for empty data
					if(!isset($data[$i]['samaccountname'][0])) $data[$i]['samaccountname'][0] = '';
					if(!isset($data[$i]['useraccountcontrol'][0])) $data[$i]['useraccountcontrol'][0] = '';
					if(!isset($data[$i]['givenname'][0])) $data[$i]['givenname'][0] = '';
					if(!isset($data[$i]['sn'][0])) $data[$i]['sn'][0] = '';
					if(!isset($data[$i]['telephonenumber'][0])) $data[$i]['telephonenumber'][0] = '';
					if(!isset($data[$i]['streetaddress'][0])) $data[$i]['streetaddress'][0] = '';
					if(!isset($data[$i]['postalcode'][0])) $data[$i]['postalcode'][0] = '';
					if(!isset($data[$i]['l'][0])) $data[$i]['l'][0] = '';
					if(!isset($data[$i]['mail'][0])) $data[$i]['mail'][0] = '';
					if(!isset($data[$i]['company'][0])) $data[$i]['company'][0] = '';
					if(!isset($data[$i]['facsimiletelephonenumber'][0])) $data[$i]['facsimiletelephonenumber'][0] = '';
					if(!isset($data[$i]['userAccountControl'][0])) $data[$i]['userAccountControl'][0] = '';
					if(!isset($data[$i]['title'][0])) $data[$i]['title'][0] = '';
					if(!isset($data[$i]['department'][0])) $data[$i]['department'][0] = '';					
					if(!isset($data[$i]['uid'][0])) $data[$i]['uid'][0] = '';
					//if(!isset($data[$i]['manager'][0])) $data[$i]['manager'][0] = '';
					
					//get user data from Windows AD or Samba4 or OpenLDAP & transform in UTF-8
					if ($rparameters['ldap_type']==0 || $rparameters['ldap_type']==3) $samaccountname=utf8_encode($data[$i]['samaccountname'][0]);  else $samaccountname=utf8_encode($data[$i]['uid'][0]);
					
					//no UTF8 decoding in Samba4 LDAP
					if($rparameters['ldap_type']==3)
					{
						$UAC=$data[$i]['useraccountcontrol'][0];
						$givenname=$data[$i]['givenname'][0];
						$sn=$data[$i]['sn'][0];
						$mail=$data[$i]['mail'][0];
						$telephonenumber=$data[$i]['telephonenumber'][0];  
						$streetaddress=$data[$i]['streetaddress'][0];  
						$postalcode=$data[$i]['postalcode'][0]; 
						$l=$data[$i]['l'][0]; 
						$company=$data[$i]['company'][0]; 
						$fax=$data[$i]['facsimiletelephonenumber'][0]; 
						$title=$data[$i]['title'][0]; 
						$department=$data[$i]['department'][0]; 
						//$manager=($data[$i]['manager'][0]);
					} else {
						$UAC=$data[$i]['useraccountcontrol'][0];
						$givenname=utf8_encode($data[$i]['givenname'][0]);
						$sn=utf8_encode($data[$i]['sn'][0]);
						$mail=utf8_encode($data[$i]['mail'][0]);
						$telephonenumber=$data[$i]['telephonenumber'][0];  
						$streetaddress=utf8_encode($data[$i]['streetaddress'][0]);  
						$postalcode=$data[$i]['postalcode'][0]; 
						$l=utf8_encode($data[$i]['l'][0]); 
						$company=utf8_encode($data[$i]['company'][0]); 
						$fax=$data[$i]['facsimiletelephonenumber'][0]; 
						$title=utf8_encode($data[$i]['title'][0]); 
						$department=utf8_encode($data[$i]['department'][0]); 
						//$manager=($data[$i]['manager'][0]);
					}
					
					
					//special characters treatment
					$title=str_replace ('','', $title); //special char SPA treatment
					
					if($rparameters['debug']==1) echo "- LDAP_SamAccountName=$samaccountname LDAP_UAC=$UAC LDAP_company=$company ";
					
					////check if account not exist in GestSup user database
					//1st Check login
					$find_login=0;
					$q = $db->query("SELECT * FROM `tusers`");
					while ($row=$q->fetch())
					{
						if($samaccountname==$row['login']) 
						{
							//get user data from GS db
							$find_login=$row['login'];
							$g_firstname=$row['firstname'];
							$g_lastname=$row['lastname'];
							$g_disable=$row['disable'];
							$g_mail=$row['mail'];
							$g_telephonenumber=$row['phone'];
							$g_streetaddress=$row['address1'];
							$g_postalcode=$row['zip'];
							$g_l=$row['city'];
							$g_company=$row['company'];
							$g_fax=$row['fax'];
							$g_title=$row['function'];
							//$g_service= $row['service'];
						}
					}
					$q->closecursor();
					if($rparameters['debug']==1) echo "<b>|</b> GS_login=$find_login GS_company=$g_company<br>";
					if ($find_login!='')
					{	
						////update exist account
						if (($UAC=='66050' || $UAC=='514') && ($g_disable==0)) 
						{
							//disable GestSup account
							$cnt_disable=$cnt_disable+1;
							if($_GET['action']=='run') {
								echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_('Utilisateur').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.'), '.T_('désactivé').'.</font><br />';
								$db->exec("UPDATE tusers SET disable='1' WHERE login='$find_login'");		
							} else {
								echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_('Désactivation de l\'utilisateur').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.'). <span style="font-size: x-small;">'.T_('Raison').': '.T_('Utilisateur désactivé dans l\'annuaire LDAP').'.</span></font><br />';
							}
						} else {
							//enable gestsup account if LDAP user is re-activate
							if(($g_disable=='1') && ($UAC!='66050' && $UAC!='514' && $UAC!='66082' && $UAC!='546')) // 546 et 66082 special detect for invité
							{
								$cnt_enable=$cnt_enable+1;
								if($_GET['action']=='run') {
								echo '<i class="icon-ok-sign icon-large green"></i><font color="green"> '.T_('Utilisateur').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.'), '.T_('activé').'.</font><br />';
								$db->exec("UPDATE tusers SET disable='0' WHERE login='$samaccountname'");
								} else {
									echo '<i class="icon-ok-sign icon-large green"></i><font color="green"> '.T_('Activation de l\'utilisateur').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.').</font><br />';
								}
							//update GestSup account if LDAP have informations
							} else if($UAC=='66050' || $UAC=='514' || $UAC=='512' || $UAC=='66048'){
								//compare data 
								$update=0;
								if($g_firstname!=$givenname) 
								{
									$update=T_('du prénom').' "'.$givenname.'"';
									if($_GET['action']=='run') {
										$givenname = $db->quote($givenname); 
										$db->exec("UPDATE tusers SET firstname=$givenname WHERE login='$samaccountname'");
									}
								}
								if($g_lastname!=$sn) 
								{
									$update=T_('du nom').' "'.$sn.'"';
									if($_GET['action']=='run') {
										$sn = $db->quote($sn); 
										$db->exec("UPDATE tusers SET lastname=$sn WHERE login='$samaccountname'");				
									}
								}
								if($g_mail!=$mail) 
								{
									$update=T_('de l\'adresse mail').' "'.$mail.'"';
									$mail= $db->quote($mail);
									if($_GET['action']=='run') {
										$db->exec("UPDATE tusers SET mail=$mail WHERE login='$samaccountname'");
									}
								}
								if(($g_telephonenumber=='') && ($telephonenumber!='')) //special case for no tel number in AD
								{
									$update=T_('du numéro de téléphone').' "'.$telephonenumber.'" ';
									if($_GET['action']=='run') {
										$db->exec("UPDATE tusers SET phone='$telephonenumber' WHERE login='$samaccountname'");
									}
								}
								if($g_streetaddress!=$streetaddress) 
								{
									$update=T_('de l\'adresse').' "'.$streetaddress.'" ';
									if($_GET['action']=='run') {
									$streetaddress = $db->quote($streetaddress);
										$db->exec("UPDATE tusers SET address1=$streetaddress WHERE login='$samaccountname'");
									}
								}
								if($g_postalcode!=$postalcode) 
								{
									$update=T_('du code postal').' "'.$postalcode.'" ';
									if($_GET['action']=='run') {
										$db->exec("UPDATE tusers SET zip='$postalcode' WHERE login='$samaccountname'");
									}
								}
								if($g_l!=$l) 
								{
									$update=T_('de la ville').' "'.$l.'" ';
									if($_GET['action']=='run') {
									$l = $db->quote($l);
										$db->exec("UPDATE tusers SET city=$l WHERE login='$samaccountname'");
									}
								}
								if($g_fax!=$fax) 
								{
									$update=T_('du FAX').' "'.$fax.'"';
									if($_GET['action']=='run') {
										$db->exec("UPDATE tusers SET fax='$fax' WHERE login='$samaccountname'");
									}
								}
								if($g_title!=$title) 
								{
									$update=T_('de la fonction').' "'.$title.'"';
									if($_GET['action']=='run') {
										$title = $db->quote($title);
										$db->exec("UPDATE tusers SET function=$title WHERE login='$samaccountname'");
									}
								}
								
								//get gestsup company name
								$q=$db->query("SELECT name FROM tcompany WHERE id='$g_company'"); 
								$g_company_name=$q->fetch();
								$q->closecursor();
								
								//update company name in lowercase to compare
								$company_lower=strtolower($company);
								$g_company_name_lower=strtolower($g_company_name[0]);
								
								if(($company_lower!=$g_company_name_lower) && $company!='' ) 
								{
									$update=T_('de la Société').' "'.$company.'" ';
									if($_GET['action']=='run') 
									{
										//find company in GestSup database
										$q = $db->query("SELECT * FROM `tcompany`");
										while ($row=$q->fetch())
										{
											//if ($company==$row['name']) $find_company=$row['id']; else $find_company='';
											 if(strcasecmp($company, $row['name']) == 0)
											 {
												$find_company=$row['id'];
												break;
											 } 
											 else 
											 {
												$find_company='';
											 }
										}
										//if company is find update company id else create company in gestsup
										if ($find_company!='')
										{
											$db->exec("UPDATE tusers SET company='$find_company' WHERE login='$samaccountname'");
										} 
										elseif ($company!='')
										{
											$company = $db->quote($company); 
											$db->exec("INSERT INTO tcompany (name) VALUES ($company)");
											echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_('Société').' '.$company.' '.T_('crée').'.</font><br />';
											//get GestSup company table
											$q = $db->query("SELECT * FROM `tcompany`");
											while ($row=$q->fetch())
											{
												if ($company==$row['name']) $find_company=$row['id']; 
											}
											// if company is find update company id else create company in gestsup
											if ($find_company!='')
											{
												$db->exec("UPDATE tusers SET company='$find_company' WHERE login='$samaccountname'");
											}
										}											
									} 
									else
									{
										//get company service table
										$q = $db->query("SELECT * FROM `tcompany`");
										while ($row=$q->fetch())
										{
											if ($company==$row['name']) $find_company=$row['id']; else $find_company='';
										}
										// if company is find update company id else create company in gestsup
										if ($find_company=='')	echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_('Création de la Société').' '.$company.'.</font><br />';
									}
								}
								// ************************************START Synchronize service******************************************
								//check if LDAP service is not empty
								if($department)
								{
									//convert quote to SQL queries
									$department=$db->quote($department);
									//check is LDAP service exist in GS db
									$query = $db->query("SELECT id FROM `tservices` WHERE name=$department");
									$row=$query->fetch();
									$query->closeCursor(); 
									if (!$row) {//LDAP service not exist in GS db
										//create service in GS DB
										if($_GET['action']=='run') 
										{
											$db->exec("INSERT INTO tservices (name) VALUES ($department)");
											echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_('Service').' '.$department.' '.T_('crée').'.</font><br />';
										} else {
											echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_('Création du service').' '.$department.'.</font><br />';
										}
									} else {//LDAP service already exist in GS DB
										//check if exist an association with current GS user and service.
										$query2 = $db->query("SELECT id,user_id FROM `tusers_services` WHERE user_id IN (SELECT id FROM tusers WHERE login='$samaccountname') AND service_id='$row[id]'");
										$row2=$query2->fetch();
										$query2->closeCursor();
										if(!$row2)//if no association found create it
										{
											$update=T_('du Service').' "'.$department.'" ';
											//create association
											if($_GET['action']=='run') 
											{
												//delete old association
												$db->exec("DELETE FROM tusers_services WHERE user_id IN (SELECT id FROM tusers WHERE login='$samaccountname')");
												//create new association
												$db->exec("INSERT INTO tusers_services (user_id,service_id) VALUES ((SELECT MAX(id) FROM tusers WHERE login='$samaccountname'),'$row[id]')");
											}
										} 
									}
								}
								// ************************************END Synchronize service******************************************
								if($update)
								{
									$cnt_maj=$cnt_maj+1;
									if($_GET['action']=='run') {
										echo '<i class="icon-refresh orange"></i><font color="orange"> '.T_('Utilisateur').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.'), '.T_('mis à jour').'.</font><br />';
									} else {
										echo '<i class="icon-refresh orange"></i><font color="orange"> '.T_('Mise à jour').' '.$update.' '.T_('pour').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.').</font><br />';
									}
								}
							}
						}
					} elseif($samaccountname!='Invité' && $samaccountname!='krbtgt' && $samaccountname!='') {
						//create GestSup account
							//escape special char for SQL query
							$samaccountname = $db->quote($samaccountname); 
							$givenname = $db->quote($givenname); 
							$sn = $db->quote($sn); 
							$streetaddress = $db->quote($streetaddress); 
							$company= $db->quote($company); 
							$title= $db->quote($title); 
							$l= $db->quote($l);
							$mail= $db->quote($mail);
							$telephonenumber= $db->quote($telephonenumber);
							$postalcode= $db->quote($postalcode);
							$fax= $db->quote($fax);
							$cnt_create=$cnt_create+1;
							//generate default pwd and salt
							$salt = substr(md5(uniqid(rand(), true)), 0, 5); //generate a random key as salt
							$pwd=substr(str_shuffle(strtolower(sha1(rand() . time() . $salt))),0, 50);
							$pwd=md5($salt . md5($pwd)); 
							
						if($_GET['action']=='run') {
							echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_('Utilisateur').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.') '.T_('à été crée').'.</font><br />';
							$db->exec("INSERT INTO tusers (login,password,salt,firstname,lastname,profile,mail,phone,address1,zip,city,company,fax) VALUES ($samaccountname,'$pwd','$salt',$givenname,$sn,'2',$mail,$telephonenumber,$streetaddress,$postalcode,$l,$company,$fax)");
						} else {
							echo '<i class="icon-plus-sign green bigger-130"></i><font color="green"> '.T_('Création de l\'utilisateur').' <b>'.$givenname.' '.$sn.'</b> ('.$samaccountname.').</font><br />';
						}
					}
				}
				//for each Gestsup USER (find user not present in LDAP for disable in GestSup)
				if ($rparameters['ldap_disable_user']==1)
				{
					$q = $db->query("SELECT * FROM `tusers`");
					while ($row=$q->fetch())	
					{
						$find2_login='';
						for ($i=0; $i < $cnt_ldap; $i++) 
						{
							if ($rparameters['ldap_type']==0 || $rparameters['ldap_type']==3) {$samaccountname=utf8_encode($data[$i]['samaccountname'][0]);} else {$samaccountname=utf8_encode($data[$i]['uid'][0]);}
							if ($samaccountname==$row['login']) $find2_login=$row['login'];
						}
						if (($find2_login=='') && ($row['disable']=='0') && ($row['login']!='') && ($row['login']!=' ') && ($row['login']!='admin'))
						{
							$cnt_disable=$cnt_disable+1;
							if($_GET['action']=='run')
							{
								echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_('Utilisateur').' <b>'.$row['firstname'].' '.$row['lastname'].'</b> ('.$row['login'].'), '.T_('désactivé').'.</font><br />';
								$rowlogin= $db->quote($row['login']);
								$db->exec("UPDATE tusers SET disable='1' WHERE login=$rowlogin");
							} else {
								echo '<i class="icon-remove-sign icon-large red"></i><font color="red"> '.T_('Désactivation de l\'utilisateur').' <b>'.$row['firstname'].' '.$row['lastname'].'</b> ('.$row['login'].'). <span style="font-size: x-small;">'.T_('Raison').': '.T_('Utilisateur non présent dans l\'annuaire LDAP').'.</span></font><br />';
							}
						}
					}
				}
				
				if (($cnt_create=='0') && ($cnt_disable=='0') && ($cnt_maj=='0') && ($cnt_enable=='0')) echo '&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-ok-sign icon-large green"></i><font color="green"> '.T_('Aucune modification à apporter, les annuaires sont à jour').'.</font><br />';
				echo'
				<br />
				&nbsp;&nbsp;&nbsp;&nbsp;'.T_('Nombre de d\'utilisateurs à créer dans GestSup').': '.$cnt_create.' <br />
				&nbsp;&nbsp;&nbsp;&nbsp;'.T_('Nombre de d\'utilisateurs à mettre à jour dans GestSup').': '.$cnt_maj.' <br />
				&nbsp;&nbsp;&nbsp;&nbsp;'.T_('Nombre de d\'utilisateurs à désactiver dans GestSup').': '.$cnt_disable.' <br />
				&nbsp;&nbsp;&nbsp;&nbsp;'.T_('Nombre de d\'utilisateurs à activer dans GestSup').': '.$cnt_enable.' <br />
				<br />
				<i class="icon-info-sign blue"></i> <b><u>'.T_('Informations de Synchronisation').':</u></b><br />
				&nbsp;&nbsp;&nbsp;&nbsp;'.T_('La jointure inter-annuaires est réalisée sur le login, les comptes existant dans GestSup qui possèdent un login doivent être existant dans l\'annuaire LDAP').'.<br />
				';
		}
		if(($_GET['action']=='simul') || ($_GET['action']=='run') || ($_GET['ldap']=='1')) 
		{
			echo'
				<br />
				<button onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;ldap=1&amp;action=simul"\' type="submit" class="btn btn-primary">
					<i class="icon-beaker bigger-120"></i>
					'.T_('Lancer une simulation').'
				</button>
				<button onclick=\'window.location.href="index.php?page=admin&amp;subpage=user&amp;ldap=1&amp;action=run"\' type="submit" class="btn btn-primary">
					<i class="icon-bolt bigger-120"></i>
					'.T_('Lancer la synchronisation').'
				</button>
				<button onclick=\'window.location.href="index.php?page=admin&subpage=user"\' type="submit" class="btn btn-primary btn-danger">
					<i class="icon-reply bigger-120"></i>
					'.T_('Retour').'
				</button>					
			';
		}
		//unbind LDAP server
		ldap_unbind($ldap);
	} else if($_GET['subpage']=='user')
	{
		echo '
		<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">
					<i class="icon-remove"></i>
				</button>
				<strong>
					<i class="icon-remove"></i>
					'.T_('Erreur').'
				</strong>
				'.T_('La connection LDAP n\'est pas disponible, vérifier si votre serveur LDAP est joignable ou vérifier vos paramètres de connection').'.
				<br>
		</div>';
	}
} 
?>