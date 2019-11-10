-- update 3.0.8 to 3.1.21;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update 3.0.9
UPDATE tparameters SET version="3.0.9";

ALTER TABLE  `tincidents` CHANGE  `description`  `description` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tthreads` CHANGE  `text`  `text` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE  `trights` CHANGE  `ticket_thread_edit`  `ticket_thread_edit` INT( 1 ) NOT NULL COMMENT  'Modification de ses résolutions';
ALTER TABLE  `trights` CHANGE  `ticket_thread_edit_all`  `ticket_thread_edit_all` INT( 1 ) NOT NULL COMMENT  'Modification de toutes les résolutions';

ALTER TABLE  `tparameters` ADD  `availability` INT( 1 ) NOT NULL;
UPDATE tparameters SET `availability`="0" WHERE id=1;
ALTER TABLE  `tparameters` ADD  `availability_all_cat` INT( 1 ) NOT NULL;
UPDATE tparameters SET `availability_all_cat`="1" WHERE id=1;
ALTER TABLE  `tparameters` ADD  `availability_dep` INT( 1 ) NOT NULL;
UPDATE tparameters SET `availability_dep`="0" WHERE id=1;


CREATE TABLE IF NOT EXISTS `tavailability` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE  `trights` ADD  `availability` INT( 1 ) NOT NULL COMMENT 'Affiche le menu Disponibilité' AFTER  `planning`;
UPDATE `trights` SET `availability`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `tparameters` ADD  `availability_condition_type` VARCHAR( 20 ) NOT NULL ;
ALTER TABLE  `tparameters` ADD  `availability_condition_value` INT( 4 ) NOT NULL;


ALTER TABLE  `tincidents` ADD  `start_availability` DATETIME NOT NULL;
ALTER TABLE  `tincidents` ADD  `end_availability` DATETIME NOT NULL;

ALTER TABLE  `trights` ADD  `ticket_availability` INT( 1 ) NOT NULL COMMENT 'Modifiction de la partie disponibilité' AFTER  `ticket_state_disp`;
UPDATE `trights` SET `ticket_availability`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `trights` ADD  `ticket_availability_disp` INT( 1 ) NOT NULL COMMENT 'Affiche la partie disponibilité' AFTER  `ticket_availability`;
UPDATE `trights` SET `ticket_availability_disp`='2' WHERE id='1' OR id='4' OR id='5';

CREATE TABLE IF NOT EXISTS `tavailability_dep` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `tincidents` ADD  `availability_planned` INT( 1 ) NOT NULL;

ALTER TABLE  `tprocedures` ADD  `category` INT( 5 ) NOT NULL AFTER  `id`;
ALTER TABLE  `tprocedures` ADD  `subcat` INT( 5 ) NOT NULL AFTER  `category`;

-- update 3.0.10
UPDATE tparameters SET version="3.0.10";

INSERT INTO  `tthreads` (`id`,`ticket`,`date`,`author`,`text`,`type`,`tech1`,`tech2`,`group1`,`group2`,`user`)
SELECT NULL,id,0,0,0,9,0,0,0,0,0 FROM tincidents WHERE tincidents.disable=0 AND id NOT IN (SELECT ticket FROM tthreads);

-- update max lengh for attachment
ALTER TABLE  `tincidents` CHANGE  `img1`  `img1` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img2`  `img2` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img3`  `img3` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img4`  `img4` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img5`  `img5` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- add service to incidents table for fix stat
ALTER TABLE  `tincidents` ADD `u_service` INT( 5 ) NOT NULL AFTER `u_group`;

-- fill current service data in tincidents from tusers 
UPDATE tincidents,tusers SET tincidents.u_service=tusers.service WHERE tincidents.user=tusers.id;

-- update 3.0.11
UPDATE tparameters SET version="3.0.11";

-- create assets table
CREATE TABLE IF NOT EXISTS `tassets` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `sn_internal` varchar(20) NOT NULL,
  `sn_manufacturer` varchar(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `netbios` varchar(30) NOT NULL,
  `description` varchar(500) NOT NULL,
  `type` int(5) NOT NULL,
  `model` int(5) NOT NULL,
  `user` int(5) NOT NULL,
  `state` int(5) NOT NULL,
  `mac_lan` varchar(20) NOT NULL,
  `mac_wifi` varchar(20) NOT NULL,
  `department` int(5) NOT NULL,
  `date_install` date NOT NULL,
  `date_stock` date NOT NULL,
  `room` varchar(10) NOT NULL,
  `technician` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- create assets parameters
ALTER TABLE  `tparameters` ADD  `asset` INT( 1 ) NOT NULL;
UPDATE `tparameters` SET `asset`=0;

-- create assets rights
ALTER TABLE  `trights` ADD  `asset` INT( 1 ) NOT NULL COMMENT 'Affiche le menu matériel' AFTER  `availability`;
UPDATE `trights` SET `asset`='2' WHERE id='1' OR id='4' OR id='5';

CREATE TABLE IF NOT EXISTS `tassets_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tassets_model` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `type` int(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tassets_type` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tassets_state` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `tparameters` ADD  `meta_state` INT( 1 ) NOT NULL;
UPDATE tparameters SET meta_state="0";

ALTER TABLE  `trights` ADD  `side_your_meta` INT( 1 ) NOT NULL COMMENT 'Affiche le meta etat à traiter personnel' AFTER `side_your_not_attribute`;
UPDATE `trights` SET `side_your_meta`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `trights` ADD  `side_all_meta` INT( 1 ) NOT NULL COMMENT 'Affiche le meta etat à traiter pour tous les techniciens' AFTER `side_all_wait`;
UPDATE `trights` SET `side_all_meta`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `trights` ADD  `ticket_date_hope_mandatory` INT( 1 ) NOT NULL COMMENT 'Oblige la saisie du champ date de résolution estimé' AFTER  `ticket_date_hope_disp`;
UPDATE `trights` SET `ticket_date_hope_mandatory`='0';

ALTER TABLE  `trights` ADD  `ticket_priority_mandatory` INT( 1 ) NOT NULL COMMENT 'Oblige la saisie du champ priorité' AFTER  `ticket_priority_disp`;
UPDATE `trights` SET `ticket_priority_mandatory`='0';

ALTER TABLE  `trights` ADD  `ticket_criticality_mandatory` INT( 1 ) NOT NULL COMMENT 'Oblige la saisie du champ criticité' AFTER  `ticket_criticality_disp`;
UPDATE `trights` SET `ticket_criticality_mandatory`='0';

-- bugfix priority number
UPDATE tincidents SET priority='6' WHERE priority='5';
UPDATE tincidents SET priority='5' WHERE priority='4';
UPDATE tincidents SET priority='4' WHERE priority='3';
UPDATE tincidents SET priority='3' WHERE priority='2';
UPDATE tincidents SET priority='2' WHERE priority='1';
UPDATE tincidents SET priority='1' WHERE priority='0';


ALTER TABLE  `tparameters` ADD  `dash_date` VARCHAR( 15 ) NOT NULL;
UPDATE tparameters SET dash_date="date_create";

ALTER TABLE  `tusers` ADD  `default_ticket_state` VARCHAR( 5 ) NOT NULL;

ALTER TABLE  `tusers` ADD  `dashboard_ticket_order` VARCHAR( 200 ) NOT NULL;


CREATE TABLE IF NOT EXISTS `tavailability_target` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `subcat` int(5) NOT NULL,
  `target` FLOAT(5) NOT NULL,
  `year` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- update 3.1.15
UPDATE tparameters SET availability_condition_type="4" WHERE availability_condition_type="";

-- add dashboard columun right
ALTER TABLE  `trights` ADD  `dashboard_col_priority` INT( 1 ) NOT NULL COMMENT 'Affiche la colonne priorité dans la liste des tickets.' AFTER `admin_user_view`;
UPDATE `trights` SET `dashboard_col_priority`='2';

-- fix empty user service problem in tincidents table
UPDATE tincidents, tusers
SET  tincidents.u_service = tusers.service
WHERE tincidents.user=tusers.id AND tincidents.u_service='0';

-- update GestSup version number
UPDATE tparameters SET version="3.1.1";

ALTER TABLE `tassets_model` ADD `manufacturer` INT(5) NOT NULL AFTER `type`;

ALTER TABLE `tassets_model` ADD `image` VARCHAR(30) NOT NULL AFTER `manufacturer`;

ALTER TABLE `tassets_state` ADD `order` INT(3) NOT NULL AFTER `id`;

ALTER TABLE `tassets` CHANGE `sn_manufacturer` `sn_manufacturer` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `tassets` ADD `disable` INT(1) NOT NULL ;

CREATE TABLE IF NOT EXISTS `tassets_thread` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `asset` int(10) NOT NULL,
  `text` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `tassets` ADD `sn_indent` VARCHAR(40) NOT NULL AFTER `sn_manufacturer`;

ALTER TABLE `tassets` CHANGE `mac_wifi` `mac_wlan` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `tassets` CHANGE `room` `socket` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `tassets` ADD `maintenance` INT(10) NOT NULL AFTER `technician`;

ALTER TABLE `tservices` ADD `disable` INT(1) NOT NULL ;

ALTER TABLE `tassets` ADD `date_recycle` DATE NOT NULL AFTER `date_stock`;

ALTER TABLE `tassets_state` ADD `disable` INT(1) NOT NULL ;

ALTER TABLE `tassets` ADD `date_standbye` DATE NOT NULL AFTER `date_stock`;

ALTER TABLE `trights` ADD `side_asset_create` INT(1) NOT NULL  COMMENT 'Affiche le bouton ajouter matériel' AFTER `side_open_ticket`;

UPDATE `trights` SET `side_asset_create`='2' WHERE id='1' OR id='4' OR id='5';

CREATE TABLE IF NOT EXISTS `tassets_network` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `network` varchar(15) NOT NULL,
  `netmask` varchar(15) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- dashboard columns
ALTER TABLE `trights` ADD `dashboard_col_date_create` INT(1) NOT NULL COMMENT 'Affiche la colonne date de création dans la liste des tickets.' AFTER `dashboard_col_priority`;
UPDATE `trights` SET `dashboard_col_date_create`='2';
ALTER TABLE `trights` ADD `dashboard_col_date_hope` INT(1) NOT NULL COMMENT 'Affiche la colonne date de résolution estimé dans la liste des tickets.' AFTER `dashboard_col_date_create`;
UPDATE `trights` SET `dashboard_col_date_hope`='0';
ALTER TABLE `trights` ADD `dashboard_col_date_res` INT(1) NOT NULL COMMENT 'Affiche la colonne date de résolution dans la liste des tickets.' AFTER `dashboard_col_date_hope`;
UPDATE `trights` SET `dashboard_col_date_res`='0';

ALTER TABLE `tparameters` DROP `dash_date`;

CREATE TABLE IF NOT EXISTS `ttoken` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `token` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- fix place problem		
ALTER TABLE `tincidents` CHANGE `place` `place` INT(5) NOT NULL DEFAULT '99999';
UPDATE `tincidents` SET place='99999' WHERE place='0';
INSERT INTO `tplaces` (`id`, `name`) VALUES ('99999', 'Aucun');

-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;


-- update GestSup version number
UPDATE tparameters SET version="3.1.2";

-- default asset state values
ALTER TABLE `tassets_state` ADD `display` VARCHAR(50) NOT NULL ;
ALTER TABLE `tassets_state` CHANGE `display` `display` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets_state` ADD `description` VARCHAR(50) NOT NULL AFTER `name`;
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('1', '1', 'Stock', 'Equipement en stock', '0', 'label label-sm label-info arrowed-in');
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('2', '2', 'Installé', 'Equipement installé en production', '0','label label-sm label-success arrowed arrowed-right arrowed-left');
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('3', '3', 'Standbye', 'Equipement de coté', '0','label label-sm label-warning arrowed-in arrowed-right arrowed-in arrowed-left');
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('4', '4', 'Recyclé', 'Equipement recyclé, jeté', '0', 'label label-sm label-inverse arrowed arrowed-right arrowed-left');


