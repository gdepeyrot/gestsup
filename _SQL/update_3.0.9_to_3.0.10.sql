-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.0.10";

-- bugfix search for migrate 
INSERT INTO  `tthreads` (
`id` ,
`ticket` ,
`date` ,
`author` ,
`text` ,
`type` ,
`tech1` ,
`tech2` ,
`group1` ,
`group2` ,
`user`
)
SELECT NULL,id,0,0,0,0,9,0,0,0 FROM tincidents WHERE tincidents.disable=0 AND id NOT IN (SELECT ticket FROM tthreads);

-- update max lengh for attachment
ALTER TABLE  `tincidents` CHANGE  `img1`  `img1` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img2`  `img2` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img3`  `img3` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img4`  `img4` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `tincidents` CHANGE  `img5`  `img5` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- add service to incidents table for fix stat
ALTER TABLE  `tincidents` ADD `u_service` INT( 5 ) NOT NULL AFTER `u_group`;

-- fill current service data in tincidents from tusers 
UPDATE tincidents,tusers SET tincidents.u_service=tusers.service WHERE tincidents.user=tusers.id;

