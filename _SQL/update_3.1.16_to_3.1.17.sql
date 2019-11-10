-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.17";

ALTER TABLE `tthreads` ADD `private` BOOLEAN NOT NULL DEFAULT FALSE;

ALTER TABLE `trights` ADD `ticket_thread_private` INT(1) NOT NULL COMMENT 'Autorise à passer le message en privé' AFTER `ticket_thread_post`;
UPDATE `trights` SET `ticket_thread_private`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `asset_delete` INT(1) NOT NULL COMMENT 'Droit de suppression des équipements' AFTER `asset`;
UPDATE `trights` SET `asset_delete`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `procedure_add` INT(1) NOT NULL COMMENT 'Droit d\'ajouter des procédures' AFTER `procedure`;
UPDATE `trights` SET `procedure_add`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `procedure_delete` INT(1) NOT NULL COMMENT 'Droit de supprimer des procédures' AFTER `procedure_add`;
UPDATE `trights` SET `procedure_delete`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `ticket_thread_private_button` INT(1) NOT NULL COMMENT 'Affiche un bouton pour ajouter un message en privé' AFTER `ticket_thread_private`;

ALTER TABLE `trights` CHANGE `admin_user_profile` `admin_user_profile` INT(1) NOT NULL COMMENT 'Droit de modification de profil des utilisateurs';
UPDATE `tpriority` SET `color` = '#B0B0B0' WHERE `tpriority`.`id` = 0;

ALTER TABLE `trights` CHANGE `ticket_time_hope` `ticket_time_hope` INT(1) NOT NULL COMMENT 'Modification du temps estimé passé par ticket';

ALTER TABLE `tparameters` ADD `asset_ip` INT(1) NOT NULL AFTER `asset`;
UPDATE `tparameters` SET `asset_ip`='1' WHERE id=1;