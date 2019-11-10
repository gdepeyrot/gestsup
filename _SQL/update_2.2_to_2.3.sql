-- SQL Update for Gestsup 
-- !!! If you are not in lastest version, all previous scripts must be passed before !!!

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.3";

ALTER TABLE  `tparameters` ADD  `ldap` INT( 1 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `ldap_auth` INT( 1 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `ldap_server` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `ldap_domain` VARCHAR( 200 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `ldap_url` VARCHAR( 200 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `ldap_user` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `ldap_password` VARCHAR( 100 ) NOT NULL;

update tparameters set ldap='0' where 1;