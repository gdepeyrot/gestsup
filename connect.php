<?php
################################################################################
# @Name : connect.php
# @Description : database connection parameters
# @Call : 
# @parameters : 
# @Author : Flox
# @Create : 07/03/2007
# @Update : 05/01/2017
# @Version : 3.1.15
################################################################################

//database connection parameters
$host='localhost'; //SQL server name
$port='3306'; //SQL server port
$db_name='bsup'; //database name
$charset='utf8'; //database charset default utf8
$user='root'; //database user name
$password=''; //database password

//database connection
try {$db = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=$charset", "$user", "$password" , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));}
catch (Exception $e)
{die('Error : ' . $e->getMessage());}
?>