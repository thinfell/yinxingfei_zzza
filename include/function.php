<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function getusername($uid){
	return DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid = '".$uid."'");
}
function get_rand($proArr) {   
	$result = '';      
	$proSum = array_sum($proArr); 
	foreach ($proArr as $key => $proCur) {   
		$randNum = mt_rand(1, $proSum);   
		if ($randNum <= $proCur) {   
			$result = $key;   
			break;   
		} else {   
			$proSum -= $proCur;   
		}         
	}   
	unset ($proArr);    
	return $result;   
}
function get_zp_theme_identifier(){
	$zp_theme_identifier = DB::result_first("SELECT identifier FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE type = 'zp_theme' AND available = '1'");
	return $zp_theme_identifier;
}
function yinxingfei_zzza_extend_template($file, $templateid = 0, $tpldir = '', $gettplfile = 0, $primaltpl='') {
	//在原来官方文件基础上修改适配扩展模板
	$zp_theme_path = get_zp_theme_identifier();
	
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
function getSetZzza($type){
	return DB::result_first("SELECT val FROM ".DB::table('yinxingfei_zzza_set')." WHERE type = '".$type."'");
}
function getGrade($total_counts){
	$return = array();
	$return = DB::fetch_first("SELECT * FROM ".DB::table('yinxingfei_zzza_grade')." WHERE min <= '".$total_counts."' AND max > '".$total_counts."'");
	return $return;
}
function makeGradeIconCss(){
	//生成函数
	foreach(array(1,2,3,4,5,6,7,8,9,10,11,12,13,14) as $v){
		foreach(array(1,2,3,4,5,6,7,8,9) as $va){
			$xpx = -($va-1)*30-13;
			$ypx = -($v-1)*30-13;
			echo '
			.zzza_grade_'.$va.'_'.$v.' {
				background-position:'.$xpx.'px '.$ypx.'px;
			}</br>	
			';
		}
	}
}
?>