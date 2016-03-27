<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid'])exit('Access Denied');
$set = $_G['cache']['plugin']['yinxingfei_zzza'];
$groups = unserialize($set['groups']);
$groups = array_filter($groups);
if(!in_array($_G['groupid'], $groups))exit('Access Denied');

//用户数据处理
$zzzadata = DB::fetch_first("SELECT * FROM ".DB::table('yinxingfei_zzza_rank')." WHERE uid = '".$_G['uid']."'");
$zzza_lasttime_dateline = empty($zzzadata['dateline']) ? 0 : $zzzadata['dateline'];
	
//判断用户今天是否参与过摇奖
if(dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset']) <= dgmdate($zzza_lasttime_dateline,'Ymd',$_G['setting']['timeoffset']))exit('Access Denied');

//是否初始化获得积分
$initialization_mark = DB::result_first("SELECT value FROM ".DB::table('yinxingfei_zzza_mark')." WHERE uid = '".$_G['uid']."'");
$initialization_mark = empty($initialization_mark) ? 0 : $initialization_mark;
//0:未初始化
//1:已经初始化		
if($initialization_mark == 0){
					
	$range1percentage = DB::result_first("SELECT percentage FROM ".DB::table('yinxingfei_zzza_range')." WHERE id = '1'");
	$range2percentage = DB::result_first("SELECT percentage FROM ".DB::table('yinxingfei_zzza_range')." WHERE id = '2'");
	$range3percentage = DB::result_first("SELECT percentage FROM ".DB::table('yinxingfei_zzza_range')." WHERE id = '3'");
	$rangeid = get_rand(array($range1percentage,$range2percentage,$range3percentage));
	$rangeid = $rangeid + 1;
	$rangeab = DB::fetch_first("SELECT min,max FROM ".DB::table('yinxingfei_zzza_range')." WHERE id = '".$rangeid."'");
	$initialization_extcredit = mt_rand($rangeab['min'],$rangeab['max']);
	$today_extcredit = $initialization_extcredit;
	$zzzadata['uid'] = $_G['uid'];
	$zzzadata['username'] = getusername($_G['uid']);
	$zzzadata['today_extcredit'] = $initialization_extcredit;
	
	DB::insert('yinxingfei_zzza_rank',$zzzadata,false,true);//更新插入数据
	
	//防止用户通过多浏览器不断刷新刷积分代码,我们就通过初始化记录为准,不管刷新多少次都是调用初始化
	$newmark = array(
				'uid' => $_G['uid'],
				'value' => 1
	);
	DB::insert('yinxingfei_zzza_mark',$newmark,false,true);					
}else{
	$today_extcredit = $zzzadata['today_extcredit'];
}
$today_extcredit = sprintf("%03d",$today_extcredit);
exit($today_extcredit);
?>