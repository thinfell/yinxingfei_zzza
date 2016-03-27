<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}	
global $_G;
if(!$_G['uid']) {
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}
$set = $_G['cache']['plugin']['yinxingfei_zzza'];
$navtitle = $set['title'];
$metakeywords = $set['keywords'];
$metadescription = $set['description'];

$groups = unserialize($set['groups']);
$groups = array_filter($groups);

$extcredit_title = $_G['setting']['extcredits'][$set['extcredit']]['title'];
$display_plate = false;

if(in_array($_G['groupid'], $groups)){
	//用户数据处理
	$zzzadata = DB::fetch_first("SELECT * FROM ".DB::table('yinxingfei_zzza_rank')." WHERE uid = '".$_G['uid']."'");
	
	//获取等级设置
	$gradeset = getSetZzza('grade_type');

	//获取用户等级
	$userGradeVal = intval($zzzadata['grade']) ? intval($zzzadata['grade']) : 1;
	//获取用户所在等级名称
	$userGradeTitle =  $zzzadata['grouptitle'];
	if(!$userGradeTitle){
		$userGradeTitle = DB::result_first("SELECT grouptitle FROM ".DB::table('yinxingfei_zzza_grade')." WHERE id = '1'");
	}
	$zzzadata['total_counts'] = intval($zzzadata['total_counts']) ? intval($zzzadata['total_counts']) : 0;
	$zzzadata['continuous_day'] = intval($zzzadata['continuous_day']) ? intval($zzzadata['continuous_day']) : 0;
	$zzzadata['total_extcredit'] = intval($zzzadata['total_extcredit']) ? intval($zzzadata['total_extcredit']) : 0;
	//获取头衔名称
	$userGradeTx = getSetZzza('grade_tx');
	//获取用户连续摇奖
	
	//获取用户总摇奖
	
	//获取用户累积奖励
	
	$zzza_lasttime_dateline = empty($zzzadata['dateline']) ? 0 : $zzzadata['dateline'];
		
	//判断用户今天是否参与过摇奖
	if(dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset']) > dgmdate($zzza_lasttime_dateline,'Ymd',$_G['setting']['timeoffset'])){
	
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
		if($undone_num <= 0){
			$display_plate = true;
		}
	}else{
		$today_mark = 1;
		$today_extcredit = $zzzadata['today_extcredit'];
		$over = $_GET['over'];
	}
}else{
	showmessage('您所在用户组不能参加每日摇摇乐');
}

//等级显示
$list_grade = array();
$query_grade = DB::query("SELECT * FROM ".DB::table('yinxingfei_zzza_grade')." ORDER BY id");
while($value_grade = DB::fetch($query_grade)) {
	$list_grade[] = $value_grade;
}

//排行榜数据
$midnight_timestamp = strtotime(date('Y-m-d',$_G['timestamp']));

$orderarray = array('today_extcredit','total_extcredit','total_counts','continuous_day','luckdays');
$order = !in_array($_GET['order'], $orderarray) ? 'today_extcredit' : $_GET['order'];
if($order == 'today_extcredit')$where = "WHERE dateline > '".$midnight_timestamp."'";

if($order == 'luckdays'){
	$limit = 10;
	$num = DB::result_first("SELECT COUNT(*) FROM ".DB::table('yinxingfei_zzza_luck')." ");
	$page = max(1, intval($_GET['page']));
	$start_limit = ($page - 1) * $limit;
	$url = 'plugin.php?id=yinxingfei_zzza&mod=hall&order='.$order;
	$multipage = multi($num, $limit, $page, $url,0,4);
	$rank_list = array();
	$querygg = DB::query("SELECT * FROM ".DB::table('yinxingfei_zzza_luck')." ORDER BY total_extcredit DESC LIMIT ".$start_limit." ,".$limit."");
	while ($value = DB::fetch($querygg)){
		$rank_list[] = $value;
	}	
}else{
	$limit = 10;
	$num = DB::result_first("SELECT COUNT(*) FROM ".DB::table('yinxingfei_zzza_rank')." {$where}");
	$page = max(1, intval($_GET['page']));
	$start_limit = ($page - 1) * $limit;
	$url = 'plugin.php?id=yinxingfei_zzza&mod=hall&order='.$order;
	$multipage = multi($num, $limit, $page, $url,0,4);
	$rank_list = array();
	$querygg = DB::query("SELECT * FROM ".DB::table('yinxingfei_zzza_rank')." {$where} ORDER BY ".$order." DESC LIMIT ".$start_limit." ,".$limit."");
	while ($value = DB::fetch($querygg)){
		$rank_list[] = $value;
	}
}
//日历统计
$firsday_stamp = strtotime(date('Y-m',$_G['timestamp']).'-01');
$lastday_stamp = strtotime(date('Y-m-01', $_G['timestamp']) . ' +1 month -1 day');
$firsday_week = date("w",$firsday_stamp);

$lastday_date = date("d",$lastday_stamp);
$calendar = '<tr class="zzza_calendar_date">';
for($i = 0;$i < $firsday_week;$i++){
	$calendar .= '<td>&nbsp;</td>';
}
for($i = 1;$i <= $lastday_date;$i++){
	$i = sprintf("%01d",$i);
	$nowi_stamp = strtotime(date('Y-m',$_G['timestamp']).'-'.$i);
	$nowi_week = date("w",$nowi_stamp);
	//判断是否摇奖
	$nowi_date = date("Ymd",$nowi_stamp);
	$zzza_calendar_on = '';
	if($nowi_date <= dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset'])){
		$nowidata = DB::fetch_first("SELECT * FROM ".DB::table('yinxingfei_zzza_day')." WHERE date = '".$nowi_date."' AND uid = '".$_G['uid']."'");
		if($nowidata['today_extcredit']){
			$zzza_calendar_on = ' class="zzza_calendar_on" title="获得'.$nowidata['today_extcredit'].$nowidata['extcredit_title'].'"';
		}
	}
	if($nowi_week == 0){
		$calendar .= '</tr><tr class="zzza_calendar_date"><td'.$zzza_calendar_on.'>'.$i.'</td>';
	}else{
		$calendar .= '<td'.$zzza_calendar_on.'>'.$i.'</td>';
	}
}
$calendar .= '</tr>';
//任务版块 

//加载页面
include template('yinxingfei_zzza:'.$mod);

function zzzadj($a){
	$a = intval($a);
	$zdxx = DB::result_first("SELECT xx FROM ".DB::table('yinxingfei_zzza_dj')." WHERE id = '1'");
	$zgsx = DB::result_first("SELECT sx FROM ".DB::table('yinxingfei_zzza_dj')." WHERE id = '20'");
	if($a < $zdxx){
		$dj = 0;
	}elseif($a > $zgsx){
		$dj = 20;
	}else{
		$dj = DB::result_first("SELECT id FROM ".DB::table('yinxingfei_zzza_dj')." WHERE xx <= '$a' AND sx > '$a'");
	}
	return $dj;
}
?>