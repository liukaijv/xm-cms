/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : fanjin

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2016-03-02 10:40:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xm_ad
-- ----------------------------
DROP TABLE IF EXISTS `xm_ad`;
CREATE TABLE `xm_ad` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(255) NOT NULL,
  `ad_url` varchar(255) DEFAULT NULL,
  `default_image` varchar(255) DEFAULT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `pos_id` int(11) NOT NULL,
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_ad
-- ----------------------------
INSERT INTO `xm_ad` VALUES ('1', 'banner01', '', 'uploadfile/ad/14568356066729.jpg', '1456835606', '1');
INSERT INTO `xm_ad` VALUES ('2', 'banner02', '', 'uploadfile/ad/14568356315983.jpg', '1456835631', '1');
INSERT INTO `xm_ad` VALUES ('3', 'banner03', '', 'uploadfile/ad/14568356436566.jpg', '1456835643', '1');

-- ----------------------------
-- Table structure for xm_admin
-- ----------------------------
DROP TABLE IF EXISTS `xm_admin`;
CREATE TABLE `xm_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_username` varchar(50) NOT NULL,
  `admin_userpwd` varchar(64) NOT NULL,
  `type_id` int(11) DEFAULT '0',
  `real_name` varchar(50) DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `last_login_time` int(10) NOT NULL DEFAULT '0',
  `add_time` int(10) DEFAULT '0',
  `sex` varchar(4) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_admin