-- default asset type
INSERT INTO `tassets_type` (`id`, `name`) VALUES (NULL, 'PC');

-- default manufacturer
INSERT INTO `tassets_manufacturer` (`id`, `name`) VALUES (NULL, 'Dell');

-- default asset model
INSERT INTO `tassets_model` (`id`, `type`, `manufacturer`, `image`, `name`) VALUES (NULL, '1', '1', '3020.jpg', 'Optiplex 3020');

-- update asset core
ALTER TABLE `tassets` ADD `manufacturer` INT(5) NOT NULL AFTER `type`;
ALTER TABLE `tassets_model` ADD `ip` INT(1) NOT NULL ;
ALTER TABLE `tassets_network` ADD `disable` INT(1) NOT NULL ;
ALTER TABLE `tassets` ADD `ip2` VARCHAR(20) NOT NULL AFTER `ip`;
ALTER TABLE `tassets_model` ADD `ip2` INT(1) NOT NULL AFTER `ip`;
ALTER TABLE `tassets_model` CHANGE `ip2` `wifi` INT(1) NOT NULL;
ALTER TABLE `tassets_model` ADD `warranty` INT(2) NOT NULL AFTER `wifi`;

-- update 3.1.2.2
ALTER TABLE  `tassets_model` CHANGE  `name`  `name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets_network` CHANGE `name` `name` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;


