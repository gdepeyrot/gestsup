-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.5";

UPDATE `trights` SET `ticket_delete` = '0' WHERE `trights`.`id` = 2;
UPDATE `trights` SET `ticket_delete` = '0' WHERE `trights`.`id` = 3;

ALTER TABLE `tparameters` ADD `mail_smtp_class` VARCHAR(15) NOT NULL DEFAULT 'isSMTP()' AFTER `mail_smtp`;