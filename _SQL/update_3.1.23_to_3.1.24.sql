-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE tparameters SET version="3.1.24";

-- remove old ip fields
ALTER TABLE `tassets` DROP `ip_old`;
ALTER TABLE `tassets` DROP `ip2_old`;
ALTER TABLE `tassets` DROP `mac_lan_old`;
ALTER TABLE `tassets` DROP `mac_wlan_old`;

-- space error in description
ALTER TABLE `trights` CHANGE `ticket_asset_disp` `ticket_asset_disp` INT(1) NOT NULL COMMENT 'Affiche le champ équipement dans le ticket';

-- new parameter
ALTER TABLE `tparameters` ADD `login_state` VARCHAR(10) NOT NULL AFTER `auto_refresh`;
UPDATE `tparameters` SET `login_state`=1;
UPDATE tparameters SET tparameters.login_state='all' WHERE tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- default parameters
UPDATE trights,tparameters SET trights.dashboard_col_user_service=2 WHERE trights.profile=3  AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';
UPDATE trights,tparameters SET trights.dashboard_agency_only=2 WHERE trights.profile=0  AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- add new ticket fields
ALTER TABLE `trights` ADD `ticket_sender_service_disp` INT(1) NOT NULL COMMENT 'Affiche le champ service du demandeur dans le ticket' AFTER `ticket_agency_mandatory`;
UPDATE trights,tparameters SET trights.ticket_sender_service_disp=2 WHERE tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';
ALTER TABLE `tincidents` ADD `sender_service` INT(5) NOT NULL AFTER `u_agency`;