-- update GestSup version number
UPDATE tparameters SET version="3.1.3";

UPDATE tassets_model SET ip='1' WHERE name='Optiplex 3020';
UPDATE tassets_model SET warranty='3' WHERE name='Optiplex 3020';

ALTER TABLE `trights` ADD `asset_list_department_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les matériels du serivce auquel est rattaché l''utilisateur.' AFTER `asset`;
UPDATE `trights` SET `asset_list_department_only` = '2' WHERE `trights`.`profile` = 1;

ALTER TABLE `trights` ADD `asset_list_view_only` INT(1) NOT NULL COMMENT 'Affiche uniquement la liste des matériels, sans droit d''éditer une fiche.' AFTER `asset_list_department_only`;
UPDATE `trights` SET `asset_list_view_only` = '2' WHERE `trights`.`profile` = 1;

ALTER TABLE `trights` ADD `side_asset_all_state` INT NOT NULL COMMENT 'Affiche tous les états des matériels dans le menu de gauche' AFTER `side_asset_create`;
UPDATE `trights` SET `side_asset_all_state` = '2' WHERE id='1' OR id='4' OR id='5';



-- update GestSup version number
UPDATE tparameters SET version="3.1.4";

ALTER TABLE `tparameters` ADD `mail_auto_user_modify` INT(1) NOT NULL DEFAULT '0' AFTER `mail_auto`;
ALTER TABLE `tparameters` ADD `mail_auto_tech_modify` INT(1) NOT NULL DEFAULT '0' AFTER `mail_auto_user_modify`;

-- update GestSup version number
UPDATE tparameters SET version="3.1.5";

UPDATE `trights` SET `ticket_delete` = '0' WHERE `trights`.`id` = 2;
UPDATE `trights` SET `ticket_delete` = '0' WHERE `trights`.`id` = 3;

ALTER TABLE `tparameters` ADD `mail_smtp_class` VARCHAR(15) NOT NULL DEFAULT 'isSMTP()' AFTER `mail_smtp`;

-- update GestSup version number
UPDATE tparameters SET version="3.1.6";

-- sql fix to empty date recycle on recyle asset state
UPDATE tassets SET date_recycle='2016-01-01' WHERE date_recycle='0000-00-00' AND state='4';

-- update GestSup version number
UPDATE tparameters SET version="3.1.7";

UPDATE tparameters SET version="3.1.9";

ALTER TABLE `tusers` CHANGE `mail` `mail` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tparameters` ADD `user_limit_ticket` INT(1) NOT NULL DEFAULT '0' AFTER `user_register`;
ALTER TABLE `tusers` ADD `limit_ticket_number` INT(5) NOT NULL AFTER `dashboard_ticket_order`;
ALTER TABLE `tusers` ADD `limit_ticket_days` INT(5) NOT NULL AFTER `limit_ticket_number`;
ALTER TABLE `tusers` ADD `limit_ticket_date_start` DATE NOT NULL AFTER `limit_ticket_days`;

UPDATE tparameters SET version="3.1.10";

ALTER TABLE `tparameters` ADD `user_company_view` INT(1) NULL DEFAULT '0' AFTER `user_limit_ticket`;
ALTER TABLE `trights` ADD `side_company` INT(1) NOT NULL DEFAULT '0' COMMENT 'Affiche la section tous les tickets de ma société' AFTER `side_all_meta`;
UPDATE `trights` SET `side_company` = '2' WHERE id='2' OR id='3';
INSERT INTO `tcompany` (`id`, `name`, `address`, `zip`, `city`) VALUES ('0', 'Aucune', '', '', '');
UPDATE `tcompany` SET id='0' WHERE name='Aucune';
ALTER TABLE `trights` ADD `user_profil_company` INT(1) NOT NULL DEFAULT '2' COMMENT 'Modification de la société sur la fiche utilisateur' ;
ALTER TABLE `tparameters` ADD `company_limit_ticket` INT(1) NOT NULL DEFAULT '0';
ALTER TABLE `tcompany` ADD `limit_ticket_number` INT(5) NOT NULL DEFAULT '0' AFTER `city`;
ALTER TABLE `tcompany` ADD `limit_ticket_days` INT(5) NOT NULL AFTER `limit_ticket_number`;
ALTER TABLE `tcompany` ADD `limit_ticket_date_start` DATE NOT NULL AFTER `limit_ticket_days`;
ALTER TABLE `tusers` CHANGE `login` `login` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tparameters` CHANGE `ldap_url` `ldap_url` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tparameters` ADD `server_private_key` VARCHAR(40) NOT NULL AFTER `server_url`;

-- update GestSup version number
UPDATE tparameters SET version="3.1.11";

