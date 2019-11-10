-- Update Gestsup 1.9 to 2.0
-- !!! If you are not in lastest version, all update scripts must be passed before !!!

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.0";

ALTER TABLE  `tparameters` ADD  `lign_yellow` INT( 5 ) NOT NULL;
ALTER TABLE  `tparameters` ADD  `lign_orange` INT( 5 ) NOT NULL;

UPDATE tparameters SET lign_yellow=30;
UPDATE tparameters SET lign_orange=45;