<?php
################################################################################################
# @Name : asset_stock.php 
# @Description : page to add multiple assets in one time in your stock based on serials numbers
# @Call : /dashboard.php
# @Author : Flox
# @Version : 3.1.26
# @Create : 18/12/2015
# @Update : 14/09/2017
################################################################################################

//initialize variables 
if(!isset($_POST['type'])) $_POST['type']= ''; 
if(!isset($_POST['save'])) $_POST['save']= ''; 
if(!isset($_POST['model'])) $_POST['model']= ''; 
if(!isset($_POST['manufacturer'])) $_POST['manufacturer']= ''; 
if(!isset($globalrow['model'])) $globalrow['model']= ''; 
if(!isset($globalrow['manufacturer'])) $globalrow['manufacturer']= ''; 
if(!isset($globalrow['type'])) $globalrow['type']= ''; 

//insert assets in database
if($_POST['save'])
{
	//check if warranty is present on asset model
	$query=$db->query("SELECT * FROM tassets_model WHERE id='$_POST[model]'");
	$row_model=$query->fetch();
	$query->closeCursor();
	if($row_model['warranty']!=0)
	{
		//calculate end warranty date
		$date_stock=date("Y");
		$year_end_warranty=$date_stock+$row_model['warranty'];
		$date_end_warranty=$year_end_warranty.'-'.date("m").'-'.date("d");
	} else {$date_end_warranty='0000-00-00';}
	
	//count serials number of serials text area
	$serials=explode("\r\n", $_POST['serials']);
	$nb=count($serials);
	
	//special case for user who want add multiple asset for company
	if($rright['asset_list_company_only']){$user_id=$_SESSION['user_id'];} else {$user_id=0;}
	
	for ($i=0; $i<$nb; $i++) {
		//find internal number of new asset
		$query=$db->query("SELECT MAX(CONVERT(sn_internal, SIGNED INTEGER)) FROM tassets");
		$row_sn_internal=$query->fetch();
		$query->closeCursor(); 
		$row_sn_internal=$row_sn_internal[0]+1;
		
		//get current date
		$date=date('Y-m-d');
		$db->exec("
		INSERT INTO tassets (
		sn_internal,
		sn_manufacturer,
		sn_indent,
		user,
		type,
		manufacturer,
		model,
		state,
		date_stock,
		date_end_warranty,
		disable
		) VALUES (
		'$row_sn_internal',
		'$serials[$i]',
		'$_POST[sn_indent]',
		'$user_id',
		'$_POST[type]',
		'$_POST[manufacturer]',
		'$_POST[model]',
		'1',
		'$date',
		'$date_end_warranty',
		'0'
		)");
	}
}
?>
<div id="row">
	<div class="col-xs-12">
		<div class="widget-box">
			<form class="form-horizontal" name="myform" id="myform" enctype="multipart/form-data" method="post" action="" onsubmit="loadVal();" >
				<div class="widget-header">
					<h4>
						<i class="icon-desktop"></i>
						<?php echo T_('Entrées en stock'); ?>
					</h4>
					<span class="widget-toolbar">

					</span>
				</div>
				<div class="widget-body">
					<div class="widget-main">
						<div class="row">
							<div class="col-sm-6">
								<!-- START type model part -->
								<div class="form-group ">
									<label class="col-sm-4 control-label no-padding-right" for="type">
										<?php echo T_('Type'); ?>:
									</label>
									<div class="col-sm-8">
										<select id="type" name="type" onchange="submit();" >
										<?php
											$query= $db->query("SELECT * FROM `tassets_type` ORDER BY name ");
											while ($row = $query->fetch()) 
											{
												if ($_POST['type'])
												{
													if ($_POST['type']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
												else
												{
													if ($globalrow['type']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
											}
											$query->closeCursor();
											if ($globalrow['type']==0 && $_POST['type']==0) echo "<option value=\"\" selected></option>";
										?>
										</select>
										<select id="manufacturer" name="manufacturer" onchange="submit();" >
										<?php
											if ($_POST['type'])
											{$query= $db->query("SELECT DISTINCT tassets_manufacturer.id, tassets_manufacturer.name FROM `tassets_manufacturer`,tassets_model WHERE tassets_manufacturer.id=tassets_model.manufacturer AND tassets_model.type='$_POST[type]' ORDER BY name ASC");}
											else
											{$query= $db->query("SELECT * FROM `tassets_manufacturer` WHERE id LIKE '$globalrow[manufacturer]' ORDER BY name ASC");}
											
											while ($row = $query->fetch()) 
											{
												if ($_POST['manufacturer'])
												{
													if ($_POST['manufacturer']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
												else
												{
													if ($globalrow['manufacturer']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
											} 
											$query->closeCursor();
											if ($globalrow['manufacturer']==0 && $_POST['manufacturer']==0) echo "<option value=\"\" selected></option>";
											?>
										</select>
										<select  id="model" name="model" onchange="submit();" >
										<?php
											if ($_POST['type'])
											{$query= $db->query("SELECT * FROM `tassets_model` WHERE type LIKE '$_POST[type]' order by name ASC");}
											else
											{$query= $db->query("SELECT * FROM `tassets_model` WHERE type LIKE '$globalrow[type]' order by name ASC");}
											
											while ($row = $query->fetch()) 
											{
												if ($_POST['model'])
												{
													if ($_POST['model']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
												else
												{
													if ($globalrow['model']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>"; else echo "<option value=\"$row[id]\">$row[name]</option>";
												}
											} 
											$query->closeCursor();
											if ($globalrow['model']==0 && $_POST['model']==0) echo "<option value=\"\" selected></option>";
										?>
										</select>
									</div>
								</div>
								<!-- END type model part -->
								
								<!-- START sn_indent part -->
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right" for="sn_indent"><?php echo T_('N° de commande'); ?>:</label>
									<div class="col-sm-6">
										<input  name="sn_indent" id="sn_indent" type="text" size="15"  value=""  />
									</div>
								</div>
								<!-- END sn_indent part -->
								
								<!-- START serials part -->
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right" for="serials"><?php echo T_('Numéros de séries'); ?>:</label>
									<div class="col-sm-6">
										<textarea  rows="30" cols="50" name="serials" id="serials"  /></textarea>
									</div>
								</div>
								<!-- END serials part -->
								
							</div>
						</div> <!-- div row -->
						<div class="row" align="center">
							<div class="clearfix form-actions" >	
								<button name="save" id="save" value="save" type="submit" class="btn btn-sm btn-success">
									<i class="icon-save icon-on-right bigger-110"></i> 
									&nbsp;<?php echo T_('Enregistrer'); ?>
								</button>
								&nbsp;
								<button name="cancel" id="cancel" value="cancel" type="submit" class="btn btn-sm btn-danger">
									<i class="icon-remove icon-on-right bigger-110"></i> 
									&nbsp;<?php echo T_('Annuler'); ?>
								</button>
							</div> <!-- div form-actions -->
						</div> <!-- div row -->
					</div> <!-- div widget main -->
				</div> <!-- div widget body -->
			</form>
		</div> <!-- div end sm -->
	</div> <!-- div end x12 -->
</div> <!-- div end row -->

<?php include ('./wysiwyg.php'); ?>

<!-- date picker script -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>
	<script src="template/assets/js/date-time/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
jQuery(function($) {
    
	<?php
		echo '
			$.datepicker.setDefaults( $.datepicker.regional["fr"] );
			jQuery(function($){
			   $.datepicker.regional["fr"] = {
				  closeText: "Fermer",
				  prevText: "'.T_('<Préc').'",
				  nextText: "'.T_('Suiv>').'",
				  currentText: "Courant",
				  monthNames: ["'.T_('Janvier').'","'.T_('Février').'","'.T_('Mars').'","'.T_('Avril').'","'.T_('Mai').'","'.T_('Juin').'","'.T_('Juillet').'","'.T_('Août').'","'.T_('Septembre').'","'.T_('Octobre').'","'.T_('Novembre').'","'.T_('Décembre').'"],
				  monthNamesShort: ["Jan","Fév","Mar","Avr","Mai","Jun",
				  "Jul","Aoû","Sep","Oct","Nov","Déc"],
				  dayNames: ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"],
				  dayNamesShort: ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],
				  dayNamesMin: ["'.T_('Di').'","'.T_('Lu').'","'.T_('Ma').'","'.T_('Me').'","'.T_('Je').'","'.T_('Ve').'","'.T_('Sa').'"],
				  weekHeader: "Sm",
				  dateFormat: "dd/mm/yy",
				  timeFormat:  "hh:mm:ss",
				  firstDay: 1,
				  isRTL: false,
				  showMonthAfterYear: false,
				  yearSuffix: ""};
			   $.datepicker.setDefaults($.datepicker.regional["fr"]);
				});
		';
	?>
		$( "#date_install" ).datepicker({ 
			dateFormat: 'yy-mm-dd'
		});
		$( "#date_stock" ).datepicker({ 
			dateFormat: 'yy-mm-dd'
		});
		$( "#date_recycle" ).datepicker({ 
			dateFormat: 'yy-mm-dd'
		});
		$( "#date_standbye" ).datepicker({ 
			dateFormat: 'dd/mm/yy'
		});
	});		
</script>		