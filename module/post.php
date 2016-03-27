<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
global $_G;
if(!$_G['uid']) {
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}
if( $_POST['formhash'] != FORMHASH )showmessage('请求来路不正确或服务器连接失败，请重试');
$set = $_G['cache']['plugin']['yinxingfei_zzza'];
$groups = unserialize($set['groups']);
$groups = array_filter($groups);

$extcredit_title = $_G['setting']['extcredits'][$set['extcredit']]['title'];

if(!in_array($_G['groupid'], $groups) && $_G['uid'])showmessage('您所在用户组无权进行"每日摇摇乐"');
		
//用户数据处理
$zzzadata = DB::fetch_first("SELECT * FROM ".DB::table('yinxingfei_zzza_rank')." WHERE uid = '".$_G['uid']."'");
$zzza_lasttime_dateline = empty($zzzadata['dateline']) ? 0 : $zzzadata['dateline'];

//判断用户今天是否参与过摇奖
if(dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset']) <= dgmdate($zzza_lasttime_dateline,'Ymd',$_G['setting']['timeoffset']))showmessage('您今天已经参与过"每日摇摇乐"，请明天再来吧');
	
$task_num = $set['task_num'];//任务数量
$task_type = $set['task_type'];//任务类型
$vip_groups = unserialize($set['vip_groups']);
$vip_groups = array_filter($vip_groups);//免任务VIP用户组

//每日的摇奖任务统计
$midnight_timestamp = strtotime(date('Y-m-d',$_G['timestamp']));

if(!in_array($_G['groupid'], $vip_groups) && $task_num != '0'){//判断是否为需要做任务的用户组
	//统计今天的发帖和回复数量
	/* 
		1 = 每日发布主题
		2 = 每日回复帖子
		3 = 发布主题+回复帖子 
	*/
	$forums = unserialize($set['forums']);//任务版块
	$forums = array_filter($forums);
	$forums_list = dimplode($forums);
	
	if($task_type == '1'){
		$complete_num = DB::result_first("SELECT count(*) FROM ".DB::table('forum_post')." WHERE authorid = '".$_G['uid']."' AND first = '1' AND fid IN(".$forums_list.") AND dateline > '".$midnight_timestamp."' AND invisible = '0'");
	}elseif($task_type == '2'){
		$complete_num  = DB::result_first("SELECT count(*) FROM ".DB::table('forum_post')." WHERE authorid = '".$_G['uid']."' AND first = '0' AND fid IN(".$forums_list.") AND dateline > '".$midnight_timestamp."' AND invisible = '0'");
	}elseif($task_type == '3'){
		$complete_num  = DB::result_first("SELECT count(*) FROM ".DB::table('forum_post')." WHERE authorid = '".$_G['uid']."' AND fid IN(".$forums_list.") AND dateline > '".$midnight_timestamp."' AND invisible = '0'");
	}
	$undone_num = $task_num - $complete_num ;//与插件设置的任务数量进行比较
	
}else{
	//不做任务用户组,直接标记为已完成全部.
	$undone_num = 0;
}
if($undone_num > 0){
	showmessage('您今天还没完成任务哦，先完成每日任务再来"每日摇摇乐"吧');
}
//连续摇奖记录处理
/* 取今日凌晨时间,与最后一次摇奖的时间进行对比,超过24小时则是没有连续,反之则是连续摇奖+1 */
$interval_time = $midnight_timestamp - $zzza_lasttime_dateline;
if($interval_time < 86400){
	$continuous_day = $zzzadata['continuous_day'] + 1;
}else{
	$continuous_day = 0;
}

updatemembercount($_G['uid'], array ($set['extcredit'] => $zzzadata['today_extcredit']),'','','','每日摇摇乐奖励','每日摇摇乐','参与"每日摇摇乐"，完成任务摇奖，即可获得奖励');//增加积分

//更新当前等级
$total_counts = intval($zzzadata['total_counts'] + $zzzadata['today_extcredit']);
$userGrade = getGrade($total_counts);

DB::query("UPDATE ".DB::table('yinxingfei_zzza_rank')." SET total_extcredit = total_extcredit + '".$zzzadata['today_extcredit']."' , total_counts = total_counts + 1, dateline = '".$_G['timestamp']."', continuous_day = '".$continuous_day."', grade = '".$userGrade['id']."', grouptitle = '".$userGrade['grouptitle']."' WHERE uid= '".$_G['uid']."'", 'UNBUFFERED');
DB::query("UPDATE ".DB::table('yinxingfei_zzza_mark')." SET value = '0' WHERE uid= '".$_G['uid']."'", 'UNBUFFERED');

//运气榜处理
$luck = array(
	'uid' => $_G['uid'],
	'total_extcredit' => $zzzadata['total_extcredit'],
	'dateline' => $_G['timestamp']
);
DB::insert('yinxingfei_zzza_luck',$luck,false,true);

//日历统计处理
$calendar = array(
	'date' => dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset']),
	'uid' => $_G['uid'],
	'today_extcredit' => $zzzadata['today_extcredit'],
	'extcredit_title' => $extcredit_title,
	'dateline' => $_G['timestamp']
);
DB::insert('yinxingfei_zzza_day',$calendar);

dheader("Location: ".$set['zzza_link']."&over=yes");
?>