-- SQL Update for Gestsup 
-- !!! If you are not in lastest version, all previous scripts must be passed before !!!

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.2";

ALTER TABLE  `tusers` CHANGE  `password`  `password` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE  `tusers` ADD  `salt` VARCHAR( 50 ) NOT NULL AFTER  `password`;

ALTER TABLE  `tparameters` ADD  `mail_smtp` VARCHAR( 100 ) NOT NULL AFTER  `maxline`;

ALTER TABLE  `tparameters` ADD  `mail_auth` VARCHAR( 10 ) NOT NULL AFTER  `mail_smtp`;	

ALTER TABLE  `tparameters` ADD  `mail_username` VARCHAR( 150 ) NOT NULL AFTER  `mail_auth`;

ALTER TABLE  `tparameters` ADD  `mail_password` VARCHAR( 150 ) NOT NULL AFTER  `mail_username`;

ALTER TABLE  `tparameters` ADD  `mail_secure` VARCHAR( 10 ) NOT NULL AFTER  `mail_auth`;

UPDATE  `tparameters` SET  `mail_secure` =  '0';
UPDATE  `tparameters` SET  `mail_auth` =  '0';
UPDATE  `tparameters` SET  `mail_smtp` =  'localhost';