ALTER TABLE `tcompany` CHANGE `name` `name` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- avoid invisible ticket with join at 0 value on tusers table
INSERT INTO `tusers` (`id`, `login`, `password`, `salt`, `firstname`, `lastname`, `profile`, `mail`, `phone`, `fax`, `function`, `service`, `company`, `address1`, `address2`, `zip`, `city`, `custom1`, `custom2`, `disable`, `chgpwd`, `last_login`, `skin`, `default_ticket_state`, `dashboard_ticket_order`, `limit_ticket_number`, `limit_ticket_days`, `limit_ticket_date_start`) VALUES ('0', 'aucun', '', '', '', '', '2', '', '', '', '', '0', '0', '', '', '', '', '', '', '1', '0', '2016-10-21 00:00:00', '', '', '', '0', '0', '2016-10-21');
UPDATE `tusers` SET `id` = '0' WHERE `tusers`.`login` = 'aucun';
UPDATE `tusers` SET `disable` = '1' WHERE `tusers`.`login` = 'aucun';

-- update GestSup version number
UPDATE tparameters SET version="3.1.12";

ALTER TABLE `tusers` CHANGE `login` `login` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets` ADD `date_end_warranty` DATE NOT NULL AFTER `date_recycle`;
ALTER TABLE `trights` CHANGE `asset_list_department_only` `asset_list_department_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les matériels du service auquel est rattaché l\'utilisateur.';

-- update new date end warranty field where warranty model is defined
SET sql_mode = '';
UPDATE tassets,tassets_model SET tassets.date_end_warranty= DATE_ADD(tassets.date_stock,INTERVAL tassets_model.warranty YEAR) 
WHERE 
tassets_model.id=tassets.model AND
tassets_model.warranty NOT LIKE 0 AND
tassets.date_stock NOT LIKE '0000-00-00' AND
tassets.date_end_warranty='0000-00-00';
ALTER TABLE `tparameters` ADD `asset_warranty` INT(1) NOT NULL AFTER `asset`;
UPDATE `tparameters` SET `asset_warranty`='0' WHERE id=1;

-- update GestSup version number
UPDATE tparameters SET version="3.1.13";

-- avoid delete default value problem
UPDATE tplaces SET id='0' WHERE name='Aucun';
UPDATE tincidents SET place='0' WHERE place='99999';
ALTER TABLE `tincidents` CHANGE `place` `place` INT(5) NULL DEFAULT NULL;

-- default value for new version of queries
INSERT INTO `tsubcat` (`cat`, `name`) VALUES ('0', 'Aucune');
UPDATE `tsubcat` SET id='0' WHERE name='Aucune';

-- default value for new version of queries
INSERT INTO `tcategory` (`id`, `name`) VALUES (NULL, 'Aucune');
UPDATE `tcategory` SET id='0' WHERE name='Aucune';

ALTER TABLE `tparameters` CHANGE `ldap_url` `ldap_url` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

UPDATE `tusers` SET `lastname` = 'Aucun' WHERE `tusers`.`login` = 'aucun';

-- update GestSup version number
UPDATE tparameters SET version="3.1.14";

-- remove space after the name
UPDATE `tstates` SET `name` = 'En cours' WHERE `tstates`.`id` = 2;

-- default value to type
INSERT INTO `ttypes` (`id`, `name`) VALUES (NULL, 'Aucun');
UPDATE ttypes SET id='0' WHERE name='Aucun';

INSERT INTO `tpriority` (`id`, `number`, `name`, `color`) VALUES (NULL, '0', 'Aucune', '#FFFFFF');
UPDATE `tpriority` SET `id` = '0' WHERE `tpriority`.`name` = 'Aucune';

-- fill current service data in tincidents from tusers 
UPDATE tincidents,tusers SET tincidents.u_service=tusers.service WHERE tincidents.user=tusers.id AND tincidents.u_service='0';

ALTER TABLE `tusers` ADD `language` VARCHAR(10) NOT NULL DEFAULT 'fr_FR' AFTER `limit_ticket_date_start`;

ALTER TABLE `trights` ADD `ticket_place` INT(1) NOT NULL COMMENT 'Modification du lieu' AFTER `ticket_cat_actions`;
UPDATE `trights` SET `ticket_place`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `side_your_tech_group` INT(1) NOT NULL COMMENT 'Affiche les tickets associés à un groupe de technicien dans lequel vous êtes présent.' AFTER `side_your_meta`;

-- update GestSup version number
UPDATE tparameters SET version="3.1.15";

-- new thread for switch states
ALTER TABLE `tthreads` ADD `state` INT(1) NOT NULL AFTER `user`;

-- new field for global ping check
ALTER TABLE `tassets` ADD `date_last_ping` DATE NOT NULL AFTER `date_end_warranty`;

-- update name error in right description table
ALTER TABLE `trights` CHANGE `task_checkbox` `task_checkbox` INT(1) NOT NULL COMMENT 'Autorise les actions sur la sélection de plusieurs tickets, dans la liste des tickets';
ALTER TABLE `trights` CHANGE `side_your_meta` `side_your_meta` INT(1) NOT NULL COMMENT 'Affiche le meta état à traiter personnel';
ALTER TABLE `trights` CHANGE `side_all_meta` `side_all_meta` INT(1) NOT NULL COMMENT 'Affiche le meta état à traiter pour tous les techniciens';
ALTER TABLE `trights` CHANGE `side_view` `side_view` INT(1) NOT NULL COMMENT 'Affiche les vues personnelles';
ALTER TABLE `trights` CHANGE `ticket_next` `ticket_next` INT(1) NOT NULL COMMENT 'Affiche les flèches ticket suivant et précédent';
ALTER TABLE `trights` CHANGE `ticket_user` `ticket_user` INT(1) NOT NULL COMMENT 'Modification du demandeur';
ALTER TABLE `trights` CHANGE `ticket_state` `ticket_state` INT(1) NOT NULL COMMENT 'Modification du champ état dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_availability` `ticket_availability` INT(1) NOT NULL COMMENT 'Modification de la partie disponibilité';
ALTER TABLE `trights` CHANGE `ticket_close` `ticket_close` INT(1) NOT NULL COMMENT 'Affiche le bouton de clôture dans le ticket';
UPDATE `tassets_state` SET `description` = 'Équipement en stock' WHERE `tassets_state`.`name` = 'Stock';
UPDATE `tassets_state` SET `description` = 'Équipement installé en production' WHERE `tassets_state`.`name` = 'Installé';
UPDATE `tassets_state` SET `description` = 'Équipement de coté' WHERE `tassets_state`.`name` = 'Standbye';
UPDATE `tassets_state` SET `description` = 'Équipement recyclé, jeté' WHERE `tassets_state`.`name` = 'Recyclé';

