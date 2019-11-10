-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.16";

ALTER TABLE `trights` ADD `procedure_modify` INT(1) NOT NULL COMMENT 'Modification des procédures' AFTER `procedure`;
UPDATE `trights` SET `procedure_modify`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE `trights` ADD `dashboard_col_criticality` INT(1) NOT NULL COMMENT 'Affiche la colonne criticité dans la liste des tickets.' AFTER `admin_user_view`;
UPDATE `trights` SET `dashboard_col_criticality`='2';

ALTER TABLE `trights` ADD `dashboard_col_type` INT(1) NOT NULL COMMENT 'Affiche la colonne type dans la liste des tickets.' AFTER `admin_user_view`;
UPDATE `trights` SET `dashboard_col_type`='0';

-- fix invisible ticket from API 
ALTER TABLE `tincidents` CHANGE `place` `place` INT(5) NOT NULL;

-- update asset name
ALTER TABLE `trights` CHANGE `asset` `asset` INT(1) NOT NULL COMMENT 'Affiche le menu équipement';
ALTER TABLE `trights` CHANGE `asset_list_department_only` `asset_list_department_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les équipements du service auquel est rattaché l\'utilisateur.';
ALTER TABLE `trights` CHANGE `asset_list_view_only` `asset_list_view_only` INT(1) NOT NULL COMMENT 'Affiche uniquement la liste des équipements, sans droit d\'éditer une fiche.';
ALTER TABLE `trights` CHANGE `side_asset_create` `side_asset_create` INT(1) NOT NULL COMMENT 'Affiche le bouton ajouter équipement';
ALTER TABLE `trights` CHANGE `side_asset_all_state` `side_asset_all_state` INT(11) NOT NULL COMMENT 'Affiche tous les états des équipements dans le menu de gauche';