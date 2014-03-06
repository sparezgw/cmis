-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 03 月 06 日 01:57
-- 服务器版本: 5.5.28
-- PHP 版本: 5.4.24

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `cmis`
--

-- --------------------------------------------------------

--
-- 表的结构 `assets`
--

CREATE TABLE IF NOT EXISTS `assets` (
  `aID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dID` int(10) unsigned NOT NULL COMMENT '出入库ID',
  `assetno` varchar(10) DEFAULT NULL COMMENT '固定资产统一编号',
  `internalno` varchar(10) NOT NULL COMMENT '校内固定资产编号',
  `departno` varchar(50) DEFAULT NULL COMMENT '电教资产编号',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`aID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='固定资产表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `checks`
--

CREATE TABLE IF NOT EXISTS `checks` (
  `cID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `checkno` varchar(10) NOT NULL COMMENT '支票号',
  `holder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付账户',
  `invoicedate` date NOT NULL COMMENT '开票日期',
  `purpose` varchar(45) NOT NULL COMMENT '支付用途',
  `money` decimal(9,2) unsigned NOT NULL COMMENT '支票金额',
  `sID` int(10) unsigned NOT NULL COMMENT '供应商ID',
  `duedate` date DEFAULT NULL COMMENT '领取日期',
  `receiptor` varchar(10) DEFAULT NULL COMMENT '领取人',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`cID`),
  UNIQUE KEY `checkno_UNIQUE` (`checkno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `depots`
--

CREATE TABLE IF NOT EXISTS `depots` (
  `dID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iID` int(10) unsigned NOT NULL COMMENT '货物ID',
  `operate` varchar(3) NOT NULL DEFAULT 'XG0' COMMENT '出入库指令',
  `amount` mediumint(8) unsigned NOT NULL COMMENT '数量',
  `price` decimal(9,2) unsigned DEFAULT NULL COMMENT '单价',
  `datetime` datetime NOT NULL COMMENT '操作时间',
  `user` varchar(10) NOT NULL COMMENT '操作人',
  `detail` varchar(45) NOT NULL COMMENT '使用详细说明，新购时填写对应发票ID',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`dID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='出入库表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `vID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoiceno` varchar(15) NOT NULL COMMENT '发票号',
  `holder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付来源',
  `invoicedate` date NOT NULL COMMENT '发票日期',
  `sID` mediumint(8) unsigned NOT NULL COMMENT '供应商ID',
  `items` text NOT NULL COMMENT '货物明细 出入库ID',
  `projectno` varchar(25) DEFAULT NULL COMMENT '政采计划编号',
  `cID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支票号',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`vID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `iID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '货物名称',
  `brand` varchar(20) DEFAULT NULL COMMENT '货物品牌',
  `type` varchar(20) DEFAULT NULL COMMENT '货物型号',
  `unit` varchar(10) DEFAULT NULL COMMENT '货物单位',
  `amount` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '库存数量',
  `tID` tinyint(3) unsigned DEFAULT NULL COMMENT '货物类别ID',
  `memo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`iID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='货物表' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `itemtype`
--

CREATE TABLE IF NOT EXISTS `itemtype` (
  `tID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL COMMENT '货物类别名称',
  PRIMARY KEY (`tID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='货物类别表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `itemtype`
--

INSERT INTO `itemtype` (`tID`, `name`) VALUES
(1, '办公消耗品'),
(2, '电脑设备'),
(3, '网络设备');

-- --------------------------------------------------------

--
-- 表的结构 `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `lID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '用户ID',
  `table` varchar(15) NOT NULL COMMENT '表名',
  `op` varchar(3) NOT NULL COMMENT '操作：增加I，修改U，删除D',
  `opID` int(10) unsigned NOT NULL COMMENT '操作ID',
  PRIMARY KEY (`lID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日志表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `operate`
--

CREATE TABLE IF NOT EXISTS `operate` (
  `oID` varchar(3) NOT NULL COMMENT '指令代码',
  `command` varchar(10) NOT NULL COMMENT '指令名称',
  `memo` varchar(45) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`oID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='出入库指令表';

-- --------------------------------------------------------

--
-- 表的结构 `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `sID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(30) NOT NULL COMMENT '供应商全称',
  `name` varchar(10) DEFAULT NULL COMMENT '供应商简称',
  `memo` varchar(100) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`sID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `suppliers`
--

INSERT INTO `suppliers` (`sID`, `fullname`, `name`, `memo`) VALUES
(1, '天津鸿阳嘉业科技有限公司', '鸿阳嘉业', '打印机耗材'),
(5, '中国联合通信有限公司天津分公司', '天津联通', '光纤网络服务'),
(6, '天津市仁和办公设备有限公司', '仁和办公', '日本京瓷天津总代理，复印机维修，耗材购买');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(16) NOT NULL DEFAULT 'guest' COMMENT '登录名',
  `passwd` varchar(32) NOT NULL DEFAULT 'guest' COMMENT '登录密码',
  `name` varchar(10) DEFAULT NULL COMMENT '用户姓名',
  `role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户角色:',
  PRIMARY KEY (`uID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`uID`, `uname`, `passwd`, `name`, `role`) VALUES
(1, 'admin', 'admin', '管理员', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
