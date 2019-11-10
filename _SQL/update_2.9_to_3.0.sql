-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

ALTER TABLE  `tparameters` CHANGE  `version`  `version` VARCHAR( 8 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

UPDATE tparameters SET version="3.0.0";

ALTER TABLE  `tprocedures` CHANGE  `name`  `name` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE  `tstates` ADD  `display` VARCHAR( 100 ) NOT NULL;
UPDATE  `tstates` SET  `display` =  'label label-sm label-info arrowed-in' WHERE  `tstates`.`id` =1;
UPDATE  `tstates` SET  `display` =  'label label-sm label-warning arrowed-in' WHERE  `tstates`.`id` =2;
UPDATE  `tstates` SET  `display` =  'label label-sm label-success arrowed arrowed-right arrowed-left' WHERE  `tstates`.`id` =3;
UPDATE  `tstates` SET  `display` =  'label label-sm label-inverse arrowed arrowed-right arrowed-left' WHERE  `tstates`.`id` =4;
UPDATE  `tstates` SET  `display` =  'label label-sm label-important arrowed-in arrowed-right rrowed-left' WHERE  `tstates`.`id` =5;
UPDATE  `tstates` SET  `display` =  'label label-sm label-pink arrowed arrowed-right arrowed-left' WHERE  `tstates`.`id` =6;

ALTER TABLE  `tusers` ADD  `skin` VARCHAR( 10 ) NOT NULL;

ALTER TABLE  `tprofiles` ADD  `img` VARCHAR( 20 ) NOT NULL;
UPDATE  `tprofiles` SET  `img` =  'user.png' WHERE  `tprofiles`.`id` =3;
UPDATE  `tprofiles` SET  `img` =  'poweruser.png' WHERE  `tprofiles`.`id` =2;
UPDATE  `tprofiles` SET  `img` =  'technician.png' WHERE  `tprofiles`.`id` =1;
UPDATE  `tprofiles` SET  `img` =  'supervisor.png' WHERE  `tprofiles`.`id` =4;
UPDATE  `tprofiles` SET  `img` =  'admin.png' WHERE  `tprofiles`.`id` =5;

ALTER TABLE  `trights` ADD  `ticket_next` INT( 1 ) NOT NULL COMMENT  'Affiche les flèches ticket suivant et précedent' AFTER  `side_view`;
UPDATE `trights` SET `ticket_next` = '2';
ALTER TABLE  `trights` ADD  `ticket_print` INT( 1 ) NOT NULL COMMENT  'Impression des tickets' AFTER  `ticket_next`;
UPDATE `trights` SET `ticket_print` = '2';
ALTER TABLE  `trights` ADD  `ticket_calendar` INT( 1 ) NOT NULL COMMENT  'Planifier un ticket' AFTER  `ticket_print`;
UPDATE `trights` SET `ticket_calendar` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_event` INT( 1 ) NOT NULL COMMENT  'Créer un rappel de ticket' AFTER  `ticket_calendar`;
UPDATE `trights` SET `ticket_event` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_save` INT( 1 ) NOT NULL COMMENT  'Sauvegarde de ticket' AFTER  `ticket_event`;
UPDATE `trights` SET `ticket_save` = '2' WHERE id='1' OR id='4' OR id='5';
UPDATE `trights` SET `ticket_save` = '0' WHERE id='2' OR id='3';
ALTER TABLE  `trights` ADD  `ticket_user` INT( 1 ) NOT NULL COMMENT  'Modfication du demandeur' AFTER  `ticket_save`;
UPDATE `trights` SET `ticket_user` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_user_actions` INT( 1 ) NOT NULL COMMENT  'Affiche les bouttons actions pour le demandeur' AFTER  `ticket_user`;
UPDATE `trights` SET `ticket_user_actions` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_tech` INT( 1 ) NOT NULL COMMENT  'Modification du technicien' AFTER  `ticket_user_actions`;
UPDATE `trights` SET `ticket_tech` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_cat` INT( 1 ) NOT NULL COMMENT  'Modification des catégories' AFTER  `ticket_tech`;
UPDATE `trights` SET `ticket_cat` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_cat_actions` INT( 1 ) NOT NULL COMMENT  'Affiche les bouttons actions pour les catégories' AFTER  `ticket_cat`;
UPDATE `trights` SET `ticket_cat_actions` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_title` INT( 1 ) NOT NULL COMMENT  'Modification du titre dans le ticket' AFTER  `ticket_cat_actions`;
UPDATE `trights` SET `ticket_title` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_description` INT( 1 ) NOT NULL COMMENT  'Modification de la description' AFTER  `ticket_title`;
UPDATE `trights` SET `ticket_description` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_attachment` INT( 1 ) NOT NULL COMMENT  'Ajouter des pièces jointes' AFTER  `ticket_description`;
UPDATE `trights` SET `ticket_attachment` = '2';
ALTER TABLE  `trights` ADD  `ticket_thread_add` INT( 1 ) NOT NULL COMMENT  'Ajouter des réponses' AFTER  `ticket_close`;
UPDATE `trights` SET `ticket_thread_add` = '2';
ALTER TABLE  `trights` ADD  `ticket_date_create` INT( 1 ) NOT NULL COMMENT  'Modification de la date de création' AFTER  `ticket_attachment`;
UPDATE `trights` SET `ticket_date_create` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_date_hope` INT( 1 ) NOT NULL COMMENT  'Modification de la date de résolution estimée' AFTER  `ticket_date_create`;
UPDATE `trights` SET `ticket_date_hope` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_time` INT( 1 ) NOT NULL COMMENT  'Modification du temps passé par ticket' AFTER  `ticket_date_hope`;
UPDATE `trights` SET `ticket_time` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_time_hope` INT( 1 ) NOT NULL COMMENT  'Modification du temps estimé passé par ticket' AFTER  `ticket_time`;
UPDATE `trights` SET `ticket_time_hope` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_priority` INT( 1 ) NOT NULL COMMENT  'Modification de la priorité dans le ticket' AFTER  `ticket_time_hope`;
UPDATE `trights` SET `ticket_priority` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_criticality` INT( 1 ) NOT NULL COMMENT  'Modification de la criticité dans le ticket' AFTER  `ticket_priority`;
UPDATE `trights` SET `ticket_criticality` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_state` INT( 1 ) NOT NULL COMMENT  'Modification de la criticité dans le ticket' AFTER  `ticket_criticality`;
UPDATE `trights` SET `ticket_state` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_save_close` INT( 1 ) NOT NULL COMMENT  'Affiche le boutton enregistrer et fermer dans le ticket' AFTER  `ticket_thread_post`;
UPDATE `trights` SET `ticket_save_close` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_send_mail` INT( 1 ) NOT NULL COMMENT  'Affiche le boutton envoyer un mail dans le ticket' AFTER  `ticket_save_close`;
UPDATE `trights` SET `ticket_send_mail` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_cancel` INT( 1 ) NOT NULL COMMENT  'Affiche le boutton annuler dans le ticket' AFTER  `ticket_send_mail`;
UPDATE `trights` SET `ticket_cancel` = '2' ;
UPDATE `trights` SET `ticket_close` = '2' WHERE id='2' OR id='3';
UPDATE `trights` SET `ticket_close` = '0' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` CHANGE  `ticket_close`  `ticket_close` INT( 1 ) NOT NULL COMMENT  'Affiche le bouton de cloture dans le ticket';
ALTER TABLE  `trights` ADD  `ticket_user_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ utilisateur dans le ticket' AFTER  `ticket_user`;
UPDATE `trights` SET `ticket_user_disp` = '2';
ALTER TABLE  `trights` ADD  `ticket_tech_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ technicien dans le ticket' AFTER  `ticket_tech`;
UPDATE `trights` SET `ticket_tech_disp` = '2';
ALTER TABLE  `trights` ADD  `ticket_cat_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ catégorie dans le ticket' AFTER  `ticket_cat`;
UPDATE `trights` SET `ticket_cat_disp` = '2';
ALTER TABLE  `trights` ADD  `ticket_title_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ titre dans le ticket' AFTER  `ticket_title`;
UPDATE `trights` SET `ticket_title_disp` = '2';
ALTER TABLE  `trights` ADD  `ticket_description_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ description dans le ticket' AFTER  `ticket_description`;
UPDATE `trights` SET `ticket_description_disp` = '2';
ALTER TABLE  `trights` ADD  `ticket_resolution_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ resolution dans le ticket' AFTER  `ticket_description_disp`;
UPDATE `trights` SET `ticket_resolution_disp` = '2';
ALTER TABLE  `trights` ADD  `ticket_date_create_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ date de création dans le ticket' AFTER  `ticket_date_create`;
UPDATE `trights` SET `ticket_date_create_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_date_hope_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ date de de résolution estimé dans le ticket' AFTER  `ticket_date_hope`;
UPDATE `trights` SET `ticket_date_hope_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_time_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ temps passé dans le ticket' AFTER  `ticket_time`;
UPDATE `trights` SET `ticket_time_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_time_hope_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ temps estimé dans le ticket' AFTER  `ticket_time_hope`;
UPDATE `trights` SET `ticket_time_hope_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_priority_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ priorité dans le ticket' AFTER  `ticket_priority`;
UPDATE `trights` SET `ticket_priority_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_criticality_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ criticité dans le ticket' AFTER  `ticket_criticality`;
UPDATE `trights` SET `ticket_criticality_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_state_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ état dans le ticket' AFTER  `ticket_state`;
UPDATE `trights` SET `ticket_state_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_new_user` INT( 1 ) NOT NULL COMMENT  'Modification du demandeur pour les nouveaux tickets' AFTER  `ticket_cancel`;
UPDATE `trights` SET `ticket_new_user` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_new_user_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ demandeur pour les nouveaux tickets' AFTER  `ticket_new_user`;
UPDATE `trights` SET `ticket_new_user_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_new_tech_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ technicien pour les nouveaux tickets' AFTER  `ticket_new_user_disp`;
UPDATE `trights` SET `ticket_new_tech_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_new_cat` INT( 1 ) NOT NULL COMMENT  'Modification de la catégorie pour les nouveaux tickets' AFTER  `ticket_new_tech_disp`;
UPDATE `trights` SET `ticket_new_cat` = '2';
ALTER TABLE  `trights` ADD  `ticket_new_cat_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ catégorie pour les nouveaux tickets' AFTER  `ticket_new_cat`;
UPDATE `trights` SET `ticket_new_cat_disp` = '2' ;
ALTER TABLE  `trights` ADD  `ticket_new_resolution_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ résolution pour les nouveaux tickets' AFTER  `ticket_new_cat_disp`;
UPDATE `trights` SET `ticket_new_resolution_disp` = '2' WHERE id='1' OR id='4' OR id='5';
ALTER TABLE  `trights` ADD  `ticket_new_send` INT( 1 ) NOT NULL COMMENT  'Affiche le boutton envoyer pour les nouveaux tickets' AFTER  `ticket_new_resolution_disp`;
UPDATE `trights` SET `ticket_new_send` = '2' WHERE id='2' OR id='3';
ALTER TABLE  `trights` ADD  `ticket_template` INT( 1 ) NOT NULL COMMENT  'Affiche le boutton modèle de ticket' AFTER  `ticket_print`;
UPDATE `trights` SET `ticket_template` = '2' WHERE id='1' OR id='4' OR id='5';


ALTER TABLE  `trights` DROP `task`;

ALTER TABLE  `tincidents` CHANGE  `date_create`  `date_create` DATETIME NOT NULL;

UPDATE  `tcriticality` SET  `color` =  '#d15b47' WHERE  `tcriticality`.`id` =1;
UPDATE  `tcriticality` SET  `color` =  '#f89406' WHERE  `tcriticality`.`id` =2;
UPDATE  `tcriticality` SET  `color` =  '#f8c806' WHERE  `tcriticality`.`id` =3;
UPDATE  `tcriticality` SET  `color` =  '#82af6f' WHERE  `tcriticality`.`id` =4;

UPDATE `tparameters` SET `mail_color_title` = '438eb9'  WHERE  `tparameters`.`mail_color_title` = '0075A4';
UPDATE `tparameters` SET `mail_color_text` = '438eb9'  WHERE  `tparameters`.`mail_color_text` = '0075A4';
UPDATE `tparameters` SET `mail_color_bg` = 'f5f5f5'  WHERE  `tparameters`.`mail_color_bg` = 'D8D8D8';

ALTER TABLE  `tpriority` ADD  `color` VARCHAR( 15 ) NOT NULL;
UPDATE  `tpriority` SET  `color` =  '#d15b47' WHERE  `tpriority`.`number` =  '0';
UPDATE  `tpriority` SET  `color` =  '#f89406' WHERE  `tpriority`.`number` =  '1';
UPDATE  `tpriority` SET  `color` =  '#f8c806' WHERE  `tpriority`.`number` =  '2';
UPDATE  `tpriority` SET  `color` =  '#e7ef20' WHERE  `tpriority`.`number` =  '3';
UPDATE  `tpriority` SET  `color` =  '#c2c921' WHERE  `tpriority`.`number` =  '4';
UPDATE  `tpriority` SET  `color` =  '#82af6f' WHERE  `tpriority`.`number` =  '5';

ALTER TABLE  `tparameters` ADD  `update_channel` VARCHAR( 10 ) NOT NULL AFTER  `version`;
UPDATE  `tparameters` SET  `update_channel` =  'beta' WHERE id='1';

UPDATE  `tparameters` SET  `maxline` =  '14' WHERE id='1';

UPDATE  `tstates` SET  `description` =  'tickets en attente d''éléments de la part du demandeur' WHERE  `tstates`.`id` =6 LIMIT 1;
UPDATE  `tstates` SET  `description` =  'tickets en attente de prise en charge par un technicien' WHERE  `tstates`.`id` =1 LIMIT 1;
UPDATE  `tstates` SET  `description` =  'tickets en cours de traitement' WHERE  `tstates`.`id` =2 LIMIT 1;
UPDATE  `tstates` SET  `description` =  'tickets rejetés' WHERE  `tstates`.`id` =4 LIMIT 1;
UPDATE  `tstates` SET  `description` =  'tickets résolus' WHERE  `tstates`.`id` =  '3' LIMIT 1;
UPDATE  `tstates` SET  `description` =  'tickets pas encore associés à un technicien' WHERE  `tstates`.`id` =5 LIMIT 1;
UPDATE  `tstates` SET  `description` =  'tickets en attente d''éléments de la part du demandeur' WHERE  `tstates`.`id` =  '6' LIMIT 1;
