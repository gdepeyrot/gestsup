-- Update Gestsup 1.9 to 2.0
-- !!! If you are not in lastest version, all update scripts must be passed before !!!

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.1";

ALTER TABLE `tusers` ADD  `fax` VARCHAR( 20 ) NOT NULL AFTER  `phone`,
ADD `company` VARCHAR( 50 ) NOT NULL AFTER  `fax`,
ADD `address1` VARCHAR( 100 ) NOT NULL AFTER  `company` ,
ADD `address2` VARCHAR( 100 ) NOT NULL AFTER  `address1` ,
ADD `zip` VARCHAR( 20 ) NOT NULL AFTER  `address2` ,
ADD `city` VARCHAR( 100 ) NOT NULL AFTER  `zip`,
ADD `custom1` VARCHAR( 100 ) NOT NULL AFTER  `city`,
ADD `custom2` VARCHAR( 100 ) NOT NULL AFTER  `custom1`;

DROP TABLE `tdomains` ;

INSERT INTO  `bsup`.`tprofiles` (
`id` ,
`name` ,
`level`
)
VALUES (
NULL ,  'administrateur',  '4');

--
-- Structure de la table `tview`
--

CREATE TABLE IF NOT EXISTS `tviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Structure de la table `trights`
--

CREATE TABLE IF NOT EXISTS `trights` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `profile` int(5) NOT NULL,
  `search` int(1) NOT NULL,
  `task` int(1) NOT NULL,
  `stat` int(1) NOT NULL,
  `admin` int(1) NOT NULL,
  `admin_user_profile` int(1) NOT NULL,
  `admin_user_view` int(1) NOT NULL,
  `userbar` int(1) NOT NULL,
  `side_open_ticket` int(1) NOT NULL,
  `side_your` int(1) NOT NULL,
  `side_your_not_read` int(1) NOT NULL,
  `side_your_not_attribute` int(1) NOT NULL,
  `side_all` int(1) NOT NULL,
  `side_all_wait` int(1) NOT NULL,
  `side_view` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `trights`
--

INSERT INTO `trights` (`id`, `profile`, `search`, `task`, `stat`, `admin`, `admin_user_profile`, `admin_user_view`, `userbar`, `side_open_ticket`, `side_your`, `side_your_not_read`, `side_your_not_attribute`, `side_all`, `side_all_wait`, `side_view`) VALUES
(1, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2),
(2, 1, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0, 2, 0),
(3, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 3, 2, 2, 2, 0, 0, 2, 0, 2, 0, 0, 0, 2, 0, 2),
(5, 4, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2);


ALTER TABLE  `tparameters` ADD  `mail_auto` INT( 1 ) NOT NULL AFTER  `mail_from`;

ALTER TABLE  `tparameters` ADD  `user_advanced` INT( 1 ) NOT NULL AFTER  `logo`;

ALTER TABLE  `tparameters` ADD  `time_display_msg` INT( 5 ) NOT NULL;

ALTER TABLE  `tparameters` ADD  `mail_color_title` VARCHAR( 6 ) NOT NULL AFTER  `mail_auto`;

ALTER TABLE  `tparameters` ADD  `mail_color_bg` VARCHAR( 6 ) NOT NULL AFTER  `mail_color_title`;

ALTER TABLE  `tparameters` ADD  `mail_color_text` VARCHAR( 6 ) NOT NULL AFTER  `mail_color_bg`;


UPDATE  `tparameters` SET  `mail_color_title` =  '0075A4', `mail_color_bg` =  'D8D8D8',`mail_color_text` =  '0075A4' ;

--
-- Structure de la table `tmails`
--

CREATE TABLE IF NOT EXISTS `tmails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `incident` int(10) NOT NULL,
  `open` int(1) NOT NULL,
  `close` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

