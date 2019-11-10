-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.9";

ALTER TABLE `tusers` CHANGE `mail` `mail` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tparameters` ADD `user_limit_ticket` INT(1) NOT NULL DEFAULT '0' AFTER `user_register`;
ALTER TABLE `tusers` ADD `limit_ticket_number` INT(5) NOT NULL AFTER `dashboard_ticket_order`;
ALTER TABLE `tusers` ADD `limit_ticket_days` INT(5) NOT NULL AFTER `limit_ticket_number`;
ALTER TABLE `tusers` ADD `limit_ticket_date_start` DATE NOT NULL AFTER `limit_ticket_days`;