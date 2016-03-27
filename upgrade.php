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
/* if(!$fromversion)
	$fromversion = trim($_GET['fromversion']);
if($fromversion < '6.1'){
//6.0->6.1
DB::query("DROP TABLE IF EXISTS ".DB::table('yinxingfei_zzza_jl')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('yinxingfei_zzza_jl_sz')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('yinxingfei_zzza_sz')."");
$is_cron =  DB::fetch_first("SELECT cronid FROM ".DB::table('common_cron')." WHERE filename='cron_zzza_jl.php'");
$if_cron = empty($is_cron['cronid']) ? 0 : $is_cron['cronid'] ;
if( $if_cron != '0'){
runquery("DELETE FROM pre_common_cron WHERE filename='cron_zzza_jl.php'");
}
$sql = <<<EOF
ALTER TABLE cdb_yinxingfei_zzza_rank DROP zzza_hmd,
DROP zzza_tiezi,ADD lxyj INT( 10 ) NOT NULL;
DROP TABLE IF EXISTS cdb_yinxingfei_zzza_fyb;
CREATE TABLE cdb_yinxingfei_zzza_fyb (
  `id` INT( 8 )  NOT NULL auto_increment,
  `uid` INT( 8 )  NOT NULL,
  `jf_all` INT( 10 )  NOT NULL,
  `lasttime` INT( 10 )  NOT NULL,
  `days` INT( 10 )  NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
DROP TABLE IF EXISTS cdb_yinxingfei_zzza_tj;
CREATE TABLE cdb_yinxingfei_zzza_tj (
  `data` INT( 8 )  NOT NULL,
  `uid` INT( 8 )  NOT NULL,
  `jf_jt` INT( 10 )  NOT NULL,
  `jf_name` CHAR( 15 )  NOT NULL,
  `lasttime` INT( 10 )  NOT NULL
) TYPE=MyISAM;
EOF;
runquery($sql);

}
if($fromversion < '6.2'){
//6.1->6.2
$sql = <<<EOF

DROP TABLE IF EXISTS cdb_yinxingfei_zzza_dj;
CREATE TABLE IF NOT EXISTS `cdb_yinxingfei_zzza_dj` (
  `id` int(11) NOT NULL,
  `xx` int(11) NOT NULL,
  `sx` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


INSERT INTO `cdb_yinxingfei_zzza_dj` (`id`, `xx`, `sx`) VALUES
(1, 0, 2),
(2, 2, 3),
(3, 3, 4),
(4, 4, 5),
(5, 5, 6),
(6, 6, 7),
(7, 7, 8),
(8, 8, 9),
(9, 9, 10),
(10, 10, 11),
(11, 11, 12),
(12, 12, 13),
(13, 13, 14),
(14, 14, 15),
(15, 15, 16),
(16, 16, 17),
(17, 17, 18),
(18, 18, 19),
(19, 19, 20),
(20, 20, 999);

EOF;
runquery($sql);
}
if($fromversion < '6.2.5.3'){

$sql = <<<EOF

ALTER TABLE `cdb_yinxingfei_zzza_tj` ADD `id` MEDIUMINT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`) ;

EOF;
runquery($sql);
}
if($fromversion < '6.2.5.5'){

$sql = <<<EOF

DROP TABLE IF EXISTS cdb_yinxingfei_zzza_kuozhan;
CREATE TABLE IF NOT EXISTS `cdb_yinxingfei_zzza_kuozhan` (
  `kzid` smallint(6) NOT NULL auto_increment,
  `menu` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(40) NOT NULL,
  `identifier` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `version` varchar(20) NOT NULL,
  PRIMARY KEY  (`kzid`)
) TYPE=MyISAM;

EOF;
runquery($sql);
}
$finish = TRUE; */
?>