-- update 3.1.16
UPDATE tparameters SET version="3.1.16";

ALTER TABLE `trights` ADD `procedure_modify` INT(1) NOT NULL COMMENT 'Modification des procédures' AFTER `procedure`;
UPDATE `trights` SET `procedure_modify`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `dashboard_col_criticality` INT(1) NOT NULL COMMENT 'Affiche la colonne criticité dans la liste des tickets.' AFTER `admin_user_view`;
UPDATE `trights` SET `dashboard_col_criticality`='2';

ALTER TABLE `trights` ADD `dashboard_col_type` INT(1) NOT NULL COMMENT 'Affiche la colonne type dans la liste des tickets.' AFTER `admin_user_view`;
UPDATE `trights` SET `dashboard_col_type`='0';

-- fix invisible ticket from API 
ALTER TABLE `tincidents` CHANGE `place` `place` INT(5) NOT NULL;

-- update asset name
ALTER TABLE `trights` CHANGE `asset` `asset` INT(1) NOT NULL COMMENT 'Affiche le menu équipement';
ALTER TABLE `trights` CHANGE `asset_list_department_only` `asset_list_department_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les équipements du service auquel est rattaché l\'utilisateur.';
ALTER TABLE `trights` CHANGE `asset_list_view_only` `asset_list_view_only` INT(1) NOT NULL COMMENT 'Affiche uniquement la liste des équipements, sans droit d\'éditer une fiche.';
ALTER TABLE `trights` CHANGE `side_asset_create` `side_asset_create` INT(1) NOT NULL COMMENT 'Affiche le bouton ajouter équipement';
ALTER TABLE `trights` CHANGE `side_asset_all_state` `side_asset_all_state` INT(11) NOT NULL COMMENT 'Affiche tous les états des équipements dans le menu de gauche';

-- update 3.1.17
UPDATE tparameters SET version="3.1.17";

ALTER TABLE `tthreads` ADD `private` BOOLEAN NOT NULL DEFAULT FALSE;

ALTER TABLE `trights` ADD `ticket_thread_private` INT(1) NOT NULL COMMENT 'Autorise à passer le message en privé' AFTER `ticket_thread_post`;
UPDATE `trights` SET `ticket_thread_private`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `asset_delete` INT(1) NOT NULL COMMENT 'Droit de suppression des équipements' AFTER `asset`;
UPDATE `trights` SET `asset_delete`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `procedure_add` INT(1) NOT NULL COMMENT 'Droit d\'ajouter des procédures' AFTER `procedure`;
UPDATE `trights` SET `procedure_add`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `procedure_delete` INT(1) NOT NULL COMMENT 'Droit de supprimer des procédures' AFTER `procedure_add`;
UPDATE `trights` SET `procedure_delete`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `ticket_thread_private_button` INT(1) NOT NULL COMMENT 'Affiche un bouton pour ajouter un message en privé' AFTER `ticket_thread_private`;

ALTER TABLE `trights` CHANGE `admin_user_profile` `admin_user_profile` INT(1) NOT NULL COMMENT 'Droit de modification de profil des utilisateurs';
UPDATE `tpriority` SET `color` = '#B0B0B0' WHERE `tpriority`.`id` = 0;

ALTER TABLE `trights` CHANGE `ticket_time_hope` `ticket_time_hope` INT(1) NOT NULL COMMENT 'Modification du temps estimé passé par ticket';

ALTER TABLE `tparameters` ADD `asset_ip` INT(1) NOT NULL AFTER `asset`;
UPDATE `tparameters` SET `asset_ip`='1' WHERE id=1;

-- update 3.1.18
UPDATE tparameters SET version="3.1.18";
-- add country field on company table
ALTER TABLE `tcompany` ADD `country` VARCHAR(100) NOT NULL AFTER `city`;

ALTER TABLE `trights` ADD `dashboard_col_category` INT(1) NOT NULL COMMENT 'Affiche la colonne catégorie dans la liste des tickets' AFTER `dashboard_col_type`;
UPDATE `trights` SET `dashboard_col_category`='2';
ALTER TABLE `trights` ADD `dashboard_col_subcat` INT(1) NOT NULL COMMENT 'Affiche la colonne sous-catégorie dans la liste des tickets' AFTER `dashboard_col_category`;
UPDATE `trights` SET `dashboard_col_subcat`='2';
ALTER TABLE `trights` ADD `dashboard_col_company` INT(1) NOT NULL COMMENT 'Affiche la colonne société dans la liste des tickets' AFTER `admin_user_view`;
UPDATE `trights` SET `dashboard_col_company`='0';
ALTER TABLE `tcompany` ADD `disable` INT(1) NOT NULL AFTER `limit_ticket_date_start`;
ALTER TABLE `trights` ADD `dashboard_col_date_create_hour` INT(1) NOT NULL COMMENT 'Affiche l\'heure de création du ticket dans la colonne date de création, sur la liste des tickets' AFTER `dashboard_col_date_create`;
UPDATE `trights` SET `dashboard_col_date_create_hour`='0';
ALTER TABLE `trights` ADD `ticket_user_company` INT(1) NOT NULL COMMENT 'Affiche le nom de la société de l\'utilisateur dans la liste des utilisateurs sur un ticket' AFTER `ticket_user_actions`;
UPDATE `trights` SET `ticket_user_company`='0';
ALTER TABLE `tparameters` ADD `imap_blacklist` VARCHAR(250) NOT NULL AFTER `imap_inbox`;
UPDATE `tparameters` SET `imap_blacklist`='';
ALTER TABLE `tparameters` ADD `imap_post_treatment` VARCHAR(100) NOT NULL AFTER `imap_blacklist`;
ALTER TABLE `tparameters` ADD `imap_post_treatment_folder` VARCHAR(100) NOT NULL AFTER `imap_post_treatment`;
ALTER TABLE `tusers` CHANGE `default_ticket_state` `default_ticket_state` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tparameters` ADD `mail_order` INT(1) NOT NULL AFTER `mail_link`;
UPDATE `tparameters` SET `mail_order`='0';

