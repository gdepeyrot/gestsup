-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;
-- update GestSup version number
UPDATE tparameters SET version="3.1.22";

-- add right display admin in tech list on ticket
ALTER TABLE `trights` ADD `ticket_tech_admin` INT(1) NOT NULL COMMENT 'Affiche les administrateurs dans la liste des techniciens sur un ticket.' AFTER `ticket_tech_mandatory`;
UPDATE `trights` SET `ticket_tech_admin`=2;

ALTER TABLE `trights` ADD `ticket_tech_super` INT(1) NOT NULL COMMENT 'Affiche les superviseurs dans la liste des techniciens sur un ticket' AFTER `ticket_tech_admin`;
UPDATE trights,tparameters SET trights.ticket_tech_super=2 WHERE tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- new parameters to enable or disable reply
ALTER TABLE `tparameters` ADD `imap_reply` INT(1) NOT NULL AFTER `imap_password`;
UPDATE `tparameters` SET `imap_reply`=1;

-- add more characters
ALTER TABLE `tservices` CHANGE `name` `name` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tusers` CHANGE `function` `function` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- add new ldap parameter
ALTER TABLE `tparameters` ADD `ldap_disable_user` INT(1) NOT NULL AFTER `ldap_password`;
UPDATE `tparameters` SET `ldap_disable_user`=1 WHERE server_private_key!='5Iss/w7u6mtfwxFvdiDRAwEEM+A3W1fqyzah8Hdh';

-- add new ticket parameter
ALTER TABLE `tparameters` ADD `ticket_default_state` INT(1) NOT NULL AFTER `ticket_type`;
UPDATE `tparameters` SET `ticket_default_state`=5;
UPDATE `tparameters` SET `ticket_default_state`=1 WHERE server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- add new ticket parameter
ALTER TABLE `tparameters` ADD `imap_ssl_check` INT(1) NOT NULL AFTER `imap_port`;
UPDATE `tparameters` SET `imap_ssl_check`=0;
UPDATE `tparameters` SET `imap_port`='993/imap/ssl' WHERE imap_port='993/imap/ssl/novalidate-cert';

ALTER TABLE `tassets` CHANGE `socket` `socket` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `trights` ADD `ticket_new_asset_disp` INT(1) NOT NULL COMMENT 'Affiche le champ équipement pour les nouveaux tickets' AFTER `ticket_new_tech_disp`;
UPDATE trights,tparameters SET trights.ticket_new_asset_disp=2 WHERE tparameters.server_private_key='v4fLxJnsGKHUUJ+awXZ3riOKrzvDez3qxMv7dcva';
ALTER TABLE `trights` ADD `ticket_asset_disp` INT(1) NOT NULL COMMENT 'Affiche le champ équipement dans le ticket' AFTER `ticket_tech_super`;
UPDATE trights,tparameters SET trights.ticket_asset_disp=2 WHERE tparameters.server_private_key='v4fLxJnsGKHUUJ+awXZ3riOKrzvDez3qxMv7dcva';
ALTER TABLE `trights` ADD `ticket_asset` INT(1) NOT NULL COMMENT 'Modification de l\'équipement sur un ticket' AFTER `ticket_tech_super`;
UPDATE trights,tparameters SET trights.ticket_asset=2 WHERE tparameters.server_private_key='v4fLxJnsGKHUUJ+awXZ3riOKrzvDez3qxMv7dcva';
ALTER TABLE `trights` ADD `ticket_asset_mandatory` INT(1) NOT NULL COMMENT 'Oblige la saisie du champ équipement' AFTER `ticket_asset_disp`;
UPDATE trights,tparameters SET trights.ticket_asset_mandatory=2 WHERE tparameters.server_private_key='v4fLxJnsGKHUUJ+awXZ3riOKrzvDez3qxMv7dcva';
ALTER TABLE `trights` ADD `dashboard_col_asset` INT(1) NOT NULL COMMENT 'Affiche la colonne équipement dans la liste des tickets' AFTER `dashboard_col_subcat`;
UPDATE trights,tparameters SET trights.dashboard_col_asset=2 WHERE tparameters.server_private_key='v4fLxJnsGKHUUJ+awXZ3riOKrzvDez3qxMv7dcva';

-- add new col in tincidents table to association ticket with asset
ALTER TABLE `tincidents` ADD `asset_id` INT(8) NOT NULL AFTER `place`;

-- add default asset to ticket search engine join
INSERT INTO tassets (id,netbios,disable) VALUES ('0','Aucun','1');