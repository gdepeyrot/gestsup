-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.6";

-- sql fix to empty date recycle on recyle asset state
UPDATE tassets SET date_recycle='2016-01-01' WHERE date_recycle='0000-00-00' AND state='4';