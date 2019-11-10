-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="3.0.4";

UPDATE  `tstates` SET  `description` =  'tickets en attente de prise en charge' WHERE  `tstates`.`id` =1;

CREATE TABLE IF NOT EXISTS `ttypes` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `tparameters` ADD  `ticket_type` INT( 1 ) NOT NULL;
UPDATE `tparameters` SET `ticket_type`='0' WHERE id='1';

ALTER TABLE  `trights` ADD  `ticket_type` INT( 1 ) NOT NULL COMMENT 'Modification du type dans le ticket' AFTER  `ticket_save`;
ALTER TABLE  `trights` ADD  `ticket_type_disp` INT( 1 ) NOT NULL COMMENT 'Affiche le champ type dans le ticket' AFTER  `ticket_type`;

UPDATE `trights` SET `ticket_type`='2' WHERE id='1' OR id='4' OR id='5';
UPDATE `trights` SET `ticket_type_disp`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `tincidents` ADD  `type` INT( 1 ) NOT NULL DEFAULT '0' AFTER  `id`;