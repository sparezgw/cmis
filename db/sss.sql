SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `cmis` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `cmis` ;

-- -----------------------------------------------------
-- Table `cmis`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`users` (
  `uid` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(16) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `role` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`uid`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `cmis`.`items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`items` (
  `iid` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL COMMENT '货物名称',
  `brand` VARCHAR(20) NULL COMMENT '货物品牌',
  `type` VARCHAR(20) NULL COMMENT '货物型号',
  `unit` VARCHAR(10) NULL COMMENT '货物单位',
  `quantity` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存数量',
  `memo` VARCHAR(255) NULL,
  PRIMARY KEY (`iid`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cmis`.`depot`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`depot` (
  `did` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `itemid` INT NOT NULL,
  PRIMARY KEY (`did`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
