-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE tparameters SET version="3.1.25";

-- add new ticket fields
ALTER TABLE `trights` ADD `ticket_tech_service_lock` INT(1) NOT NULL COMMENT 'Bloque la modification du champ technicien si la limite par service est activée et qu\'il ouvre un ticket pour un autre service ' AFTER `ticket_tech_disp`;
UPDATE trights,tparameters SET trights.ticket_tech_service_lock=2 WHERE trights.profile=3 AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';
UPDATE trights,tparameters SET trights.ticket_tech_service_lock=2 WHERE trights.profile=0 AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- clean user table from old service system
ALTER TABLE `tusers` DROP COLUMN service_old;

-- new right to admin new lists
ALTER TABLE `trights` ADD `admin_lists_priority` INT(1) NOT NULL COMMENT 'Affiche le menu Administration > Listes > Priorité' AFTER `admin_lists_criticality`;
UPDATE trights,tparameters SET trights.admin_lists_priority=2 WHERE trights.profile=3 AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';
ALTER TABLE `trights` ADD `admin_lists_type` INT(1) NOT NULL COMMENT 'Affiche le menu Administration > Listes > Types des tickets' AFTER `admin_lists_priority`;
UPDATE trights,tparameters SET trights.admin_lists_type=2 WHERE trights.profile=3 AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';

-- new service col to new lists
ALTER TABLE `tpriority` ADD `service` INT(5) NOT NULL AFTER `color`;
ALTER TABLE `ttypes` ADD `service` INT(5) NOT NULL AFTER `name`;

-- add none value in criticality table
INSERT INTO `tcriticality` (`id`, `number`, `name`, `color`, `service`) VALUES (NULL, '0', 'Aucune', '', '0'); 
UPDATE `tcriticality` SET id=0 WHERE name='Aucune';