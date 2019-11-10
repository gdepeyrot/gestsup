-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="3.0.9";

ALTER TABLE  `tincidents` CHANGE  `description`  `description` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tthreads` CHANGE  `text`  `text` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE  `trights` CHANGE  `ticket_thread_edit`  `ticket_thread_edit` INT( 1 ) NOT NULL COMMENT  'Modification de ses résolutions';
ALTER TABLE  `trights` CHANGE  `ticket_thread_edit_all`  `ticket_thread_edit_all` INT( 1 ) NOT NULL COMMENT  'Modification de toutes les résolutions';

ALTER TABLE  `tparameters` ADD  `availability` INT( 1 ) NOT NULL;
UPDATE tparameters SET `availability`="0" WHERE id=1;
ALTER TABLE  `tparameters` ADD  `availability_all_cat` INT( 1 ) NOT NULL;
UPDATE tparameters SET `availability_all_cat`="1" WHERE id=1;
ALTER TABLE  `tparameters` ADD  `availability_dep` INT( 1 ) NOT NULL;
UPDATE tparameters SET `availability_dep`="0" WHERE id=1;


CREATE TABLE IF NOT EXISTS `tavailability` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE  `trights` ADD  `availability` INT( 1 ) NOT NULL COMMENT 'Affiche le menu Disponibilité' AFTER  `planning`;
UPDATE `trights` SET `availability`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `tparameters` ADD  `availability_condition_type` VARCHAR( 20 ) NOT NULL ;
ALTER TABLE  `tparameters` ADD  `availability_condition_value` INT( 4 ) NOT NULL;


ALTER TABLE  `tincidents` ADD  `start_availability` DATETIME NOT NULL;
ALTER TABLE  `tincidents` ADD  `end_availability` DATETIME NOT NULL;

ALTER TABLE  `trights` ADD  `ticket_availability` INT( 1 ) NOT NULL COMMENT 'Modifiction de la partie disponibilité' AFTER  `ticket_state_disp`;
UPDATE `trights` SET `ticket_availability`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `trights` ADD  `ticket_availability_disp` INT( 1 ) NOT NULL COMMENT 'Affiche la partie disponibilité' AFTER  `ticket_availability`;
UPDATE `trights` SET `ticket_availability_disp`='2' WHERE id='1' OR id='4' OR id='5';


--
-- Structure de la table `tavailability_dep`
--

CREATE TABLE IF NOT EXISTS `tavailability_dep` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `tincidents` ADD  `availability_planned` INT( 1 ) NOT NULL;

ALTER TABLE  `tprocedures` ADD  `category` INT( 5 ) NOT NULL AFTER  `id`;
ALTER TABLE  `tprocedures` ADD  `subcat` INT( 5 ) NOT NULL AFTER  `category`;
