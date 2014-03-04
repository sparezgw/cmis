/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : cmis

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-02-27 17:54:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for checks
-- ----------------------------
DROP TABLE IF EXISTS `checks`;
CREATE TABLE `checks` (
  `cID` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '支票ID',
  `number` varchar(8) NOT NULL COMMENT '支票号',
  `oID` smallint(5) unsigned NOT NULL COMMENT '公司ID',
  `content` varchar(50) DEFAULT NULL COMMENT '付款原因',
  `money` float(8,2) unsigned NOT NULL COMMENT '支票金额',
  `invoicedate` date NOT NULL COMMENT '开票日期',
  `duedate` date DEFAULT NULL COMMENT '支付日期',
  `receiptor` varchar(10) DEFAULT NULL COMMENT '领取人',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`cID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of checks
-- ----------------------------

-- ----------------------------
-- Table structure for incoming
-- ----------------------------
DROP TABLE IF EXISTS `incoming`;
CREATE TABLE `incoming` (
  `inID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '入库ID',
  `iID` int(10) unsigned NOT NULL COMMENT '入库货物ID',
  `number` int(4) unsigned NOT NULL COMMENT '入库数量',
  `rID` varchar(255) DEFAULT NULL COMMENT '对应发票ID',
  `isIn` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已经执行入库操作',
  `memo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`inID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of incoming
-- ----------------------------

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `iID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '物品ID',
  `name` varchar(50) NOT NULL COMMENT '物品名称',
  `brand` varchar(25) DEFAULT NULL COMMENT '物品品牌',
  `type` varchar(25) DEFAULT NULL COMMENT '物品型号',
  `unit` varchar(4) DEFAULT NULL COMMENT '物品单位',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`iID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of items
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'user ID',
  `uname` varchar(15) NOT NULL COMMENT '用户名',
  `passwd` varchar(15) NOT NULL COMMENT '密码',
  `role` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户角色',
  PRIMARY KEY (`uID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'admin', '0');
