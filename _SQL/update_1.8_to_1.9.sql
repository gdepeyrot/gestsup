-- Update Gestsup 1.8 to 1.9
-- !!! If you are not in lastest version, all update scripts must be passed before !!!

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `bsup`
--

UPDATE tparameters SET version="1.9";

ALTER TABLE `tparameters` ADD `logo` VARCHAR( 50 ) NOT NULL ;

UPDATE tparameters SET logo="logo.png";

--
-- Structure de la table `tcriticality`
--

CREATE TABLE IF NOT EXISTS `tcriticality` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `tcriticality`
--

INSERT INTO `tcriticality` (`id`, `number`, `name`, `color`) VALUES
(1, 0, 'Critique', 'red'),
(2, 1, 'Grave', 'orange'),
(3, 2, 'Moyenne', 'yellow'),
(4, 3, 'Basse', 'green');

ALTER TABLE  `tincidents` ADD  `criticality` INT( 2 ) NOT NULL DEFAULT  '4' AFTER  `priority`;
