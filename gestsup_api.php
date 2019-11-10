<?php
################################################################################
# @Name : gestsup_api.php 
# @Desc : Display short ticket declaration interface to integer in other website (ex: intranet)
# @Author : Flox
# @Create : 29/10/2013
# @Update : 11/11/2015
# @Version : 3.1.0
################################################################################


############################## START EDITABLE PART #############################
$host='localhost'; //SQL server name
$db_name=''; //database name
$charset='utf8'; //database charset default utf8
$user='root'; //database user name
$password=''; //database password
############################## END EDITABLE PART #############################

//database connection
try {$db = new PDO	("mysql:host=$host;dbname=$db_name;charset=$charset", "$user", "$password" , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));}
catch (Exception $e)
{die('Error : ' . $e->getMessage());}
 
//initialize variables
if(!isset($_POST['send'])) $_POST['send']= '';

if ($_POST['send']) //database input
{
	$date=date('Y-m-d H:m:s');
	
	//escape special char in sql query 
	$_POST['description'] = $db->quote($_POST['description']);
	$_POST['title'] = $db->quote($_POST['title']);
	
	$db->exec("INSERT INTO tincidents (user,title,description,state,date_create,creator,criticality,techread) VALUES ('$_POST[user]',$_POST[title],$_POST[description],'5','$date','$_POST[user]','4','0')");
	
	//load parameters table
	$qparameters = $db->query("SELECT * FROM `tparameters`"); 
	$rparameters= $qparameters->fetch();
	$qparameters->closecursor();
	
	//find incident number  
	$query = $db->query("SELECT MAX(id) FROM tincidents");
	$row=$query->fetch();
	$db->closecursor();
	$number =$row[0];
	echo '
	<font color="green">
		La demande <b>#'.$number.'</b> à bien été prise en compte.<br />
	</font>
	Pour suivre vos demandes vous pouvez vous rendre sur la page <a target="_blank" href="'.$rparameters['server_url'].'">'.$rparameters['server_url'].'</a>
	';
}
else //display form
{
	echo '
	<form method="POST" action="" id="myform">
		<table border="0">
			<tr>
				<td><label for="user">Nom:</label></td>
				<td>
					<select name="user" />
						';
						$q = $db->query("SELECT * FROM `tusers` WHERE disable='0' ORDER BY lastname"); 
						while ($row=$q->fetch())
						{
							echo '<option value="'.$row['id'].'">'.$row['lastname'].' '.$row['firstname'].'</option>';
						}
						$q->closecursor();
						echo '
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="title">Titre:</label>
				</td>
				<td>
					<input name="title" type="text" size="30px" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<label for="description">Demande:</label>
				<br />
				<textarea name="description" cols="50" rows="10" ></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value=" Envoyer votre demande " id="send" name="send" />
				</td>
			</tr>
		</table>
	</form>';
}

//close database access
$db = null;
?>