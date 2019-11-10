-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
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