-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="3.0.8";

ALTER TABLE  `tparameters` ADD  `user_register` INT( 1 ) NOT NULL AFTER  `user_advanced`;

UPDATE `tparameters` SET `user_register`=0 WHERE id=1;

ALTER TABLE  `tcompany` CHANGE  `zip`  `zip` VARCHAR( 10 ) NOT NULL;

ALTER TABLE  `trights` ADD  `ticket_date_res` INT( 1 ) NOT NULL COMMENT  'Modification de la date de résolution dans le ticket' AFTER  `ticket_date_hope_disp`;
ALTER TABLE  `trights` ADD  `ticket_date_res_disp` INT( 1 ) NOT NULL COMMENT  'Affiche le champ date de résolution dans le ticket' AFTER  `ticket_date_res`;
UPDATE `trights` SET `ticket_date_res`='2' WHERE id='1' OR id='4' OR id='5';
UPDATE `trights` SET `ticket_date_res_disp`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `tparameters` ADD  `mail` INT( 1 ) NOT NULL AFTER  `maxline`;
UPDATE `tparameters` SET `mail`='1' WHERE id='1';

ALTER TABLE  `tparameters` ADD  `mail_port` INT( 4 ) NOT NULL AFTER  `mail_smtp`;
UPDATE `tparameters` SET `mail_port`='25' WHERE id='1';
UPDATE `tparameters` SET `mail_port`='465' WHERE id='1' AND mail_secure='465';
UPDATE `tparameters` SET `mail_port`='587' WHERE id='1' AND mail_secure='587';
UPDATE `tparameters` SET `mail_secure`='SSL' WHERE id='1' AND mail_secure='465';
UPDATE `tparameters` SET `mail_secure`='TLS' WHERE id='1' AND mail_secure='587';