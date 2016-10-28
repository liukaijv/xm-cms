/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : xm-cms

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2016-10-28 10:32:10
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
INSERT INTO `xm_menus_type` VALUES ('3', '产品', '3', '', '0');
INSERT INTO `xm_menus_type` VALUES ('4', '案例', '4', '', '0');
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
INSERT INTO `xm_simple` VALUES ('1', '0', '底部信息', 'Copyright &copy; 2016 All Right Reserve');
