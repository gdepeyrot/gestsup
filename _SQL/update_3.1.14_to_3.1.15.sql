-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.15";

-- new thread for switch states
ALTER TABLE `tthreads` ADD `state` INT(1) NOT NULL AFTER `user`;

-- new field for global ping check
ALTER TABLE `tassets` ADD `date_last_ping` DATE NOT NULL AFTER `date_end_warranty`;

-- update name error in right description table
ALTER TABLE `trights` CHANGE `task_checkbox` `task_checkbox` INT(1) NOT NULL COMMENT 'Autorise les actions sur la sélection de plusieurs tickets, dans la liste des tickets';
ALTER TABLE `trights` CHANGE `side_your_meta` `side_your_meta` INT(1) NOT NULL COMMENT 'Affiche le meta état à traiter personnel';
ALTER TABLE `trights` CHANGE `side_all_meta` `side_all_meta` INT(1) NOT NULL COMMENT 'Affiche le meta état à traiter pour tous les techniciens';
ALTER TABLE `trights` CHANGE `side_view` `side_view` INT(1) NOT NULL COMMENT 'Affiche les vues personnelles';
ALTER TABLE `trights` CHANGE `ticket_next` `ticket_next` INT(1) NOT NULL COMMENT 'Affiche les flèches ticket suivant et précédent';
ALTER TABLE `trights` CHANGE `ticket_user` `ticket_user` INT(1) NOT NULL COMMENT 'Modification du demandeur';
ALTER TABLE `trights` CHANGE `ticket_state` `ticket_state` INT(1) NOT NULL COMMENT 'Modification du champ état dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_availability` `ticket_availability` INT(1) NOT NULL COMMENT 'Modification de la partie disponibilité';
ALTER TABLE `trights` CHANGE `ticket_close` `ticket_close` INT(1) NOT NULL COMMENT 'Affiche le bouton de clôture dans le ticket';
UPDATE `tassets_state` SET `description` = 'Équipement en stock' WHERE `tassets_state`.`name` = 'Stock';
UPDATE `tassets_state` SET `description` = 'Équipement installé en production' WHERE `tassets_state`.`name` = 'Installé';
UPDATE `tassets_state` SET `description` = 'Équipement de coté' WHERE `tassets_state`.`name` = 'Standbye';
UPDATE `tassets_state` SET `description` = 'Équipement recyclé, jeté' WHERE `tassets_state`.`name` = 'Recyclé';