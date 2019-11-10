-- SQL Update for Gestsup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.8";
 
ALTER TABLE  `tthreads` ENGINE = INNODB;

ALTER TABLE  `trights` CHANGE  `ticket_thread_edit`  `ticket_thread_edit` INT( 1 ) NOT NULL COMMENT  'Modification de ses résolutions';

ALTER TABLE  `tincidents` ADD  `notify` INT( 1 ) NOT NULL;

ALTER TABLE  `tparameters` ADD  `notify` INT( 1 ) NOT NULL AFTER  `auto_refresh`;
UPDATE `tparameters` SET `notify` = '0';

ALTER TABLE  `tparameters` ADD  `debug` INT( 1 ) NOT NULL;
UPDATE `tparameters` SET `debug` = '0';

ALTER TABLE  `tparameters` CHANGE  `mail_from`  `mail_from_name` VARCHAR( 60 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE  `tparameters` ADD  `mail_from_adr` VARCHAR( 50 ) NOT NULL AFTER  `mail_from_name`;

ALTER TABLE  `tusers` ADD  `last_login` DATETIME NOT NULL;

ALTER TABLE  `trights` ADD  `ticket_close` INT NOT NULL COMMENT  'Fermeture de tickets' AFTER  `side_view`;

UPDATE trights SET ticket_close='2';

ALTER TABLE  `tparameters` ADD  `imap` INT( 1 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `imap_server` VARCHAR( 50 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `imap_port` VARCHAR( 50 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `imap_user` VARCHAR( 50 ) NOT NULL; 
ALTER TABLE  `tparameters` ADD  `imap_password` VARCHAR( 50 ) NOT NULL;