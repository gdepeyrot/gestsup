-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 15 Mars 2011 à 18:35
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `bsup`
--


update tparameters SET version="1.5";
-- --------------------------------------------------------

--
-- Structure de la table `tpriority`
--

CREATE TABLE IF NOT EXISTS `tpriority` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `tpriority`
--

INSERT INTO `tpriority` (`id`, `number`, `name`) VALUES
(1, 0, 'Urgent'),
(2, 1, 'TrÃ¨s haute'),
(3, 2, 'Haute'),
(4, 3, 'Moyenne'),
(5, 4, 'Basse'),
(6, 5, 'TrÃ¨s basse');


-- --------------------------------------------------------

--
-- Structure de la table `ttime`
--

CREATE TABLE IF NOT EXISTS `ttime` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `min` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ;

--
-- Contenu de la table `ttime`
--

INSERT INTO `ttime` (`id`, `min`, `name`) VALUES
(1, 1, '1m'),
(2, 5, '5m'),
(3, 10, '10m'),
(4, 30, '30m'),
(5, 60, '1h'),
(6, 180, '3h'),
(7, 300, '5h'),
(8, 480, '1j'),
(9, 960, '2j'),
(10, 2400, '1s');


CREATE TABLE IF NOT EXISTS `tevents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `technician` int(10) NOT NULL,
  `incident` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `type` int(1) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

ALTER TABLE `tusers` ADD `disable` INT( 1 ) NOT NULL ;

UPDATE  `tusers` SET `technician` =2 WHERE  `technician` =0;

UPDATE  `tusers` SET `technician` =0 WHERE  `technician` =1;

ALTER TABLE `tusers` CHANGE `technician` `profile` INT( 10 ) NOT NULL ;





CREATE TABLE IF NOT EXISTS `tprofiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `level` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


INSERT INTO `tprofiles` (`id`, `name`, `level`) VALUES
(1, 'technicien', 0),
(2, 'utilisateur avec pouvoir', 1),
(3, 'utilisateur', 2);

ALTER TABLE `tparameters` CHANGE `mail_cc` `mail_cc` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
