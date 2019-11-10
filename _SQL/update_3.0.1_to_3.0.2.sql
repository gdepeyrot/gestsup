-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="3.0.2";

CREATE TABLE IF NOT EXISTS `tusers_tech` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user` int(10) NOT NULL,
  `tech` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `tusers` CHANGE  `skin`  `skin` VARCHAR( 10 ) NOT NULL;

