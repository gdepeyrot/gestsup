-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- fix bug empty availability_condition_type value
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