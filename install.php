<?php
/**
 *      本程序由尹兴飞开发
 *      若要二次开发或用于商业用途的，需要经过尹兴飞同意。
 *
 *		http://app.yinxingfei.com			插件技术支持
 *
 *		http://www.cglnn.com			    插件演示站点
 *
 ->		==========================================================================================
 *
 *      2014-11-01 开始由6.1升级到6.2！
 *
 *		愿我的同学、家人、朋友身体安康，天天快乐！
 ->		同时也祝您使用愉快！
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_day`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_day` (
  `id` mediumint(8) NOT NULL,
  `date` int(8) NOT NULL,
  `uid` mediumint(8) NOT NULL,
  `today_extcredit` int(11) NOT NULL,
  `extcredit_title` char(15) NOT NULL,
  `dateline` int(10) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_grade`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_grade` (
  `id` int(11) NOT NULL,
  `grouptitle` char(100) NOT NULL,
  `min` mediumint(8) NOT NULL,
  `max` mediumint(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `pre_yinxingfei_zzza_grade`
--

INSERT INTO `pre_yinxingfei_zzza_grade` (`id`, `grouptitle`, `min`, `max`) VALUES
(1, 'LV1', 0, 3),
(2, 'LV2', 3, 6),
(3, 'LV3', 6, 9),
(4, 'LV4', 9, 12),
(5, 'LV5', 12, 15),
(6, 'LV6', 15, 18),
(7, 'LV7', 18, 21),
(8, 'LV8', 21, 24),
(9, 'LV9', 24, 27);

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_kuozhan`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_kuozhan` (
  `kzid` smallint(6) NOT NULL,
  `menu` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(40) NOT NULL,
  `identifier` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `version` varchar(20) NOT NULL,
  `type` char(10) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `pre_yinxingfei_zzza_kuozhan`
--

INSERT INTO `pre_yinxingfei_zzza_kuozhan` (`kzid`, `menu`, `available`, `name`, `identifier`, `description`, `copyright`, `version`, `type`) VALUES
(1, '进入管理', 1, '备份与恢复', 'sjbf', '备份每日摇摇乐数据 当您转移网站或者重装插件 可以使用该功能备份与恢复数据', '尹兴飞', '1.0', 'admincp'),
(2, '', 1, '默认转盘主题', 'zp_theme_default', '喜气洋洋的橙红色转盘', '尹兴飞', '1.0', 'zp_theme');

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_luck`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_luck` (
  `uid` mediumint(8) NOT NULL,
  `total_extcredit` int(10) NOT NULL,
  `dateline` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_mark`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_mark` (
  `uid` mediumint(8) NOT NULL,
  `value` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_range`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_range` (
  `id` int(8) NOT NULL,
  `min` smallint(1) NOT NULL,
  `max` smallint(1) NOT NULL,
  `percentage` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `pre_yinxingfei_zzza_range`
--

INSERT INTO `pre_yinxingfei_zzza_range` (`id`, `min`, `max`, `percentage`) VALUES
(1, 0, 5, 20),
(2, 5, 10, 70),
(3, 10, 15, 10);

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_rank`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_rank` (
  `uid` mediumint(8) NOT NULL,
  `username` char(15) NOT NULL,
  `grade` tinyint(1) NOT NULL DEFAULT '1',
  `grouptitle` char(100) NOT NULL,
  `today_extcredit` mediumint(8) NOT NULL DEFAULT '0',
  `total_extcredit` mediumint(8) NOT NULL DEFAULT '0',
  `total_counts` mediumint(8) NOT NULL DEFAULT '0',
  `continuous_day` mediumint(8) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_yinxingfei_zzza_set`
--

CREATE TABLE IF NOT EXISTS `pre_yinxingfei_zzza_set` (
  `type` char(30) CHARACTER SET utf8 NOT NULL,
  `val` char(30) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `pre_yinxingfei_zzza_set`
--

INSERT INTO `pre_yinxingfei_zzza_set` (`type`, `val`) VALUES
('grade_type', '14'),
('grade_tx', '摇摇乐等级');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pre_yinxingfei_zzza_day`
--
ALTER TABLE `pre_yinxingfei_zzza_day`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_yinxingfei_zzza_grade`
--
ALTER TABLE `pre_yinxingfei_zzza_grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_yinxingfei_zzza_kuozhan`
--
ALTER TABLE `pre_yinxingfei_zzza_kuozhan`
  ADD PRIMARY KEY (`kzid`);

--
-- Indexes for table `pre_yinxingfei_zzza_luck`
--
ALTER TABLE `pre_yinxingfei_zzza_luck`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `pre_yinxingfei_zzza_mark`
--
ALTER TABLE `pre_yinxingfei_zzza_mark`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `pre_yinxingfei_zzza_range`
--
ALTER TABLE `pre_yinxingfei_zzza_range`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_yinxingfei_zzza_rank`
--
ALTER TABLE `pre_yinxingfei_zzza_rank`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `pre_yinxingfei_zzza_set`
--
ALTER TABLE `pre_yinxingfei_zzza_set`
  ADD PRIMARY KEY (`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pre_yinxingfei_zzza_day`
--
ALTER TABLE `pre_yinxingfei_zzza_day`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `pre_yinxingfei_zzza_kuozhan`
--
ALTER TABLE `pre_yinxingfei_zzza_kuozhan`
  MODIFY `kzid` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pre_yinxingfei_zzza_range`
--
ALTER TABLE `pre_yinxingfei_zzza_range`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

EOF;
runquery($sql);

$finish = TRUE;

?>
