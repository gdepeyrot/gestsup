-- SQL Update for Gestsup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.4";

ALTER TABLE  `tincidents` ADD  `time_hope` INT( 10 ) NOT NULL AFTER  `time`;

ALTER TABLE  `tstates` ADD  `number` INT( 2 ) NOT NULL AFTER  `id`;
ALTER TABLE  `tstates` ADD  `mail_object` VARCHAR(200) NOT NULL AFTER  `number`;
UPDATE  `tstates` SET  `name` =  'Attente PEC' WHERE  `tstates`.`id` =1;
INSERT INTO `tstates` (`id`, `number`, `name`, `mail_object`) VALUES (6, 4, 'Attente Retour', 'Notification d''attente de retour ');
UPDATE  `tstates` SET  `number` =  '1' WHERE  `tstates`.`id` =5;
UPDATE  `tstates` SET  `number` =  '2' WHERE  `tstates`.`id` =1;
UPDATE  `tstates` SET  `number` =  '3' WHERE  `tstates`.`id` =2;
UPDATE  `tstates` SET  `number` =  '4' WHERE  `tstates`.`id` =6;
UPDATE  `tstates` SET  `number` =  '5' WHERE  `tstates`.`id` =3;
UPDATE  `tstates` SET  `number` =  '6' WHERE  `tstates`.`id` =4;
UPDATE  `tstates` SET  `mail_object` =  "Notification d'ouverture" WHERE  `tstates`.`id` =1;
UPDATE  `tstates` SET  `mail_object` =  "Notification" WHERE  `tstates`.`id` =2;
UPDATE  `tstates` SET  `mail_object` =  "Notification de clôture" WHERE  `tstates`.`id` =3;
UPDATE  `tstates` SET  `mail_object` =  "Notification de rejet" WHERE  `tstates`.`id` =4;
UPDATE  `tstates` SET  `mail_object` =  "Notification de déclaration" WHERE  `tstates`.`id` =5;
UPDATE  `tstates` SET  `mail_object` =  "Notification d'attente de retour" WHERE  `tstates`.`id` =6;

ALTER TABLE  `tparameters` ADD  `mail_newticket` INT( 1 ) NOT NULL AFTER  `mail_auto`;
ALTER TABLE  `tparameters` ADD  `mail_newticket_address` VARCHAR( 200 ) NOT NULL AFTER  `mail_newticket`;

UPDATE tparameters SET mail_newticket=0;
UPDATE tparameters SET mail_newticket_address='admin@exemple.fr';