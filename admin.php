<?php
################################################################################
# @Name : admin.php
# @Description : admin parent page check right to admin part
# @Call : /index.php
# @Parameters : 
# @Author : Flox
# @Create : 12/01/2011
# @Update : 26/04/2017
# @Version : 3.1.20
################################################################################

// initialize variables 
if(!isset($_GET['subpage'])) $_GET['subpage'] = '';
if(!isset($_GET['profileid'])) $_GET['profileid'] = '';

//default settings
if ($_GET['subpage']=='') $_GET['subpage']='user';
if ($_GET['subpage']=='user')
if ($_GET['profileid']=='') if ($_GET['subpage']=='user') $_GET['profileid'] = '%';
if ($_GET['subpage']=='profile' && $_GET['profileid']=='') $_GET['profileid']=0;

//check rights for admin page
if ($rright['admin']!=0){include ('./admin/'.$_GET['subpage'].'.php');}
elseif ($rright['admin_groups']!=0 && $_GET['subpage']=='group'){include ('./admin/'.$_GET['subpage'].'.php');}
elseif ($rright['admin_lists']!=0 && $_GET['subpage']=='list') {include ('./admin/'.$_GET['subpage'].'.php');}
elseif ($rright['admin_user_view']!=0 && $_GET['subpage']=='user') {include ('./admin/'.$_GET['subpage'].'.php');} //case to allow superuser to delete personal views
else {echo '<div class="alert alert-danger"><strong><i class="icon-remove"></i>'.T_('Erreur').':</strong> '.T_('Vous n\'avez pas accès au menu administration, contacter votre administrateur').'.<br></div>';}
?>