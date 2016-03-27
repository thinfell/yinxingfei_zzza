<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$dir = DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/extend/';
showtableheader();
showtagheader('tbody class="psetting"', '', true);
showtitle('未安装的扩展');
$extend = get_extend($dir);
$extend = array_filter($extend);
foreach($extend as $key => $value){
	if($value['name']){
		showtablerow('class="hover hover yincang_'.$value['identifier'].'" style="overflow: hidden;"', array(), array('
					<div style="float:left;width:20%;padding-bottom: 10px;">
						<p><span class="bold">'.$value['name'].'</span></p>
						<p><span class="sml"></span></p>
					</div>
					<div style="float:left;width:80%;padding-bottom: 10px;">
						<p>说明：'.$value['description'].'</p>
						<p style="overflow: hidden;">
							<div style="float:left;width:50%;">版本：'.$value['version'].'<em style="color:#ddd;padding: 0px 5px;">|</em>作者：'.$value['copyright'].'<em style="color:#ddd;padding: 0px 5px;">|</em>类型：'.$value['type'].'<em style="color:#ddd;padding: 0px 5px;">|</em>文件夹：'.$value['identifier'].'</div>
							<div style="text-align: right;float:right;width:50%;">
								<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=anzhuang&file='.$value['file'].'">安装</a>
							</div>
						</p>
					</div>
					'));
	}
}
showtagfooter('tbody');
showtablefooter();
function get_extend($dir){
	$tree = array();
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file = readdir($dh))!= false){
				if( $file != "." && $file != ".." ){
					$extend_lang = @include DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/extend/'.$file.'/extend_lang/'.currentlang().'.php';
					$tree[$file]['file'] = $file;
					$tree[$file]['name'] = $extend_lang['name'];
					$tree[$file]['type'] = $extend_lang['type'];
					$tree[$file]['identifier'] = $extend_lang['identifier'];
					$tree[$file]['version'] = $extend_lang['version'];
					$tree[$file]['copyright'] = $extend_lang['copyright'];
					$tree[$file]['description'] = $extend_lang['description'];
					$tree[$file]['menu'] = $extend_lang['menu'];
				}
			}
			closedir($dh);
		}
	}
	return $tree;
}
?>