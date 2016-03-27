<?php
//cronname:mycron
//day:-1
//week:-1 
//day: -1
//hour:"0"
//minute:      

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}	
/* global $_G;//加载系统全局变量
//连续签到
$sql="SELECT * FROM ".DB::table('yinxingfei_zzza_rank')." WHERE zzza_uid > '0' AND lxyj > '0'";
$query = DB::query($sql);
$result = array();
$zza_td_time = dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset']);//今天日期
$zzza_lasstime_jt = $zza_td_time;
while ($result = DB::fetch($query)){
	$lxyjpd = dgmdate($result['zzza_lasttime_u'],'Ymd',$_G['setting']['timeoffset']);
	$zzzayear=(int)substr($zzza_lasstime_jt,0,4);//取得年份
	$zzzamonth=(int)substr($zzza_lasstime_jt,4,2);//取得月份
	$zzzaday=(int)substr($zzza_lasstime_jt,6,2);//取得几号
	$zzzamtime = mktime(0,0,0,$zzzamonth,$zzzaday,$zzzayear);
	$zzzanyear=(int)substr($lxyjpd,0,4);//取得年份
	$zzzanmonth=(int)substr($lxyjpd,4,2);//取得月份
	$zzzanday=(int)substr($lxyjpd,6,2);//取得几号
	$zzzantime = mktime(0,0,0,$zzzanmonth,$zzzanday,$zzzanyear);
	$zzzajtime = ($zzzamtime - $zzzantime)/(3600*24);
	if($zzzajtime > 1){
		DB::query("UPDATE ".DB::table('yinxingfei_zzza_rank')." SET lxyj='0' WHERE zzza_uid = '".$result['zzza_uid']."'", 'UNBUFFERED');
	}
}
//更新风云榜数据
loadcache('plugin');
$set = $_G['cache']['plugin']['yinxingfei_zzza'];
$zzza_fyb = $set['zzza_fyb'];
DB::query("TRUNCATE ".DB::table('yinxingfei_zzza_fyb')."");// 清空旧数据
$ylxyjpd = dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset']);
$yzzzayear=(int)substr($ylxyjpd,0,4);//取得年份
$yzzzamonth=(int)substr($ylxyjpd,4,2);//取得月份
$yzzzaday=(int)substr($ylxyjpd,6,2);//取得几号
$yzzzamtime = mktime(0,0,0,$yzzzamonth,$yzzzaday,$yzzzayear);
$zzza_fybdaya = $yzzzamtime - ($zzza_fyb*3600*24);
$zzza_fybday = dgmdate($zzza_fybdaya,'Ymd',$_G['setting']['timeoffset']);
$query_a = DB::query("SELECT * FROM ".DB::table('yinxingfei_zzza_tj')." WHERE data > '$zzza_fybday' ORDER BY id DESC");

$fyb_list = array();
while($zzza_fyb_i = DB::fetch($query_a)){
	
	$fyb_list[$zzza_fyb_i['uid']] += $zzza_fyb_i['jf_jt'];

}
foreach($fyb_list as $k => $v){
	$zzza_fyb_ilasttime = DB::result_first("SELECT zzza_lasttime_u FROM ".DB::table('yinxingfei_zzza_rank')." WHERE zzza_uid = '".$k."'");     
	DB::insert('yinxingfei_zzza_fyb', array('lasttime' => $zzza_fyb_ilasttime, 'uid' => $k, 'jf_all' => $v, 'days' => $zzza_fyb));
} */
?>