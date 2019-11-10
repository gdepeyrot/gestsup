-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE `tparameters` SET `version`="3.1.30";

UPDATE `tcriticality` SET `color`='#B0B0B0' WHERE `color`='' AND `id`=0;

ALTER TABLE `tparameters` ADD `timeout` INT(5) NOT NULL AFTER `update_channel`;

ALTER TABLE `tparameters` ADD `server_timezone` VARCHAR(100) NOT NULL AFTER `server_private_key`;
UPDATE `tparameters` SET `server_timezone`='Indian/Reunion' WHERE `server_private_key`='yhUGiKRNRVgDYk41Euh6i7Vn11OIUQngGl2Ahjk4';

ALTER TABLE `tparameters` DROP `lign_yellow`, DROP `lign_orange`;
ALTER TABLE `tparameters` ADD `mail_auto_user_newticket` INT(1) NOT NULL AFTER `mail_auto_user_modify`;