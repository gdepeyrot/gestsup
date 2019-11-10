-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.13";

-- avoid delete default value problem
UPDATE tplaces SET id='0' WHERE name='Aucun';
UPDATE tincidents SET place='0' WHERE place='99999';
ALTER TABLE `tincidents` CHANGE `place` `place` INT(5) NULL DEFAULT NULL;

-- default value for new version of queries
INSERT INTO `tsubcat` (`cat`, `name`) VALUES ('0', 'Aucune');
UPDATE `tsubcat` SET id='0' WHERE name='Aucune';

-- default value for new version of queries
INSERT INTO `tcategory` (`id`, `name`) VALUES (NULL, 'Aucune');
UPDATE `tcategory` SET id='0' WHERE name='Aucune';

ALTER TABLE `tparameters` CHANGE `ldap_url` `ldap_url` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

UPDATE `tusers` SET `lastname` = 'Aucun' WHERE `tusers`.`login` = 'aucun';