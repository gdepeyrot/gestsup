-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;
-- update GestSup version number
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