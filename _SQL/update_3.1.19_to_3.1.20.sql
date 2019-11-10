-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;
-- update GestSup version number
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