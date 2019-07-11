/*
 Navicat MySQL Data Transfer

 Source Server         : mongo5-2
 Source Server Type    : MySQL
 Source Server Version : 50644
 Source Host           : localhost:3306
 Source Schema         : mongo52_hp135_cn

 Target Server Type    : MySQL
 Target Server Version : 50644
 File Encoding         : 65001

 Date: 11/07/2019 15:27:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for xh_ip_access_list
-- ----------------------------
DROP TABLE IF EXISTS `xh_ip_access_list`;
CREATE TABLE `xh_ip_access_list`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_str` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1表示白名单 2表示黑名单',
  `data_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1表示有效 0表示无效',
  `add_time` datetime(0) NOT NULL,
  `update_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ip_str_index`(`ip_str`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
