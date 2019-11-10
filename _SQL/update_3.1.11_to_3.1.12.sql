-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.12";

ALTER TABLE `tusers` CHANGE `login` `login` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets` ADD `date_end_warranty` DATE NOT NULL AFTER `date_recycle`;
ALTER TABLE `trights` CHANGE `asset_list_department_only` `asset_list_department_only` INT(1) NOT NULL COMMENT 'Affiche uniquement les matériels du service auquel est rattaché l\'utilisateur.';

-- update new date end warranty field where warranty model is defined
SET sql_mode = '';
UPDATE tassets,tassets_model SET tassets.date_end_warranty= DATE_ADD(tassets.date_stock,INTERVAL tassets_model.warranty YEAR) 
WHERE 
tassets_model.id=tassets.model AND
tassets_model.warranty NOT LIKE 0 AND
tassets.date_stock NOT LIKE '0000-00-00' AND
tassets.date_end_warranty='0000-00-00';
ALTER TABLE `tparameters` ADD `asset_warranty` INT(1) NOT NULL AFTER `asset`;
UPDATE `tparameters` SET `asset_warranty`='0' WHERE id=1;