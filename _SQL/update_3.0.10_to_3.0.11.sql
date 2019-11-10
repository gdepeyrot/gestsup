-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.0.11";

-- create assets table
CREATE TABLE IF NOT EXISTS `tassets` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `sn_internal` varchar(20) NOT NULL,
  `sn_manufacturer` varchar(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `netbios` varchar(30) NOT NULL,
  `description` varchar(500) NOT NULL,
  `type` int(5) NOT NULL,
  `model` int(5) NOT NULL,
  `user` int(5) NOT NULL,
  `state` int(5) NOT NULL,
  `mac_lan` varchar(20) NOT NULL,
  `mac_wifi` varchar(20) NOT NULL,
  `department` int(5) NOT NULL,
  `date_install` date NOT NULL,
  `date_stock` date NOT NULL,
  `room` varchar(10) NOT NULL,
  `technician` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- create assets parameters
ALTER TABLE  `tparameters` ADD  `asset` INT( 1 ) NOT NULL;
UPDATE `tparameters` SET `asset`=0;

-- create assets rights
ALTER TABLE  `trights` ADD  `asset` INT( 1 ) NOT NULL COMMENT 'Affiche le menu matériel' AFTER  `availability`;
UPDATE `trights` SET `asset`='2' WHERE id='1' OR id='4' OR id='5';

CREATE TABLE IF NOT EXISTS `tassets_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tassets_model` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `type` int(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tassets_type` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tassets_state` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `tparameters` ADD  `meta_state` INT( 1 ) NOT NULL;
UPDATE tparameters SET meta_state="0";

ALTER TABLE  `trights` ADD  `side_your_meta` INT( 1 ) NOT NULL COMMENT 'Affiche le meta etat à traiter personnel' AFTER `side_your_not_attribute`;
UPDATE `trights` SET `side_your_meta`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `trights` ADD  `side_all_meta` INT( 1 ) NOT NULL COMMENT 'Affiche le meta etat à traiter pour tous les techniciens' AFTER `side_all_wait`;
UPDATE `trights` SET `side_all_meta`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `trights` ADD  `ticket_date_hope_mandatory` INT( 1 ) NOT NULL COMMENT 'Oblige la saisie du champ date de résolution estimé' AFTER  `ticket_date_hope_disp`;
UPDATE `trights` SET `ticket_date_hope_mandatory`='0';

ALTER TABLE  `trights` ADD  `ticket_priority_mandatory` INT( 1 ) NOT NULL COMMENT 'Oblige la saisie du champ priorité' AFTER  `ticket_priority_disp`;
UPDATE `trights` SET `ticket_priority_mandatory`='0';

ALTER TABLE  `trights` ADD  `ticket_criticality_mandatory` INT( 1 ) NOT NULL COMMENT 'Oblige la saisie du champ criticité' AFTER  `ticket_criticality_disp`;
UPDATE `trights` SET `ticket_criticality_mandatory`='0';

-- bugfix priority number
UPDATE tincidents SET priority='6' WHERE priority='5';
UPDATE tincidents SET priority='5' WHERE priority='4';
UPDATE tincidents SET priority='4' WHERE priority='3';
UPDATE tincidents SET priority='3' WHERE priority='2';
UPDATE tincidents SET priority='2' WHERE priority='1';
UPDATE tincidents SET priority='1' WHERE priority='0';


ALTER TABLE  `tparameters` ADD  `dash_date` VARCHAR( 15 ) NOT NULL;
UPDATE tparameters SET dash_date="date_create";

ALTER TABLE  `tusers` ADD  `default_ticket_state` VARCHAR( 5 ) NOT NULL;

ALTER TABLE  `tusers` ADD  `dashboard_ticket_order` VARCHAR( 200 ) NOT NULL;


CREATE TABLE IF NOT EXISTS `tavailability_target` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `subcat` int(5) NOT NULL,
  `target` FLOAT(5) NOT NULL,
  `year` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
