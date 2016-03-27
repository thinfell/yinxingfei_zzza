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
if(!defined('IN_ADMINCP')) exit('Access Denied');
if(!$_GET['username']){
	showtableheader(lang("plugin/yinxingfei_zzza","h8"));
	showformheader('plugins&operation=config&identifier=yinxingfei_zzza&pmod=yinxingfei_zzza_user');
	showsetting(lang("plugin/yinxingfei_zzza","h9"), 'username', '', 'text');
	echo '<input type="hidden" name="formhash" value="'.FORMHASH.'">';
	showsubmit('search', 'search');
	showformfooter();
	showtablefooter();
}elseif(!submitcheck('submit') && $_GET['formhash']==FORMHASH){
	$_GET['username'] = daddslashes($_GET['username']);
	$uid = DB::result_first('SELECT uid FROM '.DB::table('common_member')." WHERE username='{$_GET[username]}'");
	if(!$uid) cpmsg(lang("plugin/yinxingfei_zzza","h10"), '', 'error', array('username' => $_GET['username']));
	$is_sql_k =  DB::fetch_first("SELECT id ,zzza_uid FROM ".DB::table('yinxingfei_zzza_rank')." WHERE zzza_uid = $uid");
	$isuser = empty($is_sql_k['id']) ? 0 : $is_sql_k['id'] ;
	if($isuser != '0'){
		$jf = DB::fetch_first("SELECT * FROM ".DB::table('yinxingfei_zzza_rank')." WHERE zzza_uid = $uid");
		showtableheader(lang("plugin/yinxingfei_zzza","h8"));
		showformheader("plugins&operation=config&identifier=yinxingfei_zzza&pmod=yinxingfei_zzza_user&username={$_GET[username]}");
		showsetting(lang("plugin/yinxingfei_zzza","h12"), 'jf_jt', $jf['jf_jt'], 'number');
		showsetting(lang("plugin/yinxingfei_zzza","h13"), 'jf_all', $jf['jf_all'], 'number');
		showsetting(lang("plugin/yinxingfei_zzza","h14"), 'cj_cs', $jf['cj_cs'], 'number');
		echo '<input type="hidden" name="formhash2" value="'.FORMHASH.'">';
		showsubmit('submit');
		showformfooter();
		showtablefooter();
	}else{
		cpmsg(lang("plugin/yinxingfei_zzza","h11"), '', 'error', array('username' => $_GET['username']));
	}
}else{
	if( $_GET['formhash2']==FORMHASH ){
	$_GET['username'] = daddslashes($_GET['username']);
	$_GET['jf_jt'] = daddslashes($_GET['jf_jt']);
	$_GET['jf_all'] = daddslashes($_GET['jf_all']);
	$_GET['cj_cs'] = daddslashes($_GET['cj_cs']);
	$uid = DB::result_first('SELECT uid FROM '.DB::table('common_member')." WHERE username='{$_GET[username]}'");
	if(!$uid) cpmsg(lang("plugin/yinxingfei_zzza","h10"), '', 'error', array('username'=>$_GET['username']));
	loadcache('plugin');
	$set = $_G['cache']['plugin']['yinxingfei_zzza'];
	$jf_zzza = $set['zzza_jifen'];
	$zzza_credit = $jf_zzza['zzza_jf'];
	$jf = DB::fetch_first("SELECT jf_jt, jf_all, cj_cs FROM ".DB::table('yinxingfei_zzza_rank')." WHERE zzza_uid = $uid");
	$getcreditnum = ($_GET['jf_all'] - $jf['jf_all']) + ($_GET['jf_jt'] - $jf['jf_jt']);
	updatemembercount($uid, array ($zzza_credit => $getcreditnum));
	DB::query("UPDATE ".DB::table('yinxingfei_zzza_rank')." SET jf_jt='{$_GET['jf_jt']}', jf_all='{$_GET['jf_all']}' , cj_cs='{$_GET['cj_cs']}' WHERE zzza_uid= $uid", 'UNBUFFERED');
	cpmsg(lang("plugin/yinxingfei_zzza","h15"), 'action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=yinxingfei_zzza_user', 'succeed');
	}else{
	exit('Access Denied');
	}
}
?>