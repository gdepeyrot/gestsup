<?php
################################################################################
# @Name : wysiwyg.php
# @Description : text editor
# @Call : /ticket.php
# @Parameters : 
# @Author : Flox
# @Create : 06/03/2013
# @Update : 01/02/2017
# @Version : 3.1.17
################################################################################
?>

<!-- basic scripts -->
		<!--[if !IE]> -->
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='./template/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<!-- <![endif]-->
		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='./template/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		
		<script src="./template/assets/js/bootstrap-wysiwyg.min.js"></script>
		<!-- ace scripts -->
		<script src="./template/assets/js/ace-elements.min.js"></script>
		
		<script type="text/javascript">
			//load text from editor to input value
			function loadVal(){
				<?php
				if ($rright['ticket_description']!=0 || $_GET['action']=='new')
				{
					echo '
					text = $("#editor").html();
					document.myform.text.value = text;
					';
				}
				if ($rright['ticket_thread_add']!=0)
				{
					echo '
					text2 = $("#editor2").html();
					document.myform.text2.value = text2;
					';
				}
				?>
			}
			
			jQuery(function($) {
				$('#editor').ace_wysiwyg({
					toolbar:
					[
						<?php
							//display a light font function if mobile is detected
							if($mobile==0)
							{
								echo '
									{
										name:\'font\',
										title:\''.T_('Police').'\',
										values:[\'Some Special Font!\',\'Arial\',\'Verdana\',\'Comic Sans MS\',\'Custom Font!\']
									},
									null,
									{
										name:\'fontSize\',
										title:\''.T_('Taille').'\',
										values:{1 : \'Size#1 Text\' , 2 : \'Size#1 Text\' , 3 : \'Size#3 Text\' , 4 : \'Size#4 Text\' , 5 : \'Size#5 Text\'} 
									},
									null,
									{name:\'bold\', title:\''.T_('Gras').'\'},
									{name:\'italic\', title:\''.T_('Italique').'\'},
									{name:\'underline\', title:\''.T_('Souligner').'\'},
									null,
									{name:\'insertunorderedlist\', title:\''.T_('Liste à puces').'\'},
									{name:\'insertorderedlist\', title:\''. T_('Liste numéroté').'\'},
									{name:\'outdent\', title:\''.T_('Augmenter le retrait').'\'},
									{name:\'indent\', title:\''.T_('Diminuer le retrait').'\'},
									null,
									{name:\'justifyleft\',title:\''.T_('Aligner à gauche').'\'},
									{name:\'justifycenter\',title:\''.T_('Centrer').'\'},
									{name:\'justifyright\',title:\''.T_('Aligner à droite').'\'},
									{name:\'justifyfull\',title:\''.T_('Justifier').'\'},
									null,
									{
										name:\'createLink\',
										title:\''.T_('Insérer un lien').'\',
										placeholder:\''.T_('Insérer un lien').'\',
										button_text:\''.T_('Ajouter').'\'
									},
									null,
									{
										name:\'insertImage\',
										title:\''.T_('Insérer une image').'\',
										placeholder:\''.T_('Insérer une image').'\',
										button_class:\'btn-inverse\',
										//choose_file:false,//hide choose file button
										button_text:\''.T_('Sélectionner une image').'\',
										button_insert_class:\'btn-pink\',
										button_insert:\''.T_('Insérer une image').'\'
									},
									null,
									{name:\'foreColor\',title:\''.T_('Couleur').'\',values:[\'red\',\'green\',\'blue\',\'orange\',\'black\'],},
									null,
									{name:\'undo\',title:\''.T_('Annuler la modification').'\'},
									{name:\'redo\',title:\''.T_('Rétablir').'\'}
								';
							} else {
								echo '
									{
										name:\'font\',
										title:\''.T_('Police').'\',
										values:[\'Some Special Font!\',\'Arial\',\'Verdana\',\'Comic Sans MS\',\'Custom Font!\']
									},
									null,
									{
										name:\'fontSize\',
										title:\''.T_('Taille').'\',
										values:{1 : \'Size#1 Text\' , 2 : \'Size#1 Text\' , 3 : \'Size#3 Text\' , 4 : \'Size#4 Text\' , 5 : \'Size#5 Text\'} 
									},
									null,
									{name:\'bold\', title:\''.T_('Gras').'\'},
									{name:\'italic\', title:\''.T_('Italique').'\'},
									{name:\'underline\', title:\''.T_('Souligner').'\'},
								';
							}
						?>
					],
					speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
				$('#editor2').ace_wysiwyg({
					toolbar:
					[
						<?php
							//display a light font function if mobile is detected
							if($mobile==0)
							{
								echo '
									{
										name:\'font\',
										title:\''.T_('Police').'\',
										values:[\'Some Special Font!\',\'Arial\',\'Verdana\',\'Comic Sans MS\',\'Custom Font!\']
									},
									null,
									{
										name:\'fontSize\',
										title:\''.T_('Taille').'\',
										values:{1 : \'Size#1 Text\' , 2 : \'Size#1 Text\' , 3 : \'Size#3 Text\' , 4 : \'Size#4 Text\' , 5 : \'Size#5 Text\'} 
									},
									null,
									{name:\'bold\', title:\''.T_('Gras').'\'},
									{name:\'italic\', title:\''.T_('Italique').'\'},
									{name:\'underline\', title:\''.T_('Souligner').'\'},
									null,
									{name:\'insertunorderedlist\', title:\''.T_('Liste à puces').'\'},
									{name:\'insertorderedlist\', title:\''. T_('Liste numéroté').'\'},
									{name:\'outdent\', title:\''.T_('Augmenter le retrait').'\'},
									{name:\'indent\', title:\''.T_('Diminuer le retrait').'\'},
									null,
									{name:\'justifyleft\',title:\''.T_('Aligner à gauche').'\'},
									{name:\'justifycenter\',title:\''.T_('Centrer').'\'},
									{name:\'justifyright\',title:\''.T_('Aligner à droite').'\'},
									{name:\'justifyfull\',title:\''.T_('Justifier').'\'},
									null,
									{
										name:\'insertImage\',
										title:\''.T_('Insérer une image').'\',
										placeholder:\''.T_('Insérer une image').'\',
										button_class:\'btn-inverse\',
										//choose_file:false,//hide choose file button
										button_text:\''.T_('Sélectionner une image').'\',
										button_insert_class:\'btn-pink\',
										button_insert:\''.T_('Insérer une image').'\'
									},
									null,
									{name:\'foreColor\',title:\''.T_('Couleur').'\',values:[\'red\',\'green\',\'blue\',\'orange\',\'black\'],},
									null,
									{name:\'undo\',title:\''.T_('Annuler la modification').'\'},
									{name:\'redo\',title:\''.T_('Rétablir').'\'}
								';
							} else {
								echo '
									{
										name:\'font\',
										title:\''.T_('Police').'\',
										values:[\'Some Special Font!\',\'Arial\',\'Verdana\',\'Comic Sans MS\',\'Custom Font!\']
									},
									null,
									{
										name:\'fontSize\',
										title:\''.T_('Taille').'\',
										values:{1 : \'Size#1 Text\' , 2 : \'Size#1 Text\' , 3 : \'Size#3 Text\' , 4 : \'Size#4 Text\' , 5 : \'Size#5 Text\'} 
									},
									null,
									{name:\'bold\', title:\''.T_('Gras').'\'},
									{name:\'italic\', title:\''.T_('Italique').'\'},
									{name:\'underline\', title:\''.T_('Souligner').'\'},
								';
							}
						?>
					],
					speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
			});
		</script>
