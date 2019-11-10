-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE tparameters SET version="3.1.26";

-- add right to disable limit type by service
ALTER TABLE `trights` ADD `ticket_type_service_limit` INT(1) NOT NULL COMMENT 'Affiche uniquement les types associés au service' AFTER `ticket_type_disp`;
UPDATE `trights` SET `ticket_type_service_limit`=2;
UPDATE trights,tparameters SET trights.ticket_type_service_limit=0 WHERE tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- add right to disable to enable asset company limit
ALTER TABLE `trights` ADD `asset_list_company_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les équipements de la société rattachée à l\'utilisateur' AFTER `asset_list_department_only`;
UPDATE trights,tparameters SET trights.asset_list_company_only=2 WHERE (trights.profile=1 OR trights.profile=2) AND tparameters.server_private_key='2f3ff99ce5dec06e482e02e554e23c27';

-- disable department limit to avoid company limit conflict
UPDATE trights,tparameters SET trights.asset_list_department_only=0 WHERE tparameters.server_private_key='2f3ff99ce5dec06e482e02e554e23c27';

-- disable company modification from user profile
UPDATE trights,tparameters SET trights.user_profil_company=0 WHERE (trights.profile=1 OR trights.profile=2) AND tparameters.server_private_key='2f3ff99ce5dec06e482e02e554e23c27';

-- disable asset list view only for user and poweruser
UPDATE trights,tparameters SET trights.asset_list_view_only=0 WHERE tparameters.server_private_key='2f3ff99ce5dec06e482e02e554e23c27';

-- add right to disable to enable procedure company limit
ALTER TABLE `trights` ADD `procedure_list_company_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les procédures de la société rattachée à l\'utilisateur' AFTER `procedure_modify`;
UPDATE trights,tparameters SET trights.procedure_list_company_only=2 WHERE (trights.profile=1 OR trights.profile=2) AND tparameters.server_private_key='2f3ff99ce5dec06e482e02e554e23c27';

-- add new column to procedure table to store company association
ALTER TABLE `tprocedures` ADD `company_id` INT(5) NOT NULL AFTER `file1`;

-- add right to display company field on procedure
ALTER TABLE `trights` ADD `procedure_company` INT(1) NOT NULL COMMENT 'Affiche le champ société sur une procédure' AFTER `procedure_modify`;
UPDATE trights,tparameters SET trights.procedure_company=2 WHERE (trights.profile=0 OR trights.profile=4) AND tparameters.server_private_key='2f3ff99ce5dec06e482e02e554e23c27';

ALTER TABLE `tparameters` CHANGE `ldap_url` `ldap_url` VARCHAR(2000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

UPDATE trights,tparameters SET trights.admin_lists_type=0 WHERE tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';