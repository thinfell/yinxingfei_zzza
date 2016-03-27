<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
if(submitcheck('backup', 1)){
	if(preg_match('/[^A-Za-z0-9_]/', $_GET['filename'])){
		cpmsg('文件名称含有非法字符！','','error');
	}
	$file = DISCUZ_ROOT."./data/zzza_backup/[".ZZZA_VERSION."]-[".date("Ymd")."]-".$_GET['filename'].".vbak";
	@touch($file);
	if(!is_writeable($file)){
		cpmsg('备份目录文件权限不可写，请检查！','','error');
	}
	$out_arr = zhdata($_POST['db_sel']);
	if($_POST['shezhi']){
		//设置备份
		$query = DB::query('SELECT variable,value,pluginid FROM '.DB::table('common_pluginvar').' WHERE pluginid = \''.$pluginid.'\'');
		while($data = DB::fetch($query)){
			$out_arr['shezhi'][] = $data;
		}
	}
	$output = serialize($out_arr);
	file_put_contents($file, $output);
	cpmsg('数据备份成功', "action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=extend&file=sjbf", 'succeed');
}elseif(submitcheck('restore', 1)){
	$file = DISCUZ_ROOT."./data/zzza_backup/{$_GET[filename]}";
	if(!file_exists($file)){
		cpmsg('备份文件不存在！','','error');
	}
	$filer = explode("-",$_GET['filename']);
	$banben = trim($filer[count($filer)-3]);
	if($banben != trim('['.ZZZA_VERSION.']')){
		cpmsg('备份文件版本与当前使用版本不匹配，无法进行恢复','','error');
	}
	$data_str = file_get_contents($file);
	$data = unserialize($data_str);
	
	foreach($data as $key => $value){
		if($key != 'shezhi'){
			hfdata($data[$key],$key);
		}else{
			//恢复插件设置
			hfshezhi($data[$key],$key);
		}
	}
	require_once libfile('function/cache');
	updatecache('yinxingfei_zzza');
	cpmsg('数据恢复成功', "action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=extend&file=sjbf", 'succeed');
}
//数据备份
$zzza_db = @include DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/zzza_db.php';
showformheader("plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=extend&file=sjbf");
showtableheader('数据备份');
$selected .= '<li style="float: none;height: 25px;" class="checked"><input class="checkbox" type="checkbox" name="shezhi" value="1" checked>每日摇摇乐插件设置参数</li>';

foreach($zzza_db as $key => $value){
	$selected .= '<li style="float: none;height: 25px;" class="checked"><input class="checkbox" type="checkbox" name="db_sel[]" value="'.$key.'" checked>数据库表_'.$key.'</li>';
}
showsetting('选择需要备份的数据', '', '', '<ul onmouseover="altStyle(this);">'.$selected.'</ul>');
showsetting('备份文件名称', 'filename', random(10), 'text', '', '', '储存在 /data/zzza_backup/ 下的文件名');
showsubmit('backup', '开始备份');
showtablefooter();
showformfooter();
//数据恢复
showtableheader('数据恢复');
if(!is_dir(DISCUZ_ROOT.'./data/zzza_backup/')) {
	@mkdir(DISCUZ_ROOT.'./data/zzza_backup/', 0777);
	@touch(DISCUZ_ROOT."./data/zzza_backup/index.htm");
}
$backup_dir = @dir(DISCUZ_ROOT.'./data/zzza_backup/');
$flag = false;
while(false !== ($entry = $backup_dir->read())) {
	$file = pathinfo($entry);
	if($file['extension'] == 'vbak' && $file['basename']) {
		showtablerow('', '', array(
			'历史备份文件：'.$file['basename'],
			'备份时间：'.dgmdate(filemtime(DISCUZ_ROOT."./data/zzza_backup/".$file['basename']), 'u'),
			'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=extend&file=sjbf&filename='.$file['basename'].'&restore=yes&formhash='.FORMHASH.'">开始恢复</a>',
		));
		$flag = true;
	}
}
if(!$flag){
	showtablerow('', '', array('<font color="red">系统检查，您还没有备份文件！</font>'));
}
showtablefooter();

function zhdata($zzza_db){
	$out_arr = array();
	foreach($zzza_db as $value){
		$query = DB::query('SELECT * FROM '.DB::table($value));
		while($data = DB::fetch($query)){
			$out_arr[$value][] = $data;
		}
	}
	return $out_arr;
}
function hfdata($zzza_db,$zzza_tdb){
	DB::query('TRUNCATE TABLE '.DB::table($zzza_tdb));
	foreach ($zzza_db as $line){
		DB::insert($zzza_tdb, $line);
	}
}
function hfshezhi($data){
	$pluginid = DB::result_first("SELECT pluginid FROM ".DB::table('common_plugin')." WHERE identifier = 'yinxingfei_zzza'");
	foreach ($data as $line){
		DB::query("UPDATE ".DB::table('common_pluginvar')." SET value = '".$line['value']."' WHERE variable= '".$line['variable']."' AND pluginid = '".$pluginid."'", 'UNBUFFERED');
	}
}
?>