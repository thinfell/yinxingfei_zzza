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

class plugin_yinxingfei_zzza {
		
	function global_usernav_extra1() {
		global $_G;
		
		$set = $_G['cache']['plugin']['yinxingfei_zzza'];
		$zzza_link = $set['zzza_link'];
		$usernav_color = $set['usernav_color'];
		$usernav_txt = $set['usernav_txt'];
		$usernav_position = $set['usernav_position'];
		if($usernav_position == 1){
			include template('yinxingfei_zzza:usernav');
			return $return;
		}
		
	}
	function global_usernav_extra2() {
		global $_G;
		
		$set = $_G['cache']['plugin']['yinxingfei_zzza'];
		$zzza_link = $set['zzza_link'];
		$usernav_color = $set['usernav_color'];
		$usernav_txt = $set['usernav_txt'];
		$usernav_position = $set['usernav_position'];
		if($usernav_position == 2){
			include template('yinxingfei_zzza:usernav');
			return $return;
		}
		
	}
	function global_usernav_extra3() {
		global $_G;
		
		$set = $_G['cache']['plugin']['yinxingfei_zzza'];
		$zzza_link = $set['zzza_link'];
		$usernav_color = $set['usernav_color'];
		$usernav_txt = $set['usernav_txt'];
		$usernav_position = $set['usernav_position'];
		if($usernav_position == 3){
			include template('yinxingfei_zzza:usernav');
			return $return;
		}
		
	}
	function global_usernav_extra4() {
		global $_G;
		
		$set = $_G['cache']['plugin']['yinxingfei_zzza'];
		$zzza_link = $set['zzza_link'];
		$usernav_color = $set['usernav_color'];
		$usernav_txt = $set['usernav_txt'];
		$usernav_position = $set['usernav_position'];
		if($usernav_position == 4){
			include template('yinxingfei_zzza:usernav');
			return $return;
		}
	}
	function global_footer() {
		global $_G;
		
		if(CURMODULE == 'yinxingfei_zzza')return;
		if(!$_G['uid'])return;//没登录用户直接过
		if (isset($_COOKIE["zzza_notice_close"])){
			if($_COOKIE["zzza_notice_close"])return;
		}
		$set = $_G['cache']['plugin']['yinxingfei_zzza'];
		$notice_position = $set['notice_position'];//网站底部提醒位置
		if($notice_position == 3)return;//不显示直接PASS
		
		$groups = unserialize($set['groups']);
		$groups = array_filter($groups);
		if(!in_array($_G['groupid'], $groups))return;//不属于设置允许参与用户组内直接PASS
		
		$zzzadata = DB::fetch_first("SELECT dateline,continuous_day FROM ".DB::table('yinxingfei_zzza_rank')." WHERE uid = '".$_G['uid']."'");
		$zzza_lasttime_dateline = empty($zzzadata['dateline']) ? 0 : $zzzadata['dateline'];
		if(dgmdate($_G['timestamp'],'Ymd',$_G['setting']['timeoffset']) <= dgmdate($zzza_lasttime_dateline,'Ymd',$_G['setting']['timeoffset']))return;//判断用户今天是否参与过摇奖,摇过直接PASS
		
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
		
		$continuous_day = $zzzadata['continuous_day'] ? $zzzadata['continuous_day'] : 0;
		$new_forums = array();
		foreach($forums as $k => $v){
			$new_forums[$k]['name'] = DB::result_first("SELECT name FROM ".DB::table('forum_forum')." WHERE fid = '".$v."'");
			$new_forums[$k]['fid'] = $v;
		}
		include $this->_yinxingfei_zzza_extend_template('yinxingfei_zzza:notice');
		return $return;
	}
	function _get_zp_theme_identifier(){
		$zp_theme_identifier = DB::result_first("SELECT identifier FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE type = 'zp_theme' AND available = '1'");
		return $zp_theme_identifier;
	}
	function _yinxingfei_zzza_extend_template($file, $templateid = 0, $tpldir = '', $gettplfile = 0, $primaltpl='') {
		//在原来官方文件基础上修改适配扩展模板
		$zp_theme_path = $this->_get_zp_theme_identifier();
		
		global $_G;

		static $_init_style = false;
		if($_init_style === false) {
			C::app()->_init_style();
			$_init_style = true;
		}
		$oldfile = $file;
		if(strpos($file, ':') !== false) {
			$clonefile = '';
			list($templateid, $file, $clonefile) = explode(':', $file);
			$oldfile = $file;
			$file = empty($clonefile) ? $file : $file.'_'.$clonefile;
			if($templateid == 'diy') {
				$indiy = false;
				$_G['style']['tpldirectory'] = $tpldir ? $tpldir : (defined('TPLDIR') ? TPLDIR : '');
				$_G['style']['prefile'] = '';
				$diypath = DISCUZ_ROOT.'./data/diy/'.$_G['style']['tpldirectory'].'/'; //DIY模板文件目录
				$preend = '_diy_preview';
				$_GET['preview'] = !empty($_GET['preview']) ? $_GET['preview'] : '';
				$curtplname = $oldfile;
				$basescript = $_G['mod'] == 'viewthread' && !empty($_G['thread']) ? 'forum' : $_G['basescript'];
				if(isset($_G['cache']['diytemplatename'.$basescript])) {
					$diytemplatename = &$_G['cache']['diytemplatename'.$basescript];
				} else {
					if(!isset($_G['cache']['diytemplatename'])) {
						loadcache('diytemplatename');
					}
					$diytemplatename = &$_G['cache']['diytemplatename'];
				}
				$tplsavemod = 0;
				if(isset($diytemplatename[$file]) && file_exists($diypath.$file.'.htm') && ($tplsavemod = 1) || empty($_G['forum']['styleid']) && ($file = $primaltpl ? $primaltpl : $oldfile) && isset($diytemplatename[$file]) && file_exists($diypath.$file.'.htm')) {
					$tpldir = 'data/diy/'.$_G['style']['tpldirectory'].'/';
					!$gettplfile && $_G['style']['tplsavemod'] = $tplsavemod;
					$curtplname = $file;
					if(isset($_GET['diy']) && $_GET['diy'] == 'yes' || isset($_GET['diy']) && $_GET['preview'] == 'yes') { //DIY模式或预览模式下做以下判断
						$flag = file_exists($diypath.$file.$preend.'.htm');
						if($_GET['preview'] == 'yes') {
							$file .= $flag ? $preend : '';
						} else {
							$_G['style']['prefile'] = $flag ? 1 : '';
						}
					}
					$indiy = true;
				} else {
					$file = $primaltpl ? $primaltpl : $oldfile;
				}
				$tplrefresh = $_G['config']['output']['tplrefresh'];
				if($indiy && ($tplrefresh ==1 || ($tplrefresh > 1 && !($_G['timestamp'] % $tplrefresh))) && filemtime($diypath.$file.'.htm') < filemtime(DISCUZ_ROOT.$_G['style']['tpldirectory'].'/'.($primaltpl ? $primaltpl : $oldfile).'.htm')) {
					if (!updatediytemplate($file, $_G['style']['tpldirectory'])) {
						unlink($diypath.$file.'.htm');
						$tpldir = '';
					}
				}

				if (!$gettplfile && empty($_G['style']['tplfile'])) {
					$_G['style']['tplfile'] = empty($clonefile) ? $curtplname : $oldfile.':'.$clonefile;
				}

				$_G['style']['prefile'] = !empty($_GET['preview']) && $_GET['preview'] == 'yes' ? '' : $_G['style']['prefile'];

			} else {
				//修改 $tpldir = './source/plugin/'.$templateid.'/template';
				$tpldir = './source/plugin/'.$templateid.'/extend/'.$zp_theme_path.'/template';
			}
		}

		$file .= !empty($_G['inajax']) && ($file == 'common/header' || $file == 'common/footer') ? '_ajax' : '';
		$tpldir = $tpldir ? $tpldir : (defined('TPLDIR') ? TPLDIR : '');
		$templateid = $templateid ? $templateid : (defined('TEMPLATEID') ? TEMPLATEID : '');
		$filebak = $file;

		if(defined('IN_MOBILE') && !defined('TPL_DEFAULT') && strpos($file, $_G['mobiletpl'][IN_MOBILE].'/') === false || (isset($_G['forcemobilemessage']) && $_G['forcemobilemessage'])) {
			if(IN_MOBILE == 2) {
				$oldfile .= !empty($_G['inajax']) && ($oldfile == 'common/header' || $oldfile == 'common/footer') ? '_ajax' : '';
			}
			$file = $_G['mobiletpl'][IN_MOBILE].'/'.$oldfile;
		}

		if(!$tpldir) {
			$tpldir = './template/default';
		}
		$tplfile = $tpldir.'/'.$file.'.htm';

		$file == 'common/header' && defined('CURMODULE') && CURMODULE && $file = 'common/header_'.$_G['basescript'].'_'.CURMODULE;

		if(defined('IN_MOBILE') && !defined('TPL_DEFAULT')) {
			if(strpos($tpldir, 'plugin')) {
				if(!file_exists(DISCUZ_ROOT.$tpldir.'/'.$file.'.htm') && !file_exists(DISCUZ_ROOT.$tpldir.'/'.$file.'.php')) {
					$url = $_SERVER['REQUEST_URI'].(strexists($_SERVER['REQUEST_URI'], '?') ? '&' : '?').'mobile=no';
					showmessage('mobile_template_no_found', '', array('url' => $url));
				} else {
					$mobiletplfile = $tpldir.'/'.$file.'.htm';
				}
			}
			!$mobiletplfile && $mobiletplfile = $file.'.htm';
			if(strpos($tpldir, 'plugin') && (file_exists(DISCUZ_ROOT.$mobiletplfile) || file_exists(substr(DISCUZ_ROOT.$mobiletplfile, 0, -4).'.php'))) {
				$tplfile = $mobiletplfile;
			} elseif(!file_exists(DISCUZ_ROOT.TPLDIR.'/'.$mobiletplfile) && !file_exists(substr(DISCUZ_ROOT.TPLDIR.'/'.$mobiletplfile, 0, -4).'.php')) {
				$mobiletplfile = './template/default/'.$mobiletplfile;
				if(!file_exists(DISCUZ_ROOT.$mobiletplfile) && !$_G['forcemobilemessage']) {
					$tplfile = str_replace($_G['mobiletpl'][IN_MOBILE].'/', '', $tplfile);
					$file = str_replace($_G['mobiletpl'][IN_MOBILE].'/', '', $file);
					define('TPL_DEFAULT', true);
				} else {
					$tplfile = $mobiletplfile;
				}
			} else {
				$tplfile = TPLDIR.'/'.$mobiletplfile;
			}
		}

		//修改 $cachefile = './data/template/'.(defined('STYLEID') ? STYLEID.'_' : '_').$templateid.'_'.str_replace('/', '_', $file).'.tpl.php';
		$cachefile = './data/template/'.(defined('STYLEID') ? STYLEID.'_' : '_').$templateid.'_extend_'.str_replace('/', '_', $file).'.tpl.php';
		if($templateid != 1 && !file_exists(DISCUZ_ROOT.$tplfile) && !file_exists(substr(DISCUZ_ROOT.$tplfile, 0, -4).'.php')
				&& !file_exists(DISCUZ_ROOT.($tplfile = $tpldir.$filebak.'.htm'))) {
			$tplfile = './template/default/'.$filebak.'.htm';
		}

		if($gettplfile) {
			return $tplfile;
		}
		checktplrefresh($tplfile, $tplfile, @filemtime(DISCUZ_ROOT.$cachefile), $templateid, $cachefile, $tpldir, $file);
		return DISCUZ_ROOT.$cachefile;
	}
}
?>