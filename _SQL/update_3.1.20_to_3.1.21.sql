-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;
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