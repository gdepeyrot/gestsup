-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE tparameters SET version="3.1.29";

ALTER TABLE `tparameters` CHANGE `version` `version` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets_model` CHANGE `image` `image` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tparameters` ADD `ldap_sso` INT(1) NOT NULL AFTER `ldap_auth`;
ALTER TABLE `tparameters` CHANGE `mail_cc` `mail_cc` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;