-- update 3.1.19
UPDATE tparameters SET version="3.1.19";
-- correct words errors
ALTER TABLE `trights` CHANGE `dashboard_col_date_hope` `dashboard_col_date_hope` INT(1) NOT NULL COMMENT 'Affiche la colonne date de résolution estimée dans la liste des tickets.';
ALTER TABLE `trights` CHANGE `ticket_date_hope_disp` `ticket_date_hope_disp` INT(1) NOT NULL COMMENT 'Affiche le champ date de résolution estimée dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_date_hope_mandatory` `ticket_date_hope_mandatory` INT(1) NOT NULL COMMENT 'Oblige la saisie du champ date de résolution estimée';
ALTER TABLE `trights` CHANGE `asset_list_department_only` `asset_list_department_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les équipements du service auquel est rattaché l\'utilisateur';
ALTER TABLE `trights` CHANGE `asset_list_view_only` `asset_list_view_only` INT(1) NOT NULL COMMENT 'Affiche uniquement la liste des équipements, sans droit d\'éditer une fiche';
ALTER TABLE `trights` CHANGE `dashboard_col_criticality` `dashboard_col_criticality` INT(1) NOT NULL COMMENT 'Affiche la colonne criticité dans la liste des tickets';
ALTER TABLE `trights` CHANGE `dashboard_col_priority` `dashboard_col_priority` INT(1) NOT NULL COMMENT 'Affiche la colonne priorité dans la liste des tickets';
ALTER TABLE `trights` CHANGE `dashboard_col_date_create` `dashboard_col_date_create` INT(1) NOT NULL COMMENT 'Affiche la colonne date de création dans la liste des tickets';
ALTER TABLE `trights` CHANGE `dashboard_col_type` `dashboard_col_type` INT(1) NOT NULL COMMENT 'Affiche la colonne type dans la liste des tickets';
ALTER TABLE `trights` CHANGE `dashboard_col_date_hope` `dashboard_col_date_hope` INT(1) NOT NULL COMMENT 'Affiche la colonne date de résolution estimée dans la liste des tickets';
ALTER TABLE `trights` CHANGE `dashboard_col_date_res` `dashboard_col_date_res` INT(1) NOT NULL COMMENT 'Affiche la colonne date de résolution dans la liste des tickets';
ALTER TABLE `trights` CHANGE `side_your_tech_group` `side_your_tech_group` INT(1) NOT NULL COMMENT 'Affiche les tickets associés à un groupe de technicien dans lequel vous êtes présent';
ALTER TABLE `trights` CHANGE `side_your_not_attribute` `side_your_not_attribute` INT(1) NOT NULL COMMENT 'Affiche vos demande non attribuées';
ALTER TABLE `trights` CHANGE `side_your` `side_your` INT(1) NOT NULL COMMENT 'Affiche la section vos tickets';
ALTER TABLE `trights` CHANGE `side_your_not_read` `side_your_not_read` INT(1) NOT NULL COMMENT 'Affiche vos tickets non lus';
ALTER TABLE `trights` CHANGE `side_your_not_attribute` `side_your_not_attribute` INT(1) NOT NULL COMMENT 'Affiche les tickets non attribués';
ALTER TABLE `trights` CHANGE `side_all` `side_all` INT(1) NOT NULL COMMENT 'Affiche la section tous les tickets';
ALTER TABLE `trights` CHANGE `side_all_wait` `side_all_wait` INT(1) NOT NULL COMMENT 'Affiche la vue nouveaux tickets dans tous les tickets';
-- multi iface role
CREATE TABLE `tassets_iface_role` (
  `id` int(4) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnodDB DEFAULT CHARSET=latin1;
ALTER TABLE `tassets_iface_role` ADD PRIMARY KEY (`id`);
ALTER TABLE `tassets_iface_role` MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
INSERT INTO `tassets_iface_role` (`id`, `name`) VALUES (NULL, 'LAN'), (NULL, 'WIFI');
ALTER TABLE `tassets_iface_role` ADD `disable` INT(1) NOT NULL AFTER `name`;
-- multi iface
CREATE TABLE `tassets_iface` (
  `id` int(10) NOT NULL,
  `role_id` int(5) NOT NULL,
  `asset_id` int(10) NOT NULL,
  `netbios` varchar(200) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `mac` varchar(20) NOT NULL
) ENGINE=InnodDB DEFAULT CHARSET=latin1;
ALTER TABLE `tassets_iface` ADD PRIMARY KEY (`id`);
ALTER TABLE `tassets_iface` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tassets_iface` ADD `disable` INT(1) NOT NULL AFTER `mac`;
-- current iface LAN conversion
INSERT INTO `tassets_iface` (`role_id`,`asset_id`,`netbios`,`ip`,`mac`) SELECT 1 as role_id, id, netbios, ip, mac_lan FROM tassets WHERE ip!='';
-- current iface WIFI conversion
INSERT INTO `tassets_iface` (`role_id`,`asset_id`,`netbios`,`ip`,`mac`) SELECT 2, id, netbios, ip2, mac_wlan FROM tassets WHERE ip2!='';
-- block ip search
ALTER TABLE `tassets_state` ADD `block_ip_search` INT(1) NOT NULL AFTER `description`;
UPDATE tassets_state SET block_ip_search='1' WHERE id='2';
-- location
CREATE TABLE `tassets_location` (
  `id` int(5) NOT NULL,
  `name` varchar(200) NOT NULL,
  `disable` int(1) NOT NULL
) ENGINE=InnodDB DEFAULT CHARSET=latin1;
ALTER TABLE `tassets_location` ADD PRIMARY KEY (`id`);
ALTER TABLE `tassets_location` MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
INSERT INTO `tassets_location` (`id`, `name`, `disable`) VALUES (NULL, 'Aucune', '0');
UPDATE `tassets_location` SET `id` = '0' WHERE `tassets_location`.`name` = 'Aucune';
ALTER TABLE `tassets` ADD `location` INT(5) NOT NULL AFTER `date_last_ping`;
ALTER TABLE `tassets_type` ADD `virtualization` INT(1) NOT NULL AFTER `name`;
ALTER TABLE `tassets` ADD `virtualization` INT(1) NOT NULL AFTER `maintenance`;
ALTER TABLE `trights` ADD `asset_virtualization_disp` INT(1) NOT NULL AFTER `asset_delete`;
ALTER TABLE `trights` CHANGE `asset_virtualization_disp` `asset_virtualization_disp` INT(1) NOT NULL COMMENT 'Affiche le champ équipement virtuel';
ALTER TABLE `trights` ADD `asset_list_col_location` INT(1) NOT NULL COMMENT 'Affiche la colonne localisation dans la liste des tickets' AFTER `asset_list_view_only`;
ALTER TABLE `trights` ADD `asset_location_disp` INT(1) NOT NULL COMMENT 'Affiche le champ localisation sur un équipement' AFTER `asset_virtualization_disp`;
-- add mail parameters disable certificat check
ALTER TABLE `tparameters` ADD `mail_ssl_check` INT(1) NOT NULL AFTER `mail_port`;
UPDATE `tparameters` SET `mail_ssl_check` = '1' WHERE `tparameters`.`id` = 1;
-- indexes optimizations
ALTER TABLE `tthreads` ADD INDEX(`ticket`); 
ALTER TABLE `tassets_iface` ADD INDEX(`asset_id`);
ALTER TABLE `tincidents` ADD INDEX(`state`);
ALTER TABLE `tincidents` ADD INDEX(`technician`);
ALTER TABLE `tincidents` ADD INDEX(`user`);

