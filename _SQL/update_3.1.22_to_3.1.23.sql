-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE tparameters SET version="3.1.23";

-- add new mandatory field
ALTER TABLE `trights` ADD `ticket_agency_mandatory` INT(1) NOT NULL COMMENT 'Oblige la saisie du champ agence' AFTER `ticket_agency`;
UPDATE trights,tparameters SET trights.ticket_agency_mandatory=2 WHERE (trights.id=2 OR trights.id=3) AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- add new col in ticket list
ALTER TABLE `trights` ADD `dashboard_col_user_service` INT(1) NOT NULL COMMENT 'Affiche la colonne service du demandeur dans la liste des tickets' AFTER `dashboard_agency_only`;
UPDATE trights,tparameters SET trights.dashboard_col_user_service=2 WHERE (trights.id=1 OR trights.id=5)  AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- add new field for tincidents to have technician read date
ALTER TABLE `tincidents` ADD `techread_date` DATETIME NOT NULL AFTER `techread`;