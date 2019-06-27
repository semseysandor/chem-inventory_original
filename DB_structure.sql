SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `changelog_summary`
--

DROP TABLE IF EXISTS `changelog_summary`;
CREATE TABLE IF NOT EXISTS `changelog_summary` (
  `summary_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Summary ID - PRIMARY KEY',
  `table_name` varchar(31) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Modified table',
  `record_id` varchar(254) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Modified record ID',
  `action` varchar(15) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Type of modification',
  `modified_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'User name',
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
  PRIMARY KEY (`summary_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Changelog Summary';

--
-- Table structure for table `changelog_detail`
--

DROP TABLE IF EXISTS `changelog_detail`;
CREATE TABLE IF NOT EXISTS `changelog_detail` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Details ID - PRIMARY KEY',
  `summary_id` int(11) NOT NULL COMMENT 'Summary ID - FOREIGN KEY',
  `column_name` varchar(31) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Modified column',
  `old_value` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Old value',
  `new_value` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'New value',
  PRIMARY KEY (`detail_id`),
  KEY `FK_summary` (`summary_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Changelog details';

--
-- RELATIONS FOR TABLE `changelog_detail`:
--   `summary_id`
--       `changelog_summary` -> `summary_id`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `changelog_detail`
--
ALTER TABLE `changelog_detail`
  ADD CONSTRAINT `FK_summary` FOREIGN KEY (`summary_id`) REFERENCES `changelog_summary` (`summary_id`) ON DELETE CASCADE;

--
-- Table structure for table `main_users`
--

DROP TABLE IF EXISTS `main_users`;
CREATE TABLE IF NOT EXISTS `main_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User ID - PRIMARY KEY',
  `name` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'User name',
  `right_level_leltar` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Right Level for Leltar',
  `right_level_api` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Right Level for API DB',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Users list';

--
-- Triggers `main_users`
--
DROP TRIGGER IF EXISTS `main_users_before_insert`;
DELIMITER //
CREATE TRIGGER `main_users_before_insert` BEFORE INSERT ON `main_users`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `main_users_before_update`;
DELIMITER //
CREATE TRIGGER `main_users_before_update` BEFORE UPDATE ON `main_users`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `main_users_insert`;
DELIMITER //
CREATE TRIGGER `main_users_insert` AFTER INSERT ON `main_users`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'main_users';
  
SET act='CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.user_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* users.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

/* users.right_level_leltar */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'right_level_leltar', NEW.right_level_leltar);

/* users.right_level_api */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'right_level_api', NEW.right_level_api);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `main_users_update`;
DELIMITER //
CREATE TRIGGER `main_users_update` AFTER UPDATE ON `main_users`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'main_users';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.user_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* users.user_id */
IF (NEW.user_id <> OLD.user_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'user_id', OLD.user_id, NEW.user_id);
END IF;

/* users.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* users.right_level_leltar */
IF (NEW.right_level_leltar <> OLD.right_level_leltar) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'right_level_leltar', OLD.right_level_leltar, NEW.right_level_leltar);
END IF;

/* users.right_level_api */
IF (NEW.right_level_api <> OLD.right_level_api) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'right_level_api', OLD.right_level_api, NEW.right_level_api);
END IF;

/* users.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Table structure for table `leltar_loc_lab`
--

DROP TABLE IF EXISTS `leltar_loc_lab`;
CREATE TABLE IF NOT EXISTS `leltar_loc_lab` (
  `loc_lab_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Lab ID - PRIMARY KEY',
  `name` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Lab name',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`loc_lab_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Lab list';

--
-- Triggers `leltar_loc_lab`
--
DROP TRIGGER IF EXISTS `leltar_loc_lab_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_loc_lab_before_insert` BEFORE INSERT ON `leltar_loc_lab`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_lab_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_loc_lab_before_update` BEFORE UPDATE ON `leltar_loc_lab`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_lab_insert`;
DELIMITER //
CREATE TRIGGER `leltar_loc_lab_insert` AFTER INSERT ON `leltar_loc_lab`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_loc_lab';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.loc_lab_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* loc_lab.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_lab_update`;
DELIMITER //
CREATE TRIGGER `leltar_loc_lab_update` AFTER UPDATE ON `leltar_loc_lab`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_loc_lab';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.loc_lab_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* loc_lab.loc_lab_id */
IF (NEW.loc_lab_id <> OLD.loc_lab_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'loc_lab_id', OLD.loc_lab_id, NEW.loc_lab_id);
END IF;

/* loc_lab.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* loc_lab.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `leltar_loc_place`
--

DROP TABLE IF EXISTS `leltar_loc_place`;
CREATE TABLE IF NOT EXISTS `leltar_loc_place` (
  `loc_place_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Place ID - PRIMARY KEY',
  `name` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Place Name',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`loc_place_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Place list';

--
-- Triggers `leltar_loc_place`
--
DROP TRIGGER IF EXISTS `leltar_loc_place_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_loc_place_before_insert` BEFORE INSERT ON `leltar_loc_place`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_place_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_loc_place_before_update` BEFORE UPDATE ON `leltar_loc_place`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_place_insert`;
DELIMITER //
CREATE TRIGGER `leltar_loc_place_insert` AFTER INSERT ON `leltar_loc_place`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_loc_place';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.loc_place_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* loc_place.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_place_update`;
DELIMITER //
CREATE TRIGGER `leltar_loc_place_update` AFTER UPDATE ON `leltar_loc_place`
 FOR EACH ROW BEGIN

/*****************
*  Trigger info  *
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n='leltar_loc_place';
  
SET act='UPDATE';
  

/************************************
*  Insert summary row to changelog  *
************************************/

/* Gets current user name */
SET @user=SUBSTRING_INDEX(USER(),'@',1);

IF (@user='leltar_USER') THEN
  SET @user=NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.loc_place_id, act, @user, CURRENT_TIMESTAMP);

/************************************
*  INSERT details to changelog  *
************************************/

/* Get summary_id */
SET @sum_id=LAST_INSERT_ID();

/* loc_place.loc_place_id */
IF (NEW.loc_place_id<>OLD.loc_place_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'loc_place_id', OLD.loc_place_id, NEW.loc_place_id);
END IF;

/* loc_place.name */
IF (NEW.name<>OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* loc_place.last_mod_by */
IF (NEW.last_mod_by<>OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `leltar_loc_sub`
--

DROP TABLE IF EXISTS `leltar_loc_sub`;
CREATE TABLE IF NOT EXISTS `leltar_loc_sub` (
  `loc_sub_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Sub ID - PRIMARY KEY',
  `name` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Sub name',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`loc_sub_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Sub list';

--
-- Triggers `leltar_loc_sub`
--
DROP TRIGGER IF EXISTS `leltar_loc_sub_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_loc_sub_before_insert` BEFORE INSERT ON `leltar_loc_sub`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_sub_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_loc_sub_before_update` BEFORE UPDATE ON `leltar_loc_sub`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_sub_insert`;
DELIMITER //
CREATE TRIGGER `leltar_loc_sub_insert` AFTER INSERT ON `leltar_loc_sub`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_loc_sub';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.loc_sub_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* loc_sub.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_loc_sub_update`;
DELIMITER //
CREATE TRIGGER `leltar_loc_sub_update` AFTER UPDATE ON `leltar_loc_sub`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_loc_sub';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.loc_sub_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id=LAST_INSERT_ID();

/* loc_sub.loc_sub_id */
IF (NEW.loc_sub_id <> OLD.loc_sub_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'loc_sub_id', OLD.loc_sub_id, NEW.loc_sub_id);
END IF;

/* loc_sub.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* loc_sub.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Table structure for table `leltar_location`
--

DROP TABLE IF EXISTS `leltar_location`;
CREATE TABLE IF NOT EXISTS `leltar_location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Location ID - PRIMARY KEY',
  `loc_lab_id` int(11) NOT NULL COMMENT 'Lab ID - FOREIGN KEY',
  `loc_place_id` int(11) NOT NULL COMMENT 'Place ID - FOREIGN KEY',
  `loc_sub_id` int(11) NOT NULL COMMENT 'Sub ID - FOREGIN KEY',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`location_id`),
  KEY `FK_lab` (`loc_lab_id`),
  KEY `FK_place` (`loc_place_id`),
  KEY `FK_sub` (`loc_sub_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Locations list';

--
-- RELATIONS FOR TABLE `leltar_location`:
--   `loc_lab_id`
--       `leltar_loc_lab` -> `loc_lab_id`
--   `loc_place_id`
--       `leltar_loc_place` -> `loc_place_id`
--   `loc_sub_id`
--       `leltar_loc_sub` -> `loc_sub_id`
--

--
-- Triggers `leltar_location`
--
DROP TRIGGER IF EXISTS `leltar_location_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_location_before_insert` BEFORE INSERT ON `leltar_location`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_location_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_location_before_update` BEFORE UPDATE ON `leltar_location`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_location_insert`;
DELIMITER //
CREATE TRIGGER `leltar_location_insert` AFTER INSERT ON `leltar_location`
 FOR EACH ROW BEGIN

/*****************
*  Trigger info  *
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n='location';
  
SET act='CREATE';
  

/************************************
*  Insert summary row to changelog  *
************************************/

/* Gets current user name */
SET @user=SUBSTRING_INDEX(USER(),'@',1);

IF (@user='leltar_USER') THEN
  SET @user=NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.location_id, act, @user, CURRENT_TIMESTAMP);

/************************************
*  INSERT details to changelog  *
************************************/

/* Get summary_id */
SET @sum_id=LAST_INSERT_ID();

/* location.loc_lab_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'loc_lab_id', NEW.loc_lab_id);

/* location.loc_place_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'place_id', NEW.loc_place_id);

/* location.loc_sub_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'sub_id', NEW.loc_sub_id);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_location_update`;
DELIMITER //
CREATE TRIGGER `leltar_location_update` AFTER UPDATE ON `leltar_location`
 FOR EACH ROW BEGIN

/*****************
*  Trigger info  *
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n='location';
  
SET act='UPDATE';
  

/************************************
*  Insert summary row to changelog  *
************************************/

/* Gets current user name */
SET @user=SUBSTRING_INDEX(USER(),'@',1);

IF (@user='leltar_USER') THEN
  SET @user=NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.location_id, act, @user, CURRENT_TIMESTAMP);

/************************************
*  INSERT details to changelog  *
************************************/

/* Get summary_id */
SET @sum_id=LAST_INSERT_ID();

/* location.location_id */
IF (NEW.location_id<>OLD.location_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'location_id', OLD.location_id, NEW.location_id);
END IF;

/* location.loc_lab_id */
IF (NEW.loc_lab_id<>OLD.loc_lab_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'loc_lab_id', OLD.loc_lab_id, NEW.loc_lab_id);
END IF;

/* location.loc_place_id */
IF (NEW.loc_place_id<>OLD.loc_place_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'loc_place_id', OLD.loc_place_id, NEW.loc_place_id);
END IF;

/* location.loc_sub_id */
IF (NEW.loc_sub_id<>OLD.loc_sub_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'loc_sub_id', OLD.loc_sub_id, NEW.loc_sub_id);
END IF;

/* location.last_mod_by */
IF (NEW.last_mod_by<>OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leltar_location`
--
ALTER TABLE `leltar_location`
  ADD CONSTRAINT `FK_lab` FOREIGN KEY (`loc_lab_id`) REFERENCES `leltar_loc_lab` (`loc_lab_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_place` FOREIGN KEY (`loc_place_id`) REFERENCES `leltar_loc_place` (`loc_place_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_sub` FOREIGN KEY (`loc_sub_id`) REFERENCES `leltar_loc_sub` (`loc_sub_id`) ON DELETE NO ACTION;

--
-- Table structure for table `leltar_category`
--

DROP TABLE IF EXISTS `leltar_category`;
CREATE TABLE IF NOT EXISTS `leltar_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Category ID - PRIMARY KEY',
  `name` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Category Name',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Category List';

--
-- Triggers `leltar_category`
--
DROP TRIGGER IF EXISTS `leltar_category_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_category_before_insert` BEFORE INSERT ON `leltar_category`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_category_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_category_before_update` BEFORE UPDATE ON `leltar_category`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_category_insert`;
DELIMITER //
CREATE TRIGGER `leltar_category_insert` AFTER INSERT ON `leltar_category`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_category';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.category_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* category.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_category_update`;
DELIMITER //
CREATE TRIGGER `leltar_category_update` AFTER UPDATE ON `leltar_category`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_category';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.category_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* category.category_id */
IF (NEW.category_id <> OLD.category_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'category_id', OLD.category_id, NEW.category_id);
END IF;

/* category.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* category.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Table structure for table `leltar_sub_category`
--

DROP TABLE IF EXISTS `leltar_sub_category`;
CREATE TABLE IF NOT EXISTS `leltar_sub_category` (
  `sub_category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Sub-category ID - PRIMARY KEY',
  `category_id` int(11) NOT NULL COMMENT 'Category ID - FOREIGN KEY',
  `name` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Sub-category Name',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`sub_category_id`),
  KEY `FK_category` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Sub-category list';

--
-- RELATIONS FOR TABLE `leltar_sub_category`:
--   `category_id`
--       `leltar_category` -> `category_id`
--

--
-- Triggers `leltar_sub_category`
--
DROP TRIGGER IF EXISTS `leltar_sub_category_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_sub_category_before_insert` BEFORE INSERT ON `leltar_sub_category`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_sub_category_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_sub_category_before_update` BEFORE UPDATE ON `leltar_sub_category`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_sub_category_insert`;
DELIMITER //
CREATE TRIGGER `leltar_sub_category_insert` AFTER INSERT ON `leltar_sub_category`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_sub_category';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.sub_category_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* sub_category.category_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'category_id', NEW.category_id);

/* sub_category.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_sub_category_update`;
DELIMITER //
CREATE TRIGGER `leltar_sub_category_update` AFTER UPDATE ON `leltar_sub_category`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_sub_category';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.sub_category_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* sub_category.sub_category_id */
IF (NEW.sub_category_id <> OLD.sub_category_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'sub_category_id', OLD.sub_category_id, NEW.sub_category_id);
END IF;

  /* sub_category.category_id */
IF (NEW.category_id <> OLD.category_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'category_id', OLD.category_id, NEW.category_id);
END IF;

/* sub_category.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* sub_category.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leltar_sub_category`
--
ALTER TABLE `leltar_sub_category`
  ADD CONSTRAINT `FK_category` FOREIGN KEY (`category_id`) REFERENCES `leltar_category` (`category_id`) ON DELETE NO ACTION;

--
-- Table structure for table `leltar_manfac`
--

DROP TABLE IF EXISTS `leltar_manfac`;
CREATE TABLE IF NOT EXISTS `leltar_manfac` (
  `manfac_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Manufacturer ID - PRIMARY KEY',
  `name` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Name',
  `is_frequent` tinyint(1) NOT NULL COMMENT 'Is frequenty used?',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`manfac_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Manufacturers list';

--
-- Triggers `leltar_manfac`
--
DROP TRIGGER IF EXISTS `leltar_manfac_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_manfac_before_insert` BEFORE INSERT ON `leltar_manfac`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_manfac_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_manfac_before_update` BEFORE UPDATE ON `leltar_manfac`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_manfac_insert`;
DELIMITER //
CREATE TRIGGER `leltar_manfac_insert` AFTER INSERT ON `leltar_manfac`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_manfac';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.manfac_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* manfac.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

/* manfac.is_frequent */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'is_frequent', NEW.is_frequent);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_manfac_update`;
DELIMITER //
CREATE TRIGGER `leltar_manfac_update` AFTER UPDATE ON `leltar_manfac`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_manfac';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.manfac_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* manfac.manfac_id */
IF (NEW.manfac_id <> OLD.manfac_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'manfac_id', OLD.manfac_id, NEW.manfac_id);
END IF;

/* manfac.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* manfac.is_frequent */
IF (NEW.is_frequent <> OLD.is_frequent) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'is_frequent', OLD.is_frequent, NEW.is_frequent);
END IF;

/* manfac.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Table structure for table `leltar_compound`
--

DROP TABLE IF EXISTS `leltar_compound`;
CREATE TABLE IF NOT EXISTS `leltar_compound` (
  `compound_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Compound ID - PRIMARY KEY',
  `name` varchar(255) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Compound name',
  `name_alt` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Alternative name',
  `abbrev` varchar(15) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Abbreviation',
  `chemical_name` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Chemical name',
  `iupac_name` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'IUPAC name',
  `chem_formula` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Chemical Formula',
  `cas` varchar(31) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'CAS number',
  `smiles` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'SMILES',
  `sub_category_id` int(11) NOT NULL COMMENT 'sub_category_id FOREIGN KEY',
  `oeb` int(11) DEFAULT NULL COMMENT 'OEB category',
  `mol_weight` double DEFAULT NULL COMMENT 'Molecular weight',
  `melting_point` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Melting Point',
  `note` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Note',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`compound_id`),
  KEY `FK_sub_category` (`sub_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Compound list';

--
-- RELATIONS FOR TABLE `leltar_compound`:
--   `sub_category_id`
--       `leltar_sub_category` -> `sub_category_id`
--

--
-- Triggers `leltar_compound`
--
DROP TRIGGER IF EXISTS `leltar_compound_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_compound_before_insert` BEFORE INSERT ON `leltar_compound`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_compound_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_compound_before_update` BEFORE UPDATE ON `leltar_compound`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_compound_insert`;
DELIMITER //
CREATE TRIGGER `leltar_compound_insert` AFTER INSERT ON `leltar_compound`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_compound';
  
SET act = 'CREATE';
  

/************************************
*  Insert summary row to changelog  *
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.compound_id, act, @user, CURRENT_TIMESTAMP);

/************************************
*  INSERT details to changelog  *
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* compound.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

/* compound.name_alt */
IF (ISNULL(NEW.name_alt) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name_alt', NEW.name_alt);
END IF;

/* compound.abbrev */
IF (ISNULL(NEW.abbrev) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'abbrev', NEW.abbrev);
END IF;

/* compound.chemical_name */
IF (ISNULL(NEW.chemical_name) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'chemical_name', NEW.chemical_name);
END IF;

/* compound.iupac_name */
IF (ISNULL(NEW.iupac_name) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'iupac_name', NEW.iupac_name);
END IF;

/* compound.chem_formula */
IF (ISNULL(NEW.chem_formula) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'chem_formula', NEW.chem_formula);
END IF;

/* compound.cas */
IF (ISNULL(NEW.cas) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'cas', NEW.cas);
END IF;

/* compound.smiles */
IF (ISNULL(NEW.smiles) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'smiles', NEW.smiles);
END IF;

/* compound.sub_category_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'sub_category_id', NEW.sub_category_id);

/* compound.oeb */
IF (ISNULL(NEW.oeb) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'oeb', NEW.oeb);
END IF;

/* compound.mol_weight */
IF (ISNULL(NEW.mol_weight) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'mol_weight', NEW.mol_weight);
END IF;

/* compound.melting_point */
IF (ISNULL(NEW.melting_point) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'melting_point', NEW.melting_point);
END IF;

/* compound.note */
IF (ISNULL(NEW.note) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'note', NEW.note);
END IF;

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_compound_update`;
DELIMITER //
CREATE TRIGGER `leltar_compound_update` AFTER UPDATE ON `leltar_compound`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_compound';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.compound_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* compound.compound_id */
IF (NEW.compound_id <> OLD.compound_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'compound_id', OLD.compound_id, NEW.compound_id);
END IF;

/* compound.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* compound.name_alt */
IF (NEW.name_alt <> OLD.name_alt OR (ISNULL(OLD.name_alt) AND ISNULL(NEW.name_alt) = 0) OR (ISNULL(OLD.name_alt) = 0 AND ISNULL(NEW.name_alt))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name_alt', OLD.name_alt, NEW.name_alt);
END IF;

/* compound.abbrev */
IF (NEW.abbrev <> OLD.abbrev OR (ISNULL(OLD.abbrev) AND ISNULL(NEW.abbrev) = 0) OR (ISNULL(OLD.abbrev) = 0 AND ISNULL(NEW.abbrev))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'abbrev', OLD.abbrev, NEW.abbrev);
END IF;

/* compound.chemical_name */
IF (NEW.chemical_name <> OLD.chemical_name OR (ISNULL(OLD.chemical_name) AND ISNULL(NEW.chemical_name) = 0) OR (ISNULL(OLD.chemical_name) = 0 AND ISNULL(NEW.chemical_name))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'chemical_name', OLD.chemical_name, NEW.chemical_name);
END IF;

/* compound.iupac_name */
IF (NEW.iupac_name <> OLD.iupac_name OR (ISNULL(OLD.iupac_name) AND ISNULL(NEW.iupac_name) = 0) OR (ISNULL(OLD.iupac_name) = 0 AND ISNULL(NEW.iupac_name))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'iupac_name', OLD.iupac_name, NEW.iupac_name);
END IF;

/* compound.chem_formula */
IF (NEW.chem_formula <> OLD.chem_formula OR (ISNULL(OLD.chem_formula) AND ISNULL(NEW.chem_formula) = 0) OR (ISNULL(OLD.chem_formula) = 0 AND ISNULL(NEW.chem_formula))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'chem_formula', OLD.chem_formula, NEW.chem_formula);
END IF;

/* compound.cas */
IF (NEW.cas <> OLD.cas OR (ISNULL(OLD.cas) AND ISNULL(NEW.cas) = 0) OR (ISNULL(OLD.cas) = 0 AND ISNULL(NEW.cas))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'cas', OLD.cas, NEW.cas);
END IF;

/* compound.smiles */
IF (NEW.smiles <> OLD.smiles OR (ISNULL(OLD.smiles) AND ISNULL(NEW.smiles) = 0) OR (ISNULL(OLD.smiles) = 0 AND ISNULL(NEW.smiles))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'smiles', OLD.smiles, NEW.smiles);
END IF;

/* compound.sub_category_id */
IF (NEW.sub_category_id <> OLD.sub_category_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'sub_category_id', OLD.sub_category_id, NEW.sub_category_id);
END IF;

/* compound.oeb */
IF (NEW.oeb <> OLD.oeb OR (ISNULL(OLD.oeb) AND ISNULL(NEW.oeb) = 0) OR (ISNULL(OLD.oeb) = 0 AND ISNULL(NEW.oeb))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'oeb', OLD.oeb, NEW.oeb);
END IF;

/* compound.mol_weight */
IF (NEW.mol_weight <> OLD.mol_weight OR (ISNULL(OLD.mol_weight) AND ISNULL(NEW.mol_weight) = 0) OR (ISNULL(OLD.mol_weight) = 0 AND ISNULL(NEW.mol_weight))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'mol_weight', OLD.mol_weight, NEW.mol_weight);
END IF;

/* compound.melting_point */
IF (NEW.melting_point <> OLD.melting_point OR (ISNULL(OLD.melting_point) AND ISNULL(NEW.melting_point) = 0) OR (ISNULL(OLD.melting_point) = 0 AND ISNULL(NEW.melting_point))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'melting_point', OLD.melting_point, NEW.melting_point);
END IF;

/* compound.note */
IF (NEW.note <> OLD.note OR (ISNULL(OLD.note) AND ISNULL(NEW.note) = 0) OR (ISNULL(OLD.note) = 0 AND ISNULL(NEW.note))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'note', OLD.note, NEW.note);
END IF;

/* compound.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leltar_compound`
--
ALTER TABLE `leltar_compound`
  ADD CONSTRAINT `FK_sub_category` FOREIGN KEY (`sub_category_id`) REFERENCES `leltar_sub_category` (`sub_category_id`) ON DELETE NO ACTION;

--
-- Table structure for table `leltar_batch`
--

DROP TABLE IF EXISTS `leltar_batch`;
CREATE TABLE IF NOT EXISTS `leltar_batch` (
  `batch_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Batch ID - PRIMARY KEY',
  `compound_id` int(11) NOT NULL COMMENT 'Compound ID - FOREIGN KEY',
  `manfac_id` int(11) NOT NULL COMMENT 'Manufacturer - FOREIGN KEY',
  `name` varchar(255) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Name',
  `lot` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'LOT number',
  `date_arr` date NOT NULL COMMENT 'Date Arrived',
  `date_open` date DEFAULT NULL COMMENT 'Date of Opening',
  `date_exp` date DEFAULT NULL COMMENT 'Expiration date',
  `date_arch` date DEFAULT NULL COMMENT 'Date of Archivation',
  `note` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Note',
  `is_active` tinyint(1) NOT NULL COMMENT 'If batch exist in lab: 1',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`batch_id`),
  KEY `FK_compound` (`compound_id`),
  KEY `FK_manfac` (`manfac_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Batch list';

--
-- RELATIONS FOR TABLE `leltar_batch`:
--   `compound_id`
--       `leltar_compound` -> `compound_id`
--   `manfac_id`
--       `leltar_manfac` -> `manfac_id`
--

--
-- Triggers `leltar_batch`
--
DROP TRIGGER IF EXISTS `leltar_batch_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_batch_before_insert` BEFORE INSERT ON `leltar_batch`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_batch_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_batch_before_update` BEFORE UPDATE ON `leltar_batch`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_batch_insert`;
DELIMITER //
CREATE TRIGGER `leltar_batch_insert` AFTER INSERT ON `leltar_batch`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_batch';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.batch_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* batch.compound_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'compound_id', NEW.compound_id);

/* batch.manfac_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'manfac_id', NEW.manfac_id);

/* batch.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

/* batch.lot */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'lot', NEW.lot);

/* batch.date_arr */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'date_arr', NEW.date_arr);

/* batch.date_open */
IF (ISNULL(NEW.date_open) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'date_open', NEW.date_open);
END IF;

/* batch.date_exp */
IF (ISNULL(NEW.date_exp) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'date_exp', NEW.date_exp);
END IF;

/* batch.date_arch */
IF (ISNULL(NEW.date_arch) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'date_arch', NEW.date_arch);
END IF;

/* batch.note */
IF (ISNULL(NEW.note) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'note', NEW.note);
END IF;

/* batch.is_active */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'is_active', NEW.is_active);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_batch_update`;
DELIMITER //
CREATE TRIGGER `leltar_batch_update` AFTER UPDATE ON `leltar_batch`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_batch';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.batch_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* batch.batch_id */
IF (NEW.batch_id <> OLD.batch_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'batch_id', OLD.batch_id, NEW.batch_id);
END IF;

/* batch.compound_id */
IF (NEW.compound_id <> OLD.compound_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'compound_id', OLD.compound_id, NEW.compound_id);
END IF;

/* batch.manfac_id */
IF (NEW.manfac_id <> OLD.manfac_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'manfac_id', OLD.manfac_id, NEW.manfac_id);
END IF;

/* batch.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* batch.lot */
IF (NEW.lot <> OLD.lot) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'lot', OLD.lot, NEW.lot);
END IF;

/* batch.date_arr */
IF (NEW.date_arr <> OLD.date_arr) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'date_arr', OLD.date_arr, NEW.date_arr);
END IF;

/* batch.date_open */
IF (NEW.date_open <> OLD.date_open OR (ISNULL(OLD.date_open) AND ISNULL(NEW.date_open) = 0) OR (ISNULL(OLD.date_open) = 0 AND ISNULL(NEW.date_open))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'date_open', OLD.date_open, NEW.date_open);
END IF;

/* batch.date_exp */
IF (NEW.date_exp <> OLD.date_exp OR (ISNULL(OLD.date_exp) AND ISNULL(NEW.date_exp) = 0) OR (ISNULL(OLD.date_exp) = 0 AND ISNULL(NEW.date_exp))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'date_exp', OLD.date_exp, NEW.date_exp);
END IF;

/* batch.date_arch */
IF (NEW.date_arch <> OLD.date_arch OR (ISNULL(OLD.date_arch) AND ISNULL(NEW.date_arch) = 0) OR (ISNULL(OLD.date_arch) = 0 AND ISNULL(NEW.date_arch))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'date_arch', OLD.date_arch, NEW.date_arch);
END IF;

/* batch.note */
IF (NEW.note <> OLD.note OR (ISNULL(OLD.note) AND ISNULL(NEW.note) = 0) OR (ISNULL(OLD.note) = 0 AND ISNULL(NEW.note))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'note', OLD.note, NEW.note);
END IF;

/* batch.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

/* batch.is_active */
IF (NEW.is_active <> OLD.is_active) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'is_active', OLD.is_active, NEW.is_active);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leltar_batch`
--
ALTER TABLE `leltar_batch`
  ADD CONSTRAINT `FK_compound` FOREIGN KEY (`compound_id`) REFERENCES `leltar_compound` (`compound_id`),
  ADD CONSTRAINT `FK_manfac` FOREIGN KEY (`manfac_id`) REFERENCES `leltar_manfac` (`manfac_id`);

--
-- Table structure for table `leltar_pack`
--

DROP TABLE IF EXISTS `leltar_pack`;
CREATE TABLE IF NOT EXISTS `leltar_pack` (
  `pack_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Pack ID - PRIMARY KEY',
  `batch_id` int(11) NOT NULL COMMENT 'Batch ID - FOREIGN KEY',
  `location_id` int(11) NOT NULL COMMENT 'Location ID - FOREIGN KEY',
  `is_original` tinyint(4) NOT NULL COMMENT 'Original or packed in the lab',
  `size` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Size of the pack',
  `weight` varchar(63) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Gross weight',
  `barcode` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Barcode',
  `note` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Note',
  `is_active` tinyint(1) NOT NULL COMMENT 'If pack exist: 1',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`pack_id`),
  KEY `FK_batch` (`batch_id`),
  KEY `FK_location` (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Pack list';

--
-- RELATIONS FOR TABLE `leltar_pack`:
--   `batch_id`
--       `leltar_batch` -> `batch_id`
--   `location_id`
--       `leltar_location` -> `location_id`
--

--
-- Triggers `leltar_pack`
--
DROP TRIGGER IF EXISTS `leltar_pack_before_insert`;
DELIMITER //
CREATE TRIGGER `leltar_pack_before_insert` BEFORE INSERT ON `leltar_pack`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_pack_before_update`;
DELIMITER //
CREATE TRIGGER `leltar_pack_before_update` BEFORE UPDATE ON `leltar_pack`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_pack_insert`;
DELIMITER //
CREATE TRIGGER `leltar_pack_insert` AFTER INSERT ON `leltar_pack`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_pack';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.pack_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* pack.batch_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'batch_id', NEW.batch_id);

/* pack.location_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'location_id', NEW.location_id);

/* pack.is_original */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'is_original', NEW.is_original);

/* pack.size */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'size', NEW.size);

/* pack.weight */
IF (ISNULL(NEW.weight) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'weight', NEW.weight);
END IF;

/* pack.barcode */
IF (ISNULL(NEW.barcode) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'barcode', NEW.barcode);
END IF;

/* pack.note */
IF (ISNULL(NEW.note) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'note', NEW.note);
END IF;

/* pack.is_active */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'is_active', NEW.is_active);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `leltar_pack_update`;
DELIMITER //
CREATE TRIGGER `leltar_pack_update` AFTER UPDATE ON `leltar_pack`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(16);
DECLARE act varchar(16);

SET table_n = 'leltar_pack';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.pack_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* pack.pack_id */
IF (NEW.pack_id <> OLD.pack_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'pack_id', OLD.pack_id, NEW.pack_id);
END IF;

/* pack.batch_id */
IF (NEW.batch_id <> OLD.batch_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'batch_id', OLD.batch_id, NEW.batch_id);
END IF;

/* pack.location_id */
IF (NEW.location_id <> OLD.location_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'location_id', OLD.location_id, NEW.location_id);
END IF;

/* pack.is_original */
IF (NEW.is_original <> OLD.is_original) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'is_original', OLD.is_original, NEW.is_original);
END IF;

/* pack.size */
IF (NEW.size <> OLD.size) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'size', OLD.size, NEW.size);
END IF;

/* pack.weight */
IF (NEW.weight <> OLD.weight OR (ISNULL(OLD.weight) AND ISNULL(NEW.weight) = 0) OR (ISNULL(OLD.weight) = 0 AND ISNULL(NEW.weight))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'weight', OLD.weight, NEW.weight);
END IF;

/* pack.barcode */
IF (NEW.barcode <> OLD.barcode OR (ISNULL(OLD.barcode) AND ISNULL(NEW.barcode) = 0) OR (ISNULL(OLD.barcode) = 0 AND ISNULL(NEW.barcode))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'barcode', OLD.barcode, NEW.barcode);
END IF;

/* pack.note */
IF (NEW.note <> OLD.note OR (ISNULL(OLD.note) AND ISNULL(NEW.note) = 0) OR (ISNULL(OLD.note) = 0 AND ISNULL(NEW.note))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'note', OLD.note, NEW.note);
END IF;

/* pack.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

/* pack.is_active */
IF (NEW.is_active <> OLD.is_active) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'is_active', OLD.is_active, NEW.is_active);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leltar_pack`
--
ALTER TABLE `leltar_pack`
  ADD CONSTRAINT `FK_batch` FOREIGN KEY (`batch_id`) REFERENCES `leltar_batch` (`batch_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_location` FOREIGN KEY (`location_id`) REFERENCES `leltar_location` (`location_id`) ON DELETE NO ACTION;

--
-- Stand-in structure for view `leltar_location_list`
--
DROP VIEW IF EXISTS `leltar_location_list`;
CREATE TABLE IF NOT EXISTS `leltar_location_list` (
`location_id` int(11)
,`lab_name` varchar(63)
,`place_name` varchar(63)
,`sub_name` varchar(63)
);
-- --------------------------------------------------------

--
-- Structure for view `leltar_location_list`
--
DROP TABLE IF EXISTS `leltar_location_list`;

CREATE VIEW `leltar_location_list` AS
select 
`leltar_location`.`location_id` AS `location_id`,
`leltar_loc_lab`.`name` AS `lab_name`,
`leltar_loc_place`.`name` AS `place_name`,
`leltar_loc_sub`.`name` AS `sub_name`
from
(((`leltar_location` join `leltar_loc_lab`on((`leltar_location`.`loc_lab_id` = `leltar_loc_lab`.`loc_lab_id`)))
join `leltar_loc_place` on((`leltar_location`.`loc_place_id` = `leltar_loc_place`.`loc_place_id`)))
join `leltar_loc_sub` on((`leltar_location`.`loc_sub_id` = `leltar_loc_sub`.`loc_sub_id`)));

--
-- Stand-in structure for view `leltar_category_list`
--
DROP VIEW IF EXISTS `leltar_category_list`;
CREATE TABLE IF NOT EXISTS `leltar_category_list` (
`sub_category_id` int(11)
,`category_name` varchar(63)
,`subcategory_name` varchar(63)
);
-- --------------------------------------------------------

--
-- Structure for view `leltar_category_list`
--
DROP TABLE IF EXISTS `leltar_category_list`;

CREATE VIEW `leltar_category_list` AS
select
`leltar_sub_category`.`sub_category_id` AS `sub_category_id`,
`leltar_category`.`name` AS `category_name`,
`leltar_sub_category`.`name` AS `subcategory_name`
from
(`leltar_sub_category` join `leltar_category` on((`leltar_sub_category`.`category_id` = `leltar_category`.`category_id`)));

--
-- Stand-in structure for view `leltar_all_info`
--
DROP VIEW IF EXISTS `leltar_all_info`;
CREATE TABLE IF NOT EXISTS `leltar_all_info` (
`comp_id` int(11)
,`batch_id` int(11)
,`pack_id` int(11)
,`comp` varchar(255)
,`name_alt` varchar(255)
,`abbrev` varchar(15)
,`chemical` varchar(255)
,`iupac` varchar(255)
,`chem_formula` varchar(255)
,`cas` varchar(31)
,`smiles` varchar(255)
,`category` varchar(63)
,`subcategory` varchar(63)
,`oeb` int(11)
,`mol_weight` double
,`comp_melt` varchar(255)
,`comp_note` varchar(511)
,`manfac` varchar(127)
,`batch` varchar(255)
,`lot` varchar(63)
,`date_arr` date
,`date_open` date
,`date_exp` date
,`date_arch` date
,`batch_note` varchar(511)
,`size` varchar(63)
,`lab` varchar(63)
,`place` varchar(63)
,`sub` varchar(63)
,`is_original` tinyint(4)
,`weight` varchar(63)
,`barcode` varchar(127)
,`pack_note` varchar(511)
,`pack_is_active` tinyint(1)
);
-- --------------------------------------------------------

--
-- Structure for view `leltar_all_info`
--
DROP TABLE IF EXISTS `leltar_all_info`;

CREATE VIEW `leltar_all_info` AS
select
`leltar_compound`.`compound_id` AS `comp_id`,
`leltar_batch`.`batch_id` AS `batch_id`,
`leltar_pack`.`pack_id` AS `pack_id`,
`leltar_compound`.`name` AS `comp`,
`leltar_compound`.`name_alt` AS `name_alt`,
`leltar_compound`.`abbrev` AS `abbrev`,
`leltar_compound`.`chemical_name` AS `chemical`,
`leltar_compound`.`iupac_name` AS `iupac`,
`leltar_compound`.`chem_formula` AS `chem_formula`,
`leltar_compound`.`cas` AS `cas`,
`leltar_compound`.`smiles` AS `smiles`,
`leltar_category_list`.`category_name` AS `category`,
`leltar_category_list`.`subcategory_name` AS `subcategory`,
`leltar_compound`.`oeb` AS `oeb`,
`leltar_compound`.`mol_weight` AS `mol_weight`,
`leltar_compound`.`melting_point` AS `comp_melt`,
`leltar_compound`.`note` AS `comp_note`,
`leltar_manfac`.`name` AS `manfac`,
`leltar_batch`.`name` AS `batch`,
`leltar_batch`.`lot` AS `lot`,
`leltar_batch`.`date_arr` AS `date_arr`,
`leltar_batch`.`date_open` AS `date_open`,
`leltar_batch`.`date_exp` AS `date_exp`,
`leltar_batch`.`date_arch` AS `date_arch`,
`leltar_batch`.`note` AS `batch_note`,
`leltar_pack`.`size` AS `size`,
`leltar_location_list`.`lab_name` AS `lab`,
`leltar_location_list`.`place_name` AS `place`,
`leltar_location_list`.`sub_name` AS `sub`,
`leltar_pack`.`is_original` AS `is_original`,
`leltar_pack`.`weight` AS `weight`,
`leltar_pack`.`barcode` AS `barcode`,
`leltar_pack`.`note` AS `pack_note`,
`leltar_pack`.`is_active` AS `pack_is_active`
from
(((((`leltar_pack` join `leltar_batch` on((`leltar_pack`.`batch_id` = `leltar_batch`.`batch_id`)))
join `leltar_compound` on((`leltar_batch`.`compound_id` = `leltar_compound`.`compound_id`)))
join `leltar_location_list` on((`leltar_pack`.`location_id` = `leltar_location_list`.`location_id`)))
join `leltar_category_list` on((`leltar_compound`.`sub_category_id` = `leltar_category_list`.`sub_category_id`)))
join `leltar_manfac` on((`leltar_batch`.`manfac_id` = `leltar_manfac`.`manfac_id`)));

--
-- Stand-in structure for view `leltar_barcode`
--
DROP VIEW IF EXISTS `leltar_barcode`;
CREATE TABLE IF NOT EXISTS `leltar_barcode` (
`pack_id` int(11)
,`lab` varchar(63)
,`place` varchar(63)
,`sub` varchar(63)
,`comp` varchar(255)
,`batch` varchar(255)
,`name_alt` varchar(255)
,`abbrev` varchar(15)
,`chem_formula` varchar(255)
);
-- --------------------------------------------------------

--
-- Structure for view `leltar_barcode`
--
DROP TABLE IF EXISTS `leltar_barcode`;

CREATE VIEW `leltar_barcode` AS
select
`leltar_all_info`.`pack_id` AS `pack_id`,
`leltar_all_info`.`lab` AS `lab`,
`leltar_all_info`.`place` AS `place`,
`leltar_all_info`.`sub` AS `sub`,
`leltar_all_info`.`comp` AS `comp`,
`leltar_all_info`.`batch` AS `batch`,
`leltar_all_info`.`name_alt` AS `name_alt`,
`leltar_all_info`.`abbrev` AS `abbrev`,
`leltar_all_info`.`chem_formula` AS `chem_formula`
from `leltar_all_info`
order by `leltar_all_info`.`pack_id`;

--
-- Table structure for table `api_pk_attribute`
--

DROP TABLE IF EXISTS `api_pk_attribute`;
CREATE TABLE IF NOT EXISTS `api_pk_attribute` (
  `pk_attribute_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK attribute ID - PRIMARY KEY',
  `name` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Name',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`pk_attribute_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='PK attributes';

--
-- Triggers `api_pk_attribute`
--
DROP TRIGGER IF EXISTS `api_pk_attribute_before_insert`;
DELIMITER //
CREATE TRIGGER `api_pk_attribute_before_insert` BEFORE INSERT ON `api_pk_attribute`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_pk_attribute_before_update`;
DELIMITER //
CREATE TRIGGER `api_pk_attribute_before_update` BEFORE UPDATE ON `api_pk_attribute`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_pk_attribute_insert`;
DELIMITER //
CREATE TRIGGER `api_pk_attribute_insert` AFTER INSERT ON `api_pk_attribute`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_pk_attribute';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.pk_attribute_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_pk_attribute.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_pk_attribute_update`;
DELIMITER //
CREATE TRIGGER `api_pk_attribute_update` AFTER UPDATE ON `api_pk_attribute`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_pk_attribute';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.pk_attribute_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_pk_attribute.pk_attribute_id */
IF (NEW.pk_attribute_id <> OLD.pk_attribute_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'pk_attribute_id', OLD.pk_attribute_id, NEW.pk_attribute_id);
END IF;

/* api_pk_attribute.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* api_pk_attribute.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Table structure for table `api_summary`
--

DROP TABLE IF EXISTS `api_summary`;
CREATE TABLE IF NOT EXISTS `api_summary` (
  `api_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'API ID - PRIMARY KEY',
  `name` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Name',
  `form` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Form',
  `crystallinity` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Crystallinity',
  `particle_size` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Particle size',
  `status_eval` varchar(63) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Evaluation status',
  `status_dev` varchar(63) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Development status',
  `status_market` varchar(63) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Market status',
  `patent_expire` varchar(31) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Patent expire year (or Generic)',
  `pri_indication` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Primary indication',
  `sec_indication` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Secondary indication',
  `melting_point` varchar(63) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Melting point',
  `solu_water` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Water solubility',
  `solu_hcl` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT '0.1M HCl solubility',
  `note` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Note',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`api_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='API DB';

--
-- Triggers `api_summary`
--
DROP TRIGGER IF EXISTS `api_summary_before_insert`;
DELIMITER //
CREATE TRIGGER `api_summary_before_insert` BEFORE INSERT ON `api_summary`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_summary_before_update`;
DELIMITER //
CREATE TRIGGER `api_summary_before_update` BEFORE UPDATE ON `api_summary`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_summary_insert`;
DELIMITER //
CREATE TRIGGER `api_summary_insert` AFTER INSERT ON `api_summary`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_summary';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.api_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_summary.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

/* api_summary.form */
IF (ISNULL(NEW.form) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'form', NEW.form);
END IF;

/* api_summary.status_eval */
IF (ISNULL(NEW.status_eval) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'status_eval', NEW.status_eval);
END IF;

/* api_summary.status_dev */
IF (ISNULL(NEW.status_dev) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'status_dev', NEW.status_dev);
END IF;

/* api_summary.status_market */
IF (ISNULL(NEW.status_market) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'status_market', NEW.status_market);
END IF;

/* api_summary.patent_expire */
IF (ISNULL(NEW.patent_expire) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'patent_expire', NEW.patent_expire);
END IF;

/* api_summary.pri_indication */
IF (ISNULL(NEW.pri_indication) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'pri_indication', NEW.pri_indication);
END IF;

/* api_summary.sec_indication */
IF (ISNULL(NEW.sec_indication) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'sec_indication', NEW.sec_indication);
END IF;

/* api_summary.melting_point */
IF (ISNULL(NEW.melting_point) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'melting_point', NEW.melting_point);
END IF;

/* api_summary.solu_water */
IF (ISNULL(NEW.solu_water) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'solu_water', NEW.solu_water);
END IF;

/* api_summary.solu_hcl */
IF (ISNULL(NEW.solu_hcl) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'solu_hcl', NEW.solu_hcl);
END IF;

/* api_summary.note */
IF (ISNULL(NEW.note) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'note', NEW.note);
END IF;

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_summary_update`;
DELIMITER //
CREATE TRIGGER `api_summary_update` AFTER UPDATE ON `api_summary`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_summary';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.api_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_summary.api_id */
IF (NEW.api_id <> OLD.api_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'api_id', OLD.api_id, NEW.api_id);
END IF;

/* api_summary.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* api_summary.form */
IF (NEW.form <> OLD.form OR (ISNULL(OLD.form) AND ISNULL(NEW.form) = 0) OR (ISNULL(OLD.form) = 0 AND ISNULL(NEW.form))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'form', OLD.form, NEW.form);
END IF;

/* api_summary.status_eval */
IF (NEW.status_eval <> OLD.status_eval OR (ISNULL(OLD.status_eval) AND ISNULL(NEW.status_eval) =0 ) OR (ISNULL(OLD.status_eval) = 0 AND ISNULL(NEW.status_eval))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'status_eval', OLD.status_eval, NEW.status_eval);
END IF;

/* api_summary.status_dev */
IF (NEW.status_dev <> OLD.status_dev OR (ISNULL(OLD.status_dev) AND ISNULL(NEW.status_dev) = 0) OR (ISNULL(OLD.status_dev) = 0 AND ISNULL(NEW.status_dev))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'status_dev', OLD.status_dev, NEW.status_dev);
END IF;

/* api_summary.status_market */
IF (NEW.status_market <> OLD.status_market OR (ISNULL(OLD.status_market) AND ISNULL(NEW.status_market) = 0) OR (ISNULL(OLD.status_market) = 0 AND ISNULL(NEW.status_market))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'status_market', OLD.status_market, NEW.status_market);
END IF;

/* api_summary.patent_expire */
IF (NEW.patent_expire <> OLD.patent_expire OR (ISNULL(OLD.patent_expire) AND ISNULL(NEW.patent_expire) = 0) OR (ISNULL(OLD.patent_expire) = 0 AND ISNULL(NEW.patent_expire))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'patent_expire', OLD.patent_expire, NEW.patent_expire);
END IF;

/* api_summary.pri_indication */
IF (NEW.pri_indication <> OLD.pri_indication OR (ISNULL(OLD.pri_indication) AND ISNULL(NEW.pri_indication) = 0) OR (ISNULL(OLD.pri_indication) = 0 AND ISNULL(NEW.pri_indication))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'pri_indication', OLD.pri_indication, NEW.pri_indication);
END IF;

/* api_summary.sec_indication */
IF (NEW.sec_indication <> OLD.sec_indication OR (ISNULL(OLD.sec_indication) AND ISNULL(NEW.sec_indication) = 0) OR (ISNULL(OLD.sec_indication) = 0 AND ISNULL(NEW.sec_indication))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'sec_indication', OLD.sec_indication, NEW.sec_indication);
END IF;

/* api_summary.melting_point */
IF (NEW.melting_point <> OLD.melting_point OR (ISNULL(OLD.melting_point) AND ISNULL(NEW.melting_point) = 0) OR (ISNULL(OLD.melting_point) = 0 AND ISNULL(NEW.melting_point))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'melting_point', OLD.melting_point, NEW.melting_point);
END IF;

/* api_summary.solu_water */
IF (NEW.solu_water <> OLD.solu_water OR (ISNULL(OLD.solu_water) AND ISNULL(NEW.solu_water) = 0) OR (ISNULL(OLD.solu_water) = 0 AND ISNULL(NEW.solu_water))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'solu_water', OLD.solu_water, NEW.solu_water);
END IF;

/* api_summary.solu_hcl */
IF (NEW.solu_hcl <> OLD.solu_hcl OR (ISNULL(OLD.solu_hcl) AND ISNULL(NEW.solu_hcl) = 0) OR (ISNULL(OLD.solu_hcl) = 0 AND ISNULL(NEW.solu_hcl))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'solu_hcl', OLD.solu_hcl, NEW.solu_hcl);
END IF;

/* api_summary.note */
IF (NEW.note <> OLD.note OR (ISNULL(OLD.note) AND ISNULL(NEW.note) = 0) OR (ISNULL(OLD.note) = 0 AND ISNULL(NEW.note))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'note', OLD.note, NEW.note);
END IF;

/* api_summary.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Table structure for table `api_drug_product`
--

DROP TABLE IF EXISTS `api_drug_product`;
CREATE TABLE IF NOT EXISTS `api_drug_product` (
  `drug_product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Drug product ID - PRIMARY KEY',
  `api_id` int(11) NOT NULL COMMENT 'API ID - FOREIGN KEY',
  `name` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Name',
  `name_alt` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Alternative name',
  `dosage_form` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Dosage form',
  `crystallinity` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Crystallinity',
  `particle_size` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Particle size',
  `dose_unit_free_form` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Dose unit for free form [mg]',
  `dose_unit_per_day` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Daily dose unit',
  `administration` varchar(127) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Way of administration (fasted/fed)',
  `note` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Note',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`drug_product_id`),
  KEY `FK_api_drug` (`api_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='Drug products';

--
-- RELATIONS FOR TABLE `api_drug_product`:
--   `api_id`
--       `api_summary` -> `api_id`
--

--
-- Triggers `api_drug_product`
--
DROP TRIGGER IF EXISTS `api_drug_product_before_insert`;
DELIMITER //
CREATE TRIGGER `api_drug_product_before_insert` BEFORE INSERT ON `api_drug_product`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_drug_product_before_update`;
DELIMITER //
CREATE TRIGGER `api_drug_product_before_update` BEFORE UPDATE ON `api_drug_product`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_drug_product_insert`;
DELIMITER //
CREATE TRIGGER `api_drug_product_insert` AFTER INSERT ON `api_drug_product`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_drug_product';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.drug_product_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_drug_product.api_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'api_id', NEW.api_id);

/* api_drug_product.name */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name', NEW.name);

/* api_drug_product.name_alt */
IF (ISNULL(NEW.name_alt) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'name_alt', NEW.name_alt);
END IF;

/* api_drug_product.dosage_form */
IF (ISNULL(NEW.dosage_form) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'dosage_form', NEW.dosage_form);
END IF;

/* api_drug_product.crystallinity */
IF (ISNULL(NEW.crystallinity) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'crystallinity', NEW.crystallinity);
END IF;

/* api_drug_product.particle_size */
IF (ISNULL(NEW.particle_size) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'particle_size', NEW.particle_size);
END IF;

/* api_drug_product.dose_unit_free_form */
IF (ISNULL(NEW.dose_unit_free_form) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'dose_unit_free_form', NEW.dose_unit_free_form);
END IF;

/* api_drug_product.dose_unit_per_day */
IF (ISNULL(NEW.dose_unit_per_day) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'dose_unit_per_day', NEW.dose_unit_per_day);
END IF;

/* api_drug_product.administration */
IF (ISNULL(NEW.administration) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'administration', NEW.administration);
END IF;

/* api_drug_product.note */
IF (ISNULL(NEW.note) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'note', NEW.note);
END IF;

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_drug_product_update`;
DELIMITER //
CREATE TRIGGER `api_drug_product_update` AFTER UPDATE ON `api_drug_product`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_drug_product';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.drug_product_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_drug_product.drug_product_id */
IF (NEW.drug_product_id <> OLD.drug_product_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'drug_product_id', OLD.drug_product_id, NEW.drug_product_id);
END IF;

/* api_drug_product.api_id */
IF (NEW.api_id <> OLD.api_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'api_id', OLD.api_id, NEW.api_id);
END IF;

/* api_drug_product.name */
IF (NEW.name <> OLD.name) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name', OLD.name, NEW.name);
END IF;

/* api_drug_product.name_alt */
IF (NEW.name_alt <> OLD.name_alt OR (ISNULL(OLD.name_alt) AND ISNULL(NEW.name_alt) = 0) OR (ISNULL(OLD.name_alt) = 0 AND ISNULL(NEW.name_alt))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'name_alt', OLD.name_alt, NEW.name_alt);
END IF;

/* api_drug_product.dosage_form */
IF (NEW.dosage_form <> OLD.dosage_form OR (ISNULL(OLD.dosage_form) AND ISNULL(NEW.dosage_form) = 0) OR (ISNULL(OLD.dosage_form) = 0 AND ISNULL(NEW.dosage_form))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'dosage_form', OLD.dosage_form, NEW.dosage_form);
END IF;

/* api_drug_product.crystallinity */
IF (NEW.crystallinity <> OLD.crystallinity OR (ISNULL(OLD.crystallinity) AND ISNULL(NEW.crystallinity) = 0) OR (ISNULL(OLD.crystallinity) = 0 AND ISNULL(NEW.crystallinity))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'crystallinity', OLD.crystallinity, NEW.crystallinity);
END IF;

/* api_drug_product.particle_size */
IF (NEW.particle_size <> OLD.particle_size OR (ISNULL(OLD.particle_size) AND ISNULL(NEW.particle_size) = 0) OR (ISNULL(OLD.particle_size) = 0 AND ISNULL(NEW.particle_size))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'particle_size', OLD.particle_size, NEW.particle_size);
END IF;

/* api_drug_product.dose_unit_free_form */
IF (NEW.dose_unit_free_form <> OLD.dose_unit_free_form OR (ISNULL(OLD.dose_unit_free_form) AND ISNULL(NEW.dose_unit_free_form) = 0) OR (ISNULL(OLD.dose_unit_free_form) = 0 AND ISNULL(NEW.dose_unit_free_form))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'dose_unit_free_form', OLD.dose_unit_free_form, NEW.dose_unit_free_form);
END IF;

/* api_drug_product.dose_unit_per_day */
IF (NEW.dose_unit_per_day <> OLD.dose_unit_per_day OR (ISNULL(OLD.dose_unit_per_day) AND ISNULL(NEW.dose_unit_per_day) = 0) OR (ISNULL(OLD.dose_unit_per_day) = 0 AND ISNULL(NEW.dose_unit_per_day))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'dose_unit_per_day', OLD.dose_unit_per_day, NEW.dose_unit_per_day);
END IF;

/* api_drug_product.administration */
IF (NEW.administration <> OLD.administration OR (ISNULL(OLD.administration) AND ISNULL(NEW.administration) = 0) OR (ISNULL(OLD.administration) = 0 AND ISNULL(NEW.administration))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'administration', OLD.administration, NEW.administration);
END IF;

/* api_drug_product.note */
IF (NEW.note <> OLD.note OR (ISNULL(OLD.note) AND ISNULL(NEW.note) = 0) OR (ISNULL(OLD.note) = 0 AND ISNULL(NEW.note))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'note', OLD.note, NEW.note);
END IF;

/* api_drug_product.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_drug_product`
--
ALTER TABLE `api_drug_product`
  ADD CONSTRAINT `FK_api_drug` FOREIGN KEY (`api_id`) REFERENCES `api_summary` (`api_id`) ON DELETE NO ACTION;

--
-- Table structure for table `api_literature`
--

DROP TABLE IF EXISTS `api_literature`;
CREATE TABLE IF NOT EXISTS `api_literature` (
  `api_literature_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'API literature ID - PRIMARY KEY',
  `api_id` int(11) NOT NULL COMMENT 'API ID - FOREIGN KEY',
  `title` varchar(511) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Title',
  `author` varchar(511) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Author',
  `year` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Year',
  `journal` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Journal',
  `note` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Note',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`api_literature_id`),
  KEY `FK_api_literature_api_id` (`api_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='API literature DB';

--
-- RELATIONS FOR TABLE `api_literature`:
--   `api_id`
--       `api_summary` -> `api_id`
--

--
-- Triggers `api_literature`
--
DROP TRIGGER IF EXISTS `api_literature_before_insert`;
DELIMITER //
CREATE TRIGGER `api_literature_before_insert` BEFORE INSERT ON `api_literature`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_literature_before_update`;
DELIMITER //
CREATE TRIGGER `api_literature_before_update` BEFORE UPDATE ON `api_literature`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_literature_insert`;
DELIMITER //
CREATE TRIGGER `api_literature_insert` AFTER INSERT ON `api_literature`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_literature';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user=NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.api_literature_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_literature.api_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'api_id', NEW.api_id);

/* api_literature.title */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'title', NEW.title);

/* api_literature.author */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'author', NEW.author);
 
/* api_literature.year */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'year', NEW.year);
  
/* api_literature.journal */
IF (ISNULL(NEW.journal) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'journal', NEW.journal);
END IF;

/* api_literature.note */
IF (ISNULL(NEW.note) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'note', NEW.note);
END IF;

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_literature_update`;
DELIMITER //
CREATE TRIGGER `api_literature_update` AFTER UPDATE ON `api_literature`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_literature';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.api_literature_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_literature.api_literature_id */
IF (NEW.api_literature_id <> OLD.api_literature_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'api_literature_id', OLD.api_literature_id, NEW.api_literature_id);
END IF;

/* api_literature.api_id */
IF (NEW.api_id <> OLD.api_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'api_id', OLD.api_id, NEW.api_id);
END IF;

/* api_literature.title */
IF (NEW.title <> OLD.title) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'title', OLD.title, NEW.title);
END IF;

/* api_literature.author */
IF (NEW.author <> OLD.author) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'author', OLD.author, NEW.author);
END IF;

/* api_literature.year */
IF (NEW.year <> OLD.year) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'year', OLD.year, NEW.year);
END IF;

/* api_literature.journal */
IF (NEW.journal <> OLD.journal OR (ISNULL(OLD.journal) AND ISNULL(NEW.journal) = 0) OR (ISNULL(OLD.journal) = 0 AND ISNULL(NEW.journal))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'journal', OLD.journal, NEW.journal);
END IF;

/* api_literature.note */
IF (NEW.note <> OLD.note OR (ISNULL(OLD.note) AND ISNULL(NEW.note) = 0) OR (ISNULL(OLD.note) = 0 AND ISNULL(NEW.note))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'note', OLD.note, NEW.note);
END IF;

/* api_literature.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_literature`
--
ALTER TABLE `api_literature`
  ADD CONSTRAINT `FK_api_literature_api_id` FOREIGN KEY (`api_id`) REFERENCES `api_summary` (`api_id`) ON DELETE NO ACTION;

--
-- Table structure for table `api_pk_data`
--

DROP TABLE IF EXISTS `api_pk_data`;
CREATE TABLE IF NOT EXISTS `api_pk_data` (
  `pk_data_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK data ID - PRIMARY ID',
  `drug_product_id` int(11) NOT NULL COMMENT 'Drug product ID - FOREIGN KEY',
  `pk_attribute_id` int(11) NOT NULL COMMENT 'PK attribute ID - FOREIGN KEY',
  `value` varchar(63) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Value',
  `note` varchar(511) COLLATE utf8_hungarian_ci DEFAULT NULL COMMENT 'Note',
  `last_mod_by` varchar(127) COLLATE utf8_hungarian_ci NOT NULL COMMENT 'Last modified by user',
  `last_mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified on',
  PRIMARY KEY (`pk_data_id`),
  KEY `FK_pk_attribute` (`pk_attribute_id`),
  KEY `FK_drug_product` (`drug_product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci COMMENT='PK data';

--
-- RELATIONS FOR TABLE `api_pk_data`:
--   `drug_product_id`
--       `api_drug_product` -> `drug_product_id`
--   `pk_attribute_id`
--       `api_pk_attribute` -> `pk_attribute_id`
--

--
-- Triggers `api_pk_data`
--
DROP TRIGGER IF EXISTS `api_pk_data_before_insert`;
DELIMITER //
CREATE TRIGGER `api_pk_data_before_insert` BEFORE INSERT ON `api_pk_data`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_pk_data_before_update`;
DELIMITER //
CREATE TRIGGER `api_pk_data_before_update` BEFORE UPDATE ON `api_pk_data`
 FOR EACH ROW BEGIN
  SET @user = SUBSTRING_INDEX(USER(),'@',1);

  IF (@user <> 'LELTAR_USER' OR NEW.last_mod_by = '') THEN
    SET NEW.last_mod_by = @user;
  END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_pk_data_insert`;
DELIMITER //
CREATE TRIGGER `api_pk_data_insert` AFTER INSERT ON `api_pk_data`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_pk_data';
  
SET act = 'CREATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.pk_data_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_pk_data.drug_product_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'drug_product_id', NEW.drug_product_id);

/* api_pk_data.pk_attribute_id */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'pk_attribute_id', NEW.pk_attribute_id);

/* api_pk_data.value */
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'value', NEW.value);

/* api_pk_data.note */
IF (ISNULL(NEW.note) = 0) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, new_value)
  VALUES (@sum_id, 'note', NEW.note);
END IF;

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `api_pk_data_update`;
DELIMITER //
CREATE TRIGGER `api_pk_data_update` AFTER UPDATE ON `api_pk_data`
 FOR EACH ROW BEGIN

/*
* Trigger info
*****************/

DECLARE table_n varchar(32);
DECLARE act varchar(16);

SET table_n = 'api_pk_data';
  
SET act = 'UPDATE';
  

/*
* Insert summary row to changelog
************************************/

/* Gets current user name */
SET @user = SUBSTRING_INDEX(USER(),'@',1);

IF (@user = 'leltar_USER') THEN
  SET @user = NEW.last_mod_by;
END IF;

/* Insert summary row */
INSERT INTO inventory.changelog_summary (table_name, record_id, action, modified_by, modified_on)
  VALUES (table_n, NEW.pk_data_id, act, @user, CURRENT_TIMESTAMP);

/*
* INSERT details to changelog  *
************************************/

/* Get summary_id */
SET @sum_id = LAST_INSERT_ID();

/* api_pk_data.pk_data_id */
IF (NEW.pk_data_id <> OLD.pk_data_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'pk_data_id', OLD.pk_data_id, NEW.pk_data_id);
END IF;

/* api_pk_data.drug_product_id */
IF (NEW.drug_product_id <> OLD.drug_product_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'drug_product_id', OLD.drug_product_id, NEW.drug_product_id);
END IF;

/* api_pk_data.pk_attribute_id */
IF (NEW.pk_attribute_id <> OLD.pk_attribute_id) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'pk_attribute_id', OLD.pk_attribute_id, NEW.pk_attribute_id);
END IF;

/* api_pk_data.value */
IF (NEW.value <> OLD.value) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'value', OLD.value, NEW.value);
END IF;

/* api_pk_data.note */
IF (NEW.note <> OLD.note OR (ISNULL(OLD.note) AND ISNULL(NEW.note) = 0) OR (ISNULL(OLD.note) = 0 AND ISNULL(NEW.note))) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'note', OLD.note, NEW.note);
END IF;

/* api_pk_data.last_mod_by */
IF (NEW.last_mod_by <> OLD.last_mod_by) THEN
INSERT INTO inventory.changelog_detail (summary_id, column_name, old_value, new_value)
  VALUES (@sum_id, 'last_mod_by', OLD.last_mod_by, NEW.last_mod_by);
END IF;

END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_pk_data`
--
ALTER TABLE `api_pk_data`
  ADD CONSTRAINT `FK_drug_product` FOREIGN KEY (`drug_product_id`) REFERENCES `api_drug_product` (`drug_product_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_pk_attribute` FOREIGN KEY (`pk_attribute_id`) REFERENCES `api_pk_attribute` (`pk_attribute_id`) ON DELETE NO ACTION;