-- 3.1.20
UPDATE tparameters SET version="3.1.20";
-- agencies
ALTER TABLE `tparameters` ADD `user_agency` INT(1) NOT NULL AFTER `user_company_view`;
CREATE TABLE IF NOT EXISTS `tagencies` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `disable` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `tagencies` ADD `ldap_guid` VARCHAR(50) NOT NULL AFTER `name`;
ALTER TABLE `tagencies` ADD `mail` VARCHAR(100) NOT NULL AFTER `name`;
INSERT INTO `tagencies` (`id`, `name`, `disable`) VALUES (NULL, 'Aucune', '0');
UPDATE `tagencies` SET `id` = '0' WHERE `tagencies`.`id` = 1;
ALTER TABLE `tincidents` ADD `u_agency` INT(5) NOT NULL AFTER `u_service`;
-- create agency association table 
CREATE TABLE IF NOT EXISTS `tusers_agencies` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user_id` int(5) NOT NULL,
  `agency_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- create service association table 
CREATE TABLE IF NOT EXISTS `tusers_services` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user_id` int(5) NOT NULL,
  `service_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- migration data from multi service mode
INSERT INTO `tusers_services`(`user_id`, `service_id`) SELECT id,service from tusers WHERE service!='0';
-- ldap group parameters
ALTER TABLE `tparameters` ADD `ldap_service` INT(1) NOT NULL AFTER `ldap_type`;
ALTER TABLE `tparameters` ADD `ldap_agency` INT(1) NOT NULL AFTER `ldap_service`;
ALTER TABLE `tparameters` ADD `ldap_service_url` VARCHAR(500) NOT NULL AFTER `ldap_service`;
ALTER TABLE `tparameters` ADD `ldap_agency_url` VARCHAR(500) NOT NULL AFTER `ldap_agency`;
ALTER TABLE `tservices` ADD `ldap_guid` VARCHAR(50) NOT NULL AFTER `name`;
ALTER TABLE `tusers` ADD `ldap_guid` VARCHAR(50) NOT NULL AFTER `language`;
-- right service profil modification
ALTER TABLE `trights` ADD `user_profil_service` INT(1) NOT NULL COMMENT 'Modification du service sur la fiche de l\'utilisateur' AFTER `user_profil_company`;
UPDATE `trights` SET `user_profil_service`='2' WHERE id='1' OR id='4' OR id='5';
-- right to restrict ticket by service(s)
ALTER TABLE `tparameters` ADD `user_limit_service` INT(1) NOT NULL AFTER `user_agency`;
ALTER TABLE `trights` ADD `dashboard_service_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les tickets du ou des services auquel est rattaché l\'utilisateur' AFTER `admin_user_view`;
-- category and subcat restriction by service
ALTER TABLE `tcategory` ADD `service` INT(5) NOT NULL AFTER `name`;
-- criticality list restriction by service
ALTER TABLE `tcriticality` ADD `service` INT(5) NOT NULL AFTER `color`;
-- right for new fields in ticket
ALTER TABLE `trights` ADD `ticket_service` INT(1) NOT NULL COMMENT 'Modification du service dans le ticket' AFTER `ticket_type_disp`;
ALTER TABLE `trights` ADD `ticket_service_disp` INT(1) NOT NULL COMMENT 'Affiche le champ service dans le ticket' AFTER `ticket_service`;
ALTER TABLE `trights` ADD `ticket_service_mandatory` INT NOT NULL COMMENT 'Oblige la saisie du champ service' AFTER `ticket_service_disp`;
INSERT INTO `tservices` (`id`, `name`, `ldap_guid`, `disable`) VALUES (NULL, 'Aucun', '', '0');
UPDATE `tservices` SET `id` = '0' WHERE `tservices`.`name` = 'Aucun';
ALTER TABLE `trights` ADD `ticket_new_service` INT(1) NOT NULL COMMENT 'Modification du service pour les nouveaux tickets' AFTER `ticket_new_type_disp`;
ALTER TABLE `trights` ADD `ticket_new_service_disp` INT(1) NOT NULL COMMENT 'Affiche le champ service pour les nouveaux tickets' AFTER `ticket_new_service`;
UPDATE `trights` SET `ticket_new_service`='2';
UPDATE `trights` SET `ticket_service`='2' WHERE id='1' OR id='4' OR id='5';
-- right to supervisor
ALTER TABLE `trights` ADD `admin_groups` INT(1) NOT NULL COMMENT 'Affiche le menu Administration > Groupes uniquement' AFTER `admin`;
ALTER TABLE `trights` ADD `admin_lists` INT(1) NOT NULL COMMENT 'Affiche le menu Administration > Listes uniquement' AFTER `admin_groups`;
ALTER TABLE `trights` ADD `admin_lists_category` INT(1) NOT NULL COMMENT 'Affiche le menu Administration > Listes > Catégories' AFTER `admin_lists`;
ALTER TABLE `trights` ADD `admin_lists_subcat` INT(1) NOT NULL COMMENT 'Affiche le menu Administration > Listes > Sous-catégories' AFTER `admin_lists_category`;
ALTER TABLE `trights` ADD `admin_lists_criticality` INT(1) NOT NULL COMMENT 'Affiche le menu Administration > Listes > Criticités' AFTER `admin_lists_subcat`;
ALTER TABLE `tgroups` ADD `service` INT(5) NOT NULL AFTER `type`;
-- modify dashboard for all services and agency modification
ALTER TABLE `trights` ADD `dashboard_col_service` INT(1) NOT NULL COMMENT 'Affiche la colonne service dans la liste des tickets' AFTER `dashboard_service_only`;
ALTER TABLE `trights` ADD `dashboard_col_agency` INT(1) NOT NULL COMMENT 'Affiche la colonne agence dans la liste des tickets' AFTER `dashboard_col_service`;
ALTER TABLE `trights` ADD `side_all_service_disp` INT(1) NOT NULL COMMENT 'Affiche tous les tickets associés aux services de l\'utilisateur connecté' AFTER `side_all_meta`;
ALTER TABLE `trights` ADD `side_all_service_edit` INT(1) NOT NULL COMMENT 'Permet de modifier tous les tickets associés aux services de l\'utilisateur connecté' AFTER `side_all_service_disp`;
ALTER TABLE `trights` ADD `side_all_agency_disp` INT(1) NOT NULL COMMENT 'Affiche tous les tickets associés aux agences de l\'utilisateur connecté' AFTER `side_all_service_edit`;
ALTER TABLE `trights` ADD `side_all_agency_edit` INT(1) NOT NULL COMMENT 'Permet de modifier tous les tickets associés aux agences de l\'utilisateur connecté' AFTER `side_all_agency_disp`;
ALTER TABLE `trights` ADD `dashboard_agency_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les tickets des agences auxquelles est rattaché l\'utilisateur' AFTER `dashboard_service_only`;
-- mailbox modification
ALTER TABLE `tparameters` ADD `imap_mailbox_service` INT(1) NOT NULL AFTER `imap_post_treatment_folder`;
CREATE TABLE IF NOT EXISTS `tparameters_imap_multi_mailbox` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `mail` varchar(250) NOT NULL,
  `service_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `tparameters_imap_multi_mailbox` ADD `password` VARCHAR(250) NOT NULL AFTER `mail`;
ALTER TABLE `trights` ADD `ticket_agency` INT(1) NOT NULL COMMENT 'Affiche le champ agence dans le ticket' AFTER `ticket_cat_actions`;
ALTER TABLE `tparameters` CHANGE `mail_from_adr` `mail_from_adr` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- rename old fields
ALTER TABLE `tusers` CHANGE `service` `service_old` INT(5) NOT NULL;
ALTER TABLE `tassets` CHANGE `ip` `ip_old` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets` CHANGE `ip2` `ip2_old` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets` CHANGE `mac_lan` `mac_lan_old` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets` CHANGE `mac_wlan` `mac_wlan_old` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- survey part
CREATE TABLE IF NOT EXISTS `tsurvey_questions` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `number` int(5) NOT NULL,
  `type` int(5) NOT NULL COMMENT '1=yes/no,2=text,3=select,4=scale',
  `text` varchar(250) NOT NULL,
  `scale` int(2) NOT NULL,
  `select_1` varchar(100) NOT NULL,
  `select_2` varchar(100) NOT NULL,
  `select_3` varchar(100) NOT NULL,
  `select_4` varchar(100) NOT NULL,
  `select_5` varchar(100) NOT NULL,
  `disable` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tsurvey_answers` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `question_id` int(5) NOT NULL,
  `answer` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `tparameters` ADD `survey` INT(1) NOT NULL AFTER `procedure`;
ALTER TABLE `tparameters` ADD `survey_ticket_state` INT(2) NOT NULL AFTER `survey`;
ALTER TABLE `tparameters` ADD `survey_auto_close_ticket` INT(1) NOT NULL AFTER `survey_ticket_state`;
ALTER TABLE `tparameters` ADD `survey_mail_text` VARCHAR(500) NOT NULL AFTER `survey`;
UPDATE `tparameters` SET `survey_mail_text`='Dans le cadre de l’amélioration de notre support merci de répondre au sondage suivant:';
ALTER TABLE `ttoken` ADD `action` VARCHAR(50) NOT NULL AFTER `token`;
ALTER TABLE `ttoken` ADD `ticket_id` INT(5) NOT NULL AFTER `action`;

-- update GestSup version number
UPDATE tparameters SET version="3.1.21";

-- scan net asset
ALTER TABLE `tassets_network` ADD `scan` INT(1) NOT NULL AFTER `netmask`;
ALTER TABLE `tassets` ADD `net_scan` INT(1) NOT NULL DEFAULT '1' AFTER `virtualization`;

-- bug fix disable asset where asset is disable
UPDATE tassets_iface SET disable='1' WHERE asset_id IN (SELECT id FROM tassets WHERE disable='1');

ALTER TABLE `trights` ADD `asset_net_scan` INT(1) NOT NULL COMMENT 'Affiche le bouton de désactivation du scan réseau pour cet équipement' AFTER `asset`;

ALTER TABLE `tparameters` ADD `asset_vnc_link` INT(1) NOT NULL AFTER `asset_warranty`;
ALTER TABLE `tassets` ADD `discover_net_scan` INT(1) NOT NULL AFTER `net_scan`;
ALTER TABLE `tassets` ADD `discover_import_csv` INT(1) NOT NULL AFTER `discover_net_scan`;
-- add mandatory rights
ALTER TABLE `trights` ADD `ticket_tech_mandatory` INT NOT NULL COMMENT 'Oblige la saisie du champ technicien' AFTER `ticket_tech_disp`;
ALTER TABLE `trights` ADD `ticket_title_mandatory` INT(1) NOT NULL COMMENT 'Oblige la saisie du champ titre' AFTER `ticket_title_disp`;
ALTER TABLE `trights` ADD `ticket_description_mandatory` INT(1) NOT NULL COMMENT 'Oblige la saisie de la description' AFTER `ticket_description_disp`;

-- mail end text
ALTER TABLE `tparameters` ADD `mail_txt_end` VARCHAR(500) NOT NULL AFTER `mail_txt`;