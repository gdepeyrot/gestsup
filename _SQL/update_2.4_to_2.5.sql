-- SQL Update for Gestsup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.5";

ALTER TABLE  `tincidents` ADD  `img4` VARCHAR( 100 ) NOT NULL AFTER  `img3`;
ALTER TABLE  `tincidents` ADD  `img5` VARCHAR( 100 ) NOT NULL AFTER  `img4`;
ALTER TABLE  `trights` ADD  `ticket_delete` INT( 1 ) NOT NULL AFTER  `side_view`;
UPDATE `trights` SET  `ticket_delete` =  '2' WHERE  `trights`.`id` =1;
UPDATE `trights` SET  `ticket_delete` =  '2' WHERE  `trights`.`id` =5;
ALTER TABLE  `trights` ADD  `side` INT( 1 ) NOT NULL AFTER  `userbar`;
UPDATE `trights` SET  `side` =  '2' ;
ALTER TABLE  `tstates` ADD  `description` VARCHAR( 200 ) NOT NULL AFTER  `name`;
UPDATE  `tstates` SET  `description` =  'Tickets en attente de prise en charge par un technicien' WHERE  `tstates`.`id` =1;
UPDATE  `tstates` SET  `description` =  'Tickets en cours de traitement' WHERE  `tstates`.`id` =2;
UPDATE  `tstates` SET  `description` =  'Tickets résolus' WHERE  `tstates`.`id` =3;
UPDATE  `tstates` SET  `description` =  'Tickets rejetés' WHERE  `tstates`.`id` =4;
UPDATE  `tstates` SET  `description` =  'Tickets pas encore associé à un technicien' WHERE  `tstates`.`id` =5;
UPDATE  `tstates` SET  `description` =  'Ticket en attente d''éléments de la part du demandeur' WHERE  `tstates`.`id` =6;
ALTER TABLE  `tparameters` ADD  `auto_refresh` INT( 5 ) NOT NULL AFTER  `time_display_msg`;


