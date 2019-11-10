-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.14";

-- remove space after the name
UPDATE `tstates` SET `name` = 'En cours' WHERE `tstates`.`id` = 2;

-- default value to type
INSERT INTO `ttypes` (`id`, `name`) VALUES (NULL, 'Aucun');
UPDATE ttypes SET id='0' WHERE name='Aucun';

INSERT INTO `tpriority` (`id`, `number`, `name`, `color`) VALUES (NULL, '0', 'Aucune', '#FFFFFF');
UPDATE `tpriority` SET `id` = '0' WHERE `tpriority`.`name` = 'Aucune';

-- fill current service data in tincidents from tusers 
UPDATE tincidents,tusers SET tincidents.u_service=tusers.service WHERE tincidents.user=tusers.id AND tincidents.u_service='0';

ALTER TABLE `tusers` ADD `language` VARCHAR(10) NOT NULL DEFAULT 'fr_FR' AFTER `limit_ticket_date_start`;

ALTER TABLE `trights` ADD `ticket_place` INT(1) NOT NULL COMMENT 'Modification du lieu' AFTER `ticket_cat_actions`;
UPDATE `trights` SET `ticket_place`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `side_your_tech_group` INT(1) NOT NULL COMMENT 'Affiche les tickets associés à un groupe de technicien dans lequel vous êtes présent.' AFTER `side_your_meta`;