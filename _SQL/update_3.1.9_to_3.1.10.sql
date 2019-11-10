-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
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