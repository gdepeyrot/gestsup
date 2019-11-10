-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.11";

ALTER TABLE `tcompany` CHANGE `name` `name` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- avoid invisible ticket with join at 0 value on tusers table
INSERT INTO `tusers` (`id`, `login`, `password`, `salt`, `firstname`, `lastname`, `profile`, `mail`, `phone`, `fax`, `function`, `service`, `company`, `address1`, `address2`, `zip`, `city`, `custom1`, `custom2`, `disable`, `chgpwd`, `last_login`, `skin`, `default_ticket_state`, `dashboard_ticket_order`, `limit_ticket_number`, `limit_ticket_days`, `limit_ticket_date_start`) VALUES ('0', 'aucun', '', '', '', '', '2', '', '', '', '', '0', '0', '', '', '', '', '', '', '1', '0', '2016-10-21 00:00:00', '', '', '', '0', '0', '2016-10-21');
UPDATE `tusers` SET `id` = '0' WHERE `tusers`.`login` = 'aucun';
UPDATE `tusers` SET `disable` = '1' WHERE `tusers`.`login` = 'aucun';