-- ----------------------------
INSERT INTO `xm_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '0', 'admin', '127.0.0.1', '0', '0', '男', '');

-- ----------------------------
-- Table structure for xm_ad_pos
-- ----------------------------
DROP TABLE IF EXISTS `xm_ad_pos`;
CREATE TABLE `xm_ad_pos` (
  `pos_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `width` int(255) DEFAULT NULL,
  `height` int(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_blank` tinyint(4) NOT NULL DEFAULT '0',
  `add_time` int(255) DEFAULT '0',
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_ad_pos
-- ----------------------------
INSERT INTO `xm_ad_pos` VALUES ('1', '轮播焦点图', '1000', '350', '', '0', '1456834081');

-- ----------------------------
-- Table structure for xm_article
-- ----------------------------
DROP TABLE IF EXISTS `xm_article`;
CREATE TABLE `xm_article` (
  `art_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `cate_id` int(11) DEFAULT NULL,
  `art_title` varchar(200) NOT NULL,
  `default_image` varchar(200) DEFAULT NULL,
  `art_content` text,
  `art_from` varchar(200) DEFAULT NULL,
  `art_author` varchar(200) DEFAULT NULL,
  `view_times` int(11) NOT NULL DEFAULT '0',
  `is_top` tinyint(4) NOT NULL DEFAULT '0',
  `is_recom` tinyint(4) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '255',
  `add_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`art_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_article
-- ----------------------------
INSERT INTO `xm_article` VALUES ('2', '11', null, '测试文章', '', '测试内容', '本站', 'admin', '0', '0', '1', '255', '1456731751');

-- ----------------------------
-- Table structure for xm_case
-- ----------------------------
DROP TABLE IF EXISTS `xm_case`;
CREATE TABLE `xm_case` (
  `case_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `case_name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `from` varchar(255) NOT NULL,
  `default_image` varchar(255) NOT NULL,
  `default_video` varchar(255) NOT NULL,
  `images` text NOT NULL,
  `detail` text NOT NULL,
  `is_top` tinyint(4) NOT NULL DEFAULT '0',
  `is_recom` tinyint(4) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL,
  `view_times` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`case_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_case
-- ----------------------------

-- ----------------------------
-- Table structure for xm_feedback
-- ----------------------------
DROP TABLE IF EXISTS `xm_feedback`;
CREATE TABLE `xm_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sex` varchar(5) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `qq` varchar(16) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `content` text,
  `is_check` tinyint(4) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `reply_name` varchar(50) DEFAULT NULL,
  `reply_content` text,
  `reply_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for xm_link
-- ----------------------------
DROP TABLE IF EXISTS `xm_link`;
CREATE TABLE `xm_link` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(32) NOT NULL,
  `default_image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT '255',
  `add_time` int(255) DEFAULT '0',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_link
-- ----------------------------
INSERT INTO `xm_link` VALUES ('1', '1', '百度一下', '', 'http://www.baidu.com/', '1', '1456811339');

-- ----------------------------
-- Table structure for xm_menus
-- ----------------------------
DROP TABLE IF EXISTS `xm_menus`;
CREATE TABLE `xm_menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `link_id` int(11) DEFAULT '0',
  `menu_name` varchar(255) NOT NULL,
  `menu_name_en` varchar(255) DEFAULT NULL,
  `menu_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '255',
  `is_show` int(11) NOT NULL DEFAULT '1',
  `can_delete` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_menus
-- ----------------------------
INSERT INTO `xm_menus` VALUES ('1', '0', '1', '10', '公司介绍', '', '', '1', '1', '0');
INSERT INTO `xm_menus` VALUES ('2', '0', '2', '0', '公司动态', '', '', '2', '1', '0');
INSERT INTO `xm_menus` VALUES ('3', '0', '1', '16', '服务内容', '', '', '3', '1', '0');
INSERT INTO `xm_menus` VALUES ('4', '0', '2', '13', '政策法规', '', '', '4', '1', '0');
INSERT INTO `xm_menus` VALUES ('5', '0', '5', '14', '客户留言', '', '', '5', '1', '0');
INSERT INTO `xm_menus` VALUES ('10', '1', '1', '0', '公司简介', '', '', '1', '1', '0');
INSERT INTO `xm_menus` VALUES ('11', '2', '2', '0', '行业资讯', '', '', '1', '1', '0');
INSERT INTO `xm_menus` VALUES ('13', '4', '2', '0', '政策法规', '', '', '1', '1', '0');
INSERT INTO `xm_menus` VALUES ('14', '5', '5', '0', '留言信息', '', '', '1', '1', '0');
INSERT INTO `xm_menus` VALUES ('15', '2', '2', '0', '公司动态', '', null, '2', '1', '0');
INSERT INTO `xm_menus` VALUES ('16', '3', '1', '0', '服务内容', '', null, '1', '1', '0');
INSERT INTO `xm_menus` VALUES ('17', '3', '1', '0', '劳务派遣', '', null, '2', '1', '0');
INSERT INTO `xm_menus` VALUES ('18', '0', '1', '19', '联系我们', '', null, '6', '1', '0');
INSERT INTO `xm_menus` VALUES ('19', '18', '1', '0', '联系我们', '', null, '1', '1', '0');
INSERT INTO `xm_menus` VALUES ('20', '0', '2', '21', '人力资源', '', null, '7', '1', '0');
INSERT INTO `xm_menus` VALUES ('21', '20', '2', '0', '人才岗位', '', null, '1', '1', '0');

-- ----------------------------
-- Table structure for xm_menus_type
-- ----------------------------
DROP TABLE IF EXISTS `xm_menus_type`;
CREATE TABLE `xm_menus_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '255',
  `action_page` varchar(32) NOT NULL,
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_menus_type
-- ----------------------------
INSERT INTO `xm_menus_type` VALUES ('1', '单页', '1', '', '0');
INSERT INTO `xm_menus_type` VALUES ('2', '文章', '2', '', '0');
INSERT INTO `xm_menus_type` VALUES ('3', '产品', '3', '', '1');
INSERT INTO `xm_menus_type` VALUES ('4', '案例', '4', '', '1');
INSERT INTO `xm_menus_type` VALUES ('5', '留言', '5', '', '1');

-- ----------------------------
-- Table structure for xm_pro
-- ----------------------------
DROP TABLE IF EXISTS `xm_pro`;
CREATE TABLE `xm_pro` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `pro_name` varchar(200) NOT NULL,
  `pro_description` varchar(500) NOT NULL,
  `from` varchar(200) NOT NULL,
  `author` varchar(200) NOT NULL,
  `default_image` varchar(200) NOT NULL,
  `images` varchar(500) NOT NULL,
  `detail` text NOT NULL,
  `is_top` tinyint(4) NOT NULL DEFAULT '0',
  `is_recom` tinyint(4) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL,
  `view_times` int(11) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_pro
-- ----------------------------

-- ----------------------------
-- Table structure for xm_simple
-- ----------------------------
DROP TABLE IF EXISTS `xm_simple`;
CREATE TABLE `xm_simple` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `content` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xm_simple
-- ----------------------------
INSERT INTO `xm_simple` VALUES ('1', '0', '底部信息', '合肥瑞德人力资源有限公司 版权所有 Copyright &copy; 2011 All Right Reserve');
INSERT INTO `xm_simple` VALUES ('2', '5', '客户留言', '');
INSERT INTO `xm_simple` VALUES ('3', '4', '政策法规', '');
INSERT INTO `xm_simple` VALUES ('5', '8', '公司新闻', '');
INSERT INTO `xm_simple` VALUES ('7', '10', '公司简介', '合肥瑞德人力资源公司，2013年11月获得合肥市人力资源和社会保障局颁发《劳务派遣经营许可证》，2014年9月荣获AAA级信用等级证书，公司创建于2008年，2010年8月正式注册为合肥瑞德人力资源有限公司。目前主要为合肥、上海、南京等多家企业服务。\r\n                主要业务范围： 1、职业介绍、劳务派遣、实习生代理招聘、代发工资、代买保险、劳务政策咨..合肥瑞德人力资源公司，2013年11月获得合肥市人力资源和社会保障局颁发《劳务派遣经营许可证》，2014年9月荣获AAA级信用等级证书，公司创建于2008年，2010年8月正式注册为合肥瑞德人力资源有限公司。目前主要为合肥、上海、南京等多家企业服务。\r\n                主要业务范围： 1、职业介绍、劳务派遣、实习生代理招聘、代发工资、代买保险、劳务政策咨..');
INSERT INTO `xm_simple` VALUES ('8', '11', '行业资讯', '');
INSERT INTO `xm_simple` VALUES ('9', '12', '热门产品', '');
INSERT INTO `xm_simple` VALUES ('10', '13', '政策法规', '');
INSERT INTO `xm_simple` VALUES ('11', '14', '留言信息', '');
INSERT INTO `xm_simple` VALUES ('13', '15', '公司动态', '');
INSERT INTO `xm_simple` VALUES ('14', '16', '服务内容', '公司已为大中型企业、上市公司、政府部门、事业单位和北京、南京、上海、杭州、大连、广州等200多家用工单位建立长期服务关系，服务范围包括国家机关团\r\n体、企事业用工单位、外资、合资、服务机构、私营企业、社区家庭、个体工商户、外地来肥投资及我市在外地创业、打工就业人员。公司已为大中型企业、上市公司、政府部门、事业单位和北京、南京、上海、杭州、大连、广州等200多家用工单位建立长期服务关系，服务范围包括国家机关团\r\n体、企事业用工单位、外资、合资、服务机构、私营企业、社区家庭、个体工商户、外地来肥投资及我市在外地创业、打工就业人员。');
INSERT INTO `xm_simple` VALUES ('15', '17', '劳务派遣', '');
INSERT INTO `xm_simple` VALUES ('16', '18', '联系我们', '');
INSERT INTO `xm_simple` VALUES ('17', '19', '联系我们', '<div class=\"contact\">\r\n	<p>\r\n		地址：合肥市蜀山区长江西路与潜山路交口丰乐大厦2号楼A座1003&nbsp;\r\n	</p>\r\n	<p>\r\n		电话：0551-65596260\r\n	</p>\r\n	<p>\r\n		传真：0551-65596269\r\n	</p>\r\n	<p>\r\n		邮箱：<a href=\"mailto:ruidehr@126.com\">ruidehr@126.com</a> \r\n	</p>\r\n</div>');
INSERT INTO `xm_simple` VALUES ('18', '20', '人力资源', '');
INSERT INTO `xm_simple` VALUES ('19', '21', '人才岗位', '');
