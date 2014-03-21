-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 03 月 21 日 05:22
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `checks`
--

INSERT INTO `checks` (`cID`, `checkno`, `holder`, `invoicedate`, `purpose`, `money`, `sID`, `duedate`, `receiptor`, `memo`) VALUES
(1, '03485873', 0, '2014-03-13', '购买投影机灯泡', '1300.00', 5, NULL, NULL, '政采'),
(2, '03897584', 1, '2014-03-16', '墨粉', '3300.00', 6, '2014-03-18', '孙健', '教务处速印机碳粉'),
(3, '03538234', 0, '2014-03-16', '我俄方', '1234.56', 5, '2014-03-17', '为俄文', '为');

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
  `mID` int(10) unsigned NOT NULL COMMENT '成员ID',
  `detail` varchar(45) NOT NULL COMMENT '使用详细说明，新购时填写对应发票ID',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`dID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='出入库表' AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `depots`
--

INSERT INTO `depots` (`dID`, `iID`, `operate`, `amount`, `price`, `datetime`, `mID`, `detail`, `memo`) VALUES
(1, 6, 'XG1', 10, NULL, '2014-03-13 00:00:00', 1, '打印机耗材更换预留', '1月预留'),
(2, 6, 'SY0', 3, NULL, '2014-03-13 08:31:52', 3, '教务处更换', '教务处3台打印机更换耗材'),
(3, 6, 'SY0', 1, NULL, '2014-03-13 08:34:21', 3, '招生办更换', ''),
(4, 6, 'JH0', 2, NULL, '2014-03-13 08:36:36', 1, '长时间未使用报废', '因未即使使用导致2个硒鼓过期，不能使用。'),
(5, 6, 'SY1', 1, NULL, '2014-03-13 16:38:02', 3, '教务处有一个未使用', '教务处只更换了2个'),
(6, 6, 'SY0', 3, NULL, '2014-03-13 16:40:59', 3, '校办', ''),
(7, 9, 'XG1', 10, NULL, '2014-03-11 17:16:01', 1, '备用耗材', ''),
(8, 9, 'SY0', 2, NULL, '2014-03-13 17:18:00', 1, 'AP更换', 'AP111'),
(9, 8, 'XG1', 1, NULL, '2014-03-11 22:13:52', 4, '图书馆购买', ''),
(10, 8, 'SY0', 1, NULL, '2014-03-13 22:20:18', 5, '副校长', ''),
(16, 7, 'XG1', 2, NULL, '2014-03-12 22:46:56', 11, '图书馆购', ''),
(17, 8, 'JH0', 1, NULL, '2014-03-13 23:08:41', 12, '大港五中', ''),
(18, 8, 'JH1', 1, NULL, '2014-03-13 23:14:06', 13, '大港五中还', ''),
(19, 7, 'JH0', 1, NULL, '2014-03-11 23:29:23', 12, '教务处借', ''),
(20, 7, 'BF0', 1, NULL, '2014-03-13 23:38:40', 12, '损坏报废', ''),
(21, 6, 'XG1', 5, NULL, '2014-03-14 14:00:06', 5, '备用', ''),
(22, 6, 'SY0', 2, NULL, '2014-03-14 22:37:29', 3, '全球万恶请问', '');

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
  `money` decimal(9,2) unsigned NOT NULL COMMENT '发票金额',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='货物表' AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `items`
--

INSERT INTO `items` (`iID`, `name`, `brand`, `type`, `unit`, `amount`, `tID`, `memo`) VALUES
(6, '硒鼓', 'HP', '88A', '个', 5, 1, '打印机原装硒鼓'),
(7, '打印机', '惠普', 'P1108', '台', 0, 2, '办公用打印机配件88A'),
(8, '计算机', '戴尔', 'P5Z1', '台', 1, 2, '戴尔台式机'),
(9, '硒鼓', 'HP', '128A', '个', 8, 1, 'HP 1525n 耗材');

-- --------------------------------------------------------

--
-- 表的结构 `itemtype`
--

CREATE TABLE IF NOT EXISTS `itemtype` (
  `tID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL COMMENT '货物类别名称',
  PRIMARY KEY (`tID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='货物类别表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `itemtype`
--

INSERT INTO `itemtype` (`tID`, `name`) VALUES
(1, '办公消耗品'),
(2, '电脑设备'),
(3, '网络设备'),
(4, '固定资产');

-- --------------------------------------------------------

--
-- 表的结构 `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `lID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '用户ID',
  `table` varchar(15) NOT NULL COMMENT '表名',
  `op` varchar(3) NOT NULL COMMENT '操作：增加NEW，修改PUT，删除DEL',
  `opID` int(10) unsigned NOT NULL COMMENT '操作ID',
  `time` datetime NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`lID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='日志表' AUTO_INCREMENT=29 ;

--
-- 转存表中的数据 `logs`
--

INSERT INTO `logs` (`lID`, `uid`, `table`, `op`, `opID`, `time`) VALUES
(1, 1, 'items', 'PUT', 7, '2014-03-13 23:38:40'),
(2, 1, 'depots', 'NEW', 20, '2014-03-13 23:38:40'),
(3, 1, 'items', 'PUT', 6, '2014-03-14 14:00:06'),
(4, 1, 'depots', 'NEW', 21, '2014-03-14 14:00:06'),
(5, 1, 'items', 'PUT', 6, '2014-03-14 22:37:29'),
(6, 1, 'depots', 'NEW', 22, '2014-03-14 22:37:29'),
(7, 1, 'suppliers', 'NEW', 7, '2014-03-15 09:18:00'),
(8, 1, 'suppliers', 'DEL', 7, '2014-03-15 12:01:03'),
(9, 1, 'suppliers', 'PUT', 6, '2014-03-15 12:09:26'),
(10, 1, 'suppliers', 'NEW', 8, '2014-03-15 12:10:01'),
(11, 1, 'suppliers', 'PUT', 8, '2014-03-15 12:10:07'),
(12, 1, 'suppliers', 'DEL', 8, '2014-03-15 12:10:10'),
(13, 1, 'items', 'PUT', 6, '2014-03-15 12:21:34'),
(14, 1, 'items', 'PUT', 8, '2014-03-15 12:36:20'),
(15, 1, 'items', 'PUT', 7, '2014-03-15 12:36:33'),
(16, 1, 'items', 'NEW', 14, '2014-03-15 18:41:54'),
(17, 1, 'items', 'PUT', 14, '2014-03-15 18:42:03'),
(18, 1, 'items', 'DEL', 14, '2014-03-15 18:42:07'),
(19, 1, 'checks', 'NEW', 1, '2014-03-16 01:31:28'),
(20, 1, 'itemtype', 'NEW', 4, '2014-03-16 01:45:42'),
(21, 1, 'checks', 'NEW', 2, '2014-03-16 10:00:43'),
(22, 1, 'checks', 'PUT', 2, '2014-03-16 18:01:23'),
(23, 1, 'users', 'NEW', 4, '2014-03-16 22:50:24'),
(24, 1, 'users', 'NEW', 5, '2014-03-16 23:18:18'),
(25, 1, 'suppliers', 'PUT', 6, '2014-03-17 16:20:57'),
(26, 1, 'suppliers', 'NEW', 9, '2014-03-17 16:21:34'),
(27, 1, 'checks', 'NEW', 3, '2014-03-17 21:29:31'),
(28, 1, 'checks', 'PUT', 3, '2014-03-17 21:29:40');

-- --------------------------------------------------------

--
-- 表的结构 `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `mID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '成员姓名',
  PRIMARY KEY (`mID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='成员表' AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `members`
--

INSERT INTO `members` (`mID`, `name`) VALUES
(1, '管理员'),
(2, '访客'),
(3, '工程师'),
(4, '赵国维'),
(5, '高振'),
(11, '工程师2'),
(12, '王黎晨'),
(13, '赵昕');

-- --------------------------------------------------------

--
-- 表的结构 `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `sID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(30) NOT NULL COMMENT '供应商全称',
  `name` varchar(10) NOT NULL COMMENT '供应商简称',
  `taxid` varchar(25) NOT NULL,
  `memo` varchar(100) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`sID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `suppliers`
--

INSERT INTO `suppliers` (`sID`, `fullname`, `name`, `taxid`, `memo`) VALUES
(1, '天津鸿阳嘉业科技有限公司', '鸿阳嘉业', '', '打印机耗材'),
(5, '中国联合通信有限公司天津分公司', '天津联通', '', '光纤网络服务'),
(6, '天津市仁和办公设备有限公司', '仁和办公', '120104600840863', '日本京瓷天津总代理，复印机维修，耗材购买。'),
(9, '天津金旭恒信科技有限公司', '金旭恒信', '120105668847703', '');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(16) NOT NULL DEFAULT 'guest' COMMENT '登录名',
  `passwd` varchar(32) NOT NULL DEFAULT 'guest' COMMENT '登录密码',
  `mID` int(10) unsigned DEFAULT NULL COMMENT '用户姓名',
  `role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户角色:',
  PRIMARY KEY (`uID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`uID`, `uname`, `passwd`, `mID`, `role`) VALUES
(1, 'admin', 'admin', 1, 5),
(2, 'guest', 'guest', 2, 0),
(3, 'abc', 'abc', 3, 2),
(4, 'zgw', 'colonia', 4, 4),
(5, 'zhaoxin', 'zhaoxin', 13, 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
