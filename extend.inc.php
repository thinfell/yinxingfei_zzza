<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/zzza_version.php';
if($_GET['caozuo'] == 'anzhuang'){
	$extend_lang = @include DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/extend/'.$_GET['file'].'/extend_lang/'.currentlang().'.php';
	$post['name'] = $extend_lang['name'];
	$post['identifier'] = $extend_lang['identifier'];
	$post['version'] = $extend_lang['version'];
	$post['copyright'] = $extend_lang['copyright'];
	$post['description'] = $extend_lang['description'];
	$post['menu'] = $extend_lang['menu'];
	$post['type'] = $extend_lang['type'];
	$post['available'] = '0';
	foreach($post as $key => $value){
		$post[$key] = daddslashes($value);
	}
	$ifcz = DB::result_first("SELECT COUNT(*) FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE identifier = '".$post['identifier']."'");
	if($ifcz > 0){
		cpmsg('扩展"'.$post['name'].'"已经安装', 'action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','error');
	}else{
		DB::insert('yinxingfei_zzza_kuozhan', $post);
		cpmsg('扩展安装成功', 'action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','succeed');
	}
}elseif($_GET['caozuo'] == 'guanbi'){
	$type = DB::result_first("SELECT type FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE kzid = '".$_GET['kzid']."'");
	if($type == 'zp_theme'){
		$ifcz = DB::result_first("SELECT COUNT(*) FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE type = 'zp_theme' and available = '1'");
		if($ifcz == 1){
			cpmsg('至少启用一个转盘主题扩展','action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','error');
		}
	}
	DB::query("UPDATE ".DB::table('yinxingfei_zzza_kuozhan')." SET available = '0' WHERE kzid= '".$_GET['kzid']."'", 'UNBUFFERED');
	cpmsg(lang("plugin/yinxingfei_zzza","tz_4"), 'action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','succeed');
}elseif($_GET['caozuo'] == 'xiezai'){
	$type = DB::result_first("SELECT type FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE kzid = '".$_GET['kzid']."'");
	if($type == 'zp_theme'){
		$ifavailable = DB::result_first("SELECT available FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE kzid = '".$_GET['kzid']."'");
		if($ifavailable == '1'){
			cpmsg('至少启用一个转盘主题扩展','action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','error');
		}
	}
	DB::query("DELETE FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE kzid = '".$_GET['kzid']."'");
	cpmsg('扩展卸载成功', 'action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','succeed');
}elseif($_GET['caozuo'] == 'kaiqi'){
	$type = DB::result_first("SELECT type FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE kzid = '".$_GET['kzid']."'");
	if($type == 'zp_theme'){
		$ifcz = DB::result_first("SELECT COUNT(*) FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE type = 'zp_theme' and available = '1'");
		if($ifcz > 0){
			if($_GET['isqr'] != 'yes'){
				cpmsg('是否确定替换当前正在使用的转盘主题扩展？','action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=kaiqi&isqr=yes&kzid='.$_GET['kzid'],'form','','',TRUE,ADMINSCRIPT.'?action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend');
			}
			DB::query("UPDATE ".DB::table('yinxingfei_zzza_kuozhan')." SET available = '0' WHERE type = 'zp_theme'", 'UNBUFFERED');
		}
	}
	DB::query("UPDATE ".DB::table('yinxingfei_zzza_kuozhan')." SET available = '1' WHERE kzid= '".$_GET['kzid']."'", 'UNBUFFERED');
	cpmsg('扩展开启成功', 'action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','succeed');
}elseif($_GET['caozuo'] == 'extend'){
	$ifcz = DB::result_first("SELECT COUNT(*) FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE identifier = '".$_GET['file']."' AND available = '1'");
	if($ifcz > 0){
		$extend_lang = @include DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/extend/'.$_GET['file'].'/extend_lang/'.currentlang().'.php';
		if($_GET['ac']){
			if(!file_exists(DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/extend/'.$_GET['file'].'/'.$_GET['file'].'.'.$_GET['ac'].'.extend.php')){
				cpmsg('扩展文件不存在！','','error');
			}
			include DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/extend/'.$_GET['file'].'/'.$_GET['file'].'.'.$_GET['ac'].'.extend.php';
		}else{
			include DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/extend/'.$_GET['file'].'/'.$_GET['file'].'.extend.php';
		}
	}else{
		cpmsg('扩展不存在或未开启', 'action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend','error');
	}
}else{
	showtips('<li>请勿随意安装未知开发者发布的扩展，避免网站存在漏洞被黑</li><li>需要定制扩展功能的站长请加QQ：<strong>327220053</strong></li><li>请访问：<strong><a href="http://app.yinxingfei.com" target="_blank">http://app.yinxingfei.com</a></strong>，获取更多帮助与最新开发动态，每日摇摇乐站长QQ群：<strong>187204948</strong></li>');
	require DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/module/extend_on.php';
	require DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/module/extend_off.php';
	require DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/module/extend_NotInstalled.php';
}
?>