-- SQL Update for Gestsup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="2.7";

ALTER TABLE  `tparameters` ADD  `server_url` VARCHAR( 200 ) NOT NULL AFTER  `company`;
UPDATE tparameters SET server_url="http://gestsup";

--
-- table `tthreads`
--

CREATE TABLE IF NOT EXISTS `tthreads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `author` int(10) NOT NULL,
  `text` varchar(10000) NOT NULL,
  `type` INT( 1 ) NOT NULL,
  `tech1` INT( 5 ) NOT NULL,
  `tech2` INT( 5 ) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

ALTER TABLE  `trights` ADD  `ticket_thread_delete` INT( 1 ) NOT NULL COMMENT  'Suppression d''une résolution';
ALTER TABLE  `trights` ADD  `ticket_thread_edit` INT( 1 ) NOT NULL COMMENT  'Modification de ses résolution';
ALTER TABLE  `trights` ADD  `ticket_thread_edit_all` INT( 1 ) NOT NULL COMMENT  'Modification de toutes les résolution';
ALTER TABLE  `trights` ADD  `ticket_thread_post` INT( 1 ) NOT NULL COMMENT  'Droit de répondre dans les tickets';
UPDATE `trights` SET ticket_thread_delete='2' WHERE profile=0 OR profile=4 OR profile=3;
UPDATE `trights` SET ticket_thread_edit='2' WHERE profile=0 OR profile=4 OR profile=3;
UPDATE `trights` SET ticket_thread_edit_all='2' WHERE profile=0 OR profile=4 OR profile=3;
UPDATE `trights` SET ticket_thread_post='2' WHERE profile=0 OR profile=4 OR profile=1 OR profile=3;

ALTER TABLE  `trights` CHANGE  `search`  `search` INT( 1 ) NOT NULL COMMENT  'Affiche le champ de recherche';
ALTER TABLE  `trights` CHANGE  `task`  `task` INT( 1 ) NOT NULL COMMENT  'Affiche le menu Tâche';
ALTER TABLE  `trights` CHANGE  `stat`  `stat` INT( 1 ) NOT NULL COMMENT  'Affiche le menu Statistiques';
ALTER TABLE  `trights` CHANGE  `planning`  `planning` INT( 1 ) NOT NULL COMMENT  'Affiche le menu Planning';
ALTER TABLE  `trights` CHANGE  `admin`  `admin` INT( 1 ) NOT NULL COMMENT  'Affiche le menu Administration';
ALTER TABLE  `trights` CHANGE  `admin_user_profile`  `admin_user_profile` INT( 1 ) NOT NULL COMMENT  'Droit de modification de profile des utilisateurs';
ALTER TABLE  `trights` CHANGE  `admin_user_view`  `admin_user_view` INT( 1 ) NOT NULL COMMENT  'Droit de modification des vues des utilisateurs';
ALTER TABLE  `trights` CHANGE  `userbar`  `userbar` INT( 1 ) NOT NULL COMMENT  'Affiche les propriétés étendue de la barre utilisateur';
ALTER TABLE  `trights` CHANGE  `side`  `side` INT( 1 ) NOT NULL COMMENT  'Affiche la colonne de gauche';
ALTER TABLE  `trights` CHANGE  `side_open_ticket`  `side_open_ticket` INT( 1 ) NOT NULL COMMENT  'Affiche le boutton Ouvrir un nouveau ticket';
ALTER TABLE  `trights` CHANGE  `side_your`  `side_your` INT( 1 ) NOT NULL COMMENT  'Affiche la section VOS DEMANDES';
ALTER TABLE  `trights` CHANGE  `side_your_not_read`  `side_your_not_read` INT( 1 ) NOT NULL COMMENT  'Affiche vos demande non lu';
ALTER TABLE  `trights` CHANGE  `side_your_not_attribute`  `side_your_not_attribute` INT( 1 ) NOT NULL COMMENT  'Affiche vos demande non attribué';
ALTER TABLE  `trights` CHANGE  `side_all`  `side_all` INT( 1 ) NOT NULL COMMENT  'Affiche la section TOUTES LES DEMANDES';
ALTER TABLE  `trights` CHANGE  `side_all_wait`  `side_all_wait` INT( 1 ) NOT NULL COMMENT  'Affiche la vue Nouvelles demandes dans TOUTES les demandes';
ALTER TABLE  `trights` CHANGE  `side_view`  `side_view` INT( 1 ) NOT NULL COMMENT  'Affiche les vues personelles';
ALTER TABLE  `trights` CHANGE  `ticket_delete`  `ticket_delete` INT( 1 ) NOT NULL COMMENT  'Droit de suppression de tickets';

-- Data migration from ticincident to tthreads
INSERT INTO tthreads (ticket,date,author,text) 
SELECT id, CONCAT(date_create, ' 12:00:00'), technician, resolution FROM tincidents WHERE resolution!='';

-- delete resolution col from ticindents table
ALTER TABLE  `tincidents` DROP  `resolution`;

-- update ldap conf port
ALTER TABLE  `tparameters` ADD  `ldap_port` INT( 5 ) NOT NULL AFTER  `ldap_server`;
UPDATE `tparameters` SET ldap_port='389';