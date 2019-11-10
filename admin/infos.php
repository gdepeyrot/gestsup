<?php
################################################################################
# @Name : infos.php
# @Description :  admin infos
# @Call : admin.php
# @Parameters : 
# @Author : Flox
# @Create : 12/01/2011
# @Update : 13/10/2017
# @Version : 3.1.27
################################################################################

//generate name of current version
$dedicated=substr_count($rparameters['version'], '.'); // check dedicated version
if($dedicated==2) //case current branch
{
	$vactuname=explode('.',$rparameters['version']);
	if($vactuname[2]==0) $vactuname=''; else $vactuname="($vactuname[0].$vactuname[1] patch $vactuname[2])";
} elseif($dedicated==3) { //case dedicated branch
	$vactuname=explode('.',$rparameters['version']);
	if($vactuname[2]==0) $vactuname=''; else $vactuname="($vactuname[0].$vactuname[1].$vactuname[2] patch $vactuname[3])";
} else {
	$vactuname=$rparameters['version'];
}

?>
<div class="page-header position-relative">
	<h1>
		<i class="icon-info-sign"></i>  <?php echo T_('Informations sur GestSup'); ?>
	</h1>
</div>
<div class="profile-user-info profile-user-info-striped">
	<div class="profile-info-row">
		<div class="profile-info-name"> <?php echo T_('Version'); ?>: </div>
		<div class="profile-info-value">
			<span id="username"><a href="./index.php?page=changelog"><?php echo ''.$rparameters['version'].' <span style="font-size: x-small;">'.$vactuname.'</span>';?></a></span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name"> <?php echo T_('Licence'); ?>: </div>

		<div class="profile-info-value">
			<span id="username"><a target="_blank" href="https://fr.wikipedia.org/wiki/Licence_publique_g%C3%A9n%C3%A9rale_GNU">GPL v3</a></span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name"> <?php echo T_('Site Officiel'); ?>: </div>

		<div class="profile-info-value">
			<span id="username"><a target="_blank" href="https://gestsup.fr">GestSup.fr</a></span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name"> <?php echo T_('Contact'); ?>: </div>

		<div class="profile-info-value">
			<span id="username"><a target="_blank" href="https://gestsup.fr/index.php?page=contact"><?php echo T_('Mail'); ?></a></span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name"> <?php echo T_('Communauté'); ?>: </div>

		<div class="profile-info-value">
			<span id="username"><a target="_blank" href="https://gestsup.fr/index.php?page=forum"><?php echo T_('Forum'); ?></a> (<?php echo T_('Pour toutes vos questions d\'installation, de bugs, de mises à jour'); ?>.)</span>
		</div>
	</div>
</div>