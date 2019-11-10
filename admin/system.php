<?php
################################################################################
# @Name : system.php
# @Description :  admin system
# @Call : admin.php
# @Parameters : 
# @Author : Flox
# @Create : 12/01/2011
# @Update : 17/10/2017
# @Version : 3.1.27
################################################################################
?>
<div class="page-header position-relative">
	<h1>
		<i class="icon-desktop"></i>  <?php echo T_('État du système'); ?>
	</h1>
</div>
<?php include('./system.php'); ?>
<hr />
<center>
	<button onclick='window.open("./admin/phpinfos.php?key=<?php echo $rparameters['server_private_key']; ?>")' class="btn btn-primary">
		<i class="icon-cogs bigger-140"></i>
		 <?php echo T_('Tous les paramètres PHP'); ?>
	</button>
</center>