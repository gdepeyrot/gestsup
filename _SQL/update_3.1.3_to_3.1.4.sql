-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.4";

ALTER TABLE `tparameters` ADD `mail_auto_user_modify` INT(1) NOT NULL DEFAULT '0' AFTER `mail_auto`;
ALTER TABLE `tparameters` ADD `mail_auto_tech_modify` INT(1) NOT NULL DEFAULT '0' AFTER `mail_auto_user_modify`;