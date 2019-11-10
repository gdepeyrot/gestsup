-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.9";

ALTER TABLE  `tusers` ADD  `function` VARCHAR( 50 ) NOT NULL AFTER  `fax`;
ALTER TABLE  `tusers` ADD  `service` INT( 5 ) NOT NULL AFTER  `function`;

CREATE TABLE  `bsup`.`tservices` (
`id` INT( 5 ) NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 50 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = InnoDB ;

ALTER TABLE  `trights` CHANGE  `ticket_thread_edit`  `ticket_thread_edit` INT( 1 ) NOT NULL COMMENT  'Modification de ses résolutions';
ALTER TABLE  `trights` CHANGE  `ticket_thread_edit_all`  `ticket_thread_edit_all` INT( 1 ) NOT NULL COMMENT  'Modification de toutes les résolutions';

ALTER TABLE  `tparameters` ADD  `id` INT( 1 ) NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (  `id` );

ALTER TABLE  `tparameters` ADD  `ldap_type` INT( 1 ) NOT NULL AFTER  `ldap_auth`;
UPDATE  `bsup`.`tparameters` SET  `ldap_type` =  '1' WHERE  `tparameters`.`id` =1;

CREATE TABLE IF NOT EXISTS `tgroups` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` int(1) NOT NULL,
  `disable` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `tgroups_assoc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group` int(5) NOT NULL,
  `user` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `tincidents` ADD  `t_group` INT( 5 ) NOT NULL AFTER  `technician`;
ALTER TABLE  `tincidents` ADD  `u_group` INT( 5 ) NOT NULL AFTER  `user`;

ALTER TABLE  `tthreads` ADD  `group1` INT( 5 ) NOT NULL;
ALTER TABLE  `tthreads` ADD  `group2` INT( 5 ) NOT NULL;

ALTER TABLE  `tthreads` ADD  `user` INT( 5 ) NOT NULL;

ALTER TABLE  `tincidents` CHANGE  `date_res`  `date_res` DATETIME NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `date_modif`  `date_modif` DATETIME NOT NULL;

ALTER TABLE  `trights` ADD  `task_checkbox` INT( 1 ) NOT NULL AFTER  `task`;
ALTER TABLE  `trights` CHANGE  `task_checkbox`  `task_checkbox` INT( 1 ) NOT NULL COMMENT  'Autorise les actions sur en selectionnant plusieurs tickets dans les tâches';
UPDATE `trights` SET `task_checkbox`=2 WHERE id=1 OR id=4 OR id=5;

ALTER TABLE  `tparameters` ADD  `order` VARCHAR( 100 ) NOT NULL;
UPDATE  `tparameters` SET  `order` =  'tstates.number, tincidents.priority, tincidents.criticality, tincidents.date_create';

ALTER TABLE  `tparameters` ADD  `imap_inbox` VARCHAR( 20 ) NOT NULL AFTER  `imap_password`;
UPDATE `tparameters`SET `imap_inbox`='INBOX';

ALTER TABLE  `trights` ADD  `procedure` INT( 1 ) NOT NULL AFTER  `task_checkbox`;
ALTER TABLE  `trights` CHANGE  `procedure`  `procedure` INT( 1 ) NOT NULL COMMENT  'Affiche le menu procédure';
UPDATE `trights` SET `procedure`=2 WHERE id=1 OR id=4 OR id=5;

ALTER TABLE  `tparameters` ADD  `procedure` INT( 1 ) NOT NULL;
UPDATE `tparameters`SET `procedure`=0;

CREATE TABLE IF NOT EXISTS `tprocedures` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `text` varchar(100000) NOT NULL,
  `file1` varchar(30) NOT NULL,
  `disable` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tplaces` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
ALTER TABLE  `tparameters` ADD  `ticket_places` INT( 1 ) NOT NULL;
UPDATE `tparameters`SET `ticket_places`=0;
ALTER TABLE  `tincidents` ADD  `place` INT( 5 ) NOT NULL;