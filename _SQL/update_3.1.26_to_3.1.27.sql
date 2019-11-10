-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE tparameters SET version="3.1.27";

-- add new right to lock user agency switch
ALTER TABLE `trights` ADD `user_profil_agency` INT(1) NOT NULL COMMENT 'Modification de l\'agence sur la fiche de l\'utilisateur' AFTER `user_profil_service`;
UPDATE `trights` SET `user_profil_agency`='2' WHERE id='1' OR id='4' OR id='5';
UPDATE trights,tparameters SET trights.user_profil_agency=0 WHERE tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';
UPDATE trights,tparameters SET trights.user_profil_agency=2 WHERE (trights.profile=5) AND tparameters.server_private_key='0ab847a188a2e2ceffbf52b148d8eb0f';