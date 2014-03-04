SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `cmis` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `cmis` ;

-- -----------------------------------------------------
-- Table `cmis`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`users` (
  `uID` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uname` VARCHAR(16) NOT NULL DEFAULT 'guest' COMMENT '登录名',
  `passwd` VARCHAR(32) NOT NULL DEFAULT 'guest' COMMENT '登录密码',
  `name` VARCHAR(10) NULL COMMENT '用户姓名',
  `role` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户角色:' /* comment truncated */ /*0-guest
1-admin*/,
  PRIMARY KEY (`uID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `cmis`.`items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`items` (
  `iID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL COMMENT '货物名称',
  `brand` VARCHAR(20) NULL COMMENT '货物品牌',
  `type` VARCHAR(20) NULL COMMENT '货物型号',
  `unit` VARCHAR(10) NULL COMMENT '货物单位',
  `amount` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存数量',
  `itID` TINYINT UNSIGNED NULL COMMENT '货物类别ID',
  `memo` VARCHAR(255) NULL,
  PRIMARY KEY (`iID`))
ENGINE = InnoDB
COMMENT = '货物表';


-- -----------------------------------------------------
-- Table `cmis`.`depots`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`depots` (
  `dID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `iID` INT UNSIGNED NOT NULL COMMENT '货物ID',
  `operate` VARCHAR(3) NOT NULL DEFAULT 'XG0' COMMENT '出入库指令' /* comment truncated */ /*��对应指令表中的ID
默认为新购未操作，XG0*/,
  `amount` MEDIUMINT UNSIGNED NOT NULL COMMENT '数量',
  `price` DECIMAL(9,2) UNSIGNED NULL COMMENT '单价',
  `datetime` DATETIME NOT NULL COMMENT '操作时间',
  `user` VARCHAR(10) NOT NULL COMMENT '操作人',
  `detail` VARCHAR(45) NOT NULL COMMENT '使用详细说明，新购时填写对应发票ID',
  `memo` VARCHAR(255) NULL COMMENT '备注',
  PRIMARY KEY (`dID`))
ENGINE = InnoDB
COMMENT = '出入库表';


-- -----------------------------------------------------
-- Table `cmis`.`itemtype`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`itemtype` (
  `itID` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(15) NOT NULL COMMENT '货物类别名称',
  PRIMARY KEY (`itID`))
ENGINE = InnoDB
COMMENT = '货物类别表';


-- -----------------------------------------------------
-- Table `cmis`.`suppliers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`suppliers` (
  `sID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(30) NOT NULL COMMENT '供应商全称',
  `name` VARCHAR(10) NULL COMMENT '供应商简称',
  `memo` VARCHAR(100) NULL COMMENT '备注',
  PRIMARY KEY (`sID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cmis`.`checks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`checks` (
  `cID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `checkno` VARCHAR(10) NOT NULL COMMENT '支票号',
  `holder` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付账户' /* comment truncated */ /*
0-外大附校
1-小外培训中心*/,
  `invoicedate` DATE NOT NULL COMMENT '开票日期',
  `money` DECIMAL(9,2) UNSIGNED NOT NULL COMMENT '支票金额',
  `sID` INT UNSIGNED NOT NULL COMMENT '供应商ID',
  `duedate` DATE NULL COMMENT '领取日期',
  `receiptor` VARCHAR(10) NULL COMMENT '领取人',
  `memo` VARCHAR(255) NULL COMMENT '备注',
  PRIMARY KEY (`cID`),
  UNIQUE INDEX `checkno_UNIQUE` (`checkno` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cmis`.`invoices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`invoices` (
  `vID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoiceno` VARCHAR(15) NOT NULL COMMENT '发票号',
  `holder` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付来源',
  `invoicedate` DATE NOT NULL COMMENT '发票日期',
  `sID` MEDIUMINT UNSIGNED NOT NULL COMMENT '供应商ID',
  `items` TEXT NOT NULL COMMENT '货物明细 出入库ID',
  `projectno` VARCHAR(25) NULL COMMENT '政采计划编号',
  `cID` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '支票号',
  `memo` VARCHAR(255) NULL COMMENT '备注',
  PRIMARY KEY (`vID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cmis`.`operate`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`operate` (
  `oID` VARCHAR(3) NOT NULL COMMENT '指令代码',
  `command` VARCHAR(10) NOT NULL COMMENT '指令名称',
  `memo` VARCHAR(45) NULL COMMENT '备注',
  PRIMARY KEY (`oID`))
ENGINE = InnoDB
COMMENT = '出入库指令表';


-- -----------------------------------------------------
-- Table `cmis`.`assets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`assets` (
  `aID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `dID` INT UNSIGNED NOT NULL COMMENT '出入库ID',
  `assetno` VARCHAR(10) NULL COMMENT '固定资产统一编号',
  `internalno` VARCHAR(10) NOT NULL COMMENT '校内固定资产编号',
  `departno` VARCHAR(50) NULL COMMENT '电教资产编号',
  `memo` VARCHAR(255) NULL COMMENT '备注',
  PRIMARY KEY (`aID`))
ENGINE = InnoDB
COMMENT = '固定资产表';


-- -----------------------------------------------------
-- Table `cmis`.`logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmis`.`logs` (
  `lID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` TINYINT UNSIGNED NOT NULL COMMENT '用户ID',
  `table` VARCHAR(15) NOT NULL COMMENT '表名',
  `op` VARCHAR(3) NOT NULL COMMENT '操作：增加I，修改U，删除D',
  `opID` INT UNSIGNED NOT NULL COMMENT '操作ID',
  PRIMARY KEY (`lID`))
ENGINE = InnoDB
COMMENT = '日志表';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
