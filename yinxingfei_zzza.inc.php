<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
global $_G;
require DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/include/function.php';
//过滤POST
foreach($_POST as $key => $value){
	$_POST[$key] = daddslashes($value);
}
//过滤GET
foreach($_GET as $key => $value){
	$_GET[$key] = daddslashes($value);
}
$set = $_G['cache']['plugin']['yinxingfei_zzza'];
$modarray = array('hall','post','notice_close','today_extcredit');
$mod = !in_array($_GET['mod'], $modarray) ? 'hall' : $_GET['mod'];
require DISCUZ_ROOT.'./source/plugin/yinxingfei_zzza/module/'.$mod.'.php';
?>	 		