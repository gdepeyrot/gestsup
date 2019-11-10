-- SQL Update for Gestsup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.6";

ALTER TABLE  `trights` ADD  `planning` INT( 1 ) NOT NULL AFTER  `stat`;
ALTER TABLE  `tparameters` ADD  `planning` INT( 1 ) NOT NULL;
ALTER TABLE `tevents` CHANGE `date` `date_start` DATETIME NOT NULL;
ALTER TABLE `tevents` ADD `date_end` DATETIME NOT NULL AFTER `date_start`; 

-- migrate default values of tright
UPDATE  `trights` SET  `planning` =  '2' WHERE  id='1' OR id='5';

-- bug fautes
UPDATE  `tstates` SET  `description` =  'Tickets pas encore associés à un technicien' WHERE  `tstates`.`id` =5 AND `description` =  'Tickets pas encore associé à un technicien';

ALTER TABLE  `tparameters` ADD  `mail_link` INT( 1 ) NOT NULL AFTER  `mail_color_text`;
UPDATE  `tparameters` SET  `mail_link` = '0';